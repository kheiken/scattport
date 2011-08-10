<?php

/**
 * Controller for users.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 *
 */
class Users extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('user');
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
		$config = array(
			array(
				'field' => 'username',
				'label' => 'lang:field_username',
				'rules' => 'trim|required|min_length[4]|max_length[20]|unique[users.username]',
			),
			array(
				'field' => 'password',
				'label' => 'lang:field_password',
				'rules' => 'required|min_length[6]|matches[password_confirm]',
			),
			array(
				'field' => 'password_confirm',
				'label' => 'lang:field_password_confirm',
			),
			array(
				'field' => 'firstname',
				'label' => 'lang:field_firstname',
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'lastname',
				'label' => 'lang:field_lastname',
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'email',
				'label' => 'lang:field_email',
				'rules' => 'trim|required|valid_email',
			),
			array(
				'field' => 'institution',
				'label' => 'lang:field_institution',
				'rules' => 'trim|max_length[100]',
			),
			array(
				'field' => 'phone',
				'label' => 'lang:field_phone',
				'rules' => 'trim|regex_match[/^\+\d{2,4}\w\d{2,4}\w\d{3,10}+$/i]',
			)
		);
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() === true) {
			$username = $this->input->post('username');

			$data = array(
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'institution' => $this->input->post('institution'),
				'phone' => $this->input->post('phone')
			);

			if ($this->user->register($username, $this->input->post('password'), $this->input->post('email'), $data)) {
				$this->messages->add("The user '" . $username . "' was created", 'success');
				redirect('users', 201);
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

		$config = array(
			array(
				'field' => 'firstname',
				'label' => 'lang:field_firstname',
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'lastname',
				'label' => 'lang:field_lastname',
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'email',
				'label' => 'lang:field_email',
				'rules' => 'trim|required|valid_email',
			),
			array(
				'field' => 'institution',
				'label' => 'lang:field_institution',
				'rules' => 'trim|max_length[100]',
			),
			array(
				'field' => 'phone',
				'label' => 'lang:field_phone',
				//'rules' => 'trim|regex_match[/^\+\d{2,4}\w\d{2,4}\w\d{3,10}+$/i]',
			)
		);
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() === true) {
			$data = array(
				'email' => $this->input->post('email'),
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'institution' => $this->input->post('institution'),
				'phone' => $this->input->post('phone')
			);

			if ($this->user->update($user['id'], $data)) {
				$this->messages->add("The user '" . $user['username'] . "' was updated", 'success');
				redirect('users', 200);
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
		if (!is_array($this->user->getUserByID())) {
			show_404();
		}

		$this->user->delete($id);
		$this->messages->add('The selected user was deleted', 'success');
		redirect('users', 200);
	}
}