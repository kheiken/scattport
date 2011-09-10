<?php defined('BASEPATH') || exit('No direct script access allowed');
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
	 * Creates a new job.
	 *
	 * @param array $data The data of the new job
	 * @return boolean Returns TRUE if the insert was successful.
	 */
	public function create($data) {
		$this->load->helper('date');
		$this->load->helper('hash');

		do { // generate unique hash
			$data['id'] = random_hash();
		} while ($this->db->where('id', $data['id'])->from('jobs')->count_all_results() > 0);

		$data['created_at'] = date('Y-m-d H:i:s', now());

		$this->db->insert('jobs', $data);

		return $this->db->affected_rows() > 0 ? $data['id'] : false;
	}

	/**
	 * Deletes a job.
	 * @param string The job ID to delete
	 * @return boolean Returns TRUE if the deletion was successful.
	 */
	public function delete($job_id) {
		return $this->db->delete('jobs', array('id' => $job_id));
	}

	/**
	 * Updates the details of a given job.
	 *
	 * @param string $job_id The job ID you want to update
	 * @param integer $data The data of the job.
	 */
	public function update($job_id, $data) {
		return $this->db->where('id', $job_id)->update('jobs', $data);
	}

	/**
	 * Gets a list of recent jobs.
	 *
	 * @param string $projectId The project's ID you want to get the jobs for
	 * @return array
	 */
	public function getRecent($projectId = '') {
		$this->db->select('jobs.*, experiments.project_id, experiments.name');
		$this->db->join('experiments', 'jobs.experiment_id = experiments.id', 'left');
		//$this->db->where('finished_at', 0);

		if (!empty($projectId)) {
			$this->db->where('project_id', $projectId);
		}

		$jobs = $this->db->get('jobs')->result_array();
		return array_map(function($var) {
			if($var['started_at'] == '0000-00-00 00:00:00') {
				$var['status'] = 'pending';
			} elseif($var['finished_at'] == '0000-00-00 00:00:00') {
				$var['status'] = 'running';
			} else {
				$var['status'] = 'complete';
			}
			return $var;
		}, $jobs);
	}

	/**
	 * Gets a list of results that the owner has not yet seen.
	 *
	 * @return array
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
	 * Gets a list of jobs that have not yet started running.
	 *
	 * @return mixed
	 */
	public function getWaitingJob() {
		$query = $this->db->order_by('created_at', 'asc')->get_where('jobs', array('started_at' => '0000-00-00 00:00:00'), 1);
		return $this->db->count_all_results() > 0 ? $query->row() : FALSE;
	}
}

/* End of file job.php */
/* Location: ./application/controllers/job.php */
