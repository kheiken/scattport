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
	 * Constructor.
	 *
	 * @param mixed $params
	 */
	public function __construct($params) {
		$this->CI =& get_instance();
		extract($params);

		$this->CI->load->model('program');
		$this->program = $this->CI->program->getByDriver(strtolower(__CLASS__));

		log_message('debug', "ScaTT Class Initialized");
	}

	/**
	 * Mapping function to generate correct float values
	 */
	private function formatNumbers($data) {
		if($data['type'] == 'float')
			$data['value'] = sprintf('%.6f', $data['value']);

		return $data;
	}

	/**
	 * Generate dafault.calc and param_dsm.dat files.
	 *
	 * @param string $experimentId
	 */
	public function _createJob($experimentId) {
		$this->CI->load->library('parser');

		$experiment = $this->CI->experiment->getById($experimentId);

		// create default.calc
		$path = FCPATH . 'uploads/' . $experiment['project_id'] . '/' . $experiment['id'] . '/';
		$handler = fopen($path . 'default.calc', "w");

		// if we didn't specify a model for this experiment, copy the default 
		// from the project.
		if (!file_exists($path . 'default.obj')) {
			@copy(FCPATH . 'uploads/' . $experiment['project_id'] . '/defaultmodel.obj', $path . 'default.obj');
		}

		// get the parameters for this experiment and convert the simple float values to %.6f
		$data['parameters'] = array_map(array($this, 'formatNumbers'), $this->CI->experiment->getParameters($experimentId));

		@fwrite($handler, $this->CI->parser->parse_string($this->program['config_template'], $data, true));
		@fclose($handler);

		// all param_dsm.dat files have the same header:
		$dsm_dat  = "&param_scat\n";
		$dsm_dat .= "filein_name='./default.obj',\n";
		$dsm_dat .= "tmat_file_name='./default.tma',\n";
		$dsm_dat .= "scat_diag_file_name='./default.out',\n";

		// sscatt requires a specific number format, so we need to convert the 
		// values
		foreach ($data['parameters'] as $par) {
			if ($par['type'] == 'float') {
				$par['value'] = number_format((double) $par['value'], 6, '.', '').'d0';
			}

			if ($par['name'] == 'refractive_idx_im') {
				$refractive_idx_im = $par['value'];
			} else if ($par['name'] == 'refractive_idx_re') {
				$refractive_idx_re = $par['value'];
			} else {
				$dsm_dat .= "{$par['name']}={$par['value']},\n";
			}
		}

		// add a field that contains a combination of the refractive indices
		$dsm_dat .= "ind_ref=({$refractive_idx_re},{$refractive_idx_im})\n";
		$dsm_dat .= "/\n";

		@file_put_contents($path.'param_dsm.dat', $dsm_dat);

		return true;
	}

	/**
	 * Parse results from an .out file.
	 *
	 * @param string $experimentId
	 */
	public function _getResults($experimentId) {
		$this->CI->load->helper('array');

		$experiment = $this->CI->experiment->getById($experimentId);

		$path = FCPATH . 'uploads/' . $experiment['project_id'] . '/' . $experiment['id'] . '/';

		// there was no results file found. return an empty array
		if (!file_exists($path . 'default.out')) {
			return array();
		}

		// open the results file
		$handler = fopen($path . 'default.out', "r");

		$results = array();

		// and parse its contents
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
