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
 * Controller for Ajax requests.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Ajax extends CI_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		// security check
		if (!$this->input->is_ajax_request()) {
			show_error(_('No AJAX request was made.'));
		}
	}

	/**
	 * Load the notifications for the user.
	 *
	 * This is used for sending notifications to the user like
	 * "There are new results for the project 'foobar'".
	 */
	public function get_notifications() {
		$tpl['messages'] = $this->messages->get();
		$this->load->view('global/notifications', $tpl);
	}

	/**
	 * Saves the projects description.
	 *
	 * @param string $projectId
	 */
	public function update_project($projectId) {
		$this->load->model('project');
		$this->load->helper('typography');

		$data['description'] = $this->input->post('description');
		$this->project->update($data, $projectId);

		$this->output->set_output(auto_typography($data['description']));
	}

	/**
	 *
	 * @param string $experimentId
	 */
	public function rename_project($projectId) {
		$this->load->model('project');
		$this->project->update(array('name' => $this->input->post('title')), $projectId);
		$this->output->set_output(site_url('projects/detail/' . $projectId));
	}

	/**
	 * Saves the experiments description.
	 *
	 * @param string $experimentId
	 */
	public function update_experiment($experimentId) {
		$this->load->model('experiment');
		$this->load->helper('typography');

		$data['description'] = $this->input->post('description');
		$this->experiment->update($data, $experimentId);

		$this->output->set_output(auto_typography($data['description']));
	}

	/**
	 *
	 * @param string $experimentId
	 */
	public function rename_experiment($experimentId) {
		$this->load->model('experiment');
		$this->experiment->update(array('name' => $this->input->post('title')), $experimentId);
		$this->output->set_output(site_url('experiments/detail/' . $experimentId));
	}

	/**
	 * Sorts a programs parameters.
	 */
	public function sort_parameters() {
		$this->load->model('parameter');
		$this->parameter->sort($this->input->post('parameters'));
	}

	/**
	 * Displays the description of parameters.
	 *
	 * @param string $parameterId
	 */
	public function parameter_help($parameterId) {
		$this->load->model('parameter');
		$this->load->helper('typography');

		$parameter = $this->parameter->getById($parameterId);
		$this->output->set_output(auto_typography($parameter['description']));
	}

}
