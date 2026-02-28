<?php
/**
 * wprentals Review Deletion API Functions
 *
 * Functions for permanently removing reviews via the REST API
 * with proper validation and response handling.
 *
 * @package wprentals
 * @subpackage API
 * @since 1.0.0
 */

/**
 * Delete a review.
 * 
 * Permanently removes a review comment and all its associated meta data.
 * Validates the review existence before deletion and provides
 * appropriate error responses for failure cases.
 *
 * @param WP_REST_Request $request The REST API request containing the review ID.
 * @return WP_REST_Response|WP_Error Success response or error details.
 */
function wprentals_delete_review(WP_REST_Request $request) {
    // Parse and extract request parameters
    $input_data = wprentals_parse_request_params($request);
    $comment_id = $input_data['id'];
    
    // Validate the review exists and is of the correct post type
    $comment = get_post($comment_id);
    if (!$comment || $comment->post_type !== 'estate_review') {
        return new WP_Error(
            'rest_review_not_found',
            __('Review not found.'),
            ['status' => 404]
        );
    }

    // Ensure the current user has permission to delete this review
    $current_user = wp_get_current_user();
    $review_author = intval(get_post_meta($comment->ID, 'review_author', true));
    if ($review_author !== intval($current_user->ID) && !current_user_can('manage_options')) {
        return new WP_Error(
            'rest_forbidden',
            __('You do not have permission to delete this review.'),
            ['status' => 403]
        );
    }

    // Get property information (what was being reviewed)
    $property_id    = get_post_meta($comment->ID, 'attached_to', true);
    $property_title = get_the_title($property_id);

    // Attempt to delete the review (force = true permanently deletes it)
    $result = wp_delete_post($comment_id, true);
    if (!$result) {
        return new WP_Error(
            'rest_cannot_delete',
            __('Failed to delete the review.'),
            ['status' => 500]
        );
    }

    // Recalculate property rating if we have a valid property ID
    if ($property_id) {
        wpestate_calculate_property_rating($property_id);
    }

    // Return success response with confirmation details
    return rest_ensure_response([
        'status'         => 'success',
        'review_id'      => $comment_id,
        'property_id'    => $property_id,
        'property_title' => $property_title,
        'message'        => __('Review deleted successfully.'),
    ]);
}
