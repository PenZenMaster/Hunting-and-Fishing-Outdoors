<?php
/**
 * WPRentals Core Functions
 *
 * Contains the core functionality for WPRentals plugin including initialization,
 * activation/deactivation, and helper content creation.
 *
 * @package    WPRentals
 * @subpackage Core
 * @since      4.0
 * 
 * @uses       wprentals_get_option() For fetching theme options
 * @uses       wp_get_theme() For theme version checking
 * @uses       deactivate_plugins() For plugin deactivation
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initializes core functionality when plugin is loaded
 * Checks theme compatibility and loads required components
 *
 * @since 4.0
 * @return void
 */
if (!function_exists('wpestate_rentals_functionality_loaded')):
function wpestate_rentals_functionality_loaded() {
    // Check theme compatibility
    $my_theme = wp_get_theme();
    $version = floatval($my_theme->get('Version'));
    $theme_name = $my_theme->name;
    $deactivate = false;

    // Version compatibility check
    if ($version < 2 && $version != 1) {
        $deactivate = true;
    }

    // Theme name check
    if (strpos(strtolower($theme_name), 'wprentals') === false) {
        $deactivate = true;
    }

    if (is_admin() && function_exists('deactivate_plugins')) { // Ensure the function is called only in admin context
        //deactivate_plugins(plugin_basename(__FILE__));
        //wp_die(esc_html__('WpRentals Core plugin requires WpRentals 2.01 or higher.', 'wprentals-core'));
    } 

    // Load text domain
    load_plugin_textdomain('wprentals-core', false, dirname(WPESTATE_PLUGIN_BASE) . '/languages');

    // Initialize components
    wpestate_shortcodes();
    add_action('widgets_init', 'register_wpestate_widgets');
    add_action('wp_footer', 'wpestate_core_add_to_footer');
}
endif;

/**
 * Checks current user capabilities and manages admin bar visibility
 * Hides admin bar for non-admin users
 *
 * @since 4.0
 * @return void
 */
if (!function_exists('wpestate_check_current_user')):
function wpestate_check_current_user() {
    $current_user = wp_get_current_user();
    if (!current_user_can('manage_options')) {
        show_admin_bar(false);
    }
}
endif;

/**
 * Checks if theme license is active
 *
 * @since 4.0
 * @return boolean True if theme is activated
 */
if (!function_exists('wpestate_check_license_plugin')):
function wpestate_check_license_plugin() {
    $theme_activated = get_option('is_theme_activated', '');
    return $theme_activated === 'is_active';
}
endif;

/**
 * Creates required taxonomies and pages on theme setup
 *
 * @since 4.0
 * @return void
 */
add_action( 'after_setup_theme', 'wprentals_create_helper_content' );
if (!function_exists('wprentals_create_helper_content')):
function wprentals_create_helper_content() {
    // Only run if theme hasn't been set up
    if (get_option('wprentals_theme_setup') !== 'yes') {
        
        // Property action categories
        $action_categories = array(
            'Entire home',
            'Private room',
            'Shared room'
        );

        foreach ($action_categories as $key) {
            $my_cat = array(
                'description' => $key,
                'slug' => sanitize_title($key)
            );

            if (!term_exists($key, 'property_action_category', $my_cat)) {
                wp_insert_term($key, 'property_action_category', $my_cat);
            }
        }

        // Property type categories
        $property_types = array(
            'Apartment',
            'B & B',
            'Cabin',
            'Condos',
            'Dorm',
            'House',
            'Villa'
        );

        foreach ($property_types as $key) {
            if (!term_exists($key, 'property_category')) {
                wp_insert_term($key, 'property_category');
            }
        }

        // Create default pages
        wprentals_create_default_pages_on_install();

        // Mark theme as set up
        update_option('wprentals_theme_setup', 'yes');
    }
}
endif;

/**
 * Creates default pages required by the theme
 *
 * @since 4.0
 * @return void
 */
if (!function_exists('wprentals_create_default_pages_on_install')):
function wprentals_create_default_pages_on_install() {
    $default_pages = array(
        array(
            'name' => 'Advanced Search',
            'template' => 'advanced_search_results.php',
        ),
        array(
            'name' => 'My Listings',
            'template' => 'user_dashboard.php',
        ),
        array(
            'name' => 'Edit Listing',
            'template' => 'user_dashboard_edit_listing.php',
        ),
        array(
            'name' => 'Add New Listing',
            'template' => 'user_dashboard_add_step1.php',
        ),
        array(
            'name' => 'Favorites',
            'template' => 'user_dashboard_favorite.php',
        ),
        array(
            'name' => 'My Inbox',
            'template' => 'user_dashboard_inbox.php',
        ),
        array(
            'name' => 'Dashboard',
            'template' => 'user_dashboard_main.php',
        ),
        array(
            'name' => 'Invoices',
            'template' => 'user_dashboard_invoices.php',
        ),
        array(
            'name' => 'My Profile',
            'template' => 'user_dashboard_profile.php',
        ),
        array(
            'name' => 'Dashboard - Subscriptions',
            'template' => 'user_dashboard_packs.php',
        ),
        array(
            'name' => 'My Bookings',
            'template' => 'user_dashboard_my_bookings.php',
        ),
        array(
            'name' => 'My Reservations',
            'template' => 'user_dashboard_my_reservations.php',
        ),
        array(
            'name' => 'My Reviews',
            'template' => 'user_dashboard_my_reviews.php',
        ),
        array(
            'name' => 'All in One Calendar',
            'template' => 'user_dashboard_allinone.php',
        ),
    );

    foreach ($default_pages as $page) {
        if (wpestate_get_template_link($page['template'], 1) === '' || 
            wpestate_get_template_link($page['template'], 1) === home_url('/')) {
            
            $page_args = array(
                'post_title' => $page['name'],
                'post_type' => 'page',
                'post_status' => 'publish',
            );
            
            $new_id = wp_insert_post($page_args);
            if (!is_wp_error($new_id)) {
                update_post_meta($new_id, '_wp_page_template', $page['template']);
            }
        }
    }
}
endif;