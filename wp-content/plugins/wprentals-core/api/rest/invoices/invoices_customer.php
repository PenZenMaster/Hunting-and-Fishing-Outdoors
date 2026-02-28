<?php 

/**
 * Permission callback to validate access to customer invoices.
 *
 * This function ensures that the user requesting access to customer invoices
 * is either the customer themselves or an administrator.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_invoices_for_customer($request) {
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

    $current_user = wp_get_current_user();
    $userID = $current_user->ID;

    $requested_user_id = intval($request['user_id']);

    // Check if the user is the same as the requested user ID or is an administrator
    if ($userID === $requested_user_id || current_user_can('administrator')) {
        return true;
    }

    return new WP_Error(
        'rest_forbidden',
        __('You do not have permission to access these invoices.'),
        ['status' => 403]
    );
}


/**
 * Retrieve all invoices for a customer with pagination support
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int    user_id        The ID of the customer whose invoices to retrieve
 *     @type string start_date     Start date for filtering (optional)
 *     @type string end_date       End date for filtering (optional)
 *     @type string type          Invoice type filter (optional)
 *     @type string status        Invoice status filter (optional)
 *     @type int    page          Page number (optional, default: 1)
 *     @type int    posts_per_page Number of posts per page (optional, default: 10)
 * 
 * @return WP_REST_Response|WP_Error Response containing filtered invoices data or error
 */
function wprentals_get_customer_invoices(WP_REST_Request $request) {
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
    
    // Set pagination defaults
    $paged = isset($params['page']) ? intval($params['page']) : 1;
    $posts_per_page = isset($params['posts_per_page']) ? intval($params['posts_per_page']) : 10;
    
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

    // Build meta query
    $meta_query = array(
        'relation' => 'AND',
        array(
            'key'     => 'buyer_id',
            'value'   => $user_id,
            'type'    => 'NUMERIC',
            'compare' => '='
        )
    );

    $requested_type_key = null;
    $requested_type_raw = null;

    // Add type filter if provided
    if (!empty($params['type'])) {
        $requested_type_raw = sanitize_text_field($params['type']);
        $type_key           = wpestate_get_invoice_type_key($requested_type_raw);

        if ($type_key !== null) {
            $legacy_values = wpestate_get_invoice_type_legacy_values($type_key);
            $type_filter   = array(
                'relation' => 'OR',
                array(
                    'key'     => 'invoice_type',
                    'value'   => $type_key,
                    'type'    => 'NUMERIC',
                    'compare' => '=',
                ),
            );

            if (!empty($legacy_values)) {
                $type_filter[] = array(
                    'key'     => 'invoice_type',
                    'value'   => $legacy_values,
                    'type'    => 'CHAR',
                    'compare' => 'IN',
                );
            }

            $meta_query[]      = $type_filter;
            $requested_type_key = $type_key;
        } else {
            $meta_query[] = array(
                'key'     => 'invoice_type',
                'value'   => $requested_type_raw,
                'type'    => 'CHAR',
                'compare' => 'LIKE',
            );
        }
    }

    // Add status filter if provided
    if (!empty($params['status'])) {
        $meta_query[] = array(
            'key'     => 'invoice_status',
            'value'   => $params['status'],
            'compare' => '='
        );
    }

    // Build date query
    $date_query = array();
    
    if (!empty($params['start_date'])) {
        $date_query['after'] = sanitize_text_field($params['start_date']);
    }
    
    if (!empty($params['end_date'])) {
        $date_query['before'] = sanitize_text_field($params['end_date']);
    }
    
    if (!empty($date_query)) {
        $date_query['inclusive'] = true;
    }

    // Setup query arguments
    $args = array(
        'post_type'      => 'wpestate_invoice',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'meta_query'     => $meta_query,
        'date_query'     => $date_query,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    // Execute query
    $invoice_query = new WP_Query($args);
    $invoices = array();
    
    if ($invoice_query->have_posts()) {
        while ($invoice_query->have_posts()) {
            $invoice_query->the_post();
            $invoices[] = wprentals_get_invoice_details(get_the_ID());
        }
        wp_reset_postdata();
    }

    // Calculate totals
    $totals = wprentals_calculate_invoice_totals($invoices);
    
    // Prepare pagination data
    $pagination = array(
        'current_page'   => $paged,
        'posts_per_page' => $posts_per_page,
        'total_posts'    => $invoice_query->found_posts,
        'total_pages'    => $invoice_query->max_num_pages,
        'has_previous'   => ($paged > 1),
        'has_next'       => ($paged < $invoice_query->max_num_pages)
    );
    
    // Return response
    return rest_ensure_response(array(
        'status'     => 'success',
        'user_id'    => $user_id,
        'invoices'   => $invoices,
        'pagination' => $pagination,
        'totals'     => $totals,
        'filters'    => array(
            'type'       => $requested_type_key !== null ? $requested_type_key : $requested_type_raw,
            'status'     => $params['status'] ?? null,
            'start_date' => $params['start_date'] ?? null,
            'end_date'   => $params['end_date'] ?? null
        )
    ));
}