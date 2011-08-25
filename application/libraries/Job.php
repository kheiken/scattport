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
		$this->CI->load->model('trial');

		log_message('debug', "Trial Class Initialized");
	}

	/**
	 *
	 * @param string $trialId
	 * @return boolean Returns TRUE on success.
	 */
	public function createConfigs($trialId) {
		$trial = $this->CI->trial->getById($trialId);
		$path = FCPATH . 'uploads/' . $trial['project_id'] . '/' . $trial['id'] . '/';

		$program = $this->CI->program->getById($trial['program_id']);

		$handler = fopen($path . 'trial.conf', "w");

		$parameters = $this->CI->trial->getParameters($trialId);
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