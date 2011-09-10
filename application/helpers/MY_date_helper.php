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

/**
 * Function to calculate date or time difference.
 *
 * Function to calculate date or time difference. Returns an array or
 * false on error.
 *
 * @author       J de Silva                             <giddomains@gmail.com>
 * @copyright    Copyright &copy; 2005, J de Silva
 * @link         http://www.gidnetwork.com/b-16.html    Get the date / time difference with PHP
 * @param        string                                 $start
 * @param        string                                 $end
 * @return       array
 */
if ( ! function_exists('time_diff')) 
{
	function time_diff($start, $end) 
	{
		$uts['start'] = strtotime($start);
		$uts['end'] = strtotime($end);
		if ($uts['start'] !== -1 && $uts['end'] !== -1) {
			if ($uts['end'] >= $uts['start']) {
				$diff = $uts['end'] - $uts['start'];
				$diff = intval($diff);
				return ($diff);
			}
		}
		return (false);
	}
}

/**
 * Function to convert seconds to a pretty string.
 *
 * @author Karsten Heiken <karsten@disposed.de>
 * @param integer $secs the amount of seconds
 * @param boolean $includeseconds should seconds be appended to the string?
 * @return string
 */
if ( ! function_exists('prettyTime')) 
{
	function prettyTime($secs, $includeseconds = false) 
	{
		if(!defined('SECOND')) define("SECOND", 1);
		if(!defined('MINUTE')) define("MINUTE", 60 * SECOND);
		if(!defined('HOUR')) define("HOUR", 60 * MINUTE);
		if(!defined('DAY')) define("DAY", 24 * HOUR);
		if(!defined('MONTH')) define("MONTH", 30 * DAY);
	
		if ($secs < 1 * MINUTE)
		{
			return sprintf(ngettext('one second ago', '%d seconds ago', $secs), $secs);
		}
		if ($secs < 2 * MINUTE)
		{
			return _('a minute ago');
		}
		if ($secs < 45 * MINUTE)
		{
			return _('%d minutes ago', floor($secs / MINUTE));
		}
		if ($secs < 90 * MINUTE)
		{
			return _('an hour ago');
		}
		if ($secs < 24 * HOUR)
		{
			return _('%d hours ago', floor($secs / HOUR));
		}
		if ($secs < 48 * HOUR)
		{
			return _('yesterday');
		}
		if ($secs < 30 * DAY)
		{
			return sprintf(_('%d days ago'), floor($secs / DAY));
		}
		if ($secs < 12 * MONTH)
		{
			$months = floor($secs / DAY / 30);
			return sprintf(ngettext('one month ago', '%d months ago', $months), $months);
		}
		else
		{
			$years = floor($secs / DAY / 365);
			return sprintf(ngettext('one year ago', '%d years ago', $years), $years);
		}
	}
}

/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */
