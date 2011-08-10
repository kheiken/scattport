<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Extends CI's language class.
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class MY_Lang extends CI_Lang {

	/**
	 * Calls the parent constructor.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Loads a language file.
	 *
	 * @see CI_Lang::load()
	 */
	public function load($langfile = '', $idiom = '', $return = false, $add_suffix = true, $alt_path = '') {
		$langfile = str_replace(EXT, '', $langfile);

		if ($add_suffix == true) {
			$langfile = str_replace('_lang.', '', $langfile) . '_lang';
		}

		$langfile .= EXT;

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