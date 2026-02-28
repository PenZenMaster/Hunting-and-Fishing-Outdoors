<?php
/**
 * Post a review for a property or agent.
 *
 * Allows an administrator to create a review on behalf of a user.
 *
 * @param WP_REST_Request $request The REST API request.
 * @return WP_REST_Response|WP_Error Success response or error details.
 */
function wprentals_post_review(WP_REST_Request $request) {
    $rating_fields = wpestate_get_review_fields();

    // Parse and sanitize request parameters
    $params = wprentals_parse_request_params($request);

    // Allow only specific parameters
    $allowed_params  = ['property_id', 'user_id', 'ratings', 'title', 'content'];
    $invalid_params = array_diff(array_keys($params), $allowed_params);
    if (!empty($invalid_params)) {
        return new WP_Error(
            'rest_invalid_param',
            __('Invalid parameters: ' . implode(', ', $invalid_params) . '.'),
            ['status' => 400]
        );
    }

    // Validate property ID
    if (empty($params['property_id']) || !is_numeric($params['property_id'])) {
        return new WP_Error(
            'rest_invalid_param',
            __('Invalid property ID.'),
            ['status' => 400]
        );
    }

    $property_id = intval($params['property_id']);

    // Verify post type
    $post_type = get_post_type($property_id);
    if ($post_type !== 'estate_property') {
        return new WP_Error(
            'rest_invalid_post_type',
            __('Reviews can only be posted for properties.'),
            ['status' => 400]
        );
    }

    // Validate review author
    if (empty($params['user_id']) || !is_numeric($params['user_id'])) {
        return new WP_Error(
            'rest_invalid_param',
            __('Invalid user ID.'),
            ['status' => 400]
        );
    }

    $user_id = intval($params['user_id']);
    $user    = get_user_by('ID', $user_id);
    if (!$user) {
        return new WP_Error(
            'rest_invalid_param',
            __('User not found.'),
            ['status' => 400]
        );
    }

    $user_login = $user->user_login;

    // Validate detailed ratings
    if (empty($params['ratings']) || !is_array($params['ratings'])) {
        return new WP_Error(
            'rest_invalid_param',
            __('Ratings must be an array and contain the following keys: ' . implode(', ', array_keys($rating_fields['fields'])) . '.'),
            ['status' => 400]
        );
    }

    $required_keys = array_keys($rating_fields['fields']);
    if (array_diff($required_keys, array_keys($params['ratings']))) {
        return new WP_Error(
            'rest_invalid_param',
            __('Ratings must contain the following keys: ' . implode(', ', $required_keys) . '.'),
            ['status' => 400]
        );
    }

    $extra_rating_keys = array_diff(array_keys($params['ratings']), $required_keys);
    if (!empty($extra_rating_keys)) {
        return new WP_Error(
            'rest_invalid_param',
            __('Invalid rating parameters: ' . implode(', ', $extra_rating_keys) . '.'),
            ['status' => 400]
        );
    }

    $stars = wprentals_sanitize_stars($params['ratings'], $rating_fields);
    if (is_wp_error($stars)) {
        return $stars;
    }

    // Validate content
    if (empty($params['content'])) {
        return new WP_Error(
            'rest_invalid_param',
            __('Review content is required.'),
            ['status' => 400]
        );
    }

    $allowed_html = array();
    $content      = wp_kses($params['content'], $allowed_html);
    $entity_name  = get_the_title($property_id);
    $title        = !empty($params['title']) ? wp_kses($params['title'], $allowed_html) : sprintf('Review for %s', $entity_name);

    $status = 'pending'; // Default status for new reviews
    if (wprentals_get_option('wp_estate_admin_approves_reviews', '') == 'no') {
        $status = 'publish'; // Auto-approve if setting is 'no'
    }

    $stars_total = array_sum($stars);
    $ratingValue = number_format(round(($stars_total / count($stars)) * 2) / 2, 1);
    $stars['rating'] = $ratingValue;

    // Create the estate review post
    $post_data = array(
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => $status,
        'post_type'    => 'estate_review',
        'post_author'  => $user_id,
        'meta_input'   => array(
            'review_author'   => $user_id,
            'review_stars'    => $stars,
            'reviewer_rating' => $ratingValue,
            'attached_to'     => $property_id,
        )
    );

    $post_id = wp_insert_post($post_data);

    // Check if comment was inserted successfully
    if (!$post_id || is_wp_error($post_id)) {
        return new WP_Error(
            'rest_comment_failed',
            __('Failed to post review.'),
            ['status' => 500]
        );
    }

    update_post_meta($property_id, 'review_by_' . $user_id, 'has');

    // Recalculate property rating
    wpestate_calculate_property_rating($property_id);

    // Send email notification
    $arguments = array(
        'agent_name' => $entity_name,
        'user_post'  => $user_login
    );

    wpestate_select_email_type(get_option('admin_email'), 'agent_review', $arguments);

    // Return success response
    $response_data = array(
        'status'     => 'success',
        'comment_id' => $post_id,
        'message'    => __('Review posted successfully.'),
        'approved'   => ($status == 'publish'),
    );

    return rest_ensure_response($response_data);
}

