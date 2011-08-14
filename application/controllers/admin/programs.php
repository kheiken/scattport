<?php

/**
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Programs extends CI_Controller {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('program');
	}

	/**
	 * Shows a list of all available programs.
	 */
	public function index() {
		$data['programs'] = $this->program->getAll();
		$this->load->view('admin/programs/list', $data);
	}

	/**
	 * Allows admins to edit a program.
	 *
	 * @param string $id
	 */
	public function edit($id = '') {
		$program = $this->program->getByID($id);

		if (!isset($program) || !is_array($program)){
			show_404();
		}

		if ($this->form_validation->run('programs/edit') === true) {
			if ($this->program->update($this->input->post('name'), $id)) {
				$this->messages->add(sprintf(_("The program '%s' has been updated successfully"), $this->input->post('name')), 'success');
				redirect('admin/programs', 303);
			}
		}

		$data['program'] = $program;
		$data['parameters'] = $this->program->getParameters($program['id']);

		$this->load->view('admin/programs/edit', $data);
	}
}