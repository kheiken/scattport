<?php

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
 * Programs are used to do the actual calculation of a trial.
 * 
 * @author Karsten Heiken, karsten@disposed.de
 */
class Program extends CI_Model {

	/**
	 * Create a new program.
	 *
	 * @param array $data the data of the new program
	 * @return bool was the insert successful
	 */
	public function create($data) {
		// TODO: stub
		return FALSE;
	}

	/**
	 * Delete a program.
	 * @param string the program id to delete
	 * @return bool was the deletion successful
	 */
	public function delete($program_id) {
		return $this->db->delete('programs', array('id' => $program_id));
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
	 * Get a specific program.
	 *
	 * @param string $prg_id The id of the program to get from the database
	 * @return array Declarative array with all available information of the program.
	 */
	public function getById($prg_id) {
		return $this->db->get_where('programs', array('id' => $prg_id))->row_array();
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
	 * @param type $program_id The program for which we want to get the parameters.
	 * @return array The parameters
	 */
	public function getParameters($program_id) {
		$query = $this->db->select('id, fieldname, readable, unit, description, type')
				->get_where('configuration_fields', array('program_id' => $program_id));

		return $query->result_array();
	}
}