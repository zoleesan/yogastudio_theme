<?php
/**
 * YogaStudio Framework: global variables storage
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get global variable
if (!function_exists('yogastudio_get_global')) {
	function yogastudio_get_global($var_name) {
		global $YOGASTUDIO_GLOBALS;
		return isset($YOGASTUDIO_GLOBALS[$var_name]) ? $YOGASTUDIO_GLOBALS[$var_name] : '';
	}
}

// Set global variable
if (!function_exists('yogastudio_set_global')) {
	function yogastudio_set_global($var_name, $value) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS[$var_name] = $value;
	}
}

// Inc/Dec global variable with specified value
if (!function_exists('yogastudio_inc_global')) {
	function yogastudio_inc_global($var_name, $value=1) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS[$var_name] += $value;
	}
}

// Concatenate global variable with specified value
if (!function_exists('yogastudio_concat_global')) {
	function yogastudio_concat_global($var_name, $value) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS[$var_name] .= $value;
	}
}

// Get global array element
if (!function_exists('yogastudio_get_global_array')) {
	function yogastudio_get_global_array($var_name, $key) {
		global $YOGASTUDIO_GLOBALS;
		return isset($YOGASTUDIO_GLOBALS[$var_name][$key]) ? $YOGASTUDIO_GLOBALS[$var_name][$key] : '';
	}
}

// Set global array element
if (!function_exists('yogastudio_set_global_array')) {
	function yogastudio_set_global_array($var_name, $key, $value) {
		global $YOGASTUDIO_GLOBALS;
		if (!isset($YOGASTUDIO_GLOBALS[$var_name])) $YOGASTUDIO_GLOBALS[$var_name] = array();
		$YOGASTUDIO_GLOBALS[$var_name][$key] = $value;
	}
}

// Inc/Dec global array element with specified value
if (!function_exists('yogastudio_inc_global_array')) {
	function yogastudio_inc_global_array($var_name, $key, $value=1) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS[$var_name][$key] += $value;
	}
}

// Concatenate global array element with specified value
if (!function_exists('yogastudio_concat_global_array')) {
	function yogastudio_concat_global_array($var_name, $key, $value) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS[$var_name][$key] .= $value;
	}
}
?>