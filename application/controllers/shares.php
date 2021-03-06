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

/**
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Shares extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Shows a list of all projects.
	 */
	public function index() {
		$data['projects'] = $this->share->getByUserId($this->session->userdata('user_id'));

		$this->load->view('shares/list', $data);
	}

	/**
	 * Allows users to mark all shared projects as seen.
	 */
	public function mark_all_seen() {
		$shares = $this->share->getByUserId($this->session->userdata('user_id'));
		foreach ($shares as $share) {
			if ($share['seen'] == 0) {
				$this->share->markSeen($share['project_id']);
			}
		}
		redirect('shares', 303);
	}
}

/* End of file shares.php */
/* Location: ./application/controllers/shares.php */
