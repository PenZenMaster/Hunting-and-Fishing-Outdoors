<?php 

// -> START Header options
Redux::setSection( $opt_name, array(
	'title' => __( 'Booking Configuration', 'wprentals-core' ),
	'id'    => 'booking-settings',
	'icon'  => 'el el-user'
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Form General Settings', 'wprentals-core' ),
	'id'         => 'booking_settings_tab',
	'subsection' => true,
	'fields'     => array(
		array(
		   'id'       => 'wp_estate_booking_type',
		   'type'     => 'button_set',
		   'title'    => __( 'Select Booking Type', 'wprentals-core' ),
		   'subtitle' => __( 'Select Global Booking Type', 'wprentals-core' ),
		   'options'  => array(
						'1' => __('Per Day for all Listings','wprentals-core'),
						'2' => __('Per Hour for all Listings','wprentals-core'),
						'3' => __('Mixt - Owner chooses price by hour or by day','wprentals-core')
						),
		   'default'  => '1',
		),
		array(
		   'id'       => 'wp_estate_setup_weekend',
		   'type'     => 'button_set',
		   'title'    => __( 'Select Weekend days', 'wprentals-core' ),
		   'subtitle' => __( 'Users can set a different price per day for weekend days', 'wprentals-core' ),
		   'options'  => array(
						'0' => __('Sunday and Saturday','wprentals-core'),
						'1' => __('Friday and Saturday','wprentals-core'),
						'2' => __('Friday, Saturday and Sunday','wprentals-core')
						),
		   'default'  => '0',
		),

		array(
			'id'       => 'wp_estate_week_days',
			'type'     => 'text',
			'title'    => __( 'Your number or nights / hours you wish to use instead of 7 nights (7 hours)', 'wprentals-core' ),
			'subtitle' => __( 'It allows owner to set a difference price per night / hour for longer periods. Changes apply to NEW bookings only.', 'wprentals-core' ),
			'default'  => '7',
		),
		array(
			'id'       => 'wp_estate_month_days',
			'type'     => 'text',
			'title'    => __( 'Your number or nights / hours you wish to use instead of 30 nights (30 hours)', 'wprentals-core' ),
			'subtitle' => __( 'It allows owner to set a difference price per night / hour for longer periods. Changes apply to NEW bookings only.', 'wprentals-core' ),
			'default'  => '30',
		),
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Guest Selector Settings', 'wprentals-core' ),
	'id'         => 'guest_settings_tab',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'wp_estate_custom_guest_control',
			'type'     => 'button_set',
			'title'    => __( 'Enable advanced guest control?', 'wprentals-core' ),
			'subtitle' => __( 'If Yes, you can select the number of adults, children, and infants separately. If No, the guest selection will be displayed as a simple dropdown.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				  ),
			'default'  => 'no',
		),
		
		array(
			'id'       => 'wp_estate_guest_dropdown_no',
			'type'     => 'text',
			'title'    => __( 'Maximum Guest number', 'wprentals-core' ),
			'subtitle' => __( 'Set the maximum number of guests that can be selected in the guest dropdown.', 'wprentals-core' ),
			'default'  => '15',
			'required' => array( 'wp_estate_custom_guest_control', '=', 'no' ), // Show only if "Enable advanced guest control?" is "no"
		),

		array(
			'id'       => 'wp_estate_item_rental_type',
			'type'     => 'button_set',
			'title'    => __( 'What do you Rent?', 'wprentals-core' ),
			'subtitle' => __( 'Object Rentals doesn\'t show the guest field on property booking form and changes the label "night" into "day".', 'wprentals-core' ),
			'options'  => array(
						'0' => __('Vacation Rental', 'wprentals-core'),
						'1' => __('Object Rental', 'wprentals-core')
					),
			'default'  => '0',
		),
		array(
			'id'       => 'wp_estate_show_guest_number',
			'type'     => 'button_set',

			'title'    => __( 'Show the Guest dropdown in Submission Form', 'wprentals-core' ),
			'subtitle' => __( 'Show the Guest dropdown in submit listing page? '
						 . 'Object Rentals needs this setting to be NO because guest does not exist in Booking Form', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
					),
			'default'  => 'yes',
		),


	),
	) );


	
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Form Display Settings', 'wprentals-core' ),
		'id'         => 'booking_contact_settings_tab',
		'subsection' => true,
		'fields'     => array(


			
		array(
			'id'       => 'wp_estate_replace_booking_form',
			'type'     => 'button_set',
			'title'    => __( 'Show Contact form instead of Booking Form ?', 'wprentals-core' ),
			'subtitle' => __( 'Show Contact form instead of Booking Form ?', 'wprentals-core' ),
			'options'  => array(
						'yes' => esc_html__( 'Yes','wprentals-core'),
						'no' => esc_html__( 'No','wprentals-core')
					),
			'default'  => 'no',
		),

		array(
			'id'       => 'wp_estate_show_booking_terms',
			'type'     => 'button_set',
			'required'  => array('wp_estate_replace_booking_form','=','no'),
			'title'    => __( 'Show Booking Terms checkbox?', 'wprentals-core' ),
			'subtitle' => __( 'Guest has to agree with booking terms before doing the booking request', 'wprentals-core' ),
			'options'  => array(
						'yes' => esc_html__( 'Yes','wprentals-core'),
						'no' => esc_html__( 'No','wprentals-core')
					),
			'default'  => 'no',
		),
	
		array(
			'id'       => 'wp_estate_show_booking_terms_link',
			'required'  => array('wp_estate_replace_booking_form','=','no'),
			'type'     => 'text',
			'title'    => __( 'Url for the Booking Terms page - applies to all listings on site', 'wprentals-core' ),
			'subtitle' => __( 'Url for the Booking Terms page - applies to all listings on site', 'wprentals-core' ),
	
			'default'  => '',
		),
		
		array(
			'id'       => 'wp_estate_show_booking_terms_label',
			'required'  => array('wp_estate_replace_booking_form','=','no'),
			'type'     => 'text',
			'title'    => __( 'Label for the Booking Terms page - applies to all listings on site', 'wprentals-core' ),
			'subtitle' => __( 'Label for the Booking Terms page - applies to all listings on site', 'wprentals-core' ),
	
			'default'  => 'I agree to the terms and condiitons',
		),

	
		
),
) );

	Redux::setSection( $opt_name, array(
		'title'      => __( 'Calendar Settings', 'wprentals-core' ),
		'id'         => 'calendar_settings_tab',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'wp_estate_date_format',
				'type'     => 'button_set',
				'title'    => __( 'Select Date Format for datepickers', 'wprentals-core' ),
				'subtitle' => __( 'Set a date format that applies to all datepickers', 'wprentals-core' ),
				'options'  => array(
							 '0' =>'yy-mm-dd',
							 '1' =>'yy-dd-mm',
							 '2' =>'dd-mm-yy',
							 '3' =>'mm-dd-yy',
							 '4' =>'dd-yy-mm',
							 '5' =>'mm-yy-dd',
							 ),
				'default'  => '0',
			 ),
				
			 array(
				 'id'       => 'wp_estate_date_lang',
				 'type'     => 'select',
				 'title'    => __( 'Language for datepicker', 'wprentals-core' ),
				 'subtitle' => __( 'Select the language for booking form datepicker and search by date datepicker', 'wprentals-core' ),
				 'options'  => array(
							 'xx'=> 'default',
							 'af'=>'Afrikaans',
							 'ar'=>'Arabic',
							 'ar-DZ' =>'Algerian',
							 'az'=>'Azerbaijani',
							 'be'=>'Belarusian',
							 'bg'=>'Bulgarian',
							 'bs'=>'Bosnian',
							 'ca'=>'Catalan',
							 'cs'=>'Czech',
							 'cy-GB'=>'Welsh/UK',
							 'da'=>'Danish',
							 'de'=>'German',
							 'el'=>'Greek',
							 'en-AU'=>'English/Australia',
							 'en-GB'=>'English/UK',
							 'en-NZ'=>'English/New Zealand',
							 'eo'=>'Esperanto',
							 'es'=>'Spanish',
							 'et'=>'Estonian',
							 'eu'=>'Karrikas-ek',
							 'fa'=>'Persian',
							 'fi'=>'Finnish',
							 'fo'=>'Faroese',
							 'fr'=>'French',
							 'fr-CA'=>'Canadian-French',
							 'fr-CH'=>'Swiss-French',
							 'gl'=>'Galician',
							 'he'=>'Hebrew',
							 'hi'=>'Hindi',
							 'hr'=>'Croatian',
							 'hu'=>'Hungarian',
							 'hy'=>'Armenian',
							 'id'=>'Indonesian',
							 'ic'=>'Icelandic',
							 'it'=>'Italian',
							 'it-CH'=>'Italian-CH',
							 'ja'=>'Japanese',
							 'ka'=>'Georgian',
							 'kk'=>'Kazakh',
							 'km'=>'Khmer',
							 'ko'=>'Korean',
							 'ky'=>'Kyrgyz',
							 'lb'=>'Luxembourgish',
							 'lt'=>'Lithuanian',
							 'lv'=>'Latvian',
							 'mk'=>'Macedonian',
							 'ml'=>'Malayalam',
							 'ms'=>'Malaysian',
							 'nb'=>'Norwegian',
							 'nl'=>'Dutch',
							 'nl-BE'=>'Dutch-Belgium',
							 'nn'=>'Norwegian-Nynorsk',
							 'no'=>'Norwegian',
							 'pl'=>'Polish',
							 'pt'=>'Portuguese',
							 'pt-BR'=>'Brazilian',
							 'rm'=>'Romansh',
							 'ro'=>'Romanian',
							 'ru'=>'Russian',
							 'sk'=>'Slovak',
							 'sl'=>'Slovenian',
							 'sq'=>'Albanian',
							 'sr'=>'Serbian',
							 'sr-SR'=>'Serbian-i18n',
							 'sv'=>'Swedish',
							 'ta'=>'Tamil',
							 'th'=>'Thai',
							 'tj'=>'Tajiki',
							 'tr'=>'Turkish',
							 'uk'=>'Ukrainian',
							 'vi'=>'Vietnamese',
							 'zh-CN'=>'Chinese',
							 'zh-HK'=>'Chinese-Hong-Kong',
							 'zh-TW'=>'Chinese Taiwan',
				 ),
				  'default'   => 'en-GB'
			 ),
			 
		 
			 array(
				 'id'       => 'wp_estate_month_no_show',
				 'type'     => 'text',
				 'title'    => __( 'Maximum Months Number (per night booking)', 'wprentals-core' ),
				 'subtitle' => __( 'Set the maximum number of months displayed in the Availability Calendar in listing page and Calendar Section in Edit Listing page. The recommended value is 12. Setting a higher number may cause the page to load slowly.', 'wprentals-core' ),
				 'default'  => '12',
			 ),
	),
	) );
