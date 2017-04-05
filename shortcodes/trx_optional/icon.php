<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_icon_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_icon_theme_setup' );
	function yogastudio_sc_icon_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_icon_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_icon id="unique_id" style='round|square' icon='' color="" bg_color="" size="" weight=""]
*/

if (!function_exists('yogastudio_sc_icon')) {	
	function yogastudio_sc_icon($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !yogastudio_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(yogastudio_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || yogastudio_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !yogastudio_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	yogastudio_require_shortcode('trx_icon', 'yogastudio_sc_icon');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_icon_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_icon_reg_shortcodes');
	function yogastudio_sc_icon_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_icon"] = array(
			"title" => esc_html__("Icon", "yogastudio"),
			"desc" => wp_kses( __("Insert icon", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'yogastudio'),
					"desc" => wp_kses( __('Select font icon from the Fontello icons set',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
				),
				"color" => array(
					"title" => esc_html__("Icon's color", "yogastudio"),
					"desc" => wp_kses( __("Icon's color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_shape" => array(
					"title" => esc_html__("Background shape", "yogastudio"),
					"desc" => wp_kses( __("Shape of the icon background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "none",
					"type" => "radio",
					"options" => array(
						'none' => esc_html__('None', 'yogastudio'),
						'round' => esc_html__('Round', 'yogastudio'),
						'square' => esc_html__('Square', 'yogastudio')
					)
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", "yogastudio"),
					"desc" => wp_kses( __("Icon's background color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", "yogastudio"),
					"desc" => wp_kses( __("Icon's font size", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", "yogastudio"),
					"desc" => wp_kses( __("Icon font weight", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'yogastudio'),
						'300' => esc_html__('Light (300)', 'yogastudio'),
						'400' => esc_html__('Normal (400)', 'yogastudio'),
						'700' => esc_html__('Bold (700)', 'yogastudio')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", "yogastudio"),
					"desc" => wp_kses( __("Icon text alignment", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
				), 
				"link" => array(
					"title" => esc_html__("Link URL", "yogastudio"),
					"desc" => wp_kses( __("Link URL from this icon (if not empty)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"top" => $YOGASTUDIO_GLOBALS['sc_params']['top'],
				"bottom" => $YOGASTUDIO_GLOBALS['sc_params']['bottom'],
				"left" => $YOGASTUDIO_GLOBALS['sc_params']['left'],
				"right" => $YOGASTUDIO_GLOBALS['sc_params']['right'],
				"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
				"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
				"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_icon_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_icon_reg_shortcodes_vc');
	function yogastudio_sc_icon_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", "yogastudio"),
			"description" => wp_kses( __("Insert the icon", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", "yogastudio"),
					"description" => wp_kses( __("Select icon class from Fontello icons set", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "yogastudio"),
					"description" => wp_kses( __("Icon's color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "yogastudio"),
					"description" => wp_kses( __("Background color for the icon", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_shape",
					"heading" => esc_html__("Background shape", "yogastudio"),
					"description" => wp_kses( __("Shape of the icon background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('None', 'yogastudio') => 'none',
						esc_html__('Round', 'yogastudio') => 'round',
						esc_html__('Square', 'yogastudio') => 'square'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "yogastudio"),
					"description" => wp_kses( __("Icon's font size", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", "yogastudio"),
					"description" => wp_kses( __("Icon's font weight", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'yogastudio') => 'inherit',
						esc_html__('Thin (100)', 'yogastudio') => '100',
						esc_html__('Light (300)', 'yogastudio') => '300',
						esc_html__('Normal (400)', 'yogastudio') => '400',
						esc_html__('Bold (700)', 'yogastudio') => '700'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", "yogastudio"),
					"description" => wp_kses( __("Align icon to left, center or right", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "yogastudio"),
					"description" => wp_kses( __("Link URL from this icon (if not empty)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['css'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>