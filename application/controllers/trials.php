<?php
/*
 * Copyright (c) 2011 Karsten Heiken <karsten@disposed.de>
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
 * Trials are used to store different variations of the same project.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakts@eikefoken.de>
 */
class Trials extends CI_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('parameter');
		$this->load->model('program');
		$this->load->model('project');
		$this->load->model('trial');
	}

	/**
	 * Allows users to create new trials.
	 *
	 * @param string $projectId
	 */
	public function create($projectId = '', $copyId = '') {
		// TODO: Handle copying of trials

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
			foreach ($this->config->item('trials/create') as $rule) { // restore old rules
				$this->form_validation->set_rules($rule['field'], $rule['label'], $rule['rules']);
			}

			$this->form_validation->set_rules('3dmodel', _('3d model'), 'file_required|file_allowed_type[obj]');
		}

		// run form validation
		if ($this->form_validation->run('trials/create') === true) {
			$data = array(
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description'),
					'program_id' => $this->input->post('program_id'),
					'project_id' => $projectId,
					'creator_id' => $this->session->userdata('user_id'),
			);

			$data['trial_id'] = $this->trial->create($data);

			if (isset($data['trial_id'])) {
				$this->load->helper('directory');
				$trialPath = FCPATH . 'uploads/' . $projectId . '/' .  $data['trial_id'] . '/';
				mkdirs($trialPath);

				$config = array(
						'upload_path' => $trialPath,
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
						$this->trial->addParameter($param, $data['trial_id']);
					}
				}

				// TODO: Don't start jobs automatically
				$program = $this->program->getById($data['program_id']);
				$this->load->library('program_runner', array('program_driver' => $program['driver']));
				$this->program_runner->createJob($data['trial_id']);

				redirect('/projects/detail/' . $projectId, 303);
			} else {
				$this->messages->add(_('The trial could not be created.'), 'error');
			}
		}

		$data = array(); // empty the data array
		$data['parameters'] = $parameters;
		$data['programs'] = $programs;
		$data['project'] = $project;

		$this->load->view('trial/new', $data);
	}
}

/* End of file trials.php */
/* Location: ./application/controllers/trials.php */
