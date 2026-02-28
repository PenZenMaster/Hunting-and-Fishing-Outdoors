<?php

function wpestate_comment_columns( $columns ){
    
    // $columns['is_review']   = esc_html__( 'Is Review','wprentals-core' );
    $columns['review_stars'] = esc_html__( 'Stars','wprentals-core' );
    $columns['review_info'] = esc_html__( 'Info','wprentals-core' );
    return $columns;

    
}
add_filter( 'manage_edit-estate_review_columns', 'wpestate_comment_columns' );


function wpestate_comment_column( $column, $comment_ID ){

    if ( get_post_type($comment_ID) !== 'estate_review' ) {
        return;
    }

    $stars =  get_post_meta( $comment_ID , 'review_stars', true );

    if ( 'review_stars' == $column ) {
        if ( ! empty( $stars ) ) {
            if ( is_string( $stars ) ) {
                $decoded = json_decode( $stars, true );
                if ( json_last_error() === JSON_ERROR_NONE ) {
                    $stars = $decoded;
                }
            }

            if ( is_array( $stars ) ) {
                $rating = $stars['rating'] ?? 0;
                if ( ! $rating && ! empty( $stars ) ) {
                    $rating = number_format( round( ( array_sum( $stars ) / count( $stars ) ) * 2 ) / 2, 1 );
                }
            } else {
                $rating = $stars;
            }

            if ( $rating ) {
                print floatval( $rating ) . ' ' . esc_html__( 'stars', 'wprentals-core' );
            } else {
                echo '-';
            }
        } else {
            echo '-';
        }

    } elseif ( 'review_info' == $column ) {
        $comment = get_post($comment_ID);
        $listingID = get_post_meta( $comment_ID , 'attached_to', true );
        $author = get_post_meta( $comment_ID , 'review_author', true );
        
        $noAuthor = false;
        if ( empty( $author ) ) {
            $noAuthor = true;
            $author = 1; // Fallback to current user if no author is set
        }
        $authorObj = get_user_by('id',$author);
        $ownerID = get_post_meta( $listingID , 'property_agent', true );
        $ownerObj = get_user_by('id',$ownerID);
        $owner_label = __('Property Owner:', 'wprentals-core');
        if ( $comment->post_parent != 0 )   {
            $owner_label = __('Property Owner/Author:', 'wprentals-core');
        }
        echo $owner_label . ' ' . esc_html( $ownerObj->display_name ) . '<br/>';
        echo(__('Listing:', 'wprentals-core')) . ' ' . '<a href="'.get_permalink($listingID).'" target="_blank">'.esc_html( get_the_title($listingID) ).'</a><br/>';
        if ( $comment->post_parent == 0 )   {
            echo __( 'Author:', 'wprentals-core' ) . ' ' . ( $noAuthor ? __( 'User deleted', 'wprentals-core' ) : esc_html( $authorObj->display_name ) ) . '<br/>';
        }
    }

}
add_filter( 'manage_estate_review_posts_custom_column', 'wpestate_comment_column', 10, 2 );



//add_filter( 'manage_edit-comments_sortable_columns', 'wpestate_sort_me_comments' );
if( !function_exists('wpestate_sort_me_comments') ):
function wpestate_sort_me_comments( $columns ) {

    $columns['review_stars']        = 'review_stars';
    // $columns['is_review']       = 'is_review';
 
    return $columns;
}
endif; // end   wpestate_sort_me 




// add_filter( 'request', 'wpestate_comments_column_orderby' );
function wpestate_comments_column_orderby( $vars ) {

    if ( $vars['post_type'] !== 'estate_review' ) {
        return $vars;
    }

    // if ( $vars['orderby'] == 'menu_order title' )   {
    //     $vars['orderby'] = 'date';
    //     $vars['order']  = 'DESC';
    // }

   
    // if ( isset( $vars['orderby'] ) && 'review_stars' == $vars['orderby'] ) {
    //     $vars = array_merge( $vars, array(
    //         'meta_key' => 'review_stars',
    //         'orderby' => 'meta_value'
    //     ) );
    // }
    // if ( isset( $vars['orderby'] ) && 'is_review' == $vars['orderby'] ) {
    //     $vars = array_merge( $vars, array(
    //         'meta_key' => 'is_review',
    //         'orderby' => 'meta_value'
    //     ) );
    // }


    return $vars;
}

function wpestate_comment_orderby_date_default( $query ){
    global $pagenow;

    if( ! $query->is_main_query() || 'estate_review' != $query->get( 'post_type' )  )
        return;

    if( is_admin()
        && 'edit.php' == $pagenow
        && !isset( $_GET['orderby'] ) ){echo 'yo';
            $query->set( 'orderby', 'date' );
            $query->set( 'order', 'DESC' );
    }
}
add_action( 'pre_get_posts', 'wpestate_comment_orderby_date_default' );