<?php 

//-> Start General  Section

Redux::setSection( $opt_name, array(
	'title' => __( 'General', 'wprentals-core' ),
	'id'    => 'wp_estate_general_settings_sidebar',
	'icon'  => 'el el-adjust-alt'
) );

 
Redux::setSection( $opt_name, array(
	'title'      => __( 'General Settings', 'wprentals-core' ),
	'id'         => 'wp_estate_general_settings_sidebar_tab',
	'subsection' => true,
	'fields'     => array(
		  array(
			'id'       => 'wp_estate_general_country',
			'type'     => 'select',
			'title'    => __( 'Country', 'wprentals-core' ),
			'subtitle' => __( 'Select default country', 'wprentals-core' ),
			'options'  =>  wprentals_return_country_array(),
			'default'  => 'United States'

		),

			array(
				'id'       => 'wp_estate_measure_sys',
				'type'     => 'select',
				'title'    => __( 'Measurement Unit', 'wprentals-core' ),
				'subtitle' => __( 'Select the measurement unit you will use on the website', 'wprentals-core' ),
				'options'  => array(
					'ft' => esc_html__( 'square feet - ft²', 'wprentals-core' ),
					'm'  => esc_html__( 'square meters - m²', 'wprentals-core' ),
				),
				'default' => 'ft',
			),
		

		
		array(
			'id'       => 'wp_estate_google_analytics_code',
			'type'     => 'text',
			'title'    => __( 'Google Analytics Tracking id (ex UA-41924406-1', 'wprentals-core' ),
			'subtitle' => __( 'Google Analytics Tracking id (ex UA-41924406-1)', 'wprentals-core' ),
		),

	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Theme Cache', 'wprentals-core' ),
	'id'         => 'cache_options_tab',
	'subsection' => true,
	'fields'     => array(
array(
	'id'       => 'wp_estate_disable_theme_cache',
	'type'     => 'button_set',
	'title'    => __( 'Disable Theme Cache', 'wprentals-core' ),
	'subtitle' => __( 'set the option to NO when your site is in production. Theme Cache will cache only the heavy database queries. Use this feature along classic cache plugins like WpRocket!', 'wprentals-core' ),
	'options'  => array(
					'yes'  => 'yes',
					'no' => 'no'
				),
	'default'  => 'no',
),
),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Orphan Listings', 'wprentals-core' ),
	'id'         => 'orphan_options_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_delete_orphan',
			'type'     => 'button_set',
			'title'    => __( 'Auto delete orphan listings?', 'wprentals-core' ),
			'subtitle' => __( 'Orphan Listings are listings that users start to submit but do not complete. A cron runs 1 time per day to delete orphan listings', 'wprentals-core' ),
			'options'  => array(
					'yes'  => 'yes',
					'no' => 'no'
					),
			'default' => 'no'
		),
),
) );

Redux::setSection( $opt_name, array(
'title'      => __( 'List & Sidebar Appearance', 'wprentals-core' ),
'id'         => 'appearance_options_tab',
'subsection' => true,
'fields'     => array(
		array(
			'id'       => 'wp_estate_prop_no',
			'type'     => 'text',
			'title'    => __( 'No of Properties per Page in Property List Templates and Category/Taxonomy Lists', 'wprentals-core' ),
			'subtitle' => __( 'Set the Same Value as in WordPress - Settings - Reading - Pages show at most x posts', 'wprentals-core' ),
			'default'  => '10'
		),
		array(
			'id'       => 'wp_estate_blog_sidebar',
			'type'     => 'button_set',
			'title'    => __( 'Select Sidebar Position for Property Category/Taxonomy List with Standard Layout and Blog Category/Archive List', 'wprentals-core' ),
			'subtitle' => __( 'Sidebar Position: Right, Left, or None', 'wprentals-core' ),
			'options'  => array('no sidebar' => 'no sidebar','right' => 'right','left'=>'left'),
			'default'  =>'right'
		),
		array(
			'id'       => 'wp_estate_blog_sidebar_name',
			'type'     => 'select',
			'title'    => __( 'Property Category/Taxonomy and Blog Category/Archive - Select the Sidebar', 'wprentals-core' ),
			'subtitle' => __( 'Which sidebar to show for blog category/archive list. Create new Sidebars from Appearance -≥ Sidebars.', 'wprentals-core' ),

			'data'  =>  'sidebars',
			'default'  => 'primary-widget-area'

		),

		array(
			'id'       => 'wp_estate_property_list_type',
			'type'     => 'button_set',
			'title'    => __( 'Layout Type for Property Category/Taxonomy List Pages', 'wprentals-core' ),
			'subtitle' => __( 'Half map does not support sidebar. Applies for all property categories / taxonomies pages.', 'wprentals-core' ),
			'options'  => array(
				'1' =>  __( 'standard','wprentals-core'),
				'2' =>  __( 'half map','wprentals-core')
			),
			'default'  => '1'
		),

		array(
			'id' => 'wp_estate_global_sticky_sidebar',
			'type' => 'button_set',
			'title' => __('Enable Sticky Sidebar for all pages', 'wprentals-core'),
			'subtitle' => __('Enable Sticky Sidebar for all pages', 'wprentals-core'),

			'options' => array(
				'no'=>'no',
				'yes'=>'yes'
			),
			'default' => 'no',
		),
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Logos & Favicon', 'wprentals-core' ),
	'id'         => 'logos_favicon_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_favicon_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Your Favicon', 'wprentals-core' ),
			'subtitle' => __( 'Upload site favicon in .ico, .png, .jpg or .gif format', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_logo_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Your Logo', 'wprentals-core' ),
			'subtitle' => __( 'Use the "Upload" button and "Insert into Post" button from the pop up window.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_transparent_logo_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Your Transparent Header Logo', 'wprentals-core' ),
			'subtitle' => __( 'Use the "Upload" button and "Insert into Post" button from the pop up window.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_mobile_logo_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Mobile/Tablets Logo', 'wprentals-core' ),
			'subtitle' => __( 'Upload mobile logo in jpg or png format.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_logo_image_retina',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Your Retina Logo', 'wprentals-core' ),
			'subtitle' => __( 'To create retina logo, add _2x at the end of name of the original file (for ex logo_2x.jpg)', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_transparent_logo_image_retina',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Your Transparent Retina Logo', 'wprentals-core' ),
			'subtitle' => __( 'To create retina logo, add _2x at the end of name of the original file (for ex logo_2x.jpg)', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_mobile_logo_image_retina',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Your Mobile Retina Logo', 'wprentals-core' ),
			'subtitle' => __( 'To create retina logo, add _2x at the end of name of the original file (for ex logo_2x.jpg)', 'wprentals-core' ),
		),
		
		array(
			'id'       => 'wp_estate_logo_max_height',
			'type'     => 'text',
			'title'    => __( 'Maximum height for the logo in px', 'wprentals-core' ),
			'subtitle' => __( 'Change the maximum height of the logo. Add only a number (ex: 60). Change Header height and sticky header height in Design -> Header Design.', 'wprentals-core' ),
		),

		 array(
			'id'       => 'wp_estate_logo_max_width',
			'type'     => 'text',
			'title'    => __( 'Maximum width for the logo in px', 'wprentals-core' ),
			'subtitle' => __( 'Change the maximum width of the logo. Add only a number (ex: 200).', 'wprentals-core' ),
		),
	),
) );


Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_currency/' );
Redux::setSection( $opt_name, array(
	'title'      => __( 'Price & Currency', 'wprentals-core' ),
	'id'         => 'price_curency_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_prices_th_separator',
			'type'     => 'text',
			'title'    => __( 'Price - thousands separator', 'wprentals-core' ),
			'subtitle' => __( 'Set the thousand separator for price numbers.', 'wprentals-core' ),
			'default'  => '.',
		),
		array(
			'id'       => 'wp_estate_currency_label_main',
			'type'     => 'text',
			'title'    => __( 'Currency Symbol', 'wprentals-core' ),
			'subtitle' => __( 'This is used for default listing price currency symbol and default currency symbol in multi currency dropdown', 'wprentals-core' ),
			'default'  =>'USD',
			),
		array(
			'id'       => 'wp_estate_where_currency_symbol',
			'type'     => 'button_set',
			'title'    => __( 'Where to show the currency symbol?', 'wprentals-core' ),
			'subtitle' => __( 'Where to show the currency symbol?', 'wprentals-core' ),
			'options'  =>  array(
					'before' => 'before',
					'after'  => 'after'
				),
			'default'  => 'before'
		),
		array(
			'id'       => 'wp_estate_currency_symbol',
			'type'     => 'text',
			'title'    => __( 'Currency code', 'wprentals-core' ),
			'subtitle' => __( 'Currency code is used for syncing the multi-currency options with Free Currency Exchange APi, if enabled.', 'wprentals-core' ),
			'default'  => '$'
		),
		array(
			'id'       => 'wp_estate_auto_curency',
			'type'     => 'button_set',
			'title'    => __( 'Enable auto loading of exchange rates from https://currencylayer.com/ (1 time per day)?', 'wprentals-core' ),
			'subtitle' => __( 'Currency code must be set according to international standards. Complete list is here', 'wprentals-core' ).'<a href="http://www.xe.com/iso4217.php" target="_blank"> http://www.xe.com/iso4217.php </a>',
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				  ),
			'default'  => 'no',
		),
		array(
			'id'       => 'wp_estate_currencyconverterapi_api',
			'type'     => 'text',
			'title'    => __( 'Currencyconverterapi.com Api Key', 'wprentals-core' ),
			'subtitle' => __( 'Get the free api key from here https://currencylayer.com/signup/free', 'wprentals-core' ),
			'default'  => '',
		),

		array(
		   'id'       => 'wpestate_currency',
		   'type'     => 'wpestate_currency',
		   'title'    => __( 'Add Currencies for Multi Currency Widget.', 'wprentals-core' ),
		   'class'    => 'class_wpestate_currency',
		   'full_width' => true,

	   ),
	),
) );




$default_custom_field   =   array();
$def_add_field_name     =   array('Check-in hour','Check-Out hour','Late Check-in','Optional services','Outdoor facilities','Extra People','Cancellation');
$def_add_field_label        =   array('Check-in hour','Check-Out hour','Late Check-in','Optional services','Outdoor facilities','Extra People','Cancellation');
$def_add_field_order        =   array(1,2,3,4,5,6,7);
$def_add_field_type         =   array('short text','short text','short text','short text','short text','short text','short text');

$default_custom_field['add_field_name']=$def_add_field_name;
$default_custom_field['add_field_label']=$def_add_field_label;
$default_custom_field['add_field_order']=$def_add_field_order;
$default_custom_field['add_field_type']=$def_add_field_type;




Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_custom_fields_list/' );
Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing Custom Fields', 'wprentals-core' ),
	'id'         => 'custom_fields_tab',
	'subsection' => true,
	'fields'     => array(
		array(
		   'id'       => 'wpestate_custom_fields_list',
		   'type'     => 'wpestate_custom_fields_list',
		   'full_width' => true,
		   'title'    => __( 'Add, edit or delete property custom fields.', 'wprentals-core' ),
		   'default'  => $default_custom_field
	   ),
	),
) );




Redux::setSection( $opt_name, array(
	'title'      => __( 'Splash Page', 'wprentals-core' ),
	'id'         => 'splash_page_page_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_spash_header_type',
			'type'     => 'select',
			'title'    => __( 'Select the splash page type.', 'wprentals-core' ),
			'subtitle' => __( 'Important: Create also a page with template "Splash Page" to see how your splash settings apply', 'wprentals-core' ),
			'options'  => array(
					'image'       => 'image' ,
					'video'       => 'video',
					'image slider' => 'image slider'
				),
			'default' =>  'image'

		),


		array(
			'id'       => 'wp_estate_splash_slider_gallery',
			'type'     => 'gallery',
			'class'    => 'slider_splash',
			'required' => array('wp_estate_spash_header_type', '=', 'image slider'),
			'title'    => __( 'Slider Images', 'wprentals-core' ),
			'subtitle' => __( 'Slider Images, .png, .jpg or .gif format', 'wprentals-core' ),

		),


		 array(
			'id'       => 'wp_estate_splash_slider_transition',
			'type'     => 'text',
			'class'    => 'slider_splash',
			'required' => array('wp_estate_spash_header_type', '=', 'image slider'),
			'title'    => __( 'Slider Transition', 'wprentals-core' ),
			'subtitle' => __( 'Number of milisecons before auto cycling an item (5000=5sec).Put 0 if you don\'t want to autoslide.', 'wprentals-core' ),

		),




		array(
			'id'       => 'wp_estate_splash_image',
			'type'     => 'media',
			'class'    => 'image_splash',
			'required' => array('wp_estate_spash_header_type', '=', 'image'),
			'title'    => __( 'Splash Image', 'wprentals-core' ),
			'subtitle' => __( 'Splash Image, .png, .jpg or .gif format', 'wprentals-core' ),

		),



		  array(
			'id'       => 'wp_estate_splash_video_mp4',
			'type'     => 'media',
			'class'    => 'video_splash',
			'url'      => true,
			'preview'  => false,
			'mode'     => false,
			'required' => array('wp_estate_spash_header_type', '=', 'video'),
			'title'    => __( 'Splash Video in mp4 format', 'wprentals-core' ),
			'subtitle' => __( 'Splash Video in mp4 format', 'wprentals-core' ),
		),





		array(
			'id'       => 'wp_estate_splash_video_webm',
			'type'     => 'media',
			'class'    => 'video_splash',
			'url'      => true,
			'preview'  => false,
			'mode'     => false,
			'required' => array('wp_estate_spash_header_type', '=', 'video'),
			'title'    => __( 'Splash Video in webm format', 'wprentals-core' ),
			'subtitle' => __( 'Splash Video in webm format', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_splash_video_ogv',
			'type'     => 'media',
			'class'    => 'video_splash',
			'url'      => true,
			'preview'  => false,
			'mode'     => false,
			'required' => array('wp_estate_spash_header_type', '=', 'video'),
			'title'    => __( 'Splash Video in ogv format', 'wprentals-core' ),
			'subtitle' => __( 'Splash Video in ogv format', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_splash_video_cover_img',
			'type'     => 'media',
			'class'    => 'video_splash',
			'required' => array('wp_estate_spash_header_type', '=', 'video'),
			'title'    => __( 'Cover Image for video', 'wprentals-core' ),
			'subtitle' => __( 'Cover Image for videot', 'wprentals-core' ),
		),



		array(
			'id'       => 'wp_estate_splash_overlay_image',
			'type'     => 'media',
			'title'    => __( 'Overlay Image', 'wprentals-core' ),
			'subtitle' => __( 'Overlay Image, .png, .jpg or .gif format', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_splash_overlay_color',
			'type'     => 'color',
			'title'    => __( 'Overlay Color', 'wprentals-core' ),
			'subtitle' => __( 'Overlay Color', 'wprentals-core' ),
			'transparent' => false,

		),
		array(
			'id'       => 'wp_estate_splash_overlay_opacity',
			'type'     => 'text',
			'title'    => __( 'Overlay Opacity', 'wprentals-core' ),
			'subtitle' => __( 'Overlay Opacity - values from 0 to 1 , Ex: 0.4', 'wprentals-core' ),

		),
		array(
			'id'       => 'wp_estate_splash_page_title',
			'type'     => 'text',
			'title'    => __( 'Splash Page Title', 'wprentals-core' ),
			'subtitle' => __( 'Splash Page Title', 'wprentals-core' ),

		),
		array(
			'id'       => 'wp_estate_splash_page_subtitle',
			'type'     => 'text',
			'title'    => __( 'Splash Page Subtitle', 'wprentals-core' ),
			'subtitle' => __( 'Splash Page Subtitle', 'wprentals-core' ),

		),
		array(
			'id'       => 'wp_estate_splash_page_logo_link',
			'type'     => 'text',
			 'preview'  => false,
			'title'    => __( 'Logo Link', 'wprentals-core' ),
			'subtitle' => __( 'In case you want to send users to another page', 'wprentals-core' ),
		),



	),
) );



Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_custom_url_rewrite/' );
Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing and Owner Links Names', 'wprentals-core' ),
	'id'         => 'property_rewrite_page_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'     => 'opt-info_links',
			'type'   => 'info',
			'notice' => false,
			'title'   => __( 'You cannot use special characters like "&". After changing the url you may need to wait for a few minutes until WordPress changes all the urls. In case your new names do not update automatically, go to Settings - Permalinks and Save again the "Permalinks Settings" - option "Post name"', 'wprentals-core' )
		),
		 array(
			'id'     => 'opt-info_links2',
			'type'   => 'info',
			'notice' => false,
			'title'   => __( ' DO NOT USE "type" as this name is reserved by WordPress ', 'wprentals-core' ).'<a href="https://codex.wordpress.org/Reserved_Terms" target="_blank">https://codex.wordpress.org/Reserved_Terms</a>'
		),
		array(
			'id'     => 'wp_estate_url_rewrites',
			'type'   => 'wpestate_custom_url_rewrite',
			'notice' => false,
			'full_width'    => true,

		),

	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Default Images', 'wprentals-core' ),
	'id'         => 'default_images_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_default_property_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Default Property Image', 'wprentals-core' ),
			'subtitle' => __( 'Upload Default Property Image in .png, .jpg or .gif format', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_default_user_image',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Default User Image', 'wprentals-core' ),
			'subtitle' => __( 'Upload Default User Image in .png, .jpg or .gif format', 'wprentals-core' ),
		),
	)
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Reviews Options', 'wprentals-core' ),
	'id'         => 'reviews_options_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id' => 'wp_estate_admin_approves_reviews',
			'type' => 'button_set',
      'title' => __('Should admin approve the reviews?', 'wprentals-core'),
			'subtitle' => __('If yes, the reviews will appear in the Reviews section under the Listings, Bookings & More menu.u', 'wprentals-core'),
			'options' => array(
				'yes' => 'yes',
				'no' => 'no'
			),
			'default' => 'yes',
		),
	)
) );