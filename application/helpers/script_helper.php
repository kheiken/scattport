<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Generates a script inclusion of a JavaScript file.
 *
 * Based on the CI's original link_tag.
 *
 * @access public
 * @param mixed JavaScript sources or an array
 * @param string Language
 * @param string Type
 * @param boolean Should index_page be added to the javascript path
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

/* End of file javascript_helper.php */
/* Location: ./application/helpers/javascript_helper.php */
