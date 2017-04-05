<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_zoom_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_zoom_theme_setup' );
	function yogastudio_sc_zoom_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_zoom_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_zoom_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_zoom id="unique_id" border="none|light|dark"]
*/

if (!function_exists('yogastudio_sc_zoom')) {	
	function yogastudio_sc_zoom($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"effect" => "zoom",
			"src" => "",
			"url" => "",
			"over" => "",
			"align" => "",
			"bg_image" => "",
			"bg_top" => '',
			"bg_bottom" => '',
			"bg_left" => '',
			"bg_right" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		yogastudio_enqueue_script( 'yogastudio-elevate-zoom-script', yogastudio_get_file_url('js/jquery.elevateZoom-3.0.4.js'), array(), null, true );
	
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css_dim = yogastudio_get_css_dimensions_from_values($width, $height);
		$css_bg = yogastudio_get_css_paddings_from_values($bg_top, $bg_right, $bg_bottom, $bg_left);
		$width  = yogastudio_prepare_css_value($width);
		$height = yogastudio_prepare_css_value($height);
		if (empty($id)) $id = 'sc_zoom_'.str_replace('.', '', mt_rand());
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if ($over > 0) {
			$attach = wp_get_attachment_image_src( $over, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$over = $attach[0];
		}
		if ($effect=='lens' && ((int) $width > 0 && yogastudio_substr($width, -2, 2)=='px') || ((int) $height > 0 && yogastudio_substr($height, -2, 2)=='px')) {
			if ($src)
				$src = yogastudio_get_resized_image_url($src, (int) $width > 0 && yogastudio_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && yogastudio_substr($height, -2, 2)=='px' ? (int) $height : null);
			if ($over)
				$over = yogastudio_get_resized_image_url($over, (int) $width > 0 && yogastudio_substr($width, -2, 2)=='px' ? (int) $width : null, (int) $height > 0 && yogastudio_substr($height, -2, 2)=='px' ? (int) $height : null);
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		if ($bg_image) {
			$css_bg .= $css . 'background-image: url('.esc_url($bg_image).');';
			$css = $css_dim;
		} else {
			$css .= $css_dim;
		}
		$output = empty($src) 
				? '' 
				: (
					(!empty($bg_image) 
						? '<div class="sc_zoom_wrap'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
								. '"'
							. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
							. ($css_bg!='' ? ' style="'.esc_attr($css_bg).'"' : '') 
							. '>' 
						: '')
					.'<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_zoom' 
								. (empty($bg_image) && !empty($class) ? ' '.esc_attr($class) : '') 
								. (empty($bg_image) && $align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (empty($bg_image) && !yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
							. '>'
							. '<img src="'.esc_url($src).'"' . ($css_dim!='' ? ' style="'.esc_attr($css_dim).'"' : '') . ' data-zoom-image="'.esc_url($over).'" alt="" />'
					. '</div>'
					. (!empty($bg_image) 
						? '</div>' 
						: '')
				);
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_zoom', $atts, $content);
	}
	yogastudio_require_shortcode('trx_zoom', 'yogastudio_sc_zoom');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_zoom_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_zoom_reg_shortcodes');
	function yogastudio_sc_zoom_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_zoom"] = array(
			"title" => esc_html__("Zoom", "yogastudio"),
			"desc" => wp_kses( __("Insert the image with zoom/lens effect", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"effect" => array(
					"title" => esc_html__("Effect", "yogastudio"),
					"desc" => wp_kses( __("Select effect to display overlapping image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "lens",
					"size" => "medium",
					"type" => "switch",
					"options" => array(
						"lens" => esc_html__('Lens', 'yogastudio'),
						"zoom" => esc_html__('Zoom', 'yogastudio')
					)
				),
				"url" => array(
					"title" => esc_html__("Main image", "yogastudio"),
					"desc" => wp_kses( __("Select or upload main image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"over" => array(
					"title" => esc_html__("Overlaping image", "yogastudio"),
					"desc" => wp_kses( __("Select or upload overlaping image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"align" => array(
					"title" => esc_html__("Float zoom", "yogastudio"),
					"desc" => wp_kses( __("Float zoom to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['float']
				), 
				"bg_image" => array(
					"title" => esc_html__("Background image", "yogastudio"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for zoom block background. Attention! If you use background image - specify paddings below from background margins to zoom block in percents!", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_top" => array(
					"title" => esc_html__("Top offset", "yogastudio"),
					"desc" => wp_kses( __("Top offset (padding) inside background image to zoom block (in percent). For example: 3%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_bottom" => array(
					"title" => esc_html__("Bottom offset", "yogastudio"),
					"desc" => wp_kses( __("Bottom offset (padding) inside background image to zoom block (in percent). For example: 3%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_left" => array(
					"title" => esc_html__("Left offset", "yogastudio"),
					"desc" => wp_kses( __("Left offset (padding) inside background image to zoom block (in percent). For example: 20%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"bg_right" => array(
					"title" => esc_html__("Right offset", "yogastudio"),
					"desc" => wp_kses( __("Right offset (padding) inside background image to zoom block (in percent). For example: 12%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"value" => "",
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
if ( !function_exists( 'yogastudio_sc_zoom_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_zoom_reg_shortcodes_vc');
	function yogastudio_sc_zoom_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_zoom",
			"name" => esc_html__("Zoom", "yogastudio"),
			"description" => wp_kses( __("Insert the image with zoom/lens effect", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_zoom',
			"class" => "trx_sc_single trx_sc_zoom",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "effect",
					"heading" => esc_html__("Effect", "yogastudio"),
					"description" => wp_kses( __("Select effect to display overlapping image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"std" => "zoom",
					"value" => array(
						esc_html__('Lens', 'yogastudio') => 'lens',
						esc_html__('Zoom', 'yogastudio') => 'zoom'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Main image", "yogastudio"),
					"description" => wp_kses( __("Select or upload main image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "over",
					"heading" => esc_html__("Overlaping image", "yogastudio"),
					"description" => wp_kses( __("Select or upload overlaping image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Float zoom to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['float']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", "yogastudio"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for zoom background. Attention! If you use background image - specify paddings below from background margins to video block in percents!", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_top",
					"heading" => esc_html__("Top offset", "yogastudio"),
					"description" => wp_kses( __("Top offset (padding) from background image to zoom block (in percent). For example: 3%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_bottom",
					"heading" => esc_html__("Bottom offset", "yogastudio"),
					"description" => wp_kses( __("Bottom offset (padding) from background image to zoom block (in percent). For example: 3%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_left",
					"heading" => esc_html__("Left offset", "yogastudio"),
					"description" => wp_kses( __("Left offset (padding) from background image to zoom block (in percent). For example: 20%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_right",
					"heading" => esc_html__("Right offset", "yogastudio"),
					"description" => wp_kses( __("Right offset (padding) from background image to zoom block (in percent). For example: 12%", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Background', 'yogastudio'),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Zoom extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>