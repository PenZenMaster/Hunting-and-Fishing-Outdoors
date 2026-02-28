<?php
/**
 * wprentals Review Update API Functions
 *
 * Functions for updating existing reviews via the REST API,
 * including permission verification and data processing.
 *
 * @package wprentals
 * @subpackage API
 * @since 1.0.0
 */

/**
 * Update an existing review.
 * 
 * Processes and applies updates to a review's data, including:
 * - Review content
 * - Rating stars
 * - Review title
 *
 * @param WP_REST_Request $request The REST API request containing the review data.
 * @return WP_REST_Response|WP_Error Response object or error.
 */
function wprentals_update_review(WP_REST_Request $request) {
    // Get review ID from request
    $comment_id = $request->get_param('id');
    
    // Parse and sanitize input data
    $input_data = wprentals_parse_request_params($request);

    // Allow only specific parameters
    $allowed_params = ['user_id', 'ratings', 'title', 'content'];
    $invalid_params = array_diff(array_keys($input_data), $allowed_params);
    if (!empty($invalid_params)) {
        return new WP_Error(
            'rest_invalid_param',
            __('Invalid parameters: ' . implode(', ', $invalid_params) . '.'),
            ['status' => 400]
        );
    }

    // Validate the review ID and ensure it is a review post
    $comment = get_post($comment_id);
    if (!$comment || $comment->post_type !== 'estate_review') {
        return new WP_Error(
            'rest_review_not_found',
            __('Review not found.'),
            ['status' => 404]
        );
    }

    // Load rating field configuration
    $rating_fields = wpestate_get_review_fields();
    
    // Prepare comment data for update
    $comment_data = array(
        'ID' => $comment_id
    );
    
    // Update content if provided
    if (isset($input_data['content'])) {
        $allowed_html = array();
        $comment_data['post_content'] = wp_kses($input_data['content'], $allowed_html);
    }
    
    // Update review title if provided
    if (isset($input_data['title'])) {
        $allowed_html = array();
        $comment_data['post_title'] = wp_kses($input_data['title'], $allowed_html);
    }

    // Update the comment in the database
    $result = wp_update_post($comment_data);

    if (false === $result) {
        return new WP_Error(
            'rest_update_failed',
            __('Failed to update review.'),
            ['status' => 500]
        );
    }

    // Determine the associated listing for later recalculation
    $listing_id = intval(get_post_meta($comment_id, 'attached_to', true));
    
    // Update ratings if provided
    $rating_input = $input_data['ratings'] ?? null;
    if ($rating_input !== null) {
        if (!is_array($rating_input)) {
            return new WP_Error(
                'rest_invalid_param',
                __('Ratings must be an array and contain the following keys: ' . implode(', ', array_keys($rating_fields['fields'])) . '.'),
                ['status' => 400]
            );
        }

        $required_keys = array_keys($rating_fields['fields']);
        if (array_diff($required_keys, array_keys($rating_input))) {
            return new WP_Error(
                'rest_invalid_param',
                __('Ratings must be an array and contain the following keys: ' . implode(', ', $required_keys) . '.'),
                ['status' => 400]
            );
        }

        $stars = wprentals_sanitize_stars($rating_input, $rating_fields);
        if (is_wp_error($stars)) {
            return $stars;
        }

        $starsTotal = array_sum($stars);
        $ratingValue = number_format(round(($starsTotal / count($stars)) * 2) / 2, 1);
        $stars['rating'] = $ratingValue;

        // Update the stars meta
        update_post_meta($comment_id, 'review_stars', $stars);
        update_post_meta($comment_id, 'reviewer_rating', $ratingValue);
    }

    // Get the updated review data
    $updated_comment = get_post($comment_id);
    $review_data = wpestate_format_review_data($updated_comment);

    // Recalculate property rating if we have a valid listing
    if ($listing_id) {
        wpestate_calculate_property_rating($listing_id);
    }
    
    // Return success response
    return rest_ensure_response([
        'status'     => 'success',
        'review_id'  => $comment_id,
        'message'    => __('Review updated successfully.'),
        'data'       => $review_data
    ]);
}
