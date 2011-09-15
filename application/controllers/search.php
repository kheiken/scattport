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
 * Search projects and experiments.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Search extends MY_Controller {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('experiment');
		$this->load->helper('typography');
	}

	/**
	 *
	 */
	public function index() {
		if ($this->input->get('query') != '') {
			$query = explode(" ", $this->input->get('query'));

			$data['projects'] = $this->project->search($this->input->get('query'), $this->access->isAdmin());
			$data['experiments'] = $this->experiment->search($this->input->get('query'), false, $this->access->isAdmin());

			$this->load->view('search/results', $data);
		} else {
			$this->load->view('search/new');
		}
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
