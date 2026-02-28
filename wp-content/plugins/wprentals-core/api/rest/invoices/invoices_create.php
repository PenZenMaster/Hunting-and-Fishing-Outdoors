<?php 

/**
 * Permission callback to validate access for creating invoices.
 *
 * This function ensures that the user requesting to create an invoice
 * is logged in and has either administrator privileges or the owner role.
 *
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_create_invoice() {
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
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return new WP_Error(
            'not_logged_in',
            __('You must be logged in to create invoices.'),
            ['status' => 403]
        );
    }

    // Check if user is administrator or has owner role
    if (current_user_can('administrator') || in_array('owner', $current_user->roles)) {
        return true;
    }

    return new WP_Error(
        'insufficient_permissions',
        __('Only administrators and property owners can create invoices.'),
        ['status' => 403]
    );
}








/**
 * Get invoice meta data for memebrship package-related billings.
 *
 * @param int    $property_id   The membership package ID
 * @return array  Meta data for the invoice
 */

function  wprentals_get_package_invoice_meta($item_id){
   
    $price = get_post_meta($item_id, 'pack_price', true);
    $meta_data = array(
        'item_price'        =>  $price,   
      
    );
    return $meta_data;
}



/**
 * Get invoice meta data for property-related billings.
 *
 * @param int        $property_id      The property ID
 * @param int|string $invoice_type_key Invoice type identifier or legacy label
 * @return array  Meta data for the invoice
 */


function wprentals_get_property_invoice_meta($property_id, $invoice_type_key) {
    // Get price settings
    $price_submission = floatval(wprentals_get_option('wp_estate_price_submission', ''));
    $price_featured_submission = floatval(wprentals_get_option('wp_estate_price_featured_submission', ''));

    // Calculate price based on billing type
    $price = $price_submission;
    $type_key = wpestate_get_invoice_type_key($invoice_type_key);

    switch ($type_key) {
        case WP_ESTATE_INVOICE_TYPE_LISTING:
            $price = $price_submission;
            break;

        case WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED:
            $price = $price_featured_submission;
            break;

        case WP_ESTATE_INVOICE_TYPE_PUBLISH_WITH_FEATURED:
            $price = $price_submission + $price_featured_submission;
            break;
    }

    return array(
        'item_price' => $price,
        'to_be_paid' => $price,
       
    );
}

/**
 * Get invoice meta data for booking-related billings.
 * This function calculates all the necessary meta data for a booking invoice including:
 * - Basic pricing details (base price, weekly/monthly rates)
 * - Additional fees (cleaning, city fees, security deposit)
 * - Service fees and taxes
 * - Early bird discounts
 * - Custom pricing if applicable
 *
 * @param int   $item_id         The booking ID
 * @param array $renting_details Array containing booking details (dates, guests, etc.)
 * @param array $manual_expenses Array of manual expenses to be added to the booking
 * 
 * @return array Meta data array containing all pricing and booking details including:
 *         - item_price: Base price of the booking
 *         - to_be_paid: Total amount to be paid
 *         - renting_details: Booking period and guest details
 *         - manual_expenses: Additional manual expenses
 *         - fees: All associated fees (service, cleaning, city)
 *         - taxes: Applied taxes and calculations
 *         - deposit information: Security deposit, down payment
 *         - custom pricing: Any custom price variations
 */
function wprentals_get_booking_invoice_meta($item_id, $renting_details, $manual_expenses,$invoice_status) {
    // Get booking basic information
    $booking_guests = get_post_meta($item_id, 'booking_guests', true);
    $wpestate_book_from = get_post_meta($item_id, 'booking_from_date', true);
    $wpestate_book_to = get_post_meta($item_id, 'booking_to_date', true);
    $extra_options = esc_html(get_post_meta($item_id, 'extra_options', true));
    $extra_options_array = explode(',', $extra_options);

    // Get global settings for fees and payments
    $invoice_percent = floatval(wprentals_get_option('wp_estate_book_down', '')); // Down payment percentage
    $invoice_percent_fixed_fee = floatval(wprentals_get_option('wp_estate_book_down_fixed_fee', '')); // Fixed down payment
    $service_fee_fixed_fee = floatval(wprentals_get_option('wp_estate_service_fee_fixed_fee', '')); // Fixed service fee
    $service_fee = floatval(wprentals_get_option('wp_estate_service_fee', '')); // Percentage service fee

    // Get property details and associated costs
    $property_id = get_post_meta($item_id, 'booking_id', true);
    $rented_by = get_post_field('post_author', $item_id);
    
    // Get property pricing information
    $property_taxes = floatval(get_post_meta($property_id, 'property_taxes', true));
    $default_price = get_post_meta($property_id, 'property_price', true);
    $week_price = floatval(get_post_meta($property_id, 'property_price_per_week', true));
    $month_price = floatval(get_post_meta($property_id, 'property_price_per_month', true));

    // Get additional fees
    $cleaning_fee = floatval(get_post_meta($property_id, 'cleaning_fee', true));
    $city_fee = floatval(get_post_meta($property_id, 'city_fee', true));
    $cleaning_fee_per_day = floatval(get_post_meta($listing_id, 'cleaning_fee_per_day', true));
    $city_fee_per_day = floatval(get_post_meta($listing_id, 'city_fee_per_day', true));
    $city_fee_percent = floatval(get_post_meta($listing_id, 'city_fee_percent', true));

    // Get early bird discount settings
    $early_bird_percent = floatval(get_post_meta($listing_id, 'early_bird_percent', true));
    $early_bird_days = floatval(get_post_meta($listing_id, 'early_bird_days', true));

    // Calculate booking price using all components
    $booking_array = wpestate_booking_price(
        $booking_guests,
        0, // invoice_id not yet created
        $listing_id,
        $wpestate_book_from,
        $wpestate_book_to,
        $item_id,
        $extra_options_array,
        $manual_expenses
    );

    // Build meta array with all calculated values
    $meta_array = array(
        'item_price' => $price,
        'to_be_paid' => $price,
        'renting_details' => $renting_details,
        'manual_expenses' => $manual_expenses,

        // Payment and fee settings
        'invoice_percent' => $invoice_percent,
        'invoice_percent_fixed_fee' => $invoice_percent_fixed_fee,
        'service_fee_fixed_fee' => $service_fee_fixed_fee,
        'service_fee' => $service_fee,
        
        // Property information
        'for_property' => $property_id,
        'rented_by' => $rented_by,
        'prop_taxed' => $property_taxes,
        'booking_taxes' => $booking_array['taxes'],
        
        // Price settings
        'default_price' => $default_price,
        'week_price' => $week_price,
        'month_price' => $month_price,

        // Additional fees
        'cleaning_fee' => $cleaning_fee,
        'city_fee' => $city_fee,
        'cleaning_fee_per_day' => $cleaning_fee_per_day,
        'city_fee_per_day' => $city_fee_per_day,
        'city_fee_percent' => $city_fee_percent,
        
        // Security and early bird settings
        'security_deposit' => $booking_array['security_deposit'],
        'early_bird_percent' => $early_bird_percent,
        'early_bird_days' => $early_bird_days,

        // Calculated totals
        'service_fee' => $booking_array['service_fee'],
        'youearned' => $booking_array['youearned'],
        'depozit_to_be_paid' => $booking_array['deposit'],
        'balance' => $booking_array['balance'],
        'custom_price_array' => $booking_array['custom_price_array'],
    );

    // Add balance payment status if applicable
    if ($booking_array['balance'] > 0) {
        $meta_array['invoice_status_full'] = 'waiting';
    }

    // Handle confirmed bookings
    if ($invoice_status == 'confirmed') {
        $meta_array['depozit_paid'] = 0;
        $meta_array['depozit_to_be_paid'] = 0;
    }

    return $meta_array;
}



/**
 * Create an invoice via REST API.
 *
 * @param WP_REST_Request $request The REST API request object containing:
 *     @type int    item_id          ID of booking/membership/property
 *     @type int|string billing_for  Invoice type identifier or legacy label (Listing, Package, etc.)
 *     @type int    type             Billing type (1 = One Time, 2 = Recurring)
 *     @type int    buyer_id         ID of the user being billed
 *     @type string invoice_status   Status of invoice (issued, confirmed, booking canceled by user)
 *     @type array  manual_expenses  Optional manual expenses for bookings
 * 
 * @return WP_REST_Response|WP_Error Response containing created invoice or error
 */
function wprentals_create_invoice(WP_REST_Request $request) {
    // Get current logged-in user
    $current_user = wp_get_current_user();
    $current_user_id = $current_user->ID;

    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    
    // Validate required fields
    $required_fields = ['item_id', 'billing_for', 'type', 'buyer_id', 'invoice_status'];
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (!isset($params[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        return new WP_Error(
            'missing_required_fields',
            sprintf(__('Missing required fields: %s'), implode(', ', $missing_fields)),
            ['status' => 400]
        );
    }

    // Validate invoice status
    $allowed_statuses = array(
        'issued',
        'confirmed',
        'booking canceled by user'
    );

    $billing_for_raw = sanitize_text_field($params['billing_for']);
    $invoice_status = sanitize_text_field($params['invoice_status']);
    $buyer_id       = intval($params['buyer_id']);

    $invoice_type_labels = wpestate_get_invoice_type_labels();
    $invoice_type_key    = wpestate_get_invoice_type_key($billing_for_raw);

    if ($invoice_type_key === null) {
        return new WP_Error(
            'invalid_billing_type',
            sprintf(
                __('Invalid billing type. Allowed types are: %s'),
                implode(', ', $invoice_type_labels)
            ),
            ['status' => 400]
        );
    }

    if (!in_array($invoice_status, $allowed_statuses)) {
        return new WP_Error(
            'invalid_status',
            sprintf(__('Invalid invoice status. Allowed statuses are: %s'), implode(', ', $allowed_statuses)),
            ['status' => 400]
        );
    }

    // Validate buyer exists
    if (!get_user_by('id', $buyer_id)) {
        return new WP_Error(
            'invalid_buyer',
            __('The specified buyer does not exist.'),
            ['status' => 400]
        );
    }

    // Process billing type
    $type = intval($params['type']);
    $billing_type = ($type == 2) ? esc_html__('Recurring', 'wprentals-core') : esc_html__('One Time', 'wprentals-core');

    $item_id = intval($params['item_id']);
    $manual_expenses = isset($params['manual_expenses']) ? wpestate_sanitize_array($params['manual_expenses'] ): array();
    $details         = isset($params['details']) ? wpestate_sanitize_array($params['details']) : array();


    // Verify item exists
    if (!get_post($item_id)) {
        return new WP_Error(
            'invalid_item',
            __('The specified item does not exist.'),
            ['status' => 404]
        );
    }

    // Create base invoice post
    $invoice_args = array(
        'post_title'  => esc_html__('Invoice', 'wprentals-core'),
        'post_status' => 'publish',
        'post_type'   => 'wpestate_invoice',
        'post_author' => $current_user_id
    );
    
    $invoice_id = wp_insert_post($invoice_args);
    
    if (is_wp_error($invoice_id)) {
        return new WP_Error(
            'invoice_creation_failed',
            __('Failed to create invoice.'),
            ['status' => 500]
        );
    }

    // Get meta data based on post type
    $meta_data = array();
    
    switch (get_post_type($item_id)) {
        case 'wpestate_booking':
            $meta_data = wprentals_get_booking_invoice_meta($item_id,$details, $manual_expenses,$invoice_status);
            break;

        case 'membership_package':
            $meta_data = wprentals_get_package_invoice_meta($item_id);
            break;

        case 'estate_property':
            // Handle different property-related billing types
            $meta_data = wprentals_get_property_invoice_meta($item_id, $invoice_type_key);
            break;

        default:
            return new WP_Error(
                'invalid_post_type',
                __('Invalid item type. Must be a booking, property, or membership package.'),
                ['status' => 400]
            );
    }

    // Add common meta data
    $meta_data['invoice_type'] = $invoice_type_key;
    $meta_data['biling_type'] = $billing_type;
    $meta_data['item_id'] = $item_id;
    $meta_data['invoice_status'] = $invoice_status;
    $meta_data['buyer_id'] = $buyer_id;
    $meta_data['purchase_date'] = date('Y-m-d H:i:s', time());
    $meta_data['invoice_currency'] = wpestate_curency_submission_pick();
    $meta_data['txn_id'] =     $paypal_tax_id;

   

    // Update post meta
    foreach ($meta_data as $key => $value) {
        update_post_meta($invoice_id, $key, $value);
    }

    // Update post title with invoice number
    wp_update_post(array(
        'ID' => $invoice_id,
        'post_title' => sprintf(__('Invoice %d', 'wprentals-core'), $invoice_id)
    ));


    // update booking with invoice id
    if(get_post_type($item_id) == 'wpestate_booking'){
        // Update booking 
        update_post_meta($item_id, 'booking_invoice_no', $invoice_id);
    }



    // Get complete invoice details
    $invoice_details = wprentals_get_invoice_details($invoice_id);

    // Return success response
    return rest_ensure_response(array(
        'status'  => 'success',
        'message' => __('Invoice created successfully.'),
        'invoice' => $invoice_details
    ));
}