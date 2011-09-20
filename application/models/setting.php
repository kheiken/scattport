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
 * Model for settings.
 *
 * @todo Use magic methods
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Setting extends CI_Model {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * @param string $name
	 * @param string $value
	 */
	public function set($name, $value) {
		$this->db->where('name', $name)->update('settings', array('value' => $value));

		if ($this->db->where('name', $name)->count_all_results('settings') == 0) {
			$this->create(array('name' => $name, 'value' => $value));
		}
	}

	/**
	 *
	 * @param string $name
	 */
	public function get($name) {
		return $this->db->get_where('settings', array('name' => $name))->row()->value;
	}

	/**
	 * Creates a new settings entry.
	 *
	 * @param array $data An array with "name" and "value"
	 * @return boolean Returns the ID of the created settings, or FALSE if the
	 *                  insert was unsuccessful.
	 */
	public function create($data = array()) {
		do { // generate unique hash
			$data['id'] = random_string('sha1', 40);
		} while ($this->db->where('id', $data['id'])->from('settings')->count_all_results() > 0);

		$this->db->insert('settings', $data);

		return ($this->db->affected_rows() > 0) ? $data['id'] : false;
	}

	/**
	 * Updates all settings.
	 *
	 * @param array $data
	 * @return boolean Returns TRUE on success.
	 */
	public function update($data = array()) {
		foreach ($data as $name => $value) {
			$this->set($name, $value);
		}
		return true;
	}
}

/* End of file setting.php */
/* Location: ./application/models/setting.php */
