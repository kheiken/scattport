<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Simple auth system.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Access {

    /**
     * Contains the CI instance.
     */
    protected $ci;

    /**
     * Contains occured messages (using the language file).
     *
     * @var string
     */
    protected $messages = array();

    /**
     * Contains occured errors (using the language file).
     *
     * @var string
     */
    protected $errors = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->config('auth', true);
        $this->ci->load->library('email');
        $this->ci->lang->load('auth');
        $this->ci->load->model('user');
        $this->ci->load->model('group');
        $this->ci->load->helper('cookie');

        // auto-login the user if they are remembered
        if (!$this->loggedIn() && get_cookie('username') && get_cookie('remember_code')) {
            $this->ci->access = $this;
            $this->ci->user->loginRememberedUser();
        }
    }

    /**
     * Changes a users password.
     *
     * @return boolean
     */
    public function changePassword($username, $old, $new) {
        if ($this->ci->user->changePassword($username, $old, $new)) {
            $this->setMessage('password_change_successful');
            return true;
        }

        $this->setError('password_change_unsuccessful');
        return false;
    }

    /**
     * forgotten password feature
     *
     * @return void
     */
    public function forgottenPassword($username) {
        if ($this->ci->user->forgottenPassword($username)) {
            // get user information
            $user = $this->getUserByUsername($username);

            $data = array(
                'username' => $user['username'],
                'forgotten_password_code' => $user['forgotten_password_code']
            );

            $message = $this->ci->load->view($this->ci->config->item('email_templates', 'auth') . $this->ci->config->item('email_forgot_password', 'auth'), $data, true);
            $this->ci->email->clear();
            $config['mailtype'] = $this->ci->config->item('email_type', 'auth');
            $this->ci->email->initialize($config);
            $this->ci->email->set_newline("\r\n");
            $this->ci->email->from($this->ci->config->item('admin_email', 'auth'), 'Scattport');
            $this->ci->email->to($user['email']);
            $this->ci->email->subject('Scattport - Forgotten Password Verification');
            $this->ci->email->message($message);

            if ($this->ci->email->send()) {
                $this->setMessage('forgot_password_successful');
                return true;
            } else {
                $this->setError('forgot_password_unsuccessful');
                return false;
            }
        } else {
            $this->setError('forgot_password_unsuccessful');
            return false;
        }
    }

    /**
     * forgotten_password_complete
     *
     * @return void
     */
    public function forgottenPasswordComplete($code) {
        $profile  = $this->ci->user->profile($code, true); // pass the code to profile

        if (!is_object($profile)) {
            $this->setError('password_change_unsuccessful');
            return false;
        }

        $new_password = $this->ci->user->forgottenPasswordComplete($code, $profile->salt);

        if ($new_password) {
            $data = array(
                'username' => $profile->username,
                'new_password' => $new_password
            );

            $message = $this->ci->load->view($this->ci->config->item('email_templates', 'ion_auth').$this->ci->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);

            $this->ci->email->clear();
            $config['mailtype'] = $this->ci->config->item('email_type', 'ion_auth');
            $this->ci->email->initialize($config);
            $this->ci->email->set_newline("\r\n");
            $this->ci->email->from($this->ci->config->item('admin_email', 'ion_auth'), $this->ci->config->item('site_title', 'ion_auth'));
            $this->ci->email->to($profile->email);
            $this->ci->email->subject($this->ci->config->item('site_title', 'ion_auth') . ' - New Password');
            $this->ci->email->message($message);

            if ($this->ci->email->send()) {
                $this->setMessage('password_change_successful');
                return true;
            } else {
                $this->setError('password_change_unsuccessful');
                return false;
            }
        }

        $this->setError('password_change_unsuccessful');
        return false;
    }

    /**
     * Registers a new user.
     *
     * @return integer|boolean
     */
    public function register($username, $password, $email, $additionalData, $groupName = false) {
        $id = $this->ci->user->register($username, $password, $email, $additionalData, $groupName);

        if ($id !== false) {
            $this->setMessage('account_creation_successful');
            return $id;
        } else {
            $this->setError('account_creation_unsuccessful');
            return false;
        }
    }

    /**
     * Logs the user in.
     *
     * @return boolean
     */
    public function login($username, $password, $remember = false) {
        if ($this->ci->user->login($username, $password, $remember)) {
            $this->setMessage('login_successful');
            return true;
        } else {
            $this->setError('login_unsuccessful');
            return false;
        }
    }

    /**
     * Logs the user out.
     *
     * @return boolean
     */
    public function logout() {
        $this->ci->session->unset_userdata('username');
        $this->ci->session->unset_userdata('group');
        $this->ci->session->unset_userdata('user_id');

        // delete the remember cookies if they exist
        if (get_cookie('username')) {
            delete_cookie('username');
        } if (get_cookie('remember_code')) {
            delete_cookie('remember_code');
        }

        $this->ci->session->sess_destroy();

        $this->setMessage('logout_successful');
        return true;
    }

    /**
     * Checks if the user is logged in.
     *
     * @return boolean
     */
    public function loggedIn() {
        return (boolean) $this->ci->session->userdata('username');
    }

    /**
     * Checks if the user is an admin.
     *
     * @return boolean
     */
    public function isAdmin() {
        $adminGroup = 'admins';
        $userGroup  = $this->ci->session->userdata('group');
        return $userGroup == $adminGroup;
    }

    /**
     * Checks if the current user is assigned to the specified group.
     *
     * @return boolean
     */
    public function isGroup($checkGroup)  {
        $userGroup = $this->ci->session->userdata('group');

        if (is_array($checkGroup)) {
            return in_array($userGroup, $checkGroup);
        }
        return $userGroup == $checkGroup;
    }

    /**
     * Gets the profile of the current user.
     *
     * @return array
     */
    public function profile() {
        return $this->ci->user->profile($this->ci->session->userdata('username'));
    }

    /**
     * Updates a specified user.
     *
     * @return boolean
     */
    public function updateUser($id, $data) {
        if ($this->ci->user->update($id, $data)) {
            $this->setMessage('update_successful');
            return true;
        }

        $this->setError('update_unsuccessful');
        return false;
    }

    /**
     * Deletes a specified user.
     *
     * @return boolean
     */
    public function deleteUser($id) {
        if ($this->ci->user->delete($id)) {
            $this->setMessage('delete_successful');
            return true;
        }

        $this->setError('delete_unsuccessful');
        return false;
    }

    /**
     * Sets a message.
     *
     * @return string
     */
    public function setMessage($message) {
        $this->messages[] = $message;
        return $message;
    }

    /**
     * Gets all messages.
     *
     * @return void
     */
    public function messages() {
        $output = '';
        foreach ($this->messages as $message) {
            $output .= lang($message) . '<br />';
        }

        return $output;
    }

    /**
     * Sets an error message.
     *
     * @return void
     */
    public function setError($error) {
        $this->errors[] = $error;
        return $error;
    }

    /**
     * Gets all error messages.
     *
     * @return void
     */
    public function errors() {
        $output = '';
        foreach ($this->errors as $error) {
            $output .= lang($error) . '<br />';
        }

        return $output;
    }

}

/* End of file Access.php */
/* Location: ./application/libraries/Access.php */
