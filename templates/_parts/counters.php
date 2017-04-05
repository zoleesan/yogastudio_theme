<?php
$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';
 
// Views
if ($show_all_counters || yogastudio_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php echo trim($counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo esc_attr( sprintf(__('Views - %s', 'yogastudio'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_views']); ?></span><?php if (yogastudio_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Views', 'yogastudio'); ?></<?php echo trim($counters_tag); ?>>
	<?php
}

// Comments
if ($show_all_counters || yogastudio_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'yogastudio'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_comments']); ?></span><?php if (yogastudio_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Comments', 'yogastudio'); ?></a>
	<?php 
}
 
// Rating
$rating = $post_data['post_reviews_'.(yogastudio_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || yogastudio_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php echo trim($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'yogastudio'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo trim($rating); ?></span></<?php echo trim($counters_tag); ?>>
	<?php
}

// Likes
if ($show_all_counters || yogastudio_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	yogastudio_enqueue_messages();
	$likes = isset($_COOKIE['yogastudio_likes']) ? $_COOKIE['yogastudio_likes'] : '';
	$allow = yogastudio_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'yogastudio') : esc_attr__('Dislike', 'yogastudio'); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'yogastudio'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'yogastudio'); ?>"><span class="post_counters_number"><?php echo trim($post_data['post_likes']); ?></span><?php if (yogastudio_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Likes', 'yogastudio'); ?></a>
	<?php
}

// Edit page link
if (yogastudio_strpos($post_options['counters'], 'edit')!==false) {
	edit_post_link( esc_html__( 'Edit', 'yogastudio' ), '<span class="post_edit edit-link">', '</span>' );
}

// Markup for search engines
if (is_single() && yogastudio_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(yogastudio_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(yogastudio_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>