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
 * Extends CI's form helpers.
 *
 * @package ScattPort
 * @subpackage Helpers
 * @author Eike Foken <kontakt@eikefoken.de>
 */

if (!function_exists('form_yesno')) {
	/**
	 * Shows a yes-no selection.
	 *
	 * @param string $name
	 * @param mixed $selected
	 * @param string $extra
	 * @return string
	 */
	function form_yesno($name = '', $selected = array(), $extra = '') {
		return form_dropdown($name, array('0' => _('No'), '1' => _('Yes')), $selected, $extra);
	}
}

/* End of file MY_form_helper.php */
/* Location: ./application/helpers/MY_form_helper.php */
