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
		
		$days = intval($secs / 86400);
		$hours = intval($secs / 3600 % 24);
		$minutes = intval($secs / 60 % 60);
		$seconds = intval($secs % 60);
		
		$d = sprintf(ngettext('%d day', '%d days', $days), $days);
		$h = sprintf(ngettext('%d hour', '%d hours', $hours), $hours);
		$m = sprintf(ngettext('%d minute', '%d minutes', $minutes), $minutes);
		$s = sprintf(ngettext('%d second', '%d seconds', $seconds), $seconds);
		
		$output = "";
		if($days > 0) {
			$output .= $d;
		}
		if($hours > 0) {
			$output .= !empty($output) ? ", ". $h : "". $h;
		}
		if($minutes > 0) {
			$output .= !empty($output) ? ", ". $m : "". $m;
		}
		if($includeseconds || empty($output)) {
			$output .= !empty($output) ? ", ". $s : "". $s;
		}
		
		return $output;
	}
}

/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */
