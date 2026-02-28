<?php
/**
 * Delete a property.
 *
 * @param WP_REST_Request $request The REST API request.
 * @return WP_REST_Response|WP_Error Response object or error.
 */
function wprentals_delete_property(WP_REST_Request $request) {
    $input_data = wprentals_parse_request_params($request);

    $property_id = $input_data['id'];

    // Validate the property ID
    $property = get_post($property_id);
    if (!$property || $property->post_type !== 'estate_property') {
        return new WP_Error(
            'rest_property_not_found',
            __('Property not found.'),
            ['status' => 404]
        );
    }

    // Attempt to delete the property
    $result = wp_delete_post($property_id, true); // Force delete
    if (!$result) {
        return new WP_Error(
            'rest_cannot_delete',
            __('Failed to delete the property.'),
            ['status' => 500]
        );
    }

    // Return success response
    return rest_ensure_response([
        'status'      => 'success',
        'property_id' => $property_id,
        'message'     => __('Property deleted successfully.'),
    ]);
}
