<?php


// Register routes for wpestate_booking post type
add_action('rest_api_init', function () {
    // Retrieve all bookings
    register_rest_route('wprentals/v1', '/bookings', [
        'methods' => 'POST',
        'callback' => 'wprentals_get_all_bookings',
        'permission_callback' => 'wprentals_check_permissions_all_bookings',
    ]);

    // Retrieve a single booking
    register_rest_route('wprentals/v1', '/booking/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_single_booking',
        'permission_callback' => 'wprentals_check_permissions_get_single_booking',
    ]);

    // Retrieve bookings for a user
    register_rest_route('wprentals/v1', '/renter/(?P<user_id>\d+)/bookings', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_user_bookings',
        'permission_callback' => 'wprentals_check_permissions_bookings_for_user',
    ]);


    // Retrieve bookings for a user
    register_rest_route('wprentals/v1', '/owner/(?P<user_id>\d+)/bookings', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_owner_bookings',
        'permission_callback' => 'wprentals_check_permissions_bookings_for_user',
    ]);


    // Create a new booking
    register_rest_route('wprentals/v1', '/booking/add', [
        'methods' => 'POST',
        'callback' => 'wprentals_create_booking',
        'permission_callback' => 'wprentals_check_permissions_create_booking',
    ]);

    // Retrieve booking availability
    register_rest_route('wprentals/v1', '/booking/availability', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_booking_availability',
        'permission_callback' => '__return_true',
    ]);

    // Estimate booking costs
    register_rest_route('wprentals/v1', '/booking/estimate', [
        'methods' => 'GET',
        'callback' => 'wprentals_estimate_booking_costs',
        'permission_callback' => '__return_true',
    ]);



    // Delete a booking
    register_rest_route('wprentals/v1', '/booking/delete/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'wprentals_delete_booking',
        'permission_callback' => 'wprentals_check_permissions_edit_booking',
    ]);


    // Update a booking
    register_rest_route('wprentals/v1', '/booking/edit/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'wprentals_update_booking',
        'permission_callback' => 'wprentals_check_permissions_edit_booking',
    ]);
    
    
});