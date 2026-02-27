<?php
/**
 * Module/Script Name: ajax-half-day-booking.php
 * Path: wp-content/themes/wprentals-child/libs/ajax-half-day-booking.php
 *
 * Description:
 * Child-theme overrides for the two AJAX handlers that manage half-day and
 * same-day booking. These functions are intentionally identical to the 3.11.4
 * parent theme versions that the Upwork team added. Declaring them here (where
 * the child theme loads first) means the parent's if(!function_exists()) guard
 * skips the parent's definition, so our version always wins -- including after
 * the parent theme is upgraded to 3.17.0 which removed this code entirely.
 *
 * Author(s):
 * Rank Rocket Co (C) Copyright 2026 - All Rights Reserved
 *
 * Created Date: 2026-02-27
 * Last Modified Date: 2026-02-27
 *
 * Comments:
 * v1.00 - Initial migration from wprentals/libs/ajax_functions_edit.php (lines
 *         2107-2257) and wprentals/libs/ajax_functions_booking.php (lines
 *         2597-2787) as part of the 3.17.0 upgrade preparation.
 *
 * NOTE: The parent theme registers add_action() for both functions outside its
 *       own function_exists() guard, so we do NOT need to re-register here.
 *       The parent's add_action() call will resolve to our child definitions.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

////////////////////////////////////////////////////////////////////////////
// Edit property price - child theme override to preserve half-day fields
////////////////////////////////////////////////////////////////////////////

if ( ! function_exists( 'wpestate_ajax_update_listing_price' ) ) :
    function wpestate_ajax_update_listing_price() {
        check_ajax_referer( 'wprentals_edit_prop_price_nonce', 'security' );

        $current_user = wp_get_current_user();
        $userID       = $current_user->ID;

        if ( ! is_user_logged_in() ) {
            exit( 'ko' );
        }
        if ( $userID === 0 ) {
            exit( 'out pls' );
        }

        if ( isset( $_POST['listing_edit'] ) ) {
            if ( ! is_numeric( $_POST['listing_edit'] ) ) {
                exit( 'you don\'t have the right to edit this' );
            } else {
                $edit_id   = intval( $_POST['listing_edit'] );
                $the_post  = get_post( $edit_id );

                if ( $current_user->ID != $the_post->post_author ) {
                    esc_html_e( "you don't have the right to edit this", 'wprentals' );
                    die();
                } else {
                    $cleaning_fee                 = floatval( $_POST['cleaning_fee'] );
                    $city_fee                     = floatval( $_POST['city_fee'] );
                    $price                        = floatval( $_POST['price'] );
                    $morning_price                = floatval( $_POST['morning_price'] );
                    $afternoon_price              = floatval( $_POST['afternoon_price'] );
                    $book_type                    = floatval( $_POST['book_type'] );
                    $booking_value                = esc_html( $_POST['booking_value'] );
                    $price_week                   = floatval( $_POST['price_week'] );
                    $price_month                  = floatval( $_POST['price_month'] );
                    $cleaning_fee_per_day         = floatval( $_POST['cleaning_fee_per_day'] );
                    $city_fee_per_day             = floatval( $_POST['city_fee_per_day'] );
                    $min_days_booking             = floatval( $_POST['min_days_booking'] );
                    $price_per_guest_from_one     = floatval( $_POST['price_per_guest_from_one'] );
                    $price_per_weekeend           = floatval( $_POST['price_per_weekeend'] );
                    $checkin_change_over          = floatval( $_POST['checkin_change_over'] );
                    $checkin_checkout_change_over = floatval( $_POST['checkin_checkout_change_over'] );
                    $extra_price_per_guest        = floatval( $_POST['extra_price_per_guest'] );

                    $city_fee_percent           = floatval( $_POST['city_fee_percent'] );
                    $security_deposit           = floatval( $_POST['security_deposit'] );
                    $property_price_after_label = esc_html( $_POST['property_price_after_label'] );
                    $property_price_before_label = esc_html( $_POST['property_price_before_label'] );
                    $extra_pay_options          = $_POST['extra_pay_options'];
                    $early_bird_percent         = floatval( $_POST['early_bird_percent'] );
                    $early_bird_days            = floatval( $_POST['early_bird_days'] );
                    $property_taxes             = floatval( $_POST['property_taxes'] );

                    $booking_start_hour         = esc_html( $_POST['booking_start_hour'] );
                    $booking_end_hour           = esc_html( $_POST['booking_end_hour'] );
                    $booking_start_hour_noon    = esc_html( $_POST['booking_start_hour_noon'] );
                    $booking_end_hour_noon      = esc_html( $_POST['booking_end_hour_noon'] );
                    $booking_start_hour_mrng    = esc_html( $_POST['booking_start_hour_mrng'] );
                    $booking_end_hour_mrng      = esc_html( $_POST['booking_end_hour_mrng'] );
                    $property_price_hfday       = floatval( $_POST['property_price_hfday'] );
                    $property_price_hr          = floatval( $_POST['property_price_hr'] );

                    $booking_cd_end_date   = esc_html( $_POST['booking_cd_end_date'] );
                    $booking_cd_start_date = esc_html( $_POST['booking_cd_start_date'] );
                    $booking_cd_day        = $_POST['booking_cd_day'];

                    $extra_pay_values = array();
                    if ( is_array( $extra_pay_options ) ) {
                        foreach ( $extra_pay_options as $key => $pay_option ) {
                            $option             = explode( '|', $pay_option );
                            $extra_pay_values[] = $option;
                        }
                    }

                    update_post_meta( $edit_id, 'property_price', $price );
                    update_post_meta( $edit_id, 'morning_price', $morning_price );
                    update_post_meta( $edit_id, 'afternoon_price', $afternoon_price );

                    update_post_meta( $edit_id, 'property_price_hfday', $property_price_hfday );
                    update_post_meta( $edit_id, 'property_price_hr', $property_price_hr );
                    update_post_meta( $edit_id, 'local_booking_type', $book_type );
                    if ( $booking_value === 'Half day' ) {
                        update_post_meta( $edit_id, 'is_half_day', 'yes' );
                    }
                    update_post_meta( $edit_id, 'is_half_day_name', $booking_value );
                    update_post_meta( $edit_id, 'cleaning_fee', $cleaning_fee );
                    update_post_meta( $edit_id, 'city_fee', $city_fee );
                    update_post_meta( $edit_id, 'property_price_per_week', $price_week );
                    update_post_meta( $edit_id, 'property_price_per_month', $price_month );
                    update_post_meta( $edit_id, 'cleaning_fee_per_day', $cleaning_fee_per_day );
                    update_post_meta( $edit_id, 'city_fee_per_day', $city_fee_per_day );
                    update_post_meta( $edit_id, 'price_per_guest_from_one', $price_per_guest_from_one );
                    update_post_meta( $edit_id, 'price_per_weekeend', $price_per_weekeend );
                    update_post_meta( $edit_id, 'checkin_change_over', $checkin_change_over );
                    update_post_meta( $edit_id, 'checkin_checkout_change_over', $checkin_checkout_change_over );
                    update_post_meta( $edit_id, 'min_days_booking', $min_days_booking );
                    update_post_meta( $edit_id, 'extra_price_per_guest', $extra_price_per_guest );

                    update_post_meta( $edit_id, 'city_fee_percent', $city_fee_percent );
                    update_post_meta( $edit_id, 'security_deposit', $security_deposit );
                    update_post_meta( $edit_id, 'property_price_after_label', $property_price_after_label );
                    update_post_meta( $edit_id, 'property_price_before_label', $property_price_before_label );
                    update_post_meta( $edit_id, 'extra_pay_options', $extra_pay_values );
                    update_post_meta( $edit_id, 'early_bird_days', $early_bird_days );
                    update_post_meta( $edit_id, 'early_bird_percent', $early_bird_percent );
                    update_post_meta( $edit_id, 'property_taxes', $property_taxes );

                    update_post_meta( $edit_id, 'booking_start_hour', $booking_start_hour );
                    update_post_meta( $edit_id, 'booking_end_hour', $booking_end_hour );
                    update_post_meta( $edit_id, 'booking_start_hour_noon', $booking_start_hour_noon );
                    update_post_meta( $edit_id, 'booking_end_hour_noon', $booking_end_hour_noon );
                    update_post_meta( $edit_id, 'booking_start_hour_mrng', $booking_start_hour_mrng );
                    update_post_meta( $edit_id, 'booking_end_hour_mrng', $booking_end_hour_mrng );

                    update_post_meta( $edit_id, 'booking_cd_end_date', $booking_cd_end_date );
                    update_post_meta( $edit_id, 'booking_cd_start_date', $booking_cd_start_date );
                    update_post_meta( $edit_id, 'booking_cd_day', $booking_cd_day );

                    if ( function_exists( 'icl_translate' ) ) {
                        do_action( 'wpml_sync_all_custom_fields', $edit_id );
                    }

                    $status         = wpestate_global_check_mandatory( $edit_id );
                    $message_status = '';
                    if ( $status === 'pending' ) {
                        $message_status = esc_html__( 'Your listing is pending. Please complete all the mandatory fields for it to be published!', 'wprentals' );
                    }
                    echo json_encode(
                        array(
                            'edited'   => true,
                            'response' => esc_html__( 'Changes are saved!', 'wprentals' ) . ' ' . $message_status,
                        )
                    );

                    die();
                }
            }
        }
    }
endif;

////////////////////////////////////////////////////////////////////////////////
// Ajax show booking costs - child theme override to preserve half-day pricing
////////////////////////////////////////////////////////////////////////////////

if ( ! function_exists( 'wpestate_ajax_show_booking_costs' ) ) :
    function wpestate_ajax_show_booking_costs() {
        check_ajax_referer( 'wprentals_add_booking_nonce', 'security' );
        $allowed_html      = array();
        $property_id       = intval( $_POST['property_id'] );
        $wpestate_guest_no = intval( $_POST['guest_no'] );
        $guest_fromone     = intval( $_POST['guest_fromone'] );
        $booking_from_date = wp_kses( $_POST['fromdate'], $allowed_html );
        $booking_to_date   = wp_kses( $_POST['todate'], $allowed_html );

        $booking_time  = ! empty( $_POST['booking_time'] ) ? esc_html( $_POST['booking_time'] ) : 'Morning';
        $invoice_id    = 0;
        $price_per_day = floatval( get_post_meta( $property_id, 'property_price', true ) );
        $morning_price = floatval( get_post_meta( $property_id, 'morning_price', true ) );
        $afternoon_price = floatval( get_post_meta( $property_id, 'afternoon_price', true ) );

        // Half day & hour price from database
        $price_per_h_day = floatval( get_post_meta( $property_id, 'property_price_hfday', true ) );
        $price_per_hr    = floatval( get_post_meta( $property_id, 'property_price_hr', true ) );

        $booking_array = wpestate_booking_price( $wpestate_guest_no, $invoice_id, $property_id, $booking_from_date, $booking_to_date, $property_id );

        // Inject half-day and hourly prices into the booking array
        $booking_array['default_price_hfday'] = $price_per_h_day;
        $booking_array['default_price_hour']  = $price_per_hr;

        $deposit_show        = '';
        $balance_show        = '';
        $wpestate_currency   = esc_html( wprentals_get_option( 'wp_estate_currency_label_main', '' ) );
        $wpestate_where_currency = esc_html( wprentals_get_option( 'wp_estate_where_currency_symbol', '' ) );

        $price_show       = wpestate_show_price_booking( $booking_array['default_price'], $wpestate_currency, $wpestate_where_currency, 1 );
        $price_hfday_show = wpestate_show_price_booking( $booking_array['default_price_hfday'], $wpestate_currency, $wpestate_where_currency, 1 );
        $price_hour_show  = wpestate_show_price_booking( $booking_array['default_price_hour'], $wpestate_currency, $wpestate_where_currency, 1 );

        $total_price_show = wpestate_show_price_booking( $booking_array['total_price'], $wpestate_currency, $wpestate_where_currency, 1 );

        $half_total_price_show = wpestate_show_price_booking( $booking_array['half_total_price'], $wpestate_currency, $wpestate_where_currency, 1 );
        $hour_total_price_show = wpestate_show_price_booking( $booking_array['hour_total_price'], $wpestate_currency, $wpestate_where_currency, 1 );

        $deposit_show                     = wpestate_show_price_booking( $booking_array['deposit'], $wpestate_currency, $wpestate_where_currency, 1 );
        $balance_show                     = wpestate_show_price_booking( $booking_array['balance'], $wpestate_currency, $wpestate_where_currency, 1 );
        $city_fee_show                    = wpestate_show_price_booking( $booking_array['city_fee'], $wpestate_currency, $wpestate_where_currency, 1 );
        $cleaning_fee_show                = wpestate_show_price_booking( $booking_array['cleaning_fee'], $wpestate_currency, $wpestate_where_currency, 1 );
        $total_extra_price_per_guest_show = wpestate_show_price_booking( $booking_array['total_extra_price_per_guest'], $wpestate_currency, $wpestate_where_currency, 1 );
        $inter_price_show                 = wpestate_show_price_booking( $booking_array['inter_price'], $wpestate_currency, $wpestate_where_currency, 1 );

        $half_inter_price_show = wpestate_show_price_booking( $booking_array['half_inter_price'], $wpestate_currency, $wpestate_where_currency, 1 );
        $hour_inter_price_show = wpestate_show_price_booking( $booking_array['hour_inter_price'], $wpestate_currency, $wpestate_where_currency, 1 );

        $extra_price_per_guest  = wpestate_show_price_booking( $booking_array['extra_price_per_guest'], $wpestate_currency, $wpestate_where_currency, 1 );
        $security_fee_show      = wpestate_show_price_booking( $booking_array['security_deposit'], $wpestate_currency, $wpestate_where_currency, 1 );
        $early_bird_discount_show = wpestate_show_price_booking( $booking_array['early_bird_discount'], $wpestate_currency, $wpestate_where_currency, 1 );
        $rental_type            = wprentals_get_option( 'wp_estate_item_rental_type' );
        $booking_type           = wprentals_return_booking_type( $property_id );

        print '
        <div class="show_cost_form" id="show_cost_form" >
            <div class="cost_row">
                <div class="cost_explanation">';
        if ( $booking_array['price_per_guest_from_one'] == 1 ) {

            if ( $booking_array['custom_period_quest'] != 1 ) {
                print trim( $extra_price_per_guest ) . ' x ';
            }

            if ( $booking_type == 2 ) {
                print $booking_array['curent_guest_no'] . ' ' . esc_html__( 'guests', 'wprentals' );
            } else {
                print esc_html( $booking_array['count_days'] ) . ' ' . wpestate_show_labels( 'nights', $rental_type, $booking_type ) . ' x ' . $booking_array['curent_guest_no'] . ' ' . esc_html__( 'guests', 'wprentals' );
            }

            if ( $booking_array['custom_period_quest'] == 1 ) {
                echo ' - ';
                esc_html_e( ' period with custom price per guest', 'wprentals' );
            }
        } else {

            if ( $booking_array['has_custom'] == 1 ) {
                print esc_html( $booking_array['numberDays'] ) . ' ' . wpestate_show_labels( 'nights_custom_price', $rental_type, $booking_type );
            } elseif ( $booking_array['has_wkend_price'] === 1 && $booking_array['cover_weekend'] === 1 ) {
                print esc_html( $booking_array['numberDays'] ) . ' ' . wpestate_show_labels( 'days_custom_price', $rental_type, $booking_type );
            } else {
                print esc_html( $price_show ) . ' x ' . $booking_array['numberDays'] . ' ' . wpestate_show_labels( 'nights', $rental_type, $booking_type );
            }
        }

        print '</div>
                <div class="cost_value">' . $inter_price_show . '</div>
            </div>';

        if ( $booking_array['has_guest_overload'] != 0 && $booking_array['total_extra_price_per_guest'] != 0 ) {
            print '
                <div class="cost_row">
                    <div class="cost_explanation">' . esc_html__( 'Costs for ', 'wprentals' ) . '  ' . $booking_array['extra_guests'] . ' ' . esc_html__( 'extra guests', 'wprentals' ) . '</div>
                    <div class="cost_value">' . $total_extra_price_per_guest_show . '</div>
                </div>';
        }

        if ( $booking_array['cleaning_fee'] != 0 && $booking_array['cleaning_fee'] != '' ) {
            print '
                <div class="cost_row">
                    <div class="cost_explanation">' . esc_html__( 'Cleaning Fee', 'wprentals' ) . '</div>
                    <div class="cost_value cleaning_fee_value" data_cleaning_fee="' . $booking_array['cleaning_fee'] . '">' . $cleaning_fee_show . '</div>
                </div>';
        }

        if ( $booking_array['city_fee'] != 0 && $booking_array['city_fee'] != '' ) {
            print '
                <div class="cost_row">
                    <div class="cost_explanation">' . esc_html__( 'City Fee', 'wprentals' ) . '</div>
                    <div class="cost_value city_fee_value" data_city_fee="' . $booking_array['city_fee'] . '">' . $city_fee_show . '</div>
                </div>';
        }

        if ( $booking_array['security_deposit'] != 0 && $booking_array['security_deposit'] != '' ) {
            print '
                <div class="cost_row">
                    <div class="cost_explanation">' . esc_html__( 'Security Deposit (*refundable)', 'wprentals' ) . '</div>
                    <div class="cost_value">' . $security_fee_show . '</div>
                </div>';
        }

        if ( $booking_array['early_bird_discount'] != 0 && $booking_array['early_bird_discount'] != '' ) {
            print '
                <div class="cost_row">
                    <div class="cost_explanation">' . esc_html__( 'Early Bird Discount', 'wprentals' ) . '</div>
                    <div class="cost_value" id="early_bird_discount" data-early-bird="' . esc_attr( $booking_array['early_bird_discount'] ) . '">' . $early_bird_discount_show . '</div>
                </div>';
        }

        print '
                <div class="cost_row" id="total_cost_row">
                    <div class="cost_explanation"><strong>' . esc_html__( 'TOTAL', 'wprentals' ) . '</strong></div>
                    <div class="cost_value" data_total_price="' . $booking_array['total_price'] . '" >' . $total_price_show . '</div>
                </div>
            </div>';

        $instant_booking = floatval( get_post_meta( $property_id, 'instant_booking', true ) );

        if ( $instant_booking == 1 ) {
            print '<div class="cost_row_instant instant_depozit">' . esc_html__( 'Deposit for instant booking', 'wprentals' ) . ': ';
            print '<span class="instant_depozit_value">';
            if ( floatval( $booking_array['deposit'] ) != 0 ) {
                print trim( $deposit_show );
            } else {
                echo '0';
            }
            print '</span>';
            print '</div>';

            if ( floatval( $booking_array['balance'] ) != 0 ) {
                print '<div class="cost_row_instant instant_balance">' . esc_html__( 'Balance remaining', 'wprentals' ) . ': <span class="instant_balance_value">' . $balance_show . '</span></div>';
            }

            print '<div class="instant_book_info" data-total_price="' . esc_attr( $booking_array['total_price'] ) . '" data-deposit="' . esc_attr( $booking_array['deposit'] ) . '" data-balance="' . esc_attr( $booking_array['balance'] ) . '"> ';
        }

        die();
    }
endif;
