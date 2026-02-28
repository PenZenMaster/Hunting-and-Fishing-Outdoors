<?php


// -> START Map options
Redux::setSection( $opt_name, array(
	'title' => __( 'Footer', 'wprentals-core' ),
	'id'    => 'footer_settings_sidebar',
	'icon'  => 'el el-screen'
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Footer Elements', 'wprentals-core' ),
	'id'         => 'footer_settings_tab',
	'subsection' => true,
	'fields'     => array(
		  array(
			'id'       => 'wp_estate_show_footer_copy',
			'type'     => 'button_set',
			'title'    => __( 'Show Footer Copyright Area?', 'wprentals-core' ),
			'subtitle' => __( 'Show Footer Copyright Area?', 'wprentals-core' ),
			'options'  => array(
				'yes'  => 'yes',
				'no'   => 'no',
				),
			'default'  => 'yes',
		),
		 array(
			'id'       => 'wp_estate_copyright_message',
			'type'     => 'textarea',
			'required'  => array('wp_estate_show_footer_copy','=','yes'),
		  
			'title'		=> __( 'Copyright Message', 'wprentals-core' ),
			'subtitle' => __('Type here the copyright message that will appear in footer. Add only text.', 'wprentals-core'),
			'default'	=> 'Copyright All Rights Reserved &copy; 2019',
		),
		
		

	
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Footer Layout', 'wprentals-core' ),
	'id'         => 'footer_layout_tab',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'wp_estate_wide_footer',
			'type'     => 'button_set',
			'title'    => __( 'Wide Footer?', 'wprentals-core' ),
			'subtitle' => __( 'Makes the footer show 100% screen wide.', 'wprentals-core' ),
			'options'  => array(
					'yes'=> 'yes',
					'no' => 'no'
				),
			'default'  => 'no'
		),
		array(
			'id'       => 'wp_estate_footer_type',
			'type'     => 'button_set',
			'title'    => __( 'Footer Type', 'wprentals-core' ),
			'subtitle' => __( 'Footer Type', 'wprentals-core' ),
			'options'  => array(
				'1'  =>  __('4 equal columns','wprentals-core'),
				'2'  =>  __('3 equal columns','wprentals-core'),
				'3'  =>  __('2 equal columns','wprentals-core'),
				'4'  =>  __('100% width column','wprentals-core'),
				'5'  =>  __('3 columns: 1/2 + 1/4 + 1/4','wprentals-core'),
				'6'  =>  __('3 columns: 1/4 + 1/2 + 1/4','wprentals-core'),
				'7'  =>  __('3 columns: 1/4 + 1/4 + 1/2','wprentals-core'),
				'8'  =>  __('2 columns: 2/3 + 1/3','wprentals-core'),
				'9'  =>  __('2 columns: 1/3 + 2/3','wprentals-core'),
				),
			'default'  => '2',
		),



		array(
			'id'       => 'wp_estate_footer_background',
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Background Image for Footer', 'wprentals-core' ),
			'subtitle' => __( 'Insert background footer image below.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_repeat_footer_back',
			'type'     => 'button_set',
			'title'    => __( 'Repeat Footer background ?', 'wprentals-core' ),
			'subtitle' => __( 'Set repeat options for background footer image.', 'wprentals-core' ),
			'options'  => array(
					'repeat'   => 'repeat',
					'repeat x' => 'repeat x',
					'repeat y' => 'repeat y',
					'no repeat'=> 'no repeat'
					),
			'default'  => 'no repeat'
		),
		/*
		array(
             'id' => 'wp_estate_property_list_type_footer',
             'type' => 'button_set',
             'title' => __('Show Footer when using Half Map Layout', 'wprentals-core'),
             'subtitle' => __('Enable footer when using Half Map Layout type', 'wprentals-core'),
             'options' => array(
                 'yes' => __('yes', 'wprentals-core'),
                 'no' => __('no', 'wprentals-core')
             ),
             'default' => 'no',
         ),*/
	)
	) );

	Redux::setSection( $opt_name, array(
		'title'      => __( 'Footer Colors', 'wprentals-core' ),
		'id'         => 'footer_colors_tab',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'       => 'wp_estate_footer_back_color',
				'type'     => 'color',
				'title'    => __( 'Footer Background Color', 'wprentals-core' ),
				'subtitle' => __( 'Footer Background Color', 'wprentals-core' ),
				'transparent'  => false,
			),
			array(
				'id'       => 'wp_estate_footer_font_color',
				'type'     => 'color',
				'title'    => __( 'Footer Font Color', 'wprentals-core' ),
				'subtitle' => __( 'Footer Font Color', 'wprentals-core' ),
				'transparent'  => false,
			),
			  array(
				'id'       => 'wp_estate_widget_title_footer_font_color',
				'type'     => 'color',
				'title'    => __( 'Footer Widget Title Font Color', 'wprentals-core' ),
				'subtitle' => __( 'Footer Widget Title Font Color', 'wprentals-core' ),
				'transparent'  => false,
			),
			array(
				'id'       => 'wp_estate_footer_copy_color',
				'type'     => 'color',
				'title'    => __( 'Footer Copyright Font Color', 'wprentals-core' ),
				'subtitle' => __( 'Footer Copyright Font Color', 'wprentals-core' ),
				'transparent'  => false,
			),
			array(
				'id'       => 'wp_estate_footer_copy_back_color',
				'type'     => 'color',
				'title'    => __( 'Footer Copyright Background Color', 'wprentals-core' ),
				'subtitle' => __( 'Footer Copyright Background Color', 'wprentals-core' ),
				'transparent'  => false,
			),
	),
	) );
	