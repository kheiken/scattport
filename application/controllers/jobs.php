<?php

class Jobs extends CI_Controller {

	/**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();

        // load language file
        $this->lang->load(strtolower($this->router->class));
    }

	/**
	 * Get jobs belonging to projects owned by the user.
	 */
	public function getOwn() {
		$query = $this->db->order_by('progress', 'desc')
			->get_where('jobs', array('started_by' => $this->session->userdata('user_id')));
		$count = $query->num_rows();
		$jobs = $query->result_array();
		
		for($i=0; $i<count($jobs); $i++) {
			$jobs[$i]['project_name'] = $this->db->select('name')->get_where('projects', array('id' => $jobs[$i]['project_id']))->row()->name;
			$progress = $jobs[$i]['progress'];
			
			switch($progress) {
				case -1:
					$progress = lang('waiting');
					break;
				case 100:
					$progress = lang('done');
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
			->get_where('jobs', array('started_by' => $this->session->userdata('user_id'), 'seen' => '0'));
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