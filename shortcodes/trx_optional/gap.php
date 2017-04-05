<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_gap_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_gap_theme_setup' );
	function yogastudio_sc_gap_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_gap_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('yogastudio_sc_gap')) {	
	function yogastudio_sc_gap($atts, $content = null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		$output = yogastudio_gap_start() . do_shortcode($content) . yogastudio_gap_end();
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	yogastudio_require_shortcode("trx_gap", "yogastudio_sc_gap");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_gap_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_gap_reg_shortcodes');
	function yogastudio_sc_gap_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_gap"] = array(
			"title" => esc_html__("Gap", "yogastudio"),
			"desc" => wp_kses( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", "yogastudio"),
					"desc" => wp_kses( __("Gap inner content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_gap_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_gap_reg_shortcodes_vc');
	function yogastudio_sc_gap_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", "yogastudio"),
			"description" => wp_kses( __("Insert gap (fullwidth area) in the post content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Structure', 'yogastudio'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Gap content", "yogastudio"),
					"description" => wp_kses( __("Gap inner content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				)
				*/
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends YOGASTUDIO_VC_ShortCodeCollection {}
	}
}
?>