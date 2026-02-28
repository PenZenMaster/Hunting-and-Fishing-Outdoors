<?php

/**
 * Check if the user has permission to update a property.
 *
 * @param WP_REST_Request $request The REST API request.
 * @return bool|WP_Error True if the user has permission, WP_Error otherwise.
 */
/**
 * Check if the user has permission to update a property.
 *
 * @param WP_REST_Request $request The REST API request.
 * @return bool|WP_Error True if the user has permission, WP_Error otherwise.
 */
function wprentals_check_permissions_for_property(WP_REST_Request $request) {
    // Verify the JWT token
    $user_id = apply_filters('determine_current_user', null);
    if (!$user_id) {
        return new WP_Error(
            'jwt_auth_failed',
            __('Invalid or missing JWT token.'),
            ['status' => 403]
        );
    }
    wp_set_current_user($user_id);

    // Fetch the current user details
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    // Check if the user is logged in
    if (!$user_id || !is_user_logged_in()) {
        return new WP_Error(
            'rest_forbidden',
            __('You must be logged in to update a property.'),
            ['status' => 403]
        );
    }

    // Get the property ID from the request
    $property_id = $request->get_param('id');
    if (!$property_id || !is_numeric($property_id)) {
        return new WP_Error(
            'rest_invalid_property',
            __('Invalid property ID.'),
            ['status' => 400]
        );
    }

    // Validate the property
    $property = get_post($property_id);
    if (!$property || $property->post_type !== 'estate_property') {
        return new WP_Error(
            'rest_property_not_found',
            __('Property not found.'),
            ['status' => 404]
        );
    }

    // Check if the current user is the author of the property
    if (intval($property->post_author) !== intval($user_id) && !current_user_can('edit_others_posts')) {
        return new WP_Error(
            'rest_forbidden',
            __('You do not have permission to update this property.'),
            ['status' => 403]
        );
    }

    return true;
}






/**
 * Update an existing property.
 *
 * @param WP_REST_Request $request REST API request containing property data.
 * @return WP_REST_Response|
 */
/**
 * Update an existing property.
 *
 * @param WP_REST_Request $request The REST API request containing the property data.
 * @return WP_REST_Response|WP_Error Response object or error.
 */
function wprentals_update_property(WP_REST_Request $request) {
    $property_id = $request->get_param('id');

    $input_data = wprentals_parse_request_params($request);

    // Validate the property ID
    $property = get_post($property_id);
    if (!$property || $property->post_type !== 'estate_property') {
        return new WP_Error(
            'rest_property_not_found',
            __('Property not found.'),
            ['status' => 404]
        );
    }

    // Update the title if provided
    if (isset($input_data['title'])) {
        wp_update_post([
            'ID'         => $property_id,
            'post_title' => sanitize_text_field($input_data['title']),
            'post_content'  => $input_data['property_description'],
        ]);
    }

    // Update taxonomies and meta fields
    foreach ($input_data as $key => $value) {
        if (taxonomy_exists($key)) {
            if (is_array($value)) {
                wp_set_object_terms($property_id, $value, $key);
            }
        } else {
            if ($key === 'extra_pay_options') {
                wprentals_process_extra_pay_options($property_id, $value);
            } elseif ($key === 'beds_options') {
                wprentals_process_beds_options($property_id, $value);
            } elseif ($key === 'custom_fields') {
                wprentals_process_custom_fields($property_id, $value);
            } elseif ($key === 'ical_feeds') {
                wprentals_process_ical_feeds($property_id, $value);
            } elseif ($key === 'custom_price') {
                wprentals_process_custom_price($property_id, $value);
            } else {
                update_post_meta($property_id, $key, $value);
            }
        }
    }

    // Process images if provided
    if (!empty($input_data['images']) && is_array($input_data['images'])) {
        wprentals_process_images($property_id, $input_data['images']);
    }

    // Return success response
    return rest_ensure_response([
        'status'       => 'success',
        'property_id'  => $property_id,
        'message'      => __('Property updated successfully.'),
    ]);
}
