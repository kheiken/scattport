<?php

/**
 * @author Karsten Heiken, karsten@disposed.de
 */
class Job extends CI_Model {

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
}
