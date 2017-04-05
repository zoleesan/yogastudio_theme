<?php
/* Revolution Slider support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('yogastudio_revslider_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_revslider_theme_setup', 1 );
	function yogastudio_revslider_theme_setup() {
		if (yogastudio_exists_revslider()) {
			add_filter( 'yogastudio_filter_list_sliders',					'yogastudio_revslider_list_sliders' );
			add_filter( 'yogastudio_filter_shortcodes_params',			'yogastudio_revslider_shortcodes_params' );
			add_filter( 'yogastudio_filter_theme_options_params',			'yogastudio_revslider_theme_options_params' );
			if (is_admin()) {
				add_filter( 'yogastudio_filter_importer_options',			'yogastudio_revslider_importer_set_options' );
				add_action( 'yogastudio_action_importer_params',			'yogastudio_revslider_importer_show_params', 10, 1 );
				add_action( 'yogastudio_action_importer_clear_tables',	'yogastudio_revslider_importer_clear_tables', 10, 2 );
				add_action( 'yogastudio_action_importer_import',			'yogastudio_revslider_importer_import', 10, 2 );
				add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_revslider_importer_import_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_revslider_importer_required_plugins', 10, 2 );
			add_filter( 'yogastudio_filter_required_plugins',				'yogastudio_revslider_required_plugins' );
		}
	}
}

if ( !function_exists( 'yogastudio_revslider_settings_theme_setup2' ) ) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_revslider_settings_theme_setup2', 3 );
	function yogastudio_revslider_settings_theme_setup2() {
		if (yogastudio_exists_revslider()) {

			// Add Revslider specific options in the Theme Options
			global $YOGASTUDIO_GLOBALS;

			yogastudio_array_insert_after($YOGASTUDIO_GLOBALS['options'], 'slider_engine', array(
		
				"slider_alias" => array(
					"title" => esc_html__('Revolution Slider: Select slider',  'yogastudio'),
					"desc" => wp_kses( __("Select slider to show (if engine=revo in the field above)", 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
					"override" => "category,services_group,page",
					"dependency" => array(
						'show_slider' => array('yes'),
						'slider_engine' => array('revo')
					),
					"std" => "",
					"options" => $YOGASTUDIO_GLOBALS['options_params']['list_revo_sliders'],
					"type" => "select")
				
				)
			);

		}
	}
}

// Check if RevSlider installed and activated
if ( !function_exists( 'yogastudio_exists_revslider' ) ) {
	function yogastudio_exists_revslider() {
		return function_exists('rev_slider_shortcode');
		//return class_exists('RevSliderFront');
		//return is_plugin_active('revslider/revslider.php');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'yogastudio_revslider_required_plugins' ) ) {
	//add_filter('yogastudio_filter_required_plugins',	'yogastudio_revslider_required_plugins');
	function yogastudio_revslider_required_plugins($list=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if (in_array('revslider', $YOGASTUDIO_GLOBALS['required_plugins']))
			$list[] = array(
					'name' 		=> 'Revolution Slider',
					'slug' 		=> 'revslider',
					'source'	=> yogastudio_get_file_dir('plugins/install/revslider.zip'),
					'required' 	=> false
				);

		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check RevSlider in the required plugins
if ( !function_exists( 'yogastudio_revslider_importer_required_plugins' ) ) {
	//add_filter( 'yogastudio_filter_importer_required_plugins',	'yogastudio_revslider_importer_required_plugins', 10, 2 );
	function yogastudio_revslider_importer_required_plugins($not_installed='', $list='') {
		//global $YOGASTUDIO_GLOBALS;
		//if (in_array('revslider', $YOGASTUDIO_GLOBALS['required_plugins']) && !yogastudio_exists_revslider() )
		if (yogastudio_strpos($list, 'revslider')!==false && !yogastudio_exists_revslider() )
			$not_installed .= '<br>Revolution Slider';
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'yogastudio_revslider_importer_set_options' ) ) {
	//add_filter( 'yogastudio_filter_importer_options',	'yogastudio_revslider_importer_set_options', 10, 1 );
	function yogastudio_revslider_importer_set_options($options=array()) {
	    global $YOGASTUDIO_GLOBALS;
		if ( in_array('revslider', $YOGASTUDIO_GLOBALS['required_plugins']) && yogastudio_exists_revslider() ) {
			$options['folder_with_revsliders'] = 'demo/revslider';			// Name of the folder with Revolution slider data
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'yogastudio_revslider_importer_show_params' ) ) {
	//add_action( 'yogastudio_action_importer_params',	'yogastudio_revslider_importer_show_params', 10, 1 );
	function yogastudio_revslider_importer_show_params($importer) {
		global $YOGASTUDIO_GLOBALS;
		?>
		<input type="checkbox" <?php echo in_array('revslider', $YOGASTUDIO_GLOBALS['required_plugins']) && $importer->options['plugins_initial_state'] 
											? 'checked="checked"' 
											: ''; ?> value="1" name="import_revslider" id="import_revslider" /> <label for="import_revslider"><?php esc_html_e('Import Revolution Sliders', 'yogastudio'); ?></label><br>
		<?php
	}
}

// Clear tables
if ( !function_exists( 'yogastudio_revslider_importer_clear_tables' ) ) {
	//add_action( 'yogastudio_action_importer_clear_tables',	'yogastudio_revslider_importer_clear_tables', 10, 2 );
	function yogastudio_revslider_importer_clear_tables($importer, $clear_tables) {
		if (yogastudio_strpos($clear_tables, 'revslider')!==false && $importer->last_slider==0) {
			if ($importer->options['debug']) dfl(esc_html__('Clear Revolution Slider tables', 'yogastudio'));
			global $wpdb;
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_sliders");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_sliders".', 'yogastudio' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_slides");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_slides".', 'yogastudio' ) . ' ' . ($res->get_error_message()) );
			$res = $wpdb->query("TRUNCATE TABLE " . esc_sql($wpdb->prefix) . "revslider_static_slides");
			if ( is_wp_error( $res ) ) dfl( esc_html__( 'Failed truncate table "revslider_static_slides".', 'yogastudio' ) . ' ' . ($res->get_error_message()) );
		}
	}
}

// Import posts
if ( !function_exists( 'yogastudio_revslider_importer_import' ) ) {
	//add_action( 'yogastudio_action_importer_import',	'yogastudio_revslider_importer_import', 10, 2 );
	function yogastudio_revslider_importer_import($importer, $action) {
		if ( $action == 'import_revslider' ) {
			if (file_exists(WP_PLUGIN_DIR.'/revslider/revslider.php')) {
				require_once WP_PLUGIN_DIR.'/revslider/revslider.php';
				$dir = yogastudio_get_folder_dir($importer->options['folder_with_revsliders']);
				if ( is_dir($dir) ) {
					$hdir = @opendir( $dir );
					if ( $hdir ) {
						if ($importer->options['debug']) dfl( esc_html__('Import Revolution sliders', 'yogastudio') );
						// Collect files with sliders
						$sliders = array();
						while (($file = readdir( $hdir ) ) !== false ) {
							$pi = pathinfo( ($dir) . '/' . ($file) );
							if ( substr($file, 0, 1) == '.' || is_dir( ($dir) . '/' . ($file) ) || $pi['extension']!='zip' )
								continue;
							$sliders[] = array('name' => $file, 'path' => ($dir) . '/' . ($file));
						}
						@closedir( $hdir );
						// Process next slider
						$slider = new RevSlider();
						for ($i=0; $i<count($sliders); $i++) {
							if ($i+1 <= $importer->last_slider) continue;
							if ($importer->options['debug']) dfl( sprintf(esc_html__('Process slider "%s"', 'yogastudio'), $sliders[$i]['name']) );
							if (!is_array($_FILES)) $_FILES = array();
							$_FILES["import_file"] = array("tmp_name" => $sliders[$i]['path']);
							$response = $slider->importSliderFromPost();
							if ($response["success"] == false) {
								$msg = sprintf(esc_html__('Revolution Slider "%s" import error', 'yogastudio'), $sliders[$i]['name']);
								$importer->response['error'] = $msg;
								dfl( $msg );
								dfo( $response );
							} else {
								if ($importer->options['debug']) dfl( sprintf(esc_html__('Slider "%s" imported', 'yogastudio'), $sliders[$i]['name']) );
							}
							break;
						}
						// Write last slider into log
						yogastudio_fpc($importer->import_log, $i+1 < count($sliders) ? '0|100|'.($i+1) : '');
						$importer->response['result'] = min(100, round(($i+1) / count($sliders) * 100));
					}
				}
			} else {
				dfl( sprintf(esc_html__('Can not locate plugin Revolution Slider: %s', 'yogastudio'), WP_PLUGIN_DIR.'/revslider/revslider.php') );
			}
		}
	}
}

// Display import progress
if ( !function_exists( 'yogastudio_revslider_importer_import_fields' ) ) {
	//add_action( 'yogastudio_action_importer_import_fields',	'yogastudio_revslider_importer_import_fields', 10, 1 );
	function yogastudio_revslider_importer_import_fields($importer) {
		?>
		<tr class="import_revslider">
			<td class="import_progress_item"><?php esc_html_e('Revolution Slider', 'yogastudio'); ?></td>
			<td class="import_progress_status"></td>
		</tr>
		<?php
	}
}


// Lists
//------------------------------------------------------------------------

// Add RevSlider in the sliders list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_revslider_list_sliders' ) ) {
	//add_filter( 'yogastudio_filter_list_sliders',					'yogastudio_revslider_list_sliders' );
	function yogastudio_revslider_list_sliders($list=array()) {
		$list["revo"] = esc_html__("Layer slider (Revolution)", 'yogastudio');
		return $list;
	}
}

// Return Revo Sliders list, prepended inherit (if need)
if ( !function_exists( 'yogastudio_get_list_revo_sliders' ) ) {
	function yogastudio_get_list_revo_sliders($prepend_inherit=false) {
		global $YOGASTUDIO_GLOBALS;
		if (isset($YOGASTUDIO_GLOBALS['list_revo_sliders']))
			$list = $YOGASTUDIO_GLOBALS['list_revo_sliders'];
		else {
			$list = array();
			if (yogastudio_exists_revslider()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT alias, title FROM " . esc_sql($wpdb->prefix) . "revslider_sliders" );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->alias] = $row->title;
					}
				}
			}
			$YOGASTUDIO_GLOBALS['list_revo_sliders'] = $list = apply_filters('yogastudio_filter_list_revo_sliders', $list);
		}
		return $prepend_inherit ? yogastudio_array_merge(array('inherit' => esc_html__("Inherit", 'yogastudio')), $list) : $list;
	}
}

// Add RevSlider in the shortcodes params
if ( !function_exists( 'yogastudio_revslider_shortcodes_params' ) ) {
	//add_filter( 'yogastudio_filter_shortcodes_params',			'yogastudio_revslider_shortcodes_params' );
	function yogastudio_revslider_shortcodes_params($list=array()) {
		$list["revo_sliders"] = yogastudio_get_list_revo_sliders();
		return $list;
	}
}

// Add RevSlider in the Theme Options params
if ( !function_exists( 'yogastudio_revslider_theme_options_params' ) ) {
	//add_filter( 'yogastudio_filter_theme_options_params',			'yogastudio_revslider_theme_options_params' );
	function yogastudio_revslider_theme_options_params($list=array()) {
		global $YOGASTUDIO_GLOBALS;
		$list["list_revo_sliders"] = array('$'.$YOGASTUDIO_GLOBALS['theme_slug'].'_get_list_revo_sliders' => '');
		return $list;
	}
}
?>