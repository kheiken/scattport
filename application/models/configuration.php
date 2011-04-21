<?php

/**
 * Configurations are used to store different variations of the same project.
 * 
 * @author Karsten Heiken, karsten@disposed.de
 */
class Configuration extends CI_Model {

	/**
	 * Get a configuration by id.
	 *
	 * @param type $configuration_id The configuration to get.
	 * @return array The configuration
	 */
	public function get($configuration_id) {
		$query = $this->db->get_where('configurations', array('id' => $configuration_id));

		return $query->row_array();
	}

	/**
	 * Search for a specific configuration and return a list of possible results.
	 *
	 * @param string $needle The needle to look for in the haystack.
	 */
	public function search($project, $needle) {
		$query = $this->db->where('project_id', $project)
				->like('name', $needle)->get('configurations');
		$results = $query->result_array();

		return $results;
	}
}