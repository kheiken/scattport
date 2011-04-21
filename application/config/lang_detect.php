<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Mapping browser's primary language ID to supported language directory.
 */
$config['lang_available'] = array(
    'en' => 'english',
    'en-uk' => 'english',
    'de' => 'german',
    'de-at' => 'german'
);

/**
 * Default language code. This language MUST be supported!
 */
$config['lang_default'] = 'en';

/**
 * Selected language code (is set by the language detection).
 */
$config['lang_selected'] = 'en';

/**
 * The name you want for the cookie.
 */
$config['lang_cookie_name'] = 'lang_select_language';

/**
 * The number of seconds you want the language to be remembered.
 */
$config['lang_expiration']  = 63072000;
