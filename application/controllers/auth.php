<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Authentication controller
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Auth extends CI_Controller {

    /**
     * Shows the index page.
     */
	public function index()	{
		$this->load->view('index');
	}

    /**
     * Shows the index page.
     */
	public function login()	{
		$this->load->view('auth/login');
	}

	/**
	 * Logs the user in - or not ;-)
	 */
	public function do_login() {
	    echo "{success: true}";
	}

	/**
	 * Logs the user out.
	 */
	public function do_logout() {
	    echo "{success: true}";
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
