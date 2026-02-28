<?php

/**
 * Retrieve filter settings for taxonomies and meta fields.
 *
 * @return array Filter settings for property taxonomies and meta fields.
 */
function wprentals_get_filter_settings() {
    return [
        'property_price' => '=',
        'property_city' => 'LIKE',
        'property_features' => 'IN',
    ];
}

/**
 * Generate basic response for a property.
 *
 * @param int $postID The ID of the property.
 * @return array Basic property data.
 */
function wpestate_generate_basic_response($postID) {
    $cached_data = wpestate_api_get_cached_post_data($postID, 'estate_property');

    return [
        'id' => $cached_data['ID'],
        'title' => $cached_data['title'],
        'price' => $cached_data['meta']['property_price'] ?? '',
        'location' => $cached_data['meta']['property_address'] ?? ''
    ];
}





/**
 * Determine and retrieve sanitized parameters from a REST request or internal call.
 * 
 * Extracts and sanitizes request parameters regardless of source format.
 * Special handling for HTML content like virtual tours.
 *
 * @param WP_REST_Request|array $request REST API request or internal array.
 * @return array Sanitized parameters.
 */
function wprentals_parse_request_params($request) {
    // Extract parameters based on request type
    if ($request instanceof WP_REST_Request) {
        $params = $request->get_json_params();
        if (empty($params)) {
            $params = $request->get_params();
        }
    } else {
        $params = $request;
    }

    // Special handling for virtual tour HTML content
    $virtual_tour = null;
    if (!empty($params['embed_virtual_tour'])) {
        $allowed_html = [
            'iframe' => [
                'src'             => ['pattern' => '#^https:\/\/.*$#'],
                'width'           => ['pattern' => '#^\d+$|(^auto$)|(^\d+%$)|(^\d+px$)#'],
                'height'          => ['pattern' => '#^\d+$|(^auto$)|(^\d+%$)|(^\d+px$)#'],
                'frameborder'     => ['pattern' => '#^[0-1]$#'],
                'style'           => ['pattern' => '#^([\w-]+:\s?[^;]+;(\s+)?)*$#'],
                'allow'           => ['pattern' => '#^[\w-]+(;\s*[\w-]+)*$#'],
                'allowfullscreen' => ['pattern' => '#^(true|false|"true"|"false"|0|1|"")?$#'],
                'scrolling'       => ['pattern' => '#^(yes|no|auto)$#'],
            ],
        ];
        $virtual_tour = wp_kses($params['embed_virtual_tour'], $allowed_html);
    }

    // Type-specific sanitization
    $return_array = wprentals_sanitize_params_by_type($params);
    
    // Re-add the virtual tour HTML if it exists
    if ($virtual_tour) {
        $return_array['embed_virtual_tour'] = $virtual_tour;
    }
    
    return $return_array;
}





/**
* Recursively sanitize parameters based on their data types.
* 
* Applies type-specific sanitization to maintain data integrity while ensuring security:
* - Arrays: Recursively sanitizes each element
* - Numbers: Preserves numeric format including decimals
* - Booleans: Preserves boolean type
* - Null values: Preserves null state
* - Dates: Handles date strings appropriately
* - Other strings: Applies text field sanitization
*
* @param mixed $params Input parameters (scalar or array)
* @return mixed Sanitized parameters with preserved data types
*/
function wprentals_sanitize_params_by_type($params) {
    if (!is_array($params)) {
        return sanitize_text_field($params);
    }
    
    $sanitized = [];
    foreach ($params as $key => $value) {
        $safe_key = sanitize_key($key);
        
        if (is_array($value)) {
            $sanitized[$safe_key] = wprentals_sanitize_params_by_type($value);
        } else if (is_numeric($value)) {
            $sanitized[$safe_key] = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        } else if (is_bool($value)) {
            $sanitized[$safe_key] = (bool)$value;
        } else if ($value === null) {
            $sanitized[$safe_key] = null;
        } else if (strtotime($value) !== false) {
            // Date handling
            $sanitized[$safe_key] = sanitize_text_field($value);
        } else {
            $sanitized[$safe_key] = sanitize_text_field($value);
        }
    }
    
    return $sanitized;
}



/**
 * Recursively sanitize array values.
 *
 * @param callable $callback Callback function to apply (e.g., 'sanitize_text_field').
 * @param mixed $value The value to sanitize (array or scalar).
 * @return mixed Sanitized value.
 */
function array_map_recursive($callback, $value) {
    if (is_array($value)) {
        return array_map(function ($item) use ($callback) {
            return array_map_recursive($callback, $item);
        }, $value);
    }
    return call_user_func($callback, $value);
}


/**
 * Retrieve all properties with specified filters and pagination.
 *
 * @param WP_REST_Request $request REST API request containing filter parameters.
 * @return WP_REST_Response Response containing filtered property data.
 */
function wprentals_get_all_properties(WP_REST_Request $request) {
    
    // Parse parameters
    $params = wprentals_parse_request_params($request);

    // Set defaults and extract main parameters with proper sanitization
    $paged = isset($params['page']) ? absint($params['page']) : 1;
    $posts_per_page = isset($params['posts_per_page']) ? absint($params['posts_per_page']) : 10;
    $order = isset($params['order']) ? absint($params['order']) : 0;
    $response_type = isset($params['response_type']) && in_array($params['response_type'], ['basic', 'full'], true) ? $params['response_type'] : 'basic';
    $userID = isset($params['userID']) ? absint($params['userID']) : null;
    $fields = wprentals_parse_fields_param($params['fields'] ?? null);

    // Initialize query arrays
    $meta_input = [];
    $taxonomy_input = [];

    // Process taxonomy parameters
    if (isset($params['taxonomies']) && is_array($params['taxonomies'])) {
        $taxonomy_input = $params['taxonomies'];
    }

    // Process meta parameters with secure handling
    if (isset($params['meta']) && is_array($params['meta'])) {
        foreach ($params['meta'] as $key => $meta_data) {
            // Skip if meta_data is not an array
            if (!is_array($meta_data)) {
                continue;
            }
            
            // Sanitize meta key (only allow alphanumeric, dash and underscore)
            $safe_key = preg_replace('/[^a-zA-Z0-9_-]/', '', $key);
            if (empty($safe_key) || $safe_key !== $key) {
                continue; // Skip if key contained invalid characters
            }

            // Define valid operators and types as associative arrays for strong typing
            $valid_compare = [
                '=' => '=', 
                '!=' => '!=', 
                '>' => '>', 
                '>=' => '>=', 
                '<' => '<', 
                '<=' => '<=', 
                'LIKE' => 'LIKE', 
                'NOT LIKE' => 'NOT LIKE', 
                'IN' => 'IN', 
                'NOT IN' => 'NOT IN', 
                'BETWEEN' => 'BETWEEN', 
                'NOT BETWEEN' => 'NOT BETWEEN', 
                'EXISTS' => 'EXISTS', 
                'NOT EXISTS' => 'NOT EXISTS'
            ];
            
            $valid_types = [
                'NUMERIC' => 'NUMERIC', 
                'BINARY' => 'BINARY', 
                'CHAR' => 'CHAR', 
                'DATE' => 'DATE', 
                'DATETIME' => 'DATETIME', 
                'DECIMAL' => 'DECIMAL', 
                'SIGNED' => 'SIGNED', 
                'TIME' => 'TIME', 
                'UNSIGNED' => 'UNSIGNED'
            ];

            // Validate and set default values
            $meta_query = [
                'key' => $safe_key,
                'compare' => '=',
                'type' => 'CHAR'
            ];

            // Add value with type-specific sanitization
            if (isset($meta_data['value'])) {
                $specified_type = isset($meta_data['type']) && 
                                 isset($valid_types[$meta_data['type']]) ? 
                                 $valid_types[$meta_data['type']] : 'CHAR';
                
                if (is_array($meta_data['value'])) {
                    $sanitized_values = [];
                    foreach ($meta_data['value'] as $val) {
                        $sanitized_values[] = wprentals_sanitize_meta_value($val, $specified_type);
                    }
                    $meta_query['value'] = $sanitized_values;
                } else {
                    $meta_query['value'] = wprentals_sanitize_meta_value($meta_data['value'], $specified_type);
                }
            }

            // Add compare if it's valid
            if (isset($meta_data['compare']) && isset($valid_compare[$meta_data['compare']])) {
                $meta_query['compare'] = $valid_compare[$meta_data['compare']];
            }

            // Add type if it's valid
            if (isset($meta_data['type']) && isset($valid_types[$meta_data['type']])) {
                $meta_query['type'] = $valid_types[$meta_data['type']];
            }

            // Handle special compare cases
            if (in_array($meta_query['compare'], ['IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'], true)) {
                // Ensure value is an array for these compare types
                if (!isset($meta_query['value']) || !is_array($meta_query['value'])) {
                    $meta_query['value'] = []; // Use empty array instead of coercing a scalar
                }
            }

            // Handle EXISTS and NOT EXISTS
            if (in_array($meta_query['compare'], ['EXISTS', 'NOT EXISTS'], true)) {
                unset($meta_query['value']); // No value needed for EXISTS comparisons
            }

            $meta_input[] = $meta_query;
        }
    }

    $post_type = 'estate_property';

    // Call the custom query function with API type
    $query_result = wpestate_api_custom_query(
        $post_type,
        $paged,
        $posts_per_page,
        $meta_input,
        $taxonomy_input,
        $order,
        $userID,
        'api'
    );

    // Ensure we have valid results
    if (!$query_result || !isset($query_result['post_ids'])) {
        return new WP_REST_Response(
            [
                'status' => 'error',
                'message' => 'No query results found',
            ],
            404
        );
    }

    // Process results based on response_type
    $properties = [];
    foreach ($query_result['post_ids'] as $postID) {
        if ($response_type === 'basic') {
            $properties[] = wpestate_generate_basic_response($postID);
        } else {
            // Full response
            $cached_data = wpestate_api_get_cached_post_data($postID, $post_type);
            $properties[] = $cached_data;
        }
    }

    // If specific fields are requested, filter the results
    if ($fields) {
        $properties = array_map(function ($property) use ($fields) {
            return filter_response_fields($property, $fields);
        }, $properties);
    }


    //escape answer
    $properties = wprentals_escape_api_response($properties);


    return new WP_REST_Response(
        [
            'status' => 'success',
            'query_args' => $query_result['args'],
            'data' => $properties,
            'total' => $query_result['total_posts'],
            'pages' => $query_result['max_num_pages']
        ],
        200
    );
}

/**
 * Sanitize meta value based on specified type.
 *
 * @param mixed $value The value to sanitize
 * @param string $type The declared data type (NUMERIC, CHAR, etc.)
 * @return mixed Sanitized value
 */
function wprentals_sanitize_meta_value($value, $type) {
    switch ($type) {
        case 'NUMERIC':
        case 'SIGNED':
        case 'UNSIGNED':
        case 'DECIMAL':
            return is_numeric($value) ? $value : 0;
            
        case 'DATE':
        case 'DATETIME':
        case 'TIME':
            return preg_match('/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2})?$/', $value) ? $value : '';
            
        case 'BINARY':
            return sanitize_text_field($value);
            
        case 'CHAR':
        default:
            return sanitize_text_field($value);
    }
}

/**
 * Parse and sanitize the 'fields' parameter.
 *
 * @param string|null $fields Comma-separated list of fields or null.
 * @return array|null Array of sanitized fields or null.
 */
function wprentals_parse_fields_param($fields) {
    if (!$fields) {
        return null;
    }
    return array_map('sanitize_text_field', array_map('trim', explode(',', $fields)));
}



/**
 * Retrieve a single property by its ID.
 *
 * @param WP_REST_Request $request REST API request containing the property ID.
 * @return WP_REST_Response|
 */
function wprentals_get_single_property(WP_REST_Request $request) {
   

    $params = wprentals_parse_request_params($request);

    $id = $params['id'] ?? null;
    $response_type = isset($params['response_type']) && $params['response_type'] === 'basic' ? 'basic' : 'full';


 
    $fields = $request->get_param('fields');
    $fields = $fields ? array_map('trim', explode(',', $fields)) : null;
    $fields = wprentals_parse_fields_param($params['fields'] ?? null);


    $post = get_post($id);

    if (!$post || $post->post_type !== 'estate_property') {
        return new WP_Error('rest_property_not_found', __('Property not found'), ['status' => 404]);
    }

    $cached_data = wpestate_api_get_cached_post_data($id, 'estate_property');

    if ($response_type === 'basic') {
        $response = wpestate_generate_basic_response($id);
    } else {
        $response = $cached_data;
    }

    // Filter response based on requested fields
    if ($fields && is_array($fields)) {
        $response = filter_response_fields($cached_data, $fields);
    }

    return rest_ensure_response($response);
}




/**
 * Check if the current user has permissions to perform an action.
 *
 * @param WP_REST_Request $request REST API request object.
 * @return bool|WP_Error True if the user has permission, otherwise a WP_Error.
 */
function wprentals_check_permissions(WP_REST_Request $request) {
    $user = wp_get_current_user();
    if (in_array('owner', $user->roles) || current_user_can('administrator')) {
        return true;
    }
    return new WP_Error('rest_forbidden', __('You do not have permission to perform this action'), ['status' => 403]);
}


/**
 * Filter the response based on requested fields, supporting nested fields and preserving structure.
 *
 * @param array $data The original response data.
 * @param array $fields The requested fields.
 * @return array Filtered data.
 */
/**
 * Filter the response based on requested fields, supporting nested fields and preserving structure.
 *
 * @param array $data The original response data.
 * @param array $fields The requested fields.
 * @return array|null Filtered data or null if no fields match.
 */
/**
 * Filter the response based on requested fields, supporting nested fields and preserving structure.
 *
 * @param array $data The original response data.
 * @param array $fields The requested fields.
 * @return array Filtered data.
 */
function filter_response_fields($data, $fields) {
    $filtered = [];

    foreach ($fields as $field) {
        $keys = explode('.', $field); // Handle nested keys
        $current = &$filtered;
        $source = $data;

        foreach ($keys as $key) {
            if (isset($source[$key])) {
                if (!isset($current[$key])) {
                    $current[$key] = [];
                }
                $current = &$current[$key];
                $source = $source[$key];
            } else {
                // If the key does not exist in the source, skip
                $current = null;
                break;
            }
        }

        if ($current !== null) {
            $current = $source;
        }
    }

    return $filtered;
}
