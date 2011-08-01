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
 * Configurations are used to store different variations of the same project.
 * 
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Project extends CI_Model {

	/**
	 * Get the user's own projects.
	 *
	 * @return array The user's projects.
	 */
	public function getOwn() {
		// TODO: Session: $query = $this->db->where(array('owner' => $this->session->userdata('user_id')))
		$query = $this->db->where(array('owner' => '215cd70f310ae6ae'))
				->order_by('lastaccess', 'desc')
				->get('projects');
		return $query->result_array();
	}

	/**
	 * Get projects the user was invited to use.
	 *
	 * @return array The shared projects.
	 */
	private function getShared() {
		$this->load->library('session');
		$this->db->select('*')->from('shares')->order_by('lastaccess', 'desc')->where(array('user_id' => $this->session->userdata('user_id')));
		$this->db->join('projects', 'projects.id = shares.project_id');
		$query = $this->db->get();

		return $query->result_array();
	}

	/**
	 * Get all publicly available projects.
	 *
	 * @return array All public projects.
	 */
	private function getPublic() {
		$query = $this->db->where(array('public' => '1'))
			->order_by('name', 'asc')
			->get('projects');
		
		return $query->result_array();
	}

	/**
	 * Get a specific project from the database.
	 *
	 * @param type $project_id The project to get.
	 */
	public function getById($project_id) {
		$result = $this->db->get_where('projects', array('id' => $project_id))->row_array();
		$this->db->where('id', $project_id)->update('projects', array(
			'lastaccess' => mysql_now(),
		));
		
		return $result;
	}
	
	/**
	 * Get all projects from the database.
	 */
	public function getAll() {
		$result = $this->db->select('projects.*, users.firstname AS `firstname`, users.lastname AS `lastname`')
				->join('users', 'users.id = projects.owner', 'left')
				->get('projects')->result_array();



		return $result;
	}

	/**
	 * Get all available configurations from a specific project.
	 *
	 * @param array $project_id The project to get the configuration from.
	 */
	public function getConfigurations($project_id) {
		$query = $this->db->get_where('configurations', array('project_id' => $project_id));

		$configurations = $query->result_array();

		return $configurations;
	}

	/**
	 * Search for a specific project and return a list of possible results.
	 *
	 * @param type $needle The needle to look for in the haystack.
	 */
	public function search($needle) {

		// get matching projects that are public
		$query = $this->db->query("SELECT * FROM `projects` WHERE `public`='1' AND `name` LIKE ".$this->db->escape('%'.$needle.'%'));
		$public_results = $query->result_array();

		// or belong directly to the user
		$query = $this->db->query("SELECT * FROM `projects` WHERE `owner`=".$this->db->escape($this->session->userdata('user_id'))
				." AND `name` LIKE ".$this->db->escape('%'.$needle.'%'));
		$own_results = $query->result_array();


		// get matching projects that are shared to the user
		$this->db->select('*')->from('shares')
				->where(array('user_id' => $this->session->userdata('user_id')))
				->like('name', $needle);
		$this->db->join('projects', 'projects.id = shares.project_id');
		$query = $this->db->get();

		$shared_results = $query->result_array();

		return array_merge($own_results, $shared_results);
	}

	/**
	 * Create a new project.
	 *
	 * @param array $data array with "name" and "description"
	 */
	public function create($data) {
		$this->load->helper(array('hash', 'date'));

		$data['owner'] = '215cd70f310ae6ae'; //$this->session->userdata('user_id');
		$data['created'] = mysql_now();
		$data['lastaccess'] = mysql_now();

		do {
			$data['id'] = random_hash();
		} while ($this->db->where('id', $data['id'])->from('projects')->count_all_results() > 0);


		if($this->db->insert('projects', $data))
			return $data['id'];
		else
			return FALSE;
	}

	/**
	 * Delete a project.
	 *
	 * There is no security check in here to verify if the user has the
	 * rights to do so. This needs to be done in the controller!
	 */
	public function delete($project_id) {
		return $this->db->delete('projects', array('id' => $project_id));
	}
}