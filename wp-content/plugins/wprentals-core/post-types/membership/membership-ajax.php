<?php 
/**
 * Membership AJAX Handlers
 * 
 * AJAX functions for membership-related features.
 * 
 * @package WPRentals
 * @subpackage Membership
 */





add_action( 'wp_ajax_wpestate_ajax_make_prop_featured', 'wpestate_ajax_make_prop_featured' );



/**
 * Make property featured via AJAX
 * 
 * @function wpestate_ajax_make_prop_featured
 * Verifies user can feature property and updates status
 */

if( !function_exists('wpestate_ajax_make_prop_featured') ):
    function  wpestate_ajax_make_prop_featured(){
        check_ajax_referer( 'wprentals_property_actions_nonce', 'security' );
        $prop_id=intval($_POST['propid']);
        $current_user = wp_get_current_user();
        $userID =   $current_user->ID;

        if ( !is_user_logged_in() ) {
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }



        $post   =   get_post($prop_id);

        if( $post->post_author != $userID){
            exit('get out of my cloud');
        }else{
            if(wpestate_get_remain_featured_listing_user($userID) >0 ){
               wpestate_update_featured_listing_no($userID);
               update_post_meta($prop_id, 'prop_featured', 1);
               print 'done';
               die();
            }else{
                print 'no places';
                die();
            }
        }

    }
endif; // end   wpestate_ajax_make_prop_featured

add_action( 'wp_ajax_wpestate_ajax_duplicate_listing', 'wpestate_ajax_duplicate_listing' );



/**
 * Make property featured via AJAX
 * 
 * @function wpestate_ajax_duplicate_listing
 * Verifies user can duplicate property and duplicates listing
 */

if( !function_exists('wpestate_ajax_duplicate_listing') ):
    function  wpestate_ajax_duplicate_listing(){
        check_ajax_referer( 'wprentals_property_actions_nonce', 'security' );
        $prop_id=intval($_POST['propid']);
        $current_user = wp_get_current_user();
        $userID =   $current_user->ID;

        if ( !is_user_logged_in() ) {
            exit('ko');
        }
        if($userID === 0 ){
            exit('out pls');
        }



        $post   =   get_post($prop_id);

        if( $post->post_author != $userID){
            exit('get out of my cloud');
        }else{
            
            $duplicatedID = wp_estate_duplicate_listing( $post );

            print 'done';
            die();
        }

    }
endif; // end   wpestate_ajax_make_prop_featured
