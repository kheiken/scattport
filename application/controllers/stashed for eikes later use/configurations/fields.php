<?php

class Fields extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function upload_csv() {
		$this->load-view('web/configurations/upload_csv');
	}

}