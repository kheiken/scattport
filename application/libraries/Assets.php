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
 * Simple library to manage assets.
 *
 * @package ScattPort
 * @subpackage Libraries
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Assets {

	private $assetsFolder;
	private $cacheFolder;

	private $css = array();
	private $javascript = array();

	/**
	 * Contains the CI instance.
	 *
	 * @var object
	 */
	private $CI;

	/**
	 * Constructor.
	 */
	public function __construct($config = array()) {
		log_message('debug', "Asset Class Initialized");

		// set the super object to a local variable
		$this->CI =& get_instance();

		// set the asset path
		$this->assetsPath = ($config['assets_folder'] != '') ? $config['assets_folder'] : 'assets';

		if (!is_dir($this->assetsPath)) {
			log_message('error', "Asset folder does not exist");
		}

		// set the cache path
		$this->cachePath = ($config['cache_folder'] != '') ? $config['cache_folder'] : 'assets/cache';

		if (!is_dir($this->cachePath) || !is_really_writable($this->cachePath)) {
			$this->cacheEnable = false;
		}
	}

	/**
	 * Loads an asset.
	 *
	 * @param string $type
	 * @param string $filename
	 * @param mixed $attributes
	 * @return string
	 */
	public function load($type, $filename, $attributes = '') {
		// validate type parameter
		$type = strtolower($type);
		if (!in_array($type, array('image', 'images', 'icon', 'icons', 'css', 'js'))) {
			log_message('error', "Invalid asset type '" . $type . "' used.");
		}
		if (in_array($type, array('image', 'icon'))) {
			$type .= 's';
		}

		// build the path strings
		$filepath = $this->assetsPath . $type . '/' . $filename;

		if (!file_exists($filepath)) {
			log_message('error', "Unable to load requested asset: $filename");
			return '';
		}

		switch ($type) {
		case 'images':
		case 'icons':
			if (!isset($attributes['alt'])) {
				$attributes['alt'] = '';
			}

			$output = '<img src="' . $this->CI->config->slash_item('base_url') . $filepath . '"' . $this->parseAttributes($attributes) . ' />';
			break;
		case 'css':
			$output = '<link href="' . $this->CI->config->slash_item('base_url') . $filepath . '" rel="stylesheet" type="text/css"' . $this->parseAttributes($attributes) . " />\n";
			break;
		case 'js':
//			$this->javascript[] = array('filepath' => $filepath, 'attributes' => $attributes);

// 			$code = file_get_contents(FCPATH . $this->assetsFolder . $type . '/' . $filename);

// 			if ($this->CI->config->item('enable_jsmin') == true) {
// 				$this->CI->load->library('jsmin');
// 				$code = $this->CI->jsmin->minify($code);
// 			}

//			$httpHeaders = '<' . '?php header("Content-type: text/javascript; charset: UTF-8");?' . '>';

//			if ($this->CI->config->item('compress_output') == true) {
//				$httpHeaders .= '';
//			}
//			$code = $httpHeaders . "\n" . $code;
//			file_put_contents(FCPATH . $this->cacheFolder . $type . '_' . md5($code) . '.php', $code);
			$output = '<script src="' . $this->CI->config->slash_item('base_url') . $filepath . '" language="javascript" type="text/javascript"></script>';
			$output .= "\n";
			break;
		default:
			$output = '';
		}

		return $output;
	}

	/**
	 * Generates an URL of the given asset.
	 *
	 * @param string $type
	 * @param string $filename
	 * @return string
	 */
	public function url($type, $filename) {
		$type = strtolower($type);
		if (!in_array($type, array('image', 'images', 'icon', 'icons', 'css', 'js'))) {
			log_message('error', "'" . $type . "' is not a valid asset type");
			return;
		}
		if (in_array($type, array('image', 'icon'))) {
			$type .= 's';
		}

		return $this->CI->config->slash_item('base_url') . $this->assetsPath . $type . '/' . $filename;
	}

	/**
	 * Parses out the attributes.
	 *
	 * @param mixed $attributes
	 * @return string
	 */
	private function parseAttributes($attributes) {
		if (is_string($attributes)) {
			return ($attributes != '') ? ' '.$attributes : '';
		}

		$output = '';
		foreach ($attributes as $key => $value) {
			$output .= ' ' . $key . '="' . $value . '"';
		}

		return $output;
	}
}

/* End of file Assets.php */
/* Location: ./application/libraries/Assets.php */
