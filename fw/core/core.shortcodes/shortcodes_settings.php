<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'yogastudio_shortcodes_is_used' ) ) {
	function yogastudio_shortcodes_is_used() {
		return yogastudio_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && yogastudio_strpos($_SERVER['REQUEST_URI'], 'vc-roles')!==false)			// VC Role Manager
			|| (function_exists('yogastudio_vc_is_frontend') && yogastudio_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'yogastudio_shortcodes_width' ) ) {
	function yogastudio_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", "yogastudio"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'yogastudio_shortcodes_height' ) ) {
	function yogastudio_shortcodes_height($h='') {
		global $YOGASTUDIO_GLOBALS;
		return array(
			"title" => esc_html__("Height", "yogastudio"),
			"desc" => wp_kses( __("Width (in pixels or percent) and height (only in pixels) of element", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"value" => $h,
			"type" => "text"
		);
	}
}

/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_shortcodes_settings_theme_setup' ) ) {
//	if ( yogastudio_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'yogastudio_action_before_init_theme', 'yogastudio_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'yogastudio_action_after_init_theme', 'yogastudio_shortcodes_settings_theme_setup' );
	function yogastudio_shortcodes_settings_theme_setup() {
		if (yogastudio_shortcodes_is_used()) {
			global $YOGASTUDIO_GLOBALS;

			// Sort templates alphabetically
			ksort($YOGASTUDIO_GLOBALS['registered_templates']);

			// Prepare arrays 
			$YOGASTUDIO_GLOBALS['sc_params'] = array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", "yogastudio"),
					"desc" => wp_kses( __("ID for current element", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", "yogastudio"),
					"desc" => wp_kses( __("CSS class for current element (optional)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", "yogastudio"),
					"desc" => wp_kses( __("Any additional CSS rules (if need)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'yogastudio'),
					'ol'	=> esc_html__('Ordered', 'yogastudio'),
					'iconed'=> esc_html__('Iconed', 'yogastudio')
				),
				'yes_no'	=> yogastudio_get_list_yesno(),
				'on_off'	=> yogastudio_get_list_onoff(),
				'dir' 		=> yogastudio_get_list_directions(),
				'align'		=> yogastudio_get_list_alignments(),
				'float'		=> yogastudio_get_list_floats(),
				'show_hide'	=> yogastudio_get_list_showhide(),
				'sorting' 	=> yogastudio_get_list_sortings(),
				'ordering' 	=> yogastudio_get_list_orderings(),
				'shapes'	=> yogastudio_get_list_shapes(),
				'sizes'		=> yogastudio_get_list_sizes(),
				'sliders'	=> yogastudio_get_list_sliders(),
				'categories'=> yogastudio_get_list_categories(),
				'columns'	=> yogastudio_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), yogastudio_get_list_files("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), yogastudio_get_list_icons()),
				'locations'	=> yogastudio_get_list_dedicated_locations(),
				'filters'	=> yogastudio_get_list_portfolio_filters(),
				'formats'	=> yogastudio_get_list_post_formats_filters(),
				'hovers'	=> yogastudio_get_list_hovers(true),
				'hovers_dir'=> yogastudio_get_list_hovers_directions(true),
				'schemes'	=> yogastudio_get_list_color_schemes(true),
				'animations'		=> yogastudio_get_list_animations_in(),
				'margins' 			=> yogastudio_get_list_margins(true),
				'blogger_styles'	=> yogastudio_get_list_templates_blogger(),
				'forms'				=> yogastudio_get_list_templates_forms(),
				'posts_types'		=> yogastudio_get_list_posts_types(),
				'googlemap_styles'	=> yogastudio_get_list_googlemap_styles(),
				'field_types'		=> yogastudio_get_list_field_types(),
				'label_positions'	=> yogastudio_get_list_label_positions()
			);

			$YOGASTUDIO_GLOBALS['sc_params']['animation'] = array(
				"title" => esc_html__("Animation",  'yogastudio'),
				"desc" => wp_kses( __('Select animation while object enter in the visible area of page',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"value" => "none",
				"type" => "select",
				"options" => $YOGASTUDIO_GLOBALS['sc_params']['animations']
			);
			$YOGASTUDIO_GLOBALS['sc_params']['top'] = array(
				"title" => esc_html__("Top margin",  'yogastudio'),
				"divider" => true,
				"value" => "inherit",
				"type" => "select",
				"options" => $YOGASTUDIO_GLOBALS['sc_params']['margins']
			);
			$YOGASTUDIO_GLOBALS['sc_params']['bottom'] = array(
				"title" => esc_html__("Bottom margin",  'yogastudio'),
				"value" => "inherit",
				"type" => "select",
				"options" => $YOGASTUDIO_GLOBALS['sc_params']['margins']
			);
			$YOGASTUDIO_GLOBALS['sc_params']['left'] = array(
				"title" => esc_html__("Left margin",  'yogastudio'),
				"value" => "inherit",
				"type" => "select",
				"options" => $YOGASTUDIO_GLOBALS['sc_params']['margins']
			);
			$YOGASTUDIO_GLOBALS['sc_params']['right'] = array(
				"title" => esc_html__("Right margin",  'yogastudio'),
				"desc" => wp_kses( __("Margins around this shortcode", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"value" => "inherit",
				"type" => "select",
				"options" => $YOGASTUDIO_GLOBALS['sc_params']['margins']
			);

			$YOGASTUDIO_GLOBALS['sc_params'] = apply_filters('yogastudio_filter_shortcodes_params', $YOGASTUDIO_GLOBALS['sc_params']);

	
			// Shortcodes list
			//------------------------------------------------------------------
			$YOGASTUDIO_GLOBALS['shortcodes'] = array();
			
			// Add shortcodes
			do_action('yogastudio_action_shortcodes_list');

			// Sort shortcodes list
			uasort($YOGASTUDIO_GLOBALS['shortcodes'], function($a, $b) {
				return strcmp($a['title'], $b['title']);
			});
		}
	}
}
?>