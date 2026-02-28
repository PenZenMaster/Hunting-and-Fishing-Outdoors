<?php 
function wpestate_api_build_meta_query($meta_query) {
    if (empty($meta_query)) {
        return array();  // Return empty array instead of array with relation
    }
    
    // Prepare the meta query array with a default relation of 'AND'
    $meta_query_array = array('relation' => 'AND');
    
    // Iterate over each meta condition provided
    foreach ($meta_query as $meta) {
        if (is_array($meta) && isset($meta['key'])) {
            $meta_condition = array(
                'key'     => $meta['key'],
                'compare' => isset($meta['compare']) ? $meta['compare'] : '=',
            );

            // Add value if it exists and isn't a EXISTS/NOT EXISTS comparison
            if (isset($meta['value']) && 
                !in_array($meta['compare'], ['EXISTS', 'NOT EXISTS'])) {
                $meta_condition['value'] = $meta['value'];
            }

            // Add type if specified, maintaining the provided type
            if (isset($meta['type'])) {
                $meta_condition['type'] = $meta['type'];
            }

            $meta_query_array[] = $meta_condition;
        }
    }

    return !empty($meta_query_array) ? $meta_query_array : array();
}