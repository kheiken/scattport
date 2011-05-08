<?php

/**
 * @author Karsten Heiken, karsten@disposed.de
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
	 * Update the progress of a given job.
	 *
	 * @param string $job_id The job's id you want to update.
	 * @param integer $progress The progress of the job.
	 */
	public function update($job_id, $progress) {
		$finished_at = "";
		if($progress == 100)
			$finished_at = ", `finished_at`=NOW()";

		$this->db->query("UPDATE `jobs` SET `progress`=".
				$this->db->escape($progress).$finished_at. " WHERE `id`=".
				$this->db->escape($job_id));
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
}
