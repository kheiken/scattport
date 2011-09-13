<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Messages.php - A class for writing feedback message information to the session
 *
 * Copyright (c) 2006 Vijay Mahrra & Sheikh Ahmed <webmaster@designbyfail.com>
 *
 * See the enclosed file COPYING for license information (LGPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @package ScattPort
 * @author Vijay Mahrra & Sheikh Ahmed <webmaster@designbyfail.com>
 * @author Eike Foken <kontakt@eikefoken>
 * @link http://www.designbyfail.com/
 * @version 1.0
 */
class Messages {

	/**
	 * Contains the CI instance.
	 *
	 * @var object
	 */
	private $CI;

	/**
	 * Contains the available message types.
	 *
	 * @var array
	 */
	private $types = array('success', 'error', 'notice');

	/**
	 * Constructor.
	 *
	 * @param array $params
	 */
	public function __construct($params = array()) {
		$this->CI =& get_instance();
		$this->CI->load->library('session');
		// check if theres already messages, if not, initialise the messages array in the session
		$messages = $this->CI->session->userdata('messages');
		if (empty($messages)) {
			$this->clear();
		}

		log_message('debug', "Messages Class Extension Initialized");
	}

	/**
	 * Clears all messages
	 */
	public function clear() {
		$messages = array();
		foreach ($this->types as $type) {
			$messages[$type] = array();
		}
		$this->CI->session->set_userdata('messages', $messages);
	}

	/**
	 * Adds a message (default type is 'notice').
	 */
	public function add($message, $type = 'notice') {
		$messages = $this->CI->session->userdata('messages');
		// handle PEAR errors gracefully
		if (is_a($message, 'PEAR_Error')) {
			$message = $message->getMessage();
			$type = 'error';
		} else if (!in_array($type, $this->types)) {
			// set the type to message if the user specified a type that's unknown
			$type = 'notice';
		}
		// don't repeat messages!
		if (!in_array($message, $messages[$type]) && is_string($message)) {
			$messages[$type][] = $message;
		}
		$messages = $this->CI->session->set_userdata('messages', $messages);
	}

	/**
	 * Returns messages of given type or all types, return false if none.
	 *
	 * @param string $type
	 * @return boolean|integer
	 */
	public function sum($type = null) {
		$messages = $this->CI->session->userdata('messages');
		if (!empty($type)) {
			$i = count($messages[$type]);
			return $i;
		}
		$i = 0;
		foreach ($this->types as $type) {
			$i += count($messages[$type]);
		}
		return $i > 0 ? $i : false;
	}

	/**
	 * Returns messages of given type or all types, return false if none, clearing stack.
	 *
	 * @param string $type
	 * @return mixed
	 */
	public function get($type = null) {
		$messages = $this->CI->session->userdata('messages');
		if (!empty($type)) {
			if (count($messages[$type]) == 0) {
				return false;
			}
			return $messages[$type];
		}
		// return false if there actually are no messages in the session
		$i = 0;
		foreach ($this->types as $type) {
			$i += count($messages[$type]);
		}
		if ($i == 0) {
			return false;
		}

		// order return by order of type array above
		// i.e. success, error, warning and then informational messages last
		foreach ($this->types as $type) {
			$return[$type] = $messages[$type];
		}
		$this->clear();
		return $return;
	}
}

/* End of file Messages.php */
/* Location: ./application/libraries/Messages.php */
