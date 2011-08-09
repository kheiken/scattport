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
 * @author Karsten Heiken <karsten@disposed.de>
*/
class Projects extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('project');
		$this->load->model('trial');
	}

	/**
	 * Shows a list of all projects.
	 */
	public function index() {
		$projects = $this->project->getAll();

		$tpl['projects'] = $projects;
		$this->load->view('project/list', $tpl);
	}

	/**
	 * Allows users to create a new project.
	 */
	public function create() {
		$this->load->library('upload');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		$config = array(
		array(
				'field' => 'name',
				'label' => 'Projektname',
				'rules' => 'trim|required|min_length[3]|max_length[100]|xss_clean',
		),
		array(
				'field' => 'description',
				'label' => 'Beschreibung',
				'rules' => 'trim|required|xss_clean',
		),
		array(
				'field' => 'defaultmodel',
				'label' => '3D-Modell',
		),
		array(
				'field' => 'defaultconfig',
				'label' => 'Standard-Konfiguration',
		),
		);

		$this->form_validation->set_rules($config);

		if (count($_POST) > 0) {
			$config = array();
			$config['upload_path'] = '/tmp';
			$config['allowed_types'] = 'txt';
			$config['max_size']	= '1024';
			$config['file_name'] = 'defaultmodel';

			$this->upload->initialize($config);
			$modelUploaded = $this->upload->do_upload('defaultmodel');
			$modelData = $this->upload->data();

			$config['file_name'] = 'defaultconfig';

			$this->upload->initialize($config);
			$configUploaded = $this->upload->do_upload('defaultconfig');
			$configData = $this->upload->data();
		}

		// run form validation
		if ($this->form_validation->run() == false) {
			$data['model']['success'] = isset($modelUploaded) && $modelUploaded ? true : false;
			$data['config']['success'] = isset($configUploaded) && $configUploaded ? true: false;

			$this->load->view('project/new', $data);
		} else {
			$data = array(
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
				'defaultmodel' => $modelData['file_name'],
				'defaultconfig' => $configData['file_name'],
			);

			$data['project_id'] = $this->project->create($data);

			if (isset($data['project_id'])) {
				$userpath = FCPATH . 'uploads/' . $this->session->userdata('user_id') . '/';
				$projectpath = $userpath . $data['project_id'] . '/';

				if (!is_dir($projectpath)) {
					if (!is_dir($userpath)) {
						mkdir($userpath, 0777);
						chmod($userpath, 0777);
					}
					mkdir($projectpath, 0777);
					chmod($projectpath, 0777);
				}

				if ($modelUploaded) {
					copy($modelData['full_path'], $projectpath . $modelData['file_name']);
				}
				if ($configUploaded) {
					copy($configData['full_path'], $projectpath . $configData['file_name']);
				}

				$this->messages->add($projectpath, 'notice');
				redirect('/projects/detail/' . $data['project_id'], 301);
			} else {
				$this->messages->add('Das Projekt konnte nicht gespeichert werden.', 'error');
				$this->load->view('project/new');
			}
		}
	}

	/**
	 * Shows the project details
	 *
	 * @param integer $prj_id The ID of the project to show
	 */
	public function detail($prj_id) {
		$project = $this->project->getById($prj_id);
		if (!$project) {
			$this->messages->add('Das Projekt konnte nicht geladen werden.', 'error');
			redirect('projects', 301);
		}

		$this->session->set_userdata('active_project', $prj_id);
		$trials = $this->trial->getByProjectId($prj_id);

		$tpl['project'] = $project;
		$tpl['trials'] = $trials;
		$tpl['jobsDone'] = null;
		$this->load->view('project/detail', $tpl);
	}

	/**
	 * Allows users to delete a project.
	 *
	 * @param integer $projectId
	 */
	public function delete($projectId) {
		$this->project->delete($projectId);
		$this->session->unset_userdata('active_project');
		$this->messages->add("Das Projekt wurde gelöscht.", 'notice');
		redirect('projects');
	}

}
