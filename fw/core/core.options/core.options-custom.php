<?php
/**
 * YogaStudio Framework: Theme options custom fields
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_options_custom_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_options_custom_theme_setup' );
	function yogastudio_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'yogastudio_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'yogastudio_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'yogastudio_options_custom_load_scripts');
	function yogastudio_options_custom_load_scripts() {
		yogastudio_enqueue_script( 'yogastudio-options-custom-script',	yogastudio_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
function yogastudio_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		case 'reviews':
			$output .= '<div class="reviews_block">' . trim(yogastudio_reviews_get_markup($field, $value, true)) . '</div>';
			break;

		case 'mediamanager':
			wp_enqueue_media( );
			$output .= '<a id="'.esc_attr($id).'" class="button mediamanager"
				data-param="' . esc_attr($id) . '"
				data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'yogastudio') : esc_html__( 'Choose Image', 'yogastudio')).'"
				data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'yogastudio') : esc_html__( 'Choose Image', 'yogastudio')).'"
				data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
				data-linked-field="'.esc_attr($field['media_field_id']).'"
				onclick="yogastudio_show_media_manager(this); return false;"
				>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'yogastudio') : esc_html__( 'Choose Image', 'yogastudio')) . '</a>';
			break;
	}
	return apply_filters('yogastudio_filter_show_custom_field', $output, $id, $field, $value);
}
?>