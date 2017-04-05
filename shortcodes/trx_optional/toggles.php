<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_toggles_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_toggles_theme_setup' );
	function yogastudio_sc_toggles_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_toggles_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_toggles_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('yogastudio_sc_toggles')) {	
	function yogastudio_sc_toggles($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
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
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_toggle_counter'] = 0;
		$YOGASTUDIO_GLOBALS['sc_toggle_show_counter'] = yogastudio_param_is_on($counter);
		$YOGASTUDIO_GLOBALS['sc_toggles_icon_closed'] = empty($icon_closed) || yogastudio_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed;
		$YOGASTUDIO_GLOBALS['sc_toggles_icon_opened'] = empty($icon_opened) || yogastudio_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened;
		yogastudio_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (yogastudio_param_is_on($counter) ? ' sc_show_counter' : '') 
					. '"'
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	yogastudio_require_shortcode('trx_toggles', 'yogastudio_sc_toggles');
}


if (!function_exists('yogastudio_sc_toggles_item')) {	
	function yogastudio_sc_toggles_item($atts, $content=null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_toggle_counter']++;
		if (empty($icon_closed) || yogastudio_param_is_inherit($icon_closed)) $icon_closed = $YOGASTUDIO_GLOBALS['sc_toggles_icon_closed'] ? $YOGASTUDIO_GLOBALS['sc_toggles_icon_closed'] : "icon-plus";
		if (empty($icon_opened) || yogastudio_param_is_inherit($icon_opened)) $icon_opened = $YOGASTUDIO_GLOBALS['sc_toggles_icon_opened'] ? $YOGASTUDIO_GLOBALS['sc_toggles_icon_opened'] : "icon-minus";
		$css .= yogastudio_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(yogastudio_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($YOGASTUDIO_GLOBALS['sc_toggle_counter'] % 2 == 1 ? ' odd' : ' even') 
					. ($YOGASTUDIO_GLOBALS['sc_toggle_counter'] == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(yogastudio_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (!yogastudio_param_is_off($icon_closed) ? '<span class="sc_toggles_icon sc_toggles_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
					. (!yogastudio_param_is_off($icon_opened) ? '<span class="sc_toggles_icon sc_toggles_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
					. ($YOGASTUDIO_GLOBALS['sc_toggle_show_counter'] ? '<span class="sc_items_counter">'.($YOGASTUDIO_GLOBALS['sc_toggle_counter']).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	yogastudio_require_shortcode('trx_toggles_item', 'yogastudio_sc_toggles_item');
}


/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_toggles_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_toggles_reg_shortcodes');
	function yogastudio_sc_toggles_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_toggles"] = array(
			"title" => esc_html__("Toggles", "yogastudio"),
			"desc" => wp_kses( __("Toggles items", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"counter" => array(
					"title" => esc_html__("Counter", "yogastudio"),
					"desc" => wp_kses( __("Display counter before each toggles title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "off",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['on_off']
				),
				"icon_closed" => array(
					"title" => esc_html__("Icon while closed",  'yogastudio'),
					"desc" => wp_kses( __('Select icon for the closed toggles item from Fontello icons set',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
				),
				"icon_opened" => array(
					"title" => esc_html__("Icon while opened",  'yogastudio'),
					"desc" => wp_kses( __('Select icon for the opened toggles item from Fontello icons set',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
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
				"name" => "trx_toggles_item",
				"title" => esc_html__("Toggles item", "yogastudio"),
				"desc" => wp_kses( __("Toggles item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Toggles item title", "yogastudio"),
						"desc" => wp_kses( __("Title for current toggles item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"open" => array(
						"title" => esc_html__("Open on show", "yogastudio"),
						"desc" => wp_kses( __("Open current toggles item on show", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "no",
						"type" => "switch",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
					),
					"icon_closed" => array(
						"title" => esc_html__("Icon while closed",  'yogastudio'),
						"desc" => wp_kses( __('Select icon for the closed toggles item from Fontello icons set',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
					),
					"icon_opened" => array(
						"title" => esc_html__("Icon while opened",  'yogastudio'),
						"desc" => wp_kses( __('Select icon for the opened toggles item from Fontello icons set',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
					),
					"_content_" => array(
						"title" => esc_html__("Toggles item content", "yogastudio"),
						"desc" => wp_kses( __("Current toggles item content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
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
if ( !function_exists( 'yogastudio_sc_toggles_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_toggles_reg_shortcodes_vc');
	function yogastudio_sc_toggles_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_toggles",
			"name" => esc_html__("Toggles", "yogastudio"),
			"description" => wp_kses( __("Toggles items", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_toggles',
			"class" => "trx_sc_collection trx_sc_toggles",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_toggles_item'),
			"params" => array(
				array(
					"param_name" => "counter",
					"heading" => esc_html__("Counter", "yogastudio"),
					"description" => wp_kses( __("Display counter before each toggles title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Add item numbers before each element" => "on" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", "yogastudio"),
					"description" => wp_kses( __("Select icon for the closed toggles item from Fontello icons set", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", "yogastudio"),
					"description" => wp_kses( __("Select icon for the opened toggles item from Fontello icons set", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
			),
			'default_content' => '
				[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'yogastudio' ) . '"][/trx_toggles_item]
				[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'yogastudio' ) . '"][/trx_toggles_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", "yogastudio").'">'.esc_html__("Add item", "yogastudio").'</button>
				</div>
			',
			'js_view' => 'VcTrxTogglesView'
		) );
		
		
		vc_map( array(
			"base" => "trx_toggles_item",
			"name" => esc_html__("Toggles item", "yogastudio"),
			"description" => wp_kses( __("Single toggles item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_toggles_item',
			"as_child" => array('only' => 'trx_toggles'),
			"as_parent" => array('except' => 'trx_toggles'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "yogastudio"),
					"description" => wp_kses( __("Title for current toggles item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Open on show", "yogastudio"),
					"description" => wp_kses( __("Open current toggle item on show", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Opened" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "icon_closed",
					"heading" => esc_html__("Icon while closed", "yogastudio"),
					"description" => wp_kses( __("Select icon for the closed toggles item from Fontello icons set", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_opened",
					"heading" => esc_html__("Icon while opened", "yogastudio"),
					"description" => wp_kses( __("Select icon for the opened toggles item from Fontello icons set", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxTogglesTabView'
		) );
		class WPBakeryShortCode_Trx_Toggles extends YOGASTUDIO_VC_ShortCodeToggles {}
		class WPBakeryShortCode_Trx_Toggles_Item extends YOGASTUDIO_VC_ShortCodeTogglesItem {}
	}
}
?>