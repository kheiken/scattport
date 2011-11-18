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
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Debug extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		if(ENVIRONMENT == "production") {
			show_error("Debugging functions are not available in a production environment.", 400);
		}
	}

	public function cancel_job($experiment, $jobId) {
		$this->load->model('job');
		$this->job->delete($jobId);

		redirect("experiments/detail/".$experiment);

	}
}

/* End of file debug.php */
/* Location: ./application/controllers/debug.php */
