<?php
/**
 * WpRentals review Metaboxes
 *
 * This file contains functions that create and populate custom metaboxes
 * for the 'wpestate_review' custom post type in the WordPress admin area.
 * These metaboxes provide a user interface for viewing and managing review
 * details such as sender, recipient, and review status.
 *
 * @package    WpRentals
 * @subpackage Messaging
 * @version    1.0
 * @author     WpRentals
 */



 
/**
 * Registers the custom metabox for review details
 *
 * This function hooks into WordPress to add a custom metabox to the 'estate_review'
 * post type edit screen. The metabox displays review-specific information and allows
 * administrators to view and modify review properties.
 *
 * @uses add_meta_box() WordPress function to register a metabox
 * @return void
 */
if( !function_exists('wpestate_add_reviews_metaboxes') ):
    function wpestate_add_reviews_metaboxes() {
      add_meta_box(  'estate_reviews-sectionid', esc_html__(  'Review Options', 'wprentals-core' ), 'wpestate_review_options', 'estate_review' ,'normal','default');
    }
endif; // end

if(!function_exists('wpestate_review_options')):
    function wpestate_review_options($post){

        $new = false;

        // $review_title = get_comment_meta( $comment->comment_ID , 'review_title', true );
        $stars         = get_post_meta( $post->ID, 'review_stars', TRUE );
        if ( is_array( $stars ) ) {
            $stars = json_encode( $stars );
        }
		$rating_fields = wpestate_get_review_fields();
		$max_stars     = wpestate_get_max_stars();

        $attached_to = get_post_meta( $post->ID , 'attached_to', true );
        $author = get_post_meta( $post->ID , 'review_author', true );
        $reply = get_post_meta( $post->ID , 'owner_reply', true );
        if ( ! $author ) {
            $author = 1; // Fallback to current user if no author is set
        }
        $authorObj = get_user_by( 'id', $author );

        if ( empty( $stars ) && empty( $attached_to ) ) {
            $new = true;
        }

        

        if ( is_string( $stars )) {
			$tmp_rating = json_decode( $stars, TRUE );
			$fields     = '';
			$fields     .= '<table>' . PHP_EOL;
			foreach ( $rating_fields['fields'] as $field_key => $field_value ) {
				$fields .= '<tr>' . PHP_EOL;
				$fields .= '<th align="left">' . esc_html( $field_value ) . '</th>' . PHP_EOL;
				$fields .= '<td><input name="star-rating-field[' . esc_attr( $field_key ) . ']" type="number" value="' . (!empty($tmp_rating) ? esc_attr( $tmp_rating[ $field_key ] ) : 0) . '" max="' . intval( $max_stars ) . '" min="1" step=".5"></td>' . PHP_EOL;
				$fields .= '</tr>' . PHP_EOL;
			}
			$fields .= '</table>' . PHP_EOL;
		} else if ( is_numeric( $stars ) ) {
			$i             = 1;
			$starts_select = '';
			while ( $i <= $max_stars ) {
				$starts_select .= '<option value="' . $i . '"';
				if ( $stars == $i ) {
					$starts_select .= ' selected="selected" ';
				}
				$starts_select .= '>' . $i . '</option>';
				$i ++;
			}
			$fields .= sprintf( '<select name="review_stars">%s</select>', $starts_select );
		}

        wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );

        if ( $new === false )   {
            $authorField = '<input type="text" name="reviewer_name" class="wprentals-2025-input" value="'.esc_attr($authorObj->data->user_nicename).'" style="width:100%;" />';
        } else {
            // Get authors
            $authors = get_users( array( 'fields' => array( 'ID', 'user_nicename' ) ) );
            $authorField = '<select name="review_author" class="wprentals-2025-select" style="width:100%;">';
            $authorField .= '<option value="">' . esc_html__( 'Select Author', 'wprentals-core' ) . '</option>';
            foreach ( $authors as $author ) {
                $authorField .= '<option value="' . esc_attr( $author->ID ) . '">' . esc_html( $author->user_nicename ) . '</option>';
            }
            $authorField .= '</select><input type="hidden" name="new-review" value="1">';
        }

        print '
            <table width="50%">
            <tr>
                <td width="33%" valign="top" align="left">
                    '.esc_html__( 'Author','wprentals-core').'
                </td>
                <td width="50%" valign="top" align="left">
                    '.$authorField.'
                </td>
            </tr>';
        // print '<tr>
        //         <td width="33%" valign="top" align="left">
        //             '.esc_html__( 'Owner Reply','wprentals-core').'
        //         </td>
        //         <td width="50%" valign="top" align="left">
        //             <textarea name="owner_reply" class="wprentals-2025-input" style="width:100%;">'.esc_attr($reply).'</textarea>
        //         </td>
        //     </tr>';
        print '<tr>
                <td width="33%" valign="top" align="left">
                    '.esc_html__( 'Attached to','wprentals-core').'
                </td>
                <td width="50%" valign="top" align="left">
                    <select class="wprentals-2025-select" name="attached_to">
                        <option value="">' . esc_html__( 'Select', 'wprentals-core' ) . '</option>';
                        if($attached_to){
                            $attached_to = intval($attached_to);
                            $post_title = get_the_title($attached_to);
                            print '<option value="'.$attached_to.'" selected="selected">'.$post_title.'</option>';
                        } else {
                            // Get listings
                            $listings = get_posts( array(
                                'post_type'      => 'estate_property',
                                'posts_per_page' => -1,
                                'post_status'    => 'publish',
                                'orderby'        => 'title',
                                'order'          => 'ASC',
                                'fields'         => 'ids',
                            ) );
                            foreach ( $listings as $listing ) {
                                $post_title = get_the_title($listing);
                                print '<option value="'.$listing.'">'.$post_title.'</option>';
                            }
                        }
                    print '</select>
                </td>
            </tr>
            <tr>
                <td width="33%" valign="top" align="left">
                    '.esc_html__( 'Rating','wprentals-core').'
                </td>
                <td width="50%" valign="top" align="left">
                        '.$fields.'
                </td>
            </tr>

            </table>';
    }
endif;

/**
 * Saves the custom data for the review post type
 *
 * This function hooks into the 'save_post' action to save custom metadata
 * for the 'estate_review' post type when a review is created or updated.
 * It specifically saves the review stars rating.
 *
 * @param int $post_id The ID of the post being saved
 * @param WP_Post $post The post object being saved
 */
add_action('save_post', 'wpestate_handle_reviews_custom_data_save', 1, 2);
/*
 * This function is triggered when a post is saved.
 * It checks if the post type is 'estate_review' and if the nonce is valid.
 * If so, it updates the 'review_stars' meta field with the value from the form.
 */
if(!function_exists('wpestate_handle_reviews_custom_data_save')):
function wpestate_handle_reviews_custom_data_save( $post_id, $post ) {

    // Validate that we have a proper post object
    if(!is_object($post) || !isset($post->post_type)) {
        return;
    }

    // Only process estate_review post type
    if($post->post_type!='estate_review'){
        return;
    }

    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ){
        return;
    }

    if ( ( isset( $_POST['review_stars'] ) ) && ( $_POST['review_stars'] != '') ) {
	    update_post_meta( $post_id, 'review_stars',  intval($_POST['review_stars']) );
    }
    if ( ( isset( $_POST['star-rating-field'] ) ) && ( $_POST['star-rating-field'] != '') ) {
	    $starsTotal = array_sum( $_POST['star-rating-field'] );
        $ratingValue = number_format(round(($starsTotal / count($_POST['star-rating-field'])) * 2) / 2, 1);
        $_POST['star-rating-field']['rating'] = $ratingValue;
        update_post_meta( $post_id, 'review_stars',  json_encode( $_POST['star-rating-field'] ) );
    }

    if ( isset( $_POST['review_author'] ) ) {
        update_post_meta( $post_id, 'review_author',  sanitize_text_field( $_POST['review_author'] ) );
    }

    // if ( isset( $_POST[ 'new-review' ] ) ) {
    //     // Handle new review submission
    //     if ( ( isset( $_POST['star-rating-field'] ) ) && ( $_POST['star-rating-field'] != '') ) {
    //         $starsTotal = array_sum( $_POST['star-rating-field'] );
    //         $ratingValue = number_format(round(($starsTotal / count($_POST['star-rating-field'])) * 2) / 2, 1);
    //         $_POST['star-rating-field']['rating'] = $ratingValue;
    //         update_post_meta( $post_id, 'review_stars',  json_encode( $_POST['star-rating-field'] ) );
    //     }
    // }

    if ( ( isset( $_POST['attached_to'] ) ) && ( $_POST['attached_to'] != '') ) {
	    update_post_meta( $post_id, 'attached_to',  $_POST['attached_to'] );
        wpestate_calculate_property_rating( $_POST['attached_to'] );
    }

    // if ( ( isset( $_POST['attached_to'] ) ) && ( $_POST['attached_to'] != '') && ( isset( $_POST['star-rating-field'] ) ) && ( $_POST['star-rating-field'] != '') ) {
    //     wpestate_calculate_property_rating( $_POST['attached_to'] );
    // }

    if ( ( isset( $_POST['owner_reply'] ) ) && ( $_POST['owner_reply'] != '') ) {
	    update_post_meta( $post_id, 'owner_reply',  $_POST['owner_reply'] );
    }

}
endif;