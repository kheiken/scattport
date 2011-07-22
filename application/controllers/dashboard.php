<?php

class Dashboard extends CI_Controller {

	/**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
		$this->load->model('job');
		$this->load->model('project');
		$this->load->model('user');

        // load language file
        $this->lang->load(strtolower($this->router->class));
    }

	public function index() {
		$this->load->view('dashboard');
	}
}