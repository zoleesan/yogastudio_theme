<?php
/**
 * Skin file for the theme.
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('yogastudio_action_skin_theme_setup')) {
	add_action( 'yogastudio_action_init_theme', 'yogastudio_action_skin_theme_setup', 1 );
	function yogastudio_action_skin_theme_setup() {

		// Add skin fonts in the used fonts list
		add_filter('yogastudio_filter_used_fonts',			'yogastudio_filter_skin_used_fonts');
		// Add skin fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('yogastudio_filter_list_fonts',			'yogastudio_filter_skin_list_fonts');

		// Add skin stylesheets
		add_action('yogastudio_action_add_styles',			'yogastudio_action_skin_add_styles');
		// Add skin inline styles
		add_filter('yogastudio_filter_add_styles_inline',		'yogastudio_filter_skin_add_styles_inline');
		// Add skin responsive styles
		add_action('yogastudio_action_add_responsive',		'yogastudio_action_skin_add_responsive');
		// Add skin responsive inline styles
		add_filter('yogastudio_filter_add_responsive_inline',	'yogastudio_filter_skin_add_responsive_inline');

		// Add skin scripts
		add_action('yogastudio_action_add_scripts',			'yogastudio_action_skin_add_scripts');
		// Add skin scripts inline
		add_action('yogastudio_action_add_scripts_inline',	'yogastudio_action_skin_add_scripts_inline');

		// Add skin less files into list for compilation
		add_filter('yogastudio_filter_compile_less',			'yogastudio_filter_skin_compile_less');


		/* Color schemes
		
		// Accenterd colors
		accent1			- theme accented color 1
		accent1_hover	- theme accented color 1 (hover state)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		yogastudio_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'yogastudio'),

			// Accent colors
			'accent1'				=> '#8dd0d3',
			'accent1_hover'			=> '#8dd0d3',
			'accent2'				=> '#c4d114',
			'accent2_hover'			=> '#f06276',
			'accent3'				=> '#554c86',
			'accent3_hover'			=> '#554c86',
			
			// Headers, text and links colors
			'text'					=> '#8a8a8a',
			'text_light'			=> '#9598a0',
			'text_dark'				=> '#45445a',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dedede',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#d8dce5',
			'alter_dark'			=> '#4c4c4c',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#189799',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f5f6f7',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		yogastudio_add_color_scheme('pink', array(

			'title'					=> esc_html__('Pink', 'yogastudio'),

			// Accent colors
			'accent1'				=> '#a47bba',
			'accent1_hover'			=> '#a47bba',
			'accent2'				=> '#eea0d4',
			'accent2_hover'			=> '#f06276',
			'accent3'				=> '#554c86',
			'accent3_hover'			=> '#554c86',
			
			// Headers, text and links colors
			'text'					=> '#8a8a8a',
			'text_light'			=> '#9598a0',
			'text_dark'				=> '#6babc8',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dedede',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#d8dce5',
			'alter_dark'			=> '#4c4c4c',
			'alter_link'			=> '#a47bba',
			'alter_hover'			=> '#eea0d4',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f5f6f7',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		yogastudio_add_color_scheme('brown', array(

			'title'					=> esc_html__('Brown', 'yogastudio'),

			// Accent colors
			'accent1'				=> '#306e81',
			'accent1_hover'			=> '#306e81',
			'accent2'				=> '#5e9bae',
			'accent2_hover'			=> '#f06276',
			'accent3'				=> '#554c86',
			'accent3_hover'			=> '#554c86',
			
			// Headers, text and links colors
			'text'					=> '#8a8a8a',
			'text_light'			=> '#9598a0',
			'text_dark'				=> '#a1536a',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dedede',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#d8dce5',
			'alter_dark'			=> '#4c4c4c',
			'alter_link'			=> '#306e81',
			'alter_hover'			=> '#5e9bae',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f5f6f7',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);

		// Add color schemes
		yogastudio_add_color_scheme('green', array(

			'title'					=> esc_html__('Green', 'yogastudio'),

			// Accent colors
			'accent1'				=> '#d4e129',
			'accent1_hover'			=> '#d4e129',
			'accent2'				=> '#85b803',
			'accent2_hover'			=> '#f06276',
			'accent3'				=> '#554c86',
			'accent3_hover'			=> '#554c86',
			
			// Headers, text and links colors
			'text'					=> '#8a8a8a',
			'text_light'			=> '#9598a0',
			'text_dark'				=> '#356e29',
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
			
			// Whole block border and background
			'bd_color'				=> '#dedede',
			'bg_color'				=> '#ffffff',
			'bg_image'				=> '',
			'bg_image_position'		=> 'left top',
			'bg_image_repeat'		=> 'repeat',
			'bg_image_attachment'	=> 'scroll',
			'bg_image2'				=> '',
			'bg_image2_position'	=> 'left top',
			'bg_image2_repeat'		=> 'repeat',
			'bg_image2_attachment'	=> 'scroll',
		
			// Alternative blocks (submenu items, form's fields, etc.)
			'alter_text'			=> '#8a8a8a',
			'alter_light'			=> '#d8dce5',
			'alter_dark'			=> '#4c4c4c',
			'alter_link'			=> '#85b803',
			'alter_hover'			=> '#d4e129',
			'alter_bd_color'		=> '#dddddd',
			'alter_bd_hover'		=> '#bbbbbb',
			'alter_bg_color'		=> '#f5f6f7',
			'alter_bg_hover'		=> '#f0f0f0',
			'alter_bg_image'			=> '',
			'alter_bg_image_position'	=> 'left top',
			'alter_bg_image_repeat'		=> 'repeat',
			'alter_bg_image_attachment'	=> 'scroll',
			)
		);



		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		yogastudio_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '3.438em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.1em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0.47em'
			)
		);
		yogastudio_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '3.125em',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0',
			'margin-bottom'	=> '0.55em'
			)
		);
		yogastudio_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '1.875em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '1.08em'
			)
		);
		yogastudio_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '1.438em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '1.75em'
			)
		);
		yogastudio_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '1.25em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '2.1em'
			)
		);
		yogastudio_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '1.125em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.3em',
			'margin-top'	=> '0.5em',
			'margin-bottom'	=> '2.2em'
			)
		);
		yogastudio_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '16px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.375em',
			'margin-top'	=> '',
			'margin-bottom'	=> '0.5em'
			)
		);
		yogastudio_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		yogastudio_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '0.811em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1.8em'
			)
		);
		yogastudio_add_custom_font('menu', array(
			'title'			=> esc_html__('Main menu items', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '1em',
			'font-weight'	=> '600',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		yogastudio_add_custom_font('submenu', array(
			'title'			=> esc_html__('Dropdown menu items', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> ''
			)
		);
		yogastudio_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '2em'
			)
		);
		yogastudio_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		yogastudio_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'yogastudio'),
			'description'	=> '',
			'font-family'	=> 'Catamaran',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> ''
			)
		);

	}
}





//------------------------------------------------------------------------------
// Skin's fonts
//------------------------------------------------------------------------------

// Add skin fonts in the used fonts list
if (!function_exists('yogastudio_filter_skin_used_fonts')) {
	//add_filter('yogastudio_filter_used_fonts', 'yogastudio_filter_skin_used_fonts');
	function yogastudio_filter_skin_used_fonts($theme_fonts) {
		$theme_fonts['Catamaran'] = 1;
		//$theme_fonts['Love Ya Like A Sister'] = 1;
		return $theme_fonts;
	}
}


// Add skin fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('yogastudio_filter_skin_list_fonts')) {
	//add_filter('yogastudio_filter_list_fonts', 'yogastudio_filter_skin_list_fonts');
	function yogastudio_filter_skin_list_fonts($list) {
		// Example:
		// if (!isset($list['Advent Pro'])) {
		//		$list['Advent Pro'] = array(
		//			'family' => 'sans-serif',																						// (required) font family
		//			'link'   => 'Advent+Pro:100,100italic,300,300italic,400,400italic,500,500italic,700,700italic,900,900italic',	// (optional) if you use Google font repository
		//			'css'    => yogastudio_get_file_url('/css/font-face/Advent-Pro/stylesheet.css')									// (optional) if you use custom font-face
		//			);
		// }
		if (!isset($list['Catamaran']))	$list['Catamaran'] = array('family'=>'sans-serif', 'link'   => 'Catamaran:400,300,500,600,700');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Skin's stylesheets
//------------------------------------------------------------------------------
// Add skin stylesheets
if (!function_exists('yogastudio_action_skin_add_styles')) {
	//add_action('yogastudio_action_add_styles', 'yogastudio_action_skin_add_styles');
	function yogastudio_action_skin_add_styles() {
		// Add stylesheet files
		yogastudio_enqueue_style( 'yogastudio-skin-style', yogastudio_get_file_url('skin.css'), array(), null );
		if (file_exists(yogastudio_get_file_dir('skin.customizer.css')))
			yogastudio_enqueue_style( 'yogastudio-skin-customizer-style', yogastudio_get_file_url('skin.customizer.css'), array(), null );
	}
}

// Add skin inline styles
if (!function_exists('yogastudio_filter_skin_add_styles_inline')) {
	//add_filter('yogastudio_filter_add_styles_inline', 'yogastudio_filter_skin_add_styles_inline');
	function yogastudio_filter_skin_add_styles_inline($custom_style) {
		// Todo: add skin specific styles in the $custom_style to override
		//       rules from style.css and shortcodes.css
		// Example:
		//		$scheme = yogastudio_get_custom_option('body_scheme');
		//		if (empty($scheme)) $scheme = 'original';
		//		$clr = yogastudio_get_scheme_color('accent1');
		//		if (!empty($clr)) {
		// 			$custom_style .= '
		//				a,
		//				.bg_tint_light a,
		//				.top_panel .content .search_wrap.search_style_regular .search_form_wrap .search_submit,
		//				.top_panel .content .search_wrap.search_style_regular .search_icon,
		//				.search_results .post_more,
		//				.search_results .search_results_close {
		//					color:'.esc_attr($clr).';
		//				}
		//			';
		//		}
		return $custom_style;	
	}
}

// Add skin responsive styles
if (!function_exists('yogastudio_action_skin_add_responsive')) {
	//add_action('yogastudio_action_add_responsive', 'yogastudio_action_skin_add_responsive');
	function yogastudio_action_skin_add_responsive() {
		$suffix = yogastudio_param_is_off(yogastudio_get_custom_option('show_sidebar_outer')) ? '' : '-outer';
		if (file_exists(yogastudio_get_file_dir('skin.responsive'.($suffix).'.css'))) 
			yogastudio_enqueue_style( 'theme-skin-responsive-style', yogastudio_get_file_url('skin.responsive'.($suffix).'.css'), array(), null );
	}
}

// Add skin responsive inline styles
if (!function_exists('yogastudio_filter_skin_add_responsive_inline')) {
	//add_filter('yogastudio_filter_add_responsive_inline', 'yogastudio_filter_skin_add_responsive_inline');
	function yogastudio_filter_skin_add_responsive_inline($custom_style) {
		return $custom_style;	
	}
}

// Add skin.less into list files for compilation
if (!function_exists('yogastudio_filter_skin_compile_less')) {
	//add_filter('yogastudio_filter_compile_less', 'yogastudio_filter_skin_compile_less');
	function yogastudio_filter_skin_compile_less($files) {
		if (file_exists(yogastudio_get_file_dir('skin.less'))) {
		 	$files[] = yogastudio_get_file_dir('skin.less');
		}
		return $files;	
	}
}



//------------------------------------------------------------------------------
// Skin's scripts
//------------------------------------------------------------------------------

// Add skin scripts
if (!function_exists('yogastudio_action_skin_add_scripts')) {
	//add_action('yogastudio_action_add_scripts', 'yogastudio_action_skin_add_scripts');
	function yogastudio_action_skin_add_scripts() {
		if (file_exists(yogastudio_get_file_dir('skin.js')))
			yogastudio_enqueue_script( 'theme-skin-script', yogastudio_get_file_url('skin.js'), array(), null );
		if (yogastudio_get_theme_option('show_theme_customizer') == 'yes' && file_exists(yogastudio_get_file_dir('skin.customizer.js')))
			yogastudio_enqueue_script( 'theme-skin-customizer-script', yogastudio_get_file_url('skin.customizer.js'), array(), null );
	}
}

// Add skin scripts inline
if (!function_exists('yogastudio_action_skin_add_scripts_inline')) {
	//add_action('yogastudio_action_add_scripts_inline', 'yogastudio_action_skin_add_scripts_inline');
	function yogastudio_action_skin_add_scripts_inline() {
		// Todo: add skin specific scripts
		// Example:
		// echo '<script type="text/javascript">'
		//	. 'jQuery(document).ready(function() {'
		//	. "if (YOGASTUDIO_GLOBALS['theme_font']=='') YOGASTUDIO_GLOBALS['theme_font'] = '" . yogastudio_get_custom_font_settings('p', 'font-family') . "';"
		//	. "YOGASTUDIO_GLOBALS['theme_skin_color'] = '" . yogastudio_get_scheme_color('accent1') . "';"
		//	. "});"
		//	. "< /script>";
	}
}
?>