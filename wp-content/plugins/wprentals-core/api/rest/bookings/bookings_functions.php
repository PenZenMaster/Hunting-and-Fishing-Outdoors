<?php 


/**
 * Permission callback to validate access to all invoices.
 *
 * This function ensures that the user requesting access is logged in
 * and has the role of an administrator. It verifies the JWT token
 * and checks user permissions.
 *
 * @return true|WP_Error True if the user has permission, otherwise a WP_Error object.
 */
function wprentals_check_permissions_all_invoices() {
    // Verify the JWT token
    $userID = apply_filters('determine_current_user', null);
    if (!$userID) {
        return new WP_Error(
            'jwt_auth_failed',
            __('Invalid or missing JWT token.'),
            ['status' => 403]
        );
    }
    wp_set_current_user($userID);

    // Fetch the current user details
    $current_user = wp_get_current_user();
    $userID = $current_user->ID;

    // Check if user is logged in and is an administrator
    if (is_user_logged_in() && current_user_can('administrator')) {
        return true;
    }

    return new WP_Error(
        'rest_forbidden', 
        __('You do not have permission to access all invoices.'), 
        ['status' => 403]
    );
}