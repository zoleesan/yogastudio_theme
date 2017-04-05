<?php
/* Instagram Widget support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_instagram_widget_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_instagram_widget_theme_setup', 1 );
	function yogastudio_instagram_widget_theme_setup() {
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',		'yogastudio_instagram_widget_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',					'yogastudio_instagram_widget_required_plugins' );
		}
	}
}

// Check if Instagram Widget installed and activated
if ( !function_exists( 'yogastudio_exists_instagram_widget' ) ) {
	function yogastudio_exists_instagram_widget() {
		return yogastudio_widget_is_active('wp-instagram-widget/wp-instagram-widget');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_instagram_widget_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_instagram_widget_required_plugins');
	function yogastudio_instagram_widget_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('instagram_widget', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Instagram Widget',
					'slug' 		=> 'wp-instagram-widget',
					'required' 	=> false
				);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Instagram Widget in the required plugins
if ( !function_exists( 'yogastudio_instagram_widget_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_instagram_widget_importer_required_plugins', 10, 2 );
	function yogastudio_instagram_widget_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('instagram_widget', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_instagram_widget() )
		if (yogastudio_strpos($list, 'instagram_widget')!==false && !yogastudio_exists_instagram_widget() )
			$not_installed .= '<br>WP Instagram Widget';
		return $not_installed;
	}
}
?>