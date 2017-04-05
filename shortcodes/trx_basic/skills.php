<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_skills_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_skills_theme_setup' );
	function yogastudio_sc_skills_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_skills_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_skills_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_skills id="unique_id" type="bar|pie|arc|counter" dir="horizontal|vertical" layout="rows|columns" count="" max_value="100" align="left|right"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
	[trx_skills_item title="Scelerisque pid" value="50%"]
[/trx_skills]
*/

if (!function_exists('yogastudio_sc_skills')) {	
	function yogastudio_sc_skills($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"arc_caption" => esc_html__("Skills", "yogastudio"),
			"pie_compact" => "on",
			"pie_cutout" => 0,
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'yogastudio'),
			"link" => '',
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
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_skills_counter'] = 0;
		$YOGASTUDIO_GLOBALS['sc_skills_columns'] = 0;
		$YOGASTUDIO_GLOBALS['sc_skills_height']  = 0;
		$YOGASTUDIO_GLOBALS['sc_skills_type']    = $type;
		$YOGASTUDIO_GLOBALS['sc_skills_pie_compact'] = yogastudio_param_is_on($pie_compact) ? 'on' : 'off';
		$YOGASTUDIO_GLOBALS['sc_skills_pie_cutout']  = max(0, min(99, $pie_cutout));
		$YOGASTUDIO_GLOBALS['sc_skills_color']   = $color;
		$YOGASTUDIO_GLOBALS['sc_skills_legend']  = '';
		$YOGASTUDIO_GLOBALS['sc_skills_data']    = '';

		yogastudio_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') $YOGASTUDIO_GLOBALS['sc_skills_columns'] = $columns;
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			$YOGASTUDIO_GLOBALS['sc_skills_style'] = $style;
		}
		$YOGASTUDIO_GLOBALS['sc_skills_max'] = $max_value;
		$YOGASTUDIO_GLOBALS['sc_skills_dir'] = $dir;
		$YOGASTUDIO_GLOBALS['sc_skills_height'] = yogastudio_prepare_css_value($height);
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width);
		if (!empty($YOGASTUDIO_GLOBALS['sc_skills_height']) && ($YOGASTUDIO_GLOBALS['sc_skills_type'] == 'arc' || ($YOGASTUDIO_GLOBALS['sc_skills_type'] == 'pie' && yogastudio_param_is_on($YOGASTUDIO_GLOBALS['sc_skills_pie_compact']))))
			$css .= 'height: '.$YOGASTUDIO_GLOBALS['sc_skills_height'];
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"' 
					. ' class="sc_skills sc_skills_' . esc_attr($type) 
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '') 
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_pie_compact']) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title">' . esc_html($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc' 
						? ('<div class="sc_skills_legend">'.($YOGASTUDIO_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . ($YOGASTUDIO_GLOBALS['sc_skills_data']) . '</div>'
						  )
						: '')
					. ($type=='pie' && yogastudio_param_is_on($YOGASTUDIO_GLOBALS['sc_skills_pie_compact'])
						? ('<div class="sc_skills_legend">'.($YOGASTUDIO_GLOBALS['sc_skills_legend']).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie" class="sc_skills_pie_canvas"></canvas>'
								. '<div class="sc_skills_data" style="display:none;">' . ($YOGASTUDIO_GLOBALS['sc_skills_data']) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	yogastudio_require_shortcode('trx_skills', 'yogastudio_sc_skills');
}


if (!function_exists('yogastudio_sc_skills_item')) {	
	function yogastudio_sc_skills_item($atts, $content=null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));

		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_skills_bg_color'] = $bg_color;
		$YOGASTUDIO_GLOBALS['sc_skills_border_color'] = $border_color;
		$YOGASTUDIO_GLOBALS['sc_skills_counter']++;
		$ed = yogastudio_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if ($YOGASTUDIO_GLOBALS['sc_skills_max'] < $value) $YOGASTUDIO_GLOBALS['sc_skills_max'] = $value;
		$percent = round($value / $YOGASTUDIO_GLOBALS['sc_skills_max'] * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round($YOGASTUDIO_GLOBALS['sc_skills_max']/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$old_color = $color;
		if (empty($color)) $color = $YOGASTUDIO_GLOBALS['sc_skills_color'];
		if (empty($color)) $color = yogastudio_get_scheme_color('text_dark', $color);
		if (empty($bg_color)) $bg_color = $YOGASTUDIO_GLOBALS['sc_skills_bg_color'];
		if (empty($bg_color)) $bg_color = yogastudio_get_scheme_color('text_dark', $bg_color);
		if (empty($border_color)) $border_color = $YOGASTUDIO_GLOBALS['sc_skills_border_color'];
		if (empty($border_color)) $border_color = yogastudio_get_scheme_color('bd_color', $border_color);;
		if (empty($style)) $style = $YOGASTUDIO_GLOBALS['sc_skills_style'];
		$title_block = '<div class="sc_skills_info"><div class="sc_skills_label" '. (!empty($color) ? ' style="color:' .esc_attr($color).';"' : '').'>' . ($title) . '</div></div>';
		$style = max(1, min(4, $style));
		$output = '';
		if ($YOGASTUDIO_GLOBALS['sc_skills_type'] == 'arc' || ($YOGASTUDIO_GLOBALS['sc_skills_type'] == 'pie' && yogastudio_param_is_on($YOGASTUDIO_GLOBALS['sc_skills_pie_compact']))) {
			if ($YOGASTUDIO_GLOBALS['sc_skills_type'] == 'arc' && empty($old_color)) {
				$rgb = yogastudio_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*($YOGASTUDIO_GLOBALS['sc_skills_counter']-1)).')';
			}
			$YOGASTUDIO_GLOBALS['sc_skills_legend'] .= '<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($bg_color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) . '</span></div>';
			$YOGASTUDIO_GLOBALS['sc_skills_data'] .= '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_type']).'"'
				. ($YOGASTUDIO_GLOBALS['sc_skills_type']=='pie'
					? ( ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'"'
						)
					: '')
				. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>';
		} else {
			$output .= ($YOGASTUDIO_GLOBALS['sc_skills_columns'] > 0 ? '<div class="sc_skills_column column-1_'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_columns']).'">' : '')
					. ($YOGASTUDIO_GLOBALS['sc_skills_type']=='bar' && $YOGASTUDIO_GLOBALS['sc_skills_dir']=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '') 
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($YOGASTUDIO_GLOBALS['sc_skills_counter'] % 2 == 1 ? ' odd' : ' even') 
							. ($YOGASTUDIO_GLOBALS['sc_skills_counter'] == 1 ? ' first' : '') 
							. '"'
						. ($YOGASTUDIO_GLOBALS['sc_skills_height'] !='' || $css 
							? ' style="' 
								. ($YOGASTUDIO_GLOBALS['sc_skills_height'] !='' ? 'height: '.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_height']).';' : '') 
								. ($css) 
								. '"' 
							: '')
					. '>'
					. (!empty($icon) ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : '');
			if (in_array($YOGASTUDIO_GLOBALS['sc_skills_type'], array('bar', 'counter'))) {
				$output .= '<div class="sc_skills_count"' . ($YOGASTUDIO_GLOBALS['sc_skills_type']=='bar' && $color ? ' style="background-color:' . esc_attr($bg_color) . '; color:' . esc_attr($color) . '; border-color:' . esc_attr($border_color) . '"' : '') . '>'
							. '</div>'
							. '<div class="sc_skills_total"'
								. (!empty($color) ? ' style="color:' .esc_attr($color).';"' : '')
								. ' data-start="'.esc_attr($start).'"'
								. ' data-stop="'.esc_attr($stop).'"'
								. ' data-step="'.esc_attr($step).'"'
								. ' data-max="'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_max']).'"'
								. ' data-speed="'.esc_attr($speed).'"'
								. ' data-duration="'.esc_attr($animation).'"'
								. ' data-ed="'.esc_attr($ed).'">'
								. ($start) . ($ed)
							.'</div>';
			} else if ($YOGASTUDIO_GLOBALS['sc_skills_type']=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'"></canvas>'
					. '<div class="sc_skills_total"'
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_max']).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($bg_color).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr($YOGASTUDIO_GLOBALS['sc_skills_pie_cutout']).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			}
			$output .= 
					  ($YOGASTUDIO_GLOBALS['sc_skills_type']=='counter' ? $title_block : '')
					. '</div>'
					. ($YOGASTUDIO_GLOBALS['sc_skills_type']=='bar' && $YOGASTUDIO_GLOBALS['sc_skills_dir']=='vertical' || $YOGASTUDIO_GLOBALS['sc_skills_type'] == 'pie' ? $title_block : '')
					. ($YOGASTUDIO_GLOBALS['sc_skills_columns'] > 0 ? '</div>' : '');
		}
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	yogastudio_require_shortcode('trx_skills_item', 'yogastudio_sc_skills_item');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_skills_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_skills_reg_shortcodes');
	function yogastudio_sc_skills_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_skills"] = array(
			"title" => esc_html__("Skills", "yogastudio"),
			"desc" => wp_kses( __("Insert skills diagramm in your page (post)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"max_value" => array(
					"title" => esc_html__("Max value", "yogastudio"),
					"desc" => wp_kses( __("Max value for skills items", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => 100,
					"min" => 1,
					"type" => "spinner"
				),
				"type" => array(
					"title" => esc_html__("Skills type", "yogastudio"),
					"desc" => wp_kses( __("Select type of skills block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "bar",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'bar' => esc_html__('Bar', 'yogastudio'),
						'pie' => esc_html__('Pie chart', 'yogastudio'),
						'counter' => esc_html__('Counter', 'yogastudio'),
						'arc' => esc_html__('Arc', 'yogastudio')
					)
				), 
				"layout" => array(
					"title" => esc_html__("Skills layout", "yogastudio"),
					"desc" => wp_kses( __("Select layout of skills block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "rows",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'rows' => esc_html__('Rows', 'yogastudio'),
						'columns' => esc_html__('Columns', 'yogastudio')
					)
				),
				"dir" => array(
					"title" => esc_html__("Direction", "yogastudio"),
					"desc" => wp_kses( __("Select direction of skills block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "horizontal",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['dir']
				), 
				"style" => array(
					"title" => esc_html__("Counters style", "yogastudio"),
					"desc" => wp_kses( __("Select style of skills items (only for type=counter)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('counter')
					),
					"value" => 1,
					"options" => yogastudio_get_list_styles(1, 4),
					"type" => "checklist"
				), 
				// "columns" - autodetect, not set manual
				"color" => array(
					"title" => esc_html__("Skills items color", "yogastudio"),
					"desc" => wp_kses( __("Color for all skills items", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "yogastudio"),
					"desc" => wp_kses( __("Background color for all skills items (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"border_color" => array(
					"title" => esc_html__("Border color", "yogastudio"),
					"desc" => wp_kses( __("Border color for all skills items (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Align skills block", "yogastudio"),
					"desc" => wp_kses( __("Align skills block to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['float']
				), 
				"arc_caption" => array(
					"title" => esc_html__("Arc Caption", "yogastudio"),
					"desc" => wp_kses( __("Arc caption - text in the center of the diagram", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('arc')
					),
					"value" => "",
					"type" => "text"
				),
				"pie_compact" => array(
					"title" => esc_html__("Pie compact", "yogastudio"),
					"desc" => wp_kses( __("Show all skills in one diagram or as separate diagrams", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"pie_cutout" => array(
					"title" => esc_html__("Pie cutout", "yogastudio"),
					"desc" => wp_kses( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => 0,
					"min" => 0,
					"max" => 99,
					"type" => "spinner"
				),
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
				"link" => array(
					"title" => esc_html__("Button URL", "yogastudio"),
					"desc" => wp_kses( __("Link URL for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", "yogastudio"),
					"desc" => wp_kses( __("Caption for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
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
			),
			"children" => array(
				"name" => "trx_skills_item",
				"title" => esc_html__("Skill", "yogastudio"),
				"desc" => wp_kses( __("Skills item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"container" => false,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Title", "yogastudio"),
						"desc" => wp_kses( __("Current skills item title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Value", "yogastudio"),
						"desc" => wp_kses( __("Current skills level", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => 50,
						"min" => 0,
						"step" => 1,
						"type" => "spinner"
					),
					"color" => array(
						"title" => esc_html__("Color", "yogastudio"),
						"desc" => wp_kses( __("Current skills item color", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", "yogastudio"),
						"desc" => wp_kses( __("Current skills item background color (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"border_color" => array(
						"title" => esc_html__("Border color", "yogastudio"),
						"desc" => wp_kses( __("Current skills item border color (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "color"
					),
					"style" => array(
						"title" => esc_html__("Counter style", "yogastudio"),
						"desc" => wp_kses( __("Select style for the current skills item (only for type=counter)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => 1,
						"options" => yogastudio_get_list_styles(1, 4),
						"type" => "checklist"
					), 
					"icon" => array(
						"title" => esc_html__("Counter icon",  'yogastudio'),
						"desc" => wp_kses( __('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "icons",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
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
if ( !function_exists( 'yogastudio_sc_skills_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_skills_reg_shortcodes_vc');
	function yogastudio_sc_skills_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_skills",
			"name" => esc_html__("Skills", "yogastudio"),
			"description" => wp_kses( __("Insert skills diagramm", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_skills',
			"class" => "trx_sc_collection trx_sc_skills",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_skills_item'),
			"params" => array(
				array(
					"param_name" => "max_value",
					"heading" => esc_html__("Max value", "yogastudio"),
					"description" => wp_kses( __("Max value for skills items", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "100",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Skills type", "yogastudio"),
					"description" => wp_kses( __("Select type of skills block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Bar', 'yogastudio') => 'bar',
						esc_html__('Pie chart', 'yogastudio') => 'pie',
						esc_html__('Counter', 'yogastudio') => 'counter',
						esc_html__('Arc', 'yogastudio') => 'arc'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "layout",
					"heading" => esc_html__("Skills layout", "yogastudio"),
					"description" => wp_kses( __("Select layout of skills block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter','bar','pie')
					),
					"class" => "",
					"value" => array(
						esc_html__('Rows', 'yogastudio') => 'rows',
						esc_html__('Columns', 'yogastudio') => 'columns'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", "yogastudio"),
					"description" => wp_kses( __("Select direction of skills block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['dir']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counters style", "yogastudio"),
					"description" => wp_kses( __("Select style of skills items (only for type=counter)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(yogastudio_get_list_styles(1, 4)),
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns count", "yogastudio"),
					"description" => wp_kses( __("Skills columns count (required)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "yogastudio"),
					"description" => wp_kses( __("Color for all skills items", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "yogastudio"),
					"description" => wp_kses( __("Background color for all skills items (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", "yogastudio"),
					"description" => wp_kses( __("Border color for all skills items (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Align skills block to left or right side", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['float']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "arc_caption",
					"heading" => esc_html__("Arc caption", "yogastudio"),
					"description" => wp_kses( __("Arc caption - text in the center of the diagram", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('arc')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pie_compact",
					"heading" => esc_html__("Pie compact", "yogastudio"),
					"description" => wp_kses( __("Show all skills in one diagram or as separate diagrams", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => array(esc_html__('Show separate skills', 'yogastudio') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pie_cutout",
					"heading" => esc_html__("Pie cutout", "yogastudio"),
					"description" => wp_kses( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
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
		
		
		vc_map( array(
			"base" => "trx_skills_item",
			"name" => esc_html__("Skill", "yogastudio"),
			"description" => wp_kses( __("Skills item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_skills_item',
			"class" => "trx_sc_single trx_sc_skills_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_skills'),
			"as_parent" => array('except' => 'trx_skills'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "yogastudio"),
					"description" => wp_kses( __("Title for the current skills item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", "yogastudio"),
					"description" => wp_kses( __("Value for the current skills item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "yogastudio"),
					"description" => wp_kses( __("Color for current skills item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "yogastudio"),
					"description" => wp_kses( __("Background color for current skills item (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", "yogastudio"),
					"description" => wp_kses( __("Border color for current skills item (only for type=pie)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counter style", "yogastudio"),
					"description" => wp_kses( __("Select style for the current skills item (only for type=counter)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(yogastudio_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Counter icon", "yogastudio"),
					"description" => wp_kses( __("Select icon from Fontello icons set, placed before counter (only for type=counter)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
					"type" => "dropdown"
				),
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['css']
			)
		) );
		
		class WPBakeryShortCode_Trx_Skills extends YOGASTUDIO_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Skills_Item extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>