<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_parallax_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_parallax_theme_setup' );
	function yogastudio_sc_parallax_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_parallax_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_parallax_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_parallax id="unique_id" style="light|dark" dir="up|down" image="" color='']Content for parallax block[/trx_parallax]
*/

if (!function_exists('yogastudio_sc_parallax')) {	
	function yogastudio_sc_parallax($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"gap" => "no",
			"dir" => "up",
			"speed" => 0.3,
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_image_x" => "",
			"bg_image_y" => "",
			"bg_video" => "",
			"bg_video_ratio" => "16:9",
			"bg_overlay" => "",
			"bg_texture" => "",
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
		if ($bg_video!='') {
			$info = pathinfo($bg_video);
			$ext = !empty($info['extension']) ? $info['extension'] : 'mp4';
			$bg_video_ratio = empty($bg_video_ratio) ? "16:9" : str_replace(array('/','\\','-'), ':', $bg_video_ratio);
			$ratio = explode(':', $bg_video_ratio);
			$bg_video_width = !empty($width) && yogastudio_substr($width, -1) >= '0' && yogastudio_substr($width, -1) <= '9'  ? $width : 1280;
			$bg_video_height = round($bg_video_width / $ratio[0] * $ratio[1]);
			if (yogastudio_get_theme_option('use_mediaelement')=='yes')
				yogastudio_enqueue_script('wp-mediaelement');
		}
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
		$bg_image_x = $bg_image_x!='' ? str_replace('%', '', $bg_image_x).'%' : "50%";
		$bg_image_y = $bg_image_y!='' ? str_replace('%', '', $bg_image_y).'%' : "50%";
		$speed = ($dir=='down' ? -1 : 1) * abs($speed);
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = yogastudio_get_scheme_color('bg');
			$rgb = yogastudio_hex2rgb($bg_color);
		}
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			;
		$output = (yogastudio_param_is_on($gap) ? yogastudio_gap_start() : '')
			. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_parallax' 
					. ($bg_video!='' ? ' sc_parallax_with_video' : '') 
					. ($scheme && !yogastudio_param_is_off($scheme) && !yogastudio_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. ' data-parallax-speed="'.esc_attr($speed).'"'
				. ' data-parallax-x-pos="'.esc_attr($bg_image_x).'"'
				. ' data-parallax-y-pos="'.esc_attr($bg_image_y).'"'
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. '>'
			. ($bg_video!='' 
				? '<div class="sc_video_bg_wrapper"><video class="sc_video_bg"'
					. ' width="'.esc_attr($bg_video_width).'" height="'.esc_attr($bg_video_height).'" data-width="'.esc_attr($bg_video_width).'" data-height="'.esc_attr($bg_video_height).'" data-ratio="'.esc_attr($bg_video_ratio).'" data-frame="no"'
					. ' preload="metadata" autoplay="autoplay" loop="loop" src="'.esc_attr($bg_video).'"><source src="'.esc_url($bg_video).'" type="video/'.esc_attr($ext).'"></source></video></div>' 
				: '')
			. '<div class="sc_parallax_content" style="' . ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . '); background-position:'.esc_attr($bg_image_x).' '.esc_attr($bg_image_y).';' : '').'">'
			. ($bg_overlay>0 || $bg_texture!=''
				? '<div class="sc_parallax_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
					. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
						. (yogastudio_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
						. '"'
						. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
						. '>' 
				: '')
			. do_shortcode($content)
			. ($bg_overlay > 0 || $bg_texture!='' ? '</div>' : '')
			. '</div>'
			. '</div>'
			. (yogastudio_param_is_on($gap) ? yogastudio_gap_end() : '');
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_parallax', $atts, $content);
	}
	yogastudio_require_shortcode('trx_parallax', 'yogastudio_sc_parallax');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_parallax_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_parallax_reg_shortcodes');
	function yogastudio_sc_parallax_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_parallax"] = array(
			"title" => esc_html__("Parallax", "yogastudio"),
			"desc" => wp_kses( __("Create the parallax container (with asinc background image)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"gap" => array(
					"title" => esc_html__("Create gap", "yogastudio"),
					"desc" => wp_kses( __("Create gap around parallax container", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "no",
					"size" => "small",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no'],
					"type" => "switch"
				), 
				"dir" => array(
					"title" => esc_html__("Dir", "yogastudio"),
					"desc" => wp_kses( __("Scroll direction for the parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "up",
					"size" => "medium",
					"options" => array(
						'up' => esc_html__('Up', 'yogastudio'),
						'down' => esc_html__('Down', 'yogastudio')
					),
					"type" => "switch"
				), 
				"speed" => array(
					"title" => esc_html__("Speed", "yogastudio"),
					"desc" => wp_kses( __("Image motion speed (from 0.0 to 1.0)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0.3",
					"type" => "spinner"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "yogastudio"),
					"desc" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['schemes']
				),
				"color" => array(
					"title" => esc_html__("Text color", "yogastudio"),
					"desc" => wp_kses( __("Select color for text object inside parallax block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "yogastudio"),
					"desc" => wp_kses( __("Select color for parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image", "yogastudio"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for the parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_image_x" => array(
					"title" => esc_html__("Image X position", "yogastudio"),
					"desc" => wp_kses( __("Image horizontal position (as background of the parallax block) - in percent", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"min" => "0",
					"max" => "100",
					"value" => "50",
					"type" => "spinner"
				),
				"bg_video" => array(
					"title" => esc_html__("Video background", "yogastudio"),
					"desc" => wp_kses( __("Select video from media library or paste URL for video file from other site to show it as parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'title' => esc_html__('Choose video', 'yogastudio'),
						'action' => 'media_upload',
						'type' => 'video',
						'multiple' => false,
						'linked_field' => '',
						'captions' => array( 	
							'choose' => esc_html__('Choose video file', 'yogastudio'),
							'update' => esc_html__('Select video file', 'yogastudio')
						)
					),
					"after" => array(
						'icon' => 'icon-cancel',
						'action' => 'media_reset'
					)
				),
				"bg_video_ratio" => array(
					"title" => esc_html__("Video ratio", "yogastudio"),
					"desc" => wp_kses( __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "16:9",
					"type" => "text"
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", "yogastudio"),
					"desc" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", "yogastudio"),
					"desc" => wp_kses( __("Predefined texture style from 1 to 11. 0 - without texture.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
				),
				"_content_" => array(
					"title" => esc_html__("Content", "yogastudio"),
					"desc" => wp_kses( __("Content for the parallax container", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
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
if ( !function_exists( 'yogastudio_sc_parallax_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_parallax_reg_shortcodes_vc');
	function yogastudio_sc_parallax_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_parallax",
			"name" => esc_html__("Parallax", "yogastudio"),
			"description" => wp_kses( __("Create the parallax container (with asinc background image)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Structure', 'yogastudio'),
			'icon' => 'icon_trx_parallax',
			"class" => "trx_sc_collection trx_sc_parallax",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "gap",
					"heading" => esc_html__("Create gap", "yogastudio"),
					"description" => wp_kses( __("Create gap around parallax container (not need in fullscreen pages)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Create gap', 'yogastudio') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", "yogastudio"),
					"description" => wp_kses( __("Scroll direction for the parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Up', 'yogastudio') => 'up',
							esc_html__('Down', 'yogastudio') => 'down'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "speed",
					"heading" => esc_html__("Speed", "yogastudio"),
					"description" => wp_kses( __("Parallax background motion speed (from 0.0 to 1.0)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "0.3",
					"type" => "textfield"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "yogastudio"),
					"description" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'yogastudio'),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "yogastudio"),
					"description" => wp_kses( __("Select color for text object inside parallax block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Backgroud color", "yogastudio"),
					"description" => wp_kses( __("Select color for parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image", "yogastudio"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for the parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_image_x",
					"heading" => esc_html__("Image X position", "yogastudio"),
					"description" => wp_kses( __("Parallax background X position (in percents)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "50%",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_video",
					"heading" => esc_html__("Video background", "yogastudio"),
					"description" => wp_kses( __("Paste URL for video file to show it as parallax background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_video_ratio",
					"heading" => esc_html__("Video ratio", "yogastudio"),
					"description" => wp_kses( __("Specify ratio of the video background. For example: 16:9 (default), 4:3, etc.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "16:9",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", "yogastudio"),
					"description" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", "yogastudio"),
					"description" => wp_kses( __("Texture style from 1 to 11. Empty or 0 - without texture.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Content", "yogastudio"),
					"description" => wp_kses( __("Content for the parallax container", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
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
		
		class WPBakeryShortCode_Trx_Parallax extends YOGASTUDIO_VC_ShortCodeCollection {}
	}
}
?>