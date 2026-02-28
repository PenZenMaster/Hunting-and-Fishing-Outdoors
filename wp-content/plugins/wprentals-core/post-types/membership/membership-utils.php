<?php 

/**
 * Membership Utils
 * 
 * Utility functions for membership management including:
 * - Billing period formatting
 * - Listing counts and limits
 * - Package information display
 * - User status checks
 *
 * @package WPRentals
 * @subpackage Membership
 */



/**
 * Format billing period text
 * 
 * @function wpestate_show_bill_period
 * @param string $biling_period Period type
 * @return string Localized period text
 */


if( !function_exists('wpestate_show_bill_period') ):
    function wpestate_show_bill_period($biling_period){

            if($biling_period=='Day' || $biling_period=='Days'){
                return  esc_html__( 'days','wprentals-core');
            }
            else if($biling_period=='Week' || $biling_period=='Weeks'){
               return  esc_html__( 'weeks','wprentals-core');
            }
            else if($biling_period=='Month' || $biling_period=='Months'){
                return  esc_html__( 'months','wprentals-core');
            }
            else if($biling_period=='Year'){
                return  esc_html__( 'year','wprentals-core');
            }

    }
endif;




/**
 * Calculate remaining subscription days for user
 * 
 * @function wpestate_get_remain_days_user
 * @param int $userID User ID
 * @param int $user_pack Package ID
 * @param string $user_registered Registration date
 * @param string $user_package_activation Package activation date
 * @return int Number of days remaining on subscription
 */
if( !function_exists('wpestate_get_remain_days_user') ):
    function wpestate_get_remain_days_user($userID,$user_pack,$user_registered,$user_package_activation){
    
        if ($user_pack!=''){
            $pack_time  = get_post_meta($user_pack, 'pack_time', true);
            $now        = time();
    
            $user_date  = strtotime($user_package_activation);
            $datediff   = $now - $user_date;
            if( floor($datediff/(60*60*24)) > $pack_time){
                return 0;
            }else{
                return floor($pack_time-$datediff/(60*60*24));
            }
    
    
        }else{
            $free_mem_days      = esc_html( wprentals_get_option('wp_estate_free_mem_days','') );
            $free_mem_days_unl  = wprentals_get_option('wp_estate_free_mem_days_unl', '');
            if($free_mem_days_unl==1){
                return;
            }else{
                 $now = time();
                 $user_date = strtotime($user_registered);
                 $datediff = $now - $user_date;
                 if(  floor($datediff/(60*60*24)) >$free_mem_days){
                     return 0;
                 }else{
                    return floor($free_mem_days-$datediff/(60*60*24));
                 }
            }
        }
    }
    endif; // end   wpestate_get_remain_days_user
    
    
/**
 * Get remaining listings allowed
 * 
 * @function wpestate_get_remain_listing_user
 * @param int $userID User ID
 * @param int $user_pack Package ID  
 * @return int Remaining listings count
 */

    
if( !function_exists('wpestate_get_remain_listing_user') ):
    function wpestate_get_remain_listing_user($userID,$user_pack){
        if ( $user_pack !='' ){
          $current_listings   = wpestate_get_current_user_listings($userID);
          $pack_listings      = get_post_meta($user_pack, 'pack_listings', true);

           return $current_listings;
        }else{
          $free_mem_list      = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
          $free_mem_list_unl  = wprentals_get_option('wp_estate_free_mem_list_unl', '' );
          if($free_mem_list_unl==1){
                return -1;
          }else{
              $current_listings=wpestate_get_current_user_listings($userID);
              return $current_listings;
          }
        }
    }
endif; // end   wpestate_get_remain_listing_user



/**
 * Get remaining featured listings
 * 
 * @function wpestate_get_remain_featured_listing_user
 * @param int $userID User ID
 * @return int Featured listings remaining
 */
function wpestate_get_remain_featured_listing_user($userID){
    $count  =   get_the_author_meta( 'package_featured_listings' , $userID );
    return $count;
}

/**
 * Get current user listing count
 * 
 * @function wpestate_get_current_user_listings
 * @param int $userID User ID
 * @return int Current listing count
 */

if( !function_exists('wpestate_get_current_user_listings') ):
    function wpestate_get_current_user_listings($userID){
        $count  =   get_the_author_meta( 'package_listings' , $userID );
        return $count;
    }
endif;



/**
 * Update listing count after add/remove
 * 
 * @function wpestate_update_listing_no
 * @param int $userID User ID
 */
if( !function_exists('wpestate_update_listing_no') ):
    function wpestate_update_listing_no($userID){
        $current  =   get_the_author_meta( 'package_listings' , $userID );
        if($current==''){
            //do nothing
        }else if($current==-1){ // if unlimited
            //do noting
        }else if($current-1>=0){
            update_user_meta( $userID, 'package_listings', $current-1) ;
        }else if( $current==0 ){
             update_user_meta( $userID, 'package_listings', 0) ;
        }
    }
endif; // end   wpestate_update_listing_no





/**
 * Update featured listing count
 * 
 * @function wpestate_update_featured_listing_no
 * @param int $userID User ID
 */

if( !function_exists('wpestate_update_featured_listing_no') ):
    function wpestate_update_featured_listing_no($userID){
        $current  =   get_the_author_meta( 'package_featured_listings' , $userID );

        if($current-1>=0){
            update_user_meta( $userID, 'package_featured_listings', $current-1) ;
        }else{
              update_user_meta( $userID, 'package_featured_listings', 0) ;
        }
    }
endif; // end   wpestate_update_featured_listing_no





/**
 * Display package selection dropdown
 * 
 * @function wpestate_display_packages
 * @return string HTML for package dropdown
 */

if( !function_exists('wpestate_display_packages') ):
    function wpestate_display_packages(){
        global $post;
        $args = array(
                        'post_type'     => 'membership_package',
                        'posts_per_page'=> -1,
                        'meta_query'    => array(
                                                array(
                                                    'key' => 'pack_visible',
                                                    'value' => 'yes',
                                                    'compare' => '=',
                                                )
                                            )
        );
        $pack_selection = new WP_Query($args);

        $return='<select name="pack_select" id="pack_select" class="select-submit2"><option value="">'.esc_html__( 'Select package','wprentals-core').'</option>';
        while($pack_selection->have_posts() ){

            $pack_selection->the_post();
            $title=get_the_title();
            $return.='<option value="'.$post->ID.'"  data-price="'.get_post_meta(get_the_id(),'pack_price',true).'" data-pick="'.sanitize_title($title).'" >'.$title.'</option>';
        }
        $return.='</select>';

        print $return;

    }
endif; // end   wpestate_display_packages




/**
 * Get user's current active listings
 * 
 * @function wpestate_get_user_curent_listings
 * @param int $userid User ID
 * @return int Count of active listings
 */
if( !function_exists('wpestate_get_user_curent_listings') ):
    function wpestate_get_user_curent_listings($userid) {
      $args = array(
            'post_type'     =>  'estate_property',
            'post_status'   =>  'any',
            'author'        =>  $userid,

        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();
    }
endif; // end   get_user_curent_listings


/**
 * Get published listings count
 * 
 * @function wpestate_get_user_curent_listings_published
 * @param int $userid User ID
 * @return int Published listings count
 */
if( !function_exists('wpestate_get_user_curent_listings_published') ):
function wpestate_get_user_curent_listings_published($userid) {
    $args = array(
        'post_type'     =>  'estate_property',
        'post_status'     => 'publish',
        'author'        =>  $userid,

    );
    $posts = new WP_Query( $args );
    return $posts->found_posts;
    wp_reset_query();
}
endif; // end   get_user_curent_listings
    



/**
 * Get non-expired listings count
 * 
 * @function wpestate_get_user_curent_listings_no_exp
 * @param int $userid User ID
 * @return int Non-expired listings count
 */


if( !function_exists('wpestate_get_user_curent_listings_no_exp') ):
    function wpestate_get_user_curent_listings_no_exp($userid) {
        $args = array(
            'post_type'     => 'estate_property',
            'post_status' => array( 'pending', 'publish' ),
            'author'        =>$userid,

        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();

    }
endif; // end   wpestate_get_user_curent_listings_no_exp




/**
 * Get future featured listings count
 * 
 * @function wpestate_get_user_curent_future_listings_no_exp
 * @param int $user_id User ID
 * @return int Future featured count
 */


if( !function_exists('wpestate_get_user_curent_future_listings_no_exp') ):
    function wpestate_get_user_curent_future_listings_no_exp($user_id){

        $args = array(
            'post_type'     =>  'estate_property',
            'post_status'   =>  array( 'pending', 'publish' ),
            'author'        =>  $user_id,
            'meta_query'    =>  array(
                                    array(
                                        'key'   => 'prop_featured',
                                        'value' => 1,
                                        'meta_compare '=>'='
                                    )
                                )
        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();

    }
endif; // end   wpestate_get_user_curent_future_listings_no_exp
    
    


/**
 * Get all featured listings count
 * 
 * @function wpestate_get_user_curent_future_listings
 * @param int $user_id User ID
 * @return int All featured listings count
 */

if( !function_exists('wpestate_get_user_curent_future_listings') ):
    function wpestate_get_user_curent_future_listings($user_id){

        $args = array(
            'post_type'     =>  'estate_property',
            'post_status'   =>  'any',
            'author'        =>  $user_id,
            'meta_query'    =>  array(
                                    array(
                                        'key'   => 'prop_featured',
                                        'value' => 1,
                                        'meta_compare '=>'='
                                    )
                            )
        );
        $posts = new WP_Query( $args );
        return $posts->found_posts;
        wp_reset_query();

    }
endif; // end   wpestate_get_user_curent_future_listings