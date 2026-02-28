<?php 
/**
 * review Post Type and Taxonomies Registration
 *
 * This file defines and registers the 'estate_review' custom post type
 * along with its associated taxonomies for the WP Estate real estate management system.
 * 
 * The file handles:
 * 1. Custom post type registration with appropriate labels and supports
 * 2. review category taxonomy registration
 * 3. review action taxonomy registration
 * 4. Geographic taxonomies (city, neighborhood, county/state)
 * 5. Proper rewrite rules for SEO-friendly URLs
 *
 * Each taxonomy is hierarchical and REST API enabled for modern development approaches.
 *
 * @package wprentals
 * @subpackage CustomPostTypes
 * @since 1.0.0
 */

// Register the custom post type on WordPress init hook with priority 20
add_action( 'init', 'wpestate_create_reviews_type', 20 );

if( !function_exists('wpestate_create_reviews_type') ):
/**
 * Register review Custom Post Type and Taxonomies
 * 
 * This function handles the complete registration process for:
 * - The 'estate_review' custom post type
 * - Five hierarchical taxonomies for organizing reviews:
 *   1. Category (property_category_review)
 *   2. Action (property_action_category_review)
 *   3. City (property_city_review)
 *   4. Neighborhood (property_area_review)
 *   5. County/State (property_county_state_review)
 *
 * All registrations use translatable strings and implement
 * custom rewrite rules from the wpestate_safe_rewite() function.
 *
 * @since 1.0.0
 * @return void
 */
function wpestate_create_reviews_type() {
    if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'estate_review' ) ) {
        return;
    }
    // Get custom rewrite rules from WP Estate system
    $rewrites   =   wpestate_safe_rewite();

    $labels =    array(
            'name'          => esc_html__( 'Reviews','wprentals-core'),
            'singular_name' => esc_html__( 'Review','wprentals-core'),
            'add_new'       => esc_html__('Add New Review','wprentals-core'),
            'add_new_item'          =>  esc_html__('Add Review','wprentals-core'),
            'edit'                  =>  esc_html__('Edit' ,'wprentals-core'),
            'edit_item'             =>  esc_html__('Edit Review','wprentals-core'),
            'new_item'              =>  esc_html__('New Review','wprentals-core'),
            'view'                  =>  esc_html__('View','wprentals-core'),
            'view_item'             =>  esc_html__('View Review','wprentals-core'),
            'search_items'          =>  esc_html__('Search Review','wprentals-core'),
            'not_found'             =>  esc_html__('No Reviews found','wprentals-core'),
            'not_found_in_trash'    =>  esc_html__('No Reviews found','wprentals-core'),
            'parent'                =>  esc_html__('Parent Review','wprentals-core'),
            'featured_image'        => esc_html__('Featured Image','wprentals-core'),
            'set_featured_image'    => esc_html__('Set Featured Image','wprentals-core'),
            'remove_featured_image' => esc_html__('Remove Featured Image','wprentals-core'),
            'use_featured_image'    => esc_html__('Use Featured Image','wprentals-core'),
            'parent_item_colon'     => esc_html__('Original Review:','wprentals-core')

    );



    // Define the capabilities for this post type
    $capabilities = array(
        'edit_post'              => 'edit_estate_review',
        'read_post'              => 'read_estate_review',
        'delete_post'            => 'delete_estate_review',
        'edit_posts'             => 'edit_estate_reviews',
        'edit_others_posts'      => 'edit_others_estate_reviews',
        'publish_posts'          => 'publish_estate_reviews',
        'read_private_posts'     => 'read_private_estate_reviews',
        'delete_posts'           => 'delete_estate_reviews',
        'delete_private_posts'   => 'delete_private_estate_reviews',
        'delete_published_posts' => 'delete_published_estate_reviews',
        'delete_others_posts'    => 'delete_others_estate_reviews',
        'edit_private_posts'     => 'edit_private_estate_reviews',
        'edit_published_posts'   => 'edit_published_estate_reviews',
    );





    // Register the main estate_review custom post type
    register_post_type( 'estate_review',
            array(
                'labels' => $labels,
                'public'              => false,
                'show_ui'            => true,
                'show_in_nav_menus'  => true,
                'show_in_menu'       => true,
                'show_in_admin_bar'  => true,
                'hierarchical'       => true,
                // 'capability_type'    => 'page',
                'has_archive'         => true,          // Enables archive page for reviews
                'rewrite' => array('slug' => 'reviews'), // Custom permalink structure
                'supports' => array('title', 'editor', 'page-attributes'), // Supported features
                'can_export' => true,               // Allows export via WordPress tools
                'register_meta_box_cb' => 'wpestate_add_reviews_metaboxes', // Callback for custom metaboxes
                'menu_icon'=> 'dashicons-admin-comments',   // Custom menu icon
                'show_in_rest'=>true,               // Enables Gutenberg/REST API support
                'map_meta_cap' => true,
                'exclude_from_search'=> true,
                'capabilities' => $capabilities
            )
    );


    
    // Register the review Category taxonomy
    // This taxonomy allows categorizing reviews (e.g., "Commercial", "Residential", etc.)
    // if ( function_exists( 'wprentals_ptc_is_taxonomy_enabled' ) && wprentals_ptc_is_taxonomy_enabled( 'property_category_review' ) ) {
    register_taxonomy('estate_review_category', array('estate_review'), array(
        'labels' => array(
            'name'              => esc_html__('review Categories','wprentals-core'),
            'add_new_item'      => esc_html__('Add New review Category','wprentals-core'),
            'new_item_name'     => esc_html__('New review Category','wprentals-core')
        ),
        'hierarchical'  => true,     // Category-like behavior (not tag-like)
        'query_var'     => true,     // Can be queried
        'show_in_rest'  => true,     // Enables REST API
        'rewrite'       => array( 'slug' => 'reviews_category') // Custom URL structure
        )
    );
    // }


}
endif; // end   wpestate_create_review_type



/**
 * Ensures proper taxonomy capabilities are set for the administrator role
 * This function runs early in the WordPress init process to set up required permissions
 * for review post type and taxonomies
 *
 * @since 4.0.0
 * @return void
 */
add_action('init', 'wpestate_ensure_review_taxonomy_caps', 0);
if (!function_exists('wpestate_ensure_review_taxonomy_caps')):
    function wpestate_ensure_review_taxonomy_caps() {
        $admin = get_role('administrator');
        if ($admin) {
            // Add review post type editing capabilities
            $admin->add_cap('edit_estate_review');
            $admin->add_cap('read_estate_review');
            $admin->add_cap('delete_estate_review');
            $admin->add_cap('edit_estate_reviews');
            $admin->add_cap('edit_others_estate_reviews');
            $admin->add_cap('publish_estate_reviews');
            $admin->add_cap('read_private_estate_reviews');
            $admin->add_cap('create_estate_reviews');
            $admin->add_cap('delete_estate_reviews');
            $admin->add_cap('delete_private_estate_reviews');
            $admin->add_cap('delete_published_estate_reviews');
            $admin->add_cap('delete_others_estate_reviews');
            $admin->add_cap('edit_private_estate_reviews');
            $admin->add_cap('edit_published_estate_reviews');
            
            // Define taxonomy capabilities array
            $taxonomy_caps = array(
                'manage_estate_review_category',
                'edit_estate_review_category',
                'delete_estate_review_category',
                'assign_estate_review_category',
         
            );
            
            // Add each capability to admin role
            foreach ($taxonomy_caps as $cap) {
                $admin->add_cap($cap);
            }
        }
    }
endif;