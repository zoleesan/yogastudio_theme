<?php
/**
 * The template for displaying the footer.
 */


				yogastudio_close_wrapper();	// <!-- </.content> -->

				// Show main sidebar
				get_sidebar();

				if (yogastudio_get_custom_option('body_style')!='fullscreen') yogastudio_close_wrapper();	// <!-- </.content_wrap> -->
				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			// Footer Testimonials stream
			if (yogastudio_get_custom_option('show_testimonials_in_footer')=='yes') { 
				$count = max(1, yogastudio_get_custom_option('testimonials_count'));
				$data = yogastudio_sc_testimonials(array('count'=>$count));
				if ($data) {
					?>
					<footer class="testimonials_wrap sc_section scheme_<?php echo esc_attr(yogastudio_get_custom_option('testimonials_scheme')); ?>">
						<div class="testimonials_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo trim($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}
			
			


			// Footer Twitter stream
			if (yogastudio_get_custom_option('show_twitter_in_footer')=='yes') { 
				$count = max(1, yogastudio_get_custom_option('twitter_count'));
				$data = yogastudio_sc_twitter(array('count'=>$count));
				if ($data) {
					?>
					<footer class="twitter_wrap sc_section scheme_<?php echo esc_attr(yogastudio_get_custom_option('twitter_scheme')); ?>">
						<div class="twitter_wrap_inner sc_section_inner sc_section_overlay">
							<div class="content_wrap"><?php echo trim($data); ?></div>
						</div>
					</footer>
					<?php
				}
			}

			// Footer contacts
			if (yogastudio_get_custom_option('show_contacts_in_footer')=='yes') { 
				$address_1 = yogastudio_get_theme_option('contact_address_1');
				$phone = yogastudio_get_theme_option('contact_phone');
				$email = yogastudio_get_theme_option('contact_email');
				if (!empty($address_1) || !empty($address_2) || !empty($phone) || !empty($fax)) {
					?>
					<footer class="contacts_wrap scheme_<?php echo esc_attr(yogastudio_get_custom_option('contacts_scheme')); ?>">
						<div class="contacts_wrap_inner">
							<div class="content_wrap">
								<div class="contacts_address columns_wrap">
									<div class="column-1_3"><address class="address_address">
										<span class="icon icon-home"></span>
										<div class="contact_title"><?php echo esc_html__('Address:', 'yogastudio') ?></div>
										<span class="contact_item address"><?php if (!empty($address_1)) echo esc_html($address_1); ?></span>
									</address></div><div class="column-1_3">
									<address class="address_phone">
										<span class="icon icon-phone"></span>
										<div class="contact_title"><?php echo esc_html__('Phone:', 'yogastudio') ?></div>
										<span class="contact_item phone"><?php if (!empty($phone)) echo esc_html($phone); ?></span>
										</address></div><div class="column-1_3">
									<address class="address_email">
										<span class="icon icon-mail"></span>
										<div class="contact_title"><?php echo esc_html__('Email:', 'yogastudio') ?></div>
										<span class="contact_item mail"><?php if (!empty($email)) echo esc_html($email); ?></span>
									</address></div>
								</div>
							</div>	<!-- /.content_wrap -->
						</div>	<!-- /.contacts_wrap_inner -->
					</footer>	<!-- /.contacts_wrap -->
					<?php
				}
			}
			

			// Google map
			if ( yogastudio_get_custom_option('show_googlemap')=='yes' ) { 
				$map_address = yogastudio_get_custom_option('googlemap_address');
				$map_latlng  = yogastudio_get_custom_option('googlemap_latlng');
				$map_zoom    = yogastudio_get_custom_option('googlemap_zoom');
				$map_style   = yogastudio_get_custom_option('googlemap_style');
				$map_height  = yogastudio_get_custom_option('googlemap_height');
				if (!empty($map_address) || !empty($map_latlng)) {
					$args = array();
					if (!empty($map_style))		$args['style'] = esc_attr($map_style);
					if (!empty($map_zoom))		$args['zoom'] = esc_attr($map_zoom);
					if (!empty($map_height))	$args['height'] = esc_attr($map_height);
					echo trim(yogastudio_sc_googlemap($args));
				}
			}

			// Footer sidebar
			$footer_show  = yogastudio_get_custom_option('show_sidebar_footer');
			$sidebar_name = yogastudio_get_custom_option('sidebar_footer');
			if (!yogastudio_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) { 
				yogastudio_storage_set('current_sidebar', 'footer');
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(yogastudio_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
							if ( !dynamic_sidebar($sidebar_name) ) {
								// Put here html if user no set widgets in sidebar
							}
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							echo trim(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>	<!-- /.content_wrap -->
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
			<?php
			}

			// Copyright area
			$copyright_style = yogastudio_get_custom_option('show_copyright_in_footer');
			if (!yogastudio_param_is_off($copyright_style)) {
			?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(yogastudio_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
						<?php yogastudio_show_logo(false, true); ?>
						<div class="copyright_text"><?php echo force_balance_tags(yogastudio_get_theme_option('footer_copyright')); ?></div>
							<?php
							if ($copyright_style == 'menu') {
								if (empty($YOGASTUDIO_GLOBALS['menu_footer']))	$YOGASTUDIO_GLOBALS['menu_footer'] = yogastudio_get_nav_menu('menu_footer');
								if (!empty($YOGASTUDIO_GLOBALS['menu_footer']))	echo trim($YOGASTUDIO_GLOBALS['menu_footer']);
							} else if ($copyright_style == 'socials') {
								echo trim(yogastudio_sc_socials(array('size'=>"tiny")));
							}
							?>
						</div>
					</div>
				</div>
			<?php } ?>
			
		</div>	<!-- /.page_wrap -->

	</div>		<!-- /.body_wrap -->
	
	<?php if ( !yogastudio_param_is_off(yogastudio_get_custom_option('show_sidebar_outer')) ) { ?>
	</div>	<!-- /.outer_wrap -->
	<?php } ?>

<?php
// Post/Page views counter
require yogastudio_get_file_dir('templates/_parts/views-counter.php');

// Front customizer
if (yogastudio_get_custom_option('show_theme_customizer')=='yes') {
	require_once yogastudio_get_file_dir('core/core.customizer/front.customizer.php');
}
?>

<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'yogastudio'); ?>"></a>

<div class="custom_html_section">
<?php echo force_balance_tags(yogastudio_get_custom_option('custom_code')); ?>
</div>

<?php echo force_balance_tags(yogastudio_get_custom_option('gtm_code2')); ?>

<?php wp_footer(); ?>

</body>
</html>