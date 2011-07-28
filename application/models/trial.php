<?php

/**
 * Trials are used to store different variations of the same project.
 * 
 * @author Karsten Heiken, karsten@disposed.de
 */
class Trial extends CI_Model {

	/**
	 * Create a new trial.
	 *
	 * @param array $data the data of the new trial
	 * @return bool was the insert successful
	 */
	public function create($data) {
		return $this->db->insert('trials', $data);
	}

	/**
	 * Delete a trial.
	 * @param string the trial id to delete
	 * @return bool was the deletion successful
	 */
	public function delete($trial_id) {
		return $this->db->delete('trials', array('id' => $trial_id));
	}

	/**
	 * Get a trial by id.
	 *
	 * @param type $trial_id The trial to get.
	 * @return array The trial
	 */
	public function get($trial_id) {
		$query = $this->db->get_where('trials', array('id' => $trial_id));

		return $query->row_array();
	}

		/**
	 * Get a trial by its project id.
	 *
	 * @param type $trial_id The trials to get.
	 * @return array The trial
	 */
	public function getByProjectId($project_id) {
		$query = $this->db->get_where('trials', array('project_id' => $project_id));

		return $query->result_array();
	}

	/**
	 * Search for a specific trial and return a list of possible results.
	 *
	 * @param string $needle The needle to look for in the haystack.
	 */
	public function search($project, $needle) {
		$query = $this->db->where('project_id', $project)
				->like('name', $needle)->get('trials');
		$results = $query->result_array();

		return $results;
	}
}