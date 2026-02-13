<?php

class WpestateEmail {
    private static $instance;

    private $email_type;
    private $duplicate_email_adr;

    
    private function __construct() {
        // private constructor to prevent direct instantiation
        $this->email_type               =   wprentals_get_option('wpestate_email_type','');
        $this->duplicate_email_adr      =   esc_html ( wprentals_get_option('wp_estate_duplicate_email_adr','') );
        
    }

    
    
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    
    /*
    *  send email
    * 
    */
    
 
    
    public function wpestate_send_email($user_email,$type,$arguments){
        $value          =   wprentals_get_option('wp_estate_'.$type,'');
        $value_subject  =   wprentals_get_option('wp_estate_subject_'.$type,'');

        if (function_exists('icl_translate') ){
            $value          =  icl_translate('wpestate','wp_estate_email_'.$value, $value ) ;
            $value_subject  =  icl_translate('wpestate','wp_estate_email_subject_'.$value_subject, $value_subject ) ;
        }

        $value          = stripslashes($value);
        $value_subject  = stripslashes($value_subject);

         if( trim($value_subject)=='' || trim($value)=='' ){
            return json_encode(
                    array(
                        'sent'=>false,
                        'details'=> 'subject or email content are blank'
                        )
            );
        }

        
        // send also to sms
        $user_data      =   get_user_by( 'email', $user_email );
        $user_mobile    =   get_the_author_meta( 'mobile' , $user_data->ID );

        // DO the SMS
        $this->wpestate_select_sms_type($user_mobile,$type,$arguments,$user_email, $user_data->ID);
        
        $answer =  $this->wpestate_emails_filter_replace($user_email,$value,$value_subject,$arguments);
        return $answer;
    }
       
    
    
    
    
    
    /*
    *  
     * send email contract form
     * 
    * 
    */
    
    
    public function wpestate_send_email_contact($receiver_email,$subject,$message){
        $answer = $this->wpestate_emails_filter_replace($receiver_email, $message, $subject, array());
        return $answer;
    }
    
    /*
    *
    *  
    *  replace placeholder in email content and title
    *
    *  
    */
    
    private function  wpestate_emails_filter_replace($user_email,$message,$subject,$arguments){
        $arguments ['website_url']  = get_option('siteurl');
        $arguments ['website_name'] = get_option('blogname');

        if (isset($arguments ['user_profile_url']) && isset($arguments['user_id'])){
            $user_edit_url = esc_url(sprintf('%suser-edit.php?user_id=%d',esc_url(admin_url()), absint($arguments['user_id'])));
            $arguments ['user_profile_url'] = sprintf(' <a href="%s">%s</a>', $user_edit_url, esc_html__('Edit user ID verification','wprentals-core'));
        }


        $current_user               = wp_get_current_user();
        $arguments ['user_email']   = $current_user->user_email;
        $arguments ['username']     = $current_user->user_login;

        if($user_email== 'approved_listing'){
            $arguments ['username']   =$arguments ['listing_author']  ;
        }


        foreach($arguments as $key_arg=>$arg_val){
            $subject = str_replace('%'.$key_arg, $arg_val, $subject);
            $message = str_replace('%'.$key_arg, $arg_val, $message);
        }

        
        $prepared_email_message=$message;
        
        if($this->email_type=='html'){
            $message  = preg_replace("/\r\n|\r|\n/", '<br>', $message);
            $subject  = preg_replace("/\r\n|\r|\n/", '<br>', $subject);
            $tip_email='base_email_template';

            $attributes=array(
                    'content'=>$message,
                    'subject'=>$subject
                    );

            ob_start();
                include(locate_template('templates/email_templates/'.$tip_email.'.php'));
                $email_message =    ob_get_contents();
            ob_end_clean();

            $prepared_email_message =   $this->wpestate_prepare_email_message($email_message,$attributes);
        }
          
          
        $answer = $this->actual_wpestate_send_emails($user_email, $subject, $prepared_email_message,'' );
        return $answer;
    }
    
    
    
    
    
    
    /*
    *
    *  
    *  send email
    *
    *  
    */
    
    
    
    function actual_wpestate_send_emails($user_email, $subject, $message,$reply_to,$extra_headers='' ){
        
        
        if($reply_to==''){
            $reply_to=$this->wpestate_return_sending_email() ;
        }
        
        
        

        $headers =  'From: '. $this->wpestate_return_sending_email () . "\r\n". 
                    'Reply-To:'. $reply_to . "\r\n" .
                    'Content-Type: text/html; charset="UTF-8"'."\r\n" .
                    'X-Mailer: PHP/' . phpversion();
        if($this->email_type=='text'){
            $headers = 'From: '. $this->wpestate_return_sending_email () . "\r\n" .
                    'Reply-To:'. $reply_to . "\r\n" .
                    'Content-Type: text/plain ; charset="UTF-8"'."\r\n" .
                    'Content-Transfer-Encoding: 8bit'."\r\n" .
                    'X-Mailer: PHP/' . phpversion();          
        }
        
        
        
        $headers=$headers.$extra_headers;

         //was wp_mail
        $sent=wp_mail(
            $user_email,
            $subject,
            stripslashes($message),
            $headers
            );


        if( $this->duplicate_email_adr!='' ){
            $message = $message.' '.__('Message was also sent to ','wprentals-core').$user_email;
            mail($this->duplicate_email_adr, $subject, $message, $headers);
        }
        
        if($sent){
            $return = array(
                'sent'=>true,
            );
        }else{
            $return=array(
                'sent'=>false,
                'details'=>'wp_mail error'
            );
        }
        
        return json_encode($return);
        
        
    }
    

    /*
     * 
     * Prepare email template
     * 
    */

    function wpestate_prepare_email_message($email_message,$attributes){

        foreach ($attributes as $key=>$value){
            $email_message= str_replace('{'.$key.'}', $value, $email_message);
        }

        return $email_message;

    }
    
    
    
    /*
     * 
     * Return reply data for emails
     * 
    */
    
    function wpestate_return_sending_email() {
        $name_email = wprentals_get_option('wp_estate_send_name_email_from', '');
        $from_email = wprentals_get_option('wp_estate_send_email_from', '');

        $return_string = $name_email.'  <'. $from_email.'>';
        return $return_string;
    }
    
    
    
    /*
     * 
     * select the sms
     * 
    */
     public  function wpestate_select_sms_type($user_mobile,$type,$arguments,$user_email, $user_data_id){
        $current_user = wp_get_current_user();
        $userID                 =   $current_user->ID;

        $sms_verification =esc_html( wprentals_get_option('wp_estate_sms_verification',''));
        if($sms_verification!=='yes'){
            return;
        }



        if($user_data_id!=0 && $type!='validation'){
            $roles=array('administrator');
            if( array_intersect($roles, $current_user->roles )){
               //is admin - do not check
            }else{
                $check_phone = get_the_author_meta( 'check_phone_valid' , $user_data_id);
                if($check_phone!='yes'){
                    return;
                }
            }
        }

        $value          =  wprentals_get_option('wp_estate_sms_'.$type,'');
        if (function_exists('icl_translate') ){
            $value          =  icl_translate('wpestate','wp_estate_sms_'.$value, $value ) ;
        }

        $this->wpestate_sms_filter_replace($user_mobile,$value,$arguments,$user_email);
    }
    
    
    
    
    
    /*
    * Compose sms Message / replace
     *
     *
     *
     *
    */

    
    
    
    
    private function  wpestate_sms_filter_replace($user_phone_no,$message,$arguments,$user_email){
        $arguments ['website_url'] = get_option('siteurl');
        $arguments ['website_name'] = get_option('blogname');
        $arguments ['user_email'] = $user_email;
        $user= get_user_by('email',$user_email);
        $arguments ['username'] = $user-> user_login;

        foreach($arguments as $key_arg=>$arg_val){
            $to_replace =   trim('%'.$key_arg);
            $message    =   str_replace($to_replace, $arg_val, $message);
        }

        $message=wp_strip_all_tags($message);

        $this->wpstate_call_twilio_sms($user_phone_no,$message);
       //print_r($response);
    }
    
    
    
    /*
    * Send the actula SMS
     *
     *
     *
     *
    */


    
    private function wpstate_call_twilio_sms($user_phone_no,$message){

        $account_sid         =  wprentals_get_option('wp_estate_twilio_api_key','');
        $auth_token          =  wprentals_get_option('wp_estate_twilio_auth_token','');
        $twilio_phone_no     =  wprentals_get_option('wp_estate_twilio_phone_no','');

        $ch = curl_init();
        $post_data=array(
            "To"    =>  $user_phone_no,
            "From"  =>  $twilio_phone_no,
            "Body"  =>  $message
        );


        curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/".$account_sid."/Messages.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_USERPWD, $account_sid. ":" . $auth_token);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close ($ch);
    }

}
