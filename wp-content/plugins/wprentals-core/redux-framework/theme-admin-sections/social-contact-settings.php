<?php 

//->STRAT Social & Contact
Redux::setSection( $opt_name, array(
	'title' => __( 'Social & Contact', 'wprentals-core' ),
	'id'    => 'social_contact_sidebar',
	'icon'  => 'el el-address-book'
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Contact Page Details', 'wprentals-core' ),
	'id'         => 'contact_details_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_use_gdpr',
			'type'     => 'button_set',
			'title'    => __( 'Use GDPR Checkbox?', 'wprentals-core' ),
			'subtitle' => __( 'Use GDPR Checkbox in contact forms?', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'no',
		),
		
		array(
			'id'       => 'wp_estate_show_phone_no_in_contact',
			'type'     => 'button_set',
			'title'    => __( 'Show phone number in contact form?', 'wprentals-core' ),
			'subtitle' => __( 'Show phone number in contact form?', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'no',
		),
		
		
		array(
			'id'       => 'wp_estate_company_name',
			'type'     => 'text',
			'title'    => __( 'Company Name', 'wprentals-core' ),
			'subtitle' => __( 'Company name for contact page', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_co_address',
			'type'     => 'textarea',
			'title'    => __( 'Company Address', 'wprentals-core' ),
			'subtitle' => __( 'Type company address', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_email_adr',
			'type'     => 'text',
			'title'    => __( 'Email', 'wprentals-core' ),
			'subtitle' => __( 'Company email', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_telephone_no',
			'type'     => 'text',
			'title'    => __( 'Telephone', 'wprentals-core' ),
			'subtitle' => __( 'Company phone number.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_mobile_no',
			'type'     => 'text',
			'title'    => __( 'Mobile', 'wprentals-core' ),
			'subtitle' => __( 'Company mobile', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_fax_ac',
			'type'     => 'text',
			'title'    => __( 'Fax', 'wprentals-core' ),
			'subtitle' => __( 'Company fax', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_skype_ac',
			'type'     => 'text',
			'title'    => __( 'Skype', 'wprentals-core' ),
			'subtitle' => __( 'Company Skype', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_hq_latitude',
			'type'     => 'text',
			'title'    => __( 'Contact Page - Company HQ Latitude', 'wprentals-core' ),
			'subtitle' => __( 'Set company pin location for contact page template. Latitude must be a number (ex: 40.577906).', 'wprentals-core' ),
			'default'  => '40.781711'
		),
		array(
			'id'       => 'wp_estate_hq_longitude',
			'type'     => 'text',
			'title'    => __( 'Contact Page - Company HQ Longitude', 'wprentals-core' ),
			'subtitle' => __( 'Set company pin location for contact page template. Longitude must be a number (ex: -74.155058).', 'wprentals-core' ),
			'default'  => '-73.955927'
		),
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Contact Form Settings', 'wprentals-core' ),
	'id'         => 'contact_form_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_use_gdpr',
			'type'     => 'button_set',
			'title'    => __( 'Use GDPR Checkbox?', 'wprentals-core' ),
			'subtitle' => __( 'Use GDPR Checkbox in contact forms?', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'no',
		),
		
		array(
			'id'       => 'wp_estate_show_phone_no_in_contact',
			'type'     => 'button_set',
			'title'    => __( 'Show phone number in contact form?', 'wprentals-core' ),
			'subtitle' => __( 'Show phone number in contact form?', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'no',
		),
	),
	) );
	

Redux::setSection( $opt_name, array(
	'title'      => __( 'Social Accounts', 'wprentals-core' ),
	'id'         => 'social_accounts_tab',
	'subsection' => true,
	'fields'     => array(
		 array(
			'id'       => 'wp_estate_whatsup_link',
			'type'     => 'text',
			'title'    => __( 'WhatsApp phone number', 'wprentals-core' ),
			'subtitle' => __( 'WhatsApp phone number without spaces or signs.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_facebook_link',
			'type'     => 'text',
			'title'    => __( 'Facebook Link', 'wprentals-core' ),
			'subtitle' => __( 'Facebook page url, with https://', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_twitter_link',
			'type'     => 'text',
			'title'    => __( 'Twitter page link', 'wprentals-core' ),
			'subtitle' => __( 'Twitter page link, with https://', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_google_link',
			'type'     => 'text',
			'title'    => __( 'Google+ Link', 'wprentals-core' ),
			'subtitle' => __( 'Google+ page link, with https://', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_linkedin_link',
			'type'     => 'text',
			'title'    => __( 'Linkedin Link', 'wprentals-core' ),
			'subtitle' => __( 'Linkedin page link, with https://', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_pinterest_link',
			'type'     => 'text',
			'title'    => __( 'Pinterest Link', 'wprentals-core' ),
			'subtitle' => __( 'Pinterest page link, with https://', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_instagram_ac',
			'type'     => 'text',
			'title'    => __( 'Instagram Link', 'wprentals-core' ),
			'subtitle' => __( 'Company Instagram', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_youtube_ac',
			'type'     => 'text',
			'title'    => __( 'Youtube Link', 'wprentals-core' ),
			'subtitle' => __( 'Company Youtube', 'wprentals-core' ),
		),
		 array(
			'id'       => 'wp_estate_telegram_link',
			'type'     => 'text',
			'title'    => __( 'Telegram Link', 'wprentals-core' ),
			'subtitle' => __( 'Company Telegram', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_tiktoklink',
			'type'     => 'text',
			'title'    => __( 'TikTok Link', 'wprentals-core' ),
			'subtitle' => __( 'Company TikTok', 'wprentals-core' ),
		),
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Social Login', 'wprentals-core' ),
	'id'         => 'social_login_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_social_register_on',
			'type'     => 'button_set',
			'title'    => __( 'Display social login also on register modal window?', 'wprentals-core' ),
			'subtitle' => __( 'Enable or disable social login also on register modal window', 'wprentals-core' ),
			'options'  => array(
						 'yes' => 'yes',
						 'no'  => 'no' 
						),
			'default'  => 'no',
		),
		array(
			'id'       => 'wp_estate_facebook_login',
			'type'     => 'button_set',

			'title'    => __( 'Allow login via Facebook?', 'wprentals-core' ),
			'subtitle' => __( 'Enable or disable Facebook login.', 'wprentals-core' ),
			'options'  => array(
						 'yes' => 'yes',
						 'no'  => 'no'
						),
			'default'  => 'no',
		),
		array(
			'id'       => 'wp_estate_facebook_api',
			'required' => array('wp_estate_facebook_login','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Facebook Api key', 'wprentals-core' ),
			'subtitle' => __( 'Facebook Api key is required for Facebook login. See this help article before: ', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/facebook-login/" target="_blank">https://help.wprentals.org/article/facebook-login/</a>',
		),
		array(
			'id'       => 'wp_estate_facebook_secret',
			'required' => array('wp_estate_facebook_login','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Facebook Secret', 'wprentals-core' ),
			'subtitle' => __( 'Facebook Secret is required for Facebook login.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_google_login',
			'type'     => 'button_set',
			'title'    => __( 'Allow login via Google?', 'wprentals-core' ),
			'subtitle' => __( 'Enable or disable Google login.', 'wprentals-core' ),
			'options'  => array(
						 'yes' => 'yes',
						 'no'  => 'no'
						),
			'default'  => 'no',
		),
		array(
			'id'       => 'wp_estate_google_oauth_api',
			'required' => array('wp_estate_google_login','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Google Oauth Api', 'wprentals-core' ),
			'subtitle' => __( 'Google Oauth Api is required for Google Login. See this help article before: ', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/enable-gmail-google-login/" target="_blank">https://help.wprentals.org/article/enable-gmail-google-login/</a>',
		),
		array(
			'id'       => 'wp_estate_google_oauth_client_secret',
			'required' => array('wp_estate_google_login','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Google Oauth Client Secret', 'wprentals-core' ),
			'subtitle' => __( 'Google Oauth Client Secret is required for Google Login.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_google_api_key',
			'required' => array('wp_estate_google_login','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Google api key', 'wprentals-core' ),
			'subtitle' => __( 'Google api key is required for Google Login.', 'wprentals-core' ),
		),

	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Twitter Login & Widget ', 'wprentals-core' ),
	'id'         => 'twitter_widget_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_twiter_login',
			'type'     => 'button_set',
			'title'    => __( 'Allow login via Twitter?', 'wprentals-core' ),
			'subtitle' => __( 'Allow login via Twitter? (works only over https)', 'wprentals-core' ),
			'options'  => array(
						 'yes' => 'yes',
						 'no'  => 'no'
						),
			'default'  => 'no',
		),
		
		array(
			'id'       => 'wp_estate_twitter_consumer_key',
			'type'     => 'text',
			'required' => array('wp_estate_twiter_login','=','yes'),
			'title'    => __( 'Twitter consumer_key.', 'wprentals-core' ),
			'subtitle' => __( 'Twitter consumer_key is required for theme Twitter widget or Twitter login. See this help article before: ', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/wp-estate-twitter-widget/" target="_blank">https://help.wprentals.org/article/wp-estate-twitter-widget/</a>',
		),
		
		array(
			'id'       => 'wp_estate_twitter_consumer_secret',
			'type'     => 'text',
			'required' => array('wp_estate_twiter_login','=','yes'),
			'title'    => __( 'Twitter Consumer Secret', 'wprentals-core' ),
			'subtitle' => __( 'Twitter Consumer Secret is required for theme Twitter widget or Twitter login.', 'wprentals-core' ),
		),
		
		array(
			'id'       => 'wp_estate_twitter_access_token',
			'type'     => 'text',
			'required' => array('wp_estate_twiter_login','=','yes'),
			'title'    => __( 'Twitter Access Token', 'wprentals-core' ),
			'subtitle' => __( 'Twitter Access Token is required for theme Twitter widget or Twitter login.', 'wprentals-core' ),
		),
		
		array(
			'id'       => 'wp_estate_twitter_access_secret',
			'type'     => 'text',
			'required' => array('wp_estate_twiter_login','=','yes'),
			'title'    => __( 'Twitter Access Token Secret', 'wprentals-core' ),
			'subtitle' => __( 'Twitter Access Token Secret is required for theme Twitter widget or Twitter login.', 'wprentals-core' ),
		),
		
		array(
			'id'       => 'wp_estate_twitter_cache_time',
			'type'     => 'text',
			'required' => array('wp_estate_twiter_login','=','yes'),
			'title'    => __( 'Twitter Cache Time', 'wprentals-core' ),
			'subtitle' => __( 'Twitter Cache Time', 'wprentals-core' ),
		),

	),
) );


