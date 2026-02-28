<?php

// -> START Design Selection
Redux::setSection( $opt_name, array(
	'title' => __( 'Listing Card Design (used in lists)', 'wprentals-core' ),
	'id'    => 'card_design_settings_sidebar',
	'icon'  => 'el el-lines'
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing Card Settings', 'wprentals-core' ),
	'id'         => 'listing_card_settings_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_prop_page_new_tab',
			'type'     => 'button_set',
			'title'    => __( 'Open title link to property page in new window?', 'wprentals-core' ),
			'subtitle' => __( 'Opens the listing page in new window when clicking on the title link.', 'wprentals-core' ),
			'options'  =>array(
						'_blank' =>'yes',
						'_self'  => 'no'
						),
			'default'  => '_self',
		),


		
		array(
			'id'       => 'wp_estate_listing_unit_style_half',
			'type'     => 'button_set',
			'title'    => __( 'Listing Unit Style for Half Map', 'wprentals-core' ),
			'subtitle' => __( 'Select Listing Unit Style for Half Map', 'wprentals-core' ),
			'options'  =>array(
							'1' => esc_html__( 'List','wprentals-core'),
							'2' => esc_html__( 'Grid','wprentals-core')
							),
			'default'  => '1',
		),
		
	),
) );
Redux::setSection( $opt_name, array(
	'title'      => __( 'Listing Card Design', 'wprentals-core' ),
	'id'         => 'listing_card_design_tab',
	'subsection' => true,
	'fields'     => array(
	   array(
			'id'       => 'wp_estate_listing_unit_type',
			'type'     => 'button_set',
			'title'    => __( 'Listing Unit Type', 'wprentals-core' ),
			'subtitle' => __( 'Select Listing Unit Type.</br>Unit type 3 works only with custom fields.', 'wprentals-core' ),
			'options'  =>array(
							'1' => __( 'Type 1','wprentals-core'),
							'2' => __( 'Type 2','wprentals-core'),
							'3' => __( 'Type 3','wprentals-core'),
							'4' => __( 'Type 4','wprentals-core')
						),
			'default'  => '2',
		),

		
		array(
			'id'       => 'wp_estate_custom_listing_fields',
			'type'     => 'wpestate_custom_field_type3',
			'full_width' => true,
			'title'    => __( 'Custom Fields for Unit Type 3', 'wprentals-core' ),
			'subtitle' => __( 'Add, edit or delete listing custom fields.', 'wprentals-core' ),

		),
),
) );


Redux::setSection( $opt_name, array(
        'title'      => __( 'Listing Card Image Settings', 'wprentals-core' ),
        'id'         => 'listing_card_image_design_tab',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'wp_estate_prop_list_slider',
                'type'     => 'button_set',
                'title'    => __( 'Use Slider in Listing Unit? (*doesn\'t apply for featured listing unit and listing shortcode list with no space between units)', 'wprentals-core' ),
                'subtitle' => __( 'Enable / Disable the image slider in listing unit (used in lists)', 'wprentals-core' ),
                'options'  =>array(
                            'yes' => 'yes',
                            'no'  => 'no'
                            ),
                'default'  => 'no',
            ),
            
                array(
                'id'       => 'wp_estate_image_no_slider',
                'type'     => 'text',
                'required'  => array('wp_estate_prop_list_slider','=','yes'),
                'title'    => __( 'Number of images in card unit slider', 'wprentals-core' ),
                'subtitle' => __( 'Set Number of images in card unit slider', 'wprentals-core' ),
                
                'default'  => '3',
            ),
            
),
) );

