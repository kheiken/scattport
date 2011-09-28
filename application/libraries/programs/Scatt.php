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
 * @subpackage Libraries
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

		log_message('debug', "ScaTT Class Initialized");
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
		
		if (!file_exists($path . 'default.obj')) {
			@copy(FCPATH . 'uploads/' . $experiment['project_id'] . '/defaultmodel.obj', $path . 'default.obj');
		}

		$data['parameters'] = $this->CI->experiment->getParameters($experimentId);

		@fwrite($handler, $this->CI->parser->parse_string($this->program['config_template'], $data, true));
		@fclose($handler);

		return true;
	}

	/**
	 *
	 * @param string $experimentId
	 */
	public function _getResults($experimentId) {
		$this->CI->load->helper('array');

		$experiment = $this->CI->experiment->getById($experimentId);

		$path = FCPATH . 'uploads/' . $experiment['project_id'] . '/' . $experiment['id'] . '/';

		if (!file_exists($path . 'default.out')) {
			return array();
		}

		$handler = fopen($path . 'default.out', "r");

		$results = array();

		while (($line = fgets($handler)) !== false) {
			$values = array();
			$i = 0;

			foreach (preg_split("/\s+/", $line) as $value) {
				if ($value != '') {
					$values[] = trim($value);
				}
				$i++;
			}
			$results[] = $values;
		}

		return $results;
	}

}

/* End of file Scatt.php */
/* Location: ./application/libraries/programs/Scatt.php */
