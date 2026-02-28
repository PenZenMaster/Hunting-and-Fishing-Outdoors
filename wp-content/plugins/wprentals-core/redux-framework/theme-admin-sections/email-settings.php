<?php 
	
// -> START Advanced Selection
Redux::setSection( $opt_name, array(
    'title'      => __( 'Email Management', 'wprentals-core' ),
    'id'         => 'advanced_email_settings_sidebar',
    'icon'  => 'el el-envelope el el-small'
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Duplicate Email', 'wprentals-core' ),
    'id'         => 'duplicate_management_tab',
    'desc'       => __( 'Recceive a duplicate email of all messages sent through the booking forms or contact forms', 'wprentals-core' ),
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wp_estate_duplicate_email_adr',
            'type'     => 'text',
            'title'    => __( 'Duplicate Email', 'wprentals-core' ),
            'subtitle' => __( 'Send all contact emails to', 'wprentals-core' ),
        ),

),
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Email Content', 'wprentals-core' ),
    'id'         => 'email_management_tab',
    'desc'       => __( 'Leave "Subject" blank for the email notifications you don\'t wish to send. Global variables: %website_url as website url,%website_name as website name, %user_email as user_email, %username as username', 'wprentals-core' ),
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wp_estate_subject_new_user',
            'type'     => 'text',
            'title'    => __( 'Subject for New user notification', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for New user notification', 'wprentals-core' ),
            'default'  => __( 'Your username and password on %website_url', 'wprentals-core' )
        ),
        array(
            'id'       => 'wp_estate_new_user',
            'type'     => 'editor',
            'title'    => __( 'Content for New user notification', 'wprentals-core' ),
            'subtitle' => __( 'Email content for New user notification', 'wprentals-core' ),
            'default'  => __('Hi there,
                            Welcome to %website_url ! You can login now using the below credentials:
                            Username:%user_login_register
                            Password: %user_pass_register
                            If you have any problems, please contact me.
                            Thank you!', 'wprentals-core'),
            'desc'     => esc_html__('%user_login_register as new username, %user_pass_register as user password, %user_email_register as new user email.', 'wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_admin_new_user',
            'type'     => 'text',
            'title'    => __( 'Subject for New user admin notification', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for New user admin notification', 'wprentals-core' ),
            'default'  => __('New User Registration' , 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_admin_new_user',
            'type'     => 'editor',
            'title'    => __( 'Content for New user admin notification', 'wprentals-core' ),
            'subtitle' => __( 'Email content for New user admin notification', 'wprentals-core' ),
            'default'  => __('New user registration on %website_url.
                            Username: %user_login_register,
                            E-mail: %user_email_register', 'wprentals-core'),
            'desc'     =>esc_html__( '%user_login_register as new username and %user_email_register as new user email.', 'wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_purchase_activated',
            'type'     => 'text',
            'title'    => __( 'Subject for Purchase Activated', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Purchase Activated', 'wprentals-core' ),
            'default'  =>__('Your purchase was activated', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_purchase_activated',
            'type'     => 'editor',
            'title'    => __( 'Content for Purchase Activated', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Purchase Activated', 'wprentals-core' ),
            'default'  => __('Hi there,
                            Your purchase on  %website_url is activated! You should go check it out.', 'wprentals-core'),
            'desc'     => esc_html__('','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_password_reset_request',
            'type'     => 'text',
            'title'    => __( 'Subject for Password Reset Request', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Password Reset Request', 'wprentals-core' ),
            'default'  => __('Password Reset Request', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_password_reset_request',
            'type'     => 'editor',
            'title'    => __( 'Content for Password Reset Request', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Password Reset Request', 'wprentals-core' ),
            'default'  => __('Someone requested that the password be reset for the following account:
                            %website_url
                            Username: %forgot_username .
                            If this was a mistake, just ignore this email and nothing will happen. To reset your password, visit the following address:%reset_link,
                            Thank You!', 'wprentals-core'),
            'desc'     => esc_html__('Use %reset_link as reset link, %forgot_username as user name and %forgot_email as user email.','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_password_reseted',
            'type'     => 'text',
            'title'    => __( 'Subject for Password Reseted', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Password Reseted', 'wprentals-core' ),
            'default'  => __('Your Password was Reset', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_password_reseted',
            'type'     => 'editor',
            'title'    => __( 'Content for Password Reseted', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Password Reseted', 'wprentals-core' ),
            'default'  => __('Your new password for the account at: %website_url:
                            Username:%user_login,
                            Password:%user_pass
                            You can now login with your new password at: %website_url', 'wprentals-core'),
            'desc'     => esc_html__('Use %user_login as username and %user_pass as user password.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_approved_listing',
            'type'     => 'text',
            'title'    => __( 'Subject for Approved Listings', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Approved Listings', 'wprentals-core' ),
            'default'  => __('Your Password was Reset', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_approved_listing',
            'type'     => 'editor',
            'title'    => __( 'Content for Approved Listings', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Approved Listings', 'wprentals-core' ),
            'default'  => __('Hi there,
                            Your listing, %property_title was approved on  %website_url ! The listing is: %property_url.
                            You should go check it out.', 'wprentals-core'),
            'desc'     => esc_html__('You can use %listing_author as owner name, %post_id as listing id, %property_url as property url and %property_title as property name.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_admin_expired_listing',
            'type'     => 'text',
            'title'    => __( 'Subject for Admin - Expired Listing', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Admin - Expired Listing', 'wprentals-core' ),
            'default'  => __('Expired Listing sent for approval on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_admin_expired_listing',
            'type'     => 'editor',
            'title'    => __( 'Content for Admin - Expired Listing', 'wprentals-core' ),
            'subtitle' => __( 'Email Email content for Admin - Expired Listing', 'wprentals-core' ),
            'default'  => __('Hi there,
                            A user has re-submited a new property on %website_url ! You should go check it out.
                            This is the property title: %submission_title.', 'wprentals-core'),
            'desc'     => esc_html__('You can use %submission_title as property title number, %submission_url as property submission url.','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_paid_submissions',
            'type'     => 'text',
            'title'    => __( 'Subject for Paid Submission' ),
            'subtitle' => __( 'Email subject for Paid Submission', 'wprentals-core' ),
            'default'  => __('New Paid Submission on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_paid_submissions',
            'type'     => 'editor',
            'title'    => __( 'Content for Paid Submission', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Paid Submission', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You have a new paid submission on  %website_url ! You should go check it out.', 'wprentals-core'),
            'desc'     => esc_html__('', 'wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_featured_submission',
            'type'     => 'text',
            'title'    => __( 'Subject for Featured Submission', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Featured Submission', 'wprentals-core' ),
            'default'  => __('New Feature Upgrade on  %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_featured_submission',
            'type'     => 'editor',
            'title'    => __( 'Content for Featured Submission', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Featured Submission', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You have a new featured submission on  %website_url ! You should go check it out.', 'wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_account_downgraded',
            'type'     => 'text',
            'title'    => __( 'Subject for Account Downgraded', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Account Downgraded', 'wprentals-core' ),
            'default'  => __('Account Downgraded on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_account_downgraded',
            'type'     => 'editor',
            'title'    => __( 'Content for Account Downgraded', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Account Downgraded', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You downgraded your subscription on %website_url. Because your listings number was greater than what the actual package offers, we set the status of all your listings to expired. You will need to choose which listings you want live and send them again for approval.
                            Thank you!', 'wprentals-core'),
            'desc'     => esc_html__('','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_membership_cancelled',
            'type'     => 'text',
            'title'    => __( 'Subject for Membership Cancelled', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Membership Cancelled', 'wprentals-core' ),
            'default'  => __('Membership Cancelled on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_membership_cancelled',
            'type'     => 'editor',
            'title'    => __( 'Content for Membership Cancelled', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Membership Cancelled', 'wprentals-core' ),
            'default'  => __('Hi there,
                            Your subscription on %website_url was cancelled because it expired or the recurring payment from the merchant was not processed. All your listings are no longer visible for our visitors but remain in your account.
                            Thank you.', 'wprentals-core'),
            'desc'     => esc_html__('', 'wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_free_listing_expired',
            'type'     => 'text',
            'title'    => __( 'Subject for Free Listing Expired' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Free Listing Expired', 'wprentals-core' ),
            'default'  => __('Free Listing expired on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_free_listing_expired',
            'type'     => 'editor',
            'title'    => __( 'Content for Free Listing Expired', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Free Listing Expired', 'wprentals-core' ),
            'default'  => __('Hi there,
                            One of your free listings on  %website_url has expired. The listing is %expired_listing_url.
                            Thank you!', 'wprentals-core'),
            'desc'     => esc_html__('You can use %expired_listing_url as expired listing url and %expired_listing_name as expired listing name.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_new_listing_submission',
            'type'     => 'text',
            'title'    => __( 'Subject for New Listing Submission', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for New Listing Submission', 'wprentals-core' ),
            'default'  => __('New Listing Submission on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_new_listing_submission',
            'type'     => 'editor',
            'title'    => __( 'Content for New Listing Submission', 'wprentals-core' ),
            'subtitle' => __( 'Email content for New Listing Submission', 'wprentals-core' ),
            'default'  => __('Hi there,
                            A user has submited a new property on %website_url ! You should go check it out.This is the property title %new_listing_title!', 'wprentals-core'),
            'desc'     => esc_html__('You can use %new_listing_title as new listing title and %new_listing_url as new listing url.','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_recurring_payment',
            'type'     => 'text',
            'title'    => __( 'Subject for Recurring Payment', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Recurring Payment', 'wprentals-core' ),
            'default'  => __('Recurring Payment on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_recurring_payment',
            'type'     => 'editor',
            'title'    => __( 'Content for Recurring Payment', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Recurring Payment', 'wprentals-core' ),
            'default'  => __('Hi there,
                            We charged your account on %merchant for a subscription on %website_url ! You should go check it out.', 'wprentals-core'),
            'desc'     => esc_html__('You can use %recurring_pack_name as recurring packacge name and %merchant as merchant name.','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_membership_activated',
            'type'     => 'text',
            'title'    => __( 'Subject for Membership Activated' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Membership Activated', 'wprentals-core' ),
            'default'  => __('Membership Activated on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_membership_activated',
            'type'     => 'editor',
            'title'    => __( 'Content for Membership Activated', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Membership Activated', 'wprentals-core' ),
            'default'  => __('Hi there,
                            Your new membership on %website_url is activated! You should go check it out.', 'wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_agent_update_profile',
            'type'     => 'text',
            'title'    => __( 'Subject for Update Profile', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Update Profile', 'wprentals-core' ),
            'default'  => __('Profile Update', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_agent_update_profile',
            'type'     => 'editor',
            'title'    => __( 'Content for Update Profile', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Update Profile', 'wprentals-core' ),
            'default'  => __('A user updated his profile on %website_url.
                            Username: %user_login', 'wprentals-core'),
            'desc'     => esc_html__('Use %user_login as username, %user_email_profile as user email, %user_id as user_id.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_bookingconfirmeduser',
            'type'     => 'text',
            'title'    => __( 'Subject for Booking Confirmed - User', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Booking Confirmed - User', 'wprentals-core' ),
            'default'  => __('Booking Confirmed on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_bookingconfirmeduser',
            'type'     => 'editor',
            'title'    => __( 'Content for Booking Confirmed - User', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Booking Confirmed - User', 'wprentals-core' ),
            'default'  => __('Hi there,
                            Your booking made on %website_url was confirmed! You can see all your reservations by logging in your account and visiting My Reservations page.','wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_bookingconfirmed',
            'type'     => 'text',
            'title'    => __( 'Subject for Booking Confirmed', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Booking Confirmed', 'wprentals-core' ),
            'default'  =>'Booking Confirmed on %website_url'
        ),
        array(
            'id'       => 'wp_estate_bookingconfirmed',
            'type'     => 'editor',
            'title'    => __( 'Content for Booking Confirmed', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Booking Confirmed', 'wprentals-core' ),
            'default'  => __('Hi there,
                            Somebody confirmed a booking on %website_url! You should go and check it out!Please remember that the confirmation is made based on the payment confirmation of a non-refundable fee of the total invoice cost, processed through %website_url and sent to website administrator. ','wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_bookingconfirmed_nodeposit',
            'type'     => 'text',
            'title'    => __( 'Subject for Booking Confirmed - no deposit', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Booking Confirmed - no deposit', 'wprentals-core' ),
            'default'  => __('Booking Confirmed on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_bookingconfirmed_nodeposit',
            'type'     => 'editor',
            'title'    => __( 'Content for Booking Confirmed - no deposit', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Booking Confirmed - no deposit', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You confirmed a booking on %website_url! The booking was confirmed with no deposit!','wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_inbox',
            'type'     => 'text',
            'title'    => __( 'Subject for Inbox- New Message', 'wprentals-core' ),
            'subtitle' => __( 'Email subject for Inbox- New Message', 'wprentals-core' ),
            'default'  => __('New Message on %website_url.', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_inbox',
            'type'     => 'editor',
            'title'    => __( 'Content for Inbox- New Message', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Inbox- New Message', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You have a new message on %website_url! You should go and check it out!
                            The message is:
                            %content','wprentals-core'),
            'desc'     => esc_html__('You can use %content as message content.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_newbook',
            'type'     => 'text',
            'title'    => __( 'Subject for New Booking Request' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for New Booking Request', 'wprentals-core' ),
            'default'  => __('New Booking Request on %website_url.', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_newbook',
            'type'     => 'editor',
            'title'    => __( 'Content for New Booking Request', 'wprentals-core' ),
            'subtitle' => __( 'Email content for New Booking Request', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You have received a new booking request on %website_url !  Go to your account in Bookings page to see the request, issue the invoice or reject it!
                            The property is: %booking_property_link','wprentals-core'),
            'desc'     => esc_html__('You can use %booking_property_link as property url,%booking_id as booking id.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_mynewbook',
            'type'     => 'text',
            'title'    => __( 'Subject for Owner - New Booking Request', 'wprentals-core'),
            'subtitle' => __( 'Email subject for Owner - New Booking Request', 'wprentals-core' ),
            'default'  => __('You booked a period on %website_url.', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_mynewbook',
            'type'     => 'editor',
            'title'    => __( 'Content for Owner - New Booking Request', 'wprentals-core' ),
            'subtitle' => __( 'Email content for User - New Booking Request', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You have booked a period for your own listing on %website_url !  The reservation will appear in your account, under My Bookings.
                            The property is: %booking_property_link','wprentals-core'),
            'desc'     => esc_html__('You can use %booking_property_link as property url.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_newinvoice',
            'type'     => 'text',
            'title'    => __( 'Subject for Invoice generation' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Invoice generation', 'wprentals-core' ),
            'default'  => __('New Invoice on %website_url.', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_newinvoice',
            'type'     => 'editor',
            'title'    => __( 'Content for Invoice generation', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Invoice generation', 'wprentals-core' ),
            'default'  => __('Hi there,
                            An invoice was generated for your booking request on %website_url !  A deposit will be required for booking to be confirmed. For more details check out your account, My Reservations page.','wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_deletebooking',
            'type'     => 'text',
            'title'    => __( 'Subject for Booking request rejected','wprentals-core' ),
            'subtitle' => __( 'Email subject for Booking request rejected', 'wprentals-core' ),
            'default'  => 'Booking Request Rejected on %website_url'
        ),
        array(
            'id'       => 'wp_estate_deletebooking',
            'type'     => 'editor',
            'title'    => __( 'Content for Booking request rejected', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Booking request rejected', 'wprentals-core' ),
            'default'  => __('Hi there,
                            One of your booking requests sent on %website_url was rejected by the owner. The rejected reservation is automatically removed from your account. ','wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_subject_deletebookinguser',
            'type'     => 'text',
            'title'    => __( 'Subject for Booking Request Cancelled' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Booking Request Cancelled', 'wprentals-core' ),
            'default'  => __('Booking Request Cancelled on %website_url', 'wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_deletebookinguser',
            'type'     => 'editor',
            'title'    => __( 'Content for Booking Request Cancelled', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Booking Request Cancelled', 'wprentals-core' ),
            'default'  => __('Hi there,
                            One of the unconfirmed booking requests you received on %website_url  was cancelled! The request is automatically deleted from your account!','wprentals-core'),
            'desc'     => esc_html__('You can use %receiver_email as email of the person who cancel ,%receiver_name as the username of person who cancel.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_deletebookingconfirmed',
            'type'     => 'text',
            'title'    => __( 'Subject for Booking Period Cancelled' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Booking Period Cancelled', 'wprentals-core' ),
            'default'  => __('Booking Request Cancelled on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_deletebookingconfirmed',
            'type'     => 'editor',
            'title'    => __( 'Content for Booking Period Cancelled', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Booking Period Cancelled', 'wprentals-core' ),
            'default'  => __('Hi there,
                            One of your confirmed bookings on %website_url  was cancelled by property owner. ','wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
            array(
            'id'       => 'wp_estate_subject_new_wire_transfer',
            'type'     => 'text',
            'title'    => __( 'Subject for New wire Transfer', 'wprentals-core'),
            'subtitle' => __( 'Email subject for New wire Transfer', 'wprentals-core' ),
            'default'  =>  __('You ordered a new Wire Transfer', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_new_wire_transfer',
            'type'     => 'editor',
            'title'    => __( 'Content for New wire Transfer', 'wprentals-core' ),
            'subtitle' => __( 'Email content for New wire Transfer', 'wprentals-core' ),
            'default'  => __('We received your Wire Transfer payment request on  %website_url !
                            Please follow the instructions below in order to start submitting properties as soon as possible.
                            The invoice number is: %invoice_no, Amount: %total_price.
                            Instructions:  %payment_details.','wprentals-core'),
            'desc'     => esc_html__('You can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as $payment_details.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_admin_new_wire_transfer',
            'type'     => 'text',
            'title'    => __( 'Subject for Admin - New wire Transfer' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Admin - New wire Transfer', 'wprentals-core' ),
            'default'  => __('Somebody ordered a new Wire Transfer', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_admin_new_wire_transfer',
            'type'     => 'editor',
            'title'    => __( 'Content for Admin - New wire Transfer', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Admin - New wire Transfer', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You received a new Wire Transfer payment request on %website_url.
                            The invoice number is:  %invoice_no,  Amount: %total_price.
                            Please wait until the payment is made to activate the user purchase.','wprentals-core'),
            'desc'     => esc_html__('You can use %invoice_no as invoice number, %total_price as $totalprice and %payment_details as $payment_details.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_full_invoice_reminder',
            'type'     => 'text',
            'title'    => __( 'Subject for Invoice Payment Reminder' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Invoice Payment Reminder', 'wprentals-core' ),
            'default'  => __('Invoice payment reminder', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_full_invoice_reminder',
            'type'     => 'editor',
            'title'    => __( 'Content for Invoice Payment Reminder', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Invoice Payment Reminder', 'wprentals-core' ),
            'default'  => __('Hi there,
                            We remind you that you need to fully pay the invoice no %invoice_id until  %until_date. This invoice is for booking no %booking_id on property %property_title with the url %property_url.
                            Thank you.','wprentals-core'),
            'desc'     => esc_html__('* you can use %invoice_id as invoice id, %property_url as property url and %property_title as property name, %booking_id as booking id, %until_date as the last day.','wprentals-core'),
        ),
        array(
            'id'       => 'wp_estate_subject_new_user_id_verification',
            'type'     => 'text',
            'title'    => __( 'Subject for New User ID verification' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for New User ID verification', 'wprentals-core' ),
            'default'  => __('New User ID verification', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_new_user_id_verification',
            'type'     => 'editor',
            'title'    => __( 'Content for New User ID verification', 'wprentals-core' ),
            'subtitle' => __( 'Email content for New User ID verification', 'wprentals-core' ),
            'default'  => __('A user added his User ID verification image on %website_url.
                            Username: %user_login.
                            ','wprentals-core'),
            'desc'     => esc_html__('you can use %user_login as username.','wprentals-core'),
        ),
        
            array(
            'id'       => 'wp_estate_subject_payment_action_required',
            'type'     => 'text',
            'title'    => __( 'Subject for Payment Action Required' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for  Payment Action Required (on 3ds recurring payments via Stripe) ', 'wprentals-core' ),
            'default'  => __('Payment Action Required on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_payment_action_required',
            'type'     => 'editor',
            'title'    => __( 'Content for Payment Action Required', 'wprentals-core' ),
            'subtitle' => __( 'Email content for  Payment Action Required (on 3ds recurring payments via Stripe) ', 'wprentals-core' ),
            'default'  => __('Hi there,
                            One of your subscription payments on %website_url  requires manual confirmation. Please go to your dashboard and approve the payment. ','wprentals-core'),
            'desc'     => esc_html__('','wprentals-core' ),
        ),
        
        array(
            'id'       => 'wp_estate_subject_new_review',
            'type'     => 'text',
            'title'    => __( 'Subject for New Review email' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for New Review', 'wprentals-core' ),
            'default'  => __('New Review received on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_new_review',
            'type'     => 'editor',
            'title'    => __( 'Content for New Review Email', 'wprentals-core' ),
            'subtitle' => __( 'Email content for when a new review', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You Received a new review for %property_name . User %user% posted a %stars stars review : 
                            %content.','wprentals-core'),
            'desc'     => esc_html__('You can use %stars for stars no, %user for reviewer login name, %property_name for property name, %content for review content.','wprentals-core' ),
        ),
            
        array(
            'id'       => 'wp_estate_subject_review_reply',
            'type'     => 'text',
            'title'    => __( 'Subject for Review Reply Email' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Review Reply Email', 'wprentals-core' ),
            'default'  => __('A reply was posted to your review on %website_url', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_review_reply',
            'type'     => 'editor',
            'title'    => __( 'Content for  Review Reply Email', 'wprentals-core' ),
            'subtitle' => __( 'Email content for when a review reply', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You Received a reply to your review for %property_name.
                            %reply_content','wprentals-core'),
            'desc'     => esc_html__('You can use %property_name for property name, %reply_content for reply content.','wprentals-core' ),
        ),
        
        
        
        array(
            'id'       => 'wp_estate_subject_post_review_reminder',
            'type'     => 'text',
            'title'    => __( 'Subject for Post Review Reminder' , 'wprentals-core'),
            'subtitle' => __( 'Email subject for Post Review Reminder', 'wprentals-core' ),
            'default'  => __('Rate your stay at %property_title', 'wprentals-core')
        ),
        array(
            'id'       => 'wp_estate_post_review_reminder',
            'type'     => 'editor',
            'title'    => __( 'Content for Post Review Reminder', 'wprentals-core' ),
            'subtitle' => __( 'Email content for Post Review Reminder', 'wprentals-core' ),
            'default'  => __('Hi there,
                            You just checked out of %property_title. Take a few minutes to rate your stay and let your host know how they did.
                                Thank you.','wprentals-core'),
            'desc'     => esc_html__('* you can use %invoice_id as invoice id, %property_url as property url and %property_title as property name, %booking_from_date as booking start date, %booking_to_date as booking end date','wprentals-core'),
        ),
    
    ),
) );


Redux::setSection( $opt_name, array(
    'title'      => __( 'Emails Settings', 'wprentals-core' ),
    'id'         => 'advanced_email_settings_section',
    'subsection' => true,
    'fields'     => array(
            
        array(
            'id'       => 'wpestate_email_type',
            'type'     =>  'button_set',
            'title'    => __( 'Send emails as Html or text', 'wprentals-core' ),
            'subtitle' => __( 'Send emails as Html or text','wprentals-core'),
            'options'  => array(
                            'html' => 'html',
                            'text'  => 'text'
                        ),
            'default'  => 'html'
        ),
        
        
        array(
            'id'       => 'wp_estate_send_name_email_from',
                'type'     => 'text',
            'title'    => __( 'Emails will be sent from name?', 'wprentals-core' ),
            'subtitle' => __( 'Emails will use the from name set here','wprentals-core'),
            
            'default'  => 'noreply',
        ),
        array(
            'id'       => 'wp_estate_send_email_from',
                'type'     => 'text',
            'title'    => __( 'Emails will be sent from email', 'wprentals-core' ),
            'subtitle' => __( 'Emails will use as sender email this address. If left blank, emails are sent from an address like noreply@yourdomain.com','wprentals-core'),
            
            'default'  => $siteurl,
        ),
        
        
        
        array(
            'id'       => 'wpestate_display_header_email',
            'type'     =>  'button_set',
            'title'    => __( 'Display Email Header ?', 'wprentals-core' ),
            'subtitle' => __( 'Display email header - the default header contains only the logo ','wprentals-core'),
            'options'  => array(
                            'yes' => 'yes',
                            'no'  => 'no'
                        ),
            'default'  => 'yes'
        ),
        
        array(
            'id'       => 'wp_estate_email_logo_image',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Email Logo', 'wprentals-core' ),
            'subtitle' => __( 'Use the "Upload" button and "Insert into Post" button from the pop up window. Add a small logo. ', 'wprentals-core' ),
                'default'  => array(
                'url' =>get_template_directory_uri().'/img/logo.png'
            )
            
            
        ),
            array(
            'id'       => 'wpestate_display_footer_email',
            'type'     =>  'button_set',
            'title'    => __( 'Display Email footer?', 'wprentals-core' ),
            'subtitle' => __( 'Display email footer','wprentals-core'),
            'options'  => array(
                            'yes' => 'yes',
                            'no'  => 'no'
                        ),
            'default'  => 'yes'
        ),
        
        array(
            'id'       => 'wpestate_show_footer_email_address',
            'type'     =>  'button_set',
            'title'    => __( 'Show Address in  email footer?', 'wprentals-core' ),
            'subtitle' => __( 'Show Address in  email footer?','wprentals-core'),
            'options'  => array(
                            'yes' => 'yes',
                            'no'  => 'no'
                        ),
            'default'  => 'yes'
        ),
        
        array(
            'id'       => 'wp_estate_email_footer_content',
            'type'     => 'editor',
            'title'    => __( 'Footer Content', 'wprentals-core' ),
            'subtitle' => __( 'Footer Content for email', 'wprentals-core' ),
            'default'  => __('Please do not reply directly to this email. If you believe this is an error or require further assistance, please contact us', 'wprentals-core'),
            
        ),
        
        array(
            'id'       => 'wp_estate_email_footer_social1',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Social icon no 1', 'wprentals-core' ),
            'subtitle' => __( 'Upload social icon image', 'wprentals-core' ),
            'default'  => array(
                    'url' =>get_template_directory_uri().'/templates/email_templates/images/facebook_email.png'
                )
            

        ),
        array(
            'id'       => 'wp_estate_email_footer_social_link1',
            'type'     => 'text',
            'title'    => __( 'Link social accont no 1 ?', 'wprentals-core' ),
            'subtitle' => __( 'Link for social accont no 1 ','wprentals-core'),
                'default'   =>'#'
    
        ),
        
        
        array(
            'id'       => 'wp_estate_email_footer_social2',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Social icon no 2', 'wprentals-core' ),
            'subtitle' => __( 'Upload social icon image', 'wprentals-core' ),
            'default'  => array(
                    'url' =>get_template_directory_uri().'/templates/email_templates/images/twitter-email.png'
                )
        ),
        
        array(
            'id'       => 'wp_estate_email_footer_social_link2',
            'type'     => 'text',
            'title'    => __( 'Link social accont no 2 ?', 'wprentals-core' ),
            'subtitle' => __( 'Link for social accont no 2 ','wprentals-core'),
                'default'   =>'#'
            
        ),
        
        
        array(
            'id'       => 'wp_estate_email_footer_social3',
            'type'     => 'media',
            'url'      => true,
            'title'    => __( 'Social icon no 3', 'wprentals-core' ),
            'subtitle' => __( 'Upload social icon image', 'wprentals-core' ),
            'default'  => array(
                'url' =>get_template_directory_uri().'/templates/email_templates/images/linkedin-email.png'
            )
        ),
        array(
            'id'       => 'wp_estate_email_footer_social_link3',
                'type'     => 'text',
            'title'    => __( 'Link social accont no 3 ?', 'wprentals-core' ),
            'subtitle' => __( 'Link for social accont no 3 ','wprentals-core'),
            'default'   =>'#'
        ),
        
        array(
            'id'       => 'wp_estate_email_background',
            'type'     => 'color',
            'title'    => __( 'Email Background Color', 'wprentals-core' ),
            'subtitle' => __( 'Email Background Color', 'wprentals-core' ),
            'transparent' => false,
        ),
            array(
            'id'       => 'wp_estate_email_content_background',
            'type'     => 'color',
            'title'    => __( 'Email Content Background Color', 'wprentals-core' ),
            'subtitle' => __( 'Email Content Background Color', 'wprentals-core' ),
            'transparent' => false,
        ),
        
        
        
        
    ),
) );
    
    
Redux::setSection( $opt_name, array(
    'title'      => __( 'Trip Details Email', 'wprentals-core' ),
    'id'         => 'advanced_trip_detail_email_section',
    'subsection' => true,
    'fields'     => array(
            
        array(
            'id'       => 'wpestate_your_trip_show_email',
            'type'     =>  'button_set',
            'title'    => __( 'Show owner email in trip details email?', 'wprentals-core' ),
            'subtitle' => __( 'Show owner email in trip details email?','wprentals-core'),
            'options'  => array(
                            'yes' => 'yes',
                            'no'  => 'no'
                        ),
            'default'  => 'yes'
        ),
        array(
            'id'       => 'wpestate_send_your_trip_show_email',
            'type'     =>  'button_set',
            'title'    => __( 'Send trip details email?', 'wprentals-core' ),
            'subtitle' => __( 'Send trip details email?','wprentals-core'),
            'options'  => array(
                            'yes' => 'yes',
                            'no'  => 'no'
                        ),
            'default'  => 'yes'
        ),
        
        
)));

    
    
    