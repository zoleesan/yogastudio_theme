<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_form_1_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_form_1_theme_setup', 1 );
	function yogastudio_template_form_1_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'form_1',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 1', 'yogastudio')
			));
	}
}

			

// Template output
if ( !function_exists( 'yogastudio_template_form_1_output' ) ) {
	function yogastudio_template_form_1_output($post_options, $post_data) {
		global $YOGASTUDIO_GLOBALS;
		 $page_title = get_the_title();
		?>
		<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $YOGASTUDIO_GLOBALS['ajax_url']); ?>">
			<?php yogastudio_sc_form_show_fields($post_options['fields']); ?>
			<input type="hidden" name="from_page" value="<?php echo esc_attr($page_title);?>">
			<div class="sc_columns columns_wrap"><div class="sc_form_info column-1_2">
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'yogastudio'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name', 'yogastudio'); ?>"></div>
				<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_phone"><?php esc_html_e('Phone', 'yogastudio'); ?></label><input id="sc_form_phone" type="text" name="phone" placeholder="<?php esc_attr_e('Phone', 'yogastudio'); ?>"></div>
			</div><div class="sc_form_item sc_form_message label_over column-1_2"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'yogastudio'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'yogastudio'); ?>"></textarea></div></div>
			<div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send Message', 'yogastudio'); ?></button></div>
			<div class="result sc_infobox"></div>
		</form>
		<?php
	}
}
?>