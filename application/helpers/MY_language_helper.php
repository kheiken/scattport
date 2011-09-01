<?php defined('BASEPATH') || exit("No direct script access allowed");
/**
 * Extends CI's Language helpers.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */

/**
 * Generates a link for language selection.
 *
 * @access public
 * @param string $img_folder Folder of the images for the language selection
 * @param boolean $index_page Should index_page be added to the JavaScript path
 * @return string
 */
if (!function_exists('lang_select')) {
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

