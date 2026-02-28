<?php 

// -> START Payments & Submit Selection
Redux::setSection( $opt_name, array(
	'title' => __( 'Add Listing Page & Payment Settings', 'wprentals-core' ),
	'id'    => 'membership_settings',
	'icon'  => 'el el-plus-sign'
) );

if(function_exists('wpestate_return_all_fields')){
	$wpestate_return_all_fields=wpestate_return_all_fields();
}else{
	$wpestate_return_all_fields=array();
}



Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_select/' );
Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing Submission Form', 'wprentals-core' ),
	'id'         => 'submit_page_settings_tab',
	'subsection' => true,
	'desc' => __( 'You must use the frontend listing submission form to add all listings, even if using the Single Owner / Single Admin setup. Administrators should NOT add listings from the WP admin dashboard.', 'wprentals-core' ),
	'fields'     => array(
		
		array(
			'id'       => 'wp_estate_submission_page_fields',
			'type'     => 'wpestate_select',
			'multi'     =>  true,

			'title'    =>   __( 'Select the Fields for listing submission.', 'wprentals-core' ),
			'subtitle' =>   __( 'Use CTRL to select multiple fields for listing submission page.', 'wprentals-core' ),
			'options'   =>   $wpestate_return_all_fields,
			'default'   =>   $wpestate_return_all_fields,
		),
		array(
			'id'       => 'wp_estate_mandatory_page_fields',
			'type'     => 'wpestate_select',
			'multi'     =>  true,
			'args'      => 'xxxx',
			'title'    =>   __( 'Select the Mandatory Fields for listing submission.', 'wprentals-core' ),
			'subtitle' =>   __( 'Make sure the mandatory fields for listing submission page are part of submit form (managed from the above setting). Use CTRL for multiple fields select.', 'wprentals-core' ),
			'options'   =>  array(),
		),


		array(
			'id'       => 'wp_estate_category_main',
			'type'     => 'text',
			'title'    => __( 'Main Category Label', 'wprentals-core' ),
			'subtitle' => __( 'Main Category Label', 'wprentals-core' ),
		),


		array(
			'id'       => 'wp_estate_category_main',
			'type'     => 'text',
			'title'    => __( 'Main Category Label', 'wprentals-core' ),
			'subtitle' => __( 'Main Category Label', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_category_main_dropdown',
			'type'     => 'text',
			'title'    => __( 'Main Category Label for dropdowns', 'wprentals-core' ),
			'subtitle' => __( 'Main Category Label for dropdowns', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_category_second',
			'type'     => 'text',
			'title'    => __( 'Secondary Category Label', 'wprentals-core' ),
			'subtitle' => __( 'Secondary Category Label', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_category_second_dropdown',
			'type'     => 'text',
			'title'    => __( 'Secondary Category Label for dropdowns', 'wprentals-core' ),
			'subtitle' => __( 'Secondary Category Label for dropdowns', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_item_description_label',
			'type'     => 'text',
			'title'    => __( 'Item Description Label', 'wprentals-core' ),
			'subtitle' => __( 'Item Description Label', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_prop_image_number',
			'type'     => 'text',
			'title'    => __( 'Maximum no of images per listing (only front-end upload)', 'wprentals-core' ),
			'subtitle' => __( 'Maximum no of images per listing (only front-end upload)', 'wprentals-core' ),
			'default'  => '12'
		),
		array(
			  'id'       => 'wp_estate_submit_redirect',
			  'type'     => 'text',
			  'title'    => __( 'Url where the user will be redirected after property submit.', 'wprentals-core' ),
			  'subtitle' => __( 'Leave blank if you want to remain on the same page.', 'wprentals-core' ),
			  'default'  => ''
		  ),
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Submission Settings', 'wprentals-core' ),
	'id'         => 'payment_settings_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_admin_submission',
			'type'     => 'button_set',
			'title'    => __( 'Submitted Listings should be approved by admin?', 'wprentals-core' ),
			'subtitle' => __( 'If yes, admin publishes each listing submitted in front end manually.', 'wprentals-core' ),
			'options'  => array(
						'yes'  => 'yes',
						'no'   => 'no'
					),
			'default'  => 'yes',
		),
		array(
			'id'       => 'wp_estate_paid_submission',
			'type'     => 'button_set',
			'title'    => __( 'Enable Paid Submission?', 'wprentals-core' ),
			'subtitle' => __( 'No = submission is free. Paid listing = submission requires user to pay a fee for each listing. Membership = submission is based on user membership package.', 'wprentals-core' ),
			'options'  => array(
						'no'         => 'no',
						'per listing'=> 'per listing',
						'membership' => 'membership'
					),
			'default'  => 'no',
		),

		array(
			'id'       => 'wp_estate_price_submission',
			'type'     => 'text',
			'required'  => array('wp_estate_paid_submission','=','per listing'),
			'title'    => __( 'Price Per Submission (for "per listing" mode)', 'wprentals-core' ),
			'subtitle' => __( 'Use .00 format for decimals (ex: 5.50). Do not set price as 0!', 'wprentals-core' ),
			'default'  => '0'
		),
		array(
			'id'       => 'wp_estate_price_featured_submission',
			'type'     => 'text',
			'required'  => array('wp_estate_paid_submission','=','per listing'),
			'title'    => __( 'Price to make the listing featured (for "per listing" mode)', 'wprentals-core' ),
			'subtitle' => __( 'Use .00 format for decimals (ex: 1.50). Do not set price as 0!', 'wprentals-core' ),
			'default'  => '0'
		),
		

		 array(
			'id'       => 'wp_estate_free_mem_list',
			'type'     => 'text',
			'required'  => array('wp_estate_paid_submission','=','membership'),
			'title'    => __( ' Free Membership - no of free listings for new users', 'wprentals-core' ),
			'subtitle' => __( 'If you change this value, the new value applies for new registered users. Old value applies for older registered accounts.', 'wprentals-core' ),
			'default'  => '0'
		 ),


		array(
			'id'       => 'wp_estate_free_mem_list_unl',
			'required'  => array('wp_estate_paid_submission','=','membership'),
			'type'     => 'checkbox',
			'title'    => __( 'Free Membership - Offer unlimited listings for new users', 'wprentals-core' ),
			'default'  => '1'// 1 = on | 0 = off
		),
		array(
			'id'       => 'wp_estate_free_feat_list',
			'required'  => array('wp_estate_paid_submission','=','membership'),
			'type'     => 'text',
			'title'    => __( 'Free Membership - no of featured listings (for "membership" mode)', 'wprentals-core' ),
			'subtitle' => __( 'If you change this value, the new value applies for new registered users. Old value applies for older registered accounts.', 'wprentals-core' ),
			'default'  => '0'
		),
		array(
			'id'       => 'wp_estate_free_feat_list_expiration',
			'required'  => array('wp_estate_paid_submission','=','membership'),
			'type'     => 'text',
			'title'    => __( 'Free Days for Each Free Listing - no of days until a free listing will expire. *Starts from the moment the listing is published on the website. (for "membership" mode only)', 'wprentals-core' ),
			'subtitle' => __( 'Option applies for each free published listing.', 'wprentals-core' ),
			'default'  => '0'
		),



),
) );


Redux::setSection( $opt_name, array(
	'title'      => __( 'Booking Payment Settings', 'wprentals-core' ),
	'id'         => 'booking_payment_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_include_expenses',
			'type'     => 'button_set',
			'title'    => __( 'Include expenses when calculating deposit?', 'wprentals-core' ),
			'subtitle' => __( 'Include expenses when calculating deposit. The expenses are city fee and cleaning fee.', 'wprentals-core' ),
			'default'   =>'no',
			'options'  => array(
					'yes' => 'yes',
					'no'  => 'no',

				),
		),
		array(
			'id'       => 'wp_estate_book_down',
			'type'     => 'text',
			'title'    => __( 'Deposit Fee - % booking fee', 'wprentals-core' ),
			'subtitle' => __( 'Expenses are included or not in the deposit amount according to the above option. If the value is set to 100 (100%) the "Include expenses when calculating deposit?" option will be auto set to "yes"!', 'wprentals-core' ),
			'default'  => '0'
		),
		array(
			'id'       => 'wp_estate_book_down_fixed_fee',
			'type'     => 'text',
			'title'    => __( 'Deposit Fee - fixed value booking fee', 'wprentals-core' ),
			'subtitle' => __( 'Add the fixed fee as a number. If you use this option, leave blank Deposit Fee - % booking fee', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_service_fee',
			'type'     => 'text',
			'title'    => __( 'Service Fee - % booking fee', 'wprentals-core' ),
			'subtitle' => __( 'Service Fee. Is the commision that goes to the admin and is deducted from the total booking value.', 'wprentals-core' ),
			'default'  => '0'
		),
		array(
			'id'       => 'wp_estate_service_fee_fixed_fee',
			'type'     => 'text',
			'title'    => __( 'Service Fee - fixed value service fee', 'wprentals-core' ),
			'subtitle' => __( 'Service Fee - fixed value service fee. If you use this option, leave blank Service Fee - % booking fee', 'wprentals-core' ),
		),
	),
) );


Redux::setSection( $opt_name, array(
	'title'      => __( 'Payment Currency Settings', 'wprentals-core' ),
	'id'         => 'general_payment_tab',
	'subsection' => true,
	'fields'     => array(



array(
	'id'       => 'wp_estate_paypal_api',
	'type'     => 'button_set',
	'title'    => __( 'Paypal & Stripe Api - SSL is mandatory for live payments', 'wprentals-core' ),
	'subtitle' => __( 'Sandbox = test API. LIVE = real payments API. Update PayPal and Stripe settings according to API type selection.', 'wprentals-core' ),
	'options'  => array(
			'sandbox' => 'sandbox',
			 'live'   =>  'live'
			),
	'default'  => 'sandbox',
),


array(
	'id'       => 'wp_estate_submission_curency',
	'type'     => 'button_set',
	'title'    => __( 'Currency For Paid Submission', 'wprentals-core' ),
	'subtitle' => __( 'The currency in which payments are processed.', 'wprentals-core' ),
	'options'  => array('USD' => 'USD',
						'EUR' => 'EUR',
						'AED' => 'AED',
						'ARS' => 'ARS',
						'AUD' => 'AUD',
						'BRL' => 'BRL',
						'BGN' => 'BGN',
						'CAD' => 'CAD',
						'COP' => 'COP',
						'CZK' => 'CZK',
						'DKK' => 'DKK',
						'HKD' => 'HKD',
						'HUF' => 'HUF',
						'ILS' => 'ILS',
						'JPY' => 'JPY',
						'JOD' => 'JOD',
						'MAD' => 'MAD', 
						'MXN' => 'MXN',
						'MYR' => 'MYR',
						'NOK' => 'NOK',
						'NZD' => 'NZD',
						'PHP' => 'PHP',
						'PLN' => 'PLN',
						'RON' => 'RON',
						'GBP' => 'GBP',
						'SGD' => 'SGD',
						'SEK' => 'SEK',
						'CHF' => 'CHF',
						'TWD' => 'TWD',
						'THB' => 'THB',
						'TRY' => 'TRY',
						'RUB' => 'RUB',
						'INR' => 'INR',
						'ZAR' => 'ZAR',
		),
	'default'  => 'USD',
),

array(
'id'       => 'wp_estate_submission_curency_custom',
'type'     => 'text',
'title'    => __( 'Custom Currency Symbol', 'wprentals-core' ),
'subtitle' => __( 'Add and save your own currency for Wire Transfer payments.', 'wprentals-core' ),
),

),
) );




Redux::setSection( $opt_name, array(
	'title'      => __( 'PayPal Settings', 'wprentals-core' ),
	'id'         => 'paypal_settings_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_enable_paypal',
			'type'     => 'button_set',
			'title'    => __( 'Enable Paypal', 'wprentals-core' ),
			'subtitle' => __( 'You can enable or disable PayPal buttons.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),
			'default' => 'no'
		),
		array(
			'id'       => 'wp_estate_paypal_client_id',
			'type'     => 'text',
			'required' => array('wp_estate_enable_paypal','=','yes'),
			'title'    => __( 'Paypal Client id', 'wprentals-core' ),
			'subtitle' => __( 'PayPal business account is required. Info is taken from https://developer.paypal.com/. See help:', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/paypal-set-up/" target="_blank">https://help.wprentals.org/article/paypal-set-up/</a>',
		),
		array(
			'id'       => 'wp_estate_paypal_client_secret',
			'type'     => 'text',
			 'required' => array('wp_estate_enable_paypal','=','yes'),
			'title'    => __( 'Paypal Client Secret Key', 'wprentals-core' ),
			'subtitle' => __( 'Info is taken from https://developer.paypal.com/ See help:', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/paypal-set-up/" target="_blank">https://help.wprentals.org/article/paypal-set-up/</a>',
		),
		array(
			'id'       => 'wp_estate_paypal_rec_email',
			'type'     => 'text',
			 'required' => array('wp_estate_enable_paypal','=','yes'),
			'title'    => __( 'Paypal receiving email', 'wprentals-core' ),
			'subtitle' => __( 'Info is taken from https://www.paypal.com/ or http://sandbox.paypal.com/ See help:', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/paypal-set-up/" target="_blank">https://help.wprentals.org/article/paypal-set-up/</a>',
		),

	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Stripe Settings', 'wprentals-core' ),
	'id'         => 'stripe_settings_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_enable_stripe',
			'type'     => 'button_set',
			'title'    => __( 'Enable Stripe', 'wprentals-core' ),
			'subtitle' => __( 'You can enable or disable Stripe buttons.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),
			'default' => 'no'
		),
		array(
			'id'       => 'wp_estate_stripe_secret_key',
			'required' => array('wp_estate_enable_stripe','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Stripe Secret Key', 'wprentals-core' ),
			'subtitle' => __( 'Info is taken from your account at https://dashboard.stripe.com/login See help: ', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/stripe-set-up/" target="_blank">https://help.wprentals.org/article/stripe-set-up/</a>',
		),
		array(
			'id'       => 'wp_estate_stripe_publishable_key',
			'required' => array('wp_estate_enable_stripe','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Stripe Publishable Key', 'wprentals-core' ),
			'subtitle' => __( 'Info is taken from your account at https://dashboard.stripe.com/login See help: ', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/stripe-set-up/" target="_blank">https://help.wprentals.org/article/stripe-set-up/</a>',
		),
		array(
			'id'       => 'wp_estate_stripe_webhook',
			'required' => array('wp_estate_enable_stripe','=','yes'),
			'type'     => 'text',
			'title'    => __( 'Stripe Webhook Secret Key', 'wprentals-core' ),
			'subtitle' => __( 'Info is taken from your account at https://dashboard.stripe.com/login See help: ', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/stripe-set-up/" target="_blank">https://help.wprentals.org/article/stripe-set-up/</a>',
		),
	),
) );
Redux::setSection( $opt_name, array(
	'title'      => __( 'Wire Transfer Settings', 'wprentals-core' ),
	'id'         => 'wire_transfer_settings_tab',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'wp_estate_enable_direct_pay',
			'type'     => 'button_set',
			'title'    => __( 'Enable Direct Payment / Wire Payment?', 'wprentals-core' ),
			'subtitle' => __( 'Enable or disable the wire payment option.', 'wprentals-core' ),
			'options'  => array(
						'yes'  => 'yes',
						'no'   => 'no'
					),
			'default'  => 'no',
		),
		array(
		'id' => 'wp_estate_direct_payment_details',
		'type'     => 'editor',
		'required'=>array('wp_estate_enable_direct_pay','=','yes'),
		'title' => __('Wire instructions for direct payment', 'wprentals-core'),
		'subtitle' => __('If wire payment is enabled, type the instructions below(Allowed htmls tags : a,br,em and strong).', 'wprentals-core'),
		'validate' => 'html_custom',
		'allowed_html' => array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'br'=>array(),
			'em' => array(),
			'strong' => array()
		)
	),
),



) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'WooCommerce Settings', 'wprentals-core' ),
	'id'         => 'woo_settings_tab',
	'subsection' => true,
	'fields'     => array(
	array(
		   'id'       => 'wp_estate_enable_woo_mes',
		   'type'     => 'info',
			'desc'   =>  __( 'You need WooCommerce Plugin Installed and Active & and a WooCommerce Merchant Enabled. <a href="https://help.wprentals.org/article/install-woocommerce-and-activate-woocommerce-payments/" target="_blank">See help page.</a> </br>Payments are considerd complete once the Order for a particular items has the status "Processing or Complete " . </br> WooCommerce does not suport recurring payments, and so submission membership packages cannot be bought via WooCommerce.', 'wprentals-core' ),


	   ),
		 array(
			'id'       => 'wp_estate_enable_woo',
			'type'     => 'button_set',
			'title'    => __( 'Enable WooCommerce payments?', 'wprentals-core' ),
			'subtitle' => __( '', 'wprentals-core' ),
			'options'  => array(
						'yes'       => 'yes',
						'no'        => 'no',

					),
			'default'  => 'no',
		),
		)

	));
	
