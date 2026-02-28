<?php 



// -> START help Selection
Redux::setSection( $opt_name, array(
	'title' => __( 'Help & Custom', 'wprentals-core' ),
	'id'    => 'help_custom_sidebar',
	'icon'  => 'el el-question',

) );

Redux::setSection( $opt_name, array(
	'title'      => __( 'Help & Custom', 'wprentals-core' ),
	'id'         => 'help_custom_tab',
	'subsection' => true,
	   'fields'     => array(
		array(
			'id'     => 'opt-info-normal',
			'type'   => 'info',
			'notice' => false,
			'desc'   => __( 'For support please go to ', 'wprentals-core' ).'< a href="https://support.wpestate.org/" target="_blank"> https://support.wpestate.org/ </a>'.__( 'create an account and post a ticket. The registration is simple and as soon as you post we are notified. We usually answer in the next 24h (except weekends). Please use this system and not the email. It will help us answer much faster. Thank you! ', 'wprentals-core' )
			.'</br></br>'.__( 'For custom work on this theme please go to ', 'wprentals-core' ) .'< a href="https://support.wpestate.org/" target="_blank"> https://support.wpestate.org/ </a>'.__( ', create a ticket with your request and we will offer a free quote. ', 'wprentals-core' )
			.'</br></br>'.__( 'For help files please go to ', 'wprentals-core' ) .'< a href="https://help.wprentals.org/" target="_blank"> https://help.wprentals.org/</a>'
			.'</br></br>'.__( 'Subscribe to our mailing list in order to receive news about new features and theme upgrades ', 'wprentals-core' ) .'< a href="http://eepurl.com/CP5U5" target="_blank"> Subscribe Here!</a>'
		),
		array(
			'id'       => 'wp_estate_support',
			'type'     => 'button_set',
			'title'    => __( 'WpEstate Fan', 'wprentals-core' ),
			'subtitle' => __( 'The option "Yes" places a discrete link to wpestate.org in the footer.', 'wprentals-core' ),
			'options'  => array(
						'no'  => 'no',
						'yes' => 'yes'
						),
			'default'  => 'no',
		),
	),
) );
