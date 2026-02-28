<?php
/**
 * WPRentals Redux Framework Integration
 *
 * Handles the initialization of Redux Framework and related functionality.
 * Also manages Visual Composer updates and demo content.
 *
 * @package    WPRentals
 * @subpackage Core
 * @since      4.0
 * 
 * @uses       Redux Framework
 * @uses       Visual Composer
 * @uses       wprentals_get_option() For fetching theme options
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


/**
 * Removes Redux Framework demo mode and notices
 * 
 * @since 4.0
 * @return void
 */
if (!function_exists('remove_demo')):
function remove_demo() {
    if (class_exists('ReduxFrameworkPlugin')) {
        // Remove demo mode link
        remove_filter('plugin_row_meta', array(
            ReduxFrameworkPlugin::instance(),
            'plugin_metalinks'
        ), null, 2);

        // Remove activation notice
        remove_action('admin_notices', array(
            ReduxFrameworkPlugin::instance(),
            'admin_notices'
        ));
    }
}
endif;

/**
 * Enables automatic updates for Visual Composer
 * Removes license verification for updates when appropriate
 *
 * @since 4.0
 * @return void
 */
if (!function_exists('noo_enable_vc_auto_theme_update')):
function noo_enable_vc_auto_theme_update() {
    if (function_exists('vc_updater')) {
        $vc_updater = vc_updater();
        
        // Remove pre-update filter
        remove_filter('upgrader_pre_download', array(
            $vc_updater,
            'preUpgradeFilter'
        ), 10);

        // Remove update check if license isn't activated
        if (function_exists('vc_license')) {
            if (!vc_license()->isActivated()) {
                remove_filter('pre_set_site_transient_update_plugins', array(
                    $vc_updater->updateManager(),
                    'check_update'
                ), 10);
            }
        }
    }
}
endif;

// Initialize hooks
add_action('redux/loaded', 'remove_demo');
add_action('vc_after_init', 'noo_enable_vc_auto_theme_update');