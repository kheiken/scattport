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
 * Extends CI's directory helpers.
 *
 * @package ScattPort
 * @subpackage Helpers
 * @author Eike Foken <kontakt@eikefoken.de>
 */

if (!function_exists('mkdirs')) {
	/**
	 * Recursively creates a directory if it not already exists.
	 *
	 * @param string $path
	 * @param integer $mode
	 * @return boolean Returns TRUE on success or FALSE on failure.
	 */
	function mkdirs($path, $mode = 0777) {
		if (!is_dir($path)) {
			$old_umask = umask(0);
			$result = mkdir($path, $mode, true);
			umask($old_umask);
		}
		return $result;
	}
}

/* End of file MY_file_helper.php */
/* Location: ./application/helpers/MY_file_helper.php */
