<?php 

/**
 * Permission callback to validate access to invoices for a user.
 *
 * This function ensures that the user requesting access to invoices
 * is either the owner of the invoices or the owner of the associated property.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_invoices_for_user($request) {
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
 * Generate invoice details from an invoice post.
 * 
 * @param int $invoice_id The ID of the invoice post
 * @return array Invoice details array
 */
function wprentals_get_invoice_details($invoice_id) {
    // Get base invoice details
    $status          = get_post_meta($invoice_id, 'invoice_status', true);
    $type_value      = get_post_meta($invoice_id, 'invoice_type', true);
    $type_key        = wpestate_get_invoice_type_key($type_value);
    $type_label      = wpestate_get_invoice_type_label($type_value);
    $price = floatval(get_post_meta($invoice_id, 'item_price', true));

    $invoice_period_saved  = 'not aplicable';
    $item_id = esc_html(get_post_meta($invoice_id, 'item_id', true));
    $item_price = '';
    $purchase_date = '';

    // Handle different invoice types
    if (
        $type_key === WP_ESTATE_INVOICE_TYPE_LISTING ||
        $type_key === WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED ||
        $type_key === WP_ESTATE_INVOICE_TYPE_PUBLISH_WITH_FEATURED
    ) {

        $item_price = esc_html(get_post_meta($invoice_id, 'item_price', true));
        $purchase_date = esc_html(get_post_meta($invoice_id, 'purchase_date', true));
    } else if ($type_key === WP_ESTATE_INVOICE_TYPE_PACKAGE) {
        $invoice_period_saved = esc_html(get_post_meta($invoice_id, 'biling_type', true));

        $item_price = esc_html(get_post_meta($invoice_id, 'item_price', true));
        $purchase_date = esc_html(get_post_meta($invoice_id, 'purchase_date', true));
    }

    $booking_id = intval(get_post_meta($invoice_id, 'item_id', true));
    $property_id = intval(get_post_meta($booking_id, 'booking_id', true)); // property_id

    // Return structured invoice data
    return array(
        'id'            => $invoice_id,
        'title'         => get_the_title($invoice_id),
        'date'          => get_the_date('Y-m-d H:i:s', $invoice_id),
        'status'        => $status,
        'type'          => $type_label,
        'type_key'      => $type_key,
        'item_id'       => $item_id,
        'item_price'    => $item_price,
        'price'         => $price,
        'purchase_date' => $purchase_date,
        'invoice_period_saved' => $invoice_period_saved,
        'author'        => get_post_field('post_author', $invoice_id),
        'booking_id'    => $booking_id,
        'property_id'   => $property_id,
    );
}

/**
 * Calculate invoice totals from an array of invoices.
 * 
 * @param array $invoices Array of invoice details
 * @return array Totals array with confirmed and issued amounts
 */
function wprentals_calculate_invoice_totals($invoices) {
    $total_confirmed = 0;
    $total_issued = 0;

    foreach ($invoices as $invoice) {
        $invoice_type_key = $invoice['type_key'] ?? wpestate_get_invoice_type_key($invoice['type'] ?? null);

        if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE) {
            if ($invoice['status'] == 'confirmed') {
                $total_confirmed += $invoice['price'];
            }
            if ($invoice['status'] == 'issued') {
                $total_issued += $invoice['price'];
            }
        } else {
            $total_confirmed += $invoice['price'];
        }
    }

    return array(
        'total_confirmed' => $total_confirmed,
        'total_issued'    => $total_issued
    );
}


/**
 * Permission callback to validate access to a single invoice.
 *
 * This function ensures that the user requesting access to an invoice is either:
 * - An administrator
 * - The owner of the invoice
 * - The buyer associated with the invoice
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_get_single_invoice($request) {
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

    // Get current user
    $current_user = wp_get_current_user();
    $userID = $current_user->ID;

    // Allow administrators
    if (current_user_can('administrator')) {
        return true;
    }

    // Get invoice ID from request
    $invoice_id = intval($request['id']);
    
    // Verify invoice exists and is correct type
    $invoice = get_post($invoice_id);
    if (!$invoice || $invoice->post_type !== 'wpestate_invoice') {
        return new WP_Error(
            'invalid_invoice',
            __('Invalid invoice ID or incorrect post type.'),
            ['status' => 404]
        );
    }

    // Check if user is the invoice owner
    if ($invoice->post_author == $userID) {
        return true;
    }

    // Check if user is the buyer
    $buyer_id = get_post_meta($invoice_id, 'buyer_id', true);
    if ($buyer_id && $buyer_id == $userID) {
        return true;
    }

    return new WP_Error(
        'rest_forbidden',
        __('You do not have permission to access this invoice.'),
        ['status' => 403]
    );
}