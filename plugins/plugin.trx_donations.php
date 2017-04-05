<?php
/* YogaStudio Donations support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_trx_donations_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_trx_donations_theme_setup', 1 );
	function yogastudio_trx_donations_theme_setup() {

		// Add shortcode in the shortcodes list
		if (yogastudio_exists_trx_donations()) {
			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('yogastudio_filter_get_blog_type',			'yogastudio_trx_donations_get_blog_type', 9, 2);
			add_filter('yogastudio_filter_get_blog_title',		'yogastudio_trx_donations_get_blog_title', 9, 2);
			add_filter('yogastudio_filter_get_current_taxonomy',	'yogastudio_trx_donations_get_current_taxonomy', 9, 2);
			add_filter('yogastudio_filter_is_taxonomy',			'yogastudio_trx_donations_is_taxonomy', 9, 2);
			add_filter('yogastudio_filter_get_stream_page_title',	'yogastudio_trx_donations_get_stream_page_title', 9, 2);
			add_filter('yogastudio_filter_get_stream_page_link',	'yogastudio_trx_donations_get_stream_page_link', 9, 2);
			add_filter('yogastudio_filter_get_stream_page_id',	'yogastudio_trx_donations_get_stream_page_id', 9, 2);
			add_filter('yogastudio_filter_query_add_filters',		'yogastudio_trx_donations_query_add_filters', 9, 2);
			add_filter('yogastudio_filter_detect_inheritance_key','yogastudio_trx_donations_detect_inheritance_key', 9, 1);
			add_filter('yogastudio_filter_list_post_types',		'yogastudio_trx_donations_list_post_types');
			// Add shortcodes in the list
			add_action('yogastudio_action_shortcodes_list',		'yogastudio_trx_donations_reg_shortcodes');
			if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
				add_action('yogastudio_action_shortcodes_list_vc','yogastudio_trx_donations_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',				'yogastudio_trx_donations_importer_set_options' );
			}
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_trx_donations_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',				'yogastudio_trx_donations_required_plugins' );
		}
	}
}

if ( !function_exists( 'yogastudio_trx_donations_settings_theme_setup2' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_trx_donations_settings_theme_setup2', 3 );
	function yogastudio_trx_donations_settings_theme_setup2() {
		// Add Donations post type and taxonomy into theme inheritance list
		if (yogastudio_exists_trx_donations()) {
			yogastudio_add_theme_inheritance( array('donations' => array(
				'stream_template' => 'blog-donations',
				'single_template' => 'single-donation',
				'taxonomy' => array(YOGASTUDIO_Donations::TAXONOMY),
				'taxonomy_tags' => array(),
				'post_type' => array(YOGASTUDIO_Donations::POST_TYPE),
				'override' => 'page'
				) )
			);
		}
	}
}

// Check if YogaStudio Donations installed and activated
if ( !function_exists( 'yogastudio_exists_trx_donations' ) ) {
	function yogastudio_exists_trx_donations() {
		return class_exists('YOGASTUDIO_Donations');
	}
}


// Return true, if current page is donations page
if ( !function_exists( 'yogastudio_is_trx_donations_page' ) ) {
	function yogastudio_is_trx_donations_page() {
		$is = false;
		if (yogastudio_exists_trx_donations()) {
			global $YOGASTUDIO_GLOBALS;
			$is = in_array($YOGASTUDIO_GLOBALS['page_template'], array('blog-donations', 'single-donation'));
			if (!$is) {
				if (!empty($YOGASTUDIO_GLOBALS['pre_query']))
					$is = ($YOGASTUDIO_GLOBALS['pre_query']->is_single() && $YOGASTUDIO_GLOBALS['pre_query']->get('post_type') == YOGASTUDIO_Donations::POST_TYPE) 
							|| $YOGASTUDIO_GLOBALS['pre_query']->is_post_type_archive(YOGASTUDIO_Donations::POST_TYPE) 
							|| $YOGASTUDIO_GLOBALS['pre_query']->is_tax(YOGASTUDIO_Donations::TAXONOMY);
				else
					$is = (is_single() && get_query_var('post_type') == YOGASTUDIO_Donations::POST_TYPE) 
							|| is_post_type_archive(YOGASTUDIO_Donations::POST_TYPE) 
							|| is_tax(YOGASTUDIO_Donations::TAXONOMY);
			}
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'yogastudio_trx_donations_detect_inheritance_key' ) ) {
	//add_filter('yogastudio_filter_detect_inheritance_key',	'yogastudio_trx_donations_detect_inheritance_key', 9, 1);
	function yogastudio_trx_donations_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return yogastudio_is_trx_donations_page() ? 'donations' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'yogastudio_trx_donations_get_blog_type' ) ) {
	//add_filter('yogastudio_filter_get_blog_type',	'yogastudio_trx_donations_get_blog_type', 9, 2);
	function yogastudio_trx_donations_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax(YOGASTUDIO_Donations::TAXONOMY) || is_tax(YOGASTUDIO_Donations::TAXONOMY))
			$page = 'donations_category';
		else if ($query && $query->get('post_type')==YOGASTUDIO_Donations::POST_TYPE || get_query_var('post_type')==YOGASTUDIO_Donations::POST_TYPE)
			$page = $query && $query->is_single() || is_single() ? 'donations_item' : 'donations';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'yogastudio_trx_donations_get_blog_title' ) ) {
	//add_filter('yogastudio_filter_get_blog_title',	'yogastudio_trx_donations_get_blog_title', 9, 2);
	function yogastudio_trx_donations_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( yogastudio_strpos($page, 'donations')!==false ) {
			if ( $page == 'donations_category' ) {
				$term = get_term_by( 'slug', get_query_var( YOGASTUDIO_Donations::TAXONOMY ), YOGASTUDIO_Donations::TAXONOMY, OBJECT);
				$title = $term->name;
			} else if ( $page == 'donations_item' ) {
				$title = yogastudio_get_post_title();
			} else {
				$title = esc_html__('All donations', 'yogastudio');
			}
		}

		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'yogastudio_trx_donations_get_stream_page_title' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_title',	'yogastudio_trx_donations_get_stream_page_title', 9, 2);
	function yogastudio_trx_donations_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (yogastudio_strpos($page, 'donations')!==false) {
			if (($page_id = yogastudio_trx_donations_get_stream_page_id(0, $page=='donations' ? 'blog-donations' : $page)) > 0)
				$title = yogastudio_get_post_title($page_id);
			else
				$title = esc_html__('All donations', 'yogastudio');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'yogastudio_trx_donations_get_stream_page_id' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_id',	'yogastudio_trx_donations_get_stream_page_id', 9, 2);
	function yogastudio_trx_donations_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (yogastudio_strpos($page, 'donations')!==false) $id = yogastudio_get_template_page_id('blog-donations');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'yogastudio_trx_donations_get_stream_page_link' ) ) {
	//add_filter('yogastudio_filter_get_stream_page_link',	'yogastudio_trx_donations_get_stream_page_link', 9, 2);
	function yogastudio_trx_donations_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (yogastudio_strpos($page, 'donations')!==false) {
			$id = yogastudio_get_template_page_id('blog-donations');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'yogastudio_trx_donations_get_current_taxonomy' ) ) {
	//add_filter('yogastudio_filter_get_current_taxonomy',	'yogastudio_trx_donations_get_current_taxonomy', 9, 2);
	function yogastudio_trx_donations_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( yogastudio_strpos($page, 'donations')!==false ) {
			$tax = YOGASTUDIO_Donations::TAXONOMY;
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'yogastudio_trx_donations_is_taxonomy' ) ) {
	//add_filter('yogastudio_filter_is_taxonomy',	'yogastudio_trx_donations_is_taxonomy', 9, 2);
	function yogastudio_trx_donations_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get(YOGASTUDIO_Donations::TAXONOMY)!='' || is_tax(YOGASTUDIO_Donations::TAXONOMY) ? YOGASTUDIO_Donations::TAXONOMY : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'yogastudio_trx_donations_query_add_filters' ) ) {
	//add_filter('yogastudio_filter_query_add_filters',	'yogastudio_trx_donations_query_add_filters', 9, 2);
	function yogastudio_trx_donations_query_add_filters($args, $filter) {
		if ($filter == 'donations') {
			$args['post_type'] = YOGASTUDIO_Donations::POST_TYPE;
		}
		return $args;
	}
}

// Add custom post type to the list
if ( !function_exists( 'yogastudio_trx_donations_list_post_types' ) ) {
	//add_filter('yogastudio_filter_list_post_types',		'yogastudio_trx_donations_list_post_types');
	function yogastudio_trx_donations_list_post_types($list) {
		$list[YOGASTUDIO_Donations::POST_TYPE] = esc_html__('Donations', 'yogastudio');
		return $list;
	}
}


// Add shortcode in the shortcodes list
if (!function_exists('yogastudio_trx_donations_reg_shortcodes')) {
	//add_filter('yogastudio_action_shortcodes_list',	'yogastudio_trx_donations_reg_shortcodes');
	function yogastudio_trx_donations_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['shortcodes'])) {

			$plugin = YOGASTUDIO_Donations::get_instance();
			$donations_groups = yogastudio_get_list_terms(false, YOGASTUDIO_Donations::TAXONOMY);

			yogastudio_array_insert_before($YOGASTUDIO_GLOBALS['shortcodes'], 'trx_dropcaps', array(

				// YogaStudio Donations form
				"trx_donations_form" => array(
					"title" => esc_html__("Donations form", "yogastudio"),
					"desc" => esc_html__("Insert YogaStudio Donations form", "yogastudio"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "yogastudio"),
							"desc" => esc_html__("Title for the donations form", "yogastudio"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "yogastudio"),
							"desc" => esc_html__("Subtitle for the donations form", "yogastudio"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "yogastudio"),
							"desc" => esc_html__("Short description for the donations form", "yogastudio"),
							"value" => "",
							"type" => "textarea"
						),
						"align" => array(
							"title" => esc_html__("Alignment", "yogastudio"),
							"desc" => esc_html__("Alignment of the donations form", "yogastudio"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['align']
						),
						"account" => array(
							"title" => esc_html__("PayPal account", "yogastudio"),
							"desc" => esc_html__("PayPal account's e-mail. If empty - used from YogaStudio Donations settings", "yogastudio"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"sandbox" => array(
							"title" => esc_html__("Sandbox mode", "yogastudio"),
							"desc" => esc_html__("Use PayPal sandbox to test payments", "yogastudio"),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => "yes",
							"type" => "switch",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['yes_no']
						),
						"amount" => array(
							"title" => esc_html__("Default amount", "yogastudio"),
							"desc" => esc_html__("Specify amount, initially selected in the form", "yogastudio"),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"value" => 5,
							"min" => 1,
							"step" => 5,
							"type" => "spinner"
						),
						"currency" => array(
							"title" => esc_html__("Currency", "yogastudio"),
							"desc" => esc_html__("Select payment's currency", "yogastudio"),
							"dependency" => array(
								'account' => array('not_empty')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => yogastudio_array_merge(array(0 => esc_html__('- Select currency -', 'yogastudio')), $plugin->currency_codes)
						),
						"width" => yogastudio_shortcodes_width(),
						"top" => $YOGASTUDIO_GLOBALS['sc_params']['top'],
						"bottom" => $YOGASTUDIO_GLOBALS['sc_params']['bottom'],
						"left" => $YOGASTUDIO_GLOBALS['sc_params']['left'],
						"right" => $YOGASTUDIO_GLOBALS['sc_params']['right'],
						"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
						"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
						"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
					)
				),
				
				
				// YogaStudio Donations form
				"trx_donations_list" => array(
					"title" => esc_html__("Donations list", "yogastudio"),
					"desc" => esc_html__("Insert YogaStudio Doantions list", "yogastudio"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"title" => array(
							"title" => esc_html__("Title", "yogastudio"),
							"desc" => esc_html__("Title for the donations list", "yogastudio"),
							"value" => "",
							"type" => "text"
						),
						"subtitle" => array(
							"title" => esc_html__("Subtitle", "yogastudio"),
							"desc" => esc_html__("Subtitle for the donations list", "yogastudio"),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => esc_html__("Description", "yogastudio"),
							"desc" => esc_html__("Short description for the donations list", "yogastudio"),
							"value" => "",
							"type" => "textarea"
						),
						"link" => array(
							"title" => esc_html__("Button URL", "yogastudio"),
							"desc" => esc_html__("Link URL for the button at the bottom of the block", "yogastudio"),
							"divider" => true,
							"value" => "",
							"type" => "text"
						),
						"link_caption" => array(
							"title" => esc_html__("Button caption", "yogastudio"),
							"desc" => esc_html__("Caption for the button at the bottom of the block", "yogastudio"),
							"value" => "",
							"type" => "text"
						),
						"style" => array(
							"title" => esc_html__("List style", "yogastudio"),
							"desc" => esc_html__("Select style to display donations", "yogastudio"),
							"value" => "excerpt",
							"type" => "select",
							"options" => array(
								'excerpt' => esc_html__('Excerpt', 'yogastudio')
							)
						),
						"readmore" => array(
							"title" => esc_html__("Read more text", "yogastudio"),
							"desc" => esc_html__("Text of the 'Read more' link", "yogastudio"),
							"value" => esc_html__('Read more', 'yogastudio'),
							"type" => "text"
						),
						"cat" => array(
							"title" => esc_html__("Categories", "yogastudio"),
							"desc" => esc_html__("Select categories (groups) to show donations. If empty - select donations from any category (group) or from IDs list", "yogastudio"),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => yogastudio_array_merge(array(0 => esc_html__('- Select category -', 'yogastudio')), $donations_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of donations", "yogastudio"),
							"desc" => esc_html__("How many donations will be displayed? If used IDs - this parameter ignored.", "yogastudio"),
							"value" => 3,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"columns" => array(
							"title" => esc_html__("Columns", "yogastudio"),
							"desc" => esc_html__("How many columns use to show donations list", "yogastudio"),
							"value" => 3,
							"min" => 2,
							"max" => 6,
							"step" => 1,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "yogastudio"),
							"desc" => esc_html__("Skip posts before select next part.", "yogastudio"),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Donadions order by", "yogastudio"),
							"desc" => esc_html__("Select desired sorting method", "yogastudio"),
							"value" => "date",
							"type" => "select",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['sorting']
						),
						"order" => array(
							"title" => esc_html__("Donations order", "yogastudio"),
							"desc" => esc_html__("Select donations order", "yogastudio"),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => $YOGASTUDIO_GLOBALS['sc_params']['ordering']
						),
						"ids" => array(
							"title" => esc_html__("Donations IDs list", "yogastudio"),
							"desc" => esc_html__("Comma separated list of donations ID. If set - parameters above are ignored!", "yogastudio"),
							"value" => "",
							"type" => "text"
						),
						"top" => $YOGASTUDIO_GLOBALS['sc_params']['top'],
						"bottom" => $YOGASTUDIO_GLOBALS['sc_params']['bottom'],
						"id" => $YOGASTUDIO_GLOBALS['sc_params']['id'],
						"class" => $YOGASTUDIO_GLOBALS['sc_params']['class'],
						"css" => $YOGASTUDIO_GLOBALS['sc_params']['css']
					)
				)

			));
		}
	}
}


// Add shortcode in the VC shortcodes list
if (!function_exists('yogastudio_trx_donations_reg_shortcodes_vc')) {
	//add_filter('yogastudio_action_shortcodes_list_vc',	'yogastudio_trx_donations_reg_shortcodes_vc');
	function yogastudio_trx_donations_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;

		$plugin = YOGASTUDIO_Donations::get_instance();
		$donations_groups = yogastudio_get_list_terms(false, YOGASTUDIO_Donations::TAXONOMY);

		// YogaStudio Donations form
		vc_map( array(
				"base" => "trx_donations_form",
				"name" => esc_html__("Donations form", "yogastudio"),
				"description" => esc_html__("Insert YogaStudio Donations form", "yogastudio"),
				"category" => esc_html__('Content', 'yogastudio'),
				'icon' => 'icon_trx_donations_form',
				"class" => "trx_sc_single trx_sc_donations_form",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "yogastudio"),
						"description" => esc_html__("Title for the donations form", "yogastudio"),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "yogastudio"),
						"description" => esc_html__("Subtitle for the donations form", "yogastudio"),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "yogastudio"),
						"description" => esc_html__("Description for the donations form", "yogastudio"),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "yogastudio"),
						"description" => esc_html__("Alignment of the donations form", "yogastudio"),
						"class" => "",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['align']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "account",
						"heading" => esc_html__("PayPal account", "yogastudio"),
						"description" => esc_html__("PayPal account's e-mail. If empty - used from YogaStudio Donations settings", "yogastudio"),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "sandbox",
						"heading" => esc_html__("Sandbox mode", "yogastudio"),
						"description" => esc_html__("Use PayPal sandbox to test payments", "yogastudio"),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'yogastudio'),
						'dependency' => array(
							'element' => 'account',
							'not_empty' => true
						),
						"class" => "",
						"value" => array("Sandbox mode" => "yes" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "amount",
						"heading" => esc_html__("Default amount", "yogastudio"),
						"description" => esc_html__("Specify amount, initially selected in the form", "yogastudio"),
						"admin_label" => true,
						"group" => esc_html__('PayPal', 'yogastudio'),
						"class" => "",
						"value" => "5",
						"type" => "textfield"
					),
					array(
						"param_name" => "currency",
						"heading" => esc_html__("Currency", "yogastudio"),
						"description" => esc_html__("Select payment's currency", "yogastudio"),
						"class" => "",
						"value" => array_flip(yogastudio_array_merge(array(0 => esc_html__('- Select currency -', 'yogastudio')), $plugin->currency_codes)),
						"type" => "dropdown"
					),
					$YOGASTUDIO_GLOBALS['vc_params']['id'],
					$YOGASTUDIO_GLOBALS['vc_params']['class'],
					$YOGASTUDIO_GLOBALS['vc_params']['css'],
					yogastudio_vc_width(),
					$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_left'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_right']
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_Form extends YOGASTUDIO_VC_ShortCodeSingle {}



		// YogaStudio Donations list
		vc_map( array(
				"base" => "trx_donations_list",
				"name" => esc_html__("Donations list", "yogastudio"),
				"description" => esc_html__("Insert YogaStudio Donations list", "yogastudio"),
				"category" => esc_html__('Content', 'yogastudio'),
				'icon' => 'icon_trx_donations_list',
				"class" => "trx_sc_single trx_sc_donations_list",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "style",
						"heading" => esc_html__("List style", "yogastudio"),
						"description" => esc_html__("Select style to display donations", "yogastudio"),
						"class" => "",
						"value" => array(
							esc_html__('Excerpt', 'yogastudio') => 'excerpt'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "title",
						"heading" => esc_html__("Title", "yogastudio"),
						"description" => esc_html__("Title for the donations form", "yogastudio"),
						"group" => esc_html__('Captions', 'yogastudio'),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "subtitle",
						"heading" => esc_html__("Subtitle", "yogastudio"),
						"description" => esc_html__("Subtitle for the donations form", "yogastudio"),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "description",
						"heading" => esc_html__("Description", "yogastudio"),
						"description" => esc_html__("Description for the donations form", "yogastudio"),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Button URL", "yogastudio"),
						"description" => esc_html__("Link URL for the button at the bottom of the block", "yogastudio"),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "link_caption",
						"heading" => esc_html__("Button caption", "yogastudio"),
						"description" => esc_html__("Caption for the button at the bottom of the block", "yogastudio"),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "readmore",
						"heading" => esc_html__("Read more text", "yogastudio"),
						"description" => esc_html__("Text of the 'Read more' link", "yogastudio"),
						"group" => esc_html__('Captions', 'yogastudio'),
						"class" => "",
						"value" => esc_html__('Read more', 'yogastudio'),
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "yogastudio"),
						"description" => esc_html__("Select category to show donations. If empty - select donations from any category (group) or from IDs list", "yogastudio"),
						"group" => esc_html__('Query', 'yogastudio'),
						"class" => "",
						"value" => array_flip(yogastudio_array_merge(array(0 => esc_html__('- Select category -', 'yogastudio')), $donations_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "yogastudio"),
						"description" => esc_html__("How many columns use to show donations", "yogastudio"),
						"group" => esc_html__('Query', 'yogastudio'),
						"admin_label" => true,
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "yogastudio"),
						"description" => esc_html__("How many posts will be displayed? If used IDs - this parameter ignored.", "yogastudio"),
						"group" => esc_html__('Query', 'yogastudio'),
						"class" => "",
						"value" => "3",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "yogastudio"),
						"description" => esc_html__("Skip posts before select next part.", "yogastudio"),
						"group" => esc_html__('Query', 'yogastudio'),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "yogastudio"),
						"description" => esc_html__("Select desired posts sorting method", "yogastudio"),
						"group" => esc_html__('Query', 'yogastudio'),
						"class" => "",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['sorting']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "yogastudio"),
						"description" => esc_html__("Select desired posts order", "yogastudio"),
						"group" => esc_html__('Query', 'yogastudio'),
						"class" => "",
						"value" => array_flip($YOGASTUDIO_GLOBALS['sc_params']['ordering']),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("client's IDs list", "yogastudio"),
						"description" => esc_html__("Comma separated list of donation's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "yogastudio"),
						"group" => esc_html__('Query', 'yogastudio'),
						'dependency' => array(
							'element' => 'cats',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),

					$YOGASTUDIO_GLOBALS['vc_params']['id'],
					$YOGASTUDIO_GLOBALS['vc_params']['class'],
					$YOGASTUDIO_GLOBALS['vc_params']['css'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_top'],
					$YOGASTUDIO_GLOBALS['vc_params']['margin_bottom']
				)
			) );
			
		class WPBakeryShortCode_Trx_Donations_List extends YOGASTUDIO_VC_ShortCodeSingle {}

	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_trx_donations_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_trx_donations_required_plugins');
	function yogastudio_trx_donations_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('trx_donations', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'YogaStudio Donations',
					'slug' 		=> 'trx_donations',
					'source'	=> yogastudio_get_file_dir('plugins/install/trx_donations.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'yogastudio_trx_donations_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_trx_donations_importer_required_plugins', 10, 2 );
	function yogastudio_trx_donations_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('trx_donations', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_trx_donations() )
		if (yogastudio_strpos($list, 'trx_donations')!==false && !yogastudio_exists_trx_donations() )
			$not_installed .= '<br>YogaStudio Donations';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_trx_donations_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_trx_donations_importer_set_options' );
	function yogastudio_trx_donations_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('trx_donations', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_trx_donations() ) {
			$options['additional_options'][] = 'trx_donations_options';		// Add slugs to export options for this plugin

		}
		return $options;
	}
}
?>