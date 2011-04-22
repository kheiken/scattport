<?php defined('BASEPATH') || exit("No direct script access allowed");

class Lang_detect {

    protected $CI;

    // make config item available locally
    public $languages = array();

    // the user's language (directory name)
    public $lang_dir = '';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('lang_detect');
        $this->CI->load->helper('cookie');

        // get list of supported languages
        $this->languages = $this->CI->config->item('lang_available');
        if (empty($this->lang_dir)) {
            // language directory not yet set: detect the user's language
            $this->lang_dir = $this->detectLanguage();
        }

        log_message('debug', "Lang_detect Class Initialized");
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
        $language = false;

        // obtain language code from URI segment if available
        $langURI = $this->uriLanguageDetect();
        if ($langURI !== false) {
            // check the URI's language code
            $language = $this->checkLanguage($langURI);
            if ($language !== false) {
                $lang = $langURI;
            }
        }

        // if a language cookie available get its sanitized info
        $langCookie = get_cookie($this->CI->config->item('lang_cookie_name'));
        if ($langCookie !== false) {
            if (($language !== false) && ($langURI !== $langCookie)) {
                // URI has valid language but cookie has wrong language: update cookie
                $this->setLanguageCookie($langURI);
            }
            if ($language === false) {
                // invalid or no URI language code: check the cookie's language
                $language = $this->checkLanguage($langCookie);
                if ($language !== false) {
                    $lang = $langCookie;
                }
            }
        }

        if ($language === false) {
            // no cookie/URI language code: check browser's languages
            $accept_langs = $this->CI->input->server('HTTP_ACCEPT_LANGUAGE');
            if ($accept_langs !== false) {
                // explode languages into array
                $accept_langs = strtolower($accept_langs);
                $accept_langs = explode(",", $accept_langs);

                // check all of them
                foreach ($accept_langs as $lang) {
                    // remove all after ';'
                    $pos = strpos($lang, ';');
                    if ($pos !== false) {
                        $lang = substr($lang, 0, $pos);
                    }
                    // get CI language directory
                    $language = $this->checkLanguage($lang);
                    // finish search if we support that language
                    if ($language !== false) {
                        // set cookie
                        $this->setLanguageCookie($lang);
                        break;
                    }
                }
            }
        }

        if ($language === false) {
            // no base language available or no browser language match: use default
            $lang = $this->CI->config->item('lang_default');
            $language = $this->languages[$lang];
            // set cookie
            $this->setLanguageCookie($lang);
        }

        // set the configuration for the CI_Language class
        $this->CI->config->set_item('language', $language);
        // store the language code too
        $this->CI->config->set_item('lang_selected', $lang);
        return $language;
    }

    /**
     * Sets the language cookie.
     *
     * @param string $lang The language code, e.g. en
     */
    private function setLanguageCookie($lang) {
        set_cookie($this->CI->config->item('lang_cookie_name'), $lang,
                $this->CI->config->item('lang_expiration'));
    }

    /**
     * Fetches the language code from URI segment if available.
     *
     * @return mixed The language code, or FALSE if not found.
     */
    private function uriLanguageDetect() {
        // search the language code in the uri segments
        for ($i = $this->CI->uri->total_segments(); $i > 0; $i--) {
            $segment = $this->CI->uri->segment($i);
            // the uri segment with the language code has the prefix 'l_'
            if (strlen($segment) == 2 && array_key_exists($segment, $this->languages)) {
                return $segment;
            }
        }
        return false;
    }

    /**
     * Determines the language directory.
     *
     * @param string $lang The language code, e.g. en_uk
     * @return string The language directory, or FALSE if not found.
     */
    private function checkLanguage(&$lang) {
        if (!array_key_exists($lang, $this->languages)) {
            if (strlen($lang) == 2) {
                // we had already the base language: not found so give up
                return false;
            } else {
                // try base language
                $lang = substr($lang, 0, 2);
                if (!array_key_exists($lang, $this->languages)) {
                    // calculated base language also not found: give up
                    return false;
                }
            }
        }
        // return CI language directory
        return $this->languages[$lang];
    }
}

/* End of file Lang_detect.php */
/* Location: ./application/libraries/Lang_detect.php */
