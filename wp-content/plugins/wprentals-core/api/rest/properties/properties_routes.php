<?php 

// Register REST API routes for 'estate_property'
add_action('rest_api_init', function() {

 
    register_rest_route('wprentals/v1', '/properties', [
        'methods' => 'POST',  // Changed to POST since we're sending JSON body
        'callback' => 'wprentals_get_all_properties',
        'permission_callback' => '__return_true',
    ]);


    register_rest_route('wprentals/v1', '/property/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'wprentals_get_single_property',
        'args' => [
            'response_type' => [
                'default' => 'full',
                'validate_callback' => function ($param) {
                    return in_array($param, ['basic', 'full']);
                }
            ],
            'fields' => [
                'validate_callback' => function ($param) {
                    return is_string($param); // Ensure it's a string (comma-separated)
                }
            ]
        ],
        'permission_callback' => '__return_true',
    ]);
    
    
    register_rest_route('wprentals/v1', '/property/add', [
        'methods' => 'POST',
        'callback' => 'wprentals_create_property',
        'permission_callback' => 'wprentals_check_permissions_for_posting',
    ]);
 
 

    
    register_rest_route('wprentals/v1', '/property/edit/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'wprentals_update_property',
        'permission_callback' => 'wprentals_check_permissions_for_property',
    ]);

  
    register_rest_route('wprentals/v1', '/property/delete/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'wprentals_delete_property',
        'permission_callback' => 'wprentals_check_permissions_for_property',
    ]);
   
});


/*
{
  "meta_property_price": "100000",
  "meta_property_address": "123 Main St",
  "taxonomies_property_category": "apartment,condo"
}



	'estate_property'  => array(
								'taxonomies' => array(
													'property_category',
													'property_action_category', 
													'property_city',
													'property_area',
													'property_features',	
													'property_status'
												),
				
								'meta'       => array(					
													'property_price',
													'local_booking_type',
													'property_label',
													'property_label_before',
													'property_size',
													'property_rooms',
													'property_bedrooms',*/