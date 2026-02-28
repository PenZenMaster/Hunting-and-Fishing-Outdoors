<?php
/**
 * Import Locations admin functionality.
 *
 * Provides AJAX handlers and helpers for importing
 * location taxonomies from CSV files in the admin area.
 *
 * @package WpRentals Core
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue scripts for the Import Locations page.
 *
 * @param string $hook Current admin page hook.
 */
function wprentals_import_locations_enqueue_scripts( $hook ) {
    if ( isset( $_GET['page'], $_GET['tab'] ) &&
         'wprentals-post-type-control' === $_GET['page'] &&
         'import-locations' === $_GET['tab'] ) {
        wp_enqueue_media();
        wp_enqueue_script(
            'wprentals-import-locations',
            WPESTATE_PLUGIN_DIR_URL . 'admin/js/import-locations.js',
            array( 'jquery' ),
            '1.0',
            true
        );
        wp_localize_script( 'wprentals-import-locations', 'wprentalsImportLocations', array(
            'uploadTitle' => __( 'Choose CSV File', 'wprentals-core' ),
            'choose'      => __( 'Choose', 'wprentals-core' ),
            'nonce'       => wp_create_nonce( 'wprentals-import-locations' ),
            'noFile'      => __( 'Please choose a CSV file.', 'wprentals-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'wprentals_import_locations_enqueue_scripts' );

/**
 * Handle AJAX requests for importing locations.
 */
function wprentals_handle_import_locations() {
    check_ajax_referer( 'wprentals-import-locations', 'nonce' );

    $file_url  = isset( $_POST['file'] ) ? esc_url_raw( $_POST['file'] ) : '';
    $file_path = str_replace( home_url(), untrailingslashit( ABSPATH ), $file_url );

    if ( ! file_exists( $file_path ) ) {
        wp_send_json_error( __( 'CSV file not found.', 'wprentals-core' ) );
    }

    if ( false === ( $handle = fopen( $file_path, 'r' ) ) ) {
        wp_send_json_error( __( 'Could not open CSV file.', 'wprentals-core' ) );
    }

    $headers = fgetcsv( $handle, 1000, ',' );
    if ( empty( $headers ) ) {
        fclose( $handle );
        wp_send_json_error( __( 'CSV is empty.', 'wprentals-core' ) );
    }

    $index    = array_flip( $headers );
    $required = array( 'state', 'city', 'area' );
    foreach ( $required as $col ) {
        if ( ! isset( $index[ $col ] ) ) {
            fclose( $handle );
            wp_send_json_error( sprintf( __( 'Missing %s column.', 'wprentals-core' ), $col ) );
        }
    }

    while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
        $state_name = sanitize_text_field( $data[ $index['state'] ] );
        $city_name  = sanitize_text_field( $data[ $index['city'] ] );
        $area_name  = sanitize_text_field( $data[ $index['area'] ] );
        wprentals_insert_location_terms( $state_name, $city_name, $area_name );
    }
    fclose( $handle );
    wp_send_json_success( __( 'Import completed.', 'wprentals-core' ) );
}
add_action( 'wp_ajax_wprentals_import_locations', 'wprentals_handle_import_locations' );

/**
 * Insert taxonomy terms for a single CSV row.
 *
 * @param string $state_name State term name.
 * @param string $city_name  City term name.
 * @param string $area_name  Area term name.
 */
function wprentals_insert_location_terms( $state_name, $city_name, $area_name ) {
    $state_slug = '';
    if ( $state_name ) {
        $state = term_exists( $state_name, 'property_county_state' );
        if ( ! $state ) {
            $state = wp_insert_term( $state_name, 'property_county_state' );
        }
        if ( ! is_wp_error( $state ) ) {
            $state_id   = is_array( $state ) ? $state['term_id'] : $state;
            $state_term = get_term( $state_id, 'property_county_state' );
            if ( $state_term && ! is_wp_error( $state_term ) ) {
                $state_slug = $state_term->slug;
            }
        }
    }

    $city_slug = '';
    if ( $city_name ) {
        $city = term_exists( $city_name, 'property_city' );
        if ( ! $city ) {
            $city = wp_insert_term( $city_name, 'property_city' );
        }
        if ( ! is_wp_error( $city ) ) {
            $city_id   = is_array( $city ) ? $city['term_id'] : $city;
            $city_term = get_term( $city_id, 'property_city' );
            if ( $city_term && ! is_wp_error( $city_term ) ) {
                $city_slug = $city_term->slug;
                if ( $state_slug ) {
                    $meta                = (array) get_option( "taxonomy_{$city_id}" );
                    $meta['stateparent'] = $state_slug;
                    update_option( "taxonomy_{$city_id}", $meta );
                }
            }
        }
    }

    if ( $area_name ) {
        $area = term_exists( $area_name, 'property_area' );
        if ( ! $area ) {
            $area = wp_insert_term( $area_name, 'property_area' );
        }
        if ( ! is_wp_error( $area ) ) {
            $area_id = is_array( $area ) ? $area['term_id'] : $area;
            if ( $city_slug ) {
                $meta              = (array) get_option( "taxonomy_{$area_id}" );
                $meta['cityparent'] = $city_slug;
                update_option( "taxonomy_{$area_id}", $meta );
            }
        }
    }
}
