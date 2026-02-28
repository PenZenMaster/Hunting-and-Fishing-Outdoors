<?php 
/**
 * WPRentals Capability Management
 * Handles user capability updates, checks, and state management
 * 
 * @package WPRentals Core
 * @subpackage User
 * @since 4.0.0
 */

function wprentals_update_owner_caps() {
    if (!defined('WPRENTALS_ROLE_OWNER')) {
        error_log('WPRENTALS_ROLE_OWNER constant is not defined');
        return;
    }
    
    $owner = get_role(WPRENTALS_ROLE_OWNER);
    if (!$owner) {
        error_log('Owner role not found: ' . WPRENTALS_ROLE_OWNER);
        return;
    }
    
    $post_types = array(
        'estate_property',
        'wpestate_invoice',
        'estate_agent',
        'wpestate_message',
        'wpestate_booking'
    );
    
    foreach ($post_types as $post_type) {
        // Determine the correct plural suffix for each post type
        if ($post_type === 'estate_property') {
            $plural_base = 'estate_properties';
        } else {
            $plural_base = $post_type . 's';  // Normal plural for other types
        }
        
        $capabilities = array(
            // Singular forms
            "edit_{$post_type}",
            "read_{$post_type}",
            "delete_{$post_type}",
            "publish_{$post_type}",
            
            // Plural forms using the correct base
            "edit_{$plural_base}",
            "edit_published_{$plural_base}",
            "edit_others_{$plural_base}",
            "edit_private_{$plural_base}",
            "publish_{$plural_base}",
            "delete_{$plural_base}",
            "delete_published_{$plural_base}",
            "delete_private_{$plural_base}",
            "delete_others_{$plural_base}",
            "read_private_{$plural_base}",
            "create_{$plural_base}"
        );
        
        foreach ($capabilities as $cap) {
            $owner->add_cap($cap);
        }
    }
}

// Change the hook priority to ensure role exists
add_action('init', 'wprentals_update_owner_caps', 999);






/**
 * Initialize capability management hooks
 */
function wprentals_init_capability_management() {
    // Remove the old init hook
    remove_action('init', 'wprentals_core_modifies_renter_caps', 31);

    // Add hooks for capability management
    add_action('update_option_wp_estate_separate_users', 'wprentals_update_renter_caps', 10, 2);
    add_action('admin_init', 'wprentals_check_capability_updates');


    // Add activation hook to set initial state
    register_activation_hook(__FILE__, 'wprentals_update_capability_state');
}

// Initialize the improved capability management
add_action('plugins_loaded', 'wprentals_init_capability_management');





 /**
 * Store the current state of renter capabilities
 * @return void
 */
function wprentals_update_capability_state() {
    $separate_users = wprentals_get_option('wp_estate_separate_users', '');
    update_option('wprentals_renter_caps_state', $separate_users, false);
}


add_action('init', function() {
    $user = wp_get_current_user();
    if (in_array('renter', (array)$user->roles)) {
        //error_log('Renter Capabilities:');
        //error_log(print_r($user->allcaps, true));
        
        // Specifically check publish capability
        //error_log('Can publish property: ' . (current_user_can('publish_estate_property') ? 'yes' : 'no'));
        //error_log('Can publish properties: ' . (current_user_can('publish_estate_properties') ? 'yes' : 'no'));
    }
});




/**
 * Update renter capabilities when the separate_users setting changes
 * @param mixed $old_value Previous setting value
 * @param mixed $new_value New setting value
 * @return void
 */
function wprentals_update_renter_caps($old_value = '', $new_value = '') {
    // Verify we have a valid role
    $renter = get_role(WPRENTALS_ROLE_RENTER);
    if (!$renter) {
        return;
    }

    // Get the new state if not provided
    if (empty($new_value)) {
        $new_value = wprentals_get_option('wp_estate_separate_users', '');
    }

    $custom_post_types = array(
        'estate_property',
        'wpestate_invoice',
        'estate_agent',
        'wpestate_message',
        'wpestate_booking'
    );

    foreach ($custom_post_types as $post_type) {
        // Validate post type name
        $post_type = sanitize_key($post_type);
        
        if ($post_type === 'estate_property') {
            $plural_base = 'estate_properties';
        } else {
            $plural_base = $post_type . 's';  // Normal plural for other types
        }

        // Define capabilities based on post type
        $capabilities = array();

        if ($new_value === 'yes') {
            // Restricted mode - specific capabilities per post type
            switch ($post_type) {
                case 'estate_property':
                    // Only view capabilities for properties
                    $capabilities = array(
                        "read_{$post_type}"
                    );
                    break;

                case 'wpestate_invoice':
                    // View and edit capabilities for invoices
                    $capabilities = array(
                        "read_{$post_type}",
                        "edit_{$post_type}",
                        "edit_{$plural_base}"
                    );
                    break;

                case 'wpestate_booking':
                case 'wpestate_message':
                case 'estate_agent':
                    // Full capabilities for booking, message, and agent
                    $capabilities = array(
                        // Singular forms
                        "edit_{$post_type}",
                        "read_{$post_type}",
                        "delete_{$post_type}",
                        "publish_{$post_type}",
                        
                        // Plural forms
                        "edit_{$plural_base}",
                        "edit_published_{$plural_base}",
                        "edit_others_{$plural_base}",
                        "edit_private_{$plural_base}",
                        "publish_{$plural_base}",
                        "delete_{$plural_base}",
                        "delete_published_{$plural_base}",
                        "delete_private_{$plural_base}",
                        "delete_others_{$plural_base}",
                        "read_private_{$plural_base}",
                        "create_{$plural_base}"
                    );
                    break;
            }

            // Remove all capabilities first
            $all_capabilities = array(
                // Singular forms
                "edit_{$post_type}",
                "read_{$post_type}",
                "delete_{$post_type}",
                "publish_{$post_type}",
                
                // Plural forms
                "edit_{$plural_base}",
                "edit_published_{$plural_base}",
                "edit_others_{$plural_base}",
                "edit_private_{$plural_base}",
                "publish_{$plural_base}",
                "delete_{$plural_base}",
                "delete_published_{$plural_base}",
                "delete_private_{$plural_base}",
                "delete_others_{$plural_base}",
                "read_private_{$plural_base}",
                "create_{$plural_base}"
            );

            // Remove all capabilities first
            foreach ($all_capabilities as $cap) {
                if (wprentals_is_valid_capability($cap)) {
                    $renter->remove_cap($cap);
                }
            }

            // Then add only the allowed ones
            foreach ($capabilities as $cap) {
                if (wprentals_is_valid_capability($cap)) {
                    $renter->add_cap($cap);
                }
            }
        } else {
            // Not restricted - add all capabilities
            $capabilities = array(
                // Singular forms
                "edit_{$post_type}",
                "read_{$post_type}",
                "delete_{$post_type}",
                "publish_{$post_type}",
                
                // Plural forms
                "edit_{$plural_base}",
                "edit_published_{$plural_base}",
                "edit_others_{$plural_base}",
                "edit_private_{$plural_base}",
                "publish_{$plural_base}",
                "delete_{$plural_base}",
                "delete_published_{$plural_base}",
                "delete_private_{$plural_base}",
                "delete_others_{$plural_base}",
                "read_private_{$plural_base}",
                "create_{$plural_base}"
            );

            foreach ($capabilities as $cap) {
                if (wprentals_is_valid_capability($cap)) {
                    $renter->add_cap($cap);
                }
            }
        }
    }

    // Update the stored state
    wprentals_update_capability_state();
}


/**
 * Check if capabilities need to be updated during admin init
 * @return void
 */

function wprentals_check_capability_updates() {
    if (!is_admin()) {
        return;
    }
    
    // Use transient to prevent frequent checks
    $cache_key = 'wprentals_cap_check_' . get_current_user_id();
    if (false !== get_transient($cache_key)) {
        return;
    }
    
    $current_state = get_option('wprentals_renter_caps_state', '');
    $separate_users = wprentals_get_option('wp_estate_separate_users', '');
    
    if ($current_state !== $separate_users) {
        wprentals_update_renter_caps($current_state, $separate_users);
    }
    
    // Cache the check for 5 minutes
    set_transient($cache_key, true, 6 * HOUR_IN_SECONDS);
}

/**
 * Update capabilities during plugin activation
 */
function wprentals_activate_capability_updates() {
    wprentals_update_renter_caps();
}
register_activation_hook(__FILE__, 'wprentals_activate_capability_updates');





/**
 * Define default manageable post types for user roles
 *
 * @return array Array of post types that roles can manage
 */
if (!function_exists('wprentals_get_role_post_types')):
    function wprentals_get_role_post_types() {
        $default_post_types = array(
            'estate_property',
            'wpestate_booking',
            'wpestate_invoice',
            'wpestate_message',
            'wpestate_search',
            'estate_agent'
        );

        // Allow developers to modify the post types
        return apply_filters('wprentals_role_post_types', $default_post_types);
    }
endif;



/**
 * Filter to display the admin bar only for administrators.
 *
 * This filter uses the 'show_admin_bar' hook to determine whether the admin bar
 * should be displayed. It allows the admin bar to be shown only if the current
 * user has the 'administrator' capability.
 *
 * @param bool $show Whether to show the admin bar.
 * @return bool True if the current user is an administrator, false otherwise.
 */
add_filter('show_admin_bar', function($show) {
    return current_user_can('administrator');
});


/**
 * Restrict access to the WordPress admin area to administrators.
 *
 * This action uses the 'admin_init' hook to check the current user's role and
 * redirects non-administrator users to the homepage or another specified page.
 * This ensures only administrators can access the WordPress admin panel.
 *
 * @action admin_init
 * @return void
 */
add_action('admin_init', function () {
    // Array of roles that should have admin access
    $allowed_roles = array('administrator', 'editor', 'author');
    
    // Get current user
    $user = wp_get_current_user();
    $user_roles = (array) $user->roles;
    
    // Check if user has any allowed role
    $has_access = array_intersect($allowed_roles, $user_roles);
    
    // If user doesn't have any allowed role and it's not an AJAX request
    if (empty($has_access) && !defined('DOING_AJAX')) {
        wp_redirect(home_url());
        exit;
    }
});