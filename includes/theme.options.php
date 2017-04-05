<?php

/* Theme setup section
-------------------------------------------------------------------- */


// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings



$YOGASTUDIO_GLOBALS['settings'] = array(
	
	'less_compiler'		=> 'lessc',								// no|lessc|less - Compiler for the .less
																// lessc - fast & low memory required, but .less-map, shadows & gradients not supprted
																// less  - slow, but support all features
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'internal',							// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'icons',								// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
);



// Default Theme Options
if ( !function_exists( 'yogastudio_options_settings_theme_setup' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_options_settings_theme_setup', 2 );	// Priority 1 for add yogastudio_filter handlers
	function yogastudio_options_settings_theme_setup() {
		global $YOGASTUDIO_GLOBALS;
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'yogastudio_options_reset');

		// Settings 
		$socials_type = yogastudio_get_theme_setting('socials_type');
				
		// Prepare arrays 
		$YOGASTUDIO_GLOBALS['options_params'] = apply_filters('yogastudio_filter_theme_options_params', array(
			'list_fonts'				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_socials' => ''),
			'list_icons' 				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_icons' => ''),
			'list_posts_types' 			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_posts_types' => ''),
			'list_categories' 			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_categories' => ''),
			'list_menus'				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_menus' => ''),
			'list_sidebars'				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_sidebars' => ''),
			'list_positions' 			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_sidebars_positions' => ''),
			'list_skins'				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_skins' => ''),
			'list_color_schemes'		=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_blog_counters' => ''),
			'list_animations_in' 		=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_animations_out' => ''),
			'list_filters'				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_yesno' => ''),
			'list_on_off' 				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_showhide' => ''),
			'list_sorting' 				=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_sortings' => ''),
			'list_ordering' 			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_orderings' => ''),
			'list_locations' 			=> array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_dedicated_locations' => '')
			)
		);


		// Theme options array
		$YOGASTUDIO_GLOBALS['options'] = array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'yogastudio'),
					"start" => "partitions",
					"override" => "category,services_group,page,post",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'yogastudio'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'yogastudio'),
					"desc" => wp_kses( __('Select body style, skin and color scheme for entire site. You can override this parameters on any page, post or category', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'body_style' => array(
					"title" => esc_html__('Body style', 'yogastudio'),
					"desc" => wp_kses( __('Select body style:', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] )
								. ' <br>' 
								. wp_kses( __('<b>boxed</b> - if you want use background color and/or image', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] )
								. ',<br>'
								. wp_kses( __('<b>wide</b> - page fill whole window with centered content', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] )
								. (yogastudio_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullwide</b> - page content stretched on the full width of the window (with few left and right paddings)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] )
									: '')
								. (yogastudio_get_theme_setting('allow_fullscreen') 
									? ',<br>' . wp_kses( __('<b>fullscreen</b> - page content fill whole window without any paddings', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] )
									: ''),
					"override" => "category,services_group,post,page",
					"std" => "wide",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_body_styles'],
					"dir" => "horizontal",
					"type" => "radio"
					),
		
		'body_paddings' => array(
					"title" => esc_html__('Page paddings', 'yogastudio'),
					"desc" => wp_kses( __('Add paddings above and below the page content', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'theme_skin' => array(
					"title" => esc_html__('Select theme skin', 'yogastudio'),
					"desc" => wp_kses( __('Select skin for the theme decoration', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_skins'],
					"type" => "select"
					),

		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the entire page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		'body_filled' => array(
					"title" => esc_html__('Fill body', 'yogastudio'),
					"desc" => wp_kses( __('Fill the page background with the solid color or leave it transparend to show background image (or video background)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'info_body_2' => array(
					"title" => esc_html__('Background color and image', 'yogastudio'),
					"desc" => wp_kses( __('Color and image for the site background', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'bg_custom' => array(
					"title" => esc_html__('Use custom background',  'yogastudio'),
					"desc" => wp_kses( __("Use custom color and/or image as the site background", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		'bg_color' => array(
					"title" => esc_html__('Background color',  'yogastudio'),
					"desc" => wp_kses( __('Body background color',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "#ffffff",
					"type" => "color"
					),

		'bg_pattern' => array(
					"title" => esc_html__('Background predefined pattern',  'yogastudio'),
					"desc" => wp_kses( __('Select theme background pattern (first case - without pattern)',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"options" => array(
						0 => yogastudio_get_file_url('images/spacer.png'),
						1 => yogastudio_get_file_url('images/bg/pattern_1.jpg'),
						2 => yogastudio_get_file_url('images/bg/pattern_2.jpg'),
						3 => yogastudio_get_file_url('images/bg/pattern_3.jpg'),
						4 => yogastudio_get_file_url('images/bg/pattern_4.jpg'),
						5 => yogastudio_get_file_url('images/bg/pattern_5.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_pattern_custom' => array(
					"title" => esc_html__('Background custom pattern',  'yogastudio'),
					"desc" => wp_kses( __('Select or upload background custom pattern. If selected - use it instead the theme predefined pattern (selected in the field above)',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image' => array(
					"title" => esc_html__('Background predefined image',  'yogastudio'),
					"desc" => wp_kses( __('Select theme background image (first case - without image)',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						0 => yogastudio_get_file_url('images/spacer.png'),
						1 => yogastudio_get_file_url('images/bg/image_1_thumb.jpg'),
						2 => yogastudio_get_file_url('images/bg/image_2_thumb.jpg'),
						3 => yogastudio_get_file_url('images/bg/image_3_thumb.jpg')
					),
					"style" => "list",
					"type" => "images"
					),
		
		'bg_image_custom' => array(
					"title" => esc_html__('Background custom image',  'yogastudio'),
					"desc" => wp_kses( __('Select or upload background custom image. If selected - use it instead the theme predefined image (selected in the field above)',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'bg_image_custom_position' => array( 
					"title" => esc_html__('Background custom image position',  'yogastudio'),
					"desc" => wp_kses( __('Select custom image position',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "left_top",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'left_top' => "Left Top",
						'center_top' => "Center Top",
						'right_top' => "Right Top",
						'left_center' => "Left Center",
						'center_center' => "Center Center",
						'right_center' => "Right Center",
						'left_bottom' => "Left Bottom",
						'center_bottom' => "Center Bottom",
						'right_bottom' => "Right Bottom",
					),
					"type" => "select"
					),
		
		'bg_image_load' => array(
					"title" => esc_html__('Load background image', 'yogastudio'),
					"desc" => wp_kses( __('Always load background images or only for boxed body style', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "boxed",
					"size" => "medium",
					"dependency" => array(
						'bg_custom' => array('yes')
					),
					"options" => array(
						'boxed' => esc_html__('Boxed', 'yogastudio'),
						'always' => esc_html__('Always', 'yogastudio')
					),
					"type" => "switch"
					),

		
		'info_body_3' => array(
					"title" => esc_html__('Video background', 'yogastudio'),
					"desc" => wp_kses( __('Parameters of the video, used as site background', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'show_video_bg' => array(
					"title" => esc_html__('Show video background',  'yogastudio'),
					"desc" => wp_kses( __("Show video as the site background", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'video_bg_youtube_code' => array(
					"title" => esc_html__('Youtube code for video bg',  'yogastudio'),
					"desc" => wp_kses( __("Youtube code of video", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "",
					"type" => "text"
					),

		'video_bg_url' => array(
					"title" => esc_html__('Local video for video bg',  'yogastudio'),
					"desc" => wp_kses( __("URL to video-file (uploaded on your site)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"readonly" =>false,
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"before" => array(	'title' => esc_html__('Choose video', 'yogastudio'),
										'action' => 'media_upload',
										'multiple' => false,
										'linked_field' => '',
										'type' => 'video',
										'captions' => array('choose' => esc_html__( 'Choose Video', 'yogastudio'),
															'update' => esc_html__( 'Select Video', 'yogastudio')
														)
								),
					"std" => "",
					"type" => "media"
					),

		'video_bg_overlay' => array(
					"title" => esc_html__('Use overlay for video bg', 'yogastudio'),
					"desc" => wp_kses( __('Use overlay texture for the video background', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_video_bg' => array('yes')
					),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		
		
		
		
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'yogastudio'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'yogastudio'),
					"desc" => wp_kses( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"top_panel_style" => array(
					"title" => esc_html__('Top panel style', 'yogastudio'),
					"desc" => wp_kses( __('Select desired style of the page header', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "header_5",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_header_styles'],
					"style" => "list",
					"type" => "images"),

		"top_panel_image" => array(
					"title" => esc_html__('Top panel image', 'yogastudio'),
					"desc" => wp_kses( __('Select default background image of the page header (if not single post or featured image for current post is not specified)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_7')
					),
					"std" => "",
					"type" => "media"),
		
		"top_panel_position" => array( 
					"title" => esc_html__('Top panel position', 'yogastudio'),
					"desc" => wp_kses( __('Select position for the top panel with logo and main menu', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "above",
					"options" => array(
						'hide'  => esc_html__('Hide', 'yogastudio'),
						'above' => esc_html__('Above slider', 'yogastudio'),
						'below' => esc_html__('Below slider', 'yogastudio'),
						'over'  => esc_html__('Over slider', 'yogastudio')
					),
					"type" => "checklist"),

		"top_panel_scheme" => array(
					"title" => esc_html__('Top panel color scheme', 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the top panel', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"pushy_panel_scheme" => array(
					"title" => esc_html__('Push panel color scheme', 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the push panel (with logo, menu and socials)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'top_panel_style' => array('header_8')
					),
					"std" => "dark",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'yogastudio'),
					"desc" => wp_kses( __('Show post/page/category title', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'yogastudio'),
					"desc" => wp_kses( __('Show path to current category (post, page)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'yogastudio'),
					"desc" => wp_kses( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'yogastudio'),
					"desc" => wp_kses( __('Select the Main menu style and position', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'yogastudio'),
					"desc" => wp_kses( __('Select main menu for the current page',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"menu_attachment" => array( 
					"title" => esc_html__('Main menu attachment', 'yogastudio'),
					"desc" => wp_kses( __('Attach main menu to top of window then page scroll down', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "fixed",
					"options" => array(
						"fixed"=>esc_html__("Fix menu position", 'yogastudio'), 
						"none"=>esc_html__("Don't fix menu position", 'yogastudio')
					),
					"dir" => "vertical",
					"type" => "radio"),

		"menu_slider" => array( 
					"title" => esc_html__('Main menu slider', 'yogastudio'),
					"desc" => wp_kses( __('Use slider background for main menu items', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"type" => "switch",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no']),

		"menu_animation_in" => array( 
					"title" => esc_html__('Submenu show animation', 'yogastudio'),
					"desc" => wp_kses( __('Select animation to show submenu ', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "bounceIn",
					"type" => "select",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_animations_in']),

		"menu_animation_out" => array( 
					"title" => esc_html__('Submenu hide animation', 'yogastudio'),
					"desc" => wp_kses( __('Select animation to hide submenu ', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "fadeOutDown",
					"type" => "select",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_animations_out']),
		
		"menu_relayout" => array( 
					"title" => esc_html__('Main menu relayout', 'yogastudio'),
					"desc" => wp_kses( __('Allow relayout main menu if window width less then this value', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => 960,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_responsive" => array( 
					"title" => esc_html__('Main menu responsive', 'yogastudio'),
					"desc" => wp_kses( __('Allow responsive version for the main menu if window width less then this value', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => 640,
					"min" => 320,
					"max" => 1024,
					"type" => "spinner"),
		
		"menu_width" => array( 
					"title" => esc_html__('Submenu width', 'yogastudio'),
					"desc" => wp_kses( __('Width for dropdown menus in main menu', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"step" => 5,
					"std" => "",
					"min" => 180,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"),
		
		
		
		"info_header_3" => array(
					"title" => esc_html__("User's menu area components", 'yogastudio'),
					"desc" => wp_kses( __("Select parts for the user's menu area", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_top_panel_top" => array(
					"title" => esc_html__('Show user menu area', 'yogastudio'),
					"desc" => wp_kses( __('Show user menu area on top of page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_user" => array(
					"title" => esc_html__('Select user menu',  'yogastudio'),
					"desc" => wp_kses( __('Select user menu for the current page',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "default",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_menus'],
					"type" => "select"),
		
		"show_languages" => array(
					"title" => esc_html__('Show language selector', 'yogastudio'),
					"desc" => wp_kses( __('Show language selector in the user menu (if WPML plugin installed and current page/post has multilanguage version)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_button" => array( 
					"title" => esc_html__('Add button', 'yogastudio'),
					"desc" => wp_kses( __('Add custom button to top panel', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"button_title" => array(
					"title" => esc_html__('Title', 'yogastudio'),
					"desc" => wp_kses( __('Enter your title', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_button' => array('yes')
					),
					"std" => "",
					"type" => "spinner"),
		
		"link_for_button" => array( 
					"title" => esc_html__('Isert link', 'yogastudio'),
					"desc" => wp_kses( __('Insert your link for button', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_button' => array('yes')
					),
					"std" => "",
					"type" => "spinner"),
		"show_socials" => array( 
					"title" => esc_html__('Show Social icons', 'yogastudio'),
					"desc" => wp_kses( __('Show Social icons in the user menu area', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_top_panel_top' => array('yes')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_woo_heading" => array( 
					"title" => esc_html__('Title for Woocommerce', 'yogastudio'),
					"desc" => wp_kses( __('Show custom title for Woocomerce products shop', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_woo_title" => array( 
					"title" => esc_html__('Insert title', 'yogastudio'),
					"desc" => wp_kses( __('Insert your title for head on products cart page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_woo_heading' => array('yes')
					),
					"std" => "",
					"type" => "spinner"),

		"show_woo_slogan" => array( 
					"title" => esc_html__('Insert slogan', 'yogastudio'),
					"desc" => wp_kses( __('Insert your slogan for head on products cart page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_woo_heading' => array('yes')
					),
					"std" => "",
					"type" => "spinner"),

		'woo_header_bg' => array(
					"title" => esc_html__('Background image', 'yogastudio'),
					"desc" => wp_kses( __('Background image for head on products cart page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"dependency" => array(
						'show_woo_heading' => array('yes')
					),
					"type" => "media"
					),

		







		
		"info_header_4" => array( 
					"title" => esc_html__("Table of Contents (TOC)", 'yogastudio'),
					"desc" => wp_kses( __("Table of Contents for the current page. Automatically created if the page contains objects with id starting with 'toc_'", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"menu_toc" => array( 
					"title" => esc_html__('TOC position', 'yogastudio'),
					"desc" => wp_kses( __('Show TOC for the current page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "float",
					"options" => array(
						'hide'  => esc_html__('Hide', 'yogastudio'),
						'fixed' => esc_html__('Fixed', 'yogastudio'),
						'float' => esc_html__('Float', 'yogastudio')
					),
					"type" => "checklist"),
		
		"menu_toc_home" => array(
					"title" => esc_html__('Add "Home" into TOC', 'yogastudio'),
					"desc" => wp_kses( __('Automatically add "Home" item into table of contents - return to home page of the site', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"menu_toc_top" => array( 
					"title" => esc_html__('Add "To Top" into TOC', 'yogastudio'),
					"desc" => wp_kses( __('Automatically add "To Top" item into table of contents - scroll to top of the page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'menu_toc' => array('fixed','float')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		
		
		
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'yogastudio'),
					"desc" => wp_kses( __("Select or upload logos for the site's header and select it position", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"
					),

		'favicon' => array(
					"title" => esc_html__('Favicon', 'yogastudio'),
					"desc" => wp_kses( __("Upload a 16px x 16px image that will represent your website's favicon.<br /><em>To ensure cross-browser compatibility, we recommend converting the favicon into .ico format before uploading. (<a href='http://www.favicon.cc/'>www.favicon.cc</a>)</em>", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"type" => "media"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'yogastudio'),
					"desc" => wp_kses( __('Main logo image', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'yogastudio'),
					"desc" => wp_kses( __('Main logo image used on Retina display', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "",
					"type" => "media"
					),

		'logo_fixed' => array(
					"title" => esc_html__('Logo image (fixed header)', 'yogastudio'),
					"desc" => wp_kses( __('Logo image for the header (if menu is fixed after the page is scrolled)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"divider" => false,
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'yogastudio'),
					"desc" => wp_kses( __('Logo text - display it after logo image', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'yogastudio'),
					"desc" => wp_kses( __('Height for the logo in the header area', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),

		'logo_offset' => array(
					"title" => esc_html__('Logo top offset', 'yogastudio'),
					"desc" => wp_kses( __('Top offset for the logo in the header area', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"step" => 1,
					"std" => '',
					"min" => 0,
					"max" => 99,
					"mask" => "?99",
					"type" => "spinner"
					),
		
		
		
		
		
		
		
		// Customization -> Slider
		//-------------------------------------------------
		
		"customization_slider" => array( 
					"title" => esc_html__('Slider', 'yogastudio'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_slider_1" => array(
					"title" => esc_html__('Main slider parameters', 'yogastudio'),
					"desc" => wp_kses( __('Select parameters for main slider (you can override it in each category and page)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
					
		"show_slider" => array(
					"title" => esc_html__('Show Slider', 'yogastudio'),
					"desc" => wp_kses( __('Do you want to show slider on each page (post)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_display" => array(
					"title" => esc_html__('Slider display', 'yogastudio'),
					"desc" => wp_kses( __('How display slider: boxed (fixed width and height), fullwide (fixed height) or fullscreen', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "fullwide",
					"options" => array(
						"boxed"=>esc_html__("Boxed", 'yogastudio'),
						"fullwide"=>esc_html__("Fullwide", 'yogastudio'),
						"fullscreen"=>esc_html__("Fullscreen", 'yogastudio')
					),
					"type" => "checklist"),
		
		"slider_height" => array(
					"title" => esc_html__("Height (in pixels)", 'yogastudio'),
					"desc" => wp_kses( __("Slider height (in pixels) - only if slider display with fixed height.", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => '',
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"slider_engine" => array(
					"title" => esc_html__('Slider engine', 'yogastudio'),
					"desc" => wp_kses( __('What engine use to show slider?', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes')
					),
					"std" => "swiper",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_sliders'],
					"type" => "radio"),
		
		"slider_category" => array(
					"title" => esc_html__('Posts Slider: Category to show', 'yogastudio'),
					"desc" => wp_kses( __('Select category to show in Flexslider (ignored for Revolution and Royal sliders)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "",
					"options" => yogastudio_array_merge(array(0 => esc_html__('- Select category -', 'yogastudio')), $YOGASTUDIO_GLOBALS['options_params']['list_categories']),
					"type" => "select",
					"multiple" => true,
					"style" => "list"),
		
		"slider_posts" => array(
					"title" => esc_html__('Posts Slider: Number posts or comma separated posts list',  'yogastudio'),
					"desc" => wp_kses( __("How many recent posts display in slider or comma separated list of posts ID (in this case selected category ignored)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "5",
					"type" => "text"),
		
		"slider_orderby" => array(
					"title" => esc_html__("Posts Slider: Posts order by",  'yogastudio'),
					"desc" => wp_kses( __("Posts in slider ordered by date (default), comments, views, author rating, users rating, random or alphabetically", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "date",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"slider_order" => array(
					"title" => esc_html__("Posts Slider: Posts order", 'yogastudio'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "desc",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
					
		"slider_interval" => array(
					"title" => esc_html__("Posts Slider: Slide change interval", 'yogastudio'),
					"desc" => wp_kses( __("Interval (in ms) for slides change in slider", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 7000,
					"min" => 100,
					"step" => 100,
					"type" => "spinner"),
		
		"slider_pagination" => array(
					"title" => esc_html__("Posts Slider: Pagination", 'yogastudio'),
					"desc" => wp_kses( __("Choose pagination style for the slider", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "no",
					"options" => array(
						'no'   => esc_html__('None', 'yogastudio'),
						'yes'  => esc_html__('Dots', 'yogastudio'), 
						'over' => esc_html__('Titles', 'yogastudio')
					),
					"type" => "checklist"),
		
		"slider_infobox" => array(
					"title" => esc_html__("Posts Slider: Show infobox", 'yogastudio'),
					"desc" => wp_kses( __("Do you want to show post's title, reviews rating and description on slides in slider", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "slide",
					"options" => array(
						'no'    => esc_html__('None',  'yogastudio'),
						'slide' => esc_html__('Slide', 'yogastudio'), 
						'fixed' => esc_html__('Fixed', 'yogastudio')
					),
					"type" => "checklist"),
					
		"slider_info_category" => array(
					"title" => esc_html__("Posts Slider: Show post's category", 'yogastudio'),
					"desc" => wp_kses( __("Do you want to show post's category on slides in slider", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_reviews" => array(
					"title" => esc_html__("Posts Slider: Show post's reviews rating", 'yogastudio'),
					"desc" => wp_kses( __("Do you want to show post's reviews rating on slides in slider", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"slider_info_descriptions" => array(
					"title" => esc_html__("Posts Slider: Show post's descriptions", 'yogastudio'),
					"desc" => wp_kses( __("How many characters show in the post's description in slider. 0 - no descriptions", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('swiper')
					),
					"std" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"),
		
		
		
		
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'yogastudio'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'yogastudio'),
					"desc" => wp_kses( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'yogastudio'),
					"desc" => wp_kses( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'yogastudio'),
					"desc" => wp_kses( __('Show / Hide and select main sidebar', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'yogastudio'),
					"desc" => wp_kses( __('Select position for the main sidebar or hide it',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "right",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_positions'],
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the main sidebar', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'yogastudio'),
					"desc" => wp_kses( __('Select main sidebar content',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_main' => array('left', 'right')
					),
					"std" => "sidebar_main",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'yogastudio'),
					"override" => "category,services_group,post,page",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'yogastudio'),
					"desc" => wp_kses( __("Select components of the footer, set style and put the content for the user's footer area", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'yogastudio'),
					"desc" => wp_kses( __('Select style for the footer sidebar or hide it', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the footer', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'yogastudio'),
					"desc" => wp_kses( __('Select footer sidebar for the blog page',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_sidebars'],
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'yogastudio'),
					"desc" => wp_kses( __('Select columns number for the footer sidebar',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		
		"info_footer_2" => array(
					"title" => esc_html__('Testimonials in Footer', 'yogastudio'),
					"desc" => wp_kses( __('Select parameters for Testimonials in the Footer', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_testimonials_in_footer" => array(
					"title" => esc_html__('Show Testimonials in footer', 'yogastudio'),
					"desc" => wp_kses( __('Show Testimonials slider in footer. For correct operation of the slider (and shortcode testimonials) you must fill out Testimonials posts on the menu "Testimonials"', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"testimonials_scheme" => array(
					"title" => esc_html__("Color scheme", 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the testimonials area', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"testimonials_count" => array( 
					"title" => esc_html__('Testimonials count', 'yogastudio'),
					"desc" => wp_kses( __('Number testimonials to show', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_testimonials_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		
		"info_footer_3" => array(
					"title" => esc_html__('Twitter in Footer', 'yogastudio'),
					"desc" => wp_kses( __('Select parameters for Twitter stream in the Footer (you can override it in each category and page)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_twitter_in_footer" => array(
					"title" => esc_html__('Show Twitter in footer', 'yogastudio'),
					"desc" => wp_kses( __('Show Twitter slider in footer. For correct operation of the slider (and shortcode twitter) you must fill out the Twitter API keys on the menu "Appearance - Theme Options - Socials"', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"twitter_scheme" => array(
					"title" => esc_html__("Color scheme", 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the twitter area', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		"twitter_count" => array( 
					"title" => esc_html__('Twitter count', 'yogastudio'),
					"desc" => wp_kses( __('Number twitter to show', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_twitter_in_footer' => array('yes')
					),
					"std" => 3,
					"step" => 1,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),


		"info_footer_4" => array(
					"title" => esc_html__('Google map parameters', 'yogastudio'),
					"desc" => wp_kses( __('Select parameters for Google map (you can override it in each category and page)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
					
		"show_googlemap" => array(
					"title" => esc_html__('Show Google Map', 'yogastudio'),
					"desc" => wp_kses( __('Do you want to show Google map on each page (post)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"googlemap_height" => array(
					"title" => esc_html__("Map height", 'yogastudio'),
					"desc" => wp_kses( __("Map height (default - in pixels, allows any CSS units of measure)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 400,
					"min" => 100,
					"step" => 10,
					"type" => "spinner"),
		
		"googlemap_address" => array(
					"title" => esc_html__('Address to show on map',  'yogastudio'),
					"desc" => wp_kses( __("Enter address to show on map center", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_latlng" => array(
					"title" => esc_html__('Latitude and Longitude to show on map',  'yogastudio'),
					"desc" => wp_kses( __("Enter coordinates (separated by comma) to show on map center (instead of address)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_title" => array(
					"title" => esc_html__('Title to show on map',  'yogastudio'),
					"desc" => wp_kses( __("Enter title to show on map center", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_description" => array(
					"title" => esc_html__('Description to show on map',  'yogastudio'),
					"desc" => wp_kses( __("Enter description to show on map center", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => "",
					"type" => "text"),
		
		"googlemap_zoom" => array(
					"title" => esc_html__('Google map initial zoom',  'yogastudio'),
					"desc" => wp_kses( __("Enter desired initial zoom for Google map", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 16,
					"min" => 1,
					"max" => 20,
					"step" => 1,
					"type" => "spinner"),
		
		"googlemap_style" => array(
					"title" => esc_html__('Google map style',  'yogastudio'),
					"desc" => wp_kses( __("Select style to show Google map", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => 'style1',
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_gmap_styles'],
					"type" => "select"),
		
		"googlemap_marker" => array(
					"title" => esc_html__('Google map marker',  'yogastudio'),
					"desc" => wp_kses( __("Select or upload png-image with Google map marker", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_googlemap' => array('yes')
					),
					"std" => '',
					"type" => "media"),
		
		
		
		"info_footer_5" => array(
					"title" => esc_html__("Contacts area", 'yogastudio'),
					"desc" => wp_kses( __("Show/Hide contacts area in the footer", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"show_contacts_in_footer" => array(
					"title" => esc_html__('Show Contacts in footer', 'yogastudio'),
					"desc" => wp_kses( __('Show contact information area in footer: site logo, contact info and large social icons', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"contacts_scheme" => array(
					"title" => esc_html__("Color scheme", 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the contacts area', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),

		'logo_footer' => array(
					"title" => esc_html__('Logo image for footer', 'yogastudio'),
					"desc" => wp_kses( __('Logo image in the footer (in the contacts area)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),

		'logo_footer_retina' => array(
					"title" => esc_html__('Logo image for footer for Retina', 'yogastudio'),
					"desc" => wp_kses( __('Logo image in the footer (in the contacts area) used on Retina display', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"std" => "",
					"type" => "media"
					),
		
		'logo_footer_height' => array(
					"title" => esc_html__('Logo height', 'yogastudio'),
					"desc" => wp_kses( __('Height for the logo in the footer area (in the contacts area)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_contacts_in_footer' => array('yes')
					),
					"step" => 1,
					"std" => 30,
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
		
		
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'yogastudio'),
					"desc" => wp_kses( __("Show/Hide copyright area in the footer", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'yogastudio'),
					"desc" => wp_kses( __('Show area with copyright information, footer menu and small social icons in footer', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'yogastudio'),
						'text' => esc_html__('Text', 'yogastudio'),
						'menu' => esc_html__('Text and menu', 'yogastudio'),
						'socials' => esc_html__('Text and Social icons', 'yogastudio')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'yogastudio'),
					"desc" => wp_kses( __('Select predefined color scheme for the copyright area', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_color_schemes'],
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'yogastudio'),
					"desc" => wp_kses( __('Select footer menu for the current page',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_menus'],
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'yogastudio'),
					"desc" => wp_kses( __("Copyright text to show in footer area (bottom of site)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "YogaStudio &copy; 2014 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'yogastudio'),
					"override" => "category,services_group,page,post",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'yogastudio'),
					"desc" => wp_kses( __('Animation parameters and responsive layouts for the small screens', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"
					),

		'show_theme_customizer' => array(
					"title" => esc_html__('Show Theme customizer', 'yogastudio'),
					"desc" => wp_kses( __('Do you want to show theme customizer in the right panel? Your website visitors will be able to customise it yourself.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		"customizer_demo" => array(
					"title" => esc_html__('Theme customizer panel demo time', 'yogastudio'),
					"desc" => wp_kses( __('Timer for demo mode for the customizer panel (in milliseconds: 1000ms = 1s). If 0 - no demo.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_theme_customizer' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 10000,
					"step" => 500,
					"type" => "spinner"),
		
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'yogastudio'),
					"desc" => wp_kses( __('Do you want use extended animations effects on your site?', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),

		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'yogastudio'),
					"desc" => wp_kses( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'yogastudio'),
					"desc" => wp_kses( __('Do you want use responsive layouts on small screen or still use main layout?', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'yogastudio'),
					"desc" => wp_kses( __('Specify additional parameters, used to load Google fonts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'yogastudio'),
					"desc" => wp_kses( __('Select subset, included into used Google fonts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'yogastudio'),
						'latin-ext' => esc_html__('Latin Extended', 'yogastudio'),
						'greek' => esc_html__('Greek', 'yogastudio'),
						'greek-ext' => esc_html__('Greek Extended', 'yogastudio'),
						'cyrillic' => esc_html__('Cyrillic', 'yogastudio'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'yogastudio'),
						'vietnamese' => esc_html__('Vietnamese', 'yogastudio')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'yogastudio'),
					"desc" => wp_kses( __('Put here your custom CSS and JS code', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom CSS/HTML/JS', 'yogastudio'),
					"desc" => wp_kses( __('Do you want use custom HTML/CSS/JS code in your site? For example: custom styles, Google Analitics code, etc.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'yogastudio'),
					"desc" => wp_kses( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'yogastudio'),
					"desc" => wp_kses( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		'custom_code' => array(
					"title" => esc_html__('Your custom HTML/JS code',  'yogastudio'),
					"desc" => wp_kses( __('Put here your invisible html/js code: Google analitics, counters, etc',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"
					),
		
		'custom_css' => array(
					"title" => esc_html__('Your custom CSS code',  'yogastudio'),
					"desc" => wp_kses( __('Put here your css code to correct main theme styles',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"type" => "textarea"
					),
		
		
		
		
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'yogastudio'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'yogastudio'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'yogastudio'),
					"desc" => wp_kses( __('Select desired blog streampage parameters (you can override it in each category)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'yogastudio'),
					"desc" => wp_kses( __('Select desired blog style', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "excerpt",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_blog_styles'],
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'yogastudio'),
					"desc" => wp_kses( __('Select desired hover style (only for Blog style = Portfolio)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_hovers'],
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'yogastudio'),
					"desc" => wp_kses( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_hovers_dir'],
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'yogastudio'),
					"desc" => wp_kses( __('Select article display method: boxed or stretch', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "stretch",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_article_styles'],
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'yogastudio'),
					"desc" => wp_kses( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_locations'],
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'yogastudio'),
					"desc" => wp_kses( __('What taxonomy use for filter buttons', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_filters'],
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'yogastudio'),
					"desc" => wp_kses( __('Select the desired sorting method for posts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "date",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_sorting'],
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'yogastudio'),
					"desc" => wp_kses( __('Select the desired ordering method for posts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "desc",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'yogastudio'),
					"desc" => wp_kses( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'yogastudio'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'yogastudio'),
					"desc" => wp_kses( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "50",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'yogastudio'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'yogastudio'),
					"desc" => wp_kses( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"type" => "info"),
		
		"single_style" => array(
					"title" => esc_html__('Single page style', 'yogastudio'),
					"desc" => wp_kses( __('Select desired style for single page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "single-standard",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_single_styles'],
					"dir" => "horizontal",
					"type" => "radio"),

		"icon" => array(
					"title" => esc_html__('Select post icon', 'yogastudio'),
					"desc" => wp_kses( __('Select icon for output before post/category name in some layouts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "services_group,page,post",
					"std" => "",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_icons'],
					"style" => "select",
					"type" => "icons"
					),

		"alter_thumb_size" => array(
					"title" => esc_html__('Alter thumb size (WxH)',  'yogastudio'),
					"override" => "page,post",
					"desc" => wp_kses( __("Select thumb size for the alternative portfolio layout (number items horizontally x number items vertically)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"class" => "",
					"std" => "1_1",
					"type" => "radio",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_alter_sizes']
					),
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'yogastudio'),
					"desc" => wp_kses( __("Show featured image (if selected) before post content on single pages", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page,post",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'yogastudio'),
					"desc" => wp_kses( __('Show area with post title on single pages', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'yogastudio'),
					"desc" => wp_kses( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'yogastudio'),
					"desc" => wp_kses( __('Show area with post info on single pages', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'yogastudio'),
					"desc" => wp_kses( __('Show text before "Read more" tag on single pages', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'yogastudio'),
					"desc" => wp_kses( __("Show post author information block on single post page", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'yogastudio'),
					"desc" => wp_kses( __("Show tags block on single post page", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'yogastudio'),
					"desc" => wp_kses( __("Show related posts block on single post page", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'yogastudio'),
					"desc" => wp_kses( __("How many related posts showed on single post page", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,page",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'yogastudio'),
					"desc" => wp_kses( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'yogastudio'),
					"desc" => wp_kses( __('Select the desired sorting method for related posts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_sorting'],
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'yogastudio'),
					"desc" => wp_kses( __('Select the desired ordering method for related posts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
		//			"override" => "category,services_group,page",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_ordering'],
					"size" => "big",
					"type" => "switch"),
		
		"show_post_comments" => array(
					"title" => esc_html__('Show comments',  'yogastudio'),
					"desc" => wp_kses( __("Show comments block on single post page", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'yogastudio'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'yogastudio'),
					"desc" => wp_kses( __('Select excluded categories, substitute parameters, etc.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'yogastudio'),
					"desc" => wp_kses( __('Select categories, which posts are exclude from blog page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_categories'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'yogastudio'),
					"desc" => wp_kses( __('Select type of the pagination on blog streampages', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "pages",
					"override" => "category,services_group,page",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'yogastudio'),
						'slider'   => esc_html__('Slider with page numbers', 'yogastudio'),
						'viewmore' => esc_html__('"View more" button', 'yogastudio'),
						'infinite' => esc_html__('Infinite scroll', 'yogastudio')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'yogastudio'),
					"desc" => wp_kses( __('Select counters, displayed near the post title', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "views",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_blog_counters'],
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'yogastudio'),
					"desc" => wp_kses( __('What category display in announce block (over posts thumb) - original or nearest parental', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'yogastudio'),
						'original' => esc_html__("Original post's category", 'yogastudio')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'yogastudio'),
					"desc" => wp_kses( __('Show post date after N days (before - show post age)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'yogastudio'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'yogastudio'),
					"desc" => wp_kses( __('Set up list of reviews criterias. You can override it in any category.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'yogastudio'),
					"desc" => wp_kses( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'yogastudio'),
					"desc" => wp_kses( __("Maximum level for reviews marks", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'yogastudio'), 
						'10'=>esc_html__('10 stars', 'yogastudio'), 
						'100'=>esc_html__('100%', 'yogastudio')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'yogastudio'),
					"desc" => wp_kses( __("Show rating marks as text or as stars/progress bars.", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'yogastudio'), 
						'stars' => esc_html__('As stars or bars', 'yogastudio')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'yogastudio'),
					"desc" => wp_kses( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => esc_html__("bad,poor,normal,good,great", 'yogastudio'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'yogastudio'),
					"desc" => wp_kses( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'yogastudio'),
						'users' => esc_html__('By visitors', 'yogastudio')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'yogastudio'),
					"desc" => wp_kses( __("Do you want hide second reviews tab in widgets and single posts?", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "show",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_show_hide'],
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'yogastudio'),
					"desc" => wp_kses( __("What visitors can vote: all or only registered", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'yogastudio'), 
						'registered'=>esc_html__('Only registered', 'yogastudio')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'yogastudio'),
					"desc" => wp_kses( __('Add default reviews criterias.',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'yogastudio'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'yogastudio'),
					"desc" => wp_kses( __('Set up parameters to show images, galleries, audio and video posts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'yogastudio'),
					"desc" => wp_kses( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'yogastudio'), 
						"2" => esc_html__("Retina", 'yogastudio')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'yogastudio'),
					"desc" => wp_kses( __('Quality (1-100) to save cropped images', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'yogastudio'),
					"desc" => wp_kses( __('Substitute standard Wordpress gallery with our slider on the single pages', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'yogastudio'),
					"desc" => wp_kses( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'yogastudio'),
					"desc" => wp_kses( __('Maximum images number from gallery into slider', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'yogastudio'),
					"desc" => wp_kses( __('Select engine to show popup windows with images and galleries', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "magnific",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_popups'],
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'yogastudio'),
					"desc" => wp_kses( __('Substitute audio tag with source from soundcloud to embed player', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'yogastudio'),
					"desc" => wp_kses( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,post,page",
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'yogastudio'),
					"desc" => wp_kses( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'yogastudio'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'yogastudio'),
					"desc" => wp_kses( __("Social networks list for site footer and Social widget", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'yogastudio'),
					"desc" => wp_kses( __('Select icon and write URL to your profile in desired social networks.',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $YOGASTUDIO_GLOBALS['options_params']['list_socials'] : $YOGASTUDIO_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'yogastudio'),
					"desc" => wp_kses( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'yogastudio'),
					"desc" => wp_kses( __("Show social share buttons block", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'yogastudio'),
						'vertical'	=> esc_html__('Vertical', 'yogastudio'),
						'horizontal'=> esc_html__('Horizontal', 'yogastudio')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'yogastudio'),
					"desc" => wp_kses( __("Show share counters after social buttons", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'yogastudio'),
					"desc" => wp_kses( __('Caption for the block with social share buttons',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'yogastudio'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'yogastudio'),
					"desc" => wp_kses( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? $YOGASTUDIO_GLOBALS['options_params']['list_socials'] : $YOGASTUDIO_GLOBALS['options_params']['list_icons'],
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'yogastudio'),
					"desc" => wp_kses( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'yogastudio'),
					"desc" => wp_kses( __('Your login (username) in Twitter',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'yogastudio'),
					"desc" => wp_kses( __('Twitter API Consumer key',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'yogastudio'),
					"desc" => wp_kses( __('Twitter API Consumer secret',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'yogastudio'),
					"desc" => wp_kses( __('Twitter API Token key',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'yogastudio'),
					"desc" => wp_kses( __('Twitter API Token secret',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'yogastudio'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		"info_contact_1" => array(
					"title" => esc_html__('Contact information', 'yogastudio'),
					"desc" => wp_kses( __('Company address, phones and e-mail', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"contact_info" => array(
					"title" => esc_html__('Contacts in the header', 'yogastudio'),
					"desc" => wp_kses( __('String with contact info in the left side of the site header', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_open_hours" => array(
					"title" => esc_html__('Open hours in the header', 'yogastudio'),
					"desc" => wp_kses( __('String with open hours in the site header', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-clock'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_email" => array(
					"title" => esc_html__('Contact form email', 'yogastudio'),
					"desc" => wp_kses( __('E-mail for send contact form and user registration data', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-mail'),
					"type" => "text"),
		
		"contact_address_1" => array(
					"title" => esc_html__('Company address (part 1)', 'yogastudio'),
					"desc" => wp_kses( __('Company country, post code and city', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_address_2" => array(
					"title" => esc_html__('Company address (part 2)', 'yogastudio'),
					"desc" => wp_kses( __('Street and house number', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-home'),
					"type" => "text"),
		
		"contact_phone" => array(
					"title" => esc_html__('Phone', 'yogastudio'),
					"desc" => wp_kses( __('Phone number', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"contact_fax" => array(
					"title" => esc_html__('Fax', 'yogastudio'),
					"desc" => wp_kses( __('Fax number', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "",
					"before" => array('icon'=>'iconadmin-phone'),
					"allow_html" => true,
					"type" => "text"),
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'yogastudio'),
					"desc" => wp_kses( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'yogastudio'),
					"desc" => wp_kses( __("Message's maxlength in the contact form shortcode", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'yogastudio'),
					"desc" => wp_kses( __("Message's maxlength in the comments form", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'yogastudio'),
					"desc" => wp_kses( __('What function you want to use for sending mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'yogastudio'),
					"desc" => wp_kses( __("What function you want to use for sending mail?", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'yogastudio'),
						'mail' => esc_html__('PHP mail', 'yogastudio')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'yogastudio'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'yogastudio'),
					"desc" => wp_kses( __('Enable/disable AJAX search and output settings for it', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'yogastudio'),
					"desc" => wp_kses( __('Show search field in the top area and side menus', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'yogastudio'),
					"desc" => wp_kses( __('Use incremental AJAX search for the search field in top of page', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes')
					),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'yogastudio'),
					"desc" => wp_kses( __('The minimum length of the search string',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'yogastudio'),
					"desc" => wp_kses( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'yogastudio'),
					"desc" => wp_kses( __('Select post types, what will be include in search results. If not selected - use all types.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_posts_types'],
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'yogastudio'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __('Number of the posts to show in search results',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'yogastudio'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's thumbnail in the search results", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'yogastudio'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's publish date in the search results", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'yogastudio'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's author in the search results", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'yogastudio'),
					"dependency" => array(
						'show_search' => array('yes'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses( __("Show post's counters (views, comments, likes) in the search results", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'yogastudio'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'yogastudio'),
					"desc" => wp_kses( __('Basic theme functionality settings', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"notify_about_new_registration" => array(
					"title" => esc_html__('Notify about new registration', 'yogastudio'),
					"desc" => wp_kses( __('Send E-mail with new registration data to the contact email or to site admin e-mail (if contact email is empty)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"std" => "no",
					"options" => array(
						'no'    => esc_html__('No', 'yogastudio'),
						'both'  => esc_html__('Both', 'yogastudio'),
						'admin' => esc_html__('Admin', 'yogastudio'),
						'user'  => esc_html__('User', 'yogastudio')
					),
					"dir" => "horizontal",
					"type" => "checklist"),
		
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'yogastudio'),
					"desc" => wp_kses( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"allow_editor" => array(
					"title" => esc_html__('Frontend editor',  'yogastudio'),
					"desc" => wp_kses( __("Allow authors to edit their posts in frontend area)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'yogastudio'),
					"desc" => wp_kses( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'yogastudio'),
					"desc" => wp_kses( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'yogastudio'),
					"desc" => wp_kses( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'yogastudio'),
					"desc" => wp_kses( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "yes",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'yogastudio'),
					"desc" => wp_kses( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"admin_emailer" => array(
					"title" => esc_html__('Enable Emailer in the admin panel', 'yogastudio'),
					"desc" => wp_kses( __('Allow to use YogaStudio Emailer for mass-volume e-mail distribution and management of mailing lists in "Appearance - Emailer"', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),

		"admin_po_composer" => array(
					"title" => esc_html__('Enable PO Composer in the admin panel', 'yogastudio'),
					"desc" => wp_kses( __('Allow to use "PO Composer" for edit language files in this theme (in the "Appearance - PO Composer")', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'yogastudio'),
					"desc" => wp_kses( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"std" => "no",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_yes_no'],
					"type" => "switch"),
		
		
		"info_service_2" => array(
					"title" => esc_html__('Clear Wordpress cache', 'yogastudio'),
					"desc" => wp_kses( __('For example, it recommended after activating the WPML plugin - in the cache are incorrect data about the structure of categories and your site may display "white screen". After clearing the cache usually the performance of the site is restored.', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"type" => "info"),
		
		"clear_cache" => array(
					"title" => esc_html__('Clear cache', 'yogastudio'),
					"desc" => wp_kses( __('Clear Wordpress cache data', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"divider" => false,
					"icon" => "iconadmin-trash",
					"action" => "clear_cache",
					"type" => "button")
		);



		
		
		
		//###############################################
		//#### Hidden fields (for internal use only) #### 
		//###############################################
		/*
		$YOGASTUDIO_GLOBALS['options']["custom_stylesheet_file"] = array(
			"title" => esc_html__('Custom stylesheet file', 'yogastudio'),
			"desc" => wp_kses( __('Path to the custom stylesheet (stored in the uploads folder)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		
		$YOGASTUDIO_GLOBALS['options']["custom_stylesheet_url"] = array(
			"title" => esc_html__('Custom stylesheet url', 'yogastudio'),
			"desc" => wp_kses( __('URL to the custom stylesheet (stored in the uploads folder)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
			"std" => "",
			"type" => "hidden");
		*/

		
		
	}
}


// Update all temporary vars (start with $yogastudio_) in the Theme Options with actual lists
if ( !function_exists( 'yogastudio_options_settings_theme_setup2' ) ) {
	add_action( 'yogastudio_action_after_init_theme', 'yogastudio_options_settings_theme_setup2', 1 );
	function yogastudio_options_settings_theme_setup2() {
		if (yogastudio_options_is_used()) {
			global $YOGASTUDIO_GLOBALS;
			// Replace arrays with actual parameters
			$lists = array();
			if (count($YOGASTUDIO_GLOBALS['options']) > 0) {
				$prefix = '$' . $YOGASTUDIO_GLOBALS['theme_slug'] . '_';
				$prefix_len = yogastudio_strlen($prefix);
				foreach ($YOGASTUDIO_GLOBALS['options'] as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (yogastudio_substr($k1, 0, $prefix_len) == $prefix || yogastudio_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = yogastudio_substr(yogastudio_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								unset($YOGASTUDIO_GLOBALS['options'][$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$YOGASTUDIO_GLOBALS['options'][$k]['options'] = yogastudio_array_merge($YOGASTUDIO_GLOBALS['options'][$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$YOGASTUDIO_GLOBALS['options'][$k]['options'] = $lists[$list_func] = yogastudio_array_merge($YOGASTUDIO_GLOBALS['options'][$k]['options'], $list_func == 'yogastudio_get_list_menus' ? $list_func(true) : $list_func());
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'yogastudio'), $list_func));
								}
							}
						}
					}
				}
			}
		}
	}
}

// Reset old Theme Options while theme first run
if ( !function_exists( 'yogastudio_options_reset' ) ) {
	function yogastudio_options_reset($clear=true) {
		$theme_data = wp_get_theme();
		$slug = str_replace(' ', '_', trim(yogastudio_strtolower((string) $theme_data->get('Name'))));
		$option_name = 'yogastudio_'.strip_tags($slug).'_options_reset';
		if ( get_option($option_name, false) === false ) {	// && (string) $theme_data->get('Version') == '1.0'
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query('delete from '.esc_sql($wpdb->options).' where option_name like "yogastudio_%"');
				// Add Templates Options
				if (file_exists(yogastudio_get_file_dir('demo/templates_options.txt'))) {
					$txt = yogastudio_fgc(yogastudio_get_file_dir('demo/templates_options.txt'));
					$data = yogastudio_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = yogastudio_replace_uploads_url(yogastudio_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>