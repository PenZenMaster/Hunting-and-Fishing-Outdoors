<?php 
/**
 * Delete a booking via REST API
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int id       The booking ID
 *     @type int isuser   Whether deletion is requested by user (1) or owner (0)
 * 
 * @return WP_REST_Response|WP_Error Response confirming deletion or error
 */
function wprentals_delete_booking(WP_REST_Request $request) {
    // Get and validate parameters
    $booking_id = intval($request['id']);
    $params = wprentals_parse_request_params($request);
    $is_user = isset($params['is_renter']) ? intval($params['is_renter']) : 0;
    
    // Get current user
    $current_user = wp_get_current_user();
    $userID = $current_user->ID;

    // Verify the booking exists and is correct type
    if (get_post_type($booking_id) !== 'wpestate_booking') {
        return new WP_Error(
            'invalid_booking',
            'Invalid booking ID or incorrect post type',
            array('status' => 400)
        );
    }

    // Get related IDs and verify permissions
    $invoice_id = get_post_meta($booking_id, 'booking_invoice_no', true);
    $listing_id = get_post_meta($booking_id, 'booking_id', true);
    $user_id = wpse119881_get_author($listing_id);
    $booking_owner = wpse119881_get_author($booking_id);

    // Check if user has permission to delete
    if ($user_id != $userID && $booking_owner != $userID) {
        return new WP_Error(
            'unauthorized',
            'You do not have permission to delete this booking',
            array('status' => 403)
        );
    }

    // Process email notifications
    $receiver = get_userdata($booking_owner);
    $receiver_email = $receiver->user_email;
    $receiver_name = $receiver->user_login;

    if ($is_user == 1) {
        // User cancelling their own booking
        $prop_id = get_post_meta($booking_id, 'booking_id', true);
        $to_id = wpse119881_get_author($prop_id);
        $to_userdata = get_userdata($to_id);
        $to_email = $to_userdata->user_email;
        
        $args = array(
            'receiver_email' => $receiver_email,
            'receiver_name' => $receiver_name
        );
        wpestate_send_booking_email('deletebookinguser', $to_email, $args);
        
        $subject = esc_html__('Request Cancelled', 'wprentals-core');
        $description = esc_html__('User ', 'wprentals-core') . $receiver_name . 
                      esc_html__(' cancelled his booking request', 'wprentals-core');
        wpestate_add_to_inbox($userID, $userID, $to_id, $subject, $description, "isfirst");
    } else {
        // Owner denying booking
        wpestate_send_booking_email('deletebooking', $receiver_email);
        
        $subject = esc_html__('Request Denied', 'wprentals-core');
        $description = esc_html__('Your booking request was denied.', 'wprentals-core');
        wpestate_add_to_inbox($userID, $userID, $booking_owner, $subject, $description, "isfirst");
    }

    // Delete associated invoice if it exists
    if (!empty($invoice_id)) {
        wp_delete_post($invoice_id);
    }

    // Update booking status to canceled
    $booking_details = array(
        'booking_status' => 'canceled',
        'booking_status_full' => 'canceled'
    );

    // Update reservation array
    $from_date = esc_html(get_post_meta($booking_id, 'booking_from_date', true));
    $reservation_array = wpestate_get_booking_dates($listing_id);
    $is_per_hour = wprentals_return_booking_type($listing_id);

    if ($is_per_hour == 2) {
        // Per hour booking
        unset($reservation_array[strtotime($from_date)]);
    } else {
        // Per day booking
        foreach ($reservation_array as $key => $value) {
            if ($value == $booking_id) {
                unset($reservation_array[$key]);
            }
        }
    }

    // Update property's booking dates
    update_post_meta($listing_id, 'booking_dates', $reservation_array);

    // Delete the booking
    $result = wp_delete_post($booking_id);
    
    if (!$result) {
        return new WP_Error(
            'deletion_failed',
            'Failed to delete the booking',
            array('status' => 500)
        );
    }

    // Return success response
    return rest_ensure_response(array(
        'status' => 'success',
        'message' => $is_user ? 'Booking cancelled successfully' : 'Booking denied successfully',
        'booking_id' => $booking_id,
        'listing_id' => $listing_id
    ));
}