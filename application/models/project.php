<?php

class Project extends CI_Model {
	
	public function getOwn() {
		$query = $this->db->where(array('owner' => '215cd70f310ae6ae'))
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
			$ownProjects[$i]['icon'] = "/ScattPort/assets/images/icons/document.png";
			$i++;
		}
		
		return $ownProjects;
	}
	
	public function getShared() {
		$this->db->select('*')->from('shares')->order_by('lastaccess', 'desc')->where(array('user_id' => '215cd70f310ae6ae'));
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
			$sharedProjects[$i]['icon'] = "/ScattPort/assets/images/icons/document.png";
			$i++;
		}
		
		return $sharedProjects;
	}
	
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
			$publicProjects[$i]['icon'] = "/ScattPort/assets/images/icons/document.png";
			$i++;
		}
		
		return $publicProjects;
	}
}