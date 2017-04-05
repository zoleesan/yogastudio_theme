<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_form_custom_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_form_custom_theme_setup', 1 );
	function yogastudio_template_form_custom_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'form_custom',
			'mode'   => 'forms',
			'title'  => esc_html__('Custom Form', 'yogastudio')
			));
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_form_custom_output' ) ) {
	function yogastudio_template_form_custom_output($post_options, $post_data) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $YOGASTUDIO_GLOBALS['ajax_url']); ?>">
			<?php
			yogastudio_sc_form_show_fields($post_options['fields']);
			echo trim($post_options['content']);
			?>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>