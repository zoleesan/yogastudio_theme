<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_services_5_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_services_5_theme_setup', 1 );
	function yogastudio_template_services_5_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'services-5',
			'template' => 'services-5',
			'mode'   => 'services',
			'need_columns' => true,
			'title'  => esc_html__('Services /Style 5/', 'yogastudio')
		));
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_services_5_output' ) ) {
	function yogastudio_template_services_5_output($post_options, $post_data) {
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
				if ($post_data['post_icon'] && $post_options['tag_type']=='icons') {
					$html = yogastudio_do_shortcode('[trx_icon icon="'.esc_attr($post_data['post_icon']).'" shape="round"]');
					if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
						?><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($html); ?></a><?php
					} else
						echo trim($html);
				} else {
					?>
					<div class="sc_services_item_featured post_featured">
						<?php require yogastudio_get_file_dir('templates/_parts/post-featured.php'); ?>
					</div>
					<?php
				}
				?>
				<div class="sc_services_item_content">
					<?php
					//echo the_date('M d, Y', '<h4 class="title_date">', '</h4>', true);
					echo '<h4 class="title_date">', get_post_meta($id, 'days', true),'</h4>';
					if ($show_title) {
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><h4 class="sc_services_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h4><?php
						} else {
							?><h4 class="sc_services_item_title"><?php echo trim($post_data['post_title']); ?></h4><?php
						}
					}
					?>

					<div class="sc_services_item_description">
						<?php
							if (!empty($post_data['post_link']) && !yogastudio_param_is_off($post_options['readmore'])) {
								?><a href="<?php echo esc_url($post_data['post_link']); ?>" class="sc_services_item_readmore"><?php echo trim($post_options['readmore']); ?><span class="icon-right"></span></a><?php
							}
						?>
					</div>
				</div>
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