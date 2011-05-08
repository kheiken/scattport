<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Extends CI's date helpers.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Date Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/date_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Get "now" time formatted for MySQL
 *
 * Returns time() formatted for direct insertion into a DATETIME field.
 *
 * @access	public
 * @return	integer
 */
if ( ! function_exists('mysql_now'))
{
	function mysql_now()
	{
		return mdate('%Y-%m-%d %H:%i:%s', now());
	}
}

/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */