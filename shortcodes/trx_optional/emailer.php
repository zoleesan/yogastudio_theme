<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_emailer_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_emailer_theme_setup' );
	function yogastudio_sc_emailer_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_emailer_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_emailer_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_emailer group=""]

if (!function_exists('yogastudio_sc_emailer')) {	
	function yogastudio_sc_emailer($atts, $content = null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"group" => "",
			"open" => "yes",
			"align" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height);
		// Load core messages
		yogastudio_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="sc_emailer' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (yogastudio_param_is_on($open) ? ' sc_emailer_opened' : '') . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
					. ($css ? ' style="'.esc_attr($css).'"' : '') 
					. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
					. '>'
				. '<form class="sc_emailer_form">'
				. '<input type="text" class="sc_emailer_input" name="email" value="" placeholder="'.esc_attr__('Please, enter you email address.', 'yogastudio').'">'
				. '<a href="#" class="sc_emailer_button icon-mail" title="'.esc_attr__('Submit', 'yogastudio').'" data-group="'.esc_attr($group ? $group : esc_html__('E-mailer subscription', 'yogastudio')).'"></a>'
				. '</form>'
			. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_emailer', $atts, $content);
	}
	yogastudio_require_shortcode("trx_emailer", "yogastudio_sc_emailer");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_emailer_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_emailer_reg_shortcodes');
	function yogastudio_sc_emailer_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_emailer"] = array(
			"title" => esc_html__("E-mail collector", "yogastudio"),
			"desc" => wp_kses( __("Collect the e-mail address into specified group", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"group" => array(
					"title" => esc_html__("Group", "yogastudio"),
					"desc" => wp_kses( __("The name of group to collect e-mail address", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"open" => array(
					"title" => esc_html__("Open", "yogastudio"),
					"desc" => wp_kses( __("Initially open the input field on show object", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "yes",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"align" => array(
					"title" => esc_html__("Alignment", "yogastudio"),
					"desc" => wp_kses( __("Align object to left, center or right", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
				), 
				"width" => yogastudio_shortcodes_width(),
				"height" => yogastudio_shortcodes_height(),
				"top" => $YOGASTUDIO_GLOBALS['sc_params']['top'],
				"bottom" => $YOGASTUDIO_GLOBALS['sc_params']['bottom'],
				"left" => $YOGASTUDIO_GLOBALS['sc_params']['left'],
				"right" => $YOGASTUDIO_GLOBALS['sc_params']['right'],
				"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
				"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
				"animation" => $YOGASTUDIO_GLOBALS['sc_params']['animation'],
				"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_emailer_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_emailer_reg_shortcodes_vc');
	function yogastudio_sc_emailer_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_emailer",
			"name" => esc_html__("E-mail collector", "yogastudio"),
			"description" => wp_kses( __("Collect e-mails into specified group", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_emailer',
			"class" => "trx_sc_single trx_sc_emailer",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "group",
					"heading" => esc_html__("Group", "yogastudio"),
					"description" => wp_kses( __("The name of group to collect e-mail address", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Opened", "yogastudio"),
					"description" => wp_kses( __("Initially open the input field on show object", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Initially opened', 'yogastudio') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Align field to left, center or right", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['animation'],
				$YOGASTUDIO_GLOBALS['vc_params']['css'],
				yogastudio_vc_width(),
				yogastudio_vc_height(),
				$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
			)
		) );
		
		class WPBakeryShortCode_Trx_Emailer extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>