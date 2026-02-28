<?php
/**
 * Search Post Type Registration
 * src: post-types/search/search-post-type.php
 */


 /**
* Register the Search custom post type
* Creates wpestate_search post type and sets up admin capabilities
*
* @return void
*/


if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', 'wpestate_create_saved_search', 20);
if (!function_exists('wpestate_create_saved_search')):
    function wpestate_create_saved_search() {
        if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'wpestate_search' ) ) {
            return;
        }
        // Define capabilities
        $capabilities = array(
            'edit_post'              => 'edit_wpestate_search',
            'read_post'              => 'read_wpestate_search',
            'delete_post'            => 'delete_wpestate_search',
            'edit_posts'             => 'edit_wpestate_searches',
            'edit_others_posts'      => 'edit_others_wpestate_searches',
            'publish_posts'          => 'publish_wpestate_searches',
            'read_private_posts'     => 'read_private_wpestate_searches',
            'create_posts'           => 'create_wpestate_searches',
            'delete_posts'           => 'delete_wpestate_searches',
            'delete_private_posts'   => 'delete_private_wpestate_searches',
            'delete_published_posts' => 'delete_published_wpestate_searches',
            'delete_others_posts'    => 'delete_others_wpestate_searches',
            'edit_private_posts'     => 'edit_private_wpestate_searches',
            'edit_published_posts'   => 'edit_published_wpestate_searches'
        );

        // Grant capabilities to admin
        $admin = get_role('administrator');
        if ($admin) {
            foreach ($capabilities as $cap) {
                $admin->add_cap($cap);
            }
        }

        register_post_type('wpestate_search', array(
            'labels' => array(
                'name'              => esc_html__('Searches', 'wprentals-core'),
                'singular_name'     => esc_html__('Searches', 'wprentals-core'),
                'add_new'           => esc_html__('Add New Searches', 'wprentals-core'),
                'add_new_item'      => esc_html__('Add Searches', 'wprentals-core'),
                'edit'              => esc_html__('Edit Searches', 'wprentals-core'),
                'edit_item'         => esc_html__('Edit Searches', 'wprentals-core'),
                'new_item'          => esc_html__('New Searches', 'wprentals-core'),
                'view'              => esc_html__('View Searches', 'wprentals-core'),
                'view_item'         => esc_html__('View Searches', 'wprentals-core'),
                'search_items'      => esc_html__('Search Searches', 'wprentals-core'),
                'not_found'         => esc_html__('No Searches found', 'wprentals-core'),
                'not_found_in_trash'=> esc_html__('No Searches found', 'wprentals-core'),
                'parent'            => esc_html__('Parent Searches', 'wprentals-core')
            ),
            'public'              => false,
            'has_archive'         => false,
            'rewrite'            => array('slug' => 'searches'),
            'supports'           => array('title'),
            'can_export'         => true,
            'register_meta_box_cb'=> 'wpestate_add_searches',
            'menu_icon'          => WPESTATE_PLUGIN_DIR_URL . '/img/searches.png',
            'capability_type'    => 'wpestate_search',
            'map_meta_cap'       => true,
            'capabilities'       => $capabilities
        ));
    }
endif;