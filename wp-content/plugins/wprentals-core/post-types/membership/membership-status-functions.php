<?php 


/**
 * Membership Status Management
 *
 * Handles checking and updating membership statuses,
 * including expiration checks and free listing management.
 *
 * @package WPRentals
 * @subpackage Membership
 */


/**
 * Check membership status for all users
 *
 * @function wpestate_check_user_membership_status_function
 * Processes membership and listing expirations
 */

 function wpestate_check_user_membership_status_function() {

    $args = array(
        'role__in' => array(
            'subscriber', 
            WPRENTALS_ROLE_RENTER, 
            WPRENTALS_ROLE_OWNER
        ),
       
    );
    
    $users = get_users($args);
    
    foreach ($users as $user) {
        $user_id = $user->ID;
        $pack_id = get_user_meta($user_id, 'package_id', true);
        
        // Debug print should be removed
        // print ' userid= '.$user_id. '  ---- pack '.$pack_id.'</br>';
 
        // Skip users without package
        if (empty($pack_id)) {
            continue;
        }

        $activation_date = strtotime(get_user_meta($user_id, 'package_activation', true));
        if (!$activation_date) {
            continue;
        }

        $billing_period = get_post_meta($pack_id, 'biling_period', true);
        $billing_freq   = absint(get_post_meta($pack_id, 'billing_freq', true));
        
        $seconds=0;
        switch ($billing_period){
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
               

        // Calculate period in seconds
     
    
        $time_frame = $seconds * intval($billing_freq);
        $grace_period = 4 * HOUR_IN_SECONDS; // 4 hours grace period
        $expiration_date = $activation_date + $time_frame + $grace_period;

        if (time() > $expiration_date) {
            //print ' WPRentals: Package expired for user '.$user_id.' - Pack ID: '.$pack_id;
            //print ' Activation: '.date("Y-m-d H:i:s",$activation_date).', Expiration:'.   date("Y-m-d H:i:s", $expiration_date).'</br>';
            wpestate_downgrade_to_free($user_id);
        }
    }

    wpestate_check_free_listing_expiration();
}



/**
 * Helper function to convert billing period to seconds
 * 
 * @param string $period Billing period (Day, Week, Month, Year)
 * @return int Number of seconds in period, 0 if invalid
 */
function wpestate_get_billing_period_seconds($period) {
    $periods = array(
        'Day' => DAY_IN_SECONDS,
        'Week' => WEEK_IN_SECONDS,
        'Month' => 30 * DAY_IN_SECONDS,
        'Year' => YEAR_IN_SECONDS
    );

    return isset($periods[$period]) ? $periods[$period] : 0;
}



/**
 * Check free listing expiration
 *
 * @function wpestate_check_free_listing_expiration
 */

if( !function_exists('wpestate_check_free_listing_expiration') ):
    function wpestate_check_free_listing_expiration(){

        $free_feat_list_expiration= intval ( wprentals_get_option('wp_estate_free_feat_list_expiration','') );

        if($free_feat_list_expiration!=0 && $free_feat_list_expiration!=''){
           
            $args = array(
                'role__in' => array(
                    'subscriber', 
                    WPRENTALS_ROLE_RENTER, 
                    WPRENTALS_ROLE_OWNER
                ),

            );

            $blogusers = get_users($args);
            $users_with_free='';
            $author_array=array();
            $author_array[]=0;
            foreach ($blogusers as $user) {
                $user_id=$user->ID;
                $pack_id= get_user_meta ( $user_id, 'package_id', true);

                if( $pack_id =='' ){ // if the pack is ! free
                    //$users_with_free .= $user_id.',';
                    $author_array[]=$user_id;
                }
            }


            $args = array(
                'post_type'        =>  'estate_property',
                'author__in'           =>  $author_array,
                'post_status'      =>  'publish'
            );
            $prop_selection = new WP_Query($args);

            while ($prop_selection->have_posts()): $prop_selection->the_post();

                $the_id=get_the_ID();
                $pfx_date = strtotime ( get_the_date("Y-m-d",  $the_id ) );
                $expiration_date=$pfx_date+$free_feat_list_expiration*24*60*60;
                $today=time();

                if ($expiration_date < $today){
                    wpestate_listing_set_to_expire($the_id);
                }

            endwhile;
        }
    }
endif;



/**
 * Set listing to expired status
 *
 * @function wpestate_listing_set_to_expire
 * @param int $post_id Property ID
 */
if( !function_exists('wpestate_listing_set_to_expire') ):
    function wpestate_listing_set_to_expire($post_id){
        $prop = array(
                'ID'            => $post_id,
                'post_type'     => 'estate_property',
                'post_status'   => 'expired'
        );

        wp_update_post($prop );

        $user_id    =   wpsestate_get_author( $post_id );
        $user       =   get_user_by('id',$user_id);
        $user_email =   $user->user_email;

        $arguments=array(
            'expired_listing_url'  => esc_url ( get_permalink($post_id)),
            'expired_listing_name' => get_the_title($post_id)
        );
        wpestate_select_email_type($user_email,'free_listing_expired',$arguments);
    }
endif;
