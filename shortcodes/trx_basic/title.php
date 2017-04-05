<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_title_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_title_theme_setup' );
	function yogastudio_sc_title_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_title_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_title id="unique_id" style='regular|iconed' icon='' image='' background="on|off" type="1-6"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta[/trx_title]
*/

if (!function_exists('yogastudio_sc_title')) {	
	function yogastudio_sc_title($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"lined_icon" =>""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !yogastudio_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !yogastudio_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).' '.($lined_icon == 'yes' ? 'lined':'').' sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(yogastudio_strpos($image, 'http:')!==false ? $image : yogastudio_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !yogastudio_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	yogastudio_require_shortcode('trx_title', 'yogastudio_sc_title');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_title_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_title_reg_shortcodes');
	function yogastudio_sc_title_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_title"] = array(
			"title" => esc_html__("Title", "yogastudio"),
			"desc" => wp_kses( __("Create header tag (1-6 level) with many styles", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", "yogastudio"),
					"desc" => wp_kses( __("Title content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", "yogastudio"),
					"desc" => wp_kses( __("Title type (header level)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'yogastudio'),
						'2' => esc_html__('Header 2', 'yogastudio'),
						'3' => esc_html__('Header 3', 'yogastudio'),
						'4' => esc_html__('Header 4', 'yogastudio'),
						'5' => esc_html__('Header 5', 'yogastudio'),
						'6' => esc_html__('Header 6', 'yogastudio'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", "yogastudio"),
					"desc" => wp_kses( __("Title style", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'yogastudio'),
						'underline' => esc_html__('Underline', 'yogastudio'),
						'divider' => esc_html__('Divider', 'yogastudio'),
						'iconed' => esc_html__('With icon (image)', 'yogastudio')
					)
				),
				"lined_icon" => array(
					"title" => esc_html__("Lined", "yogastudio"),
					"desc" => wp_kses( __("line before and afte image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
								'style' => array('iconed')
							),
					"value" => "no",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
					),
				"align" => array(
					"title" => esc_html__("Alignment", "yogastudio"),
					"desc" => wp_kses( __("Title text alignment", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", "yogastudio"),
					"desc" => wp_kses( __("Custom font size. If empty - use theme default", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", "yogastudio"),
					"desc" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'yogastudio'),
						'100' => esc_html__('Thin (100)', 'yogastudio'),
						'300' => esc_html__('Light (300)', 'yogastudio'),
						'400' => esc_html__('Normal (400)', 'yogastudio'),
						'600' => esc_html__('Semibold (600)', 'yogastudio'),
						'700' => esc_html__('Bold (700)', 'yogastudio'),
						'900' => esc_html__('Black (900)', 'yogastudio')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", "yogastudio"),
					"desc" => wp_kses( __("Select color for the title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('Title font icon',  'yogastudio'),
					"desc" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)",  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
				),
				"image" => array(
					"title" => esc_html__('or image icon',  'yogastudio'),
					"desc" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)",  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "images",
					"size" => "small",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['images']
				),
				"picture" => array(
					"title" => esc_html__('or URL for image file', "yogastudio"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"image_size" => array(
					"title" => esc_html__('Image (picture) size', "yogastudio"),
					"desc" => wp_kses( __("Select image (picture) size (if style='iconed')", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "small",
					"type" => "checklist",
					"options" => array(
						'small' => esc_html__('Small', 'yogastudio'),
						'medium' => esc_html__('Medium', 'yogastudio'),
						'large' => esc_html__('Large', 'yogastudio')
					)
				),
				"position" => array(
					"title" => esc_html__('Icon (image) position', "yogastudio"),
					"desc" => wp_kses( __("Select icon (image) position (if style=iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "left",
					"type" => "checklist",
					"options" => array(
						'top' => esc_html__('Top', 'yogastudio'),
						'left' => esc_html__('Left', 'yogastudio')
					)
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
if ( !function_exists( 'yogastudio_sc_title_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_title_reg_shortcodes_vc');
	function yogastudio_sc_title_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", "yogastudio"),
			"description" => wp_kses( __("Create header tag (1-6 level) with many styles", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", "yogastudio"),
					"description" => wp_kses( __("Title content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", "yogastudio"),
					"description" => wp_kses( __("Title type (header level)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'yogastudio') => '1',
						esc_html__('Header 2', 'yogastudio') => '2',
						esc_html__('Header 3', 'yogastudio') => '3',
						esc_html__('Header 4', 'yogastudio') => '4',
						esc_html__('Header 5', 'yogastudio') => '5',
						esc_html__('Header 6', 'yogastudio') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", "yogastudio"),
					"description" => wp_kses( __("Title style: only text (regular) or with icon/image (iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'yogastudio') => 'regular',
						esc_html__('Underline', 'yogastudio') => 'underline',
						esc_html__('Divider', 'yogastudio') => 'divider',
						esc_html__('With icon (image)', 'yogastudio') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
						"param_name" => "lined_icon",
						"heading" => esc_html__("Lines", "yogastudio"),
						"description" => esc_html__("Use before and after lines", "yogastudio"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'style',
							'value' => 'iconed'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Title text alignment", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "yogastudio"),
					"description" => wp_kses( __("Custom font size. If empty - use theme default", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", "yogastudio"),
					"description" => wp_kses( __("Custom font weight. If empty or inherit - use theme default", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'yogastudio') => 'inherit',
						esc_html__('Thin (100)', 'yogastudio') => '100',
						esc_html__('Light (300)', 'yogastudio') => '300',
						esc_html__('Normal (400)', 'yogastudio') => '400',
						esc_html__('Semibold (600)', 'yogastudio') => '600',
						esc_html__('Bold (700)', 'yogastudio') => '700',
						esc_html__('Black (900)', 'yogastudio') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", "yogastudio"),
					"description" => wp_kses( __("Select color for the title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title font icon", "yogastudio"),
					"description" => wp_kses( __("Select font icon for the title from Fontello icons set (if style=iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'yogastudio'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "image",
					"heading" => esc_html__("or image icon", "yogastudio"),
					"description" => wp_kses( __("Select image icon for the title instead icon above (if style=iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"group" => esc_html__('Icon &amp; Image', 'yogastudio'),
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['images'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "picture",
					"heading" => esc_html__("or select uploaded image", "yogastudio"),
					"description" => wp_kses( __("Select or upload image or write URL from other site (if style=iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "image_size",
					"heading" => esc_html__("Image (picture) size", "yogastudio"),
					"description" => wp_kses( __("Select image (picture) size (if style=iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'yogastudio'),
					"class" => "",
					"value" => array(
						esc_html__('Small', 'yogastudio') => 'small',
						esc_html__('Medium', 'yogastudio') => 'medium',
						esc_html__('Large', 'yogastudio') => 'large'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "position",
					"heading" => esc_html__("Icon (image) position", "yogastudio"),
					"description" => wp_kses( __("Select icon (image) position (if style=iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Icon &amp; Image', 'yogastudio'),
					"class" => "",
					"std" => "left",
					"value" => array(
						esc_html__('Top', 'yogastudio') => 'top',
						esc_html__('Left', 'yogastudio') => 'left'
					),
					"type" => "dropdown"
				),
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['animation'],
				$YOGASTUDIO_GLOBALS['vc_params']['css'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>