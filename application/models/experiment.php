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
 * Experiments are used to store different variations of the same project.
 *
 * @package ScattPort
 * @subpackage Models
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Experiment extends CI_Model {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Creates a new experiment.
	 *
	 * @param array $data The data of the new experiment
	 * @return boolean Returns the ID of the created experiment, or FALSE if the
	 *                  insert was unsuccessful.
	 */
	public function create($data) {
		if (!isset($data['project_id'])) {
			return false;
		}

		do { // generate unique hash
			$data['id'] = random_string('sha1', 40);
		} while ($this->db->where('id', $data['id'])->from('experiments')->count_all_results() > 0);

		if ($this->db->insert('experiments', $data)) {
			return $data['id'];
		} else {
			return false;
		}
	}

	/**
	 * Updates an experiment.
	 *
	 * @param array $data
	 * @param string $experimentId
	 * @return boolean Returns TRUE if the update was successful.
	 */
	public function update($data, $experimentId) {
		$this->db->update('experiments', $data, array('id' => $experimentId));
		return $this->db->affected_rows() == 1;
	}

	/**
	 * Deletes an experiment.
	 *
	 * @param string $experimentId The experiments ID to delete
	 * @return boolean Returns TRUE if the deletion was successful.
	 */
	public function delete($experimentId) {
		$this->db->delete('jobs', array('experiment_id' => $experimentId));
		$this->db->delete('experiments_parameters', array('experiment_id' => $experimentId));
		$this->db->delete('experiments', array('id' => $experimentId));

		return $this->db->affected_rows() > 1;
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

		$experiment = $this->getById($experimentId);
		$parameter = $this->db->get_where('parameters', array('id' => $data['parameter_id']))->row_array();

		if (isset($experiment['id']) && $experiment['program_id'] == $parameter['program_id']) {
			$data['experiment_id'] = $experimentId;
			$this->db->insert('experiments_parameters', $data);
		}
		return $this->db->affected_rows() == 1 ? $experimentId : false;
	}

	/**
	 * Updates a specific parameter for the specified experiment.
	 * 
	 * @param string $value
	 * @param string $experimentId
	 * @param string $parameterId
	 * @return boolean
	 */
	public function updateParameter($value, $experimentId, $parameterId) {
		if (!empty($value)) {
			// replace into, to overwrite existing parameters
			$this->db->query("REPLACE INTO `experiments_parameters`"
					. " (`experiment_id`, `parameter_id`, `value`) VALUES"
					. " ('{$experimentId}', '{$parameterId}', '{$value}')");
		} else {
			// delete table entry, if value is empty
			$this->db->where('experiment_id', $experimentId);
			$this->db->where('parameter_id', $parameterId);
			$this->db->delete('experiments_parameters');
		}

		return true;
	}

	/**
	 * Gets all parameters for the specified experiment.
	 *
	 * @param string $experimentId
	 * @return array
	 */
	public function getParameters($experimentId) {
		$programId = $this->db->get_where('experiments', array('id' => $experimentId))->row()->program_id;

		$query = $this->db->query("SELECT `experiments_parameters`.*, `parameters`.`readable`,"
				. " `parameters`.`name`, `parameters`.`type`, `parameters`.`unit`,"
				. " `parameters`.`description`, `parameters`.`id` AS parameter_id"
				. " FROM `parameters` LEFT JOIN `experiments_parameters`"
				. " ON (`experiments_parameters`.`parameter_id` = `parameters`.`id`"
				. " AND `experiment_id` = '{$experimentId}') WHERE `program_id` = '{$programId}' ORDER BY `sort_number` ASC");

		return $query->num_rows() > 0 ? $query->result_array() : false;
	}

	/**
	 * Gets an experiment by ID (kept for backwards compatibility).
	 *
	 * @deprecated 14-09-2011
	 * @see Experiment::getById()
	 * @param string $experimentId The experiment to get
	 * @return array The experiment
	 */
	public function get($experimentId) {
		return $this->getById($experimentId);
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
		$this->db->select('experiments.*, jobs.id AS job_id, jobs.started_at, jobs.finished_at, jobs.progress');
		$this->db->join('jobs', 'jobs.experiment_id = experiments.id', 'left');

		$query = $this->db->get_where('experiments', array('project_id' => $projectId));

		return $query->result_array();
	}

	/**
	 * Counts all experiments.
	 *
	 * @param string $userId
	 * @return integer
	 */
	public function count($userId = false) {
		if ($userId) {
			$this->db->where('creator_id', $userId);
		}
		return $this->db->count_all_results('experiments');
	}

	/**
	 * Search for a specific experiment and return a list of possible results.
	 *
	 * @param string $needle The needle to look for in the haystack
	 * @param string $projectId Search only in a specific project
	 * @param boolean $searchAll Search all experiments
	 * @return array Returns an array of all found experiments.
	 */
	public function search($needle, $projectId = false, $searchAll = false) {
		if ($projectId) {
			$this->db->where('projects.id', $projectId);
		}

		$this->db->select('experiments.*')->from('experiments');

		if (!$searchAll) {
			$this->db->join('projects', 'projects.id = experiments.project_id', 'left');
			$this->db->join('shares', 'shares.project_id = projects.id', 'left');

			$this->db->where("(`shares`.`user_id` = " . $this->db->escape($this->session->userdata('user_id'))
					. " OR `projects`.`owner` = " . $this->db->escape($this->session->userdata('user_id'))
					. " OR `projects`.`public` = 1)");
		}

		$this->db->like('experiments.name', $needle);
		$this->db->or_like('experiments.id', $needle);

		$query = $this->db->get();

		return $query->result_array();
	}
}

/* End of file group.php */
/* Location: ./application/controllers/group.php */
