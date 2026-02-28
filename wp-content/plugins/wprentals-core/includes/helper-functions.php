<?php
/**
 * WPRentals Helper Functions
 *
 * Contains utility functions for templates, caching, image sizes, and data import/export.
 *
 * @package    WPRentals
 * @subpackage Core
 * @since      4.0
 * 
 * @uses       get_pages() For retrieving WordPress pages
 * @uses       wprentals_get_option() For fetching theme options
 * @uses       ICL_LANGUAGE_CODE For WPML compatibility
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Gets transient cache if caching is enabled
 *
 * @since  4.0
 * @param  string $transient_name The transient key
 * @return mixed                  The cached value or false
 */
if (!function_exists('wpestate_request_transient_cache')):
function wpestate_request_transient_cache($transient_name) {
    if (wprentals_get_option('wp_estate_disable_theme_cache') === 'yes') {
        return false;
    }
    return get_transient($transient_name);
}
endif;

/**
 * Sets transient cache if caching is enabled
 *
 * @since  4.0
 * @param  string $transient_name The transient key
 * @param  mixed  $value          The value to cache
 * @param  int    $time           Cache duration in seconds
 * @return void
 */
if (!function_exists('wpestate_set_transient_cache')):
function wpestate_set_transient_cache($transient_name, $value, $time) {
    if (wprentals_get_option('wp_estate_disable_theme_cache') !== 'yes') {
        set_transient($transient_name, $value, $time);
    }
}
endif;

/**
 * Returns default image sizes for the theme
 *
 * @since  4.0
 * @return array Array of image size configurations
 */
if (!function_exists('wpestate_return_default_image_size')):
function wpestate_return_default_image_size() {
    return array(
        'wpestate_blog_unit' => array(
            'name'   => esc_html__('Blog Unit', 'wprentals-core'),
            'width'  => 400,
            'height' => 242,
            'crop'   => true,
        ),
        'wpestate_blog_unit2' => array(
            'name'   => esc_html__('Blog Unit type 2', 'wprentals-core'),
            'width'  => 805,
            'height' => 453,
            'crop'   => true,
        ),
        'wpestate_slider_thumb' => array(
            'name'   => esc_html__('Slider thumb', 'wprentals-core'),
            'width'  => 143,
            'height' => 83,
            'crop'   => true,
        ),
        'wpestate_property_listings' => array(
            'name'   => esc_html__('Property Card Image', 'wprentals-core'),
            'width'  => 400,
            'height' => 314,
            'crop'   => true,
        ),
        'wpestate_property_featured' => array(
            'name'   => esc_html__('Property Featured', 'wprentals-core'),
            'width'  => 1170,
            'height' => 921,
            'crop'   => true,
        ),
        'wpestate_property_listings_page' => array(
            'name'   => esc_html__('Property Page Thumb', 'wprentals-core'),
            'width'  => 240,
            'height' => 160,
            'crop'   => true,
        ),
        'wpestate_property_places' => array(
            'name'   => esc_html__('Category Image', 'wprentals-core'),
            'width'  => 600,
            'height' => 456,
            'crop'   => true,
        ),
        'wpestate_property_full_map' => array(
            'name'   => esc_html__('Property full', 'wprentals-core'),
            'width'  => 1920,
            'height' => 790,
            'crop'   => true,
        ),
        'wpestate_user_thumb' => array(
            'name'   => esc_html__('User Thumb', 'wprentals-core'),
            'width'  => 60,
            'height' => 60,
            'crop'   => true,
        )
    );
}
endif;

/**
 * Returns imported theme options data
 *
 * @since  4.0
 * @return array Unserialized theme options data
 */
if (!function_exists('wpestate_return_imported_data')):
function wpestate_return_imported_data() {
    return @unserialize(base64_decode(trim($_POST['import_theme_options'])));
}
endif;

/**
 * Encodes theme options data for export
 *
 * @since  4.0
 * @param  array  $return_exported_data Data to be exported
 * @return string                       Base64 encoded serialized data
 */
if (!function_exists('wpestate_return_imported_data_encoded')):
function wpestate_return_imported_data_encoded($return_exported_data) {
    return base64_encode(serialize($return_exported_data));
}
endif;