<?php

/**
 * @author Karsten Heiken, karsten@disposed.de
 */
class Programs extends CI_Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('program');

        // load language file
        $this->lang->load(strtolower($this->router->class));
    }

	/**
	 * Show a list of all available programs.
	 */
	public function index() {
		$programs = $this->program->getAll();
		
		$tpl['programs'] = $programs;
		$this->load->view('program/list', $tpl);
	}

	/**
	 * Show detailed information about a program.
	 * 
	 * @param type $prg_id The program's id
	 */
	public function detail($prg_id) {
		$program = $this->program->getById($prg_id);
		
		$tpl['program'] = $program;
		$this->load->view('program/detail', $tpl);
	}
}
