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
 * Parameter management.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Parameters extends Admin_Controller {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('parameter');
		$this->load->model('program');
		$this->load->library('form_validation');
	}

	/**
	 * Allows admins to create a new parameter.
	 *
	 * @param string $programId
	 */
	public function create($programId = '') {
		$program = $this->program->getByID($programId);

		if (empty($programId) || !isset($program['id'])) {
			show_404();
		}

		if ($this->form_validation->run('parameters/create') === true) {
			$paramName = $this->input->post('name');

			$data = array(
				'program_id' => $program['id'],
				'name' => $paramName,
				'readable' => $this->input->post('readable'),
				'unit' => $this->input->post('unit'),
				'description' => $this->input->post('description'),
				'type' => $this->input->post('type'),
				'default_value' => $this->input->post('default_value'),
			);

			if ($this->parameter->create($data)) {
				$this->messages->add(sprintf(_("The parameter '%s' has been successfully created."), $paramName), 'success');
				redirect('admin/programs/edit/' . $program['id'], 303);
			}
		}

		$data = array(); // empty the data array
		$data['types'] = $this->parameter->getTypes();
		$data['program'] = $program;

		$this->load->view('admin/parameters/create', $data);
	}

	/**
	 * Allows admins to upload a CSV file to create a bunch of parameters.
	 *
	 * @param string $programId
	 */
	public function upload_csv($programId = '') {
		$program = $this->program->getByID($programId);

		if (empty($programId) || !isset($program['id'])) {
			show_404();
		}

		if (count($_FILES) > 0) {
			if ($_FILES['csv_file']['type'] == 'text/csv') {
				$row = 1;
				$inserted = 0;
				$handler = fopen($_FILES['csv_file']['tmp_name'], "r");

				while (($parameter = fgetcsv($handler, 1000, ',')) !== false) {
					if ($row > 1) {
						$data = array(
							'program_id' => $program['id'],
							'name' => $parameter[0],
							'readable' => $parameter[1],
							'unit' => $parameter[2],
							'type' => $parameter[3],
							'default_value' => $parameter[4],
							'description' => $parameter[5],
						);

						if ($this->parameter->create($data)) {
							$inserted++;
						}
					}
					$row++;
				}

				if ($inserted > 0) {
					$this->messages->add(sprintf(_('%s parameters were successfully created.'), $inserted), 'success');
				}
			} else {
				$this->messages->add(_('No CSV file specified.'), 'error');
			}
		}

		redirect('admin/programs/edit/' . $program['id'], 303);
	}

	/**
	 * Allows admins to edit a parameter.
	 *
	 * @param string $id
	 */
	public function edit($id = '') {
		$parameter = $this->parameter->getByID($id);

		if (empty($id) || !isset($parameter['id'])){
			show_404();
		}

		if ($this->form_validation->run('parameters/create') === true) {
			$data = array(
				'name' => $this->input->post('name'),
				'readable' => $this->input->post('readable'),
				'unit' => $this->input->post('unit'),
				'description' => $this->input->post('description'),
				'type' => $this->input->post('type'),
				'default_value' => $this->input->post('default_value'),
			);

			if ($this->parameter->update($data, $id)) {
				$this->messages->add(sprintf(_("The parameter '%s' has been successfully updated."), $parameter['name']), 'success');
				redirect('admin/programs/edit/' . $parameter['program_id'], 303);
			}
		}

		$data = array(); // empty the data array
		$data['types'] = $this->parameter->getTypes();
		$data['parameter'] = $parameter;

		$this->load->view('admin/parameters/edit', $data);
	}

	/**
	 * Allows admins to delete a parameter.
	 *
	 * @param string $id
	 */
	public function delete($id = '') {
		$parameter = $this->parameter->getByID($id);

		if (empty($id) || !isset($parameter['id'])) {
			show_404();
		} else {
			if ($this->parameter->delete($parameter['id'])) {
				$this->messages->add(_('The selected parameter has been successfully deleted.'), 'success');
			}
			redirect('admin/programs/edit/' . $parameter['program_id'], 303);
		}
	}
}

/* End of file parameters.php */
/* Location: ./application/controllers/admin/parameters.php */
