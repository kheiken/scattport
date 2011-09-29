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
 * Result browser.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Results extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('program');
		$this->load->model('job');
		$this->load->model('server');
		$this->load->model('experiment');
	}

	/**
	 *
	 */
	public function index() {
	}

	/**
	 * Get the results of a given project.
	 *
	 * @param string $prj_id the project for which to get the results
	 */
	public function project($projectId) {
	}

	/**
	 * Get the results of a given experiment.
	 *
	 * @param string $experimentId The experiment for which to get the results
	 */
	public function experiment($experimentId = '') {
		$experiment = $this->experiment->getById($experimentId);

		if (!is_array($experiment) || !isset($experiment['id'])) {
			show_404();
		}

		// execute program runner
		$program = $this->program->getById($experiment['program_id']);
		$this->load->library('program_runner', array('program_driver' => $program['driver']));
		$results = $this->program_runner->getResults($experiment['id']);

		$data = array(); // empty data array
		$data['experiment'] = $experiment;
		$data['project'] = $this->project->getById($experiment['project_id']);
		$data['results'] = $results;

		// mark the project as seen
		$job = $this->job->getByExperimentId($experimentId);
		$this->job->markSeen($job['id']);
		
		$this->load->view('results/experiment', $data);
	}

	/**
	 * Downloads the results of a given experiment.
	 *
	 * @param string $experimentId
	 */
	public function download($experimentId = '') {
		$job = $this->job->getByExperimentId($experimentId);
		if (empty($experimentId) || !$job) {
			show_404();
		}

		$experiment = $this->experiment->getById($job['experiment_id']);

		$path = FCPATH.'uploads/'.$experiment['project_id'].'/'.$experiment['id'].'/';

		if (file_exists($path.'default.out')) {
			// load download helper
			$this->load->helper('download');
			// download the file
			$data = file_get_contents($path.'default.out');
			force_download('default.out', $data);
		}
	}
}

/* End of file results.php */
/* Location: ./application/controllers/results.php */
