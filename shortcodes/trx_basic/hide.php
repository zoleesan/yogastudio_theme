<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('yogastudio_sc_hide_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_hide_theme_setup' );
	function yogastudio_sc_hide_theme_setup() {
		add_action('yogastudio_action_shortcodes_list', 		'yogastudio_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

/*
[trx_hide selector="unique_id"]
*/

if (!function_exists('yogastudio_sc_hide')) {	
	function yogastudio_sc_hide($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		$output = $selector == '' ? '' : 
			'<script type="text/javascript">
				jQuery(document).ready(function() {
					'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
					'.($delay>0 ? '},'.($delay).');' : '').'
				});
			</script>';
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	yogastudio_require_shortcode('trx_hide', 'yogastudio_sc_hide');
}



/* Add shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'yogastudio_sc_hide_reg_shortcodes' ) ) {
	//add_action('yogastudio_action_shortcodes_list', 'yogastudio_sc_hide_reg_shortcodes');
	function yogastudio_sc_hide_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
	
		$YOGASTUDIO_GLOBALS['shortcodes']["trx_hide"] = array(
			"title" => esc_html__("Hide/Show any block", "yogastudio"),
			"desc" => wp_kses( __("Hide or Show any block with desired CSS-selector", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", "yogastudio"),
					"desc" => wp_kses( __("Any block's CSS-selector", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", "yogastudio"),
					"desc" => wp_kses( __("New state for the block: hide or show", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"value" => "yes",
					"size" => "small",
					"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no'],
					"type" => "switch"
				)
			)
		);
	}
}
?>