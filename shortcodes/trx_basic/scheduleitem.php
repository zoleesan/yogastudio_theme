<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_schedule_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_schedule_theme_setup' );
	function yogastudio_sc_schedule_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_schedule_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_schedule_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_schedule id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_schedule]
[trx_schedule id="unique_id" link="url" title=""]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_schedule]
...
*/

if (!function_exists('yogastudio_sc_schedule')) {	
	function yogastudio_sc_schedule($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"isempty" => "no",
			"title" => "",
			"bg_color" => "",
			"lessontime" => "",
			"tags" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height);
		$title = $title=='' ? 'lesson' : esc_attr($title);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_schedule_wrap' . (!empty($class) ? ' '.esc_attr($class) : '') . ' '.($isempty == "yes"? 'active' : 'disabled' ).'"' 
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. ($css ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. ($isempty =="yes" ? '<div class="sc_schedule_inner">'
				. '<div class="sc_schedule_title" '.(empty($bg_color) ? '' : 'style="background:'.esc_attr($bg_color).'; border-color:'.esc_attr($bg_color).';"').'>'.($title).'</div>'
					. '<div class="sc_schedule_content">'
						. '<div class="sc_schedule_tags">' . str_replace('//', '<br/>', esc_attr($tags) ). '</div>'
						. '<div class="sc_schedule_time">' . esc_attr($lessontime) . '</div>'
					. '</div>'
					. '<div class="sc_schedule_button">
							<a href="' .($link == '' ? '#': esc_url($link)).'">'.esc_html__("Register", "yogastudio").'</a>
					   </div>'
				. '</div>' : '')
				. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_schedule', $atts, $content);
	}
	yogastudio_require_shortcode('trx_schedule', 'yogastudio_sc_schedule');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_schedule_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_schedule_reg_shortcodes');
	function yogastudio_sc_schedule_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_schedule"] = array(
			"title" => esc_html__("Schedule cell", "yogastudio"),
			"desc" => wp_kses( __("Schedule cell message", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"isempty" => array(
					"title" => esc_html__("Empty cell", "yogastudio"),
					"desc" => wp_kses( __("Schedule cell item will be empty", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "no",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"title" => array(
					"title" => esc_html__("Item title", "yogastudio"),
					"desc" => wp_kses( __("Title for current schedule item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"dependency" => array(
								'isempty' => array('no')
							),
					"type" => "text"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "yogastudio"),
					"desc" => wp_kses( __("Color for bg title item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"tags" => array(
					"title" => esc_html__("Item tags", "yogastudio"),
					"desc" => wp_kses( __("Cell tags will use on front", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"dependency" => array(
								'isempty' => array('no')
							),
					"type" => "text"
				),
				"lessontime" => array(
					"title" => esc_html__("Time lesson", "yogastudio"),
					"desc" => wp_kses( __("When it would be?", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"dependency" => array(
								'isempty' => array('no')
							),
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Item link", "yogastudio"),
					"desc" => wp_kses( __("Item link for registr button", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"dependency" => array(
								'slider' => array('no')
							),
					"type" => "text"
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
if ( !function_exists( 'yogastudio_sc_schedule_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_schedule_reg_shortcodes_vc');
	function yogastudio_sc_schedule_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_schedule",
			"name" => esc_html__("Schedule cell", "yogastudio"),
			"description" => wp_kses( __("Schedule cell item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_schedule',
			"class" => "trx_sc_container trx_sc_schedule",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "isempty",
					"description" => wp_kses( __("Schedule cell item will be active", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array("Active cell" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Item title", "yogastudio"),
					"description" => wp_kses( __("Title for current schedule item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
							"element" => "isempty",
							"value" => "yes"
						),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "yogastudio"),
					"description" => wp_kses( __("Color for bg title item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
							"element" => "isempty",
							"value" => "yes"
						),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "tags",
					"heading" => esc_html__("Item tags", "yogastudio"),
					"description" => wp_kses( __("Cell tags will use on front", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
							"element" => "isempty",
							"value" => "yes"
						),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "lessontime",
					"heading" => esc_html__("Time lesson", "yogastudio"),
					"description" => wp_kses( __("When it would be?", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
							"element" => "isempty",
							"value" => "yes"
						),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Item link", "yogastudio"),
					"description" => wp_kses( __("Item link for registr button", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
							"element" => "isempty",
							"value" => "yes"
						),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
			),
			'js_view' => 'VcTrxTextContainerView'
		
		) );
		
		class WPBakeryShortCode_Trx_Schedule extends YOGASTUDIO_VC_ShortCodeContainer {}
	}
}
?>