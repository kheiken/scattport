<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Karsten Heiken, Eike Foken
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
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Server extends CI_Model {

	/**
	 * Creates a new server.
	 *
	 * @param array $data The server informations
	 * @return boolean Returns TRUE if the insert was successful.
	 */
	public function create($data) {
		return $this->db->insert('servers', $data);
	}

	/**
	 * Deletes a server.
	 *
	 * @param string $serverId
	 * @return boolean Returns TRUE if the deletion was successful.
	 */
	public function delete($serverId) {
		$this->db->delete('servers', array('id' => $serverId));
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Gets a list of all available servers.
	 *
	 * @return array The list of all available servers
	 */
	public function getAll() {
		$servers = $this->db->get('servers')->result_array();
		return array_map(function($var) {
			$var['available'] = time_diff($var['last_update'], mysql_now()) < 120 ? true : false;
			return $var;
		}, $servers);
	}

	/**
	 * Gets a list of servers that could handle another job.
	 *
	 * @return array The list of servers that could handle another job
	 */
	public function getIdle() {
		return $this->db->get_where('servers', 'workload <= 2')->result_array();
	}

	/**
	 * Updates a server.
	 *
	 * @param type $secret The server's secret for basic authentication
	 * @param type $workload The server's workload
	 * @return boolean Returns TRUE if the update was successful.
	 */
	public function update($serverId, $data) {
		$this->db->where('id', $serverId)->update('servers', $data);
		return $this->db->affected_rows() > 0;
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
		$server = $this->db->get_where('servers', array('id' => $serverId))->row();
		if (is_object($server)) {
			$server->uptimestring = prettyTime($server->uptime);
			$server->lastheartbeat = prettyTime(time_diff($server->last_update, mysql_now()));
		}
		return $server;
	}
}

/* End of file server.php */
/* Location: ./application/models/server.php */
