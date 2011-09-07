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
 * Experiments are used to store different variations of the same project.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Experiment extends CI_Model {

	/**
	 * Create a new experiment.
	 *
	 * @param array $data The data of the new experiment
	 * @return boolean Was the insert successful?
	 */
	public function create($data) {
		if (!isset($data['project_id'])) {
			return false;
		}

		do { // generate unique hash
			$data['id'] = random_hash();
		} while ($this->db->where('id', $data['id'])->from('experiments')->count_all_results() > 0);

		if ($this->db->insert('experiments', $data)) {
			return $data['id'];
		} else {
			return false;
		}
	}

	/**
	 * Deletes a experiment.
	 *
	 * @param string $experimentId The experiments ID to delete
	 * @return boolean Was the deletion successful?
	 */
	public function delete($experimentId) {
		return $this->db->delete('experiments', array('id' => $experimentId));
	}

	/**
	 * Adds a parameter for a specific experiment.
	 *
	 * @param array $data
	 * @param string $experimentId
	 * @return boolean Returns TRUE if the parameter was added successfully.
	 */
	public function addParameter($data, $experimentId) {
		if (!isset($data['parameter_id'])) {
			return false;
		}

		$experiment = $this->get($experimentId);
		$parameter = $this->db->get_where('parameters', array('id' => $data['parameter_id']))->row_array();

		if (isset($experiment['id']) && $experiment['program_id'] == $parameter['program_id']) {
			$data['experiment_id'] = $experimentId;
			$this->db->insert('experiments_parameters', $data);
		}
		return $this->db->affected_rows() == 1 ? $experimentId : false;
	}

	/**
	 * Gets all parameters for the specified experiment.
	 *
	 * @param string $experimentId
	 * @return array
	 */
	public function getParameters($experimentId) {
		$this->db->select('experiments_parameters.*, parameters.name AS `name`, parameters.type AS `type`');
		$this->db->join('parameters', 'experiments_parameters.parameter_id = parameters.id', 'left');
		$this->db->where('experiment_id', $experimentId);

		$query = $this->db->get('experiments_parameters');

		return $query->num_rows() > 0 ? $query->result_array() : false;
	}

	/**
	 * Gets an experiment by ID.
	 *
	 * @param string $experimentId The experiment to get
	 * @return array The experiment
	 */
	public function get($experimentId) {
		$query = $this->db->get_where('experiments', array('id' => $experimentId));
		return $query->row_array();
	}

	/**
	 * Gets an experiment by ID.
	 *
	 * @param string $experimentId The experiment to get
	 * @return array The experiment
	 */
	public function getById($experimentId) {
		return $this->db->get_where('experiments', array('id' => $experimentId))->row_array();
	}

	/**
	 * Gets a experiment by its project ID.
	 *
	 * @param string $projectId
	 * @return array
	 */
	public function getByProjectId($projectId) {
		$query = $this->db->get_where('experiments', array('project_id' => $projectId));
		return $query->result_array();
	}

	/**
	 * Search for a specific experiment and return a list of possible results.
	 *
	 * @param string $needle The needle to look for in the haystack.
	 */
	public function search($project, $needle) {
		$query = $this->db->where('project_id', $project)
				->like('name', $needle)->get('experiments');
		$results = $query->result_array();

		return $results;
	}
}