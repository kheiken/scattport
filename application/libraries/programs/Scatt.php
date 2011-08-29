<?php

/**
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Scatt extends Program_runner {

	/**
	 *
	 * @param unknown_type $params
	 */
	public function __construct($params) {
		$this->CI =& get_instance();
		extract($params);

		$this->CI->load->model('program');
		$this->program = $this->CI->program->getByDriver(strtolower(__CLASS__));

		log_message('debug', "Scatt Class Initialized");
	}

	/**
	 *
	 * @param unknown_type $trialId
	 */
	public function _createJob($trialId) {
		$this->CI->load->library('parser');

		$trial = $this->CI->trial->getById($trialId);

		$path = FCPATH . 'uploads/' . $trial['project_id'] . '/' . $trial['id'] . '/';
		$handler = fopen($path . 'default.calc', "w");

		$data['parameters'] = $this->CI->trial->getParameters($trialId);

		@fwrite($handler, $this->CI->parser->parse_string($this->program['config_template'], $data, true));
		@fclose($handler);

		return true;
	}
}