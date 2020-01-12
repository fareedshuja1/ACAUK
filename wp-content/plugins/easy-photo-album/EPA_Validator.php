<?php

/**
 * Class that holds some validation functions.
 * Mostly for EPA settings.
 *
 * @author TV productions
 * @package EasyPhotoAlbum
 * @since 1.3
 *
 */
class EPA_Validator {

	/**
	 * Returns the nummeric value if there is any in $var.
	 * Else $default will be returned.
	 *
	 * @param mixed $var
	 * @param number $min
	 * @param number $default
	 * @return number
	 *
	 * @since 1.3
	 */
	public function validate_nummeric($var, $min = 0, $default = 0) {
		if (is_numeric ( $var ) && intval ( $var ) >= $min)
			return intval ( $var );
		return $default;
	}

	/**
	 * Checks for a key in the array and if it exists, it should contain the value
	 * <code>'true'</code>
	 *
	 * @param array $array
	 * @param mixed $key
	 * @return boolean
	 *
	 * @since 1.3
	 */
	public function validate_checkbox(&$array, $key) {
		if (isset ( $array [$key] )) {
			return $array [$key] == 'true';
		}
		return false;
	}

	/**
	 * Returns the value of the key of an array or $default if not set.
	 *
	 * @param array $array
	 * @param mixed $key
	 * @param mixed $default
	 * @return unknown $default when the key isn't set.
	 *
	 * @since 1.3
	 */
	public function get_if_set(&$array, $key, $default = '') {
		if (isset ( $array [$key] )) {
			return $array [$key];
		}
		return $default;
	}
}
