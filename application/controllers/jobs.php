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
class Jobs extends MY_Controller {

	/**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
		$this->load->model('job');
		$this->load->model('experiment');
		$this->load->model('program');
    }

    /**
     * Starts a job for the specified experiment.
     *
     * @param string $experimentId
     */
	public function start($experimentId = '') {
		$experiment = $this->experiment->getByID($experimentId);
		if (isset($experiment['id'])) {
			// execute program runner
			$program = $this->program->getById($experiment['program_id']);
			$this->load->library('program_runner', array('program_driver' => $program['driver']));
			$this->program_runner->createJob($experiment['id']);

			redirect('experiments/detail/' . $experiment['id']);
		} else {
			show_404();
		}
	}

	/**
	 * Get jobs belonging to projects owned by the user.
	 */
	public function getOwn() {
		$query = $this->db->order_by('progress', 'desc')
			->get_where('jobs', array('started_by' => $this->session->userdata('user_id')));
		$jobs = $query->result_array();

		for($i=0; $i<count($jobs); $i++) {
			$jobs[$i]['project_name'] = $this->db->select('name')->get_where('projects', array('id' => $jobs[$i]['project_id']))->row()->name;
			$progress = $jobs[$i]['progress'];

			switch($progress) {
				case -1:
					$progress = _('waiting');
					break;
				case -2:
					$progress = _('failed');
					break;
				case 100:
					$progress = _('done');
					break;
				default:
					$progress = $progress . "%";
					break;
			}

			$jobs[$i]['progress'] = $progress;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(
				array(
					'count' => count($jobs),
					'jobs' => $jobs
				)
			));
	}


	/**
	 * Get a list of results that the owner has not yet seen.
	 * @todo not yet verified
	 */
	public function get_unseen_results() {
		$results = $this->job->getUnseenResults();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(
				array(
					'count' => count($results),
					'results' => $results
				)
			));
	}
}
