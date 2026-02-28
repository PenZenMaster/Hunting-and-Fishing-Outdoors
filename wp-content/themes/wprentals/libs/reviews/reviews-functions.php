<?php

/*
*
* Property reviews 
*
*/
if( !function_exists('wpestate_property_show_reviews')):
    function wpestate_property_show_reviews($postID ){
        $return_string='';

        $cpaged           = ( get_query_var( 'rp' ) != '' ) ? get_query_var( 'rp' ) : 1;
        $reviews_per_page = 7;// set low for testing purposes

        $attached_to_value = $postID;
        $attached_to_compare = '=';
        $suppress_filters = false;

        // WPML workaround for compsupp-8268.
        if ( class_exists('Sitepress') ) {
            $trid = apply_filters( 'wpml_element_trid', null, $postID, 'estate_property' );
            $translations = apply_filters( 'wpml_get_element_translations', null, $trid, 'estate_property' );
            $attached_to_value = array();

            foreach ( $translations as $lang => $tr ) {
                $attached_to_value[] = (int) $tr->element_id;
            }

            $attached_to_compare = 'IN';
            $suppress_filters = true;
        }

        $args = array(
            'post_type'         => 'estate_review',
            'post_parent'       => 0,
            'post_status'       => 'publish',
            'posts_per_page'    => intval( $reviews_per_page ),
            'meta_query'        => array(
                array(
                    'key'     => 'attached_to',
                    'value'   => $attached_to_value,
                    'compare' => $attached_to_compare,
                )
            ),
            'paged'             => intval( $cpaged ),
            'suppress_filters'  => $suppress_filters,
        );

        $comments_query = new WP_Query( $args );
        $comments       = $comments_query->posts;
        $nr_of_reviews  = $comments_query->found_posts;
        $review_pages   = $comments_query->max_num_pages;
        $coments_no =   0;
        $stars_total=   0;
        $review_templates=' ';
        
        foreach($comments as $comment) :
            $coments_no++;

            $userId         =   get_post_meta($comment->ID, 'review_author', true);
            if ( empty($userId) ){
                $userId = $comment->post_author;
            }
      
            $authorObj = get_user_by( 'id', $userId );
        
            if($userId == 1){
                $reviewer_name="admin";
                $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
            }else{
                $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
                $reviewer_name  =   get_sanitized_truncated_title($userid_agent, 0);
                if($userid_agent==''){
                    $reviewer_name=   $authorObj->data->user_nicename;
                }
                
            }
        
            if($userid_agent==''){
                $user_small_picture_id     =    get_the_author_meta( 'small_custom_picture' , $userId, true  );
                $preview                   =    wp_get_attachment_image_src($user_small_picture_id,'wpestate_user_thumb');
                $preview_img               =    '';
                if(isset($preview[0])){
                    $preview_img               =    $preview[0];
                }
              
            }else{
                $thumb_id           = get_post_thumbnail_id($userid_agent);
                $preview            = wp_get_attachment_image_src($thumb_id, 'thumbnail');
                $preview_img               =    '';
                if(isset($preview[0])){
                    $preview_img               =    $preview[0];
                }
            }
        
            if($preview_img==''){
                $preview_img = wprentals_get_option('wp_estate_default_user_image', 'url');
                if ( empty($preview_img) ) {
                    $preview_img = get_stylesheet_directory_uri().'/img/default-user.png';
                }
            }

            $rating= get_post_meta( $comment->ID , 'review_stars', true );
            $review_templates.='  
                 <div class="listing-review">
                             
        
                                <div class="col-md-8 review-list-content norightpadding">
                                    <div class="reviewer_image"  style="background-image: url('.esc_url($preview_img).');"></div>
                                  
                                    <div class="reviwer-name">'.esc_html($reviewer_name).'</div>
                                    
                                    <div class="review-date">
                                        '.esc_html__( 'Posted on ','wprentals' ). ' '. get_the_date('j F Y',$comment->ID).' 
                                    </div>
                                    
                                    <div class="property_ratings">';
        
                                    $review_templates .= wpestate_display_rating($rating, 'total');
                                    $total_rating = wpestate_get_star_total_rating($rating);
                                    $review_templates.=' <span class="ratings-star">( ' . $total_rating . ' ' . esc_html__( 'of','wprentals').' 5)</span>
                                    </div>
        
                                    <div class="review-content">
                                        '. wp_kses_post( apply_filters( 'the_content', $comment->post_content ) );

                                        $owner_reply_review = get_posts( array( 'post_type' => 'estate_review', 'post_parent' => $comment->ID, 'post_status' => 'any' ) );
                                        // $owner_reply = get_post_meta($comment->ID,'owner_reply',true);

                                        if( !empty($owner_reply_review) && $owner_reply_review[0]->post_status == 'publish' ){
                                            $review_templates.='<div class="review-content-owner-reply">';
                                            $review_templates.= '<h4 class="reviwer-name">'.esc_html__('Owner Reply','wprentals').'</h4>';
                                            $review_templates.= wp_kses_post( apply_filters( 'the_content', $owner_reply_review[0]->post_content ) );
                                            $review_templates.='</div>';
                                        }


                                    $review_templates.='</div>
                                </div>
                            </div>       ';
        
        endforeach;
        
        if($coments_no>0){
            $list_rating = get_post_meta($postID, 'property_stars', TRUE);
            if ( ! $list_rating ) {
                $list_rating = wpestate_calculate_property_rating( $postID );
            }
        
        $return_string.='    
        <div class="property_page_container for_reviews">
            <div class="listing_reviews_wrapper">
                    <div class="listing_reviews_container">
                        <h3 id="listing_reviews" class="panel-title">
                               '.sprintf( _n('%d Review', '%d Reviews', $coments_no, 'wprentals'), $nr_of_reviews ).'                                                            
                        </h3>
                        
                        <div class="property_ratings">
                            '.wpestate_display_rating($list_rating, 'complete').'
                        </div>
        
                        '.trim($review_templates);
                        ob_start();
                        wpestate_review_pagination($review_pages, 2);
                        $pagination=ob_get_contents();
                        ob_end_clean();

                        $return_string.=$pagination.'
                </div>
              
            </div>
        </div>';
        } 
    
        return $return_string;
    }
endif;

if (!function_exists('wpestate_review_pagination')) {

    /**
     * Display review pagination
     *
     * @param string $pages
     * @param int $range
     */
    function wpestate_review_pagination($pages = '', $range = 2) {
        global $post;
        $showitems = ($range * 2) + 1;
        $cpaged = (get_query_var('rp') != '') ? get_query_var('rp') : 1;

        if ($pages == '') {
            if (!$pages) {
                $pages = 1;
            }
        }

        if (1 != $pages) {
            echo '<ul class="pagination pagination_nojax" id="wprentals_review_pagination">';

            if (($cpaged - 1) <= 1) {
                $prev_page = wpestate_review_paging_url(array('rp' => ''), 'rem');
            } else {
                $prev_page = wpestate_review_paging_url(array('rp' => $cpaged - 1));
            }

            printf('<li class="roundleft"><a href="%s"><i class="fas fa-chevron-left"></i></a></li>', esc_url($prev_page));

            for ($i = 1; $i <= $pages; $i++) {
                if (1 != $pages && (!($i >= $cpaged + $range + 1 || $i <= $cpaged - $range - 1) || $pages <= $showitems)) {
                    $active = ($cpaged == $i) ? ' class=active ' : '';
                    printf('<li %s><a href="%s" >%d</a><li>', esc_attr($active), esc_url(wpestate_review_paging_url(array('rp' => $i))), $i);
                }
            }

            $next_page = wpestate_review_paging_url(array('rp' => $cpaged + 1));
            if (($cpaged + 1) > $pages) {
                $next_page = wpestate_review_paging_url(array('rp' => $cpaged));
            }

            printf('<li class="roundright"><a href="%s"><i class="fas fa-chevron-right"></i></a><li>', esc_url($next_page));

            echo "</ul>";
        }
    }

} // end   wpestate_review_pagination

if (!function_exists('wpestate_starts_reviews_core')):

    function wpestate_starts_reviews_core($stars) {
        $whole = floor($stars);
        $fraction = $stars - $whole;
        $return_string = '';

        for ($i = 1; $i <= $whole; $i++) {
            $return_string .= '<i class="fas fa-star"></i>';
        }
        if ($fraction > 0) {
            $return_string .= '<i class="fas fa-star-half"></i>';
        }
        return $return_string;
    }

endif;

/**
*
*
* Return property ratings v1 - to be used in title 
*
*
*/

if(!function_exists('wprentals_return_property_ratiings_v1')):
    function wprentals_return_property_ratiings_v1($postID) {
        $return_string='<div class="property_ratings">';
    
        if(wpestate_has_some_review($postID) !== 0){
            $reviews = get_posts(array(
                'post_type' => 'estate_review',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'post_parent' => 0,
                'meta_query' => array(
                    array(
                        'key' => 'attached_to',
                        'value' => $postID,
                        'compare' => '='
                    )
                )
            ));
            
            $coments_no = count($reviews);
            
            if($coments_no > 0){
                $return_string.= wpestate_display_property_rating($postID); 
                $return_string.= '<div class="rating_no">('.esc_html($coments_no).')</div>';
            }
        } 
            
        $return_string.='</div>';
        return $return_string;
    }
endif;

if (!function_exists('wpestate_review_composer')):

    function wpestate_review_composer($agent_id) {
        global $post;
        $prop_no = intval(wprentals_get_option('wp_estate_prop_no', ''));
        $owner_id = get_post_meta($agent_id, 'user_agent_id', true);
        
        if ($owner_id == 0) {
            $return_array['list_rating'] = 0;
            $return_array['coments_no'] = 0;
            $return_array['prop_selection'] = '';
            $return_array['templates'] = '';
            return $return_array;
        }

        $post_array = array();
        $post_array[] = 0;
        $return_array = array();
        $paged = 1;

        if (isset($_GET['pagelist'])) {
            $paged = intval($_GET['pagelist']);
        }

        $args = array(
            'post_type' => 'estate_property',
            'author' => $owner_id,
            'paged' => $paged,
            'posts_per_page' => $prop_no,
            'post_status' => 'publish'
        );

        $prop_selection = new WP_Query($args);
        $return_array['prop_selection'] = $prop_selection;
        wp_reset_postdata();

        $arg2_reviews = array(
            'post_type' => 'estate_property',
            'author' => $owner_id,
            'paged' => 1,
            'posts_per_page' => 100,
            'post_status' => 'publish'
        );
        
        $prop_selection_Reviews = new WP_Query($arg2_reviews);
        
        if ($prop_selection_Reviews->have_posts()) {
            while ($prop_selection_Reviews->have_posts()):
                $prop_selection_Reviews->the_post();
                $post_array[] = $post->ID;
            endwhile;
            wp_reset_postdata();

            // Get reviews for all properties
            $reviews = get_posts(array(
                'post_type' => 'estate_review',
                'post_status' => 'publish',
                'posts_per_page' => 15,
                'post_parent' => 0,
                'meta_query' => array(
                    array(
                        'key' => 'attached_to',
                        'value' => $post_array,
                        'compare' => 'IN'
                    )
                )
            ));

            $coments_no = 0;
            $stars_total = 0;
            $review_templates = '';

            foreach ($reviews as $review) :
                $coments_no++;     
                $reviewer_id =  get_post_meta($review->ID, 'review_author', true);
                $userid_agent = get_user_meta($reviewer_id, 'user_agent_id', true);
                $reviewer_name = get_the_title($userid_agent);
                
                
                if ($userid_agent == '') {
                    $reviewer_name = get_the_author_meta('display_name', $reviewer_id);
                }

                if ($userid_agent == '') {
                    $user_small_picture_id = get_the_author_meta('small_custom_picture', $reviewer_id, true);
                    $preview = wp_get_attachment_image_src($user_small_picture_id, 'wpestate_user_thumb');
                    $preview_img = '';
                    if(isset($preview[0])){
                        $preview_img = $preview[0];
                    }
                } else {
                    $thumb_id = get_post_thumbnail_id($userid_agent);
                    $preview = wp_get_attachment_image_src($thumb_id, 'thumbnail');
                    $preview_img = isset($preview[0]) ? $preview[0] : '';
                }

                if ($preview_img == '') {
                    $preview_img = wprentals_get_option('wp_estate_default_user_image', 'url');
                    if ( empty($preview_img) ) {
                        $preview_img = get_stylesheet_directory_uri().'/img/default_user.png';
                    }
                }

         $rating = get_post_meta($review->ID, 'review_stars', true);
$tmp_rating = is_array($rating) ? $rating : json_decode($rating, true);
$rating = wpestate_get_star_total_value($tmp_rating);
$stars_total += $rating;
                $review_templates .= '
                    <div class="listing-review">
                        <div class="col-md-12 review-list-content norightpadding">
                            <div class="reviewer_image" style="background-image: url(' . $preview_img . ');"></div>
                            <div class="reviwer-name">' . $reviewer_name . '</div>
                            <div class="property_ratings">';
                $review_templates .= wpestate_display_rating($rating);
                $review_templates .= ' <span class="ratings-star">(' . wpestate_get_star_total_value(wpestate_get_star_total_rating($rating)) . ' ' . esc_html__('of', 'wprentals') . ' 5)</span>
                            </div>
                            <div class="review-content">';
                            $review_content=$review->post_content;
                            $review_content = preg_replace('/<!--.*?-->/s', '', $review_content); // remove Gutenberg comments
$review_content = wp_strip_all_tags($review_content); // remove HTML
$review_templates .=

                                esc_html( $review_content );

                $owner_reply_review = get_posts( array( 'post_type' => 'estate_review', 'post_parent' => $review->ID, 'post_status' => 'any' ) );
                // $owner_reply = get_post_meta($review->ID, 'owner_reply', true);
                if ( !empty( $owner_reply_review ) )  {
                    $review_templates .= '<div class="review-content-owner-reply">';
                    if ( $owner_reply_review[0]->post_status == 'publish' )    {
                    $review_templates .= '<h4 class="reviwer-name">'.esc_html('Owner Reply','wprentals').'</h4>';
           $review_content = $owner_reply_review[0]->post_content;
$review_content = preg_replace('/<!--.*?-->/s', '', $review_content); // remove Gutenberg comments
$review_content = wp_strip_all_tags($review_content); // remove HTML
$review_templates .= esc_html(trim($review_content));
                    }
                    $review_templates .= '</div>';
                }

                $review_templates .= '<div class="review-date">
                                ' . esc_html__('Posted on ', 'wprentals') . ' ' . get_the_date('j F Y', $review->ID) . '
                                </div>
                            </div>
                        </div>
                    </div>';

            endforeach;

            $return_array['templates'] = $review_templates;
            $list_rating = 0;
            if ($coments_no > 0) {
                $list_rating = ceil($stars_total / $coments_no);
            }

            $return_array['list_rating'] = $list_rating;
            $return_array['coments_no'] = $coments_no;
        }

        return $return_array;
    }

endif;

if (!function_exists('wpestate_has_some_review')) :

    function wpestate_has_some_review($property_id) {
        $total_stars = get_post_meta($property_id, 'property_stars', TRUE);
        $total_stars = json_decode($total_stars, TRUE);


        $total = 0;
        if (is_array($total_stars)) {
            foreach ($total_stars as $key => $value) {
                $total = $total + intval($value);
            }
        }
        return $total;
    }

endif;


if (!function_exists('wpestate_display_property_rating')) {

    /**
     * Simple property rating display function
     *
     * @param $proeprty_id
     * @param string $type (total|fields|complete), 'total' is default
     *
     * @return string
     */
    function wpestate_display_property_rating($proeprty_id, $type = 'total') {
        $star_rating = '';
        $total_stars = get_post_meta($proeprty_id, 'property_stars', TRUE);
        if (!$total_stars) {
            $total_stars = wpestate_calculate_property_rating($proeprty_id);
        }

        if ($total_stars != '') {
            $star_rating = sprintf('<div class="property-rating">%s</div>', wpestate_display_rating($total_stars, $type));
        }

        return $star_rating;
    }

}


if (!function_exists('wpestate_get_max_stars')) {

    /**
     * Set the max. nr. of stars for review
     *
     * @return int
     */
    function wpestate_get_max_stars() {
        return 5;
    }

}

if (!function_exists('wpestate_display_rating')) {

    /**
     * Display star rating
     *
     * @param $rating
     * @param string $type can be total|fields|complete
     *
     * @return string
     */
    function wpestate_display_rating($rating, $type = 'total') {
        $rating_fields = wpestate_get_review_fields();

        if (is_string($rating) && strlen($rating) > 3) {
            $tmp_rating = json_decode($rating, TRUE);
            switch ($type) {
                case 'total':
                    $stars_total = wpestate_get_star_total_value($tmp_rating);
                    $star_rating = wpestate_render_rating_stars($stars_total);
                    break;
                case 'fields':
                    $star_rating = wpestate_render_fields_rating($tmp_rating, $rating_fields['fields']);
                    break;
                case 'complete':
                    $star_rating = '<div class="property-rating">' . PHP_EOL;
                    $stars_total = wpestate_get_star_total_value($tmp_rating);
                    $star_rating .= wpestate_render_rating_stars($stars_total);
                    $star_rating .= wpestate_render_fields_rating($tmp_rating, $rating_fields['fields']);
                    $star_rating .= '</div> <!-- end .property-rating -->' . PHP_EOL;
                    break;
            }
        } else {
            $star_rating = wpestate_render_rating_stars($rating);
        }

        return $star_rating;
    }

}

if (!function_exists('wpestate_render_rating_stars')) {

    /**
     * Render rating fields
     *
     * @param $rating
     * @param $rating_fields
     *
     * @return string
     */
    function wpestate_render_fields_rating($rating, $rating_fields) {
        $star_rating = '';
        foreach ($rating_fields as $field_key => $field_value) {
            if (isset($rating[$field_key])) {
                $star_rating .= sprintf('<div class="%s">', esc_attr($field_key)) . PHP_EOL;
                $star_rating .= sprintf('<span class="rating_legend">%s</span>', esc_html($field_value)) . PHP_EOL;
                $star_rating .= wpestate_render_rating_stars(intval($rating[$field_key]));
                $star_rating .= sprintf('</div><!-- end .%s -->', esc_attr($field_key)) . PHP_EOL;
            }
        }

        return $star_rating;
    }

}

if (!function_exists('wpestate_render_rating_stars')) {

    /**
     * Renders the actual star rating
     *
     * @param $rating
     *
     * @return string
     */
    function wpestate_render_rating_stars($rating) {
        $rating = floatval($rating);
        $max_rating = wpestate_get_max_stars();
        if (floor($rating) < $rating) { // add '&& 1 == 0' to disable half star rating
            $half_stars = '<i class="fas fa-star-half-alt"></i>';
        } else {
            $half_stars = '';
            $rating = ceil($rating); // to fix if half star rating is disabled
        }
        $full_stars = str_repeat('<i class="fas fa-star"></i>', intval($rating));
        $empty_stars = str_repeat('<i class="far fa-star"></i>', intval(( $max_rating - $rating)));

        return $full_stars . $half_stars . $empty_stars;
    }

}

if (!function_exists('wpestate_get_star_total_value')) {

    /**
     * Find the total rating value
     * (old and new rating system)
     *
     * @param $rating
     *
     * @return mixed
     */
    function wpestate_get_star_total_value($rating) {

        if (isset($rating['total'])) {
            $stars_total = $rating['total'];
        } else if (isset($rating['rating'])) {
            $stars_total = $rating['rating'];
        } else {
            $stars_total = $rating;
        }

        return $stars_total;
    }

}

if (!function_exists('wpestate_get_star_total_rating')) {

    /**
     * Returns the total rating
     *
     * @param $rating
     *
     * @return mixed
     */
    function wpestate_get_star_total_rating($rating) {
        $tmp_rating = json_decode($rating, TRUE);

        return wpestate_get_star_total_value($tmp_rating);
    }

}

if (!function_exists('wpestate_get_review_fields')) {

    /**
     * Define review fields
     *
     * @return array
     */
    function wpestate_get_review_fields() {
        $fields = array(
            'total' => esc_html__('Total', 'wprentals'),
            'fields' => array(
                'accuracy' => esc_html__('Accuracy', 'wprentals'),
                'communication' => esc_html__('Communication', 'wprentals'),
                'cleanliness' => esc_html__('Cleanliness', 'wprentals'),
                'location' => esc_html__('Location', 'wprentals'),
                'check_in' => esc_html__('Check-In', 'wprentals'),
                'value' => esc_html__('Value', 'wprentals'),
            )
        );

        return $fields;
    }

}

add_action('delete_post', 'wpestate_delete_comment_admin');
add_action('wp_trash_post', 'wpestate_delete_comment_admin');
add_action('untrash_post', 'wpestate_delete_comment_admin');

function wpestate_delete_comment_admin($comment_id) {

    if ( get_post_type($comment_id) !== 'estate_review' ) {
        return;
    }

    $comment = get_post($comment_id);
    $comment_post_id = $comment->ID;
    wpestate_calculate_property_rating($comment_post_id);
}

if (!function_exists('wpestate_calculate_property_rating')) {

    /**
     * Calculates the property rating
     *
     * @param $property_id
     *
     * @return string|void
     */
    function wpestate_calculate_property_rating($property_id) {
        if (!$property_id) {
            return;
        }

        $reviews = get_posts(array(
            'post_type' => 'estate_review',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'post_parent' => 0,
            'meta_query' => array(
                array(
                    'key' => 'attached_to',
                    'value' => $property_id,
                    'compare' => '='
                )
            )
        ));

        $category_fields = wpestate_get_review_fields();
        $count_old_reviews = 0;
        $count_new_reviews = 0;
        $sum_old_reviews = 0;
        $stars_in_fields = array();
        $stars_fields = array();
        $stars_averages = array();
        $store = array();

        foreach ($reviews as $review) {
            $raw_review_rating = get_post_meta($review->ID, 'review_stars', TRUE);

            switch (TRUE) {
                // Old reviews (simple numeric rating)
                case (is_numeric($raw_review_rating)):
                    $count_old_reviews++;
                    $sum_old_reviews = $sum_old_reviews + intval($raw_review_rating);
                    $stars_in_fields['total'][] = intval($raw_review_rating);
                    break;

                // New reviews (JSON format)
                case (is_string($raw_review_rating)):
                    $count_new_reviews++;
                    $tmp_rating = json_decode($raw_review_rating, TRUE);
                    if (isset($tmp_rating['rating'])) {
                        $stars_in_fields['total'][] = $tmp_rating['rating'];
                    }

                    // gather all stars per field in an array
                    foreach ($category_fields['fields'] as $field_key => $field_value) {
                        if (isset($tmp_rating[$field_key])) {
                            $stars_in_fields[$field_key][] = $tmp_rating[$field_key];
                        }
                    }
                    break;
            }
        }

        // Sums per fields
        foreach ($category_fields['fields'] as $field_key => $field_value) {
            if (isset($stars_in_fields[$field_key])) {
                $stars_fields[$field_key] = array_sum($stars_in_fields[$field_key]);
                $tmp_round = round($stars_fields[$field_key] / count($stars_in_fields[$field_key]), 1);
                $stars_averages[$field_key] = wpestate_round_to_nearest_05($tmp_round);
                $store[] = sprintf('"%s": %s', $field_key, $stars_averages[$field_key]);
            }
        }

        // Calc total rating
        if (($count_new_reviews + $count_old_reviews) != 0) {
            $all_reviews_total = array_sum($stars_in_fields['total']) / (($count_new_reviews + $count_old_reviews));
        } else {
            $all_reviews_total = 0;
        }

        $property_rating['total'] = wpestate_round_to_nearest_05($all_reviews_total);
        // Construct rating string for db
        $store[] = sprintf('"%s": %s', 'rating', $property_rating['total']);
        $star_rating = '{' . implode(',', $store) . '}';
        update_post_meta($property_id, 'property_stars', $star_rating);
        return $star_rating;
    }

}


if (!function_exists('wpestate_round_to_nearest_05')) {

    /**
     * Rounds a number to the nearest .5
     *
     * examples: 4.5 => 4.5; 4.3 => 4; 4.7 => 5;
     *
     * @param $round
     *
     * @return float
     */
    function wpestate_round_to_nearest_05($round) {
        $tmp_remainder = $round - floor($round);

        switch (TRUE) {
            case ( $tmp_remainder > .5 ):
                $rounded = ceil($round);
                break;
            case ( $tmp_remainder < .5 ):
                $rounded = floor($round);
                break;
            default:
                $rounded = $round;
                break;
        }

        return $rounded;
    }

}

if (!function_exists('wpestate_query_vars')) {

    /**
     * Add reviews paging
     *
     * @param $vars
     *
     * @return array
     */
    function wpestate_query_vars($vars) {
        $vars[] = 'rp';

        return $vars;
    }

}

add_filter('query_vars', 'wpestate_query_vars');
