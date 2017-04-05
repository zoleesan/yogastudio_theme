<?php
/**
 * YogaStudio Framework: Inherited properties for taxonomies
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Theme init
if (!function_exists('yogastudio_taxonomy_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_taxonomy_theme_setup');
	function yogastudio_taxonomy_theme_setup() {
		$inheritance = yogastudio_get_theme_inheritance();
		if (!empty($inheritance) && is_array($inheritance)) {
			$show_overriden = yogastudio_get_theme_option('show_overriden_taxonomies')=='yes';
			foreach($inheritance as $k=>$v) {
				// Set taxonomy actions
				if (!empty($v['taxonomy']) && is_array($v['taxonomy'])) {
					foreach ($v['taxonomy'] as $tax) {
						// Add the fields to the taxonomy, using our callback function  
						add_action( $tax.'_edit_form_fields',	'yogastudio_taxonomy_show_custom_fields', 10, 1 );  
						add_action( $tax.'_add_form_fields',	'yogastudio_taxonomy_show_custom_fields', 10, 1 );  
						// Save the changes made on the taxonomy, using our callback function  
						add_action( 'edited_'.($tax),			'yogastudio_taxonomy_save_custom_fields', 10, 1 );
						add_action( 'created_'.($tax),			'yogastudio_taxonomy_save_custom_fields', 10, 1 );
						// Extra column for taxonomies lists
						if ($show_overriden) {
							add_filter('manage_edit-'.($tax).'_columns',	'yogastudio_taxonomy_add_options_column', 9);
							add_filter('manage_'.($tax).'_custom_column',	'yogastudio_taxonomy_fill_options_column', 9, 3);
						}
					}
				}
			}
		}
	}
}


/* Extra column for taxonomies lists
-------------------------------------------------------------------------------------------- */

// Create additional column
if (!function_exists('yogastudio_taxonomy_add_options_column')) {
	//add_filter('manage_edit-taxonomy_columns',	'yogastudio_taxonomy_add_options_column', 9);
	function yogastudio_taxonomy_add_options_column( $columns ){
		$columns['theme_options'] = esc_html__('Theme Options', 'yogastudio');
		return $columns;
	}
}

// Fill column with data
if (!function_exists('yogastudio_taxonomy_fill_options_column')) {
	//add_filter('manage_taxonomy_custom_column',	'yogastudio_taxonomy_fill_options_column', 9, 3);
	function yogastudio_taxonomy_fill_options_column($output='', $column_name='', $tax_id=0) {
		if ($column_name != 'theme_options') return;
		if ($props = yogastudio_taxonomy_load_custom_options($tax_id)) {
			global $YOGASTUDIO_GLOBALS;
			$options = '';
			if (is_array($props) && count($props) > 0) {
				foreach ($props as $prop_name=>$prop_value) {
					if (!yogastudio_is_inherit_option($prop_value) && (!isset($YOGASTUDIO_GLOBALS['options'][$prop_name]['type']) || $YOGASTUDIO_GLOBALS['options'][$prop_name]['type']!='hidden')) {
						$prop_title = isset($YOGASTUDIO_GLOBALS['options'][$prop_name]) && !empty($YOGASTUDIO_GLOBALS['options'][$prop_name]['title']) ? $YOGASTUDIO_GLOBALS['options'][$prop_name]['title'] : $prop_name;
						$options .= '<div class="yogastudio_options_prop_row"><span class="yogastudio_options_prop_name">' . esc_html($prop_title) . '</span>&nbsp;=&nbsp;<span class="yogastudio_options_prop_value">' . (is_array($prop_value) ? esc_html__('[Complex Data]', 'yogastudio') : '"' . esc_html(yogastudio_strshort($prop_value, 80)) . '"') . '</span></div>';
					}
				}
			}
		}
		if (!empty($options)) echo '<div class="yogastudio_options_list">'.trim(chop($options)).'</div>';
	}
}


/* Inherited properties for taxonomies
-------------------------------------------------------------------------------------------- */

// Return taxonomy's inherited property value (from parent taxonomies)
if (!function_exists('yogastudio_taxonomy_get_inherited_property')) {
	function yogastudio_taxonomy_get_inherited_property($tax, $id, $prop, $defa='') {
		if ((int) $id == 0) {
			$obj = get_term_by( 'slug', $id, $tax, OBJECT);
			$id = $obj->term_id;
		}
		$val = $defa;
		$tax_obj = get_taxonomy($tax);
		do {
			if ($props = yogastudio_taxonomy_load_custom_options($id, $tax)) {
				if (isset($props[$prop]) && !empty($props[$prop]) && !yogastudio_is_inherit_option($props[$prop])) {
					$val = $props[$prop];
					break;
				}
			}
			if (!$tax_obj->hierarchical) break;
			$obj = get_term_by( 'id', $id, $tax, OBJECT);
			$id = !empty($obj->parent) ? $obj->parent : 0;
		} while ($id);
		return $val;
	}
}

// Return all inherited properties for taxonomy (from parent taxonomies)
if (!function_exists('yogastudio_taxonomy_get_inherited_properties')) {
	function yogastudio_taxonomy_get_inherited_properties($tax, $id) {
		if ((int) $id == 0) {
			$obj = get_term_by( 'slug', $id, $tax, OBJECT);
			$id = $obj->term_id;
		}
		$val = array('taxonomy_id'=>$id);
		$tax_obj = get_taxonomy($tax);
		do {
			if ($props = yogastudio_taxonomy_load_custom_options($id, $tax)) {
				if (is_array($props) && count($props) > 0) {
					foreach ($props as $prop_name=>$prop_value) {
						if (!isset($val[$prop_name]) || empty($val[$prop_name]) || yogastudio_is_inherit_option($val[$prop_name])) {
							$val[$prop_name] = $prop_value;
						}
					}
				}
			}
			if (!$tax_obj->hierarchical) break;
			$obj = get_term_by( 'id', $id, $tax, OBJECT);
			$id = $obj->parent;
		} while ($id);
		return $val;
	}
}

// Return all inherited properties value (from parent categories) for list taxonomies
if (!function_exists('yogastudio_taxonomies_get_inherited_properties')) {
	function yogastudio_taxonomies_get_inherited_properties($tax, $list) {
		$tax_options = array();
		$tax_obj = get_taxonomy($tax);
		if (!empty($list->terms) && is_array($list->terms)) {
			foreach ($list->terms as $obj) {
				$new_options = yogastudio_taxonomy_get_inherited_properties($tax, $obj->term_id);
				if (is_array($new_options) && count($new_options) > 0) {
					foreach ($new_options as $k=>$v) {
						if (!empty($v) && !yogastudio_is_inherit_option($v) && (!isset($tax_options[$k]) || empty($tax_options[$k]) || yogastudio_is_inherit_option($tax_options[$k])))
							$tax_options[$k] = $v;
					}
				}
			}
		}
		return $tax_options;
	}
}


/* Custom fields for taxonomies
-------------------------------------------------------------------------------------------- */

// Add the fields to the "category" taxonomy, using our callback function  
//add_action( 'taxonomy_edit_form_fields', 'yogastudio_taxonomy_show_custom_fields', 10, 1 );  
//add_action( 'taxonomy_add_form_fields', 'yogastudio_taxonomy_show_custom_fields', 10, 1 );  
if (!function_exists('yogastudio_taxonomy_show_custom_fields')) {
	function yogastudio_taxonomy_show_custom_fields($tax_obj = null) {  
		global $YOGASTUDIO_GLOBALS;
		?>  
		<table border="0" cellpadding="0" cellspacing="0" class="form-table">
		<tr class="form-field" valign="top">  
			<td span="2">
		<div class="section section-info ">
			<h3 class="heading"><?php esc_html_e('Custom settings for this taxonomy (and nested):', 'yogastudio'); ?></h3>
			<div class="option">
				<div class="controls">
					<div class="info">
						<?php esc_html_e('Select parameters for showing posts from this taxonomy and all nested taxonomies.', 'yogastudio'); ?><br />
						<?php esc_html_e('Attention: In each nested taxonomy you can override this settings.', 'yogastudio'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php 
		$tax_type = is_object($tax_obj) ? $tax_obj->taxonomy : $tax_obj;
		$override_key = yogastudio_get_override_key($tax_type, 'taxonomy');

		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_taxonomy_nonce" value="'.esc_attr($YOGASTUDIO_GLOBALS['admin_nonce']).'" />';
		echo '<input type="hidden" name="meta_box_taxonomy_type" value="'.esc_attr($tax_type).'" />';
	
		$custom_options = yogastudio_taxonomy_load_custom_options($tax_obj, $tax_type);

		do_action('yogastudio_action_taxonomy_before_show_meta_box', $tax_type, $tax_obj);

		yogastudio_options_page_start(array(
			'data' => $YOGASTUDIO_GLOBALS['options'],
			'add_inherit' => true,
			'create_form' => false,
			'buttons' => array('import', 'export'),
			'override' => $override_key
			));
	
		if (count($YOGASTUDIO_GLOBALS['options']) > 0) {
			foreach ($YOGASTUDIO_GLOBALS['options'] as $id=>$option) { 
				if (!isset($option['override']) || !in_array($override_key, explode(',', $option['override']))) continue;
	
				$option = apply_filters('yogastudio_filter_taxonomy_show_custom_field_option', $option, $id, $tax_type, $tax_obj);
				$meta = isset($custom_options[$id]) ? apply_filters('yogastudio_filter_taxonomy_show_custom_field_value', $custom_options[$id], $option, $id, $tax_type, $tax_obj) : '';

				do_action('yogastudio_action_taxonomy_before_show_custom_field', $tax_type, $tax_obj, $option, $id, $meta);
	
				yogastudio_options_show_field($id, $option, $meta);

				do_action('yogastudio_action_taxonomy_after_show_custom_field', $tax_type, $tax_obj, $option, $id, $meta);
			}
		}
	
		yogastudio_options_page_stop();

		do_action('yogastudio_action_taxonomy_after_show_meta_box', $tax_type, $tax_obj);
		?>
			</td>
		</tr>
		</table>
		<?php
	} 
}


  
// Save the changes made on the taxonomy, using our callback function  
//add_action( 'edited_taxonomy', 'yogastudio_taxonomy_save_custom_fields', 10, 1 );
//add_action( 'created_taxonomy', 'yogastudio_taxonomy_save_custom_fields', 10, 1 );
if (!function_exists('yogastudio_taxonomy_save_custom_fields')) {
	function yogastudio_taxonomy_save_custom_fields( $term_id=0 ) {  
		global $YOGASTUDIO_GLOBALS;

		// verify nonce
		if (!isset($_POST['meta_box_taxonomy_nonce']) || !wp_verify_nonce($_POST['meta_box_taxonomy_nonce'], $YOGASTUDIO_GLOBALS['admin_url'])) {
			return $term_id;
		}
		
		$tax_type = isset($_POST['meta_box_taxonomy_type']) ? $_POST['meta_box_taxonomy_type'] : 'category';
		$override_key = yogastudio_get_override_key($tax_type, 'taxonomy');

		$custom_options = yogastudio_taxonomy_load_custom_options($term_id, $tax_type);

		if (yogastudio_options_merge_new_values($YOGASTUDIO_GLOBALS['options'], $custom_options, $_POST, 'save', $override_key))
			yogastudio_taxonomy_save_custom_options($term_id, $tax_type, $custom_options);
	}
}

// Get taxonomy custom fields
if (!function_exists('yogastudio_taxonomy_load_custom_options')) {
	function yogastudio_taxonomy_load_custom_options($tax_obj, $tax_type = '') {  
		$t_id = is_object($tax_obj) ? $tax_obj->term_id : $tax_obj; 					// Get the ID of the term you're editing
		if ((int) $t_id == 0) {
			$tax_obj = get_term_by( 'slug', $t_id, $tax_type, OBJECT);
			$t_id = $tax_obj!==false ? $tax_obj->term_id : 0;
		}
		global $YOGASTUDIO_GLOBALS;
		return apply_filters('yogastudio_filter_taxonomy_load_custom_options', $t_id ? get_option( $YOGASTUDIO_GLOBALS['theme_slug'] . "_options_taxonomy_{$t_id}" ) : false, $tax_type, $tax_obj); 
	}
}

// Set taxonomy custom fields
if (!function_exists('yogastudio_taxonomy_save_custom_options')) {
	function yogastudio_taxonomy_save_custom_options($term_id, $tax_type, $term_meta) {  
		update_option( "yogastudio_options_taxonomy_{$term_id}", apply_filters('yogastudio_filter_taxonomy_save_custom_options', $term_meta, $tax_type, $term_id) );  
	}
}
?>