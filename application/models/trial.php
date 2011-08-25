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
 * Trials are used to store different variations of the same project.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Trial extends CI_Model {

	/**
	 * Create a new trial.
	 *
	 * @param array $data the data of the new trial
	 * @return bool was the insert successful
	 */
	public function create($data) {
		if (!isset($data['project_id'])) {
			return false;
		}

		do { // generate unique hash
			$data['id'] = random_hash();
		} while ($this->db->where('id', $data['id'])->from('trials')->count_all_results() > 0);

		if ($this->db->insert('trials', $data)) {
			return $data['id'];
		} else {
			return false;
		}
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
	 * Adds a parameter for a specific trial.
	 *
	 * @param array $data
	 * @param string $trialId
	 * @return boolean Returns TRUE if the parameter was added successfully.
	 */
	public function addParameter($data, $trialId) {
		if (!isset($data['parameter_id'])) {
			return false;
		}

		$trial = $this->get($trialId);
		$parameter = $this->db->get_where('parameters', array('id' => $data['parameter_id']))->row_array();

		if (isset($trial['id']) && $trial['program_id'] == $parameter['program_id']) {
			$data['trial_id'] = $trialId;
			$this->db->insert('trials_parameters', $data);
		}
		return $this->db->affected_rows() == 1 ? $trialId : false;
	}

	/**
	 * Gets all parameters for the specified trial.
	 *
	 * @param string $trialId
	 * @return array
	 */
	public function getParameters($trialId) {
		$this->db->select('trials_parameters.*, parameters.name AS `name`, parameters.type AS `type`');
		$this->db->join('parameters', 'trials_parameters.parameter_id = parameters.id', 'left');
		$this->db->where('trial_id', $trialId);

		$query = $this->db->get('trials_parameters');

		return $query->num_rows() > 0 ? $query->result_array() : false;
	}

	/**
	 * Get a trial by id.
	 *
	 * @param string $trial_id The trial to get.
	 * @return array The trial
	 */
	public function get($trial_id) {
		$query = $this->db->get_where('trials', array('id' => $trial_id));

		return $query->row_array();
	}

	/**
	 * Gets a trial by ID.
	 *
	 * @param string $trialId The trial to get
	 * @return array The trial
	 */
	public function getById($trialId) {
		return $this->db->get_where('trials', array('id' => $trialId))->row_array();
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