<?php defined('BASEPATH') || exit('No direct script access allowed');
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
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Server extends CI_Model {

	/**
	 * Create a new server.
	 *
	 * @param array $data the server informations
	 * @return bool was the insert successful
	 */
	public function create($data) {
		return $this->db->insert('servers', $data);
	}

	/**
	 * Delete a server.
	 *
	 * @param string $server_id
	 * @return bool was the deletion successful
	 */
	public function delete($server_id) {
		return $this->db->delete('servers', array('id' => $server_id));
	}

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
		return $this->db->where('id', $server_id)->update('servers', $data);
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

	/**
	 *
	 * @param string $secret
	 */
	public function getBySecret($secret) {
		return $this->db->get_where('servers', array('secret' => $secret))->row();
	}

	/**
	 *
	 * @param string $serverId
	 */
	public function getById($serverId) {
		$this->load->helper('date');
		$server = $this->db->get_where('servers', array('id' => $serverId))->row();
		$server->uptimestring = secondsToString($server->uptime);
		$server->lastheartbeat = sprintf(_('%s ago'), 
				secondsToString(time_diff($server->last_update, mysql_now())));
		return $server;
	}
}

/* End of file server.php */
/* Location: ./application/models/server.php */
