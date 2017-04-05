<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_services_4_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_services_4_theme_setup', 1 );
	function yogastudio_template_services_4_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'services-4',
			'template' => 'services-4',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 4/', 'yogastudio'),
			'thumb_title'  => esc_html__('Small square images services 2 (crop)', 'yogastudio'),
			'w'		 => 780,
			'h'		 => 520
		));
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_services_4_output' ) ) {
	function yogastudio_template_services_4_output($post_options, $post_data) {
		$show_title = true;
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$id = get_the_ID();
		$columns = max(1, min(12, empty($parts[1]) ? (!empty($post_options['columns_count']) ? $post_options['columns_count'] : 1) : (int) $parts[1]));
		if (yogastudio_param_is_on($post_options['slider'])) {
			?><div class="swiper-slide" data-style="<?php echo esc_attr($post_options['tag_css_wh']); ?>" style="<?php echo esc_attr($post_options['tag_css_wh']); ?>"><div class="sc_services_item_wrap"><?php
		} else if ($columns > 1) {
			?><div class="column-1_<?php echo esc_attr($columns); ?> column_padding_bottom"><?php
		}
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_services_item sc_services_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!yogastudio_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
				<?php 
				if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
					?><?php
				}
				if ($post_data['post_icon'] && $post_options['tag_type']=='icons') {
					echo trim(yogastudio_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]'));
				} else {
					?>
					<div class="sc_services_item_featured post_featured">
						<?php require yogastudio_get_file_dir('templates/_parts/post-featured.php'); ?>
					</div>
					<?php
				}
				if ($show_title) {
					?><div class="sc_services_item_title columns_wrap">
					<div class="column-1_2"><h4><?php
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php
						}
						echo trim($post_data['post_title']); 
if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
					?></a><?php
				}
						?></h4></div><div class="column-1_2">
						<span class="days"><?php echo get_post_meta($id, 'days', true); ?></span>
						<span class="time"><?php echo get_post_meta($id, 'time', true); ?></span>
					</div>	
				</div><?php
			}
			if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
				?><?php
			}
			?>
		</div>
		<?php
		if (yogastudio_param_is_on($post_options['slider'])) {
			?></div></div><?php
		} else if ($columns > 1) {
			?></div><?php
		}

	}
}
?>