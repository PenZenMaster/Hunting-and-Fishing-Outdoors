<?php 


// -> START Design Selection
Redux::setSection( $opt_name, array(
	'title' => __( 'Design', 'wprentals-core' ),
	'id'    => 'design_settings_sidebar',
	'icon'  => 'el el-brush'
) );
Redux::setSection( $opt_name, array(
	'title'      => __( 'General Design Settings', 'wprentals-core' ),
	'id'         => 'general_design_settings_tab',
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'wp_estate_wide_status',
			'type'     => 'button_set',
			'title'    => __( 'Wide or Boxed?', 'wprentals-core' ),
			'subtitle' => __( 'Choose the theme layout: wide or boxed.', 'wprentals-core' ),
			'options'  => array(
				'1' =>  __( 'wide','wprentals-core'),
				'2' =>  __( 'boxed','wprentals-core')
			),
			'default'  => '1'
		),
		 array(
		'id' => 'wp_estate_main_grid_content_width',
		'type' => 'text',
		'title' => __('Main Grid Width in px', 'wprentals-core'),
		'subtitle' => __('This option defines the main content width. Default value is 1200px', 'wprentals-core'),
		),
		array(
			'id' => 'wp_estate_main_content_width',
			'type' => 'text',
			'title' => __('Content Width (In Percent)', 'wprentals-core'),
			'subtitle' => __('Using this option you can define the width of the content in percent.Sidebar will occupy the rest of the main content space.', 'wprentals-core'),
		),
		array(
			'id'       => 'wp_estate_elements_border_radius',
			'type'     => 'text',
			'title'    => __( 'Border Corner Radius', 'wprentals-core' ),
			'subtitle' => __( 'Border Corner Radius for unit elements like listing unit or blog unit. Only numbers', 'wprentals-core' ),
		),
		
	

	),
) );




Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_custom_field_type3/' );



Redux::setSection( $opt_name, array(
	'title'      => __( 'Custom Colors Settings', 'wprentals-core' ),
	'id'         => 'custom_colors_tab',
	'desc'       => __( '***Please understand that we cannot add here color controls for all theme elements & details. Doing that will result in a overcrowded and useless interface. These small details need to be addressed via custom css code', 'wprentals-core' ),
	'subsection' => true,
	'fields'     => array(

		array(
			'id'       => 'wp_estate_on_child_theme',
			'type'     => 'checkbox',
			'title'    => __( 'On save, give me the css code to save in child theme style.css ?', 'wprentals-core' ),
			'subtitle' => __( '*Recommended option', 'wprentals-core' ),
			'desc'     => __( 'If you use this option, you will need to copy / paste the code below and use it in child theme style.css. The colors will NOT change otherwise!', 'wprentals-core' ),
			'default'  => '1'// 1 = on | 0 = off
		),
		 array(
			'id'       => 'wp_estate_on_child_theme_customcss',
			'type'     => 'callback',
			'required'     => array('wp_estate_on_child_theme','=','1'),
			'title'    => __( 'Css Code for Child Theme', 'redux-framework-demo' ),
			'subtitle' =>    __('Copy the above code and add it into your child theme style.css','wprentals-core'),
			'callback' => 'wp_estate_redux_on_child_theme_customcss',
			'class'    => 'wp_estate_redux_on_child_theme_customcss'
		),

		array(
			'id'       => 'wp_estate_main_color',
			'type'     => 'color',
			'title'    => __( 'Main Color', 'wprentals-core' ),
			'subtitle' => __( 'Main Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_background_color',
			'type'     => 'color',
			'title'    => __( 'Background Color', 'wprentals-core' ),
			'subtitle' => __( 'Background Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_breadcrumbs_font_color',
			'type'     => 'color',
			'title'    => __( 'Breadcrumbs, Meta and Listing Info Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Breadcrumbs, Meta and Listing Info Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_font_color',
			'type'     => 'color',
			'title'    => __( 'Font Color', 'wprentals-core' ),
			'subtitle' => __( 'Font Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_link_color',
			'type'     => 'color',
			'title'    => __( 'Link Color', 'wprentals-core' ),
			'subtitle' => __( 'Link Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_headings_color',
			'type'     => 'color',
			'title'    => __( 'Headings Color', 'wprentals-core' ),
			'subtitle' => __( 'Headings Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		
		array(
			'id'       => 'wp_estate_hover_button_color',
			'type'     => 'color',
			'title'    => __( 'Hover Button Color', 'wprentals-core' ),
			'subtitle' => __( 'Hover Button Color', 'wprentals-core' ),
			'transparent'  => false,
		),

	),
	) );

	Redux::setSection( $opt_name, array(
		'title'      => __( 'Sidebar Widget Colors', 'wprentals-core' ),
		'id'         => 'sidebar_colors_tab',
		'subsection' => true,
		'fields'     => array(

		array(
			'id'       => 'wp_estate_sidebar_widget_color',
			'type'     => 'color',
			'title'    => __( 'Sidebar Widget Background Color( for "boxed" widgets)', 'wprentals-core' ),
			'subtitle' => __( 'Sidebar Widget Background Color( for "boxed" widgets)', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_sidebar_heading_color',
			'type'     => 'color',
			'title'    => __( 'Sidebar Heading Color', 'wprentals-core' ),
			'subtitle' => __( 'Sidebar Heading Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_sidebar2_font_color',
			'type'     => 'color',
			'title'    => __( 'Sidebar Font color', 'wprentals-core' ),
			'subtitle' => __( 'Sidebar Font color', 'wprentals-core' ),
			'transparent'  => false,
		),
	),
	) );	
	
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Property, Blog and Owner Card Unit Colors', 'wprentals-core' ),
		'id'         => 'card_colors_tab',
		'subsection' => true,
		'fields'     => array(

		array(
			'id'       => 'wp_estate_box_content_back_color',
			'type'     => 'color',
			'title'    => __( 'Boxed Content Background Color', 'wprentals-core' ),
			'subtitle' => __( 'Boxed Content Background Color', 'wprentals-core' ),
			'transparent'  => false,
		),
		array(
			'id'       => 'wp_estate_box_content_border_color',
			'type'     => 'color',
			'title'    => __( 'Border Color', 'wprentals-core' ),
			'subtitle' => __( 'Border Color', 'wprentals-core' ),
			'transparent'  => false,
		),


	),
	) );
	
	Redux::setSection( $opt_name, array(
		'title'      => __( 'Calendar Colors', 'wprentals-core' ),
		'id'         => 'calendar_colors_tab',
		'subsection' => true,
		'fields'     => array(
	
		
		array(
			'id'       => 'wp_estate_calendar_back_color',
			'type'     => 'color',
			'title'    => __( 'Calendar - Background Color for Fully Booked day', 'wprentals-core' ),
			'subtitle' => __( 'Choose the background color that will be applied to days marked as booked', 'wprentals-core' ),
			'transparent'  => false,
		),
		
		
		array(
			'id'       => 'wp_estate_calendar_font_color',
			'type'     => 'color',
			'title'    => __( 'Calendar - Font Color for Fully Booked Day', 'wprentals-core' ),
			'subtitle' => __( 'Select the text color that will appear on days that are fully booked', 'wprentals-core' ),
			'transparent'  => false,
		),
		
		
		 array(
			'id'       => 'wp_estate_calendar_internal_color',
			'type'     => 'color',
			'title'    => __( 'Calendar - External bookings background color', 'wprentals-core' ),
			'subtitle' => __( 'Choose the background color for bookings imported via iCal (external bookings)', 'wprentals-core' ),
			'transparent'  => false,
		),
	),
) );







if(function_exists('wprentals_redux_font_google')){
$wprentals_redux_font_google = wprentals_redux_font_google();
}else{
	$wprentals_redux_font_google=array();
}

Redux::setSection( $opt_name, array(
	'title'      => __( 'Fonts', 'wprentals-core' ),
	'id'         => 'custom_fonts_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_general_font',
			'type'     => 'typography',
			'title'    => __( 'Main Font', 'wprentals-core' ),
			'subtitle' => __( 'Select Main Font', 'wprentals-core' ),
			'options'  => $wprentals_redux_font_google,
			'default'  =>''
		),
		array(
			'id'       => 'wp_estate_headings_font_subset',
			'type'     => 'text',
			'title'    => __( 'Main Font subset', 'wprentals-core' ),
			'subtitle' => __( 'Select Main Font subset( like greek,cyrillic, etc..)', 'wprentals-core' ),
		),
		array(
		   'id'          => 'h1_typo',
		   'type'        => 'typography',
		   'title'       => esc_html__('H1 Font', 'wprentals-core'),

		   'google'      => true,
		   'font-family' => true,
		   'subsets'     => true,
		   'line-height'=> true,
		   'font-weight'=> true,
		   'font-backup' => false,
		   'text-align'  => false,
		   'text-transform' => false,
		   'font-style' => false,
		   'color'      => false,
		   'units'       =>'px',
		   'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
		   'all_styles'  => true
	   ),

	array(
			'id'          => 'h2_typo',
			'type'        => 'typography',
			'title'       => esc_html__('H2 Font', 'wprentals-core'),
			'google'      => true,
			'font-family' => true,
			'subsets'     => true,
			'line-height'=> true,
			'font-weight'=> true,
			'font-backup' => false,
			'text-align'  => false,
			'text-transform' => false,
			'font-style' => false,
			'color'      => false,
			'units'       =>'px',
			'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
			'all_styles'  => true
		),

	array(
			'id'          => 'h3_typo',
			'type'        => 'typography',
			'title'       => esc_html__('H3 Font', 'wprentals-core'),
			'google'      => true,
			'font-family' => true,
			'subsets'     => true,
			'line-height'=> true,
			'font-weight'=> true,
			'font-backup' => false,
			'text-align'  => false,
			'text-transform' => false,
			'font-style' => false,
			'color'      => false,
			'units'       =>'px',
			'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
			'all_styles'  => true
		),

	array(
		   'id'          => 'h4_typo',
		   'type'        => 'typography',
		   'title'       => esc_html__('H4 Font', 'wprentals-core'),
		   'google'      => true,
		   'font-family' => true,
		   'subsets'     => true,
		   'line-height'=> true,
		   'font-weight'=> true,
		   'font-backup' => false,
		   'text-align'  => false,
		   'text-transform' => false,
		   'font-style' => false,
		   'color'      => false,
		   'units'       =>'px',
		   'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
		   'all_styles'  => true
	   ),

	array(
			'id'          => 'h5_typo',
			'type'        => 'typography',
			'title'       => esc_html__('H5 Font', 'wprentals-core'),
			'google'      => true,
			'font-family' => true,
			'subsets'     => true,
			'line-height'=> true,
			'font-weight'=> true,
			'font-backup' => false,
			'text-align'  => false,
			'text-transform' => false,
			'font-style' => false,
			'color'      => false,
			'units'       =>'px',
			'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
			'all_styles'  => true
		),

	 array(
			'id'          => 'h6_typo',
			'type'        => 'typography',
			'title'       => esc_html__('H6 Font', 'wprentals-core'),
			'google'      => true,
			'font-family' => true,
			'subsets'     => true,
			'line-height'=> true,
			'font-weight'=> true,
			'font-backup' => false,
			'text-align'  => false,
			'text-transform' => false,
			'font-style' => false,
			'color'      => false,
			'units'       =>'px',
			'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
			'all_styles'  => true
		),

	 array(
			'id'          => 'paragraph_typo',
			'type'        => 'typography',
			'title'       => esc_html__('Paragraph Font', 'wprentals-core'),
			'google'      => true,
			'font-family' => true,
			'subsets'     => true,
			'line-height'=> true,
			'font-weight'=> true,
			'font-backup' => false,
			'text-align'  => false,
			'text-transform' => false,
			'font-style' => false,
			'color'      => false,
			'units'       =>'px',
			'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
			'all_styles'  => true
		),

	 array(
			'id'          => 'menu_typo',
			'type'        => 'typography',
			'title'       => esc_html__('Menu Font', 'wprentals-core'),
			'google'      => true,
			'font-family' => true,
			'subsets'     => true,
			'line-height' => true,
			'font-weight' => true,
			'font-backup' => false,
			'text-align'  => false,
			'text-transform' => false,
			'font-style' => false,
			'color'      => false,
			'units'       =>'px',
			'subtitle'    => esc_html__('Select your custom font options.', 'wprentals-core'),
			'all_styles'  => true
		),
	),
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Custom CSS', 'wprentals-core' ),
	'id'         => 'custom_css_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_custom_css',
			'type'     => 'ace_editor',
			'title'    => __( 'Custom Css', 'wprentals-core' ),
			'subtitle' => __( 'Overwrite theme css using custom css.', 'wprentals-core' ),
			'mode'     => 'css',
			'theme'    => 'monokai',
			),
	),
) );
