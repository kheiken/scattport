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

/**
 * XML-RPC API for ScattPort.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Xmlrpc extends CI_Controller {

	function __construct() {
		parent::__construct();

		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');

		$this->load->model('server');
	}

	/**
	 * Set up the routes for the XML-RPC API.
	 */
	function index() {

		$config['functions']['heartbeat'] = array('function' => 'Xmlrpc._heartbeat');
		$config['functions']['get_job'] = array('function' => 'Xmlrpc._get_job');
		$config['functions']['job_done'] = array('function' => 'Xmlrpc._job_done');
		$config['object'] = $this;

		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}

	/**
	 * Get the oldest not yet started job, if there is one.
	 *
	 * @param type $request
	 * @return mixed
	 */
	function _get_job($request) {
		$this->load->model('job');
		$parameters = $request->output_parameters();
		$server = $this->server->getBySecret($parameters[0]);

		//TODO: Auth
		//if($server)
		//	return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		$parameters = $parameters[1];

		$job = $this->job->getWaitingJob();
		if ($job) {
			$update = array(
				'started_at' => mysql_now(),
				'server' => $server->id,
			);
			$this->job->update($job->id, $update);
			$response = array(array(
					'success' => array('true', 'string'),
					'new_job' => array('true', 'string'),
					'job_id' => array($job->id, 'string'),
					'experiment_id' => array($job->experiment_id, 'string'),
				), 'struct');
		} else {
			$response = array(array(
					'success' => array('true', 'string'),
					'new_job' => array('false', 'string'),
				), 'struct');
		}
		return $this->xmlrpc->send_response($response);
	}

	/**
	 * Receive a heartbeat from the client.
	 *
	 * And while we're at it store the current workload.
	 *
	 * @param mixed $request
	 * @return mixed
	 */
	function _heartbeat($request) {
		$parameters = $request->output_parameters();
		$server = $this->server->getBySecret($parameters[0]);

		//TODO: Auth
		//if($server)
		//	return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		$parameters = $parameters[1];

		$update = array(
			'os' => $parameters[0],
			'uptime' => $parameters[1],
			'workload' => $parameters[3],
			'hardware' => $parameters[2],
			'last_update' => mysql_now()
		);
		$this->server->update($server->id, $update);
		$response = array(array(
				'success' => array('true', 'string'),
			), 'struct');
		return $this->xmlrpc->send_response($response);
	}

	function _job_done($request) {
		$this->load->model('job');
		$parameters = $request->output_parameters();
		$server = $this->server->getBySecret($parameters[0]);

		//TODO: Auth
		//if($server)
		//	return $this->xmlrpc->send_error_message('100', 'Invalid Access');
		$parameters = $parameters[1];

		$job_id = $parameters[0];

		$update = array(
			'finished_at' => mysql_now(),
			'progress' => 100
		);
		$this->job->update($job_id, $update);
		$response = array(array(
				'success' => array('true', 'string'),
			), 'struct');
		return $this->xmlrpc->send_response($response);
	}

}
