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
 * Program management.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Programs extends Admin_Controller {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('program');
		$this->load->model('parameter');
		$this->load->library('form_validation');
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
	public function edit($programId = '') {
		$program = $this->program->getByID($programId);

		if (empty($programId) || !isset($program['id'])){
			show_404();
		}

		if ($this->form_validation->run('programs/edit') === true) {
			$data = array(
				'name' => $this->input->post('name', true),
				'config_template' => $this->input->post('config_template', false),
			);
			if ($this->program->update($data, $programId)) {
				$this->messages->add(sprintf(_("The program &quot;%s&quot; has been updated successfully"), $this->input->post('name')), 'success');
				redirect('admin/programs/edit/' . $program['id'], 303);
			}
		}

		$data = array(); // empty data array
		$data['program'] = $program;
		$data['parameters'] = $this->parameter->getAll($program['id']);

		$this->load->view('admin/programs/edit', $data);
	}
}

/* End of file programs.php */
/* Location: ./application/controllers/admin/programs.php */
