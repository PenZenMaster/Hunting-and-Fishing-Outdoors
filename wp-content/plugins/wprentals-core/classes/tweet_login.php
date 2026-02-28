<?php

/**
 * Description of tweet_login
 *
 * @author cretu remus
 */

use Abraham\TwitterOAuth\TwitterOAuth;

class Wpestate_Social_Login {
    

    //put your code here
    
    private $twitter_consumer_key;
    private $twitter_consumer_secret;
    private $twitter_access_token;
    private $twitter_access_secret;
    private $redirect;      
    private $facebook_status;
    private $google_status;
    private $twiter_status;
    private $facebook_api;
    private $facebook_secret;
    private $google_client_id;
    private $google_client_secret;
    private $google_developer_key;
    private $twitter_url;
    
 
    
    function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
       
        
     
        $this->twitter_url                = '';
        $this->twitter_consumer_key       = trim( wprentals_get_option('wp_estate_twitter_consumer_key','') );
        $this->twitter_consumer_secret    = trim( wprentals_get_option('wp_estate_twitter_consumer_secret','') );
        $this->twitter_access_token       = trim( wprentals_get_option('wp_estate_twitter_access_token','') );
        $this->twitter_access_secret      = trim( wprentals_get_option('wp_estate_twitter_access_secret','') );
        $this->redirect                   = trim( wpestate_get_template_link('user_dashboard_profile.php') );
        $this->facebook_status            = trim( esc_html( wprentals_get_option('wp_estate_facebook_login','') ) );
        $this->google_status              = trim( esc_html( wprentals_get_option('wp_estate_google_login','') ) );
        $this->twiter_status              = trim( esc_html( wprentals_get_option('wp_estate_twiter_login','') ) );
        $this->facebook_api               = trim( esc_html ( wprentals_get_option('wp_estate_facebook_api','') ) );
        $this->facebook_secret            = trim( esc_html ( wprentals_get_option('wp_estate_facebook_secret','') ) );
        $this->google_client_id           = trim( esc_html ( wprentals_get_option('wp_estate_google_oauth_api','') )) ;
        $this->google_client_secret       = trim( esc_html ( wprentals_get_option('wp_estate_google_oauth_client_secret','') ) );
        $this->google_developer_key       = trim( esc_html ( wprentals_get_option('wp_estate_google_api_key','') ) );
       
        add_action( 'wp_ajax_wpestate_social_login_generate_link', array($this,'wpestate_social_login_generate_link') );
        add_action( 'wp_ajax_nopriv_wpestate_social_login_generate_link', array($this,'wpestate_social_login_generate_link') );
  
     
     }
    
    /*
    *
    *
    *  
    */
     
    function wpestate_social_login_generate_link(){
        
        check_ajax_referer( 'wpestate_social_login_nonce', 'nonce' );
        $social_type=esc_html($_POST['social_type']);
        
        if($social_type=='facebook'){
            print $this->return_facebook_url();
        }else  if($social_type=='google'){
            print $this->return_google_url();
        }else   if($social_type=='twitter'){
            print $this->return_twiter_url();
        }
       die();
    }
     
    /*
    *
    *
    *  
    */
    
    function display_form($where,$return=''){
        
        $to_return  ='';
        $appendix   ='';
        
        if($where   ==    'mobile'){
           $appendix ='_mb';
        } else if($where    ==  'widget'    ){
            $appendix ='_wd';
        }else if(   $where ==  'short'  ){
            $appendix ='_sh';
        }else if(   $where=='short_reg' ){
            $appendix ='_sh_reg';
        }else if(   $where=='register' ){
            $appendix ='_reg';
        }
        
         
        if($this->facebook_status=='yes'){
            $to_return.= '<div class="wpestate_social_login" id="facebooklogin'.$appendix.'" data-social="facebook"><i class="fab fa-facebook-f"></i> '.esc_html__( 'Login with Facebook','wprentals-core').'</div>';
        }
        
        if($this->google_status=='yes'){
            $to_return.= '<div class="wpestate_social_login"  id="googlelogin'.$appendix.'" data-social="google"><i class="fab fa-google"></i>'.esc_html__( 'Login with Google','wprentals-core').'</div>';
        }
        
        if($this->twiter_status=='yes'){
            $to_return.= '<div class="wpestate_social_login"  id="twitterlogin'.$appendix.'" data-social="twitter"><i class="fa-brands fa-x-twitter"></i>'.esc_html__( 'Login with Twitter','wprentals-core').'</div>';
        }
        
        $nonce = wp_create_nonce( 'wpestate_social_login_nonce' );
        $to_return.='<input type="hidden" class="wpestate_social_login_nonce" value="'.$nonce.'">';
        
        if($return==1){
            return $to_return;
        }else{
            print  $to_return;
        }
    }
    
    
    
    
    
    
    /*
    *
    *
    *  
    */
    
    
    
    function return_twiter_url(){
        
     
            $connection         = new TwitterOAuth( $this->twitter_consumer_key,  $this->twitter_consumer_secret,  $this->twitter_access_token,  $this->twitter_access_secret); 

            $request_token      = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->redirect));
            $oauth_token        = $request_token['oauth_token'];
            $oauth_token_secret = $request_token['oauth_token_secret'];


        
            $_SESSION['token_tw']              =   $oauth_token;
            $_SESSION['token_secret_tw']       =   $oauth_token_secret;
            $_SESSION['wpestate_is_twet']   =   'ison';
            $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
            $this->twitter_url=$url;
          
           
            return $url;
        
    }
    

    
    
    /*
    *
    *
    *  
    */
    
    
    
    function twiter_authentificate_user(){
      
        if(!isset($_SESSION['token_tw']) || $_SESSION['token_tw']=='' || !isset($_SESSION['token_secret_tw']) || $_SESSION['token_secret_tw']==''    ){
            return;
        }
        
    
        $twClient = new TwitterOAuth($this->twitter_consumer_key, $this->twitter_consumer_secret, esc_html($_SESSION['token_tw']) , esc_html($_SESSION['token_secret_tw']) ); 
        $params = array(
            'oauth_verifier' => esc_html($_REQUEST['oauth_verifier'])
        );
        
        $access_token = $twClient->oauth('oauth/access_token', $params );
        $params = array('include_email' => 'true', 'include_entities' => 'false', 'skip_status' => 'true');
        
        $twitter  = new TwitterOAuth($this->twitter_consumer_key,$this->twitter_consumer_secret,$access_token['oauth_token'],$access_token['oauth_token_secret']);
        $user_info= $twitter->get('account/verify_credentials',$params); 
        
       
        unset($_SESSION['token_tw']); 
        unset($_SESSION['token_secret_tw']); 
        unset($_SESSION['wpestate_is_twet']); 
        unset($_SESSION['wpestate_is_fb']); 
        unset($_SESSION['wpestate_is_google']); 
        
        $email                  =   $user_info->email;
        $full_name              =   $user_info->screen_name;
        $openid_identity_code   =   $user_info->id;

        $name = explode(" ",$full_name); 
        $firsname = isset($name[0])?$name[0]:''; 
        $lastname = isset($name[1])?$name[1]:''; 
        
    
        $this->create_or_login_user ($email,$full_name,$openid_identity_code,$firsname,$lastname);
    }
    
    
    
    
    
    
    /*
    *
    *
    *  
    */
      
    function return_facebook_url(){ 
     
   
        $fb = new Facebook\Facebook([
            'app_id'                =>      $this->facebook_api,
            'app_secret'            =>      $this->facebook_secret,
            'default_graph_version' =>      'v2.12',
        ]);
    
        if( isset($_POST['propid'])){
            $prop_id=intval ( $_POST['propid'] );
        }else{
            $prop_id=0;
        }
    
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // optional
      
        $loginUrl = $helper->getLoginUrl(    $this->redirect   , $permissions);
        $_SESSION['wpestate_is_fb']   =   'ison';
        return  $loginUrl;
        
    }
      
    /*
    *
    *
    *  
    */
    
    function facebook_authentificate_user(){
   
        $fb = new Facebook\Facebook([
            'app_id'  => $this->facebook_api,
            'app_secret' => $this->facebook_secret,
            'default_graph_version' => 'v2.12',
        ]);
        
        $helper = $fb->getRedirectLoginHelper();
        

        $secret      =   $this->facebook_secret;
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
             // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
        exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
    

        // Logged in
   

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);


        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->facebook_api); 

        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
              $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
              echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
              exit;
            }

        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,email,name,first_name,last_name', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $response->getGraphUser();

    
        if(isset($user['name'])){
            $full_name=$user['name'];
        }
        if(isset($user['email'])){
            $email=$user['email'];
        }
        $identity_code=$secret.$user['id']; 
        
    
        unset($_SESSION['wpestate_is_twet']); 
        unset($_SESSION['wpestate_is_fb']); 
        unset($_SESSION['wpestate_is_google']); 
        
        $this->create_or_login_user($email,$full_name,$identity_code,$user['first_name'],$user['last_name']); 
    }

    /*
    *
    *
    *  
    */
    function return_google_url() {
        require_once dirname(__FILE__) . '/../vendor/autoload.php';

        try {
            $client = new \Google\Auth\OAuth2([
                'clientId' => $this->google_client_id,
                'clientSecret' => $this->google_client_secret,
                'redirectUri' => $this->redirect,
                'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
                'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
            ]);

            $auth_url = $client->buildFullAuthorizationUri([
                'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
                'response_type' => 'code'
            ]);

            $_SESSION['wpestate_is_google'] = 'ison';
         
            return $auth_url;

        } catch (Exception $e) {
            error_log('Google Auth Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /*
    *
    *
    *  
    */
    function google_authentificate_user() {
        require_once dirname(__FILE__) . '/../vendor/autoload.php';
        $allowed_html = array();
     
        try {
            $client = new \Google\Auth\OAuth2([
                'clientId' => $this->google_client_id,
                'clientSecret' => $this->google_client_secret,
                'redirectUri' => $this->redirect,
                'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
                'tokenCredentialUri' => 'https://oauth2.googleapis.com/token',
            ]);
    
            // Handle the OAuth callback
            if (isset($_REQUEST['code'])) {
                $code = sanitize_text_field(wp_kses($_REQUEST['code'], $allowed_html));
             
                // Fetch the token correctly
                $client->setCode($code);
                $token = $client->fetchAuthToken();
              
                if ($token && isset($token['access_token'])) {
                    // Use the access token to fetch user info
                    $userInfoClient = new \GuzzleHttp\Client();
                    $response = $userInfoClient->get('https://www.googleapis.com/oauth2/v2/userinfo', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token['access_token']
                        ]
                    ]);
                    
                    $user = json_decode($response->getBody(), true);
                    
                    if ($user) {
                        // Sanitize user data
                        $user_id = intval($user['id']);
                        $full_name = wp_kses($user['name'], $allowed_html);
                        $email = wp_kses($user['email'], $allowed_html);
                        $full_name = str_replace(' ', '.', $full_name);
                        
                        $first_name = '';
                        $last_name = '';
                        
                        if (isset($user['family_name'])) {
                            $last_name = wp_kses($user['family_name'], $allowed_html);
                        }
                        
                        if (isset($user['given_name'])) {
                            $first_name = wp_kses($user['given_name'], $allowed_html);
                        }
                        
                        // Clean up sessions
                        if (isset($_SESSION)) {
                            unset($_SESSION['wpestate_is_twet']); 
                            unset($_SESSION['wpestate_is_fb']); 
                            unset($_SESSION['wpestate_is_google']); 
                        }
                 
                        // Create or login user
                        $this->create_or_login_user($email, $full_name, $user_id, $first_name, $last_name);
                    }
                }
            }
            
        } catch (Exception $e) {
            error_log('Google Auth Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /*
     * 
     * Create & login user on social 
     * Thanks to Keith G.B.
     *  
     */
    
    
    function  create_or_login_user ($email,$username,$openid_identity_code,$firsname='',$lastname=''){
       
        
        if ( email_exists( $email ) ){  
            // do nothing - you will be logged in with email account - email being check on social platform 
        }else{
            if(username_exists($username) ){
                $username=$username.'-'.time();
            }           
            $user_id  = wp_create_user( $username, $openid_identity_code, $email );
	  
           

            $default_role = sanitize_key( wprentals_get_option('wp_estate_social_login_user_role','owner') );
            if( !in_array( $default_role, array('owner','renter'), true ) ){
                $default_role = 'owner';
            }
            $role_assigned = wprentals_register_user_role( $user_id, $default_role );

            $this->wpestate_update_profile($user_id);
            $this->wpestate_register_as_user($username,$user_id,$firsname,$lastname);
        }
        
     
        $user = get_user_by('email', $email );
            
        if ( is_wp_error($user) ){ 
            wp_redirect( esc_url(home_url('/')) );  
            exit();
        }else{
            wp_clear_auth_cookie();
            wp_set_current_user ( $user->ID );
            wp_set_auth_cookie  ( $user->ID );
            
            $this->wpestate_update_old_users($user->ID);
     
            wp_redirect(  $this->redirect    );
            exit();
        }
        
        
    }
    
    
    /*
    *
    *
    *  
    */
    
    
    
    
    function  wpestate_register_as_user($user_name,$user_id,$first_name='',$last_name=''){
	    if (user_can($user_id, 'publish_estate_agents')){
		    $post = array(
			    'post_title'  => $user_name,
			    'post_status' => 'publish',
			    'post_type'   => 'estate_agent',
			    'post_author' => $user_id
		    );

		    $post_id = wp_insert_post($post);
		    update_post_meta($post_id, 'user_meda_id', $user_id);
		    update_post_meta($post_id, 'user_agent_id', $user_id);
		    update_user_meta($user_id, 'user_agent_id', $post_id);

		    if(esc_html(wprentals_get_option('wp_estate_separate_users', '')) == 'yes'){
			    $type = wprentals_core_user_has_role($user_id, 'owner') ? 0 : 1;
			    update_post_meta($post_id, 'user_sub_type', $type);
		    }
	    }

	    if($first_name != ''){
		    update_user_meta($user_id, 'first_name', $first_name);
	    }
	    if($last_name != ''){
		    update_user_meta($user_id, 'last_name', $last_name);
	    }
        
    }
    
    
    /*
    *
    *
    *  
    */
    
    
    
    function wpestate_update_profile($userID){
        if(1==1){ // if membership is on
            //user can only book
            update_user_meta($userID, 'user_type', 1);
             
            if( wprentals_get_option('wp_estate_free_mem_list_unl', '' ) ==1 ){
                $package_listings =-1;
                $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
            }else{
                $package_listings           = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );

                if($package_listings==''){
                    $package_listings=0;
                }
                if($featured_package_listings==''){
                    $featured_package_listings=0;
                }
            }
            update_user_meta( $userID, 'package_listings', $package_listings) ;
            update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;
            $time = time(); 
            $date = date('Y-m-d H:i:s',$time);
            update_user_meta( $userID, 'package_activation', $date);
            //package_id no id since the pack is free
            
           

        }

    }
    
    /*
    *
    *
    *  
    */
    
    
    function wpestate_update_old_users($userID){
        $paid_submission_status    = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
        if($paid_submission_status == 'membership' ){

            $curent_list   =   get_user_meta( $userID, 'package_listings', true) ;
            $cur_feat_list =   get_user_meta( $userID, 'package_featured_listings', true) ;

                if($curent_list=='' || $cur_feat_list=='' ){
                    $package_listings           = esc_html( wprentals_get_option('wp_estate_free_mem_list','') );
                    $featured_package_listings  = esc_html( wprentals_get_option('wp_estate_free_feat_list','') );
                    if($package_listings==''){
                        $package_listings=0;
                    }
                    if($featured_package_listings==''){
                       $featured_package_listings=0;
                    }

                     update_user_meta( $userID, 'package_listings', $package_listings) ;
                     update_user_meta( $userID, 'package_featured_listings', $featured_package_listings) ;

                   $time = time(); 
                   $date = date('Y-m-d H:i:s',$time);
                   update_user_meta( $userID, 'package_activation', $date);
                }

        }// end if memebeship
    }
    
    
}
