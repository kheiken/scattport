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
require_once APPPATH . 'core/Admin_Controller.php';

/**
 * User management.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Users extends Admin_Controller {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('group');
		$this->load->library('form_validation');
	}

	/**
	 * Shows a list of all users.
	 */
	public function index() {
		$this->load->view('admin/users/list', array('users' => $this->user->getAll()));
	}

	/**
	 * Allows admins to create a new user.
	 */
	public function create() {
		if ($this->form_validation->run() === true) {
			$username = $this->input->post('username');

			$data = array(
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'institution' => $this->input->post('institution'),
				'phone' => $this->input->post('phone'),
				'group_id' => $this->input->post('group_id'),
			);

			if ($this->user->register($username, $this->input->post('password'), $this->input->post('email'), $data)) {
				redirect('admin/users', 303);
			} else {
				$this->messages->add(_("Something went wrong with the action you performed."), 'error');
			}
		}

		$data = array(); // empty data array
		$data['groups'] = $this->group->getAll();

		// get default group
		$this->load->config('auth');
		$data['default'] = $this->group->getByName($this->config->item('default_group', 'auth'));

		$this->load->view('admin/users/create', $data);
	}

	/**
	 * Allows admins to edit the specified user.
	 *
	 * @param integer $id
	 */
	public function edit($id = '') {
		$user = $this->user->getUserByID($id);

		if (!isset($user) || !is_array($user)){
			show_404();
		}

		if ($this->form_validation->run('users/edit') === true) {
			$data = array(
				'email' => $this->input->post('email'),
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'institution' => $this->input->post('institution'),
				'phone' => $this->input->post('phone'),
				'group_id' => $this->input->post('group_id'),
			);

			if ($this->user->update($user['id'], $data)) {
				$this->messages->add(sprintf(_("The user &quot;%s&quot; has been updated successfully."), $user['username']), 'success');
			}
			redirect('admin/users', 303);
		}

		$data = array(); // empty data array
		$data['user'] = $user;
		$data['groups'] = $this->group->getAll();

		$this->load->view('admin/users/edit', $data);
	}

	/**
	 * Allows admins to delete the specified user.
	 *
	 * @param integer $id
	 */
	public function delete($id = '') {
		$user = $this->user->getUserByID($id);

		if (!isset($user) || !is_array($user) || !isset($user['id'])) {
			show_404();
		} else {
			$this->user->delete($user['id']);
			redirect('admin/users', 303);
		}
	}
}

/* End of file users.php */
/* Location: ./application/constrollers/admin/users.php */
