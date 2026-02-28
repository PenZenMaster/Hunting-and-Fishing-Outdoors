<?php
global $post;

$post_id = isset( $post->ID ) ? $post->ID : 0;

if ( function_exists( 'wprentals_render_related_posts' ) ) {
    echo wprentals_render_related_posts( $post_id );
}

