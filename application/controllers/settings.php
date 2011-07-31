<?php

/**
 * @author Karsten Heiken, karsten@disposed.de
 */
class Settings extends CI_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('program');

		// load language file
		$this->lang->load(strtolower($this->router->class));
	}

	/**
	 * Show a list of all available programs.
	 */
	public function index() {
		$profile = $this->user->profile();
		$profile_fields = array(
			array('firstname', 'Vorname', 'text'),
			array('lastname', 'Nachname', 'text'),
			array('intitution', 'Institution', 'text'),
		);
		$tpl['profile'] = $profile;
		$tpl['profile_fields'] = $profile_fields;
		$this->load->view('user/settings', $tpl);
	}
}
