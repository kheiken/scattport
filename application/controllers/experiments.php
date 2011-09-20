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

/**
 * Experiments are used to store different variations of the same project.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakts@eikefoken.de>
 */
class Experiments extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('parameter');
		$this->load->model('program');
		$this->load->model('experiment');
		$this->load->model('job');
	}

	/**
	 * Allows users to create new experiments.
	 *
	 * @param string $projectId
	 */
	public function create($projectId = '', $copyId = '') {
		// TODO: Handle copying of experiments

		$project = $this->project->getByID($projectId);

		if (empty($projectId) || !isset($project['id'])){
			show_404();
		}

		$programs = $this->program->getAll();

		// get the parameters for a specific program
		foreach ($programs as $program) {
			$parameters[$program['id']] = $this->parameter->getAll($program['id']);
		}

		if (is_null($project['default_model'])) {
			$this->load->config('form_validation');
			foreach ($this->config->item('experiments/create') as $rule) { // restore old rules
				$this->form_validation->set_rules($rule['field'], $rule['label'], $rule['rules']);
			}

			$this->form_validation->set_rules('3dmodel', _('3D model'), 'file_required|file_allowed_type[obj]');
		}

		// run form validation
		if ($this->form_validation->run('experiments/create') === true) {
			$data = array(
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description'),
					'program_id' => $this->input->post('program_id'),
					'project_id' => $projectId,
					'creator_id' => $this->session->userdata('user_id'),
			);

			$data['experiment_id'] = $this->experiment->create($data);

			if (isset($data['experiment_id'])) {
				$this->load->helper('directory');
				$experimentPath = FCPATH . 'uploads/' . $projectId . '/' .  $data['experiment_id'] . '/';
				mkdirs($experimentPath);

				$config = array(
						'upload_path' => $experimentPath,
						'allowed_types' => '*',
						'overwrite' => true,
						'file_name' => 'default',
				);
				$this->load->library('upload', $config);

				if ($_FILES['3dmodel']['tmp_name'] != '') {
					if ($this->upload->do_upload('3dmodel')) {
						$model = $this->upload->data();
					} else {
						$this->messages->add(_('The selected model could not be uploaded.'), 'error');
					}
				}

				// save parameters to db
				foreach ($_POST as $key => $value) {
					if (preg_match('/^param-[0-9a-z]+/', $key) && !empty($value)) {
						$param['parameter_id'] = substr($key, 6, 16);
						$param['value'] = $this->input->post($key);
						$this->experiment->addParameter($param, $data['experiment_id']);
					}
				}

				//redirect('/experiments/detail/' . $data['experiment_id'], 303);
				redirect('/projects/detail/' . $projectId, 303);
			} else {
				$this->messages->add(_('The experiment could not be created.'), 'error');
			}
		}

		$data = array(); // empty the data array
		$data['parameters'] = $parameters;
		$data['programs'] = $programs;
		$data['project'] = $project;

		$this->load->view('experiments/new', $data);
	}

	/**
	 * Copies an experiment.
	 *
	 * @param string $experimentId
	 */
	public function copy($experimentId = '') {
		$experiment = $this->experiment->getByID($experimentId);
		if (isset($experiment['project_id'])) {
			redirect('experiments/create/' . $experiment['project_id'] . '/' . $experimentId);
		} else {
			show_404();
		}
	}

	/**
	 * Shows detailed informations about a specific experiment.
	 *
	 * @param string $experimentId
	 */
	public function detail($experimentId = '') {
		$experiment = $this->experiment->getByID($experimentId);
		if (empty($experimentId) || !isset($experiment['id'])){
			show_404();
		}

		$this->load->helper('typography');

		$data['experiment'] = $experiment;
		$data['parameters'] = $this->experiment->getParameters($experiment['id']);
		$data['job'] = $this->job->getByExperimentId($experiment['id']);
		$data['project'] = $this->project->getById($experiment['project_id']);

		$this->load->view('experiments/detail', $data);
	}

	/**
	 * Allows users to edit an experiment.
	 *
	 * @param string $experimentId
	 */
	public function edit($experimentId) {
		redirect('experiments/detail/' . $experimentId);
	}

	/**
	 * Allows users to delete an experiment.
	 *
	 * @param string $experimentId
	 */
	public function delete($experimentId = '') {
		// TODO: Check user rights

		$experiment = $this->experiment->getByID($experimentId);
		if (empty($experimentId) || !isset($experiment['id'])){
			show_404();
		}

		$this->load->helper('file');

		$experimentPath = FCPATH . 'uploads/' . $experiment['project_id'] . '/' .  $experiment['id'] . '/';
		if (delete_files($experimentPath, true)) {
			rmdir($experimentPath);
		}

		$this->experiment->delete($experimentId);
		redirect('projects/detail/' . $experiment['project_id'], 303);
	}
}

/* End of file experiments.php */
/* Location: ./application/controllers/experiments.php */
