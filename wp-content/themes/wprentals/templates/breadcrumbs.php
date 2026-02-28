<?php
global $post;

$post_id = isset( $post->ID ) ? $post->ID : 0;

if ( function_exists( 'wprentals_render_breadcrumbs' ) ) {
    echo wprentals_render_breadcrumbs( $post_id );
}

