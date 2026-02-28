<?php 

/**
 * Membership Functions
 *
 * Core membership functionality including package data retrieval
 * and display, user status checks and updates.
 *
 * @package WPRentals
 * @subpackage Membership
 */



 /**
 * Get all membership packages
 *
 * @function wpestate_get_all_packs 
 * @return string HTML options for package select
 */

if( !function_exists('wpestate_get_all_packs') ):
    function wpestate_get_all_packs(){
        $args = array(
                'post_type'         => 'membership_package',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                                            array(
                                                'key' => 'pack_visible',
                                                'value' => 'yes',
                                                'compare' => '='
                                            )

                 )

         );
        $pack_selection = new WP_Query($args);

        while ($pack_selection->have_posts()): $pack_selection->the_post();
            $return_string.='<option value="'.$post->ID.'">'.get_the_title().'</option>';
        endwhile;
        wp_reset_query();
        return $return_string;
    }
endif; // end   wpestate_get_all_packs





/**
 * Display user package data summary
 *
 * @function wpestate_get_pack_data_for_user_top
 * @param int $userID User ID
 * @param int $user_pack Package ID
 * @param string $user_registered Registration date
 * @param string $user_package_activation Activation date
 */
if( !function_exists('wpestate_get_pack_data_for_user_top') ):
    function wpestate_get_pack_data_for_user_top($userID,$user_pack,$user_registered,$user_package_activation){
            print '<div class="pack_description">
                ';
            $remaining_lists=wpestate_get_remain_listing_user($userID,$user_pack);
            if($remaining_lists==-1){
                $remaining_lists=' &#8734';
            }


            if ($user_pack!=''){
                $title              = get_the_title($user_pack);
                $pack_time          = get_post_meta($user_pack, 'pack_time', true);
                $pack_list          = get_post_meta($user_pack, 'pack_listings', true);
                $pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
                $pack_price         = get_post_meta($user_pack, 'pack_price', true);
                $unlimited_lists    = get_post_meta($user_pack, 'mem_list_unl', true);
                $date               = strtotime ( get_user_meta($userID, 'package_activation',true) );
                $biling_period      = get_post_meta($user_pack, 'biling_period', true);
                $billing_freq_raw   = get_post_meta($user_pack, 'billing_freq', true);
                $billing_freq       = is_numeric($billing_freq_raw) ? floatval($billing_freq_raw) : 0;


                $seconds=0;
                switch ($biling_period){
                   case 'Day':
                       $seconds=60*60*24;
                       break;
                   case 'Week':
                       $seconds=60*60*24*7;
                       break;
                   case 'Month':
                       $seconds=60*60*24*30;
                       break;
                   case 'Year':
                       $seconds=60*60*24*365;
                       break;
               }

               $time_frame      =   $seconds * $billing_freq;
               $expired_date    =   $date+$time_frame;
               $expired_date    =   date('Y-m-d',$expired_date);






                print '<div class="pack-name">'.$title.' <span>'.esc_html__( 'Current Subscription','wprentals-core').'</span></div>';
                $extra_pack_class='';
                if($pack_list>999){
                    $extra_pack_class= ' extrapackclass ';
                }

                print '<div class="pack-info '.$extra_pack_class.'">';


                 if($unlimited_lists==1){
                    print '<div class="normal_list_no">  &#8734 <span>'.esc_html__( 'Listings Included','wprentals-core').'</span></div>';
                    print '<div class="normal_list_no">  &#8734 <span>'.esc_html__( 'Remaining Listings','wprentals-core').'</span></div>';
                }else{
                    print '<div class="normal_list_no">'.$pack_list      .'<span>'.esc_html__( 'Listings Included','wprentals-core').'</span></div>';
                    print '<div class="normal_list_no">'.$remaining_lists.'<span>'.esc_html__( 'Remaining Listings','wprentals-core').'</span></div>';

                }

                print '<div class="normal_list_no">'.$pack_featured.'<span>'.esc_html__( 'Featured included','wprentals-core').'</span></div>';
                print '<div class="normal_list_no extend_normal_list_no">'.wpestate_get_remain_featured_listing_user($userID).'<span>'.esc_html__( 'Featured remaining','wprentals-core').'</span></div>';
                print '<div class="pack-date-wrapper">'.esc_html__( 'Expiration date','wprentals-core').': <span class="pack-date-wrapper-date">'.$expired_date.'</span></div></div>';

            }else{
///////////////////////////
                $free_mem_list      =   esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                $free_feat_list     =   esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
                $free_mem_list_unl  =   intval(wprentals_get_option('wp_estate_free_mem_list_unl', '' ));
                $extra_pack_class='';

                if( intval($free_mem_list)>999 || $free_mem_list_unl==1){

                    $extra_pack_class= ' extrapackclass ';
                }
                print '<div class="pack-name"  >'.esc_html__( 'Free Membership','wprentals-core').'<span>'.esc_html__( 'Current Subscription','wprentals-core').'</span></div>';


                print '<div class="pack-info '.$extra_pack_class.'"><div class="normal_list_no"> ';
                 if($free_mem_list_unl==1){
                    print esc_html__( '-','wprentals-core');
                }else{
                    print $free_mem_list;
                }
                print '<span>'.esc_html__( 'Listings Included','wprentals-core').'</span></div>';
                print '<div class="normal_list_no">'.$remaining_lists.'<span>'.esc_html__( 'Remaining listings','wprentals-core').'</span></div>';
                print '<div class="normal_list_no">'.$free_feat_list.'<span>'.esc_html__( 'Featured listings included','wprentals-core').'</span></div>';
                print '<div class="normal_list_no extend_normal_list_no">'.wpestate_get_remain_featured_listing_user($userID).'<span>'.esc_html__( 'Featured Listings remaining','wprentals-core').'</span></div>';
                print '<div class="pack-date-wrapper">'.esc_html__( 'Expiration date','wprentals-core').' -</div></div>';



            }
            print '</div>';
    }
endif; // end   wpestate_get_pack_data_for_user_top





/**
 * Display detailed package info
 *
 * @function wpestate_get_pack_data_for_user
 * @param int $userID User ID
 * @param int $user_pack Package ID
 * @param string $user_registered Registration date
 * @param string $user_package_activation Activation date
 */

if( !function_exists('wpestate_get_pack_data_for_user') ):
    function wpestate_get_pack_data_for_user($userID,$user_pack,$user_registered,$user_package_activation){
    
                if ($user_pack!=''){
                    $title              = get_the_title($user_pack);
                    $pack_time          = get_post_meta($user_pack, 'pack_time', true);
                    $pack_list          = get_post_meta($user_pack, 'pack_listings', true);
                    $pack_featured      = get_post_meta($user_pack, 'pack_featured_listings', true);
                    $pack_price         = get_post_meta($user_pack, 'pack_price', true);
    
                    $unlimited_lists    = get_post_meta($user_pack, 'mem_list_unl', true);
    
                    print '<strong>'.esc_html__( 'Your Current Package: ','wprentals-core').'</strong></br><strong>'.$title.'</strong></br> ';
                    print '<p class="full_form-nob">';
                    if($unlimited_lists==1){
                        print esc_html__( '  Unlimited listings','wprentals-core');
                    }else{
                        print $pack_list.esc_html__( ' Listings','wprentals-core');
                        print ' - '.wpestate_get_remain_listing_user($userID,$user_pack).esc_html__( ' remaining ','wprentals-core').'</p>';
                    }
    
                    print ' <p class="full_form-nob"> <span id="normal_list_no">'.$pack_featured.esc_html__( ' Featured listings','wprentals-core').'</span>';
                    print ' - <span id="featured_list_no">'.wpestate_get_remain_featured_listing_user($userID).'</span>'.esc_html__( ' remaining','wprentals-core').' </p>';
    
    
                }else{
    
                    $free_mem_list      =   esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                    $free_feat_list     =   esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
                    $free_mem_list_unl  =   wprentals_get_option('wp_estate_free_mem_list_unl', '' );
                    print '<strong>'.esc_html__( 'Your Current Package: ','wprentals-core').'</strong></br><strong>'.esc_html__( 'Free Membership','wprentals-core').'</strong>';
                    print '<p class="full_form-nob">';
                    if($free_mem_list_unl==1){
                         print esc_html__( 'Unlimited listings','wprentals-core');
                    }else{
                         print $free_mem_list.esc_html__( ' Listings','wprentals-core');
                         print ' - <span id="normal_list_no">'.wpestate_get_remain_listing_user($userID,$user_pack).'</span>'.esc_html__( ' remaining','wprentals-core').'</p>';
    
                    }
                    print '<p class="full_form-nob">';
                    print $free_feat_list.esc_html__( ' Featured listings','wprentals-core');
                    print ' - <span id="featured_list_no">'.wpestate_get_remain_featured_listing_user($userID).'</span>'.esc_html__( '  remaining','wprentals-core').' </p>';
                }
    
    }
    endif; // end   wpestate_get_pack_data_for_user
    


    
/**
 * Update legacy user data
 *
 * @function wpestate_update_old_users
 * @param int $userID User ID
 */    
if( !function_exists('wpestate_update_old_users') ):
    function wpestate_update_old_users($userID){
        $paid_submission_status    = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
        if($paid_submission_status == 'membership' ){

            $curent_list   =   get_user_meta( $userID, 'package_listings', true) ;
            $cur_feat_list =   get_user_meta( $userID, 'package_featured_listings', true) ;

                if($curent_list=='' || $cur_feat_list=='' ){
                     $package_listings           = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                     $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
                       if($package_listings==''){
                           $package_listings=0;
                       }
                       if($featured_package_listings==''){
                          $featured_package_listings=0;
                       }

                     update_user_meta( $userID, 'package_listings', $package_listings) ;
                     update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;

                   $time = time();
                   $date = date('Y-m-d H:i:s',$time);
                   update_user_meta( $userID, 'package_activation', $date);
                }

        }// end if memebeship
    }
endif; // end   wpestate_update_old_users



/**
 * Initialize new user profile
 *
 * @function wpestate_update_profile
 * @param int $userID User ID
 */
if( !function_exists('wpestate_update_profile') ):
    function wpestate_update_profile($userID){
        if(1==1){ // if membership is on

            if( wprentals_get_option('wp_estate_free_mem_list_unl', '' ) ==1 ){
                $package_listings =-1;
                $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
            }else{
                $package_listings           = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );

                if($package_listings==''){
                    $package_listings=0;
                }
                if($featured_package_listings==''){
                    $featured_package_listings=0;
                }
            }
            update_user_meta( $userID, 'package_listings', $package_listings) ;
            update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;
            $time = time();
            $date = date('Y-m-d H:i:s',$time);
            update_user_meta( $userID, 'package_activation', $date);
            //package_id no id since the pack is free

        }

    }
endif; // end   wpestate_update_profile

