<?php
// File: admin-sections/wprentals-api.php



$custom_fields =   Redux::get_option($opt_name, 'wpestate_custom_fields_list');


$custom_fields_string='';
$i=0;
if (!empty($custom_fields)) {
	while ($i< count($custom_fields['add_field_name'])) {
		$name =   $custom_fields['add_field_name'][$i];
		$label=   $custom_fields['add_field_label'][$i];

		//    $slug =   sanitize_key ( str_replace(' ','_',$name) );
		if (function_exists('wpestate_limit45')) {
			$slug = wpestate_limit45(sanitize_title($name));
		} else {
			$slug = sanitize_title($name);
		}
		$slug         =   sanitize_key($slug);

		
		$label = stripslashes($label);

		if ($label!='' && $slug!='') {
			$custom_fields_string.= esc_html__('For Custom Field with label: ','wprentals-core').$label.esc_html__(' use the following slug in API: ','wprentals-core').$slug.'</br>';
		}
		$i++;
	}
}
if(!$custom_fields_string){
    $custom_fields_string = esc_html__('No custom fields defined yet. Please go to General Settings and add custom fields.','wprentals-core');
} 

Redux::setSection($opt_name, array(
    'title'      => __('WpRentals API', 'wprentals-core'),
    'id'         => 'developers_custom_tab',
    'subsection' => false,
    'fields'     => array(
        array(
			'id'       => 'wp_estate_display_cache_metabox',
			'type'     => 'button_set',
			'title'    => __( 'Display Cached data as metabox? ', 'wprentals-core' ),
			'subtitle' => __( 'The option "Yes" will display cached data for a estate_property post type in metabox format.', 'wprentals-core' ),
			'options'  => array( 
						'no'  => 'no',
						'yes' => 'yes'
						),
			'default'  => 'no',
        ),
        array(
			'id'       => 'wp_estate_enable_api',
			'type'     => 'button_set',
			'title'    => __( 'Enable WpRentals Theme API', 'wprentals-core' ),
			'subtitle' => __( 'The option "Yes" will enable the wprentals api. Please note you will need to install the "JWT Authentication for WP-API" plugin.', 'wprentals-core' ),
			'options'  => array(
						'no'  => 'no',
						'yes' => 'yes'
						),
			'default'  => 'no',
		),
		array(
            'id'     => 'jwt-info-normal-api-link',
            'type'   => 'info',
            'required' => array('wp_estate_enable_api','=','yes'),
            'notice' => false,
            'desc'   => 'API Documentation: <a href="https://documenter.getpostman.com/view/229114/2sAYQfE9PK#intro" target="_blank">https://wpestate.org/api-documentation/</a>', 
		      ),
        array(
            'id'     => 'jwt-info-normal',
            'type'   => 'info',
            'required' => array('wp_estate_enable_api','=','yes'),
            'notice' => false,
            'desc'   => defined('JWT_AUTH_SECRET_KEY')
                ? 'JWT_AUTH_SECRET_KEY is defined with the value: ' . JWT_AUTH_SECRET_KEY
                : 'JWT_AUTH_SECRET_KEY is not defined in wp-config.php - The method of authentication for the API is not set up. Please follow the instructions in the help file to set up the API.',
        ),
        array(
            'id'     => 'jwt-info-slugs',
            'required' => array('wp_estate_enable_api','=','yes'),
            'type'   => 'content',
            'content' => $custom_fields_string
        ),
     
    ),
));