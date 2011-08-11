<?php defined('BASEPATH') || exit("No direct script access allowed");

class Lang_detect {

	private $CI;

	private $supportedLanguages = array();

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
		for ($i = $this->CI->uri->total_segments(); $i > 0; $i--) {
			$segment = $this->CI->uri->segment($i);
			if (strlen($segment) == 2 && array_key_exists($segment, $this->supportedLanguages)) {
				$lang = $segment;
				$this->CI->session->set_userdata('language', $lang);
			}
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
