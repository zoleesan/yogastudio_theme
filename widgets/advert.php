<?php
/**
 * Theme Widget: Advertisement
 */

// Theme init
if (!function_exists('yogastudio_widget_advert_theme_setup')) {
	add_action( 'yogastudio_action_before_init_theme', 'yogastudio_widget_advert_theme_setup', 1 );
	function yogastudio_widget_advert_theme_setup() {

		// Add shortcodes in the shortcodes list
		//add_action('yogastudio_action_shortcodes_list',		'yogastudio_widget_advert_reg_shortcodes');
		if (function_exists('yogastudio_exists_visual_composer') && yogastudio_exists_visual_composer())
			add_action('yogastudio_action_shortcodes_list_vc','yogastudio_widget_advert_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('yogastudio_widget_advert_load')) {
	add_action( 'widgets_init', 'yogastudio_widget_advert_load' );
	function yogastudio_widget_advert_load() {
		register_widget( 'yogastudio_widget_advert' );
	}
}

// Widget Class
class yogastudio_widget_advert extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_advert', 'description' => esc_html__('Advertisement block', 'yogastudio') );
		parent::__construct( 'yogastudio_widget_advert', esc_html__('YogaStudio - Advertisement block', 'yogastudio'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		
		extract( $args );

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$advert_image = isset($instance['advert_image']) ? $instance['advert_image'] : '';
		$advert_link = isset($instance['advert_link']) ? $instance['advert_link'] : '';
		$advert_code = isset($instance['advert_code']) ? $instance['advert_code'] : '';

		// Before widget (defined by themes)
		echo trim($before_widget);

		// Display the widget title if one was input (before and after defined by themes)
		if ($title) echo trim($before_title . $title . $after_title);
		?>			
		<div class="widget_advert_inner">
			<?php
			if ($advert_image!='') {
				if ((int) $advert_image > 0) {
					$attach = wp_get_attachment_image_src( $advert_image, 'full' );
					if (isset($attach[0]) && $attach[0]!='')
						$advert_image = $attach[0];
				}
				$attr = yogastudio_getimagesize($advert_image);
				echo (!empty($advert_link) ? '<a href="' . esc_url($advert_link) . '"' : '<span') . ' class="image_wrap"><img src="' . esc_url($advert_image) . '" alt="' . esc_attr($title) . '"'.(!empty($attr[3]) ? ' '.trim($attr[3]) : '').'>' . (!empty($advert_link) ? '</a>': '</span>');
			}
			if ($advert_code!='') {
				echo force_balance_tags(yogastudio_substitute_all($advert_code));
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
		$instance['advert_image'] = strip_tags( $new_instance['advert_image'] );
		$instance['advert_link'] = strip_tags( $new_instance['advert_link'] );
		$instance['advert_code'] = $new_instance['advert_code'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {
		global $YOGASTUDIO_GLOBALS;

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'advert_image' => '',
			'advert_link' => '',
			'advert_code' => ''
			)
		);
		$title = $instance['title'];
		$advert_image = $instance['advert_image'];
		$advert_link = $instance['advert_link'];
		$advert_code = $instance['advert_code'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'yogastudio'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_image' )); ?>"><?php echo wp_kses( __('Image source URL:<br />(leave empty if you paste advert code)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'advert_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_image' )); ?>" value="<?php echo esc_attr($advert_image); ?>" style="width:100%;" onchange="if (jQuery(this).siblings('img').length > 0) jQuery(this).siblings('img').get(0).src=this.value;" />
            <?php
			echo trim(yogastudio_show_custom_field($this->get_field_id( 'advert_media' ), array('type'=>'mediamanager', 'media_field_id'=>$this->get_field_id( 'advert_image' )), null));
			if ($advert_image) {
			?>
	            <br /><br /><img src="<?php echo esc_url($advert_image); ?>" style="max-width:220px;" alt="" />
			<?php
			}
			?>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_link' )); ?>"><?php echo wp_kses( __('Image link URL:<br />(leave empty if you paste advert code)', 'yogastudio'), $YOGASTUDIO_GLOBALS['allowed_tags'] ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'advert_link' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_link' )); ?>" value="<?php echo esc_attr($advert_link); ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'advert_code' )); ?>"><?php esc_html_e('or paste Advert Widget HTML Code:', 'yogastudio'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'advert_code' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'advert_code' )); ?>" rows="5" style="width:100%;"><?php echo htmlspecialchars($advert_code); ?></textarea>
		</p>
	<?php
	}
}

if (is_admin()) {
	require_once yogastudio_get_file_dir('core/core.options/core.options-custom.php');
}



// trx_widget_advert
//-------------------------------------------------------------
/*
[trx_widget_advert id="unique_id" title="Widget title" fullwidth="0|1" image="image_url" link="Image_link_url" code="html & js code"]
*/
if ( !function_exists( 'yogastudio_sc_widget_advert' ) ) {
	function yogastudio_sc_widget_advert($atts, $content=null){	
		$atts = yogastudio_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"image" => "",
			"link" => "",
			"code" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		extract($atts);
		$type = 'yogastudio_widget_advert';
		$output = '';
		global $wp_widget_factory, $YOGASTUDIO_GLOBALS;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$atts['advert_image'] = $image;
			$atts['advert_link'] = $link;
			$atts['advert_code'] = $code;
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_advert' 
								. (yogastudio_exists_visual_composer() ? ' vc_widget_advert wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, yogastudio_prepare_widgets_args($YOGASTUDIO_GLOBALS['widgets_args'], $id ? $id.'_widget' : 'widget_advert', 'widget_advert') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('yogastudio_shortcode_output', $output, 'trx_widget_advert', $atts, $content);
	}
	yogastudio_require_shortcode("trx_widget_advert", "yogastudio_sc_widget_advert");
}


// Add [trx_widget_advert] in the VC shortcodes list
if (!function_exists('yogastudio_widget_advert_reg_shortcodes_vc')) {
	//add_action('yogastudio_action_shortcodes_list_vc','yogastudio_widget_advert_reg_shortcodes_vc');
	function yogastudio_widget_advert_reg_shortcodes_vc() {
		
		global $YOGASTUDIO_GLOBALS;

		vc_map( array(
				"base" => "trx_widget_advert",
				"name" => esc_html__("Widget Advertisement", "yogastudio"),
				"description" => wp_kses( __("Insert widget with advertisement banner or any HTML and/or Javascript code", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
				"category" => esc_html__('Content', 'yogastudio'),
				"icon" => 'icon_trx_widget_advert',
				"class" => "trx_widget_advert",
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
						"param_name" => "image",
						"heading" => esc_html__("Image", "yogastudio"),
						"description" => wp_kses( __("Select or upload image or write URL from other site for the banner (leave empty if you paste advert code)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "link",
						"heading" => esc_html__("Banner's link", "yogastudio"),
						"description" => wp_kses( __("Link URL for the banner (leave empty if you paste advert code)", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "code",
						"heading" => esc_html__("or paste Advert Widget HTML Code", "yogastudio"),
						"description" => wp_kses( __("Widget's HTML and/or JS code", "yogastudio"), $YOGASTUDIO_GLOBALS['allowed_tags'] ),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					$YOGASTUDIO_GLOBALS['vc_params']['id'],
					$YOGASTUDIO_GLOBALS['vc_params']['class'],
					$YOGASTUDIO_GLOBALS['vc_params']['css']
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Advert extends WPBakeryShortCode {}

	}
}
?>