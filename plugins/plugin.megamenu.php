<?php
/* Mega Main Menu support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_megamenu_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_megamenu_theme_setup', 1 );
	function yogastudio_megamenu_theme_setup() {
		if (yogastudio_exists_megamenu()) {
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',				'yogastudio_megamenu_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',		'yogastudio_megamenu_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',					'yogastudio_megamenu_required_plugins' );
		}
	}
}

// Check if MegaMenu installed and activated
if ( !function_exists( 'yogastudio_exists_megamenu' ) ) {
	function yogastudio_exists_megamenu() {
		return class_exists('mega_main_init');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_megamenu_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_megamenu_required_plugins');
	function yogastudio_megamenu_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('mega_main_menu', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Mega Main Menu',
					'slug' 		=> 'mega_main_menu',
					'source'	=> yogastudio_get_file_dir('plugins/install/mega_main_menu.zip'),
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mega Menu in the required plugins
if ( !function_exists( 'yogastudio_megamenu_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_megamenu_importer_required_plugins', 10, 2 );
	function yogastudio_megamenu_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('mega_main_menu', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_megamenu())
		if (yogastudio_strpos($list, 'mega_main_menu')!==false && !yogastudio_exists_megamenu())
			$not_installed .= '<br>Mega Main Menu';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_megamenu_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_megamenu_importer_set_options' );
	function yogastudio_megamenu_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('mega_main_menu', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_megamenu() ) {
			$options['additional_options'][] = 'mega_main_menu_options';		// Add slugs to export options for this plugin

		}
		return $options;
	}
}
?>