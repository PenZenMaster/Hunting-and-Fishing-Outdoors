<?php 

/**
 * Validates booking dates format and logic.
 *
 * @param string $book_from Start date
 * @param string $book_to End date
 * @return array|WP_Error Success array or error
 */
function validate_booking_dates($book_from, $book_to) {
    if (empty($book_from) || empty($book_to)) {
        return new WP_Error('invalid_dates', 'Start and end dates are required');
    }

    // Get WordPress date format and show it in the error
    $wp_date_format = get_option('date_format');

    
    try {
        // Create date objects using WordPress date format
        $from_date = DateTime::createFromFormat($wp_date_format, $book_from);
        $to_date = DateTime::createFromFormat($wp_date_format, $book_to);
        
        // Check if date creation was successful
        if (!$from_date || !$to_date) {
            return new WP_Error('invalid_date_format', 
                sprintf('Dates must be in the format: %s (example: 2025-04-14). Your dates were: %s and %s', 
                    $wp_date_format, 
                    $book_from, 
                    $book_to
                )
            );
        }

        // Reset time parts
        $from_date->setTime(0, 0, 0);
        $to_date->setTime(0, 0, 0);
        
        if ($from_date > $to_date) {
            return new WP_Error('invalid_date_range', 'Start date must be before end date');
        }

        $today = new DateTime();
        $today->setTime(0, 0, 0);

        if ($from_date < $today) {
            return new WP_Error('past_date', 'Cannot book dates in the past');
        }

        return [
            'from_date' => $from_date,
            'to_date' => $to_date,
            'from_unix' => $from_date->getTimestamp(),
            'to_unix' => $to_date->getTimestamp()
        ];
    } catch (Exception $e) {
        return new WP_Error('invalid_date_format', 
            sprintf('Dates must be in the format: %s (example: 2025-04-14). Your dates were: %s and %s', 
                $wp_date_format, 
                $book_from, 
                $book_to
            )
        );
    }
}



/**
 * Validates minimum stay requirements.
 *
 * @param int $property_id Property ID
 * @param DateTime $from_date Start date
 * @param DateTime $to_date End date
 * @param array $custom_rules Custom period rules if any
 * @param bool $internal Whether this is an internal booking
 * @return true|WP_Error True if valid, WP_Error if not
 */
/**
 * Validates minimum stay requirements.
 *
 * @param int $property_id Property ID
 * @param DateTime $from_date Start date
 * @param DateTime $to_date End date
 * @param array $custom_rules Custom period rules if any
 * @param bool $internal Whether this is an internal booking
 * @return true|WP_Error True if valid, WP_Error if not
 */
function validate_min_stay($property_id, $from_date, $to_date, $custom_rules = [], $internal = false) {
    if ($internal) {
        return true;
    }

    $from_unix = $from_date->getTimestamp();
    $to_unix = $to_date->getTimestamp();
    $booking_type = wprentals_return_booking_type($property_id);
    $diff = $booking_type == 2 ? 3600 : 86400;
    
    // Check custom period rules first
    $date_checker = strtotime(date("Y-m-d 00:00", $from_unix));
    if (!empty($custom_rules) && isset($custom_rules[$date_checker]['period_min_days_booking'])) {
        $min_days = $custom_rules[$date_checker]['period_min_days_booking'];
        $stay_days = abs($to_unix - $from_unix) / $diff;
        if ($stay_days < $min_days) {
            return new WP_Error(
                'min_stay_not_met', 
                sprintf('Custom period minimum stay requirement not met. Minimum stay required: %d days, Selected stay: %d days', 
                    $min_days, 
                    intval($stay_days)
                )
            );
        }
    } else {
        // Check default minimum stay
        $min_days = intval(get_post_meta($property_id, 'min_days_booking', true));
        if ($min_days > 0) {
            $stay_days = abs($to_unix - $from_unix) / $diff;
            if ($stay_days < $min_days) {
                return new WP_Error(
                    'min_stay_not_met', 
                    sprintf('Minimum stay requirement not met. Minimum stay required: %d days, Selected stay: %d days', 
                        $min_days, 
                        intval($stay_days)
                    )
                );
            }
        }
    }

    return true;
}





/**
 * Get day name from number (1-7).
 * 
 * @param int $day_number Day number (1=Monday, 7=Sunday)
 * @return string Day name
 */
function get_day_name($day_number) {
    $days = [
        1 => 'Monday',
        2 => 'Tuesday', 
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday'
    ];
    return $days[$day_number] ?? 'Unknown';
}

/**
 * Validates check-in day restrictions.
 *
 * @param int $property_id Property ID
 * @param DateTime $from_date Start date
 * @param array $custom_rules Custom period rules if any
 * @return true|WP_Error True if valid, WP_Error if not
 */
function validate_checkin_rules($property_id, $from_date, $custom_rules = []) {
    $from_unix = $from_date->getTimestamp();
    $weekday = date('N', $from_unix);

    // Check custom period rules first
    if (!empty($custom_rules) && isset($custom_rules[$from_unix]['period_checkin_change_over']) 
        && $custom_rules[$from_unix]['period_checkin_change_over'] != 0) {
        $allowed_day = $custom_rules[$from_unix]['period_checkin_change_over'];
        if ($weekday != $allowed_day) {
            return new WP_Error(
                'invalid_checkin', 
                sprintf('Selected check-in day not allowed for this period. Allowed: %s, Selected: %s',
                    get_day_name($allowed_day),
                    get_day_name($weekday)
                )
            );
        }
    } else {
        // Check default check-in rules
        $checkin_change_over = floatval(get_post_meta($property_id, 'checkin_change_over', true));
        if ($checkin_change_over > 0 && $weekday != $checkin_change_over) {
            return new WP_Error(
                'invalid_checkin', 
                sprintf('Selected check-in day not allowed. Allowed: %s, Selected: %s',
                    get_day_name($checkin_change_over),
                    get_day_name($weekday)
                )
            );
        }
    }

    return true;
}

/**
 * Validates check-out day restrictions.
 *
 * @param int $property_id Property ID
 * @param DateTime $from_date Start date
 * @param DateTime $to_date End date
 * @param array $custom_rules Custom period rules if any
 * @return true|WP_Error True if valid, WP_Error if not
 */
function validate_checkout_rules($property_id, $from_date, $to_date, $custom_rules = []) {
    $from_unix = $from_date->getTimestamp();
    $to_unix = $to_date->getTimestamp();
    $weekday = date('N', $from_unix);
    $end_weekday = date('N', $to_unix);

    // Check custom period rules first
    if (!empty($custom_rules) && isset($custom_rules[$from_unix]['period_checkin_checkout_change_over']) 
        && $custom_rules[$from_unix]['period_checkin_checkout_change_over'] != 0) {
        $allowed_day = $custom_rules[$from_unix]['period_checkin_checkout_change_over'];
        if ($weekday != $allowed_day || $end_weekday != $allowed_day) {
            return new WP_Error(
                'invalid_checkout', 
                sprintf('Selected check-in/out days not allowed for this period. Allowed: %s, Selected check-in: %s, Selected check-out: %s',
                    get_day_name($allowed_day),
                    get_day_name($weekday),
                    get_day_name($end_weekday)
                )
            );
        }
    } else {
        // Check default check-in/out rules
        $checkin_checkout_change_over = floatval(get_post_meta($property_id, 'checkin_checkout_change_over', true));
        if ($checkin_checkout_change_over > 0 && ($weekday != $checkin_checkout_change_over || $end_weekday != $checkin_checkout_change_over)) {
            return new WP_Error(
                'invalid_checkout', 
                sprintf('Selected check-in/out days not allowed. Allowed: %s, Selected check-in: %s, Selected check-out: %s',
                    get_day_name($checkin_checkout_change_over),
                    get_day_name($weekday),
                    get_day_name($end_weekday)
                )
            );
        }
    }

    return true;
}






/**
 * Gets all booking rules for a property.
 *
 * @param int $property_id Property ID
 * @return array Property booking rules
 */
function get_property_rules($property_id) {
    return [
        'min_days' => intval(get_post_meta($property_id, 'min_days_booking', true)),
        'checkin_change_over' => floatval(get_post_meta($property_id, 'checkin_change_over', true)),
        'checkin_checkout_change_over' => floatval(get_post_meta($property_id, 'checkin_checkout_change_over', true)),
        'booking_type' => wprentals_return_booking_type($property_id)
    ];
}

/**
 * Gets custom period rules for a property.
 *
 * @param int $property_id Property ID
 * @param int $from_unix Start date unix timestamp
 * @return array Custom period rules
 */
function get_custom_period_rules($property_id, $from_unix) {
    $mega = wpml_mega_details_adjust($property_id);
    $date_checker = strtotime(date("Y-m-d 00:00", $from_unix));
    
    if (is_array($mega) && array_key_exists($date_checker, $mega)) {
        return $mega;
    }
    
    return [];
}











/**
 * Check booking availability for a property.
 *
 * @param WP_REST_Request|array $request Request object or array
 * @return array|WP_REST_Response Array for internal calls, WP_REST_Response for API calls
 */
function wprentals_get_booking_availability($request, $is_api_call = true) {
    // Get and validate parameters
    $params = wprentals_parse_request_params($request);
    
    if (empty($params['book_from']) || empty($params['book_to']) || empty($params['listing_id'])) {
        $response = [
            'status' => 'error',
            'message' => 'Missing required parameters: book_from, book_to, or listing_id'
        ];
        return $is_api_call ? new WP_REST_Response($response, 400) : $response;
    }

    $listing_id = intval($params['listing_id']);
    $book_from = sanitize_text_field($params['book_from']);
    $book_to = sanitize_text_field($params['book_to']);
    $internal = isset($params['internal']) ? intval($params['internal']) : 0;
    
    // Validate dates
    $dates = validate_booking_dates($book_from, $book_to);
    if (is_wp_error($dates)) {
        $response = [
            'status' => 'error',
            'message' => $dates->get_error_message()
        ];
        return $is_api_call ? new WP_REST_Response($response, 400) : $response;
    }

    // Get property and custom rules
    $property_rules = get_property_rules($listing_id);
    $custom_rules = get_custom_period_rules($listing_id, $dates['from_unix']);
    
    // Check minimum stay requirements
    $min_stay_check = validate_min_stay(
        $listing_id, 
        $dates['from_date'], 
        $dates['to_date'], 
        $custom_rules, 
        $internal
    );
    if (is_wp_error($min_stay_check)) {
        $response = [
            'status' => 'error',
            'message' => $min_stay_check->get_error_message()
        ];
        return $is_api_call ? new WP_REST_Response($response, 400) : $response;
    }
    
    // Check check-in rules
    $checkin_check = validate_checkin_rules($listing_id, $dates['from_date'], $custom_rules);
    if (is_wp_error($checkin_check)) {
        $response = [
            'status' => 'error',
            'message' => $checkin_check->get_error_message()
        ];
        return $is_api_call ? new WP_REST_Response($response, 400) : $response;
    }
    
    // Check check-out rules
    $checkout_check = validate_checkout_rules(
        $listing_id, 
        $dates['from_date'], 
        $dates['to_date'], 
        $custom_rules
    );
    if (is_wp_error($checkout_check)) {
        $response = [
            'status' => 'error',
            'message' => $checkout_check->get_error_message()
        ];
        return $is_api_call ? new WP_REST_Response($response, 400) : $response;
    }
    
    // Get existing reservations
    $reservation_array = get_post_meta($listing_id, 'booking_dates', true);
    if (empty($reservation_array)) {
        $reservation_array = wpestate_get_booking_dates($listing_id);
    }
    
    // Check if start date is already booked
    if (array_key_exists($dates['from_unix'], $reservation_array)) {
        $response = [
            'status' => 'error',
            'message' => 'Selected start date is not available'
        ];
        return $is_api_call ? new WP_REST_Response($response, 400) : $response;
    }
    
    // Adjust to_date for daily bookings and check overlaps
    if ($property_rules['booking_type'] == 2) {
        // Hourly booking overlap check
        if (wprentals_check_hour_booking_overlap_reservations(
            $dates['from_unix'],
            $dates['to_unix'],
            $reservation_array
        )) {
            $response = [
                'status' => 'error',
                'message' => 'Selected time period overlaps with existing bookings'
            ];
            return $is_api_call ? new WP_REST_Response($response, 400) : $response;
        }
    } else {
        // Daily booking overlap check
        $dates['to_date']->modify('yesterday');
        if (wprentals_check_booking_overlap_reservations(
            $dates['from_date'],
            $dates['from_unix'],
            $dates['to_date']->getTimestamp(),
            $reservation_array
        )) {
            $response = [
                'status' => 'error',
                'message' => 'Selected dates overlap with existing bookings'
            ];
            return $is_api_call ? new WP_REST_Response($response, 400) : $response;
        }
    }
    
    // If we get here, the dates are available
    $response = [
        'status' => 'success',
        'message' => 'Selected dates are available',
        'data' => [
            'listing_id' => $listing_id,
            'start_date' => $book_from,
            'end_date' => $book_to,
            'is_hourly' => $property_rules['booking_type'] == 2,
            'internal' => $internal
        ]
    ];
    return $is_api_call ? new WP_REST_Response($response, 200) : $response;
}


function wprentals_get_booking_availability_complicated(WP_REST_Request $request) {
    // Get and validate parameters
    $params = $request->get_params();
    
    // Validate required parameters
    if (empty($params['book_from']) || empty($params['book_to']) || empty($params['listing_id'])) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'Missing required parameters: book_from, book_to, or listing_id'
        ], 400);
    }

    $listing_id = intval($params['listing_id']);
    $book_from = sanitize_text_field($params['book_from']);
    $book_to = sanitize_text_field($params['book_to']);
    $internal = isset($params['internal']) ? intval($params['internal']) : 0;
    
    // Get mega details for custom period settings
    $mega = wpml_mega_details_adjust($listing_id);
    
    // Convert dates
    $book_from = wpestate_convert_dateformat($book_from);
    $book_to = wpestate_convert_dateformat($book_to);
    
    // Create DateTime objects
    try {
        $from_date = new DateTime($book_from);
        $to_date = new DateTime($book_to);
    } catch (Exception $e) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'Invalid date format'
        ], 400);
    }
    
    $from_date_unix = $from_date->getTimestamp();
    $to_date_unix = $to_date->getTimestamp();
    $to_date_unix_check = $to_date->getTimestamp();
    $date_checker = strtotime(date("Y-m-d 00:00", $from_date_unix));
    
    // Get booking type (hourly or daily)
    $wprentals_is_per_hour = wprentals_return_booking_type($listing_id);
    $diff = $wprentals_is_per_hour == 2 ? 3600 : 86400;
    
    // Get existing reservations
    $reservation_array = get_post_meta($listing_id, 'booking_dates', true);
    if (empty($reservation_array)) {
        $reservation_array = wpestate_get_booking_dates($listing_id);
    }
    
    // Check minimum days requirement only if not internal
    if ($internal == 0) {
        $min_days_booking = intval(get_post_meta($listing_id, 'min_days_booking', true));
        $min_days_value = 0;
        
        if (is_array($mega) && array_key_exists($date_checker, $mega)) {
            if (isset($mega[$date_checker]['period_min_days_booking'])) {
                $min_days_value = $mega[$date_checker]['period_min_days_booking'];
                if (abs($from_date_unix - $to_date_unix) / $diff < $min_days_value) {
                    return new WP_REST_Response([
                        'status' => 'error',
                        'message' => 'Custom period minimum stay requirement not met'
                    ], 400);
                }
            }
        } else if ($min_days_booking > 0) {
            if (abs($from_date_unix - $to_date_unix) / $diff < $min_days_booking) {
                return new WP_REST_Response([
                    'status' => 'error',
                    'message' => 'Minimum stay requirement not met'
                ], 400);
            }
        }
    }
    
    // Check check-in/check-out change over days
    $checkin_checkout_change_over = floatval(get_post_meta($listing_id, 'checkin_checkout_change_over', true));
    $weekday = date('N', $from_date_unix);
    $end_bookday = date('N', $to_date_unix_check);
    
    if (is_array($mega) && array_key_exists($from_date_unix, $mega)) {
        if (isset($mega[$from_date_unix]['period_checkin_checkout_change_over']) && 
            $mega[$from_date_unix]['period_checkin_checkout_change_over'] != 0) {
            $period_checkin_checkout_change_over = $mega[$from_date_unix]['period_checkin_checkout_change_over'];
            if ($weekday != $period_checkin_checkout_change_over || $end_bookday != $period_checkin_checkout_change_over) {
                return new WP_REST_Response([
                    'status' => 'error',
                    'message' => 'Selected check-in/check-out days not allowed for this period'
                ], 400);
            }
        }
    } else if ($checkin_checkout_change_over > 0) {
        if ($weekday != $checkin_checkout_change_over || $end_bookday != $checkin_checkout_change_over) {
            return new WP_REST_Response([
                'status' => 'error',
                'message' => 'Selected check-in/check-out days not allowed'
            ], 400);
        }
    }
    
    // Check check-in change over days
    $checkin_change_over = floatval(get_post_meta($listing_id, 'checkin_change_over', true));
    
    if (is_array($mega) && array_key_exists($from_date_unix, $mega)) {
        if (isset($mega[$from_date_unix]['period_checkin_change_over']) && 
            $mega[$from_date_unix]['period_checkin_change_over'] != 0) {
            $period_checkin_change_over = $mega[$from_date_unix]['period_checkin_change_over'];
            if ($weekday != $period_checkin_change_over) {
                return new WP_REST_Response([
                    'status' => 'error',
                    'message' => 'Selected check-in day not allowed for this period'
                ], 400);
            }
        }
    } else if ($checkin_change_over > 0) {
        if ($weekday != $checkin_change_over) {
            return new WP_REST_Response([
                'status' => 'error',
                'message' => 'Selected check-in day not allowed'
            ], 400);
        }
    }
    
    // Check if start date is already booked
    if (array_key_exists($from_date_unix, $reservation_array)) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'Selected start date is not available'
        ], 400);
    }
    
    // Adjust to_date for daily bookings
    if (!$wprentals_is_per_hour == 2) {
        $to_date->modify('yesterday');
        $to_date_unix = $to_date->getTimestamp();
    }
    
    // Check for booking overlap
    if ($wprentals_is_per_hour == 2) {
        if (wprentals_check_hour_booking_overlap_reservations($from_date_unix, $to_date_unix, $reservation_array)) {
            return new WP_REST_Response([
                'status' => 'error',
                'message' => 'Selected time period overlaps with existing bookings'
            ], 400);
        }
    } else {
        if (wprentals_check_booking_overlap_reservations($from_date, $from_date_unix, $to_date_unix, $reservation_array)) {
            return new WP_REST_Response([
                'status' => 'error',
                'message' => 'Selected dates overlap with existing bookings'
            ], 400);
        }
    }
    
    // If we get here, the dates are available
    return new WP_REST_Response([
        'status' => 'success',
        'message' => 'Selected dates are available',
        'data' => [
            'listing_id' => $listing_id,
            'start_date' => $book_from,
            'end_date' => $book_to,
            'is_hourly' => $wprentals_is_per_hour == 2,
            'internal' => $internal,
            'reservation_array'=> $reservation_array
        ]
    ], 200);
}





