<?php 

function wpestate_api_build_taxonomy_query($taxonomy_input) {
    if (empty($taxonomy_input)) {
        return array();  // Return empty array instead of array with relation
    }
    
    // Initialize the main tax query array with AND relation
    $tax_query_array = array('relation' => 'AND');
    
    // Loop through each taxonomy and its terms
    foreach ($taxonomy_input as $taxonomy => $terms) {
        // Skip if terms is empty
        if (empty($terms)) {
            continue;
        }
        
        // Filter out 'all' and empty terms
        $filtered_terms = array_filter($terms, function($term) {
            $term = trim($term);
            return !empty($term) && strtolower($term) !== 'all';
        });
        
        // Skip if no valid terms remain after filtering
        if (empty($filtered_terms)) {
            continue;
        }
        
        // Determine if we're dealing with term IDs or slugs
        $field = is_numeric($filtered_terms[array_key_first($filtered_terms)]) ? 'term_id' : 'slug';
        
        // Add the taxonomy query
        $tax_query_array[] = array(
            'taxonomy' => $taxonomy,
            'field'    => $field,
            'terms'    => $filtered_terms,
            'operator' => 'IN'
        );
    }
    
    return count($tax_query_array) > 1 ? $tax_query_array : array();
}