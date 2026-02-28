<?php 
/**
 * WPRentals Updates function
 * This file handles functions used in data updates betwen versions
 */

   
/**
 * Hook the migration to plugin update
 */
function wprentals_check_version_and_migrate() {
    $current_version = get_option('wprentals_version');

    if ($current_version && version_compare($current_version, '3.13', '<')) {
        wprentals_migrate_user_roles_to_313();
    }
    // Update version after migration
    update_option('wprentals_version', '3.13');
}

add_action('admin_init', 'wprentals_migrate_user_roles_to_313',9999);



 /**
 * One-time migration for user roles based on user_type meta
 * Run during plugin update to version 3.13
 * 
 * @return void
 */
function wprentals_migrate_user_roles_to_313() {

    // Verify admin privileges
    if (!current_user_can('manage_options')) {
        return;
    }
    // Check if migration already ran
    if (get_option('wprentals_role_migration_313_completed')) {
        return;
    }
     // Create roles if needed
    if (!get_role(WPRENTALS_ROLE_OWNER) || !get_role(WPRENTALS_ROLE_RENTER)) {
        wprentals_create_custom_roles();
    }

    // Get all users with 'user_type' meta
    $users = get_users(array(
        'meta_key' => 'user_type',
        'compare' => 'EXISTS',
        'fields' => array('ID'),
        'role__not_in' => array('administrator', 'editor', 'author', 'contributor'),
    ));

    foreach ($users as $user) {
        
        $user_type = intval(get_user_meta($user->ID, 'user_type', true));
        $user_obj = new WP_User($user->ID);

        // Determine role based on user_type
        $new_role = ($user_type === 0) ? WPRENTALS_ROLE_OWNER : WPRENTALS_ROLE_RENTER;
          
        // Store existing roles except administrator
        $existing_roles = array_diff($user_obj->roles, array('administrator', 'editor', 'author', 'contributor'));

        // Add new role without removing existing ones
        $user_obj->add_role($new_role);


        error_log( $user->ID. ' new role '.$new_role.'</br>') ;
          // Log migration
          error_log(sprintf(
            'WPRentals Migration: User %d assigned role %s while preserving roles: %s',
            $user->ID,
            $new_role,
            implode(', ', $existing_roles)
        ));
    }
    
    // Mark migration as complete
    update_option('wprentals_role_migration_313_completed', true, false);
}

/**
 * Hook the migration to property template from page temaplte to post type wpestate-studio
 */
function wprentals_check_version_and_migrate_40() {

    $my_theme = wp_get_theme();
    $version = floatval($my_theme->get('Version'));

   if ($version && version_compare($version, '4.0', '<=')) {

        wprentals_convert_comments_to_estate_review();
        

        // Update wp_estate_submission_page_fields
        $current_fields = wprentals_get_option('wp_estate_submission_page_fields','');
        if ( !empty( $current_fields ) )    {
            // Add two new fields
            $new_fields = array( 'private_notes', 'checkin_message' );
            $updated_fields = array_merge($current_fields, $new_fields);
            wprentals_update_option('wp_estate_submission_page_fields', $updated_fields);
        }
    }

    // Update version after migration
    update_option('wprentals_version', '4.0');
}
add_action('admin_init', 'wprentals_check_version_and_migrate_40', 9999);

/**
 * Converts existing WordPress comments to custom 'estate_review' post type
 * 
 * This function migrates all comments in the database to a custom post type called 'estate_review'.
 * It preserves comment metadata like review title, content, stars rating, and approval status.
 * The function includes safety checks to prevent unauthorized access and duplicate executions.
 * After conversion, original comments are deleted and URL rewrite rules are updated.
 * 
 * @return void
 */
function wprentals_convert_comments_to_estate_review() {

    // Only allow admin to run this
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    
    // Prevent re-running
    if ( get_option( 'wprentals_convert_comments_to_estate_review_done' ) ) {
        return;
    }
    
     // Get all comments for estate_property posts
    $comments = get_comments(array(
        'post_type' => 'estate_property',
        // 'status' => 'approve',
    ));
    
    foreach ($comments as $comment) {

        $approved = $comment->comment_approved;

        // Convert comment approval status to post status
        $status = $approved === '1' ? 'publish' : 'draft';
        
        // Use original comment author ID, fallback to admin (ID 1) if not set
        $userID = !empty($comment->user_id) ? $comment->user_id : 1;

        // Create new review post
        $review_data = array(
            'post_title' => sprintf('Review for %s', get_the_title($comment->comment_post_ID)),
            'post_content' => $comment->comment_content,
            'post_status' => $status,
            'post_type' => 'estate_review',
            'post_author' => $userID,
            'post_date' => $comment->comment_date
        );
        
        $review_id = wp_insert_post($review_data);
        
        if ($review_id) {
            // Migrate meta data
            update_post_meta($review_id, 'attached_to', $comment->comment_post_ID);
            
            $review_stars = get_comment_meta($comment->comment_ID, 'review_stars', true);
            if ($review_stars) {
                update_post_meta($review_id, 'review_stars', $review_stars);
            }
            
            $owner_reply = get_comment_meta($comment->comment_ID, 'owner_reply', true);
            if ($owner_reply) {
                // $status = 'pending';
                // if (wprentals_get_option('wp_estate_admin_approves_reviews', '') == 'no') {
                //     $status = 'publish'; // Auto-approve if setting is 'no'
                // }

                // Create a child review post
                $replyID = wp_insert_post(
                    array(
                        'post_title'   => sprintf('Owner reply to Review #%d', $review_id),
                        'post_content' => $owner_reply,
                        'post_status'  => 'publish',
                        'post_type'    => 'estate_review',
                        'post_author'  => $userID,
                        'post_parent'  => $review_id,
                        'meta_input'   => array(
                            'attached_to' => $comment->comment_post_ID
                        )
                    )
                );

                // Old owner reply
                update_post_meta($review_id, 'owner_reply', $owner_reply);
            }

            update_post_meta($comment->comment_post_ID,'review_by_'.$userID,'has');

            update_post_meta($review_id, 'review_author', $userID);
            update_post_meta($review_id, 'reviewer_name', $comment->comment_author);
            update_post_meta($review_id, 'reviewer_email', $comment->comment_author_email);
            update_post_meta($review_id, 'migrated_from_comment', $comment->comment_ID);

            // Delete original comment
            wp_delete_comment($comment->comment_ID, true);
        }
    }
    
    // Recalculate ratings for all properties
    $properties = get_posts(array(
        'post_type' => 'estate_property',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));
    
    foreach ($properties as $property_id) {
        wpestate_calculate_property_rating($property_id);
    }
    
    // Update rewrite_urls_option
    $rewrite_urls_option = get_option('wp_estate_url_rewrites', array());
    
    // Ensure the option is an array
    if ( !is_array($rewrite_urls_option) ) {
        $rewrite_urls_option = array();
    }
    
    // Add URL rewrite rules for the new post type
    $rewrite_urls_option[26] = 'review';          // Single review page slug
    $rewrite_urls_option[27] = 'review_category'; // Review category archive slug
    
    // Save the updated URL rewrite options
    update_option('wp_estate_url_rewrites', $rewrite_urls_option);
    
    // Mark this conversion as completed to prevent re-running
    update_option( 'wprentals_convert_comments_to_estate_review_done', true, false );
}