<?php

class Program_runner {

	private $CI;

	/**
	 * Constructor.
	 *
	 * @param array $params
	 */
	public function __construct($params = array()) {
		$defaults = array('program_driver' => 'scatt');

		foreach ($defaults as $key => $val) {
			if (isset($params[$key]) && $params[$key] !== "") {
				$defaults[$key] = $params[$key];
			}
		}

		extract($defaults);

		$this->CI =& get_instance();

		// load the requested programs library
		$this->CI->load->library('programs/' . $program_driver, array());
		// make calc to refer to current library
		$this->driver =& $this->CI->$program_driver;

		log_message('debug', "Program Runner Class Initialized and loaded. Driver used: " . $program_driver);
	}

	/**
	 * Creates a job from the specified experiment.
	 *
	 * @param string $experimentId
	 */
	public function createJob($experimentId) {
		if ($this->driver->_createJob($experimentId)) {
			$this->CI->load->model('job');
			$this->CI->job->create(array('experiment_id' => $experimentId, 'started_by' => $this->CI->session->userdata('user_id')));
		}
		return true;
	}

}
