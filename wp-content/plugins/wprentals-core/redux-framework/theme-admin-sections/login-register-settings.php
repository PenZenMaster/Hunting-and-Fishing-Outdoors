<?php 

// -> START Payments & Submit Selection
Redux::setSection( $opt_name, array(
	'title' => __( 'Register & Login Settings', 'wprentals-core' ),
	'id'    => 'register_settings',
	'icon'  => 'el el-group-alt'
) );

	Redux::setSection( $opt_name, array(
		'title'      => __( 'Login / Register & Submit Display', 'wprentals-core' ),
		'id'         => 'property_login_display_tab',
		'subsection' => true,
		'desc' => __( 'Enable or disable the user login/register buttons in the header. If enabled, a login/register modal will appear for users.', 'wprentals-core' ),
		'fields'     => array(
	
			array(
				'id'       => 'wp_estate_show_top_bar_user_login',
				'type'     => 'button_set',
				'title'    => __( 'Show user login menu in header?', 'wprentals-core' ),
				'subtitle' => __( 'Enable or disable the user login / register buttons in header.', 'wprentals-core' ),
				'options'  => array(
					'yes' => 'yes',
					'no'  => 'no'
					),
				'default'  => 'yes',
			),
	
			array(
				'id'       => 'wp_estate_show_submit',
				'type'     => 'button_set',
				'title'    => __( 'Show the submit listing button in header?', 'wprentals-core' ),
				'subtitle' => __( 'Submit listing shows only if theme register/login buttons show in header.', 'wprentals-core' ),
				'options'  => array(
							'yes' => 'yes',
							'no'  => 'no'
							),
				'default'  => 'yes',
			),
		
	
			array(
				'id'       => 'wp_estate_modal_image',
				'type'     => 'media',
				'url'      => true,
				'title'    => __( 'Login / Register Modal Image', 'wprentals-core' ),
				'subtitle' => __( 'Login / Register Modal Image', 'wprentals-core' ),
			),
		 
	),
	) );

	Redux::setSection( $opt_name, array(
		'title'      => __( 'Login Redirect Settings', 'wprentals-core' ),
		'id'         => 'login_redirect_register_page_tab',
		'subsection' => true,
		'desc' => __( 'Control where users are redirected after logging in. By default, users are sent back to the same page they logged in from. If you disable this option, you can specify a custom URL for the redirect.', 'wprentals-core' ),
		'fields'     => array(		
	array(
		'id'       => 'wp_estate_redirect_users',
		'type'     => 'button_set',
		'title'    => __( 'Redirect users to the same page after login?', 'wprentals-core' ),
		'subtitle' => __( '*Login from property page always redirects user to the same page. *The option does NOT apply to social login.', 'wprentals-core' ),
		'options'  => array(
			'yes'   => 'yes',
			'no'    => 'no'
		),
		'default'  => 'no',
	),
	array(
		'id'       => 'wp_estate_redirect_custom_link',
		'type'     => 'text',
		'required' => array('wp_estate_redirect_users', '=', 'no'),
		'title'    => __( 'The link where users will be redirected after login', 'wprentals-core' ),
		'subtitle' => __( '*If blank, users are redirected to user profile page. *Login from property page always redirects user to the same page. *The option does NOT apply to social login.', 'wprentals-core' ),
	),
),
) );

		Redux::setSection( $opt_name, array(
		'title'      => __( 'Register Form Fields', 'wprentals-core' ),
		'id'         => 'property_login_register_page_tab',
		'subsection' => true,
		'desc' => __( 'Allow users to set their own password during registration. If disabled, users will receive an automatically generated password by email.', 'wprentals-core' ),
		'fields'     => array(			
			array(
				'id'       => 'wp_estate_enable_user_pass',
				'type'     => 'button_set',
				'title'    => __( 'Can users type the password on registration form?', 'wprentals-core' ),
				'subtitle' => __( 'If NO, users get the auto generated password via email', 'wprentals-core' ),
				'options'  => array(
					'yes' => 'yes',
					'no'  => 'no'
					),
				'default'  => 'no',
			),
			array(
				'id'       => 'wp_estate_enable_user_phone',
				'type'     => 'button_set',
				'title'    => __( 'Enable mobile phone number in registration form?', 'wprentals-core' ),
				'subtitle' => __( 'Enable mobile phone number in registration form?', 'wprentals-core' ),
				'options'  => array(
					'yes' => 'yes',
					'no'  => 'no'
					),
				'default'  => 'no',
			),

		),
	) );

	Redux::setSection( $opt_name, array(
		'title'      => __( 'Renter / Owner Separation', 'wprentals-core' ),
		'id'         => '_register_page_tab',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'wp_estate_separate_users',
				'type'     => 'button_set',
				'title'    => __( 'Separate users on registration?', 'wprentals-core' ),
				'subtitle' => __( 'Enable user type separation during registration. If enabled, users can either be renters (only book listings) or owners (can rent and book listings).', 'wprentals-core' ),
				'options'  => array(
					'yes'  => 'yes',
					'no' => 'no'
					),
				'default'  => 'no',
			),
			 array(
				'id'       => 'wp_estate_publish_only',
				'type'     => 'textarea',
				'title'    => __( 'Only these users can publish listings (can be owners).', 'wprentals-core' ),
				'subtitle' => __( 'Copy the username as it is writren in Wp-admin -> Users. Separate usernames with commas, without spaces. Example: paula,victoria ', 'wprentals-core' ),
			),

			array(
                'id'       => 'wp_estate_social_login_user_role',
                'type'     => 'button_set',
                'title'    => __( 'Default role for social login users', 'wprentals-core' ),
                'subtitle' => __( 'Role assigned to users who register through social login.', 'wprentals-core' ),
                'options'  => array(
                    'owner'  => 'owner',
                    'renter' => 'renter'
                ),
                'default'  => 'owner',
            ),
	),
	) );
	
	
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Register reCaptcha settings', 'wprentals-core' ),
		'id'         => 'recaptcha_tab',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'wp_estate_use_captcha',
				'type'     => 'button_set',
				'title'    => __( 'Add reCaptcha to register form?', 'wprentals-core' ),
				'subtitle' => __( 'This helps preventing registration spam with ReCaptcha system. All theme forms are already secured. ReCaptcha is Optional.', 'wprentals-core' ),
				'options'  => array(
							'yes' => 'yes',
							'no'  => 'no'
							),
				'default'  => 'no',
			),
			array(
				'id'       => 'wp_estate_recaptha_sitekey',
				'type'     => 'text',
				'required' =>   array('wp_estate_use_captcha','=','yes'),
				'title'    => __( 'reCaptha site key' , 'wprentals-core' ),
				'subtitle' => __( 'Get this detail after you signup here: ', 'wprentals-core' ).'<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">https://www.google.com/recaptcha/intro/index.html</a>',
			),
			array(
				'id'       => 'wp_estate_recaptha_secretkey',
				'type'     => 'text',
				'required' =>   array('wp_estate_use_captcha','=','yes'),
				'title'    => __( 'reCaptha secret key', 'wprentals-core' ),
				'subtitle' => __( 'Get this detail after you signup here: ', 'wprentals-core' ).'<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">https://www.google.com/recaptcha/intro/index.html</a>',
			),
		),
	) );
