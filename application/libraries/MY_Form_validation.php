<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Extends CI's form validation class.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class MY_Form_validation extends CI_Form_validation {

    /**
     * Calls the parent constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Checks if a username or email is unique.
     *
     * @param string $value
     * @param string $params
     */
    function unique($value, $params) {
        $CI =& get_instance();

        $CI->form_validation->set_message('unique', 'The %s is already being used.');

        list($table, $field) = explode(".", $params, 2);

        $query = $CI->db->select($field)->from($table)->where($field, $value)->limit(1)->get();

        if ($query->row()) {
            return false;
        } else {
            return true;
        }
    }

}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
