<?php
/**
 * White Label Support for WpRentals
 *
 * This file contains helper functions that allow users to re-brand
 * the WpRentals theme within the WordPress admin area. The settings
 * are stored in the `wprentals_white_label` option and used to
 * override theme information shown throughout WordPress.
 *
 * @package WpRentals Core
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Initialize white label hooks.
 *
 * Registers settings and filters that update theme branding text
 * displayed in the WordPress dashboard.
 */
function wprentals_white_label_setup() {
    add_action( 'admin_init', 'wprentals_white_label_register_setting' );
    add_filter( 'wp_prepare_themes_for_js', 'wprentals_white_label_set_branding' );
    add_filter( 'update_right_now_text', 'wprentals_white_label_dashboard_text' );

     // Add customizer hiding functionality
    $settings = wprentals_white_label_get_settings();
    if ( true == $settings['hide_themes_customizer'] ) {
        add_action( 'customize_register', 'wprentals_remove_themes_section', 30 );
    }

}
add_action( 'init', 'wprentals_white_label_setup' );


/**
 * Remove themes section from customizer
 */
function wprentals_remove_themes_section( $wp_customize ) {
    // Remove theme switching controls
    $wp_customize->remove_section( 'themes' );
    $wp_customize->remove_control( 'theme_switch' );
    
    // Also try removing the theme selection panel
    $wp_customize->remove_panel( 'themes' );
}


/**
 * Enqueue media uploader for the White Label settings page.
 *
 * Loads WordPress media scripts and the custom uploader logic when the
 * Post Type Control page is displayed. This allows the Screenshot URL and
 * Branding Logo URL fields to use the media library.
 *
 * @param string $hook Current admin page hook suffix.
 */
function wprentals_white_label_enqueue_media( $hook ) {
    // Only load scripts on our post type control page
    if ( isset( $_GET['page'] ) && 'wprentals-post-type-control-white-label' === $_GET['page'] ) {
        wp_enqueue_media();
        wp_enqueue_script(
            'wprentals-white-label',
            WPESTATE_PLUGIN_DIR_URL . 'admin/js/white-label.js',
            array( 'jquery' ),
            '1.0',
            true
        );

        wp_localize_script( 'wprentals-white-label', 'wprentalsWhiteLabel', array(
            'uploadTitle'  => esc_html__( 'Select Image', 'wprentals-core' ),
            'uploadButton' => esc_html__( 'Use this image', 'wprentals-core' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'wprentals_white_label_enqueue_media' );

/**
 * Provide default white label settings.
 *
 * @return array Default values for all branding options.
 */
function wprentals_white_label_default_settings() {
    return array(
        'branding'               => '',
        'name'                   => '',
        'author'                 => '',
        'author_url'             => '',
        'description'            => '',
        'screenshot'             => '',
        'branding_logo'          => '',
        'hide_themes_customizer' => false,
    );
}

/**
 * Retrieve the stored white label settings.
 *
 * Merges user defined values with the defaults so that
 * each option always has a value.
 *
 * @return array Array of branding options.
 */
function wprentals_white_label_get_settings() {
    $defaults = wprentals_white_label_default_settings();
    $options  = get_option( 'wprentals_white_label', array() );
    return wp_parse_args( $options, $defaults );
}

/**
 * Register the settings group for white label options.
 */
function wprentals_white_label_register_setting() {
    register_setting( 'wprentals_white_label', 'wprentals_white_label', 'wprentals_white_label_sanitize' );
}

/**
 * Sanitize and validate input from the settings form.
 *
 * @param array $input Raw form input.
 * @return array Sanitized array to be saved.
 */
function wprentals_white_label_sanitize( $input ) {
    $defaults = wprentals_white_label_default_settings();
    $new      = array();

    foreach ( $defaults as $key => $val ) {
        if ( 'hide_themes_customizer' === $key ) {
            $new[ $key ] = isset( $input[ $key ] ) ? true : false;
        } else {
            $new[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
        }
    }

    return $new;
}

/**
 * Replace theme information with custom branding.
 *
 * @param array $themes Array of themes prepared for JS.
 * @return array Modified themes array.
 */
function wprentals_white_label_set_branding( $themes ) {
    $key = 'wprentals';

    if ( isset( $themes[ $key ] ) ) {
        $branding = wprentals_white_label_get_settings();

        if ( ! empty( $branding['name'] ) ) {
            $themes[ $key ]['name'] = $branding['name'];
        }

        if ( ! empty( $branding['description'] ) ) {
            $themes[ $key ]['description'] = $branding['description'];
        }

        if ( ! empty( $branding['author'] ) ) {
            $author_url                   = empty( $branding['author_url'] ) ? '#' : $branding['author_url'];
            $themes[ $key ]['author']     = $branding['author'];
            $themes[ $key ]['authorAndUri'] = '<a href="' . esc_url( $author_url ) . '">' . $branding['author'] . '</a>';
        }

        if ( ! empty( $branding['screenshot'] ) ) {
            $themes[ $key ]['screenshot'] = array( $branding['screenshot'] );
        }
    }

    return $themes;
}

/**
 * Modify the "At a Glance" dashboard widget to use the white label name.
 *
 * @param string $text Existing widget text containing the theme name.
 * @return string Modified text with custom theme name.
 */
function wprentals_white_label_dashboard_text( $text ) {
    $branding = wprentals_white_label_get_settings();

    if ( is_admin() && 'WpRentals' == wp_get_theme() && ! empty( $branding['name'] ) ) {
        return sprintf( $text, get_bloginfo( 'version', 'display' ), '<a href="themes.php">' . $branding['name'] . '</a>' );
    }

    return $text;
}


if ( ! function_exists( 'wprentals_theme_branding' ) ) {
    /**
     * Retrieve the current theme branding text.
     *
     * Falls back to the default theme name if no branding is set.
     *
     * @return string Branding name for display.
     */
    function wprentals_theme_branding() {
        // Get the white label settings
        $settings = wprentals_white_label_get_settings();
        
        // Use custom branding text if set
        if ( ! empty( $settings['branding'] ) ) {
            $return = $settings['branding'];
        } 
        // Use custom theme name if set
        elseif ( ! empty( $settings['name'] ) ) {
            $return = $settings['name'];
        } 
        // Default fallback
        else {
            $return = esc_html__( 'WpRentals', 'wprentals-core' );
        }
        
        return apply_filters( 'wprentals_theme_branding', $return );
    }
}


/**
 * Get custom branding logo from white label settings.
 *
 * @param string $return Default logo HTML or empty string.
 * @return string Custom logo HTML or default logo HTML.
 */
function wprentals_get_theme_branding_logo( $return ) {
    $settings = wprentals_white_label_get_settings();
    
    if ( ! empty( $settings['branding_logo'] ) ) {
        $return = '<img src="' . esc_url( $settings['branding_logo'] ) . '" >';
    } else {
        $return = '<img src="' . esc_url( WPESTATE_PLUGIN_DIR_URL . '/img/rentals_icon.png' ) . '" >';
    }
    
    return $return;
}

/**
 * Get the URL to the custom branding logo.
 *
 * Falls back to the default theme icon if no logo is set.
 *
 * @return string URL to the branding logo image.
 */
function wprentals_get_theme_branding_logo_url() {
    $settings = wprentals_white_label_get_settings();
    
    if ( ! empty( $settings['branding_logo'] ) ) {
        return $settings['branding_logo'];
    } else {
        return WPESTATE_PLUGIN_DIR_URL . '/img/rentals_icon.png';
    }
}


/**
 * Hide themes menu and customizer sections for non-administrators
 */
function wprentals_hide_themes_menu() {
    $settings = wprentals_white_label_get_settings();
    
    if ( true == $settings['hide_themes_customizer'] && ! current_user_can( 'administrator' ) ) {
        // Remove themes menu from admin
        remove_submenu_page( 'themes.php', 'themes.php' );
        remove_submenu_page( 'themes.php', 'customize.php' );
        
        // Hide customize link in admin bar
        global $wp_admin_bar;
        if ( $wp_admin_bar !== null ) {
            $wp_admin_bar->remove_menu( 'customize' );
        }
    }
}
add_action( 'admin_menu', 'wprentals_hide_themes_menu', 999 );
add_action( 'wp_before_admin_bar_render', 'wprentals_hide_themes_menu' );


/**
 * Output inline CSS for the custom branding logo in the admin area.
 *
 * Adds a style block to the admin head to set the logo as a background image
 * for the Redux group menu.
 */
function wprentals_white_label_logo_inline_css()  {

    $settings = wprentals_white_label_get_settings();
    $brandName = wprentals_theme_branding();
    
    if ( ! empty( $settings['branding_logo'] ) ) {
        echo '<style>
            .redux-group-menu::before {
                background: url("' . esc_url( $settings['branding_logo'] ) . '");
                background-repeat: no-repeat;
                background-size: 40px;
                content: "'.$brandName.'";
                color: #fff;
                font-weight: bold;
                padding-left: 50px;
                align-items: center;
                display: flex;
            }
            #adminmenu li.wp-has-current-submenu .wp-menu-image img, #adminmenu .wp-menu-image img {
                max-width: 100%;
                height: auto;
            }
        </style>';
    }
}
add_action( 'admin_head', 'wprentals_white_label_logo_inline_css' );