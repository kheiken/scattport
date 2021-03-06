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
 * Authentication controller.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Auth extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
	}

	/**
	 * Redirects the user if needed, otherwise display the layout.
	 */
	public function index() {
		if (!$this->access->loggedIn()) {
			redirect('auth/login');
		} else {
			$this->load->view('index');
		}
	}

	/**
	 * Logs the user in - or not ;-)
	 */
	public function login() {
		if ($this->access->loggedIn()) {
			redirect('dashboard');
		}

		$data['messages'] = $this->messages->get('success');
		$this->messages->clear(); // clear all messages

		if ($this->form_validation->run() === true) {
			// check for "remember me"
			$remember = (boolean) $this->input->post('remember');

			if ($this->access->login($this->input->post('username'), $this->input->post('password'), $remember)) {
				redirect('dashboard', 303);
			} else { // if the login was un-successful
				$data['errors'] = $this->messages->get('error');
			}
		}

		$this->load->view('auth/login', $data);
	}

	/**
	 * Logs the user out.
	 */
	public function logout() {
		$logout = $this->access->logout();
		redirect('auth/login');
	}

	/**
	 * Allows users to register.
	 */
	public function register() {
		if ($this->access->loggedIn()) {
			redirect('dashboard');
		}

		// validate form input
		$this->form_validation->set_rules('username', _('Username'), 'required');
		$this->form_validation->set_rules('realname', _('Real name'), 'required');
		$this->form_validation->set_rules('lastname', _('Last name'), 'required');
		$this->form_validation->set_rules('email', _('eMail address'), 'required|valid_email');
		$this->form_validation->set_rules('password', _('Password'), 'required|min_length[' . $this->config->item('min_password_length', 'access') . ']|max_length[' . $this->config->item('max_password_length', 'access') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', _('Password confirmation'), 'required');

		if ($this->form_validation->run() == true) {
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$additional_data = array(
                'realname' => $this->input->post('realname'),
			);
		}

		if ($this->form_validation->run() == true && $this->access->register($username, $password, $email, $additional_data))  {
			// redirect them to the login page
			$this->session->set_flashdata('message', _('Registration successful'));
			redirect('auth/register_success');
		} else {
			// set the flash data error message if there is one
			$this->data['message'] = validation_errors() ? validation_errors() : ($this->access->errors() ? $this->access->errors() : $this->session->flashdata('message'));
			$this->data['username'] = $this->form_validation->set_value('username');
			$this->data['email'] = $this->form_validation->set_value('email');
			$this->data['realname'] = $this->form_validation->set_value('realname');
			$this->data['password'] = $this->form_validation->set_value('password');
			$this->data['password_confirm'] = $this->form_validation->set_value('password_confirm');
			$this->load->view('auth/register', $this->data);
		}
	}

	/**
	 * Allows users to edit their settings.
	 */
	public function settings() {
		$user = $this->access->getCurrentUser();

		if ($this->form_validation->run() === true) {
			// change password if needed
			if ($this->input->post('new_password') != '') {
				$username = $this->session->userdata('username');
				$change = $this->access->changePassword($username, $this->input->post('old_password'), $this->input->post('new_password'));
			}

			// update users table
			$data = array(
				'email' => $this->input->post('email'),
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'institution' => $this->input->post('institution'),
				'phone' => $this->input->post('phone')
			);

			// update the users settings
			$settings = array(
				'projects_sort_recently' => $this->input->post('projects_sort_recently'),
				'jobs_check_interval' => $this->input->post('jobs_check_interval'),
			);

			$this->user->updateSettings($settings, $user['id']);
			$this->session->unset_userdata('settings'); // clear saved settings

			if ($this->user->update($user['id'], $data)) {
				$this->messages->add(_("Changes saved successfully"), 'success');
				redirect('auth/settings', 303);
			}
		}

		$this->load->view('auth/settings', $user);
	}

	/**
	 * Allows users to request a new password.
	 */
	public function forgot_password() {
		if ($this->form_validation->run() === true) {
			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->access->forgottenPassword($this->input->post('email'));

			if ($forgotten) { // if there were no errors
				redirect('auth/login'); // TODO Display a confirmation page here instead of the login page
			} else {
				redirect('auth/forgot_password');
			}
		}

		$data['messages'] = $this->messages->get('success');
		$data['errors'] = $this->messages->get('error');

		$this->load->view('auth/forgot_password', $data);
	}

	/**
	 * Final step for forgotten password.
	 */
	public function reset_password($code) {
		$reset = $this->access->forgottenPasswordComplete($code);

		if ($reset) {  // if the reset worked then send them to the login page
			redirect('auth/login');
		} else { // if the reset didn't work then send them back to the forgot password page
			redirect('auth/forgot_password');
		}
	}

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
