<?php

/**
 * Trials are used to store different variations of the same project.
 *
 * @author Karsten Heiken, karsten@disposed.de
 */
class Trials extends CI_Controller {

	/**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('trial');

        // load language file
        // $this->lang->load(strtolower($this->router->class));
    }

}