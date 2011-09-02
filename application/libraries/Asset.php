<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class Asset {

	private $assetsFolder;
	private $cacheFolder;

	private $CI;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->config('assets');

		$this->assetsFolder = $this->CI->config->item('assets_folder');
		$this->cacheFolder = $this->CI->config->item('cache_folder');

		log_message('debug', "Asset Class Initialized");
	}

	/**
	 * Loads an asset.
	 *
	 * @param string $type
	 * @param string $filename
	 */
	public function load($type, $filename, $attributes = array()) {
		// validate type parameter
		if (!empty($type)) {
			$type = strtolower($type);
			if (!in_array($type, array('image', 'images', 'icon', 'icons', 'css', 'js'))) {
				log_message('error', "Invalid asset type '" . $type . "' used.");
			}
			if (in_array($type, array('image', 'icon'))) {
				$type .= 's';
			}
		}

		// convert filename to array
		if (!empty($filename)) {
			$link = $this->CI->config->slash_item('base_url') . $this->assetsFolder . $type . '/' . $filename;
			$path = FCPATH . $this->assetsFolder . $type . '/' . $filename;
		}

		switch ($type) {
		case 'images':
		case 'icons':
			if (!isset($attributes['alt'])) {
				$attributes['alt'] = '';
			}

			$output = '<img src="' . $link . '"';

			foreach ($attributes as $key => $value) {
				$output .= " $key=\"$value\"";
			}

			$output .= ' />';
			break;
		case 'css':
			$output = '<link href="' . $link . '" rel="stylesheet" type="text/css"';
			foreach ($attributes as $key => $value) {
				$output .= " $key=\"$value\"";
			}
			$output .= " />\n";
			break;
		case 'js':
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
//			file_put_contents(FCPATH . $this->cacheFolder . $type . '_' . md5($code) . EXT, $code);

			$output = '<script src="' . $link . '" language="javascript" type="text/javascript"></script>';
			$output .= "\n";
			break;
		default:
			$output = '';
		}

		return $output;
	}
}

/* End of file Assets.php */
/* Location: ./application/libraries/Assets.php */
