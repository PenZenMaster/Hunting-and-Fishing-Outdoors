<?php
/**
 * WPRentals User Role Management
 * This file handles the creation, modification, and removal of custom user roles
 * and their associated capabilities with comprehensive security measures.
 *
 * @package WPRentals Core
 * @subpackage User
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define role constants
define('WPRENTALS_ROLE_OWNER', 'owner');
define('WPRENTALS_ROLE_RENTER', 'renter');




/**
 * Creates the custom user roles: Owner and Renter with proper security measures
 * Runs on plugin activation
 *
 * @return void
 */
add_action('wpestate_rentals_plugin_activated', 'wprentals_create_custom_roles');
if(!function_exists('wprentals_create_custom_roles')):
    function wprentals_create_custom_roles() {
      

        global $wp_roles;
        if (empty($wp_roles) || !($subscriber = get_role('subscriber'))) {
            return;
        }

        // Rate limiting check
        if (!wprentals_check_role_modification_limit(get_current_user_id())) {
            return;
        }

        do_action('wprentals_before_roles_creation');

        // Start with base subscriber capabilities
        $custom_capabilities = wprentals_sanitize_capabilities($subscriber->capabilities);
        
        // Define custom post types
        $custom_post_types = wprentals_get_role_post_types();


        // Add capabilities for each post type
        foreach ($custom_post_types as $post_type) {
            $type_caps = array(
                "edit_{$post_type}s"             => true,
                "edit_published_{$post_type}s"   => true,
                "edit_others_{$post_type}s"      => true,
                "publish_{$post_type}s"          => true,
                "delete_{$post_type}s"           => true,
                "delete_published_{$post_type}s" => true,
            );
            
            $custom_capabilities = array_merge(
                $custom_capabilities,
                wprentals_sanitize_capabilities($type_caps)
            );
        }

        // Remove estate agent editing capability
        unset($custom_capabilities['edit_others_estate_agents']);
        
        // Create roles if they don't exist
        foreach (array(WPRENTALS_ROLE_OWNER, WPRENTALS_ROLE_RENTER) as $role_name) {
            if (!get_role($role_name)) {
                $role = add_role($role_name, ucfirst($role_name), array());
                if ($role) {
                    foreach ($custom_capabilities as $cap => $grant) {
                        $role->add_cap($cap, $grant);
                    }
                    /*
                    error_log(sprintf(
                        'WPRentals: Created role "%s" with capabilities by user %d',
                        $role_name,
                        get_current_user_id()
                    ));
                    */
                }
            }
        }

        do_action('wprentals_after_roles_creation');
    }
endif;

/**
 * Removes user roles and cleans up associated capabilities
 * Runs on plugin deactivation
 *
 * @return void
 */
add_action('wpestate_rentals_plugin_deactivate', 'wprentals_remove_user_role');
if(!function_exists('wprentals_remove_user_role')):
    function wprentals_remove_user_role() {
    

        // Rate limiting check
        if (!wprentals_check_role_modification_limit(get_current_user_id())) {
            return;
        }

        do_action('wprentals_before_roles_removal');

        $roles = array(WPRENTALS_ROLE_RENTER, WPRENTALS_ROLE_OWNER);
        foreach ($roles as $role) {
            $role_obj = get_role($role);
            if ($role_obj) {
                // Remove each capability before removing role
                foreach (array_keys($role_obj->capabilities) as $cap) {
                    $role_obj->remove_cap($cap);
                }
                remove_role($role);
                /*
                error_log(sprintf(
                    'WPRentals: Removed role "%s" with all capabilities by user %d',
                    $role,
                    get_current_user_id()
                ));
                */
            }
        }
        
        // Clean up user meta
        global $wpdb;
        foreach ($roles as $role) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $wpdb->usermeta 
                     WHERE meta_key = %s 
                     AND meta_value = %s",
                    $wpdb->prefix . 'capabilities',
                    $role
                )
            );
        }

        do_action('wprentals_after_roles_removal');
    }
endif;




/**
 * Assigns a role to a new user during registration process
 * 
 * @param int $user_id Newly created user ID
 * @param string $role Role to assign
 * @return bool True on success, false otherwise
 */
if(!function_exists('wprentals_register_user_role')):
    function wprentals_register_user_role($user_id, $role) {
        // Input validation
        if (!is_numeric($user_id) || empty($role)) {
            return false;
        }

        // Sanitize inputs
        $user_id = absint($user_id);
        $role = sanitize_key($role);

        // Verify role exists and is allowed for registration
        $allowed_roles = array('owner', 'renter');
        if (!get_role($role) || !in_array($role, $allowed_roles, true)) {
            return false;
        }

        // Get user
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return false;
        }
        
        
        // Remove ALL existing roles first
        $existing_roles = (array) $user->roles;
        foreach ($existing_roles as $existing_role) {
            $user->remove_role($existing_role);
        }

        // Assign role
        $user->add_role($role);
        
        // Log assignment
        /*
        error_log(sprintf(
            'WPRentals: Role "%s" assigned to new user %d during registration',
            $role,
            $user_id
        ));
        */
        return true;
    }
endif;





/**
 * Recovery function to fix corrupted role capabilities
 * Should be called from admin only
 *
 * @return void
 */
if(!function_exists('wprentals_repair_role_capabilities')):
    function wprentals_repair_role_capabilities() {
        // Verify admin privileges and nonce
        if (!current_user_can('manage_options') || 
            !check_admin_referer('wprentals_repair_roles', 'repair_nonce')) {
            return;
        }

        // Get all custom roles
        $roles = array(WPRENTALS_ROLE_OWNER, WPRENTALS_ROLE_RENTER);
        
        foreach ($roles as $role_name) {
            $role = get_role($role_name);
            if (!$role) {
                continue;
            }

            // Remove all existing capabilities
            $existing_caps = array_keys($role->capabilities);
            foreach ($existing_caps as $cap) {
                $role->remove_cap($cap);
            }
        }

        // Recreate roles with proper capabilities
        wprentals_create_custom_roles();
        /*
        error_log(sprintf(
            'WPRentals: Role capabilities repaired by user %d',
            get_current_user_id()
        ));
        */
    }
endif;





/**
 * Register deactivation hook to ensure proper cleanup
 */
register_deactivation_hook(__FILE__, 'wprentals_remove_user_role');

/**
 * Ensure proper nonce field is output in admin forms
 */
add_action('admin_footer', 'wprentals_output_role_nonce');
if(!function_exists('wprentals_output_role_nonce')):
    function wprentals_output_role_nonce() {
        if (current_user_can('edit_users')) {
            wp_nonce_field('wprentals_role_assignment', 'role_nonce');
        }
    }
endif;

