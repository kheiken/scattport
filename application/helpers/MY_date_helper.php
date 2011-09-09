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
if ( ! function_exists('secondsToString')) 
{
	function secondsToString($secs, $includeseconds = false) 
	{
		$days = intval($secs / 86400);
		$hours = intval($secs / 3600 % 24);
		$minutes = intval($secs / 60 % 60);
		$seconds = intval($secs % 60);
		if (($minutes + $hours + $days) < 1)
			return (sprintf(_('%d seconds'), $seconds));
		else if (($minutes + $hours) < 1)
			$string = sprintf(_('%d minutes'), $minutes);
		else if ($days < 1)
			$string = sprintf(_('%d hours, %d minutes'), $hours, $minutes);
		else
			$string = sprintf(_('%d days, %d hours, %d minutes'), $days, $hours,
					$minutes);
	
		if ($includeseconds)
			$string .= ' ' . sprintf(_('and %d seconds'), $seconds);
	
		return $string;
	}
}

/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */
