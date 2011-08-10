<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Extends CI's session class.
 *
 * @author Eike Foken
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
	 * Creates a new session.
	 *
	 * @see CI_Session::sess_create()
	 */
	public function sess_create() {
		$this->userdata = array(
        	'session_id' => $this->generateHash(),
        	'ip_address' => $this->CI->input->ip_address(),
        	'user_agent' => substr($this->CI->input->user_agent(), 0, 50),
        	'last_activity' => $this->now
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

		$oldSessionID = $this->userdata['session_id'];
		$newSessionID = $this->generateHash();

		$this->userdata['session_id'] = $newSessionID;
		$this->userdata['last_activity'] = $this->now;

		$cookieData = null;

		// update the DB if needed
		if ($this->sess_use_database === true) {
			// set cookie explicitly to only have our session data
			$cookieData = array();
			foreach (array('session_id', 'user_id', 'ip_address', 'user_agent', 'last_activity') as $val) {
				$cookieData[$val] = $this->userdata[$val];
			}

			$this->CI->db->update($this->sess_table_name, array('last_activity' => $this->now, 'user_id' => $this->userdata['user_id'], 'session_id' => $newSessionID), array('session_id' => $oldSessionID));

			// update users table if user is logged in
			if (array_key_exists('user_id', $this->userdata) && $this->userdata['user_id'] > 0) {
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
