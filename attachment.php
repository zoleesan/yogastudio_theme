<?php
/**
Template Name: Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move yogastudio_set_post_views to the javascript - counter will work under cache system
	if (yogastudio_get_custom_option('use_ajax_views_counter')=='no') {
		yogastudio_set_post_views(get_the_ID());
	}

	yogastudio_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !yogastudio_param_is_off(yogastudio_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>