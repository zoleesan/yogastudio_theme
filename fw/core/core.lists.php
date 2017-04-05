<?php
/**
 * YogaStudio Framework: return lists
 *
 * @package yogastudio
 * @since yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'yogastudio_get_list_styles' ) ) {
	function yogastudio_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'yogastudio'), $i);
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'yogastudio_get_list_margins' ) ) {
	function yogastudio_get_list_margins($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_margins']))
			$list = $YOGASTUDIO_GLOBALS['list_margins'];
		else {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'yogastudio'),
				'tiny'		=> esc_html__('Tiny',		'yogastudio'),
				'small'		=> esc_html__('Small',		'yogastudio'),
				'medium'	=> esc_html__('Medium',		'yogastudio'),
				'large'		=> esc_html__('Large',		'yogastudio'),
				'huge'		=> esc_html__('Huge',		'yogastudio'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'yogastudio'),
				'small-'	=> esc_html__('Small (negative)',	'yogastudio'),
				'medium-'	=> esc_html__('Medium (negative)',	'yogastudio'),
				'large-'	=> esc_html__('Large (negative)',	'yogastudio'),
				'huge-'		=> esc_html__('Huge (negative)',	'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_margins'] = $list = apply_filters('yogastudio_filter_list_margins', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'yogastudio_get_list_animations' ) ) {
	function yogastudio_get_list_animations($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_animations']))
			$list = $YOGASTUDIO_GLOBALS['list_animations'];
		else {
			$list = array(
				'none'			=> esc_html__('- None -',	'yogastudio'),
				'bounced'		=> esc_html__('Bounced',		'yogastudio'),
				'flash'			=> esc_html__('Flash',		'yogastudio'),
				'flip'			=> esc_html__('Flip',		'yogastudio'),
				'pulse'			=> esc_html__('Pulse',		'yogastudio'),
				'rubberBand'	=> esc_html__('Rubber Band',	'yogastudio'),
				'shake'			=> esc_html__('Shake',		'yogastudio'),
				'swing'			=> esc_html__('Swing',		'yogastudio'),
				'tada'			=> esc_html__('Tada',		'yogastudio'),
				'wobble'		=> esc_html__('Wobble',		'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_animations'] = $list = apply_filters('yogastudio_filter_list_animations', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'yogastudio_get_list_line_styles' ) ) {
	function yogastudio_get_list_line_styles($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_line_styles']))
			$list = $YOGASTUDIO_GLOBALS['list_line_styles'];
		else {
			$list = array(
				'solid'	=> esc_html__('Solid', 'yogastudio'),
				'dashed'=> esc_html__('Dashed', 'yogastudio'),
				'dotted'=> esc_html__('Dotted', 'yogastudio'),
				'double'=> esc_html__('Double', 'yogastudio'),
				'image'	=> esc_html__('Image', 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_line_styles'] = $list = apply_filters('yogastudio_filter_list_line_styles', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'yogastudio_get_list_animations_in' ) ) {
	function yogastudio_get_list_animations_in($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_animations_in']))
			$list = $YOGASTUDIO_GLOBALS['list_animations_in'];
		else {
			$list = array(
				'none'				=> esc_html__('- None -',			'yogastudio'),
				'bounceIn'			=> esc_html__('Bounce In',			'yogastudio'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'yogastudio'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'yogastudio'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'yogastudio'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'yogastudio'),
				'fadeIn'			=> esc_html__('Fade In',			'yogastudio'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'yogastudio'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'yogastudio'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'yogastudio'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'yogastudio'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'yogastudio'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'yogastudio'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'yogastudio'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'yogastudio'),
				'flipInX'			=> esc_html__('Flip In X',			'yogastudio'),
				'flipInY'			=> esc_html__('Flip In Y',			'yogastudio'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'yogastudio'),
				'rotateIn'			=> esc_html__('Rotate In',			'yogastudio'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','yogastudio'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'yogastudio'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'yogastudio'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','yogastudio'),
				'rollIn'			=> esc_html__('Roll In',			'yogastudio'),
				'slideInUp'			=> esc_html__('Slide In Up',		'yogastudio'),
				'slideInDown'		=> esc_html__('Slide In Down',		'yogastudio'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'yogastudio'),
				'slideInRight'		=> esc_html__('Slide In Right',		'yogastudio'),
				'zoomIn'			=> esc_html__('Zoom In',			'yogastudio'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'yogastudio'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'yogastudio'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'yogastudio'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_animations_in'] = $list = apply_filters('yogastudio_filter_list_animations_in', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'yogastudio_get_list_animations_out' ) ) {
	function yogastudio_get_list_animations_out($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_animations_out']))
			$list = $YOGASTUDIO_GLOBALS['list_animations_out'];
		else {
			$list = array(
				'none'				=> esc_html__('- None -',	'yogastudio'),
				'bounceOut'			=> esc_html__('Bounce Out',			'yogastudio'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'yogastudio'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',		'yogastudio'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',		'yogastudio'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'yogastudio'),
				'fadeOut'			=> esc_html__('Fade Out',			'yogastudio'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',			'yogastudio'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'yogastudio'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'yogastudio'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'yogastudio'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',		'yogastudio'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'yogastudio'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'yogastudio'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'yogastudio'),
				'flipOutX'			=> esc_html__('Flip Out X',			'yogastudio'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'yogastudio'),
				'hinge'				=> esc_html__('Hinge Out',			'yogastudio'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',		'yogastudio'),
				'rotateOut'			=> esc_html__('Rotate Out',			'yogastudio'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left',	'yogastudio'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right',		'yogastudio'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',		'yogastudio'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right',	'yogastudio'),
				'rollOut'			=> esc_html__('Roll Out',		'yogastudio'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'yogastudio'),
				'slideOutDown'		=> esc_html__('Slide Out Down',	'yogastudio'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',	'yogastudio'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'yogastudio'),
				'zoomOut'			=> esc_html__('Zoom Out',			'yogastudio'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'yogastudio'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',	'yogastudio'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',	'yogastudio'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',	'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_animations_out'] = $list = apply_filters('yogastudio_filter_list_animations_out', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('yogastudio_get_animation_classes')) {
	function yogastudio_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return yogastudio_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!yogastudio_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of categories
if ( !function_exists( 'yogastudio_get_list_categories' ) ) {
	function yogastudio_get_list_categories($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_categories']))
			$list = $YOGASTUDIO_GLOBALS['list_categories'];
		else {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			$YOGASTUDIO_GLOBALS['list_categories'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'yogastudio_get_list_terms' ) ) {
	function yogastudio_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_taxonomies_'.($taxonomy)]))
			$list = $YOGASTUDIO_GLOBALS['list_taxonomies_'.($taxonomy)];
		else {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = yogastudio_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			$YOGASTUDIO_GLOBALS['list_taxonomies_'.($taxonomy)] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'yogastudio_get_list_posts_types' ) ) {
	function yogastudio_get_list_posts_types($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_posts_types']))
			$list = $YOGASTUDIO_GLOBALS['list_posts_types'];
		else {
			$list = array();
			/* 
			// This way to return all registered post types
			$types = get_post_types();
			if (in_array('post', $types)) $list['post'] = esc_html__('Post', 'yogastudio');
			if (is_array($types) && count($types) > 0) {
				foreach ($types as $t) {
					if ($t == 'post') continue;
					$list[$t] = yogastudio_strtoproper($t);
				}
			}
			*/
			// Return only theme inheritance supported post types
			$YOGASTUDIO_GLOBALS['list_posts_types'] = $list = apply_filters('yogastudio_filter_list_post_types', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'yogastudio_get_list_posts' ) ) {
	function yogastudio_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		global $YOGASTUDIO_GLOBALS;
		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (isset($YOGASTUDIO_GLOBALS[$hash]))
			$list = $YOGASTUDIO_GLOBALS[$hash];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'yogastudio');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			$YOGASTUDIO_GLOBALS[$hash] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'yogastudio_get_list_pages' ) ) {
	function yogastudio_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return yogastudio_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'yogastudio_get_list_users' ) ) {
	function yogastudio_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_users']))
			$list = $YOGASTUDIO_GLOBALS['list_users'];
		else {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'yogastudio');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			$YOGASTUDIO_GLOBALS['list_users'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_get_list_sliders' ) ) {
	function yogastudio_get_list_sliders($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_sliders']))
			$list = $YOGASTUDIO_GLOBALS['list_sliders'];
		else {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'yogastudio')
			);
			$YOGASTUDIO_GLOBALS['list_sliders'] = $list = apply_filters('yogastudio_filter_list_sliders', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_get_list_slider_controls' ) ) {
	function yogastudio_get_list_slider_controls($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_slider_controls']))
			$list = $YOGASTUDIO_GLOBALS['list_slider_controls'];
		else {
			$list = array(
				'no'		=> esc_html__('None', 'yogastudio'),
				'side'		=> esc_html__('Side', 'yogastudio'),
				'bottom'	=> esc_html__('Bottom', 'yogastudio'),
				'pagination'=> esc_html__('Pagination', 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_slider_controls'] = $list = apply_filters('yogastudio_filter_list_slider_controls', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'yogastudio_get_slider_controls_classes' ) ) {
	function yogastudio_get_slider_controls_classes($controls) {
		if (yogastudio_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'yogastudio_get_list_popup_engines' ) ) {
	function yogastudio_get_list_popup_engines($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_popup_engines']))
			$list = $YOGASTUDIO_GLOBALS['list_popup_engines'];
		else {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'yogastudio'),
				"magnific"	=> esc_html__("Magnific popup", 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_popup_engines'] = $list = apply_filters('yogastudio_filter_list_popup_engines', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_menus' ) ) {
	function yogastudio_get_list_menus($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_menus']))
			$list = $YOGASTUDIO_GLOBALS['list_menus'];
		else {
			$list = array();
			$list['default'] = esc_html__("Default", 'yogastudio');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			$YOGASTUDIO_GLOBALS['list_menus'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'yogastudio_get_list_sidebars' ) ) {
	function yogastudio_get_list_sidebars($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_sidebars'])) {
			$list = $YOGASTUDIO_GLOBALS['list_sidebars'];
		} else {
			$list = isset($YOGASTUDIO_GLOBALS['registered_sidebars']) ? $YOGASTUDIO_GLOBALS['registered_sidebars'] : array();
			$YOGASTUDIO_GLOBALS['list_sidebars'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'yogastudio_get_list_sidebars_positions' ) ) {
	function yogastudio_get_list_sidebars_positions($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_sidebars_positions']))
			$list = $YOGASTUDIO_GLOBALS['list_sidebars_positions'];
		else {
			$list = array(
				'none'  => esc_html__('Hide',  'yogastudio'),
				'left'  => esc_html__('Left',  'yogastudio'),
				'right' => esc_html__('Right', 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_sidebars_positions'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'yogastudio_get_sidebar_class' ) ) {
	function yogastudio_get_sidebar_class() {
		$sb_main = yogastudio_get_custom_option('show_sidebar_main');
		$sb_outer = yogastudio_get_custom_option('show_sidebar_outer');
		return (yogastudio_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (yogastudio_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_body_styles' ) ) {
	function yogastudio_get_list_body_styles($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_body_styles']))
			$list = $YOGASTUDIO_GLOBALS['list_body_styles'];
		else {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'yogastudio'),
				'wide'	=> esc_html__('Wide',		'yogastudio')
				);
			if (yogastudio_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'yogastudio');
				$list['fullscreen']	= esc_html__('Fullscreen',	'yogastudio');
			}
			$YOGASTUDIO_GLOBALS['list_body_styles'] = $list = apply_filters('yogastudio_filter_list_body_styles', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return skins list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_skins' ) ) {
	function yogastudio_get_list_skins($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_skins']))
			$list = $YOGASTUDIO_GLOBALS['list_skins'];
		else
			$YOGASTUDIO_GLOBALS['list_skins'] = $list = yogastudio_get_list_folders("skins");
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return css-themes list
if ( !function_exists( 'yogastudio_get_list_themes' ) ) {
	function yogastudio_get_list_themes($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_themes']))
			$list = $YOGASTUDIO_GLOBALS['list_themes'];
		else
			$YOGASTUDIO_GLOBALS['list_themes'] = $list = yogastudio_get_list_files("css/themes");
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_templates' ) ) {
	function yogastudio_get_list_templates($mode='') {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_templates_'.($mode)]))
			$list = $YOGASTUDIO_GLOBALS['list_templates_'.($mode)];
		else {
			$list = array();
			if (is_array($YOGASTUDIO_GLOBALS['registered_templates']) && count($YOGASTUDIO_GLOBALS['registered_templates']) > 0) {
				foreach ($YOGASTUDIO_GLOBALS['registered_templates'] as $k=>$v) {
					if ($mode=='' || yogastudio_strpos($v['mode'], $mode)!==false)
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: yogastudio_strtoproper($v['layout'])
										);
				}
			}
			$YOGASTUDIO_GLOBALS['list_templates_'.($mode)] = $list;
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_templates_blog' ) ) {
	function yogastudio_get_list_templates_blog($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_templates_blog']))
			$list = $YOGASTUDIO_GLOBALS['list_templates_blog'];
		else {
			$list = yogastudio_get_list_templates('blog');
			$YOGASTUDIO_GLOBALS['list_templates_blog'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_templates_blogger' ) ) {
	function yogastudio_get_list_templates_blogger($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_templates_blogger']))
			$list = $YOGASTUDIO_GLOBALS['list_templates_blogger'];
		else {
			$list = yogastudio_array_merge(yogastudio_get_list_templates('blogger'), yogastudio_get_list_templates('blog'));
			$YOGASTUDIO_GLOBALS['list_templates_blogger'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_templates_single' ) ) {
	function yogastudio_get_list_templates_single($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_templates_single']))
			$list = $YOGASTUDIO_GLOBALS['list_templates_single'];
		else {
			$list = yogastudio_get_list_templates('single');
			$YOGASTUDIO_GLOBALS['list_templates_single'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_templates_header' ) ) {
	function yogastudio_get_list_templates_header($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_templates_header']))
			$list = $YOGASTUDIO_GLOBALS['list_templates_header'];
		else {
			$list = yogastudio_get_list_templates('header');
			$YOGASTUDIO_GLOBALS['list_templates_header'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_templates_forms' ) ) {
	function yogastudio_get_list_templates_forms($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_templates_forms']))
			$list = $YOGASTUDIO_GLOBALS['list_templates_forms'];
		else {
			$list = yogastudio_get_list_templates('forms');
			$YOGASTUDIO_GLOBALS['list_templates_forms'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_article_styles' ) ) {
	function yogastudio_get_list_article_styles($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_article_styles']))
			$list = $YOGASTUDIO_GLOBALS['list_article_styles'];
		else {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'yogastudio'),
				"stretch" => esc_html__('Stretch', 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_article_styles'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_post_formats_filters' ) ) {
	function yogastudio_get_list_post_formats_filters($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_post_formats_filters']))
			$list = $YOGASTUDIO_GLOBALS['list_post_formats_filters'];
		else {
			$list = array(
				"no"      => esc_html__('All posts', 'yogastudio'),
				"thumbs"  => esc_html__('With thumbs', 'yogastudio'),
				"reviews" => esc_html__('With reviews', 'yogastudio'),
				"video"   => esc_html__('With videos', 'yogastudio'),
				"audio"   => esc_html__('With audios', 'yogastudio'),
				"gallery" => esc_html__('With galleries', 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_post_formats_filters'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_portfolio_filters' ) ) {
	function yogastudio_get_list_portfolio_filters($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_portfolio_filters']))
			$list = $YOGASTUDIO_GLOBALS['list_portfolio_filters'];
		else {
			$list = array(
				"hide"		=> esc_html__('Hide', 'yogastudio'),
				"tags"		=> esc_html__('Tags', 'yogastudio'),
				"categories"=> esc_html__('Categories', 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_portfolio_filters'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_hovers' ) ) {
	function yogastudio_get_list_hovers($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_hovers']))
			$list = $YOGASTUDIO_GLOBALS['list_hovers'];
		else {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'yogastudio');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'yogastudio');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'yogastudio');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'yogastudio');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'yogastudio');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'yogastudio');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'yogastudio');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'yogastudio');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'yogastudio');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'yogastudio');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'yogastudio');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'yogastudio');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'yogastudio');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'yogastudio');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'yogastudio');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'yogastudio');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'yogastudio');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'yogastudio');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'yogastudio');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'yogastudio');
			$list['square effect1']  = esc_html__('Square Effect 1',  'yogastudio');
			$list['square effect2']  = esc_html__('Square Effect 2',  'yogastudio');
			$list['square effect3']  = esc_html__('Square Effect 3',  'yogastudio');
	//		$list['square effect4']  = esc_html__('Square Effect 4',  'yogastudio');
			$list['square effect5']  = esc_html__('Square Effect 5',  'yogastudio');
			$list['square effect6']  = esc_html__('Square Effect 6',  'yogastudio');
			$list['square effect7']  = esc_html__('Square Effect 7',  'yogastudio');
			$list['square effect8']  = esc_html__('Square Effect 8',  'yogastudio');
			$list['square effect9']  = esc_html__('Square Effect 9',  'yogastudio');
			$list['square effect10'] = esc_html__('Square Effect 10',  'yogastudio');
			$list['square effect11'] = esc_html__('Square Effect 11',  'yogastudio');
			$list['square effect12'] = esc_html__('Square Effect 12',  'yogastudio');
			$list['square effect13'] = esc_html__('Square Effect 13',  'yogastudio');
			$list['square effect14'] = esc_html__('Square Effect 14',  'yogastudio');
			$list['square effect15'] = esc_html__('Square Effect 15',  'yogastudio');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'yogastudio');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'yogastudio');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'yogastudio');
			$list['square effect_more']  = esc_html__('Square Effect More',  'yogastudio');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'yogastudio');
			$YOGASTUDIO_GLOBALS['list_hovers'] = $list = apply_filters('yogastudio_filter_portfolio_hovers', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'yogastudio_get_list_blog_counters' ) ) {
	function yogastudio_get_list_blog_counters($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_blog_counters']))
			$list = $YOGASTUDIO_GLOBALS['list_blog_counters'];
		else {
			$list = array(
				'views'		=> esc_html__('Views', 'yogastudio'),
				'likes'		=> esc_html__('Likes', 'yogastudio'),
				'rating'	=> esc_html__('Rating', 'yogastudio'),
				'comments'	=> esc_html__('Comments', 'yogastudio')
				);
			$YOGASTUDIO_GLOBALS['list_blog_counters'] = $list = apply_filters('yogastudio_filter_list_blog_counters', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'yogastudio_get_list_alter_sizes' ) ) {
	function yogastudio_get_list_alter_sizes($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_alter_sizes']))
			$list = $YOGASTUDIO_GLOBALS['list_alter_sizes'];
		else {
			$list = array(
					'1_1' => esc_html__('1x1', 'yogastudio'),
					'1_2' => esc_html__('1x2', 'yogastudio'),
					'2_1' => esc_html__('2x1', 'yogastudio'),
					'2_2' => esc_html__('2x2', 'yogastudio'),
					'1_3' => esc_html__('1x3', 'yogastudio'),
					'2_3' => esc_html__('2x3', 'yogastudio'),
					'3_1' => esc_html__('3x1', 'yogastudio'),
					'3_2' => esc_html__('3x2', 'yogastudio'),
					'3_3' => esc_html__('3x3', 'yogastudio')
					);
			$YOGASTUDIO_GLOBALS['list_alter_sizes'] = $list = apply_filters('yogastudio_filter_portfolio_alter_sizes', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_hovers_directions' ) ) {
	function yogastudio_get_list_hovers_directions($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_hovers_directions']))
			$list = $YOGASTUDIO_GLOBALS['list_hovers_directions'];
		else {
			$list = array();
			$list['left_to_right'] = esc_html__('Left to Right',  'yogastudio');
			$list['right_to_left'] = esc_html__('Right to Left',  'yogastudio');
			$list['top_to_bottom'] = esc_html__('Top to Bottom',  'yogastudio');
			$list['bottom_to_top'] = esc_html__('Bottom to Top',  'yogastudio');
			$list['scale_up']      = esc_html__('Scale Up',  'yogastudio');
			$list['scale_down']    = esc_html__('Scale Down',  'yogastudio');
			$list['scale_down_up'] = esc_html__('Scale Down-Up',  'yogastudio');
			$list['from_left_and_right'] = esc_html__('From Left and Right',  'yogastudio');
			$list['from_top_and_bottom'] = esc_html__('From Top and Bottom',  'yogastudio');
			$YOGASTUDIO_GLOBALS['list_hovers_directions'] = $list = apply_filters('yogastudio_filter_portfolio_hovers_directions', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'yogastudio_get_list_label_positions' ) ) {
	function yogastudio_get_list_label_positions($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_label_positions']))
			$list = $YOGASTUDIO_GLOBALS['list_label_positions'];
		else {
			$list = array();
			$list['top']	= esc_html__('Top',		'yogastudio');
			$list['bottom']	= esc_html__('Bottom',		'yogastudio');
			$list['left']	= esc_html__('Left',		'yogastudio');
			$list['over']	= esc_html__('Over',		'yogastudio');
			$YOGASTUDIO_GLOBALS['list_label_positions'] = $list = apply_filters('yogastudio_filter_label_positions', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'yogastudio_get_list_bg_image_positions' ) ) {
	function yogastudio_get_list_bg_image_positions($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_bg_image_positions']))
			$list = $YOGASTUDIO_GLOBALS['list_bg_image_positions'];
		else {
			$list = array();
			$list['left top']	  = esc_html__('Left Top', 'yogastudio');
			$list['center top']   = esc_html__("Center Top", 'yogastudio');
			$list['right top']    = esc_html__("Right Top", 'yogastudio');
			$list['left center']  = esc_html__("Left Center", 'yogastudio');
			$list['center center']= esc_html__("Center Center", 'yogastudio');
			$list['right center'] = esc_html__("Right Center", 'yogastudio');
			$list['left bottom']  = esc_html__("Left Bottom", 'yogastudio');
			$list['center bottom']= esc_html__("Center Bottom", 'yogastudio');
			$list['right bottom'] = esc_html__("Right Bottom", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_bg_image_positions'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'yogastudio_get_list_bg_image_repeats' ) ) {
	function yogastudio_get_list_bg_image_repeats($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_bg_image_repeats']))
			$list = $YOGASTUDIO_GLOBALS['list_bg_image_repeats'];
		else {
			$list = array();
			$list['repeat']	  = esc_html__('Repeat', 'yogastudio');
			$list['repeat-x'] = esc_html__('Repeat X', 'yogastudio');
			$list['repeat-y'] = esc_html__('Repeat Y', 'yogastudio');
			$list['no-repeat']= esc_html__('No Repeat', 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_bg_image_repeats'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'yogastudio_get_list_bg_image_attachments' ) ) {
	function yogastudio_get_list_bg_image_attachments($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_bg_image_attachments']))
			$list = $YOGASTUDIO_GLOBALS['list_bg_image_attachments'];
		else {
			$list = array();
			$list['scroll']	= esc_html__('Scroll', 'yogastudio');
			$list['fixed']	= esc_html__('Fixed', 'yogastudio');
			$list['local']	= esc_html__('Local', 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_bg_image_attachments'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'yogastudio_get_list_bg_tints' ) ) {
	function yogastudio_get_list_bg_tints($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_bg_tints']))
			$list = $YOGASTUDIO_GLOBALS['list_bg_tints'];
		else {
			$list = array();
			$list['white']	= esc_html__('White', 'yogastudio');
			$list['light']	= esc_html__('Light', 'yogastudio');
			$list['dark']	= esc_html__('Dark', 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_bg_tints'] = $list = apply_filters('yogastudio_filter_bg_tints', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_field_types' ) ) {
	function yogastudio_get_list_field_types($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_field_types']))
			$list = $YOGASTUDIO_GLOBALS['list_field_types'];
		else {
			$list = array();
			$list['text']     = esc_html__('Text',  'yogastudio');
			$list['textarea'] = esc_html__('Text Area','yogastudio');
			$list['password'] = esc_html__('Password',  'yogastudio');
			$list['radio']    = esc_html__('Radio',  'yogastudio');
			$list['checkbox'] = esc_html__('Checkbox',  'yogastudio');
			$list['select']   = esc_html__('Select',  'yogastudio');
			$list['date']     = esc_html__('Date','yogastudio');
			$list['time']     = esc_html__('Time','yogastudio');
			$list['button']   = esc_html__('Button','yogastudio');
			$YOGASTUDIO_GLOBALS['list_field_types'] = $list = apply_filters('yogastudio_filter_field_types', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'yogastudio_get_list_googlemap_styles' ) ) {
	function yogastudio_get_list_googlemap_styles($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_googlemap_styles']))
			$list = $YOGASTUDIO_GLOBALS['list_googlemap_styles'];
		else {
			$list = array();
			$list['default'] = esc_html__('Default', 'yogastudio');
			$list['simple'] = esc_html__('Simple', 'yogastudio');
			$list['greyscale'] = esc_html__('Greyscale', 'yogastudio');
			$list['greyscale2'] = esc_html__('Greyscale 2', 'yogastudio');
			$list['invert'] = esc_html__('Invert', 'yogastudio');
			$list['dark'] = esc_html__('Dark', 'yogastudio');
			$list['style1'] = esc_html__('Custom style 1', 'yogastudio');
			$list['style2'] = esc_html__('Custom style 2', 'yogastudio');
			$list['style3'] = esc_html__('Custom style 3', 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_googlemap_styles'] = $list = apply_filters('yogastudio_filter_googlemap_styles', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return iconed classes list
if ( !function_exists( 'yogastudio_get_list_icons' ) ) {
	function yogastudio_get_list_icons($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_icons']))
			$list = $YOGASTUDIO_GLOBALS['list_icons'];
		else
			$YOGASTUDIO_GLOBALS['list_icons'] = $list = yogastudio_parse_icons_classes(yogastudio_get_file_dir("css/fontello/css/fontello-codes.css"));
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'yogastudio_get_list_socials' ) ) {
	function yogastudio_get_list_socials($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_socials']))
			$list = $YOGASTUDIO_GLOBALS['list_socials'];
		else
			$YOGASTUDIO_GLOBALS['list_socials'] = $list = yogastudio_get_list_files("images/socials", "png");
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return flags list
if ( !function_exists( 'yogastudio_get_list_flags' ) ) {
	function yogastudio_get_list_flags($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_flags']))
			$list = $YOGASTUDIO_GLOBALS['list_flags'];
		else
			$YOGASTUDIO_GLOBALS['list_flags'] = $list = yogastudio_get_list_files("images/flags", "png");
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'yogastudio_get_list_yesno' ) ) {
	function yogastudio_get_list_yesno($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_yesno']))
			$list = $YOGASTUDIO_GLOBALS['list_yesno'];
		else {
			$list = array();
			$list["yes"] = esc_html__("Yes", 'yogastudio');
			$list["no"]  = esc_html__("No", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_yesno'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'yogastudio_get_list_onoff' ) ) {
	function yogastudio_get_list_onoff($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_onoff']))
			$list = $YOGASTUDIO_GLOBALS['list_onoff'];
		else {
			$list = array();
			$list["on"] = esc_html__("On", 'yogastudio');
			$list["off"] = esc_html__("Off", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_onoff'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'yogastudio_get_list_showhide' ) ) {
	function yogastudio_get_list_showhide($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_showhide']))
			$list = $YOGASTUDIO_GLOBALS['list_showhide'];
		else {
			$list = array();
			$list["show"] = esc_html__("Show", 'yogastudio');
			$list["hide"] = esc_html__("Hide", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_showhide'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'yogastudio_get_list_orderings' ) ) {
	function yogastudio_get_list_orderings($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_orderings']))
			$list = $YOGASTUDIO_GLOBALS['list_orderings'];
		else {
			$list = array();
			$list["asc"] = esc_html__("Ascending", 'yogastudio');
			$list["desc"] = esc_html__("Descending", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_orderings'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'yogastudio_get_list_directions' ) ) {
	function yogastudio_get_list_directions($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_directions']))
			$list = $YOGASTUDIO_GLOBALS['list_directions'];
		else {
			$list = array();
			$list["horizontal"] = esc_html__("Horizontal", 'yogastudio');
			$list["vertical"] = esc_html__("Vertical", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_directions'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'yogastudio_get_list_shapes' ) ) {
	function yogastudio_get_list_shapes($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_shapes']))
			$list = $YOGASTUDIO_GLOBALS['list_shapes'];
		else {
			$list = array();
			$list["round"]  = esc_html__("Round", 'yogastudio');
			$list["square"] = esc_html__("Square", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_shapes'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'yogastudio_get_list_sizes' ) ) {
	function yogastudio_get_list_sizes($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_sizes']))
			$list = $YOGASTUDIO_GLOBALS['list_sizes'];
		else {
			$list = array();
			$list["tiny"]   = esc_html__("Tiny", 'yogastudio');
			$list["small"]  = esc_html__("Small", 'yogastudio');
			$list["medium"] = esc_html__("Medium", 'yogastudio');
			$list["large"]  = esc_html__("Large", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_sizes'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'yogastudio_get_list_floats' ) ) {
	function yogastudio_get_list_floats($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_floats']))
			$list = $YOGASTUDIO_GLOBALS['list_floats'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'yogastudio');
			$list["left"] = esc_html__("Float Left", 'yogastudio');
			$list["right"] = esc_html__("Float Right", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_floats'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'yogastudio_get_list_alignments' ) ) {
	function yogastudio_get_list_alignments($justify=false, $prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_alignments']))
			$list = $YOGASTUDIO_GLOBALS['list_alignments'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'yogastudio');
			$list["left"] = esc_html__("Left", 'yogastudio');
			$list["center"] = esc_html__("Center", 'yogastudio');
			$list["right"] = esc_html__("Right", 'yogastudio');
			if ($justify) $list["justify"] = esc_html__("Justify", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_alignments'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'yogastudio_get_list_sortings' ) ) {
	function yogastudio_get_list_sortings($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_sortings']))
			$list = $YOGASTUDIO_GLOBALS['list_sortings'];
		else {
			$list = array();
			$list["date"] = esc_html__("Date", 'yogastudio');
			$list["title"] = esc_html__("Alphabetically", 'yogastudio');
			$list["views"] = esc_html__("Popular (views count)", 'yogastudio');
			$list["comments"] = esc_html__("Most commented (comments count)", 'yogastudio');
			$list["author_rating"] = esc_html__("Author rating", 'yogastudio');
			$list["users_rating"] = esc_html__("Visitors (users) rating", 'yogastudio');
			$list["random"] = esc_html__("Random", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_sortings'] = $list = apply_filters('yogastudio_filter_list_sortings', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'yogastudio_get_list_columns' ) ) {
	function yogastudio_get_list_columns($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_columns']))
			$list = $YOGASTUDIO_GLOBALS['list_columns'];
		else {
			$list = array();
			$list["none"] = esc_html__("None", 'yogastudio');
			$list["1_1"] = esc_html__("100%", 'yogastudio');
			$list["1_2"] = esc_html__("1/2", 'yogastudio');
			$list["1_3"] = esc_html__("1/3", 'yogastudio');
			$list["2_3"] = esc_html__("2/3", 'yogastudio');
			$list["1_4"] = esc_html__("1/4", 'yogastudio');
			$list["3_4"] = esc_html__("3/4", 'yogastudio');
			$list["1_5"] = esc_html__("1/5", 'yogastudio');
			$list["2_5"] = esc_html__("2/5", 'yogastudio');
			$list["3_5"] = esc_html__("3/5", 'yogastudio');
			$list["4_5"] = esc_html__("4/5", 'yogastudio');
			$list["1_6"] = esc_html__("1/6", 'yogastudio');
			$list["5_6"] = esc_html__("5/6", 'yogastudio');
			$list["1_7"] = esc_html__("1/7", 'yogastudio');
			$list["2_7"] = esc_html__("2/7", 'yogastudio');
			$list["3_7"] = esc_html__("3/7", 'yogastudio');
			$list["4_7"] = esc_html__("4/7", 'yogastudio');
			$list["5_7"] = esc_html__("5/7", 'yogastudio');
			$list["6_7"] = esc_html__("6/7", 'yogastudio');
			$list["1_8"] = esc_html__("1/8", 'yogastudio');
			$list["3_8"] = esc_html__("3/8", 'yogastudio');
			$list["5_8"] = esc_html__("5/8", 'yogastudio');
			$list["7_8"] = esc_html__("7/8", 'yogastudio');
			$list["1_9"] = esc_html__("1/9", 'yogastudio');
			$list["2_9"] = esc_html__("2/9", 'yogastudio');
			$list["4_9"] = esc_html__("4/9", 'yogastudio');
			$list["5_9"] = esc_html__("5/9", 'yogastudio');
			$list["7_9"] = esc_html__("7/9", 'yogastudio');
			$list["8_9"] = esc_html__("8/9", 'yogastudio');
			$list["1_10"]= esc_html__("1/10", 'yogastudio');
			$list["3_10"]= esc_html__("3/10", 'yogastudio');
			$list["7_10"]= esc_html__("7/10", 'yogastudio');
			$list["9_10"]= esc_html__("9/10", 'yogastudio');
			$list["1_11"]= esc_html__("1/11", 'yogastudio');
			$list["2_11"]= esc_html__("2/11", 'yogastudio');
			$list["3_11"]= esc_html__("3/11", 'yogastudio');
			$list["4_11"]= esc_html__("4/11", 'yogastudio');
			$list["5_11"]= esc_html__("5/11", 'yogastudio');
			$list["6_11"]= esc_html__("6/11", 'yogastudio');
			$list["7_11"]= esc_html__("7/11", 'yogastudio');
			$list["8_11"]= esc_html__("8/11", 'yogastudio');
			$list["9_11"]= esc_html__("9/11", 'yogastudio');
			$list["10_11"]= esc_html__("10/11", 'yogastudio');
			$list["1_12"]= esc_html__("1/12", 'yogastudio');
			$list["5_12"]= esc_html__("5/12", 'yogastudio');
			$list["7_12"]= esc_html__("7/12", 'yogastudio');
			$list["10_12"]= esc_html__("10/12", 'yogastudio');
			$list["11_12"]= esc_html__("11/12", 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_columns'] = $list = apply_filters('yogastudio_filter_list_columns', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'yogastudio_get_list_dedicated_locations' ) ) {
	function yogastudio_get_list_dedicated_locations($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_dedicated_locations']))
			$list = $YOGASTUDIO_GLOBALS['list_dedicated_locations'];
		else {
			$list = array();
			$list["default"] = esc_html__('As in the post defined', 'yogastudio');
			$list["center"]  = esc_html__('Above the text of the post', 'yogastudio');
			$list["left"]    = esc_html__('To the left the text of the post', 'yogastudio');
			$list["right"]   = esc_html__('To the right the text of the post', 'yogastudio');
			$list["alter"]   = esc_html__('Alternates for each post', 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_dedicated_locations'] = $list = apply_filters('yogastudio_filter_list_dedicated_locations', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'yogastudio_get_post_format_name' ) ) {
	function yogastudio_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'yogastudio') : esc_html__('galleries', 'yogastudio');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'yogastudio') : esc_html__('videos', 'yogastudio');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'yogastudio') : esc_html__('audios', 'yogastudio');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'yogastudio') : esc_html__('images', 'yogastudio');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'yogastudio') : esc_html__('quotes', 'yogastudio');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'yogastudio') : esc_html__('links', 'yogastudio');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'yogastudio') : esc_html__('statuses', 'yogastudio');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'yogastudio') : esc_html__('asides', 'yogastudio');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'yogastudio') : esc_html__('chats', 'yogastudio');
		else						$name = $single ? esc_html__('standard', 'yogastudio') : esc_html__('standards', 'yogastudio');
		return apply_filters('yogastudio_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'yogastudio_get_post_format_icon' ) ) {
	function yogastudio_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('yogastudio_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'yogastudio_get_list_fonts_styles' ) ) {
	function yogastudio_get_list_fonts_styles($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_fonts_styles']))
			$list = $YOGASTUDIO_GLOBALS['list_fonts_styles'];
		else {
			$list = array();
			$list['i'] = esc_html__('I','yogastudio');
			$list['u'] = esc_html__('U', 'yogastudio');
			$YOGASTUDIO_GLOBALS['list_fonts_styles'] = $list;
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'yogastudio_get_list_fonts' ) ) {
	function yogastudio_get_list_fonts($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_fonts']))
			$list = $YOGASTUDIO_GLOBALS['list_fonts'];
		else {
			$list = array();
			$list = yogastudio_array_merge($list, yogastudio_get_list_font_faces());
			$list['Advent Pro'] = array('family'=>'sans-serif');
			$list['Alegreya Sans'] = array('family'=>'sans-serif');
			$list['Arimo'] = array('family'=>'sans-serif');
			$list['Asap'] = array('family'=>'sans-serif');
			$list['Averia Sans Libre'] = array('family'=>'cursive');
			$list['Averia Serif Libre'] = array('family'=>'cursive');
			$list['Bree Serif'] = array('family'=>'serif',);
			$list['Cabin'] = array('family'=>'sans-serif');
			$list['Cabin Condensed'] = array('family'=>'sans-serif');
			$list['Caudex'] = array('family'=>'serif');
			$list['Comfortaa'] = array('family'=>'cursive');
			$list['Cousine'] = array('family'=>'sans-serif');
			$list['Crimson Text'] = array('family'=>'serif');
			$list['Cuprum'] = array('family'=>'sans-serif');
			$list['Dosis'] = array('family'=>'sans-serif');
			$list['Economica'] = array('family'=>'sans-serif');
			$list['Exo'] = array('family'=>'sans-serif');
			$list['Expletus Sans'] = array('family'=>'cursive');
			$list['Karla'] = array('family'=>'sans-serif');
			$list['Lato'] = array('family'=>'sans-serif');
			$list['Lekton'] = array('family'=>'sans-serif');
			$list['Lobster Two'] = array('family'=>'cursive');
			$list['Maven Pro'] = array('family'=>'sans-serif');
			$list['Merriweather'] = array('family'=>'serif');
			$list['Montserrat'] = array('family'=>'sans-serif');
			$list['Neuton'] = array('family'=>'serif');
			$list['Noticia Text'] = array('family'=>'serif');
			$list['Old Standard TT'] = array('family'=>'serif');
			$list['Open Sans'] = array('family'=>'sans-serif');
			$list['Orbitron'] = array('family'=>'sans-serif');
			$list['Oswald'] = array('family'=>'sans-serif');
			$list['Overlock'] = array('family'=>'cursive');
			$list['Oxygen'] = array('family'=>'sans-serif');
			$list['Philosopher'] = array('family'=>'serif');
			$list['PT Serif'] = array('family'=>'serif');
			$list['Puritan'] = array('family'=>'sans-serif');
			$list['Raleway'] = array('family'=>'sans-serif');
			$list['Roboto'] = array('family'=>'sans-serif');
			$list['Roboto Slab'] = array('family'=>'sans-serif');
			$list['Roboto Condensed'] = array('family'=>'sans-serif');
			$list['Rosario'] = array('family'=>'sans-serif');
			$list['Share'] = array('family'=>'cursive');
			$list['Signika'] = array('family'=>'sans-serif');
			$list['Signika Negative'] = array('family'=>'sans-serif');
			$list['Source Sans Pro'] = array('family'=>'sans-serif');
			$list['Tinos'] = array('family'=>'serif');
			$list['Ubuntu'] = array('family'=>'sans-serif');
			$list['Vollkorn'] = array('family'=>'serif');
			$YOGASTUDIO_GLOBALS['list_fonts'] = $list = apply_filters('yogastudio_filter_list_fonts', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'yogastudio_get_list_font_faces' ) ) {
	function yogastudio_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$list = array();
		$dir = yogastudio_get_folder_dir("css/font-face");
		if ( is_dir($dir) ) {
			$hdir = @ opendir( $dir );
			if ( $hdir ) {
				while (($file = readdir( $hdir ) ) !== false ) {
					$pi = pathinfo( ($dir) . '/' . ($file) );
					if ( substr($file, 0, 1) == '.' || ! is_dir( ($dir) . '/' . ($file) ) )
						continue;
					$css = file_exists( ($dir) . '/' . ($file) . '/' . ($file) . '.css' ) 
						? yogastudio_get_folder_url("css/font-face/".($file).'/'.($file).'.css')
						: (file_exists( ($dir) . '/' . ($file) . '/stylesheet.css' ) 
							? yogastudio_get_folder_url("css/font-face/".($file).'/stylesheet.css')
							: '');
					if ($css != '')
						$list[$file.' ('.esc_html__('uploaded font', 'yogastudio').')'] = array('css' => $css);
				}
				@closedir( $hdir );
			}
		}
		return $list;
	}
}
?>