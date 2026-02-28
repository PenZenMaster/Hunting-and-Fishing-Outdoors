<?php 



// Example usage of the function
/*$meta_query_params = array(
    array(
        'key'     => 'price',
        'value'   => 200,
        'compare' => '>=',
        'type'    => 'numeric'  // Specify 'numeric' for numerical comparison
    ),
    array(
        'key'     => 'color',
        'value'   => 'blue',
        'compare' => '=',
        'type'    => 'text'  // 'text' can be omitted, defaults to 'CHAR'
    )
);

*/
// Example usage of the function with 'AND' and 'OR' relationships
/*
$tax_query_params = array(
    'relation' => 'OR',  // Overall relation for all taxonomies
    array(
        'taxonomy' => 'category',
        'field'    => 'slug',
        'terms'    => array('news', 'updates'),
        'operator' => 'IN'
    ),
    array(
        'taxonomy' => 'post_tag',
        'field'    => 'name',
        'terms'    => array('featured', 'top'),
        'operator' => 'AND'
    )
);






$taxonomy_input_sample = array(
    'taxonomy_name_1'=> array( slug1, slug2, slug3),
    'taxonomy_name_2'=> array( term_id_1, term_id_2, term_id_3),
)



*/
 // print '<pre>';
   // print_r($args);
   // print('</pre>');
function wpestate_api_custom_query(
    $post_type='post',
    $paged=1,
    $posts_per_page=10,
    $meta_input=array(),
    $taxonomy_input=array(),
    $order=1,
    $userID=null,
    $query_type = 'web') {
        
        // create order array
        $order_array = wpestate_api_create_query_order_by_array($order);

        $args = array(
            'post_type'         => $post_type,
            'post_status'       => 'publish',
            'paged'             => $paged,
            'posts_per_page'    => $posts_per_page,
            'fields'            => 'ids'  // Only get post IDs
        );

         // Add author filter if userID is provided
        if (!empty($userID)) {
            $args['author'] = $userID;
        }


        // Add meta query only if not empty
        $meta_query = wpestate_api_build_meta_query($meta_input);
        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        // Add tax query only if not empty
        $tax_query = wpestate_api_build_taxonomy_query($taxonomy_input);
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        // Merge order arguments
        $args = array_merge($args, $order_array);

        if ($query_type === 'web') {
            if ($order == 0) {
                if (function_exists('wpestate_return_filtered_by_order')) {
                    $prop_selection = wpestate_return_filtered_by_order($args);
                }
            } else {
                $prop_selection = new WP_Query($args);
            }
            
            if ($prop_selection->have_posts()) {
                foreach ($prop_selection->posts as $postID) {
                    print $postID . '   <br>';
                }
            }
        } else {
            $query = new WP_Query($args);
            $return_array = array(
                'post_ids' => $query->posts,  // Will be array of IDs
                'total_posts' => $query->found_posts,
                'max_num_pages' => $query->max_num_pages,
                'args' => $args
            );
        }

        wp_reset_postdata();

        if ($query_type !== 'web') {
            return $return_array;
        }
}







