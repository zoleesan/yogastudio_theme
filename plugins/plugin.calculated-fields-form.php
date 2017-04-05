<?php
/* Calculated fields form support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_calcfields_form_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_calcfields_form_theme_setup', 1 );
	function yogastudio_calcfields_form_theme_setup() {
		// Add shortcode in the shortcodes list
		if (yogastudio_exists_calcfields_form()) {
			add_action('yogastudio_action_shortcodes_list',				'yogastudio_calcfields_form_reg_shortcodes');
			if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
				add_action('yogastudio_action_shortcodes_list_vc',		'yogastudio_calcfields_form_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',			'yogastudio_calcfields_form_importer_set_options', 10, 1 );
				add_action( 'yogastudio_action_importer_params',			'yogastudio_calcfields_form_importer_show_params', 10, 1 );
				add_action( 'yogastudio_action_importer_import',			'yogastudio_calcfields_form_importer_import', 10, 2 );
				add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_calcfields_form_importer_import_fields', 10, 1 );
				add_action( 'yogastudio_action_importer_export',			'yogastudio_calcfields_form_importer_export', 10, 1 );
				add_action( 'yogastudio_action_importer_export_fields',	'yogastudio_calcfields_form_importer_export_fields', 10, 1 );
			}
			add_action('wp_enqueue_scripts', 							'yogastudio_calcfields_form_frontend_scripts');
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_calcfields_form_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',				'yogastudio_calcfields_form_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'yogastudio_exists_calcfields_form' ) ) {
	function yogastudio_exists_calcfields_form() {
		return defined('CP_SCHEME');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_calcfields_form_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_calcfields_form_required_plugins');
	function yogastudio_calcfields_form_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('calcfields', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Calculated Fields Form',
					'slug' 		=> 'calculated-fields-form',
					'required' 	=> false
					);
		return $list;
	}
}

// Remove jquery_ui from frontend
if ( !function_exists( 'yogastudio_calcfields_form_frontend_scripts' ) ) {
	//add_action('wp_enqueue_scripts', 'yogastudio_calcfields_form_frontend_scripts');
	function yogastudio_calcfields_form_frontend_scripts() {
		// Disable loading JQuery UI CSS
		//global $wp_styles, $wp_scripts;
		//$wp_styles->done[] = 'cpcff_jquery_ui';
	}
}


// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'yogastudio_calcfields_form_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_calcfields_form_importer_required_plugins', 10, 2 );
	function yogastudio_calcfields_form_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('calcfields', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_calcfields_form() )
		if (yogastudio_strpos($list, 'calcfields')!==false && !yogastudio_exists_calcfields_form() )
			$not_installed .= '<br>Calculated Fields Form';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_calcfields_form_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_calcfields_form_importer_set_options', 10, 1 );
	function yogastudio_calcfields_form_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('calcfields', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_calcfields_form() ) {
			$options['file_with_calcfields_form'] = 'demo/calcfields_form.txt';			// Name of the file with Calculated Fields Form data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'yogastudio_calcfields_form_importer_show_params' ) ) {
	//add_action( 'yogastudio_action_importer_params',	'yogastudio_calcfields_form_importer_show_params', 10, 1 );
	function yogastudio_calcfields_form_importer_show_params($importer) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<input type="checkbox" <?php echo in_array('calcfields', $YOGASTUDIO_GLOBALS['required_plugins']) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_calcfields_form" id="import_calcfields_form" /> <label for="import_calcfields_form"><?php esc_html_e('Import Calculated Fields Form', 'yogastudio'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'yogastudio_calcfields_form_importer_import' ) ) {
	//add_action( 'yogastudio_action_importer_import',	'yogastudio_calcfields_form_importer_import', 10, 2 );
	function yogastudio_calcfields_form_importer_import($importer, $action) {
		if ( $action == 'import_calcfields_form' ) {
			$importer->import_dump('calcfields_form', esc_html__('Calculated Fields Form', 'yogastudio'));
		}
	}
}

// Display import progress
if ( !function_exists( 'yogastudio_calcfields_form_importer_import_fields' ) ) {
	//add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_calcfields_form_importer_import_fields', 10, 1 );
	function yogastudio_calcfields_form_importer_import_fields($importer) {
		?>
		<tr class="import_calcfields_form">
			<td class="import_progress_item"><?php esc_html_e('Calculated Fields Form', 'yogastudio'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'yogastudio_calcfields_form_importer_export' ) ) {
	//add_action( 'yogastudio_action_importer_export',	'yogastudio_calcfields_form_importer_export', 10, 1 );
	function yogastudio_calcfields_form_importer_export($importer) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['export_calcfields_form'] = serialize( array(
			CP_CALCULATEDFIELDSF_FORMS_TABLE => $importer->export_dump(CP_CALCULATEDFIELDSF_FORMS_TABLE)
			)
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'yogastudio_calcfields_form_importer_export_fields' ) ) {
	//add_action( 'yogastudio_action_importer_export_fields',	'yogastudio_calcfields_form_importer_export_fields', 10, 1 );
	function yogastudio_calcfields_form_importer_export_fields($importer) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<tr>
			<th align="left"><?php esc_html_e('Calculated Fields Form', 'yogastudio'); ?></th>
			<td><?php yogastudio_fpc(yogastudio_get_file_dir('core/core.importer/export/calcfields_form.txt'), $YOGASTUDIO_GLOBALS['export_calcfields_form']); ?>
				<a download="calcfields_form.txt" href="<?php echo esc_url(yogastudio_get_file_url('core/core.importer/export/calcfields_form.txt')); ?>"><?php esc_html_e('Download', 'yogastudio'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Calculated forms list list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_get_list_calcfields_form' ) ) {
	function yogastudio_get_list_calcfields_form($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_calcfields_form']))
			$list = $YOGASTUDIO_GLOBALS['list_calcfields_form'];
		else {
			$list = array();
			if (yogastudio_exists_calcfields_form()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, form_name FROM " . esc_sql($wpdb->prefix . CP_CALCULATEDFIELDSF_FORMS_TABLE) );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->form_name;
					}
				}
			}
			$YOGASTUDIO_GLOBALS['list_calcfields_form'] = $list = apply_filters('yogastudio_filter_list_calcfields_form', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Add shortcode in the shortcodes list
if (!function_exists('yogastudio_calcfields_form_reg_shortcodes')) {
	//add_filter('yogastudio_action_shortcodes_list',	'yogastudio_calcfields_form_reg_shortcodes');
	function yogastudio_calcfields_form_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['shortcodes'])) {

			$forms_list = yogastudio_get_list_calcfields_form();

			yogastudio_array_insert_after($YOGASTUDIO_GLOBALS['shortcodes'], 'trx_button', array(

				// Calculated fields form
				'CP_CALCULATED_FIELDS' => array(
					"title" => esc_html__("Calculated fields form", "yogastudio"),
					"desc" => esc_html__("Insert calculated fields form", "yogastudio"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Form ID", "yogastudio"),
							"desc" => esc_html__("Select Form to insert into current page", "yogastudio"),
							"value" => "",
							"size" => "medium",
							"options" => $forms_list,
							"type" => "select"
						)
					)
				)

			));
		}
	}
}


// Add shortcode in the VC shortcodes list
if (!function_exists('yogastudio_calcfields_form_reg_shortcodes_vc')) {
	//add_filter('yogastudio_action_shortcodes_list_vc',	'yogastudio_calcfields_form_reg_shortcodes_vc');
	function yogastudio_calcfields_form_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;

		$forms_list = yogastudio_get_list_calcfields_form();

		// Calculated fields form
		vc_map( array(
				"base" => "CP_CALCULATED_FIELDS",
				"name" => esc_html__("Calculated fields form", "yogastudio"),
				"description" => esc_html__("Insert calculated fields form", "yogastudio"),
				"category" => esc_html__('Content', 'yogastudio'),
				'icon' => 'icon_trx_calcfields',
				"class" => "trx_sc_single trx_sc_calcfields",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Form ID", "yogastudio"),
						"description" => esc_html__("Select Form to insert into current page", "yogastudio"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($forms_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Cp_Calculated_Fields extends YOGASTUDIO_VC_ShortCodeSingle {}

	}
}
?>