<?php
/**
 * Permission callback to check if the user is logged in.
 *
 * This function ensures that the user making the request is logged in.
 *
 * @return true|WP_Error True if the user is logged in, otherwise a WP_Error object.
 */
function wprentals_check_permissions_create_booking() {
    if (is_user_logged_in()) {
        return true;
    }
    return new WP_Error(
        'rest_forbidden',
        __('You must be logged in to create a booking.'),
        ['status' => 403]
    );
}




/**
 * Create a new booking.
 *
 * This function handles the creation of a new booking using the provided input data.
 * All input fields are saved as post meta.
 *
 * @param WP_REST_Request $request The REST API request object.
 *
 * @return WP_REST_Response|WP_Error The response containing the booking ID or an error.
 */
function wprentals_create_booking(WP_REST_Request $request) {
    $input_data = wprentals_parse_request_params($request);

    // Verify required fields
    // Verify required fields
    $required_fields = ['property_id', 'from_date', 'to_date', 'guests'];
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (empty($input_data[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        return new WP_Error(
            'missing_fields',
            __('The following required fields are missing: ') . implode(', ', $missing_fields),
            ['status' => 400]
        );
    }



    // Set up variables
    $userID = get_current_user_id();

    // Check if property exists and is of type 'estate_property'
    $property_id = intval($input_data['property_id']);
    $property_post = get_post($property_id);

    if (!$property_post || $property_post->post_type !== 'estate_property') {
        return new WP_Error(
            'invalid_property',
            __('The provided property ID is invalid or does not exist.'),
            ['status' => 400]
        );
    }
   

    $fromdate = $input_data['from_date'];
    $to_date = $input_data['to_date'];



    // Check availability before proceeding
    $availability_check = wprentals_get_booking_availability([
        'listing_id' => $property_id,
        'book_from' => $fromdate,
        'book_to' => $to_date,
        'internal' => $input_data['internal'] ?? 0
    ], false);

    // If availability check fails, return the error
    if ($availability_check['status'] === 'error') {
        return new WP_Error(
            'availability_error',
            $availability_check['message'],
            ['status' => 400]
        );
    }





    $booking_guest_no = intval($input_data['guests']);
    $booking_adults = intval($input_data['adults'] ?? 0);
    $booking_childs = intval($input_data['childs'] ?? 0);
    $booking_infants = intval($input_data['infants'] ?? 0);
    $extra_options = $input_data['extra_options'] ?? '';

    // Fetch property owner ID
    $owner_id = get_post_field('post_author', $property_id);

    // Create the booking post
    $post_id = wp_insert_post([
        'post_title'   => esc_html__('Booking Request', 'wprentals-core'),
        'post_content' => $input_data['comment'] ?? '',
        'post_status'  => 'publish',
        'post_type'    => 'wpestate_booking',
        'post_author'  => $userID,
    ]);

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    // Update the booking post title
    wp_update_post([
        'ID'         => $post_id,
        'post_title' => esc_html__('Booking Request', 'wprentals-core') . ' ' . $post_id,
    ]);

    // Save booking meta data
    $meta_fields = [
        'booking_id'               => $property_id,
        'owner_id'                 => $owner_id,
        'booking_from_date'        => $fromdate,
        'booking_to_date'          => $to_date,
        'booking_status'           => 'pending',
        'booking_invoice_no'       => 0,
        'booking_pay_ammount'      => 0,
        'booking_guests'           => $booking_guest_no,
        'booking_adults'           => $booking_adults,
        'booking_childs'           => $booking_childs,
        'booking_infants'          => $booking_infants,
        'extra_options'            => $extra_options,
        'booking_from_date_unix'   => strtotime($fromdate),
        'booking_to_date_unix'     => strtotime($to_date),
        'security_deposit'         => get_post_meta($property_id, 'security_deposit', true),
        'full_pay_invoice_id'      => 0,
        'to_be_paid'               => 0,
    ];

    foreach ($meta_fields as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }

  



    // Update booking dates for the property
    $reservation_array = wpestate_get_booking_dates($property_id);
    update_post_meta($property_id, 'booking_dates', $reservation_array);

    // Notify users
    $current_user = wp_get_current_user();
    if ($owner_id == $userID) {
        $subject = esc_html__('You reserved a period', 'wprentals-core');
        $description = esc_html__('You have reserved a period on your own listing', 'wprentals-core');

        wpestate_add_to_inbox($userID, $userID, $userID, $subject, $description, "internal_book_req");
        wpestate_send_booking_email('mynewbook', $current_user->user_email, $property_id);
    } else {
        $receiver = get_userdata($owner_id);
        $receiver_email = $receiver->user_email;
        $subject = esc_html__('New Booking Request from ', 'wprentals-core') . $current_user->user_login;
        $description = sprintf(
            esc_html__('Dear %s, You have received a new booking request from %s.', 'wprentals-core'),
            $receiver->user_login,
            $current_user->user_login
        );

  
        wpestate_add_to_inbox($userID, $userID, $owner_id, $subject, $description, "external_book_req");
        wpestate_send_booking_email('newbook', $receiver_email, ['property_id' => $property_id, 'booking_id' => $post_id]);
      
    }

    return rest_ensure_response([
        'status'      => 'success',
        'booking_id'  => $post_id,
        'message'     => __('Booking created successfully.'),
    ]);
}