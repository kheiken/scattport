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
class Dashboard extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('job');
	}

	public function index() {
		$tpl['action_buttons'] = array(
			array(
				'icon' => 'icons-big/blue-folder-new.png',
				'text' => _('New project'),
				'target' => site_url('projects/create'),
			),
			array(
				'icon' => 'icons-big/blue-folder-open-document.png',
				'text' => _('Recent results'),
				'target' => site_url('jobs#finished'),
			),
		);

		$tpl['recent_buttons'] = array(
			array(
				'count' => $this->job->countUnseenResults(),
				'text' => _('Jobs finished'),
				'id' => 'jobs_finished',
				'title' => _('Show all finished jobs'),
				'target' => site_url('jobs#finished'),
			),
			array(
				'count' => $this->share->countUnseen(),
				'text' => _('Newly shared projects'),
				'id' => 'shared_projects',
				'title' => _('Show all projects shared with me'),
				'target' => site_url('shares'),
			),
			array(
				'count' => $this->job->countRunning(),
				'text' => _('Job running'),
				'id' => 'jobs_running',
				'title' => _('Show all running jobs'),
				'target' => site_url('jobs#running'),
			),
			array(
				'count' => $this->job->countPending(),
				'text' => _('Jobs pending'),
				'id' => 'jobs_pending',
				'title' => _('Show all pending jobs'),
				'target' => site_url('jobs#pending'),
			),
		);
		$this->load->view('dashboard', $tpl);
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */
