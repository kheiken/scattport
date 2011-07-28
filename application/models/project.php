<?php

/**
 * Configurations are used to store different variations of the same project.
 * 
 * @property CI_DB_active_record $db
 * @author Karsten Heiken, karsten@disposed.de
 */
class Project extends CI_Model {

	/**
	 * Get the user's own projects.
	 *
	 * @return array The user's projects.
	 */
	private function getOwn() {
		$query = $this->db->where(array('owner' => $this->session->userdata('user_id')))
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
	public function get($project_id) {
		$result = $this->db->get_where('projects', array('id' => $project_id))->row_array();
		$this->db->where('id', $project_id)->update('projects', array(
			'lastaccess' => mysql_now(),
		));
		
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