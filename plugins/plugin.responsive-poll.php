<?php
/* Responsive Poll support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_responsive_poll_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_responsive_poll_theme_setup', 1 );
	function yogastudio_responsive_poll_theme_setup() {
		// Add shortcode in the shortcodes list
		if (yogastudio_exists_responsive_poll()) {
			add_action('yogastudio_action_shortcodes_list',				'yogastudio_responsive_poll_reg_shortcodes');
			if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
				add_action('yogastudio_action_shortcodes_list_vc',		'yogastudio_responsive_poll_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',			'yogastudio_responsive_poll_importer_set_options', 10, 1 );
				add_action( 'yogastudio_action_importer_params',			'yogastudio_responsive_poll_importer_show_params', 10, 1 );
				add_action( 'yogastudio_action_importer_import',			'yogastudio_responsive_poll_importer_import', 10, 2 );
				add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_responsive_poll_importer_import_fields', 10, 1 );
				add_action( 'yogastudio_action_importer_export',			'yogastudio_responsive_poll_importer_export', 10, 1 );
				add_action( 'yogastudio_action_importer_export_fields',	'yogastudio_responsive_poll_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_responsive_poll_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',				'yogastudio_responsive_poll_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'yogastudio_exists_responsive_poll' ) ) {
	function yogastudio_exists_responsive_poll() {
		return class_exists('Weblator_Polling');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_responsive_poll_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_responsive_poll_required_plugins');
	function yogastudio_responsive_poll_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('responsive_poll', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Responsive Poll',
					'slug' 		=> 'responsive-poll',
					'source'	=> yogastudio_get_file_dir('plugins/install/responsive-poll.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'yogastudio_responsive_poll_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_responsive_poll_importer_required_plugins', 10, 2 );
	function yogastudio_responsive_poll_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('responsive_poll', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_responsive_poll() )
		if (yogastudio_strpos($list, 'responsive_poll')!==false && !yogastudio_exists_responsive_poll() )
			$not_installed .= '<br>Responsive Poll';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_responsive_poll_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_responsive_poll_importer_set_options', 10, 1 );
	function yogastudio_responsive_poll_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('responsive_poll', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_responsive_poll() ) {
			$options['file_with_responsive_poll'] = 'demo/responsive_poll.txt';			// Name of the file with Responsive Poll data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'yogastudio_responsive_poll_importer_show_params' ) ) {
	//add_action( 'yogastudio_action_importer_params',	'yogastudio_responsive_poll_importer_show_params', 10, 1 );
	function yogastudio_responsive_poll_importer_show_params($importer) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<input type="checkbox" <?php echo in_array('responsive_poll', $YOGASTUDIO_GLOBALS['required_plugins']) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_responsive_poll" id="import_responsive_poll" /> <label for="import_responsive_poll"><?php esc_html_e('Import Responsive Poll', 'yogastudio'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'yogastudio_responsive_poll_importer_import' ) ) {
	//add_action( 'yogastudio_action_importer_import',	'yogastudio_responsive_poll_importer_import', 10, 2 );
	function yogastudio_responsive_poll_importer_import($importer, $action) {
		if ( $action == 'import_responsive_poll' ) {
			$importer->import_dump('responsive_poll', esc_html__('Responsive Poll', 'yogastudio'));
		}
	}
}

// Display import progress
if ( !function_exists( 'yogastudio_responsive_poll_importer_import_fields' ) ) {
	//add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_responsive_poll_importer_import_fields', 10, 1 );
	function yogastudio_responsive_poll_importer_import_fields($importer) {
		?>
		<tr class="import_responsive_poll">
			<td class="import_progress_item"><?php esc_html_e('Responsive Poll', 'yogastudio'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'yogastudio_responsive_poll_importer_export' ) ) {
	//add_action( 'yogastudio_action_importer_export',	'yogastudio_responsive_poll_importer_export', 10, 1 );
	function yogastudio_responsive_poll_importer_export($importer) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['export_responsive_poll'] = serialize( array(
			'weblator_polls'		=> $importer->export_dump('weblator_polls'),
			'weblator_poll_options'	=> $importer->export_dump('weblator_poll_options'),
			'weblator_poll_votes'	=> $importer->export_dump('weblator_poll_votes')
			)
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'yogastudio_responsive_poll_importer_export_fields' ) ) {
	//add_action( 'yogastudio_action_importer_export_fields',	'yogastudio_responsive_poll_importer_export_fields', 10, 1 );
	function yogastudio_responsive_poll_importer_export_fields($importer) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<tr>
			<th align="left"><?php esc_html_e('Responsive Poll', 'yogastudio'); ?></th>
			<td><?php yogastudio_fpc(yogastudio_get_file_dir('core/core.importer/export/responsive_poll.txt'), $YOGASTUDIO_GLOBALS['export_responsive_poll']); ?>
				<a download="responsive_poll.txt" href="<?php echo esc_url(yogastudio_get_file_url('core/core.importer/export/responsive_poll.txt')); ?>"><?php esc_html_e('Download', 'yogastudio'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Responsive Pollst list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_get_list_responsive_polls' ) ) {
	function yogastudio_get_list_responsive_polls($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_responsive_polls']))
			$list = $YOGASTUDIO_GLOBALS['list_responsive_polls'];
		else {
			$list = array();
			if (yogastudio_exists_responsive_poll()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, poll_name FROM " . esc_sql($wpdb->prefix . 'weblator_polls') );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->poll_name;
					}
				}
			}
			$YOGASTUDIO_GLOBALS['list_responsive_polls'] = $list = apply_filters('yogastudio_filter_list_responsive_polls', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Add shortcode in the shortcodes list
if (!function_exists('yogastudio_responsive_poll_reg_shortcodes')) {
	//add_filter('yogastudio_action_shortcodes_list',	'yogastudio_responsive_poll_reg_shortcodes');
	function yogastudio_responsive_poll_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['shortcodes'])) {

			$polls_list = yogastudio_get_list_responsive_polls();

			yogastudio_array_insert_before($YOGASTUDIO_GLOBALS['shortcodes'], 'trx_popup', array(

				// Calculated fields form
				'poll' => array(
					"title" => esc_html__("Poll", "yogastudio"),
					"desc" => esc_html__("Insert poll", "yogastudio"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Poll ID", "yogastudio"),
							"desc" => esc_html__("Select Poll to insert into current page", "yogastudio"),
							"value" => "",
							"size" => "medium",
							"options" => $polls_list,
							"type" => "select"
						)
					)
				)

			));
		}
	}
}


// Add shortcode in the VC shortcodes list
if (!function_exists('yogastudio_responsive_poll_reg_shortcodes_vc')) {
	//add_filter('yogastudio_action_shortcodes_list_vc',	'yogastudio_responsive_poll_reg_shortcodes_vc');
	function yogastudio_responsive_poll_reg_shortcodes_vc() {
		global $YOGASTUDIO_GLOBALS;

		$polls_list = yogastudio_get_list_responsive_polls();

		// Calculated fields form
		vc_map( array(
				"base" => "poll",
				"name" => esc_html__("Poll", "yogastudio"),
				"description" => esc_html__("Insert poll", "yogastudio"),
				"category" => esc_html__('Content', 'yogastudio'),
				'icon' => 'icon_trx_poll',
				"class" => "trx_sc_single trx_sc_poll",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Poll ID", "yogastudio"),
						"description" => esc_html__("Select Poll to insert into current page", "yogastudio"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($polls_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Poll extends YOGASTUDIO_VC_ShortCodeSingle {}

	}
}
?>