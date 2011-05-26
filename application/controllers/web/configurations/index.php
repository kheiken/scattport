<?php

/**
 * Configurations are used to store different variations of the same project.
 *
 * @author Karsten Heiken, karsten@disposed.de
 */
class Configurations extends CI_Controller {

	/**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('configuration');

        // load language file
        // $this->lang->load(strtolower($this->router->class));
    }

	/**
	 * Get a specific configuration from the database.
	 * 
	 * @param string $config_id The configuration id to get.
	 */
	public function detail($config_id) {
		$configs = $this->configuration->get($config_id);

		$this->load->view('web/configurations/detail', $configs);
	}
}