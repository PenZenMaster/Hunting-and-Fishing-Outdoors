<?php 


// Register routes for wpestate_invoice post type
add_action('rest_api_init', function () {
    // Retrieve all invoices with filtering and pagination
    register_rest_route('wprentals/v1', '/invoices', [
        'methods' => 'POST',  // Using POST since we're sending JSON body for filters
        'callback' => 'wprentals_get_all_invoices',
        'permission_callback' => 'wprentals_check_permissions_all_invoices',
    ]);

    // Retrieve a single invoice
    register_rest_route('wprentals/v1', '/invoice/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_single_invoice',
        'permission_callback' => 'wprentals_check_permissions_get_single_invoice',
        'args' => [
            'id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);

    // Retrieve invoices for a property owner
    register_rest_route('wprentals/v1', '/owner/(?P<user_id>\d+)/invoices', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_owner_invoices',
        'permission_callback' => 'wprentals_check_permissions_invoices_for_user',
        'args' => [
            'user_id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);

    
    // Retrieve invoices for a specific customer
    register_rest_route('wprentals/v1', '/customer/(?P<user_id>\d+)/invoices', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_customer_invoices',
        'permission_callback' => 'wprentals_check_permissions_invoices_for_customer',
        'args' => [
            'user_id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);



    // Create a new invoice
    register_rest_route('wprentals/v1', '/invoice/add', [
        'methods' => 'POST',
        'callback' => 'wprentals_create_invoice',
        'permission_callback' => 'wprentals_check_permissions_create_invoice',
    ]);

    // Update an invoice
    register_rest_route('wprentals/v1', '/invoice/edit/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'wprentals_update_invoice',
        'permission_callback' => 'wprentals_check_permissions_create_invoice',
        'args' => [
            'id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);

    // Delete an invoice
    register_rest_route('wprentals/v1', '/invoice/delete/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'wprentals_delete_invoice',
        'permission_callback' => 'wprentals_check_permissions_delete_invoice',
        'args' => [
            'id' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);


    
});