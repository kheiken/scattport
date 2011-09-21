<?php defined('BASEPATH') || exit('No direct script access allowed');
/*
 * Copyright (c) 2011 Karsten Heiken, Eike Foken
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Extends CI's date helpers.
 *
 * @package ScattPort
 * @subpackage Helpers
 * @author Karsten Heiken <karsten@disposed.de>
 */

if (!function_exists('mysql_now')) {
	/**
	 * Get "now" time formatted for MySQL.
	 *
	 * Returns time() formatted for direct insertion into a DATETIME field.
	 *
	 * @return integer
	 */
	function mysql_now() {
		return mdate('%Y-%m-%d %H:%i:%s', now());
	}
}

if (!function_exists('time_diff')) {
	/**
	 * Calculates a date or time difference.
	 *
	 * @author J de Silva <giddomains@gmail.com>
	 * @copyright Copyright &copy; 2005, J de Silva
	 * @link http://www.gidnetwork.com/b-16.html
	 * @param string $start
	 * @param string $end
	 * @return mixed Returns the difference as integer or FALSE on error.
	 */
	function time_diff($start, $end) {
		$uts['start'] = strtotime($start);
		$uts['end'] = strtotime($end);
		if ($uts['start'] !== -1 && $uts['end'] !== -1) {
			if ($uts['end'] >= $uts['start']) {
				$diff = $uts['end'] - $uts['start'];
				$diff = intval($diff);
				return $diff;
			}
		}
		return false;
	}
}

if (!function_exists('prettyTime')) {
	/**
	 * Converts seconds to a pretty string.
	 *
	 * @author Karsten Heiken <karsten@disposed.de>
	 * @param integer $secs The amount of seconds
	 * @param boolean $includeseconds Should seconds be appended to the string?
	 * @return string
	 */
	function prettyTime($secs, $includeseconds = false) {
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

if (!function_exists('relative_time')) {
	/**
	 * Parses any english textual datetime description into a relative date string.
	 *
	 * @author Eike Foken <kontakt@eikefoken.de>
	 * @param string $date
	 * @param boolean $show_seconds
	 * @return string
	 */
	function relative_time($date, $show_seconds = false) {
		if (is_integer($date)) {
			$diff = time() - $date;
		} else {
			$diff = time() - strtotime($date);
		}

		if ($diff < 120 && !$show_seconds) {
			$output = _('just now');
		} else if ($diff < 60 && $show_seconds) {
			$output = sprintf(ngettext('%d second ago', '%d seconds ago', $diff), $diff);
		} else if (($diff = round($diff / 60)) < 60) {
			$output = sprintf(ngettext('%d minute ago', '%d minutes ago', $diff), $diff);
		} else if (($diff = round($diff / 60)) < 24) {
			$output = sprintf(ngettext('%d hour ago', '%d hours ago', $diff), $diff);
		} else if (($diff = round($diff / 24)) < 7) {
			$output = sprintf(ngettext('%d day ago', '%d days ago', $diff), $diff);
		} else if (($diff = round($diff / 7)) < 4) {
			$output = sprintf(ngettext('%d week ago', '%d weeks ago', $diff), $diff);
		} else {
			$output = _('on') . ' ' . strftime('%B %Y', strtotime($date));
		}

		return $output;
	}
}

/* End of file MY_date_helper.php */
/* Location: ./application/helpers/MY_date_helper.php */
