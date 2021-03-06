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
 * Extends CI's input class.
 * 
 * @package ScattPort
 * @subpackage Libraries
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class MY_Input extends CI_Input {
	
	public function __construct() {
		log_message('debug', "Input Class Initialized");
		
		$this->_allow_get_array	= (config_item('allow_get_array') === true);
		$this->_enable_csrf = (config_item('csrf_protection') === true);

		$router =& load_class('Router');

		if ($router->class == 'programs') {
			$this->_enable_xss = false;
		} else {
			$this->_enable_xss = (config_item('global_xss_filtering') === true);
		}

		global $SEC;
		$this->security =& $SEC;

		// do we need the UTF-8 class?
		if (UTF8_ENABLED === true)
		{
			global $UNI;
			$this->uni =& $UNI;
		}

		// sanitize global arrays
		$this->_sanitize_globals();
	}
}

/* End of file MY_Input.php */
/* Location: ./application/core/MY_Input.php */
