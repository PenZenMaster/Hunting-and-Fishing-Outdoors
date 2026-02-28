<?php
/** MILLDONE
 * Property Post Type Registration
 * src: post-types\property\property-post-type.php
 * This file handles the registration of the Estate Property custom post type 
 * and its associated taxonomies (category, action, city, area, features, status).
 * It also sets up the required capabilities for managing these post types and taxonomies.
 *
 * @package WPRentals Core
 * @subpackage Property
 * @since 4.0.0
 * 
 * @dependencies
 * - WordPress Core post type and taxonomy functions
 * - WPRentals options (wp_estate_category_main, wp_estate_category_second)
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Ensures proper taxonomy capabilities are set for the administrator role
 * This function runs early in the WordPress init process to set up required permissions
 *
 * @since 4.0.0
 * @return void
 */
add_action('init', 'wprentals_ensure_taxonomy_caps', 0);
if (!function_exists('wprentals_ensure_taxonomy_caps')):
    function wprentals_ensure_taxonomy_caps() {
        $admin = get_role('administrator');
        if ($admin) {

              
            // Add property editing capabilities
            $admin->add_cap('edit_estate_property');
            $admin->add_cap('read_estate_property');
            $admin->add_cap('delete_estate_property');
            $admin->add_cap('edit_estate_properties');
            $admin->add_cap('edit_others_estate_properties');
            $admin->add_cap('publish_estate_properties');
            $admin->add_cap('read_private_estate_properties');
            $admin->add_cap('create_estate_properties');
            $admin->add_cap('delete_estate_properties');
            $admin->add_cap('delete_private_estate_properties');
            $admin->add_cap('delete_published_estate_properties');
            $admin->add_cap('delete_others_estate_properties');
            $admin->add_cap('edit_private_estate_properties');
            $admin->add_cap('edit_published_estate_properties');


            
            // Define taxonomy capabilities array
            $taxonomy_caps = array(
                'manage_property_categories',
                'edit_property_categories',
                'delete_property_categories',
                'assign_property_categories',
                'manage_property_features',
                'edit_property_features',
                'delete_property_features',
                'assign_property_features',
                'manage_property_action_categories',
                'edit_property_action_categories',
                'delete_property_action_categories',
                'assign_property_action_categories',
                'manage_property_cities',
                'edit_property_cities',
                'delete_property_cities',
                'assign_property_cities',
                'manage_property_areas',
                'edit_property_areas',
                'delete_property_areas',
                'assign_property_areas',
                'manage_property_status',
                'edit_property_status',
                'delete_property_status',
                'assign_property_status'
            );

            // Add each capability to admin role
            foreach ($taxonomy_caps as $cap) {
                $admin->add_cap($cap);
            }
        }
    }
endif;







add_action('init', 'wpestate_create_property_type',1);


/**
 * Creates and registers the Estate Property custom post type and its taxonomies
 * This function initializes the property listings functionality in WPRentals
 *
 * @since 4.0.0
 * @return void
 */

 
if (!function_exists('wpestate_create_property_type')):
    function wpestate_create_property_type() {
         if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'estate_property' ) ) {
            return;
        }
        // Get custom permalink structure
        $rewrites = wpestate_safe_rewite();
        $slug = isset($rewrites[0]) ? $rewrites[0] : 'properties';

        // Setup post type labels
        $property_labels = array(
            'name'                  => esc_html__('Listings', 'wprentals-core'),
            'singular_name'         => esc_html__('Listing', 'wprentals-core'),
            'add_new'               => esc_html__('Add New Listing', 'wprentals-core'),
            'add_new_item'          => esc_html__('Add Listing', 'wprentals-core'),
            'edit'                  => esc_html__('Edit', 'wprentals-core'),
            'edit_item'             => esc_html__('Edit Listings', 'wprentals-core'),
            'new_item'              => esc_html__('New Listing', 'wprentals-core'),
            'view'                  => esc_html__('View', 'wprentals-core'),
            'view_item'             => esc_html__('View Listings', 'wprentals-core'),
            'search_items'          => esc_html__('Search Listings', 'wprentals-core'),
            'not_found'             => esc_html__('No Listings found', 'wprentals-core'),
            'not_found_in_trash'    => esc_html__('No Listings found in Trash', 'wprentals-core'),
            'parent'                => esc_html__('Parent Listings', 'wprentals-core')
        );

        // Setup post type capabilities
        $property_capabilities = array(
            'edit_post'              => 'edit_estate_property',
            'read_post'              => 'read_estate_property',
            'delete_post'            => 'delete_estate_property',
            'edit_posts'             => 'edit_estate_properties',
            'edit_others_posts'      => 'edit_others_estate_properties',
            'publish_posts'          => 'publish_estate_properties',
            'read_private_posts'     => 'read_private_estate_properties',
            'create_posts'           => 'create_estate_properties',
            'delete_posts'           => 'delete_estate_properties',
            'delete_private_posts'   => 'delete_private_estate_properties',
            'delete_published_posts' => 'delete_published_estate_properties',
            'delete_others_posts'    => 'delete_others_estate_properties',
            'edit_private_posts'     => 'edit_private_estate_properties',
            'edit_published_posts'   => 'edit_published_estate_properties',
        );

        // Register the main property post type
        register_post_type('estate_property', array(
            'labels'              => $property_labels,
            'public'              => true,
            'has_archive'         => true,
            'rewrite'            => array('slug' => $slug),
            'supports'           => array('title', 'editor', 'thumbnail', 'comments', 'excerpt'),
            'can_export'         => true,
            'register_meta_box_cb' => 'wpestate_add_property_metaboxes',
            'menu_icon'          => WPESTATE_PLUGIN_DIR_URL . '/img/properties.png',
            'map_meta_cap'       => true,
            'capability_type'    => array('estate_property', 'estate_properties'),
            'capabilities'       => $property_capabilities
        ));

        // Setup taxonomy registration parameters
        $taxonomy_args = array(
            'hierarchical' => true,
            'query_var'    => true,
            'capabilities' => array(
                'manage_terms'  => 'manage_property_categories',
                'edit_terms'    => 'edit_property_categories',
                'delete_terms'  => 'delete_property_categories',
                'assign_terms'  => 'assign_property_categories'
            )
        );

        // Get custom category labels
        $category_main_label = stripslashes(esc_html(wprentals_get_option('wp_estate_category_main', '')));
        $category_second_label = stripslashes(esc_html(wprentals_get_option('wp_estate_category_second', '')));

        // Setup category taxonomy labels
        $category_labels = array(
            'name'          => !empty($category_main_label) ? $category_main_label : esc_html__('Categories', 'wprentals-core'),
            'add_new_item'  => !empty($category_main_label) ? esc_html__('Add New', 'wprentals-core') . ' ' . $category_main_label : esc_html__('Add New Listing Category', 'wprentals-core'),
            'new_item_name' => !empty($category_main_label) ? esc_html__('New', 'wprentals-core') . ' ' . $category_main_label : esc_html__('New Listing Category', 'wprentals-core')
        );

        // Register property category taxonomy
        $slug = isset($rewrites[1]) ? $rewrites[1] : 'listings';
        register_taxonomy('property_category', 
            'estate_property', 
            array_merge(
                $taxonomy_args, 
                array(
                    'labels'  => $category_labels,
                    'rewrite' => array('slug' => $slug)
                )
            )
        );

        // Setup action taxonomy labels
        $action_labels = array(
            'name'          => !empty($category_second_label) ? $category_second_label : esc_html__('What do you rent ?', 'wprentals-core'),
            'add_new_item'  => !empty($category_second_label) ? esc_html__('Add New', 'wprentals-core') . ' ' . $category_second_label : esc_html__('Add new option for "What do you rent"', 'wprentals-core'),
            'new_item_name' => !empty($category_second_label) ? esc_html__('New', 'wprentals-core') . ' ' . $category_second_label : esc_html__('Add new option for "What do you rent"', 'wprentals-core')
        );

        // Register property action taxonomy
        $slug = isset($rewrites[2]) ? $rewrites[2] : 'action';
        register_taxonomy('property_action_category',
            'estate_property',
            array_merge(
                $taxonomy_args,
                array(
                    'labels'  => $action_labels,
                    'rewrite' => array('slug' => $slug)
                )
            )
        );

        // Register remaining taxonomies
        register_property_taxonomies($rewrites, $taxonomy_args);
    }
endif;

/**
 * Helper function to register the remaining property taxonomies
 * Extracts taxonomy registration from main function for better organization
 * 
 * @param array $rewrites Custom permalink structure
 * @param array $taxonomy_args Base taxonomy arguments
 * @return void
 */
if (!function_exists('register_property_taxonomies')):
    function register_property_taxonomies($rewrites, $taxonomy_args) {
        
        if ( function_exists( 'wprentals_ptc_is_taxonomy_enabled' ) && wprentals_ptc_is_taxonomy_enabled( 'property_city' ) ) {
        // City taxonomy
        $slug = isset($rewrites[3]) ? $rewrites[3] : 'city';
        register_taxonomy('property_city',
            'estate_property',
            array_merge(
                $taxonomy_args,
                array(
                    'labels'  => array(
                        'name'          => esc_html__('City', 'wprentals-core'),
                        'add_new_item'  => esc_html__('Add New City', 'wprentals-core'),
                        'new_item_name' => esc_html__('New City', 'wprentals-core')
                    ),
                    'rewrite' => array('slug' => $slug)
                )
            )
        );
        }

        if ( function_exists( 'wprentals_ptc_is_taxonomy_enabled' ) && wprentals_ptc_is_taxonomy_enabled( 'property_area' ) ) {
        // Area taxonomy
        $slug = isset($rewrites[4]) ? $rewrites[4] : 'area';
        register_taxonomy('property_area',
            'estate_property',
            array_merge(
                $taxonomy_args,
                array(
                    'labels'  => array(
                        'name'          => esc_html__('Neighborhood / Area', 'wprentals-core'),
                        'add_new_item'  => esc_html__('Add New Neighborhood / Area', 'wprentals-core'),
                        'new_item_name' => esc_html__('New Neighborhood / Area', 'wprentals-core')
                    ),
                    'rewrite' => array('slug' => $slug)
                )
            )
        );
        }

        if ( function_exists( 'wprentals_ptc_is_taxonomy_enabled' ) && wprentals_ptc_is_taxonomy_enabled( 'property_features' ) ) {

        // Features taxonomy
        $slug = isset($rewrites[5]) ? $rewrites[5] : 'features';
        register_taxonomy('property_features',
            'estate_property',
            array_merge(
                $taxonomy_args,
                array(
                    'labels'  => array(
                        'name'          => esc_html__('Features & Amenities', 'wprentals-core'),
                        'add_new_item'  => esc_html__('Add New Feature', 'wprentals-core'),
                        'new_item_name' => esc_html__('New Feature', 'wprentals-core')
                    ),
                    'rewrite' => array('slug' => $slug)
                )
            )
        );
        }

        if ( function_exists( 'wprentals_ptc_is_taxonomy_enabled' ) && wprentals_ptc_is_taxonomy_enabled( 'property_status' ) ) {

        // Status taxonomy
        $slug = isset($rewrites[6]) ? $rewrites[6] : 'status';
        register_taxonomy('property_status',
            'estate_property',
            array_merge(
                $taxonomy_args,
                array(
                    'labels'  => array(
                        'name'          => esc_html__('Property Status', 'wprentals-core'),
                        'add_new_item'  => esc_html__('Add New Status', 'wprentals-core'),
                        'new_item_name' => esc_html__('New Status', 'wprentals-core')
                    ),
                    'rewrite' => array('slug' => $slug)
                )
            )
        );
        }
    }
endif;