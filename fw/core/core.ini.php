<?php
/**
 * YogaStudio Framework: ini-files manipulations
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


//  Get value by name from .ini-file
if (!function_exists('yogastudio_ini_get_value')) {
	function yogastudio_ini_get_value($file, $name, $defa='') {
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = yogastudio_fga($file);
			} else
				return $defa;
		}
		$name = yogastudio_strtolower($name);
		$rez = $defa;
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim($file[$i]);
			if (($pos = yogastudio_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(yogastudio_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			if (yogastudio_strtolower(trim(chop($parts[0])))==$name) {
				$rez = trim(chop($parts[1]));
				if (yogastudio_substr($rez, 0, 1)=='"')
					$rez = yogastudio_substr($rez, 1, yogastudio_strlen($rez)-2);
				else
					$rez *= 1;
				break;
			}
		}
		return $rez;
	}
}

//  Retrieve all values from .ini-file as assoc array
if (!function_exists('yogastudio_ini_get_values')) {
	function yogastudio_ini_get_values($file) {
		$rez = array();
		if (!is_array($file)) {
			if (file_exists($file)) {
				$file = yogastudio_fga($file);
			} else
				return $rez;
		}
		for ($i=0; $i<count($file); $i++) {
			$file[$i] = trim(chop($file[$i]));
			if (($pos = yogastudio_strpos($file[$i], ';'))!==false)
				$file[$i] = trim(yogastudio_substr($file[$i], 0, $pos));
			$parts = explode('=', $file[$i]);
			if (count($parts)!=2) continue;
			$key = trim(chop($parts[0]));
			$rez[$key] = trim($parts[1]);
			if (yogastudio_substr($rez[$key], 0, 1)=='"')
				$rez[$key] = yogastudio_substr($rez[$key], 1, yogastudio_strlen($rez[$key])-2);
			else
				$rez[$key] *= 1;
		}
		return $rez;
	}
}
?>