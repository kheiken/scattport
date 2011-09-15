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
 *
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Projects extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('experiment');
		$this->load->model('job');
	}

	/**
	 * Shows a list of all projects.
	 */
	public function index() {
		if ($this->access->isAdmin()) {
			$data['projects'] = $this->project->getAll();
		} else {
			$data['projects'] = $this->project->getAccessible($this->session->userdata('user_id'));
		}

		$this->load->view('projects/list', $data);
	}

	/**
	 * Allows users to create a new project.
	 */
	public function create() {
		// run form validation
		if ($this->form_validation->run() === true) {
			$data = array(
					'name' => $this->input->post('name'),
					'description' => $this->input->post('description'),
					'public' => $this->input->post('public'),
			);

			$data['project_id'] = $this->project->create($data);

			if (isset($data['project_id'])) {
				$this->load->helper('directory');
				$projectPath = FCPATH . 'uploads/' . $data['project_id'] . '/';
				mkdirs($projectPath);

				$config = array(
						'upload_path' => $projectPath,
						'allowed_types' => '*',
						'overwrite' => true,
				);
				$this->load->library('upload', $config);

				if ($_FILES['defaultmodel']['tmp_name'] != '') {
					$config['file_name'] = 'defaultmodel';
					$this->upload->initialize($config);

					if ($this->upload->do_upload('defaultmodel')) {
						$default = $this->upload->data();
						$this->project->update($data['project_id'], array('default_model' => $default['file_name']));
					} else {
						$this->messages->add(_('The default model could not be uploaded.'), 'error');
					}
				}
				if ($_FILES['defaultconfig']['tmp_name'] != '') {
					$config['file_name'] = 'defaultconfig';
					$this->upload->initialize($config);

					if ($this->upload->do_upload('defaultconfig')) {
						$default = $this->upload->data();
						$this->project->update($data['project_id'], array('default_config' => $default['file_name']));
					} else {
						$this->messages->add(_('The default config could not be uploaded.'), 'error');
					}
				}

				$this->messages->add($projectpath, 'notice');
				redirect('/projects/detail/' . $data['project_id'], 303);
			} else {
				$this->messages->add(_('The project could not be created.'), 'error');
			}
		}

		$data = array();

		$this->load->view('projects/new', $data);
	}

	/**
	 * Shows the project details
	 *
	 * @param integer $id The ID of the project to show
	 */
	public function detail($id) {
		$project = $this->project->getById($id);
		if (!$project) {
			show_404();
		}

		if (!$this->_checkAccess($id)) { // check if the user has access
			show_error(_("Sorry, you don't have access to this project."), 403);
		}

		$this->load->helper('typography');

		$data['project'] = $project;
		$data['experiments'] = $this->experiment->getByProjectId($id);
		$data['jobs'] = $this->job->getRecent($id);
		$data['shares'] = $this->share->getByProjectId($id);

		$this->load->view('projects/detail', $data);
	}

	/**
	 * Allows users to share their projects.
	 *
	 * @param string $id
	 */
	public function shares($id) {
		$project = $this->project->getById($id);
		if (!$project) {
			show_404();
		}

		if (!$this->_checkAccess($id)) {
			// check if the user has access
			show_error(_("Sorry, you don't have access to this project."), 403);
		}

		// add a new share
		if ($this->input->get('action') == 'add') {
			$data = array(
				'user_id' => $this->input->post('user_id'),
				'project_id' => $project['id'],
				'can_edit' => $this->input->post('rights'),
			);
			if ($this->share->create($data)) {
				redirect('projects/shares/' . $project['id'], 303);
			}
		} else if ($this->input->get('action') == 'update') { // update all shares
			foreach ($_POST['rights'] as $userId => $value) {
				$this->share->update(array('project_id' => $project['id'], 'user_id' => $userId, 'can_edit' => $value));
			}
			redirect('projects/shares/' . $project['id'], 303);
		} else if ($this->input->get('action') == 'delete') { // delete a share
			if ($this->share->delete(array('user_id' => $this->input->get('user_id'), 'project_id' => $project['id']))) {
				redirect('projects/shares/' . $project['id'], 303);
			}
		}

		$data = array(); // empty data array
		$data['project'] = $project;
		$data['shares'] = $this->share->getByProjectId($id);

		$this->load->view('projects/shares', $data);
	}

	/**
	 * Allows users to delete a project.
	 *
	 * @param string $id
	 */
	public function delete($id) {
		if (!$this->_checkAccess($id)) { // check if the user has access
			show_error(_("Sorry, you don't have access to this project."), 403);
		}

		if ($this->project->delete($id)) {
			$this->messages->add(_('The project was deleted.'), 'success');
		}
		redirect('projects', 303);
	}

	/**
	 * Checks if users have access to a project.
	 *
	 * @param string $projectId
	 */
	private function _checkAccess($projectId) {
		$project = $this->project->getById($projectId);
		$share = $this->share->get($projectId, $this->session->userdata('user_id'));
		return $this->access->isAdmin() || $project['public'] == 1 || isset($share['project_id']);
	}
}

/* End of file projects.php */
/* Location: ./application/controllers/projects.php */
