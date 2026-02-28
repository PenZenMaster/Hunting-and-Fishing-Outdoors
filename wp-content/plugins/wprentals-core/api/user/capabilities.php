<?php
/**
 * WPRentals Capabilities Management
 * This file handles the validation, sanitization, and management of capabilities
 * for the WPRentals custom roles.
 *
 * @package WPRentals Core
 * @subpackage User
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Validates if a capability name is legitimate and allowed
 *
 * @param string $capability The capability to validate
 * @return bool Whether the capability is valid
 */
if (!function_exists('wprentals_is_valid_capability')):
    function wprentals_is_valid_capability($capability) {
        // Basic sanitization
        $capability = sanitize_key($capability);
        
        // List of valid custom capabilities
        $valid_capabilities = array(
            // Property capabilities
            'edit_estate_property', 'read_estate_property', 'delete_estate_property',
            'edit_estate_properties', 'edit_others_estate_properties',
            'publish_estate_properties', 'read_private_estate_properties',
            'create_estate_properties', 'delete_estate_properties',
            'delete_private_estate_properties', 'delete_published_estate_properties',
            'delete_others_estate_properties', 'edit_private_estate_properties',
            'edit_published_estate_properties',
            
            // Booking capabilities
            'edit_wpestate_booking', 'read_wpestate_booking', 'delete_wpestate_booking',
            'edit_wpestate_bookings', 'publish_wpestate_bookings',
            
            // Invoice capabilities
            'edit_wpestate_invoice', 'read_wpestate_invoice', 'delete_wpestate_invoice',
            'edit_wpestate_invoices', 'publish_wpestate_invoices',
            
            // Message capabilities
            'edit_wpestate_message', 'read_wpestate_message', 'delete_wpestate_message',
            'edit_wpestate_messages', 'publish_wpestate_messages',
            
            // Search capabilities
            'edit_wpestate_search', 'read_wpestate_search', 'delete_wpestate_search',
            'edit_wpestate_searches', 'publish_wpestate_searches',
            
            // Agent capabilities
            'edit_estate_agent', 'read_estate_agent', 'delete_estate_agent',
            'edit_estate_agents', 'publish_estate_agents',
            
            // Taxonomy capabilities
            'manage_property_categories', 'edit_property_categories',
            'delete_property_categories', 'assign_property_categories',
            'manage_property_features', 'edit_property_features',
            'delete_property_features', 'assign_property_features',
            'manage_property_action_categories', 'edit_property_action_categories',
            'delete_property_action_categories', 'assign_property_action_categories',
            'manage_property_cities', 'edit_property_cities',
            'delete_property_cities', 'assign_property_cities',
            'manage_property_areas', 'edit_property_areas',
            'delete_property_areas', 'assign_property_areas',
            'manage_property_status', 'edit_property_status',
            'delete_property_status', 'assign_property_status'
        );
        
        // Check if capability is in our whitelist
        if (in_array($capability, $valid_capabilities, true)) {
            return true;
        }
        
        // Check if it's a valid WordPress core capability
        $wp_roles = wp_roles();
        foreach ($wp_roles->roles as $role) {
            if (isset($role['capabilities'][$capability])) {
                return true;
            }
        }
        
        return false;
    }
endif;




/**
 * Sanitizes and validates an array of capabilities
 *
 * @param array $capabilities Array of capabilities to validate
 * @return array Sanitized and validated capabilities
 */
if (!function_exists('wprentals_sanitize_capabilities')):
    function wprentals_sanitize_capabilities($capabilities) {
        if (!is_array($capabilities)) {
            return array();
        }

        $sanitized = array();
        foreach ($capabilities as $cap => $grant) {
            $cap = sanitize_key($cap);
            
            if (wprentals_is_valid_capability($cap)) {
                $sanitized[$cap] = (bool) $grant;
            } else {
                error_log(sprintf(
                    'WPRentals: Invalid capability "%s" attempted to be assigned',
                    esc_html($cap)
                ));
            }
        }
        
        return $sanitized;
    }
endif;


/**
 * Rate limiting for role modifications
 *
 * @param int $user_id User ID attempting the modification
 * @return bool Whether the action should be allowed
 */
if (!function_exists('wprentals_check_role_modification_limit')):
    function wprentals_check_role_modification_limit($user_id) {
        $count = get_transient('wprentals_role_mod_count_' . $user_id);
        
        if ($count > 100) { // Max 10 modifications per hour
            error_log(sprintf(
                'WPRentals: Rate limit exceeded for user %d',
                $user_id
            ));
            return false;
        }
        
        set_transient('wprentals_role_mod_count_' . $user_id, ($count ? $count + 1 : 1), HOUR_IN_SECONDS);
        return true;
    }
endif;


/**
 * Verifies if current user can modify roles
 * Helper function to centralize permission checks
 *
 * @return bool True if user can modify roles, false otherwise
 */
if(!function_exists('wprentals_can_modify_roles')):
    function wprentals_can_modify_roles() {
        // Check basic permissions
        if (!current_user_can('edit_users')) {
            error_log(sprintf(
                'WPRentals: Unauthorized role modification attempt by user %d',
                get_current_user_id()
            ));
            return false;
        }

        // Check rate limiting
        if (!wprentals_check_role_modification_limit(get_current_user_id())) {
            error_log(sprintf(
                'WPRentals: Rate limit exceeded for role modifications by user %d',
                get_current_user_id()
            ));
            return false;
        }

        return true;
    }
endif;


/**
 * Handle failed role modifications
 *
 * @param string $message Error message
 * @param string $code Error code
 * @return void
 */
if(!function_exists('wprentals_handle_role_error')):
    function wprentals_handle_role_error($message, $code = '') {
        error_log(sprintf(
            'WPRentals Role Error: %s (Code: %s) by user %d',
            $message,
            $code,
            get_current_user_id()
        ));

        if (wp_doing_ajax()) {
            wp_send_json_error(array(
                'message' => $message,
                'code' => $code
            ));
        } else {
            // Store error in transient for admin notice
            set_transient(
                'wprentals_role_error_' . get_current_user_id(),
                array(
                    'message' => $message,
                    'code' => $code
                ),
                60 // Expire after 1 minute
            );
        }
    }
endif;