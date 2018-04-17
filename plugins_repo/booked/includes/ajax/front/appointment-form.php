<?php

/**
 * $bookings = arrat(
 * 	calendar_id => array(
 * 		appointment_1
 * 		appointment_2
 * 		...
 * 	)
 * 	...
 * )
 */
$bookings = array();

if ( ! empty( $_POST['calendars'] ) ) {
	$bookings = $_POST['calendars'];
} else {
	$calendar_id = isset( $_POST['calendar_id'] ) ? intval( $_POST['calendar_id'] ) : false;
	$bookings[ $calendar_id ][] = array(
		'date' => isset( $_POST['date'] ) ? $_POST['date'] : '',
        'title' => isset( $_POST['title'] ) ? $_POST['title'] : '',
        'timeslot' => isset( $_POST['timeslot'] ) ? $_POST['timeslot'] : '',
        'calendar_id' => $calendar_id,
	);
}


$user_id = get_current_user_id();
$calendar_id = array_keys($bookings)[0];

$args = array(
    'posts_per_page'   	=> -1,
    'meta_key'   	   	=> '_appointment_timestamp',
    'orderby'			=> 'meta_value_num',
    'order'            	=> 'ASC',
    'meta_query' => array(
        array(
            'key'     => '_appointment_timestamp',
            'value'   => strtotime(date_i18n('Y-m-d H:i:s')),
            'compare' => '>=',
        ),
    ),
    'author'		   	=> $user_id,
    'post_type'        	=> 'booked_appointments',
    'post_status'      	=> array('publish','future'),
    'suppress_filters'	=> true );

if ($calendar_id):
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'booked_custom_calendars',
            'field'    => 'term_id',
            'terms'    => $calendar_id,
        )
    );
endif;

$appointments = get_posts($args);
$allready_booked = false;

foreach ($appointments as $post) {
        $timeslot = get_post_meta($post->ID, '_appointment_timeslot', true);
        $timestamp = get_post_meta($post->ID, '_appointment_timestamp', true);
        $app_date = date_i18n('Y-m-d', $timestamp);

        if(strcmp($post->post_status, "publish") == 0 &&
           strcmp($timeslot, $bookings[$calendar_id][0]['timeslot']) == 0 &&
           strcmp($app_date, $bookings[$calendar_id][0]['date']) == 0) {
                $allready_booked = true;
        }
}


// allow other addons to modify the appointments booking list and filter those if necessary 
$bookings = apply_filters( 'booked_fe_appt_form_bookings', $bookings );

// this must be False, if a plugin or script has already checked it while filtering the appointments with 'booked_fe_appt_form_bookings'
$check_availability = apply_filters( 'booked_fe_appt_form_check_availability', true );

// count the appointments
$total_appts = 0;
$total_calendars = count( $bookings );
foreach ( $bookings as $calendar_id => $appointments ) {
	$total_appts += count( $appointments );
}

$has_appts = ! empty( $bookings );
$availability_error = esc_html__( "Sorry, someone just booked this appointment before you could. Please choose a different booking time.", "booked" );
?>
<div class="booked-form booked-scrollable">

	<?php

    if($allready_booked) {
        $availability_error = esc_html__( "Ne cerem scuze, nu vă puteți programa de mai multe ori la aceeași oră.", "booked" );
        echo wpautop( $availability_error );
    } else {
        // If there are appointments, show the form
        if ($has_appts) {
            include(BOOKED_AJAX_INCLUDES_DIR . 'front/appointment-form/form.php');
        }

        // there are no available appointments
        // probably some of them have been already booked and removed by an add on
        if (!$has_appts) {
            echo wpautop($availability_error);
        }
    }
	?>
	
</div>

<?php $new_appointment_default = get_option('booked_new_appointment_default','draft'); ?>

<p class="booked-title-bar"><small><?php echo ( $new_appointment_default == 'draft' ? esc_html__('Request an Appointment','booked') : esc_html__('Book an Appointment','booked') ); ?></small></p>

<?php echo '<a href="#" class="close"><i class="fa fa-remove"></i></a>';