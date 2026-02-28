<?php 
/**
 * WPRentals User Role Assignment
 * Handles assigning and checking user roles
 * 
 * @package WPRentals Core
 * @subpackage User
 * @since 4.0.0
 */

 
/**
 * Safely assigns a role to a user
 *
 * @param int    $user_id The ID of the user
 * @param string $role    The role to assign
 * @return bool  True if successful, false otherwise
 */
if(!function_exists('wprentals_core_set_role_to_user')):
    function wprentals_core_set_role_to_user($user_id, $role) {
        // Check nonce
        if (!check_admin_referer('wprentals_role_assignment', 'role_nonce')) {
            return false;
        }

        // Check rate limit
        if (!wprentals_check_role_modification_limit(get_current_user_id())) {
            return false;
        }

        // Input validation
        if (!is_numeric($user_id) || empty($role)) {
            return false;
        }

        // Security checks
        if (!current_user_can('edit_users')) {
            return false;
        }

        // Sanitize inputs
        $user_id = absint($user_id);
        $role = sanitize_key($role);

        // Verify role exists
        if (!get_role($role)) {
            return false;
        }

        // Prevent privilege escalation
        if (!current_user_can('promote_users') && $role === 'administrator') {
            error_log(sprintf(
                'WPRentals: Unauthorized attempt to assign administrator role by user %d',
                get_current_user_id()
            ));
            return false;
        }

        do_action('wprentals_before_role_assignment', $user_id, $role);

        $user = get_user_by('id', $user_id);
        if ($user) {
            $user->add_role($role);
          
            do_action('wprentals_after_role_assignment', $user_id, $role);
            return true;
        }
        return false;
    }
endif;



/**
 * Checks if a user has a specific role
 *
 * @param int    $user_id The ID of the user
 * @param string $role    The role to check
 * @return bool  True if user has the role, false otherwise
 */
if(!function_exists('wprentals_core_user_has_role')):
    function wprentals_core_user_has_role($user_id, $role) {
        // Sanitize inputs
        $user_id = absint($user_id);
        $role = sanitize_key($role);
        
        // Get user
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return false;
        }
        
        // Check role
        return in_array($role, (array) $user->roles, true);
    }
endif;

if(!function_exists('wprentals_core_user_is_renter_on_separation')):
    function wprentals_core_user_is_renter_on_separation($user_id) {

        if(esc_html(wprentals_get_option('wp_estate_separate_users', '')) == 'yes'){
            if( wprentals_core_user_has_role($user_id, 'renter') ){
                return true;
            }


        }
        return false;

    }
endif;