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
 * @author Karsten Heiken, karsten@disposed.de
 */
class Programs extends CI_Controller {

    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->load->model('program');

        // load language file
        $this->lang->load(strtolower($this->router->class));
    }

	/**
	 * Show a list of all available programs.
	 */
	public function index() {
		$programs = $this->program->getAll();
		
		$tpl['programs'] = $programs;
		$this->load->view('program/list', $tpl);
	}

	/**
	 * Show detailed information about a program.
	 * 
	 * @param type $prg_id The program's id
	 */
	public function detail($prg_id) {
		$program = $this->program->getById($prg_id);
		
		$tpl['program'] = $program;
		$this->load->view('program/detail', $tpl);
	}
}
