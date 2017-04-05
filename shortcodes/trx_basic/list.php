<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_list_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_list_theme_setup' );
	function yogastudio_sc_list_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_list_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_list id="unique_id" style="arrows|iconed|ol|ul"]
	[trx_list_item id="unique_id" title="title_of_element"]Et adipiscing integer.[/trx_list_item]
	[trx_list_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in.[/trx_list_item]
	[trx_list_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer.[/trx_list_item]
	[trx_list_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus.[/trx_list_item]
[/trx_list]
*/

if (!function_exists('yogastudio_sc_list')) {	
	function yogastudio_sc_list($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"underlined" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_list_counter'] = 0;
		$YOGASTUDIO_GLOBALS['sc_list_icon'] = empty($icon) || yogastudio_param_is_inherit($icon) ? "icon-right" : $icon;
		$YOGASTUDIO_GLOBALS['sc_list_icon_color'] = $icon_color;
		$YOGASTUDIO_GLOBALS['sc_list_style'] = $style;
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') 
				. ($underlined=='true' ? ' underlined' : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	yogastudio_require_shortcode('trx_list', 'yogastudio_sc_list');
}


if (!function_exists('yogastudio_sc_list_item')) {	
	function yogastudio_sc_list_item($atts, $content=null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"style"=>"",
			"class" => "",
			"css" => ""
		), $atts)));
		global $YOGASTUDIO_GLOBALS;
		$style = $YOGASTUDIO_GLOBALS['sc_list_style'];
		$YOGASTUDIO_GLOBALS['sc_list_counter']++;
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || yogastudio_param_is_inherit($icon)) $icon = $YOGASTUDIO_GLOBALS['sc_list_icon'];
		if (trim($color) == '' || yogastudio_param_is_inherit($icon_color)) $icon_color = $YOGASTUDIO_GLOBALS['sc_list_icon_color'];
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. ($YOGASTUDIO_GLOBALS['sc_list_counter'] % 2 == 1 ? ' odd' : ' even') 
			. ($YOGASTUDIO_GLOBALS['sc_list_counter'] == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. ($YOGASTUDIO_GLOBALS['sc_list_style']=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. ($style=='ol' ? '<span>' : '')
			. trim($content)
			. ($style=='ol' ? '</span>' : '')
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	yogastudio_require_shortcode('trx_list_item', 'yogastudio_sc_list_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_list_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_list_reg_shortcodes');
	function yogastudio_sc_list_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;

		$YOGASTUDIO_GLOBALS['shortcodes']["trx_list"] = array(
			"title" => esc_html__("List", "yogastudio"),
			"desc" => wp_kses( __("List items with specific bullets", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", "yogastudio"),
					"desc" => wp_kses( __("Bullet's style for each list item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "ul",
					"type" => "checklist",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['list_styles']
					),
				"underlined" => array(
					"title" => __("Line for li", "yogastudio"),
					"desc" => __("Add line for li", "yogastudio"),
					"type" => "checkbox"
					), 
				"color" => array(
					"title" => esc_html__("Color", "yogastudio"),
					"desc" => wp_kses( __("List items color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
					),
				"icon" => array(
					"title" => esc_html__('List icon',  'yogastudio'),
					"desc" => wp_kses( __("Select list icon from Fontello icons set (only for style=Iconed)",  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", "yogastudio"),
					"desc" => wp_kses( __("List icons color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
				),
				"top" => $YOGASTUDIO_GLOBALS['sc_params']['top'],
				"bottom" => $YOGASTUDIO_GLOBALS['sc_params']['bottom'],
				"left" => $YOGASTUDIO_GLOBALS['sc_params']['left'],
				"right" => $YOGASTUDIO_GLOBALS['sc_params']['right'],
				"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
				"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
				"animation" => $YOGASTUDIO_GLOBALS['sc_params']['animation'],
				"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
			),
			"children" => array(
				"name" => "trx_list_item",
				"title" => esc_html__("Item", "yogastudio"),
				"desc" => wp_kses( __("List item with specific bullet", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", "yogastudio"),
						"desc" => wp_kses( __("Current list item content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", "yogastudio"),
						"desc" => wp_kses( __("Current list item title (show it as tooltip)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", "yogastudio"),
						"desc" => wp_kses( __("Text color for this item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'yogastudio'),
						"desc" => wp_kses( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", "yogastudio"),
						"desc" => wp_kses( __("Icon color for this item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", "yogastudio"),
						"desc" => wp_kses( __("Link URL for the current list item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", "yogastudio"),
						"desc" => wp_kses( __("Link target for the current list item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
					"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
					"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_list_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_list_reg_shortcodes_vc');
	function yogastudio_sc_list_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", "yogastudio"),
			"description" => wp_kses( __("List items with specific bullets", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", "yogastudio"),
					"description" => wp_kses( __("Bullet's style for each list item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['list_styles']),
					"type" => "dropdown"
					),
				array(
					"param_name" => "underlined",
					"heading" => __("Underline", "yogastudio"),
					"description" => __("Underline for li", "yogastudio"),
					"type" => "checkbox"
					),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "yogastudio"),
					"description" => wp_kses( __("List items color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", "yogastudio"),
					"description" => wp_kses( __("Select list icon from Fontello icons set (only for style=Iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", "yogastudio"),
					"description" => wp_kses( __("List icons color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
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
			'default_content' => '
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", "yogastudio"),
			"description" => wp_kses( __("List item with specific bullet", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", "yogastudio"),
					"description" => wp_kses( __("Title for the current list item (show it as tooltip)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "yogastudio"),
					"description" => wp_kses( __("Link URL for the current list item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", "yogastudio"),
					"description" => wp_kses( __("Link target for the current list item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "yogastudio"),
					"description" => wp_kses( __("Text color for this item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", "yogastudio"),
					"description" => wp_kses( __("Select list item icon from Fontello icons set (only for style=Iconed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", "yogastudio"),
					"description" => wp_kses( __("Icon color for this item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("List item text", "yogastudio"),
					"description" => wp_kses( __("Current list item content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
*/
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['css']
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends YOGASTUDIO_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends YOGASTUDIO_VC_ShortCodeContainer {}
	}
}
?>