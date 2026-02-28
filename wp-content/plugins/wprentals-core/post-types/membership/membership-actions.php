<?php 


/**
* Membership Actions
* 
* Handles membership package changes including upgrades, downgrades 
* and package transitions. Manages listing status updates and notifications.
*
* @package WPRentals
* @subpackage Membership
*/





/**
* Downgrade user to specific package
* 
* @function wpestate_downgrade_to_pack
* @param int $user_id User ID
* @param int $pack_id Package ID
*/

if( !function_exists('wpestate_downgrade_to_pack') ):
    function wpestate_downgrade_to_pack( $user_id, $pack_id ){

        $future_listings                  =   get_post_meta($pack_id, 'pack_listings', true);
        $future_featured_listings         =   get_post_meta($pack_id, 'pack_featured_listings', true);
        update_user_meta( $user_id, 'package_listings', $future_listings) ;
        update_user_meta( $user_id, 'package_featured_listings', $future_featured_listings);

        $args = array(
                   'post_type' => 'estate_property',
                   'author'    => $user_id,
                   'post_status'   => 'any'
            );

        $query = new WP_Query( $args );
        global $post;
        while( $query->have_posts()){
                $query->the_post();

                $prop = array(
                        'ID'            => $post->ID,
                        'post_type'     => 'estate_property',
                        'post_status'   => 'expired'
                );

                wp_update_post($prop );
                update_post_meta($post->ID, 'prop_featured', 0);
          }
        wp_reset_query();

        $user = get_user_by('id',$user_id);
        $user_email=$user->user_email;

        $arguments=array();
        wpestate_select_email_type($user_email,'account_downgraded',$arguments);


    }
endif; // end   wpestate_downgrade_to_pack



/**
* Downgrade user to free membership
* 
* @function wpestate_downgrade_to_free
* @param int $user_id User ID 
*/

if( !function_exists('wpestate_downgrade_to_free') ):
    function wpestate_downgrade_to_free($user_id){
        global $post;

        $free_pack_listings        = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
        $free_pack_feat_listings   = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );

        update_user_meta( $user_id, 'package_id', '') ;
        update_user_meta( $user_id, 'package_listings', $free_pack_listings) ;
        update_user_meta( $user_id, 'package_featured_listings', $free_pack_feat_listings);
        update_user_meta(  $user_id  , 'stripe'                ,  '' );
        update_user_meta( $user_id  , 'stripe_subscription_id',  '' );
        $args = array(
                'post_type' => 'estate_property',
                'author'    => $user_id,
                'post_status'   => 'any'
         );

        $query = new WP_Query( $args );
        while( $query->have_posts()){
                $query->the_post();

                $prop = array(
                        'ID'            => $post->ID,
                        'post_type'     => 'estate_property',
                        'post_status'   => 'expired'
                );

                wp_update_post($prop );
          }
        wp_reset_query();

        $user = get_user_by('id',$user_id);
        $user_email=$user->user_email;

        $arguments=array();
        wpestate_select_email_type($user_email,'membership_cancelled',$arguments);

    }
 endif; // end   wpestate_downgrade_to_free




 /**
* Upgrade user membership package
* 
* @function wpestate_upgrade_user_membership
* @param int $user_id User ID
* @param int $pack_id Package ID
* @param string $type Payment type
* @param string $paypal_tax_id PayPal tax ID
*/

if( !function_exists('wpestate_upgrade_user_membership') ):
function wpestate_upgrade_user_membership($user_id,$pack_id,$type,$paypal_tax_id){

    $available_listings                  =   floatval(get_post_meta($pack_id, 'pack_listings', true) );
    $featured_available_listings         =   floatval(get_post_meta($pack_id, 'pack_featured_listings', true) );
    $pack_unlimited_list                 =   floatval(get_post_meta($pack_id, 'mem_list_unl', true) );


    $current_used_listings               =   floatval( get_user_meta($user_id, 'package_listings',true) );
    $curent_used_featured_listings       =   floatval( get_user_meta($user_id, 'package_featured_listings',true) );
    $current_pack                        =   floatval( get_user_meta($user_id, 'package_id',true) );


    $user_curent_listings               =   wpestate_get_user_curent_listings_no_exp ( $user_id );
    $user_curent_future_listings        =   wpestate_get_user_curent_future_listings_no_exp( $user_id );


    if( wpestate_check_downgrade_situation($user_id,$pack_id) ){
        $new_listings           =   $available_listings;
        $new_featured_listings  =   $featured_available_listings;
    }else{
        $new_listings            =  $available_listings - $user_curent_listings ;
        $new_featured_listings   =  $featured_available_listings-  $user_curent_future_listings ;
    }


    // in case of downgrade
    if($new_listings<0){
        $new_listings=0;
    }

    if($new_featured_listings<0){
        $new_featured_listings=0;
    }


    if ($pack_unlimited_list==1){
        $new_listings=-1;
    }


    update_user_meta( $user_id, 'package_listings', $new_listings) ;
    update_user_meta( $user_id, 'package_featured_listings', $new_featured_listings);


    $time = time();
    $date = date('Y-m-d H:i:s',$time);
    update_user_meta( $user_id, 'package_activation', $date);
    update_user_meta( $user_id, 'package_id', $pack_id);


    $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
    $message  = esc_html__( 'Hi there,','wprentals-core') . "\r\n\r\n";
    $message .= sprintf( esc_html__( "Your new membership on  %s is activated! You should go check it out.",'wprentals-core'), get_option('blogname')) . "\r\n\r\n";

    $user = get_user_by('id',$user_id);
    $user_email=$user->user_email;

    $arguments=array();
    wpestate_select_email_type($user_email,'membership_activated',$arguments);


    $billing_for = WP_ESTATE_INVOICE_TYPE_PACKAGE;
    $invoice_id = wpestate_insert_invoice($billing_for,$type,$pack_id,$date,$user_id,0,0,$paypal_tax_id);
    update_post_meta($invoice_id, 'invoice_status', 'confirmed');
}

endif; // end   wpestate_upgrade_user_membership





/**
* Upgrade user on wire transfer payment
* 
* @function wpestate_upgrade_user_membership_on_wiretransfer
* @param int $user_id User ID
* @param int $pack_id Package ID  
* @param string $type Payment type
* @param string $paypal_tax_id PayPal tax ID
*/


if( !function_exists('wpestate_upgrade_user_membership_on_wiretransfer') ):
    function wpestate_upgrade_user_membership_on_wiretransfer($user_id,$pack_id,$type,$paypal_tax_id){

        $available_listings                  = floatval( get_post_meta($pack_id, 'pack_listings', true) );
        $featured_available_listings         = floatval(  get_post_meta($pack_id, 'pack_featured_listings', true));
        $pack_unlimited_list                 =   floatval(  get_post_meta($pack_id, 'mem_list_unl', true) );


        $current_used_listings               =   floatval( get_user_meta($user_id, 'package_listings',true));
        $curent_used_featured_listings       =  floatval(  get_user_meta($user_id, 'package_featured_listings',true));
        $current_pack=get_user_meta($user_id, 'package_id',true);


        $user_curent_listings               =   wpestate_get_user_curent_listings_no_exp ( $user_id );
        $user_curent_future_listings        =   wpestate_get_user_curent_future_listings_no_exp( $user_id );


        if( wpestate_check_downgrade_situation($user_id,$pack_id) ){
            $new_listings           =   $available_listings;
            $new_featured_listings  =   $featured_available_listings;
        }else{
            $new_listings            =  $available_listings - $user_curent_listings ;
            $new_featured_listings   =  $featured_available_listings-  $user_curent_future_listings ;
        }


        // in case of downgrade
        if($new_listings<0){
            $new_listings=0;
        }

        if($new_featured_listings<0){
            $new_featured_listings=0;
        }


        if ($pack_unlimited_list==1){
            $new_listings=-1;
        }


        update_user_meta( $user_id, 'package_listings', $new_listings) ;
        update_user_meta( $user_id, 'package_featured_listings', $new_featured_listings);


        $time = time();
        $date = date('Y-m-d H:i:s',$time);
        update_user_meta( $user_id, 'package_activation', $date);
        update_user_meta( $user_id, 'package_id', $pack_id);


        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
        $message  = esc_html__( 'Hi there,','wprentals-core') . "\r\n\r\n";
        $message .= sprintf( esc_html__( "Your new membership on  %s is activated! You should go check it out.",'wprentals-core'), get_option('blogname')) . "\r\n\r\n";

        $user = get_user_by('id',$user_id);
        $user_email=$user->user_email;

        $arguments=array();
        wpestate_select_email_type($user_email,'membership_activated',$arguments);



    }

endif; // end   wpestate_upgrade_user_membership




/**
* Check if downgrade is needed
*
* @function wpestate_check_downgrade_situation
* @param int $user_id User ID
* @param int $new_pack_id New package ID
* @return bool If downgrade needed
*/

if( !function_exists('wpestate_check_downgrade_situation') ):
    function  wpestate_check_downgrade_situation($user_id,$new_pack_id){

        $future_listings                  =   get_post_meta($new_pack_id, 'pack_listings', true);
        $future_featured_listings         =   get_post_meta($new_pack_id, 'pack_featured_listings', true);
        $unlimited_future                 =   get_post_meta($new_pack_id, 'mem_list_unl', true);
        $curent_list                      =   get_user_meta( $user_id, 'package_listings', true) ;

        if($unlimited_future==1){
            return false;
        }

        if ($curent_list == -1 && $unlimited_future!=1 ){ // if is unlimited and go to non unlimited pack
            return true;
        }

        if ( (wpestate_get_user_curent_listings_published($user_id) > $future_listings ) || ( wpestate_get_user_curent_future_listings($user_id) > $future_featured_listings ) ){
            return true;
        }else{
            return false;
        }
    }
endif; // end   wpestate_check_downgrade_situation





/**
* Update user recurring profile
*
* @function wpestate_update_user_recuring_profile
* @param string $profile_id Profile ID
* @param int $user_id User ID
*/
if( !function_exists('wpestate_update_user_recuring_profile') ):
    function wpestate_update_user_recuring_profile( $profile_id,$user_id ){
          $profile_id=  str_replace('-', 'xxx', $profile_id);
          $profile_id=  str_replace('%2d', 'xxx', $profile_id);

          update_user_meta( $user_id, 'profile_id', $profile_id);
    }
endif; // end   wpestate_update_user_recuring_profile