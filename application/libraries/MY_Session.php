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
 * Extends CI's session class.
 *
 * @package ScattPort
 * @subpackage Libraries
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class MY_Session extends CI_Session {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Generates a random and unique session ID.
	 *
	 * @return string
	 */
	private function generateHash() {
		return sha1(uniqid(microtime() . $this->CI->input->ip_address(), true));
	}

	/**
	 * Writes the session data.
	 *
	 * @see CI_Session::sess_write()
	 */
	public function sess_write() {
		// are we saving custom data to the DB? If not, all we do is update the cookie
		if ($this->sess_use_database === false) {
			$this->_set_cookie();
			return;
		}

		// set the custom userdata, the session data we will set in a second
		$customUserdata = $this->userdata;
		$cookieUserdata = array();

		// before continuing, we need to determine if there is any custom data to deal with.
		foreach (array('session_id', 'user_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
			unset($customUserdata[$val]);
			$cookieUserdata[$val] = $this->userdata[$val];
		}

		// did we find any custom data? If not, we turn the empty array into a string
		if (count($customUserdata) === 0) {
			$customUserdata = '';
		} else {
			// serialize the custom data array so we can store it
			$customUserdata = $this->_serialize($customUserdata);
		}

		// run the update query
		$this->CI->db->where('session_id', $this->userdata['session_id']);
		$this->CI->db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_id' => $this->userdata['user_id'], 'user_data' => $customUserdata));

		// write the cookie.
		$this->_set_cookie($cookieUserdata);
	}

	/**
	 * Creates a new session.
	 *
	 * @see CI_Session::sess_create()
	 */
	public function sess_create() {
		$this->userdata = array(
	        	'session_id' => $this->generateHash(),
	        	'ip_address' => $this->CI->input->ip_address(),
	        	'user_agent' => substr($this->CI->input->user_agent(), 0, 120),
	        	'last_activity' => $this->now,
	        	'user_id' => null,
		);

		// save data to the DB if needed
		if ($this->sess_use_database === true) {
			$this->CI->db->insert($this->sess_table_name, $this->userdata);
		}

		// write the cookie
		$this->_set_cookie();
	}

	/**
	 * Updates an existing session.
	 *
	 * @see CI_Session::sess_update()
	 */
	public function sess_update() {
		// we only update the session every five minutes by default
		if ($this->userdata['last_activity'] + $this->sess_time_to_update >= $this->now) {
			return;
		}

		$oldSessionId = $this->userdata['session_id'];

		// don't change the session ID on ajax requests
		if ($this->CI->input->is_ajax_request()) {
			$newSessionId = $oldSessionId;
		} else {
			$newSessionId = $this->generateHash();
		}

		// update the session data in the session data array
		$this->userdata['session_id'] = $newSessionId;
		$this->userdata['last_activity'] = $this->now;
		$this->userdata['user_id'] = array_key_exists('user_id', $this->userdata) ? $this->userdata['user_id'] : null;

		$cookieData = null;

		// update the DB if needed
		if ($this->sess_use_database === true) {
			// set cookie explicitly to only have our session data
			$cookieData = array();
			foreach (array('session_id', 'user_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
				$cookieData[$val] = $this->userdata[$val];
			}

			$this->CI->db->update($this->sess_table_name, array('last_activity' => $this->now, 'user_id' => $this->userdata['user_id'], 'session_id' => $newSessionId), array('session_id' => $oldSessionId));

			// update users table if user is logged in
			if (array_key_exists('user_id', $this->userdata) && !is_null($this->userdata['user_id'])) {
				$this->CI->db->update('users', array('last_activity' => $this->now), array('id' => $this->userdata['user_id']));
			}
		}

		// write the cookie
		$this->_set_cookie($cookieData);
	}

	/**
	 * Destroys an existing session.
	 *
	 * @see CI_Session::sess_destroy()
	 */
	public function sess_destroy() {
		parent::sess_destroy();
		$this->userdata = array();
	}
}

/* End of file MY_Session.php */
/* Location: ./application/libraries/MY_Session.php */
