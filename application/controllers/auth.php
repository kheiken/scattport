<?php defined('BASEPATH') || exit("No direct script access allowed");

class Auth extends CI_Controller {

	public function index()	{
		$this->load->view('auth/login');
	}

	public function do_login() {
	    echo "{success: true}";
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
