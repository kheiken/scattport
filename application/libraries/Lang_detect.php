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
 * Language detection library.
 *
 * @package ScattPort
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Lang_detect {

	/**
	 * Contains the CI instance.
	 *
	 * @var object
	 */
	private $CI;

	/**
	 * Contains all supported languages.
	 *
	 * @var array
	 */
	private $supportedLanguages = array();

	/**
	 * Contains the detected language.
	 *
	 * @var string
	 */
	private $language = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->config('language');
		$this->CI->load->helper('cookie');

		log_message('debug', "Lang_detect Class Initialized");

		// get list of supported languages
		$this->supportedLanguages = $this->CI->config->item('supported_languages');

		if (empty($this->language)) {
			// language not yet set: detect the user's language
			$this->language = $this->detectLanguage();
		}

		$this->CI->lang->load_gettext($this->CI->config->item('language'));
	}

	/**
	 * Determine a user's language.
	 *
	 * Use either the URI segment's or the cookie's language code or determine
	 * the best match of the browser's languages with the available languages.
	 * If no match s found, the configured default language is taken.
	 *
	 * @return string Language directory name, e.g. 'english'
	 */
	public function detectLanguage() {
		$segment = $this->CI->input->get('lang');
		if (strlen($segment) == 2 && array_key_exists($segment, $this->supportedLanguages)) {
			$lang = $segment;
			$this->CI->session->set_userdata('language', $lang);
		}

		if ($this->CI->session->userdata('language')) {
			$lang = $this->CI->session->userdata('language');
		} else if (get_cookie('language') !== false) {
			$lang = get_cookie('language');
		} else if ($this->CI->input->server('HTTP_ACCEPT_LANGUAGE')) {
			// explode languages into an array
			$accept_langs = explode(',', $this->CI->input->server('HTTP_ACCEPT_LANGUAGE'));

			log_message('debug', 'Checking browser languages: ' . implode(', ', $accept_langs));

			// check them all, until a match is found
			foreach ($accept_langs as $lang) {
				$lang = strtolower(substr($lang, 0, 2));

				if (in_array($lang, array_keys($this->supportedLanguages))) {
					break;
				}
			}
		}

		// if no valid language has been detected, use the default
		if (empty($lang) || !in_array($lang, array_keys($this->supportedLanguages))) {
			$lang = $this->CI->config->item('default_language');
		}

		// save the selected language to avoid detecting it again
		$this->CI->session->set_userdata('language', $lang);

		// set the language config
		$this->CI->config->set_item('language', $this->supportedLanguages[$lang]['locale']);

		return $lang;
	}

	/**
	 * Sets the language cookie.
	 *
	 * @param string $lang The language code, e.g. en
	 */
	private function setLanguageCookie($lang) {
		set_cookie($this->CI->config->item('language_cookie_name'), $lang, $this->CI->config->item('language_expiration'));
	}

}

/* End of file Lang_detect.php */
/* Location: ./application/libraries/Lang_detect.php */
