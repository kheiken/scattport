<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Helper functions for including assets.
 *
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
