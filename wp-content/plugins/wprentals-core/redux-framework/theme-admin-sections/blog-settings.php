<?php 

 // -> START Advanced Selection
 Redux::setSection( $opt_name, array(
	'title'      => __( 'Blog', 'wprentals-core' ),
	'id'         => '_settings_sidebar',
	'icon'  => 'el el-adjust'
) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Blog Card Settings', 'wprentals-core' ),
	'id'         => 'blog_card_tab',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wp_estate_blog_unit',
			'type'     => 'select',
			'title'    => __( 'Blog Card Design', 'wprentals-core' ),
			'subtitle' => __( 'Blog Card Unit Design', 'wprentals-core' ),
			'options'  => array(
				1 =>    'type 1 - full row',
				2 =>    'type 2',
				3 =>    'type 3'),
			'data'  =>  'sidebars',
			'default'  => 2

		),
	),
) );


