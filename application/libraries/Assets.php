<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Assets {

	private $assetsFolder;
	private $cacheFolder;

	private $css = array();
	private $javascript = array();

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
