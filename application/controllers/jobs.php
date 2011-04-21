<?php

class Jobs extends CI_Controller {
	
	public function getOwn() {
		$query = $this->db->order_by('progress', 'desc')
			->get_where('jobs', array('started_by' => '215cd70f310ae6ae'));
		$count = $query->num_rows();
		$jobs = $query->result_array();
		
		for($i=0; $i<count($jobs); $i++) {
			$jobs[$i]['project_name'] = $this->db->select('name')->get_where('projects', array('id' => $jobs[$i]['project_id']))->row()->name;
			$progress = $jobs[$i]['progress'];
			
			switch($progress) {
				case -1:
					$progress = "Warte auf Start...";
					break;
				case 100:
					$progress = "Fertig";
					break;
				default:
					$progress = $progress . "%";
					break;
			}
			
			$jobs[$i]['progress'] = $progress;
		}		

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(
				array(
					'count' => $count,
					'jobs' => $jobs
				)
			));
	}
	
	public function listResultsNotSeen() {
		$query = $this->db->order_by('started_at', 'asc')
			->get_where('jobs', array('started_by' => '215cd70f310ae6ae', 'seen' => '0'));
		$count = $query->num_rows();
		$jobs = $query->result_array();
		
		for($i=0; $i<count($jobs); $i++) {
			$jobs[$i]['project_name'] = $this->db->select('name')->get_where('projects', array('id' => $jobs[$i]['project_id']))->row()->name;
		}		

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(
				array(
					'count' => $count,
					'jobs' => $jobs
				)
			));
	}
}