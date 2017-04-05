<?php
/**
 * YogaStudio Framework
 *
 * @package yogastudio
 * @since yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Framework directory path from theme root
if ( ! defined( 'YOGASTUDIO_FW_DIR' ) )		define( 'YOGASTUDIO_FW_DIR', '/fw/' );

// Theme timing
if ( ! defined( 'YOGASTUDIO_START_TIME' ) )	define( 'YOGASTUDIO_START_TIME', microtime());			// Framework start time
if ( ! defined( 'YOGASTUDIO_START_MEMORY' ) )	define( 'YOGASTUDIO_START_MEMORY', memory_get_usage());	// Memory usage before core loading

// Global variables storage

$YOGASTUDIO_GLOBALS = array(
	'theme_slug'	=> 'yogastudio',	// Theme slug (used as prefix for theme's functions, text domain, global variables, etc.)
	'page_template'	=> '',			// Storage for current page template name (used in the inheritance system)
	'widgets_args' => array(		// Arguments to register widgets
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget_title">',
		'after_title'   => '</h5>',
	),
    'allowed_tags'	=> array(		// Allowed tags list (with attributes) in translations
    	'p' => array(),
    	'br' => array(),
    	'b' => array(),
    	'strong' => array(),
    	'i' => array(),
    	'em' => array(),
    	'u' => array(),
    	'a' => array(
			'href' => array(),
			'title' => array(),
			'target' => array(),
			'id' => array(),
			'class' => array()
		),
    	'span' => array(
			'id' => array(),
			'class' => array()
		)
    )	
);

/* Theme setup section
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_loader_theme_setup' ) ) {
	add_action( 'after_setup_theme', 'yogastudio_loader_theme_setup', 20 );
	function yogastudio_loader_theme_setup() {
		
		// Init admin url and nonce
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['admin_url']	= get_admin_url();
		$YOGASTUDIO_GLOBALS['admin_nonce']= wp_create_nonce($YOGASTUDIO_GLOBALS['admin_url']);
		$YOGASTUDIO_GLOBALS['ajax_url']	= admin_url('admin-ajax.php');
		$YOGASTUDIO_GLOBALS['ajax_nonce']	= wp_create_nonce($YOGASTUDIO_GLOBALS['ajax_url']);

		// Before init theme
		do_action('yogastudio_action_before_init_theme');

		// Load current values for main theme options
		yogastudio_load_main_options();

		// Theme core init - only for admin side. In frontend it called from header.php
		if ( is_admin() ) {
			yogastudio_core_init_theme();
		}
	}
}


/* Include core parts
------------------------------------------------------------------------ */

// Manual load important libraries before load all rest files
// core.strings must be first - we use yogastudio_str...() in the yogastudio_get_file_dir()
require_once (file_exists(get_stylesheet_directory().(YOGASTUDIO_FW_DIR).'core/core.strings.php') ? get_stylesheet_directory() : get_template_directory()).(YOGASTUDIO_FW_DIR).'core/core.strings.php';
// core.files must be first - we use yogastudio_get_file_dir() to include all rest parts
require_once (file_exists(get_stylesheet_directory().(YOGASTUDIO_FW_DIR).'core/core.files.php') ? get_stylesheet_directory() : get_template_directory()).(YOGASTUDIO_FW_DIR).'core/core.files.php';


// Include theme variables storage
require_once yogastudio_get_file_dir('core/core.storage.php');

// Include debug and profiler
require_once yogastudio_get_file_dir('core/core.debug.php');

// Include custom theme files
yogastudio_autoload_folder( 'includes' );

// Include core files
yogastudio_autoload_folder( 'core' );

// Include theme-specific plugins and post types
yogastudio_autoload_folder( 'plugins' );

// Include theme templates
yogastudio_autoload_folder( 'templates' );

// Include theme widgets
yogastudio_autoload_folder( 'widgets' );
?>