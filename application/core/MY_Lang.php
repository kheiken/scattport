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
 * Extends CI's language class.
 *
 * @package ScattPort
 * @subpackage Libraries
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class MY_Lang extends CI_Lang {

	var $_gettext_language;
	var $_gettext_domain;
	var $_gettext_path;

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->_gettext_domain = 'lang';
		log_message('debug', "Gettext Class Initialized");
	}

	/**
	 * This method overides the original load method. It's duty is loading the
	 * domain files by config or by default internal settings.
	 *
	 * @param string $user_lang
	 */
	public function load_gettext($user_lang = false) {
		if ($user_lang) {
			$this->_gettext_language = $user_lang;
		} else {
			$this->_gettext_language = 'en_US';
		}
		log_message('debug', 'The gettext language was set by parameter: ' . $this->_gettext_language);

		putenv("LC_ALL=$this->_gettext_language");
		setlocale(LC_ALL, $this->_gettext_language . ".utf8");

		// set the path of .po files
		$this->_gettext_path = APPPATH . 'language/locale';
		log_message('debug', 'Gettext Class path chosen is: ' . $this->_gettext_path);

		$filename = $this->_gettext_path . '/' . $this->_gettext_language . '/LC_MESSAGES/' . $this->_gettext_domain . '.mo';

		// if there is no language file, we can't load anything
		if (file_exists($filename)) {
			$mtime = filemtime($filename);

			$newFilename = $this->_gettext_path . '/' . $this->_gettext_language . '/LC_MESSAGES/' . $this->_gettext_domain . '_' . $mtime . '.mo';

			if (!file_exists($newFilename)) {
				$dir = scandir(dirname($filename));
				foreach ($dir as $file) {
					// remove all the old files
					if (!in_array($file, array('.', '..', $this->_gettext_domain . '.po', $this->_gettext_domain . '.mo'))) {
						@unlink(dirname($filename) . '/' . $file);
					}
				}

				@copy($filename, $newFilename);
			}

			$newDomain = $this->_gettext_domain . '_' . $mtime;

			bindtextdomain($newDomain, $this->_gettext_path);
			bind_textdomain_codeset($newDomain, "UTF-8");
			textdomain($newDomain);
		}

		log_message('debug', 'The gettext domain chosen is: '. $this->_gettext_domain);

		return true;
	}

	/**
	 * Loads a language file.
	 *
	 * @see CI_Lang::load()
	 */
	public function load($langfile = '', $idiom = '', $return = false, $add_suffix = true, $alt_path = '') {
		$langfile = str_replace('.php', '', $langfile);

		if ($add_suffix == true) {
			$langfile = str_replace('_lang.', '', $langfile) . '_lang';
		}

		$langfile .= '.php';

		if (in_array($langfile, $this->is_loaded, true)) {
			return;
		}

		$config =& get_config();

		if ($idiom == '') {
			$defaultLang = !isset($config['language']) ? 'english' : $config['language'];
			$idiom = ($defaultLang == '') ? 'english' : $defaultLang;
		}

		// determine where the language file is and load it
		if ($alt_path != '' && file_exists($alt_path . 'language/' . $idiom . '/' . $langfile)) {
			include($alt_path . 'language/' . $idiom . '/' . $langfile);
		} else {
			$found = false;

			foreach (get_instance()->load->get_package_paths(true) as $packagePath) {
				if (file_exists($packagePath . 'language/' . $idiom . '/' . $langfile)) {
					include($packagePath . 'language/' . $idiom . '/' . $langfile);
					$found = true;
					break;
				} else if (file_exists($packagePath . 'language/english/' . $langfile)) {
					// load the english language file if the other file does exists
					include($packagePath . 'language/english/' . $langfile);
					$found = true;
					break;
				}
			}

			if ($found !== true) {
				show_error('Unable to load the requested language file: language/' . $idiom . '/' . $langfile);
			}
		}

		if (!isset($lang)) {
			log_message('error', 'Language file contains no data: language/' . $idiom . '/' . $langfile);
			return;
		}

		if ($return == true) {
			return $lang;
		}

		$this->is_loaded[] = $langfile;
		$this->language = array_merge($this->language, $lang);
		unset($lang);

		log_message('debug', 'Language file loaded: language/' . $idiom . '/' . $langfile);
		return true;
	}
}

/* End of file MY_Lang.php */
/* Location: ./application/libraries/MY_Lang.php */
