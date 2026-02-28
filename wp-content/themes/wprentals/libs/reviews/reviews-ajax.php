<?php

////////////////////////////////////////////////////////////////////////////////
/// Ajax  create review form
////////////////////////////////////////////////////////////////////////////////


add_action('wp_ajax_wpestate_show_review_form', 'wpestate_show_review_form' );

if (!function_exists('wpestate_show_review_form')):
    function wpestate_show_review_form(){
        check_ajax_referer( 'wprentals_reservation_actions_nonce', 'security' );
        $current_user = wp_get_current_user();
        $userID         =   $current_user->ID;

        if ( !is_user_logged_in() ) {
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }


        $user_email     =   $current_user->user_email;
        $listing_id     =   intval($_POST['listing_id']);
        $bookid         =   intval ($_POST['bookid']);

        $the_post= get_post( $bookid);

        if( $current_user->ID != $the_post->post_author ) {
            exit('you don\'t have the right to see this');
        }

        $review_fields = wpestate_get_review_fields();
	    $max_stars = wpestate_get_max_stars();
	    $field_html = '';
        foreach ( $review_fields['fields'] as $key => $label ) {
	        $fields = 0;
        	$field_html .= sprintf('<div class="%s">', esc_attr($key)) . PHP_EOL;
	        $field_html .= sprintf('<span class="rating_legend">%s</span>', $label);
        	while ( $fields < $max_stars) {
		        $fields++;
		        $field_html .= '<span class="empty_star"></span>' . PHP_EOL;
	        }
        	$field_html .= sprintf('</div><!-- end .%s -->', esc_attr($key)) . PHP_EOL;
        }

        print '
            <div class="create_invoice_form">
                    <h3>'.esc_html__( 'Post Review','wprentals').'</h3>'
              . $field_html .
                    '<textarea id="review_content" name="review_content" class="form-control"></textarea>

                    <div class="action1_booking" id="post_review" data-bookid="'.esc_attr($bookid).'" data-listing_id="'.esc_attr($listing_id).'">'.esc_html__( 'Submit Review','wprentals').'</div>
            </div>';
        die();
    }
endif;

////////////////////////////////////////////////////////////////////////////
/// Ajax  post review
////////////////////////////////////////////////////////////////////////////////
// This function handles the review submission, including validation and email notifications.
////////////////////////////////////////////////////////////////////////////////

// This function is triggered by an AJAX request to post a review.
// It checks the user's permissions, retrieves the necessary data, and inserts a new review post.
// It also sends an email notification to the property owner about the new review.
////////////////////////////////////////////////////////////////////////////////
add_action('wp_ajax_wpestate_post_review', 'wpestate_post_review' );

if (!function_exists('wpestate_post_review')):
    function wpestate_post_review(){
        check_ajax_referer( 'wprentals_reservation_actions_nonce', 'security' );
        $current_user = wp_get_current_user();
        $allowed_html=array();

        $bookid     =   intval($_POST['bookid']);
        $userID                         =   $current_user->ID;

        if ( !is_user_logged_in() ) {
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

        $the_post= get_post( $bookid);
        if( $current_user->ID != $the_post->post_author ) {
            exit('you don\'t have the right to see this');
        }

        $userID         =   $current_user->ID;
        $user_login     =   $current_user->user_login;
        $user_email     =   $current_user->user_email;
        $listing_id     =   intval($_POST['listing_id']);

        $booking_listing_id = intval( get_post_meta( $bookid, 'booking_id', true ) );
        if ( $booking_listing_id !== $listing_id ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid listing for this booking','wprentals' ) ) );
        }

        $stars_raw      =   isset( $_POST['stars'] ) ? wp_unslash( $_POST['stars'] ) : '';
        $stars_decoded = json_decode( $stars_raw, true );
        if ( ! is_array( $stars_decoded ) ) {
            wp_send_json_error( array( 'message' => esc_html__( 'Invalid rating data','wprentals' ) ) );
        }

        $max_stars      =   wpestate_get_max_stars();
        $sanitized_stars = array();
        foreach ( $stars_decoded as $key => $value ) {
            $sanitized_stars[ sanitize_key( $key ) ] = min( max( floatval( $value ), 0 ), $max_stars );
        }
        $stars          =   wp_json_encode( $sanitized_stars );

        $content        =   wp_kses( wp_unslash( $_POST['content'] ), $allowed_html );
        $time           =   time();
        $time = current_time('mysql');
        $status = 'pending';
        if (wprentals_get_option('wp_estate_admin_approves_reviews', '') == 'no') {
            $status = 'publish'; // Auto-approve if setting is 'no'
        }
        $review_data = array(
            'post_title' => sprintf('Review for %s', get_the_title($listing_id)),
            'post_content' => $content,
            'post_status' => $status,
            'post_type' => 'estate_review',
            'post_author' => get_current_user_id()
        );
        
        $review_id = wp_insert_post($review_data);

       if ($review_id) {
            // Add meta data
            update_post_meta($review_id, 'attached_to', $listing_id);
            update_post_meta($review_id, 'review_author', $userID);
            update_post_meta($review_id, 'review_stars', $stars);

            update_post_meta($listing_id,'review_by_'.$userID,'has');
            
            // Recalculate property rating
            wpestate_calculate_property_rating($listing_id);


            $author_id = get_post_field('post_author', $listing_id);

            // Get the user data for the author
            $user_data = get_userdata($author_id);
            $owner_email = $user_data->user_email;
    

            $total_stars_raw = get_post_meta( $review_id , 'review_stars', true );
            $total_stars = wpestate_get_star_total_rating( $total_stars_raw );

            $content= array(
                'stars'     =>  $total_stars,
                'user'      =>  $user_login,
                'content'   =>  $content,
                'listing_id'=>  $listing_id,
                
            );
            wpestate_send_booking_email('new_review',$owner_email,$content);

            wp_send_json_success(
                array(
                    'message' => esc_html__( 'Review posted successfully!','wprentals'),
                    'stars'   => $total_stars,
                    'user'    => $user_login,
                    'content' => $content,
                )
            );
            
        } else {
            wp_send_json_error(
                array(
                    'message' => esc_html__( 'Failed to post review. Please try again.','wprentals'),
                )
            );
        }


        die();

    }
endif;


/*
* Reply to review 
*
*
*/

add_action('wp_ajax_wpestate_review_message_reply', 'wpestate_review_message_reply' );

if( !function_exists('wpestate_review_message_reply') ):
    function wpestate_review_message_reply(){

        
        check_ajax_referer( 'wprentals_reviews_actions_nonce', 'security' );
        $current_user   =   wp_get_current_user();
        $userID         =   $current_user->ID;
        if ( !is_user_logged_in() ) {
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }

      


        $commentId         =   intval($_POST['commentId']);
        $propertyid        =   intval($_POST['propertyid']);
        $content           =   esc_html($_POST['content']);
      
        $post_author_id = get_post_field( 'post_author', $propertyid );

   


        if($post_author_id!=$userID){
            $answer=array(
                'succes'    =>  false,
                'message'   =>  esc_html__('You are not the property owner!','wprentals')
            );
            print json_encode($answer);
        }else{

            // Old owner reply
            update_post_meta($commentId,'owner_reply',$content);

            $status = 'pending';
            if (wprentals_get_option('wp_estate_admin_approves_reviews', '') == 'no') {
                $status = 'publish'; // Auto-approve if setting is 'no'
            }

            // Create a child review post
            $replyID = wp_insert_post(
                array(
                    'post_title'   => sprintf('Owner reply to Review #%d', $commentId),
                    'post_content' => $content,
                    'post_status'  => $status,
                    'post_type'    => 'estate_review',
                    'post_author'  => $userID,
                    'post_parent'  => $commentId,
                    'meta_input'   => array(
                        'attached_to' => $propertyid
                    )
                )
            );

            $author = get_user_by('id', $post_author_id);
            $author_email   = $author->user_email;
            $arguments=array(
                'reply_content'     =>  $content,
                'property_name'     =>  get_sanitized_truncated_title($propertyid, 0),
                'author_email'      =>  $author_email
            );


       
          


        
            wpestate_send_booking_email('review_reply',$author_email,$arguments);

            $responseMessage = esc_html__('We posted your reply','wprentals');
            if ( $status == 'pending' ) {
                $responseMessage = esc_html__('Your reply is waiting for approval.','wprentals');
            }


            $answer=array(
                'succes'        =>  true,
                'message'       =>  $responseMessage,
                'arguments'=>$arguments
            

            );
            print json_encode($answer);
        }

        die();
    }
endif;