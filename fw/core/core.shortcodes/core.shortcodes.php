<?php
/**
 * YogaStudio Framework: shortcodes manipulations
 *
 * @package	yogastudio
 * @since	yogastudio 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('yogastudio_sc_theme_setup')) {
	add_action( 'yogastudio_action_init_theme', 'yogastudio_sc_theme_setup', 1 );
	function yogastudio_sc_theme_setup() {
		// Add sc stylesheets
		add_action('yogastudio_action_add_styles', 'yogastudio_sc_add_styles', 1);
	}
}

if (!function_exists('yogastudio_sc_theme_setup2')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_sc_theme_setup2' );
	function yogastudio_sc_theme_setup2() {

		if ( !is_admin() || isset($_POST['action']) ) {
			// Enable/disable shortcodes in excerpt
			add_filter('the_excerpt', 					'yogastudio_sc_excerpt_shortcodes');
	
			// Prepare shortcodes in the content
			if (function_exists('yogastudio_sc_prepare_content')) yogastudio_sc_prepare_content();
		}

		// Add init script into shortcodes output in VC frontend editor
		add_filter('yogastudio_shortcode_output', 'yogastudio_sc_add_scripts', 10, 4);

		// AJAX: Send contact form data
		add_action('wp_ajax_send_form',			'yogastudio_sc_form_send');
		add_action('wp_ajax_nopriv_send_form',	'yogastudio_sc_form_send');

		// Show shortcodes list in admin editor
		add_action('media_buttons',				'yogastudio_sc_selector_add_in_toolbar', 11);

	}
}


// Add shortcodes styles
if ( !function_exists( 'yogastudio_sc_add_styles' ) ) {
	//add_action('yogastudio_action_add_styles', 'yogastudio_sc_add_styles', 1);
	function yogastudio_sc_add_styles() {
		// Shortcodes
		yogastudio_enqueue_style( 'yogastudio-shortcodes-style',	yogastudio_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
	}
}


// Add shortcodes init scripts
if ( !function_exists( 'yogastudio_sc_add_scripts' ) ) {
	//add_filter('yogastudio_shortcode_output', 'yogastudio_sc_add_scripts', 10, 4);
	function yogastudio_sc_add_scripts($output, $tag='', $atts=array(), $content='') {

		global $YOGASTUDIO_GLOBALS;
		
		if (empty($YOGASTUDIO_GLOBALS['shortcodes_scripts_added'])) {
			$YOGASTUDIO_GLOBALS['shortcodes_scripts_added'] = true;
			//yogastudio_enqueue_style( 'yogastudio-shortcodes-style', yogastudio_get_file_url('shortcodes/theme.shortcodes.css'), array(), null );
			yogastudio_enqueue_script( 'yogastudio-shortcodes-script', yogastudio_get_file_url('shortcodes/theme.shortcodes.js'), array('jquery'), null, true );	
		}
		
		return $output;
	}
}


/* Prepare text for shortcodes
-------------------------------------------------------------------------------- */

// Prepare shortcodes in content
if (!function_exists('yogastudio_sc_prepare_content')) {
	function yogastudio_sc_prepare_content() {
		if (function_exists('yogastudio_sc_clear_around')) {
			$filters = array(
				array('yogastudio', 'sc', 'clear', 'around'),
				array('widget', 'text'),
				array('the', 'excerpt'),
				array('the', 'content')
			);
			if (function_exists('yogastudio_exists_woocommerce') && yogastudio_exists_woocommerce()) {
				$filters[] = array('woocommerce', 'template', 'single', 'excerpt');
				$filters[] = array('woocommerce', 'short', 'description');
			}
			if (is_array($filters) && count($filters) > 0) {
				foreach ($filters as $flt)
					add_filter(join('_', $flt), 'yogastudio_sc_clear_around', 1);	// Priority 1 to clear spaces before do_shortcodes()
			}
		}
	}
}

// Enable/Disable shortcodes in the excerpt
if (!function_exists('yogastudio_sc_excerpt_shortcodes')) {
	function yogastudio_sc_excerpt_shortcodes($content) {
		if (!empty($content)) {
			$content = do_shortcode($content);
			//$content = strip_shortcodes($content);
		}
		return $content;
	}
}



/*
// Remove spaces and line breaks between close and open shortcode brackets ][:
[trx_columns]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
	[trx_column_item]Column text ...[/trx_column_item]
[/trx_columns]

convert to

[trx_columns][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][trx_column_item]Column text ...[/trx_column_item][/trx_columns]
*/
if (!function_exists('yogastudio_sc_clear_around')) {
	function yogastudio_sc_clear_around($content) {
		if (!empty($content)) $content = preg_replace("/\](\s|\n|\r)*\[/", "][", $content);
		return $content;
	}
}






/* Shortcodes support utils
---------------------------------------------------------------------- */

// YogaStudio shortcodes load scripts
if (!function_exists('yogastudio_sc_load_scripts')) {
	function yogastudio_sc_load_scripts() {
		yogastudio_enqueue_script( 'yogastudio-shortcodes-script', yogastudio_get_file_url('core/core.shortcodes/shortcodes_admin.js'), array('jquery'), null, true );
		yogastudio_enqueue_script( 'yogastudio-selection-script',  yogastudio_get_file_url('js/jquery.selection.js'), array('jquery'), null, true );
	}
}

// YogaStudio shortcodes prepare scripts
if (!function_exists('yogastudio_sc_prepare_scripts')) {
	function yogastudio_sc_prepare_scripts() {
		global $YOGASTUDIO_GLOBALS;
		if (!isset($YOGASTUDIO_GLOBALS['shortcodes_prepared'])) {
			$YOGASTUDIO_GLOBALS['shortcodes_prepared'] = true;
			$json_parse_func = 'eval';	// 'JSON.parse'
			?>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					try {
						YOGASTUDIO_GLOBALS['shortcodes'] = <?php echo trim($json_parse_func); ?>(<?php echo json_encode( yogastudio_array_prepare_to_json($YOGASTUDIO_GLOBALS['shortcodes']) ); ?>);
					} catch (e) {}
					YOGASTUDIO_GLOBALS['shortcodes_cp'] = '<?php echo is_admin() ? (!empty($YOGASTUDIO_GLOBALS['to_colorpicker']) ? $YOGASTUDIO_GLOBALS['to_colorpicker'] : 'wp') : 'custom'; ?>';	// wp | tiny | custom
				});
			</script>
			<?php
		}
	}
}

// Show shortcodes list in admin editor
if (!function_exists('yogastudio_sc_selector_add_in_toolbar')) {
	//add_action('media_buttons','yogastudio_sc_selector_add_in_toolbar', 11);
	function yogastudio_sc_selector_add_in_toolbar(){

		if ( !yogastudio_options_is_used() ) return;

		yogastudio_sc_load_scripts();
		yogastudio_sc_prepare_scripts();

		global $YOGASTUDIO_GLOBALS;

		$shortcodes = $YOGASTUDIO_GLOBALS['shortcodes'];
		$shortcodes_list = '<select class="sc_selector"><option value="">&nbsp;'.esc_html__('- Select Shortcode -', 'yogastudio').'&nbsp;</option>';

		if (is_array($shortcodes) && count($shortcodes) > 0) {
			foreach ($shortcodes as $idx => $sc) {
				$shortcodes_list .= '<option value="'.esc_attr($idx).'" title="'.esc_attr($sc['desc']).'">'.esc_html($sc['title']).'</option>';
			}
		}

		$shortcodes_list .= '</select>';

		echo trim($shortcodes_list);
	}
}

// YogaStudio shortcodes builder settings
require_once yogastudio_get_file_dir('core/core.shortcodes/shortcodes_settings.php');

// VC shortcodes settings
if ( class_exists('WPBakeryShortCode') ) {
	require_once yogastudio_get_file_dir('core/core.shortcodes/shortcodes_vc.php');
}

// YogaStudio shortcodes implementation
yogastudio_autoload_folder( 'shortcodes/trx_basic' );
yogastudio_autoload_folder( 'shortcodes/trx_optional' );
?>