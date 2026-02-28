<?php

if(isset($userid_agent) && intval($userid_agent)!=0) {
    $useragent_user_id = get_post_meta( $userid_agent, 'user_agent_id', true );
    $user_verified = get_user_meta( $useragent_user_id, 'user_id_verified', true );
    if ( $user_verified ) {
        $user_verified = '<span class="verified-status"><i class="fas fa-square-check"></i> Verified</span>';
    } else {
        $user_verified = '';
    }
    print '<a href="'.esc_url ( get_permalink($userid_agent) ).'" target="_blank" > '. esc_html($author).' '.$user_verified.'</a>';
}else{
    if ( isset($author_id) )   {
        $user_verified = get_user_meta( $author_id, 'user_id_verified', true );
    } else {
        $user_verified = '';
    }
    print esc_html($author);
    if ($user_verified || (isset($author_id) && user_can( $author_id, 'manage_options' ))) {
        print ' <span class="verified-status"><i class="fas fa-square-check"></i> Verified</span>';
    }
}
?>
