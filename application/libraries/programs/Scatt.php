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
	 * @param unknown_type $experimentId
	 */
	public function _createJob($experimentId) {
		$this->CI->load->library('parser');

		$experiment = $this->CI->experiment->getById($experimentId);

		$path = FCPATH . 'uploads/' . $experiment['project_id'] . '/' . $experiment['id'] . '/';
		$handler = fopen($path . 'default.calc', "w");

		$data['parameters'] = $this->CI->experiment->getParameters($experimentId);

		@fwrite($handler, $this->CI->parser->parse_string($this->program['config_template'], $data, true));
		@fclose($handler);

		return true;
	}
}