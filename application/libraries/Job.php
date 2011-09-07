<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Job {

	private $CI;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->CI =& get_instance();

		// load models
		$this->CI->load->model('program');
		$this->CI->load->model('experiment');

		log_message('debug', "Job Class Initialized");
	}

	/**
	 *
	 * @param string $experimentId
	 * @return boolean Returns TRUE on success.
	 */
	public function createConfigs($experimentId) {
		$experiment = $this->CI->experiment->getById($experimentId);
		$path = FCPATH . 'uploads/' . $experiment['project_id'] . '/' . $experiment['id'] . '/';

		$program = $this->CI->program->getById($experiment['program_id']);

		$handler = fopen($path . 'default.calc', "w");

		$parameters = $this->CI->experiment->getParameters($experimentId);
		foreach ($parameters as $param) {
			$line = str_replace("{type}", $param['type'], $program['input_line']);
			$line = str_replace("{param}", $param['name'], $line);
			$line = str_replace("{value}", $param['value'], $line);
			fwrite($handler, $line);
		}
		fclose($handler);

		return true;
	}
}