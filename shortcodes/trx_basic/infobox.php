<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_infobox_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_infobox_theme_setup' );
	function yogastudio_sc_infobox_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_infobox_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_infobox id="unique_id" style="regular|info|success|error|result" static="0|1"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_infobox]
*/

if (!function_exists('yogastudio_sc_infobox')) {	
	function yogastudio_sc_infobox($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');
		if (empty($icon)) {
			if ($icon=='none')
				$icon = '';
			else if ($style=='regular')
				$icon = 'icon-cog';
			else if ($style=='success')
				$icon = 'icon-check';
			else if ($style=='error')
				$icon = 'icon-attention';
			else if ($style=='info')
				$icon = 'icon-info';
		}
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (yogastudio_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !yogastudio_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	yogastudio_require_shortcode('trx_infobox', 'yogastudio_sc_infobox');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_infobox_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_infobox_reg_shortcodes');
	function yogastudio_sc_infobox_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_infobox"] = array(
			"title" => esc_html__("Infobox", "yogastudio"),
			"desc" => wp_kses( __("Insert infobox into your post (page)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "yogastudio"),
					"desc" => wp_kses( __("Infobox style", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'yogastudio'),
						'info' => esc_html__('Info', 'yogastudio'),
						'success' => esc_html__('Success', 'yogastudio'),
						'error' => esc_html__('Error', 'yogastudio')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", "yogastudio"),
					"desc" => wp_kses( __("Create closeable box (with close button)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "no",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"icon" => array(
					"title" => esc_html__("Custom icon",  'yogastudio'),
					"desc" => wp_kses( __('Select icon for the infobox from Fontello icons set. If empty - use default icon',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
				),
				"color" => array(
					"title" => esc_html__("Text color", "yogastudio"),
					"desc" => wp_kses( __("Any color for text and headers", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "yogastudio"),
					"desc" => wp_kses( __("Any background color for this infobox", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", "yogastudio"),
					"desc" => wp_kses( __("Content for infobox", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
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
if ( !function_exists( 'yogastudio_sc_infobox_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_infobox_reg_shortcodes_vc');
	function yogastudio_sc_infobox_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", "yogastudio"),
			"description" => wp_kses( __("Box with info or error message", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "yogastudio"),
					"description" => wp_kses( __("Infobox style", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'yogastudio') => 'regular',
							esc_html__('Info', 'yogastudio') => 'info',
							esc_html__('Success', 'yogastudio') => 'success',
							esc_html__('Error', 'yogastudio') => 'error',
							esc_html__('Result', 'yogastudio') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", "yogastudio"),
					"description" => wp_kses( __("Create closeable box (with close button)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'yogastudio') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Custom icon", "yogastudio"),
					"description" => wp_kses( __("Select icon for the infobox from Fontello icons set. If empty - use default icon", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "yogastudio"),
					"description" => wp_kses( __("Any color for the text and headers", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "yogastudio"),
					"description" => wp_kses( __("Any background color for this infobox", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Message text", "yogastudio"),
					"description" => wp_kses( __("Message for the infobox", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['animation'],
				$YOGASTUDIO_GLOBALS['vc_params']['css'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends YOGASTUDIO_VC_ShortCodeContainer {}
	}
}
?>