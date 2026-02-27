/*
 * half-day-price-save.js
 * Path: wp-content/themes/wprentals-child/js/half-day-price-save.js
 *
 * Purpose:
 * Ensures half-day and same-day booking fields are always included in the
 * wpestate_ajax_update_listing_price AJAX call, even if the parent theme's
 * ajaxcalls_add.js no longer sends them (as is the case in WPRentals 3.17.0+).
 *
 * Approach:
 * Uses jQuery.ajaxPrefilter to intercept outbound AJAX requests. When the
 * request targets wpestate_ajax_update_listing_price and a field is missing
 * from the serialised data string, it is appended. This is idempotent: if the
 * parent's JS already includes a field, the prefilter skips it, so the file
 * is safe to enqueue alongside both 3.11.4 and 3.17.0 parent JS.
 *
 * Author(s): Rank Rocket Co (C) Copyright 2026 - All Rights Reserved
 * Created: 2026-02-27
 * v1.00
 */

/* global jQuery */
jQuery(function ($) {
    'use strict';

    $.ajaxPrefilter(function (options) {
        if (
            typeof options.data !== 'string' ||
            options.data.indexOf('action=wpestate_ajax_update_listing_price') === -1
        ) {
            return;
        }

        // Helper: append field only when not already present in the POST data.
        function addIfMissing(paramName, value) {
            if (options.data.indexOf(paramName + '=') === -1) {
                options.data += '&' + paramName + '=' + encodeURIComponent(value !== undefined && value !== null ? value : '');
            }
        }

        // Half-day pricing fields.
        addIfMissing('property_price_hfday', $('#property_price_hfday').val());
        addIfMissing('property_price_hr',    $('#property_price_hr').val());
        addIfMissing('morning_price',        $('#morning_price').val());
        addIfMissing('afternoon_price',      $('#afternoon_price').val());

        // Booking type / half-day flag.
        addIfMissing('book_type',      $('#local_booking_type').val());
        addIfMissing('booking_value',  $('#local_booking_type option:selected').attr('data-opt'));

        // Half-day business hours.
        addIfMissing('booking_start_hour_noon', $('#booking_start_hour_noon').val());
        addIfMissing('booking_end_hour_noon',   $('#booking_end_hour_noon').val());
        addIfMissing('booking_start_hour_mrng', $('#booking_start_hour_mrng').val());
        addIfMissing('booking_end_hour_mrng',   $('#booking_end_hour_mrng').val());
    });
});
