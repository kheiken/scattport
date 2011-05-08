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
	 * Update a server.
	 *	 *
	 * @param type $secret The server's secret for basic authentication.
	 * @param type $workload The server's workload.
	 */
	public function update($server_id, $data) {
		return $this->db->update('servers', $data);
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
