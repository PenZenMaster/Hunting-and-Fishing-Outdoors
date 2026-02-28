<?php 
/**
 * Recursively escape API response data for output.
 *
 * @param mixed $data The data to escape
 * @return mixed The escaped data
 */
function wprentals_escape_api_response($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = wprentals_escape_api_response($value);
        }
        return $data;
    } else if (is_object($data)) {
        foreach (get_object_vars($data) as $key => $value) {
            $data->$key = wprentals_escape_api_response($value);
        }
        return $data;
    } else if (is_string($data)) {
        // Handle different data types appropriately
        if (filter_var($data, FILTER_VALIDATE_URL)) {
            return esc_url($data);
        } else if (preg_match('/<[^>]*>/', $data)) {
            // Contains HTML
            return wp_kses_post($data);
        } else {
            return esc_html($data);
        }
    } else {
        // Return non-string values as is (numbers, booleans, etc.)
        return $data;
    }
}


/**
 * Parse the fields parameter for API requests.
 * 
 * Accepts fields in various formats (string, array) and
 * normalizes to an array of field names.
 *
 * @param mixed $fields The fields parameter from the request.
 * @return array|null Array of field names or null if no valid fields.
 */
function wprentals_parse_api_fields_param($fields) {
    // If already an array, just return it
    if (is_array($fields)) {
        return $fields;
    }
    
    // If string, parse it as comma-separated list
    if (is_string($fields) && !empty($fields)) {
        return array_map('trim', explode(',', $fields));
    }
    
    // If null or invalid, return null
    return null;
}