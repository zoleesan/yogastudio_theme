<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_call_to_action_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_call_to_action_theme_setup' );
	function yogastudio_sc_call_to_action_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_call_to_action_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_call_to_action_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_call_to_action id="unique_id" style="1|2" align="left|center|right"]
	[inner shortcodes]
[/trx_call_to_action]
*/

if (!function_exists('yogastudio_sc_call_to_action')) {	
	function yogastudio_sc_call_to_action($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "1",
			"align" => "center",
			"custom" => "no",
			"accent" => "no",
			"image" => "",
			"video" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'yogastudio'),
			"link2" => '',
			"link2_caption" => '',
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
	
		if (empty($id)) $id = "sc_call_to_action_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
	
		if ($image > 0) {
			$attach = wp_get_attachment_image_src( $image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$image = $attach[0];
		}
		if (!empty($image)) {
			$thumb_sizes = yogastudio_get_thumb_sizes(array('layout' => 'excerpt'));
			$image = !empty($video) 
				? yogastudio_get_resized_image_url($image, $thumb_sizes['w'], $thumb_sizes['h']) 
				: yogastudio_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		if (!empty($video)) {
			$video = '<video' . ($id ? ' id="' . esc_attr($id.'_video') . '"' : '') 
				. ' class="sc_video"'
				. ' src="' . esc_url(yogastudio_get_video_player_url($video)) . '"'
				. ' width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' 
				. ' data-width="' . esc_attr($width) . '" data-height="' . esc_attr($height) . '"' 
				. ' data-ratio="16:9"'
				. ($image ? ' poster="'.esc_attr($image).'" data-image="'.esc_attr($image).'"' : '') 
				. ' controls="controls" loop="loop"'
				. '>'
				. '</video>';
			if (yogastudio_get_custom_option('substitute_video')=='no') {
				$video = yogastudio_get_video_frame($video, $image, '', '');
			} else {
				if ((isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')) {
					$video = yogastudio_substitute_video($video, $width, $height, false);
				}
			}
			if (yogastudio_get_theme_option('use_mediaelement')=='yes')
				yogastudio_enqueue_script('wp-mediaelement');
		}
		
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height);
		
		$content = do_shortcode($content);
		
		$featured = ($style==1 && (!empty($content) || !empty($image) || !empty($video))
					? '<div class="sc_call_to_action_featured column-1_2">'
						. (!empty($content) 
							? $content 
							: (!empty($video) 
								? $video 
								: $image)
							)
						. '</div>'
					: '');
	
		$need_columns = ($featured || $style==2) && !in_array($align, array('center', 'none'))
							? ($style==2 ? 4 : 2)
							: 0;
		
		$buttons = (!empty($link) || !empty($link2) 
						? '<div class="sc_call_to_action_buttons sc_item_buttons'.($need_columns && $style==2 ? ' column-1_'.esc_attr($need_columns) : '').'">'
							. (!empty($link) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' 
								: '')
							. (!empty($link2) 
								? '<div class="sc_call_to_action_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link2).'" icon="icon-right"]'.esc_html($link2_caption).'[/trx_button]').'</div>' 
								: '')
							. '</div>'
						: '');
	
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_call_to_action'
					. (yogastudio_param_is_on($accent) ? ' sc_call_to_action_accented' : '')
					. ' sc_call_to_action_style_' . esc_attr($style) 
					. ' sc_call_to_action_align_'.esc_attr($align)
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. '"'
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (yogastudio_param_is_on($accent) ? '<div class="content_wrap">' : '')
				. ($need_columns ? '<div class="columns_wrap">' : '')
				. ($align!='right' ? $featured : '')
				. ($style==2 && $align=='right' ? $buttons : '')
				. '<div class="sc_call_to_action_info'.($need_columns ? ' column-'.esc_attr($need_columns-1).'_'.esc_attr($need_columns) : '').'">'
					. (!empty($subtitle) ? '<h6 class="sc_call_to_action_subtitle sc_item_subtitle">' . trim(yogastudio_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_call_to_action_title sc_item_title">' . trim(yogastudio_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_call_to_action_descr sc_item_descr">' . trim(yogastudio_strmacros($description)) . '</div>' : '')
					. ($style==1 ? $buttons : '')
				. '</div>'
				. ($style==2 && $align!='right' ? $buttons : '')
				. ($align=='right' ? $featured : '')
				. ($need_columns ? '</div>' : '')
				. (yogastudio_param_is_on($accent) ? '</div>' : '')
			. '</div>';
	
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_call_to_action', $atts, $content);
	}
	yogastudio_require_shortcode('trx_call_to_action', 'yogastudio_sc_call_to_action');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_call_to_action_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_call_to_action_reg_shortcodes');
	function yogastudio_sc_call_to_action_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_call_to_action"] = array(
			"title" => esc_html__("Call to action", "yogastudio"),
			"desc" => wp_kses( __("Insert call to action block in your page (post)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", "yogastudio"),
					"desc" => wp_kses( __("Title for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", "yogastudio"),
					"desc" => wp_kses( __("Subtitle for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", "yogastudio"),
					"desc" => wp_kses( __("Short description for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "textarea"
				),
				"style" => array(
					"title" => esc_html__("Style", "yogastudio"),
					"desc" => wp_kses( __("Select style to display block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "1",
					"type" => "checklist",
					"options" => yogastudio_get_list_styles(1, 2)
				),
				"align" => array(
					"title" => esc_html__("Alignment", "yogastudio"),
					"desc" => wp_kses( __("Alignment elements in the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
				),
				"accent" => array(
					"title" => esc_html__("Accented", "yogastudio"),
					"desc" => wp_kses( __("Fill entire block with Accent1 color from current color scheme", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"custom" => array(
					"title" => esc_html__("Custom", "yogastudio"),
					"desc" => wp_kses( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "no",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"image" => array(
					"title" => esc_html__("Image", "yogastudio"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site to include image into this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"video" => array(
					"title" => esc_html__("URL for video file", "yogastudio"),
					"desc" => wp_kses( __("Select video from media library or paste URL for video file from other site to include video into this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
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
				"link" => array(
					"title" => esc_html__("Button URL", "yogastudio"),
					"desc" => wp_kses( __("Link URL for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", "yogastudio"),
					"desc" => wp_kses( __("Caption for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"link2" => array(
					"title" => esc_html__("Button 2 URL", "yogastudio"),
					"desc" => wp_kses( __("Link URL for the second button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"link2_caption" => array(
					"title" => esc_html__("Button 2 caption", "yogastudio"),
					"desc" => wp_kses( __("Caption for the second button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
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
if ( !function_exists( 'yogastudio_sc_call_to_action_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_call_to_action_reg_shortcodes_vc');
	function yogastudio_sc_call_to_action_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_call_to_action",
			"name" => esc_html__("Call to Action", "yogastudio"),
			"description" => wp_kses( __("Insert call to action block in your page (post)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_call_to_action',
			"class" => "trx_sc_collection trx_sc_call_to_action",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block's style", "yogastudio"),
					"description" => wp_kses( __("Select style to display this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(yogastudio_get_list_styles(1, 2)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Select block alignment", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "accent",
					"heading" => esc_html__("Accent", "yogastudio"),
					"description" => wp_kses( __("Fill entire block with Accent1 color from current color scheme", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Fill with Accent1 color" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom", "yogastudio"),
					"description" => wp_kses( __("Allow get featured image or video from inner shortcodes (custom) or get it from shortcode parameters below", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Custom content" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("Image", "yogastudio"),
					"description" => wp_kses( __("Image to display inside block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "video",
					"heading" => esc_html__("URL for video file", "yogastudio"),
					"description" => wp_kses( __("Paste URL for video file to display inside block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'custom',
						'is_empty' => true
					),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "yogastudio"),
					"description" => wp_kses( __("Title for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", "yogastudio"),
					"description" => wp_kses( __("Subtitle for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", "yogastudio"),
					"description" => wp_kses( __("Description for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", "yogastudio"),
					"description" => wp_kses( __("Link URL for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", "yogastudio"),
					"description" => wp_kses( __("Caption for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2",
					"heading" => esc_html__("Button 2 URL", "yogastudio"),
					"description" => wp_kses( __("Link URL for the second button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link2_caption",
					"heading" => esc_html__("Button 2 caption", "yogastudio"),
					"description" => wp_kses( __("Caption for the second button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Captions', 'yogastudio'),
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
		
		class WPBakeryShortCode_Trx_Call_To_Action extends YOGASTUDIO_VC_ShortCodeCollection {}
	}
}
?>