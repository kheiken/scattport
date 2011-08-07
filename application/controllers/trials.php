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
 * Trials are used to store different variations of the same project.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */
class Trials extends MY_Controller {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('trial');
		$this->load->model('program');
		$this->load->model('project');
	}

	/**
	 * Create a new project.
	 */
	public function create() {
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<span class="error">', '</span>');

		$programs = $this->program->getAll();

		// Get the parameters for a specific program
		foreach ($programs as $program)
			$parameters[$program['id']] = $this->program->getParameters($program['id']);

		$config = array(
			array(
				'field' => 'name',
				'label' => 'Projektname',
				'rules' => 'trim|required|min_length[3]|max_length[100]|xss_clean',
			),
			array(
				'field' => 'description',
				'label' => 'Beschreibung',
				'rules' => 'trim|required|xss_clean',
			),
		);

		$this->form_validation->set_rules($config);


		if ($this->form_validation->run() == FALSE) {
			$tpl['parameters'] = $parameters;
			$tpl['programs'] = $programs;
			$this->load->view('trial/new', $tpl);
		} else {
			// TODO: handle file upload

			$data = array(
				'name' => $this->input->post('name'),
				'description' => $this->input->post('description'),
			);

			$result = $this->trial->create($data);
			if ($result) {
				$userpath = FCPATH . 'uploads/' . $this->session->userdata('user_id') . '/';
				$projectpath = $userpath . $data['project_id'] . '/';
				$trialpath = $projectpath . $data['trial_id'] . '/';
				if(!is_dir($trialpath))
					if (!is_dir($projectpath))
						if(!is_dir($userpath))
							mkdir($userpath);
						mkdir($projectpath);
					mkdir($trialpath);
				chmod($userpath, 0777);
				chmod($projectpath, 0777);
				chmod($trialpath, 0777);

				redirect('/trial/detail/' . $result, 'refresh');
			} else {
				$tpl['error'][] = "Der Versuch konnte nicht gespeichert werden.";
				$this->load->view('trial/new', $tpl);
			}
		}
	}
}