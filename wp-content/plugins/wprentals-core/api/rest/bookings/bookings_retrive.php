<?php
/*
* Permission callback to validate access to all bookings.
*
* This function ensures that the user requesting access is logged in
* and has the role of an administrator.
*
* @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
*/
function wprentals_check_permissions_all_bookings() {
    // Verify the JWT token
    $userID = apply_filters('determine_current_user', null);
    if (!$userID) {
        return new WP_Error(
            'jwt_auth_failed',
            __('Invalid or missing JWT token.'),
            ['status' => 403]
        );
    }
    wp_set_current_user($userID);

    // Fetch the current user details
    $current_user = wp_get_current_user();
    $userID = $current_user->ID;

    if (is_user_logged_in() && current_user_can('administrator')) {
        return true;
    }
    return new WP_Error('rest_forbidden', __('You do not have permission to access this route.'), ['status' => 403]);
}


/**
 * Permission callback to validate access to a single booking.
 *
 * This function ensures that the user requesting access to a booking
 * is either the owner of the booking or the owner of the property
 * associated with the booking.
 *
 * @param WP_REST_Request $request The REST API request object.
 *
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_get_single_booking($request) {
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
    $property_id = get_post_meta($booking_id, 'booking_id', true);

    // Check if the user is the owner of the booking
    if ((int)$booking_user_id === $userID) {
        return true;
    }

    // Check if the user owns the property associated with the booking
    $property_owner_id = get_post_field('post_author', $property_id);
    if ((int)$property_owner_id === $userID) {
        return true;
    }

    return new WP_Error(
        'rest_forbidden',
        __('You do not have permission to access this booking.'),
        ['status' => 403]
    );
}



/**
 * Permission callback to validate access to bookings for a user.
 *
 * This function ensures that the user requesting bookings for a specific user ID
 * is either logged in as that user or is an administrator.
 *
 * @param WP_REST_Request $request The REST API request object.
 *
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_bookings_for_user($request) {
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

    $requested_user_id = intval($request['user_id']);

    // Check if the user is the same as the requested user ID or is an administrator
    if ($userID === $requested_user_id || current_user_can('administrator')) {
        return true;
    }

    return new WP_Error(
        'rest_forbidden',
        __('You do not have permission to access these bookings.'),
        ['status' => 403]
    );
}


/**
 * Helper function to get booking details
 * 
 * @param int  $booking_id   The ID of the booking
 * @param bool $extra_detail Whether to include extra details (default: false)
 * 
 * @return array|false Booking details array or false if booking/property doesn't exist
 */
function wprentals_get_booking_details($booking_id, $extra_detail = false) {
    // Verify booking exists and is correct type
    $booking = get_post($booking_id);
    if (!$booking || $booking->post_type !== 'wpestate_booking') {
        return false;
    }
    
    // Get property ID and verify it exists
    $property_id = get_post_meta($booking_id, 'booking_id', true);
    if (!get_post($property_id)) {
        return false;
    }
    
    // Get booking user ID
    $booking_user_id = get_post_field('post_author', $booking_id);
    
    // Collect booking details
    $booking_details = array(
        'booking_id' => $booking_id,
        'booking_status' => get_post_meta($booking_id, 'booking_status', true),
        'property_id' => $property_id,
        'property_title' => get_the_title($property_id),
        'booking_from_date' => get_post_meta($booking_id, 'booking_from_date', true),
        'booking_to_date' => get_post_meta($booking_id, 'booking_to_date', true),
        'booking_guests' => get_post_meta($booking_id, 'booking_guests', true),
        'booking_adults' => get_post_meta($booking_id, 'booking_adults', true),
        'booking_childs' => get_post_meta($booking_id, 'booking_childs', true),
        'booking_infants' => get_post_meta($booking_id, 'booking_infants', true),
        'booking_invoice_no' => get_post_meta($booking_id, 'booking_invoice_no', true),
        'booking_pay_amount' => get_post_meta($booking_id, 'booking_pay_amount', true),
        'booking_user' => array(
            'id' => $booking_user_id,
            'name' => get_the_author_meta('display_name', $booking_user_id),
            'email' => get_the_author_meta('user_email', $booking_user_id)
        ),
        'booking_dates' => array(
            'created' => get_the_date('Y-m-d H:i:s', $booking_id),
            'modified' => get_the_modified_date('Y-m-d H:i:s', $booking_id)
        )
    );
    
    // Add extra details if requested
    if ($extra_detail) {
        $booking_details['extra_details'] = array(
            'extra_options' => get_post_meta($booking_id, 'extra_options', true),
            'security_deposit' => get_post_meta($booking_id, 'security_deposit', true),
            'full_pay_invoice_id' => get_post_meta($booking_id, 'full_pay_invoice_id', true),
            'to_be_paid' => get_post_meta($booking_id, 'to_be_paid', true),
            'booking_from_date_unix' => get_post_meta($booking_id, 'booking_from_date_unix', true),
            'booking_to_date_unix' => get_post_meta($booking_id, 'booking_to_date_unix', true),
            'booking_comment' => $booking->post_content
        );
    }
    
    return $booking_details;
}


/**
 * Retrieves all bookings with pagination support
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int page           Page number (optional, default: 1)
 *     @type int posts_per_page Number of posts per page (optional, default: 30)
 * 
 * @return WP_REST_Response|WP_Error Response containing bookings data or error
 */
function wprentals_get_all_bookings(WP_REST_Request $request) {
    // [Previous pagination validation code remains the same]
    
    $args = array(
        'post_type' => 'wpestate_booking',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => $posts_per_page,
        'order' => 'DESC'
    );
    
    $booking_query = new WP_Query($args);
    $bookings = array();
    
    if ($booking_query->have_posts()) {
        while ($booking_query->have_posts()) {
            $booking_query->the_post();
            $booking_details = wprentals_get_booking_details(get_the_ID());
            if ($booking_details) {
                $bookings[] = $booking_details;
            }
        }
        wp_reset_postdata();
    }
    
    return rest_ensure_response(array(
        'status' => 'success',
        'bookings' => $bookings,
        'pagination' => [
            'current_page' => $paged,
            'posts_per_page' => $posts_per_page,
            'total_posts' => $booking_query->found_posts,
            'total_pages' => $booking_query->max_num_pages,
            'has_previous' => ($paged > 1),
            'has_next' => ($paged < $booking_query->max_num_pages)
        ]
    ));
}



/**
 * Retrieves details for a single booking
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int id The booking ID
 * 
 * @return WP_REST_Response|WP_Error Response containing booking data or error
 */
function wprentals_get_single_booking(WP_REST_Request $request) {
    $booking_id = intval($request['id']);
    
    $booking_details = wprentals_get_booking_details($booking_id, true);
    if (!$booking_details) {
        return new WP_Error(
            'booking_not_found',
            'The specified booking does not exist or has an invalid property',
            array('status' => 404)
        );
    }
    
    return rest_ensure_response(array(
        'status' => 'success',
        'booking' => $booking_details
    ));
}



/**
 * Retrieves all bookings for a specific user with pagination support
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int user_id        The ID of the user whose bookings to retrieve
 *     @type int page          Page number (optional, default: 1)
 *     @type int posts_per_page Number of posts per page (optional, default: 30)
 * 
 * @return WP_REST_Response|WP_Error Response containing bookings data or error
 */
function wprentals_get_user_bookings(WP_REST_Request $request) {
    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    $user_id = intval($request['user_id']); // From URL parameter
    
    // Verify user exists
    if (!get_user_by('id', $user_id)) {
        return new WP_Error(
            'invalid_user',
            'The specified user does not exist',
            array('status' => 404)
        );
    }
    
    // Set default values for pagination
    $paged = isset($params['page']) ? intval($params['page']) : 1;
    $posts_per_page = isset($params['posts_per_page']) ? intval($params['posts_per_page']) : 30;
    
    // Validate pagination parameters
    if ($paged < 1) {
        return new WP_Error(
            'invalid_page',
            'Page number must be greater than 0',
            array('status' => 400)
        );
    }
    
    if ($posts_per_page < 1 || $posts_per_page > 100) {
        return new WP_Error(
            'invalid_posts_per_page',
            'Posts per page must be between 1 and 100',
            array('status' => 400)
        );
    }
    
    // Setup query arguments with author parameter
    $args = array(
        'post_type' => 'wpestate_booking',
        'post_status' => 'publish',
        'author' => $user_id,
        'paged' => $paged,
        'posts_per_page' => $posts_per_page,
        'order' => 'DESC'
    );
    
    // Execute query
    $booking_query = new WP_Query($args);
    $bookings = array();
    
    if ($booking_query->have_posts()) {
        while ($booking_query->have_posts()) {
            $booking_query->the_post();
            $booking_details = wprentals_get_booking_details(get_the_ID());
            if ($booking_details) {
                $bookings[] = $booking_details;
            }
        }
        wp_reset_postdata();
    }
    
    // Prepare pagination data
    $pagination = array(
        'current_page' => $paged,
        'posts_per_page' => $posts_per_page,
        'total_posts' => $booking_query->found_posts,
        'total_pages' => $booking_query->max_num_pages,
        'has_previous' => ($paged > 1),
        'has_next' => ($paged < $booking_query->max_num_pages)
    );
    
    // Return response
    return rest_ensure_response(array(
        'status' => 'success',
        'user_id' => $user_id,
        'bookings' => $bookings,
        'pagination' => $pagination
    ));
}





/**
 * Retrieves all bookings for a property owner with pagination support
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int user_id        The ID of the owner whose bookings to retrieve
 *     @type int page          Page number (optional, default: 1)
 *     @type int posts_per_page Number of posts per page (optional, default: 30)
 * 
 * @return WP_REST_Response|WP_Error Response containing bookings data or error
 */
function wprentals_get_owner_bookings(WP_REST_Request $request) {
    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    $user_id = intval($request['user_id']); // From URL parameter
    
    // Verify user exists
    if (!get_user_by('id', $user_id)) {
        return new WP_Error(
            'invalid_user',
            'The specified user does not exist',
            array('status' => 404)
        );
    }
    
    // Set default values for pagination
    $paged = isset($params['page']) ? intval($params['page']) : 1;
    $posts_per_page = isset($params['posts_per_page']) ? intval($params['posts_per_page']) : 30;
    
    // Validate pagination parameters
    if ($paged < 1) {
        return new WP_Error(
            'invalid_page',
            'Page number must be greater than 0',
            array('status' => 400)
        );
    }
    
    if ($posts_per_page < 1 || $posts_per_page > 100) {
        return new WP_Error(
            'invalid_posts_per_page',
            'Posts per page must be between 1 and 100',
            array('status' => 400)
        );
    }
    
    // Setup query arguments with owner_id meta query
    $args = array(
        'post_type' => 'wpestate_booking',
        'post_status' => 'publish',
        'paged' => $paged,
        'posts_per_page' => $posts_per_page,
        'order' => 'DESC',
        'meta_query' => array(
            array(
                'key' => 'owner_id',
                'value' => $user_id,
                'compare' => '='
            )
        )
    );
    
    // Execute query
    $booking_query = new WP_Query($args);
    $bookings = array();
    
    if ($booking_query->have_posts()) {
        while ($booking_query->have_posts()) {
            $booking_query->the_post();
            $booking_id = get_the_ID();
            $property_id = get_post_meta($booking_id, 'booking_id', true);
            
            // Check if the property exists and user is the owner
            $property = get_post($property_id);
            if ($property && intval($property->post_author) === $user_id) {
                $booking_details = wprentals_get_booking_details($booking_id);
                if ($booking_details) {
                    $bookings[] = $booking_details;
                }
            }
        }
        wp_reset_postdata();
    }
    
    // Prepare pagination data
    $pagination = array(
        'current_page' => $paged,
        'posts_per_page' => $posts_per_page,
        'total_posts' => $booking_query->found_posts,
        'total_pages' => $booking_query->max_num_pages,
        'has_previous' => ($paged > 1),
        'has_next' => ($paged < $booking_query->max_num_pages)
    );
    
    // Return response
    return rest_ensure_response(array(
        'status' => 'success',
        'owner_id' => $user_id,
        'bookings' => $bookings,
        'pagination' => $pagination
    ));
}