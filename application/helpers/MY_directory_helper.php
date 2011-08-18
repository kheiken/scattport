<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Extends CI's directory helpers.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */

/**
 * Creates a directory if it not already exists. Works recursively.
 *
 * @param string $path Path to the directory to create
 * @return boolean
 */
if (!function_exists('mkdirs')) {
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
