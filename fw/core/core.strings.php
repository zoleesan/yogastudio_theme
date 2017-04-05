<?php
/**
 * YogaStudio Framework: strings manipulations
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'YOGASTUDIO_MULTIBYTE' ) ) define( 'YOGASTUDIO_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('yogastudio_strlen')) {
	function yogastudio_strlen($text) {
		return YOGASTUDIO_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('yogastudio_strpos')) {
	function yogastudio_strpos($text, $char, $from=0) {
		return YOGASTUDIO_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('yogastudio_strrpos')) {
	function yogastudio_strrpos($text, $char, $from=0) {
		return YOGASTUDIO_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('yogastudio_substr')) {
	function yogastudio_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = yogastudio_strlen($text)-$from;
		}
		return YOGASTUDIO_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('yogastudio_strtolower')) {
	function yogastudio_strtolower($text) {
		return YOGASTUDIO_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('yogastudio_strtoupper')) {
	function yogastudio_strtoupper($text) {
		return YOGASTUDIO_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('yogastudio_strtoproper')) {
	function yogastudio_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<yogastudio_strlen($text); $i++) {
			$ch = yogastudio_substr($text, $i, 1);
			$rez .= yogastudio_strpos(' .,:;?!()[]{}+=', $last)!==false ? yogastudio_strtoupper($ch) : yogastudio_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('yogastudio_strrepeat')) {
	function yogastudio_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('yogastudio_strshort')) {
	function yogastudio_strshort($str, $maxlength, $add='...') {
	//	if ($add && yogastudio_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength < 1 || $maxlength >= yogastudio_strlen($str)) 
			return strip_tags($str);
		$str = yogastudio_substr(strip_tags($str), 0, $maxlength - yogastudio_strlen($add));
		$ch = yogastudio_substr($str, $maxlength - yogastudio_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = yogastudio_strlen($str) - 1; $i > 0; $i--)
				if (yogastudio_substr($str, $i, 1) == ' ') break;
			$str = trim(yogastudio_substr($str, 0, $i));
		}
		if (!empty($str) && yogastudio_strpos(',.:;-', yogastudio_substr($str, -1))!==false) $str = yogastudio_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('yogastudio_strclear')) {
	function yogastudio_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (yogastudio_substr($text, 0, yogastudio_strlen($open))==$open) {
					$pos = yogastudio_strpos($text, '>');
					if ($pos!==false) $text = yogastudio_substr($text, $pos+1);
				}
				if (yogastudio_substr($text, -yogastudio_strlen($close))==$close) $text = yogastudio_substr($text, 0, yogastudio_strlen($text) - yogastudio_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('yogastudio_get_slug')) {
	function yogastudio_get_slug($title) {
		return yogastudio_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('yogastudio_strmacros')) {
	function yogastudio_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('yogastudio_unserialize')) {
	function yogastudio_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>