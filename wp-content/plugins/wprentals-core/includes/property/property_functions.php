<?php

add_action('wp_ajax_wp_estate_handle_property_action', 'wp_estate_handle_property_action');

/**
 * Handle AJAX requests for property actions like approve, disapprove, expire, on-hold, sold, featured, and duplicate.
 *
 * @return void
 */
function wp_estate_handle_property_action() {

    if ( ! check_ajax_referer( 'wp_estate_property_action', '_wpnonce', false ) ) {
        wp_send_json_error( array( 'error' => __( 'Invalid nonce', 'wprentals-core' ) ) );
    }

    $action_type = isset($_POST['action_type']) ? sanitize_text_field($_POST['action_type']) : '';
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$post_id || !$action_type) {
        wp_send_json_error(array('error' => __('Invalid request', 'wprentals-core')));
    }

    $property = get_post($post_id);
    if (!$property || $property->post_type !== 'estate_property') {
        wp_send_json_error(array('error' => __('Property not found', 'wprentals-core')));
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( array( 'error' => __( 'Unauthorized', 'wprentals-core' ) ) );
    }

    // Handle the action based on the action type
    switch ($action_type) {
        case 'approve':
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'publish'
            ));
            break;
        case 'disapprove':
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'disabled'
            ));
            // global $wpdb;
            // $updated = $wpdb->query(
            //     $wpdb->prepare(
            //         "UPDATE {$wpdb->posts} SET post_status = %s WHERE ID = %d",
            //         'disabled',
            //         $post_id
            //     )
            // );
            break;
        case 'expire':
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'expired'
            ));
            // global $wpdb;
            // $updated = $wpdb->query(
            //     $wpdb->prepare(
            //         "UPDATE {$wpdb->posts} SET post_status = %s WHERE ID = %d",
            //         'expired',
            //         $post_id
            //     )
            // );
            break;
        case 'on-hold':
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'pending'
            ));
            break;
        case 'sold':
            $soldTermID = intval(wprentals_get_option('wpestate_mark_sold_status', '', 'sold'));
     
            if (term_exists($soldTermID, 'property_status')) {
                if (!has_term($soldTermID, 'property_status', $post_id)) {
                    wp_set_post_terms($post_id, array($soldTermID), 'property_status', false);
                } else {
                    // If the property is already marked as sold, remove the term
                    wp_remove_object_terms($post_id, $soldTermID, 'property_status');
                }
            } else {
                $term = wp_insert_term('sold', 'property_status');
                if (!is_wp_error($term)) {
                    wp_set_post_terms($post_id, array($term['term_id']), 'property_status', false);
                }
            }
            break;
        case 'featured':
            $featured = get_post_meta($post_id, 'prop_featured', true);
            if ($featured) {
                // Unmark as featured
                update_post_meta($post_id, 'prop_featured', false);
            } else {
                // Mark as featured
                update_post_meta($post_id, 'prop_featured', true);
            }
            break;
        case 'duplicate':

            $duplicatedID = wp_estate_duplicate_listing( $property );

            break;
        default:
            wp_send_json_error(array('error' => __('Unknown action', 'wprentals-core')));
            break;
    }

    // We need to replace the buttons based on new status
    $buttons = wp_estate_display_action_buttons($post_id);
    $status = get_post_status($post_id);
    if ( $action_type == 'expired' ) {
        $status = 'expired';
    } elseif ( $action_type == 'disapprove' ) {
        $status = 'disabled';
    } elseif ( $action_type == 'sold' ) {
        if ( has_term($soldTermID, 'property_status', $post_id) ) {
            $status = 'sold';
        }
    }
    $status_map = array(
        'expired'  => esc_html__('Expired', 'wprentals-core'),
        'publish'  => esc_html__('Published', 'wprentals-core'),
        'disabled' => esc_html__('Disabled', 'wprentals-core'),
        'draft'    => esc_html__('Draft', 'wprentals-core'),
        'sold'     => esc_html__('Sold', 'wprentals-core'),
        'default'  => esc_html__('Waiting for approval', 'wprentals-core')
    );
    $statusText = isset($status_map[$status]) ? $status_map[$status] : $status_map['default'];
    $status_class = sanitize_key(strtolower($statusText));
    $responseArray = array(
        'action_type' => $action_type,
        'post_id' => $post_id,
        'status' => $status,
        'status_text' => $statusText,
        'status_class' => $status_class,
        'buttons' => $buttons
    );

    if ( $action_type == 'featured' ) {
        $responseArray['featured_text'] = !$featured ? esc_html__('Yes', 'wprentals-core') : esc_html__('No', 'wprentals-core');
    }

    wp_send_json_success( $responseArray );
}

/* * Duplicate property function
 *
 */
function wp_estate_duplicate_listing( $listing )    {

    if ( get_post_type( $listing ) != 'estate_property' ) {
        wp_die( __('You are not allowed to duplicate this item.', 'wprentals-core') );
    }

    $current_user = wp_get_current_user();
    $post_id = $listing->ID;

    if ( ! current_user_can( 'manage_options' ) && (int) $current_user->ID !== (int) $listing->post_author ) {
        wp_die( __( 'You are not allowed to duplicate this item.', 'wprentals-core' ) );
    }

    $paid_submission_status = esc_html(wprentals_get_option('wp_estate_paid_submission', ''));
    // $parent_userID = wpestate_check_for_agency($current_user->ID);
    // Duplicate the property
    $new_post_id = wp_insert_post(array(
        'post_title' => $listing->post_title . ' ' . __('(Copy)', 'wprentals-core'),
        'post_content' => $listing->post_content,
        'post_status' => 'draft',
        'post_type' => 'estate_property',
        'post_author' => $listing->post_author,
    ));

    if ( is_wp_error( $new_post_id ) ) {
        wp_die( __('Error duplicating listing: ', 'wprentals-core') . $new_post_id->get_error_message() );
    }

    /*
    * get all current post terms ad set them to the new post draft
    */
    $taxonomies = get_object_taxonomies( $listing->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
    if( $taxonomies ) {
        foreach( $taxonomies as $taxonomy ) {
            $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
            wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
        }
    }

    // duplicate all post meta
    $post_meta = get_post_meta( $post_id );
    if( $post_meta ) {

        foreach ( $post_meta as $meta_key => $meta_values ) {
            // we need to exclude some system meta keys and data that should not be duplicated
            if( in_array( $meta_key, array( '_edit_lock', '_wp_old_slug', 'booking_dates', 'property_icalendar_import_multi', 'property_icalendar_import' ), true ) ) {
                continue;
            }
            // do not forget that each meta key can have multiple values
            foreach ( $meta_values as $meta_value ) {
                add_post_meta( $new_post_id, $meta_key, maybe_unserialize( $meta_value ) );
            }
        }

        // update memberhip package
            if ($paid_submission_status == 'membership') { // update pack status
                wpestate_update_listing_no($current_user->ID);
            }
        //defaults
        
        $sidebar =  wprentals_get_option( 'wp_estate_blog_sidebar');
        update_post_meta($new_post_id, 'sidebar_option', $sidebar);
        $sidebar_name   = wprentals_get_option( 'wp_estate_blog_sidebar_name');
        update_post_meta($new_post_id, 'sidebar_select', $sidebar_name);
        update_post_meta($new_post_id, 'prop_featured', 0);
        update_post_meta($new_post_id, 'pay_status', 'not paid');
        update_post_meta($new_post_id, 'page_custom_zoom', 16);

    }

    return $new_post_id;


}


/* * Display action buttons for a property based on its status.
 *
 * @param int $postID The ID of the property post.
 * @return string HTML string containing action buttons.
 */
function wp_estate_display_action_buttons( $postID ) {

    $post = get_post( $postID );
    if ( ! $post ) {
        return;
    }

    $edit_link = get_edit_post_link($postID);
    $featured = get_post_meta($postID, 'prop_featured', true);
    $featuredIcon = $featured ? '<i class="el el-star"></i>' : '<i class="el el-star-empty"></i>';
    $featuredString = $featured ? esc_html__('Remove from featured', 'wprentals-core') : esc_html__('Mark as featured', 'wprentals-core');

    $status = $post->post_status;
    $paid_submission_status = esc_html( wprentals_get_option( 'wp_estate_paid_submission', '' ) );
    // $soldTermID = intval(wprentals_get_option('wpestate_mark_sold_status', ''));
    // $soldTerm = get_term($soldTermID, 'property_status');
    // if ( has_term($soldTermID, 'property_status', $postID) ) {
    //     $status = 'sold';
    // }

    // $soldString = ($status == 'sold') ? esc_html__('Remove from ', 'wprentals-core') . $soldTerm->name : esc_html__('Mark as ', 'wprentals-core') . $soldTerm->name;

    if ( $status === 'draft' || $status === 'expired' || $status === 'pending'|| $status === 'disabled' ) {
        $goLive = true;
    } else {
        $goLive = false;
    }

    $returnString = '';

    if ( $goLive ) {
        $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin approve" data-action="approve" data-postid="' . esc_attr($postID) . '" title="' . esc_html__('Approve', 'wprentals-core') . '"><i class="el el-play-circle"></i></a> ';
        $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin duplicate" data-action="duplicate" data-postid="' . esc_attr($postID) . '" title="' . esc_html__('Duplicate', 'wprentals-core') . '"><i class="el el-view-mode"></i></a> ';
    } else {
        $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin disapprove" data-action="disapprove" data-postid="' . esc_attr($postID) . '" title="' . esc_html__('Disapprove', 'wprentals-core') . '"><i class="el el-stop-alt"></i></a> ';
        $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin featured" data-action="featured" data-postid="' . esc_attr($postID) . '" title="' . $featuredString . '">' . $featuredIcon . '</a> ';
        if ( 'membership' === $paid_submission_status ) {
            $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin expire" data-action="expire" data-postid="' . esc_attr($postID) . '" title="' . esc_html__('Expire', 'wprentals-core') . '"><i class="el el-error"></i></a> ';
        }
        $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin on-hold" data-action="on-hold" data-postid="' . esc_attr($postID) . '" title="' . esc_html__('On hold', 'wprentals-core') . '"><i class="el el-pause-alt"></i></a> ';
    // if ( $status !== 'sold' )   {
        // $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin sold" data-action="sold" data-postid="' . esc_attr($postID) . '" title="' . $soldString . '"><i class="el el-usd"></i></a> ';
    // }
        $returnString .= '<a href="' . esc_url($edit_link) . '" class="button wprentals_button wprentals_properties_action_admin duplicate" data-action="duplicate" data-postid="' . esc_attr($postID) . '" title="' . esc_html__('Duplicate', 'wprentals-core') . '"><i class="el el-view-mode"></i></a> ';
    }

    return $returnString;

}

add_action('admin_footer', 'wpestate_append_post_status_list');

/**
 * Append custom post statuses to the post status dropdown in the admin area.
 *
 * This function adds "Expired" and "Disabled" options to the post status dropdown
 * for properties, allowing administrators to easily set these statuses.
 */
function wpestate_append_post_status_list() {
    global $post;
    $complete = '';
    $label = '';
    
    if ($post && $post->post_type == 'estate_property') { // Change 'post' to your post type if needed
        if ($post->post_status == 'expired') {
            $complete = ' selected="selected"';
            $label = '<span id="post-status-display"> Expired</span>';
        } elseif ($post->post_status == 'disabled') {
            $complete = ' selected="selected"';
            $label = '<span id="post-status-display"> Disabled</span>';
        }
        
        echo '<script>
        jQuery(document).ready(function($) {
            $("select#post_status").append("<option value=\"expired\" ' . $complete . '>Expired</option>");
            $("select#post_status").append("<option value=\"disabled\" ' . $complete . '>Disabled</option>");
            $(".misc-pub-section").append("' . $label . '");
        });
        </script>';
    }
}
