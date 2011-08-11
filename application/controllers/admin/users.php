<?php

/**
 * Controller for users.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 *
 */
class Users extends CI_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('user');
		$this->load->config('form_validation');
	}

	/**
	 * Shows a list of all users.
	 */
	public function index() {
		$data['users'] = $this->user->getAll();
		$this->load->view('admin/users/index', $data);
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
				'phone' => $this->input->post('phone')
			);

			if ($this->user->register($username, $this->input->post('password'), $this->input->post('email'), $data)) {
				$this->messages->add(sprintf(_("The user '%s' was created"), $username), 'success');
				redirect('admin/users', 303);
			}
		}

		$this->load->view('admin/users/create');
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
				'phone' => $this->input->post('phone')
			);

			if ($this->user->update($user['id'], $data)) {
				$this->messages->add(sprintf(_("The user '%s' was updated"), $user['username']), 'success');
				redirect('admin/users', 303);
			}
		}

		$this->load->view('admin/users/edit', array('user' => $user));
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
			$this->messages->add(_("The selected user was deleted"), 'success');
			redirect('admin/users', 303);
		}
	}

}

/* End of file users.php */
/* Location: ./application/constrollers/users.php */
