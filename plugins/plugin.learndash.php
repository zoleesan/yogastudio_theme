<?php
/* LearnDash LMS support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_learndash_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_learndash_theme_setup', 1 );
	function yogastudio_learndash_theme_setup() {

		// Add shortcode in the shortcodes list
		if (yogastudio_exists_learndash()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('yogastudio_filter_get_blog_type',			'yogastudio_learndash_get_blog_type', 9, 2);
			add_filter('yogastudio_filter_get_blog_title',		'yogastudio_learndash_get_blog_title', 9, 2);
			add_filter('yogastudio_filter_get_current_taxonomy',	'yogastudio_learndash_get_current_taxonomy', 9, 2);
			add_filter('yogastudio_filter_is_taxonomy',			'yogastudio_learndash_is_taxonomy', 9, 2);
			add_filter('yogastudio_filter_get_stream_page_title',	'yogastudio_learndash_get_stream_page_title', 9, 2);
			add_filter('yogastudio_filter_get_stream_page_link',	'yogastudio_learndash_get_stream_page_link', 9, 2);
			add_filter('yogastudio_filter_get_stream_page_id',	'yogastudio_learndash_get_stream_page_id', 9, 2);
			add_filter('yogastudio_filter_query_add_filters',		'yogastudio_learndash_query_add_filters', 9, 2);
			add_filter('yogastudio_filter_detect_inheritance_key','yogastudio_learndash_detect_inheritance_key', 9, 1);

			// One-click importer support
			add_filter( 'yogastudio_filter_importer_options',		'yogastudio_learndash_importer_set_options' );

			// Add shortcodes in the list
			//add_action('yogastudio_action_shortcodes_list',		'yogastudio_learndash_reg_shortcodes');
			//if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			//	add_action('yogastudio_action_shortcodes_list_vc','yogastudio_learndash_reg_shortcodes_vc');

			// Get list post_types and taxonomies
			global $YOGASTUDIO_GLOBALS;
			$YOGASTUDIO_GLOBALS['learndash_post_types'] = array('sfwd-courses', 'sfwd-lessons', 'sfwd-quiz', 'sfwd-topic', 'sfwd-certificates', 'sfwd-transactions');
			$YOGASTUDIO_GLOBALS['learndash_taxonomies'] = array('category');
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_learndash_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',				'yogastudio_learndash_required_plugins' );
		}
	}
}

// Attention! Add action on 'init' instead 'before_init_theme' because LearnDash add post_types and taxonomies on this action
if ( !function_exists( 'yogastudio_learndash_settings_theme_setup2' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_learndash_settings_theme_setup2', 3 );
	//add_action( 'init', 'yogastudio_learndash_settings_theme_setup2', 20 );
	function yogastudio_learndash_settings_theme_setup2() {
		// Add LearnDash post type and taxonomy into theme inheritance list
		if (yogastudio_exists_learndash()) {
			global $YOGASTUDIO_GLOBALS;
			// Get list post_types and taxonomies
			if (!empty(SFWD_CPT_Instance::$instances) && count(SFWD_CPT_Instance::$instances) > 0) {
				$post_types = array();
				foreach (SFWD_CPT_Instance::$instances as $pt=>$data)
					$post_types[] = $pt;
				if (count($post_types) > 0)
					$YOGASTUDIO_GLOBALS['learndash_post_types'] = $post_types;
			}
			// Add in the inheritance list
			yogastudio_add_theme_inheritance( array('learndash' => array(
				'stream_template' => 'blog-learndash',
				'single_template' => 'single-learndash',
				'taxonomy' => $YOGASTUDIO_GLOBALS['learndash_taxonomies'],
				'taxonomy_tags' => array('post_tag'),
				'post_type' => $YOGASTUDIO_GLOBALS['learndash_post_types'],
				'override' => 'page'
				) )
			);
		}
	}
}



// Check if YogaStudio Donations installed and activated
if ( !function_exists( 'yogastudio_exists_learndash' ) ) {
	function yogastudio_exists_learndash() {
		return class_exists('SFWD_LMS');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'yogastudio_is_learndash_page' ) ) {
	function yogastudio_is_learndash_page() {
		$is = false;
		if (yogastudio_exists_learndash()) {
			global $YOGASTUDIO_GLOBALS;
			$is = in_array($YOGASTUDIO_GLOBALS['page_template'], array('blog-learndash', 'single-learndash'));
			if (!$is) {
				$is = !empty($YOGASTUDIO_GLOBALS['pre_query'])
							? $YOGASTUDIO_GLOBALS['pre_query']->is_single() && in_array($YOGASTUDIO_GLOBALS['pre_query']->get('post_type'), $YOGASTUDIO_GLOBALS['learndash_post_types'])
							: is_single() && in_array(get_query_var('post_type'), $YOGASTUDIO_GLOBALS['learndash_post_types']);
			}
			if (!$is) {
				if (count($YOGASTUDIO_GLOBALS['learndash_post_types']) > 0) {
					foreach ($YOGASTUDIO_GLOBALS['learndash_post_types'] as $pt) {
						if (!empty($YOGASTUDIO_GLOBALS['pre_query']) ? $YOGASTUDIO_GLOBALS['pre_query']->is_post_type_archive($pt) : is_post_type_archive($pt)) {
							$is = true;
							break;
						}
					}
				}
			}
			if (!$is) {
				if (count($YOGASTUDIO_GLOBALS['learndash_taxonomies']) > 0) {
					foreach ($YOGASTUDIO_GLOBALS['learndash_taxonomies'] as $pt) {
						if (!empty($YOGASTUDIO_GLOBALS['pre_query']) ? $YOGASTUDIO_GLOBALS['pre_query']->is_tax($pt) : is_tax($pt)) {
							$is = true;
							break;
						}
					}
				}
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'yogastudio_learndash_detect_inheritance_key' ) ) {
	//add_filter('yogastudio_filter_detect_inheritance_key',	'yogastudio_learndash_detect_inheritance_key', 9, 1);
	function yogastudio_learndash_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return yogastudio_is_learndash_page() ? 'learndash' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'yogastudio_learndash_get_blog_type' ) ) {
	//add_filter('yogastudio_filter_get_blog_type',	'yogastudio_learndash_get_blog_type', 9, 2);
	function yogastudio_learndash_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		global $YOGASTUDIO_GLOBALS;
		if (count($YOGASTUDIO_GLOBALS['learndash_taxonomies']) > 0) {
			foreach ($YOGASTUDIO_GLOBALS['learndash_taxonomies'] as $pt) {
				if ($query && $query->is_tax($pt) || is_tax($pt)) {
					$page = 'learndash_'.$pt;
					break;
				}
			}
		}
		if (empty($page)) {
			$pt = $query ? $query->get('post_type') : get_query_var('post_type');
			if (in_array($pt, $YOGASTUDIO_GLOBALS['learndash_post_types'])) {
				$page = $query && $query->is_single() || is_single() ? 'learndash_item' : 'learndash';
			}
		}
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'yogastudio_learndash_get_blog_title' ) ) {
	//add_filter('yogastudio_filter_get_blog_title',	'yogastudio_learndash_get_blog_title', 9, 2);
	function yogastudio_learndash_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( yogastudio_strpos($page, 'learndash')!==false ) {
			if ( $page == 'learndash_item' ) {
				$title = yogastudio_get_post_title();
			} else if ( yogastudio_strpos($page, 'learndash_')!==false ) {
				$parts = explode('_', $page);
				$term = get_term_by( 'slug', get_query_var( $parts[1] ), $parts[1], OBJECT);
				$title = $term->name;
			} else {
				$title = esc_html__('All courses', 'yogastudio');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'yogastudio_learndash_get_stream_page_title' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_title',	'yogastudio_learndash_get_stream_page_title', 9, 2);
	function yogastudio_learndash_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (yogastudio_strpos($page, 'learndash')!==false) {
			if (($page_id = yogastudio_learndash_get_stream_page_id(0, $page=='learndash' ? 'blog-learndash' : $page)) > 0)
				$title = yogastudio_get_post_title($page_id);
			else
				$title = esc_html__('All courses', 'yogastudio');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'yogastudio_learndash_get_stream_page_id' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_id',	'yogastudio_learndash_get_stream_page_id', 9, 2);
	function yogastudio_learndash_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (yogastudio_strpos($page, 'learndash')!==false) $id = yogastudio_get_template_page_id('blog-learndash');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'yogastudio_learndash_get_stream_page_link' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_link',	'yogastudio_learndash_get_stream_page_link', 9, 2);
	function yogastudio_learndash_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (yogastudio_strpos($page, 'learndash')!==false) {
			$id = yogastudio_get_template_page_id('blog-learndash');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'yogastudio_learndash_get_current_taxonomy' ) ) {
	//add_filter('yogastudio_filter_get_current_taxonomy',	'yogastudio_learndash_get_current_taxonomy', 9, 2);
	function yogastudio_learndash_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( yogastudio_strpos($page, 'learndash')!==false ) {
			global $YOGASTUDIO_GLOBALS;
			if (count($YOGASTUDIO_GLOBALS['learndash_taxonomies']) > 0) {
				$tax = $YOGASTUDIO_GLOBALS['learndash_taxonomies'][0];
			}
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'yogastudio_learndash_is_taxonomy' ) ) {
	//add_filter('yogastudio_filter_is_taxonomy',	'yogastudio_learndash_is_taxonomy', 9, 2);
	function yogastudio_learndash_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else {
			global $YOGASTUDIO_GLOBALS;
			if (count($YOGASTUDIO_GLOBALS['learndash_taxonomies']) > 0) {
				foreach ($YOGASTUDIO_GLOBALS['learndash_taxonomies'] as $pt) {
					if ($query && ($query->get($pt)!='' || $query->is_tax($pt)) || is_tax($pt)) {
						$tax = $pt;
						break;
					}
				}
			}
			return $tax;
		}
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'yogastudio_learndash_query_add_filters' ) ) {
	//add_filter('yogastudio_filter_query_add_filters',	'yogastudio_learndash_query_add_filters', 9, 2);
	function yogastudio_learndash_query_add_filters($args, $filter) {
		if ($filter == 'learndash') {
			//global $YOGASTUDIO_GLOBALS;
			$args['post_type'] = 'sfwd-courses';	//$YOGASTUDIO_GLOBALS['learndash_post_types'];
		}
		return $args;
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_learndash_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_learndash_required_plugins');
	function yogastudio_learndash_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('learndash', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'LearnDash LMS',
					'slug' 		=> 'sfwd-lms',
					'source'	=> yogastudio_get_file_dir('plugins/install/sfwd-lms.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'yogastudio_learndash_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_learndash_importer_required_plugins', 10, 2 );
	function yogastudio_learndash_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('learndash', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_learndash() )
		if (yogastudio_strpos($list, 'learndash')!==false && !yogastudio_exists_learndash() )
			$not_installed .= '<br>LearnDash LMS';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_learndash_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_learndash_importer_set_options' );
	function yogastudio_learndash_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('learndash', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_learndash() ) {
			$options['additional_options'][] = 'sfwd_cpt_options';		// Add slugs to export options for this plugin
		}
		return $options;
	}
}
?>