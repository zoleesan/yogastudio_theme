<?php
/* Instagram Feed support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_instagram_feed_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_instagram_feed_theme_setup', 1 );
	function yogastudio_instagram_feed_theme_setup() {
		if (yogastudio_exists_instagram_feed()) {
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',				'yogastudio_instagram_feed_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',		'yogastudio_instagram_feed_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',					'yogastudio_instagram_feed_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'yogastudio_exists_instagram_feed' ) ) {
	function yogastudio_exists_instagram_feed() {
		return defined('SBIVER');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_instagram_feed_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_instagram_feed_required_plugins');
	function yogastudio_instagram_feed_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('instagram_feed', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Instagram Feed',
					'slug' 		=> 'instagram-feed',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Feed in the required plugins
if ( !function_exists( 'yogastudio_instagram_feed_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_instagram_feed_importer_required_plugins', 10, 2 );
	function yogastudio_instagram_feed_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('instagram_feed', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_instagram_feed() )
		if (yogastudio_strpos($list, 'instagram_feed')!==false && !yogastudio_exists_instagram_feed() )
			$not_installed .= '<br>Instagram Feed';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_instagram_feed_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_instagram_feed_importer_set_options' );
	function yogastudio_instagram_feed_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('instagram_feed', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_instagram_feed() ) {
			$options['additional_options'][] = 'sb_instagram_settings';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}
?>