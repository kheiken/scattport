<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Eike Foken <kontakt@eikefoken.de>
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
* Model for shares.
*
* @author Eike Foken <kontakt@eikefoken.de>
*/
class Share extends CI_Model {

	/**
	* Calls the parent constructor.
	*/
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Gets a share by its primary key.
	 *
	 * @param string $projectId
	 * @param string $userId
	 * @return array
	 */
	public function get($projectId, $userId) {
		return $this->db->get_where('shares', array('project_id' => $projectId, 'user_id' => $userId))->row_array();
	}

	/**
	 * Gets all share for a specific project.
	 *
	 * @param string $projectId
	 * @return array
	 */
	public function getByProjectId($projectId) {
		$this->db->select('shares.*, users.username, users.firstname, users.lastname');
		$this->db->join('users', 'users.id = shares.user_id', 'left');

		return $this->db->get_where('shares', array('project_id' => $projectId))->result_array();
	}

	/**
	 * Gets all share for a specific user.
	 *
	 * @param string $userId
	 * @return array
	 */
	public function getByUserId($userId) {
		$this->db->select('shares.*, projects.name');
		$this->db->join('projects', 'projects.id = shares.project_id', 'left');

		return $this->db->get_where('shares', array('user_id' => $userId))->result_array();
	}

	/**
	 * Creates a share.
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function create($data) {
		if (!isset($data['project_id']) || !isset($data['user_id'])) {
			return false;
		}

		$this->db->query('REPLACE INTO `shares` (`project_id`, `user_id`, `can_edit`) VALUES ('
				. $this->db->escape($data['project_id']) . ', '
				. $this->db->escape($data['user_id']) . ', '
				. $this->db->escape($data['can_edit']) . ')');

		//$this->db->insert('shares', $data);
		return $this->db->affected_rows() == 1;
	}

	/**
	 * Updates a share.
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function update($data) {
		if (!isset($data['project_id']) || !isset($data['user_id'])) {
			return false;
		}

		$this->db->update('shares', $data, array('project_id' => $data['project_id'], 'user_id' => $data['user_id']));
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Deletes a share.
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function delete($data) {
		if (!isset($data['project_id']) || !isset($data['user_id'])) {
			return false;
		}

		$this->db->delete('shares', array('project_id' => $data['project_id'], 'user_id' => $data['user_id']));
		return $this->db->affected_rows() > 0;
	}
}

/* End of file share.php */
/* Location: ./application/controllers/share.php */
