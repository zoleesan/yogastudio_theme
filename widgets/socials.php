<?php
/**
 * Theme Widget: Socials
 */

// Theme init
if (!function_exists('yogastudio_widget_socials_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_widget_socials_theme_setup', 1 );
	function yogastudio_widget_socials_theme_setup() {

		// Add shortcodes in the shortcodes list
		//add_action('yogastudio_action_shortcodes_list',	'yogastudio_widget_socials_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_widget_socials_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('yogastudio_widget_socials_load')) {
	add_action( 'widgets_init', 'yogastudio_widget_socials_load' );
	function yogastudio_widget_socials_load() {
		register_widget( 'yogastudio_widget_socials' );
	}
}

// Widget Class
class yogastudio_widget_socials extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_socials', 'description' => esc_html__('Show site logo and social links', 'yogastudio') );
		parent::__construct( 'yogastudio_widget_socials', esc_html__('YogaStudio - Show logo and social links', 'yogastudio'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$text = isset($instance['text']) ? yogastudio_do_shortcode($instance['text']) : '';
		$logo_image = isset($instance['logo_image']) ? $instance['logo_image'] : '';
		$logo_text = isset($instance['logo_text']) ? $instance['logo_text'] : '';
		$logo_slogan = isset($instance['logo_slogan']) ? $instance['logo_slogan'] : '';
		$show_logo = isset($instance['show_logo']) ? (int) $instance['show_logo'] : 1;
		$show_icons = isset($instance['show_icons']) ? (int) $instance['show_icons'] : 1;

		// Before widget (defined by themes)
		echo trim($before_widget);

		// Display the widget title if one was input (before and after defined by themes)
		if ($title) echo trim($before_title . $title . $after_title);
		
		// Display widget body
		?>
		<div class="widget_inner">
            <?php
				if ($show_logo) {
					if ($logo_image=='')
						$logo_image = true;
					else {
						if ((int) $logo_image > 0) {
							$attach = wp_get_attachment_image_src( $logo_image, 'full' );
							if (isset($attach[0]) && $attach[0]!='')
								$logo_image = $attach[0];
						}
					}
					if ($logo_text=='')		$logo_text = true;
					if ($logo_slogan=='')	$logo_slogan = true;
					if ($logo_image || $logo_text)
						yogastudio_show_logo($logo_image, false, false, false, $logo_text, $logo_slogan);
				}

				if (!empty($text)) {
					?>
					<div class="logo_descr"><?php echo nl2br(do_shortcode($text)); ?></div>
                    <?php
				}
				
				if ($show_icons) {
					echo trim(yogastudio_sc_socials(array('size' => "tiny", 'shape' => 'round')));
				}
			?>
		</div>

		<?php
		// After widget (defined by themes)
		echo trim($after_widget);
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = $new_instance['text'];
		$instance['logo_image'] = strip_tags( $new_instance['logo_image'] );
		$instance['logo_text'] = strip_tags( $new_instance['logo_text'] );
		$instance['logo_slogan'] = strip_tags( $new_instance['logo_slogan'] );
		$instance['show_logo'] = (int) $new_instance['show_logo'];
		$instance['show_icons'] = (int) $new_instance['show_icons'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {
		global $YOGASTUDIO_GLOBALS;

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '',
			'text' => '',
			'logo_image' => '',
			'logo_text' => '',
			'logo_slogan' => '',
			'show_logo' => '1',
			'show_icons' => '1'
			)
		);
		$title = $instance['title'];
		$text = $instance['text'];
		$logo_image = $instance['logo_image'];
		$logo_text = $instance['logo_text'];
		$logo_slogan = $instance['logo_slogan'];
		$show_logo = (int) $instance['show_logo'];
		$show_icons = (int) $instance['show_icons'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'yogastudio'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php esc_html_e('Description:', 'yogastudio'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'text' )); ?>" style="width:100%;"><?php echo htmlspecialchars($instance['text']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>"><?php echo wp_kses( __('Logo image:<br />(if empty - use logo from Theme Options)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_image' )); ?>" value="<?php echo esc_attr($logo_image); ?>" style="width:100%;" onchange="if (jQuery(this).siblings('img').length > 0) jQuery(this).siblings('img').get(0).src=this.value;" />
            <?php
			echo trim(yogastudio_show_custom_field($this->get_field_id( 'logo_media' ), array('type'=>'mediamanager', 'media_field_id'=>$this->get_field_id( 'logo_image' )), null));
			if ($logo_image) {
			?>
	            <br /><br /><img src="<?php echo esc_url($logo_image); ?>" style="max-width:220px;" alt="" />
			<?php
			}
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>"><?php esc_html_e('Logo text:', 'yogastudio'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_text' )); ?>" value="<?php echo esc_attr($instance['logo_text']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_slogan' )); ?>"><?php esc_html_e('Logo slogan:', 'yogastudio'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_slogan' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_slogan' )); ?>" value="<?php echo esc_attr($instance['logo_slogan']); ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php esc_html_e('Show logo:', 'yogastudio'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="1" <?php echo (1==$show_logo ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php esc_html_e('Show', 'yogastudio'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="0" <?php echo (0==$show_logo ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0"><?php esc_html_e('Hide', 'yogastudio'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php esc_html_e('Show social icons:', 'yogastudio'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="1" <?php echo (1==$show_icons ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php esc_html_e('Show', 'yogastudio'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="0" <?php echo (0==$show_icons ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0"><?php esc_html_e('Hide', 'yogastudio'); ?></label>
		</p>

	<?php
	}
}

if (is_admin()) {
	require_once yogastudio_get_file_dir('core/core.options/core.options-custom.php');
}



// trx_widget_socials
//-------------------------------------------------------------
/*
[trx_widget_socials id="unique_id" title="Widget title" text="4" logo_image="url" logo_text="Basekit" show_logo="0|1" show_icons="0|1"]
*/
if ( !function_exists( 'yogastudio_sc_widget_socials' ) ) {
	function yogastudio_sc_widget_socials($atts, $content=null){	
		$atts = yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"text" => "",
			"logo_image" => "",
			"logo_text" => "",
			"logo_slogan" => "",
			"show_logo" => 1,
			"show_icons" => 1,
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		if ($atts['show_logo']=='') $atts['show_logo'] = 0;
		if ($atts['show_icons']=='') $atts['show_icons'] = 0;
		extract($atts);
		$type = 'yogastudio_widget_socials';
		$output = '';
		global $wp_widget_factory, $YOGASTUDIO_GLOBALS;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_socials' 
								. (yogastudio_exists_visual_composer() ? ' vc_widget_socials wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, yogastudio_prepare_widgets_args($YOGASTUDIO_GLOBALS['widgets_args'], $id ? $id.'_widget' : 'widget_socials', 'widget_socials') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_widget_socials', $atts, $content);
	}
	yogastudio_require_shortcode("trx_widget_socials", "yogastudio_sc_widget_socials");
}


// Add [trx_widget_socials] in the VC shortcodes list
if (!function_exists('yogastudio_widget_socials_reg_shortcodes_vc')) {
	function yogastudio_widget_socials_reg_shortcodes_vc() {
		
		global $YOGASTUDIO_GLOBALS;

		vc_map( array(
				"base" => "trx_widget_socials",
				"name" => esc_html__("Widget Socials", "yogastudio"),
				"description" => wp_kses( __("Insert site logo, description and/or socials list", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"category" => esc_html__('Content', 'yogastudio'),
				"icon" => 'icon_trx_widget_socials',
				"class" => "trx_widget_socials",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", "yogastudio"),
						"description" => wp_kses( __("Title of the widget", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "text",
						"heading" => esc_html__("Widget text", "yogastudio"),
						"description" => wp_kses( __("Any description", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "show_logo",
						"heading" => esc_html__("Show logo", "yogastudio"),
						"description" => wp_kses( __("Do you want display logo image?", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"std" => 1,
						"value" => array("Show logo" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_icons",
						"heading" => esc_html__("Show social icons", "yogastudio"),
						"description" => wp_kses( __("Do you want display social icons?", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"std" => 1,
						"value" => array("Show icons" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "logo_image",
						"heading" => esc_html__("Logo image", "yogastudio"),
						"description" => wp_kses( __("Select or upload image or write URL from other site for the logo (leave empty if you want use default site logo)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						'dependency' => array(
							'element' => 'show_logo',
							'not_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "logo_text",
						"heading" => esc_html__("Logo text", "yogastudio"),
						"description" => wp_kses( __("Site name for the logo(leave empty if you want use default site text)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'show_logo',
							'not_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "logo_slogan",
						"heading" => esc_html__("Logo slogan", "yogastudio"),
						"description" => wp_kses( __("Site slogan for the logo(leave empty if you want use default site tagline)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						'dependency' => array(
							'element' => 'show_logo',
							'not_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					$YOGASTUDIO_GLOBALS['vc_params']['id'],
					$YOGASTUDIO_GLOBALS['vc_params']['class'],
					$YOGASTUDIO_GLOBALS['vc_params']['css']
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Socials extends WPBakeryShortCode {}

	}
}
?>