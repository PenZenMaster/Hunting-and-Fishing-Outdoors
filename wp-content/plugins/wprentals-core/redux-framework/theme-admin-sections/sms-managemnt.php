<?php 

$sms_data_array=array(
    array(
        'id'       => 'wp_estate_sms_verification',
        'type'     => 'button_set',
        'title'    => __( 'Enable SMS service', 'wprentals-core' ),
        'subtitle' => __( 'Enable SMS service', 'wprentals-core' ),
        'options'  =>array(
                    'yes'   => 'yes',
                    'no'   => 'no'
                    ),
        'default' => 'no'
    ),
    array(
        'id'       => 'wp_estate_twilio_phone_no',
        'type'     => 'text',
        'title'    => __( 'Twilio phone number', 'wprentals-core' ),
        'subtitle' => __( 'Twilio phone number (ex +1256973878)', 'wprentals-core' ),
    ),
    array(
        'id'       => 'wp_estate_twilio_api_key',
        'type'     => 'text',
        'title'    => __( 'Twilio Account Sid', 'wprentals-core' ),
        'subtitle' => __( 'Twilio Account Sid', 'wprentals-core' ),
    ),
    array(
        'id'       => 'wp_estate_twilio_auth_token',
        'type'     => 'text',
        'title'    => __( 'Twilio Auth Token', 'wprentals-core' ),
        'subtitle' => __( 'Twilio Auth Token', 'wprentals-core' ),
    ),
     array(
        'id'    => 'sms_info',
        'type'  => 'info',
        'style' => 'info',
        'title' => __( 'Leave "content" blank for the sms notifications you don\'t wish to send. Global variables: %website_url as website url,%website_name as website name, %user_email as user_email, %username as username', 'wprentals-core' ),
    ),

);







$sms_array=array(
    'validation'                =>  __('Phone Number Validation','wprentals'),
    'admin_new_user'            =>  __('New user admin notification','wprentals'),
    'password_reset_request'    =>  __('Password Reset Request','wprentals'),
    'password_reseted'          =>  __('Password Reseted','wprentals'),
    'approved_listing'          =>  __('Approved Listings','wprentals'),
    'admin_expired_listing'     =>  __('Admin - Expired Listing','wprentals'),
    'paid_submissions'          =>  __('Paid Submission','wprentals'),
    'featured_submission'       =>  __('Featured Submission','wprentals'),
    'account_downgraded'        =>  __('Account Downgraded','wprentals'),
    'membership_cancelled'      =>  __('Membership Cancelled','wprentals'),
    'free_listing_expired'      =>  __('Free Listing Expired','wprentals'),
    'new_listing_submission'    =>  __('New Listing Submission','wprentals'),
    'recurring_payment'         =>  __('Recurring Payment','wprentals'),
    'membership_activated'      =>  __('Membership Activated','wprentals'),
    'agent_update_profile'      =>  __('Update Profile','wprentals'),
    'bookingconfirmeduser'      =>  __('Booking Confirmed - User','wprentals'),
    'bookingconfirmed'          =>  __('Booking Confirmed','wprentals'),
    'bookingconfirmed_nodeposit'=>  __('Booking Confirmed - no deposit','wprentals'),
    'inbox'                     =>  __('Inbox- New Message','wprentals'),
    'newbook'                   =>  __('New Booking Request','wprentals'),
    'mynewbook'                 =>  __('User - New Booking Request','wprentals'),
    'newinvoice'                =>  __('Invoice generation','wprentals'),
    'deletebooking'             =>  __('Booking request rejected','wprentals'),
    'deletebookinguser'         =>  __('Booking Request Cancelled','wprentals'),
    'deletebookingconfirmed'    =>  __('Booking Period Cancelled ','wprentals'),
    'new_wire_transfer'         =>  __('New wire Transfer','wprentals'),
    'admin_new_wire_transfer'   =>  __('Admin - New wire Transfer','wprentals'),
    'full_invoice_reminder'     =>  __('Invoice Payment Reminder','wprentals'),
);


foreach ($sms_array as $key=>$label ){

// $value          = stripslashes( wprentals_get_option('wp_estate_'.$key,'') );

$temp_array = array(
        'id'       => 'wp_estate_sms_'.$key,
        'type'     => 'text',
        'title'    => __( 'SMS for', 'wprentals-core' ).' '.$label,
        'subtitle' => wpestate_emails_extra_details($key,1),
    );

$sms_data_array[]=$temp_array;
}
Redux::setSection( $opt_name, array(
	'title'      => __( 'SMS Management', 'wprentals-core' ),
	'id'         => 'sms_notice_tab',
	'desc'       => __( 'SMS Management is offered through Twilio API <a href="https://www.twilio.com">https://www.twilio.com</a>. You will need an active account with them to use their SMS service and you may need to buy extra SMS as well. Your account info will have to be added below. ', 'wprentals-core' ),
	'subsection' => false,
	'fields'     => $sms_data_array,
) );

?>