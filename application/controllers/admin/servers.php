<?php
/*
 * Copyright (c) 2011 Karsten Heiken <karsten@disposed.de>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
require_once APPPATH . 'core/Admin_Controller.php';

/**
 * Server management.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Servers extends Admin_Controller {

	/**
	 * Constructor.
	 */
	function __construct() {
		parent::__construct();
		$this->load->model('server');
	}

	/**
	 * List all servers.
	 */
	function index() {
		$tpl->servers = $this->server->getAll();

		$this->load->view('admin/server/list', $tpl);
	}

	/**
	 * Retrieve details of a server.
	 *
	 * @param type $server_id
	 */
	function detail($server_id) {
		$tpl->server = $this->server->getById($server_id);

		$this->load->view('admin/server/detail', $tpl);
	}

}

/* End of file servers.php */
/* Location: ./application/controllers/admin/servers.php */
