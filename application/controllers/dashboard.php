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
				'target' => site_url('jobs/results'),
			),
		);

		$tpl['recent_buttons'] = array(
			array(
				'count' => $this->job->countUnseenResults(),
				'text' => sprintf(ngettext('%d Job finished', '%d Jobs finished',
						$this->job->countUnseenResults()), $this->job->countUnseenResults()),
				'id' => 'jobs_finished',
				'title' => sprintf(ngettext('%d job finished recently', '%d jobs finished recently',
						$this->job->countUnseenResults()), $this->job->countUnseenResults()),
				'target' => '#',
			),
			array(
				'count' => 0,
				'text' => sprintf(ngettext('Newly shared project', 'Newly shared projects', 0), 0),
				'id' => 'shared_projects',
				'title' => sprintf(ngettext('You were invited to join %d project',
						'You were invited to join %d projects', 0), 0),
				'target' => '#',
			),
			array(
				'count' => $this->job->countRunning(),
				'text' => sprintf(ngettext('Job running', 'Jobs running',
						$this->job->countRunning()), $this->job->countRunning()),
				'id' => 'jobs_running',
				'title' => sprintf(ngettext('There is %d job currently running', 'There are %d jobs currently running',
						$this->job->countRunning()), $this->job->countRunning()),
				'target' => '#',
			),
			array(
				'count' => $this->job->countPending(),
				'text' => sprintf(ngettext('Job pending', 'Jobs pending',
						$this->job->countPending()), $this->job->countPending()),
				'id' => 'jobs_pending',
				'title' => sprintf(ngettext('There is %d job currently pending', 'There are %d jobs currently pending',
						$this->job->countPending()), $this->job->countPending()),
				'target' => '#',
			),
		);
		$this->load->view('dashboard', $tpl);
	}
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */
