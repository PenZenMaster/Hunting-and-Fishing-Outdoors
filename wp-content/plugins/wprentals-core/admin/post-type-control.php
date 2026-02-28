<?php
/**
 * WpRentals Settings - Post Type Control
 *
 * This file handles the admin interface for enabling/disabling
 * custom post types and taxonomies in the WpRentals theme.
 *
 * @package WpRentals Core
 * @version 1.0.0
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Hook into WordPress admin initialization
add_action( 'admin_menu', 'wprentals_settings_menu' );      // Create admin menu pages
add_action( 'admin_init', 'wprentals_ptc_save' );          // Handle form submissions
add_action( 'init', 'wprentals_ptc_apply', 100 );          // Apply post type/taxonomy settings
add_action( 'admin_post_wprentals_feedback_submit', 'wprentals_ptc_handle_feedback' ); // Handle feedback submissions

require_once WPESTATE_PLUGIN_PATH . "admin/ptc-tabs/post-types.php";
require_once WPESTATE_PLUGIN_PATH . "admin/ptc-tabs/taxonomies.php";
require_once WPESTATE_PLUGIN_PATH . "admin/ptc-tabs/plugins.php";
require_once WPESTATE_PLUGIN_PATH . "admin/ptc-tabs/feedback.php";
require_once WPESTATE_PLUGIN_PATH . "admin/ptc-tabs/import-locations.php";
require_once WPESTATE_PLUGIN_PATH . "admin/ptc-tabs/white-label.php";
require_once WPESTATE_PLUGIN_PATH . "admin/ptc-tabs/license.php";

require_once WPESTATE_PLUGIN_PATH . "admin/translator/translator-functions.php";

/**
 * Create the main settings menu and submenu pages in WordPress admin
 *
 * Adds a main "WpRentals Settings" menu item and a "Post Type Control"
 * submenu page for managing which post types and taxonomies are active.
 */
function wprentals_settings_menu() {
    // Create main menu page
    $wprentals_branding = wprentals_theme_branding(); // Uses the filter system
    //   WPESTATE_PLUGIN_DIR_URL . '/img/rentals_icon.png', 
    $menu_icon = wprentals_get_theme_branding_logo_url(); // Just returns the URL
 

    add_menu_page(
        $wprentals_branding .' Site Settings',  // Page title
        $wprentals_branding .' Site Settings', // Menu title
        'manage_options',                                           // Required capability
        'wprentals-settings',                                     // Menu slug
        'wprentals_settings_main_page',                          // Callback function
        $menu_icon , // Just returns the URL
        3                                                           // Menu position
    );



    add_submenu_page(
        'wprentals-settings',
        __( 'Plugins', 'wprentals-core' ),
        __( 'Plugins', 'wprentals-core' ),
        'manage_options',
        'wprentals-post-type-control-plugins',
        'wprentals_ptc_page_plugins'
    );

    add_submenu_page(
        'wprentals-settings',
        __( 'Feedback', 'wprentals-core' ),
        __( 'Feedback', 'wprentals-core' ),
        'manage_options',
        'wprentals-post-type-control-feedback',
        'wprentals_ptc_page_feedback'
    );

    add_submenu_page(
        'wprentals-settings',
        __( $wprentals_branding .' License', 'wprentals-core' ),
        __( $wprentals_branding .' License', 'wprentals-core' ),
        'manage_options',
        'wprentals-post-type-control-license',
        'wprentals_ptc_page_license'
    );


  // Show white label menu UNLESS the constant is defined as true
    if ( ! ( defined( 'HIDE_WHITE_LABEL_ACCESS' ) && HIDE_WHITE_LABEL_ACCESS === true ) ) {
        add_submenu_page(
            'wprentals-settings',
            __( 'White Label', 'wprentals-core' ),
            __( 'White Label', 'wprentals-core' ),
            'manage_options',
            'wprentals-post-type-control-white-label',
            'wprentals_ptc_page_white_label'
        );
        remove_submenu_page('wprentals-settings','wprentals-settings');
    }
    remove_submenu_page('wprentals-settings','wprentals-settings');
}

/**
 * Get all available custom post types for WpRentals
 * 
 * Returns an array of post type slugs and their human-readable labels.
 * These are the core post types used by the real estate theme.
 * 
 * @return array Associative array of post type slug => label pairs
 */
function wprentals_ptc_get_post_types() {
    return array(
        'estate_property'   => __( 'Listings', 'wprentals-core' ),        // Real estate listings
        'estate_booking'    => __( 'Bookings', 'wprentals-core' ),        // Real estate bookings
        'estate_agent'      => __( 'Owners', 'wprentals-core' ),            // Real estate owners
        'wpestate_invoice'  => __( 'Invoices', 'wprentals-core' ),          // Billing invoices
        'membership_package' => __( 'Membership Packages', 'wprentals-core' ), // Subscription plans
        'wpestate_message'  => __( 'Messages', 'wprentals-core' ),          // User messages
        'estate_review'  => __( 'Reviews', 'wprentals-core' ),          // Billing invoices
    );
}

/**
 * Get all available taxonomies organized by post type
 * 
 * Returns a multi-dimensional array where each post type has its associated
 * taxonomies. These are used for categorizing and organizing content.
 * 
 * @return array Multi-dimensional array of post_type => [taxonomy_slug => label]
 */
function wprentals_ptc_get_taxonomies() {
    return array(
        // Taxonomies for property listings
        'estate_property' => array(
            'property_category'           => __( 'Listing Categories', 'wprentals-core' ),     // House, Apartment, etc.
            'property_action_category'    => __( 'Listing Types', 'wprentals-core' ),          // For Sale, For Rent, etc.
            'property_city'               => __( 'Listing City', 'wprentals-core' ),           // Geographic location
            'property_area'               => __( 'Listing Area', 'wprentals-core' ),           // Neighborhood/district
            // 'property_county_state'       => __( 'Property County/State', 'wprentals-core' ),   // Administrative division
            'property_features'           => __( 'Listing Features', 'wprentals-core' ),       // Pool, Garage, etc.
            'property_status'             => __( 'Listing Status', 'wprentals-core' ),         // Available, Sold, etc.
        ),
    
    );
}

/**
 * Generate default options for post type control settings
 * 
 * Creates the initial configuration where all post types and taxonomies 
 * are enabled by default. This ensures the theme works out of the box.
 * 
 * @return array Default settings array with all items enabled
 */
function wprentals_ptc_default_options() {
    // Initialize empty arrays for settings
    $defaults = array(
        'post_types'  => array(),
        'taxonomies'  => array(),
    );

    // Enable all post types by default
    $post_types = wprentals_ptc_get_post_types();
    foreach ( $post_types as $slug => $label ) {
        $defaults['post_types'][ $slug ] = 1; // 1 = enabled
    }

    // Enable all taxonomies by default
    $taxonomies = wprentals_ptc_get_taxonomies();
    foreach ( $taxonomies as $type => $taxes ) {
        foreach ( $taxes as $tax_slug => $tax_label ) {
            $defaults['taxonomies'][ $tax_slug ] = 1; // 1 = enabled
        }
    }

    return $defaults;
}

/**
 * Check if a post type is enabled in settings.
 *
 * @param string $slug Post type slug
 * @return bool True if enabled
 */
function wprentals_ptc_is_post_type_enabled( $slug ) {
    $options = get_option( 'wprentals_ptc_settings', wprentals_ptc_default_options() );
    if ( isset( $options['post_types'][ $slug ] ) && ! $options['post_types'][ $slug ] ) {
        return false;
    }
    return true;
}

/**
 * Check if a taxonomy is enabled in settings.
 *
 * @param string $slug Taxonomy slug
 * @return bool True if enabled
 */
function wprentals_ptc_is_taxonomy_enabled( $slug ) {
    $options = get_option( 'wprentals_ptc_settings', wprentals_ptc_default_options() );
    if ( isset( $options['taxonomies'][ $slug ] ) && ! $options['taxonomies'][ $slug ] ) {
        return false;
    }
    return true;
}

/**
 * Handle form submission and save post type control settings
 * 
 * Processes the admin form data, validates the nonce for security,
 * and updates the WordPress options table with the new settings.
 */
function wprentals_ptc_save() {
    // Security check: verify nonce and user capability
    if ( isset( $_POST['wprentals_ptc_nonce'] ) && wp_verify_nonce( $_POST['wprentals_ptc_nonce'], 'wprentals_ptc_save' ) ) {
        
        // Get current options or defaults if none exist
        $options = get_option( 'wprentals_ptc_settings', wprentals_ptc_default_options() );
        
        $current_tab = 'post-types';
        if ( ( isset( $_GET['page'] ) && 'wprentals-post-type-control-taxonomies' === $_GET['page'] ) ||
            ( isset( $_GET['tab'] ) && 'taxonomies' === $_GET['tab'] ) ) {
            $current_tab = 'taxonomies';
        }

        if ( 'post-types' === $current_tab ) {
            $post_types = wprentals_ptc_get_post_types();
            foreach ( $post_types as $slug => $label ) {
                $options['post_types'][ $slug ] = isset( $_POST['post_types'][ $slug ] );
            }
        }

        if ( 'taxonomies' === $current_tab ) {
            $taxonomies = wprentals_ptc_get_taxonomies();
            foreach ( $taxonomies as $type => $taxes ) {
                foreach ( $taxes as $tax_slug => $tax_label ) {
                    $options['taxonomies'][ $tax_slug ] = isset( $_POST['taxonomies'][ $tax_slug ] );
                }
            }
        }

        // Save updated options to database
        update_option( 'wprentals_ptc_settings', $options );
        flush_rewrite_rules();
        
        // Show success message to user
        add_settings_error( 'wprentals_ptc', 'settings_updated', __( 'Settings saved.', 'wprentals-core' ), 'updated' );
    }
}

/**
 * Render the main post type control admin page
 * 
 * Creates the tabbed interface for managing post types and taxonomies.
 * Handles different tabs and renders the appropriate content for each.
 */

function wprentals_ptc_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $wprentals_branding = wprentals_theme_branding(); // Uses the filter system
    //   WPESTATE_PLUGIN_DIR_URL . '/img/rentals_icon.png', 
    $menu_icon = wprentals_get_theme_branding_logo_url(); // Just returns the URL

    $options    = get_option( 'wprentals_ptc_settings', wprentals_ptc_default_options() );
    $post_types = wprentals_ptc_get_post_types();
    $taxonomies = wprentals_ptc_get_taxonomies();
    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'post-types';

    settings_errors( 'wprentals_ptc' );

    echo '<div class="wrap wprentals2025-content-wrapper ">';
    echo '<div class="wprentals2025-content-wrapper-header"> ' . $wprentals_branding . esc_html__( ' Site Settings', 'wprentals-core' ) . '</div>';
    echo '<hr>';
    echo '<div class="wprentals2025-content-wrapper-inside-box"> ';

        echo '<div class="wprentals2025-content-container">';

            echo '<div class="wprentals-nav-tab-wrapper nav-tab-wrapper">';
            // echo '<a href="' . admin_url( 'admin.php?page=wprentals-post-type-control&tab=post-types' ) . '" class="nav-tab ' . ( $active_tab === 'post-types' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Post Types', 'wprentals-core' ) . '</a>';
            // echo '<a href="' . admin_url( 'admin.php?page=wprentals-post-type-control&tab=taxonomies' ) . '" class="nav-tab ' . ( $active_tab === 'taxonomies' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Taxonomies', 'wprentals-core' ) . '</a>';
            echo '<a href="' . admin_url( 'admin.php?page=wprentals-post-type-control-plugins' ) . '" class="nav-tab ' . ( $active_tab === 'plugins' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Plugins', 'wprentals-core' ) . '</a>';
            echo '<a href="' . admin_url( 'admin.php?page=wprentals-post-type-control-feedback' ) . '" class="nav-tab ' . ( $active_tab === 'feedback' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Feedback', 'wprentals-core' ) . '</a>';
            // echo '<a href="' . admin_url( 'admin.php?page=wprentals-post-type-control-import-locations' ) . '" class="nav-tab ' . ( $active_tab === 'import-locations' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'Import Locations', 'wprentals-core' ) . '</a>';
            echo '<a href="' . admin_url( 'admin.php?page=wprentals-post-type-control-white-label' ) . '" class="nav-tab ' . ( $active_tab === 'white-label' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'White Label', 'wprentals-core' ) . '</a>';
            echo '<a href="' . admin_url( 'admin.php?page=wprentals-post-type-control-license&tab=license' ) . '" class="nav-tab ' . ( $active_tab === 'license' ? 'nav-tab-active' : '' ) . '">' . esc_html__( 'WpRentals License', 'wprentals-core' ) . '</a>';
            echo '</div>';

            echo '<hr>';

            if ( in_array( $active_tab, array( 'post-types', 'taxonomies' ), true ) ) {
                echo '<form method="post">';
                wp_nonce_field( 'wprentals_ptc_save', 'wprentals_ptc_nonce' );
                if ( 'taxonomies' === $active_tab ) {
                    wprentals_ptc_render_taxonomies_tab( $taxonomies, $post_types, $options );
                } else {
                    wprentals_ptc_render_post_types_tab( $post_types, $options );
                }
              submit_button(
                esc_html__( 'Save Changes', 'wprentals-core' ),   // $text
                'primary wprentals_button',             // $type — built-in + your class
                'submit',                                  // $name
                true,                                      // $wrap
                array( 'id' => 'submit' )                  // $other_attributes — only use for id, tabindex, etc.
                );



                echo '</form>';
            } elseif ( 'plugins' === $active_tab ) {
                wprentals_ptc_render_plugins_tab();
            } elseif ( 'feedback' === $active_tab ) {
                wprentals_ptc_render_feedback_tab();
            } elseif ( 'import-locations' === $active_tab ) {
                wprentals_ptc_render_import_locations_tab();
            } elseif ( 'white-label' === $active_tab ) {
                wprentals_ptc_render_white_label_tab();
            } elseif ( 'license' === $active_tab ) {
                wprentals_ptc_render_license_tab();
            }

        echo '</div>';
    echo '</div>';
    echo '</div>';
}

/** Wrapper for Taxonomies tab */
function wprentals_ptc_page_taxonomies() {
    $_GET['tab'] = 'taxonomies';
    wprentals_ptc_page();
}

/** Wrapper for Plugins tab */
function wprentals_ptc_page_plugins() {
    $_GET['tab'] = 'plugins';
    wprentals_ptc_page();
}

/** Wrapper for Feedback tab */
function wprentals_ptc_page_feedback() {
    $_GET['tab'] = 'feedback';
    wprentals_ptc_page();
}

/** Wrapper for Import Locations tab */
function wprentals_ptc_page_import_locations() {
    $_GET['tab'] = 'import-locations';
    wprentals_ptc_page();
}

/** Wrapper for White Label tab */
function wprentals_ptc_page_white_label() {
    $_GET['tab'] = 'white-label';
    wprentals_ptc_page();
}

/** Wrapper for License tab */
function wprentals_ptc_page_license() {
    $_GET['tab'] = 'license';
    wprentals_ptc_page();
}

/**
 * Apply the post type and taxonomy settings by unregistering disabled items
 * 
 * This function runs on the 'init' hook and unregisters any post types or 
 * taxonomies that have been disabled in the admin settings. This effectively
 * removes them from the WordPress admin and frontend.
 * 
 * Note: unregister_post_type() and unregister_taxonomy() were added in WP 4.5
 */
function wprentals_ptc_apply() {
    // Post types and taxonomies are now registered conditionally, so nothing to do here.
}

/**
 * Render the main settings page (parent menu page)
 * 
 * This is a simple landing page that directs users to choose from
 * the available submenu options. Currently just shows basic info.
 */
function wprentals_settings_main_page() {
    // Security check: ensure user has proper permissions
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // Simple main page with basic information
    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'WpRentals Settings', 'wprentals-core' ) . '</h1>';
    echo '<p>' . esc_html__( 'Select an option from the submenu.', 'wprentals-core' ) . '</p>';
    echo '</div>';
}
