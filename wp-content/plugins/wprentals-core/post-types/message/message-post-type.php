<?php
/**
 * Message Post Type Registration
 * src: post-types/message/message-post-type.php
 * This file handles the registration of the Message custom post type
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
* Register the Message custom post type
* Creates the 'wpestate_message' post type with required capabilities
* and ensures admin has access to manage messages
*
* @return void 
*/
add_action('after_setup_theme', 'wpestate_create_message_type', 20);
if (!function_exists('wpestate_create_message_type')):
    function wpestate_create_message_type() {

        if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'wpestate_message' ) ) {
            return;
        }

        $capabilities = array(
            'edit_post'              => 'edit_wpestate_message',
            'read_post'              => 'read_wpestate_message', 
            'delete_post'            => 'delete_wpestate_message',
            'edit_posts'             => 'edit_wpestate_messages',
            'edit_others_posts'      => 'edit_others_wpestate_messages',
            'publish_posts'          => 'publish_wpestate_messages',
            'read_private_posts'     => 'read_private_wpestate_messages',
            'create_posts'           => 'create_wpestate_messages',
            'delete_posts'           => 'delete_wpestate_messages',
            'delete_private_posts'   => 'delete_private_wpestate_messages',
            'delete_published_posts' => 'delete_published_wpestate_messages',
            'delete_others_posts'    => 'delete_others_wpestate_messages',
            'edit_private_posts'     => 'edit_private_wpestate_messages',
            'edit_published_posts'   => 'edit_published_wpestate_messages'
        );
     
        // Grant capabilities to admin
        $admin = get_role('administrator');
        foreach ($capabilities as $cap) {
            $admin->add_cap($cap);
        }


        register_post_type('wpestate_message', array(
            'labels' => array(
                'name'               => esc_html__('Messages', 'wprentals-core'),
                'singular_name'      => esc_html__('Message', 'wprentals-core'),
                'add_new'           => esc_html__('Add New Message', 'wprentals-core'),
                'add_new_item'      => esc_html__('Add Message', 'wprentals-core'),
                'edit'              => esc_html__('Edit', 'wprentals-core'),
                'edit_item'         => esc_html__('Edit Message', 'wprentals-core'),
                'new_item'          => esc_html__('New Message', 'wprentals-core'),
                'view'              => esc_html__('View', 'wprentals-core'),
                'view_item'         => esc_html__('View Message', 'wprentals-core'),
                'search_items'      => esc_html__('Search Message', 'wprentals-core'),
                'not_found'         => esc_html__('No Message found', 'wprentals-core'),
                'not_found_in_trash'=> esc_html__('No Message found', 'wprentals-core'),
                'parent'            => esc_html__('Parent Message', 'wprentals-core')
            ),
            'public'              => true,
            'has_archive'         => true,
            'rewrite'            => array('slug' => 'message'),
            'supports'           => array('title', 'editor'),
            'can_export'         => true,
            'register_meta_box_cb'=> 'wpestate_add_message_metaboxes',
            'menu_icon'          => WPESTATE_PLUGIN_DIR_URL . '/img/message.png',
            'exclude_from_search'=> true,
            'capability_type'    => 'wpestate_message',
            'map_meta_cap'       => true,
            'capabilities' => $capabilities
        ));
    }
endif;


/**
* Hide 'Add New' menu items for message, booking and invoice post types
*
* @return void
*/
add_action('admin_menu', 'wpestate_hide_add_new_wpestate_message');
if (!function_exists('wpestate_hide_add_new_wpestate_message')):
    function wpestate_hide_add_new_wpestate_message() {
        global $submenu;
        unset($submenu['edit.php?post_type=wpestate_message'][10]);
        unset($submenu['edit.php?post_type=wpestate_booking'][10]);
        unset($submenu['edit.php?post_type=wpestate_invoice'][10]);
    }
endif;


/**
* Add custom columns to message list view
*
* @param array $columns Default columns
* @return array Modified columns 
*/
add_filter('manage_edit-wpestate_message_columns', 'wpestate_my_mess_columns');
if (!function_exists('wpestate_my_mess_columns')):
    function wpestate_my_mess_columns($columns) {
        $slice = array_slice($columns, 2, 2);
        unset($columns['comments']);
        unset($slice['comments']);
        $splice = array_splice($columns, 2);
        $columns['mess_from_who'] = esc_html__('From', 'wprentals-core');
        $columns['mess_to_who'] = esc_html__('To', 'wprentals-core');
        return array_merge($columns, array_reverse($slice));
    }
endif;




/**
* Populate custom columns in message list
*
* @param string $column Column name
* @return void
*/
/**
 * Populate custom columns in message list
 *
 * @param string $column Column name being displayed
 * @return void
 */
add_action('manage_posts_custom_column', 'wpestate_populate_messages_columns');
if (!function_exists('wpestate_populate_messages_columns')):
    function wpestate_populate_messages_columns($column) {
        $the_id = get_the_ID();
        
        if ('mess_from_who' == $column) {
            $from_value = get_post_meta($the_id, 'message_from_user', true);
            if (empty($from_value)) {
                esc_html_e('Unregistered User', 'wprentals-core');
                return;
            }
            
            $user = get_user_by('id', $from_value);
            if (!$user) {
                esc_html_e('User not found', 'wprentals-core');
                return;
            }
            
            echo esc_html($user->user_login);
        }

        if ('mess_to_who' == $column) {
            $to_val = get_post_meta($the_id, 'message_to_user', true);
            if (empty($to_val)) {
                return;
            }
            
            $user = get_user_by('id', $to_val);
            if (!$user) {
                return;
            }
            
            echo esc_html($user->user_login);
        }
    }
endif;