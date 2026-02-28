<?php 


// -> START Design Selection
Redux::setSection( $opt_name, array(
	'title' => __( 'Listing Page', 'wprentals-core' ),
	'id'    => 'listing_page_settings_sidebar',
	'icon'  => 'el el-file'
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing Page Settings', 'wprentals-core' ),
	'id'         => 'property_page_settings_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_listing_page_type',
			'type'     => 'button_set',
			'title'    => __( 'Listing Page Design Type', 'wprentals-core' ),
			'subtitle' => __( 'Select design type for Listing Page .', 'wprentals-core' ),
			'options'  => array(
						'1' => esc_html__( 'Type 1','wprentals-core'),
						'2' => esc_html__( 'Type 2','wprentals-core'),
						'3' => esc_html__( 'Type 3','wprentals-core'),
						'4' => esc_html__( 'Type 4','wprentals-core'),
						'5' => esc_html__( 'Type 5','wprentals-core'),
					),
			'default'  => '1',
		),

		array(
			'id'       => 'wp_estate_show_map_location',
			'type'     => 'button_set',
			'title'    => __( 'Hide property map location until booking is confirmed?', 'wprentals-core' ),
			'subtitle' => __( 'If "Yes", the address or exact location will not be shown on the property page map.', 'wprentals-core' ),
			'options'  => array(
						'yes' => esc_html__( 'Yes','wprentals-core'),
						'no' => esc_html__( 'No','wprentals-core')
					),
			'default'  => 'no',
		),

	   
	
		array(
			'id'       => 'wp_estate_show_min_nights_calendar',
			'type'     => 'button_set',
			'title'    => __( 'Show Minimum nights in availability calendar?', 'wprentals-core' ),
			'subtitle' => __( 'Show Minimum nights in availability calendar?(the calendar in content , not the booking calendar)', 'wprentals-core' ),
			'options'  =>  array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'no',
		),
	),
) );
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Listing Page Sidebar', 'wprentals-core' ),
		'id'         => 'property_sidebar_manager_tab',
		'subsection' => true,
		'fields'     => array(
			array(
			'id'       => 'wp_estate_property_sidebar_sitcky',
			'type'     => 'button_set',
			'title'    => __( 'Use Sticky Sidebar on Listing page', 'wprentals-core' ),
			'subtitle' => __( 'Use Sticky Sidebar on Listing page.', 'wprentals-core' ),
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'

				),
			'default'  => 'no',
		),
	),
	) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing Page Layout Manager', 'wprentals-core' ),
	'id'         => 'property_page_layout_manager_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wprentals_layout_manager',
			'type'     => 'button_set',
			'title'    => __( 'Enable Layout Manager ?', 'wprentals-core' ),
			'subtitle' => __( 'If yes, you will have the option re-arrange the sections on property page.', 'wprentals-core' ),
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'
				),
			'default'  => 'no',
		),
		
		array(
			'id'       => 'wprentals_hide_description',
			'type'     => 'button_set',
			'title'    => __( 'Hide Default Description Section?', 'wprentals-core' ),
			'subtitle' => __( 'Hide Default Description Section?', 'wprentals-core' ),
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'
				),
			'default'  => 'no',
		),
		
		array(
			'id'       => 'wprentals_hide_default_owner',
			'type'     => 'button_set',
			'title'    => __( 'Hide Default Owner section?', 'wprentals-core' ),
			'subtitle' => __( 'Hide Default Owner section?', 'wprentals-core' ),
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'
				),
			'default'  => 'no',
		),
		  array(
			'id'       => 'wprentals_hide_default_map',
			'type'     => 'button_set',
			'title'    => __( 'Hide Default Map section?', 'wprentals-core' ),
			'subtitle' => __( 'Hide Default Map section?', 'wprentals-core' ),
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'
				),
			'default'  => 'no',
		),
		
		array(
			'id'       => 'wprentals_hide_similar_listing',
			'type'     => 'button_set',
			'title'    => __( 'Hide Default Similar Listing Section?', 'wprentals-core' ),
			'subtitle' => __( 'Hide Default Similar Listing Section?', 'wprentals-core' ),
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'
				),
			'default'  => 'no',
		),
		
	  
		
		array(
			'id'      => 'wprentals_property_layout_tabs',
			'type'    => 'sorter',
			'title'   => 'Property Page Layout Manager',
			'desc'    => 'Drag and drop sections and organize your property page design.',
			'options' => array(
				'enabled'  => array(
					'gallery'               => esc_html__('Gallery', 'wprentals-core'),
					'description'           => esc_html__('Description', 'wprentals-core'),
					'price_details'         => esc_html__('Price Details', 'wprentals-core'),
					'sleeping'              => esc_html__('Sleeping Arrangements', 'wprentals-core'),
					'address'               => esc_html__('Address', 'wprentals-core'),
					'listing_details'       => esc_html__('Listing Details', 'wprentals-core'),
					'features'              => esc_html__('Features', 'wprentals-core'),
					'terms'                 => esc_html__('Terms and Conditions', 'wprentals-core'),
					'nearby'                => esc_html__('What\'s Nearby', 'wprentals-core'),
					'availability'          => esc_html__('Availability', 'wprentals-core'),
					'reviews'               => esc_html__('Reviews', 'wprentals-core'),
					'virtual_tour'          => esc_html__('Virtual Tour', 'wprentals-core'),
					'map'                   => esc_html__('Map', 'wprentals-core'),
					'owner'                 => esc_html__('Owner Section', 'wprentals-core'),
					'similar'               => esc_html__('Similar Listings', 'wprentals-core'),
				),
				'disabled' => array(
				  
				)
			),
		),
		


	   
		)
	));


	
Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_property_page_header/' );

Redux::setSection($opt_name, array(
'title' => __('Listing Page Overview Design ', 'wprentals-core'),
'id' => 'listing_icon_area_design_composer_extra_details_tab',
'subsection' => true,
'fields' => array(
	array(
			'id'       => 'wp_estate_use_custom_icon_area',
			'type'     => 'button_set',
			'title'    => __( 'Use Custom Icon Area?', 'wprentals-core' ),
			'subtitle' => __( 'Use Custom Icon Area?', 'wprentals-core' ),
			'options'  =>  array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'no',
		),

	array(
		'id'       => 'wp_estate_property_page_header',
		'type'     => 'wpestate_property_page_header',
		'full_width' => true,
		'required' => array('wp_estate_use_custom_icon_area','=','yes'),
		'class'    =>'wpestate_property_page_header',
		'title'    => __( 'Listing Icon Area Design', 'wprentals-core' ),
		'subtitle' => __( 'Add, edit or delete listing custom fields.', 'wprentals-core' ),
		'default'  => ''// 1 = on | 0 = off
	),
	
 
	  
	array(
			'id' => 'wp_estate_property_page_icon_area_font_size',
			'required' => array('wp_estate_use_custom_icon_area','=','yes'),
			'type' => 'typography',
			'title' => __(' Font Control', 'wprentals-core'),
			'subtitle' => __('Set font size,weight or color', 'wprentals-core'),
		   
			'default'     => array(
			'font-weight'  => '500',
			'font-family' => 'Roboto',
			'google'      => true,
			'font-size'   => '14px',
			),
			'color' => true,
			'text-align' => false,
			'units' => 'px',
			'font-style'=>true,
			 'font-family' => true,
			'font-weight'=>true,           
	),
	
	array(
		'id' => 'wp_estate_property_page_icon_area_icon_size',
		'type' => 'text',
		'required' => array('wp_estate_use_custom_icon_area','=','yes'),
		'title' => __(' Image or Icons Size', 'wprentals-core'),
		'subtitle' => __('Image or Icons Size max-height in px', 'wprentals-core'),
		'default' => '17',
	),
	array(
		'id' => 'wp_estate_property_page_icon_area_color',
		'required' => array('wp_estate_use_custom_icon_area','=','yes'),
		'type' => 'color',
		'title' => __('Icon Color - (it is not applicable to images)', 'wprentals-core'),
		'subtitle' => __('For Images, upload the image in the color you wish', 'wprentals-core'),
		'transparent' => false,
	),
	
	array(
		'id' => 'wp_estate_property_page_icon_area_alignment',
		'type' => 'button_set',
		'required' => array('wp_estate_use_custom_icon_area','=','yes'),
		'title' => __('Align options', 'wprentals-core'),
		'subtitle' => __('Manage how to align the fields in the details row.', 'wprentals-core'),
		'options' => array(
			'flex-start' => 'left',
			'flex-end' => 'right',
			'space-between' => 'fill'
		),
		'default' => 'fill',
	),
	
	
	
	
	array(
		'id' => 'wp_estate_property_page_icon_area_image_position',
		'type' => 'button_set',
		'required' => array('wp_estate_use_custom_icon_area','=','yes'),
		'title' => __('Image/Icon position', 'wprentals-core'),
		'subtitle' => __('Manage how to align the icon', 'wprentals-core'),
		'options' => array(
			'row' => 'left',
			'row-reverse' => 'right',
			'column' => 'top',
			'column-reverse'=>'bottom'
		),
		'default' => 'left',
	),
	
	array(
		'id' => 'wp_estate_property_page_icon_area_text_position',
		'type' => 'button_set',
		'required' => array('wp_estate_use_custom_icon_area','=','yes'),
		'title' => __('Text position', 'wprentals-core'),
		'subtitle' => __('Where to place the text?', 'wprentals-core'),
		'options' => array(
			'start' => 'start',
			'end' => 'end',
	   
		),
		'default' => 'start',
	),
	array(
		'id' => 'wp_estate_property_page_icon_area_gap',
		'type' => 'text',
		'required' => array('wp_estate_use_custom_icon_area','=','yes'),
		'title' => __('Gap between details in px', 'wprentals-core'),
		'subtitle' => __('Space between details', 'wprentals-core'),
		
	),
  
)));
	
Redux::setSection( $opt_name, array(
	'title'      => __( 'Listings Page Text Labels', 'wprentals-core' ),
	'id'         => 'listing_labels_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_property_adr_text',
			'type'     => 'text',
			'title'    => __( 'Property Address Label', 'wprentals-core' ),
			'subtitle' => __( 'Custom title instead of Property Address Label.', 'wprentals-core' ),
			'default'  => 'Property Address',
		),
		array(
			'id'       => 'wp_estate_property_features_text',
			'type'     => 'text',
			'title'    => __( 'Property Features Label', 'wprentals-core' ),
			'subtitle' => __( 'Custom title instead of Property Features Label.', 'wprentals-core' ),
			'default'  => 'Property Features',
		),
		array(
			'id'       => 'wp_estate_property_description_text',
			'type'     => 'text',
			'title'    => __( 'Property Description Label', 'wprentals-core' ),
			'subtitle' => __( 'Custom title instead of Property Description Label.', 'wprentals-core' ),
			'default'  => 'Property Description',
		),
		array(
			'id'       => 'wp_estate_property_details_text',
			'type'     => 'text',
			'title'    => __( 'Property Details Label', 'wprentals-core' ),
			'subtitle' => __( 'Custom title instead of Property Details Label.', 'wprentals-core' ),
			'default'  => 'Property Details',
		),
		array(
			'id'       => 'wp_estate_property_price_text',
			'type'     => 'text',
			'title'    => __( 'Property Price Label', 'wprentals-core' ),
			'subtitle' => __( 'Custom title instead of Property Price label.', 'wprentals-core' ),
			'default'  => 'Property Price',
		),
		array(
			'id'       => 'wp_estate_sleeping_text',
			'type'     => 'text',
			'title'    => __( 'Sleeping Situation Label', 'wprentals-core' ),
			'subtitle' => __( 'Custom title instead of Sleeping Situation label.', 'wprentals-core' ),
			'default'  => 'Sleeping Situation',
		),
		array(
			'id'       => 'wp_estate_terms_text',
			'type'     => 'text',
			'title'    => __( 'Terms and Conditions Label', 'wprentals-core' ),
			'subtitle' => __( 'Custom title instead of STerms and Conditions label.', 'wprentals-core' ),
			'default'  => 'Terms and Conditions',
		),
		 array(
			'id'       => 'wp_estate_bed_list',
			'type'     => 'text',
			'title'    => __( 'Types of Beds', 'wprentals-core' ),
			'subtitle' => __( 'List of bed types separated by comma', 'wprentals-core' ),
			'default'  => 'King Bed,Queen Bed,Double,Single,Couch',
		),
	),
) );   

$default_feature_list='Kitchen,Internet,Smoking Allowed,TV,Wheelchair Accessible,Elevator in Building,Indoor Fireplace,Heating,Essentials,Doorman,Pool,Washer,Hot Tub,Dryer,Gym,Free Parking on Premises,Wireless Internet,Pets Allowed,Family/Kid Friendly,Suitable for Events,Non Smoking,Phone (booth/lines),Projector(s),Bar / Restaurant,Air Conditioner,Scanner / Printer,Fax';

Redux::setSection( $opt_name, array(
	'title'      => __( 'Features & Amenities', 'wprentals-core' ),
	'id'         => 'ammenities_features_tab',
	'subsection' => true,
	'fields'     => array(
		array(
		   'id'       => 'wp_estate_feature_list',
		   'type'     => 'info',
			'desc'   =>  __( 'Starting with v2.6 all features & amenities are converted to taxonomy (category) terms. Manage Features & Amenities from the left sidebar, Listings -> Features & Amenities menu or from Edit Property in wp-admin.', 'wprentals-core' ),


	   ),
		array(
			'id'       => 'wp_estate_show_no_features',
			'type'     => 'button_set',
			'title'    => __( 'Show the Features and Amenities that are not available', 'wprentals-core' ),
			'subtitle' => __( 'Show on property page the features and amenities that are not selected?', 'wprentals-core' ),
			'options'  => array(
						'yes'  => 'yes',
						'no'   => 'no'
				),
			'default'  => 'yes',
		),

	),
) );



Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing Text Disclaimer', 'wprentals-core' ),
	'id'         => 'disclaimer_section_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_disclaiment_text',
	 
			'type'     => 'editor',
			'title'    => __( 'Disclaimer Text', 'wprentals-core' ),
			'subtitle' => __( 'Shows at the end of the listing page. You can use the strings %property_address and %propery_id and the theme will replace those with the property address and id. ', 'wprentals-core' ),
		),
		 
	
)));

Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_custom_fields_infobox/' );
Redux::setSection( $opt_name, array(
	'title'      => __( 'Map Marker Infobox Design', 'wprentals-core' ),
	'id'         => 'infobox_design_tab',
	'subsection' => true,
	'fields'     => array(
			array(
			'id'       => 'wp_estate_custom_icons_infobox',
			'type'     => 'button_set',
			'title'    => __( 'Use custom icons on Infobox ?', 'wprentals-core' ),
			'subtitle' => __( 'Use custom icons on Infobox ?', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'no',
		),
		array(
			'id'       =>   'wp_estate_custom_infobox_fields',
			'type'     =>   'wpestate_custom_fields_infobox',
			'title'    => __( 'Custom Fields for Infobox', 'wprentals-core' ),
			'subtitle' => __( 'Add, edit or delete listing custom fields.', 'wprentals-core' ),
			'full_width' => true,

		),
	),
) );

   
$wpestate_listings_sort_options_array=array();
if(function_exists('wpestate_listings_sort_options_array')){
	$wpestate_listings_sort_options_array= wpestate_listings_sort_options_array();
}

Redux::setSection( $opt_name, array(
	'title'      => __( 'Similar Listings', 'wprentals-core' ),
	'id'         => 'similar_listings_section_tab',
	'subsection' => true,
	'fields'     => array(
			

		array(
				'id'       => 'wp_estate_similar_prop_no',
				'type'     => 'text',
				'title'    => __( 'No of similar properties in property page', 'wprentals-core' ),
				'subtitle' => __( 'Similar listings show when there are other properties from the same area, city, type and category.', 'wprentals-core' ),
				'default'  => '4'
		),

		array(
			'id'       => 'wp_estate_simialar_taxes',
			'type'     => 'select',
			'multi'    => true,
			'title'    => __( 'Select taxonomies for similar listings', 'wprentals-core' ),
			'subtitle' => __( 'Select taxonomies for similar listings( if none is selected we will use property category, property action category and property city)', 'wprentals-core' ),
		
			
			 'options'  => array(
					'property_category' =>'category',
					'property_action_category'=>'action category',
					'property_city'=>'city',
					'property_area'=>'area'
				),

		),
		 array(
			'id'       => 'wp_estate_similar_listins_order',
			'type'     => 'select',
			'title'    => __( 'Select Similar Listings Order', 'wprentals-core' ),
			'subtitle' => __( 'Select Similar Listings Order', 'wprentals-core' ),
			'options'  => $wpestate_listings_sort_options_array,
		),
	
)));

if(function_exists('wprentals_redux_yelp')){
	$wprentals_redux_yelp=wprentals_redux_yelp();
}else{
	$wprentals_redux_yelp=array();
}

Redux::setSection( $opt_name, array(
	'title'      => __( 'Yelp settings', 'wprentals-core' ),
	'id'         => 'yelp_tab',
	'desc'       => __( 'Please note that Yelp is not working for all countries. See here https://www.yelp.com/factsheet the list of countries where Yelp is available.', 'wprentals-core' ),
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_yelp_client_id',
			'type'     => 'text',
			'title'    => __( 'Yelp Api Client ID' , 'wprentals-core'),
			'subtitle' => __( 'Get this detail after you signup here: ', 'wprentals-core' ).'<a href="https://www.yelp.com/developers/v3/manage_app" target="_blank">https://www.yelp.com/developers/v3/manage_app</a>',
		),
		array(
			'id'       => 'wp_estate_yelp_client_secret',
			'type'     => 'text',
			'title'    => __( 'Yelp Api Key' , 'wprentals-core'),
			'subtitle' => __( 'Get this detail after you signup here: ', 'wprentals-core' ).'<a href="https://www.yelp.com/developers/v3/manage_app" target="_blank">https://www.yelp.com/developers/v3/manage_app</a>',
		),
		array(
			'id'       => 'wp_estate_yelp_categories',
			'type'     => 'select',
			'multi'    =>   true,
			'title'    => __( 'Yelp Categories', 'wprentals-core' ),
			'subtitle' => __( 'Yelp Categories to show on front page', 'wprentals-core' ),
			'options'  => $wprentals_redux_yelp,
		),
		array(
			'id'       => 'wp_estate_yelp_results_no',
			'type'     => 'text',
			'title'    => __( 'Yelp - no of results', 'wprentals-core' ),
			'subtitle' => __( 'Yelp - no of results', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_yelp_dist_measure',
			'type'     => 'button_set',
			'title'    => __( 'Yelp Distance Measurement Unit', 'wprentals-core' ),
			'subtitle' => __( 'Yelp Distance Measurement Unit', 'wprentals-core' ),
			'options'  => array('miles'=>'miles','kilometers'=>'kilometers'),
			'default'  => 'miles',
		),
	),
) );


