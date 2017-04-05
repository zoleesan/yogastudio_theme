<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_form_2_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_form_2_theme_setup', 1 );
	function yogastudio_template_form_2_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'form_2',
			'mode'   => 'forms',
			'title'  => esc_html__('Contact Form 2', 'yogastudio')
			));
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_form_2_output' ) ) {
	function yogastudio_template_form_2_output($post_options, $post_data) {
		global $YOGASTUDIO_GLOBALS;
		$address_1 = yogastudio_get_theme_option('contact_address_1');
		$address_2 = yogastudio_get_theme_option('contact_address_2');
		$phone = yogastudio_get_theme_option('contact_phone');
		$fax = yogastudio_get_theme_option('contact_fax');
		$email = yogastudio_get_theme_option('contact_email');
		$open_hours = yogastudio_get_theme_option('contact_open_hours');
		?>
		<div class="sc_columns columns_wrap">
			<div class="sc_form_address column-1_3">
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Address', 'yogastudio'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($address_1) . (!empty($address_1) && !empty($address_2) ? ', ' : '') . $address_2; ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('We are open', 'yogastudio'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($open_hours); ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('Phone', 'yogastudio'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($phone) . (!empty($phone) && !empty($fax) ? ', ' : '') . $fax; ?></span>
				</div>
				<div class="sc_form_address_field">
					<span class="sc_form_address_label"><?php esc_html_e('E-mail', 'yogastudio'); ?></span>
					<span class="sc_form_address_data"><?php echo trim($email); ?></span>
				</div>
				<?php echo do_shortcode('[trx_socials size="tiny" shape="round"][/trx_socials]'); ?>
			</div><div class="sc_form_fields column-2_3">
				<form <?php echo !empty($post_options['id']) ? ' id="'.esc_attr($post_options['id']).'"' : ''; ?> data-formtype="<?php echo esc_attr($post_options['layout']); ?>" method="post" action="<?php echo esc_url($post_options['action'] ? $post_options['action'] : $YOGASTUDIO_GLOBALS['ajax_url']); ?>">
					<?php yogastudio_sc_form_show_fields($post_options['fields']); ?>
					<div class="sc_form_info">
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_username"><?php esc_html_e('Name', 'yogastudio'); ?></label><input id="sc_form_username" type="text" name="username" placeholder="<?php esc_attr_e('Name *', 'yogastudio'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_email"><?php esc_html_e('E-mail', 'yogastudio'); ?></label><input id="sc_form_email" type="text" name="email" placeholder="<?php esc_attr_e('E-mail *', 'yogastudio'); ?>"></div>
						<div class="sc_form_item sc_form_field label_over"><label class="required" for="sc_form_subj"><?php esc_html_e('Subject', 'yogastudio'); ?></label><input id="sc_form_subj" type="text" name="subject" placeholder="<?php esc_attr_e('Subject', 'yogastudio'); ?>"></div>
					</div>
					<div class="sc_form_item sc_form_message label_over"><label class="required" for="sc_form_message"><?php esc_html_e('Message', 'yogastudio'); ?></label><textarea id="sc_form_message" name="message" placeholder="<?php esc_attr_e('Message', 'yogastudio'); ?>"></textarea></div>
					<div class="sc_form_item sc_form_button"><button><?php esc_html_e('Send Message', 'yogastudio'); ?></button></div>
					<div class="result sc_infobox"></div>
				</form>
			</div>
		</div>
		<?php
	}
}
?>