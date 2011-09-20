<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Eike Foken <kontakt@eikefoken.de>
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
 * Model for parameters.
 *
 * Each program has many parameters used for configuration of experiments.
 *
 * @package ScattPort
 * @subpackage Models
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Parameter extends CI_Model {

	/**
	 * Contains the possible types for parameters.
	 *
	 * @var array
	 */
	private $availableTypes = array('boolean', 'integer', 'float', 'string', 'array');

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Gets all available parameters for a program.
	 *
	 * @param string $programId
	 * @return array
	 */
	public function getAll($programId) {
		return $this->db->order_by('sort_number ASC')
				->get_where('parameters', array('program_id' => $programId))->result_array();
	}

	/**
	 * Gets a specific parameter.
	 *
	 * @param string $id
	 */
	public function getById($id) {
		return $this->db->get_where('parameters', array('id' => $id))->row_array();
	}

	/**
	 * Gets the possible parameter types.
	 */
	public function getTypes() {
		return $this->availableTypes;
	}

	/**
	 * Creates a new parameter.
	 *
	 * @param array $data
	 * @return mixed Returns the ID of the created parameter, or FALSE if
	 *                the insert was unsuccessful.
	 */
	public function create($data) {
		if (!isset($data['program_id'])) {
			return false;
		}

		do { // generate unique hash
			$data['id'] = random_string('sha1', 16);
		} while ($this->db->where('id', $data['id'])->from('parameters')->count_all_results() > 0);

		// put new parameter to the end
		$data['sort_number'] = $this->db->select_max('sort_number')
			->get_where('parameters', array('program_id' => $data['program_id']))
			->row()->sort_number + 1;

		$this->db->insert('parameters', $data);
		return $this->db->affected_rows() > 0 ? $data['id'] : false;
	}

	/**
	 * Updates a parameter.
	 *
	 * @param array $data
	 * @param string $id
	 * @return boolean Returns TRUE if the update was successful.
	 */
	public function update($data, $id) {
		$this->db->update('parameters', $data, array('id' => $id));
		return $this->db->affected_rows() == 1;
	}

	/**
	 * Deletes a specified parameter.
	 *
	 * @param string $id
	 * @return boolean Returns TRUE if the deletion was successful.
	 */
	public function delete($id) {
		$this->db->delete('parameters', array('id' => $id));
		return $this->db->affected_rows() == 1;
	}

	/**
	 * Saves the order of an array of parameters.
	 *
	 * @param array $parameters
	 * @return boolean Returns TRUE if the new order was successfully saved.
	 */
	public function sort($parameters) {
		foreach ($parameters as $number => $id) {
			$this->db->update('parameters', array('sort_number' => $number), array('id' => $id));
		}

		return $this->db->affected_rows() > 0;
	}
}

/* End of file parameter.php */
/* Location: ./application/models/parameter.php */
