<?php

/*
* change logo on admin login screen
*
*/


function wpestate_admin_login_logo() { 
         ?> <style type="text/css"> 
        body.login div#login h1 a {
            background-image: url(<?php 
                             $logo       =   esc_html(  wprentals_get_option('wp_estate_logo_image', 'url') );
                            if ($logo != '') {
                                    print  esc_url($logo);
                                } else {
                                    print get_template_directory_uri() . '/img/logo.png';
                                };
                                ?>);  //Add your own logo image in this url 
            padding-bottom: 30px; 
            background-position: center center;
            background-repeat: no-repeat;
            color: #444;
            height: 85px;
            width: 161px;
            margin: 0px auto;
            margin-top: 10px;
            background-size: contain;
        }
        body.login {
           background: linear-gradient(43deg, rgba(20,28,21,1) 0%, rgba(184, 129 ,253,1) 100%);
        }
        
        #login {
            padding: 0% 0 0;
            margin: auto;
            background-color: #fff;
            position: absolute;
            padding-bottom: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,.13);
            top: 50%;
            left: 50%;
            margin-left: -160px;
            margin-top: -235px;
        }  
        .login form{
            box-shadow: none;
            padding: 26px 24px 26px;
            margin-top: 0px;
        }
        .interim-login #login {
            margin-left: -160px;
            margin-top: -235px;
            margin-bottom: 0px;
            top: 56%;
        }
        #wp-auth-check-wrap #wp-auth-check {
            max-height: 515px!important;
        }
        .interim-login #login_error, 
        .interim-login.login .message {
            margin: 0px;
        }

</style><?php 
   
 }
 
add_action('login_head', 'wpestate_admin_login_logo');


/*
* Login url  on admin
*
*/
function wpestate_login_logo_url() {
    return esc_url( home_url('/') );
}
add_filter( 'login_headerurl', 'wpestate_login_logo_url' );

/*
* Login url title on admin
*
*/

function wpestate_login_logo_url_title() {
    return esc_html__('Powered by ','wprentals'). esc_url( home_url('/') );
}
add_filter( 'login_headertext', 'wpestate_login_logo_url_title' );


 
add_action( 'wp_ajax_wpestate_disable_licence_notifications', 'wpestate_disable_licence_notifications' );  
if( !function_exists('wpestate_disable_licence_notifications') ):
    function wpestate_disable_licence_notifications(){ 
        check_ajax_referer( 'wprentals_activate_license_nonce', 'security' );
        if(current_user_can('administrator')){
            update_option('wp_estate_disable_notice','yes'); 
            print 'disable';
        }
        die();
    }   
endif;