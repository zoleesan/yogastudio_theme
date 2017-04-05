<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_news_announce_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_news_announce_theme_setup', 1 );
	function yogastudio_template_news_announce_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'news-announce',
			'template' => 'news-announce',
			'mode'   => 'news',
			'title'  => esc_html__('Recent News /Style Announce/', 'yogastudio')
		));

		// Add thumb sizes into list
		yogastudio_add_thumb_sizes( array( 'thumb_slug' => 'full', 'add_image_size' => false, 'w' => 1170, 'h' => 660 ) );
		yogastudio_add_thumb_sizes( array( 'thumb_slug' => 'big', 'add_image_size' => false, 'w' => 870, 'h' => 490 ) );
		yogastudio_add_thumb_sizes( array( 'thumb_slug' => 'medium', 'add_image_size' => false, 'w' => 870, 'h' => 245 ) );
		yogastudio_add_thumb_sizes( array( 'thumb_slug' => 'small', 'add_image_size' => false, 'w' => 390, 'h' => 220 ) );
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_news_announce_output' ) ) {
	function yogastudio_template_news_announce_output($post_options, $post_data) {
		$style = $post_options['layout'];
		$number = $post_options['number'];
		$count = $post_options['posts_on_page'];
		$post_format = $post_data['post_format'];
		$grid = array(
			array('full'),
			array('big', 'big'),
			array('big', 'medium', 'medium'),
			array('big', 'medium', 'small', 'small'),
			array('big', 'small', 'small', 'small', 'small'),
			array('medium', 'medium', 'small', 'small', 'small', 'small'),
			array('medium', 'small', 'small', 'small', 'small', 'small', 'small'),
			array('small', 'small', 'small', 'small', 'small', 'small', 'small', 'small')
		);
		$thumb_slug = $grid[$count-$number >= 8 ? 8 : ($count-1)%8][($number-1)%8];
		$thumb_sizes = yogastudio_get_thumb_sizes(array(
			'thumb_slug' => $thumb_slug
		));
		$post_data['post_thumb'] = yogastudio_get_resized_image_tag($post_data['post_attachment'], $thumb_sizes['w'], $post_data['post_type']=='product' && yogastudio_get_theme_option('crop_product_thumb')=='no' ? null :  $thumb_sizes['h']);
		?><article id="post-<?php echo esc_html($post_data['post_id']); ?>" 
			<?php post_class( 'post_item post_layout_'.esc_attr($style)
							.' post_format_'.esc_attr($post_format)
							.' post_size_'.esc_attr($thumb_slug)
							); ?>
			>
		
			<?php if ($post_data['post_flags']['sticky']) {	?>
				<span class="sticky_label"></span>
			<?php } ?>

			<div class="post_featured">
				<?php
				if (!empty($post_options['dedicated'])) {
					echo trim($post_options['dedicated']);
				} else if ($post_data['post_thumb']) {
					$post_data['post_video'] = $post_data['post_audio'] = $post_data['post_gallery'] = '';
					require yogastudio_get_file_dir('templates/_parts/post-featured.php');
				}
				?>
				<div class="post_info">
					<span class="post_categories"><?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_links); ?></span>
					<h5 class="post_title entry-title"><a href="<?php echo esc_url($post_data['post_link']); ?>" rel="bookmark"><?php echo trim($post_data['post_title']); ?></a></h5>
					<?php if ( in_array($post_data['post_type'], array('post', 'attachment')) ) { ?>
						<div class="post_meta">
							<span class="post_meta_author"><?php echo trim($post_data['post_author_link']); ?></span>
							<span class="post_meta_date"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo esc_html($post_data['post_date']); ?></a></span>
						</div>
					<?php } ?>
				</div>
			</div>
		</article>
		<?php
	}
}
?>