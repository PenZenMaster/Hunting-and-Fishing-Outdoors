<?php 
/**
* Membership Post Type Registration
* src: post-types/membership/membership-post-type.php
*/



if (!defined('ABSPATH')) {
    exit;
 }
 

  /**
 * Function header:
 * Creates the Membership Package custom post type
 * Establishes all required capabilities for admin users
 * Sets up post type settings including UI visibility, permalinks and features
 *
 * @return void
 */

 
 add_action('after_setup_theme', 'wpestate_create_membership_type', 20);
 if (!function_exists('wpestate_create_membership_type')):
    function wpestate_create_membership_type() {
        if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'membership_package' ) ) {
                return;
        }
        // Define capabilities
        $capabilities = array(
            'edit_post'              => 'edit_membership_package',
            'read_post'              => 'read_membership_package',
            'delete_post'            => 'delete_membership_package',
            'edit_posts'             => 'edit_membership_packages',
            'edit_others_posts'      => 'edit_others_membership_packages',
            'publish_posts'          => 'publish_membership_packages',
            'read_private_posts'     => 'read_private_membership_packages',
            'create_posts'           => 'create_membership_packages',
            'delete_posts'           => 'delete_membership_packages',
            'delete_private_posts'   => 'delete_private_membership_packages',
            'delete_published_posts' => 'delete_published_membership_packages',
            'delete_others_posts'    => 'delete_others_membership_packages',
            'edit_private_posts'     => 'edit_private_membership_packages',
            'edit_published_posts'   => 'edit_published_membership_packages'
        );
 
        // Get admin role securely
        $admin = get_role('administrator'); 
        if ($admin) {
            foreach ($capabilities as $cap) {
                if (is_string($cap)) {
                    $admin->add_cap(sanitize_key($cap));
                }
            }
        }
 
        register_post_type('membership_package', array(
            'labels' => array(
                'name'              => esc_html__('Membership Packages', 'wprentals-core'),
                'singular_name'     => esc_html__('Membership Packages', 'wprentals-core'),
                'add_new'           => esc_html__('Add New Membership Package', 'wprentals-core'),
                'add_new_item'      => esc_html__('Add Membership Packages', 'wprentals-core'),
                'edit'              => esc_html__('Edit Membership Packages', 'wprentals-core'),
                'edit_item'         => esc_html__('Edit Membership Package', 'wprentals-core'),
                'new_item'          => esc_html__('New Membership Packages', 'wprentals-core'),
                'view'              => esc_html__('View Membership Packages', 'wprentals-core'),
                'view_item'         => esc_html__('View Membership Packages', 'wprentals-core'),
                'search_items'      => esc_html__('Search Membership Packages', 'wprentals-core'),
                'not_found'         => esc_html__('No Membership Packages found', 'wprentals-core'),
                'not_found_in_trash'=> esc_html__('No Membership Packages found', 'wprentals-core'),
                'parent'            => esc_html__('Parent Membership Package', 'wprentals-core')
            ),
            'public'              => false,
            'show_ui'            => true,
            'show_in_nav_menus'  => true,
            'show_in_menu'       => true,
            'show_in_admin_bar'  => true,
            'has_archive'         => true,
            'rewrite'            => array('slug' => 'package'),
            'supports'           => array('title', 'thumbnail'),
            'can_export'         => true,
            'register_meta_box_cb'=> 'wpestate_add_pack_metaboxes',
            'menu_icon'          => WPESTATE_PLUGIN_DIR_URL . '/img/membership.png',
            'capability_type'    => 'membership_package',
            'map_meta_cap'       => true,
            'capabilities'       => $capabilities
        ));
    }
 endif;
 
