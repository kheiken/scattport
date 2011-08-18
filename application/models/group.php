<?php defined('BASEPATH') || exit("No direct script access allowed");

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
