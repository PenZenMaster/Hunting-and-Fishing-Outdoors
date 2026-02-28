/*
 * Module/Script Name: places-proxy.js
 * Path: wp-content/themes/wprentals-child/js/places-proxy.js
 *
 * Description:
 * Monkey-patches window.fetch to redirect Google Places API (New) calls
 * through the WordPress AJAX proxy (hnfo_places_autocomplete_proxy,
 * hnfo_places_details_proxy) so the API key never appears in browser
 * network requests.
 *
 * Intercepts two URL patterns from ajaxcalls_add.js:
 *   POST https://places.googleapis.com/v1/places:autocomplete
 *   GET  https://places.googleapis.com/v1/places/{placeId}
 *
 * Requires hnfo_places_proxy_vars to be localised before this script runs:
 *   { ajax_url: string, nonce: string }
 *
 * Author(s):
 * Rank Rocket Co (C) Copyright 2026 - All Rights Reserved
 *
 * Created Date: 2026-02-28
 * Last Modified Date: 2026-02-28
 *
 * Comments:
 * v1.00 - Initial implementation. Resolves GitHub issue #6.
 */

/*global hnfo_places_proxy_vars */
(function () {
    'use strict';

    if (typeof hnfo_places_proxy_vars === 'undefined') {
        return;
    }

    var proxyUrl    = hnfo_places_proxy_vars.ajax_url;
    var nonce       = hnfo_places_proxy_vars.nonce;
    var originalFetch = window.fetch;

    /**
     * Build a URLSearchParams body for a WP AJAX POST request.
     *
     * @param {Object} fields Key/value pairs to include.
     * @returns {string} URL-encoded body string.
     */
    function buildAjaxBody(fields) {
        return new URLSearchParams(fields).toString();
    }

    /**
     * Options for a WP AJAX POST fetch.
     *
     * @param {string} body        URLSearchParams body string.
     * @param {AbortSignal} signal Optional AbortController signal.
     * @returns {Object} fetch init object.
     */
    function ajaxFetchOptions(body, signal) {
        var opts = {
            method:  'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body:    body
        };
        if (signal) {
            opts.signal = signal;
        }
        return opts;
    }

    window.fetch = function (url, options) {
        var urlStr  = (typeof url === 'string') ? url : String(url);
        var signal  = options && options.signal ? options.signal : null;

        // --- Intercept: Places Autocomplete ---
        if (urlStr.indexOf('places.googleapis.com/v1/places:autocomplete') !== -1) {
            var reqBody = {};
            try {
                reqBody = JSON.parse((options && options.body) ? options.body : '{}');
            } catch (e) { /* malformed body -- use empty object */ }

            var autocompleteParams = {
                action: 'hnfo_places_autocomplete',
                nonce:  nonce,
                input:  reqBody.input || ''
            };

            if (reqBody.includedRegionCodes) {
                autocompleteParams.included_region_codes = JSON.stringify(reqBody.includedRegionCodes);
            }

            return originalFetch(
                proxyUrl,
                ajaxFetchOptions(buildAjaxBody(autocompleteParams), signal)
            );
        }

        // --- Intercept: Place Details ---
        // Matches places.googleapis.com/v1/places/{placeId}
        // The [^:] guard ensures :autocomplete is not matched here.
        var detailsMatch = urlStr.match(/places\.googleapis\.com\/v1\/places\/([^?#/:]+)/);
        if (detailsMatch) {
            var detailsParams = {
                action:   'hnfo_places_details',
                nonce:    nonce,
                place_id: detailsMatch[1]
            };

            return originalFetch(
                proxyUrl,
                ajaxFetchOptions(buildAjaxBody(detailsParams), signal)
            );
        }

        // All other fetch calls pass through unchanged.
        return originalFetch.apply(this, arguments);
    };

}());
