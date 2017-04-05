<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_price_block_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_price_block_theme_setup' );
	function yogastudio_sc_price_block_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_price_block_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_price_block_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('yogastudio_sc_price_block')) {	
	function yogastudio_sc_price_block($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"style" => 1,
			"title" => "",
			"link" => "",
			"link_text" => "",
			"icon" => "",
			"money" => "",
			"currency" => "$",
			"period" => "",
			"align" => "",
			"scheme" => "",
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
		$output = '';
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height);
		if ($money) $money = do_shortcode('[trx_price money="'.esc_attr($money).'" period="'.esc_attr($period).'"'.($currency ? ' currency="'.esc_attr($currency).'"' : '').']');
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_price_block sc_price_block_style_'.max(1, min(3, $style))
						. (!empty($class) ? ' '.esc_attr($class) : '')
						. ($scheme && !yogastudio_param_is_off($scheme) && !yogastudio_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
					. '>'
				. (!empty($title) ? '<div class="sc_price_block_title">'.($title).'</div>' : '')
				. '<div class="sc_price_block_money">'
					. (!empty($icon) ? '<div class="sc_price_block_icon '.esc_attr($icon).'"></div>' : '')
					. ($money)
				. '</div>'
				. (!empty($content) ? '<div class="sc_price_block_description">'.($content).'</div>' : '')
				. (!empty($link_text) ? '<div class="sc_price_block_link">'.do_shortcode('[trx_button link="'.($link ? esc_url($link) : '#').'"]'.($link_text).'[/trx_button]').'</div>' : '')
			. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_price_block', $atts, $content);
	}
	yogastudio_require_shortcode('trx_price_block', 'yogastudio_sc_price_block');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_price_block_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_price_block_reg_shortcodes');
	function yogastudio_sc_price_block_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_price_block"] = array(
			"title" => esc_html__("Price block", "yogastudio"),
			"desc" => wp_kses( __("Insert price block with title, price and description", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Block style", "yogastudio"),
					"desc" => wp_kses( __("Select style for this price block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => 1,
					"options" => yogastudio_get_list_styles(1, 3),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", "yogastudio"),
					"desc" => wp_kses( __("Block title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Link URL", "yogastudio"),
					"desc" => wp_kses( __("URL for link from button (at bottom of the block)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"link_text" => array(
					"title" => esc_html__("Link text", "yogastudio"),
					"desc" => wp_kses( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon",  'yogastudio'),
					"desc" => wp_kses( __('Select icon from Fontello icons set (placed before/instead price)',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "icons",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
				),
				"money" => array(
					"title" => esc_html__("Money", "yogastudio"),
					"desc" => wp_kses( __("Money value (dot or comma separated)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"currency" => array(
					"title" => esc_html__("Currency", "yogastudio"),
					"desc" => wp_kses( __("Currency character", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "$",
					"type" => "text"
				),
				"period" => array(
					"title" => esc_html__("Period", "yogastudio"),
					"desc" => wp_kses( __("Period text (if need). For example: monthly, daily, etc.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "yogastudio"),
					"desc" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['schemes']
				),
				"align" => array(
					"title" => esc_html__("Alignment", "yogastudio"),
					"desc" => wp_kses( __("Align price to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['float']
				), 
				"_content_" => array(
					"title" => esc_html__("Description", "yogastudio"),
					"desc" => wp_kses( __("Description for this price block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
if ( !function_exists( 'yogastudio_sc_price_block_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_price_block_reg_shortcodes_vc');
	function yogastudio_sc_price_block_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_price_block",
			"name" => esc_html__("Price block", "yogastudio"),
			"description" => wp_kses( __("Insert price block with title, price and description", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_price_block',
			"class" => "trx_sc_single trx_sc_price_block",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Block style", "yogastudio"),
					"desc" => wp_kses( __("Select style of this price block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"std" => 1,
					"value" => array_flip(yogastudio_get_list_styles(1, 3)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "yogastudio"),
					"description" => wp_kses( __("Block title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "yogastudio"),
					"description" => wp_kses( __("URL for link from button (at bottom of the block)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_text",
					"heading" => esc_html__("Link text", "yogastudio"),
					"description" => wp_kses( __("Text (caption) for the link button (at bottom of the block). If empty - button not showed", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", "yogastudio"),
					"description" => wp_kses( __("Select icon from Fontello icons set (placed before/instead price)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				array(
					"param_name" => "money",
					"heading" => esc_html__("Money", "yogastudio"),
					"description" => wp_kses( __("Money value (dot or comma separated)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "currency",
					"heading" => esc_html__("Currency symbol", "yogastudio"),
					"description" => wp_kses( __("Currency character", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'yogastudio'),
					"class" => "",
					"value" => "$",
					"type" => "textfield"
				),
				array(
					"param_name" => "period",
					"heading" => esc_html__("Period", "yogastudio"),
					"description" => wp_kses( __("Period text (if need). For example: monthly, daily, etc.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"group" => esc_html__('Money', 'yogastudio'),
					"class" => "",
					"value" => "",
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
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Align price to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['float']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Description", "yogastudio"),
					"description" => wp_kses( __("Description for this price block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_PriceBlock extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>