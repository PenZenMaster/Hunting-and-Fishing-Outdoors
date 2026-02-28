<?php 

/**
 * Update invoice meta fields via REST API.
 *
 * @param WP_REST_Request $request The REST request object containing:
 *     @type int    id                 The invoice ID
 *     @type string invoice_status     New invoice status (optional)
 *     @type string invoice_status_full New full invoice status (optional)
 *     @type float  depozit_paid      New deposit paid amount (optional)
 * 
 * @return WP_REST_Response|WP_Error Response confirming update or error
 */
function wprentals_update_invoice(WP_REST_Request $request) {
    // Get and validate invoice ID
    $invoice_id = intval($request['id']);
    
    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    
    // Verify invoice exists and is correct type
    $invoice = get_post($invoice_id);
    if (!$invoice || $invoice->post_type !== 'wpestate_invoice') {
        return new WP_Error(
            'invalid_invoice',
            'Invalid invoice ID or incorrect post type',
            array('status' => 400)
        );
    }

    // Define allowed status values
    $allowed_invoice_status = array('issued', 'confirmed', 'canceled');
    $allowed_invoice_status_full = array('confirmed', 'pending', 'canceled');
    
    // Get current values
    $current_status = get_post_meta($invoice_id, 'invoice_status', true);
    $current_status_full = get_post_meta($invoice_id, 'invoice_status_full', true);
    $current_deposit = floatval(get_post_meta($invoice_id, 'depozit_paid', true));
    
    $changes = array();
    
    // Update invoice_status if provided
    if (isset($params['invoice_status'])) {
        $new_status = sanitize_text_field($params['invoice_status']);
        
        if (!in_array($new_status, $allowed_invoice_status)) {
            return new WP_Error(
                'invalid_status',
                'Invalid invoice_status. Allowed values: ' . implode(', ', $allowed_invoice_status),
                array('status' => 400)
            );
        }
        
        if ($new_status !== $current_status) {
            update_post_meta($invoice_id, 'invoice_status', $new_status);
            $changes['invoice_status'] = array(
                'from' => $current_status,
                'to' => $new_status
            );
        }
    }
    
    // Update invoice_status_full if provided
    if (isset($params['invoice_status_full'])) {
        $new_status_full = sanitize_text_field($params['invoice_status_full']);
        
        if (!in_array($new_status_full, $allowed_invoice_status_full)) {
            return new WP_Error(
                'invalid_status_full',
                'Invalid invoice_status_full. Allowed values: ' . implode(', ', $allowed_invoice_status_full),
                array('status' => 400)
            );
        }
        
        if ($new_status_full !== $current_status_full) {
            update_post_meta($invoice_id, 'invoice_status_full', $new_status_full);
            $changes['invoice_status_full'] = array(
                'from' => $current_status_full,
                'to' => $new_status_full
            );
        }
    }
    
    // Update depozit_paid if provided
    if (isset($params['depozit_paid'])) {
        $new_deposit = floatval($params['depozit_paid']);
        
        if ($new_deposit < 0) {
            return new WP_Error(
                'invalid_deposit',
                'Deposit paid amount cannot be negative',
                array('status' => 400)
            );
        }
        
        if ($new_deposit !== $current_deposit) {
            update_post_meta($invoice_id, 'depozit_paid', $new_deposit);
            $changes['depozit_paid'] = array(
                'from' => $current_deposit,
                'to' => $new_deposit
            );
        }
    }
    
    // If no changes were made
    if (empty($changes)) {
        return rest_ensure_response(array(
            'status' => 'success',
            'message' => 'No changes were necessary',
            'invoice_id' => $invoice_id,
            'current_values' => array(
                'invoice_status' => $current_status,
                'invoice_status_full' => $current_status_full,
                'depozit_paid' => $current_deposit
            )
        ));
    }

    // Get updated invoice details
    $invoice_details = wprentals_get_invoice_details($invoice_id);
    
    // Return success response with changes
    return rest_ensure_response(array(
        'status' => 'success',
        'message' => 'Invoice updated successfully',
        'invoice_id' => $invoice_id,
        'changes' => $changes,
        'invoice' => $invoice_details
    ));
}