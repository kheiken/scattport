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
 * Controller for Ajax requests.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Ajax extends CI_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Load the notifications for the user.
	 *
	 * This is used for sending notifications to the user like
	 * "There are new results for the project 'foobar'".
	 */
	public function get_notifications() {
		$tpl['messages'] = $this->messages->get();
		$this->load->view('global/notifications', $tpl);
	}

	/**
	 * Saves a projects description.
	 */
	public function save_project() {
		$this->load->model('project');
		$data['description'] = $this->input->post('content');
		$this->project->update($this->session->userdata('active_project'), $data);
	}

	/**
	 * Sorts a programs parameters.
	 *
	 * @param string $id
	 */
	public function sort_parameters($programId) {
		$this->load->model('program');
		$this->program->sortParameters($this->input->post('parameters'), $programId);
	}

}