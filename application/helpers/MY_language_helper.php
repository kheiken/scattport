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
 * Extends CI's Language helpers.
 *
 * @package ScattPort
 * @subpackage Helpers
 * @author Eike Foken <kontakt@eikefoken.de>
 */

if (!function_exists('lang_select')) {
	/**
	 * Generates a link for changing the language.
	 *
	 * @param string $img_folder The folder containing flags for the language selection
	 * @return string The generated link
	 */
	function lang_select($img_folder = '') {
		$CI =& get_instance();

		$link = '<a';

		if ($CI->config->item('language') == 'en_US') {
			$link .= ' href="?lang=de">';
			$link .= '<img src="' . $CI->config->slash_item('base_url') . 'assets/images/languages/en.png" />';
			$link .= ' English';
		} else if ($CI->config->item('language') == 'de_DE') {
			$link .= ' href="?lang=en">';
			$link .= '<img src="' . $CI->config->slash_item('base_url') . 'assets/images/languages/de.png" />';
			$link .= ' Deutsch';
		}

		$link .= "</a>\n";

		return $link;
	}
}

/* End of file MY_language_helper.php */
/* Location: ./application/helpers/MY_language_helper.php */
