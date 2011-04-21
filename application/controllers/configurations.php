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
	public function get($config_id) {
		$configs = $this->configuration->get($config_id);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($configs));
	}

	/**
	 * Search for a specific configuration and return a list of possible results.
	 *
	 * @param string $project The project in which to search for a configuration.
	 * @param string $needle The needle to look for in the haystack.
	 */
	public function search($project, $needle) {
		$results = $this->configuration->search($project, $needle);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('count' => count($results), 'results' => $results)));
	}
}