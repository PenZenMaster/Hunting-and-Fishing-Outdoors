<?php
/**
 * Module/Script Name: places-proxy.php
 * Path: wp-content/themes/wprentals-child/libs/places-proxy.php
 *
 * Description:
 * WordPress AJAX proxy for Google Places API (New) calls. WPRentals 3.17.0
 * calls places.googleapis.com/v1/ directly from the browser, exposing the API
 * key and preventing HTTP referrer restrictions. This proxy intercepts those
 * calls server-side so the key never leaves the server.
 *
 * Two endpoints are proxied:
 *  - hnfo_places_autocomplete : POST places:autocomplete (city search suggestions)
 *  - hnfo_places_details      : GET  places/{placeId}   (lat/lng + address fields)
 *
 * The companion JS (js/places-proxy.js) monkey-patches window.fetch in the
 * browser to redirect matching places.googleapis.com requests here instead.
 *
 * Author(s):
 * Rank Rocket Co (C) Copyright 2026 - All Rights Reserved
 *
 * Created Date: 2026-02-28
 * Last Modified Date: 2026-02-28
 *
 * Comments:
 * v1.00 - Initial implementation. Resolves GitHub issue #6.
 *         Fixes: API key exposed in browser + HTTP referrer restrictions
 *         incompatible with Places API (New) REST endpoint.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Autocomplete - logged-in and non-logged-in users (public listing form).
add_action( 'wp_ajax_hnfo_places_autocomplete',        'hnfo_places_autocomplete_proxy' );
add_action( 'wp_ajax_nopriv_hnfo_places_autocomplete', 'hnfo_places_autocomplete_proxy' );

// Place details - logged-in and non-logged-in users.
add_action( 'wp_ajax_hnfo_places_details',        'hnfo_places_details_proxy' );
add_action( 'wp_ajax_nopriv_hnfo_places_details', 'hnfo_places_details_proxy' );

/**
 * Proxy: POST places.googleapis.com/v1/places:autocomplete
 *
 * Accepts: action, nonce, input, included_region_codes (optional JSON array).
 * Returns: raw Google JSON response with matching HTTP status code.
 *
 * @return void
 */
function hnfo_places_autocomplete_proxy() {
	check_ajax_referer( 'hnfo_places_nonce', 'nonce' );

	$input = isset( $_POST['input'] ) ? sanitize_text_field( wp_unslash( $_POST['input'] ) ) : '';
	if ( '' === $input ) {
		wp_send_json_error( 'Empty query', 400 );
	}

	$api_key = trim( wprentals_get_option( 'wp_estate_api_key', '' ) );
	if ( '' === $api_key ) {
		wp_send_json_error( 'Maps API key not configured', 500 );
	}

	$body = array( 'input' => $input );

	// Forward region restrictions when the site has country limiting enabled.
	$region_codes_raw = isset( $_POST['included_region_codes'] )
		? wp_unslash( $_POST['included_region_codes'] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		: '';
	if ( '' !== $region_codes_raw ) {
		$decoded = json_decode( $region_codes_raw, true );
		if ( is_array( $decoded ) && ! empty( $decoded ) ) {
			$body['includedRegionCodes'] = array_map( 'sanitize_text_field', $decoded );
		}
	}

	$response = wp_remote_post(
		'https://places.googleapis.com/v1/places:autocomplete',
		array(
			'headers' => array(
				'Content-Type'     => 'application/json',
				'X-Goog-Api-Key'   => $api_key,
				'X-Goog-FieldMask' => 'suggestions.placePrediction.text,suggestions.placePrediction.placeId',
			),
			'body'    => wp_json_encode( $body ),
			'timeout' => 10,
		)
	);

	hnfo_proxy_send_response( $response );
}

/**
 * Proxy: GET places.googleapis.com/v1/places/{placeId}
 *
 * Accepts: action, nonce, place_id.
 * Returns: raw Google JSON response with matching HTTP status code.
 *
 * @return void
 */
function hnfo_places_details_proxy() {
	check_ajax_referer( 'hnfo_places_nonce', 'nonce' );

	$place_id = isset( $_POST['place_id'] ) ? sanitize_text_field( wp_unslash( $_POST['place_id'] ) ) : '';
	if ( '' === $place_id ) {
		wp_send_json_error( 'Missing place_id', 400 );
	}

	$api_key = trim( wprentals_get_option( 'wp_estate_api_key', '' ) );
	if ( '' === $api_key ) {
		wp_send_json_error( 'Maps API key not configured', 500 );
	}

	$url = 'https://places.googleapis.com/v1/places/' . rawurlencode( $place_id );

	$response = wp_remote_get(
		$url,
		array(
			'headers' => array(
				'X-Goog-Api-Key'   => $api_key,
				'X-Goog-FieldMask' => 'id,formattedAddress,displayName,location,viewport,addressComponents,adrFormatAddress',
			),
			'timeout' => 10,
		)
	);

	hnfo_proxy_send_response( $response );
}

/**
 * Output the upstream response body with the correct HTTP status code.
 *
 * Shared by both proxy handlers. Passes through Google's JSON response
 * verbatim so the browser JS can parse it as if it came from Google directly.
 *
 * @param array|WP_Error $response wp_remote_* response or WP_Error.
 * @return void
 */
function hnfo_proxy_send_response( $response ) {
	if ( is_wp_error( $response ) ) {
		wp_send_json_error( 'Upstream request failed', 502 );
	}

	$code = wp_remote_retrieve_response_code( $response );
	$body = wp_remote_retrieve_body( $response );

	status_header( $code );
	header( 'Content-Type: application/json; charset=utf-8' );
	echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- raw upstream JSON passthrough.
	wp_die();
}
