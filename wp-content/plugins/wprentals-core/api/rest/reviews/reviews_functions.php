<?php
/**
 * wprentals API Functions for Review Management
 *
 * Core functions for handling review filtering, data processing, 
 * and response formatting for the wprentals REST API.
 *
 * @package wprentals
 * @subpackage API
 * @since 1.0.0
 */

 
/**
 * Check if the current user has permissions to perform an action on a review.
 * 
 * Verifies user permissions for review management actions.
 *
 * @param WP_REST_Request $request REST API request object.
 * @return bool|WP_Error True if the user has permission, otherwise a WP_Error.
 */
function wprentals_check_permissions_for_review(WP_REST_Request $request) {
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
            __('You must be logged in to manage reviews.'),
            ['status' => 403]
        );
    }

    // Get the review ID from the request
    $comment_id = $request->get_param('id');
    if (!$comment_id || !is_numeric($comment_id)) {
        return new WP_Error(
            'rest_invalid_review',
            __('Invalid review ID.'),
            ['status' => 400]
        );
    }

    // Validate the review exists and is a review post type
    $comment = get_post($comment_id);
    if (!$comment || $comment->post_type !== 'estate_review') {
        return new WP_Error(
            'rest_review_not_found',
            __('Review not found.'),
            ['status' => 404]
        );
    }

    // Check if the current user is the review author or an administrator
    if (
        intval(get_post_meta($comment->ID, 'review_author', true)) !== intval($user_id) &&
        !current_user_can('manage_options')
    ) {
        return new WP_Error(
            'rest_forbidden',
            __('You do not have permission to modify this review.'),
            ['status' => 403]
        );
    }

    return true;
}

/**
 * Verify permissions for creating reviews.
 *
 * Allows requests only from authenticated administrators. Supports JWT
 * authentication by manually determining the current user.
 *
 * @param WP_REST_Request $request REST API request object.
 * @return bool|WP_Error True if the user may create reviews, otherwise a WP_Error.
 */
function wprentals_can_post_review( WP_REST_Request $request ) {
    // Verify the JWT token or other authentication method
    $user_id = apply_filters( 'determine_current_user', null );
    if ( ! $user_id ) {
        return new WP_Error(
            'jwt_auth_failed',
            __( 'Authentication required.' ),
            [ 'status' => 401 ]
        );
    }

    wp_set_current_user( $user_id );

    if ( ! current_user_can( 'manage_options' ) ) {
        return new WP_Error(
            'rest_forbidden',
            __( 'Only administrators can post reviews.' ),
            [ 'status' => 403 ]
        );
    }

    return true;
}

/**
 * Sanitize a stars rating array.
 *
 * Casts all recognized rating fields to floats and discards unknown keys.
 *
 * @param array $stars         Raw stars array from the request.
 * @param array $rating_fields Review rating fields configuration.
 * @return array Sanitized stars array.
 */
function wprentals_sanitize_stars( $stars, $rating_fields ) {
    $sanitized = array();

    if ( ! is_array( $stars ) ) {
        return new WP_Error(
            'rest_invalid_param',
            __( 'Ratings must be provided as an array.' ),
            [ 'status' => 400 ]
        );
    }

    foreach ( $rating_fields['fields'] as $key => $label ) {
        if ( isset( $stars[ $key ] ) ) {
            $value = $stars[ $key ];
            if ( ! is_numeric( $value ) || $value < 1 || $value > 5 ) {
                return new WP_Error(
                    'rest_invalid_param',
                    __( 'Rating values must be numbers between 1 and 5.' ),
                    [ 'status' => 400 ]
                );
            }
            $sanitized[ $key ] = floatval( $value );
        }
    }

    return $sanitized;
}

/**
 * Retrieve all reviews with specified filters and pagination.
 *
 * Main function for review listing API endpoint.
 * Processes complex filtering options and returns data in requested format.
 *
 * Supported parameters: page, posts_per_page, property_id, min_stars, max_stars, user_id.
 *
 * @param WP_REST_Request $request REST API request containing filter parameters.
 * @return WP_REST_Response Response containing filtered review data.
 */
function wprentals_get_all_reviews(WP_REST_Request $request) {
    // Parse parameters
    $params = wprentals_parse_request_params($request);
    $allowed_params = ['page','posts_per_page','property_id','min_stars','max_stars','user_id'];
    $params = array_intersect_key($params, array_flip($allowed_params));

    // Set defaults and extract main parameters
    $paged = isset($params['page']) ? intval($params['page']) : 1;
    $posts_per_page = isset($params['posts_per_page']) ? intval($params['posts_per_page']) : 10;

    // Build the query args
    $offset = ($paged - 1) * $posts_per_page;
    $args = array(
        'post_type' => 'estate_review',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'offset' => $offset,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(),
    );

    // Filter by property ID
    if (isset($params['property_id']) && is_numeric($params['property_id'])) {
        $args['meta_query'][] = array(
            'key' => 'attached_to',
            'value' => $params['property_id'],
            'compare' => '=',
        );
    }

    // Filter by minimum star rating
    if (isset($params['min_stars']) && is_numeric($params['min_stars'])) {
        $args['meta_query'][] = array(
            'key'     => 'review_stars',
            'value'   => intval($params['min_stars']),
            'compare' => '>=',
            'type'    => 'NUMERIC'
        );
    }

    // Filter by maximum star rating
    if (isset($params['max_stars']) && is_numeric($params['max_stars'])) {
        $args['meta_query'][] = array(
            'key'     => 'review_stars',
            'value'   => intval($params['max_stars']),
            'compare' => '<=',
            'type'    => 'NUMERIC'
        );
    }

    // Filter by user ID
    if (isset($params['user_id']) && is_numeric($params['user_id'])) {
        $args['meta_query'][] = array(
            'key'     => 'review_author',
            'value'   => intval($params['user_id']),
            'compare' => '=',
            'type'    => 'NUMERIC'
        );
    }

    // Get the comments
    $comments = new WP_Query($args);

    // Get total comments for pagination
    $total_comments = $comments->found_posts;
    $total_pages   = (int) ceil($total_comments / $posts_per_page);

    // Process results
    $reviews = array();
    foreach ($comments->posts as $comment) {
        $review_data = wpestate_format_review_data($comment);
        $reviews[]   = $review_data;
    }

    // Return formatted response
    return new WP_REST_Response(
        [
            'status' => 'success',
            'data' => $reviews,
            'total' => $total_comments,
            'pages' => $total_pages
        ],
        200
    );
}


/**
 * Retrieve a single review by its ID.
 * 
 * Fetches complete review data for a specific review comment.
 * Supports field filtering to return only requested data.
 *
 * @param WP_REST_Request $request REST API request containing the review ID.
 * @return WP_REST_Response|WP_Error Response with review data or error if not found.
 */
function wprentals_get_single_review(WP_REST_Request $request) {
    // Extract and validate the review ID directly from the request to
    // avoid losing path parameters when parsing request data.
    $comment_id = absint( $request->get_param( 'id' ) );

    // Parse fields parameter if provided
    $fields = wprentals_parse_api_fields_param( $request->get_param( 'fields' ) );

    // Verify review exists and is of the correct post type
    $comment = get_post( $comment_id );
    if ( ! $comment || $comment->post_type !== 'estate_review' ) {
        return new WP_Error( 'rest_review_not_found', __( 'Review not found' ), [ 'status' => 404 ] );
    }

    // Format the review data
    $review_data = wpestate_format_review_data($comment);
    
    // Filter response based on requested fields
    if ($fields) {
        $review_data = wprentals_filter_api_response_fields($review_data, $fields);
    }

    return new WP_REST_Response(
        [
            'status' => 'success',
            'data'   => $review_data,
        ],
        200
    );
}

/**
 * Format a comment object into a standardized review data structure.
 * 
 * Extracts and structures all relevant data from a WP_Comment object
 * for use in API responses.
 *
 * @param WP_Comment $comment The comment object to format.
 * @return array Formatted review data.
 */
function wpestate_format_review_data($comment) {
    // Get the property details
    $property_id    = get_post_meta( $comment->ID , 'attached_to', true );
    $property_title = get_the_title($property_id);
    $entity_type    = get_post_type($property_id);
    
    // Get review meta
    $stars_meta = get_post_meta($comment->ID, 'review_stars', true);
    $stars = '';
    $star_details = array();
    if (empty($stars_meta)) {
        $stars = get_post_meta($comment->ID, 'reviewer_rating', true);
    } else {
        if (is_string($stars_meta)) {
            $decoded = json_decode($stars_meta, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $stars_meta = $decoded;
            }
        }
        if (is_array($stars_meta)) {
            $stars = $stars_meta['rating'] ?? '';
            $star_details = $stars_meta;
            unset($star_details['rating']);
        } else {
            $stars = $stars_meta;
        }
    }
    $title = get_the_title($comment->ID);

    // Get user data
    $user_id = get_post_meta( $comment->ID , 'review_author', true );
    if (empty($user_id)) {
        $user_id = $comment->post_author;
    }
    $user_data = get_userdata($user_id);
    $user_name = $user_data ? $user_data->display_name : '';
    
    // Format the date
    $date = mysql2date(get_option('date_format'), $comment->post_date);
    
    // Build the review data structure
    $review_data = array(
        'id'            => $comment->ID,
        'property_id'   => $property_id,
        'property_title'=> $property_title,
        'entity_type'   => $entity_type,
        'user_id'       => $user_id,
        'user_name'     => $user_name,
        'date'          => $date,
        'timestamp'     => strtotime($comment->post_date),
        'stars'         => $stars,
        'ratings'       => $star_details,
        'title'         => $title,
        'content'       => $comment->post_content,
        'approved'      => ($comment->post_status == 'publish'),
    );
    
    return $review_data;
}
