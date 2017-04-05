<?php
/**
 * IronFIT Framework: theme variables storage
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('yogastudio_storage_get')) {
	function yogastudio_storage_get($var_name, $default='') {
		global $YOGASTUDIO_STORAGE;
		return isset($YOGASTUDIO_STORAGE[$var_name]) ? $YOGASTUDIO_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('yogastudio_storage_set')) {
	function yogastudio_storage_set($var_name, $value) {
		global $YOGASTUDIO_STORAGE;
		$YOGASTUDIO_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('yogastudio_storage_empty')) {
	function yogastudio_storage_empty($var_name, $key='', $key2='') {
		global $YOGASTUDIO_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($YOGASTUDIO_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($YOGASTUDIO_STORAGE[$var_name][$key]);
		else
			return empty($YOGASTUDIO_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('yogastudio_storage_isset')) {
	function yogastudio_storage_isset($var_name, $key='', $key2='') {
		global $YOGASTUDIO_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($YOGASTUDIO_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($YOGASTUDIO_STORAGE[$var_name][$key]);
		else
			return isset($YOGASTUDIO_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('yogastudio_storage_inc')) {
	function yogastudio_storage_inc($var_name, $value=1) {
		global $YOGASTUDIO_STORAGE;
		if (empty($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = 0;
		$YOGASTUDIO_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('yogastudio_storage_concat')) {
	function yogastudio_storage_concat($var_name, $value) {
		global $YOGASTUDIO_STORAGE;
		if (empty($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = '';
		$YOGASTUDIO_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('yogastudio_storage_get_array')) {
	function yogastudio_storage_get_array($var_name, $key, $key2='', $default='') {
		global $YOGASTUDIO_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($YOGASTUDIO_STORAGE[$var_name][$key]) ? $YOGASTUDIO_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($YOGASTUDIO_STORAGE[$var_name][$key][$key2]) ? $YOGASTUDIO_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('yogastudio_storage_set_array')) {
	function yogastudio_storage_set_array($var_name, $key, $value) {
		global $YOGASTUDIO_STORAGE;
		if (!isset($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = array();
		if ($key==='')
			$YOGASTUDIO_STORAGE[$var_name][] = $value;
		else
			$YOGASTUDIO_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('yogastudio_storage_set_array2')) {
	function yogastudio_storage_set_array2($var_name, $key, $key2, $value) {
		global $YOGASTUDIO_STORAGE;
		if (!isset($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = array();
		if (!isset($YOGASTUDIO_STORAGE[$var_name][$key])) $YOGASTUDIO_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$YOGASTUDIO_STORAGE[$var_name][$key][] = $value;
		else
			$YOGASTUDIO_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('yogastudio_storage_set_array_after')) {
	function yogastudio_storage_set_array_after($var_name, $after, $key, $value='') {
		global $YOGASTUDIO_STORAGE;
		if (!isset($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = array();
		if (is_array($key))
			yogastudio_array_insert_after($YOGASTUDIO_STORAGE[$var_name], $after, $key);
		else
			yogastudio_array_insert_after($YOGASTUDIO_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('yogastudio_storage_set_array_before')) {
	function yogastudio_storage_set_array_before($var_name, $before, $key, $value='') {
		global $YOGASTUDIO_STORAGE;
		if (!isset($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = array();
		if (is_array($key))
			yogastudio_array_insert_before($YOGASTUDIO_STORAGE[$var_name], $before, $key);
		else
			yogastudio_array_insert_before($YOGASTUDIO_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('yogastudio_storage_push_array')) {
	function yogastudio_storage_push_array($var_name, $key, $value) {
		global $YOGASTUDIO_STORAGE;
		if (!isset($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($YOGASTUDIO_STORAGE[$var_name], $value);
		else {
			if (!isset($YOGASTUDIO_STORAGE[$var_name][$key])) $YOGASTUDIO_STORAGE[$var_name][$key] = array();
			array_push($YOGASTUDIO_STORAGE[$var_name][$key], $value);
		}
	}
}

// Pop element from array
if (!function_exists('yogastudio_storage_pop_array')) {
	function yogastudio_storage_pop_array($var_name, $key='', $defa='') {
		global $YOGASTUDIO_STORAGE;
		$rez = $defa;
		if ($key==='') {
			if (isset($YOGASTUDIO_STORAGE[$var_name]) && is_array($YOGASTUDIO_STORAGE[$var_name]) && count($YOGASTUDIO_STORAGE[$var_name]) > 0) 
				$rez = array_pop($YOGASTUDIO_STORAGE[$var_name]);
		} else {
			if (isset($YOGASTUDIO_STORAGE[$var_name][$key]) && is_array($YOGASTUDIO_STORAGE[$var_name][$key]) && count($YOGASTUDIO_STORAGE[$var_name][$key]) > 0) 
				$rez = array_pop($YOGASTUDIO_STORAGE[$var_name][$key]);
		}
		return $rez;
	}
}

// Inc/Dec array element with specified value
if (!function_exists('yogastudio_storage_inc_array')) {
	function yogastudio_storage_inc_array($var_name, $key, $value=1) {
		global $YOGASTUDIO_STORAGE;
		if (!isset($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = array();
		if (empty($YOGASTUDIO_STORAGE[$var_name][$key])) $YOGASTUDIO_STORAGE[$var_name][$key] = 0;
		$YOGASTUDIO_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('yogastudio_storage_concat_array')) {
	function yogastudio_storage_concat_array($var_name, $key, $value) {
		global $YOGASTUDIO_STORAGE;
		if (!isset($YOGASTUDIO_STORAGE[$var_name])) $YOGASTUDIO_STORAGE[$var_name] = array();
		if (empty($YOGASTUDIO_STORAGE[$var_name][$key])) $YOGASTUDIO_STORAGE[$var_name][$key] = '';
		$YOGASTUDIO_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('yogastudio_storage_call_obj_method')) {
	function yogastudio_storage_call_obj_method($var_name, $method, $param=null) {
		global $YOGASTUDIO_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($YOGASTUDIO_STORAGE[$var_name]) ? $YOGASTUDIO_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($YOGASTUDIO_STORAGE[$var_name]) ? $YOGASTUDIO_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('yogastudio_storage_get_obj_property')) {
	function yogastudio_storage_get_obj_property($var_name, $prop, $default='') {
		global $YOGASTUDIO_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($YOGASTUDIO_STORAGE[$var_name]->$prop) ? $YOGASTUDIO_STORAGE[$var_name]->$prop : $default;
	}
}
?>