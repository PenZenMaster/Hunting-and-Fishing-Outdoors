<?php
/**
 * WpRentals Real Estate menu and submenus
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'wprentals_real_estate_menu' );
add_action( 'admin_menu', 'wprentals_remove_default_cpt_menus', 999 );

/**
 * Registers the "WpRentals Real Estate" admin menu with submenus
 */
function wprentals_real_estate_menu() {
    $branding = wprentals_theme_branding();
    $icon     = wprentals_get_theme_branding_logo_url();

    add_menu_page(
        esc_html__( 'Listings, Bookings & More', 'wprentals-core' ),
        esc_html__( 'Listings, Bookings & More', 'wprentals-core' ),
        'manage_options',
        'wprentals-real-estate',
        '',
        $icon,
        4.1
    );

    // Properties list
    add_submenu_page(
        'wprentals-real-estate',
        __( 'Listings', 'wprentals-core' ),
        __( 'Listings', 'wprentals-core' ),
        'manage_options',
        'edit.php?post_type=estate_property'
    );
    
    remove_submenu_page('wprentals-real-estate', 'wprentals-real-estate');

    // Add Property
    // if (post_type_exists('estate_property')) {
    //     add_submenu_page(
    //         'wprentals-real-estate',
    //         __( 'Add Listing', 'wprentals-core' ),
    //         __( 'Add Listing', 'wprentals-core' ),
    //         'manage_options',
    //         'post-new.php?post_type=estate_property'
    //     );
    // }

    $all_taxonomies = wprentals_ptc_get_taxonomies();

    foreach ( $all_taxonomies['estate_property'] as $slug => $name ) {
        add_submenu_page(
            'wprentals-real-estate',
            $name,
            $name,
            'manage_options',
            'edit-tags.php?taxonomy=' . $slug . '&post_type=estate_property'
        );
    }

    // Agents
    if (post_type_exists('estate_agent')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Owners', 'wprentals-core' ),
            __( 'Owners', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=estate_agent'
        );
    }

    // Agencies
    if (post_type_exists('estate_agency')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Agencies', 'wprentals-core' ),
            __( 'Agencies', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=estate_agency'
        );
    }

    // Developers
    if (post_type_exists('estate_developer')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Developers', 'wprentals-core' ),
            __( 'Developers', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=estate_developer'
        );
    }

    // Categories page
    // add_submenu_page(
    //     'wprentals-real-estate',
    //     __( 'Categories', 'wprentals-core' ),
    //     __( 'Categories', 'wprentals-core' ),
    //     'manage_options',
    //     'wprentals-real-estate-categories',
    //     'wprentals_real_estate_categories_page'
    // );

    // Reviews
    if (post_type_exists('estate_review')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Reviews', 'wprentals-core' ),
            __( 'Reviews', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=estate_review'
        );
    }

    // Invoices
    if (post_type_exists('wpestate_booking')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Bookings', 'wprentals-core' ),
            __( 'Bookings', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=wpestate_booking'
        );
    }

    // Invoices
    if (post_type_exists('wpestate_invoice')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Invoices', 'wprentals-core' ),
            __( 'Invoices', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=wpestate_invoice'
        );
    }

    // Membership Packages
    if (post_type_exists('membership_package')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Membership Packages', 'wprentals-core' ),
            __( 'Membership Packages', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=membership_package'
        );
    }

    // Searches
    // if (post_type_exists('wpestate_search')) {
    //     add_submenu_page(
    //         'wprentals-real-estate',
    //         __( 'Searches', 'wprentals-core' ),
    //         __( 'Searches', 'wprentals-core' ),
    //         'manage_options',
    //         'edit.php?post_type=wpestate_search'
    //     );
    // }

    // Messages
    if (post_type_exists('wpestate_message')) {
        add_submenu_page(
            'wprentals-real-estate',
            __( 'Messages', 'wprentals-core' ),
            __( 'Messages', 'wprentals-core' ),
            'manage_options',
            'edit.php?post_type=wpestate_message'
        );
    }
}
add_action('admin_menu', 'add_custom_menu_class', 999);

function add_custom_menu_class() {
    global $menu;
    
    // Loop through menu items to find your specific menu
    foreach ($menu as $key => $menu_item) {
        // Check if this is your menu item by slug
        if (isset($menu_item[2]) && $menu_item[2] === 'wprentals-real-estate') {
            // Add custom class to the menu item
            $menu[$key][4] = (isset($menu[$key][4]) ? $menu[$key][4] . ' ' : '') . 'wprentals-real-estate-custom-class';
            break;
        }
    }
}



/**
 * Render the Categories page listing taxonomy links in four columns
 */
function wprentals_real_estate_categories_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $all_taxonomies = wprentals_ptc_get_taxonomies();
    $columns = array(
        'estate_property'  => __( 'Listing Taxonomies', 'wprentals-core' ),
        // 'estate_agent'     => __( 'Agent Taxonomies', 'wprentals-core' ),
        // 'estate_agency'    => __( 'Agency Taxonomies', 'wprentals-core' ),
        // 'estate_developer' => __( 'Developer Taxonomies', 'wprentals-core' ),
    );

    echo '<div class=" wprentals2025-content-wrapper" style="margin-top:15px;">';
    echo '<div class="wprentals2025-content-wrapper-header">' . esc_html__( 'Categories', 'wprentals-core' ) . '</div><hr>';
    
    echo '<div class="wprentals2025-content-wrapper-inside-box" >';
    echo '<div class="wprentals2025-content-container">';
       
    
    echo '<div class="wprentals-admin-categories-wrapper">';
        foreach ( $columns as $type => $label ) {
            // Check if the post type exists
            if ( !post_type_exists( $type ) ) {
                continue;
            }
            
            echo '<div style="flex:1 1 200px;min-width:200px;">';
            echo '<h2>' . esc_html( $label ) . '</h2>';
            if ( isset( $all_taxonomies[ $type ] ) ) {
                echo '<ul>';
                foreach ( $all_taxonomies[ $type ] as $slug => $name ) {
                    // Check if the taxonomy exists
                    if ( taxonomy_exists( $slug ) ) {
                        $link = admin_url( 'edit-tags.php?taxonomy=' . $slug . '&post_type=' . $type );
                        echo '<li><a href="' . esc_url( $link ) . '">' . esc_html( $name ) . '</a></li>';
                    }
                }
                echo '</ul>';
            }
            echo '</div>';
        }
   echo '</div>';   echo '</div>';
    echo '</div></div>';
}

/**
 * Remove the original menu entries for custom post types now shown
 * under the "WpRentals Real Estate" menu.
 */
function wprentals_remove_default_cpt_menus() {
    remove_menu_page( 'edit.php?post_type=estate_property' );
    remove_menu_page( 'edit.php?post_type=estate_agent' );
    // remove_menu_page( 'edit.php?post_type=estate_agency' );
    // remove_menu_page( 'edit.php?post_type=estate_developer' );
    remove_menu_page( 'edit.php?post_type=estate_review' );
    remove_menu_page( 'edit.php?post_type=wpestate_invoice' );
    remove_menu_page( 'edit.php?post_type=membership_package' );
    remove_menu_page( 'edit.php?post_type=wpestate_search' );
    remove_menu_page( 'edit.php?post_type=wpestate_message' );
    remove_menu_page( 'edit.php?post_type=wpestate_booking' );
}
