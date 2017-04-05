<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_attachment_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_attachment_theme_setup', 1 );
	function yogastudio_template_attachment_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'attachment',
			'mode'   => 'internal',
			'title'  => esc_html__('Attachment page layout', 'yogastudio'),
			'thumb_title'  => esc_html__('Fullwidth image', 'yogastudio'),
			'w'		 => 1170,
			'h'		 => null,
			'h_crop' => 660
		));
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_attachment_output' ) ) {
	function yogastudio_template_attachment_output($post_options, $post_data) {
		$post_data['post_views']++;
		$title_tag = yogastudio_get_custom_option('show_page_title')=='yes' ? 'h3' : 'h1';
		?>
		<article <?php post_class('post_item post_item_attachment template_attachment'); ?>>
		
			<<?php echo esc_html($title_tag); ?> class="post_title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php echo !empty($post_data['post_excerpt']) ? strip_tags($post_data['post_excerpt']) : $post_data['post_title']; ?></<?php echo esc_html($title_tag); ?>>

			<div class="post_featured">
				<div class="post_thumb post_nav" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<?php
					echo trim($post_data['post_thumb']);
					$post = get_post();
					$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
					if (is_array($attachments) && count($attachments) > 0) {
						foreach ($attachments as $k => $attachment) {
							if ( $attachment->ID == $post->ID )
								break;
						}
					}
					if ( isset( $attachments[ $k-1 ] ) ) {
						$link = get_permalink( $attachments[ $k-1 ]->ID ).'#top_of_page';
						$desc = yogastudio_strshort(!empty($attachments[ $k-1 ]->post_excerpt) ? $attachments[ $k-1 ]->post_excerpt : $attachments[ $k-1 ]->post_title, 30);
						?>
						<a class="post_nav_item post_nav_prev" href="<?php echo esc_url($link); ?>">
							<span class="post_nav_info">
								<span class="post_nav_info_title"><?php esc_html_e('Previous item', 'yogastudio'); ?></span>
								<span class="post_nav_info_description"><?php echo trim($desc); ?></span>
							</span>
						</a>
						<?php
					}
					if ( isset( $attachments[ $k+1 ] ) ) {
						$link = get_permalink( $attachments[ $k+1 ]->ID ).'#top_of_page';
						$desc = yogastudio_strshort(!empty($attachments[ $k+1 ]->post_excerpt) ? $attachments[ $k+1 ]->post_excerpt : $attachments[ $k+1 ]->post_title, 30);
						?>
						<a class="post_nav_item post_nav_next" href="<?php echo esc_url($link); ?>">
							<span class="post_nav_info">
								<span class="post_nav_info_title"><?php esc_html_e('Next item', 'yogastudio'); ?></span>
								<span class="post_nav_info_description"><?php echo trim($desc); ?></span>
							</span>
						</a>
						<?php
					}
					?>
				</div>
			</div>
		
			<?php
			if (!$post_data['post_protected'] && yogastudio_get_custom_option('show_post_info')=='yes') {
				require yogastudio_get_file_dir('templates/_parts/post-info.php'); 
			}
			?>
		
			<div class="post_content">
				<?php
				// Post content
				if ($post_data['post_protected']) { 
					echo trim($post_data['post_excerpt']);
				} else {
					echo !empty($post_data['post_content']) ? trim($post_data['post_content']) : esc_html__('No image description ...', 'yogastudio'); 
					wp_link_pages( array( 
						'before' => '<div class="nav_pages_parts"><span class="pages">' . esc_html__( 'Pages:', 'yogastudio' ) . '</span>', 
						'after' => '</div>',
						'link_before' => '<span class="page_num">',
						'link_after' => '</span>'
					) ); 
					
					if ( yogastudio_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
						?>
						<div class="post_info post_info_bottom">
							<span class="post_info_item post_info_tags"><?php esc_html_e('Tags:', 'yogastudio'); ?> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
						</div>
						<?php 
					}
				}
				?>
			
			</div>	<!-- /.post_content -->
		
		</article>

		<section class="related_wrap related_wrap_empty"></section>

		<?php	
		if (!$post_data['post_protected']) {
			require yogastudio_get_file_dir('templates/_parts/comments.php');
		}

		require yogastudio_get_file_dir('templates/_parts/views-counter.php');
	}
}
?>