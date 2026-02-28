<?php

// -> START Header options
Redux::setSection( $opt_name, array(
	'title' => __( 'Header', 'wprentals-core' ),
	'id'    => 'header-settings',
	'icon'  => 'el el el-photo'
) );

Redux::setSection($opt_name, array(
	'title' => __('Header Settings', 'wprentals-core'),
	'id' => 'header_settings_tab',
	'subsection' => true,
	'fields' => array(

		array(
			'id'       => 'wp_estate_show_menu_dashboard',
			'type'     => 'button_set',
			'title'    => __( 'Display the header menu on user dashboard pages?', 'wprentals-core' ),
			'subtitle' => __( 'Enable this option to show the website\'s header menu on user dashboard pages.', 'wprentals-core' ),
			'options'  =>  array(
						'yes' => 'yes',
						'no'  => 'no'
						),
			'default'  => 'yes',
		),
		
		array(
		'id'       => 'wp_estate_wide_header',
		'type'     => 'button_set',
		'title'    => __( 'Wide Header?', 'wprentals-core' ),
		'subtitle' => __( 'Wide Header: Displays the header at full width (100%).', 'wprentals-core' ),
		'options'  => array(
					'yes' => 'yes',
					'no'  => 'no'
			),
		'default'  => 'no'
		),

		array(
			'id'       => 'wp_estate_logo_header_type',
			'type'     => 'button_set',
			'title'    => __( 'Select Header Type', 'wprentals-core' ),
			'subtitle' => __( 'Choose the header layout style you want to use.', 'wprentals-core' ),
			'options'  => array(
				'type1' =>  __( 'type1','wprentals-core'),
				'type2' =>  __( 'type2','wprentals-core')
			),
			'default' => 'type1'
		),
		array(
			'id'       => 'wp_estate_logo_header_align',
			'type'     => 'button_set',
			'title'    => __( 'Logo Position', 'wprentals-core' ),
			'subtitle' => __( 'Choose the alignment of the header logo. Center is not working for type1.', 'wprentals-core' ),
			'options'  => array(
				'left' =>  __( 'left','wprentals-core'),
				'center' =>  __( 'center','wprentals-core'),
				'right' =>  __( 'right','wprentals-core')
			),
			'default'  => 'left'
		),

		array(
			'id'       => 'wp_estate_header2_menu_position',
			'type'     => 'button_set',
			'title'    => __( 'Menu Position', 'wprentals-core' ),
			'subtitle' => __( 'Choose where the menu appears in Type 2 header.', 'wprentals-core' ),
			'options'  => array(
				'left'   => __( 'left','wprentals-core'),
				'right'  => __( 'right','wprentals-core')
			),
			'default'  => 'left',
			'required' => array( 'wp_estate_logo_header_type', '=', 'type2' )
		),

		array(
			'id'       => 'wp_estate_header_height',
			'type'     => 'text',
			'title'    => __( 'Header Height', 'wprentals-core' ),
			'subtitle' => __( 'Header Height in px', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_sticky_header_height',
			'type'     => 'text',
			'title'    => __( 'Sticky Header Height', 'wprentals-core' ),
			'subtitle' => __( 'Sticky Header Height in px', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_border_bottom_header',
			'type'     => 'text',
			'title'    => __( 'Border Bottom Header Height', 'wprentals-core' ),
			'subtitle' => __( 'Header Border Bottom Height', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_top_menu_font_size',
			'type'     => 'text',
			'title'    => __( 'Top Menu Font Size', 'wprentals-core' ),
			'subtitle' => __( 'Top Menu Font Size', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_menu_item_font_size',
			'type'     => 'text',
			'title'    => __( 'Menu Item Font Size', 'wprentals-core' ),
			'subtitle' => __( 'Menu Item Font Size', 'wprentals-core' ),
		),

	),
));



Redux::setSection( $opt_name, array(
	'title'      => __( 'Top Bar Widget Area', 'wprentals-core' ),
	'id'         => 'top_bar_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_show_top_bar_user_menu',
			'type'     => 'button_set',
			'title'    => __( 'Show top bar widget menu ?', 'wprentals-core' ),
			'subtitle' => __( 'Enable or disable top bar widget area. If enabled, see this help article to add widgets: ', 'wprentals-core' ).'<a href="https://help.wprentals.org/article/header-widgets/" target = "_blank"> https://help.wprentals.org/article/header-widgets/ </a>',
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'
				),
			'default'  => 'yes',
		),
		array(
			'id'       => 'wp_estate_show_top_bar_mobile_menu',
			'type'     => 'button_set',
			'required' => array('wp_estate_show_top_bar_user_menu','=','yes'),
			'title'    => __( 'Show top bar on mobile devices?', 'wprentals-core' ),
			'subtitle' => __( 'Enable or disable top bar on mobile devices', 'wprentals-core' ),
			'options'  => array(
				'yes' => 'yes',
				'no'  => 'no'
				),
			'default'  => 'no',
		),
	),
	) );

	Redux::setSection( $opt_name, array(
		'title'      => __( 'Mobile Menu Settings', 'wprentals-core' ),
		'id'         => 'MOBILE-MENU_tab',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'wp_estate_mobile_sticky_header',
				'type'     => 'button_set',
				'title'    => __( 'Enable Sticky mobile menu header?', 'wprentals-core' ),
				'subtitle' => __( 'It does not apply if "Show top bar on mobile devices?" is set to YES', 'wprentals-core' ),
				'options'  => array(
					'yes'  => 'yes',
					'no'   => 'no',
					),
				'default'  => 'no',
			),	
	),

	) );

	Redux::setSection( $opt_name, array(
	'title'      => __( 'Hero Media Header', 'wprentals-core' ),
	'id'         => 'hero-media_slider_tab',
	'subsection' => true,
	'fields'     => array(	

		
		array(
			'id'       => 'wp_estate_transparent_menu',
			'type'     => 'button_set',
			'title'    => __( 'Enable Transparent Menu Over Header for All Pages', 'wprentals-core' ),
			'subtitle' => __( 'Do not use this option if "Header Media" is set to None or if Map is displayed.', 'wprentals-core' ),
			'options'  =>  array(
						'yes' => 'yes',
						'no' => 'no'
						),
			'default'  => 'no'
		),
		array(
			'id'       => 'wp_estate_transparent_menu_listing',
			'type'     => 'button_set',
			'title'    => __( 'Enable Transparent Menu for Properties Page', 'wprentals-core' ),
			'subtitle' => __( 'Overrides the "Enable Transparent Menu Over Header" option.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no' => 'no'
						),
			'default'  => 'no'
		),

		array(
			'id'       => 'wp_estate_header_type',
			'type'     => 'button_set',
			'title'    => __( 'Global Hero Media Header Type', 'wprentals-core' ),
			'subtitle' => __( 'Media Header is the first section below header. Select what media header to use globally.', 'wprentals-core' ),
			'options'  => array(
						'0' => 'none',
						'1' =>'image',
						'2' =>'theme slider',
						'3' =>'revolution slider',
						'4' =>'google map'
						),
			'default' => '4'
		),

		array(
			'id'       => 'wp_estate_global_revolution_slider',
			'required'  => array('wp_estate_header_type','=','3'),
			'type'     => 'text',
			'title'    => __( 'Global Revolution Slider', 'wprentals-core' ),
			'subtitle' => __( 'If media header is set to Revolution Slider, type the slider name and save.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_global_header',
			'required'  => array('wp_estate_header_type','=','1'),
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Global Header Static Image', 'wprentals-core' ),
			'subtitle' => __( 'If media header is set to image, add the image below.', 'wprentals-core' ),
		),




		array(
			'id'       => 'wp_estate_user_header_type',
			'type'     => 'button_set',
			'title'    => __( 'Hero Media Header Type for Owners page?', 'wprentals-core' ),
			'subtitle' => __( 'Overwrites the Global Hero Media Header Type option.', 'wprentals-core' ),
			'options'  => array(
						'0' => 'none',
						'1' =>'image',
						'2' =>'theme slider',
						'3' =>'revolution slider',
						'4' =>'google map'
			),
			'default' => '0'
		),

		array(
			'id'       => 'wp_estate_global_revolution_slider_user',
			'required'  => array('wp_estate_user_header_type','=','3'),
			'type'     => 'text',
			'title'    => __( 'Global Revolution Slider', 'wprentals-core' ),
			'subtitle' => __( 'If media header is set to Revolution Slider, type the slider name and save.', 'wprentals-core' ),
		),
		array(
			'id'       => 'wp_estate_global_header_image_user',
			'required'  => array('wp_estate_user_header_type','=','1'),
			'type'     => 'media',
			'url'      => true,
			'title'    => __( 'Global Media Header -  Static Image', 'wprentals-core' ),
			'subtitle' => __( 'If media header is set to image, add the image below.', 'wprentals-core' ),
		),




		array(
			'id'       => 'wp_estate_header_type_taxonomy',
			'type'     => 'button_set',
			'title'    => __( 'Hero Media Header Type for Property Category/Taxonomy Pages', 'wprentals-core' ),
			'subtitle' => __( 'Select what media header to use globally for taxonomies/categories. Maps selection is mandatory for Half Map layout.', 'wprentals-core' ),
			'options'  => array(
				'none',
				'image',
				'theme slider',
				'revolution slider',
				'google map'
				),
			'default'  => 4,
		),

		
		array(
				'id'       => 'wp_estate_header_taxonomy_revolution_slider',
				'type'     => 'text',
				'required'  => array('wp_estate_header_type_taxonomy','=','3'),
				'title'    => __( 'Property Category/Taxonomy -  Revolution Slider', 'wprentals-core' ),
				'subtitle' => __( 'If media header is set to Revolution Slider, type the slider name and save.', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_header_taxonomy_image',
			'type'     => 'media',
			'url'      => true,
			'required'  => array('wp_estate_header_type_taxonomy','=','1'),
			'title'    => __( 'Property Category/Taxonomy Header Static Image', 'wprentals-core' ),
			'subtitle' => __( 'If media header is set to image, and no image is added we will use the taxonomy featured image', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_use_upload_tax_page',
			'type'     => 'button_set',
			'required'  => array('wp_estate_header_type_taxonomy','=','1'),
			'title'    => __( 'Use uploaded Image for City and Area taxonomy page Header?', 'wprentals-core' ),
			'subtitle' => __( 'Works with Property Category/Taxonomy set to Standard type in General -> List & Sidebar Appearance', 'wprentals-core' ),
			'options'  =>  array(
						'yes' => 'yes',
						'no' => 'no'
						),
			'default'  => 'no'
		),
	

		array(
			'id'       => 'wp_estate_header_type_blog_post',
			'type'     => 'button_set',
			'title'    => __( 'Hero Media Header Type for Blog Post?', 'wprentals-core' ),
			'subtitle' => __( 'Select what media header to use globally for all blog posts', 'wprentals-core' ),
			'options'  => array(
				'none',
				'image',
				'theme slider',
				'revolution slider',
				'google map'
				),
			'default'  => 4,
		),
		
		
		array(
				'id'       => 'wp_estate_header_single_post_revolution_slider',
				'type'     => 'text',
				'required'  => array('wp_estate_header_type_blog_post','=','3'),
				'title'    => __( 'Single Post Header -  Revolution Slider', 'wprentals-core' ),
				'subtitle' => __( 'If media header is set to Revolution Slider, type the slider name and save.', 'wprentals-core' ),
		),

		array(
			'id'       => 'wp_estate_header_single_post_image',
			'type'     => 'media',
			'url'      => true,
			'required'  => array('wp_estate_header_type_blog_post','=','1'),
			'title'    => __( 'Single Post Header Static Image', 'wprentals-core' ),
			'subtitle' => __( 'If media header is set to image, and no image is added we will use the taxonomy featured image', 'wprentals-core' ),
		),



		array(
			'id'       => 'wp_estate_paralax_header',
			'type'     => 'button_set',
			'title'    => __( 'Parallax effect for image/video header media?', 'wprentals-core' ),
			'subtitle' => __( 'Enable parallax effect for image/video media header.', 'wprentals-core' ),
			'options'  => array(
						'yes' => 'yes',
						'no' => 'no'
						),
			'default'  => 'no'
		),

	),	

	) );
	


	Redux::setSection( $opt_name, array(
	'title'      => __( 'Hero Media - Theme Slider', 'wprentals-core' ),
	'id'         => 'theme_slider_tab',
	'subsection' => true,
	'fields'     => array(


		array(
			'id'       => 'wp_estate_theme_slider',
			'type'     => 'select',
			'multi'    => true,
			'title'    => __( 'Select Properties ', 'wprentals-core' ),
			'subtitle' => __( 'Select properties for header theme listing slider. For speed reason we show only the first 50 listings. If you want to add other listings, use the field below to select properties by ID. ', 'wprentals-core' ),
			'data'  => 'posts',
						'args'  => array(
							'post_type'         =>  'estate_property',
							 'post_status'       =>  'publish',
							 'posts_per_page'    =>  50,

						),
			// 'options'  => wprentals_return_theme_slider_list(),
		),

		array(
			'id'       => 'wp_estate_theme_slider_manual',
			'type'     => 'text',
			'title'    => __( 'Add Properties in theme slider by ID. Place here the Listings Id separated by comma.', 'wprentals-core' ),
			'subtitle' => __( 'Place here the Listings Id separated by comma. Will Overwrite the above selection!', 'wprentals-core' ),
		),
		
		array(
			'id'       => 'wp_estate_slider_cycle',
			'type'     => 'text',
			'title'    => __( 'Number of milisecons before auto cycling an item', 'wprentals-core' ),
			'subtitle' => __( 'Number of milisecons before auto cycling an item (5000=5sec).Put 0 if you don\'t want to autoslide.', 'wprentals-core' ),
			'default'  => '5000'
		),

		array(
			'id'       => 'wp_estate_theme_slider_type',
			'type'     => 'button_set',
			'title'    => __( 'Design Type?', 'wprentals-core' ),
			'subtitle' => __( 'Select the design type.', 'wprentals-core' ),
			'options'  => array(
						 'type1' => 'type1',
						 'type2' => 'type2'
				),
			'default'  => 'type1',
		),
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Header Menu Colors', 'wprentals-core' ),
	'id'         => 'header-menu_elements_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_header_color',
			'type'     => 'color',
			'title'    => __( 'Header Background Color', 'wprentals-core' ),
			'subtitle' => __( 'Header Background Color', 'wprentals-core' ),
			'transparent'  => false,
		),

		array(
			'id'       => 'wp_estate_border_bottom_header_color',
			'type'     => 'color',
			'title'    => __( 'Header Border Bottom Color', 'wprentals-core' ),
			'subtitle' => __( 'Header Border Bottom Color', 'wprentals-core' ),
			'transparent'  => false,
		),

		array(
			'id'       => 'wp_estate_menu_font_color',
			'type'     => 'color',
			'title'    => __( 'Top Menu Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Top Menu Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_top_menu_hover_font_color',
			'type'     => 'color',
			'title'    => __( 'Top Menu Hover Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Top Menu Hover Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_top_menu_hover_back_font_color',
			'type'     => 'color',
			'transparent'  => false,
			'title'    => __( 'Top Menu Hover Background Color', 'wprentals-core' ),
			'subtitle' => __( 'Top Menu Hover Background Color (*applies on some hover types)', 'wprentals-core' ),
		),
		array(
			'id'     => 'opt-info_hover',
			'type'   => 'info',
			'notice' => false,
			'desc'   => __( ' <img  style="border:1px solid #FFE7E7;margin-bottom:10px;" src="'. get_template_directory_uri().'/img/menu_types.png" alt="logo"/>', 'wprentals-core' )
		),
		array(
			'id'       => 'wp_estate_top_menu_hover_type',
			'type'     => 'button_set',
			'title'    => __( 'Top Menu Hover Type', 'wprentals-core' ),
			'subtitle' => __( 'Top Menu Hover Type', 'wprentals-core' ),
			'options'  =>   array(
						'1'=>'1',
						'2'=>'2',
						'3'=>'3',
						'4'=>'4',
						'5'=>'5',
						'6'=>'6'),
			'default'  => '1',
		),
		array(
			'id'       => 'wp_estate_active_menu_font_color',
			'type'     => 'color',
			'title'    => __( 'Active Menu Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Active Menu Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_transparent_menu_font_color',
			'type'     => 'color',
			'title'    => __( 'Transparent Header - Top Menu Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Transparent Header - Top Menu Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_transparent_menu_hover_font_color',
			'type'     => 'color',
			'title'    => __( 'Transparent Header - Top Menu Hover Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Transparent Header - Top Menu Hover Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_sticky_menu_font_color',
			'type'     => 'color',
			'title'    => __( 'Sticky Menu Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Sticky Menu Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_menu_items_color',
			'type'     => 'color',
			'title'    => __( 'Menu Item Color', 'wprentals-core' ),
			'subtitle' => __( 'Menu Item Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_menu_item_back_color',
			'type'     => 'color',
			'title'    => __( 'Menu Item Back Color', 'wprentals-core' ),
			'subtitle' => __( 'Menu Item Back Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_menu_hover_font_color',
			'type'     => 'color',
			'title'    => __( 'Menu Item hover font color', 'wprentals-core' ),
			'subtitle' => __( 'Menu Item hover font color', 'wprentals-core' ),
			'transparent'  => false,
		),

		
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Top Bar Colors', 'wprentals-core' ),
	'id'         => 'top-bar-design_elements_tab',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'wp_estate_top_bar_back',
			'type'     => 'color',
			'title'    => __( 'Top Bar Background Color (Header Widget Menu)', 'wprentals-core' ),
			'subtitle' => __( 'Top Bar Background Color (Header Widget Menu)', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_top_bar_font',
			'type'     => 'color',
			'title'    => __( 'Top Bar Font Color (Header Widget Menu)', 'wprentals-core' ),
			'subtitle' => __( 'Top Bar Font Color (Header Widget Menu)', 'wprentals-core' ),
			'transparent'  => false,
		),	
	),
	) );	

Redux::setSection( $opt_name, array(
'title'      => __( 'Mobile Menu Colors', 'wprentals-core' ),
'id'         => 'mobile_design_elements_tab',
'subsection' => true,
'fields'     => array(
		array(
			'id'       => 'wp_estate_mobile_header_background_color',
			'type'     => 'color',
			'title'    => __( 'Mobile header background color', 'wprentals-core' ),
			'subtitle' => __( 'Mobile header background color', 'wprentals-core' ),
			'transparent' => false,
		),

		array(
			'id'       => 'wp_estate_mobile_header_icon_color',
			'type'     => 'color',
			'title'    => __( 'Mobile header icon color', 'wprentals-core' ),
			'subtitle' => __( 'Mobile header icon color', 'wprentals-core' ),
			'transparent' => false,
		),

		array(
			'id'       => 'wp_estate_mobile_menu_font_color',
			'type'     => 'color',
			'title'    => __( 'Mobile menu font color', 'wprentals-core' ),
			'subtitle' => __( 'Mobile menu font color', 'wprentals-core' ),
			'transparent' => false,
		),

		array(
			'id'       => 'wp_estate_mobile_menu_hover_font_color',
			'type'     => 'color',
			'title'    => __( 'Mobile menu hover font color', 'wprentals-core' ),
			'subtitle' => __( 'Mobile menu hover font color', 'wprentals-core' ),
			'transparent' => false,
		),

		array(
			'id'       => 'wp_estate_mobile_item_hover_back_color',
			'type'     => 'color',
			'title'    => __( 'Mobile menu item hover background color', 'wprentals-core' ),
			'subtitle' => __( 'Mobile menu item hover background color', 'wprentals-core' ),
			'transparent' => false,
		),

		array(
			'id'       => 'wp_estate_mobile_menu_backgound_color',
			'type'     => 'color',
			'title'    => __( 'Mobile menu background color', 'wprentals-core' ),
			'subtitle' => __( 'Mobile menu background color', 'wprentals-core' ),
			'transparent' => false,
		),

		array(
			'id'       => 'wp_estate_mobile_menu_border_color',
			'type'     => 'color',
			'title'    => __( 'Mobile menu item border color', 'wprentals-core' ),
			'subtitle' => __( 'Mobile menu item border color', 'wprentals-core' ),
			'transparent' => false,
		),

	   ),
) );

