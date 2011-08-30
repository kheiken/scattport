<?php defined('BASEPATH') || exit("No direct script access allowed");

/**
 * Extends CI's form validation class.
 *
 * Supported rules are:
 * unique
 * file_required
 * file_allowed_type[type]
 * file_disallowed_type[type]
 * file_size_min[size]
 * file_size_max[size]
 * file_image_mindim[x,y]
 * file_image_maxdim[x,y]
 *
 * @author Eike Foken <kontakt@eikefoken.de>
 */
class MY_Form_validation extends CI_Form_validation {

	/**
	 * Calls the parent constructor.
	 *
	 * @param array $rules
	 */
	public function __construct($rules = array()) {
		parent::__construct($rules);

		// overwrite default error delimiters
		$this->set_error_delimiters('<p class="error">', '</p>');
	}

	/**
	 * Sets rules.
	 *
	 * @see CI_Form_validation::set_rules()
	 */
	public function set_rules($field, $label = '', $rules = '') {
		// this will prevent the form_validation from working
		if (count($_POST) === 0 && count($_FILES) > 0) {
			// add a dummy $_POST
			$_POST['DUMMY_ITEM'] = '';
			parent::set_rules($field, $label, $rules);
			unset($_POST['DUMMY_ITEM']);
		} else {
			// we are safe just run as is
			parent::set_rules($field, $label, $rules);
		}
	}

	/**
	 * Runs the validator.
	 *
	 * @see CI_Form_validation::run()
	 */
	public function run($group = '') {
		$rc = false;
		log_message('debug', "Called MY_Form_validation::run()");

		// does it have a file only form?
		if (count($_POST) === 0 && count($_FILES) > 0) {
			// add a dummy $_POST
			$_POST['DUMMY_ITEM'] = '';
			$rc = parent::run($group);
			unset($_POST['DUMMY_ITEM']);
		} else {
			// we are safe just run as is
			$rc = parent::run($group);
		}

		return $rc;
	}

	/**
	 * Executes the validation routines.
	 *
	 * @see CI_Form_validation::_execute()
	 */
	function _execute($row, $rules, $postdata = null, $cycles = 0) {
		log_message('debug', "Called MY_Form_validation::_execute() " . $row['field']);

		if (isset($_FILES[$row['field']])) {
			// it is a file so process as a file
			log_message('debug', "Processing as a file");
			$postdata = $_FILES[$row['field']];

			// before doing anything check for errors
			if ($postdata['error'] !== UPLOAD_ERR_OK && $postdata['error'] !== UPLOAD_ERR_NO_FILE) {
				$this->_error_array[$row['field']] = $this->getUploadError($postdata['error']);
				return false;
			}

			$_in_array = false;

			// if the field is blank, but NOT required, no further tests are necessary
			$callback = false;
			if (!in_array('file_required', $rules) && $postdata['error'] === UPLOAD_ERR_NO_FILE) {
				// before we bail out, does the rule contain a callback?
				if (preg_match("/(callback_\w+)/", implode(' ', $rules), $match)) {
					$callback = true;
					$rules = array('1' => $match[1]);
				} else {
					return;
				}
			}

			// partly copied from the original class
			foreach ($rules as $rule) {
				// is the rule a callback?
				$callback = false;
				if (substr($rule, 0, 9) == 'callback_') {
					$rule = substr($rule, 9);
					$callback = true;
				}

				// rules can contain a parameter: max_length[5]
				$param = false;
				if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match)) {
					$rule = $match[1];
					$param = $match[2];
				}

				// call the function that corresponds to the rule
				if ($callback === true) {
					if (!method_exists($this->CI, $rule)) {
						continue;
					}

					// run the function and grab the result
					$result = $this->CI->$rule($postdata, $param);

					// re-assign the result to the master data array
					if ($_in_array == true) {
						$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
					} else {
						$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
					}

					// if the field isn't required and we just processed a callback we'll move on...
					if (!in_array('file_required', $rules, true) && $result !== false) {
						return;
					}
				} else {
					if (!method_exists($this, $rule)) {
						/*
						 * If our own wrapper function doesn't exist we see if a native
						 * PHP function does. Users can use any native PHP function call
						 * that has one param.
						 */
						if (function_exists($rule)) {
							$result = $rule($postdata);

							if ($_in_array == true) {
								$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
							} else {
								$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
							}
						}

						continue;
					}

					$result = $this->$rule($postdata, $param);

					if ($_in_array == true) {
						$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
					} else {
						$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
					}
				}

				// TODO The following line needs testing!!! Not sure if it will work

				// put back the tested values back into $_FILES
				//$_FILES[$row['field']] = $this->_field_data[$row['field']]['postdata'];

				// did the rule test negatively? If so, grab the error.
				if ($result === false) {
					if (!isset($this->_error_messages[$rule])) {
						if (false === ($line = $this->CI->lang->line($rule))) {
							$line = 'Unable to access an error message corresponding to your field name.';
						}
					} else {
						$line = $this->_error_messages[$rule];
					}

					/*
					 * Is the parameter we are inserting into the error message the name
					 * of another field? If so we need to grab it's "field label".
					 */
					if (isset($this->_field_data[$param]) && isset($this->_field_data[$param]['label'])) {
						$param = $this->_field_data[$param]['label'];
					}

					// build the error message
					$message = sprintf($line, $this->_translate_fieldname($row['label']), $param);

					// save the error message
					$this->_field_data[$row['field']]['error'] = $message;

					if (!isset($this->_error_array[$row['field']])) {
						$this->_error_array[$row['field']] = $message;
					}

					return;
				}
			}
		} else {
			log_message('debug', "Called parent::_execute()");
			parent::_execute($row, $rules, $postdata,$cycles);
		}
	}

	/**
	 * Checks if a username or email is unique.
	 *
	 * @param string $value
	 * @param string $params
	 */
	public function unique($value, $params) {
		$CI =& get_instance();

		$this->set_message('unique', _('The %s is already being used.'));

		list($table, $field) = explode(".", $params, 2);

		$query = $CI->db->select($field)->from($table)->where($field, $value)->limit(1)->get();

		if ($query->row()) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Returns the upload error as a human-readable string.
	 *
	 * @param integer $errorCode
	 * @return string
	 */
	private function getUploadError($errorCode) {
		switch ($errorCode) {
			case UPLOAD_ERR_INI_SIZE:
				$error = $this->setError('upload_file_exceeds_limit');
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$error = $this->setError('upload_file_exceeds_form_limit');
				break;
			case UPLOAD_ERR_PARTIAL:
				$error = $this->setError('upload_file_partial');
				break;
			case UPLOAD_ERR_NO_FILE:
				$error = $this->setError('upload_no_file_selected');
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$error = $this->setError('upload_no_temp_directory');
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$error = $this->setError('upload_unable_to_write_file');
				break;
			case UPLOAD_ERR_EXTENSION:
				$error = $this->setError('upload_stopped_by_extension');
				break;
			default:
				$error = _('Unknown upload error');
		}

		return $error;
	}

	/**
	 * Returns the error message of choice.
	 *
	 * The function will use $msg if it cannot find the error in the language file.
	 *
	 * @param string $msg the error message
	 */
	private function setError($msg) {
		$CI =& get_instance();
		$CI->lang->load('upload');

		$msg = ($CI->lang->line($msg) == false) ? $msg : $CI->lang->line($msg);
		log_message('error', $msg);

		return $msg;
	}

	/**
	 * Converts a given string in format of ###AA to the number of bits it is assigning.
	 *
	 * @see http://codeigniter.com/forums/viewthread/123816/P20
	 *
	 * @param string $sValue
	 * @return integer number of bits
	 */
	private function letToBit($sValue) {
		// split value from name
		if (!preg_match('/([0-9]+)([ptgmkb]{1,2}|)/ui', $sValue, $aMatches)) {
			// invalid input
			return false;
		}

		if (empty($aMatches[2])) {
			// no name? Enter default value
			$aMatches[2] = 'KB';
		}

		if (strlen($aMatches[2]) == 1) {
			// shorted name? Full name
			$aMatches[2] .= 'B';
		}

		$iBit = (substr($aMatches[2], -1) == 'B') ? 1024 : 1000;
		// calculate bits
		switch (strtoupper(substr($aMatches[2], 0, 1))) {
			case 'P':
				$aMatches[1] *= $iBit;
			case 'T':
				$aMatches[1] *= $iBit;
			case 'G':
				$aMatches[1] *= $iBit;
			case 'M':
				$aMatches[1] *= $iBit;
			case 'K':
				$aMatches[1] *= $iBit;
				break;
		}

		// return the value in bits
		return $aMatches[1];
	}

	/**
	 * Checks if a required file is uploaded.
	 *
	 * @param mixed $file
	 */
	public function file_required($file) {
		if ($file['size'] === 0) {
			$this->set_message('file_required', _('Uploading a file for %s is required.'));
			return false;
		}
		return true;
	}

	/**
	 * Checks if a file is within expected file size limit.
	 *
	 * @param mixed $file
	 * @param mixed $maxSize
	 */
	public function file_size_max($file, $maxSize) {
		if ($file['size'] > $this->letToBit($maxSize)) {
			$this->set_message('file_size_max', sprintf(_('The selected file is too big. (Maximum allowed is %s)', $maxSize)));
			return false;
		}
		return true;
	}

	/**
	 * Checks if a file is bigger than minimum size.
	 *
	 * @param mixed $file
	 * @param mixed $minSize
	 */
	public function file_size_min($file, $minSize) {
		if ($file['size'] < $this->letToBit($minSize)) {
			$this->set_message('file_size_min', sprintf(_('The selected file is too small. (Minimum allowed is %s)'), $minSize));
			return false;
		}
		return true;
	}

	/**
	 * Checks the file extension for valid file types.
	 *
	 * @param mixed $file
	 * @param mixed $type
	 */
	public function file_allowed_type($file, $type) {
		// is type of format a,b,c,d? -> convert to array
		$exts = explode(',', $type);

		// is $type array? run self recursively
		if (count($exts) > 1) {
			foreach ($exts as $v) {
				$rc = $this->file_allowed_type($file,$v);
				if ($rc === true) {
					return true;
				}
			}
		}

		// is type a group type? image, application, code... -> load proper array
		$extGroups = array();
		$extGroups['image'] = array('jpg', 'jpeg', 'gif', 'png');
		$extGroups['config'] = array('calc', 'par');
		$extGroups['application'] = array('exe', 'dll', 'so', 'cgi');
		$extGroups['php_code'] = array('php', 'php4', 'php5', 'inc', 'phtml');
		$extGroups['compressed'] = array('zip', 'gzip', 'tar', 'gz');

		if (array_key_exists($exts[0], $extGroups)) {
			$exts = $extGroups[$exts[0]];
		}

		// get file ext
		$file_ext = strrchr($file['name'], '.');
		$file_ext = substr(strtolower($file_ext), 1);

		if (!in_array($file_ext, $exts)) {
			$this->set_message('file_allowed_type', sprintf(_('The selected files type should be %s.'), $type));
			return false;
		} else {
			return true;
		}
	}

	/**
	 *
	 * @param mixed $file
	 * @param mixed $type
	 * @return boolean
	 */
	public function file_disallowed_type($file, $type) {
		$rc = $this->file_allowed_type($file, $type);
		if (!$rc) {
			$this->set_message('file_disallowed_type', sprintf(_('The selected file cannot be %s.'), $type));
		}
		return $rc;
	}

	/**
	 * Returns FALSE if image is bigger than the given dimensions.
	 *
	 * @param mixed $file
	 * @param array $dim
	 * @return boolean
	 */
	public function file_image_maxdim($file, $dim) {
		log_message('debug', "MY_Form_validation::file_image_maxdim() " . $dim);
		$dim = explode(',', $dim);

		if (count($dim) !== 2) {
			// bad size given
			$this->set_message('file_image_maxdim', "%s has invalid rule expected similar to 150,300 .");
			return false;
		}

		log_message('debug', 'MY_Form_validation::file_image_maxdim() ' . $dim[0] . ' ' . $dim[1]);

		// get image size
		$d = $this->getImageDimensions($file['tmp_name']);

		log_message('debug', $d[0] . ' ' . $d[1]);

		if (!$d) {
			$this->set_message('file_image_maxdim', "%s dimensions was not detected.");
			return false;
		}

		if ($d[0] < $dim[0] && $d[1] < $dim[1]) {
			return true;
		}

		$this->set_message('file_image_maxdim', "%s image size is too big.");
		return false;
	}

	/**
	 * Returns FALSE is the image is smaller than the given dimension.
	 *
	 * @param mixed $file
	 * @param array $dim
	 * @return boolean
	 */
	public function file_image_mindim($file, $dim) {
		$dim = explode(',', $dim);

		if (count($dim) !== 2) {
			// bad size given
			$this->set_message('file_image_mindim', "%s has invalid rule expected similar to 150,300 .");
			return false;
		}

		// get image size
		$d = $this->getImageDimensions($file['tmp_name']);

		if (!$d) {
			$this->set_message('file_image_mindim', "%s dimensions was not detected.");
			return false;
		}

		log_message('debug', $d[0] . ' ' . $d[1]);

		if ($d[0] > $dim[0] && $d[1] > $dim[1]) {
			return true;
		}

		$this->set_message('file_image_mindim', "%s image size is too big.");
		return false;
	}

	/**
	 * Attempts to determine the image dimension.
	 *
	 * @param mixed $filename The path to the image file
	 * @return mixed
	 */
	private function getImageDimensions($filename) {
		log_message('debug', $filename);

		if (function_exists('getimagesize')) {
			$d = @getimagesize($filename);
			return $d;
		}
		return false;
	}

}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */
