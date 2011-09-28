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
 * Configurations are used to store different variations of the same project.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Project extends CI_Model {

	/**
	 * Add a short and medium length description to one project.
	 *
	 * @param mixed $project
	 */
	private function _addShortName($project) {
		$project['shortname'] = character_limiter($project['name'], 20);
		$project['mediumname'] = character_limiter($project['name'], 35);
		return $project;
	}

	/**
	 * Add a short and medium length description to an array of projects.
	 *
	 * @param mixed $project
	 */
	private function _addShortNames($project) {
		return array_map(function($var) {
			$var['shortname'] = character_limiter($var['name'], 20);
			$var['mediumname'] = character_limiter($var['name'], 35);
			return $var;
		}, $project);
	}

	/**
	 * Get the user's own projects.
	 *
	 * @return array The user's projects.
	 */
	public function getOwn() {
		$this->db->select('projects.*, COUNT(shares.user_id) AS shares');
		$this->db->join('shares', 'shares.project_id = projects.id', 'left');
		$this->db->group_by('projects.id');
		$this->db->where(array('owner' => $this->session->userdata('user_id')));

		$query = $this->db->order_by('last_access DESC')->get('projects');

		return $this->_addShortNames($query->result_array());
	}

	/**
	 * Get projects the user was invited to use.
	 *
	 * @return array The shared projects.
	 */
	public function getShared() {
		$this->load->library('session');
		$this->db->select('*')->from('shares')->order_by('last_access DESC')->where(array('user_id' => $this->session->userdata('user_id')));
		$this->db->join('projects', 'projects.id = shares.project_id');
		$query = $this->db->get();

		return $this->_addShortNames($query->result_array());
	}

	/**
	 * Gets all publicly available projects.
	 *
	 * @return array All public projects
	 */
	public function getPublic() {
		$query = $this->db->order_by('name ASC')->get_where('projects', array('public' => 1));
		return $this->_addShortNames($query->result_array());
	}

	/**
	 * Gets all accessible projects for a user.
	 *
	 * @param string $userId
	 * @return array All accessible projects
	 */
	public function getAccessible($userId = '') {
		if (empty($userId)) {
			$userId = $this->session->userdata('user_id');
		}

		$shares = array();
		$query = $this->db->get_where('shares', array('user_id' => $userId));
		foreach ($query->result_array() as $share) {
			$shares[] = $share['project_id'];
		}

		$this->db->where('public', 1);
		$this->db->or_where('owner', $userId);

		if (count($shares) > 0) {
			$this->db->or_where_in('projects.id', $shares);
		}

		return $this->getAll();
	}

	/**
	 * Get a specific project from the database.
	 *
	 * @param type $project_id The project to get.
	 */
	public function getById($projectId) {
		$result = $this->db->get_where('projects', array('id' => $projectId))->row_array();

		if ($result) {
			return $this->_addShortName($result);
		} else {
			return false;
		}
	}

	/**
	 * Get all projects from the database.
	 */
	public function getAll() {
		$result = $this->db->select('projects.*, users.username, users.firstname, users.lastname')
				->join('users', 'users.id = projects.owner', 'left')
				->get('projects')->result_array();

		return $this->_addShortNames($result);
	}

	/**
	 * Counts all projects.
	 *
	 * @param string $userId
	 * @return integer
	 */
	public function count($userId = false) {
		if ($userId) {
			$this->db->where('owner', $userId);
		}
		return $this->db->count_all_results('projects');
	}

	/**
	 * Counts all experiments for the specified project.
	 *
	 * @param string $projectId
	 * @return integer
	 */
	public function countExperiments($projectId) {
		return $this->db->get_where('experiments', array('project_id' => $projectId))->num_rows();
	}

	/**
	 * Search for a specific project and return a list of possible results.
	 *
	 * @param string $needle The needle to look for in the haystack
	 * @param boolean $searchAll Search all projects
	 * @return array Returns an array of all found projects.
	 */
	public function search($needle, $searchAll = false) {
		if ($searchAll) {
			$query = $this->db->like('name', $needle)->get('projects');
		} else {
			$this->db->select('projects.*')->from('projects');
			$this->db->join('shares', 'shares.project_id = projects.id', 'left');

			$this->db->where("(`shares`.`user_id` = " . $this->db->escape($this->session->userdata('user_id'))
					. " OR `projects`.`owner` = " . $this->db->escape($this->session->userdata('user_id'))
					. " OR `projects`.`public` = 1)");

			$this->db->like('projects.name', $needle);

			$query = $this->db->get();
		}

		return $this->_addShortNames($query->result_array());
	}

	/**
	 * Creates a new project.
	 *
	 * @param array $data An array with "name" and "description"
	 * @return mixed Returns the ID of the created project, or FALSE if the
	 *                insert was unsuccessful.
	 */
	public function create($data) {
		$data['owner'] = $this->session->userdata('user_id');
		$data['created'] = mysql_now();
		$data['last_access'] = mysql_now();

		do { // generate unique hash
			$data['id'] = random_string('sha1', 40);
		} while ($this->db->where('id', $data['id'])->from('projects')->count_all_results() > 0);

		if ($this->db->insert('projects', $data)) {
			return $data['id'];
		} else {
			return false;
		}
	}

	/**
	 * Updates a project.
	 *
	 * @param integer $projectId The ID of the project to update
	 * @param array $data Array with data to update
	 */
	public function update($data, $projectId) {
		return $this->db->where('id', $projectId)->update('projects', $data);
	}

	/**
	 * Updates the last access of a project.
	 *
	 * @param string $projectId The ID of the project to update
	 * @return boolean Returns TRUE on success.
	 */
	public function updateLastAccess($projectId) {
		$this->db->where('id', $projectId)->update('projects', array('last_access' => mysql_now()));
		return $this->db->affected_rows() == 0;
	}

	/**
	 * Deletes a project.
	 *
	 * There is no security check in here to verify if the user has the
	 * rights to do so. This needs to be done in the controller!
	 *
	 * @param integer $projectId The ID of the project to delete
	 */
	public function delete($projectId) {
		$this->db->delete('shares', array('project_id' => $projectId));

		$experiments = $this->db->get_where('experiments', array('project_id' => $projectId))->result_array();
		foreach ($experiments as $experiment) {
			$this->db->delete('experiments_parameters', array('experiment_id' => $experiment['id']));
			$this->db->delete('jobs', array('experiment_id' => $experiment['id']));
			$this->db->delete('experiments', array('id' => $experiment['id']));
		}

		$this->db->delete('projects', array('id' => $projectId));

		return $this->db->affected_rows() > 0;
	}

}

/* End of file project.php */
/* Location: ./application/models/project.php */
