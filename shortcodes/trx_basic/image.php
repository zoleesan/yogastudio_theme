<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_image_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_image_theme_setup' );
	function yogastudio_sc_image_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_image_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_image id="unique_id" src="image_url" width="width_in_pixels" height="height_in_pixels" title="image's_title" align="left|right"]
*/

if (!function_exists('yogastudio_sc_image')) {	
	function yogastudio_sc_image($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = yogastudio_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) yogastudio_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') 
			. (trim($link) ? ' '.'hover_icon hover_icon_view' : '') .'"'
			. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	yogastudio_require_shortcode('trx_image', 'yogastudio_sc_image');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_image_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_image_reg_shortcodes');
	function yogastudio_sc_image_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_image"] = array(
			"title" => esc_html__("Image", "yogastudio"),
			"desc" => wp_kses( __("Insert image into your post (page)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", "yogastudio"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", "yogastudio"),
					"desc" => wp_kses( __("Image title (if need)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'yogastudio'),
					"desc" => wp_kses( __('Select icon for the title from Fontello icons set',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
				),
				"align" => array(
					"title" => esc_html__("Float image", "yogastudio"),
					"desc" => wp_kses( __("Float image to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['float']
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", "yogastudio"),
					"desc" => wp_kses( __("Shape of the image: square (rectangle) or round", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'yogastudio'),
						"round" => esc_html__('Round', 'yogastudio')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", "yogastudio"),
					"desc" => wp_kses( __("The link URL from the image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
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
if ( !function_exists( 'yogastudio_sc_image_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_image_reg_shortcodes_vc');
	function yogastudio_sc_image_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", "yogastudio"),
			"description" => wp_kses( __("Insert image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", "yogastudio"),
					"description" => wp_kses( __("Select image from library", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", "yogastudio"),
					"description" => wp_kses( __("Align image to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['float']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", "yogastudio"),
					"description" => wp_kses( __("Shape of the image: square or round", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'yogastudio') => 'square',
						esc_html__('Round', 'yogastudio') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "yogastudio"),
					"description" => wp_kses( __("Image's title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", "yogastudio"),
					"description" => wp_kses( __("Select icon for the title from Fontello icons set", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", "yogastudio"),
					"description" => wp_kses( __("The link URL from the image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
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
		
		class WPBakeryShortCode_Trx_Image extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>