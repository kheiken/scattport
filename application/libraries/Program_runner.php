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
 * @package ScattPort
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Program_runner {

	/**
	 * Constains the CI instance.
	 *
	 * @var object
	 */
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

/* End of file Program_runner.php */
/* Location: ./application/libraries/Program_runner.php */
