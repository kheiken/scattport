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
	 * @param string $username
	 * @param string $old
	 * @param string $new
	 * @return boolean
	 */
	public function changePassword($username, $old, $new) {
		if ($this->ci->user->changePassword($username, $old, $new)) {
			$this->ci->messages->add(_('Password successfully changed'), 'success');
			return true;
		}

		$this->ci->messages->add(_('Unable to change password'), 'error');
		return false;
	}

	/**
	 * forgotten password feature
	 *
	 * @param string $email
	 * @return void
	 */
	public function forgottenPassword($email) {
		if ($this->ci->user->forgottenPassword($email)) {
			// get user information
			$user = $this->ci->user->getUserByEmail($email);

			$data = array(
                'username' => $user['username'],
                'forgotten_password_code' => $user['forgotten_password_code'],
			);

			$message = $this->ci->load->view('auth/email/forgot_password', $data, true);
			$this->ci->email->clear();
			$config['mailtype'] = $this->ci->config->item('email_type', 'auth');
			$this->ci->email->initialize($config);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from($this->ci->config->item('admin_email', 'auth'), 'Scattport');
			$this->ci->email->to($user['email']);
			$this->ci->email->subject('ScattPort - Forgotten Password Verification');
			$this->ci->email->message($message);

			if ($this->ci->email->send()) {
				$this->ci->messages->add(_('Password reset email sent'), 'success');
				return true;
			} else {
				$this->ci->messages->add(_('Unable to send password reset email'), 'error');
				return false;
			}
		} else {
			$this->ci->messages->add(_('This email address is not registered'), 'error');
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
		$profile  = $this->ci->user->profile($code, true); // pass the code to profile

		if (!is_object($profile)) {
			$this->ci->messages->add(_('Unable to change password'), 'error');
			return false;
		}

		$new_password = $this->ci->user->forgottenPasswordComplete($code, $profile->salt);

		if ($new_password) {
			$data = array(
                'username' => $profile->username,
                'new_password' => $new_password
			);

			$message = $this->ci->load->view('auth/email/forgot_password_complete', $data, true);

			$this->ci->email->clear();
			$config['mailtype'] = $this->ci->config->item('email_type', 'auth');
			$this->ci->email->initialize($config);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from($this->ci->config->item('admin_email', 'auth'), $this->ci->config->item('site_title', 'auth'));
			$this->ci->email->to($profile->email);
			$this->ci->email->subject('ScattPort - New Password');
			$this->ci->email->message($message);

			if ($this->ci->email->send()) {
				$this->ci->messages->add(_('Password successfully changed'), 'success');
				return true;
			} else {
				$this->ci->messages->add(_('Unable to change password'), 'error');
				return false;
			}
		}

		$this->ci->messages->add(_('Unable to change password'), 'error');
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
		if ($this->ci->user->login($username, $password, $remember)) {
			return true;
		} else {
			$this->ci->messages->add(_('Incorrect username or password'), 'error');
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

		$this->ci->messages->add(_('Logged out successfully'), 'success');
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
	 * @param string $checkGroup
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
	 * Gets the current logged in user.
	 *
	 * @return object
	 */
	public function getCurrentUser() {
		return $this->ci->user->getUserByID($this->ci->session->userdata('user_id'));
	}

	/**
	 * Gets the profile of the current user.
	 *
	 * @return array
	 */
	public function profile() {
		return $this->ci->user->profile($this->ci->session->userdata('username'));
	}

}

/* End of file Access.php */
/* Location: ./application/libraries/Access.php */
