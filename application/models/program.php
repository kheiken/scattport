<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Karsten Heiken <karsten@disposed.de>
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
 * Programs are used to do the actual calculation of a experiment.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Program extends CI_Model {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Creates a new program.
	 *
	 * @param string $name The name of the new program
	 * @return mixed Returns the ID of the created program, or FALSE if the insert was unsuccessful.
	 */
	public function create($name) {
		do { // generate unique hash
			$id = random_string('sha1', 8);
		} while ($this->db->where('id', $id)->from('programs')->count_all_results() > 0);

		$this->db->insert('programs', array('id' => $id, 'name' => $name));

		return $this->db->affected_rows() > 0 ? $id : false;
	}

	/**
	 * Updates a program.
	 *
	 * @param string $name The new name of the program
	 * @param string $id The ID of the program to update
	 * @return boolean Returns TRUE if the update was successful
	 */
	public function update($data, $id) {
		$this->db->update('programs', $data, array('id' => $id));
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Deletes a program.
	 *
	 * @param string $programId The program ID to delete
	 * @return boolean Returns TRUE if the deletion was successful
	 */
	public function delete($programId) {
		return $this->db->delete('programs', array('id' => $programId));
	}

	/**
	 * Get all available programs from the database.
	 *
	 * @return array Declarative array with all available information of all programs.
	 */
	public function getAll() {
		return $this->db->get('programs')->result_array();
	}

	/**
	 * Gets a specific program.
	 *
	 * @param string $id The id of the program to get from the database
	 * @return array Declarative array with all available information of the program.
	 */
	public function getById($id) {
		return $this->db->get_where('programs', array('id' => $id))->row_array();
	}

	/**
 	 * Gets a specific program by it's driver's name.
 	 *
	 * @param string $driver The driver
	 * @return array Declarative array with all available information of the program.
	 */
	public function getByDriver($driver) {
		return $this->db->get_where('programs', array('driver' => $driver))->row_array();
	}

	/**
	 * Get a program's available parameters by program id.
	 *
	 * The structure of the returned array is generated in the following way:
	 *
	 * array(
	 *     array(
	 *         'id' => varchar,
	 *         'fieldname' => the actual field name in the configuration,
	 *         'readable' => the name of the field in human readable form,
	 *         'unit' => this field is measured in 'unit's,
	 *         'description' => a full description of the field,
	 *         'type' => integer/float/varchar
	 *     ),
	 *     array(
	 *         ...
	 *     )
	 * )
	 *
	 * @param string $id The program for which we want to get the parameters.
	 * @return array The parameters
	 */
	public function getParameters($id) {
		$query = $this->db->order_by('sort_number ASC')
			->get_where('parameters', array('program_id' => $id));

		return $query->result_array();
	}
}

/* End of file program.php */
/* Location: ./application/models/program.php */
