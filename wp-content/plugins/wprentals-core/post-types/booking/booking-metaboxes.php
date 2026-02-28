<?php
/**
 * Booking Metaboxes
 * src: post-types/booking/booking-metaboxes.php
 * This file handles the metaboxes for the Booking post type
 *
 * @package WPRentals Core
 * @subpackage Booking
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add booking metaboxes
 */
add_action('add_meta_boxes', 'wpestate_add_bookings_metaboxes');
if (!function_exists('wpestate_add_bookings_metaboxes')):
    function wpestate_add_bookings_metaboxes() {    
        add_meta_box(
            'estate_booking-sectionid',
            esc_html__('Booking Details', 'wprentals-core'),
            'wpestate_booking_meta_function',
            'wpestate_booking',
            'normal',
            'default'
        );
    }
endif;

/**
 * Booking metabox content
 */
if (!function_exists('wpestate_booking_meta_function')):
    function wpestate_booking_meta_function($post) {
        wp_nonce_field('booking_meta_nonce', 'booking_meta_nonce');

        // Get booking status options
        $status_values = array('confirmed', 'pending');
        $status_type = get_post_meta($post->ID, 'booking_status', true);
        $option_status = '';
        
        foreach ($status_values as $value) {
            $option_status .= sprintf(
                '<option value="%s" %s>%s</option>',
                esc_attr($value),
                selected($value, $status_type, false),
                esc_html($value)
            );
        }

        $property_id = esc_html(get_post_meta($post->ID, 'booking_id', true));
        $property_id = apply_filters('wpml_object_id', $property_id, get_post_type($property_id), true);

        // Display fields
        $fields = array(
            array(
                'label' => esc_html__('Booking Status:', 'wprentals-core'),
                'value' => get_post_meta($post->ID, 'booking_status', true),
                'type'  => 'text'
            ),
            array(
                'label' => esc_html__('Booking Invoice:', 'wprentals-core'),
                'value' => get_post_meta($post->ID, 'booking_invoice_no', true),
                'type'  => 'text'
            ),
            array(
                'label' => esc_html__('Check-In:', 'wprentals-core'),
                'id'    => 'booking_from_date',
                'value' => get_post_meta($post->ID, 'booking_from_date', true),
                'type'  => 'date'
            ),
            array(
                'label' => esc_html__('Check-Out:', 'wprentals-core'),
                'id'    => 'booking_to_date',
                'value' => get_post_meta($post->ID, 'booking_to_date', true),
                'type'  => 'date'
            ),
            array(
                'label' => esc_html__('Property ID:', 'wprentals-core'),
                'id'    => 'booking_id',
                'value' => $property_id,
                'type'  => 'text'
            ),
            array(
                'label' => esc_html__('Guests No:', 'wprentals-core'),
                'id'    => 'booking_guests',
                'value' => get_post_meta($post->ID, 'booking_guests', true),
                'type'  => 'text'
            ),
            array(
                'label' => esc_html__('Property Name:', 'wprentals-core'),
                'value' => get_the_title($property_id),
                'type'  => 'text'
            )
        );

        $active_tab = 'booking_details';
        print '<div class="property_options_wrapper meta-options">'
        .'<div class="property_options_wrapper_list">';
            // print '<div class="property_tab_item'.( $active_tab === 'agent_details' ? ' active_tab' : '' ).'" data-content="agent_details">'.esc_html__('Agent Details','wprentals-core').'</div>';
            print '<div class="property_tab_item'.( $active_tab === 'booking_details' ? ' active_tab' : '' ).'" data-content="booking_details">'.esc_html__('Booking Details','wprentals-core').'</div>';
            // print '<div class="property_tab_item'.( $active_tab === 'agency_profile' ? ' active_tab' : '' ).'" data-content="agency_profile">'.esc_html__('Agency Profile','wprentals-core').'</div>';
            // print '<div class="property_tab_item'.( $active_tab === 'agent_customs' ? ' active_tab' : '' ).'" data-content="agent_customs">'.esc_html__('Agent Custom Fields','wprentals-core').'</div>';
        print '</div><div class="property_options_content_wrapper">';

        print '<div class="property_tab_item_content'.( $active_tab === 'booking_details' ? ' active_tab' : '' ).'" id="booking_details">';

        foreach ($fields as $field) {
            wpestate_display_booking_field($field);
        }

        // Status dropdown
        echo '<div class="property_prop_half">';
        echo '<label for="booking_status">' . esc_html__('Booking Status:', 'wprentals-core') . '</label><br />';
        echo '<select id="booking_status" name="booking_status">' . $option_status . '</select>';
        echo '</div>';

        print '</div></div>'; // Close booking_details tab content

        // Add datepicker scripts
        wpestate_booking_add_datepicker();
    }
endif;

/**
 * Display booking field
 */
if (!function_exists('wpestate_display_booking_field')):
    function wpestate_display_booking_field($field) {
        echo '<div class="property_prop_half">';
        echo '<label>' . $field['label'] . '</label><br />';
        
        if ($field['type'] === 'text' && isset($field['id'])) {
            echo '<input type="text" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" 
                    value="' . esc_attr($field['value']) . '" size="58">';
        } elseif ($field['type'] === 'date' && isset($field['id'])) {
            echo '<input type="text" id="' . esc_attr($field['id']) . '" name="' . esc_attr($field['id']) . '" 
                    value="' . esc_attr($field['value']) . '" size="58" class="datepicker">';
        } else {
            echo '<input type="text" 
                    value="' . esc_attr($field['value']) . '" size="58" disabled>';
        }
        
        echo '</div>';
    }
endif;

/**
 * Save booking meta
 */
add_action('save_post', 'wpestate_save_booking_meta', 99);
if (!function_exists('wpestate_save_booking_meta')):
    function wpestate_save_booking_meta($post_id) {
        if (!isset($_POST['booking_meta_nonce']) || 
            !wp_verify_nonce($_POST['booking_meta_nonce'], 'booking_meta_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save booking data
        $meta_fields = array(
            'booking_id',
            'booking_from_date',
            'booking_to_date',
            'booking_guests',
            'booking_status'
        );

        foreach ($meta_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Update booking dates for the property
        $listing_id = get_post_meta($post_id, 'booking_id', true);
        if ($listing_id) {
            $reservation_array = wpestate_get_booking_dates($listing_id);
            update_post_meta($listing_id, 'booking_dates', $reservation_array);
        }
    }
endif;

/**
 * Handle booking deletion
 */
add_action('wp_trash_post', 'wpestate_delete_booking_from_admin', 10);
if (!function_exists('wpestate_delete_booking_from_admin')):
    function wpestate_delete_booking_from_admin($postid) {
        if (get_post_type($postid) !== 'wpestate_booking') {
            return;
        }

        if (!current_user_can('administrator')) {
            return;
        }

        $listing_id = get_post_meta($postid, 'booking_id', true);
        $invoice_id = get_post_meta($postid, 'booking_invoice_no', true);

        if (!$listing_id) {
            return;
        }

        // Remove booking from property's booking dates
        $reservation_array = wpestate_get_booking_dates($listing_id);
        foreach ($reservation_array as $key => $value) {
            if ($value == $postid) {
                unset($reservation_array[$key]);
            }
        }
        update_post_meta($listing_id, 'booking_dates', $reservation_array);

        // Delete associated invoice
        if ($invoice_id) {
            wp_delete_post($invoice_id);
        }

         
    }
        


endif;




/**
 * Get booking dates for a listing
 *
 * @param int $listing_id The listing ID
 * @param string $on_blank Whether to return blank array
 * @return array Array of booking dates
 */
if (!function_exists('wpestate_get_booking_dates')):
    function wpestate_get_booking_dates($listing_id, $on_blank = '') {

        if(intval($listing_id)==0){
            return;
        }

        
        $args = array(
            'post_type'        => 'wpestate_booking',
            'post_status'      => 'any',
            'posts_per_page'   => -1,
            'meta_query'       => array(
                array(
                    'key'       => 'booking_id',
                    'value'     => $listing_id,
                    'type'      => 'NUMERIC',
                    'compare'   => '='
                ),
                array(
                    'key'       => 'booking_status',
                    'value'     => 'confirmed',
                    'compare'   => '='
                )
            )
        );

        $reservation_array = get_post_meta($listing_id, 'booking_dates', true);
        $wprentals_is_per_hour = wprentals_return_booking_type($listing_id);

        if (!is_array($reservation_array) || $reservation_array == '') {
            $reservation_array = array();
        }

        if ($on_blank == 'on_blank') {
            $reservation_array = array();
        }

        $booking_selection = new WP_Query($args);
        $now = time();
        $daysago = ($wprentals_is_per_hour == 2) ? $now - 24*60*60 : $now - 2*24*60*60;

        if ($booking_selection->have_posts()) {
            while ($booking_selection->have_posts()): $booking_selection->the_post();
                $pid = get_the_ID();
                $fromd = esc_html(get_post_meta($pid, 'booking_from_date', true));
                $tod = esc_html(get_post_meta($pid, 'booking_to_date', true));
                $unix_time_start = strtotime($fromd);
                $unix_time_end = strtotime($tod);

                if ($unix_time_end  > $daysago) {
                    if ($wprentals_is_per_hour == 2) {
                        $reservation_array[$unix_time_start] = $unix_time_end;
                    } else {
                        $from_date = new DateTime($fromd);
                        $to_date = new DateTime($tod);
                        $from_date_unix = $from_date->getTimestamp();
                        $to_date_unix = $to_date->getTimestamp();

                        $reservation_array[$from_date_unix] = $pid;

                        while ($from_date_unix < $to_date_unix) {
                            $reservation_array[$from_date_unix] = $pid;
                            $from_date->modify('tomorrow');
                            $from_date_unix = $from_date->getTimestamp();
                        }
                    }
                }
            endwhile;
            wp_reset_query();
        }

        return $reservation_array;
    }
endif;

/**
 * Add datepicker scripts for booking form
 */
if (!function_exists('wpestate_booking_add_datepicker')):
    function wpestate_booking_add_datepicker() {
        print '<script type="text/javascript">
                    //<![CDATA[
                    jQuery(document).ready(function(){
                        '.wpestate_date_picker_translation("booking_from_date").'
                    });
                    //]]>
                    </script>';
    print '<script type="text/javascript">
                  //<![CDATA[
                  jQuery(document).ready(function(){
                        '.wpestate_date_picker_translation("booking_to_date").'
                  });
                  //]]>
                  </script>';
    }
endif;

/**
 * Display booking management interface
 */
if (!function_exists('wpestate_payments_management')):
    function wpestate_payments_management() {
        if (!is_user_logged_in()) {
            return;
        }

        $current_user = wp_get_current_user();
        $userID = $current_user->ID;

        if ($userID === 0) {
            return;
        }

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type'         => 'wpestate_booking',
            'post_status'       => 'publish',
            'paged'             => $paged,
            'posts_per_page'    => 100,
            'order'             => 'DESC',
        );

        $book_selection = new WP_Query($args);
        wpestate_display_payments_header();
        
        while ($book_selection->have_posts()): $book_selection->the_post();
            get_template_part('templates/payment_management_booking');
        endwhile;

        wp_reset_postdata();
    }
endif;

/**
 * Display payments management header
 */
if (!function_exists('wpestate_display_payments_header')):
    function wpestate_display_payments_header() {
        $headers = array(
            'booking_no'        => esc_html__('Booking No', 'wprentals-core'),
            'status'            => esc_html__('Status', 'wprentals-core'),
            'property'          => esc_html__('Property', 'wprentals-core'),
            'period'            => esc_html__('Period', 'wprentals-core'),
            'guests'            => esc_html__('Guests', 'wprentals-core'),
            'invoice_no'        => esc_html__('Invoice No', 'wprentals-core'),
            'total_price'       => esc_html__('Total Price', 'wprentals-core'),
            'to_be_paid'        => esc_html__('To Be Paid', 'wprentals-core'),
            'security_deposit'  => esc_html__('Security Deposit', 'wprentals-core'),
            'balance_invoice'   => esc_html__('Balance Invoice ID', 'wprentals-core')
        );

        echo '<div class="estate_option_row"><div class="row payments_management_wrapper">';
        
        foreach ($headers as $key => $label) {
            echo '<div class="col-md-1 payment_management_head">' . $label . '</div>';
        }
        
        echo '</div></div>';
    }
endif;