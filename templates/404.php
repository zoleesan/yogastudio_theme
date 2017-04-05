<?php
/*
 * The template for displaying "Page 404"
*/

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'yogastudio_template_404_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_template_404_theme_setup', 1 );
	function yogastudio_template_404_theme_setup() {
		yogastudio_add_template(array(
			'layout' => '404',
			'mode'   => 'internal',
			'title'  => 'Page 404',
			'theme_options' => array(
				'article_style' => 'stretch'
			),
			'w'		 => null,
			'h'		 => null
			));
	}
}

// Template output
if ( !function_exists( 'yogastudio_template_404_output' ) ) {
	function yogastudio_template_404_output() {
		global $YOGASTUDIO_GLOBALS;
		?>
		<article class="post_item post_item_404">
		<img class="background_cover" src="/wp-content/themes/yogastudio/images/404back.png">
			<div class="post_content">
				<h1 class="page_title"><?php esc_html_e('Sorry! Can\'t find that page! Error 404.', 'yogastudio'); ?></h1>
				<p class="page_description"><?php echo wp_kses( sprintf( __('Can\'t find what you need? Take a moment and do<br>a search below or start from <a href="%s">our homepage</a>.', 'yogastudio'), esc_url(home_url('/')) ), $YOGASTUDIO_GLOBALS['allowed_tags'] ); ?></p>
				<div class="page_search"><?php echo trim(yogastudio_sc_search(array('state'=>'fixed', 'title'=>__('To search type and hit enter', 'yogastudio')))); ?></div>
			</div>
		</article>
		<?php
	}
}
?>