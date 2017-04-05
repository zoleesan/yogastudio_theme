<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_form_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_form_theme_setup' );
	function yogastudio_sc_form_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_form_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_form_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_form id="unique_id" title="Contact Form" description="Mauris aliquam habitasse magna."]
*/

if (!function_exists('yogastudio_sc_form')) {	
	function yogastudio_sc_form($atts, $content = null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "form_custom",
			"action" => "",
			"return_url" => "",
			"return_page" => "",
			"align" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_form_".str_replace('.', '', mt_rand());
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$css .= yogastudio_get_css_dimensions_from_values($width);
	
		yogastudio_enqueue_messages();	// Load core messages
	
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_form_id'] = $id;
		$YOGASTUDIO_GLOBALS['sc_form_counter'] = 0;
	
		if ($style == 'form_custom')
			$content = do_shortcode($content);
		
		$fields = array();
		if (!empty($return_page)) 
			$return_url = get_permalink($return_page);
		if (!empty($return_url))
			$fields[] = array(
				'name' => 'return_url',
				'type' => 'hidden',
				'value' => $return_url
			);

		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '')
					. ' class="sc_form_wrap'
					. ($scheme && !yogastudio_param_is_off($scheme) && !yogastudio_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">'
			.'<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_form'
					. ' sc_form_style_'.($style) 
					. (!empty($align) && !yogastudio_param_is_off($align) ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
				. '>'
					. (!empty($subtitle) 
						? '<h6 class="sc_form_subtitle sc_item_subtitle">' . trim(yogastudio_strmacros($subtitle)) . '</h6>' 
						: '')
					. (!empty($title) 
						? '<h2 class="sc_form_title sc_item_title">' . trim(yogastudio_strmacros($title)) . '</h2>' 
						: '')
					. (!empty($description) 
						? '<div class="sc_form_descr sc_item_descr">' . trim(yogastudio_strmacros($description)) . ($style == 1 ? do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]') : '') . '</div>' 
						: '');
		
		$output .= yogastudio_show_post_layout(array(
												'layout' => $style,
												'id' => $id,
												'action' => $action,
												'content' => $content,
												'fields' => $fields,
												'show' => false
												), false);

		$output .= '</div>'
				. '</div>';
	
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_form', $atts, $content);
	}
	yogastudio_require_shortcode("trx_form", "yogastudio_sc_form");
}

if (!function_exists('yogastudio_sc_form_item')) {	
	function yogastudio_sc_form_item($atts, $content=null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "text",
			"name" => "",
			"value" => "",
			"options" => "",
			"align" => "",
			"label" => "",
			"label_position" => "top",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['sc_form_counter']++;
	
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		if (empty($id)) $id = ($YOGASTUDIO_GLOBALS['sc_form_id']).'_'.($YOGASTUDIO_GLOBALS['sc_form_counter']);
	
		$label = $type!='button' && $type!='submit' && $label ? '<label for="' . esc_attr($id) . '">' . esc_attr($label) . '</label>' : $label;
	
		// Open field container
		$output = '<div class="sc_form_item sc_form_item_'.esc_attr($type)
						.' sc_form_'.($type == 'textarea' ? 'message' : ($type == 'button' || $type == 'submit' ? 'button' : 'field'))
						.' label_'.esc_attr($label_position)
						.($class ? ' '.esc_attr($class) : '')
						.($align && $align!='none' ? ' align'.esc_attr($align) : '')
					.'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
					. '>';
		
		// Label top or left
		if ($type!='button' && $type!='submit' && ($label_position=='top' || $label_position=='left'))
			$output .= $label;

		// Field output
		if ($type == 'textarea')

			$output .= '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">' . esc_attr($value) . '</textarea>';

		else if ($type=='button' || $type=='submit')

			$output .= '<button id="' . esc_attr($id) . '">'.($label ? $label : $value).'</button>';

		else if ($type=='radio' || $type=='checkbox') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$i = 0;
					foreach ($options as $v) {
						$i++;
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<div class="sc_form_element">'
										. '<input type="'.esc_attr($type) . '"'
											. ' id="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '"'
											. ' name="' . esc_attr($name ? $name : $id) . (count($options) > 1 && $type=='checkbox' ? '[]' : '') . '"'
											. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
											. (in_array($parts[0], explode(',', $value)) ? ' checked="checked"' : '') 
										. '>'
										. '<label for="' . esc_attr($id.($i>1 ? '_'.$i : '')) . '">' . trim(chop($parts[1])) . '</label>'
									. '</div>';
					}
				}
			}

		} else if ($type=='select') {

			if (!empty($options)) {
				$options = explode('|', $options);
				if (!empty($options)) {
					$output .= '<div class="sc_form_select_container">'
						. '<select id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '">';
					foreach ($options as $v) {
						$parts = explode('=', $v);
						if (count($parts)==1) $parts[1] = $parts[0];
						$output .= '<option'
										. ' value="' . esc_attr(trim(chop($parts[0]))) . '"' 
										. (in_array($parts[0], explode(',', $value)) ? ' selected="selected"' : '') 
									. '>'
									. trim(chop($parts[1]))
									. '</option>';
					}
					$output .= '</select>'
							. '</div>';
				}
			}

		} else if ($type=='date') {
			yogastudio_enqueue_script( 'jquery-picker', yogastudio_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			yogastudio_enqueue_script( 'jquery-picker-date', yogastudio_get_file_url('/js/picker/picker.date.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_date_wrap icon-calendar-light">'
						. '<input placeholder="' . esc_attr__('Date', 'yogastudio') . '" id="' . esc_attr($id) . '" class="js__datepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';

		} else if ($type=='time') {
			yogastudio_enqueue_script( 'jquery-picker', yogastudio_get_file_url('/js/picker/picker.js'), array('jquery'), null, true );
			yogastudio_enqueue_script( 'jquery-picker-time', yogastudio_get_file_url('/js/picker/picker.time.js'), array('jquery'), null, true );
			$output .= '<div class="sc_form_time_wrap icon-clock-empty">'
						. '<input placeholder="' . esc_attr__('Time', 'yogastudio') . '" id="' . esc_attr($id) . '" class="js__timepicker" type="text" name="' . esc_attr($name ? $name : $id) . '">'
					. '</div>';
	
		} else

			$output .= '<input type="'.esc_attr($type ? $type : 'text').'" id="' . esc_attr($id) . '" name="' . esc_attr($name ? $name : $id) . '" value="' . esc_attr($value) . '">';

		// Label bottom
		if ($type!='button' && $type!='submit' && $label_position=='bottom')
			$output .= $label;
		
		// Close field container
		$output .= '</div>';
	
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_form_item', $atts, $content);
	}
	yogastudio_require_shortcode('trx_form_item', 'yogastudio_sc_form_item');
}

// AJAX Callback: Send contact form data
if ( !function_exists( 'yogastudio_sc_form_send' ) ) {
	function yogastudio_sc_form_send() {
		global $_REQUEST, $YOGASTUDIO_GLOBALS;
	
		if ( !wp_verify_nonce( $_REQUEST['nonce'], $YOGASTUDIO_GLOBALS['ajax_url'] ) )
			die();
	
		$response = array('error'=>'');
		if (!($contact_email = yogastudio_get_theme_option('contact_email')) && !($contact_email = yogastudio_get_theme_option('admin_email'))) 
			$response['error'] = esc_html__('Unknown admin email!', 'yogastudio');
		else {
			$type = yogastudio_substr($_REQUEST['type'], 0, 7);
			parse_str($_POST['data'], $post_data);

			if (in_array($type, array('form_2'))) {
				$user_name	= yogastudio_strshort($post_data['username'],	100);
				$user_email	= yogastudio_strshort($post_data['email'],	100);
				$user_subj	= yogastudio_strshort($post_data['subject'],	100);
				$user_msg	= yogastudio_strshort($post_data['message'],	yogastudio_get_theme_option('message_maxlength_contacts'));
		
				$subj = sprintf(esc_html__('Site %s - Contact form message from %s', 'yogastudio'), get_bloginfo('site_name'), $user_name);
				$msg = "\n".esc_html__('Name:', 'yogastudio')   .' '.esc_html($user_name)
					.  "\n".esc_html__('E-mail:', 'yogastudio') .' '.esc_html($user_email)
					.  "\n".esc_html__('Subject:', 'yogastudio').' '.esc_html($user_subj)
					.  "\n".esc_html__('Message:', 'yogastudio').' '.esc_html($user_msg);

			} else if (in_array($type, array('form_1'))) {
				$from_page = yogastudio_strshort($post_data['from_page'],	400);
				$user_name	= yogastudio_strshort($post_data['username'],	100);
				$user_phone	= yogastudio_strshort($post_data['phone'] ,100);
				$user_msg	= yogastudio_strshort($post_data['message'],	yogastudio_get_theme_option('message_maxlength_contacts'));
		
				$subj = sprintf(esc_html__('Site %s - Contact form message from %s', 'yogastudio'), get_bloginfo('site_name'), $user_name);
				$msg = "\n".esc_html__('Name:', 'yogastudio')   .' '.esc_html($user_name)
					.  "\n".esc_html__('Phone:', 'yogastudio') .' '.esc_html($user_phone)
					.  "\n".esc_html__('Page:', 'yogastudio') .' '.esc_html($from_page)
					.  "\n".esc_html__('Message:', 'yogastudio').' '.esc_html($user_msg);

			}else {

				$subj = sprintf(esc_html__('Site %s - Custom form data', 'yogastudio'), get_bloginfo('site_name'));
				$msg = '';
				if (is_array($post_data) && count($post_data) > 0) {
					foreach ($post_data as $k=>$v)
						$msg .= "\n{$k}: $v";
				}
			}

			$msg .= "\n\n............. " . get_bloginfo('site_name') . " (" . esc_url(home_url('/')) . ") ............";

			$mail = yogastudio_get_theme_option('mail_function');
			if (!@$mail($contact_email, $subj, apply_filters('yogastudio_filter_form_send_message', $msg))) {
				$response['error'] = esc_html__('Error send message!', 'yogastudio');
			}
		
			echo json_encode($response);
			die();
		}
	}
}

// Show additional fields in the form
if ( !function_exists( 'yogastudio_sc_form_show_fields' ) ) {
	function yogastudio_sc_form_show_fields($fields) {
		if (is_array($fields) && count($fields)>0) {
			foreach ($fields as $f) {
				if (in_array($f['type'], array('hidden', 'text'))) {
					echo '<input type="'.esc_attr($f['type']).'" name="'.esc_attr($f['name']).'" value="'.esc_attr($f['value']).'">';
				}
			}
		}
	}
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_form_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_form_reg_shortcodes');
	function yogastudio_sc_form_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$pages = yogastudio_get_list_pages(false);

		$YOGASTUDIO_GLOBALS['shortcodes']["trx_form"] = array(
			"title" => esc_html__("Form", "yogastudio"),
			"desc" => wp_kses( __("Insert form with specified style or with set of custom fields", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => true,
			"container" => false,
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
					"type" => "text"
				),
				"style" => array(
					"title" => esc_html__("Style", "yogastudio"),
					"desc" => wp_kses( __("Select style of the form (if 'style' is not equal 'Custom Form' - all tabs 'Field #' are ignored!)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => 'form_custom',
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['forms'],
					"type" => "checklist"
				), 
				"scheme" => array(
					"title" => esc_html__("Color scheme", "yogastudio"),
					"desc" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['schemes']
				),
				"action" => array(
					"title" => esc_html__("Action", "yogastudio"),
					"desc" => wp_kses( __("Contact form action (URL to handle form data). If empty - use internal action", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"return_page" => array(
					"title" => esc_html__("Page after submit", "yogastudio"),
					"desc" => wp_kses( __("Select page to redirect after form submit", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "0",
					"type" => "select",
					"options" => $pages
				),
				"return_url" => array(
					"title" => esc_html__("URL to redirect", "yogastudio"),
					"desc" => wp_kses( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"align" => array(
					"title" => esc_html__("Align", "yogastudio"),
					"desc" => wp_kses( __("Select form alignment", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
				),
				"width" => yogastudio_shortcodes_width(),
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
				"name" => "trx_form_item",
				"title" => esc_html__("Field", "yogastudio"),
				"desc" => wp_kses( __("Custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"container" => false,
				"params" => array(
					"type" => array(
						"title" => esc_html__("Type", "yogastudio"),
						"desc" => wp_kses( __("Type of the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "text",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['field_types']
					), 
					"name" => array(
						"title" => esc_html__("Name", "yogastudio"),
						"desc" => wp_kses( __("Name of the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Default value", "yogastudio"),
						"desc" => wp_kses( __("Default value of the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"options" => array(
						"title" => esc_html__("Options", "yogastudio"),
						"desc" => wp_kses( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"dependency" => array(
							'type' => array('radio', 'checkbox', 'select')
						),
						"value" => "",
						"type" => "text"
					),
					"label" => array(
						"title" => esc_html__("Label", "yogastudio"),
						"desc" => wp_kses( __("Label for the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "",
						"type" => "text"
					),
					"label_position" => array(
						"title" => esc_html__("Label position", "yogastudio"),
						"desc" => wp_kses( __("Label position relative to the field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"value" => "top",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => $YOGASTUDIO_GLOBALS['sc_params']['label_positions']
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
			)
		);
	}
}


/* Add shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_form_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_form_reg_shortcodes_vc');
	function yogastudio_sc_form_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;

		$pages = yogastudio_get_list_pages(false);
	
		vc_map( array(
			"base" => "trx_form",
			"name" => esc_html__("Form", "yogastudio"),
			"description" => wp_kses( __("Insert form with specefied style of with set of custom fields", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_form',
			"class" => "trx_sc_collection trx_sc_form",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('except' => 'trx_form'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "yogastudio"),
					"description" => wp_kses( __("Select style of the form (if 'style' is not equal 'custom' - all tabs 'Field NN' are ignored!)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"std" => "form_custom",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['forms']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "yogastudio"),
					"description" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['schemes']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "action",
					"heading" => esc_html__("Action", "yogastudio"),
					"description" => wp_kses( __("Contact form action (URL to handle form data). If empty - use internal action", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "return_page",
					"heading" => esc_html__("Page after submit", "yogastudio"),
					"description" => wp_kses( __("Select page to redirect after form submit", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => 0,
					"value" => array_flip($pages),
					"type" => "dropdown"
				),
				array(
					"param_name" => "return_url",
					"heading" => esc_html__("URL to redirect", "yogastudio"),
					"description" => wp_kses( __("or specify any URL to redirect after form submit. If both fields are empty - no navigate from current page after submission", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Select form alignment", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
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
				$YOGASTUDIO_GLOBALS['vc_params']['id'],
				$YOGASTUDIO_GLOBALS['vc_params']['class'],
				$YOGASTUDIO_GLOBALS['vc_params']['animation'],
				$YOGASTUDIO_GLOBALS['vc_params']['css'],
				yogastudio_vc_width(),
				$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
				$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
			)
		) );
		
		
		vc_map( array(
			"base" => "trx_form_item",
			"name" => esc_html__("Form item (custom field)", "yogastudio"),
			"description" => wp_kses( __("Custom field for the contact form", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"class" => "trx_sc_item trx_sc_form_item",
			'icon' => 'icon_trx_form_item',
			//"allowed_container_element" => 'vc_row',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_form,trx_column_item'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", "yogastudio"),
					"description" => wp_kses( __("Select type of the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['field_types']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "name",
					"heading" => esc_html__("Name", "yogastudio"),
					"description" => wp_kses( __("Name of the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Default value", "yogastudio"),
					"description" => wp_kses( __("Default value of the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "options",
					"heading" => esc_html__("Options", "yogastudio"),
					"description" => wp_kses( __("Field options. For example: big=My daddy|middle=My brother|small=My little sister", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('radio','checkbox','select')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label",
					"heading" => esc_html__("Label", "yogastudio"),
					"description" => wp_kses( __("Label for the custom field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "label_position",
					"heading" => esc_html__("Label position", "yogastudio"),
					"description" => wp_kses( __("Label position relative to the field", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['label_positions']),
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
			)
		) );
		
		class WPBakeryShortCode_Trx_Form extends YOGASTUDIO_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Form_Item extends YOGASTUDIO_VC_ShortCodeItem {}
	}
}
?>