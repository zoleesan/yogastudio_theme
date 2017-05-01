<?php
/**
 * YogaStudio Framework: Theme specific actions
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_core_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_core_theme_setup', 11 );
	function yogastudio_core_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array('header-text'=>false));
		
		// Custom backgrounds setup
		add_theme_support( 'custom-background');
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add user menu
		add_theme_support('nav-menus');
		
		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Editor custom stylesheet - for user
		add_editor_style(yogastudio_get_file_url('css/editor-style.css'));	
		
		// Make theme available for translation
		// Translations can be filed in the /languages/ directory
		load_theme_textdomain( 'yogastudio', yogastudio_get_folder_dir('languages') );


		/* Front and Admin actions and filters:
		------------------------------------------------------------------------ */

		if ( !is_admin() ) {
			
			/* Front actions and filters:
			------------------------------------------------------------------------ */
	
			// Filters wp_title to print a neat <title> tag based on what is being viewed
			if (floatval(get_bloginfo('version')) < "4.1") {
				add_filter('wp_title',						'yogastudio_wp_title', 10, 2);
			}

			// Add main menu classes
			//add_filter('wp_nav_menu_objects', 			'yogastudio_add_mainmenu_classes', 10, 2);
	
			// Prepare logo text
			add_filter('yogastudio_filter_prepare_logo_text',	'yogastudio_prepare_logo_text', 10, 1);
	
			// Add class "widget_number_#' for each widget
			add_filter('dynamic_sidebar_params', 			'yogastudio_add_widget_number', 10, 1);

			// Frontend editor: Save post data
			add_action('wp_ajax_frontend_editor_save',		'yogastudio_callback_frontend_editor_save');
			add_action('wp_ajax_nopriv_frontend_editor_save', 'yogastudio_callback_frontend_editor_save');

			// Frontend editor: Delete post
			add_action('wp_ajax_frontend_editor_delete', 	'yogastudio_callback_frontend_editor_delete');
			add_action('wp_ajax_nopriv_frontend_editor_delete', 'yogastudio_callback_frontend_editor_delete');
	
			// Enqueue scripts and styles
			add_action('wp_enqueue_scripts', 				'yogastudio_core_frontend_scripts');
			add_action('wp_footer',		 					'yogastudio_core_frontend_scripts_inline');
			add_action('yogastudio_action_add_scripts_inline','yogastudio_core_add_scripts_inline');

			// Prepare theme core global variables
			add_action('yogastudio_action_prepare_globals',	'yogastudio_core_prepare_globals');

		}

		// Register theme specific nav menus
		yogastudio_register_theme_menus();

		// Register theme specific sidebars
		yogastudio_register_theme_sidebars();
	}
}




/* Theme init
------------------------------------------------------------------------ */

// Init theme template
function yogastudio_core_init_theme() {
	global $YOGASTUDIO_GLOBALS;
	if (!empty($YOGASTUDIO_GLOBALS['theme_inited'])) return;
	$YOGASTUDIO_GLOBALS['theme_inited'] = true;

	// Load custom options from GET and post/page/cat options
	if (isset($_GET['set']) && $_GET['set']==1) {
		foreach ($_GET as $k=>$v) {
			if (yogastudio_get_theme_option($k, null) !== null) {
				setcookie($k, $v, 0, '/');
				$_COOKIE[$k] = $v;
			}
		}
	}

	// Get custom options from current category / page / post / shop / event
	yogastudio_load_custom_options();

	// Load skin
	$skin = yogastudio_esc(yogastudio_get_custom_option('theme_skin'));
	$YOGASTUDIO_GLOBALS['theme_skin'] = $skin;
	if ( file_exists(yogastudio_get_file_dir('skins/'.($skin).'/skin.php')) ) {
		require_once yogastudio_get_file_dir('skins/'.($skin).'/skin.php');
	}

	// Fire init theme actions (after skin and custom options are loaded)
	do_action('yogastudio_action_init_theme');

	// Prepare theme core global variables
	do_action('yogastudio_action_prepare_globals');

	// Fire after init theme actions
	do_action('yogastudio_action_after_init_theme');
}


// Prepare theme global variables
if ( !function_exists( 'yogastudio_core_prepare_globals' ) ) {
	function yogastudio_core_prepare_globals() {
		if (!is_admin()) {
			// AJAX Queries settings
			global $YOGASTUDIO_GLOBALS;
		
			// Logo text and slogan
			$YOGASTUDIO_GLOBALS['logo_text'] = apply_filters('yogastudio_filter_prepare_logo_text', yogastudio_get_custom_option('logo_text'));
			$YOGASTUDIO_GLOBALS['logo_slogan'] = get_bloginfo ( 'description' );
			
			// Logo image and icons from skin
			$logo_side   = yogastudio_get_logo_icon('logo_side');
			$logo_fixed  = yogastudio_get_logo_icon('logo_fixed');
			$logo_footer = yogastudio_get_logo_icon('logo_footer');
			$YOGASTUDIO_GLOBALS['logo']        = yogastudio_get_logo_icon('logo');
			$YOGASTUDIO_GLOBALS['logo_icon']   = yogastudio_get_logo_icon('logo_icon');
			$YOGASTUDIO_GLOBALS['logo_side']   = $logo_side   ? $logo_side   : $YOGASTUDIO_GLOBALS['logo'];
			$YOGASTUDIO_GLOBALS['logo_fixed']  = $logo_fixed  ? $logo_fixed  : $YOGASTUDIO_GLOBALS['logo'];
			$YOGASTUDIO_GLOBALS['logo_footer'] = $logo_footer ? $logo_footer : $YOGASTUDIO_GLOBALS['logo'];
	
			$shop_mode = '';
			if (yogastudio_get_custom_option('show_mode_buttons')=='yes')
				$shop_mode = yogastudio_get_value_gpc('yogastudio_shop_mode');
			if (empty($shop_mode))
				$shop_mode = yogastudio_get_custom_option('shop_mode', '');
			if (empty($shop_mode) || !is_archive())
				$shop_mode = 'thumbs';
			$YOGASTUDIO_GLOBALS['shop_mode'] = $shop_mode;
		}
	}
}


// Return url for the uploaded logo image or (if not uploaded) - to image from skin folder
if ( !function_exists( 'yogastudio_get_logo_icon' ) ) {
	function yogastudio_get_logo_icon($slug) {
		$mult = yogastudio_get_retina_multiplier();
		$logo_icon = '';
		if ($mult > 1) 			$logo_icon = yogastudio_get_custom_option($slug.'_retina');
		if (empty($logo_icon))	$logo_icon = yogastudio_get_custom_option($slug);
		return $logo_icon;
	}
}


// Display logo image with text and slogan (if specified)
if ( !function_exists( 'yogastudio_show_logo' ) ) {
	function yogastudio_show_logo($logo_main=true, $logo_fixed=false, $logo_footer=false, $logo_side=false, $logo_text=true, $logo_slogan=true) {
		global $YOGASTUDIO_GLOBALS;
		if ($logo_main===true)		$logo_main = $YOGASTUDIO_GLOBALS['logo'];
		if ($logo_fixed===true)		$logo_fixed = $YOGASTUDIO_GLOBALS['logo_fixed'];
		if ($logo_footer===true)	$logo_footer = $YOGASTUDIO_GLOBALS['logo_footer'];
		if ($logo_side===true)		$logo_side = $YOGASTUDIO_GLOBALS['logo_side'];
		if ($logo_text===true)		$logo_text = $YOGASTUDIO_GLOBALS['logo_text'];
		if ($logo_slogan===true)	$logo_slogan = $YOGASTUDIO_GLOBALS['logo_slogan'];
		if ($logo_main || $logo_fixed || $logo_footer || $logo_side || $logo_text) {
		?>
		<div class="logo">
			<a href="<?php echo esc_url(home_url('/')); ?>"><?php
				if (!empty($logo_main)) {
					$attr = yogastudio_getimagesize($logo_main);
					echo '<img src="'.esc_url($logo_main).'" class="logo_main" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_fixed)) {
					$attr = yogastudio_getimagesize($logo_fixed);
					echo '<img src="'.esc_url($logo_fixed).'" class="logo_fixed" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_footer)) {
					$attr = yogastudio_getimagesize($logo_footer);
					echo '<img src="'.esc_url($logo_footer).'" class="logo_footer" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				if (!empty($logo_side)) {
					$attr = yogastudio_getimagesize($logo_side);
					echo '<img src="'.esc_url($logo_side).'" class="logo_side" alt=""'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>';
				}
				echo !empty($logo_text) ? '<div class="logo_text">'.trim($logo_text).'</div>' : '';
			?></a>
		</div>
		<?php 
		}
	} 
}


// Add menu locations
if ( !function_exists( 'yogastudio_register_theme_menus' ) ) {
	function yogastudio_register_theme_menus() {
		register_nav_menus(apply_filters('yogastudio_filter_add_theme_menus', array(
			'menu_main'		=> esc_html__('Main Menu', 'yogastudio'),
			'menu_user'		=> esc_html__('User Menu', 'yogastudio'),
			'menu_footer'	=> esc_html__('Footer Menu', 'yogastudio'),
			'menu_side'		=> esc_html__('Side Menu', 'yogastudio')
		)));
	}
}


// Register widgetized area
if ( !function_exists( 'yogastudio_register_theme_sidebars' ) ) {
	function yogastudio_register_theme_sidebars($sidebars=array()) {
		global $YOGASTUDIO_GLOBALS;
		if (!is_array($sidebars)) $sidebars = array();
		// Custom sidebars
		$custom = yogastudio_get_theme_option('custom_sidebars');
		if (is_array($custom) && count($custom) > 0) {
			foreach ($custom as $i => $sb) {
				if (trim(chop($sb))=='') continue;
				$sidebars['sidebar_custom_'.($i)]  = $sb;
			}
		}
		$sidebars = apply_filters( 'yogastudio_filter_add_theme_sidebars', $sidebars );
		$YOGASTUDIO_GLOBALS['registered_sidebars'] = $sidebars;
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$name) {
				register_sidebar( array_merge( array(
													'name'          => $name,
													'id'            => $id
												),
												$YOGASTUDIO_GLOBALS['widgets_args']
									)
				);
			}
		}
	}
}





/* Front actions and filters:
------------------------------------------------------------------------ */

//  Enqueue scripts and styles
if ( !function_exists( 'yogastudio_core_frontend_scripts' ) ) {
	function yogastudio_core_frontend_scripts() {
		global $YOGASTUDIO_GLOBALS;
		
		// Modernizr will load in head before other scripts and styles
		// Use older version (from photostack)
		yogastudio_enqueue_script( 'yogastudio-core-modernizr-script', yogastudio_get_file_url('js/photostack/modernizr.min.js'), array(), null, false );
		
		// Enqueue styles
		//-----------------------------------------------------------------------------------------------------
		
		// Prepare custom fonts
		$fonts = yogastudio_get_list_fonts(false);
		$theme_fonts = array();
		$custom_fonts = yogastudio_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
			foreach ($custom_fonts as $s=>$f) {
				if (!empty($f['font-family']) && !yogastudio_is_inherit_option($f['font-family'])) $theme_fonts[$f['font-family']] = 1;
			}
		}
		// Prepare current skin fonts
		$theme_fonts = apply_filters('yogastudio_filter_used_fonts', $theme_fonts);
		// Link to selected fonts
		if (is_array($theme_fonts) && count($theme_fonts) > 0) {
			$google_fonts = '';
			foreach ($theme_fonts as $font=>$v) {
				if (isset($fonts[$font])) {
					$font_name = ($pos=yogastudio_strpos($font,' ('))!==false ? yogastudio_substr($font, 0, $pos) : $font;
					if (!empty($fonts[$font]['css'])) {
						$css = $fonts[$font]['css'];
						yogastudio_enqueue_style( 'yogastudio-font-'.str_replace(' ', '-', $font_name).'-style', $css, array(), null );
					} else {
						$google_fonts .= ($google_fonts ? '|' : '') 
							. (!empty($fonts[$font]['link']) ? $fonts[$font]['link'] : str_replace(' ', '+', $font_name).':300,300italic,400,400italic,700,700italic');
					}
				}
			}
			if ($google_fonts)
				yogastudio_enqueue_style( 'yogastudio-font-google_fonts-style', yogastudio_get_protocol() . '://fonts.googleapis.com/css?family=' . $google_fonts . '&subset=' . yogastudio_get_theme_option('fonts_subset'), array(), null );
		}
		
		// Fontello styles must be loaded before main stylesheet
		yogastudio_enqueue_style( 'yogastudio-fontello-style',  yogastudio_get_file_url('css/fontello/css/fontello.css'),  array(), null);
		//yogastudio_enqueue_style( 'yogastudio-fontello-animation-style', yogastudio_get_file_url('css/fontello/css/animation.css'), array(), null);

		// Main stylesheet
		yogastudio_enqueue_style( 'yogastudio-main-style', get_stylesheet_uri(), array(), null );
		
		// Animations
		if (yogastudio_get_theme_option('css_animation')=='yes')
			yogastudio_enqueue_style( 'yogastudio-animation-style',	yogastudio_get_file_url('css/core.animation.css'), array(), null );

		// Theme skin stylesheet
		do_action('yogastudio_action_add_styles');
		
		// Theme customizer stylesheet and inline styles
		yogastudio_enqueue_custom_styles();

		// Responsive
		if (yogastudio_get_theme_option('responsive_layouts') == 'yes') {
			$suffix = yogastudio_param_is_off(yogastudio_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
			yogastudio_enqueue_style( 'yogastudio-responsive-style', yogastudio_get_file_url('css/responsive'.($suffix).'.css'), array(), null );
			do_action('yogastudio_action_add_responsive');
			if (yogastudio_get_custom_option('theme_skin')!='') {
				$css = apply_filters('yogastudio_filter_add_responsive_inline', '');
				if (!empty($css)) wp_add_inline_style( 'yogastudio-responsive-style', $css );
			}
		}

		// Disable loading JQuery UI CSS
		//global $wp_styles, $wp_scripts;
		//$wp_styles->done[]	= 'jquery-ui';
		//$wp_styles->done[]	= 'date-picker-css';
		wp_deregister_style('jquery_ui');
		wp_deregister_style('date-picker-css');


		// Enqueue scripts	
		//----------------------------------------------------------------------------------------------------------------------------
		
		// Load separate theme scripts
		yogastudio_enqueue_script( 'superfish', yogastudio_get_file_url('js/superfish.min.js'), array('jquery'), null, true );
		if (yogastudio_get_theme_option('menu_slider')=='yes') {
			yogastudio_enqueue_script( 'yogastudio-slidemenu-script', yogastudio_get_file_url('js/jquery.slidemenu.js'), array('jquery'), null, true );
			//yogastudio_enqueue_script( 'yogastudio-jquery-easing-script', yogastudio_get_file_url('js/jquery.easing.js'), array('jquery'), null, true );
		}

		if ( is_single() && yogastudio_get_custom_option('show_reviews')=='yes' ) {
			yogastudio_enqueue_script( 'yogastudio-core-reviews-script', yogastudio_get_file_url('js/core.reviews.js'), array('jquery'), null, true );
		}

		yogastudio_enqueue_script( 'yogastudio-core-utils-script',	yogastudio_get_file_url('js/core.utils.js'), array('jquery'), null, true );
		yogastudio_enqueue_script( 'yogastudio-core-init-script',	yogastudio_get_file_url('js/core.init.js'), array('jquery'), null, true );	
		yogastudio_enqueue_script( 'yogastudio-theme-init-script',	yogastudio_get_file_url('js/theme.init.js'), array('jquery'), null, true );	

		// Media elements library	
		if (yogastudio_get_theme_option('use_mediaelement')=='yes') {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		} else {
//			$wp_styles->done[]	= 'mediaelement';
//			$wp_styles->done[]	= 'wp-mediaelement';
//			$wp_scripts->done[]	= 'mediaelement';
//			$wp_scripts->done[]	= 'wp-mediaelement';
			wp_deregister_style('mediaelement');
			wp_deregister_style('wp-mediaelement');
			wp_deregister_script('mediaelement');
			wp_deregister_script('wp-mediaelement');
		}
		
		// Video background
		if (yogastudio_get_custom_option('show_video_bg') == 'yes' && yogastudio_get_custom_option('video_bg_youtube_code') != '') {
			yogastudio_enqueue_script( 'yogastudio-video-bg-script', yogastudio_get_file_url('js/jquery.tubular.1.0.js'), array('jquery'), null, true );
		}

		// Google map
		if ( yogastudio_get_custom_option('show_googlemap')=='yes' ) { 
			yogastudio_enqueue_script( 'googlemap', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBGq02KRjjgoi-w1D2T-JzCCOwEXbpv0tI', array(), null, true );
			yogastudio_enqueue_script( 'yogastudio-googlemap-script', yogastudio_get_file_url('js/core.googlemap.js'), array(), null, true );
		}

			
		// Social share buttons
		if (is_singular() && !yogastudio_get_global('blog_streampage') && yogastudio_get_custom_option('show_share')!='hide') {
			yogastudio_enqueue_script( 'yogastudio-social-share-script', yogastudio_get_file_url('js/social/social-share.js'), array('jquery'), null, true );
		}

		// Comments
		if ( is_singular() && !yogastudio_get_global('blog_streampage') && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply', false, array(), null, true );
		}

		// Custom panel
		if (yogastudio_get_theme_option('show_theme_customizer') == 'yes') {
			if (file_exists(yogastudio_get_file_dir('core/core.customizer/front.customizer.css')))
				yogastudio_enqueue_style(  'yogastudio-customizer-style',  yogastudio_get_file_url('core/core.customizer/front.customizer.css'), array(), null );
			if (file_exists(yogastudio_get_file_dir('core/core.customizer/front.customizer.js')))
				yogastudio_enqueue_script( 'yogastudio-customizer-script', yogastudio_get_file_url('core/core.customizer/front.customizer.js'), array(), null, true );	
		}
		
		//Debug utils
		if (yogastudio_get_theme_option('debug_mode')=='yes') {
			yogastudio_enqueue_script( 'yogastudio-core-debug-script', yogastudio_get_file_url('js/core.debug.js'), array(), null, true );
		}

		// Theme skin script
		do_action('yogastudio_action_add_scripts');
	}
}

//  Enqueue Swiper Slider scripts and styles
if ( !function_exists( 'yogastudio_enqueue_slider' ) ) {
	function yogastudio_enqueue_slider($engine='all') {
		if ($engine=='all' || $engine=='swiper') {
			yogastudio_enqueue_style(  'yogastudio-swiperslider-style', 			yogastudio_get_file_url('js/swiper/swiper.css'), array(), null );
			yogastudio_enqueue_script( 'yogastudio-swiperslider-script', 			yogastudio_get_file_url('js/swiper/swiper.js'), array(), null, true );
			// jQuery version conflict with Revolution Slider
			//yogastudio_enqueue_script( 'yogastudio-swiperslider-script', 			yogastudio_get_file_url('js/swiper/swiper.jquery.js'), array(), null, true );
		}
	}
}

//  Enqueue Photostack gallery
if ( !function_exists( 'yogastudio_enqueue_polaroid' ) ) {
	function yogastudio_enqueue_polaroid() {
		yogastudio_enqueue_style(  'yogastudio-polaroid-style', 	yogastudio_get_file_url('js/photostack/component.css'), array(), null );
		yogastudio_enqueue_script( 'yogastudio-classie-script',		yogastudio_get_file_url('js/photostack/classie.js'), array(), null, true );
		yogastudio_enqueue_script( 'yogastudio-polaroid-script',	yogastudio_get_file_url('js/photostack/photostack.js'), array(), null, true );
	}
}

//  Enqueue Messages scripts and styles
if ( !function_exists( 'yogastudio_enqueue_messages' ) ) {
	function yogastudio_enqueue_messages() {
		yogastudio_enqueue_style(  'yogastudio-messages-style',		yogastudio_get_file_url('js/core.messages/core.messages.css'), array(), null );
		yogastudio_enqueue_script( 'yogastudio-messages-script',	yogastudio_get_file_url('js/core.messages/core.messages.js'),  array('jquery'), null, true );
	}
}

//  Enqueue Portfolio hover scripts and styles
if ( !function_exists( 'yogastudio_enqueue_portfolio' ) ) {
	function yogastudio_enqueue_portfolio($hover='') {
		yogastudio_enqueue_style( 'yogastudio-portfolio-style',  yogastudio_get_file_url('css/core.portfolio.css'), array(), null );
		if (yogastudio_strpos($hover, 'effect_dir')!==false)
			yogastudio_enqueue_script( 'hoverdir', yogastudio_get_file_url('js/hover/jquery.hoverdir.js'), array(), null, true );
	}
}

//  Enqueue Charts and Diagrams scripts and styles
if ( !function_exists( 'yogastudio_enqueue_diagram' ) ) {
	function yogastudio_enqueue_diagram($type='all') {
		if ($type=='all' || $type=='pie') yogastudio_enqueue_script( 'yogastudio-diagram-chart-script',	yogastudio_get_file_url('js/diagram/chart.min.js'), array(), null, true );
		if ($type=='all' || $type=='arc') yogastudio_enqueue_script( 'yogastudio-diagram-raphael-script',	yogastudio_get_file_url('js/diagram/diagram.raphael.min.js'), array(), 'no-compose', true );
	}
}

// Enqueue Theme Popup scripts and styles
// Link must have attribute: data-rel="popup" or data-rel="popup[gallery]"
if ( !function_exists( 'yogastudio_enqueue_popup' ) ) {
	function yogastudio_enqueue_popup($engine='') {
		if ($engine=='pretty' || (empty($engine) && yogastudio_get_theme_option('popup_engine')=='pretty')) {
			yogastudio_enqueue_style(  'yogastudio-prettyphoto-style',	yogastudio_get_file_url('js/prettyphoto/css/prettyPhoto.css'), array(), null );
			yogastudio_enqueue_script( 'yogastudio-prettyphoto-script',	yogastudio_get_file_url('js/prettyphoto/jquery.prettyPhoto.min.js'), array('jquery'), 'no-compose', true );
		} else if ($engine=='magnific' || (empty($engine) && yogastudio_get_theme_option('popup_engine')=='magnific')) {
			yogastudio_enqueue_style(  'yogastudio-magnific-style',	yogastudio_get_file_url('js/magnific/magnific-popup.css'), array(), null );
			yogastudio_enqueue_script( 'yogastudio-magnific-script',yogastudio_get_file_url('js/magnific/jquery.magnific-popup.min.js'), array('jquery'), '', true );
		} else if ($engine=='internal' || (empty($engine) && yogastudio_get_theme_option('popup_engine')=='internal')) {
			yogastudio_enqueue_messages();
		}
	}
}

//  Add inline scripts in the footer hook
if ( !function_exists( 'yogastudio_core_frontend_scripts_inline' ) ) {
	function yogastudio_core_frontend_scripts_inline() {
		do_action('yogastudio_action_add_scripts_inline');
	}
}

//  Add inline scripts in the footer
if (!function_exists('yogastudio_core_add_scripts_inline')) {
	function yogastudio_core_add_scripts_inline() {
		global $YOGASTUDIO_GLOBALS;
		
		$msg = yogastudio_get_system_message(true); 
		if (!empty($msg['message'])) yogastudio_enqueue_messages();

		echo "<script type=\"text/javascript\">"
			
			. "if (typeof YOGASTUDIO_GLOBALS == 'undefined') var YOGASTUDIO_GLOBALS = {};"			
			
			// AJAX parameters
			. "YOGASTUDIO_GLOBALS['ajax_url']			 = '" . esc_url($YOGASTUDIO_GLOBALS['ajax_url']) . "';"
			. "YOGASTUDIO_GLOBALS['ajax_nonce']		 = '" . esc_attr($YOGASTUDIO_GLOBALS['ajax_nonce']) . "';"
			. "YOGASTUDIO_GLOBALS['ajax_nonce_editor'] = '" . esc_attr(wp_create_nonce('yogastudio_editor_nonce')) . "';"
			
			// Site base url
			. "YOGASTUDIO_GLOBALS['site_url']			= '" . get_site_url() . "';"
			
			// VC frontend edit mode
			. "YOGASTUDIO_GLOBALS['vc_edit_mode']		= " . (function_exists('yogastudio_vc_is_frontend') && yogastudio_vc_is_frontend() ? 'true' : 'false') . ";"
			
			// Theme base font
			. "YOGASTUDIO_GLOBALS['theme_font']		= '" . yogastudio_get_custom_font_settings('p', 'font-family') . "';"
			
			// Theme skin
			. "YOGASTUDIO_GLOBALS['theme_skin']			= '" . esc_attr(yogastudio_get_custom_option('theme_skin')) . "';"
			. "YOGASTUDIO_GLOBALS['theme_skin_color']		= '" . yogastudio_get_scheme_color('text_dark') . "';"
			. "YOGASTUDIO_GLOBALS['theme_skin_bg_color']	= '" . yogastudio_get_scheme_color('bg_color') . "';"
			
			// Slider height
			. "YOGASTUDIO_GLOBALS['slider_height']	= " . max(100, yogastudio_get_custom_option('slider_height')) . ";"
			
			// System message
			. "YOGASTUDIO_GLOBALS['system_message']	= {"
				. "message: '" . addslashes($msg['message']) . "',"
				. "status: '"  . addslashes($msg['status'])  . "',"
				. "header: '"  . addslashes($msg['header'])  . "'"
				. "};"
			
			// User logged in
			. "YOGASTUDIO_GLOBALS['user_logged_in']	= " . (is_user_logged_in() ? 'true' : 'false') . ";"
			
			// Show table of content for the current page
			. "YOGASTUDIO_GLOBALS['toc_menu']		= '" . esc_attr(yogastudio_get_custom_option('menu_toc')) . "';"
			. "YOGASTUDIO_GLOBALS['toc_menu_home']	= " . (yogastudio_get_custom_option('menu_toc')!='hide' && yogastudio_get_custom_option('menu_toc_home')=='yes' ? 'true' : 'false') . ";"
			. "YOGASTUDIO_GLOBALS['toc_menu_top']	= " . (yogastudio_get_custom_option('menu_toc')!='hide' && yogastudio_get_custom_option('menu_toc_top')=='yes' ? 'true' : 'false') . ";"
			
			// Fix main menu
			. "YOGASTUDIO_GLOBALS['menu_fixed']		= " . (yogastudio_get_theme_option('menu_attachment')=='fixed' ? 'true' : 'false') . ";"
			
			// Use responsive version for main menu
			. "YOGASTUDIO_GLOBALS['menu_relayout']	= " . max(0, (int) yogastudio_get_theme_option('menu_relayout')) . ";"
			. "YOGASTUDIO_GLOBALS['menu_responsive']	= " . (yogastudio_get_theme_option('responsive_layouts') == 'yes' ? max(0, (int) yogastudio_get_theme_option('menu_responsive')) : 0) . ";"
			. "YOGASTUDIO_GLOBALS['menu_slider']     = " . (yogastudio_get_theme_option('menu_slider')=='yes' ? 'true' : 'false') . ";"

			// Right panel demo timer
			. "YOGASTUDIO_GLOBALS['demo_time']		= " . (yogastudio_get_theme_option('show_theme_customizer')=='yes' ? max(0, (int) yogastudio_get_theme_option('customizer_demo')) : 0) . ";"

			// Video and Audio tag wrapper
			. "YOGASTUDIO_GLOBALS['media_elements_enabled'] = " . (yogastudio_get_theme_option('use_mediaelement')=='yes' ? 'true' : 'false') . ";"
			
			// Use AJAX search
			. "YOGASTUDIO_GLOBALS['ajax_search_enabled'] 	= " . (yogastudio_get_theme_option('use_ajax_search')=='yes' ? 'true' : 'false') . ";"
			. "YOGASTUDIO_GLOBALS['ajax_search_min_length']	= " . min(3, yogastudio_get_theme_option('ajax_search_min_length')) . ";"
			. "YOGASTUDIO_GLOBALS['ajax_search_delay']		= " . min(200, max(1000, yogastudio_get_theme_option('ajax_search_delay'))) . ";"

			// Use CSS animation
			. "YOGASTUDIO_GLOBALS['css_animation']      = " . (yogastudio_get_theme_option('css_animation')=='yes' ? 'true' : 'false') . ";"
			. "YOGASTUDIO_GLOBALS['menu_animation_in']  = '" . esc_attr(yogastudio_get_theme_option('menu_animation_in')) . "';"
			. "YOGASTUDIO_GLOBALS['menu_animation_out'] = '" . esc_attr(yogastudio_get_theme_option('menu_animation_out')) . "';"

			// Popup windows engine
			. "YOGASTUDIO_GLOBALS['popup_engine']	= '" . esc_attr(yogastudio_get_theme_option('popup_engine')) . "';"

			// E-mail mask
			. "YOGASTUDIO_GLOBALS['email_mask']		= '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$';"
			
			// Messages max length
			. "YOGASTUDIO_GLOBALS['contacts_maxlength']	= " . intval(yogastudio_get_theme_option('message_maxlength_contacts')) . ";"
			. "YOGASTUDIO_GLOBALS['comments_maxlength']	= " . intval(yogastudio_get_theme_option('message_maxlength_comments')) . ";"

			// Remember visitors settings
			. "YOGASTUDIO_GLOBALS['remember_visitors_settings']	= " . (yogastudio_get_theme_option('remember_visitors_settings')=='yes' ? 'true' : 'false') . ";"

			// Internal vars - do not change it!
			// Flag for review mechanism
			. "YOGASTUDIO_GLOBALS['admin_mode']			= false;"
			// Max scale factor for the portfolio and other isotope elements before relayout
			. "YOGASTUDIO_GLOBALS['isotope_resize_delta']	= 0.3;"
			// jQuery object for the message box in the form
			. "YOGASTUDIO_GLOBALS['error_message_box']	= null;"
			// Waiting for the viewmore results
			. "YOGASTUDIO_GLOBALS['viewmore_busy']		= false;"
			. "YOGASTUDIO_GLOBALS['video_resize_inited']	= false;"
			. "YOGASTUDIO_GLOBALS['top_panel_height']		= 0;"
			
			. "</script>";
	}
}


//  Enqueue Custom styles (main Theme options settings)
if ( !function_exists( 'yogastudio_enqueue_custom_styles' ) ) {
	function yogastudio_enqueue_custom_styles() {
		// Custom stylesheet
		$custom_css = '';	//yogastudio_get_custom_option('custom_stylesheet_url');
		yogastudio_enqueue_style( 'yogastudio-custom-style', $custom_css ? $custom_css : yogastudio_get_file_url('css/custom-style.css'), array(), null );
		// Custom inline styles
		wp_add_inline_style( 'yogastudio-custom-style', yogastudio_prepare_custom_styles() );
	}
}

// Add class "widget_number_#' for each widget
if ( !function_exists( 'yogastudio_add_widget_number' ) ) {
	//add_filter('dynamic_sidebar_params', 'yogastudio_add_widget_number', 10, 1);
	function yogastudio_add_widget_number($prm) {
		global $YOGASTUDIO_GLOBALS;
		if (is_admin()) return $prm;
		static $num=0, $last_sidebar='', $last_sidebar_id='', $last_sidebar_columns=0, $last_sidebar_count=0, $sidebars_widgets=array();
		$cur_sidebar = !empty($YOGASTUDIO_GLOBALS['current_sidebar']) ? $YOGASTUDIO_GLOBALS['current_sidebar'] : 'undefined';
		if (count($sidebars_widgets) == 0)
			$sidebars_widgets = wp_get_sidebars_widgets();
		if ($last_sidebar != $cur_sidebar) {
			$num = 0;
			$last_sidebar = $cur_sidebar;
			$last_sidebar_id = $prm[0]['id'];
			$last_sidebar_columns = max(1, (int) yogastudio_get_custom_option('sidebar_'.($cur_sidebar).'_columns'));
			$last_sidebar_count = count($sidebars_widgets[$last_sidebar_id]);
		}
		$num++;
		$prm[0]['before_widget'] = str_replace(' class="', ' class="widget_number_'.esc_attr($num).($last_sidebar_columns > 1 ? ' column-1_'.esc_attr($last_sidebar_columns) : '').' ', $prm[0]['before_widget']);
		return $prm;
	}
}


// Filters wp_title to print a neat <title> tag based on what is being viewed.
if ( !function_exists( 'yogastudio_wp_title' ) ) {
	// add_filter( 'wp_title', 'yogastudio_wp_title', 10, 2 );
	function yogastudio_wp_title( $title, $sep ) {
		global $page, $paged;
		if ( is_feed() ) return $title;
		// Add the blog name
		$title .= get_bloginfo( 'name' );
		// Add the blog description for the home/front page.
		if ( is_home() || is_front_page() ) {
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description )
				$title .= " $sep $site_description";
		}
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'yogastudio' ), max( $paged, $page ) );
		return $title;
	}
}

// Add main menu classes
if ( !function_exists( 'yogastudio_add_mainmenu_classes' ) ) {
	// add_filter('wp_nav_menu_objects', 'yogastudio_add_mainmenu_classes', 10, 2);
	function yogastudio_add_mainmenu_classes($items, $args) {
		if (is_admin()) return $items;
		if ($args->menu_id == 'mainmenu' && yogastudio_get_theme_option('menu_colored')=='yes' && is_array($items) && count($items) > 0) {
			foreach($items as $k=>$item) {
				if ($item->menu_item_parent==0) {
					if ($item->type=='taxonomy' && $item->object=='category') {
						$cur_tint = yogastudio_taxonomy_get_inherited_property('category', $item->object_id, 'bg_tint');
						if (!empty($cur_tint) && !yogastudio_is_inherit_option($cur_tint))
							$items[$k]->classes[] = 'bg_tint_'.esc_attr($cur_tint);
					}
				}
			}
		}
		return $items;
	}
}


// Save post data from frontend editor
if ( !function_exists( 'yogastudio_callback_frontend_editor_save' ) ) {
	function yogastudio_callback_frontend_editor_save() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'yogastudio_editor_nonce' ) )
			die();

		$response = array('error'=>'');

		parse_str($_REQUEST['data'], $output);
		$post_id = $output['frontend_editor_post_id'];

		if ( yogastudio_get_theme_option("allow_editor")=='yes' && (current_user_can('edit_posts', $post_id) || current_user_can('edit_pages', $post_id)) ) {
			if ($post_id > 0) {
				$title   = stripslashes($output['frontend_editor_post_title']);
				$content = stripslashes($output['frontend_editor_post_content']);
				$excerpt = stripslashes($output['frontend_editor_post_excerpt']);
				$rez = wp_update_post(array(
					'ID'           => $post_id,
					'post_content' => $content,
					'post_excerpt' => $excerpt,
					'post_title'   => $title
				));
				if ($rez == 0) 
					$response['error'] = esc_html__('Post update error!', 'yogastudio');
			} else {
				$response['error'] = esc_html__('Post update error!', 'yogastudio');
			}
		} else
			$response['error'] = esc_html__('Post update denied!', 'yogastudio');
		
		echo json_encode($response);
		die();
	}
}

// Delete post from frontend editor
if ( !function_exists( 'yogastudio_callback_frontend_editor_delete' ) ) {
	function yogastudio_callback_frontend_editor_delete() {
		global $_REQUEST;

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'yogastudio_editor_nonce' ) )
			die();

		$response = array('error'=>'');
		
		$post_id = $_REQUEST['post_id'];

		if ( yogastudio_get_theme_option("allow_editor")=='yes' && (current_user_can('delete_posts', $post_id) || current_user_can('delete_pages', $post_id)) ) {
			if ($post_id > 0) {
				$rez = wp_delete_post($post_id);
				if ($rez === false) 
					$response['error'] = esc_html__('Post delete error!', 'yogastudio');
			} else {
				$response['error'] = esc_html__('Post delete error!', 'yogastudio');
			}
		} else
			$response['error'] = esc_html__('Post delete denied!', 'yogastudio');

		echo json_encode($response);
		die();
	}
}


// Prepare logo text
if ( !function_exists( 'yogastudio_prepare_logo_text' ) ) {
	function yogastudio_prepare_logo_text($text) {
		$text = str_replace(array('[', ']'), array('<span class="theme_accent">', '</span>'), $text);
		$text = str_replace(array('{', '}'), array('<strong>', '</strong>'), $text);
		return $text;
	}
}
?>