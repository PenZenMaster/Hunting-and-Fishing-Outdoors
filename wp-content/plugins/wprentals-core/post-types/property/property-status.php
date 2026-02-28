<?php
/** MILLDONE
 * Custom Post Status for WPRentals
 * src: post-types\property\property-status.php
 * This file registers custom post statuses "expired" and "disabled" for use within the WPRentals theme.
 * These statuses are utilized to control the visibility and manage the lifecycle of various post types,
 * specifically related to properties and memberships.
 *
 * @package WPRentals Core
 * @subpackage Custom Post Status
 * @since 4.0.0
 *
 * @dependencies
 * - WordPress core functions: add_action, register_post_status
 * - WPRentals theme functions and settings
 *
 * Usage:
 * - This file should be included as part of the WPRentals theme to add custom post statuses "expired" and "disabled".
 */

// Hook into WordPress 'init' action to register custom post statuses.
add_action( 'init', 'wpestate_my_custom_post_status' );

// Register custom post statuses 'expired' and 'disabled'.
if( !function_exists('wpestate_my_custom_post_status') ):
    /**
     * Registers custom post statuses for the WPRentals theme.
     *
     * Adds 'expired' and 'disabled' statuses to handle properties and memberships.
     */
    function wpestate_my_custom_post_status() {
        // Register 'expired' post status.
        register_post_status( 'expired', array(
            'label'                     => esc_html__( 'expired', 'wprentals-core' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Membership Expired <span class="count">(%s)</span>', 'Membership Expired <span class="count">(%s)</span>', 'wprentals-core' ),
        ) );

        // Register 'disabled' post status.
        register_post_status( 'disabled', array(
            'label'                     => esc_html__( 'disabled', 'wprentals-core' ),
            'public'                    => false,
            'exclude_from_search'       => true,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Disabled by user <span class="count">(%s)</span>', 'Disabled by user <span class="count">(%s)</span>', 'wprentals-core' ),
        ) );
    }
endif; // End of wpestate_my_custom_post_status function.
?>
