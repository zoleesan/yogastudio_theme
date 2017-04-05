<?php
//####################################################
//#### Inheritance system (for internal use only) #### 
//####################################################

// Add item to the inheritance settings
if ( !function_exists( 'yogastudio_add_theme_inheritance' ) ) {
	function yogastudio_add_theme_inheritance($options, $append=true) {
		global $YOGASTUDIO_GLOBALS;
		if (!isset($YOGASTUDIO_GLOBALS["inheritance"])) $YOGASTUDIO_GLOBALS["inheritance"] = array();
		$YOGASTUDIO_GLOBALS['inheritance'] = $append 
			? yogastudio_array_merge($YOGASTUDIO_GLOBALS['inheritance'], $options) 
			: yogastudio_array_merge($options, $YOGASTUDIO_GLOBALS['inheritance']);
	}
}



// Return inheritance settings
if ( !function_exists( 'yogastudio_get_theme_inheritance' ) ) {
	function yogastudio_get_theme_inheritance($key = '') {
		global $YOGASTUDIO_GLOBALS;
		return $key ? $YOGASTUDIO_GLOBALS['inheritance'][$key] : $YOGASTUDIO_GLOBALS['inheritance'];
	}
}



// Detect inheritance key for the current mode
if ( !function_exists( 'yogastudio_detect_inheritance_key' ) ) {
	function yogastudio_detect_inheritance_key() {
		static $inheritance_key = '';
		if (!empty($inheritance_key)) return $inheritance_key;
		$key = apply_filters('yogastudio_filter_detect_inheritance_key', '');
		global $YOGASTUDIO_GLOBALS;
		if (empty($YOGASTUDIO_GLOBALS['pre_query'])) $inheritance_key = $key;
		return $key;
	}
}


// Return key for override parameter
if ( !function_exists( 'yogastudio_get_override_key' ) ) {
	function yogastudio_get_override_key($value, $by) {
		$key = '';
		$inheritance = yogastudio_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v[$by]) && in_array($value, $v[$by])) {
					$key = $by=='taxonomy' 
						? $value
						: (!empty($v['override']) ? $v['override'] : $k);
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for categories) by post_type from inheritance array
if ( !function_exists( 'yogastudio_get_taxonomy_categories_by_post_type' ) ) {
	function yogastudio_get_taxonomy_categories_by_post_type($value) {
		$key = '';
		$inheritance = yogastudio_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach ($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy']) ? $v['taxonomy'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}


// Return taxonomy (for tags) by post_type from inheritance array
if ( !function_exists( 'yogastudio_get_taxonomy_tags_by_post_type' ) ) {
	function yogastudio_get_taxonomy_tags_by_post_type($value) {
		$key = '';
		$inheritance = yogastudio_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			foreach($inheritance as $k=>$v) {
				if (!empty($v['post_type']) && in_array($value, $v['post_type'])) {
					$key = !empty($v['taxonomy_tags']) ? $v['taxonomy_tags'][0] : '';
					break;
				}
			}
		}
		return $key;
	}
}
?>