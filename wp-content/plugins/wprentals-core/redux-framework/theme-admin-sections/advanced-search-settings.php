<?php 


// -> START Search Selection
Redux::setSection( $opt_name, array(
	'title' => __( 'Search', 'wprentals-core' ),
	'id'    => 'advanced_search_settings',
	'icon'  => 'el el-search'
) );


Redux::setSection($opt_name, array(
	'title' => __('Advanced Search Results Page', 'wprentals-core'),
	'id' => 'advanced_search_results',
	'subsection' => true,
	'fields' => array(

		array(
			'id'       => 'wp_estate_ondemandmap',
			'type'     => 'button_set',
			'title'    => __( 'Use on demand pins when moving the map, in Properties list half map and Advanced search results half map pages', 'wprentals-core' ),
			'subtitle' => __( 'See this help article before: ', 'wprentals-core' ).'<a href=" https://help.wprentals.org/article/google-maps-settings/" target="_blank"> https://help.wprentals.org/article/google-maps-settings/</a>',
			'default' => 'no',
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),

		),

		array(
			'id'       => 'wp_estate_property_list_type_adv',
			'type'     => 'button_set',
			'title'    => __( 'Page List Type for Advanced Search Results', 'wprentals-core' ),
			'subtitle' => __( 'Select standard or half map style for advanced search results page.', 'wprentals-core' ),
			'options'  => array(
				'1' =>  __( 'standard','wprentals-core'),
				'2' =>  __( 'half map','wprentals-core')
			),
			'default'  => '1'
		),
		array(
			'id' => 'wp_estate_property_list_type_adv_order',
			'type' => 'button_set',
			'title' => __('Properties default order in advanced search results page', 'wprentals-core'),
			'subtitle' => __('Select the default order for properties in advanced search results page', 'wprentals-core'),
			'options' => $listing_filter_array,
			'default' => "0",
			),

	),
));

Redux::setSection( $opt_name, array(
	'title'      => __( 'Advanced Search Display & Position', 'wprentals-core' ),
	'id'         => 'advanced_search_form_position_tab',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'wp_estate_show_adv_search_general',
			'type'     => 'button_set',
			'title'    => __( 'Show Advanced Search?', 'wprentals-core' ),
			'subtitle' => __( 'Disables or enables the display of advanced search over header media (Google Maps, Revolution Slider, theme slider or image).', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),
			'default' => 'no'
		),

		
		array(
			'id'       => 'wp_estate_show_adv_search_slider',
			'required' => array('wp_estate_show_adv_search_general', '=', 'yes'),
			'type'     => 'button_set',
			'title'    => __( 'Show Advanced Search over sliders or images?', 'wprentals-core' ),
			'subtitle' => __( 'Disables or enables the display of advanced search over header type: revolution slider, image and theme slider.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),
			'default'  => 'yes'
		),

		array(
			'id'       => 'wp_estate_sticky_search',
			'type'     => 'button_set',
			'title'    => __( 'Use sticky search ?', 'wprentals-core' ),
			'subtitle' => __( 'This will replace the sticky header. Doesn\'t apply to search type 1', 'wprentals-core' ),
			'options'  =>array(
						'yes'   => 'yes',
						'no'   => 'no'
						),
			'default' => 'no'
		),

		array(
			'id'       => 'wp_estate_use_float_search_form',
			'type'     => 'button_set',
			'title'    => __( 'Use Float Search Form?', 'wprentals-core' ),
			'subtitle' => __( 'The search form is "floating" over the media header and you set the distance between search and browser\'s margin bottom below.', 'wprentals-core' ),
			'options'  =>array(
						'yes'   => 'yes',
						'no'   => 'no'
						),
			'default'  => 'yes'
		),
		array(
			'id'       => 'wp_estate_float_form_top',
			'type'     => 'text',
			'required' =>  array('wp_estate_use_float_search_form','=','yes'),
			'title'    => __( 'Distance between search form and the browser margin bottom: Ex 200px or 20%.', 'wprentals-core' ),
			'subtitle' => __( 'Distance between search form and the browser margin bottom: Ex 200px or 20%.', 'wprentals-core' ),
			'default'  => '20%'
		),

		array(
			'id'       => 'wp_estate_float_form_top_tax',
			'type'     => 'text',
			'required' =>  array('wp_estate_use_float_search_form','=','yes'),
			'title'    => __( 'Distance between search form and the browser margin bottom in px Ex 200px or 20% - for taxonomy, category and archives pages.', 'wprentals-core' ),
			'subtitle' => __( 'Distance between search form and the browser margin bottom in px Ex 200px or 20% - for taxonomy, category and archives pages.', 'wprentals-core' ),
			'default'  => '15%'
		),

		array(
			'id'       => 'wp_estate_search_on_start',
			'required' =>  array('wp_estate_use_float_search_form','=','no'),
			'type'     => 'button_set',
			'title'    => __( 'Put Search form before the header media ?', 'wprentals-core' ),
			'subtitle' => __( 'Works with "Use FLoat Form" options set to no. Doesn\'t apply to search type 1', 'wprentals-core' ),
			'options'  =>array(
						'no'   => 'no',
						'yes'   => 'yes',
						),
			'default'  => 'no'
		),


	),
) );

if(function_exists('wprentals_redux_advanced_exteded')){
	$wprentals_redux_advanced_exteded=wprentals_redux_advanced_exteded();
}else{
	$wprentals_redux_advanced_exteded=array();
}
Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_set_search/' );



Redux::setSection( $opt_name, array(
	'title'      => __( 'Advanced Search Settings', 'wprentals-core' ),
	'id'         => 'advanced_search_settings_tab',
	'subsection' => true,
	'fields'     => array(
	
		array(
			'id'       => 'wp_estate_show_slider_min_price',
			'type'     => 'text',
			'title'    => __( 'Minimum value for Price Slider', 'wprentals-core' ),
			'subtitle' => __( 'Type only numbers!', 'wprentals-core' ),
			'default'  => '0'
		),
		array(
			'id'       => 'wp_estate_show_slider_max_price',
			'type'     => 'text',
			'title'    => __( 'Maximum value for Price Slider', 'wprentals-core' ),
			'subtitle' => __( 'Type only numbers!', 'wprentals-core' ),
			'default'  => '2500'
		),

		array(
			'id'       => 'wp_estate_show_adv_search_extended',
			'type'     => 'button_set',
			'title'    => __( 'Show Amenities and Features fields?', 'wprentals-core' ),
			'subtitle' => __( 'Displayed Only on: header search type 3, 4 and 5 & half map filters.', 'wprentals-core' ),
			'options'  =>array(
						'yes'   => 'yes',
						'no'   => 'no',
						),
			'default' => 'yes'
		),
		array(
			'id'       => 'wp_estate_advanced_exteded',
			'type'     => 'wpestate_select',
			'required' => array('wp_estate_show_adv_search_extended','=','yes'),
			'multi'    => true,
			'title'    => __( 'Amenities and Features for Advanced Search', 'wprentals-core' ),
			'subtitle' => __( 'Select which features and amenities show in search.', 'wprentals-core' ),
			'options'  => $wprentals_redux_advanced_exteded,
		),
	
		array(
			'id'       => 'wp_estate_show_dropdowns',
			'type'     => 'button_set',
			'title'    => __( 'Show Dropdowns for Guests, beds, bathrooms or rooms?', 'wprentals-core' ),
			'subtitle' => __( 'Works with SEARCH Types 3, 4, 5 and Half Map Form. Guests, Rooms, Bedrooms or Bathrooms must be added to Search Custom Fields for the option to apply.', 'wprentals-core' ),
			'options'  =>array(
						'yes'   => 'yes',
						'no'   => 'no',
						),
			'default' => 'yes'
		),

		array(
			'id' => 'wp_estate_beds_component_values',
			'type' => 'text',

			'title' => __('Possible dropdown values for beds', 'wprentals-core' ),
			'subtitle' => __('Type only numbers with/without + !', 'wprentals-core' ),
			'default' => '1,2,3,4,5,6+'
		),

		array(
			'id' => 'wp_estate_baths_component_values',
			'type' => 'text',

			'title' => __('Possible dropdown values for baths', 'wprentals-core' ),
			'subtitle' => __('Type only numbers with/without + !', 'wprentals-core' ),
			'default' => '1,2,3,4,5,6+'
		),
		
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Advanced Search Form', 'wprentals-core' ),
	'id'         => 'advanced_search_form_tab',
	'subsection' => true,
	'fields'     => array(
		 array(
			'id'       => 'wp_estate_adv_search_type',
			'type'     => 'button_set',
			'title'    => __( 'Select search type.', 'wprentals-core' ),
			'subtitle' => __( 'Type 1 - vertical design - hardcoded search type
							   </br>Type 2 - horizontal design - hardcoded search type
							   </br>Type 3, 4, 5 - work with search custom fields only.', 'wprentals-core' ),
			'options'  =>array(
						'newtype'   => esc_html__( 'Type 1','wprentals-core'),
						'oldtype'   => esc_html__( 'Type 2','wprentals-core'),
						'type3'     => esc_html__( 'Type 3','wprentals-core'),
						'type4'     => esc_html__( 'Type 4','wprentals-core'),
						'type5'     => esc_html__( 'Type 5','wprentals-core')
						),
			'default' => 'newtype'
		),

		array(
			'id'       => 'wp_estate_adv_search_label_for_form',
			'type'     => 'text',
			'title'    => __( 'Advanced Search Label for type 3', 'wprentals-core' ),
			'subtitle' => __( 'Advanced Search Label for type 3', 'wprentals-core' ),
		),


		array(
			'id'       => 'wp_estate_adv_search_fields_no',
			'type'     => 'text',
			'title'    => __( 'No of Search fields', 'wprentals-core' ),
			'subtitle' => __( 'No of Search fields for type 3, 4 and 5.', 'wprentals-core' ),
			'default'  => '3'
		),
		array(
			'id'       => 'wp_estate_search_fields_no_per_row',
			'type'     => 'text',
			'title'    => __( 'No of Search fields per row', 'wprentals-core' ),
			'subtitle' => __( 'No of Search fields per row (Possible values: 2,3,4). Only for types 3, 4, 5', 'wprentals-core' ),
			'default'  => '3'
		),
		array(
		   'id'       => 'wpestate_set_search',
		   'type'     => 'wpestate_set_search',
		   'title'    => __( 'Type 3, Type 4 and Type 5 custom search fields setup', 'wprentals-core' ),
		   'subtitle' => __( '*Do not duplicate fields and make sure search fields do not contradict themselves.
						</br>*<strong>Greater, Smaller and Equal</strong> must be used only for numeric fields.
						</br>*<strong>Like</strong> MUST be used for all text fields (including dropdowns)
						</br>*<strong>Date Greater / Date Smaller</strong> can be used for all date format fields.
						</br>*Labels will not apply for taxonomy dropdowns fields. These sync with the names added in Listing Submit Settings</br>', 'wprentals-core' ),
		   'full_width' => true,
		),

	),
) );


Redux::setSection( $opt_name, array(
	'title'      => __( 'Half Map Search Form', 'wprentals-core' ),
	'id'         => 'advanced_search_half_map_form_tab',
	'subsection' => true,
	'fields'     => array(

		 array(
			'id'       => 'wp_estate_adv_search_fields_no_half_map',
			'type'     => 'text',
			'title'    => __( 'No of Search fields', 'wprentals-core' ),
			'subtitle' => __( 'Total number of search fields.', 'wprentals-core' ),
			'default'  => '3'
		),
		array(
			'id'       => 'wp_estate_search_fields_no_per_row_half_map',
			'type'     => 'text',
			'title'    => __( 'No of Search fields per row', 'wprentals-core' ),
			'subtitle' => __( 'No of Search fields per row (Possible values: 2,3,4)', 'wprentals-core' ),
			'default'  => '3'
		),
		array(
		   'id'       => 'wpestate_set_search_half_map',
		   'type'     => 'wpestate_set_search',
		   'title'    => __( 'Half Map custom search fields setup', 'wprentals-core' ),
		   'subtitle' => __( '*Do not duplicate fields and make sure search fields do not contradict themselves.
						</br>*<strong>Greater, Smaller and Equal</strong> must be used only for numeric fields.
						</br>*<strong>Like</strong> MUST be used for all text fields (including dropdowns)
						</br>*<strong>Date Greater / Date Smaller</strong> can be used for all date format fields.
						</br>*Labels will not apply for taxonomy dropdowns fields. These sync with the names added in Listing Submit Settings</br>', 'wprentals-core' ),
		   'full_width' => true,
		),



	),
));

Redux::setSection($opt_name, array(
		'title' => __('Location Field Search Settings', 'wprentals-core' ),
		'id' => 'location_multi_select_items',
		'subsection' => true,
		'fields' => array(
	
		array(
			'id'       => 'wp_estate_show_city_drop_submit',
			'type'     => 'button_set',
			'title'    => __( 'Use Dropdowns for Cities and Areas in Submission Form?', 'wprentals-core' ),
			'subtitle' => __( 'Enable dropdowns for cities and areas populated with database items.', 'wprentals-core' ),
			'desc'     => __( 'This option is ignored if "Use Google Places or Open Street Places for Location Field Search?" is set to YES because the Location you type in Submission must match Location found in search.', 'wprentals-core' ),				
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no',
					),
			'default'  => 'no',
		),
	
	
		array(
			'id'       => 'wp_estate_wpestate_autocomplete',
			'type'     => 'button_set',
			'title'    => __( 'Use Google Places or Open Street Places for Location Field in Search?', 'wprentals-core' ),
			'subtitle' => __( 'Enable location autocomplete with Google Places or Open Street Places. 
If disabled, autocomplete will use data from saved properties.', 'wprentals-core' ),
			'desc'     => __( 'Saved data is refreshed once per day for speed optimization. 
You can manually refresh it via Map -> Generate Data & Pins.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),
			'default' => 'no'
		),
	
		array(
			'id'       => 'wp_estate_wpestate_autocomplete_use_list',
			'required' => array('wp_estate_wpestate_autocomplete', '=', 'no'),
			'type'     => 'button_set',
			'title'    => __( 'Use Dropdowns for data saved from properties in Location Search Field?', 'wprentals-core' ),
			'subtitle' => __( 'Saved data is refreshed once per day for speed optimization. 
You can manually refresh it via Map -> Generate Data & Pins.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),
			'default' => 'no'
		),

		array(
			'id' => 'wp_estate_use_geo_location_limit_country',
			'type' => 'button_set',
			'title' => __('Limit Use Google Places or Open Street Places to a specific country?', 'wprentals-core'),
			'subtitle' => __('If YES, the location field with Places enabled & Geo location search will be limited to a specific country', 'wprentals-core'),
			'default' => 'no',
			'options' => array(
				'yes' => 'yes',
				'no' => 'no'
			),
		),
		array(
			'id' => 'wp_estate_use_geo_location_limit_country_selected',
			'type' => 'select',
			'required' => array('wp_estate_use_geo_location_limit_country', '=', 'yes'),
			'title' => __('Select the country', 'wprentals-core'),
			'subtitle' => __('If YES, the geo location search will be limited to a specific country', 'wprentals-core'),
			'options' => wpestate_country_list_code(),
			'default' => ''
		),

	
		array(
			'id'       => 'wp_estate_show_empty_city',
			'type'     => 'button_set',
			'title'    => __( 'Show Cities and Areas with 0 listings in dropdowns?', 'wprentals-core' ),
			'subtitle' => __( 'Enable or disable empty city or area categories in dropdowns', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no'  => 'no'
				),
			'default' => 'no'
		),
	
		),
) );
	
Redux::setSection($opt_name, array(
'title' => __('Categories Multi Selection', 'wprentals-core' ),
'id' => 'advanced_search_multi_select_items',
'subsection' => true,
'fields' => array(
		array(
		'id' => 'wp_estate_categ_select_list_multiple',
		'type' => 'button_set',
		'title' => __('Multiselect for the Category taxonomy', 'wprentals-core' ),
		'subtitle' => __('If yes, users can select multiple items in the Category dropdown in Advanced Search.', 'wprentals-core' ),
		'options' => array(
			'yes' => 'yes',
			'no' => 'no'
		),
		'default' => 'no',
	),
	array(
		'id' => 'wp_estate_action_select_list_multiple',
		'type' => 'button_set',
		'title' => __('Multiselect for the Type taxonomy', 'wprentals-core' ),
		'subtitle' => __('If yes, users can select multiple items in the Type dropdown in Advanced Search.', 'wprentals-core' ),
		'options' => array(
			'yes' => 'yes',
			'no' => 'no'
		),
		'default' => 'no',
	),
	array(
		'id' => 'wp_estate_city_select_list_multiple',
		'type' => 'button_set',
		'title' => __('Multiselect for the City taxonomy', 'wprentals-core' ),
		'subtitle' => __('If yes, users cand select multiple items in the City dropdown in Advanced Search.', 'wprentals-core' ),
		'options' => array(
			'yes' => 'yes',
			'no' => 'no'
		),
		'default' => 'no',
	),
	array(
		'id' => 'wp_estate_area_select_list_multiple',
		'type' => 'button_set',
		'title' => __('Multiselect for the Area taxonomy', 'wprentals-core' ),
		'subtitle' => __('If yes, users can select multiple items in the Area dropdown in Advanced Search.', 'wprentals-core' ),
		'options' => array(
			'yes' => 'yes',
			'no' => 'no'
		),
		'default' => 'no',
	),
	
	array(
		'id' => 'wp_estate_select_list_multiple_show_search',
		'type' => 'button_set',
		'title' => __('Show Search in the multiselect component', 'wprentals-core' ),
		'subtitle' => __('Show or hide the search in the multiselect component', 'wprentals-core' ),
		'options' => array(
			'yes' => 'yes',
			'no' => 'no'
		),
		'default' => 'yes',
	),
	
),
));

Redux::setSection( $opt_name, array(
	'title'      => __( 'Geo Location Search', 'wprentals-core' ),
	'id'         => 'geo_location_search_tab',
	'subsection' => true,
	'desc' => __( 'Geo Location search finds a specific location on the map and returns results based on saved listing coordinates (latitude and longitude). It differs from the Location Search field.', 'wprentals-core' ),
	'fields'     => array(
	   array(
			'id'       => 'wp_estate_use_geo_location',
			'type'     => 'button_set',
			'title'    => __( 'Show the Geo Location Search field in Half Map', 'wprentals-core'),
			'subtitle' => __( 'If YES, the Geo Location search show in half map properties list and half map advanced search results, above the search fields.', 'wprentals-core' ),
			'default'  => 'no',
			'options'  =>array(
						'yes'   => 'yes',
						'no'   => 'no'
						),
		),
		array(
			'id'       => 'wp_estate_geo_radius_measure',
			'type'     => 'button_set',
			'title'    => __( 'Show Geo Location Radius in:', 'wprentals-core' ),
			'subtitle' => __( 'Select between miles and kilometers.', 'wprentals-core' ),
			'default'  => 'miles',
			'options'  =>array (
					   'miles' =>  esc_html__('miles','wprentals-core'),
					   'km'    =>  esc_html__('km','wprentals-core')
						),
		),
		array(
			'id'       => 'wp_estate_initial_radius',
			'type'     => 'text',
			'title'    => __( 'Initial area radius', 'wprentals-core' ),
			'subtitle' => __( 'Initial area radius. Use only numbers.', 'wprentals-core' ),
			'default' => '3'
		),
		array(
			'id'       => 'wp_estate_min_geo_radius',
			'type'     => 'text',
			'title'    => __( 'Minimum radius value', 'wprentals-core' ),
			'subtitle' => __( 'Minimum radius value. Use only numbers.', 'wprentals-core' ),
			'default' => '1'
		),
		array(
			'id'       => 'wp_estate_max_geo_radius',
			'type'     => 'text',
			'title'    => __( 'Maximum radius value', 'wprentals-core' ),
			'subtitle' => __( 'Maximum radius value. Use only numbers.', 'wprentals-core' ),
			'default' => '10'
		),
	),
) );


 Redux::setSection( $opt_name, array(
	'title'      => __( 'Advanced Search Colors', 'wprentals-core' ),
	'id'         => 'advanced_search_colors_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_adv_back_color',
			'type'     => 'color',
			'title'    => __( 'Advanced Search Background Color', 'wprentals-core' ),
			'subtitle' => __( 'Advanced Search Background Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_adv_back_color_opacity',
			'type'     => 'text',
			'title'    => __( 'Advanced Search Background color Opacity', 'wprentals-core' ),
			'subtitle' => __( 'Values between 0 -invisible and 1 - fully visible. Applies only when search form position "Use Float Search Form?" - is YES.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_adv_search_back_button',
			'type'     => 'color',
			'title'    => __( 'Advanced Search Button Background Color', 'wprentals-core' ),
			'subtitle' => __( 'Advanced Search Button Background Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_adv_search_back_hover_button',
			'type'     => 'color',
			'title'    => __( 'Advanced Search Button Hover Background Color', 'wprentals-core' ),
			'subtitle' => __( 'Advanced Search Button Hover Background Color', 'wprentals-core' ),
			'transparent'  => false,
		),
	),
) );
