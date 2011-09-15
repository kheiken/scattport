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
 * Simple RPC class to handle requests from calculation servers.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Api extends CI_Controller {

	/**
	 * Update the state of a given job.
	 *
	 * Because we do not want any access from servers we do not trust,
	 * we need a special secret to authenticate the servers.
	 *
	 * @param type $secret The secret to authenticate the server.
	 * @param type $job_id The job id that is running on the server.
	 * @param type $state The state of the job.
	 */
	public function update_job() {
		$this->load->model('job');

		$query = $this->db->get_where('servers', array('secret' => $secret));

		// if we are in production mode, we do not want to tell the evil hacker
		// that he used a wrong secret. That just encourages him.
		if($query->num_rows() < 1 && ENVIRONMENT != 'production') {
			die("Unauthorized access.");
		}

		$this->job->update($job_id, $progress);
	}

	/**
	 * Update the workload of the server.
	 */
	public function update_workload() {
		$this->load->model('server');
		$query = $this->db->get_where('servers', array('secret' => $secret));

		// if we are in production mode, we do not want to tell the evil hacker
		// that he used a wrong secret. That just encourages him.
		if($query->num_rows() < 1 && ENVIRONMENT != 'production') {
			die("Unauthorized access.");
		}

		$this->server->updateWorkload($secret, $workload);
	}
}