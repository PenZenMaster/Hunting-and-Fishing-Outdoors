<?php
/**
 * Message Metaboxes
 * src: post-types/message/message-metaboxes.php
 * This file handles the metaboxes for the Message post type
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
* Add message metaboxes to admin edit screen
*
* @return void
*/

add_action('add_meta_boxes', 'wpestate_add_message_metaboxes');
if (!function_exists('wpestate_add_message_metaboxes')):
    function wpestate_add_message_metaboxes() {
        add_meta_box(
            'estate_message-sectionid',
            esc_html__('Message Details', 'wprentals-core'),
            'wpestate_message_meta_function',
            'wpestate_message',
            'normal',
            'default'
        );
    }
endif;



/**
 * Display message metabox content
 *
 * @param WP_Post $post Post object
 * @return void
 */
if (!function_exists('wpestate_message_meta_function')):
    function wpestate_message_meta_function($post) {
        // Better nonce name tied to action
        wp_nonce_field('wpestate_message_meta_save', 'wpestate_message_nonce');

        // Get message data
        $from_user_id = get_post_meta($post->ID, 'message_from_user', true);
        $to_user_id = get_post_meta($post->ID, 'message_to_user', true);
        
        // From user section
        $from_display = '';
        if ($from_user_id) {
            $user = get_user_by('id', $from_user_id);
            $from_display = $user ? esc_html($user->user_login) : esc_html__('User not found', 'wprentals-core');
        } else {
            $from_display = esc_html__('Unregistered User', 'wprentals-core');
        }
        
        ?>
        <p class="meta-options">
            <label for="message_from_user"><?php esc_html_e('From User:', 'wprentals-core'); ?></label><br />
            <input type="text" 
                   id="message_from_user" 
                   name="message_from_user" 
                   class="" 
                   value="<?php echo $from_display; ?>" 
                   readonly>
        </p>

        <p class="meta-options">
            <label for="message_to_user"><?php esc_html_e('To User:', 'wprentals-core'); ?></label><br />
            <select id="message_to_user" name="message_to_user" class="widefat">
                <?php echo wpestate_get_user_list(); ?>
            </select>
        </p>

        <input type="hidden" name="message_status" value="unread">
        <input type="hidden" name="delete_source" value="0">
        <input type="hidden" name="delete_destination" value="0">
        <?php
    }
endif;




if( !function_exists('wpestate_is_edit_page') ):
    function wpestate_is_edit_page($new_edit = null){
        global $pagenow;
        //make sure we are on the backend
        if (!is_admin()) return false;


        if($new_edit == "edit")
            return in_array( $pagenow, array( 'post.php',  ) );
        elseif($new_edit == "new") //check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        else //check for either new or edit
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
    }
endif;





/**
* Get list of users for message dropdown
*
* @return string HTML select options
*/
if (!function_exists('wpestate_get_user_list')):
    function wpestate_get_user_list() {
        global $post;
        $selected = get_post_meta($post->ID, 'message_to_user', true);
        $return_string = '';
        $blogusers = get_users();
        
        foreach ($blogusers as $user) {
            $return_string .= '<option value="' . $user->ID . '" ';
            if ($selected == $user->ID) {
                $return_string .= ' selected="selected" ';
            }
            $return_string .= '>' . $user->user_nicename . '</option>';
        }
        return $return_string;
    }
endif;
