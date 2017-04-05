<?php
/**
 * YogaStudio Framework: templates and thumbs management
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('yogastudio_templates_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_templates_theme_setup' );
	function yogastudio_templates_theme_setup() {

		// Add custom thumb sizes into media manager
		add_filter( 'image_size_names_choose', 'yogastudio_show_thumb_sizes');
	}
}



/* Templates
-------------------------------------------------------------------------------- */

// Add template (layout name)
// $tpl = array( 
//		'layout' => 'layout_name',
//		'template' => 'template_file_name',		// If empty - use 'layout' name
//		'body_style' => 'required_body_style',	// If not empty - use instead current body_style
//		'need_content' => true|false,			// true - for single posts or if template need prepared full content of the posts, else - false
//		'need_terms' => true|false,				// true - for single posts or if template need prepared terms list (categories, tags, product_cat, etc.), else - false
//		'need_columns' => true|false,			// true - if template need columns wrapper for horizontal direction, else - false
//		'need_isotope' => true|false,			// true - if template need isotope wrapper, else - false
//		'container' => '',						// Additional elements container (if need) for single post and blog streampage. For example: <div class="addit_wrap">%s</div>
//		'container_classes' => '',				// or additional classes for existing elements container
//		'mode'   => 'blog|single|widgets|blogger|internal',
//		'title'  => 'Layout title',
//		'thumb_title' => 'Thumb title',			// If empty - don't show in the thumbs list (and not add image size)
//		'thumb_slug' => 'Thumb slug',			// Optional. If empty - use Thumb title as slug
//		'w'      => width,
//		'h'      => height (null if no crop, but only scale),
//		'h_crop' => cropped height (optional),
//		);
// $tpl = array('layout' => 'excerpt', 'mode' => 'blog', 'title'=>'Excerpt', 'thumb_title'=>'Medium image size', 'w' => 720, 'h' => 460, 'h_crop' => 460);
// $tpl = array('layout' => 'fullpost', 'mode' => 'blog,single', 'title'=>'Fullwidth post', 'thumb_title'=>'Large image', 'w' => 1150, 'h' => null, 'h_crop' => 720);
// $tpl = array('layout' => 'accordion', 'mode' => 'blogger', 'title'=>'Accordion');
if (!function_exists('yogastudio_add_template')) {
	function yogastudio_add_template($tpl) {
		global $YOGASTUDIO_GLOBALS;
		if (empty($tpl['mode']))						$tpl['mode'] = 'blog';
		if (empty($tpl['template']))					$tpl['template'] = $tpl['layout'];
		if (empty($tpl['need_content']))				$tpl['need_content'] = false;
		if (empty($tpl['need_terms']))					$tpl['need_terms'] = false;
		if (empty($tpl['need_columns']))				$tpl['need_columns'] = false;
		if (empty($tpl['need_isotope']))				$tpl['need_isotope'] = false;
		if (!isset($tpl['h_crop']) && isset($tpl['h']))	$tpl['h_crop'] = $tpl['h'];
		if (!isset($YOGASTUDIO_GLOBALS['registered_templates'])) $YOGASTUDIO_GLOBALS['registered_templates'] = array();
		$YOGASTUDIO_GLOBALS['registered_templates'][$tpl['layout']] = $tpl;
		if (!empty($tpl['thumb_title']) || !empty($tpl['thumb_slug']))
			yogastudio_add_thumb_sizes( $tpl );
		else 
			$tpl['thumb_title'] = '';
	}
}

// Return template file name
if (!function_exists('yogastudio_get_template_name')) {
	function yogastudio_get_template_name($layout_name) {
		global $YOGASTUDIO_GLOBALS;
		return $YOGASTUDIO_GLOBALS['registered_templates'][$layout_name]['template'];
	}
}

// Return true, if template required content
if (!function_exists('yogastudio_get_template_property')) {
	function yogastudio_get_template_property($layout_name, $what) {
		global $YOGASTUDIO_GLOBALS;
		return !empty($YOGASTUDIO_GLOBALS['registered_templates'][$layout_name][$what]) ? $YOGASTUDIO_GLOBALS['registered_templates'][$layout_name][$what] : '';
	}
}

// Return template output function name
if (!function_exists('yogastudio_get_template_function_name')) {
	function yogastudio_get_template_function_name($layout_name) {
		global $YOGASTUDIO_GLOBALS;
		return 'yogastudio_template_'.str_replace(array('-', '.'), '_', $YOGASTUDIO_GLOBALS['registered_templates'][$layout_name]['template']).'_output';
	}
}


/* Thumbs
-------------------------------------------------------------------------------- */

// Add image dimensions with layout name
// $sizes = array( 
//		'layout' => 'layout_name',
//		'thumb_slug' => 'Thumb title',	// Optional. If omitted - use thumb_title
//		'thumb_title' => 'Thumb title',
//		'w'      => width,
//		'h'      => height (null if no crop, but only scale),
//		'h_crop' => cropped height,
//		'add_image_size" => true
//		);
// $sizes = array('layout' => 'excerpt',  'thumb_title'=>'Medium image', 'w' => 720, 'h' => 460, 'h_crop' => 460);
// $sizes = array('layout' => 'fullpost', 'thumb_title'=>'Large image', 'w' => 1150, 'h' => null, 'h_crop' => 720);
if (!function_exists('yogastudio_add_thumb_sizes')) {
	function yogastudio_add_thumb_sizes($sizes) {
		global $YOGASTUDIO_GLOBALS;
		static $mult = 0;
		if ($mult == 0) $mult = min(4, max(1, yogastudio_get_theme_option("retina_ready")));
		if (!isset($sizes['h_crop']))		$sizes['h_crop'] =  isset($sizes['h']) ? $sizes['h'] : null;
		//if (empty($sizes['mode']))		$sizes['mode'] = 'blog';
		if (empty($sizes['thumb_title']))	$sizes['thumb_title'] = yogastudio_strtoproper(!empty($sizes['layout']) ? $sizes['layout'] : $sizes['thumb_slug']);
		$thumb_slug = !empty($sizes['thumb_slug']) ? $sizes['thumb_slug'] : yogastudio_get_slug($sizes['thumb_title']);
		if (empty($sizes['layout']))		$sizes['layout'] = $thumb_slug;
		if (empty($YOGASTUDIO_GLOBALS['thumb_sizes'][$thumb_slug])) {
			if (empty($YOGASTUDIO_GLOBALS['thumb_sizes'])) $YOGASTUDIO_GLOBALS['thumb_sizes'] = array();
			$YOGASTUDIO_GLOBALS['thumb_sizes'][$thumb_slug] = $sizes;
			if (!isset($sizes['add_image_size']) || $sizes['add_image_size']) {
				add_image_size( 'yogastudio-'.$thumb_slug, $sizes['w'], $sizes['h'], $sizes['h']!=null );
				if ($mult > 1)
					add_image_size( 'yogastudio-'.$thumb_slug.'-@retina', $sizes['w'] ? $sizes['w']*$mult : $sizes['w'], $sizes['h'] ? $sizes['h']*$mult : $sizes['h'], $sizes['h']!=null );
			}
			if ($sizes['h']!=$sizes['h_crop']) {
				// Uncomment this lines, if you want create separate entry for the cropped sizes (optional)
				//$sizes['h']=$sizes['h_crop'];
				//$sizes['title'] .= esc_html__(' (cropped)', 'yogastudio');
				//$YOGASTUDIO_GLOBALS['thumb_sizes'][$sizes['layout'].'_crop'] = $sizes;
				if (!isset($sizes['add_image_size']) || $sizes['add_image_size']) {
					add_image_size( 'yogastudio-'.$thumb_slug.'_crop', $sizes['w'], $sizes['h_crop'], true );
					if ($mult > 1) 
						add_image_size( 'yogastudio-'.$thumb_slug.'_crop-@retina', $sizes['w'] ? $sizes['w']*$mult : $sizes['w'], $sizes['h_crop'] ? $sizes['h_crop']*$mult : $sizes['h_crop'], true );
				}
			}
		}
	}
}

// Return image dimensions
if (!function_exists('yogastudio_get_thumb_sizes')) {
	function yogastudio_get_thumb_sizes($opt) {
		$opt = array_merge(array(
			'layout' => 'excerpt',
			'thumb_slug' => ''
		), $opt);
		global $YOGASTUDIO_GLOBALS;
		$thumb_slug = !empty($opt['thumb_slug']) 
						? $opt['thumb_slug'] 
						: (empty($YOGASTUDIO_GLOBALS['registered_templates'][$opt['layout']]['thumb_slug']) 
							? (empty($YOGASTUDIO_GLOBALS['registered_templates'][$opt['layout']]['thumb_title']) 
								? '' 
								: yogastudio_get_slug($YOGASTUDIO_GLOBALS['registered_templates'][$opt['layout']]['thumb_title'])
								) 
							: yogastudio_get_slug($YOGASTUDIO_GLOBALS['registered_templates'][$opt['layout']]['thumb_slug'])
							);
		return !empty($thumb_slug) && isset($YOGASTUDIO_GLOBALS['thumb_sizes'][$thumb_slug]) 
				? $YOGASTUDIO_GLOBALS['thumb_sizes'][$thumb_slug] 
				: array('w'=>null, 'h'=>null, 'h_crop'=>null);
	}
}

// Show custom thumb sizes into media manager sizes list
if (!function_exists('yogastudio_show_thumb_sizes')) {
	function yogastudio_show_thumb_sizes( $sizes ) {
		global $YOGASTUDIO_GLOBALS;
		$thumb_sizes = $YOGASTUDIO_GLOBALS['thumb_sizes'];
		if (is_array($thumb_sizes) && count($thumb_sizes) > 0) {
			$rez = array();
			foreach ($thumb_sizes as $k=>$v)
				$rez[$k] = !empty($v['thumb_title']) ? $v['thumb_title'] : $k;
			$sizes = array_merge( $sizes, $rez);
		}
		return $sizes;
	}
}

// AJAX callback: Get attachment url
if ( !function_exists( 'yogastudio_callback_get_attachment_url' ) ) {
	function yogastudio_callback_get_attachment_url() {
		global $_REQUEST, $YOGASTUDIO_GLOBALS;
		
		if ( !wp_verify_nonce( $_REQUEST['nonce'], $YOGASTUDIO_GLOBALS['ajax_url'] ) )
			die();
	
		$response = array('error'=>'');
		
		$id = (int) $_REQUEST['attachment_id'];
		
		$response['data'] = wp_get_attachment_url($id);
		
		echo json_encode($response);
		die();
	}
}
?>