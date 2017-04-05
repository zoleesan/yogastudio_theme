<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_twitter_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_twitter_theme_setup' );
	function yogastudio_sc_twitter_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_twitter_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_sc_twitter_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_twitter id="unique_id" user="username" consumer_key="" consumer_secret="" token_key="" token_secret=""]
*/

if (!function_exists('yogastudio_sc_twitter')) {	
	function yogastudio_sc_twitter($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"user" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			"count" => "3",
			"controls" => "yes",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
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
	
		$twitter_username = $user ? $user : yogastudio_get_theme_option('twitter_username');
		$twitter_consumer_key = $consumer_key ? $consumer_key : yogastudio_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = $consumer_secret ? $consumer_secret : yogastudio_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = $token_key ? $token_key : yogastudio_get_theme_option('twitter_token_key');
		$twitter_token_secret = $token_secret ? $token_secret : yogastudio_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, $count ? $count : intval(yogastudio_get_theme_option('twitter_count')));
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && yogastudio_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = yogastudio_get_scheme_color('bg');
			$rgb = yogastudio_hex2rgb($bg_color);
		}
		
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);
		$ws = yogastudio_get_css_dimensions_from_values($width);
		$hs = yogastudio_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
	
		$output = '';
	
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
			$data = yogastudio_get_twitter_data(array(
				'mode'            => 'user_timeline',
				'consumer_key'    => $twitter_consumer_key,
				'consumer_secret' => $twitter_consumer_secret,
				'token'           => $twitter_token_key,
				'secret'          => $twitter_token_secret
				)
			);
			if ($data && isset($data[0]['text'])) {
				yogastudio_enqueue_slider('swiper');
				$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || yogastudio_strlen($bg_texture)>2 || ($scheme && !yogastudio_param_is_off($scheme) && !yogastudio_param_is_inherit($scheme))
						? '<div class="sc_twitter_wrap sc_section'
								. ($scheme && !yogastudio_param_is_off($scheme) && !yogastudio_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
								. ($align && $align!='none' && !yogastudio_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							.' style="'
								. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
								. ($bg_image !== '' ? 'background-image:url('.esc_url($bg_image).');' : '')
								. '"'
							. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
							. '>'
							. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
									. ' style="' 
										. ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
										. (yogastudio_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
										. '"'
										. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
										. '>' 
						: '')
						. '<div class="sc_twitter sc_slider_swiper sc_slider_nopagination swiper-slider-container'
								. (yogastudio_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
								. (yogastudio_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
								. ($hs ? ' sc_slider_height_fixed' : '')
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && $align && $align!='none' && !yogastudio_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
							. (!empty($width) && yogastudio_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
							. (!empty($height) && yogastudio_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
							. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
							. '>'
							. '<div class="slides swiper-wrapper">';
				$cnt = 0;
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $tweet) {
						if (yogastudio_substr($tweet['text'], 0, 1)=='@') continue;
							$output .= '<div class="swiper-slide" data-style="'.esc_attr(($ws).($hs)).'" style="'.esc_attr(($ws).($hs)).'">'
										. '<div class="sc_twitter_item">'
											. '<span class="sc_twitter_icon icon-twitter"></span>'
											. '<div class="sc_twitter_content">'
												. '<a href="' . esc_url('https://twitter.com/'.($twitter_username)).'" class="sc_twitter_author" target="_blank">@' . esc_html($tweet['user']['screen_name']) . '</a> '
												. force_balance_tags(yogastudio_prepare_twitter_text($tweet))
											. '</div>'
										. '</div>'
									. '</div>';
						if (++$cnt >= $twitter_count) break;
					}
				}
				$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
					. '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || yogastudio_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
			}
		}
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_twitter', $atts, $content);
	}
	yogastudio_require_shortcode('trx_twitter', 'yogastudio_sc_twitter');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_twitter_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_twitter_reg_shortcodes');
	function yogastudio_sc_twitter_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_twitter"] = array(
			"title" => esc_html__("Twitter", "yogastudio"),
			"desc" => wp_kses( __("Insert twitter feed into post (page)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"user" => array(
					"title" => esc_html__("Twitter Username", "yogastudio"),
					"desc" => wp_kses( __("Your username in the twitter account. If empty - get it from Theme Options.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"consumer_key" => array(
					"title" => esc_html__("Consumer Key", "yogastudio"),
					"desc" => wp_kses( __("Consumer Key from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"consumer_secret" => array(
					"title" => esc_html__("Consumer Secret", "yogastudio"),
					"desc" => wp_kses( __("Consumer Secret from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"token_key" => array(
					"title" => esc_html__("Token Key", "yogastudio"),
					"desc" => wp_kses( __("Token Key from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"token_secret" => array(
					"title" => esc_html__("Token Secret", "yogastudio"),
					"desc" => wp_kses( __("Token Secret from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"count" => array(
					"title" => esc_html__("Tweets number", "yogastudio"),
					"desc" => wp_kses( __("Tweets number to show", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => 3,
					"max" => 20,
					"min" => 1,
					"type" => "spinner"
				),
				"controls" => array(
					"title" => esc_html__("Show arrows", "yogastudio"),
					"desc" => wp_kses( __("Show control buttons", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "yes",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"interval" => array(
					"title" => esc_html__("Tweets change interval", "yogastudio"),
					"desc" => wp_kses( __("Tweets change interval (in milliseconds: 1000ms = 1s)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => 7000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
				),
				"align" => array(
					"title" => esc_html__("Alignment", "yogastudio"),
					"desc" => wp_kses( __("Alignment of the tweets block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
				),
				"autoheight" => array(
					"title" => esc_html__("Autoheight", "yogastudio"),
					"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "yes",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "yogastudio"),
					"desc" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "checklist",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['schemes']
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "yogastudio"),
					"desc" => wp_kses( __("Any background color for this section", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", "yogastudio"),
					"desc" => wp_kses( __("Select or upload image or write URL from other site for the background", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
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
if ( !function_exists( 'yogastudio_sc_twitter_reg_shortcodes_vc' ) ) {
	//add_action('yogastudio_action_shortcodes_list_vc', 'yogastudio_sc_twitter_reg_shortcodes_vc');
	function yogastudio_sc_twitter_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;
	
		vc_map( array(
			"base" => "trx_twitter",
			"name" => esc_html__("Twitter", "yogastudio"),
			"description" => wp_kses( __("Insert twitter feed into post (page)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"category" => esc_html__('Content', 'yogastudio'),
			'icon' => 'icon_trx_twitter',
			"class" => "trx_sc_single trx_sc_twitter",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "user",
					"heading" => esc_html__("Twitter Username", "yogastudio"),
					"description" => wp_kses( __("Your username in the twitter account. If empty - get it from Theme Options.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_key",
					"heading" => esc_html__("Consumer Key", "yogastudio"),
					"description" => wp_kses( __("Consumer Key from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_secret",
					"heading" => esc_html__("Consumer Secret", "yogastudio"),
					"description" => wp_kses( __("Consumer Secret from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_key",
					"heading" => esc_html__("Token Key", "yogastudio"),
					"description" => wp_kses( __("Token Key from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_secret",
					"heading" => esc_html__("Token Secret", "yogastudio"),
					"description" => wp_kses( __("Token Secret from the twitter account", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Tweets number", "yogastudio"),
					"description" => wp_kses( __("Number tweets to show", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"divider" => true,
					"value" => 3,
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Show arrows", "yogastudio"),
					"description" => wp_kses( __("Show control buttons", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['yes_no']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Tweets change interval", "yogastudio"),
					"description" => wp_kses( __("Tweets change interval (in milliseconds: 1000ms = 1s)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "yogastudio"),
					"description" => wp_kses( __("Alignment of the tweets block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", "yogastudio"),
					"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
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
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "yogastudio"),
					"description" => wp_kses( __("Any background color for this section", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", "yogastudio"),
					"description" => wp_kses( __("Select background image from library for this section", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", "yogastudio"),
					"description" => wp_kses( __("Overlay color opacity (from 0.0 to 1.0)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'yogastudio'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", "yogastudio"),
					"description" => wp_kses( __("Texture style from 1 to 11. Empty or 0 - without texture.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"group" => esc_html__('Colors and Images', 'yogastudio'),
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
			),
		) );
		
		class WPBakeryShortCode_Trx_Twitter extends YOGASTUDIO_VC_ShortCodeSingle {}
	}
}
?>