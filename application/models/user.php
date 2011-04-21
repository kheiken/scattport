<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * User model.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class User extends CI_Model {

    /**
     * Contains the forgotten password key.
     *
     * @var string
     */
    public $forgottenPasswordCode;

    public function __construct() {
        parent::__construct();
        $this->load->config('auth', true);
        $this->load->helper('cookie');
        $this->load->helper('date');

        $this->storeSalt = $this->config->item('store_salt', 'auth');
        $this->saltLength = $this->config->item('salt_length', 'auth');
    }

    /**
     * Hashes the password to be stored in the database.
     *
     * @param string $password
     * @param boolean $salt
     * @return boolean|string
     */
    public function hashPassword($password, $salt = false) {
        if (empty($password)) {
            return false;
        }

        if ($this->storeSalt && $salt) {
            return sha1($password . $salt);
        } else {
            $salt = $this->salt();
            return  $salt . substr(sha1($salt . $password), 0, -$this->saltLength);
        }
    }

    /**
     * Takes a password and validates it against an entry in the users table.
     *
     * @param string $username
     * @param string $password
     * @return boolean|string
     */
    public function hashPasswordForDB($username, $password) {
        if (empty($username) || empty($password)) {
            return false;
        }

        $query = $this->db->select('password, salt')
                ->where('username', $username)->limit(1)->get('users');

        $result = $query->row();

        if ($query->num_rows() !== 1) {
            return false;
        }

        if ($this->storeSalt) {
            return sha1($password . $result->salt);
        } else {
            $salt = substr($result->password, 0, $this->saltLength);
            return $salt . substr(sha1($salt . $password), 0, -$this->saltLength);
        }
    }

    /**
     * Generates a random salt value.
     *
     * @return string
     */
    private function salt() {
        return substr(md5(uniqid(rand(), true)), 0, $this->saltLength);
    }

    /**
     * Changes the password of the given user.
     *
     * @param string $username
     * @param string $old
     * @param string $new
     * @return boolean
     */
    public function changePassword($username, $old, $new) {
        $query = $this->db->select('password, salt')
                ->where('username', $username)->limit(1)->get('users');

        $result = $query->row();

        $dbPassword = $result->password;
        $old = $this->hashPasswordForDB($username, $old);
        $new = $this->hashPassword($new, $result->salt);

        if ($dbPassword === $old) {
            // store the new password and reset the remember code so all remembered instances have to re-login
            $data = array('password' => $new, 'remember_code' => '');

            $this->db->update('users', $data, array('username' => $username));

            return $this->db->affected_rows() == 1;
        }
        return false;
    }

    /**
     * Checks entered usernames.
     *
     * @return boolean
     */
    public function checkUsername($username = '') {
        if (empty($username)) {
            return false;
        }
        return $this->db->where('username', $username)->count_all_results('users') > 0;
    }

    /**
     * Checks entered emails.
     *
     * @return boolean
     */
    public function checkEmail($email = '') {
        if (empty($email)) {
            return false;
        }
        return $this->db->where('email', $email)->count_all_results('users') > 0;
    }

    /**
     * Inserts a forgotten password key.
     *
     * @return boolean
     */
    public function forgottenPassword($email = '') {
        if (empty($email)) {
            return false;
        }

        $key = $this->hashPassword(microtime() . $email);

        $this->forgottenPasswordCode = $key;

        $this->db->update('users', array('forgotten_password_code' => $key), array('email' => $email));

        return $this->db->affected_rows() == 1;
    }

    /**
     * Forgotten Password Complete
     *
     * @return string
     */
    public function forgottenPasswordComplete($code, $salt = false) {
        if (empty($code)) {
            return false;
        }

        $this->db->where('forgotten_password_code', $code);

        if ($this->db->count_all_results('users') > 0) {
            $password = $this->salt();

            $data = array(
                'password' => $this->hashPassword($password, $salt),
                'forgotten_password_code' => null
            );

            $this->db->update('users', $data, array('forgotten_password_code' => $code));

            return $password;
        }
        return false;
    }

    /**
     * profile
     *
     * @return boolean|object
     */
    public function profile($username = '', $isCode = false) {
        if (empty($username)) {
            return false;
        }

        $this->db->select('users.*, groups.name AS `group`, groups.description AS `group_description`');
        $this->db->join('groups', 'users.group_id = groups.id', 'left');

        if ($isCode) {
            $this->db->where('users.forgotten_password_code', $username);
        } else {
            $this->db->where('users.username', $username);
        }

        $query = $this->db->limit(1)->get('users');

        return $query->num_rows > 0 ? $query->row() : false;
    }

    /**
     * register
     *
     * @return boolean
     */
    public function register($username, $password, $email, $additionalData = false, $groupName = false) {
        if ($this->username_check($username)) {
            $this->access->setError('account_creation_duplicate_username');
            return false;
        }

        // if a groupID was passed, use it
        if (isset($additional_data['group_id'])) {
            $groupID = $additional_data['group_id'];
            unset($additional_data['group_id']);
        } else { // otherwise get default groupID
            $groupName = !$groupName ? 'users' : $groupName;
            $groupID = $this->db->select('id')->where('name', $groupName)->get('groups')->row()->id;
        }

        // IP Address
        $ipAddress = $this->input->ip_address();
        $salt = $this->storeSalt ? $this->salt() : false;
        $password = $this->hashPassword($password, $salt);

        // Users table.
        $data = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'group_id' => $groupID,
            'last_login' => now(),
        );

        if ($this->storeSalt) {
            $data['salt'] = $salt;
        }

        $this->db->insert('users', $data);
        $id = $this->db->insert_id();

        return $this->db->affected_rows() > 0 ? $id : false;
    }

    /**
     * login
     *
     * @return boolean
     */
    public function login($username, $password, $remember = false) {
        if (empty($username) || empty($password) || !$this->checkUsername($username)) {
            return false;
        }

        $query = $this->db->select('id, username, password, group_id')
                ->where('username', $username)->limit(1)->get('users');

        $result = $query->row();

        if ($query->num_rows() == 1) {
            $password = $this->hashPasswordForDB($username, $password);

            if ($result->password === $password) {
                $this->updateLastLogin($result->id);

                $group = $this->db->select('name')->where('id', $result->group_id)->get('groups')->row();

                $session_data = array(
                    'username' => $result->username,
					'user_id' => $result->id,
					'group_id' => $result->group_id,
					'group' => $group->name
                );

                $this->session->set_userdata($session_data);

                if ($remember && $this->config->item('remember_users', 'auth')) {
                    $this->rememberUser($result->id);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * get
     *
     * @return object
     */
    public function get($group = false, $limit = null, $offset = null) {
        $this->db->select('users.*, groups.name AS `group`, groups.description AS `group_description`');
        $this->db->join('groups', 'users.group_id = groups.id', 'left');

        if (is_string($group)) {
            $this->db->where('groups.name', $group);
        } else if (is_array($group)) {
            $this->db->where_in('groups.name', $group);
        }

        if (isset($limit) && isset($offset)) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get('users');
    }

    /**
     * Returns the number of users.
     *
     * @return integer The number of users
     */
    public function count($group = false) {
        if (is_string($group)) {
            $this->db->where('groups.name', $group);
        } else if (is_array($group)) {
            $this->db->where_in('groups.name', $group);
        }
        return $this->db->from('users')->count_all_results();
    }

    /**
     * getUserByID
     *
     * @return object
     */
    public function getUserByID($id = false) {
        // if no ID was passed use the current users ID
        if (empty($id)) {
            $id = $this->session->userdata('user_id');
        }

        $this->db->where('users.id', $id);
        $this->db->limit(1);

        return $this->get();
    }

    /**
     * getUserByEmail
     *
     * @return object
     */
    public function getUserByEmail($email) {
        $this->db->where('users.email', $email);
        $this->db->limit(1);
        return $this->get();
    }

    /**
     * getUserByUsername
     *
     * @return object
     */
    public function getUserByUsername($username) {
        $this->db->where('users.username', $username);
        $this->db->limit(1);
        return $this->get();
    }

    /**
     * getNewestUsers
     *
     * @return object
     */
    public function getNewestUsers($limit = 10) {
        $this->db->order_by('users.created_on DESC');
        $this->db->limit($limit);
        return $this->get();
    }

    /**
     * getUsersGroup
     *
     * @return object
     */
    public function getUsersGroup($id = false) {
        // if no ID was passed use the current users ID
        $id || $id = $this->session->userdata('user_id');

        $user = $this->db->select('group_id')->where('id', $id)->get('users')
                ->row();

        return $this->db->select('name, description')
                ->where('id', $user->group_id)->get('groups')->row();
    }

    /**
     * update
     *
     * @return boolean
     */
    public function update($id, $data) {
        $user = $this->get_user($id)->row();

        $this->db->trans_begin();

        if (array_key_exists('username', $data) && $this->username_check($data['username']) && $user->username !== $data['username']) {
            $this->db->trans_rollback();
            $this->access->setError('account_creation_duplicate_username');
            return false;
        }

        if (array_key_exists('username', $data) || array_key_exists('password', $data) || array_key_exists('email', $data)) {
            if (array_key_exists('password', $data)) {
                $data['password'] = $this->hashPassword($data['password'], $user->salt);
            }

            $this->db->update('users', $data, array('id' => $id));
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    /**
     * delete
     *
     * @return boolean
     */
    public function delete($id) {
        $this->db->trans_begin();

        $this->db->delete('users', array('id' => $id));

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    /**
     * updateLastLogin
     *
     * @return boolean
     */
    public function updateLastLogin($id) {
        $this->db->update('users', array('last_login' => now()), array('id' => $id));
        return $this->db->affected_rows() == 1;
    }

    /**
     * loginRemembedUser
     *
     * @return boolean
     */
    public function loginRememberedUser() {
        if (!get_cookie('username') || !get_cookie('remember_code') || !$this->username_check(get_cookie('username'))) {
            return false;
        }

        $query = $this->db->select('id, username, group_id')
                ->where('username', get_cookie('username'))
                ->where('remember_code', get_cookie('remember_code'))->limit(1)
                ->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();

            $this->updateLastLogin($user->id);

            $group = $this->db->select('name')->where('id', $user->group_id)
                    ->get('groups')->row();

            $session_data = array(
                'username' => $user->username,
                'user_id' => $user->id,
                'group_id' => $user->group_id,
                'group' => $group->name
            );

            $this->session->set_userdata($session_data);

            // extend the users cookies if the option is enabled
            if ($this->config->item('user_extend_on_login', 'auth')) {
                $this->rememberUser($user->id);
            }
            return true;
        }
        return false;
    }

    /**
     * rememberUser
     *
     * @return boolean
     */
    private function rememberUser($id) {
        if (!$id) {
            return false;
        }

        $user = $this->getUserByID($id)->row();

        $salt = sha1($user->password);

        $this->db->update('users', array('remember_code' => $salt), array('id' => $id));

        if ($this->db->affected_rows() > -1) {
            set_cookie(array(
                'name' => 'username',
                'value' => $user->username,
                'expire' => $this->config->item('user_expire', 'auth'),
            ));
            set_cookie(array(
                'name' => 'remember_code',
                'value' => $salt,
                'expire' => $this->config->item('user_expire', 'auth'),
            ));
            return true;
        }
        return false;
    }

}

/* End of file user.php */
/* Location: ./application/models/user.php */