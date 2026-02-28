<?php 

/**
 * Permission callback to validate access to edit a booking.
 *
 * This function ensures that the user requesting to edit a booking
 * is logged in and is the owner of the booking.
 *
 * @param WP_REST_Request $request The REST API request object.
 *
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_edit_booking($request) {
    $userID = apply_filters('determine_current_user', null);
    if (!$userID) {
        return new WP_Error(
            'jwt_auth_failed',
            __('Invalid or missing JWT token.'),
            ['status' => 403]
        );
    }
    wp_set_current_user($userID);

    $current_user = wp_get_current_user();
    $userID = $current_user->ID;

    $booking_id = $request['id'];
    $booking_user_id = get_post_field('post_author', $booking_id);

    // Check if the user is the owner of the booking
    if ((int)$booking_user_id === $userID) {
        return true;
    }

    // check of the user is owner of the property that receive the booking
    $property_id = get_post_meta($booking_id, 'booking_id', true);
    $property = get_post($property_id);
    if ($property && intval($property->post_author) === $userID) {
        return true;
    }

    //allows admins
    if(current_user_can('administrator')){
        return true;
    }

    return new WP_Error(
        'rest_forbidden',
        __('You do not have permission to edit this booking.'),
        ['status' => 403]
    );
}




/**
 * Update booking status via REST API
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int    id                  The booking ID
 *     @type string booking_status      New booking status ('request','waiting','pending','confirmed')
 *     @type string booking_status_full New full booking status ('waiting','confirmed')
 * 
 * @return WP_REST_Response|WP_Error Response confirming update or error
 */
function wprentals_update_booking(WP_REST_Request $request) {
    // Get and validate booking ID
    $booking_id = intval($request['id']);
    
    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    
    // Verify booking exists and is correct type
    $booking = get_post($booking_id);
    if (!$booking || $booking->post_type !== 'wpestate_booking') {
        return new WP_Error(
            'invalid_booking',
            'Invalid booking ID or incorrect post type',
            array('status' => 400)
        );
    }
    
    // Define allowed status values
    $allowed_booking_status = array('request', 'waiting', 'pending', 'confirmed');
    $allowed_booking_status_full = array('waiting', 'confirmed');
    
    // Get current status values
    $current_status = get_post_meta($booking_id, 'booking_status', true);
    $current_status_full = get_post_meta($booking_id, 'booking_status_full', true);
    
    // Check if trying to set confirmed status without being admin
    if (
        (isset($params['booking_status']) && $params['booking_status'] === 'confirmed' ||
         isset($params['booking_status_full']) && $params['booking_status_full'] === 'confirmed') && 
        !current_user_can('administrator')
    ) {
        return new WP_Error(
            'unauthorized_status',
            'Only administrators can set confirmed status',
            array('status' => 403)
        );
    }
    
    // Update booking_status if provided
    if (isset($params['booking_status'])) {
        $new_status = sanitize_text_field($params['booking_status']);
        
        if (!in_array($new_status, $allowed_booking_status)) {
            return new WP_Error(
                'invalid_status',
                'Invalid booking_status. Allowed values: ' . implode(', ', $allowed_booking_status),
                array('status' => 400)
            );
        }
        
        if ($new_status !== $current_status) {
            update_post_meta($booking_id, 'booking_status', $new_status);
            $changes['booking_status'] = array(
                'from' => $current_status,
                'to' => $new_status
            );
        }
    }
    
    // Update booking_status_full if provided
    if (isset($params['booking_status_full'])) {
        $new_status_full = sanitize_text_field($params['booking_status_full']);
        
        if (!in_array($new_status_full, $allowed_booking_status_full)) {
            return new WP_Error(
                'invalid_status_full',
                'Invalid booking_status_full. Allowed values: ' . implode(', ', $allowed_booking_status_full),
                array('status' => 400)
            );
        }
        
        if ($new_status_full !== $current_status_full) {
            update_post_meta($booking_id, 'booking_status_full', $new_status_full);
            $changes['booking_status_full'] = array(
                'from' => $current_status_full,
                'to' => $new_status_full
            );
        }
    }
    
    // If no changes were made
    if (empty($changes)) {
        return rest_ensure_response(array(
            'status' => 'success',
            'message' => 'No changes were necessary',
            'booking_id' => $booking_id,
            'current_status' => array(
                'booking_status' => $current_status,
                'booking_status_full' => $current_status_full
            )
        ));
    }
    
    // Return success response with changes
    return rest_ensure_response(array(
        'status' => 'success',
        'message' => 'Booking status updated successfully',
        'booking_id' => $booking_id,
        'changes' => $changes,
        'current_status' => array(
            'booking_status' => get_post_meta($booking_id, 'booking_status', true),
            'booking_status_full' => get_post_meta($booking_id, 'booking_status_full', true)
        )
    ));
}