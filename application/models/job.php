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
class Job extends CI_Model {

	/**
	 * Create a new job.
	 *
	 * @param array $data the data of the new job
	 * @return bool was the insert successful
	 */
	public function create($data) {
		return $this->db->insert('jobs', $data);
	}

	/**
	 * Delete a job.
	 * @param string the job id to delete
	 * @return bool was the deletion successful
	 */
	public function delete($job_id) {
		return $this->db->delete('jobs', array('id' => $job_id));
	}

	/**
	 * Update the details of a given job.
	 *
	 * @param string $job_id The job's id you want to update.
	 * @param integer $data The data of the job.
	 */
	public function update($job_id, $data) {
		return $this->db->where('id', $job_id)->update('jobs', $data);
	}

	/**
	 * Get a list of results that the owner has not yet seen.
	 */
	public function getUnseenResults() {
		$query = $this->db->order_by('started_at', 'asc')
			->get_where('jobs', array('started_by' => $this->session->userdata('user_id'), 'seen' => '0'));
		$jobs = $query->result_array();

		for($i=0; $i<count($jobs); $i++) {
			$jobs[$i]['project_name'] = $this->db->select('name')->get_where('projects', array('id' => $jobs[$i]['project_id']))->row()->name;
		}

		return $jobs;
	}

	/**
	 * Get a list of jobs that have not yet started running.
	 */
	public function getWaitingJob() {
		$query = $this->db->order_by('created_at', 'asc')->get_where('jobs', array('started_at' => '0000-00-00 00:00:00'), 1);
		return $this->db->count_all_results() > 0 ? $query->row() : FALSE;
	}
}
