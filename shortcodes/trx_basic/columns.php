<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_columns_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_columns_theme_setup' );
	function yogastudio_sc_columns_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_columns_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_columns_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_columns id="unique_id" count="number"]
	[trx_column_item id="unique_id" span="2 - number_columns"]Et adipiscing integer, scelerisque pid, augue mus vel tincidunt porta, odio arcu vut natoque dolor ut, enim etiam vut augue. Ac augue amet quis integer ut dictumst? Elit, augue vut egestas! Tristique phasellus cursus egestas a nec a! Sociis et? Augue velit natoque, amet, augue. Vel eu diam, facilisis arcu.[/trx_column_item]
	[trx_column_item]A pulvinar ut, parturient enim porta ut sed, mus amet nunc, in. Magna eros hac montes, et velit. Odio aliquam phasellus enim platea amet. Turpis dictumst ultrices, rhoncus aenean pulvinar? Mus sed rhoncus et cras egestas, non etiam a? Montes? Ac aliquam in nec nisi amet eros! Facilisis! Scelerisque in.[/trx_column_item]
	[trx_column_item]Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim. Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna.[/trx_column_item]
	[trx_column_item]Nec purus, cras tincidunt rhoncus proin lacus porttitor rhoncus, vut enim habitasse cum magna. Duis sociis, elit odio dapibus nec, dignissim purus est magna integer eu porta sagittis ut, pid rhoncus facilisis porttitor porta, et, urna parturient mid augue a, in sit arcu augue, sit lectus, natoque montes odio, enim.[/trx_column_item]
[/trx_columns]
*/

if (!function_exists('yogastudio_sc_columns')) {	
	function yogastudio_sc_columns($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"count" => "2",
			"fluid" => "no",
			"margins" => "yes",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width, $height);
		$count = max(1, min(12, (int) $count));
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_columns_counter'] = 1;
		$YOGASTUDIO_GLOBALS['sc_columns_after_span2'] = false;
		$YOGASTUDIO_GLOBALS['sc_columns_after_span3'] = false;
		$YOGASTUDIO_GLOBALS['sc_columns_after_span4'] = false;
		$YOGASTUDIO_GLOBALS['sc_columns_count'] = $count;
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="columns_wrap sc_columns'
					. ' columns_' . (yogastudio_param_is_on($fluid) ? 'fluid' : 'nofluid') 
					. (!empty($margins) && yogastudio_param_is_off($margins) ? ' no_margins' : '') 
					. ' sc_columns_count_' . esc_attr($count)
					. (!empty($class) ? ' '.esc_attr($class) : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. '>'
					. do_shortcode($content)
				. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_columns', $atts, $content);
	}
	yogastudio_require_shortcode('trx_columns', 'yogastudio_sc_columns');
}


if (!function_exists('yogastudio_sc_column_item')) {	
	function yogastudio_sc_column_item($atts, $content=null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts( array(
			// Individual params
			"span" => "1",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_tile" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => ""
		), $atts)));
		$css .= ($align !== '' ? 'text-align:' . esc_attr($align) . ';' : '') 
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '');
		$span = max(1, min(11, (int) $span));
		if (!empty($bg_image)) {
			if ($bg_image > 0) {
				$attach = wp_get_attachment_image_src( $bg_image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$bg_image = $attach[0];
			}
		}
		global $YOGASTUDIO_GLOBALS;
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="column-'.($span > 1 ? esc_attr($span) : 1).'_'.esc_attr($YOGASTUDIO_GLOBALS['sc_columns_count']).' sc_column_item sc_column_item_'.esc_attr($YOGASTUDIO_GLOBALS['sc_columns_counter']) 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($YOGASTUDIO_GLOBALS['sc_columns_counter'] % 2 == 1 ? ' odd' : ' even') 
					. ($YOGASTUDIO_GLOBALS['sc_columns_counter'] == 1 ? ' first' : '') 
					. ($span > 1 ? ' span_'.esc_attr($span) : '') 
					. ($YOGASTUDIO_GLOBALS['sc_columns_after_span2'] ? ' after_span_2' : '') 
					. ($YOGASTUDIO_GLOBALS['sc_columns_after_span3'] ? ' after_span_3' : '') 
					. ($YOGASTUDIO_GLOBALS['sc_columns_after_span4'] ? ' after_span_4' : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
					. '>'
					. ($bg_color!=='' || $bg_image !== '' ? '<div class="sc_column_item_inner" style="'
							. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(yogastudio_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
							. '">' : '')
						. do_shortcode($content)
					. ($bg_color!=='' || $bg_image !== '' ? '</div>' : '')
					. '</div>';
		$YOGASTUDIO_GLOBALS['sc_columns_counter'] += $span;
		$YOGASTUDIO_GLOBALS['sc_columns_after_span2'] = $span==2;
		$YOGASTUDIO_GLOBALS['sc_columns_after_span3'] = $span==3;
		$YOGASTUDIO_GLOBALS['sc_columns_after_span4'] = $span==4;
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_column_item', $atts, $content);
	}
	yogastudio_require_shortcode('trx_column_item', 'yogastudio_sc_column_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_columns_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_columns_reg_shortcodes');
	function yogastudio_sc_columns_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_columns"] = array(
			"title" => esc_html__("Columns", "yogastudio"),
			"desc" => wp_kses( __("Insert up to 5 columns in your page (post)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"fluid" => array(
					"title" => esc_html__("Fluid columns", "yogastudio"),
					"desc" => wp_kses( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "no",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				), 
				"margins" => array(
					"title" => esc_html__("Margins between columns", "yogastudio"),
					"desc" => wp_kses( __("Add margins between columns", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "yes",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
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
			),
			"children" => array(
				"name" => "trx_column_item",
				"title" => esc_html__("Column", "yogastudio"),
				"desc" => wp_kses( __("Column item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"container" => true,
				"params" => array(
					"span" => array(
						"title" => esc_html__("Merge columns", "yogastudio"),
						"desc" => wp_kses( __("Count merged columns from current", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"align" => array(
						"title" => esc_html__("Alignment", "yogastudio"),
						"desc" => wp_kses( __("Alignment text in the column", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
					),
					"color" => array(
						"title" => esc_html__("Fore color", "yogastudio"),
						"desc" => wp_kses( __("Any color for objects in this column", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", "yogastudio"),
						"desc" => wp_kses( __("Any background color for this column", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"bg_image" => array(
						"title" => esc_html__("URL for background image file", "yogastudio"),
						"desc" => wp_kses( __("Select or upload image or write URL from other site for the background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"bg_tile" => array(
						"title" => esc_html__("Tile background image", "yogastudio"),
						"desc" => wp_kses( __("Do you want tile background image or image cover whole column?", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "no",
						"dependency" => array(
							'bg_image' => array('not_empty')
						),
						"type" => "switch",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
					),
					"_content_" => array(
						"title" => esc_html__("Column item content", "yogastudio"),
						"desc" => wp_kses( __("Current column item content", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"divider" => true,
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
					"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
					"animation" => $YOGASTUDIO_GLOBALS['sc_params']['animation'],
					"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
				)
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_columns_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_columns_reg_shortcodes_vc');
	function yogastudio_sc_columns_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_columns",
			"name" => esc_html__("Columns", "yogastudio"),
			"description" => wp_kses( __("Insert columns with margins", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_columns',
			"class" => "trx_sc_columns",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_column_item'),
			"params" => array(
				array(
					"param_name" => "count",
					"heading" => esc_html__("Columns count", "yogastudio"),
					"description" => wp_kses( __("Number of the columns in the container.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "2",
					"type" => "textfield"
				),
				array(
					"param_name" => "fluid",
					"heading" => esc_html__("Fluid columns", "yogastudio"),
					"description" => wp_kses( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array(esc_html__('Fluid columns', 'yogastudio') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "margins",
					"heading" => esc_html__("Margins between columns", "yogastudio"),
					"description" => wp_kses( __("Add margins between columns", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => "yes",
					"value" => array(esc_html__('Disable margins between columns', 'yogastudio') => 'no'),
					"type" => "checkbox"
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
			'default_content' => '
				[trx_column_item][/trx_column_item]
				[trx_column_item][/trx_column_item]
			',
			'js_view' => 'VcTrxColumnsView'
		) );
		
		
		vc_map( array(
			"base" => "trx_column_item",
			"name" => esc_html__("Column", "yogastudio"),
			"description" => wp_kses( __("Column item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_column_item',
			"as_child" => array('only' => 'trx_columns'),
			"as_parent" => array('except' => 'trx_columns'),
			"params" => array(
				array(
					"param_name" => "span",
					"heading" => esc_html__("Merge columns", "yogastudio"),
					"description" => wp_kses( __("Count merged columns from current", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Alignment text in the column", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", "yogastudio"),
					"description" => wp_kses( __("Any color for objects in this column", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "yogastudio"),
					"description" => wp_kses( __("Any background color for this column", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("URL for background image file", "yogastudio"),
					"description" => wp_kses( __("Select or upload image or write URL from other site for the background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", "yogastudio"),
					"description" => wp_kses( __("Do you want tile background image or image cover whole column?", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'yogastudio') => 'yes'),
					"type" => "checkbox"
				),
				/*
				array(
					"param_name" => "content",
					"heading" => esc_html__("Column's content", "yogastudio"),
					"description" => wp_kses( __("Content of the current column", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				*/
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['animation'],
				$YOGASTUDIO_GLOBALS['vc_params']['css']
			),
			'js_view' => 'VcTrxColumnItemView'
		) );
		
		class WPBakeryShortCode_Trx_Columns extends YOGASTUDIO_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Column_Item extends YOGASTUDIO_VC_ShortCodeCollection {}
	}
}
?>