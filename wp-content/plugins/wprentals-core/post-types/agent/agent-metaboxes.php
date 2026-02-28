<?php
/**
 * Agent Metaboxes
 * src: post-types/agent/agent-metaboxes.php
 * This file handles the metaboxes for the Estate Agent post type
 *
 * @package WPRentals Core
 * @subpackage Agent
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add agent metaboxes
 */
add_action('add_meta_boxes', 'wpestate_add_agents_metaboxes');
if (!function_exists('wpestate_add_agents_metaboxes')):
    function wpestate_add_agents_metaboxes() {    
        add_meta_box(
            'estate_agent-sectionid',
            esc_html__('Owner Details', 'wprentals-core'),
            'estate_agent_metabox_content',
            'estate_agent',
            'normal',
            'default'
        );
    }
endif;

/**
 * Agent metabox content
 */
if (!function_exists('estate_agent_metabox_content')):
    function estate_agent_metabox_content($post) {
        wp_nonce_field('estate_agent_nonce', 'estate_agent_nonce');
        
        // Define fields array
        $fields = array(
            'agent_email' => array(
                'label' => esc_html__('Email:', 'wprentals-core'),
                'type' => 'text'
            ),
            'agent_phone' => array(
                'label' => esc_html__('Phone:', 'wprentals-core'),
                'type' => 'text'
            ),
            'agent_mobile' => array(
                'label' => esc_html__('Mobile:', 'wprentals-core'),
                'type' => 'text'
            ),
            'agent_skype' => array(
                'label' => esc_html__('Skype:', 'wprentals-core'),
                'type' => 'text'
            ),
            'agent_facebook' => array(
                'label' => esc_html__('Facebook:', 'wprentals-core'),
                'type' => 'text'
            ),
            'agent_twitter' => array(
                'label' => esc_html__('X - Twitter:', 'wprentals-core'),
                'type' => 'text'
            ),
            'agent_linkedin' => array(
                'label' => esc_html__('Linkedin:', 'wprentals-core'),
                'type' => 'text'
            ),
            'agent_pinterest' => array(
                'label' => esc_html__('Pinterest:', 'wprentals-core'),
                'type' => 'text'
            ),
            'live_in' => array(
                'label' => esc_html__('I Live In:', 'wprentals-core'),
                'type' => 'text'
            ),
            'i_speak' => array(
                'label' => esc_html__('I Speak:', 'wprentals-core'),
                'type' => 'text'
            ),
            'payment_info' => array(
                'label' => esc_html__('Payment Info/Hidden Field:', 'wprentals-core'),
                'type' => 'textarea'
            ),
            'user_agent_id' => array(
                'label' => esc_html__('User Agent ID:', 'wprentals-core'),
                'type' => 'text'
            )
        );

        $active_tab = 'agent_contact';
        print '<div class="property_options_wrapper meta-options">'
        .'<div class="property_options_wrapper_list">';
            // print '<div class="property_tab_item'.( $active_tab === 'agent_details' ? ' active_tab' : '' ).'" data-content="agent_details">'.esc_html__('Agent Details','wprentals-core').'</div>';
            print '<div class="property_tab_item'.( $active_tab === 'agent_contact' ? ' active_tab' : '' ).'" data-content="agent_contact">'.esc_html__('Owner Details','wprentals-core').'</div>';
            // print '<div class="property_tab_item'.( $active_tab === 'agency_profile' ? ' active_tab' : '' ).'" data-content="agency_profile">'.esc_html__('Agency Profile','wprentals-core').'</div>';
            // print '<div class="property_tab_item'.( $active_tab === 'agent_customs' ? ' active_tab' : '' ).'" data-content="agent_customs">'.esc_html__('Agent Custom Fields','wprentals-core').'</div>';
        print '</div><div class="property_options_content_wrapper">';

        print '<div class="property_tab_item_content'.( $active_tab === 'agent_contact' ? ' active_tab' : '' ).'" id="agent_contact">';

        // Output fields
        foreach ($fields as $field_id => $field) {
            $value = get_post_meta($post->ID, $field_id, true);
            echo '<div class="property_prop_half">';
            echo '<label for="' . esc_attr($field_id) . '">' . $field['label'] . '</label><br />';
            
            if ($field['type'] === 'textarea') {
                echo '<textarea id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" cols="70" rows="3">' . 
                     esc_textarea($value) . '</textarea>';
            } else {
                echo '<input type="text" id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" ' .
                     'size="58" value="' . esc_attr($value) . '">';
            }
            echo '</div>';
        }

        print '</div></div>'; // Close agent_contact tab content
    }
endif;

/**
 * Save agent details
 */
add_action('save_post', 'wpestate_save_agent_postdata', 1, 2);
if (!function_exists('wpestate_save_agent_postdata')):
    function wpestate_save_agent_postdata($post_id, $post) {
        // Basic checks
        if (!is_object($post) || !isset($post->post_type) || $post->post_type !== 'estate_agent') {
            return;
        }

        // Verify nonce
        if (!isset($_POST['estate_agent_nonce']) || 
            !wp_verify_nonce($_POST['estate_agent_nonce'], 'estate_agent_nonce')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $allowed_html = array();
        $user_id = get_post_meta($post_id, 'user_meda_id', true);

        // Fields to update
        $fields = array(
            'agent_email', 'agent_phone', 'agent_skype', 'agent_mobile',
            'agent_facebook', 'agent_twitter', 'agent_linkedin', 'agent_pinterest',
            'i_speak', 'live_in', 'payment_info'
        );

        // Update post meta
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = wp_kses($_POST[$field], $allowed_html);
                update_post_meta($post_id, $field, $value);
                
                // Update corresponding user meta
                $user_meta_key = str_replace('agent_', '', $field);
                update_user_meta($user_id, $user_meta_key, $value);
            }
        }

        // Handle image
        $image_id = get_post_thumbnail_id($post_id);
        if ($image_id) {
            $full_img = wp_get_attachment_image_src($image_id, 'wpestate_property_places');
            if ($full_img) {
                update_user_meta($user_id, 'aim', '/' . $full_img[0] . '/');
                update_user_meta($user_id, 'custom_picture', $full_img[0]);
                update_user_meta($user_id, 'small_custom_picture', $image_id);
            }
        }

        // Handle email update
        $email = wp_kses($_POST['agent_email'], $allowed_html);
        $new_user_id = email_exists($email);
        if (!$new_user_id) {
            wp_update_user(array(
                'ID' => $user_id,
                'user_email' => $email
            ));
        }
    }
endif;