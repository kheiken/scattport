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
 * Helper functions for including assets.
 *
 * @package ScattPort
 * @subpackage Helpers
 * @author Eike Foken <kontakt@eikefoken.de>
 */

/**
 * Helps generating links to asset files of any sort.
 *
 * Note: The type should be the name of the folder the asset is stored in.
 *
 * @param string $type
 * @param string $filename
 * @return string
 */
function asset_url($type, $filename) {
	$CI = &get_instance();
	$CI->load->library('assets');

	return $CI->assets->url($type, $filename);
}

/**
 * Helps generating HTML for assets of any sort.
 *
 * @param string $type
 * @param string $filename
 * @param mixed $attributes
 * @return string
 */
function asset($type, $filename, $attributes = '') {
	$CI = &get_instance();
	$CI->load->library('assets');

	return $CI->assets->load($type, $filename, $attributes);
}

/**
 * Inserts CSS assets.
 *
 * @param string $filename
 * @param mixed $attributes
 * @return string
 */
function css_asset($filename, $attributes = '') {
	return asset('css', $filename, $attributes);
}

/**
 * Inserts image assets.
 *
 * @param string $filename
 * @param mixed $attributes
 * @return string
 */
function image_asset($filename, $attributes = '') {
	return asset('image', $filename, $attributes);
}

/**
 * Inserts javascript assets.
 *
 * @param string $filename
 * @return string
 */
function js_asset($filename) {
	return asset('js', $filename);
}

/* End of file asset_helper.php */
/* Location: ./application/helpers/asset_helper.php */
