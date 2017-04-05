<?php
/**
 * The Header for our theme.
 */

// Theme init - don't remove next row! Load custom options
yogastudio_core_init_theme();

$theme_skin = yogastudio_esc(yogastudio_get_custom_option('theme_skin'));
$body_scheme = yogastudio_get_custom_option('body_scheme');
if (empty($body_scheme)  || yogastudio_is_inherit_option($body_scheme)) $body_scheme = 'original';
$blog_style = yogastudio_get_custom_option(is_singular() && !yogastudio_get_global('blog_streampage') ? 'single_style' : 'blog_style');
$body_style  = yogastudio_get_custom_option('body_style');
$article_style = yogastudio_get_custom_option('article_style');
$top_panel_style = yogastudio_get_custom_option('top_panel_style');
$top_panel_position = yogastudio_get_custom_option('top_panel_position');
$top_panel_scheme = yogastudio_get_custom_option('top_panel_scheme');
$video_bg_show  = yogastudio_get_custom_option('show_video_bg')=='yes' && (yogastudio_get_custom_option('video_bg_youtube_code')!='' || yogastudio_get_custom_option('video_bg_url')!='');
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr('scheme_'.$body_scheme); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1<?php if (yogastudio_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
	<meta name="format-detection" content="telephone=no">
	<?php
	if (floatval(get_bloginfo('version')) < 4.1) {
		?><title><?php wp_title( '|', true, 'right' ); ?></title><?php
	}
	?>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    
    <?php
	if ( !function_exists('has_site_icon') || !has_site_icon() ) {
		$favicon = yogastudio_get_custom_option('favicon');
		if (!$favicon) {
			if ( file_exists(yogastudio_get_file_dir('skins/'.($theme_skin).'/images/favicon.ico')) )
				$favicon = yogastudio_get_file_url('skins/'.($theme_skin).'/images/favicon.ico');
			if ( !$favicon && file_exists(yogastudio_get_file_dir('favicon.ico')) )
				$favicon = yogastudio_get_file_url('favicon.ico');
		}
		if ($favicon) {
			?><link rel="icon" type="image/x-icon" href="<?php echo esc_url($favicon); ?>" /><?php
		}
	}
	
	wp_head();
	?>
</head>

<body <?php 
	body_class('yogastudio_body body_style_' . esc_attr($body_style) 
		. ' body_' . (yogastudio_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent')
		. ' theme_skin_' . esc_attr($theme_skin)
		. ' article_style_' . esc_attr($article_style)
		. ' layout_' . esc_attr($blog_style)
		. ' template_' . esc_attr(yogastudio_get_template_name($blog_style))
		. (!yogastudio_param_is_off($top_panel_position) ? ' top_panel_show top_panel_' . esc_attr($top_panel_position) : 'top_panel_hide')
		. ' ' . esc_attr(yogastudio_get_sidebar_class())
		. ($video_bg_show ? ' video_bg_show' : '')
	);
	?>

>
	<?php echo force_balance_tags(yogastudio_get_custom_option('gtm_code')); ?>

	<?php do_action( 'before' ); ?>

	<?php
	// Add TOC items 'Home' and "To top"
	if (yogastudio_get_custom_option('menu_toc_home')=='yes')
		echo trim(yogastudio_sc_anchor(array(
			'id' => "toc_home",
			'title' => esc_html__('Home', 'yogastudio'),
			'description' => esc_html__('{{Return to Home}} - ||navigate to home page of the site', 'yogastudio'),
			'icon' => "icon-home",
			'separator' => "yes",
			'url' => esc_url(home_url('/'))
			)
		)); 
	if (yogastudio_get_custom_option('menu_toc_top')=='yes')
		echo trim(yogastudio_sc_anchor(array(
			'id' => "toc_top",
			'title' => esc_html__('To Top', 'yogastudio'),
			'description' => esc_html__('{{Back to top}} - ||scroll to top of the page', 'yogastudio'),
			'icon' => "icon-double-up",
			'separator' => "yes")
			)); 
	?>

	<?php if ( !yogastudio_param_is_off(yogastudio_get_custom_option('show_sidebar_outer')) ) { ?>
	<div class="outer_wrap">
	<?php } ?>

	<?php require_once yogastudio_get_file_dir('sidebar_outer.php'); ?>

	<?php
		$class = $style = '';
		if ($body_style=='boxed' || yogastudio_get_custom_option('bg_image_load')=='always') {
			if (($img = (int) yogastudio_get_custom_option('bg_image', 0)) > 0)
				$class = 'bg_image_'.($img);
			else if (($img = (int) yogastudio_get_custom_option('bg_pattern', 0)) > 0)
				$class = 'bg_pattern_'.($img);
			else if (($img = yogastudio_get_custom_option('bg_color', '')) != '')
				$style = 'background-color: '.($img).';';
			else if (yogastudio_get_custom_option('bg_custom')=='yes') {
				if (($img = yogastudio_get_custom_option('bg_image_custom')) != '')
					$style = 'background: url('.esc_url($img).') ' . str_replace('_', ' ', yogastudio_get_custom_option('bg_image_custom_position')) . ' no-repeat fixed;';
				else if (($img = yogastudio_get_custom_option('bg_pattern_custom')) != '')
					$style = 'background: url('.esc_url($img).') 0 0 repeat fixed;';
				else if (($img = yogastudio_get_custom_option('bg_image')) > 0)
					$class = 'bg_image_'.($img);
				else if (($img = yogastudio_get_custom_option('bg_pattern')) > 0)
					$class = 'bg_pattern_'.($img);
				if (($img = yogastudio_get_custom_option('bg_color')) != '')
					$style .= 'background-color: '.($img).';';
			}
		}
	?>

	<div class="body_wrap<?php echo !empty($class) ? ' '.esc_attr($class) : ''; ?>"<?php echo !empty($style) ? ' style="'.esc_attr($style).'"' : ''; ?>>

		<?php
		if ($video_bg_show) {
			$youtube = yogastudio_get_custom_option('video_bg_youtube_code');
			$video   = yogastudio_get_custom_option('video_bg_url');
			$overlay = yogastudio_get_custom_option('video_bg_overlay')=='yes';
			if (!empty($youtube)) {
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>" data-youtube-code="<?php echo esc_attr($youtube); ?>"></div>
				<?php
			} else if (!empty($video)) {
				$info = pathinfo($video);
				$ext = !empty($info['extension']) ? $info['extension'] : 'src';
				?>
				<div class="video_bg<?php echo !empty($overlay) ? ' video_bg_overlay' : ''; ?>"><video class="video_bg_tag" width="1280" height="720" data-width="1280" data-height="720" data-ratio="16:9" preload="metadata" autoplay loop src="<?php echo esc_url($video); ?>"><source src="<?php echo esc_url($video); ?>" type="video/<?php echo esc_attr($ext); ?>"></source></video></div>
				<?php
			}
		}
		?>

		<div class="page_wrap">

			<?php
			// Top panel 'Above' or 'Over'
			if (in_array($top_panel_position, array('above', 'over'))) {
				yogastudio_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}
			// Slider
			require_once yogastudio_get_file_dir('templates/headers/_parts/slider.php');
			// Top panel 'Below'
			if ($top_panel_position == 'below') {
				yogastudio_show_post_layout(array(
					'layout' => $top_panel_style,
					'position' => $top_panel_position,
					'scheme' => $top_panel_scheme
					), false);
			}

			// Top of page section: page title and breadcrumbs
			$show_title = yogastudio_get_custom_option('show_page_title')=='yes';
			$show_breadcrumbs = yogastudio_get_custom_option('show_breadcrumbs')=='yes';
			if ($show_title || $show_breadcrumbs) {
				?>
				<div class="top_panel_title top_panel_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present' : ''); ?> scheme_<?php echo esc_attr($top_panel_scheme); ?>">
					<div class="top_panel_title_inner top_panel_inner_style_<?php echo esc_attr(str_replace('header_', '', $top_panel_style)); ?> <?php echo (!empty($show_title) ? ' title_present_inner' : '') . (!empty($show_breadcrumbs) ? ' breadcrumbs_present_inner' : ''); ?>">
						<div class="content_wrap">
							<?php if ($show_title) { ?>
								<h1 class="page_title"><?php echo strip_tags(yogastudio_get_blog_title()); ?></h1>
							<?php } ?>
							<?php if ($show_breadcrumbs) { ?>
								<div class="breadcrumbs">
									<?php if (!is_404()) yogastudio_show_breadcrumbs(); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php
			}
			?>

			<div class="page_content_wrap page_paddings_<?php echo esc_attr(yogastudio_get_custom_option('body_paddings')); ?>">

				<?php
				// Content and sidebar wrapper
				if ($body_style!='fullscreen') yogastudio_open_wrapper('<div class="content_wrap">');

				
//Header for woocommerce custom heading
				if (yogastudio_get_custom_option('show_woo_heading') == 'yes') { 
				$woo_title = yogastudio_get_theme_option('show_woo_title');
				$woo_slogan = yogastudio_get_theme_option('show_woo_slogan');
				$woo_image = yogastudio_get_theme_option('woo_header_bg');
					?>
							<div class="content_wrap">
							<div class="custom_header_wrap">	
								<div class="woocommerce_custom_header" <?php echo (!empty($woo_image) ? 'style="background: url('.esc_url($woo_image).') center center;"' : ''); ?> ></div>
								<div class="header_content_wrap">
									<h2 class="title_header"><?php echo (!empty($woo_title) ? esc_attr($woo_title) : esc_html__('Welcome', 'yogastudio')); ?></h2>
									<h6 class="slogan_header"><?php echo (!empty($woo_slogan) ? esc_attr($woo_slogan) : esc_html__('Glad to see you in our store', 'yogastudio'));?></h6>
								
								</div>
							</div>	<!-- /.content_wrap -->
						</div>	<!-- /.contacts_wrap_inner -->
					<?php
			}

				// Main content wrapper
				yogastudio_open_wrapper('<div class="content">');
				?>