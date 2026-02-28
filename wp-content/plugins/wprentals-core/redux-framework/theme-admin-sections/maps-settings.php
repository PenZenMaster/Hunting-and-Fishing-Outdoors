<?php 

// -> START Map options
Redux::setSection( $opt_name, array(
    'title' => __( 'Map Configuration', 'wprentals-core' ),
    'id'    => 'map_settings_sidebar',
    'icon'  => 'el el-map-marker'
) );

    Redux::setSection( $opt_name, array(
    'title'      => __( 'Map General Settings', 'wprentals-core' ),
    'id'         => 'general_map_tab',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'swithc_info',
            'type'     => 'info',
            'title'    => __( 'VERY IMPORTANT: For already published properties, switching from Google Places to OpenStreet Places (or from Openstreet to Google Places) may require adding properties City & address again. The 2 systems can have different names for city, area and country and search by location may not work.', 'wprentals-core' ),


        ),

            array(
            'id'       => 'wp_estate_kind_of_map',
            'type'     => 'button_set',
            'title'    => __( 'What Map System do you want to use?', 'wprentals-core' ),
            'subtitle' => __( 'Select a Map System.', 'wprentals-core' ),
            'options'  => array(
                        2 => 'open street',
                        1  => 'google maps'
                        ),
            'default'  => 1,
        ),



        array(
            'id'       => 'wp_estate_kind_of_places',
            'type'     => 'button_set',
            'title'    => __( 'What Places Api do you want to use?', 'wprentals-core' ),
            'subtitle' => __( 'Google Places works only with Google Maps option activated.', 'wprentals-core' ),
            'options'  => array(
                        3 => 'open street',
                        1  => 'google places'
                        ),
            'default'  => 1,
        ),



        array(
            'id'       => 'wp_estate_api_key',
            'type'     => 'text',
            'title'    => __( 'Google Maps API KEY', 'wprentals-core' ),
            'subtitle' => __( 'The Google Maps JavaScript API v3 REQUIRES an API key to function correctly. Get an APIs Console key and post the code below. You can get it from here: https://developers.google.com/maps/documentation/javascript/tutorial#api_key', 'wprentals-core' ),
        ),
        array(
            'id'       => 'wp_estate_mapbox_api_key',
            'type'     => 'text',
            'title'    => __( 'MapBox API KEY - used for tiles (maps images) server when Open Street Maps is enabled', 'wprentals-core' ),
            'subtitle' => __( 'You can get it from here: https://www.mapbox.com/. If you leave it blank we will use the default openstreet server which can be slow', 'wprentals-core' ),
        ),
        

        array(
            'id'       => 'wp_estate_general_latitude',
            'type'     => 'text',
            'title'    => __( 'Starting Point Latitude', 'wprentals-core' ),
            'subtitle' => __( 'Applies when Maps are enabled in Header - Hero Media. Add only numbers (ex: 40.577906).', 'wprentals-core' ),
            'default'  => '40.781711'
        ),
        array(
            'id'       => 'wp_estate_general_longitude',
            'type'     => 'text',
            'title'    => __( 'Starting Point Longitude', 'wprentals-core' ),
            'subtitle' => __( 'Applies when Maps are enabled in Header - Hero Media. Add only numbers (ex: -74.155058).', 'wprentals-core' ),
            'default'  => '-73.955927'
        ),
        array(
            'id'       => 'wp_estate_default_map_zoom',
            'type'     => 'text',
            'title'    => __( 'Default Map zoom (1 to 20)', 'wprentals-core' ),
            'subtitle' => __( 'Applies when Maps are enabled in Header - Hero Media. Exceptions: advanced search results, properties list and taxonomies pages.', 'wprentals-core' ),
            'default'  => '15'
        ),
        array(
            'id'       => 'wp_estate_pin_cluster',
            'type'     => 'button_set',
            'title'    => __( 'Use Pin Cluster on the maps', 'wprentals-core' ),
            'subtitle' => __( 'If yes, it groups nearby pins in cluster.', 'wprentals-core' ),
            'options'  => array(
                        'yes' => 'yes',
                        'no'  => 'no'
                        ),
            'default'  => 'yes',
        ),
        array(
            'id'       => 'wp_estate_zoom_cluster',
            'type'     => 'text',
            'required' => array('wp_estate_pin_cluster', '=', 'yes'),
            'title'    => __( 'Maximum zoom level for Cloud Cluster to appear - Only for Google Maps', 'wprentals-core' ),
            'subtitle' => __( 'Pin cluster disappears when map zoom is less than the value set in here.', 'wprentals-core' ),
            'default'  => '10'
        ),
        array(
            'id'       => 'wp_estate_geolocation_radius',
            'type'     => 'text',
            'title'    => __( 'Geolocation Circle over map (in meters)', 'wprentals-core' ),
            'subtitle' => __( 'Controls circle radius value for user geolocation pin. Type only numbers (ex: 400).', 'wprentals-core' ),
            'default'  => '1000'
        ),
        array(
            'id'       => 'wp_estate_min_height',
            'type'     => 'text',
            'title'    => __( 'Header Height when maps appear in "closed" view', 'wprentals-core' ),
            'subtitle' => __( 'Applies when Maps are enabled in Header - Hero Media.', 'wprentals-core' ),
            'default'  => '550'
        ),
        array(
            'id'       => 'wp_estate_max_height',
            'type'     => 'text',
            'title'    => __( 'Header Height when maps appear in "open" view', 'wprentals-core' ),
            'subtitle' => __( 'Applies when Maps are enabled in Header - Hero Media.', 'wprentals-core' ),
            'default'  => '650'
        ),
        array(
            'id'       => 'wp_estate_keep_min',
            'type'     => 'button_set',
            'title'    => __( 'Always show Maps in "closed" view?', 'wprentals-core' ),
            'subtitle' => __( 'Applies when Maps are enabled in Header - Hero Media. Exception: property page.', 'wprentals-core' ),
            'options'  => array(
                        'yes' => 'yes',
                        'no'  => 'no'
                        ),
            'default'  => 'no',
        ),

    ),
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Google Maps Extra Settings', 'wprentals-core' ),
    'id'         => 'google-extra-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wp_estate_map_style',
            'type'     => 'textarea',
            'title'    => __( 'Style for Google Map. Use <strong> https://snazzymaps.com/ </strong> to create styles', 'wprentals-core' ),
            'subtitle' => __( 'Copy/paste below the custom map style code.', 'wprentals-core' ),
        ),
),
) );

Redux::setSection( $opt_name, array(
    'title'      => __( 'Maps Settings in Half Map Layout', 'wprentals-core' ),
    'id'         => 'halfmap_settings_tab',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wp_estate_align_style_half',
            'type'     => 'button_set',
            'title'    => __( 'Half Map layout - Select Map Position', 'wprentals-core' ),
            'subtitle' => __( 'Choose the map position for all pages using the half map layout, including the Search Results Page, Category/Taxonomy Page with Half Map, and Properties List with Half Map Template.', 'wprentals-core' ),
            'options'  =>array(
                            '1' => esc_html__( 'Map on the Left','wprentals-core'),
                            '2' => esc_html__( 'Map on the Right','wprentals-core')
                            ),
            'default'  => '1',
        ),
    ),
) );



$pin_fields=array();

$pin_fields[]=array(
            'id'       => 'wp_estate_use_price_pins',
            'type'     => 'button_set',
            'title'    => __( 'Use price Pins?', 'wprentals-core' ),
            'subtitle' => __( 'Use price Pins? (The css class for price pins is "wpestate_marker". Each pin has also a class with the name of the category or action. For example "wpestate_marker apartments sales")', 'wprentals-core' ),
            'options'  => array(
                        'yes' => 'yes',
                        'no'  => 'no'
                        ),
            'default'  => 'no',
        );

    $pin_fields[]=array(
            'id'       => 'wp_estate_use_price_pins_full_price',
            'type'     => 'button_set',
            'title'    => __( 'Use Full Price Pins?', 'wprentals-core' ),
            'subtitle' => __( 'If not we will show prices without before and after label and in this format: 5,23m or 6.83k', 'wprentals-core' ),
            'options'  =>  array(
                        'yes' => 'yes',
                        'no'  => 'no'
                        ),
            'default'  => 'no',
        );


$pin_fields[]=array(
            'id'       => 'wp_estate_use_single_image_pin',
            'type'     => 'button_set',
            'title'    => __( 'Use single Image Pin?', 'wprentals-core' ),
            'subtitle' => __( 'We will use 1 single pins for all markers. This option will decrease the loading time for maps.', 'wprentals-core' ),
            'options'  =>  array(
                        'yes' => 'yes',
                        'no'  => 'no'
                        ),
            'default'  => 'no',
        );

$pin_fields[]=array(
            'id'       => 'wp_estate_single_pin',
            'type'     => 'media',
            'title'    => __( 'Single Pin Marker / Contact page marker', 'wprentals-core' ),
            'subtitle' => __( 'Image size must be 44px x 50px.', 'wprentals-core' ),
        );

    $pin_fields[]=array(
            'id'       => 'wp_estate_cloud_pin',
            'type'     => 'media',
            'title'    => __( 'Cloud Marker Image', 'wprentals-core' ),
            'subtitle' => __( 'Image must be 70px x 70px', 'wprentals-core' ),
        );



if(function_exists('wprentals_add_pins_icons')){
    $pin_fields = wprentals_add_pins_icons(  $pin_fields );
}else{
    $pin_fields=array();
}
Redux::setSection( $opt_name, array(
    'title'      =>     __( 'Maps Pins Management', 'wprentals-core' ),
    'id'         =>     'pin_management_tab',
    'class'      =>     'wprentals_pin_fields',
    'desc'       =>     __( 'Add new Google Maps pins for single actions / single categories. For speed reason, you MUST add pins if you change categories and actions names.'
            . '</br>Use the "Upload" button and "Insert into Post" button from the pop up window.'
            . '</br> Pins retina version must be uploaded at the same time (same folder) as the original pin and the name of the retina file should be with_2x at the end. Help here', 'wprentals-core' ) . '<a href="https://help.wprentals.org/article/retina-pins/" target="_blank">https://help.wprentals.org/article/retina-pins/</a>',
    'subsection' => true,
    'fields'     => $pin_fields,
) );



Redux::setSection( $opt_name, array(
    'title'      => __( 'Number of Pins Settings', 'wprentals-core' ),
    'id'         => 'number-pins-settings',
    'subsection' => true,
    'fields'     => array(

        array(
            'id'       => 'wp_estate_map_max_pins',
            'type'     => 'text',
            'title'    => __( 'The maximum number of pins to show on the map.', 'wprentals-core' ),
            'subtitle' => __( 'A high number will increase the response time and server load. Use a number that works for your current hosting situation. Put -1 for all pins.', 'wprentals-core' ),
            'default'  => '25'
        ),

        array(
            'id'       => 'wp_estate_readsys',
            'type'     => 'button_set',
            'title'    => __( 'Use file reading for pins?', 'wprentals-core' ),
            'subtitle' => __( 'Use file reading for pins? (*recommended for over 200 listings. File reading is faster than mysql reading and improves page speed)', 'wprentals-core' ),
            'options'  => array(
                        'yes' => 'yes',
                        'no'  => 'no'
                        ),
            'default'  => 'no',
        ),

),
) );



Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_generate_pins/' );
Redux::setSection( $opt_name, array(
    'title'      => __( 'Generate Data & Pins', 'wprentals-core' ),
    'id'         => 'generare_pins_tab',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'wp_estate_generate_pins',
            'type'     => 'wpestate_generate_pins',

            'title'    => __( 'Generate Pins and Autocomplete data', 'wprentals-core' ),
            'subtitle' => __( 'Generate Pins for Google Map and Autocomplete data for Advanced Search with theme auto-complete enabled', 'wprentals-core' ),
        ),
    ),
) );




