<?php
/** 
 * Agent Post Type Registration
 * src: post-types/agent/agent-post-type.php
 * This file handles the registration of the Estate Agent custom post type
 *
 * @package WPRentals Core
 * @subpackage Agent
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Estate Agent custom post type
 *
 * @return void
 */
add_action('after_setup_theme', 'wpestate_create_agent_type', 20);
if (!function_exists('wpestate_create_agent_type')):
    function wpestate_create_agent_type() {
        if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'estate_agent' ) ) {
            return;
        }
        // Get custom permalink structure
        $rewrites = wpestate_safe_rewite();
        $slug = isset($rewrites[7]) ? $rewrites[7] : 'owners';

        // Setup post type labels
        $labels = array(
            'name'                  => esc_html__('Owners', 'wprentals-core'),
            'singular_name'         => esc_html__('Owner', 'wprentals-core'),
            'add_new'              => esc_html__('Add New Owner', 'wprentals-core'),
            'add_new_item'         => esc_html__('Add Owner', 'wprentals-core'),
            'edit'                 => esc_html__('Edit', 'wprentals-core'),
            'edit_item'            => esc_html__('Edit Owner', 'wprentals-core'),
            'new_item'             => esc_html__('New Owner', 'wprentals-core'),
            'view'                 => esc_html__('View', 'wprentals-core'),
            'view_item'            => esc_html__('View Owner', 'wprentals-core'),
            'search_items'         => esc_html__('Search Owner', 'wprentals-core'),
            'not_found'            => esc_html__('No Owners found', 'wprentals-core'),
            'not_found_in_trash'   => esc_html__('No Owners found in Trash', 'wprentals-core'),
            'parent'               => esc_html__('Parent Owner', 'wprentals-core')
        );

        // Define the capabilities for this post type
        $capabilities = array(
            'edit_post'              => 'edit_estate_agent',
            'read_post'              => 'read_estate_agent',
            'delete_post'            => 'delete_estate_agent',
            'edit_posts'             => 'edit_estate_agents',
            'edit_others_posts'      => 'edit_others_estate_agents',
            'publish_posts'          => 'publish_estate_agents',
            'read_private_posts'     => 'read_private_estate_agents',
            'delete_posts'           => 'delete_estate_agents',
            'delete_private_posts'   => 'delete_private_estate_agents',
            'delete_published_posts' => 'delete_published_estate_agents',
            'delete_others_posts'    => 'delete_others_estate_agents',
            'edit_private_posts'     => 'edit_private_estate_agents',
            'edit_published_posts'   => 'edit_published_estate_agents',
        );

        // Register the post type
        register_post_type('estate_agent', 
            array(
                'labels' => $labels,
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => $slug),
                'supports' => array('title', 'editor', 'thumbnail', 'comments'),
                'can_export' => true,
                'register_meta_box_cb' => 'wpestate_add_agents_metaboxes',
                'menu_icon' => WPESTATE_PLUGIN_DIR_URL . '/img/agents.png',
                'capability_type' => 'estate_agent',
                'map_meta_cap' => true,
                'capabilities' => $capabilities
            )
        );

        // Ensure administrator has these capabilities
        $admin = get_role('administrator');
        if ($admin) {
            foreach ($capabilities as $cap) {
                $admin->add_cap($cap);
            }
        }
    }
endif;