<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_br_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_br_theme_setup' );
	function yogastudio_sc_br_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_br_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_br_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_br clear="left|right|both"]
*/

if (!function_exists('yogastudio_sc_br')) {	
	function yogastudio_sc_br($atts, $content = null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	yogastudio_require_shortcode("trx_br", "yogastudio_sc_br");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_br_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_br_reg_shortcodes');
	function yogastudio_sc_br_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_br"] = array(
			"title" => esc_html__("Break", "yogastudio"),
			"desc" => wp_kses( __("Line break with clear floating (if need)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", "yogastudio"),
					"desc" => wp_kses( __("Clear floating (if need)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'yogastudio'),
						'left' => esc_html__('Left', 'yogastudio'),
						'right' => esc_html__('Right', 'yogastudio'),
						'both' => esc_html__('Both', 'yogastudio')
					)
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_br_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_br_reg_shortcodes_vc');
	function yogastudio_sc_br_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
/*
		vc_map( array(
			"base" => "trx_br",
			"name" => esc_html__("Line break", "yogastudio"),
			"description" => wp_kses( __("Line break or Clear Floating", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_br',
			"class" => "trx_sc_single trx_sc_br",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "clear",
					"heading" => esc_html__("Clear floating", "yogastudio"),
					"description" => wp_kses( __("Select clear side (if need)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"value" => array(
						esc_html__('None', 'yogastudio') => 'none',
						esc_html__('Left', 'yogastudio') => 'left',
						esc_html__('Right', 'yogastudio') => 'right',
						esc_html__('Both', 'yogastudio') => 'both'
					),
					"type" => "dropdown"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Br extends YOGASTUDIO_VC_ShortCodeSingle {}
*/
	}
}
?>