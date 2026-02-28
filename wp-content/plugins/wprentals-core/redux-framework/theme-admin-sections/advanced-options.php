<?php 
 Redux::setSection( $opt_name, array(
	'title' => __( 'Advanced', 'wprentals-core' ),
	'id'    => 'advanced_settings_sidebar',
	'icon'  => 'el el-cogs'
) );

 Redux::setSection( $opt_name, array(
	'title'      => __( 'Import & Export', 'wprentals-core' ),
	'id'         => 'import_export_ab',
	'subsection' => true,
	'fields'     => array(
		array(
			  'id'            => 'opt-import-export',
			  'type'          => 'import_export',
			  'title'         => 'Import & Export',
			//  'subtitle'      => '',
			  'full_width'    => false,
		  ),
	),
) );


Redux::set_extensions( $opt_name, WPESTATE_PLUGIN_PATH.'redux-framework/extensions/wpestate_image_size/' );
$default_image_size = wpestate_return_default_image_size();
$redux_image_size=array();
foreach ($default_image_size as $key=>$image_size){
$redux_image_size[]=array(
		'id' => 'wp_estate_'.$key,
		'full_width' => true,
		'type' => 'wpestate_image_size',
		'title' => $image_size['name'],
		'subtitle'=> esc_html('Orignal size:').' '.$image_size['width'].'px x '.$image_size['height'].' px. '.esc_html('The new values apply only for the images you upload after saving your change. For older images you need to use "Regenerate Thumbnails" plugin in order to recreate thumbs.','wprentals-core')
	   
);
}


Redux::setSection($opt_name, array(
'title' => __('Image settings', 'wprentals-core'),
'desc' => '<div class="wpsestate_admin_notice">' . __('VERY IMPORTANT!'
		. '</strong></br>- Make a backup of your site before modifying thumbs. You can revert the backup if you do not like how your changes influence your site design.'
		. '</br>- Your changes will influence design (thumbs height / width could look different than in default design) and speed (the bigger width and height values are, the slower the site could be)'
		. '</br>- After you change a image size you need to regenerate the thumbs for the already uploaded images (thre are free plugins for that) '
		. '</br>- Learn about WordPress thumbs and how they work before making changes -  https://developer.wordpress.org/reference/functions/add_image_size/ ', 'wprentals-core').'</div>',
'id' => 'Image_settings_tab',
'subsection' => true,
'fields' =>  $redux_image_size
));

