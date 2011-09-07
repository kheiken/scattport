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
 * Group model.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Group extends CI_Model {

    /**
     * Calls the parent constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Gets all groups.
     *
     * @return array
     */
    public function get() {
        return $this->db->get('groups')->result_array();
    }

    /**
     * Gets a specific group.
     *
     * @param string $id
     * @return array
     */
    public function getByID($id) {
        return $this->db->get_where('groups', array('id' => $id))->row_array();
    }

    /**
     * Gets a specific group by it's name.
     *
     * @param string $name
     * @return array
     */
    public function getByName($name) {
        return $this->db->get_where('groups', array('name' => $name))->row_array();
    }
}

/* End of file group.php */
/* Location: ./application/controllers/group.php */
