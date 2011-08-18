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
 * @author Eike Foken <kontakt@eikefoken.de>
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
	 * Create a new project.
	 */
	public function create($projectId = '') {
		$project = $this->project->getByID($projectId);

		if (empty($projectId) || !isset($project['id'])){
			show_404();
		}

		$programs = $this->program->getAll();

		// get the parameters for a specific program
		foreach ($programs as $program) {
			$parameters[$program['id']] = $this->parameter->getAll($program['id']);
		}

		$config = array(
			array(
				'field' => 'name',
				'label' => _('Trial name'),
				'rules' => 'required|min_length[3]|max_length[60]|trim',
			),
			array(
				'field' => 'description',
				'label' => _('Description'),
				'rules' => 'required|trim',
			),
		);
		$this->form_validation->set_rules($config);

		if ($this->form_validation->run() === true) {
			// TODO: handle file upload

			$data = array(
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'program_id' => $this->input->post('program_id'),
				'project_id' => $projectId,
				'creator_id' => $this->session->userdata('user_id'),
			);

			$result = $this->trial->create($data);
			if ($result) {
				foreach ($_POST as $key => $value) {
					if (preg_match('/^param-[0-9a-z]+/', $key) && !empty($value)) {
						$param['parameter_id'] = substr($key, 6, 16);
						$param['value'] = $this->input->post($key);
						$this->trial->addParameter($param, $result);
					}
				}

				$userpath = FCPATH . 'uploads/' . $this->session->userdata('user_id') . '/';
				$projectpath = $userpath . $projectId . '/';
				$trialpath = $projectpath . $result . '/';
				if(!is_dir($trialpath)) {
					if (!is_dir($projectpath)) {
						if(!is_dir($userpath)) {
							mkdir($userpath);
							chmod($userpath, 0777);
						}
						mkdir($projectpath);
						chmod($projectpath, 0777);
					}
					mkdir($trialpath);
					chmod($trialpath, 0777);
				}

				redirect('trials/detail/' . $result, 'refresh');
			} else {
				$this->messages->add(_('The trial could not be created.'), 'error');
			}
		}

		$tpl['parameters'] = $parameters;
		$tpl['programs'] = $programs;
		$tpl['project'] = $project;

		$this->load->view('trial/new', $tpl);
	}
}

/* End of file trials.php */
/* Location: ./application/controllers/trials.php */
