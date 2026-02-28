<?php
$userId         =   get_post_meta($comment->ID, 'review_author', true);
         
if($userId == 1){
    $reviewer_name="admin";
    $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
}else{
    $userid_agent   =   get_user_meta($userId, 'user_agent_id', true);
    $reviewer_name  =   get_sanitized_truncated_title($userid_agent, 0);
    if($userid_agent==''){
        $authorObj = get_user_by( 'id', $userId );
        if ( $authorObj ) {
            $reviewer_name = $authorObj->display_name;
        } else {
            $reviewer_name = esc_html__( 'Unknown User', 'wprentals' );
        }
    }
    
}

if($userid_agent==''){
    $user_small_picture_id     =    get_the_author_meta( 'small_custom_picture' ,  $userId,true  );
    $preview                   =    wp_get_attachment_image_src($user_small_picture_id,'wpestate_user_thumb');
    $preview_img               =    '';
    if(isset($preview[0])){
        $preview_img               =    $preview[0];
    }
  
}else{
    $thumb_id           = get_post_thumbnail_id($userid_agent);
    $preview            = wp_get_attachment_image_src($thumb_id, 'thumbnail');
    $preview_img               =    '';
    if(isset($preview[0])){
        $preview_img               =    $preview[0];
    }
}

if($preview_img==''){
    $preview_img = wprentals_get_option('wp_estate_default_user_image', 'url');
    if ( empty($preview_img) ) {
        $preview_img = get_stylesheet_directory_uri().'/img/default-user.png';
    }
}

$rating= get_post_meta( $comment->ID , 'review_stars', true );
?>


    <div class="reviewer_image"  style="background-image: url(<?php echo esc_url($preview_img);?>);"></div>
    
    <div class="reviwer-name-wrapper">    
        <div class="reviwer-name">
            <?php echo esc_html($reviewer_name);?>
        </div>
        <div class="property_ratings">
            <?php
            print wpestate_display_rating($rating, 'total');
            $total_rating = wpestate_get_star_total_rating($rating);
            ?>
            <span class="ratings-star">( <?php print $total_rating . ' ' . esc_html__( 'of','wprentals').' 5'; ?>)
            </span>
        </div>
    </div>
 
    <div class="review-date">
        <?php echo esc_html__( 'Posted on ','wprentals' ). ' '. get_the_date('j F Y',$comment->ID);?>
    </div>
    
    
    <div class="review-content">
        <?php print  $comment->post_content;?>       
    </div>

    <?php
        $owner_reply_review = get_posts( array( 'post_type' => 'estate_review', 'post_parent' => $comment->ID, 'post_status' => 'any' ) );
        // $owner_reply = get_post_meta($comment->ID,'owner_reply',true);   
    ?>

        <div class="wpestate-repy-review-wrapper">
        
            <h4><?php esc_html_e('Your Reply','wprentals');?></h4>
            <div class="wpestate_reply_to_review_message"></div>

            <?php if ( !empty( $owner_reply_review ) )  { ?>
                <?php if ( $owner_reply_review[0]->post_status != 'publish' ) { ?>
                    <div class="wpestate_reply_to_review_content waiting-approval" style="display:block;"><?php esc_html_e('Your reply is waiting for approval.','wprentals');?></div>
                <?php } ?>
                    <div class="wpestate_reply_to_review_content" style="display:block;" ><?php echo esc_html($owner_reply_review[0]->post_content);?></div>
            <?php }else{ ?>

                <?php if ( $comment->post_status == 'publish' ) { ?>

                    <div class="wpestate_reply_to_review_content"></div>
                    <textarea autocomplete="off" rows="4" class="review_reply_content form-control" placeholder="<?php esc_html_e('type your reply','wprentals');?>"></textarea>
                    <span class="mess_send_reply_review_button" data-review-reply-to-id="<?php print intval($comment->ID);?>" data-review-reply-to-propertyid="<?php print intval($propertyId);?>">
                        <?php esc_html_e('Reply to Review','wprentals');?>        
                    </span>

                <?php } else { ?>

                    <div class="wpestate_reply_to_review_content"><?php esc_html_e('This review has not been approved yet.','wprentals');?></div>
                    
                <?php } ?>
            <?php } ?>


        </div>
        <?php

    ?>


   