<?php
/**
Template Name: Single post
 */
get_header(); 

$single_style = yogastudio_storage_get('single_style');
if (empty($single_style)) $single_style = yogastudio_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	yogastudio_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !yogastudio_param_is_off(yogastudio_get_custom_option('show_sidebar_main')),
			'content' => yogastudio_get_template_property($single_style, 'need_content'),
			'terms_list' => yogastudio_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>