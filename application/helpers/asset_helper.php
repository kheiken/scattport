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
	$CI->load->config('assets');

	return $CI->config->item('base_url') . $CI->config->item('assets_folder')
			. $type . '/' . $filename;
}

/**
 * Helps generating HTML for assets of any sort.
 *
 * @param string $type
 * @param string $filename
 * @param array $attributes
 * @return string
 */
function asset($type, $filename, $attributes = array()) {
	$CI = &get_instance();
	$CI->load->library('asset');

	return $CI->asset->load($type, $filename, $attributes);
}

/* End of file asset_helper.php */
/* Location: ./application/helpers/asset_helper.php */
