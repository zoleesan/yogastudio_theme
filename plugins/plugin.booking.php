<?php
/* Booking Calendar support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_booking_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_booking_theme_setup', 1 );
	function yogastudio_booking_theme_setup() {
		// Add shortcode in the shortcodes list
		if (yogastudio_exists_booking()) {
			add_action('yogastudio_action_shortcodes_list',				'yogastudio_booking_reg_shortcodes');
			if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
				add_action('yogastudio_action_shortcodes_list_vc',		'yogastudio_booking_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',			'yogastudio_booking_importer_set_options' );
				add_action( 'yogastudio_action_importer_params',			'yogastudio_booking_importer_show_params', 10, 1 );
				add_action( 'yogastudio_action_importer_import',			'yogastudio_booking_importer_import', 10, 2 );
				add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_booking_importer_import_fields', 10, 1 );
				add_action( 'yogastudio_action_importer_export',			'yogastudio_booking_importer_export', 10, 1 );
				add_action( 'yogastudio_action_importer_export_fields',	'yogastudio_booking_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_booking_importer_required_plugins', 10, 2);
			add_filter( 'yogastudio_filter_required_plugins',				'yogastudio_booking_required_plugins' );
		}
	}
}


// Check if Booking Calendar installed and activated
if ( !function_exists( 'yogastudio_exists_booking' ) ) {
	function yogastudio_exists_booking() {
		return function_exists('wp_booking_start_session');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_booking_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_booking_required_plugins');
	function yogastudio_booking_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('booking', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Booking Calendar',
					'slug' 		=> 'wp-booking-calendar',
					'source'	=> yogastudio_get_file_dir('plugins/install/wp-booking-calendar.zip'),
					'required' 	=> false
					);
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'yogastudio_booking_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_booking_importer_required_plugins', 10, 2);
	function yogastudio_booking_importer_required_plugins($not_installed='', $list='') {
	    //global $YOGASTUDIO_GLOBALS;
		//if (in_array('booking', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_booking() )
		if (yogastudio_strpos($list, 'booking')!==false && !yogastudio_exists_booking() )
			$not_installed .= '<br>Booking Calendar';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_booking_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_booking_importer_set_options', 10, 1 );
	function yogastudio_booking_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('booking', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_booking() ) {
			$options['file_with_booking'] = 'demo/booking.txt';			// Name of the file with Booking Calendar data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'yogastudio_booking_importer_show_params' ) ) {
	//add_action( 'yogastudio_action_importer_params',	'yogastudio_booking_importer_show_params', 10, 1 );
	function yogastudio_booking_importer_show_params($importer) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<input type="checkbox" <?php echo in_array('booking', $YOGASTUDIO_GLOBALS['required_plugins']) && $importer->options['plugins_initial_state']
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_booking" id="import_booking" /> <label for="import_booking"><?php esc_html_e('Import Booking Calendar', 'yogastudio'); ?></label><br>
		<?php
	}
}

// Import posts
if ( !function_exists( 'yogastudio_booking_importer_import' ) ) {
	//add_action( 'yogastudio_action_importer_import',	'yogastudio_booking_importer_import', 10, 2 );
	function yogastudio_booking_importer_import($importer, $action) {
		if ( $action == 'import_booking' ) {
			$importer->import_dump('booking', esc_html__('Booking Calendar', 'yogastudio'));
		}
	}
}

// Display import progress
if ( !function_exists( 'yogastudio_booking_importer_import_fields' ) ) {
	//add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_booking_importer_import_fields', 10, 1 );
	function yogastudio_booking_importer_import_fields($importer) {
		?>
		<tr class="import_booking">
			<td class="import_progress_item"><?php esc_html_e('Booking Calendar', 'yogastudio'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}

// Export posts
if ( !function_exists( 'yogastudio_booking_importer_export' ) ) {
	//add_action( 'yogastudio_action_importer_export',	'yogastudio_booking_importer_export', 10, 1 );
	function yogastudio_booking_importer_export($importer) {
		global $YOGASTUDIO_GLOBALS;
		$YOGASTUDIO_GLOBALS['export_booking'] = serialize( array(
			"booking_calendars"		=> $importer->export_dump("booking_calendars"),
			"booking_categories"	=> $importer->export_dump("booking_categories"),
            "booking_config"		=> $importer->export_dump("booking_config"),
            "booking_reservation"	=> $importer->export_dump("booking_reservation"),
            "booking_slots"			=> $importer->export_dump("booking_slots")
            )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'yogastudio_booking_importer_export_fields' ) ) {
	//add_action( 'yogastudio_action_importer_export_fields',	'yogastudio_booking_importer_export_fields', 10, 1 );
	function yogastudio_booking_importer_export_fields($importer) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<tr>
			<th align="left"><?php esc_html_e('Booking', 'yogastudio'); ?></th>
			<td><?php yogastudio_fpc(yogastudio_get_file_dir('core/core.importer/export/booking.txt'), $YOGASTUDIO_GLOBALS['export_booking']); ?>
				<a download="booking.txt" href="<?php echo esc_url(yogastudio_get_file_url('core/core.importer/export/booking.txt')); ?>"><?php esc_html_e('Download', 'yogastudio'); ?></a>
			</td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Return Booking categories list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_get_list_booking_categories' ) ) {
	function yogastudio_get_list_booking_categories($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_booking_cats']))
			$list = $YOGASTUDIO_GLOBALS['list_booking_cats'];
		else {
			$list = array();
			if (yogastudio_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT category_id, category_name FROM " . esc_sql($wpdb->prefix . 'booking_categories') );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->category_id] = $row->category_name;
					}
				}
			}
			$YOGASTUDIO_GLOBALS['list_booking_cats'] = $list = apply_filters('yogastudio_filter_list_booking_categories', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Return Booking calendars list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_get_list_booking_calendars' ) ) {
	function yogastudio_get_list_booking_calendars($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_booking_calendars']))
			$list = $YOGASTUDIO_GLOBALS['list_booking_calendars'];
		else {
			$list = array();
			if (yogastudio_exists_booking()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT cl.calendar_id, cl.calendar_title, ct.category_name FROM " . esc_sql($wpdb->prefix . 'booking_calendars') . " AS cl"
												. " INNER JOIN " . esc_sql($wpdb->prefix . 'booking_categories') . " AS ct ON cl.category_id=ct.category_id"
										);
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->calendar_id] = $row->calendar_title . ' (' . $row->category_name . ')';
					}
				}
			}
			$YOGASTUDIO_GLOBALS['list_booking_calendars'] = $list = apply_filters('yogastudio_filter_list_booking_calendars', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Add shortcode in the shortcodes list
if (!function_exists('yogastudio_booking_reg_shortcodes')) {
	//add_filter('yogastudio_action_shortcodes_list',	'yogastudio_booking_reg_shortcodes');
	function yogastudio_booking_reg_shortcodes() {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['shortcodes'])) {

			$booking_cats = yogastudio_get_list_booking_categories();
			$booking_cals = yogastudio_get_list_booking_calendars();

			$YOGASTUDIO_GLOBALS['shortcodes']['wp_booking_calendar'] = array(
				"title" => esc_html__("Booking Calendar", "yogastudio"),
				"desc" => esc_html__("Insert Booking calendar", "yogastudio"),
				"decorate" => true,
				"container" => false,
				"params" => array(
					"category_id" => array(
						"title" => esc_html__("Category", "yogastudio"),
						"desc" => esc_html__("Select booking category", "yogastudio"),
						"value" => "",
						"type" => "select",
						"options" => yogastudio_array_merge(array(0 => esc_html__('- Select category -', 'yogastudio')), $booking_cats)
					),
					"calendar_id" => array(
						"title" => esc_html__("Calendar", "yogastudio"),
						"desc" => esc_html__("or select booking calendar (id category is empty)", "yogastudio"),
						"dependency" => array(
							'category_id' => array('empty', '0')
						),
						"value" => "",
						"type" => "select",
						"options" => yogastudio_array_merge(array(0 => esc_html__('- Select calendar -', 'yogastudio')), $booking_cals)
					)
				)
			);
		}
	}
}


// Add shortcode in the VC shortcodes list
if (!function_exists('yogastudio_booking_reg_shortcodes_vc')) {
	//add_filter('yogastudio_action_shortcodes_list_vc',	'yogastudio_booking_reg_shortcodes_vc');
	function yogastudio_booking_reg_shortcodes_vc() {

		$booking_cats = yogastudio_get_list_booking_categories();
		$booking_cals = yogastudio_get_list_booking_calendars();


		// YogaStudio Donations form
		vc_map( array(
				"base" => "wp_booking_calendar",
				"name" => esc_html__("Booking Calendar", "yogastudio"),
				"description" => esc_html__("Insert Booking calendar", "yogastudio"),
				"category" => esc_html__('Content', 'yogastudio'),
				'icon' => 'icon_trx_booking',
				"class" => "trx_sc_single trx_sc_booking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "category_id",
						"heading" => esc_html__("Category", "yogastudio"),
						"description" => esc_html__("Select Booking category", "yogastudio"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(yogastudio_array_merge(array(0 => esc_html__('- Select category -', 'yogastudio')), $booking_cats)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "calendar_id",
						"heading" => esc_html__("Calendar", "yogastudio"),
						"description" => esc_html__("Select Booking calendar", "yogastudio"),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'category_id',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(yogastudio_array_merge(array(0 => esc_html__('- Select calendar -', 'yogastudio')), $booking_cals)),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Wp_Booking_Calendar extends YOGASTUDIO_VC_ShortCodeSingle {}

	}
}
?>