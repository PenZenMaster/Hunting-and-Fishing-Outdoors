<?php

/**
 * Permission callback to validate access for deleting an invoice.
 *
 * This function ensures that the user requesting to delete an invoice
 * is either the owner of the invoice or an administrator.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_delete_invoice($request) {
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

    return new WP_Error(
        'rest_forbidden',
        __('You do not have permission to delete this invoice.'),
        ['status' => 403]
    );
}

/**
 * Delete an invoice via REST API
 * 
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int id The invoice ID
 * 
 * @return WP_REST_Response|WP_Error Response confirming deletion or error
 */
function wprentals_delete_invoice(WP_REST_Request $request) {
    // Get invoice ID
    $invoice_id = intval($request['id']);
    
    // Get current user
    $current_user = wp_get_current_user();
    $userID = $current_user->ID;

    // Verify the invoice exists and is correct type
    $invoice = get_post($invoice_id);
    if (!$invoice || $invoice->post_type !== 'wpestate_invoice') {
        return new WP_Error(
            'invalid_invoice',
            'Invalid invoice ID or incorrect post type',
            array('status' => 400)
        );
    }

    // Get related booking ID and verify permissions
    $booking_id = get_post_meta($invoice_id, 'item_id', true);
    $invoice_type_value = get_post_meta($invoice_id, 'invoice_type', true);
    $invoice_type_key   = wpestate_get_invoice_type_key($invoice_type_value);
    
    

    // Get invoice details before deletion for notification purposes
    $invoice_details = wprentals_get_invoice_details($invoice_id);
    
    // If this is a booking invoice, update the booking's invoice reference
    if ($booking_id && $invoice_type_key === WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE) {
        update_post_meta($booking_id, 'booking_invoice_no', 0);
    }

    // Delete the invoice
    $result = wp_delete_post($invoice_id, true);
    
    if (!$result) {
        return new WP_Error(
            'deletion_failed',
            'Failed to delete the invoice',
            array('status' => 500)
        );
    }

    // Return success response
    return rest_ensure_response(array(
        'status' => 'success',
        'message' => 'Invoice deleted successfully',
        'invoice_id' => $invoice_id,
        'deleted_invoice' => $invoice_details
    ));
}