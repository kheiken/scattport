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
				'label' => _('Username'),
				'rules' => 'trim|required|min_length[4]|max_length[20]|unique[users.username]',
			),
			array(
				'field' => 'password',
				'label' => _('Password'),
				'rules' => 'required|min_length[6]|matches[password_confirm]',
			),
			array(
				'field' => 'password_confirm',
				'label' => _('Confirm password'),
			),
			array(
				'field' => 'firstname',
				'label' => _('First name'),
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'lastname',
				'label' => _('Last name'),
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'email',
				'label' => _('Email address'),
				'rules' => 'trim|required|valid_email',
			),
			array(
				'field' => 'institution',
				'label' => _('Institution'),
				'rules' => 'trim|max_length[100]',
			),
			array(
				'field' => 'phone',
				'label' => _('Phone number'),
				'rules' => 'trim|regex_match[/^\+\d{2,4}\s\d{2,4}\s\d{3,10}+$/i]',
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
				$this->messages->add(sprintf(_("The user '%s' was created"), $username), 'success');
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
				'label' => _('First name'),
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'lastname',
				'label' => _('Last name'),
				'rules' => 'trim|required|max_length[50]',
			),
			array(
				'field' => 'email',
				'label' => _('Email address'),
				'rules' => 'trim|required|valid_email',
			),
			array(
				'field' => 'institution',
				'label' => _('Institution'),
				'rules' => 'trim|max_length[100]',
			),
			array(
				'field' => 'phone',
				'label' => _('Phone number'),
				'rules' => 'trim|regex_match[/^\+\d{2,4}\s\d{2,4}\s\d{3,10}+$/i]',
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
				$this->messages->add(sprintf(_("The user '%s' was updated"), $user['username']), 'success');
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
		$user = $this->user->getUserByID($id);

		if (!isset($user) || !is_array($user) || !isset($user['id'])) {
			show_404();
		} else {
			$this->user->delete($user['id']);
			$this->messages->add(_("The selected user was deleted"), 'success');
			redirect('users', 200);
		}
	}

}

/* End of file users.php */
/* Location: ./application/constrollers/users.php */
