<?php 
/**
 * Invoice Metaboxes
 * src: post-types/invoice/invoice-metaboxes.php
 * This file handles the metaboxes for the Invoice post type
 *
 * @package WPRentals Core
 * @subpackage Invoice
 * @since 4.0.0
 */

 if (!defined('ABSPATH')) {
    exit;
}




/**
 * Add Invoice metaboxes
 * Creates the metabox for invoice details in admin area
 */

if (!function_exists('wpestate_add_pack_invoices')):

    function wpestate_add_pack_invoices() {
        add_meta_box('estate_invoice-sectionid', esc_html__('Invoice Details', 'wprentals-core'), 'wpestate_invoice_details', 'wpestate_invoice', 'normal', 'default');
    }

endif; // end   wpestate_add_pack_invoices







/**
 * Invoice details metabox content
 * Displays all invoice information: property, owner, payment status, booking details
 *
 * @param object $post WP Post object
 */
if (!function_exists('wpestate_invoice_details')):

    function wpestate_invoice_details($post) {
        global $post;
        wp_nonce_field(plugin_basename(__FILE__), 'estate_invoice_noncename');

        $invoice_type_value = get_post_meta($post->ID, 'invoice_type', true);
        $invoice_type_key   = wpestate_get_invoice_type_key($invoice_type_value);
        $invoice_saved      = wpestate_get_invoice_type_label($invoice_type_value);

        $purchase_type = 0;
        if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_LISTING) {
            $purchase_type = 1;
        } else if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED) {
            $purchase_type = 2;
        } else if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_PUBLISH_WITH_FEATURED) {
            $purchase_type = 3;
        }


        $invoice_period = array(esc_html__('One Time', 'wprentals-core'), esc_html__('Recurring', 'wprentals-core'));
        $invoice_period_saved = esc_html(get_post_meta($post->ID, 'biling_type', true));

        $txn_id = esc_html(get_post_meta($post->ID, 'txn_id', true));

        $invoice_id = $post->ID;


        $bookid = esc_html(get_post_meta($invoice_id, 'item_id', true));
        $booking_prop = esc_html(get_post_meta($bookid, 'booking_id', true)); // property_id
        $owner_id = intval( wpsestate_get_author($booking_prop));
        $agent_id = intval( get_user_meta($owner_id, 'user_agent_id', true));

        $meta = get_post_meta($invoice_id);

        if (intval($booking_prop) == 0) {
            $booking_prop = get_post_meta($invoice_id, 'for_property', true);
        }

        $available_tabs = array( 'invoice_details' );
        $active_tab     = 'invoice_details';
        if ( isset( $_GET['invoice_tab'] ) && in_array( $_GET['invoice_tab'], $available_tabs, true ) ) {
            $active_tab = sanitize_key( $_GET['invoice_tab'] );
        }

        print '<div class="property_options_wrapper meta-options">'
            .'<div class="property_options_wrapper_list">';
                print '<div class="property_tab_item'.( $active_tab === 'invoice_details' ? ' active_tab' : '' ).'" data-content="invoice_details">'.esc_html__('Invoice Details','wprentals-core').'</div>';
        print '</div><div class="property_options_content_wrapper">';

        print '<div class="property_tab_item_content'.( $active_tab === 'invoice_details' ? ' active_tab' : '' ).'" id="invoice_details">';



        print '<strong>' . esc_html__('Property', 'wprentals-core') . ':</strong> <a href="' . esc_html(get_permalink($booking_prop)) . '" target="_blank">' . get_the_title($booking_prop) . '</a>';

        if ($agent_id == 1) {
            print '</br><strong>' . esc_html__('Owner ', 'wprentals-core') . ':</strong> Admin';
        }else if ($agent_id == 0) {
            $user_for_property = get_user_by('id',$owner_id);
            print '</br><strong>' . esc_html__('Owner ', 'wprentals-core') . ':</strong> '.$user_for_property->user_login;
        } else {
            print '</br><strong>' . esc_html__('Owner ', 'wprentals-core') . ':</strong> <a href="' . get_permalink($agent_id) . '">' . get_the_title($agent_id) . '</a>';
        }
        ///////////////////////////////////////////////////////////////////////////////////
        wpestate_super_invoice_details($invoice_id);
        ///////////////////////////////////////////////////////////////////////////////////




        $purchase_date = esc_html(get_post_meta($post->ID, 'purchase_date', true));
        print'<div style="clear:both;"></div>
        <div class="property_prop_half prop_full">
            <strong>' . esc_html__('Invoice Id:', 'wprentals-core') . ' </strong>' . $post->ID . '
        </div>';

        $status = get_post_meta($post->ID, 'invoice_status', true);
        $status_full = get_post_meta($post->ID, 'invoice_status_full', true);
        $balance = get_post_meta($post->ID, 'balance', true);
        $enable_wire_status = esc_html(wprentals_get_option('wp_estate_enable_direct_pay', ''));


     

        if (empty(get_post_meta($post->ID, 'pay_status', true)) || get_post_meta($post->ID, 'pay_status', true) == 0) {

            if ($enable_wire_status === 'yes' && $status !== 'confirmed') {

                if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_PACKAGE) {
                    print '<div id="activate_pack" data-invoice="' . $post->ID . '" data-item="' . get_post_meta($post->ID, 'item_id', true) . '">' . esc_html__('Wire Payment Received - Activate the purchase', 'wprentals-core') . '</div>';
                } else if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE) {
                    print '<div id="activate_pack_reservation_fee" data-invoice="' . $post->ID . '" data-item="' . get_post_meta($post->ID, 'item_id', true) . '">' . esc_html__('Wire Payment Received - Activate the purchase', 'wprentals-core') . '</div>';
                } else {
                    print '<div id="activate_pack_listing" data-invoice="' . $post->ID . '" data-item="' . get_post_meta($post->ID, 'item_id', true) . ' " data-type="' . $purchase_type . '">' . esc_html__('Wire Payment Received - Activate the purchase', 'wprentals-core') . '</div>';
                }

                $ajax_nonce = wp_create_nonce("wprentals_activate_pack_nonce");
                print'<input type="hidden" id="wprentals_activate_pack" value="' . esc_html($ajax_nonce) . '" />    ';


                if (trim($status) != 'confirmed/ booking canceled by user') {
                    print'
                    <div class="property_prop_half" id="invnotpaid">
                        <strong>' . __('Invoice NOT paid', 'wprentals-core') . '</strong>
                    </div>';
                } else {
                    print'
                    <div class="property_prop_half" id="invnotpaid">
                        <strong>' . $status . '</strong>
                    </div>';
                }
            } else if ($enable_wire_status === 'yes' && $status == 'confirmed' && $balance > 0) {

                if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE) {
                    print '<div id="activate_pack_reservation_fee" data-invoice="' . $post->ID . '" data-item="' . get_post_meta($post->ID, 'item_id', true) . '">' . esc_html__('Wire Payment Received - Activate the purchase(invoice will be 100% paid)', 'wprentals-core') . '</div>';
                }

                $ajax_nonce = wp_create_nonce("wprentals_activate_pack_nonce");
                print'<input type="hidden" id="wprentals_activate_pack" value="' . esc_html($ajax_nonce) . '" />    ';


                print'
                <div class="property_prop_half" id="invnotpaid">
                    <strong>' . __('Invoice NOT paid in full', 'wprentals-core') . ' </strong>
                </div>';
            }
        } else {
            print'
            <div class="property_prop_half">
                <strong>' . __('Invoice PAID', 'wprentals-core') . ' </strong>
            </div>';
        }


        print'
        <div class="property_prop_half">
            <label for="biling_period"><strong>' . esc_html__('Billing For', 'wprentals-core') . ':</strong></label><br />
            ' . esc_html($invoice_saved) . '
        </div>

        <div class="property_prop_half">
            <label for="biling_type"><strong>' . esc_html__('Billing Type', 'wprentals-core') . ':</strong></label><br />
            ' . $invoice_period_saved . '
        </div>

        <div class="property_prop_half">
            <label for="item_id"><strong>' . esc_html__('Item Id (Listing or Package id)', 'wprentals-core') . ':</strong></label><br />
             ' . wpestate_show_product_type(esc_html(get_post_meta($post->ID, 'item_id', true))) . '
        </div>

        <div class="property_prop_half">
            <label for="item_price"><strong>' . esc_html__('Item Price', 'wprentals-core') . ':</strong></label><br />
            ' . esc_html(get_post_meta($post->ID, 'item_price', true)) . '
        </div>

        <div class="property_prop_half">
            <label for="purchase_date"><strong>' . esc_html__('Purchase Date', 'wprentals-core') . ':</strong></label><br />
        ';
        //    '.esc_html(get_post_meta($post->ID, 'purchase_date', true)).'

        $time_unix = strtotime($purchase_date);
        echo gmdate('Y-m-d H:i:s', ( $time_unix + ( get_option('gmt_offset') * HOUR_IN_SECONDS )));

        /*  if(is_numeric($purchase_date)){
          echo date('l jS \of F Y',$purchase_date);
          }else{
          print $purchase_date;
          }
         */

        print '
        </div>

        <div class="property_prop_half">
            <label for="buyer_id"><strong>' . esc_html__('User', 'wprentals-core') . ':</strong></label><br />';
        $user_id = esc_html(get_post_meta($post->ID, 'buyer_id', true));
        $user_info = get_userdata($user_id);
        if ($user_info === false) {
            $user_info = new stdClass();
            $user_info->user_login = esc_html__('Deleted User', 'wprentals-core');
        }
        print '<a href="' . esc_url(get_edit_user_link($user_id)) . '">';
        print esc_html__('Username: ', 'wprentals-core') . $user_info->user_login . ' ' . esc_html__('/ user id', 'wprentals-core') . ' ' . $user_id;
        print '</a>
        </div>
        ';
        if ($txn_id != '') {
            print esc_html__('Paypal - Reccuring Payment ID: ', 'wprentals-core') . $txn_id;
        }

        $details = get_post_meta($invoice_id, 'renting_details', true);

        print '</div></div>'; // end of property_tab_item_content
    }

endif; // end   wpestate_invoice_details



/**
 * Super invoice details display
 * Shows detailed booking information including dates, guests, prices, and fees
 *
 * @param int $invoice_id The invoice ID
 * @param string $width_logo Whether to display logo (optional)
 */
if (!function_exists('wpestate_super_invoice_details')):

    function wpestate_super_invoice_details($invoice_id, $width_logo = '') {

        $bookid = esc_html(get_post_meta($invoice_id, 'item_id', true));
        $booking_from_date = esc_html(get_post_meta($bookid, 'booking_from_date', true));
        $booking_prop = esc_html(get_post_meta($bookid, 'booking_id', true)); // property_id
        $booking_to_date = esc_html(get_post_meta($bookid, 'booking_to_date', true));
        $booking_guests = floatval(get_post_meta($bookid, 'booking_guests', true));
        $extra_options = get_post_meta($bookid, 'extra_options', true);
        $booking_type = wprentals_return_booking_type($booking_prop);


        $extra_options_array = array();
        if ($extra_options != '') {
            $extra_options_array = explode(',', $extra_options);
        }

        $manual_expenses = get_post_meta($invoice_id, 'manual_expense', true);
        $booking_array = wpestate_booking_price($booking_guests, $invoice_id, $booking_prop, $booking_from_date, $booking_to_date, $bookid, $extra_options_array, $manual_expenses);

        // and you might want to convert to integer

        $price_per_weekeend = floatval(get_post_meta($booking_prop, 'price_per_weekeend', true));
        $price_per_day = $booking_array['default_price'];
        $cleaning_fee = $booking_array['cleaning_fee'];
        $city_fee = $booking_array['city_fee'];
        $total_price = floatval(get_post_meta($invoice_id, 'item_price', true));
        $default_price = $booking_array['default_price'];
        $wp_estate_book_down = get_post_meta($invoice_id, 'invoice_percent', true);

        $depozit = $depozit_paid = floatval(get_post_meta($invoice_id, 'depozit_paid', true));
        $balance = 0;
        $balance = $total_price - $depozit;
        $depozit_show = '';
        $balance_show = '';

        $wpestate_currency = esc_html(get_post_meta($invoice_id, 'invoice_currency', true));
        $wpestate_where_currency = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $details = get_post_meta($invoice_id, 'renting_details', true);


        $invoice_status = esc_html(get_post_meta($invoice_id, 'invoice_status', true));


        $price_show = wpestate_show_price_booking_for_invoice($default_price, $wpestate_currency, $wpestate_where_currency, 0, 1);
        $price_per_weekeend_show = wpestate_show_price_booking_for_invoice($price_per_weekeend, $wpestate_currency, $wpestate_where_currency, 0, 1);
        $total_price_show = wpestate_show_price_booking_for_invoice($total_price, $wpestate_currency, $wpestate_where_currency, 0, 1);
        $depozit_show = wpestate_show_price_booking_for_invoice($depozit, $wpestate_currency, $wpestate_where_currency, 0, 1);
        $balance_show = wpestate_show_price_booking_for_invoice($balance, $wpestate_currency, $wpestate_where_currency, 0, 1);
        $guest_price = wpestate_show_price_booking_for_invoice($booking_array['extra_price_per_guest'], $wpestate_currency, $wpestate_where_currency, 1, 1);


        $invoice_type_value = get_post_meta($invoice_id, 'invoice_type', true);
        $invoice_saved      = wpestate_get_invoice_type_label($invoice_type_value);

        wpestate_print_create_form_invoice($guest_price, $booking_guests, $invoice_id, $invoice_saved, $booking_from_date, $booking_to_date, $booking_array, $price_show, $details, $wpestate_currency, $wpestate_where_currency, $total_price, $total_price_show, $depozit_show, $balance_show, $booking_prop, $price_per_weekeend_show, $booking_type, $width_logo);
    }

endif;


/**
 * Create invoice form display
 * Shows the invoice form with all pricing and booking details
 *
 * @param string $guest_price Price per guest
 * @param int $booking_guests Number of guests
 * @param int $invoice_id Invoice ID
 * @param int|string $invoice_saved Invoice type saved
 * @param string $booking_from_date Check-in date
 * @param string $booking_to_date Check-out date
 * @param array $booking_array Array of booking details
 * @param string $price_show Formatted price to display
 * @param array $details Additional details
 * @param string $wpestate_currency Currency symbol
 * @param string $wpestate_where_currency Currency position
 * @param float $total_price Total price
 * @param string $total_price_show Formatted total price
 * @param string $depozit_show Formatted deposit amount
 * @param string $balance_show Formatted balance amount
 * @param int $booking_prop Property ID
 * @param string $price_per_weekeend_show Weekend price formatted
 * @param int $booking_type Type of booking
 * @param string $width_logo Whether to display logo
 */

if (!function_exists('wpestate_print_create_form_invoice')):

    function wpestate_print_create_form_invoice($guest_price, $booking_guests, $invoice_id, $invoice_saved, $booking_from_date, $booking_to_date, $booking_array, $price_show, $details, $wpestate_currency, $wpestate_where_currency, $total_price, $total_price_show, $depozit_show, $balance_show, $booking_prop, $price_per_weekeend_show, $booking_type, $width_logo = '') {
        $rental_type = esc_html(wprentals_get_option('wp_estate_item_rental_type', ''));
        $invoice_deposit_tobe = floatval(get_post_meta($invoice_id, 'depozit_to_be_paid', true));
        $depozit_show = wpestate_show_price_booking_for_invoice($invoice_deposit_tobe, $wpestate_currency, $wpestate_where_currency, 0, 1);

        $balance = get_post_meta($invoice_id, 'balance', true);
        $balance_show = wpestate_show_price_booking_for_invoice($balance, $wpestate_currency, $wpestate_where_currency, 0, 1);
        $current_user = wp_get_current_user();
        $userID = $current_user->ID;
        $owner_see = 0;

        $invoice_type_key   = wpestate_get_invoice_type_key($invoice_saved);
        $invoice_type_label = wpestate_get_invoice_type_label($invoice_saved);

        if (wpsestate_get_author($booking_prop) == $userID ||  current_user_can( 'administrator' ) ) {
            $total_label = esc_html__('User Pays', 'wprentals-core');
            $owner_see = 1;
        } else {
            $total_label = esc_html__('You Pay', 'wprentals-core');
        }



        print '
           <div class="create_invoice_form">';


        if ($invoice_id != 0) {
            if ($width_logo == 'yes'):
                $logo = wprentals_get_option('wp_estate_logo_image', 'url');
                if ($logo != '') {
                    print '<img src="' . $logo . '" class="img-responsive printlogo" alt="logo"/>';
                } else {
                    print '<img class="img-responsive printlogo" src="' . get_theme_file_uri('/img/logo.png') . '" alt="logo"/>';
                }
            endif;
            print '<h3>' . esc_html__('Invoice INV', 'wprentals-core') . $invoice_id . '</h3>';
        }
        print '
                <div class="invoice_table">';
        if ($invoice_id != 0) {
            print '<div id="print_invoice" data-invoice_id="' . $invoice_id . '" ><i class="fas fa-print" aria-hidden="true" ></i></div>';
            $ajax_nonce = wp_create_nonce("wprentals_print_invoice_nonce");
            print'<input type="hidden" id="wprentals_print_invoice" value="' . esc_html($ajax_nonce) . '" />    ';
        }
        print'
        <div class="invoice_data">';
        $bookid = get_post_meta($invoice_id, 'item_id', true);
        $post_author_id = intval(get_post_meta($invoice_id, 'rented_by', true));
        $extra_price_per_guest = wpestate_show_price_booking($booking_array['extra_price_per_guest'], $wpestate_currency, $wpestate_where_currency, 1);

        if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE) {
            
            if($booking_from_date==''){
                $booking_from_date = esc_html(get_post_meta($invoice_id, 'booking_from_date', true));
                $booking_to_date = esc_html(get_post_meta($invoice_id, 'booking_to_date', true));
            }
            
            print'
            <span class="date_interval show_invoice_period"><span class="invoice_data_legend">' . esc_html__('Period', 'wprentals-core') . ' : </span>' . wpestate_convert_dateformat_reverse($booking_from_date) . ' ' . esc_html__('to', 'wprentals-core') . ' ' . wpestate_convert_dateformat_reverse($booking_to_date) . '</span>
            <span class="date_duration show_invoice_no_nights"><span class="invoice_data_legend">' . wpestate_show_labels('no_of_nights', $rental_type, $booking_type) . ': </span>' . $booking_array['numberDays'] . '</span>';
            
            if($booking_guests>0){
                print '<span class="date_duration show_invoice_guests"><span class="invoice_data_legend">' . esc_html__('Guests', 'wprentals-core') . ': </span>' . $booking_guests . wpestate_booking_guest_explanations($bookid) . '</span>';
            }
            
            if ($booking_array['price_per_guest_from_one'] == 1) {
                print'<span class="date_duration show_invoice_price_per_quest "><span class="invoice_data_legend">' . esc_html__('Price per Guest', 'wprentals-core') . ': </span>';
                if ($booking_array['custom_period_quest'] == 1) {
                    _e('custom price', 'wprentals-core');
                } else {
                    print $extra_price_per_guest;
                }
                print'</span>';
            } else {
                print '<span class="date_duration show_invoice_price_per_night"><span class="invoice_data_legend">' . wpestate_show_labels('price_label', $rental_type, $booking_type) . ': </span>';

                print $price_show;
                if ($booking_array['has_custom']) {
                    print ', ' . esc_html__('has custom price', 'wprentals-core');
                }
                if ($booking_array['cover_weekend']) {
                    print ', ' . esc_html__('has weekend price of', 'wprentals-core') . ' ' . $price_per_weekeend_show;
                }

                print '</span>';
            }

            if ($booking_array['has_custom'] || $booking_array['custom_period_quest'] == 1) {
                if (is_array($booking_array['custom_price_array'])) {
                    print '<span class="invoice_data_legend show_invoice_price_details">' . __('Price details:', 'wprentals-core') . '</span>';
                    foreach ($booking_array['custom_price_array'] as $date => $price) {
                        $day_price = wpestate_show_price_booking_for_invoice($price, $wpestate_currency, $wpestate_where_currency, 1, 1);
                        print '<span class="price_custom_explained show_invoice_price_details">' . __('on', 'wprentals-core') . ' ' . wpestate_convert_dateformat_reverse(date("Y-m-d", $date)) . ' ' . __('price is', 'wprentals-core') . ' ' . $day_price . '</span>';
                    }
                }
            }
        }


     




        if ($post_author_id == 0) {
            if (get_post_type($bookid) == 'wpestate_booking') {
                $post_author_id = get_post_field('post_author', $bookid);
            } else {
                $post_author_id = wpsestate_get_author($booking_prop);
            }
        }





        $booking_prop = esc_html(get_post_meta($bookid, 'booking_id', true)); // property_id
        if (intval($booking_prop) == 0) {
            $booking_prop = get_post_meta($invoice_id, 'for_property', true);
        }
        $first_name = get_the_author_meta('first_name', $post_author_id);
        $last_name = get_the_author_meta('last_name', $post_author_id);
        $user_email = get_the_author_meta('user_email', $post_author_id);
        $user_mobile = get_the_author_meta('mobile', $post_author_id);
        $payment_info = get_the_author_meta('payment_info', $post_author_id);
        $paypal_payments_to = get_the_author_meta('paypal_payments_to', $post_author_id);

        print'<span class="date_duration invoice_date_property_name_wrapper"><span class="invoice_data_legend">' . esc_html__('Property', 'wprentals-core') . ': </span><a href="' . esc_url(get_permalink($booking_prop)) . '" target="_blank">' . esc_html(get_the_title($booking_prop)) . '</a></span>';
        print'<span class="date_duration invoice_date_renter_name_wrapper"><span class="invoice_data_legend">' . esc_html__('Rented by', 'wprentals-core') . ': </span>' . $first_name . ' ' . $last_name . '</span>';
        print'<span class="date_duration invoice_date_renter_email_wrapper"><span class="invoice_data_legend">' . esc_html__('Email', 'wprentals-core') . ': </span>' . $user_email . '</span>';
        print'<span class="date_duration invoice_date_renter_phone_wrapper"><span class="invoice_data_legend">' . esc_html__('Phone', 'wprentals-core') . ': </span>' . $user_mobile . '</span>';
        print'<span class="date_duration invoice_date_renter_payment_info_wrapper"><span class="invoice_data_legend">' . esc_html__('Payment Info', 'wprentals-core') . ': </span>' . $payment_info . '</span>';
        print'<span class="date_duration invoice_date_renter_payments_to_wrapper"><span class="invoice_data_legend">' . esc_html__('Payments to ', 'wprentals-core') . ': </span>' . $paypal_payments_to . '</span>';

        print'
                    </div>

                   <div class="invoice_details">
                        <div class="invoice_row header_legend">
                            <span class="inv_legend">' . esc_html__('Cost', 'wprentals-core') . '</span>
                            <span class="inv_data">  ' . esc_html__('Price', 'wprentals-core') . '</span>
                            <span class="inv_exp">   ' . esc_html__('Detail', 'wprentals-core') . '</span>
                        </div>';


        if (is_array($details)) {

            foreach ($details as $detail) {
                if ($detail[1] != 0) {
                    print'<div class="invoice_row invoice_content">
                                        <span class="inv_legend">  ' . $detail[0] . '</span>
                                        <span class="inv_data">  ' . wpestate_show_price_booking_for_invoice($detail[1], $wpestate_currency, $wpestate_where_currency, 0, 1) . '</span>
                                        <span class="inv_exp"> ';
                    if (trim($detail[0]) == esc_html__('Security Depozit', 'wprentals-core') || trim($detail[0]) == esc_html__('Security Deposit', 'wprentals-core')) {
                        esc_html_e('*refundable', 'wprentals-core');
                    }

                    if (trim($detail[0]) == esc_html__('Subtotal', 'wprentals-core') || trim($detail[0]) == esc_html__('Subtotal', 'wprentals-core')     ) {
                        if ($booking_array['price_per_guest_from_one'] == 1) {
                            if ($booking_array['custom_period_quest'] == 1) {
                                print $booking_array['count_days'] . ' ' . wpestate_show_labels('nights', $rental_type, $booking_type) . ' x ' . $booking_array['curent_guest_no'] . ' ' . esc_html__('guests', 'wprentals-core') . ' - ' . esc_html__(" period with custom price per guest", "wprentals-core");
                            } else {
                                print $extra_price_per_guest . ' x ' . $booking_array['count_days'] . ' ' . wpestate_show_labels('nights', $rental_type, $booking_type) . ' x ' . $booking_array['curent_guest_no'] . ' ' . esc_html__('guests', 'wprentals-core');
                            }
                        } else {
                            print $booking_array['numberDays'] . ' ' . wpestate_show_labels('nights', $rental_type, $booking_type) . ' x ';
                            if ($booking_array['cover_weekend']) {
                                echo esc_html__('has weekend price of', 'wprentals-core') . ' ' . $price_per_weekeend_show;
                            } else {
                                if ($booking_array['has_custom'] != 0) {
                                    esc_html_e('custom price', 'wprentals-core');
                                } else {
                                    print $price_show;
                                }
                            }
                        }
                    }


                    if ($booking_array['custom_period_quest'] == 1) {
                        $new_guest_price = esc_html__("custom price", "wprentals-core");
                    } else {
                        $new_guest_price = $guest_price . ' ' . wpestate_show_labels('per_night', $rental_type, $booking_type);
                    }

                    if ( trim($detail[0]) == esc_html__('Extra Guests', 'wprentals-core') || trim($detail[0]) == esc_html__('Extra Guests', 'wprentals-core')    ) {
                        print $booking_array['numberDays'] . ' ' . wpestate_show_labels('nights', $rental_type, $booking_type) . ' x ' . $booking_array['extra_guests'] . ' ' . esc_html__('extra guests', 'wprentals-core') . ' x ' . $new_guest_price;
                    }

                    if (isset($detail[2])) {
                        print $detail[2];
                    }


                    print'  </span>
                                       </div>';
                }//end if       if($detail[1]>0)
            }
        } else {
            
        }

        // show Total row
        print '
                        <div class="invoice_row invoice_total invoice_create_print_invoice">
                            <span class="inv_legend inv_legend_total"><strong>' . $total_label . '</strong></span>
                            <span class="inv_data" id="total_amm" data-total="' . $total_price . '">' . $total_price_show . '</span>
                            <div class="deposit_show_wrapper total_inv_span"> ';

        if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE) {
            print'
                                <span class="inv_legend">' . esc_html__('Reservation Fee Required', 'wprentals-core') . ':</span> <span class="inv_depozit">' . $depozit_show . ' </span></br>
                                <span class="inv_legend">' . esc_html__('Balance', 'wprentals-core') . ':</span> <span class="inv_depozit">' . $balance_show . '</span>';
        } else {
            print esc_html($invoice_type_label);
        }

        print'
                        </div> ';


        // show earnings for owner

        if ($owner_see == 1) {
            wpestate_show_youearn($booking_array, $booking_prop, $total_price, $wpestate_currency, $wpestate_where_currency);
        }

        print'</div>
                       </div>
               </div>';
    }

endif;



/**
 * Show owner earnings section
 * Displays what the owner earns after fees and taxes
 *
 * @param array $booking_array Array of booking details
 * @param int $booking_prop Property ID
 * @param float $total_price Total price
 * @param string $wpestate_currency Currency symbol
 * @param string $wpestate_where_currency Currency position
 */
if (!function_exists('wpestate_show_youearn')):

    function wpestate_show_youearn($booking_array, $booking_prop, $total_price, $wpestate_currency, $wpestate_where_currency) {
        $taxes = 0;
        $cleaning_fee_deduct = 0;
        $city_fee_deduct = 0;
        $service_fee = 0;
        if (isset($booking_array['city_fee'])) {
            $city_fee_deduct = $booking_array['city_fee'];
        }
        if (isset($booking_array['cleaning_fee'])) {
            $cleaning_fee_deduct = $booking_array['cleaning_fee'];
        }



        $taxes_show = wpestate_show_price_booking_for_invoice($booking_array['taxes'], $wpestate_currency, $wpestate_where_currency, 0, 1);
        $you_earn_show = wpestate_show_price_booking_for_invoice($booking_array['youearned'], $wpestate_currency, $wpestate_where_currency, 0, 1);
        $service_fee_show = wpestate_show_price_booking_for_invoice($booking_array['service_fee'], $wpestate_currency, $wpestate_where_currency, 0, 1);
        print'
        <div class="invoice_row invoice_totalx invoice_total_generate_invoice">
            <span class="inv_legend"><strong>' . esc_html__('You Earn', 'wprentals-core') . '</strong></span>
            <span class="inv_data" id="youearned" data-youearned="' . $booking_array['youearned'] . '"><strong>' . $you_earn_show . '</strong></span>


            <div class="invoice_explantions">' . esc_html__('we deduct security deposit, city fees, cleaning fees and website service fee', 'wprentals-core') . '</div>

            <span class="total_inv_span ">
                <span class="inv_legend show_invoice_service_fee">' . esc_html__('Service Fee', 'wprentals-core') . ':</span>
                <span id="inv_depozit show_invoice_service_fee" >' . $service_fee_show . '</span>

                <div style="width:100%"></div>

                <span class="inv_legend show_invoice_taxes">' . esc_html__('Taxes', 'wprentals-core') . ':</span>
                <span id="inv_balance show_invoice_taxes">' . $taxes_show . '</span>
            </span>

            <div class="invoice_explantions show_invoice_taxes">' . esc_html__('*taxes are included in your earnings and you are responsible for paying these taxes', 'wprentals-core') . '</div>
        </div>';
    }

endif;