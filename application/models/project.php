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
	public function getOwn() {
		$query = $this->db->where(array('owner' => $this->session->user_data('id')))
				->order_by('lastaccess', 'desc')
				->get('projects');
		$projects = $query->result_array();
		$ownCount = $query->num_rows();

		$i = 0;
		foreach($projects as $project) {
			$ownProjects[$i]['id'] = '/projects/own/'.$project['id'];
			$ownProjects[$i]['cls'] = 'folder';
			$ownProjects[$i]['text'] = $project['name'];
			$ownProjects[$i]['leaf'] = true;
			$i++;
		}

		return $ownProjects;
	}

	/**
	 * Get projects the user was invited to use.
	 *
	 * @return array The shared projects.
	 */
	public function getShared() {
		$this->db->select('*')->from('shares')->order_by('lastaccess', 'desc')->where(array('user_id' => $this->session->user_data('id')));
		$this->db->join('projects', 'projects.id = shares.project_id');
		$query = $this->db->get();

		$projects = $query->result_array();
		$sharedCount = $query->num_rows();

		$i = 0;
		foreach($projects as $project) {
			$sharedProjects[$i]['id'] = '/projects/shared/'.$project['id'];
			$sharedProjects[$i]['cls'] = 'folder';
			$sharedProjects[$i]['text'] = $project['name'];
			$sharedProjects[$i]['leaf'] = true;
			$i++;
		}

		return $sharedProjects;
	}

	/**
	 * Get all publicly available projects.
	 *
	 * @return array All public projects.
	 */
	public function getPublic() {
		$query = $this->db->where(array('public' => '1'))
			->order_by('name', 'asc')
			->get('projects');
		$projects = $query->result_array();
		$publicCount = $query->num_rows();

		$i = 0;
		foreach($projects as $project) {
			$publicProjects[$i]['id'] = '/projects/public/'.$project['id'];
			$publicProjects[$i]['cls'] = 'folder';
			$publicProjects[$i]['text'] = $project['name'];
			$publicProjects[$i]['leaf'] = true;
			$i++;
		}

		return $publicProjects;
	}
	
	/**
	 * Get all available configurations from a specific project.
	 *
	 * @param array $project_id The project to get the configuration from.
	 */
	public function get_configurations($project_id) {
		$query = $this->db->get_where('configurations', array('project_id' => $project_id));

		$configurations = $query->result_array();
		$configuration_count = $query->num_rows();

		return array('count' => $configuration_count, 'configs' => $configurations);
	}

	/**
	 * Search for a specific project and return a list of possible results.
	 *
	 * @param type $needle The needle to look for in the haystack.
	 */
	public function search($needle) {

		// get matching projects that are public or belong directly to the user
		$query = $this->db->where('public','1')
				->or_where('owner', $this->session->userdata('id'))
				->like('name', $needle)->get('projects');
		$results = $query->result_array();

		// get matching projects that are shared to the user
		$this->db->select('*')->from('shares')
				->where(array('user_id' => $this->session->userdata('id')))
				->like('name', $needle);
		$this->db->join('projects', 'projects.id = shares.project_id');
		$query = $this->db->get();

		$own_results = $query->result_array();

		return array_merge($results, $own_results);
	}
}