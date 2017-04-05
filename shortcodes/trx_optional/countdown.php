<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_countdown_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_countdown_theme_setup' );
	function yogastudio_sc_countdown_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_countdown_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_countdown_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_countdown date="" time=""]

if (!function_exists('yogastudio_sc_countdown')) {	
	function yogastudio_sc_countdown($atts, $content = null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"date" => "",
			"time" => "",
			"style" => "1",
			"align" => "center",
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
		if (empty($id)) $id = "sc_countdown_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height);
		if (empty($interval)) $interval = 1;
		yogastudio_enqueue_script( 'yogastudio-jquery-plugin-script', yogastudio_get_file_url('js/countdown/jquery.plugin.js'), array('jquery'), null, true );	
		yogastudio_enqueue_script( 'yogastudio-countdown-script', yogastudio_get_file_url('js/countdown/jquery.countdown.js'), array('jquery'), null, true );	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
			. ' class="sc_countdown sc_countdown_style_' . esc_attr(max(1, min(2, $style))) . (!empty($align) && $align!='none' ? ' align'.esc_attr($align) : '') . (!empty($class) ? ' '.esc_attr($class) : '') .'"'
			. ($css ? ' style="'.esc_attr($css).'"' : '')
			. ' data-date="'.esc_attr(empty($date) ? date('Y-m-d') : $date).'"'
			. ' data-time="'.esc_attr(empty($time) ? '00:00:00' : $time).'"'
			. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
			. '>'
				. ($align=='center' ? '<div class="sc_countdown_inner">' : '')
				. '<div class="sc_countdown_item sc_countdown_days">'
					. '<span class="sc_countdown_digits"><span></span><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Days', 'yogastudio').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_hours">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Hours', 'yogastudio').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_minutes">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Minutes', 'yogastudio').'</span>'
				. '</div>'
				. '<div class="sc_countdown_separator">:</div>'
				. '<div class="sc_countdown_item sc_countdown_seconds">'
					. '<span class="sc_countdown_digits"><span></span><span></span></span>'
					. '<span class="sc_countdown_label">'.esc_html__('Seconds', 'yogastudio').'</span>'
				. '</div>'
				. '<div class="sc_countdown_placeholder hide"></div>'
				. ($align=='center' ? '</div>' : '')
			. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_countdown', $atts, $content);
	}
	yogastudio_require_shortcode("trx_countdown", "yogastudio_sc_countdown");
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_countdown_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_countdown_reg_shortcodes');
	function yogastudio_sc_countdown_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_countdown"] = array(
			"title" => esc_html__("Countdown", "yogastudio"),
			"desc" => wp_kses( __("Insert countdown object", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"date" => array(
					"title" => esc_html__("Date", "yogastudio"),
					"desc" => wp_kses( __("Upcoming date (format: yyyy-mm-dd)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"format" => "yy-mm-dd",
					"type" => "date"
				),
				"time" => array(
					"title" => esc_html__("Time", "yogastudio"),
					"desc" => wp_kses( __("Upcoming time (format: HH:mm:ss)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", "yogastudio"),
					"desc" => wp_kses( __("Countdown style", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "1",
					"type" => "checklist",
					"options" => yogastudio_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", "yogastudio"),
					"desc" => wp_kses( __("Align counter to left, center or right", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
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
if ( !function_exists( 'yogastudio_sc_countdown_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_countdown_reg_shortcodes_vc');
	function yogastudio_sc_countdown_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_countdown",
			"name" => esc_html__("Countdown", "yogastudio"),
			"description" => wp_kses( __("Insert countdown object", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_countdown',
			"class" => "trx_sc_single trx_sc_countdown",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "date",
					"heading" => esc_html__("Date", "yogastudio"),
					"description" => wp_kses( __("Upcoming date (format: yyyy-mm-dd)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "time",
					"heading" => esc_html__("Time", "yogastudio"),
					"description" => wp_kses( __("Upcoming time (format: HH:mm:ss)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "yogastudio"),
					"description" => wp_kses( __("Countdown style", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(yogastudio_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Align counter to left, center or right", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
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
		
		class WPBakeryShortCode_Trx_Countdown extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>