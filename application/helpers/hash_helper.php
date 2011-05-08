<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Helper for generating hash values.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */

/**
 * Generate a pseudo-random SHA1-hash.
 *
 * @access	public
 * @return	integer
 */
if ( ! function_exists('random_hash'))
{
	function random_hash($len = 40)
	{
		return substr(sha1(rand(1,1000).now().rand(1001,2000)), 0, $len);
	}
}

/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */