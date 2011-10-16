<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Karsten Heiken, Eike Foken
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
	 * Calls the parent constructor.
	 */
	function __construct() {
		parent::__construct();
		$this->load->model('server');
		$this->load->library('form_validation');
	}

	/**
	 * Lists all servers.
	 */
	function index() {
		$this->load->view('admin/servers/list', array('servers' => $this->server->getAll()));
	}

	/**
	 * Shows details of a server.
	 *
	 * @param string $serverId
	 */
	function detail($serverId) {
		$this->load->helper('typography');

		$data['server'] = $this->server->getById($serverId);
		$data['owner'] = $this->user->getUserByID($data['server']->owner);

		$this->load->view('admin/servers/detail', $data);
	}

	/**
	* Allows admins to create a server.
	*
	* @param string $serverId
	*/
	public function create() {

		if ($this->form_validation->run('servers/create') === true) {
			$data = array(
						'id' => $this->input->post('id'),
						'description' => $this->input->post('description'),
						'location' => $this->input->post('location'),
						'owner' => $this->input->post('owner'),
						'secret' => $this->input->post('secret'),
			);

			if ($this->server->create($data))
				redirect('admin/servers', 303);
			else
				$this->messages->add(_("Something went wrong with the action you performed."), 'error');
		}

		$data = array(); // empty data array

		$data['users'] = array();

		$users = $this->user->getAll();
		foreach ($users as $user) {
			$data['users'][$user['id']] = $user['firstname'] . ' ' . $user['lastname'];
		}

		// generate a secret
		$data['secret'] = $this->input->post('secret');

		if(empty($data['secret']))
			$data['secret'] = sha1(uniqid());

		$this->load->view('admin/servers/create', $data);
	}

	/**
	 * Allows admins to edit a server.
	 *
	 * @param string $serverId
	 */
	public function edit($serverId) {
		$server = $this->server->getById($serverId);

		if (!isset($server) || !is_object($server)) {
			show_404();
		}

		if ($this->form_validation->run('servers/edit') === true) {
			$data = array(
					'description' => $this->input->post('description'),
					'location' => $this->input->post('location'),
					'owner' => $this->input->post('owner'),
			);

			if ($this->server->update($serverId, $data)) {
				$this->messages->add(sprintf(_("The server &quot;%s&quot; has been updated successfully."), $server->id), 'success');
			}
			redirect('admin/servers', 303);
		}

		$data = array(); // empty data array
		$data['server'] = $server;

		$data['users'] = array();

		$users = $this->user->getAll();
		foreach ($users as $user) {
			$data['users'][$user['id']] = $user['firstname'] . ' ' . $user['lastname'];
		}

		$this->load->view('admin/servers/edit', $data);
	}

	/**
	 * Allows admins to delete a server.
	 *
	 * @param string $serverId
	 */
	public function delete($serverId) {
		$server = $this->server->getById($serverId);

		if (!isset($server) || !is_object($server)) {
			show_404();
		} else {
			$this->server->delete($server->id);
			redirect('admin/servers', 303);
		}
	}
}

/* End of file servers.php */
/* Location: ./application/controllers/admin/servers.php */
