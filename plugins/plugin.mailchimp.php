<?php
/* Mail Chimp support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_mailchimp_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_mailchimp_theme_setup', 1 );
	function yogastudio_mailchimp_theme_setup() {
		if (yogastudio_exists_mailchimp()) {
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',				'yogastudio_mailchimp_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',		'yogastudio_mailchimp_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',					'yogastudio_mailchimp_required_plugins' );
		}
	}
}

// Check if Instagram Feed installed and activated
if ( !function_exists( 'yogastudio_exists_mailchimp' ) ) {
	function yogastudio_exists_mailchimp() {
		return function_exists('mc4wp_load_plugin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_mailchimp_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_mailchimp_required_plugins');
	function yogastudio_mailchimp_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('mailchimp', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
				'name' 		=> 'MailChimp for WP',
				'slug' 		=> 'mailchimp-for-wp',
				'required' 	=> false
			);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check Mail Chimp in the required plugins
if ( !function_exists( 'yogastudio_mailchimp_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_mailchimp_importer_required_plugins', 10, 2 );
	function yogastudio_mailchimp_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('mailchimp', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_mailchimp() )
		if (yogastudio_strpos($list, 'mailchimp')!==false && !yogastudio_exists_mailchimp() )
			$not_installed .= '<br>Mail Chimp';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_mailchimp_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_mailchimp_importer_set_options' );
	function yogastudio_mailchimp_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('mailchimp', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_mailchimp() ) {
			$options['additional_options'][] = 'mc4wp_lite_checkbox';		// Add slugs to export options for this plugin
			$options['additional_options'][] = 'mc4wp_lite_form';
		}
		return $options;
	}
}
?>