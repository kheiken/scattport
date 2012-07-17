<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Karsten Heiken, Eike Foken
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
 * Simple auth system.
 *
 * @package ScattPort
 * @subpackage Libraries
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Access {

	/**
	 * Contains the CI instance.
	 *
	 * @var object
	 */
	private $CI;

	/**
	 * Contains cached stuff.
	 *
	 * @var array
	 */
	private $cache = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->config('auth', true);
		$this->CI->load->library('email');
		$this->CI->load->model('user');
		$this->CI->load->model('group');
		$this->CI->load->helper('cookie');

		// auto-login the user if they are remembered
		if (!$this->isloggedIn() && get_cookie('username') && get_cookie('remember_code')) {
			$this->CI->access = $this;
			$this->CI->user->loginRememberedUser();
		}

		log_message('debug', "Access Class Initialized");
	}

	/**
	 * Changes a users password.
	 *
	 * @param string $username
	 * @param string $old
	 * @param string $new
	 * @return boolean
	 */
	public function changePassword($username, $old, $new) {
		if ($this->CI->user->changePassword($username, $old, $new)) {
			$this->CI->messages->add(_('Password successfully changed'), 'success');
			return true;
		}

		$this->CI->messages->add(_('Unable to change password'), 'error');
		return false;
	}

	/**
	 * forgotten password feature
	 *
	 * @param string $email
	 * @return void
	 */
	public function forgottenPassword($email) {
		if ($this->CI->user->forgottenPassword($email)) {
			// get user information
			$user = $this->CI->user->getByEmail($email);

			$data = array(
                'username' => $user['username'],
                'forgotten_password_code' => $user['forgotten_password_code'],
			);

			$message = $this->CI->load->view('auth/email/forgot_password', $data, true);
			$this->CI->email->clear();
			$config['mailtype'] = $this->CI->config->item('email_type', 'auth');
			$this->CI->email->initialize($config);
			$this->CI->email->set_newline("\r\n");
			$this->CI->email->from($this->CI->config->item('admin_email', 'auth'), 'Scattport');
			$this->CI->email->to($user['email']);
			$this->CI->email->subject('ScattPort - Forgotten Password Verification');
			$this->CI->email->message($message);

			if ($this->CI->email->send()) {
				$this->CI->messages->add(_('Password reset email sent'), 'success');
				return true;
			} else {
				$this->CI->messages->add(_('Unable to send password reset email'), 'error');
				return false;
			}
		} else {
			$this->CI->messages->add(_('This email address is not registered'), 'error');
			return false;
		}
	}

	/**
	 * forgotten_password_complete
	 *
	 * @param string $code
	 * @return void
	 */
	public function forgottenPasswordComplete($code) {
		$profile  = $this->CI->user->profile($code, true); // pass the code to profile

		if (!is_object($profile)) {
			$this->CI->messages->add(_('Unable to change password'), 'error');
			return false;
		}

		$new_password = $this->CI->user->forgottenPasswordComplete($code, $profile->salt);

		if ($new_password) {
			$data = array(
                'username' => $profile->username,
                'new_password' => $new_password
			);

			$message = $this->CI->load->view('auth/email/forgot_password_complete', $data, true);

			$this->CI->email->clear();
			$config['mailtype'] = $this->CI->config->item('email_type', 'auth');
			$this->CI->email->initialize($config);
			$this->CI->email->set_newline("\r\n");
			$this->CI->email->from($this->CI->config->item('admin_email', 'auth'), $this->CI->config->item('site_title', 'auth'));
			$this->CI->email->to($profile->email);
			$this->CI->email->subject('ScattPort - New Password');
			$this->CI->email->message($message);

			if ($this->CI->email->send()) {
				$this->CI->messages->add(_('Password successfully changed'), 'success');
				return true;
			} else {
				$this->CI->messages->add(_('Unable to change password'), 'error');
				return false;
			}
		}

		$this->CI->messages->add(_('Unable to change password'), 'error');
		return false;
	}

	/**
	 * Logs the user in.
	 *
	 * @param string $username
	 * @param string $password
	 * @param boolean $remember
	 * @return boolean
	 */
	public function login($username, $password, $remember = false) {
		if ($this->CI->user->login($username, $password, $remember)) {
			return true;
		} else {
			$this->CI->messages->add(_('Incorrect username or password'), 'error');
			return false;
		}
	}

	/**
	 * Logs the user out.
	 *
	 * @return boolean
	 */
	public function logout() {
		$this->CI->session->unset_userdata('username');
		$this->CI->session->unset_userdata('group');
		$this->CI->session->unset_userdata('user_id');

		// delete the remember cookies if they exist
		if (get_cookie('username')) {
			delete_cookie('username');
		} if (get_cookie('remember_code')) {
			delete_cookie('remember_code');
		}

		$this->CI->session->sess_destroy();
		$this->CI->session->sess_create();

		return true;
	}

	/**
	 * Kept for backwards compatibility.
	 *
	 * @deprecated 14-09-2011
	 * @see Access::isLoggedIn()
	 */
	public function loggedIn() {
		return $this->isLoggedIn();
	}

	/**
	 * Checks if the user is logged in.
	 *
	 * @return boolean
	 */
	public function isLoggedIn() {
		return (boolean) $this->CI->session->userdata('username');
	}

	/**
	 * Checks if the user is an admin.
	 *
	 * @return boolean
	 */
	public function isAdmin() {
		$adminGroup = 'admins';
		$userGroup  = $this->CI->session->userdata('group');
		return $userGroup == $adminGroup;
	}

	/**
	 * Checks if the current user is assigned to the speCIfied group.
	 *
	 * @param string $checkGroup
	 * @return boolean
	 */
	public function isGroup($checkGroup)  {
		$userGroup = $this->CI->session->userdata('group');

		if (is_array($checkGroup)) {
			return in_array($userGroup, $checkGroup);
		}
		return $userGroup == $checkGroup;
	}

	/**
	 * Gets the current logged in user.
	 *
	 * @return object
	 */
	public function getCurrentUser() {
		return $this->CI->user->getById($this->CI->session->userdata('user_id'));
	}

	/**
	 * Gets the profile of the current user.
	 *
	 * @return array
	 */
	public function profile() {
		if (!isset($this->cache['profile']->username)) {
			$this->cache['profile'] = $this->CI->user->profile($this->CI->session->userdata('username'));
		}
		return $this->cache['profile'];
	}

	/**
	 * Gets the settings of the current user.
	 *
	 * @return array
	 */
	public function settings($key) {
		if ((boolean) $this->CI->session->userdata('settings')) {
			$settings = $this->CI->session->userdata('settings');
		} else {
			$settings = $this->CI->user->getSettings($this->CI->session->userdata('user_id'));
			$this->CI->session->set_userdata('settings', $settings);
		}

		return isset($settings[$key]) ? $settings[$key] : false;
	}

}

/* End of file Access.php */
/* Location: ./application/libraries/Access.php */
