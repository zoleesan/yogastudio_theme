<?php
/**
 * YogaStudio Framework: Services post type settings
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Theme init
if (!function_exists('yogastudio_services_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_services_theme_setup',1 );
	function yogastudio_services_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('yogastudio_filter_get_blog_type',			'yogastudio_services_get_blog_type', 9, 2);
		add_filter('yogastudio_filter_get_blog_title',		'yogastudio_services_get_blog_title', 9, 2);
		add_filter('yogastudio_filter_get_current_taxonomy',	'yogastudio_services_get_current_taxonomy', 9, 2);
		add_filter('yogastudio_filter_is_taxonomy',			'yogastudio_services_is_taxonomy', 9, 2);
		add_filter('yogastudio_filter_get_stream_page_title',	'yogastudio_services_get_stream_page_title', 9, 2);
		add_filter('yogastudio_filter_get_stream_page_link',	'yogastudio_services_get_stream_page_link', 9, 2);
		add_filter('yogastudio_filter_get_stream_page_id',	'yogastudio_services_get_stream_page_id', 9, 2);
		add_filter('yogastudio_filter_query_add_filters',		'yogastudio_services_query_add_filters', 9, 2);
		add_filter('yogastudio_filter_detect_inheritance_key','yogastudio_services_detect_inheritance_key', 9, 1);

		// Extra column for services lists
		if (yogastudio_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-services_columns',			'yogastudio_post_add_options_column', 9);
			add_filter('manage_services_posts_custom_column',	'yogastudio_post_fill_options_column', 9, 2);
		}

		// Add shortcodes [trx_services] and [trx_services_item]
		add_action('yogastudio_action_shortcodes_list',		'yogastudio_services_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_services_reg_shortcodes_vc');
		
		// Add supported data types
		yogastudio_theme_support_pt('services');
		yogastudio_theme_support_tx('services_group');
	}
}

if ( !function_exists( 'yogastudio_services_settings_theme_setup2' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_services_settings_theme_setup2', 3 );
	function yogastudio_services_settings_theme_setup2() {
		// Add post type 'services' and taxonomy 'services_group' into theme inheritance list
		yogastudio_add_theme_inheritance( array('services' => array(
			'stream_template' => 'blog-services',
			'single_template' => 'single-service',
			'taxonomy' => array('services_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('services'),
			'override' => 'page'
			) )
		);
	}
}



// Return true, if current page is services page
if ( !function_exists( 'yogastudio_is_services_page' ) ) {
	function yogastudio_is_services_page() {
		global $YOGASTUDIO_GLOBALS;
		$is = in_array($YOGASTUDIO_GLOBALS['page_template'], array('blog-services', 'single-service'));
		if (!$is) {
			if (!empty($YOGASTUDIO_GLOBALS['pre_query']))
				$is = $YOGASTUDIO_GLOBALS['pre_query']->get('post_type')=='services' 
						|| $YOGASTUDIO_GLOBALS['pre_query']->is_tax('services_group') 
						|| ($YOGASTUDIO_GLOBALS['pre_query']->is_page() 
								&& ($id=yogastudio_get_template_page_id('blog-services')) > 0 
								&& $id==(isset($YOGASTUDIO_GLOBALS['pre_query']->queried_object_id) 
											? $YOGASTUDIO_GLOBALS['pre_query']->queried_object_id 
											: 0)
						);
			else
				$is = get_query_var('post_type')=='services' 
						|| is_tax('services_group') 
						|| (is_page() && ($id=yogastudio_get_template_page_id('blog-services')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'yogastudio_services_detect_inheritance_key' ) ) {
	//add_filter('yogastudio_filter_detect_inheritance_key',	'yogastudio_services_detect_inheritance_key', 9, 1);
	function yogastudio_services_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return yogastudio_is_services_page() ? 'services' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'yogastudio_services_get_blog_type' ) ) {
	//add_filter('yogastudio_filter_get_blog_type',	'yogastudio_services_get_blog_type', 9, 2);
	function yogastudio_services_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('services_group') || is_tax('services_group'))
			$page = 'services_category';
		else if ($query && $query->get('post_type')=='services' || get_query_var('post_type')=='services')
			$page = $query && $query->is_single() || is_single() ? 'services_item' : 'services';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'yogastudio_services_get_blog_title' ) ) {
	//add_filter('yogastudio_filter_get_blog_title',	'yogastudio_services_get_blog_title', 9, 2);
	function yogastudio_services_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( yogastudio_strpos($page, 'services')!==false ) {
			if ( $page == 'services_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'services_group' ), 'services_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'services_item' ) {
				$title = yogastudio_get_post_title();
			} else {
				$title = esc_html__('All services', 'yogastudio');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'yogastudio_services_get_stream_page_title' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_title',	'yogastudio_services_get_stream_page_title', 9, 2);
	function yogastudio_services_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (yogastudio_strpos($page, 'services')!==false) {
			if (($page_id = yogastudio_services_get_stream_page_id(0, $page=='services' ? 'blog-services' : $page)) > 0)
				$title = yogastudio_get_post_title($page_id);
			else
				$title = esc_html__('All services', 'yogastudio');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'yogastudio_services_get_stream_page_id' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_id',	'yogastudio_services_get_stream_page_id', 9, 2);
	function yogastudio_services_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (yogastudio_strpos($page, 'services')!==false) $id = yogastudio_get_template_page_id('blog-services');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'yogastudio_services_get_stream_page_link' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_link',	'yogastudio_services_get_stream_page_link', 9, 2);
	function yogastudio_services_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (yogastudio_strpos($page, 'services')!==false) {
			$id = yogastudio_get_template_page_id('blog-services');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'yogastudio_services_get_current_taxonomy' ) ) {
	//add_filter('yogastudio_filter_get_current_taxonomy',	'yogastudio_services_get_current_taxonomy', 9, 2);
	function yogastudio_services_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( yogastudio_strpos($page, 'services')!==false ) {
			$tax = 'services_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'yogastudio_services_is_taxonomy' ) ) {
	//add_filter('yogastudio_filter_is_taxonomy',	'yogastudio_services_is_taxonomy', 9, 2);
	function yogastudio_services_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('services_group')!='' || is_tax('services_group') ? 'services_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'yogastudio_services_query_add_filters' ) ) {
	//add_filter('yogastudio_filter_query_add_filters',	'yogastudio_services_query_add_filters', 9, 2);
	function yogastudio_services_query_add_filters($args, $filter) {
		if ($filter == 'services') {
			$args['post_type'] = 'services';
		}
		return $args;
	}
}





// ---------------------------------- [trx_services] ---------------------------------------

/*
[trx_services id="unique_id" columns="4" count="4" style="services-1|services-2|..." title="Block title" subtitle="xxx" description="xxxxxx"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
	[trx_services_item icon="url" title="Item title" description="Item description" link="url" link_caption="Link text"]
[/trx_services]
*/
if ( !function_exists( 'yogastudio_sc_services' ) ) {
	function yogastudio_sc_services($atts, $content=null){	
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "services-1",
			"columns" => 4,
			"slider" => "no",
			"slides_space" => 0,
			"controls" => "no",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"custom" => "no",
			"type" => "icons",	// icons | images
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"orderby" => "date",
			"order" => "desc",
			"readmore" => esc_html__('More info', 'yogastudio'),
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('More info', 'yogastudio'),
			"link" => '',
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		if (empty($id)) $id = "sc_services_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && yogastudio_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
		
		$class .= ($class ? ' ' : '') . yogastudio_get_css_position_as_classes($top, $right, $bottom, $left);

		$ws = yogastudio_get_css_dimensions_from_values($width);
		$hs = yogastudio_get_css_dimensions_from_values('', $height);
		$css .= ($hs) . ($ws);

		$count = max(1, (int) $count);
		$columns = max(1, min(12, (int) $columns));
		if (yogastudio_param_is_off($custom) && $count < $columns) $columns = $count;

		if (yogastudio_param_is_on($slider)) yogastudio_enqueue_slider('swiper');

		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['type'] = $type;
		$YOGASTUDIO_GLOBALS['sc_services_id'] = $id;
		$YOGASTUDIO_GLOBALS['sc_services_style'] = $style;
		$YOGASTUDIO_GLOBALS['sc_services_columns'] = $columns;
		$YOGASTUDIO_GLOBALS['sc_services_counter'] = 0;
		$YOGASTUDIO_GLOBALS['sc_services_slider'] = $slider;
		$YOGASTUDIO_GLOBALS['sc_services_css_wh'] = $ws . $hs;
		$YOGASTUDIO_GLOBALS['sc_services_readmore'] = $readmore;
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_services_wrap'
						. ($scheme && !yogastudio_param_is_off($scheme) && !yogastudio_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_services'
							. ' sc_services_style_'.esc_attr($style)
							. ' sc_services_type_'.esc_attr($type)
							. ' ' . esc_attr(yogastudio_get_template_property($style, 'container_classes'))
							. ' ' . esc_attr(yogastudio_get_slider_controls_classes($controls))
							. (yogastudio_param_is_on($slider)
								? ' sc_slider_swiper swiper-slider-container'
									. (yogastudio_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
									. ($hs ? ' sc_slider_height_fixed' : '')
								: '')
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!empty($width) && yogastudio_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
						. (!empty($height) && yogastudio_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
						. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
						. ($columns > 1 ? ' data-slides-per-view="' . esc_attr($columns) . '"' : '')
						. ($slides_space > 0 ? ' data-slides-space="' . esc_attr($slides_space) . '"' : '')
						. (!yogastudio_param_is_off($animation) ? ' data-animation="'.esc_attr(yogastudio_get_animation_classes($animation)).'"' : '')
					. '>'
					. (!empty($subtitle) ? '<h6 class="sc_services_subtitle sc_item_subtitle">' . trim(yogastudio_strmacros($subtitle)) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_services_title sc_item_title">' . trim(yogastudio_strmacros($title)) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_services_descr sc_item_descr">' . trim(yogastudio_strmacros($description)) . '</div>' : '')
					. (yogastudio_param_is_on($slider) 
						? '<div class="slides swiper-wrapper">' 
						: ($columns > 1 
							? '<div class="sc_columns columns_wrap">' 
							: '')
						);
	
		$content = do_shortcode($content);
	
		if (yogastudio_param_is_on($custom) && $content) {
			$output .= $content;
		} else {
			global $post;
	
			if (!empty($ids)) {
				$posts = explode(',', $ids);
				$count = count($posts);
			}
			
			$args = array(
				'post_type' => 'services',
				'post_status' => 'publish',
				'posts_per_page' => $count,
				'ignore_sticky_posts' => true,
				'order' => $order=='asc' ? 'asc' : 'desc',
				'readmore' => $readmore
			);
		
			if ($offset > 0 && empty($ids)) {
				$args['offset'] = $offset;
			}
		
			$args = yogastudio_query_add_sort_order($args, $orderby, $order);
			$args = yogastudio_query_add_posts_and_cats($args, $ids, 'services', $cat, 'services_group');
			$query = new WP_Query( $args );
	
			$post_number = 0;
				
			while ( $query->have_posts() ) { 
				$query->the_post();
				$post_number++;
				$args = array(
					'layout' => $style,
					'show' => false,
					'number' => $post_number,
					'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
					"descr" => yogastudio_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : '')),
					"orderby" => $orderby,
					'content' => false,
					'terms_list' => false,
					'readmore' => $readmore,
					'tag_type' => $type,
					'columns_count' => $columns,
					'slider' => $slider,
					'tag_id' => $id ? $id . '_' . $post_number : '',
					'tag_class' => '',
					'tag_animation' => '',
					'tag_css' => '',
					'tag_css_wh' => $ws . $hs
				);
				$output .= yogastudio_show_post_layout($args);
			}
			wp_reset_postdata();
		}
	
		if (yogastudio_param_is_on($slider)) {
			$output .= '</div>'
				. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
				. '<div class="sc_slider_pagination_wrap"></div>';
		} else if ($columns > 1) {
			$output .= '</div>';
		}

		$output .=  (!empty($link) ? '<div class="sc_services_button sc_item_button">'.yogastudio_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. '</div><!-- /.sc_services -->'
				. '</div><!-- /.sc_services_wrap -->';
	
		// Add template specific scripts and styles
		do_action('yogastudio_action_blog_scripts', $style);
	
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_services', $atts, $content);
	}
	yogastudio_require_shortcode('trx_services', 'yogastudio_sc_services');
}


if ( !function_exists( 'yogastudio_sc_services_item' ) ) {
	function yogastudio_sc_services_item($atts, $content=null) {
		if (yogastudio_in_shortcode_blogger()) return '';
		extract(yogastudio_html_decode(shortcode_atts( array(
			// Individual params
			"icon" => "",
			"image" => "",
			"title" => "",
			"link" => "",
			"readmore" => "(none)",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => ""
		), $atts)));
		global $YOGASTUDIO_GLOBALS;

		$YOGASTUDIO_GLOBALS['sc_services_counter']++;

		$id = $id ? $id : ($YOGASTUDIO_GLOBALS['sc_services_id'] ? $YOGASTUDIO_GLOBALS['sc_services_id'] . '_' . $YOGASTUDIO_GLOBALS['sc_services_counter'] : '');

		$descr = trim(chop(do_shortcode($content)));
		$readmore = $readmore=='(none)' ? $YOGASTUDIO_GLOBALS['sc_services_readmore'] : $readmore;

		if (!empty($icon)) {
			$type = 'icons';
		} else if (!empty($image)) {
			$type = 'images';
			if ($image > 0) {
				$attach = wp_get_attachment_image_src( $image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$image = $attach[0];
			}
			$thumb_sizes = yogastudio_get_thumb_sizes(array('layout' => $YOGASTUDIO_GLOBALS['sc_services_style']));
			$image = yogastudio_get_resized_image_tag($image, $thumb_sizes['w'], $thumb_sizes['h']);
		}
	
		$post_data = array(
			'post_title' => $title,
			'post_excerpt' => $descr,
			'post_thumb' => $image,
			'post_icon' => $icon,
			'post_link' => $link,
			'post_protected' => false,
			'post_format' => 'standard'
		);
		$args = array(
			'layout' => $YOGASTUDIO_GLOBALS['sc_services_style'],
			'number' => $YOGASTUDIO_GLOBALS['sc_services_counter'],
			'columns_count' => $YOGASTUDIO_GLOBALS['sc_services_columns'],
			'slider' => $YOGASTUDIO_GLOBALS['sc_services_slider'],
			'show' => false,
			'descr'  => -1,		// -1 - don't strip tags, 0 - strip_tags, >0 - strip_tags and truncate string
			'readmore' => $readmore,
			'tag_type' => $YOGASTUDIO_GLOBALS['type'],
			'tag_id' => $id,
			'tag_class' => $class,
			'tag_animation' => $animation,
			'tag_css' => $css,
			'tag_css_wh' => $YOGASTUDIO_GLOBALS['sc_services_css_wh']
		);
		$output = yogastudio_show_post_layout($args, $post_data);
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_services_item', $atts, $content);
	}
	yogastudio_require_shortcode('trx_services_item', 'yogastudio_sc_services_item');
}
// ---------------------------------- [/trx_services] ---------------------------------------



// Add [trx_services] and [trx_services_item] in the shortcodes list
if (!function_exists('yogastudio_services_reg_shortcodes')) {
	//add_filter('yogastudio_action_shortcodes_list',	'yogastudio_services_reg_shortcodes');
	function yogastudio_services_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['shortcodes'])) {

			$services_groups = yogastudio_get_list_terms(false, 'services_group');
			$services_styles = yogastudio_get_list_templates('services');
			$controls 		 = yogastudio_get_list_slider_controls();

			yogastudio_array_insert_after($YOGASTUDIO_GLOBALS['shortcodes'], 'trx_section', array(

				// Services
				"trx_services" => array(
					"title" => esc_html__("Services", "yogastudio"),
					"desc" => wp_kses( __("Insert services list in your page (post)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "yogastudio"),
							"desc" => wp_kses( __("Title for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "yogastudio"),
							"desc" => wp_kses( __("Subtitle for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "yogastudio"),
							"desc" => wp_kses( __("Short description for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "textarea"
						),
						"style" => array(
							"title" => esc_html__("Services style", "yogastudio"),
							"desc" => wp_kses( __("Select style to display services list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "services-1",
							"type" => "select",
							"options" => $services_styles
						),
						"type" => array(
							"title" => esc_html__("Icon's type", "yogastudio"),
							"desc" => wp_kses( __("Select type of icons: font icon or image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "icons",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => array(
								'icons'  => esc_html__('Icons', 'yogastudio'),
								'images' => esc_html__('Images', 'yogastudio')
							)
						),
						"columns" => array(
							"title" => esc_html__("Columns", "yogastudio"),
							"desc" => wp_kses( __("How many columns use to show services list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => 4,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"scheme" => array(
							"title" => esc_html__("Color scheme", "yogastudio"),
							"desc" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "checklist",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['schemes']
						),
						"slider" => array(
							"title" => esc_html__("Slider", "yogastudio"),
							"desc" => wp_kses( __("Use slider to show services", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "no",
							"type" => "switch",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
						),
						"controls" => array(
							"title" => esc_html__("Controls", "yogastudio"),
							"desc" => wp_kses( __("Slider controls style and position", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $controls
						),
						"slides_space" => array(
							"title" => esc_html__("Space between slides", "yogastudio"),
							"desc" => wp_kses( __("Size of space (in px) between slides", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 0,
							"min" => 0,
							"max" => 100,
							"step" => 10,
							"type" => "spinner"
						),
						"interval" => array(
							"title" => esc_html__("Slides change interval", "yogastudio"),
							"desc" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => 7000,
							"step" => 500,
							"min" => 0,
							"type" => "spinner"
						),
						"autoheight" => array(
							"title" => esc_html__("Autoheight", "yogastudio"),
							"desc" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'slider' => array('yes')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
						),
						"align" => array(
							"title" => esc_html__("Alignment", "yogastudio"),
							"desc" => wp_kses( __("Alignment of the services block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
						),
						"custom" => array(
							"title" => esc_html__("Custom", "yogastudio"),
							"desc" => wp_kses( __("Allow get services items from inner shortcodes (custom) or get it from specified group (cat)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"divider" => true,
							"value" => "no",
							"type" => "switch",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
						),
						"cat" => array(
							"title" => esc_html__("Categories", "yogastudio"),
							"desc" => wp_kses( __("Select categories (groups) to show services list. If empty - select services from any category (group) or from IDs list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => yogastudio_array_merge(array(0 => esc_html__('- Select category -', 'yogastudio')), $services_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "yogastudio"),
							"desc" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "yogastudio"),
							"desc" => wp_kses( __("Skip posts before select next part.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "yogastudio"),
							"desc" => wp_kses( __("Select desired posts sorting method", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "title",
							"type" => "select",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Post order", "yogastudio"),
							"desc" => wp_kses( __("Select desired posts order", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "asc",
							"type" => "switch",
							"size" => "big",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "yogastudio"),
							"desc" => wp_kses( __("Comma separated list of posts ID. If set - parameters above are ignored!", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"readmore" => array(
							"title" => esc_html__("Read more", "yogastudio"),
							"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "yogastudio"),
							"desc" => wp_kses( __("Link URL for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "yogastudio"),
							"desc" => wp_kses( __("Caption for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
							"value" => "",
							"type" => "text"
						),
						"width" => yogastudio_shortcodes_width(),
						"height" => yogastudio_shortcodes_height(),
						"top" => $YOGASTUDIO_GLOBALS['sc_params']['top'],
						"bottom" => $YOGASTUDIO_GLOBALS['sc_params']['bottom'],
						"left" => $YOGASTUDIO_GLOBALS['sc_params']['left'],
						"right" => $YOGASTUDIO_GLOBALS['sc_params']['right'],
						"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
						"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
						"animation" => $YOGASTUDIO_GLOBALS['sc_params']['animation'],
						"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
					),
					"children" => array(
						"name" => "trx_services_item",
						"title" => esc_html__("Service item", "yogastudio"),
						"desc" => wp_kses( __("Service item", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"container" => true,
						"params" => array(
							"title" => array(
								"title" => esc_html__("Title", "yogastudio"),
								"desc" => wp_kses( __("Item's title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"icon" => array(
								"title" => esc_html__("Item's icon",  'yogastudio'),
								"desc" => wp_kses( __('Select icon for the item from Fontello icons set',  'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "icons",
								"options" => $YOGASTUDIO_GLOBALS['sc_params']['icons']
							),
							"image" => array(
								"title" => esc_html__("Item's image", "yogastudio"),
								"desc" => wp_kses( __("Item's image (if icon not selected)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
								"dependency" => array(
									'icon' => array('is_empty', 'none')
								),
								"value" => "",
								"readonly" => false,
								"type" => "media"
							),
							"link" => array(
								"title" => esc_html__("Link", "yogastudio"),
								"desc" => wp_kses( __("Link on service's item page", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"value" => "",
								"type" => "text"
							),
							"readmore" => array(
								"title" => esc_html__("Read more", "yogastudio"),
								"desc" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
								"value" => "",
								"type" => "text"
							),
							"_content_" => array(
								"title" => esc_html__("Description", "yogastudio"),
								"desc" => wp_kses( __("Item's short description", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
								"divider" => true,
								"rows" => 4,
								"value" => "",
								"type" => "textarea"
							),
							"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
							"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
							"animation" => $YOGASTUDIO_GLOBALS['sc_params']['animation'],
							"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
						)
					)
				)

			));
		}
	}
}


// Add [trx_services] and [trx_services_item] in the VC shortcodes list
if (!function_exists('yogastudio_services_reg_shortcodes_vc')) {
	//add_filter('yogastudio_action_shortcodes_list_vc',	'yogastudio_services_reg_shortcodes_vc');
	function yogastudio_services_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;

		$services_groups = yogastudio_get_list_terms(false, 'services_group');
		$services_styles = yogastudio_get_list_templates('services');
		$controls		 = yogastudio_get_list_slider_controls();

		// Services
		vc_map( array(
				"base" => "trx_services",
				"name" => esc_html__("Services", "yogastudio"),
				"description" => wp_kses( __("Insert services list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"category" => esc_html__('Content', 'yogastudio'),
				"icon" => 'icon_trx_services',
				"class" => "trx_sc_columns trx_sc_services",
				"content_element" => true,
				"is_container" => true,
				"show_settings_on_create" => true,
				"as_parent" => array('only' => 'trx_services_item'),
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("Services style", "yogastudio"),
						"description" => wp_kses( __("Select style to display services list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"admin_label" => true,
						"value" => array_flip($services_styles),
						"type" => "dropdown"
					),
					array(
						"param_name" => "type",
						"heading" => esc_html__("Icon's type", "yogastudio"),
						"description" => wp_kses( __("Select type of icons: font icon or image", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"admin_label" => true,
						"value" => array(
							esc_html__('Icons', 'yogastudio') => 'icons',
							esc_html__('Images', 'yogastudio') => 'images'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "yogastudio"),
						"description" => wp_kses( __("Select color scheme for this block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['schemes']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slider",
						"heading" => esc_html__("Slider", "yogastudio"),
						"description" => wp_kses( __("Use slider to show services", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'yogastudio'),
						"class" => "",
						"std" => "no",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['yes_no']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "controls",
						"heading" => esc_html__("Controls", "yogastudio"),
						"description" => wp_kses( __("Slider controls style and position", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'yogastudio'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"std" => "no",
						"value" => array_flip($controls),
						"type" => "dropdown"
					),
					array(
						"param_name" => "slides_space",
						"heading" => esc_html__("Space between slides", "yogastudio"),
						"description" => wp_kses( __("Size of space (in px) between slides", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Slider', 'yogastudio'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "interval",
						"heading" => esc_html__("Slides change interval", "yogastudio"),
						"description" => wp_kses( __("Slides change interval (in milliseconds: 1000ms = 1s)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Slider', 'yogastudio'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => "7000",
						"type" => "textfield"
					),
					array(
						"param_name" => "autoheight",
						"heading" => esc_html__("Autoheight", "yogastudio"),
						"description" => wp_kses( __("Change whole slider's height (make it equal current slide's height)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Slider', 'yogastudio'),
						'dependency' => array(
							'element' => 'slider',
							'value' => 'yes'
						),
						"class" => "",
						"value" => array("Autoheight" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "yogastudio"),
						"description" => wp_kses( __("Alignment of the services block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "custom",
						"heading" => esc_html__("Custom", "yogastudio"),
						"description" => wp_kses( __("Allow get services from inner shortcodes (custom) or get it from specified group (cat)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => array("Custom services" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "yogastudio"),
						"description" => wp_kses( __("Title for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "yogastudio"),
						"description" => wp_kses( __("Subtitle for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "yogastudio"),
						"description" => wp_kses( __("Description for the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "yogastudio"),
						"description" => wp_kses( __("Select category to show services. If empty - select services from any category (group) or from IDs list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'yogastudio'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(yogastudio_array_merge(array(0 => esc_html__('- Select category -', 'yogastudio')), $services_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "yogastudio"),
						"description" => wp_kses( __("How many columns use to show services list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'yogastudio'),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "yogastudio"),
						"description" => wp_kses( __("How many posts will be displayed? If used IDs - this parameter ignored.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'yogastudio'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "yogastudio"),
						"description" => wp_kses( __("Skip posts before select next part.", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'yogastudio'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "yogastudio"),
						"description" => wp_kses( __("Select desired posts sorting method", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'yogastudio'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "yogastudio"),
						"description" => wp_kses( __("Select desired posts order", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'yogastudio'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Service's IDs list", "yogastudio"),
						"description" => wp_kses( __("Comma separated list of service's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Query', 'yogastudio'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", "yogastudio"),
						"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "yogastudio"),
						"description" => wp_kses( __("Link URL for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "yogastudio"),
						"description" => wp_kses( __("Caption for the button at the bottom of the block", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					yogastudio_vc_width(),
					yogastudio_vc_height(),
					$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_right'],
					$YOGASTUDIO_GLOBALS['vc_params']['id'],
					$YOGASTUDIO_GLOBALS['vc_params']['class'],
					$YOGASTUDIO_GLOBALS['vc_params']['animation'],
					$YOGASTUDIO_GLOBALS['vc_params']['css']
				),
				'default_content' => '
					[trx_services_item title="' . esc_html__( 'Service item 1', 'yogastudio' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 2', 'yogastudio' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 3', 'yogastudio' ) . '"][/trx_services_item]
					[trx_services_item title="' . esc_html__( 'Service item 4', 'yogastudio' ) . '"][/trx_services_item]
				',
				'js_view' => 'VcTrxColumnsView'
			) );
			
			
		vc_map( array(
				"base" => "trx_services_item",
				"name" => esc_html__("Services item", "yogastudio"),
				"description" => wp_kses( __("Custom services item - all data pull out from shortcode parameters", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"show_settings_on_create" => true,
				"class" => "trx_sc_collection trx_sc_column_item trx_sc_services_item",
				"content_element" => true,
				"is_container" => true,
				'icon' => 'icon_trx_services_item',
				"as_child" => array('only' => 'trx_services'),
				"as_parent" => array('except' => 'trx_services'),
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "yogastudio"),
						"description" => wp_kses( __("Item's title", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "icon",
						"heading" => esc_html__("Icon", "yogastudio"),
						"description" => wp_kses( __("Select icon for the item from Fontello icons set", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => $YOGASTUDIO_GLOBALS['sc_params']['icons'],
						"type" => "dropdown"
					),
					array(
						"param_name" => "image",
						"heading" => esc_html__("Image", "yogastudio"),
						"description" => wp_kses( __("Item's image (if icon is empty)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Link", "yogastudio"),
						"description" => wp_kses( __("Link on item's page", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more", "yogastudio"),
						"description" => wp_kses( __("Caption for the Read more link (if empty - link not showed)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$YOGASTUDIO_GLOBALS['vc_params']['id'],
					$YOGASTUDIO_GLOBALS['vc_params']['class'],
					$YOGASTUDIO_GLOBALS['vc_params']['animation'],
					$YOGASTUDIO_GLOBALS['vc_params']['css']
				),
				'js_view' => 'VcTrxColumnItemView'
			) );
			
		class WPBakeryShortCode_Trx_Services extends YOGASTUDIO_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Services_Item extends YOGASTUDIO_VC_ShortCodeCollection {}

	}
}
?>