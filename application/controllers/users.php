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
		$this->load->view('admin/users/create');
	}
}