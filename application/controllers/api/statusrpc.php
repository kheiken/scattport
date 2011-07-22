<?php

/**
 * Simple RPC class to handle requests from calculation servers.
 *
 * @author Karsten Heiken, karsten@disposed.de
 */
class Statusrpc extends CI_Controller {
	
	/**
	 * Update the state of a given job.
	 * 
	 * Because we do not want any access from servers we do not trust,
	 * we need a special secret to authenticate the servers.
	 * 
	 * @param type $secret The secret to authenticate the server.
	 * @param type $job_id The job id that is running on the server.
	 * @param type $state The state of the job.
	 */
	public function update_job($secret, $job_id, $progress) {
		$this->load->model('job');

		$query = $this->db->get_where('servers', array('secret' => $secret));

		// if we are in production mode, we do not want to tell the evil hacker
		// that he used a wrong secret. That just encourages him.
		if($query->num_rows() < 1 && ENVIRONMENT != 'production') {
			die("Unauthorized access.");
		}

		$this->job->update($job_id, $progress);
	}

	/**
	 * Update the workload of the server.
	 */
	public function update_workload($secret, $workload) {
		$this->load->model('server');
		$query = $this->db->get_where('servers', array('secret' => $secret));

		// if we are in production mode, we do not want to tell the evil hacker
		// that he used a wrong secret. That just encourages him.
		if($query->num_rows() < 1 && ENVIRONMENT != 'production') {
			die("Unauthorized access.");
		}

		$this->server->updateWorkload($secret, $workload);
	}
}