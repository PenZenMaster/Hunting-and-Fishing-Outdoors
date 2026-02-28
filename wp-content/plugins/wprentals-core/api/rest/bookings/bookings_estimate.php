<?php

/**
 * Estimate booking costs.
 *
 * This function estimates the costs for a booking based on the provided parameters.
 * It includes calculations for extra options, manual expenses, and discounts.
 *
 * @param WP_REST_Request $request The REST API request object.
 *
 * @return WP_REST_Response|WP_Error The response containing booking cost details or an error.
 */
function wprentals_estimate_booking_costs(WP_REST_Request $request) {
    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    
    // Required parameters validation
    if (empty($params['property_id']) || empty($params['guest_no']) || 
        empty($params['fromdate']) || empty($params['todate'])) {
        return new WP_Error(
            'missing_parameters',
            'Missing required parameters. Need: property_id, guest_no, fromdate, todate',
            array('status' => 400)
        );
    }

    // Extract basic parameters
    $property_id = intval($params['property_id']);
    $guest_no = intval($params['guest_no']);
    $guest_fromone = isset($params['guest_fromone']) ? intval($params['guest_fromone']) : 0;
    $booking_from_date = sanitize_text_field($params['fromdate']);
    $booking_to_date = sanitize_text_field($params['todate']);
    $invoice_id = 0;
    
    // Verify property exists and is of correct type
    $property = get_post($property_id);
    if (!$property || $property->post_type !== 'estate_property') {
        return new WP_Error(
            'invalid_property',
            'The specified property does not exist',
            array('status' => 404)
        );
    }

    // Fetch property price
    $price_per_day = floatval(get_post_meta($property_id, 'property_price', true));
    
    // Get booking and rental types
    $booking_type = wprentals_return_booking_type($property_id);
    $rental_type = wprentals_get_option('wp_estate_item_rental_type');
    
    // Process extra options
    $extra_options = sanitize_text_field($params['extra_options']);
    $extra_options_array = explode(',', $extra_options);
    $extra_pay_options = get_post_meta($property_id, 'extra_pay_options', true);

    // Process manual expenses
    $manual_expenses = array();
    if (isset($params['manual_expenses'])) {
        $manual_expenses = wpestate_sanitize_array($params['manual_expenses']);
    }

    // Calculate booking costs
    $booking_array = wpestate_booking_price(
        $guest_no,
        $invoice_id,
        $property_id,
        $booking_from_date, 
        $booking_to_date,
        $property_id,
        $extra_options_array,
        $manual_expenses,
        $guest_no
    );
 
    // Process extra options for response
    $return_extra_options = array();
    foreach ($extra_options_array as $key => $value) {
        if (isset($extra_pay_options[$value][0])) {
            $extra_option_value = wpestate_calculate_extra_options_value(
                $booking_array['count_days'],
                $guest_no, 
                floatval($extra_pay_options[$value][2]), 
                floatval($extra_pay_options[$value][1])
            );
                
            $return_extra_options[] = array(
                'name' => $extra_pay_options[$value][0],
                'value' => $extra_option_value
            );               
        }
    }

    // Get currency settings
    $wpestate_where_currency = wprentals_get_option('wp_estate_where_currency_symbol', '');
    $wpestate_currency = wpestate_curency_submission_pick();
    
    // Fetch additional property settings
    $include_expenses = wprentals_get_option('wp_estate_include_expenses', '');
    $security_deposit = floatval(get_post_meta($property_id, 'security_deposit', true));
    $price_per_weekend = floatval(get_post_meta($property_id, 'price_per_weekeend', true));
    
    // Calculate total price components
    $total_price_comp = floatval($booking_array['total_price']);
    $total_price_comp2 = ($include_expenses == 'yes') ? 
        $total_price_comp : 
        ($total_price_comp - $booking_array['city_fee'] - $booking_array['cleaning_fee']);
    
    // Calculate deposit and balance
    $wp_estate_book_down = floatval(wprentals_get_option('wp_estate_book_down', ''));
    $wp_estate_book_down_fixed_fee = floatval(wprentals_get_option('wp_estate_book_down_fixed_fee', ''));
    $deposit = floatval(wpestate_calculate_deposit($wp_estate_book_down, $wp_estate_book_down_fixed_fee, $total_price_comp2));
    $balance = $total_price_comp - $deposit;

    // Prepare response data
    $response = array(
        'booking_details' => array(
            'property_id' => $property_id,
            'property_title' => get_the_title($property_id),
            'period' => array(
                'from_date' => wpestate_convert_dateformat_reverse($booking_from_date),
                'to_date' => wpestate_convert_dateformat_reverse($booking_to_date),
                'days' => $booking_array['count_days']
            ),
            'guests' => $guest_no,
            'guest_fromone' => $guest_fromone,
            'booking_type' => $booking_type,
            'rental_type' => $rental_type
        ),
        'prices' => array(
            'price_per_day' => $price_per_day,
            'total_price' => $total_price_comp,
            'deposit' => $deposit,
            'balance' => $balance,
            'security_deposit' => $security_deposit,
            'city_fee' => $booking_array['city_fee'],
            'cleaning_fee' => $booking_array['cleaning_fee'],
            'early_bird_discount' => $booking_array['early_bird_discount'],
            'extra_options' => $return_extra_options,
            'manual_expenses' => $manual_expenses
        ),
        'extra_price_details' => array(
            'extra_price_per_guest' => isset($booking_array['extra_price_per_guest']) ? 
                wpestate_show_price_booking($booking_array['extra_price_per_guest'], $wpestate_currency, $wpestate_where_currency, 1) : 0,
            'custom_period_quest' => $booking_array['custom_period_quest'] ?? 0,
            'has_custom' => $booking_array['has_custom'] ?? 0,
            'custom_price_array' => $booking_array['custom_price_array'] ?? array()
        ),
        'taxes_details' => array(
            'taxes' => $booking_array['taxes'],
            'you_earn' => $booking_array['youearned'],
            'service_fee' => $booking_array['service_fee']
        ),
        'currency' => array(
            'currency_symbol' => $wpestate_currency,
            'where_currency' => $wpestate_where_currency,
            'include_expenses' => $include_expenses
        )
    );
    
    return rest_ensure_response($response);
}
