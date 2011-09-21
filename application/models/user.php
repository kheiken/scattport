<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Eike Foken <kontakt@eikefoken.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * User model.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class User extends CI_Model {

	/**
	 * Should the salt be stored in the database?
	 *
	 * @var boolean
	 */
	private $storeSalt;

	/**
	 * Contains the salt length.
	 *
	 * @var integer
	 */
	private $saltLength;

	/**
	 * Contains the forgotten password key.
	 *
	 * @var string
	 */
	public $forgottenPasswordCode;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->config('auth', true);
		$this->load->helper('cookie');

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
		return substr(sha1(uniqid(rand(), true)), 0, $this->saltLength);
	}

	/**
	 * Checks entered usernames.
	 *
	 * @param string $username
	 * @return boolean
	 */
	private function checkUsername($username = '') {
		if (empty($username)) {
			return false;
		}
		return $this->db->where('username', $username)->count_all_results('users') > 0;
	}

	/**
	 * Checks entered emails.
	 *
	 * @param string $email
	 * @return boolean
	 */
	private function checkEmail($email = '') {
		if (empty($email)) {
			return false;
		}
		return $this->db->where('email', $email)->count_all_results('users') > 0;
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
			// reset the remember code so all remembered instances have to re-login
			$data = array('password' => $new, 'remember_code' => '');

			$this->db->update('users', $data, array('username' => $username));

			return $this->db->affected_rows() == 1;
		}
		return false;
	}

	/**
	 * Inserts a forgotten password key.
	 *
	 * @param string $email
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
	 * Completes the forgotten password procedure.
	 *
	 * @param string $code
	 * @param boolean $salt
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
				'forgotten_password_code' => null,
			);

			$this->db->update('users', $data, array('forgotten_password_code' => $code));

			return $password;
		}
		return false;
	}

	/**
	 * Gets a users profile.
	 *
	 * @param string $username
	 * @param boolean $isCode
	 * @return mixed
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

		return $query->num_rows() > 0 ? $query->row() : false;
	}

	/**
	 * Registers a new user.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $email
	 * @param array $additionalData
	 * @param string $groupName
	 * @return mixed Returns the ID of the new user, or FALSE if the
	 *                registration was unsuccessful.
	 */
	public function register($username, $password, $email, $additionalData = array(), $groupName = '') {
		if ($this->checkUsername($username)) {
			return false;
		}

		// if a groupID was passed, use it
		if (isset($additionalData['group_id'])) {
			$groupID = $additionalData['group_id'];
			unset($additionalData['group_id']);
		} else { // otherwise get default groupID
			$groupName = ($groupName == '') ? 'users' : $groupName;
			$groupID = $this->db->select('id')->where('name', $groupName)->get('groups')->row()->id;
		}

		$salt = $this->storeSalt ? $this->salt() : false;
		$password = $this->hashPassword($password, $salt);

		// users table
		$data = array(
			'username' => $username,
			'password' => $password,
			'email' => $email,
			'group_id' => $groupID,
			'last_login' => now(),
		);

		do { // generate unique hash
			$data['id'] = random_string('sha1', 16);
		} while ($this->db->where('id', $data['id'])->count_all_results('users') > 0);

		if ($this->storeSalt) {
			$data['salt'] = $salt;
		}

		$this->db->insert('users', array_merge($data, $additionalData));

		return $this->db->affected_rows() > 0 ? $data['id'] : false;
	}

	/**
	 * Validates the given password against the username.
	 *
	 * @param string $username
	 * @param string $password
	 * @param boolean $remember
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
	 * Gets users.
	 *
	 * @param mixed $group
	 * @param integer $limit
	 * @param integer $offset
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
	 * Gets all users.
	 *
	 * @return array
	 */
	public function getAll() {
		return $this->get()->result_array();
	}

	/**
	 * Gets a specified number of new users.
	 *
	 * @param integer $limit
	 * @return array
	 */
	public function getNewest($limit = 10) {
		$this->db->order_by('users.created_on DESC')->limit($limit);
		return $this->get()->result_array();
	}

	/**
	 * Gets a user by ID.
	 *
	 * @param string $id
	 * @return array
	 */
	public function getById($id = false) {
		if (empty($id)) {
			return false;
		}

		$this->db->where('users.id', $id)->limit(1);

		return $this->get()->row_array();
	}

	/**
	 * Gets a user by ID.
	 *
	 * @deprecated 21-09-2011
	 * @param string $id
	 * @return array
	 */
	public function getUserByID($id = false) {
		return $this->getById($id);
	}

	/**
	 * Gets a user by email.
	 *
	 * @param string $email
	 * @return array
	 */
	public function getByEmail($email) {
		$this->db->where('users.email', $email)->limit(1);
		return $this->get()->row_array();
	}

	/**
	 * Gets a user by username.
	 *
	 * @param string $username
	 * @return array
	 */
	public function getByUsername($username) {
		$this->db->where('users.username', $username)->limit(1);
		return $this->get()->row_array();
	}

	/**
	 * Gets a users settings.
	 *
	 * @param string $userId
	 * @return array
	 */
	public function getSettings($userId) {
		$query = $this->db->get_where('users_settings', array('user_id' => $userId));
		return $query->row_array();
	}

	/**
	 * Returns the number of users.
	 *
	 * @param mixed $group
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
	 * Updates a user.
	 *
	 * @param string $id
	 * @param array $data
	 * @return boolean Returns TRUE if the update was successful.
	 */
	public function update($id, $data) {
		$user = $this->getById($id);

		if (array_key_exists('username', $data) && $this->checkUsername($data['username']) && $user['username'] !== $data['username']) {
			$this->messages->add(_('The entered username is already in use.'), 'error');
			return false;
		}

		if (array_key_exists('username', $data) || array_key_exists('password', $data) || array_key_exists('email', $data)) {
			if (array_key_exists('password', $data)) {
				$data['password'] = $this->hashPassword($data['password'], $user['salt']);
			}

			$this->db->update('users', $data, array('id' => $id));
		}

		return $this->db->affected_rows() > 0;
	}

	/**
	 *
	 */
	public function updateSettings($data, $userId) {
		foreach ($data as $key => $value) {
			$data[$key] = $this->db->escape($value);
		}

		$query = $this->db->query("REPLACE INTO `users_settings` (`user_id`, "
				. implode(", ", array_keys($data)) . ") VALUES ('" . $userId . "', "
				. implode(", ", array_values($data)) . ")");

		return $this->db->affected_rows() > 0;
	}

	/**
	 * Updates a users last login time.
	 *
	 * @param string $id
	 * @return boolean Returns TRUE if the update was successful.
	 */
	public function updateLastLogin($id) {
		$this->db->update('users', array('last_login' => now()), array('id' => $id));
		return $this->db->affected_rows() == 1;
	}

	/**
	 * Deletes a specified user.
	 *
	 * @param string $id
	 * @return boolean Returns TRUE if the deletion was successful.
	 */
	public function delete($id) {
		$this->db->delete('users', array('id' => $id));
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Logs a remembed user in.
	 *
	 * @return boolean
	 */
	public function loginRememberedUser() {
		if (!get_cookie('username') || !get_cookie('remember_code') || !$this->checkUsername(get_cookie('username'))) {
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
	 * Remembers a user.
	 *
	 * @param string $id
	 * @return boolean
	 */
	private function rememberUser($id) {
		if (!$id) {
			return false;
		}

		$user = $this->getById($id);

		$salt = sha1($user['password']);

		$this->db->update('users', array('remember_code' => $salt), array('id' => $id));

		if ($this->db->affected_rows() > -1) {
			set_cookie(array(
				'name' => 'username',
				'value' => $user['username'],
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
