<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_masonry_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_masonry_theme_setup', 1 );
	function yogastudio_template_masonry_theme_setup() {
		yogastudio_add_template(array(
			'layout' => 'masonry_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile (different height) /2 columns/', 'yogastudio'),
			'thumb_title'  => esc_html__('Large image', 'yogastudio'),
			'w'		 => 870,
			'h_crop' => 490,
			'h'      => null
		));
		yogastudio_add_template(array(
			'layout' => 'masonry_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /3 columns/', 'yogastudio'),
			'thumb_title'  => esc_html__('Medium image', 'yogastudio'),
			'w'		 => 390,
			'h_crop' => 220,
			'h'      => null
		));
		yogastudio_add_template(array(
			'layout' => 'masonry_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /4 columns/', 'yogastudio'),
			'thumb_title'  => esc_html__('Small image', 'yogastudio'),
			'w'		 => 300,
			'h_crop' => 170,
			'h'      => null
		));
		yogastudio_add_template(array(
			'layout' => 'classic_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile (equal height) /2 columns/', 'yogastudio'),
			'thumb_title'  => esc_html__('Large image classic_2 (crop)', 'yogastudio'),
			'w'		 => 870,
			'h'		 => 430
		));
		yogastudio_add_template(array(
			'layout' => 'classic_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile /3 columns/', 'yogastudio'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'yogastudio'),
			'w'		 => 390,
			'h'		 => 220
		));
		yogastudio_add_template(array(
			'layout' => 'classic_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Classic tile /4 columns/', 'yogastudio'),
			'thumb_title'  => esc_html__('Small image (crop)', 'yogastudio'),
			'w'		 => 300,
			'h'		 => 170
		));
		// Add template specific scripts
		add_action('yogastudio_action_blog_scripts', 'yogastudio_template_masonry_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('yogastudio_template_masonry_add_scripts')) {
	//add_action('yogastudio_action_blog_scripts', 'yogastudio_template_masonry_add_scripts');
	function yogastudio_template_masonry_add_scripts($style) {
		if (in_array(yogastudio_substr($style, 0, 8), array('classic_', 'masonry_'))) {
			yogastudio_enqueue_script( 'isotope', yogastudio_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_masonry_output' ) ) {
	function yogastudio_template_masonry_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = yogastudio_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
					<?php
					if ($post_options['filters'] != '') {
						if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
						else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
					}
					?>">
			<<?php echo trim($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
				 <?php echo ' post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
				?>">
				
				<?php if ($post_data['post_video'] || $post_data['post_audio'] || $post_data['post_thumb'] ||  $post_data['post_gallery']) { ?>
					<div class="post_featured">
						<?php require yogastudio_get_file_dir('templates/_parts/post-featured.php'); ?>
					</div>
				<?php } ?>

				<div class="post_content isotope_item_content">
					
					<?php

										if (!$post_data['post_protected'] && $post_options['info']) {
						$info_parts = array('counters'=>false, 'terms'=>false, 'author'=>true);
						require yogastudio_get_file_dir('templates/_parts/post-info.php');
					}


					if ($show_title) {
						if (!isset($post_options['links']) || $post_options['links']) {
							?>
							<h5 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo trim($post_data['post_title']); ?></a></h5>
							<?php
						} else {
							?>
							<h5 class="post_title"><?php echo trim($post_data['post_title']); ?></h5>
							<?php
						}
					}
					
					?>

				</div>				<!-- /.post_content -->
			</<?php echo trim($tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php
	}
}
?>