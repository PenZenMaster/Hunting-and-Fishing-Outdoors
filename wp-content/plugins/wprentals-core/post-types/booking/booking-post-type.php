<?php
/**
 * Booking Post Type Registration
 * src: post-types/booking/booking-post-type.php
 * This file handles the registration of the Booking custom post type 
 *
 * @package WPRentals Core
 * @subpackage Booking
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Booking custom post type
 */
add_action('after_setup_theme', 'wpestate_create_booking_type', 20);
if (!function_exists('wpestate_create_booking_type')):
    function wpestate_create_booking_type() {

         if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'wpestate_booking' ) ) {
            return;
        }

        // Setup post type labels
        $labels = array(
            'name'              => esc_html__('Bookings', 'wprentals-core'),
            'singular_name'     => esc_html__('Booking', 'wprentals-core'),
            'add_new'           => esc_html__('Add New Booking', 'wprentals-core'),
            'add_new_item'      => esc_html__('Add Booking', 'wprentals-core'),
            'edit'              => esc_html__('Edit', 'wprentals-core'),
            'edit_item'         => esc_html__('Edit Booking', 'wprentals-core'),
            'new_item'          => esc_html__('New Booking', 'wprentals-core'),
            'view'              => esc_html__('View', 'wprentals-core'),
            'view_item'         => esc_html__('View Booking', 'wprentals-core'),
            'search_items'      => esc_html__('Search Booking', 'wprentals-core'),
            'not_found'         => esc_html__('No Bookings found', 'wprentals-core'),
            'not_found_in_trash'=> esc_html__('No Bookings found in Trash', 'wprentals-core'),
            'parent'            => esc_html__('Parent Booking', 'wprentals-core')
        );

        // Define capabilities for the post type
        $capabilities = array(
            'edit_post'              => 'edit_wpestate_booking',
            'read_post'              => 'read_wpestate_booking',
            'delete_post'            => 'delete_wpestate_booking',
            'edit_posts'             => 'edit_wpestate_bookings',
            'edit_others_posts'      => 'edit_others_wpestate_bookings',
            'publish_posts'          => 'publish_wpestate_bookings',
            'read_private_posts'     => 'read_private_wpestate_bookings',
            'create_posts'           => 'create_wpestate_bookings',
            'delete_posts'           => 'delete_wpestate_bookings',
            'delete_private_posts'   => 'delete_private_wpestate_bookings',
            'delete_published_posts' => 'delete_published_wpestate_bookings',
            'delete_others_posts'    => 'delete_others_wpestate_bookings',
            'edit_private_posts'     => 'edit_private_wpestate_bookings',
            'edit_published_posts'   => 'edit_published_wpestate_bookings'
        );

        // Register post type
        register_post_type('wpestate_booking', array(
            'labels'              => $labels,
            'public'              => true,
            'has_archive'         => true,
            'rewrite'            => array('slug' => 'bookings'),
            'supports'           => array('title',''),
            'can_export'         => true,
            'register_meta_box_cb'=> 'wpestate_add_bookings_metaboxes',
            'menu_icon'          => WPESTATE_PLUGIN_DIR_URL . '/img/book.png',
            'exclude_from_search'=> true,
            'capability_type'    => 'wpestate_booking',
            'map_meta_cap'       => true,
            'capabilities'       => $capabilities
        ));

        // Ensure administrator has these capabilities
        $admin = get_role('administrator');
        if ($admin) {
            foreach ($capabilities as $cap) {
                $admin->add_cap($cap);
            }
        }
    }
endif;

/**
 * Add custom columns to booking list
 */
add_filter('manage_edit-wpestate_booking_columns', 'wpestate_booking_columns');
if (!function_exists('wpestate_booking_columns')):
    function wpestate_booking_columns($columns) {
        $slice = array_slice($columns, 2, 2);
        unset($columns['comments']);
        unset($slice['comments']);
        $splice = array_splice($columns, 2);

        $new_columns = array(
            'booking_estate_status'   => esc_html__('Status', 'wprentals-core'),
            'booking_estate_period'   => esc_html__('Period', 'wprentals-core'),
            'booking_estate_listing'  => esc_html__('Listing', 'wprentals-core'),
            'booking_estate_owner'    => esc_html__('Owner', 'wprentals-core'),
            'booking_estate_renter'   => esc_html__('Renter', 'wprentals-core'),
            'booking_estate_value'    => esc_html__('Value', 'wprentals-core'),
            'booking_estate_value_to_be_paid' => esc_html__('Initial Deposit', 'wprentals-core'),
        );

        return array_merge($columns, $new_columns, array_reverse($slice));
    }
endif;

/**
 * Populate custom columns in booking list
 */
add_action('manage_posts_custom_column', 'wpestate_populate_booking_columns');
if (!function_exists('wpestate_populate_booking_columns')):
    function wpestate_populate_booking_columns( $column ) {
        $the_id=get_the_ID();
        $invoice_no         =   get_post_meta($the_id, 'booking_invoice_no', true);
        if(  'booking_estate_status' == $column){
            $booking_status         =  esc_html(get_post_meta($the_id, 'booking_status', true));
            $booking_status_full    = esc_html(get_post_meta($the_id, 'booking_status_full', true));
            
            if($booking_status == 'canceled' && $booking_status_full== 'canceled'){
                esc_html_e('canceled','wprentals-core');
            }else if($booking_status == 'confirmed' && $booking_status_full== 'confirmed'){
                echo    esc_html__('confirmed','wprentals-core').' | ' .esc_html__('fully paid','wprentals-core');
            }else if($booking_status == 'confirmed' && $booking_status_full== 'waiting'){
                echo    esc_html__('deposit paid','wprentals-core').' | ' .esc_html__('waiting for full payment','wprentals-core');
            }else if($booking_status == 'refunded' ){
                esc_html_e('refunded','wprentals-core');
            }else if($booking_status == 'pending' ){
                esc_html_e('pending','wprentals-core');
            }else if($booking_status == 'waiting' ){
                esc_html_e('waiting','wprentals-core');
            }
       
        }
          
        if(  'booking_estate_period' == $column){
            $from_date  = wpestate_convert_dateformat_reverse ( esc_html(get_post_meta($the_id, 'booking_from_date', true)) );
            $to_date    = wpestate_convert_dateformat_reverse ( esc_html(get_post_meta($the_id, 'booking_to_date', true)) );
            
            echo esc_html__( 'from','wprentals-core').' '.$from_date.' '.esc_html__( 'to','wprentals-core').' '.$to_date;
        }
        
        if(  'booking_estate_listing' == $column){
            $curent_listng_id= get_post_meta($the_id, 'booking_id',true);
            echo get_the_title($curent_listng_id);
        }
        
        if(  'booking_estate_owner' == $column){
            $owner_id = get_post_meta($the_id, 'owner_id', true);
            $user = get_user_by( 'id', $owner_id );
            if ( $user ) {
                print $user->user_login;
            } else {
                esc_html_e('Deleted User', 'wprentals-core');
            }
        }
        
        if(  'booking_estate_renter' == $column){
            print $author             =   get_the_author();
        }
        
        if(  'booking_estate_value' == $column){
            $total_price        =   get_post_meta($invoice_no, 'item_price', true);
            print $total_price;
        }
        if(  'booking_estate_value_to_be_paid' == $column){
            $to_be_paid         =   floatval ( get_post_meta($invoice_no, 'depozit_to_be_paid', true));
            print $to_be_paid;
        }
        
       
        
    }
endif;