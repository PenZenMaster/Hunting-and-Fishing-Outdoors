<?php
/**
 * Sanitizes URL rewrite rules by removing forward slashes
 * 
 * @since 2.0.0
 * @return array Sanitized array of rewrite rules with keys preserved and forward slashes removed from values
 */

if( !function_exists('wpestate_safe_rewite') ):
function wpestate_safe_rewite(){
    $rewrites   =   get_option('wp_estate_url_rewrites',true);
    $return     =   array();

    if(is_array($rewrites)){
        foreach($rewrites as $key=>$value){
            $return[$key] = str_replace('/', '', $value);
        }
    }

    return $return;

}
endif;






if( !function_exists('wpestate_recaptcha_path') ):
function wpestate_recaptcha_path($secret,$captcha){
    return "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".esc_html($_SERVER['REMOTE_ADDR']);
}
endif;


/*
 * Check recapthca 
 * 
 * 
 * 
 * */

if( !function_exists('wpestate_return_recapthca') ):
function wpestate_return_recapthca($secret,$captcha){

    $remoteip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $post_data = http_build_query(
        array(
            'secret' => $secret,
            'response' => $captcha,
            'remoteip' => $remoteip
        ), '', '&'
    );

    $options=array(
        // If site has SSL then
        'ssl'=>array(
            //    'cafile'            => '/path/to/cacert.pem',
            'verify_peer'       => true,
            'verify_peer_name'  => true,
        ),

       'http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $post_data
        )
    );

    $context = stream_context_create( $options );

    $result_json = file_get_contents( $url, false, $context );
    $resulting = json_decode($result_json, true);
    return $resulting;
}
endif;





if( !function_exists('wpestate_disable_filtering') ):
function wpestate_disable_filtering($filter, $function){
    remove_filter($filter, $function);
}
endif;

if( !function_exists('wpestate_disable_filtering2') ):
function wpestate_disable_filtering2($filter, $function,$order='',$params=''){
    remove_filter($filter, $function,$order,$params);
}
endif;


if( !function_exists('wpestate_return_filtered_by_order') ):
function wpestate_return_filtered_by_order($args){
    add_filter( 'posts_orderby', 'wpestate_my_order' );
    $prop_selection = new WP_Query($args);
    remove_filter( 'posts_orderby', 'wpestate_my_order' );
    return $prop_selection;
}
endif;



if( !function_exists('wpestate_search_by_title_only_filter') ):
function wpestate_search_by_title_only_filter($args){
    add_filter( 'posts_search', 'wpestate_search_by_title_only', 500, 2 );
    $prop_selection =   new WP_Query($args);
    remove_filter( 'posts_search', 'wpestate_search_by_title_only', 500 );
    return $prop_selection;
}
endif;




if( !function_exists('wpestate_general_country_list') ):
    function wpestate_general_country_list($selected){


        $countries = wprentals_return_country_array();

        $country_select='<select id="general_country" style="width: 200px;" name="general_country">';

        foreach($countries as $key=>$country){
            $country_select.='<option value="'.$key.'"';
            if($selected==$key){
                $country_select.='selected="selected"';
            }
            $country_select.='>'.$country.'</option>';
        }

        $country_select.='</select>';
        return $country_select;
    }
endif; // end   wpestate_general_country_list




if( !function_exists('wprentals_return_sidebar_array') ):
    function wprentals_return_sidebar_array(){
        $return_array=array();


        foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
            $return_array[$sidebar['id']]=ucwords($sidebar['name']);
        }
        return $return_array;
    }
endif;




if ( ! function_exists( 'wprentals_update_option' ) ):
    function wprentals_update_option( $theme_option,  $value ,$option = false) {
        global $wprentals_admin;
        if($option){
            $option_array   =   array($option=>$value);
            Redux::set_option('wprentals_admin',$theme_option, $option_array);
        }else{
            Redux::set_option('wprentals_admin',$theme_option, $value);
        }
    }
endif;



if ( ! function_exists( 'wprentals_get_option' ) ):
    function wprentals_get_option( $theme_option,  $option = false ,$in_case_not = false) {

        global $wprentals_admin;
        $theme_option=trim($theme_option);
        if(!function_exists('wpestate_reverse_convert_redux_wp_estate_custom_fields')){
            return '';
        }
   

        if($theme_option=='wpestate_currency'){
            $return = wpestate_reverse_convert_redux_wp_estate_multi_curr();
            return $return;
        }else if($theme_option=='wpestate_custom_fields_list'){
            $return = wpestate_reverse_convert_redux_wp_estate_custom_fields();
             usort($return, function($a, $b) {
                return intval($a['3']) - intval($b['3']);
            });
            return $return;
        }else if($theme_option=='wp_estate_property_page_header'){
            $return = wpestate_reverse_convert_redux_wp_estate_property_page_header();
            return $return;
        }else if($theme_option=='wp_estate_custom_listing_fields'){
            $return = wpestate_reverse_convert_redux_wp_estate_custom_listing_fields();
            return $return;
        }else if($theme_option=='wp_estate_custom_infobox_fields'){
            $return = wpestate_reverse_convert_redux_wpestate_convert_redux_wp_estate_custom_infobox_fields();
            return $return;
        }


        if( isset( $wprentals_admin[$theme_option]) && $wprentals_admin[$theme_option]!='' ){
            $return=$wprentals_admin[$theme_option];
            if($option && isset($wprentals_admin[$theme_option][$option])){
                $return = $wprentals_admin[$theme_option][$option];
            }
        }else{
            $return=$in_case_not;
        }

        return $return;

    }
endif;



//////////////////////////////////////////////////////////////////////////////
/// Ajax adv search contact function
////////////////////////////////////////////////////////////////////////////////
add_action( 'wp_ajax_nopriv_wpestate_ajax_agent_contact_page', 'wpestate_ajax_agent_contact_page' );
add_action( 'wp_ajax_wpestate_ajax_agent_contact_page', 'wpestate_ajax_agent_contact_page' );

if( !function_exists('wpestate_ajax_agent_contact_page') ):

    function wpestate_ajax_agent_contact_page(){


        // check for POST vars
        $message        =   '';
        $hasError       =   false;
        $allowed_html   =   array();
        $to_print       =   '';
        $email          =   '';

        if ( !wp_verify_nonce( $_POST['nonce'], 'ajax-property-contact')) {
            exit("No naughty business please");
        }
        
   
        $is_elementor_contact_builder =  isset($_POST['is_elementor']) ? intval($_POST['is_elementor']) : 0;

        if($is_elementor_contact_builder==0){
            if ( isset($_POST['name']) ) {
                if( trim($_POST['name']) =='' || trim($_POST['name']) ==esc_html__( 'Your Name','wprentals-core') ){
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'The name field is empty !','wprentals-core') ));
                    exit();
                }else {
                    $name = wp_kses( trim($_POST['name']),$allowed_html );
                }
            }


            //Check email
            if ( isset($_POST['email']) || trim($_POST['email']) ==esc_html__( 'Your Email','wprentals-core') ) {
                  if( trim($_POST['email']) ==''){
                        echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'The email field is empty','wprentals-core' ) ) );
                        exit();
                  } else if( filter_var($_POST['email'],FILTER_VALIDATE_EMAIL) === false) {
                        echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'The email doesn\'t look right !','wprentals-core') ) );
                        exit();
                  } else {
                        $email = wp_kses( trim($_POST['email']),$allowed_html );
                  }
            }

            //Check comments
            if ( isset($_POST['comment']) ) {
                  if( trim($_POST['comment']) =='' || trim($_POST['comment']) ==esc_html__( 'Your Message','wprentals-core')){
                    echo json_encode(array('sent'=>false, 'response'=>esc_html__( 'Your message is empty !','wprentals-core') ) );
                    exit();
                  }else {
                    $comment = wp_kses($_POST['comment'] ,$allowed_html );
                  }
            }


          $message    .=  esc_html__('Client Name','wprentals-core').": " . $name . PHP_EOL;
          $message    .=  esc_html__('Email','wprentals-core').": " . $email . PHP_EOL;
          if(isset($_POST['website'])){
              $website = wp_kses( trim($_POST['website']),$allowed_html );
              $message    .=  esc_html__('Website','wprentals-core').": " . $website . PHP_EOL;
          }

        }else{

            if ( isset($_POST['email']) ) {
                $email = wp_kses( trim($_POST['email']),$allowed_html );
            }

            if ( isset($_POST['comment']) ) {
                $comment = wp_kses($_POST['comment'] ,$allowed_html );
                $comment = str_replace('/n',PHP_EOL,$comment);
                $comment = str_replace('booking_to_date',  esc_html__('Booking To Date','wprentals-core'),$comment);
                $comment = str_replace('booking_from_date',esc_html__('Booking From Date','wprentals-core'),$comment);
            }
        }


        $subject =esc_html__( 'Contact form from ','wprentals-core') . esc_url( home_url('/') ) ;

        $receiver_email = esc_html( wprentals_get_option('wp_estate_email_adr', '') );



        //  $message    .=  esc_html__('Phone','wprentals-core').": " . $phone . PHP_EOL;
        //  $message    .=  esc_html__('Subject','wprentals-core').": " . $subject . PHP_EOL;
        $message    .=  esc_html__('Message','wprentals-core').": ".PHP_EOL." " . $comment. PHP_EOL;
        $message    .=  esc_html__('Message sent from contact page','wprentals-core'). PHP_EOL;


        $headers = 'From: No Reply <noreply@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";

        if(isset($_POST['elementor_email_subject'])){
          $subject        = sanitize_text_field( $_POST['elementor_email_subject']);
        }



        if(class_exists('WpestateEmail')){
            $WpestateEmail = WpestateEmail::get_instance();
            $sending_Email = $WpestateEmail->wpestate_send_email_contact($receiver_email,$subject,$message,$email);

        }

     
        echo json_encode(array('sent'=>true,'data'=>$sending_Email, 'response'=>esc_html__( 'The message was sent!','wprentals-core') ) );
    die();
}

endif; // end   wpestate_ajax_agent_contact_form






/*
 *  Print invoice ajax function 
 * 
 * 
 * 
 * 
 * 
 **/


add_action( 'wp_ajax_ajax_create_print', 'ajax_create_print' );

  if( !function_exists('ajax_create_print') ):
  function ajax_create_print(){
    check_ajax_referer( 'wprentals_print_invoice_nonce', 'security' );
    if(!isset($_POST['invoice_id'])|| !is_numeric($_POST['invoice_id'])){
        exit('out pls1');
    }

    $invoice_id          = intval($_POST['invoice_id']);
    $the_post= get_post( $invoice_id);
    if($the_post->post_type!='wpestate_invoice' || $the_post->post_status!='publish'){
        exit('out pls2');
    }
    $title= esc_html__('Invoice','wprentals-core');

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    // end get agent details
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    print  '<html><head><title>'.$title.'</title><link href="'.get_stylesheet_uri().'" rel="stylesheet" type="text/css" />';


    if(is_child_theme()){
        print '<link href="'.get_template_directory_uri().'/style.css" rel="stylesheet" type="text/css" />';
    }

    if(is_rtl()){
        print '<link href="'.get_template_directory_uri().'/rtl.css" rel="stylesheet" type="text/css" />';
    }
    print '</head>';
    $protocol = is_ssl() ? 'https' : 'http';
    print  '<script src="'.$protocol.'://code.jquery.com/jquery-1.10.1.min.js"></script><script>$(window).load(function(){ print(); });</script>';
    print  '<body class="print_body" >';





     wpestate_super_invoice_details($invoice_id,'yes');

    print '</body></html>';
    die();
  }

endif;



/*
 *  Print trip ajax function 
 * 
 * 
 * 
 * 
 * 
 **/


add_action( 'wp_ajax_wprentals_ajax_create_print_trip', 'wprentals_ajax_create_print_trip' );

  if( !function_exists('wprentals_ajax_create_print_trip') ):
  function wprentals_ajax_create_print_trip(){
 
    $current_user = wp_get_current_user();
    $userID                         =   $current_user->ID;

        
    if(!isset($_POST['property_id'])|| !is_numeric($_POST['property_id'])){
        exit('out pls1');
    }
      if(!isset($_POST['booking_id'])|| !is_numeric($_POST['booking_id'])){
        exit('out pls1');
    }
    
    
    $bookid =intval($_POST['booking_id']);
    $property_id =intval($_POST['property_id']);
    
    
    $the_post= get_post( $bookid);
    $book_author=$the_post->post_author;
        
  
    if( $book_author!=$userID){
        exit('out pls');
    }

  
    
    $title= esc_html__('Your trip details','wprentals-core');

    /////////////////////////////////////////////////////////////////////////////////////////////////////
    // end get agent details
    /////////////////////////////////////////////////////////////////////////////////////////////////////

    print  '<html><head><title>'.$title.'</title><link href="'.get_stylesheet_uri().'" rel="stylesheet" type="text/css" />';


    if(is_child_theme()){
        print '<link href="'.get_template_directory_uri().'/style.css" rel="stylesheet" type="text/css" />';
    }

    if(is_rtl()){
        print '<link href="'.get_template_directory_uri().'/rtl.css" rel="stylesheet" type="text/css" />';
    }
    print '</head>';
    $protocol = is_ssl() ? 'https' : 'http';
    print  '<script src="'.$protocol.'://code.jquery.com/jquery-1.10.1.min.js"></script><script>$(window).load(function(){ print(); });</script>';
    print  '<body class="print_body" >';


    wpestate_generate_trip_details($property_id,'',$bookid,$mode='');

    print '</body></html>';
    die();
  }

endif;








if ( ! function_exists( 'wpestate_admin_bar_menu' ) ) {
	function wpestate_admin_bar_menu() {
            global $wp_admin_bar;
            $theme_data = wp_get_theme();


            if ( ! current_user_can( 'manage_options' ) || ! is_admin_bar_showing() ) {
                return;
            }



             $wp_admin_bar->add_menu(array(
                    'id' => 'clear_cache',
                    'title' => __( 'Clear WpRentals Cache', 'wprentals-core' ),
                    'href' => wp_nonce_url( esc_url( admin_url( 'admin-post.php?action=wpestate_purge_cache') ) , 'theme_purge_cache' ),
            ));

	}
}
add_action( 'admin_bar_menu', 'wpestate_admin_bar_menu', 100 );


////////////////////////////////////////////////////////////////////////////////
/// Add new profile fields
////////////////////////////////////////////////////////////////////////////////

add_filter('user_contactmethods', 'wpestate_modify_contact_methods');
if (!function_exists('wpestate_modify_contact_methods')):

    function wpestate_modify_contact_methods($profile_fields) {

        // Add new fields
        $profile_fields['facebook']                     = esc_html__('Facebook','wprentals-core');
        $profile_fields['twitter']                      = esc_html__('X - Twitter','wprentals-core');
        $profile_fields['linkedin']                     = esc_html__('Linkedin','wprentals-core');
        $profile_fields['pinterest']                    = esc_html__('Pinterest','wprentals-core');
        $profile_fields['instagram']                    = esc_html__('Instagram','wprentals-core');
        $profile_fields['youtube']                      = esc_html__('Youtube','wprentals-core');
        $profile_fields['phone']                        = esc_html__('Phone','wprentals-core');
        $profile_fields['mobile']                       = esc_html__('Mobile','wprentals-core');
        $profile_fields['check_phone_valid']            = esc_html__('Mobile Verified','wprentals-core');
        $profile_fields['skype']                        = esc_html__('Skype','wprentals-core');
        $profile_fields['website']                      = esc_html__('Website','wprentals-core');
        $profile_fields['title']                        = esc_html__('Title/Position','wprentals-core');
        $profile_fields['custom_picture']               = esc_html__('Picture Url','wprentals-core');
        $profile_fields['small_custom_picture']         = esc_html__('Small Picture Url','wprentals-core');
        $profile_fields['package_id']                   = esc_html__('Package Id','wprentals-core');
        $profile_fields['package_activation']           = esc_html__('Package Activation','wprentals-core');
        $profile_fields['package_listings']             = esc_html__('Listings available','wprentals-core');
        $profile_fields['package_featured_listings']    = esc_html__('Featured Listings available','wprentals-core');
        $profile_fields['paypal_agreement']             = esc_html__('Paypal Recurring Profile - rest api','wprentals-core');
        $profile_fields['profile_id']                   = esc_html__('Paypal Recurring Profile','wprentals-core');
        $profile_fields['user_agent_id']                = esc_html__('User Owner Id','wprentals-core');
        $profile_fields['stripe']                       = esc_html__( 'Stripe Consumer Profile','wprentals-core');
        $profile_fields['stripe_subscription_id']       = esc_html__( 'Stripe Subscription Id','wprentals-core');
        $profile_fields['has_stripe_recurring']         = esc_html__( 'Has Recurring Stripe ','wprentals-core');
        $profile_fields['i_speak']                      = esc_html__('I Speak','wprentals-core');
        $profile_fields['live_in']                      = esc_html__('Live In','wprentals-core');
        $profile_fields['payment_info']                 = esc_html__('Payment Info','wprentals-core');
        $profile_fields['user_type']                    = esc_html__('User Type(0-can rent and book / 1 can only book)','wprentals-core');
        return $profile_fields;
    }

endif; // end   wpestate_modify_contact_methods


function wpestate_core_add_to_footer(){
    $ga = esc_html(wprentals_get_option('wp_estate_google_analytics_code', ''));
    if ($ga != '') { ?>
     <!-- Global site  tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', '<?php echo esc_html($ga); ?>');
        </script>


    <?php }
}

if( !function_exists('wprentals_return_country_array') ):
function wprentals_return_country_array(){
    $countries = array(     'Afghanistan'           => esc_html__('Afghanistan','wprentals-core'),
                            'Albania'               => esc_html__('Albania','wprentals-core'),
                            'Algeria'               => esc_html__('Algeria','wprentals-core'),
                            'American Samoa'        => esc_html__('American Samoa','wprentals-core'),
                            'Andorra'               => esc_html__('Andorra','wprentals-core'),
                            'Angola'                => esc_html__('Angola','wprentals-core'),
                            'Anguilla'              => esc_html__('Anguilla','wprentals-core'),
                            'Antarctica'            => esc_html__('Antarctica','wprentals-core'),
                            'Antigua and Barbuda'   => esc_html__('Antigua and Barbuda','wprentals-core'),
                            'Argentina'             => esc_html__('Argentina','wprentals-core'),
                            'Armenia'               => esc_html__('Armenia','wprentals-core'),
                            'Aruba'                 => esc_html__('Aruba','wprentals-core'),
                            'Australia'             => esc_html__('Australia','wprentals-core'),
                            'Austria'               => esc_html__('Austria','wprentals-core'),
                            'Azerbaijan'            => esc_html__('Azerbaijan','wprentals-core'),
                            'Bahamas'               => esc_html__('Bahamas','wprentals-core'),
                            'Bahrain'               => esc_html__('Bahrain','wprentals-core'),
                            'Bangladesh'            => esc_html__('Bangladesh','wprentals-core'),
                            'Barbados'              => esc_html__('Barbados','wprentals-core'),
                            'Belarus'               => esc_html__('Belarus','wprentals-core'),
                            'Belgium'               => esc_html__('Belgium','wprentals-core'),
                            'Belize'                => esc_html__('Belize','wprentals-core'),
                            'Benin'                 => esc_html__('Benin','wprentals-core'),
                            'Bermuda'               => esc_html__('Bermuda','wprentals-core'),
                            'Bhutan'                => esc_html__('Bhutan','wprentals-core'),
                            'Bolivia'               => esc_html__('Bolivia','wprentals-core'),
                            'Bosnia and Herzegowina'=> esc_html__('Bosnia and Herzegowina','wprentals-core'),
                            'Botswana'              => esc_html__('Botswana','wprentals-core'),
                            'Bouvet Island'         => esc_html__('Bouvet Island','wprentals-core'),
                            'Brazil'                => esc_html__('Brazil','wprentals-core'),
                            'British Indian Ocean Territory'=> esc_html__('British Indian Ocean Territory','wprentals-core'),
                            'Brunei Darussalam'     => esc_html__('Brunei Darussalam','wprentals-core'),
                            'Bulgaria'              => esc_html__('Bulgaria','wprentals-core'),
                            'Burkina Faso'          => esc_html__('Burkina Faso','wprentals-core'),
                            'Burundi'               => esc_html__('Burundi','wprentals-core'),
                            'Cambodia'              => esc_html__('Cambodia','wprentals-core'),
                            'Cameroon'              => esc_html__('Cameroon','wprentals-core'),
                            'Canada'                => esc_html__('Canada','wprentals-core'),
                            'Cape Verde'            => esc_html__('Cape Verde','wprentals-core'),
                            'Cayman Islands'        => esc_html__('Cayman Islands','wprentals-core'),
                            'Central African Republic'  => esc_html__('Central African Republic','wprentals-core'),
                            'Chad'                  => esc_html__('Chad','wprentals-core'),
                            'Chile'                 => esc_html__('Chile','wprentals-core'),
                            'China'                 => esc_html__('China','wprentals-core'),
                            'Christmas Island'      => esc_html__('Christmas Island','wprentals-core'),
                            'Cocos (Keeling) Islands' => esc_html__('Cocos (Keeling) Islands','wprentals-core'),
                            'Colombia'              => esc_html__('Colombia','wprentals-core'),
                            'Comoros'               => esc_html__('Comoros','wprentals-core'),
                            'Congo'                 => esc_html__('Congo','wprentals-core'),
                            'Congo, the Democratic Republic of the' => esc_html__('Congo, the Democratic Republic of the','wprentals-core'),
                            'Cook Islands'          => esc_html__('Cook Islands','wprentals-core'),
                            'Costa Rica'            => esc_html__('Costa Rica','wprentals-core'),
                            'Cote dIvoire'          => esc_html__('Cote dIvoire','wprentals-core'),
                            'Croatia'               => esc_html__('Croatia','wprentals-core'),
                            'Cuba'                  => esc_html__('Cuba','wprentals-core'),
                            'Curacao'               => esc_html__('Curacao','wprentals-core'),
                            'Cyprus'                => esc_html__('Cyprus','wprentals-core'),
                            'Czech Republic'        => esc_html__('Czech Republic','wprentals-core'),
                            'Denmark'               => esc_html__('Denmark','wprentals-core'),
                            'Djibouti'              => esc_html__('Djibouti','wprentals-core'),
                            'Dominica'              => esc_html__('Dominica','wprentals-core'),
                            'Dominican Republic'    => esc_html__('Dominican Republic','wprentals-core'),
                            'East Timor'            => esc_html__('East Timor','wprentals-core'),
                            'Ecuador'               => esc_html__('Ecuador','wprentals-core'),
                            'Egypt'                 => esc_html__('Egypt','wprentals-core'),
                            'El Salvador'           => esc_html__('El Salvador','wprentals-core'),
                            'Equatorial Guinea'     => esc_html__('Equatorial Guinea','wprentals-core'),
                            'Eritrea'               => esc_html__('Eritrea','wprentals-core'),
                            'Estonia'               => esc_html__('Estonia','wprentals-core'),
                            'Ethiopia'              => esc_html__('Ethiopia','wprentals-core'),
                            'Falkland Islands (Malvinas)' => esc_html__('Falkland Islands (Malvinas)','wprentals-core'),
                            'Faroe Islands'         => esc_html__('Faroe Islands','wprentals-core'),
                            'Fiji'                  => esc_html__('Fiji','wprentals-core'),
                            'Finland'               => esc_html__('Finland','wprentals-core'),
                            'France'                => esc_html__('France','wprentals-core'),
                            'France Metropolitan'   => esc_html__('France Metropolitan','wprentals-core'),
                            'French Guiana'         => esc_html__('French Guiana','wprentals-core'),
                            'French Polynesia'      => esc_html__('French Polynesia','wprentals-core'),
                            'French Southern Territories' => esc_html__('French Southern Territories','wprentals-core'),
                            'Gabon'                 => esc_html__('Gabon','wprentals-core'),
                            'Gambia'                => esc_html__('Gambia','wprentals-core'),
                            'Georgia'               => esc_html__('Georgia','wprentals-core'),
                            'Germany'               => esc_html__('Germany','wprentals-core'),
                            'Ghana'                 => esc_html__('Ghana','wprentals-core'),
                            'Gibraltar'             => esc_html__('Gibraltar','wprentals-core'),
                            'Greece'                => esc_html__('Greece','wprentals-core'),
                            'Greenland'             => esc_html__('Greenland','wprentals-core'),
                            'Grenada'               => esc_html__('Grenada','wprentals-core'),
                            'Guadeloupe'            => esc_html__('Guadeloupe','wprentals-core'),
                            'Guam'                  => esc_html__('Guam','wprentals-core'),
                            'Guatemala'             => esc_html__('Guatemala','wprentals-core'),
                            'Guinea'                => esc_html__('Guinea','wprentals-core'),
                            'Guinea-Bissau'         => esc_html__('Guinea-Bissau','wprentals-core'),
                            'Guyana'                => esc_html__('Guyana','wprentals-core'),
                            'Haiti'                 => esc_html__('Haiti','wprentals-core'),
                            'Heard and Mc Donald Islands'  => esc_html__('Heard and Mc Donald Islands','wprentals-core'),
                            'Holy See (Vatican City State)'=> esc_html__('Holy See (Vatican City State)','wprentals-core'),
                            'Honduras'              => esc_html__('Honduras','wprentals-core'),
                            'Hong Kong'             => esc_html__('Hong Kong','wprentals-core'),
                            'Hungary'               => esc_html__('Hungary','wprentals-core'),
                            'Iceland'               => esc_html__('Iceland','wprentals-core'),
                            'India'                 => esc_html__('India','wprentals-core'),
                            'Indonesia'             => esc_html__('Indonesia','wprentals-core'),
                            'Iran (Islamic Republic of)'  => esc_html__('Iran (Islamic Republic of)','wprentals-core'),
                            'Iraq'                  => esc_html__('Iraq','wprentals-core'),
                            'Ireland'               => esc_html__('Ireland','wprentals-core'),
                            'Israel'                => esc_html__('Israel','wprentals-core'),
                            'Italy'                 => esc_html__('Italy','wprentals-core'),
                            'Island of Saba'        => esc_html__('Island of Saba','wprentals-core'),
                            'Jamaica'               => esc_html__('Jamaica','wprentals-core'),
                            'Japan'                 => esc_html__('Japan','wprentals-core'),
                            'Jordan'                => esc_html__('Jordan','wprentals-core'),
                            'Kazakhstan'            => esc_html__('Kazakhstan','wprentals-core'),
                            'Kenya'                 => esc_html__('Kenya','wprentals-core'),
                            'Kiribati'              => esc_html__('Kiribati','wprentals-core'),
                            'Korea, Democratic People Republic of'  => esc_html__('Korea, Democratic People Republic of','wprentals-core'),
                            'Korea, Republic of'    => esc_html__('Korea, Republic of','wprentals-core'),
                            'Kosovo'                => esc_html__('Kosovo', 'wprentals-core'),
                            'Kuwait'                => esc_html__('Kuwait','wprentals-core'),
                            'Kyrgyzstan'            => esc_html__('Kyrgyzstan','wprentals-core'),
                            'Lao, People Democratic Republic' => esc_html__('Lao, People Democratic Republic','wprentals-core'),
                            'Latvia'                => esc_html__('Latvia','wprentals-core'),
                            'Lebanon'               => esc_html__('Lebanon','wprentals-core'),
                            'Lesotho'               => esc_html__('Lesotho','wprentals-core'),
                            'Liberia'               => esc_html__('Liberia','wprentals-core'),
                            'Libyan Arab Jamahiriya'=> esc_html__('Libyan Arab Jamahiriya','wprentals-core'),
                            'Liechtenstein'         => esc_html__('Liechtenstein','wprentals-core'),
                            'Lithuania'             => esc_html__('Lithuania','wprentals-core'),
                            'Luxembourg'            => esc_html__('Luxembourg','wprentals-core'),
                            'Macau'                 => esc_html__('Macau','wprentals-core'),
                            'Macedonia, The Former Yugoslav Republic of'    => esc_html__('Macedonia, The Former Yugoslav Republic of','wprentals-core'),
                            'Madagascar'            => esc_html__('Madagascar','wprentals-core'),
                            'Malawi'                => esc_html__('Malawi','wprentals-core'),
                            'Malaysia'              => esc_html__('Malaysia','wprentals-core'),
                            'Maldives'              => esc_html__('Maldives','wprentals-core'),
                            'Mali'                  => esc_html__('Mali','wprentals-core'),
                            'Malta'                 => esc_html__('Malta','wprentals-core'),
                            'Marshall Islands'      => esc_html__('Marshall Islands','wprentals-core'),
                            'Martinique'            => esc_html__('Martinique','wprentals-core'),
                            'Mauritania'            => esc_html__('Mauritania','wprentals-core'),
                            'Mauritius'             => esc_html__('Mauritius','wprentals-core'),
                            'Mayotte'               => esc_html__('Mayotte','wprentals-core'),
                            'Mexico'                => esc_html__('Mexico','wprentals-core'),
                            'Micronesia, Federated States of'    => esc_html__('Micronesia, Federated States of','wprentals-core'),
                            'Moldova, Republic of'  => esc_html__('Moldova, Republic of','wprentals-core'),
                            'Monaco'                => esc_html__('Monaco','wprentals-core'),
                            'Mongolia'              => esc_html__('Mongolia','wprentals-core'),
                            'Montserrat'            => esc_html__('Montserrat','wprentals-core'),
                            'Morocco'               => esc_html__('Morocco','wprentals-core'),
                            'Mozambique'            => esc_html__('Mozambique','wprentals-core'),
                            'Montenegro'            => esc_html__('Montenegro','wprentals-core'),
                            'Myanmar'               => esc_html__('Myanmar','wprentals-core'),
                            'Namibia'               => esc_html__('Namibia','wprentals-core'),
                            'Nauru'                 => esc_html__('Nauru','wprentals-core'),
                            'Nepal'                 => esc_html__('Nepal','wprentals-core'),
                            'Netherlands'           => esc_html__('Netherlands','wprentals-core'),
                            'Netherlands Antilles'  => esc_html__('Netherlands Antilles','wprentals-core'),
                            'New Caledonia'         => esc_html__('New Caledonia','wprentals-core'),
                            'New Zealand'           => esc_html__('New Zealand','wprentals-core'),
                            'Nicaragua'             => esc_html__('Nicaragua','wprentals-core'),
                            'Niger'                 => esc_html__('Niger','wprentals-core'),
                            'Nigeria'               => esc_html__('Nigeria','wprentals-core'),
                            'Niue'                  => esc_html__('Niue','wprentals-core'),
                            'Norfolk Island'        => esc_html__('Norfolk Island','wprentals-core'),
                            'Northern Mariana Islands' => esc_html__('Northern Mariana Islands','wprentals-core'),
                            'Norway'                => esc_html__('Norway','wprentals-core'),
                            'Oman'                  => esc_html__('Oman','wprentals-core'),
                            'Pakistan'              => esc_html__('Pakistan','wprentals-core'),
                            'Palau'                 => esc_html__('Palau','wprentals-core'),
                            'Panama'                => esc_html__('Panama','wprentals-core'),
                            'Papua New Guinea'      => esc_html__('Papua New Guinea','wprentals-core'),
                            'Paraguay'              => esc_html__('Paraguay','wprentals-core'),
                            'Peru'                  => esc_html__('Peru','wprentals-core'),
                            'Philippines'           => esc_html__('Philippines','wprentals-core'),
                            'Pitcairn'              => esc_html__('Pitcairn','wprentals-core'),
                            'Poland'                => esc_html__('Poland','wprentals-core'),
                            'Portugal'              => esc_html__('Portugal','wprentals-core'),
                            'Puerto Rico'           => esc_html__('Puerto Rico','wprentals-core'),
                            'Qatar'                 => esc_html__('Qatar','wprentals-core'),
                            'Reunion'               => esc_html__('Reunion','wprentals-core'),
                            'Romania'               => esc_html__('Romania','wprentals-core'),
                            'Russian Federation'    => esc_html__('Russian Federation','wprentals-core'),
                            'Rwanda'                => esc_html__('Rwanda','wprentals-core'),
                            'Saint Kitts and Nevis' => esc_html__('Saint Kitts and Nevis','wprentals-core'),
                            'Saint Lucia'           => esc_html__('Saint Lucia','wprentals-core'),
                            'Saint Vincent and the Grenadines' => esc_html__('Saint Vincent and the Grenadines','wprentals-core'),
                            'Saint Barthélemy'      => esc_html__('Saint Barthélemy','wprentals-core'),
                            'Saint Martin'          => esc_html__('Saint Martin','wprentals-core'),
                            'Sint Maarten'          => esc_html__('Sint Maarten','wprentals-core'),
                            'Samoa'                 => esc_html__('Samoa','wprentals-core'),
                            'San Marino'            => esc_html__('San Marino','wprentals-core'),
                            'Sao Tome and Principe' => esc_html__('Sao Tome and Principe','wprentals-core'),
                            'Saudi Arabia'          => esc_html__('Saudi Arabia','wprentals-core'),
                            'Serbia'                => esc_html__('Serbia','wprentals-core'),
                            'Senegal'               => esc_html__('Senegal','wprentals-core'),
                            'Seychelles'            => esc_html__('Seychelles','wprentals-core'),
                            'Sierra Leone'          => esc_html__('Sierra Leone','wprentals-core'),
                            'Singapore'             => esc_html__('Singapore','wprentals-core'),
                            'Slovakia (Slovak Republic)'=> esc_html__('Slovakia (Slovak Republic)','wprentals-core'),
                            'Slovenia'              => esc_html__('Slovenia','wprentals-core'),
                            'Solomon Islands'       => esc_html__('Solomon Islands','wprentals-core'),
                            'Somalia'               => esc_html__('Somalia','wprentals-core'),
                            'South Africa'          => esc_html__('South Africa','wprentals-core'),
                            'South Georgia and the South Sandwich Islands' => esc_html__('South Georgia and the South Sandwich Islands','wprentals-core'),
                            'Spain'                 => esc_html__('Spain','wprentals-core'),
                            'Sri Lanka'             => esc_html__('Sri Lanka','wprentals-core'),
                            'St. Helena'            => esc_html__('St. Helena','wprentals-core'),
                            'St. Pierre and Miquelon'=> esc_html__('St. Pierre and Miquelon','wprentals-core'),
                            'Sudan'                 => esc_html__('Sudan','wprentals-core'),
                            'Suriname'              => esc_html__('Suriname','wprentals-core'),
                            'Svalbard and Jan Mayen Islands'    => esc_html__('Svalbard and Jan Mayen Islands','wprentals-core'),
                            'Swaziland'             => esc_html__('Swaziland','wprentals-core'),
                            'Sweden'                => esc_html__('Sweden','wprentals-core'),
                            'Switzerland'           => esc_html__('Switzerland','wprentals-core'),
                            'Syrian Arab Republic'  => esc_html__('Syrian Arab Republic','wprentals-core'),
                            'Taiwan, Province of China' => esc_html__('Taiwan, Province of China','wprentals-core'),
                            'Tajikistan'            => esc_html__('Tajikistan','wprentals-core'),
                            'Tanzania, United Republic of'=> esc_html__('Tanzania, United Republic of','wprentals-core'),
                            'Thailand'              => esc_html__('Thailand','wprentals-core'),
                            'Togo'                  => esc_html__('Togo','wprentals-core'),
                            'Tokelau'               => esc_html__('Tokelau','wprentals-core'),
                            'Tonga'                 => esc_html__('Tonga','wprentals-core'),
                            'Trinidad and Tobago'   => esc_html__('Trinidad and Tobago','wprentals-core'),
                            'Tunisia'               => esc_html__('Tunisia','wprentals-core'),
                            'Turkey'                => esc_html__('Turkey','wprentals-core'),
                            'Turkmenistan'          => esc_html__('Turkmenistan','wprentals-core'),
                            'Turks and Caicos Islands'  => esc_html__('Turks and Caicos Islands','wprentals-core'),
                            'Tuvalu'                => esc_html__('Tuvalu','wprentals-core'),
                            'Uganda'                => esc_html__('Uganda','wprentals-core'),
                            'Ukraine'               => esc_html__('Ukraine','wprentals-core'),
                            'United Arab Emirates'  => esc_html__('United Arab Emirates','wprentals-core'),
                            'United Kingdom'        => esc_html__('United Kingdom','wprentals-core'),
                            'United States'         => esc_html__('United States','wprentals-core'),
                            'United States Minor Outlying Islands'  => esc_html__('United States Minor Outlying Islands','wprentals-core'),
                            'Uruguay'               => esc_html__('Uruguay','wprentals-core'),
                            'Uzbekistan'            => esc_html__('Uzbekistan','wprentals-core'),
                            'Vanuatu'               => esc_html__('Vanuatu','wprentals-core'),
                            'Venezuela'             => esc_html__('Venezuela','wprentals-core'),
                            'Vietnam'               => esc_html__('Vietnam','wprentals-core'),
                            'Virgin Islands (British)'=> esc_html__('Virgin Islands (British)','wprentals-core'),
                            'Virgin Islands (U.S.)' => esc_html__('Virgin Islands (U.S.)','wprentals-core'),
                            'Wallis and Futuna Islands' => esc_html__('Wallis and Futuna Islands','wprentals-core'),
                            'Western Sahara'        => esc_html__('Western Sahara','wprentals-core'),
                            'Yemen'                 => esc_html__('Yemen','wprentals-core'),
                            'Yugoslavia'            => esc_html__('Yugoslavia','wprentals-core'),
                            'Zambia'                => esc_html__('Zambia','wprentals-core'),
                            'Zimbabwe'              => esc_html__('Zimbabwe','wprentals-core')
    );
    return $countries;

}
endif;









if(!function_exists('wprentals_return_google_fonts')):
function wprentals_return_google_fonts(){
    $google_fonts_array = array(
          "Inter"=>"Inter",
            "Manrope"=>"Manrope",
            "Abel" => "Abel",
            "Abril Fatface" => "Abril Fatface",
            "Aclonica" => "Aclonica",
            "Acme" => "Acme",
            "Actor" => "Actor",
            "Adamina" => "Adamina",
            "Advent Pro" => "Advent Pro",
            "Aguafina Script" => "Aguafina Script",
            "Aladin" => "Aladin",
            "Aldrich" => "Aldrich",
            "Alegreya" => "Alegreya",
            "Alegreya SC" => "Alegreya SC",
            "Alex Brush" => "Alex Brush",
            "Alfa Slab One" => "Alfa Slab One",
            "Alice" => "Alice",
            "Alike" => "Alike",
            "Alike Angular" => "Alike Angular",
            "Allan" => "Allan",
            "Allerta" => "Allerta",
            "Allerta Stencil" => "Allerta Stencil",
            "Allura" => "Allura",
            "Almendra" => "Almendra",
            "Almendra SC" => "Almendra SC",
            "Amaranth" => "Amaranth",
            "Amatic SC" => "Amatic SC",
            "Amethysta" => "Amethysta",
            "Andada" => "Andada",
            "Andika" => "Andika",
            "Angkor" => "Angkor",
            "Annie Use Your Telescope" => "Annie Use Your Telescope",
            "Anonymous Pro" => "Anonymous Pro",
            "Antic" => "Antic",
            "Antic Didone" => "Antic Didone",
            "Antic Slab" => "Antic Slab",
            "Anton" => "Anton",
            "Arapey" => "Arapey",
            "Arbutus" => "Arbutus",
            "Architects Daughter" => "Architects Daughter",
            "Arimo" => "Arimo",
            "Arizonia" => "Arizonia",
            "Armata" => "Armata",
            "Artifika" => "Artifika",
            "Arvo" => "Arvo",
            "Asap" => "Asap",
            "Asset" => "Asset",
            "Astloch" => "Astloch",
            "Asul" => "Asul",
            "Atomic Age" => "Atomic Age",
            "Aubrey" => "Aubrey",
            "Audiowide" => "Audiowide",
            "Average" => "Average",
            "Averia Gruesa Libre" => "Averia Gruesa Libre",
            "Averia Libre" => "Averia Libre",
            "Averia Sans Libre" => "Averia Sans Libre",
            "Averia Serif Libre" => "Averia Serif Libre",
            "Bad Script" => "Bad Script",
            "Balthazar" => "Balthazar",
            "Bangers" => "Bangers",
            "Basic" => "Basic",
            "Battambang" => "Battambang",
            "Baumans" => "Baumans",
            "Bayon" => "Bayon",
            "Belgrano" => "Belgrano",
            "Belleza" => "Belleza",
            "Bentham" => "Bentham",
            "Berkshire Swash" => "Berkshire Swash",
            "Bevan" => "Bevan",
            "Bigshot One" => "Bigshot One",
            "Bilbo" => "Bilbo",
            "Bilbo Swash Caps" => "Bilbo Swash Caps",
            "Bitter" => "Bitter",
            "Black Ops One" => "Black Ops One",
            "Bokor" => "Bokor",
            "Bonbon" => "Bonbon",
            "Boogaloo" => "Boogaloo",
            "Bowlby One" => "Bowlby One",
            "Bowlby One SC" => "Bowlby One SC",
            "Brawler" => "Brawler",
            "Bree Serif" => "Bree Serif",
            "Bubblegum Sans" => "Bubblegum Sans",
            "Buda" => "Buda",
            "Buenard" => "Buenard",
            "Butcherman" => "Butcherman",
            "Butterfly Kids" => "Butterfly Kids",
            "Cabin" => "Cabin",
            "Cabin Condensed" => "Cabin Condensed",
            "Cabin Sketch" => "Cabin Sketch",
            "Caesar Dressing" => "Caesar Dressing",
            "Cagliostro" => "Cagliostro",
            "Calligraffitti" => "Calligraffitti",
            "Cambo" => "Cambo",
            "Candal" => "Candal",
            "Cantarell" => "Cantarell",
            "Cantata One" => "Cantata One",
            "Cardo" => "Cardo",
            "Carme" => "Carme",
            "Carter One" => "Carter One",
            "Caudex" => "Caudex",
            "Cedarville Cursive" => "Cedarville Cursive",
            "Ceviche One" => "Ceviche One",
            "Changa One" => "Changa One",
            "Chango" => "Chango",
            "Chau Philomene One" => "Chau Philomene One",
            "Chelsea Market" => "Chelsea Market",
            "Chenla" => "Chenla",
            "Cherry Cream Soda" => "Cherry Cream Soda",
            "Chewy" => "Chewy",
            "Chicle" => "Chicle",
            "Chivo" => "Chivo",
            "Coda" => "Coda",
            "Coda Caption" => "Coda Caption",
            "Codystar" => "Codystar",
            "Comfortaa" => "Comfortaa",
            "Coming Soon" => "Coming Soon",
            "Concert One" => "Concert One",
            "Condiment" => "Condiment",
            "Content" => "Content",
            "Contrail One" => "Contrail One",
            "Convergence" => "Convergence",
            "Cookie" => "Cookie",
            "Copse" => "Copse",
            "Corben" => "Corben",
            "Cousine" => "Cousine",
            "Coustard" => "Coustard",
            "Covered By Your Grace" => "Covered By Your Grace",
            "Crafty Girls" => "Crafty Girls",
            "Creepster" => "Creepster",
            "Crete Round" => "Crete Round",
            "Crimson Text" => "Crimson Text",
            "Crushed" => "Crushed",
            "Cuprum" => "Cuprum",
            "Cutive" => "Cutive",
            "Damion" => "Damion",
            "Dancing Script" => "Dancing Script",
            "Dangrek" => "Dangrek",
            "Dawning of a New Day" => "Dawning of a New Day",
            "Days One" => "Days One",
            "Delius" => "Delius",
            "Delius Swash Caps" => "Delius Swash Caps",
            "Delius Unicase" => "Delius Unicase",
            "Della Respira" => "Della Respira",
            "Devonshire" => "Devonshire",
            "Didact Gothic" => "Didact Gothic",
            "Diplomata" => "Diplomata",
            "Diplomata SC" => "Diplomata SC",
            "Doppio One" => "Doppio One",
            "Dorsa" => "Dorsa",
            "Dosis" => "Dosis",
            "Dr Sugiyama" => "Dr Sugiyama",
            "Droid Sans" => "Droid Sans",
            "Droid Sans Mono" => "Droid Sans Mono",
            "Droid Serif" => "Droid Serif",
            "Duru Sans" => "Duru Sans",
            "Dynalight" => "Dynalight",
            "EB Garamond" => "EB Garamond",
            "Eater" => "Eater",
            "Economica" => "Economica",
            "Electrolize" => "Electrolize",
            "Emblema One" => "Emblema One",
            "Emilys Candy" => "Emilys Candy",
            "Engagement" => "Engagement",
            "Enriqueta" => "Enriqueta",
            "Erica One" => "Erica One",
            "Esteban" => "Esteban",
            "Euphoria Script" => "Euphoria Script",
            "Ewert" => "Ewert",
            "Exo" => "Exo",
            "Expletus Sans" => "Expletus Sans",
            "Fanwood Text" => "Fanwood Text",
            "Fascinate" => "Fascinate",
            "Fascinate Inline" => "Fascinate Inline",
            "Federant" => "Federant",
            "Federo" => "Federo",
            "Felipa" => "Felipa",
            "Fjord One" => "Fjord One",
            "Flamenco" => "Flamenco",
            "Flavors" => "Flavors",
            "Fondamento" => "Fondamento",
            "Fontdiner Swanky" => "Fontdiner Swanky",
            "Forum" => "Forum",
            "Francois One" => "Francois One",
            "Fredericka the Great" => "Fredericka the Great",
            "Fredoka One" => "Fredoka One",
            "Freehand" => "Freehand",
            "Fresca" => "Fresca",
            "Frijole" => "Frijole",
            "Fugaz One" => "Fugaz One",
            "GFS Didot" => "GFS Didot",
            "GFS Neohellenic" => "GFS Neohellenic",
            "Galdeano" => "Galdeano",
            "Gentium Basic" => "Gentium Basic",
            "Gentium Book Basic" => "Gentium Book Basic",
            "Geo" => "Geo",
            "Geostar" => "Geostar",
            "Geostar Fill" => "Geostar Fill",
            "Germania One" => "Germania One",
            "Give You Glory" => "Give You Glory",
            "Glass Antiqua" => "Glass Antiqua",
            "Glegoo" => "Glegoo",
            "Gloria Hallelujah" => "Gloria Hallelujah",
            "Goblin One" => "Goblin One",
            "Gochi Hand" => "Gochi Hand",
            "Gorditas" => "Gorditas",
            "Goudy Bookletter 1911" => "Goudy Bookletter 1911",
            "Graduate" => "Graduate",
            "Gravitas One" => "Gravitas One",
            "Great Vibes" => "Great Vibes",
            "Gruppo" => "Gruppo",
            "Gudea" => "Gudea",
            "Habibi" => "Habibi",
            "Hammersmith One" => "Hammersmith One",
            "Handlee" => "Handlee",
            "Hanuman" => "Hanuman",
            "Happy Monkey" => "Happy Monkey",
            "Henny Penny" => "Henny Penny",
            "Herr Von Muellerhoff" => "Herr Von Muellerhoff",
            "Holtwood One SC" => "Holtwood One SC",
            "Homemade Apple" => "Homemade Apple",
            "Homenaje" => "Homenaje",
            "IM Fell DW Pica" => "IM Fell DW Pica",
            "IM Fell DW Pica SC" => "IM Fell DW Pica SC",
            "IM Fell Double Pica" => "IM Fell Double Pica",
            "IM Fell Double Pica SC" => "IM Fell Double Pica SC",
            "IM Fell English" => "IM Fell English",
            "IM Fell English SC" => "IM Fell English SC",
            "IM Fell French Canon" => "IM Fell French Canon",
            "IM Fell French Canon SC" => "IM Fell French Canon SC",
            "IM Fell Great Primer" => "IM Fell Great Primer",
            "IM Fell Great Primer SC" => "IM Fell Great Primer SC",
            "Iceberg" => "Iceberg",
            "Iceland" => "Iceland",
            "Imprima" => "Imprima",
            "Inconsolata" => "Inconsolata",
            "Inder" => "Inder",
            "Indie Flower" => "Indie Flower",
            "Inika" => "Inika",
            "Irish Grover" => "Irish Grover",
            "Istok Web" => "Istok Web",
            "Italiana" => "Italiana",
            "Italianno" => "Italianno",
            "Jim Nightshade" => "Jim Nightshade",
            "Jockey One" => "Jockey One",
            "Jolly Lodger" => "Jolly Lodger",
            "Josefin Sans" => "Josefin Sans",
            "Josefin Slab" => "Josefin Slab",
            "Judson" => "Judson",
            "Julee" => "Julee",
            "Junge" => "Junge",
            "Jura" => "Jura",
            "Just Another Hand" => "Just Another Hand",
            "Just Me Again Down Here" => "Just Me Again Down Here",
            "Kameron" => "Kameron",
            "Karla" => "Karla",
            "Kaushan Script" => "Kaushan Script",
            "Kelly Slab" => "Kelly Slab",
            "Kenia" => "Kenia",
            "Khmer" => "Khmer",
            "Knewave" => "Knewave",
            "Kotta One" => "Kotta One",
            "Koulen" => "Koulen",
            "Kranky" => "Kranky",
            "Kreon" => "Kreon",
            "Kristi" => "Kristi",
            "Krona One" => "Krona One",
            "La Belle Aurore" => "La Belle Aurore",
            "Lancelot" => "Lancelot",
            "Lato" => "Lato",
            "League Script" => "League Script",
            "Leckerli One" => "Leckerli One",
            "Ledger" => "Ledger",
            "Lekton" => "Lekton",
            "Lemon" => "Lemon",
            "Lilita One" => "Lilita One",
            "Limelight" => "Limelight",
            "Linden Hill" => "Linden Hill",
            "Lobster" => "Lobster",
            "Lobster Two" => "Lobster Two",
            "Londrina Outline" => "Londrina Outline",
            "Londrina Shadow" => "Londrina Shadow",
            "Londrina Sketch" => "Londrina Sketch",
            "Londrina Solid" => "Londrina Solid",
            "Lora" => "Lora",
            "Love Ya Like A Sister" => "Love Ya Like A Sister",
            "Loved by the King" => "Loved by the King",
            "Lovers Quarrel" => "Lovers Quarrel",
            "Luckiest Guy" => "Luckiest Guy",
            "Lusitana" => "Lusitana",
            "Lustria" => "Lustria",
            "Macondo" => "Macondo",
            "Macondo Swash Caps" => "Macondo Swash Caps",
            "Magra" => "Magra",
            "Maiden Orange" => "Maiden Orange",
            "Mako" => "Mako",
            "Marck Script" => "Marck Script",
            "Marko One" => "Marko One",
            "Marmelad" => "Marmelad",
            "Marvel" => "Marvel",
            "Mate" => "Mate",
            "Mate SC" => "Mate SC",
            "Maven Pro" => "Maven Pro",
            "Meddon" => "Meddon",
            "MedievalSharp" => "MedievalSharp",
            "Medula One" => "Medula One",
            "Megrim" => "Megrim",
            "Merienda One" => "Merienda One",
            "Merriweather" => "Merriweather",
            "Metal" => "Metal",
            "Metamorphous" => "Metamorphous",
            "Metrophobic" => "Metrophobic",
            "Michroma" => "Michroma",
            "Miltonian" => "Miltonian",
            "Miltonian Tattoo" => "Miltonian Tattoo",
            "Miniver" => "Miniver",
            "Miss Fajardose" => "Miss Fajardose",
            "Modern Antiqua" => "Modern Antiqua",
            "Molengo" => "Molengo",
            "Monofett" => "Monofett",
            "Monoton" => "Monoton",
            "Monsieur La Doulaise" => "Monsieur La Doulaise",
            "Montaga" => "Montaga",
            "Montez" => "Montez",
            "Montserrat" => "Montserrat",
            "Moul" => "Moul",
            "Moulpali" => "Moulpali",
            "Mountains of Christmas" => "Mountains of Christmas",
            "Mr Bedfort" => "Mr Bedfort",
            "Mr Dafoe" => "Mr Dafoe",
            "Mr De Haviland" => "Mr De Haviland",
            "Mrs Saint Delafield" => "Mrs Saint Delafield",
            "Mrs Sheppards" => "Mrs Sheppards",
            "Muli" => "Muli",
            "Mystery Quest" => "Mystery Quest",
            "Neucha" => "Neucha",
            "Neuton" => "Neuton",
            "News Cycle" => "News Cycle",
            "Niconne" => "Niconne",
            "Nixie One" => "Nixie One",
            "Nobile" => "Nobile",
            "Nokora" => "Nokora",
            "Norican" => "Norican",
            "Nosifer" => "Nosifer",
            "Nothing You Could Do" => "Nothing You Could Do",
            "Noticia Text" => "Noticia Text",
            "Nova Cut" => "Nova Cut",
            "Nova Flat" => "Nova Flat",
            "Nova Mono" => "Nova Mono",
            "Nova Oval" => "Nova Oval",
            "Nova Round" => "Nova Round",
            "Nova Script" => "Nova Script",
            "Nova Slim" => "Nova Slim",
            "Nova Square" => "Nova Square",
            "Numans" => "Numans",
            "Nunito" => "Nunito",
            "Odor Mean Chey" => "Odor Mean Chey",
            "Old Standard TT" => "Old Standard TT",
            "Oldenburg" => "Oldenburg",
            "Oleo Script" => "Oleo Script",
            "Open Sans" => "Open Sans",
            "Open Sans Condensed" => "Open Sans Condensed",
            "Orbitron" => "Orbitron",
            "Original Surfer" => "Original Surfer",
            "Oswald" => "Oswald",
            "Over the Rainbow" => "Over the Rainbow",
            "Overlock" => "Overlock",
            "Overlock SC" => "Overlock SC",
            "Ovo" => "Ovo",
            "Oxygen" => "Oxygen",
            "PT Mono" => "PT Mono",
            "PT Sans" => "PT Sans",
            "PT Sans Caption" => "PT Sans Caption",
            "PT Sans Narrow" => "PT Sans Narrow",
            "PT Serif" => "PT Serif",
            "PT Serif Caption" => "PT Serif Caption",
            "Pacifico" => "Pacifico",
            "Parisienne" => "Parisienne",
            "Passero One" => "Passero One",
            "Passion One" => "Passion One",
            "Patrick Hand" => "Patrick Hand",
            "Patua One" => "Patua One",
            "Paytone One" => "Paytone One",
            "Permanent Marker" => "Permanent Marker",
            "Petrona" => "Petrona",
            "Philosopher" => "Philosopher",
            "Piedra" => "Piedra",
            "Pinyon Script" => "Pinyon Script",
            "Plaster" => "Plaster",
            "Play" => "Play",
            "Playball" => "Playball",
            "Playfair Display" => "Playfair Display",
            "Podkova" => "Podkova",
            "Poiret One" => "Poiret One",
            "Poller One" => "Poller One",
            "Poly" => "Poly",
            "Pompiere" => "Pompiere",
            "Pontano Sans" => "Pontano Sans",
            "Port Lligat Sans" => "Port Lligat Sans",
            "Port Lligat Slab" => "Port Lligat Slab",
            "Prata" => "Prata",
            "Preahvihear" => "Preahvihear",
            "Press Start 2P" => "Press Start 2P",
            "Princess Sofia" => "Princess Sofia",
            "Prociono" => "Prociono",
            "Prosto One" => "Prosto One",
            "Puritan" => "Puritan",
            "Quantico" => "Quantico",
            "Quattrocento" => "Quattrocento",
            "Quattrocento Sans" => "Quattrocento Sans",
            "Questrial" => "Questrial",
            "Quicksand" => "Quicksand",
            "Qwigley" => "Qwigley",
            "Radley" => "Radley",
            "Raleway" => "Raleway",
            "Rammetto One" => "Rammetto One",
            "Rancho" => "Rancho",
            "Rationale" => "Rationale",
            "Redressed" => "Redressed",
            "Reenie Beanie" => "Reenie Beanie",
            "Revalia" => "Revalia",
            "Ribeye" => "Ribeye",
            "Ribeye Marrow" => "Ribeye Marrow",
            "Righteous" => "Righteous",
            "Rochester" => "Rochester",
            "Rock Salt" => "Rock Salt",
            "Rokkitt" => "Rokkitt",
            "Ropa Sans" => "Ropa Sans",
            "Rosario" => "Rosario",
            "Rosarivo" => "Rosarivo",
            "Rouge Script" => "Rouge Script",
            "Ruda" => "Ruda",
            "Ruge Boogie" => "Ruge Boogie",
            "Ruluko" => "Ruluko",
            "Ruslan Display" => "Ruslan Display",
            "Russo One" => "Russo One",
            "Ruthie" => "Ruthie",
            "Sail" => "Sail",
            "Salsa" => "Salsa",
            "Sancreek" => "Sancreek",
            "Sansita One" => "Sansita One",
            "Sarina" => "Sarina",
            "Satisfy" => "Satisfy",
            "Schoolbell" => "Schoolbell",
            "Seaweed Script" => "Seaweed Script",
            "Sevillana" => "Sevillana",
            "Shadows Into Light" => "Shadows Into Light",
            "Shadows Into Light Two" => "Shadows Into Light Two",
            "Shanti" => "Shanti",
            "Share" => "Share",
            "Shojumaru" => "Shojumaru",
            "Short Stack" => "Short Stack",
            "Siemreap" => "Siemreap",
            "Sigmar One" => "Sigmar One",
            "Signika" => "Signika",
            "Signika Negative" => "Signika Negative",
            "Simonetta" => "Simonetta",
            "Sirin Stencil" => "Sirin Stencil",
            "Six Caps" => "Six Caps",
            "Slackey" => "Slackey",
            "Smokum" => "Smokum",
            "Smythe" => "Smythe",
            "Sniglet" => "Sniglet",
            "Snippet" => "Snippet",
            "Sofia" => "Sofia",
            "Sonsie One" => "Sonsie One",
            "Sorts Mill Goudy" => "Sorts Mill Goudy",
            "Special Elite" => "Special Elite",
            "Spicy Rice" => "Spicy Rice",
            "Spinnaker" => "Spinnaker",
            "Spirax" => "Spirax",
            "Squada One" => "Squada One",
            "Stardos Stencil" => "Stardos Stencil",
            "Stint Ultra Condensed" => "Stint Ultra Condensed",
            "Stint Ultra Expanded" => "Stint Ultra Expanded",
            "Stoke" => "Stoke",
            "Sue Ellen Francisco" => "Sue Ellen Francisco",
            "Sunshiney" => "Sunshiney",
            "Supermercado One" => "Supermercado One",
            "Suwannaphum" => "Suwannaphum",
            "Swanky and Moo Moo" => "Swanky and Moo Moo",
            "Syncopate" => "Syncopate",
            "Tangerine" => "Tangerine",
            "Taprom" => "Taprom",
            "Telex" => "Telex",
            "Tenor Sans" => "Tenor Sans",
            "The Girl Next Door" => "The Girl Next Door",
            "Tienne" => "Tienne",
            "Tinos" => "Tinos",
            "Titan One" => "Titan One",
            "Trade Winds" => "Trade Winds",
            "Trocchi" => "Trocchi",
            "Trochut" => "Trochut",
            "Trykker" => "Trykker",
            "Tulpen One" => "Tulpen One",
            "Ubuntu" => "Ubuntu",
            "Ubuntu Condensed" => "Ubuntu Condensed",
            "Ubuntu Mono" => "Ubuntu Mono",
            "Ultra" => "Ultra",
            "Uncial Antiqua" => "Uncial Antiqua",
            "UnifrakturCook" => "UnifrakturCook",
            "UnifrakturMaguntia" => "UnifrakturMaguntia",
            "Unkempt" => "Unkempt",
            "Unlock" => "Unlock",
            "Unna" => "Unna",
            "VT323" => "VT323",
            "Varela" => "Varela",
            "Varela Round" => "Varela Round",
            "Vast Shadow" => "Vast Shadow",
            "Vibur" => "Vibur",
            "Vidaloka" => "Vidaloka",
            "Viga" => "Viga",
            "Voces" => "Voces",
            "Volkhov" => "Volkhov",
            "Vollkorn" => "Vollkorn",
            "Voltaire" => "Voltaire",
            "Waiting for the Sunrise" => "Waiting for the Sunrise",
            "Wallpoet" => "Wallpoet",
            "Walter Turncoat" => "Walter Turncoat",
            "Wellfleet" => "Wellfleet",
            "Wire One" => "Wire One",
            "Yanone Kaffeesatz" => "Yanone Kaffeesatz",
            "Yellowtail" => "Yellowtail",
            "Yeseva One" => "Yeseva One",
            "Yesteryear" => "Yesteryear",
            "Zeyada" => "Zeyada",
    );
    return $google_fonts_array;
}
endif;




/*
 *
 * Country list for map limitation
 *
 *
 *
 *
 */


function wpestate_country_list_code(){
    $countries = array(
    'US' => 'United States',
    'CA' => 'Canada',
    'AU' => 'Australia',
    'FR' => 'France',
    'DE' => 'Germany',
    'IS' => 'Iceland',
    'IE' => 'Ireland',
    'IT' => 'Italy',
    'ES' => 'Spain',
    'SE' => 'Sweden',
    'AT' => 'Austria',
    'BE' => 'Belgium',
    'FI' => 'Finland',
    'CZ' => 'Czech Republic',
    'DK' => 'Denmark',
    'NO' => 'Norway',
    'GB' => 'United Kingdom',
    'CH' => 'Switzerland',
    'NZ' => 'New Zealand',
    'RU' => 'Russian Federation',
    'PT' => 'Portugal',
    'NL' => 'Netherlands',
    'IM' => 'Isle of Man',
    'AF' => 'Afghanistan',
    'AX' => 'Aland Islands ',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AS' => 'American Samoa',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AQ' => 'Antarctica',
    'AG' => 'Antigua and Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia, Plurinational State of',
    'BQ' => 'Bonaire, Sint Eustatius and Saba',
    'BA' => 'Bosnia and Herzegovina',
    'BW' => 'Botswana',
    'BV' => 'Bouvet Island',
    'BR' => 'Brazil',
    'IO' => 'British Indian Ocean Territory',
    'BN' => 'Brunei Darussalam',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island',
    'CC' => 'Cocos (Keeling) Islands',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo',
    'CD' => 'Congo, the Democratic Republic of the',
    'CK' => 'Cook Islands',
    'CR' => 'Costa Rica',
    'CI' => 'Cote d\'Ivoire',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CW' => 'Curaçao',
    'CY' => 'Cyprus',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands (Malvinas)',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'TF' => 'French Southern Territories',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GU' => 'Guam',
    'GT' => 'Guatemala',
    'GG' => 'Guernsey',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HM' => 'Heard Island and McDonald Islands',
    'VA' => 'Holy See (Vatican City State)',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong',
    'HU' => 'Hungary',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran, Islamic Republic of',
    'IQ' => 'Iraq',
    'IL' => 'Israel',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JE' => 'Jersey',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KP' => 'Korea, Democratic People\'s Republic of',
    'KR' => 'Korea, Republic of',
    'KV' => 'kosovo',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Lao People\'s Democratic Republic',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libyan Arab Jamahiriya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macao',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte',
    'MX' => 'Mexico',
    'FM' => 'Micronesia, Federated States of',
    'MD' => 'Moldova, Republic of',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NC' => 'New Caledonia',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'NU' => 'Niue',
    'NF' => 'Norfolk Island',
    'MP' => 'Northern Mariana Islands',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PS' => 'Palestinian Territory, Occupied',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn',
    'PL' => 'Poland',
    'PR' => 'Puerto Rico',
    'QA' => 'Qatar',
    'RE' => 'Reunion',
    'RO' => 'Romania',
    'RW' => 'Rwanda',
    'BL' => 'Saint Barthélemy',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts and Nevis',
    'LC' => 'Saint Lucia',
    'MF' => 'Saint Martin (French part)',
    'PM' => 'Saint Pierre and Miquelon',
    'VC' => 'Saint Vincent and the Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome and Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SX' => 'Sint Maarten (Dutch part)',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'GS' => 'South Georgia and the South Sandwich Islands',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SJ' => 'Svalbard and Jan Mayen',
    'SZ' => 'Swaziland',
    'SY' => 'Syrian Arab Republic',
    'TW' => 'Taiwan, Province of China',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania, United Republic of',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TK' => 'Tokelau',
    'TO' => 'Tonga',
    'TT' => 'Trinidad and Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks and Caicos Islands',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'UM' => 'United States Minor Outlying Islands',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VE' => 'Venezuela, Bolivarian Republic of',
    'VN' => 'Viet Nam',
    'VG' => 'Virgin Islands, British',
    'VI' => 'Virgin Islands, U.S.',
    'WF' => 'Wallis and Futuna',
    'EH' => 'Western Sahara',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe'
);

return $countries;
}
