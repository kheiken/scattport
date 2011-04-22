<?php

/**
 * @author Karsten Heiken, karsten@disposed.de
 */
class Server extends CI_Model {

	/**
	 * Get a list of all available servers.
	 *
	 * @return array List of all available servers.
	 */
	public function getAll() {
		return $this->db->get('servers')->result_array();
	}

	/**
	 * Get a list of servers that could handle another job.
	 *
	 * @return array List of servers that could handle another job.
	 */
	public function getIdle() {
		return $this->db->get_where('servers', 'workload <= 2')->result_array();
	}

	/**
	 * Set a server's workload.
	 *
	 * In order to check if a server can handle another job we need to know
	 * the workload of every server.
	 *
	 * @param type $secret The server's secret for basic authentication.
	 * @param type $workload The server's workload.
	 */
	public function updateWorkload($secret, $workload) {
		$this->db->query("UPDATE `servers` SET `workload`=".$this->db->escape($workload)
				. ", `last_update`=NOW()"
				. " WHERE `secret`=".$this->db->escape($secret));
	}

	/**
	 * Get the best suiting server for a new job.
	 *
	 * @todo not yet verified.
	 */
	public function getBestServer() {
		return $this->db->limit(1)->order_by('last_update', 'desc')->
				get_where('servers', 'workload <= 2')->row_array();
	}
}
