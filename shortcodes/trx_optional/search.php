<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_search_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_search_theme_setup' );
	function yogastudio_sc_search_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_search_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_search id="unique_id" open="yes|no"]
*/

if (!function_exists('yogastudio_sc_search')) {	
	function yogastudio_sc_search($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"state" => "fixed",
			"scheme" => "original",
			"ajax" => "",
			"title" => esc_html__('Search', 'yogastudio'),
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
		if (empty($ajax)) $ajax = yogastudio_get_theme_option('use_ajax_search');
		// Load core messages
		yogastudio_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (yogastudio_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit icon-search" title="' . ($state=='closed' ? esc_attr__('Open search', 'yogastudio') : esc_attr__('Start search', 'yogastudio')) . '" data-title="'.esc_html__('Search', 'yogastudio').'"></button>
								<input type="text" class="search_field" value="' . esc_attr(get_search_query()) . '" name="s" />
							</form>
						</div>
						<div class="search_results widget_area' . ($scheme && !yogastudio_param_is_off($scheme) && !yogastudio_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>
				</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	yogastudio_require_shortcode('trx_search', 'yogastudio_sc_search');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_search_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_search_reg_shortcodes');
	function yogastudio_sc_search_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_search"] = array(
			"title" => esc_html__("Search", "yogastudio"),
			"desc" => wp_kses( __("Show search form", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "yogastudio"),
					"desc" => wp_kses( __("Select style to display search field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "regular",
					"options" => array(
						"regular" => esc_html__('Regular', 'yogastudio'),
						"rounded" => esc_html__('Rounded', 'yogastudio')
					),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", "yogastudio"),
					"desc" => wp_kses( __("Select search field initial state", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'yogastudio'),
						"opened" => esc_html__('Opened', 'yogastudio'),
						"closed" => esc_html__('Closed', 'yogastudio')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", "yogastudio"),
					"desc" => wp_kses( __("Title (placeholder) for the search field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => esc_html__("Search &hellip;", 'yogastudio'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", "yogastudio"),
					"desc" => wp_kses( __("Search via AJAX or reload page", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no'],
					"type" => "switch"
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
if ( !function_exists( 'yogastudio_sc_search_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_search_reg_shortcodes_vc');
	function yogastudio_sc_search_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", "yogastudio"),
			"description" => wp_kses( __("Insert search form", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "yogastudio"),
					"description" => wp_kses( __("Select style to display search field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'yogastudio') => "regular",
						esc_html__('Flat', 'yogastudio') => "flat"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", "yogastudio"),
					"description" => wp_kses( __("Select search field initial state", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'yogastudio')  => "fixed",
						esc_html__('Opened', 'yogastudio') => "opened",
						esc_html__('Closed', 'yogastudio') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "yogastudio"),
					"description" => wp_kses( __("Title (placeholder) for the search field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'yogastudio'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", "yogastudio"),
					"description" => wp_kses( __("Search via AJAX or reload page", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'yogastudio') => 'yes'),
					"type" => "checkbox"
				),
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['animation'],
				$YOGASTUDIO_GLOBALS['vc_params']['css'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>