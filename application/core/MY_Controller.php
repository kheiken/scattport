<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
* Extends CI's controller class.
*
* @author Eike Foken <kontakt@eikefoken.de>
*/
class MY_Controller extends CI_Controller {

	/**
	 * Calls the parent constructor and loads the relevant language file.
	 */
	public function __construct() {
		parent::__construct();

		// load relevant language file
		$this->lang->load(strtolower($this->router->class));
	}

}