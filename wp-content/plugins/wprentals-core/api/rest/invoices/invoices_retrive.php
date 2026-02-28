<?php

/* Retrieve all invoices with filtering and pagination support.
 *
 * @param WP_REST_Request $request The REST request object containing:
 *     @type string start_date     Start date for filtering (optional)
 *     @type string end_date       End date for filtering (optional)
 *     @type string type          Invoice type filter (optional)
 *     @type string status        Invoice status filter (optional)
 *     @type int    page          Page number (optional, default: 1)
 *     @type int    posts_per_page Number of posts per page (optional, default: 10)
 * 
 * @return WP_REST_Response|WP_Error Response containing filtered invoices data or error
 */
function wprentals_get_all_invoices(WP_REST_Request $request) {
    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    
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
    $meta_query = array();

    // Add type filter
    if (!empty($params['type'])) {
        $requested_type = sanitize_text_field($params['type']);
        $type_key       = wpestate_get_invoice_type_key($requested_type);

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

            $meta_query[] = $type_filter;
        } else {
            $meta_query[] = array(
                'key'     => 'invoice_type',
                'value'   => $requested_type,
                'type'    => 'CHAR',
                'compare' => 'LIKE',
            );
        }
    }

    // Add status filter
    if (!empty($params['status'])) {
        $meta_query[] = array(
            'key'     => 'invoice_status',
            'value'   => $params['status'],
            'type'    => 'char',
            'compare' => 'LIKE'
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
        'date_query'     => $date_query
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
    
    return rest_ensure_response(array(
        'status'     => 'success',
        'invoices'   => $invoices,
        'pagination' => $pagination,
        'totals'     => $totals
    ));
}

/**
 * Retrieve all invoices for a property owner with pagination support.
 *
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int    user_id        The ID of the owner whose invoices to retrieve
 *     @type string start_date     Start date for filtering (optional)
 *     @type string end_date       End date for filtering (optional)
 *     @type string type          Invoice type filter (optional)
 *     @type string status        Invoice status filter (optional)
 *     @type int    page          Page number (optional, default: 1)
 *     @type int    posts_per_page Number of posts per page (optional, default: 10)
 * 
 * @return WP_REST_Response|WP_Error Response containing filtered invoices data or error
 */
function wprentals_get_owner_invoices(WP_REST_Request $request) {
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
            'key'     => 'owner_id',
            'value'   => $user_id,
            'type'    => 'NUMERIC',
            'compare' => '='
        )
    );

    // Add type filter
    if (!empty($params['type'])) {
        $requested_type = sanitize_text_field($params['type']);
        $type_key       = wpestate_get_invoice_type_key($requested_type);

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

            $meta_query[] = $type_filter;
        } else {
            $meta_query[] = array(
                'key'     => 'invoice_type',
                'value'   => $requested_type,
                'type'    => 'CHAR',
                'compare' => 'LIKE',
            );
        }
    }

    // Add status filter
    if (!empty($params['status'])) {
        $meta_query[] = array(
            'key'     => 'invoice_status',
            'value'   => $params['status'],
            'type'    => 'char',
            'compare' => 'LIKE'
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
        'date_query'     => $date_query
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
        '$args '=> $args ,
        'owner_id'   => $user_id,
        'invoices'   => $invoices,
        'pagination' => $pagination,
        'totals'     => $totals
    ));
}


/**
 * Retrieve a single invoice via REST API.
 *
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int id The invoice ID
 * 
 * @return WP_REST_Response|WP_Error Response containing invoice data or error
 */
function wprentals_get_single_invoice(WP_REST_Request $request) {
    // Get invoice ID
    $invoice_id = intval($request['id']);
    
    // Verify the invoice exists and is correct type
    $invoice = get_post($invoice_id);
    if (!$invoice || $invoice->post_type !== 'wpestate_invoice') {
        return new WP_Error(
            'invalid_invoice',
            'Invalid invoice ID or incorrect post type',
            array('status' => 404)
        );
    }

    // Get invoice details
    $invoice_details = wprentals_get_invoice_details($invoice_id);
    if (!$invoice_details) {
        return new WP_Error(
            'invoice_retrieval_failed',
            'Failed to retrieve invoice details',
            array('status' => 500)
        );
    }
    
    // Return success response
    return rest_ensure_response(array(
        'status' => 'success',
        'invoice' => $invoice_details
    ));
}