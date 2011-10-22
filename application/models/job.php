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
 * Model for jobs.
 *
 * @package ScattPort
 * @subpackage Models
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Job extends CI_Model {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Add a human readable status to the job.
	 */
	private function addStatus($var) {
		if ($var['started_at'] == '0000-00-00 00:00:00') {
			$var['status'] = 'pending';
		} else if ($var['finished_at'] == '0000-00-00 00:00:00') {
			$var['status'] = 'running';
		} else {
			$var['status'] = 'complete';
		}
		return $var;
	}

	/**
	 * Creates a new job.
	 *
	 * @param array $data The data of the new job
	 * @return mixed Returns the ID of the created job, or FALSE if the insert
	 *                was unsuccessful.
	 */
	public function create($data) {
		do { // generate unique hash
			$data['id'] = random_string('sha1', 40);
		} while ($this->db->where('id', $data['id'])->from('jobs')->count_all_results() > 0);

		$data['created_at'] = mysql_now();

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
	 * Marks a finished job as seen.
	 *
	 * @param string $jobId
	 * @return boolean Returns TRUE on success.
	 */
	public function markSeen($jobId) {
		$this->db->where('started_by', $this->session->userdata('user_id'));
		$this->db->where('finished_at !=', 0);
		$this->db->where('id', $jobId);

		$query = $this->db->update('jobs', array('seen' => 1));

		return $this->db->affected_rows() > 0;
	}

	/**
	 * Gets a specific job.
	 *
	 * @param string $experimentId
	 * @return array The job data
	 */
	public function getByExperimentId($experimentId) {
		$this->db->select('jobs.*, users.username, users.firstname, users.lastname');
		$this->db->join('users', 'jobs.started_by = users.id', 'left');
		$this->db->where('experiment_id', $experimentId);

		$query = $this->db->get('jobs');

		return $query->row_array();
	}

	/**
	 * Gets a job by its id.
	 */
	public function getById($job_id) {
		$job = $this->db->get_where('jobs', array('id' => $job_id))->row_array();

		$job = addStatus($job);
		
		return $job;
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
		$this->db->order_by('created_at DESC');

		if (!empty($projectId)) {
			$this->db->where('project_id', $projectId);
		}

		$jobs = $this->db->get('jobs')->result_array();
		return array_map(array($this, "addStatus"), $jobs);
	}

	/**
	 * Gets a list of results that the owner has not yet seen.
	 *
	 * @return array
	 */
	public function getUnseenResults() {
		$this->db->select('jobs.*, experiments.name AS experiment_name, projects.id AS project_id, projects.name AS project_name');
		$this->db->join('experiments', 'experiments.id = jobs.experiment_id', 'left');
		$this->db->join('projects', 'projects.id = experiments.project_id', 'left');

		$this->db->where('started_by', $this->session->userdata('user_id'));
		$this->db->where('finished_at !=', 0);
		$this->db->where('seen', 0);

		$this->db->order_by('started_at ASC');

		return $this->db->get('jobs')->result_array();
	}

	/**
	 *
	 */
	public function getUnnotifiedResults() {
		$this->db->where('notified', 0);
		return $this->getUnseenResults();
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

	/**
	 * Counts all unseen jobs.
	 *
	 * @return integer
	 */
	public function countUnseenResults() {
		$this->db->where('started_by', $this->session->userdata('user_id'));
		return $this->db->where(array('finished_at !=' => 0, 'seen' => 0))->count_all_results('jobs');
	}

	/**
	 * Counts all running jobs.
	 *
	 * @return integer
	 */
	public function countRunning() {
		$this->db->where('started_by', $this->session->userdata('user_id'));
		return $this->db->where(array('started_at !=' => 0, 'finished_at' => 0))->count_all_results('jobs');
	}

	/**
	 * Counts all pending jobs.
	 *
	 * @return integer
	 */
	public function countPending() {
		$this->db->where('started_by', $this->session->userdata('user_id'));
		return $this->db->where('started_at', 0)->count_all_results('jobs');
	}
}

/* End of file job.php */
/* Location: ./application/controllers/job.php */
