<?php
/**
 * WPRentals Script Enqueuing
 *
 * Handles the enqueuing of styles and scripts for both frontend and admin.
 * Currently contains placeholder functions for future implementation.
 *
 * @package    WPRentals
 * @subpackage Core
 * @since      4.0
 * 
 * @uses       wp_enqueue_style() For enqueueing styles
 * @uses       wp_enqueue_script() For enqueueing scripts 
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueues styles and scripts for the frontend
 * Currently a placeholder function for future implementation
 *
 * @since 4.0
 * @return void
 */
if (!function_exists('wpestate_rentals_enqueue_styles')):
function wpestate_rentals_enqueue_styles() {
    // Placeholder for frontend styles and scripts enqueuing
}
endif;

/**
 * Enqueues styles and scripts for the admin area
 * Currently a placeholder function for future implementation
 *
 * @since 4.0
 * @return void
 */
if (!function_exists('wpestate_rentals_enqueue_styles_admin')):
function wpestate_rentals_enqueue_styles_admin() {
    // Placeholder for admin styles and scripts enqueuing
    
    wp_enqueue_style(
        'wprentals-theme-admin',
        WPESTATE_PLUGIN_DIR_URL . 'css/theme-admin.css',
        array(),
        '1.0'
    );

    wp_enqueue_style(
        'redux-elusive-icon',
        Redux_Core::$url . 'assets/css/vendor/elusive-icons' . Redux_Functions::is_min() . '.css',
        array(),
        '2.0.0'
    );

    wp_enqueue_style(
        'wprentals-theme-2025',
        WPESTATE_PLUGIN_DIR_URL . 'css/wprentals2025.css',
        array(),
        '1.0'
    );

    wp_enqueue_style(
        'wprentals-header-metabox',
        WPESTATE_PLUGIN_DIR_URL . 'css/header-metabox.css',
        array(),
        '1.0'
    );

    wp_enqueue_style(
        'wprentals-pro-list-adv-metabox',
        WPESTATE_PLUGIN_DIR_URL . 'css/pro-list-adv-metabox.css',
        array(),
        '1.0'
    );

    // Load script that remembers the last active tab for each metabox.
    wp_enqueue_script(
        'wprentals-tabs-functionality',
        WPESTATE_PLUGIN_DIR_URL . 'admin/js/tabs-functionality.js',
        array('jquery'),
        '1.0',
        true
    );

    wp_enqueue_script(
        'wprentals-property-actions',
        WPESTATE_PLUGIN_DIR_URL . 'admin/js/property-actions.js',
        array('jquery'),
        time(),
        true
    );

    wp_localize_script(
        'wprentals-property-actions',
        'wprentalsPropertyActions',
        array(
            'nonce' => wp_create_nonce('wp_estate_property_action')
        )
    );

    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( $screen && isset( $screen->base ) && in_array( $screen->base, array( 'term', 'edit-tags' ), true ) ) {
        $taxonomies   = array(
            'property_category',
            'property_action_category',
            'property_city',
            'property_county_state',
            'property_area',
            'property_features',
        );
        $current_tax = isset( $_GET['taxonomy'] ) ? sanitize_key( $_GET['taxonomy'] ) : '';

        if ( in_array( $current_tax, $taxonomies, true ) ) {
            $map_dependencies = array( 'jquery' );
            $map_type         = intval( wprentals_get_option( 'wp_estate_kind_of_map', 1 ) );
            $default_lat      = wprentals_get_option( 'wp_estate_general_latitude', '40.781711' );
            $default_lng      = wprentals_get_option( 'wp_estate_general_longitude', '-73.955927' );
            $default_zoom     = wprentals_get_option( 'wp_estate_default_map_zoom', 15 );

            if ( 1 === $map_type ) {
                $api_key   = trim( wprentals_get_option( 'wp_estate_api_key', '' ) );
                $libraries = '&libraries=places&language=en';
                $protocol  = is_ssl() ? 'https' : 'http';
                $google_url = sprintf(
                    '%1$s://maps.googleapis.com/maps/api/js?v=3.38%2$s%3$s',
                    $protocol,
                    $api_key ? '&key=' . rawurlencode( $api_key ) : '',
                    $libraries
                );

                wp_enqueue_script(
                    'wprentals-admin-google-maps',
                    $google_url,
                    array(),
                    null,
                    true
                );

                $map_dependencies[] = 'wprentals-admin-google-maps';
            } elseif ( 2 === $map_type ) {
                $theme_dir = trailingslashit( get_template_directory_uri() );

                wp_enqueue_style(
                    'leaflet',
                    $theme_dir . 'js/openstreet/leaflet.css',
                    array(),
                    '1.3.4'
                );

                wp_enqueue_script(
                    'leaflet',
                    $theme_dir . 'js/openstreet/leaflet.js',
                    array( 'jquery' ),
                    '1.3.4',
                    true
                );

                $map_dependencies[] = 'leaflet';
            }

            $term_lat  = '';
            $term_lng  = '';
            $term_zoom = '';
            $term_id   = isset( $_GET['tag_ID'] ) ? intval( $_GET['tag_ID'] ) : 0;

            if ( $term_id ) {
                $term_meta = get_option( 'taxonomy_' . $term_id, array() );

                if ( function_exists( 'wpestate_parse_category_term_array' ) ) {
                    $term_meta = wpestate_parse_category_term_array( $term_meta );
                    $term_lat  = isset( $term_meta['term_latitude'] ) ? $term_meta['term_latitude'] : '';
                    $term_lng  = isset( $term_meta['term_longitude'] ) ? $term_meta['term_longitude'] : '';
                    $term_zoom = isset( $term_meta['page_custom_zoom'] ) ? $term_meta['page_custom_zoom'] : '';
                }
            }

            wp_enqueue_script(
                'wprentals-term-custom-fields',
                WPESTATE_PLUGIN_DIR_URL . 'admin/js/term-custom-fields.js',
                array( 'jquery' ),
                '1.0',
                true
            );

            wp_enqueue_script(
                'wprentals-term-gallery',
                WPESTATE_PLUGIN_DIR_URL . 'admin/js/term-gallery.js',
                array( 'jquery' ),
                '1.0',
                true
            );

            wp_enqueue_script(
                'wprentals-term-documents',
                WPESTATE_PLUGIN_DIR_URL . 'admin/js/term-documents.js',
                array( 'jquery' ),
                '1.0',
                true
            );

            wp_localize_script(
                'wprentals-term-gallery',
                'wprentals_admin_gallery',
                array(
                    'title'  => esc_html__( 'Add Images', 'wprentals-core' ),
                    'button' => esc_html__( 'Use images', 'wprentals-core' ),
                    'term_id' => $term_id,
                    'nonce'   => wp_create_nonce( 'wpestate_term_gallery' ),
                )
            );

            wp_localize_script(
                'wprentals-term-documents',
                'wprentals_admin_documents',
                array(
                    'title'  => esc_html__( 'Add Documents', 'wprentals-core' ),
                    'button' => esc_html__( 'Use files', 'wprentals-core' ),
                    'term_id' => $term_id,
                    'nonce'   => wp_create_nonce( 'wpestate_term_documents' ),
                )
            );

            wp_localize_script(
                'wprentals-term-custom-fields',
                'wprentalsGeoJSON',
                array(
                    'title'  => esc_html__( 'Choose GeoJSON File', 'wprentals-core' ),
                    'button' => esc_html__( 'Use file', 'wprentals-core' ),
                )
            );

            wp_enqueue_script(
                'wprentals-term-map',
                WPESTATE_PLUGIN_DIR_URL . 'admin/js/term-map.js',
                $map_dependencies,
                '1.0',
                true
            );

            wp_localize_script(
                'wprentals-term-map',
                'wprentalsTermMap',
                array(
                    'mapType'        => $map_type,
                    'defaultLat'     => floatval( $default_lat ),
                    'defaultLng'     => floatval( $default_lng ),
                    'defaultZoom'    => intval( $default_zoom ),
                    'termLat'        => $term_lat,
                    'termLng'        => $term_lng,
                    'termZoom'       => $term_zoom,
                    'mapboxKey'      => wprentals_get_option( 'wp_estate_mapbox_api_key', '' ),
                    'geoFailMessage' => esc_html__( 'Geolocation was not successful for the following reason:', 'wprentals-core' ),
                )
            );
        }
    }
}
endif;