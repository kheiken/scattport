<?php defined('BASEPATH') || exit("No direct script access allowed");
/**
 * Extends CI's HTML helpers.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */

/**
 * Generates a script inclusion of a JavaScript file.
 *
 * Based on the CI's original link_tag.
 *
 * @access public
 * @param mixed $src JavaScript sources or an array
 * @param string $language Language
 * @param string $type Type
 * @param boolean $index_page Should index_page be added to the JavaScript path
 * @return string
 */
if (!function_exists('script_tag')) {
	function script_tag($src = '', $language = 'javascript', $type = 'text/javascript', $index_page = false) {
		$CI =& get_instance();

		$script = '<script';

		if (is_array($src)) {
			foreach ($src as $k => $v) {
				if ($k == 'src' && strpos($v, '://') === false) {
					if ($index_page === true) {
						$script .= ' src="' . $CI->config->site_url($v) . '"';
					} else {
						$script .= ' src="' . $CI->config->slash_item('base_url') . $v . '"';
					}
				} else {
					$script .= "$k=\"$v\"";
				}
			}

			$script .= "></script>\n";
		} else {
			if (strpos($src, '://') !== false) {
				$script .= ' src="' . $src . '" ';
			} else if ($index_page === true) {
				$script .= ' src="' . $CI->config->site_url($src) . '" ';
			} else {
				$script .= ' src="' . $CI->config->slash_item('base_url') . $src . '" ';
			}

			$script .= 'language="' . $language . '" type="' . $type . '"';
			$script .= "></script>\n";
		}

		return $script;
	}
}

/* End of file MY_html_helper.php */
/* Location: ./application/helpers/MY_html_helper.php */
