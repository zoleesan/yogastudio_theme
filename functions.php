<?php
/**
 * Theme sprecific functions and definitions
 */


/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */

// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'yogastudio_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_theme_setup', 1 );
	function yogastudio_theme_setup() {

		// Register theme menus
		add_filter( 'yogastudio_filter_add_theme_menus',		'yogastudio_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'yogastudio_filter_add_theme_sidebars',	'yogastudio_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'yogastudio_filter_importer_options',		'yogastudio_set_importer_options' );

		// Set list of the theme required plugins
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['required_plugins'] = array(
			'booked',
			'essgrids',
			'instagram_widget',
			'revslider',
			'trx_utils',
			'visual_composer',
			'woocommerce'
		);
		
	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'yogastudio_add_theme_menus' ) ) {
	function yogastudio_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'yogastudio_add_theme_sidebars' ) ) {
	function yogastudio_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'yogastudio' ),
				// 'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'yogastudio' ),
				'sidebar_left'	=> esc_html__( 'Left Sidebar', 'yogastudio' ),
				'sidebar_rightwoo'	=> esc_html__( 'Right WooCommerce Sidebar', 'yogastudio' )
			);
			if (function_exists('yogastudio_exists_woocommerce') && yogastudio_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'yogastudio' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Set theme specific importer options
if ( !function_exists( 'yogastudio_set_importer_options' ) ) {
	function yogastudio_set_importer_options($options=array()) {
		if (is_array($options)) {
			$options['debug'] = yogastudio_get_theme_option('debug_mode')=='yes';
			$options['domain_dev'] = esc_url('yogastudio.dv.ancorathemes.com');
			$options['domain_demo'] = esc_url('yogastudio.ancorathemes.com');
			$options['menus'] = array(
				'menu-main'	  => esc_html__('Main menu', 'yogastudio'),
				'menu-user'	  => esc_html__('User menu', 'yogastudio'),
				'menu-footer' => esc_html__('Footer menu', 'yogastudio'),
				'menu-outer'  => esc_html__('Main menu', 'yogastudio')
			);
		}
		return $options;
	}
}


/* Include framework core files
------------------------------------------------------------------- */
// If now is WP Heartbeat call - skip loading theme core files
	require_once get_template_directory().'/fw/loader.php';
?>