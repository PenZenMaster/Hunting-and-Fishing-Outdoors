<?php
global $post;

$post_id = isset( $post->ID ) ? $post->ID : 0;

if ( function_exists( 'wprentals_render_postslider' ) ) {
    echo wprentals_render_postslider( $post_id );
}

