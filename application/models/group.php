<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Group model.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Group extends CI_Model {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * get
     *
     * @return object
     */
    public function get() {
        return $this->db->get('groups')->result_array();
    }

    /**
     * getGroupByID
     *
     * @return object
     */
    public function getGroupByID($id) {
        $this->db->where('id', $id);
        return $this->db->get('groups')->row_array();
    }

    /**
     * getGroupByName
     *
     * @return object
     */
    public function getGroupByName($name) {
        $this->db->where('name', $name);
        return $this->db->get('groups')->row_array();
    }

}

/* End of file group.php */
/* Location: ./application/controllers/group.php */
