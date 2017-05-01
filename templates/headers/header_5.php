 <?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_header_5_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_header_5_theme_setup', 1 );
	function yogastudio_template_header_5_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'header_5',
			'mode'   => 'header',
			'title'  => esc_html__('Header 5', 'yogastudio'),
			'icon'   => yogastudio_get_file_url('templates/headers/images/5.jpg')
			));
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_header_5_output' ) ) {
	function yogastudio_template_header_5_output($post_options, $post_data) {
		global $YOGASTUDIO_GLOBALS;

		// WP custom header
		$header_css = '';
		if ($post_options['position'] != 'over') {
			$header_image = get_header_image();
			$header_css = $header_image!='' 
			? ' style="background: url('.esc_url($header_image).') repeat center top"' 
			: '';
		}
		?>

		<div class="top_panel_fixed_wrap"></div>

		<header class="top_panel_wrap top_panel_style_5 scheme_<?php echo esc_attr($post_options['scheme']); ?>">
			<div class="top_panel_wrap_inner top_panel_inner_style_5 top_panel_position_<?php echo esc_attr(yogastudio_get_custom_option('top_panel_position')); ?>">
				<div class="top_panel_middle" <?php echo trim($header_css); ?>>
					<div class="top_panel_top">
						<div class="content_wrap">
							<div class="columns_wrap contact_logo_wrap">
								<div class="column-1_3">
									<?php
									if (($contact_info=trim(yogastudio_get_custom_option('contact_info')))!='') {
										?>
										<div class="top_panel_top_contact_info icon-location"><span><?php echo force_balance_tags($contact_info); ?></span></div>
										<?php
									}
									?>
								</div><div class="column-1_4">
								<div class="contact_logo">
									<?php yogastudio_show_logo(true, true); ?>
								</div>
							</div><div class="column-1_3">
							<?php
							if (($contact_phone=trim(yogastudio_get_custom_option('contact_phone')))!='') {
								?>
								<div class="top_panel_top_contact_phone icon-phone"><span><?php echo force_balance_tags($contact_phone); ?></span></div>
								<?php
							}
							?>
							<?php
							if (($open_hours=trim(yogastudio_get_custom_option('contact_open_hours')))!='') {
								?>
								<div class="top_panel_top_open_hours icon-clock"><span><?php echo force_balance_tags($open_hours); ?></span></div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="top_panel_middle">
				<div class="content_wrap">
					<div class="menu_main_wrap clearfix">
						<a href="#" class="menu_main_responsive_button icon-menu"></a>
						<nav class="menu_main_nav_area">
							<?php
							if (empty($YOGASTUDIO_GLOBALS['menu_main'])) $YOGASTUDIO_GLOBALS['menu_main'] = yogastudio_get_nav_menu('menu_main');
							if (empty($YOGASTUDIO_GLOBALS['menu_main'])) $YOGASTUDIO_GLOBALS['menu_main'] = yogastudio_get_nav_menu();
							echo trim($YOGASTUDIO_GLOBALS['menu_main']);
							?>
						</nav>
					</div>
					<div class="social_icon search_panel">
						<?php 
						if (yogastudio_get_custom_option('show_socials')=='yes') {
							?>
							<div class="top_panel_top_socials">
								<?php
							$arr_soc = yogastudio_get_custom_option('social_icons');
							$first_ico = (!empty($arr_soc[key($arr_soc)]['icon']) ? esc_attr(substr($arr_soc[key($arr_soc)]['icon'], 5)) : '');
							$first_ico_url = (!empty($arr_soc[key($arr_soc)]['url']) ? esc_url($arr_soc[key($arr_soc)]['url']) : '');
							$second_ico = (!empty($arr_soc[key($arr_soc)+1]['icon']) ? esc_attr(substr($arr_soc[key($arr_soc)+1]['icon'], 5)) : '');
							$second_ico_url = (!empty($arr_soc[key($arr_soc)+1]['url']) ? esc_url($arr_soc[key($arr_soc)+1]['url']) : '');
								echo (!empty($first_ico) || !empty($second_ico) ? trim(yogastudio_sc_socials(array('size'=>'tiny', 'socials'=> (!empty($first_ico) ? ($first_ico) . '=' . ($first_ico_url) : '') . (!empty($second_ico) ? '|'. ($second_ico) . '=' . ($second_ico_url) : '')))) : ''); 
								?>
							</div>
							<?php
						}
						if (yogastudio_get_custom_option('show_search')=='yes') 
							echo trim(yogastudio_sc_search(array('class'=>"top_panel_icon", 'state'=>"closed")));
						?>
					</div>
					<div class="language_switcher_left_menu">
						<?php do_action('wpml_add_language_selector'); ?>
					</div>
				</div>
			</div>
		</div>

	</div>
</header>

<?php
}
}
?>