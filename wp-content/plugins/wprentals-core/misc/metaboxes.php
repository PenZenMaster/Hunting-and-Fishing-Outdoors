<?php

if( !function_exists('wpestate_dropdowns_theme_admin') ):
            function wpestate_dropdowns_theme_admin_option($post_id,$array_values,$option_name,$pre=''){
                $dropdown_return    =   '';
                $option_value       =   esc_html ( get_post_meta($post_id, $option_name, true) );
                foreach($array_values as $value){
                    $dropdown_return.='<option value="'.$value.'"';
                      if ( $option_value == $value ){
                        $dropdown_return.='selected="selected"';
                    }
                    $dropdown_return.='>'.$pre.$value.'</option>';
                }

                return $dropdown_return;

            }
endif;
add_action('add_meta_boxes', 'estate_sidebar_meta');
add_action('save_post', 'estate_save_postdata', 1, 2);
add_action( 'edit_comment', 'extend_comment_edit_metafields' );

if( !function_exists('estate_sidebar_meta') ):
    function estate_sidebar_meta() {
        global $post;
        // add_meta_box('wpestate-sidebar-post',       esc_html__( 'Sidebar Settings',  'wprentals-core'), 'estate_sidebar_box', 'post');
        // add_meta_box('wpestate-sidebar-page',       esc_html__( 'Sidebar Settings',  'wprentals-core'), 'estate_sidebar_box', 'page');
        // add_meta_box('wpestate-sidebar-property',   esc_html__( 'Sidebar Settings',  'wprentals-core'), 'estate_sidebar_box', 'estate_property');
        // add_meta_box('wpestate-sidebar-agent',      esc_html__( 'Sidebar Settings',  'wprentals-core'), 'estate_sidebar_box', 'estate_agent');
        // add_meta_box('wpestate-settings-post',      esc_html__( 'Post Settings',     'wprentals-core'), 'estate_post_options_box', 'post', 'normal', 'default' );
        // add_meta_box('wpestate-settings-page',      esc_html__( 'Page Settings',     'wprentals-core'), 'estate_page_options_box', 'page', 'normal', 'default' );
        
      
        if(isset($post->ID)){
            $page_template  =   basename( get_page_template($post->ID) ) ;
            if(  $page_template == 'property_list.php' || $page_template == 'property_list_half.php' ){
                add_meta_box('wpestate-pro_list_adv',       esc_html__( 'Property List Advanced Options','wprentals-core'), 'estate_prop_advanced_function', 'page', 'normal', 'low');
            }
        }
        add_meta_box('wpestate-header',             esc_html__( 'Appearance Options','wprentals-core'), 'estate_header_function', 'page', 'normal', 'low');
        add_meta_box('wpestate-header',             esc_html__( 'Appearance Options','wprentals-core'), 'estate_header_function', 'post', 'normal', 'low');
        add_meta_box('wpestate-header',             esc_html__( 'Appearance Options','wprentals-core'), 'estate_header_function', 'estate_agent', 'normal', 'low');
        add_meta_box('wpestate-header',             esc_html__( 'Appearance Options','wprentals-core'), 'estate_header_function', 'estate_property', 'normal', 'low');
        add_meta_box('wpestate-header', esc_html__('Stars','wprentals-core'), 'estate_comment_starts', 'comment', 'normal');
    }
endif; // end   estate_sidebar_meta





///////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Header Option
///////////////////////////////////////////////////////////////////////////////////////////////////////////

// if( !function_exists('estate_header_function') ):
//     function estate_header_function(){
//         global $post;
//         $header_array   =   array(
//                                 'global',
//                                 'none',
//                                 'image',
//                                 'theme slider',
//                                 'revolution slider',
//                                 'google map',
//                                 'video header'
//                                 );

//         $header_type    =   get_post_meta ( $post->ID, 'header_type', true);
//         $header_select  =   '';

//         foreach($header_array as $key=>$value){
//            $header_select.='<option value="'.$key.'" ';
//            if($key==$header_type){
//                $header_select.=' selected="selected" ';
//            }
//            $header_select.='>'.$value.'</option>';
//         }


//         $cache_array        = array('global','no','yes');
//         $transparent_symbol    = '';
//         $transparent_status    = esc_html ( get_post_meta($post->ID, 'transparent_status', true) );

//         foreach($cache_array as $value){
//                 $transparent_symbol.='<option value="'.$value.'"';
//                 if ($transparent_status==$value){
//                         $transparent_symbol.=' selected="selected" ';
//                 }
//                 $transparent_symbol.='>'.$value.'</option>';
//         }


//         print'
//         <div class="property_prop_half">
//             <h3 class="pblankh">'.__('Use transparent header','wprentals-core').'</h3>
//             <select name="transparent_status">
//                 '.$transparent_symbol.'
//             </select>
//         </div>';

//         print '
//         <div class="property_prop_half">
//             <h3 class = "pblankh">'.__('Select header type','wprentals-core').'</h3>
//             <select id = "page_header_type" name = "header_type">
//             '.$header_select.'
//             </select>
//         </div>';



//     estate_page_map_box($post);
//     estate_page_slider_box($post);
//     estate_page_video_box($post);
//     estate_page_theme_slider($post);
//     }
// endif;

if( !function_exists('estate_header_function') ):
function estate_header_function(){
    global $post;

    $title_tab           = '';

    if ( 'post' === get_post_type( $post->ID ) ) {
        $option       = '';
        $title_values = array( 'yes', 'no' );
        $post_title   = get_post_meta( $post->ID, 'post_show_title', true );
        foreach ( $title_values as $value ) {
            $option .= '<option value="' . $value . '"';
            if ( $value == $post_title ) {
                $option .= ' selected="selected"';
            }
            $option .= '>' . $value . '</option>';
        }

        $title_tab .= '<div class="property_prop_half">'
                . '<label for="post_show_title">' . esc_html__( 'Show Title:', 'wprentals-core' ) . ' </label><br />'
                . '<select id="post_show_title" name="post_show_title">'
                . $option
                . '</select><br />'
          . '</div>';
    } elseif ( 'page' === get_post_type( $post->ID ) ) {
        ob_start();
        estate_page_options_box( $post );
        $title_tab .= ob_get_clean();
    }
    if ( 'estate_property' === get_post_type( $post->ID ) ) {

    }







    $header_array   =   array(
                            'global',
                            'none',
                            'image',
                            'theme slider',
                            'revolution slider',
                            'maps',
                            'video header'

                            );

    $header_type    =   get_post_meta ( $post->ID, 'header_type', true);
    $header_select  =   '';
    // $header_array['11']=  'virtual tour - only for properties';


    foreach($header_array as $key=>$value){
       $header_select.='<option value="'.$key.'" ';
       if($key==$header_type){
           $header_select.=' selected="selected" ';
       }
       $header_select.='>'.$value.'</option>';
    }
    ////////// search form
    $cache_array                        =   array('global','no','yes');
    $use_float_search_form_local_select =   '';
    $search_float_type                  =   get_post_meta ( $post->ID, 'use_float_search_form_local_set', true);
    foreach($cache_array as $key=>$value){
       $use_float_search_form_local_select.='<option value="'.$key.'" ';
       if($key==$search_float_type){
           $use_float_search_form_local_select.=' selected="selected" ';
       }
       $use_float_search_form_local_select.='>'.$value.'</option>';
    }


    ////////// end logo header
    $cache_array                =   array('global','no','yes');
    $header_transparent         =   get_post_meta ( $post->ID, 'transparent_status', true);
    $header_transparent_select  =   '';

    foreach($cache_array as $key=>$value){
       $header_transparent_select.='<option value="'.$value.'" ';
       if($value==$header_transparent){
           $header_transparent_select.=' selected="selected" ';
       }
       $header_transparent_select.='>'.$value.'</option>';
    }


    $topbar_transparent         =   get_post_meta ( $post->ID, 'topbar_transparent', true);
    $topbar_transparent_select  =   '';

    foreach($cache_array as $key=>$value){
       $topbar_transparent_select.='<option value="'.$value.'" ';
       if($value==$topbar_transparent){
           $topbar_transparent_select.=' selected="selected" ';
       }
       $topbar_transparent_select.='>'.$value.'</option>';
    }


    $topbar_border_transparent         =   get_post_meta ( $post->ID, 'topbar_border_transparent', true);
    $ttopbar_border_transparent_select  =   '';

    foreach($cache_array as $key=>$value){
       $ttopbar_border_transparent_select.='<option value="'.$value.'" ';
       if($value==$topbar_border_transparent){
           $ttopbar_border_transparent_select.=' selected="selected" ';
       }
       $ttopbar_border_transparent_select.='>'.$value.'</option>';
    }

    $show_filter_area_select='';
    $cache_array=array('yes','no');
    $show_filter_area  =   get_post_meta($post->ID, 'show_filter_area', true);

    foreach($cache_array as $value){
         $show_filter_area_select.='<option value="'.$value.'"';
         if ( $show_filter_area == $value ){
                 $show_filter_area_select.=' selected="selected" ';
         }
         $show_filter_area_select.='>'.$value.'</option>';
    }

    $header_tab  = '<div class="property_prop_half">
                <label for="transparent_status">' . esc_html__( 'Use transparent header', 'wprentals-core' ) . '</label>
                <select name="transparent_status">' . $header_transparent_select . '</select>
            </div>
           ';

    $cache_array_rev             = array( 'global', 'yes', 'no' );
    $page_show_adv_search_select = wpestate_dropdowns_theme_admin_option_core( $post->ID, $cache_array_rev, 'page_show_adv_search' );
    $page_use_float_search_select = wpestate_dropdowns_theme_admin_option_core( $post->ID, $cache_array_rev, 'page_use_float_search' );

    $adv_tab = '';
    // $adv_tab  = '<div class="property_prop_half">';
    // $adv_tab .= '<label for="page_show_adv_search">' . esc_html__( 'Show Advanced Search?', 'wprentals-core' ) . '</label>';
    // $adv_tab .= '<select id="page_show_adv_search" name="page_show_adv_search">' . $page_show_adv_search_select . '</select>';
    // $adv_tab .= '</div>';
    $adv_tab .= '<div class="property_prop_half">';
    $adv_tab .= '<label for="page_use_float_search">' . esc_html__( 'Use Float Search Form?', 'wprentals-core' ) . '</label>';
    $adv_tab .= '<select id="page_use_float_search" name="page_use_float_search">' . $page_use_float_search_select . '</select>';
    $adv_tab .= '</div>';
    $adv_tab .= '<div class="property_prop_half">';
    $adv_tab .= '<label for="show_filter_area">Show filter area</label>';
    $adv_tab .= '<select id="show_filter_area" name="show_filter_area">' . $show_filter_area_select . '</select>';
    $adv_tab .= '</div>';
    // $adv_tab .= '<div class="property_prop_half">';
    // $adv_tab .= '<label for="page_wp_estate_float_form_top">' . esc_html__( 'Distance between search form and the top margin. Type for ex: 90% or 90px.', 'wprentals-core' ) . '</label>';
    // $adv_tab .= '<input type="text" id="page_wp_estate_float_form_top" name="page_wp_estate_float_form_top" value="' . get_post_meta( $post->ID, 'page_wp_estate_float_form_top', true ) . '">';
    // $adv_tab .= '</div>';

    $header_media_tab  = '<h3 class="pblankh">' . esc_html__( 'Select The Hero Header Media Type', 'wprentals-core' ) . '</h3>';
    $header_media_tab .= '<div class="property_prop_half prop_full"><select id="page_header_type" name="header_type" class="header_media">' . $header_select . '</select></div>';
    ob_start();
    estate_page_map_box( $post );
    estate_page_slider_box( $post );
    estate_page_video_box( $post );
    $header_media_tab .= ob_get_clean();

    ob_start();
    estate_sidebar_box( $post );
    $sidebar_tab = ob_get_clean();

    $post_settings_tab = '';
    if ( 'post' === get_post_type( $post->ID ) ) {

        $option         = '';
        $group_pictures = get_post_meta( $post->ID, 'group_pictures', true );
        foreach ( $title_values as $value ) {
            $option .= '<option value="' . $value . '"';
            if ( $value == $group_pictures ) {
                $option .= ' selected="selected"';
            }
            $option .= '>' . $value . '</option>';
        }
        $post_settings_tab .= '<div class="property_prop_half">'
            . '<label for="group_pictures">' . esc_html__( 'Display image slider? (*only for blog posts)', 'wprentals-core' ) . ' </label>'
            . '<select id="group_pictures" name="group_pictures">'
            . $option
            . '</select>'
            . '</div>';

        $post_settings_tab .= '<div class="property_prop_half">'
            . '<label for="embed_video_id">' . esc_html__( 'Use this video Embed Video id in slider:', 'wprentals-core' ) . '</label>'
            . '<input type="text" id="embed_video_id" name="embed_video_id" value="' . esc_html( get_post_meta( $post->ID, 'embed_video_id', true ) ) . '">'
            . '</div>';

        $option_video  = '';
        $video_values  = array( 'vimeo', 'youtube' );
        $video_type    = get_post_meta( $post->ID, 'embed_video_type', true );
        foreach ( $video_values as $value ) {
            $option_video .= '<option value="' . $value . '"';
            if ( $value == $video_type ) {
                $option_video .= ' selected="selected"';
            }
            $option_video .= '>' . $value . '</option>';
        }
        $post_settings_tab .= '<div class="property_prop_half">'
            . '<label for="embed_video_type">' . esc_html__( 'Video from', 'wprentals-core' ) . '</label>'
            . '<select id="embed_video_type" name="embed_video_type">'
            . $option_video
            . '</select>'
            . '</div>';

        $slider_images_tab = '';
        ob_start();

        $already_in = '';
        print '<div class="property_uploaded_thumb_wrapepr" id="property_uploaded_thumb_wrapepr">';
        $ajax_nonce = wp_create_nonce("wpestate_attach_delete");

    

        print'<input type="hidden" id="wpestate_attach_delete" value="' . esc_html($ajax_nonce) . '" />    ';

        $arguments = array(
            'numberposts' => -1,
            'post_type' => 'attachment',
            'post_parent' => $post->ID,
            'post_status' => null, 
            'orderby' => 'menu_order',
            'post_mime_type' => 'image',
            'order' => 'ASC',
            'fields' => 'ids'
        );

        $post_attachments_new = get_posts($arguments);


        foreach ($post_attachments_new as $attachment_id) {
            $attachment = get_post($attachment_id);
    
            if ($attachment && ($attachment->post_mime_type == 'image/jpeg' ||
                    $attachment->post_mime_type == 'application/pdf' ||
                    $attachment->post_mime_type == 'image/webp' ||
                    $attachment->post_mime_type == 'image/png')) {
    
                print '<div class="uploaded_thumb" data-imageid="' . $attachment_id . '">';
    
                if ($attachment->post_mime_type == 'application/pdf') {
                    print ' <img src="' . get_theme_file_uri('/img/pdf.png') . '" alt="' . esc_html__('user document', 'wprentals-core') . '" />';
                } else {
                    $preview = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    print '<img src="' . $preview[0] . '" alt="slider" />';
                }
    
                $already_in .= $attachment_id . ',';
                print '<a target="_blank" href="' . esc_url(admin_url()) . 'post.php?post=' . $attachment_id . '&action=edit" class="attach_edit"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                <span class="attach_delete"><i class="far fa-trash-alt" aria-hidden="true"></i></span>';
    
                print '</div>';
            }
        }


        print '<input type="hidden" id="image_to_attach" name="image_to_attach" value="' . $already_in . '"/>';

        print '</div>';

        print '<button class="upload_button button" id="button_new_image" data-postid="' . $post->ID . '">' . esc_html__('Upload new image(s)', 'wprentals-core') . '</button>';
        $slider_images_tab .= ob_get_clean();
    }

    $show_title_tab = in_array( get_post_type( $post->ID ), array( 'post', 'page' ), true );

    $tabs = array();

    if ( $show_title_tab ) {
        $tabs['header_title'] = array(
            'label'   => esc_html__( 'Title Section', 'wprentals-core' ),
            'content' => $title_tab,
        );
    }

    if ( 'post' === get_post_type( $post->ID ) ) {
        $tabs['header_post'] = array(
            'label'   => esc_html__( 'Post Settings', 'wprentals-core' ),
            'content' => $post_settings_tab,
        );
        $tabs['media_post'] = array(
            'label'   => esc_html__( 'Image Slider', 'wprentals-core' ),
            'content' => $slider_images_tab,
        );
    }

    if ( $show_title_tab ) {
        $tabs['header_transparent'] = array(
            'label'   => esc_html__( 'Header', 'wprentals-core' ),
            'content' => $header_tab,
        );
    }

    // $tabs['header_search'] = array(
    //     'label'   => esc_html__( 'Advanced Search', 'wprentals-core' ),
    //     'content' => $adv_tab,
    // );

    $tabs['header_sidebar'] = array(
        'label'   => esc_html__( 'Sidebar Settings', 'wprentals-core' ),
        'content' => $sidebar_tab,
    );

    if ( $show_title_tab ) {
        $tabs['header_media_type'] = array(
            'label'   => esc_html__( 'Header Media Type', 'wprentals-core' ),
            'content' => $header_media_tab,
        );
    }

    // 'Content Options' tab is now part of the Property Details metabox.

    $available_tabs = array_keys( $tabs );
    $active_tab    = $available_tabs[0];
    if ( isset( $_GET['header_tab'] ) && in_array( $_GET['header_tab'], $available_tabs, true ) ) {
        $active_tab = sanitize_key( $_GET['header_tab'] );
    }

    echo '<div class="header_options_wrapper meta-options">';
    echo '<div class="header_options_wrapper_list">';

    foreach ( $tabs as $tab_id => $tab_data ) {
        $active = ( $active_tab === $tab_id ) ? ' active_tab' : '';
        echo '<div class="header_tab_item' . $active . '" data-content="' . esc_attr( $tab_id ) . '">' . $tab_data['label'] . '</div>';
    }

    echo '</div><div class="header_options_content_wrapper">';

    foreach ( $tabs as $tab_id => $tab_data ) {
        $active = ( $active_tab === $tab_id ) ? ' active_tab' : '';
        echo '<div class="header_tab_item_content' . $active . '" id="' . esc_attr( $tab_id ) . '">' . $tab_data['content'] . '</div>';
    }

    echo '</div></div>';
    }

endif;

///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Property Listing advanced options
///////////////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_prop_advanced_function') ):
function estate_prop_advanced_function(){
    global $post;
    
    $page_template  =   basename( get_page_template($post->ID) ) ;

    if( $page_template!= 'property_list.php' && $page_template!= 'property_list_half.php' ){
        esc_html_e('Only for "Properties List" page template ! ','wprentals-core');
        return;
    }

    $args = array(
        'hide_empty'    => false
    );

    $actions_select     =   '';
    $categ_select       =   '';
    $taxonomy           =   'property_action_category';
    $tax_terms          =   get_terms($taxonomy,$args);

    $current_adv_filter_search_action = get_post_meta ( $post->ID, 'adv_filter_search_action', true);
    if($current_adv_filter_search_action==''){
        $current_adv_filter_search_action=array();
    }


    $all_selected='';
    if(!empty($current_adv_filter_search_action) && $current_adv_filter_search_action[0]=='all'){
      $all_selected=' selected="selected" ';
    }

    $actions_select.='<option value="all" '.$all_selected.'>'.esc_html__( 'all','wprentals-core').'</option>';
    if( !empty( $tax_terms ) ){
        foreach ($tax_terms as $tax_term) {
            $actions_select.='<option value="'.$tax_term->name.'" ';
            if( in_array  ( $tax_term->name,$current_adv_filter_search_action) ){
              $actions_select.=' selected="selected" ';
            }
            $actions_select.=' />'.$tax_term->name.'</option>';
        }
    }



    //////////////////////////////////////////////////////////////////////////////////////////
    $taxonomy           =   'property_category';
    $tax_terms          =   get_terms($taxonomy,$args);

    $current_adv_filter_search_category = get_post_meta ( $post->ID, 'adv_filter_search_category', true);
    if($current_adv_filter_search_category==''){
        $current_adv_filter_search_category=array();
    }

    $all_selected='';
    if( !empty($current_adv_filter_search_category) && $current_adv_filter_search_category[0]=='all'){
      $all_selected=' selected="selected" ';
    }

    $categ_select.='<option value="all" '.$all_selected.'>'.esc_html__( 'all','wprentals-core').'</option>';
    if( !empty( $tax_terms ) ){
        foreach ($tax_terms as $tax_term) {
            $categ_select.='<option value="'.$tax_term->name.'" ';
            if( in_array  ( $tax_term->name, $current_adv_filter_search_category) ){
              $categ_select.=' selected="selected" ';
            }
            $categ_select.=' />'.$tax_term->name.'</option>';
        }
    }


 //////////////////////////////////////////////////////////////////////////////////////////

    $select_city='';
    $taxonomy = 'property_city';
    $tax_terms_city = get_terms($taxonomy,$args);
    $current_adv_filter_city = get_post_meta ( $post->ID, 'current_adv_filter_city', true);

    if($current_adv_filter_city==''){
        $current_adv_filter_city=array();
    }

    $all_selected='';
    if( !empty($current_adv_filter_city) && $current_adv_filter_city[0]=='all'){
      $all_selected=' selected="selected" ';
    }

    $select_city.='<option value="all" '.$all_selected.' >'.esc_html__( 'all','wprentals-core').'</option>';
    foreach ($tax_terms_city as $tax_term) {

        $select_city.= '<option value="' . $tax_term->name . '" ';
        if( in_array  ( $tax_term->name, $current_adv_filter_city) ){
              $select_city.=' selected="selected" ';
        }
        $select_city.= '>' . $tax_term->name . '</option>';
    }


 //////////////////////////////////////////////////////////////////////////////////////////

    $select_area='';
    $taxonomy = 'property_area';
    $tax_terms_area = get_terms($taxonomy,$args);
    $current_adv_filter_area = get_post_meta ( $post->ID, 'current_adv_filter_area', true);
    if($current_adv_filter_area==''){
        $current_adv_filter_area=array();
    }

    $all_selected='';
    if(!empty($current_adv_filter_area) && $current_adv_filter_area[0]=='all'){
      $all_selected=' selected="selected" ';
    }

    $select_area.='<option value="all" '.$all_selected.'>'.esc_html__( 'all','wprentals-core').'</option>';
    foreach ($tax_terms_area as $tax_term) {
        $term_meta=  get_option( "taxonomy_$tax_term->term_id");
        $select_area.= '<option value="' . $tax_term->name . '" ';
        if( in_array  ( $tax_term->name, $current_adv_filter_area) ){
              $select_area.=' selected="selected" ';
        }
        $select_area.= '>' . $tax_term->name . '</option>';
    }

//////////////////////////////////



    $show_filter_area_select='';
    $cache_array=array('yes','no');
    $show_filter_area  =   get_post_meta($post->ID, 'show_filter_area', true);

    foreach($cache_array as $value){
         $show_filter_area_select.='<option value="'.$value.'"';
         if ( $show_filter_area == $value ){
                 $show_filter_area_select.=' selected="selected" ';
         }
         $show_filter_area_select.='>'.$value.'</option>';
    }






    $show_featured_only_select='';
    $show_featured_only  =   get_post_meta($post->ID, 'show_featured_only', true);
    foreach($cache_array as $value){

         $show_featured_only_select.='<option value="'.$value.'" ';
         if ( $show_featured_only == $value ){
                 $show_featured_only_select.=' selected="selected" ';
         }
         $show_featured_only_select.='>'.$value.'</option>';
    }

    $listing_filter = get_post_meta($post->ID, 'listing_filter',true );

 
    $listing_filter_array=array();
    if(function_exists('wpestate_listings_sort_options_array')){
        $listing_filter_array=wpestate_listings_sort_options_array();
    }

    $filter_tab = '';
    $filter_tab .= '<div class="property_prop_half">'
        .'<label for="filter_search_action[]">Pick actions</label><br />'
        .'<select name="adv_filter_search_action[]" multiple="multiple" >'
        .$actions_select.'</select></div>';
    $filter_tab .= '<div class="property_prop_half">'
        .'<label for="adv_filter_search_category[]">Pick category</label><br />'
        .'<select name="adv_filter_search_category[]" multiple="multiple" >'
        .$categ_select.'</select></div>';
    $filter_tab .= '<div class="property_prop_half">'
        .'<label for="current_adv_filter_city[]">Pick City</label><br />'
        .'<select name="current_adv_filter_city[]" multiple="multiple" >'
        .$select_city.'</select></div>';
    $filter_tab .= '<div class="property_prop_half">'
        .'<label for="current_adv_filter_area[]">Pick Area</label><br />'
        .'<select name="current_adv_filter_area[]" multiple="multiple" >'
        .$select_area.'</select></div>';
    // $filter_tab .= '<div class="property_prop_half">'
    //     .'<label for="current_adv_filter_county[]">Pick County/State</label><br />'
    //     .'<select name="current_adv_filter_county[]" multiple="multiple" >'
    //     .$select_county.'</select></div>';

    $order_tab = '<div class="property_prop_half">'
        .'<label for="listing_filter_div">Default sort ?</label><br />'
        .'<select id="listing_filter_div" name="listing_filter" >';
            foreach($listing_filter_array as $key=>$value){
                $order_tab.='<option value="'.$key.'" ';
                if($key==$listing_filter){
                    $order_tab.=' selected="selected" ';
                }
                $order_tab.='>'.$value.'</option>';
            }
    $order_tab .='</select></div>';
    $order_tab .= '<div class="property_prop_half">'
        .'<label for="show_featured_only">Show featured only </label><br />'
        .'<select id="show_featured_only" name="show_featured_only" >'
        .$show_featured_only_select.'</select></div>';
    $order_tab .= '<div class="property_prop_half">'
        .'<label for="show_filter_area">Show filter area</label><br />'
        .'<select id="show_filter_area" name="show_filter_area">'
        .$show_filter_area_select.'</select></div>';

    // ob_start();
    // echo '<label for="property_list_second_content">Content that comes after property list.If you want to use shortcodes just create your page in the original content area and them copy paste it from text mode.</label><br />';
    // wp_editor(
    //     $property_list_second_content,
    //     'property_list_second_content',
    //     array(
    //         'textarea_rows' =>  6,
    //         'textarea_name' =>  'property_list_second_content',
    //         'wpautop'       =>  false,
    //         'media_buttons' =>  true,
    //         'tabindex'      =>  '',
    //         'editor_css'    =>  '',
    //         'editor_class'  => '',
    //         'teeny'         => false,
    //         'dfw'           => false,
    //         'tinymce'       => true,
    //         'quicktags'     => array('buttons'=>'strong,em,block,ins,ul,li,ol,close,more'),
    //     )
    // );
    // $content_editor = ob_get_clean();
    // $content_tab = '<div class="property_prop_half prop_full">'.$content_editor.'</div>';

    $tabs = array(
        'pladv_filter' => array(
            'label'   => esc_html__('Filter Listings','wprentals-core'),
            'content' => $filter_tab,
        ),
        'pladv_order' => array(
            'label'   => esc_html__('Order & Featured','wprentals-core'),
            'content' => $order_tab,
        ),
        // 'pladv_content' => array(
        //     'label'   => esc_html__('Page Content','wprentals-core'),
        //     'content' => $content_tab,
        // ),
    );

    $available_tabs = array_keys($tabs);
    $active_tab = $available_tabs[0];
    if ( isset( $_GET['prolistadv_tab'] ) && in_array( $_GET['prolistadv_tab'], $available_tabs, true ) ) {
        $active_tab = sanitize_key( $_GET['prolistadv_tab'] );
    }

    echo '<div class="pladv_options_wrapper meta-options">';
    echo '<div class="pladv_options_wrapper_list">';
    foreach ( $tabs as $tab_id => $tab_data ) {
        $active = ( $active_tab === $tab_id ) ? ' active_tab' : '';
        echo '<div class="pladv_tab_item'.$active.'" data-content="'.$tab_id.'">'.$tab_data['label'].'</div>';
    }
    echo '</div><div class="pladv_options_content_wrapper">';
    foreach ( $tabs as $tab_id => $tab_data ) {
        $active = ( $active_tab === $tab_id ) ? ' active_tab' : '';
        echo '<div class="pladv_tab_item_content'.$active.'" id="'.$tab_id.'">'.$tab_data['content'].'</div>';
    }
    echo '</div></div>';

}

endif;
///////////////////////////////////////////////////////////////////////////////////////////////////////////
///  Listing options
///////////////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('estate_listing_options') ):
    function estate_listing_options(){


        global $post;
        if ( 'property_list.php'== basename( get_page_template() )){

            $listing_action  =   get_post_meta($post->ID, 'listing_action', true);
            $listing_categ   =   get_post_meta($post->ID, 'listing_categ', true);
            $listing_city    =   get_post_meta($post->ID, 'listing_city', true);
            $listing_area    =   get_post_meta($post->ID, 'listing_area', true);

            $args = array(
            'hide_empty'    => false
            );

            $taxonomy = 'property_action_category';
            $tax_terms = get_terms($taxonomy,$args);

            $taxonomy_categ = 'property_category';
            $tax_terms_categ = get_terms($taxonomy_categ,$args);

            $actions_select     =   '';
            $categ_select       =   '';


            ///////////////////////// actions
            if( !empty( $tax_terms ) ){
                foreach ($tax_terms as $tax_term) {
                  $actions_select.='<option value="'.$tax_term->name.'" ';
                  if ($tax_term->name == $listing_action ){
                       $actions_select.=' selected="selected" ';
                  }
                  $actions_select.=' >'.$tax_term->name.'</option>';
                }
            }


            /////////////////////////categ

            if( !empty( $tax_terms_categ ) ){
                foreach ($tax_terms_categ as $categ) {
                  $categ_select.='<option value="'.$categ->name.'" ';
                   if ($categ->name == $listing_categ ){
                       $categ_select.=' selected="selected" ';
                  }
                  $categ_select.='>'.$categ->name.'</option>';
                }
            }


            ///////////////////////// city
            $select_city='';
            $taxonomy = 'property_city';
            $tax_terms = get_terms($taxonomy,$args);
            foreach ($tax_terms as $tax_term) {
               $select_city.= '<option value="' . $tax_term->name . '" ';
               if ( $tax_term->name  == $listing_city ){
                       $select_city.=' selected="selected" ';
                  }
               $select_city.='>' . $tax_term->name . '</option>';
            }

            if ($select_city==''){
                  $select_city.= '<option value="">No Cities</option>';
            }



            /////////////////////////area
            $select_area='';
            $taxonomy = 'property_area';
            $tax_terms = get_terms($taxonomy,$args);

            foreach ($tax_terms as $tax_term) {
                $term_meta=  get_option( "taxonomy_$tax_term->term_id");
                $select_area.= '<option value="' . $tax_term->name . '" data-parentcity="' . $term_meta['cityparent'] . '" ';

                 if ( $tax_term->name  == $listing_area ){
                       $select_area.=' selected="selected" ';
                  }

                $select_area.= '>' . $tax_term->name . '</option>';

             }





          print '
          <p class="meta-options">
            <label for="listing_action">'.esc_html__( 'Action category','wprentals-core').'</label><br />
              <select  name="listing_action" >
                         <option value="all">'.wpestate_category_labels_dropdowns('second').'</option>
                         '.$actions_select.'
                    </select>
          </p>


        <p class="meta-options">
        <label for="listing_categ">'.esc_html__( 'Pick Category','wprentals-core').'</label><br />
         <select name="listing_categ"  >
                        <option value="all">'.wpestate_category_labels_dropdowns('main').'</option>
                        '. $categ_select.'
                    </select>
        </p>

        <p class="meta-options">
          <label for="listing_city">'.esc_html__( 'Pick City','wprentals-core').'</label><br />
          <select  name="listing_city"  >
                <option value="all">'.esc_html__( 'All Cities','wprentals-core').'</option>
                '. $select_city.'
           </select>
        </p>

        <p class="meta-options">
            <label for="listing_area">'.esc_html__( 'Pick Area','wprentals-core').'</label><br />
            <select  name="listing_area">
                <option data-parentcity="*" value="all">'.esc_html__( 'All Areas','wprentals-core').'</option>
                '.$select_area.'
            </select>
        </p>
         ';


        }else{
            print esc_html_e('These Options are available for "Property list" page template only!','wprentals-core');
        }

    }
endif; // end   estate_listing_options





////////////////////////////////////////////////////////////////////////////////////////////////
// Manage Revolution Slider
////////////////////////////////////////////////////////////////////////////////////////////////

if( !function_exists('estate_page_slider_box') ):
function estate_page_slider_box($post) {
    global $post;
    $rev_slider           = get_post_meta($post->ID, 'rev_slider', true);
    print '
    <div class="header_admin_options revolution_slider">
        <p class="meta-options pblank">
            <h3 class="pblankh">'.esc_html__('Options for Revolution Slider (if Hero Header Media Type "revolution slider" is Selected)','wprentals-core').'</h3>
        </p>
        <div class="property_prop_half">
            <label for="page_custom_lat">'.esc_html__('Revolution Slider Name','wprentals-core').'</label><br />
            <input type="text" id="rev_slider" name="rev_slider" size="40" value="'.$rev_slider.'">
        </div>
    </div>
    ';
}
endif; // end   estate_page_slider_box


////////////////////////////////////////////////////////////////////////////////////////////////
// Manage Google Maps
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_page_map_box') ):
function estate_page_map_box($post) {
    global $post;
    $page_lat           = get_post_meta($post->ID, 'page_custom_lat', true);
    $page_long          = get_post_meta($post->ID, 'page_custom_long', true);
    $page_custom_image  = get_post_meta($post->ID, 'page_custom_image', true);
    $page_custom_zoom   = get_post_meta($post->ID, 'page_custom_zoom', true);
    $min_height         = intval( esc_html(get_post_meta($post->ID, 'min_height', true)) );
    $max_height         = intval( esc_html(get_post_meta($post->ID, 'max_height', true)) );
    $cache_array        = array('yes','no');
    $keep_min_symbol    = '';
    $keep_min_status    = esc_html ( get_post_meta($post->ID, 'keep_min', true) );

    foreach($cache_array as $value){
            $keep_min_symbol.='<option value="'.$value.'"';
            if ($keep_min_status==$value){
                    $keep_min_symbol.=' selected="selected" ';
            }
            $keep_min_symbol.='>'.$value.'</option>';
    }

    if ($page_custom_zoom==''){
        $page_custom_zoom=15;
    }
    print ' <div class="header_admin_options google_map">
        <p class="meta-options pblank">
        <h3 class="pblankh">'.esc_html__( 'Options for Google Maps (if Header Type "google map" is selected)','wprentals-core').'</h3>
        </p>';

    if( get_post_type($post->ID)!="estate_property" ){
        print '

        <p class="meta-options pblank">
        '.esc_html__( '  Leave these blank in order to get the general map settings.','wprentals-core').'
        </p>
        <div class="property_prop_half">
        <label for="page_custom_lat">'.esc_html__( 'Map - Center point  Latitudine: ','wprentals-core').'</label><br />
        <input type="text" id="page_custom_lat" name="page_custom_lat" size="36" value="'.$page_lat.'">
        </div>

        <div class="property_prop_half">
        <label for="page_custom_long">'.esc_html__( 'Map - Center point  Longitudine: ','wprentals-core').'</label><br />
        <input type="text" id="page_custom_long" name="page_custom_long" size="36" value="'.$page_long.'">
        </div>

        <div class="property_prop_half">
        <label for="page_custom_zoom">'.esc_html__( 'Zoom Level for map (1-20)','wprentals-core').'</label><br />
        <select name="page_custom_zoom" id="page_custom_zoom">';

        for ($i=1;$i<21;$i++){
            print '<option value="'.$i.'"';
            if($page_custom_zoom==$i){
                print ' selected="selected" ';
            }
            print '>'.$i.'</option>';
        }
        print'
        </select>
    </div>';
    }

    print'
    <div class="property_prop_half">
     <label for="min_height">'.esc_html__( 'Height of the map when closed','wprentals-core').'</label><br />
      <input id="min_height" type="text" size="36" name="min_height" value="'.$min_height.'" />
    </div>

    <div class="property_prop_half">
       <label for="max_height">'.esc_html__( 'Height of map when open','wprentals-core').'</label><br />
       <input id="max_height" type="text" size="36" name="max_height" value="'.$max_height.'" />
    </div>

    <div class="property_prop_half">
       <label for="keep_min">'.esc_html__( 'Force map at the "closed" size ? ','wprentals-core').'</label><br />
       <select id="keep_min" name="keep_min">
       <option value=""></option>
          '.$keep_min_symbol.'
       </select>
    </div>


    <div class="property_prop_half">
        <label class="checklabel" for="bypass_fit_bounds">'.esc_html__( 'ByPass fit bounds (auto zoom and pan of the map around visible markers) ','wprentals-core').'</label>
        <input type="hidden" value="0" name="bypass_fit_bounds" />
        <input type="checkbox" value="1" name="bypass_fit_bounds" id="bypass_fit_bounds"  ';
        if( get_post_meta($post->ID,'bypass_fit_bounds',true)==1){
            print ' checked="checked" ';
        }
    print '/>
     </div></div>';




    $cache_array        =   array('yes','no');
    $cache_array_rev    =   array('no','yes');
    $cache_array_fix    =   array('cover','contain');
    $img_full_screen                    = wpestate_dropdowns_theme_admin_option($post->ID,$cache_array_rev,'page_header_image_full_screen');
    $img_full_back_type                 = wpestate_dropdowns_theme_admin_option($post->ID,$cache_array_fix,'page_header_image_back_type');
    $page_header_title_over_image       = stripslashes ( esc_html ( get_post_meta($post->ID, 'page_header_title_over_image', true) ) );
    $page_header_subtitle_over_image    = stripslashes ( esc_html ( get_post_meta($post->ID, 'page_header_subtitle_over_image', true) ) );
    $page_header_image_height           = esc_html ( get_post_meta($post->ID, 'page_header_image_height', true) );
    $page_header_overlay_val            = esc_html ( get_post_meta($post->ID, 'page_header_overlay_val', true) );
    $page_header_overlay_color          = esc_html ( get_post_meta($post->ID, 'page_header_overlay_color', true) );

print'<div class="header_admin_options image_header">
    <p class="meta-options pblank">
    <h3 class="pblankh">'.esc_html__( 'Options for Static Image  (if Header Type "image" is selected)','wprentals-core').'</h3>
    </p>

   <div class="property_prop_half">
        <label for="page_custom_image">'.esc_html__( 'Header Image','wprentals-core').'</label><br />
        <input id="page_custom_image" type="text" size="36" class="wpestate_landing_upload" name="page_custom_image" value="'.$page_custom_image.'" />
	<input id="page_custom_image_button" type="button"   size="40" class="upload_button button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
    </div>

    <div class="property_prop_half">
        <label for="page_header_image_full_screen">'.__('Full Screen?','wprentals-core').'</label><br />
        <select id="page_header_image_full_screen" name="page_header_image_full_screen">
            '.$img_full_screen.'
        </select>
    </div>

    <div class="property_prop_half">
        <label for="page_header_image_back_type">'.__('Full Screen Background Type?','wprentals-core').'</label><br />
        <select id="page_header_image_back_type" name="page_header_image_back_type">
            '.$img_full_back_type.'
        </select>
    </div>

    <div class="property_prop_half">
        <label for="page_header_title_over_image">'.__('Title Over Image','wprentals-core').'</label><br />
        <input id="page_header_title_over_image" type="text" size="36" name="page_header_title_over_image" value="'.$page_header_title_over_image.'" />
    </div>

    <div class="property_prop_half">
        <label for="page_header_subtitle_over_image">'.__('SubTitle Over Image','wprentals-core').'</label><br />
        <input id="page_header_subtitle_over_image" type="text" size="36" name="page_header_subtitle_over_image" value="'.$page_header_subtitle_over_image.'" />
    </div>

    <div class="property_prop_half">
        <label for="page_header_image_height">'.__('Image Height(Ex:700, Default:580px)','wprentals-core').'</label><br />
        <input id="page_header_image_height" type="text" size="36" name="page_header_image_height" value="'.$page_header_image_height.'" />
    </div>

        <div class="property_prop_half">
            <label for="page_header_overlay_color">'.__('Overlay Color','wprentals-core').'</label><br />
            <div id="page_header_overlay_color" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$page_header_overlay_color.';"  ></div></div>  <input type="text" name="page_header_overlay_color" maxlength="7" class="inptxt " value="'.$page_header_overlay_color.'"/>
        </div>

        <div class="property_prop_half">
            <label for="page_header_overlay_val">'.__('Overlay Opacity(betwen 0 and 1 , Ex:0.5, default 0.6)','wprentals-core').'</label><br />
            <input id="page_header_overlay_val" type="text" size="36" name="page_header_overlay_val" value="'.$page_header_overlay_val.'" />
        </div>


</div>';
}
endif; // end   estate_page_map_box



if( !function_exists('estate_page_video_box') ):
function estate_page_video_box($post) {
    global $post;
    //page_custom_video


    $cache_array                        =   array('yes','no');
    $cache_array_reverse                =   array('no','yes');
    $cache_array_fix                    =   array('screen','auto');
    $page_custom_video                  =   get_post_meta($post->ID, 'page_custom_video', true);
    $page_custom_video_webbm            =   get_post_meta($post->ID, 'page_custom_video_webbm', true);
    $page_custom_video_ogv              =   get_post_meta($post->ID, 'page_custom_video_ogv', true);
    $page_custom_video_cover_image      =   get_post_meta($post->ID, 'page_custom_video_cover_image', true);
    $img_full_screen                    =   wpestate_dropdowns_theme_admin_option($post->ID,$cache_array_reverse,'page_header_video_full_screen');
    $page_header_title_over_video       =   stripslashes ( esc_html ( get_post_meta($post->ID, 'page_header_title_over_video', true) ) );
    $page_header_subtitle_over_video    =   stripslashes ( esc_html ( get_post_meta($post->ID, 'page_header_subtitle_over_video', true) ) );
    $page_header_video_height           =   esc_html ( get_post_meta($post->ID, 'page_header_video_height', true) );
    $page_header_overlay_color_video    =   esc_html ( get_post_meta($post->ID, 'page_header_overlay_color_video', true) );
    $page_header_overlay_val_video      =   esc_html ( get_post_meta($post->ID, 'page_header_overlay_val_video', true) );

    print '
    <div class="header_admin_options video_header">
        <p class="meta-options pblank">
            <h3 class="pblankh">'.__('Options for Video Header','wprentals-core').'</h3>
        </p>



       <div class="property_prop_half">
            <label for="page_custom_image">'.__('Video MP4 version','wprentals-core').'</label><br />
            <input id="page_custom_video" type="text"  class="wpestate_landing_upload"   size="36" name="page_custom_video" value="'.$page_custom_video.'" />
            <input id="page_custom_video_button" type="button"   size="40" class="upload_button button" value="'.__('Upload Video','wprentals-core').'" />
        </div>

        <div class="property_prop_half">
            <label for="page_custom_image">'.__('Video WEBM version','wprentals-core').'</label><br />
            <input id="page_custom_video_webbm" type="text" class="wpestate_landing_upload" size="36" name="page_custom_video_webbm" value="'.$page_custom_video_webbm.'" />
            <input id="page_custom_video_webbm_button" type="button"   size="40" class="upload_button button" value="'.__('Upload Video','wprentals-core').'" />
        </div>

        <div class="property_prop_half">
            <label for="page_custom_image">'.__('Video OGV version','wprentals-core').'</label><br />
            <input id="page_custom_video_ogv" type="text" class="wpestate_landing_upload" size="36" name="page_custom_video_ogv" value="'.$page_custom_video_ogv.'" />
            <input id="page_custom_video_ogv_button" type="button"   size="40" class="upload_button button" value="'.__('Upload Video','wprentals-core').'" />
        </div>

        <div class="property_prop_half">
            <label for="page_custom_video_cover_image">'.__('Cover Image','wprentals-core').'</label><br />
            <input id="page_custom_video_cover_image" class="wpestate_landing_upload" type="text" size="36" name="page_custom_video_cover_image" value="'.$page_custom_video_cover_image.'" />
            <input id="page_custom_video_cover_image_button" type="button"   size="40" class="upload_button button" value="'.__('Upload Image','wprentals-core').'" />
        </div>

        <div class="property_prop_half">
            <label for="page_header_video_full_screen">'.__('Full Screen?','wprentals-core').'</label><br />
            <select id="page_header_video_full_screen" name="page_header_video_full_screen">
                '.$img_full_screen.'
            </select>
        </div>

        <div class="property_prop_half">
            <label for="page_header_title_over_video">'.__('Title Over Image','wprentals-core').'</label><br />
            <input id="page_header_title_over_video" type="text" size="36" name="page_header_title_over_video" value="'.$page_header_title_over_video.'" />
        </div>

        <div class="property_prop_half">
            <label for="page_header_subtitle_over_video">'.__('SubTitle Over Image','wprentals-core').'</label><br />
            <input id="page_header_subtitle_over_video" type="text" size="36" name="page_header_subtitle_over_video" value="'.$page_header_subtitle_over_video.'" />
        </div>

        <div class="property_prop_half">
            <label for="page_header_video_height">'.__('Video Height(Ex:700, Default:580px)','wprentals-core').'</label><br />
            <input id="page_header_video_height" type="text" size="36" name="page_header_video_height" value="'.$page_header_video_height.'" />
        </div>

        <div class="property_prop_half">
            <label for="page_header_overlay_color_video">'.__('Overlay Color','wprentals-core').'</label><br />

            <div id="page_header_overlay_color_video" class="colorpickerHolder"><div class="sqcolor" style="background-color:#'.$page_header_overlay_color_video.';"  ></div></div>  <input type="text" name="page_header_overlay_color_video" maxlength="7" class="inptxt " value="'.$page_header_overlay_color_video.'"/>
        </div>

        <div class="property_prop_half">
            <label for="page_header_overlay_val_video">'.__('Overlay Opacity(betwen 0 and 1 , Ex:0.5, default 0.6)','wprentals-core').'</label><br />
            <input id="page_header_overlay_val_video" type="text" size="36" name="page_header_overlay_val_video" value="'.$page_header_overlay_val_video.'" />
        </div>


    </div>';

}
endif; // end   estate_page_slider_box



if( !function_exists('estate_page_theme_slider') ):
function estate_page_theme_slider($post) {
    return;
    global $post;
    $rev_slider           = get_post_meta($post->ID, 'rev_slider', true);
    print '
    <div class="header_admin_options theme_slider">
        <p class="meta-options pblank">
            <h3 class="pblankh">'.__('Options for Theme Slider','wprentals-core').'</h3>
        </p>
        <p class="meta-options">

        </p>
    </div>
    ';
}
endif; // end   estate_page_slider_box








////////////////////////////////////////////////////////////////////////////////////////////////
// Manage Custom Header of the page
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_page_map_box_agent') ):
    function estate_page_map_box_agent($post) {
        global $post;
        $page_lat           = get_post_meta($post->ID, 'page_custom_lat', true);
        $page_long          = get_post_meta($post->ID, 'page_custom_long', true);
        $page_custom_image  = get_post_meta($post->ID, 'page_custom_image', true);
        $page_custom_zoom  = get_post_meta($post->ID, 'page_custom_zoom', true);

        if ($page_custom_zoom==''){
            $page_custom_zoom=15;
        }

        print '

        <p class="meta-options">
            <label for="page_custom_image">'.esc_html__( 'Replace Map with this image','wprentals-core').'</label><br />
            <input id="page_custom_image" class="wpestate_landing_upload" type="text" size="36" name="page_custom_image" value="'.$page_custom_image.'" />
            <input id="page_custom_image_button" type="button"   size="40" class="upload_button button" value="'.esc_html__( 'Upload Image','wprentals-core').'" />
         </p>

         <p class="meta-options">
           <label for="page_custom_zoom">'.esc_html__( 'Zoom Level for map (1-20)','wprentals-core').'</label><br />
           <select name="page_custom_zoom" id="page_custom_zoom">';

          for ($i=1;$i<21;$i++){
               print '<option value="'.$i.'"';
               if($page_custom_zoom==$i){
                   print ' selected="selected" ';
               }
               print '>'.$i.'</option>';
           }

         print'
           </select>
         <p>
        ';

    }
endif; // end   estate_page_map_box_agent


if( !function_exists('wpestate_dropdowns_theme_admin') ):
    function wpestate_dropdowns_theme_admin_option_core($post_id,$array_values,$option_name,$pre=''){

        $dropdown_return    =   '';
        $option_value       =   esc_html ( get_post_meta($post_id, $option_name, true) );
        foreach($array_values as $value){
            $dropdown_return.='<option value="'.$value.'"';
              if ( $option_value == $value ){
                $dropdown_return.='selected="selected"';
            }
            $dropdown_return.='>'.$pre.$value.'</option>';
        }

        return $dropdown_return;

    }
endif;


////////////////////////////////////////////////////////////////////////////////////////////////
// Manage page options
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_page_options_box') ):
    function estate_page_options_box($post) {
        global $post;

        $page_title = get_post_meta($post->ID, 'page_show_title', true);
        $selected_no = $selected_yes = '';

        if ($page_title == 'no') {
            $selected_no = 'selected="selected"';
        } else {
            $selected_yes = 'selected="selected"';
        }

        if ($page_title != '') {
            $page_title_select = '<option value="' . $page_title . '" selected="selected">' . $page_title . '</option>';
        }

        print '
        <p class="meta-options">
        <label for="page_show_title">'.esc_html__( 'Show Title: ','wprentals-core').'</label><br />
        <select id="page_show_title" name="page_show_title">
                <option value="yes" ' . $selected_yes . '>yes</option>
                <option value="no" ' . $selected_no . '>no</option>
        </select></p>';

    }
endif; // end   estate_page_options_box


////////////////////////////////////////////////////////////////////////////////////////////////
// Manage post options
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_post_options_box') ):
    function estate_post_options_box($post) {
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
        global $post;

        $option = '';
        $title_values = array('yes', 'no');
        $post_title = get_post_meta($post->ID, 'post_show_title', true);
        foreach ($title_values as $value) {
            $option.='<option value="' . $value . '"';
            if ($value == $post_title) {
                $option.='selected="selected"';
            }
            $option.='>' . $value . '</option>';
        }

        print   '<p class="meta-options">
                    <label for="post_show_title">'.esc_html__( 'Show Title:','wprentals-core').' </label><br />
                    <select id="post_show_title" name="post_show_title">
                            ' . $option . '
                    </select><br />
                </p>';

        $option = '';
        $title_values = array( 'no','yes');
        $group_pictures = get_post_meta($post->ID, 'group_pictures', true);
        foreach ($title_values as $value) {
            $option.='<option value="' . $value . '"';
            if ($value == $group_pictures) {
                $option.='selected="selected"';
            }
            $option.='>' . $value . '</option>';
        }

        print'  <p class="meta-options">
                    <label for="group_pictures">'.esc_html__( 'Group pictures in slider?(*only for blog posts)','wprentals-core').' </label><br />
                    <select id="group_pictures" name="group_pictures">
                            ' . $option . '
                    </select><br />
                </p>';

    }
endif; // end   estate_post_options_box





////////////////////////////////////////////////////////////////////////////////////////////////
// Manage Sidebars per posts/page
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_sidebar_box') ):
    function estate_sidebar_box($post) {
        // Use nonce for verification
        wp_nonce_field(plugin_basename(__FILE__), 'wpestate_sidebar_noncename');
        global $post;
        global $wp_registered_sidebars ;
        $sidebar_name   = get_post_meta($post->ID, 'sidebar_select', true);
        $sidebar_option = get_post_meta($post->ID, 'sidebar_option', true);

        $sidebar_values = array(   0=>'right',
                                   1=>'left',
                                   2=>'none');

        $option         = '';

        foreach ($sidebar_values as $key=>$value) {
            $option.='<option value="' . $value . '"';
            if ($value == $sidebar_option) {
                $option.=' selected="selected"';
            }
            $option.='>' . $value . '</option>';
        }

        print '
        <div class="property_prop_half">
        <label for="sidebar_option">'.esc_html__( 'Where to show the sidebar: ','wprentals-core').' </label><br />
            <select id="sidebar_option" name="sidebar_option">
            ' . $option . '
            </select>
        </div>';

        print'
        <div class="property_prop_half">
        <label for="sidebar_select">'.esc_html__( 'Select the sidebar: ','wprentals-core').'</label><br />
            <select name="sidebar_select" id="sidebar_select">';
            foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
                print'<option value="' . ($sidebar['id'] ) . '"';
                if ($sidebar_name == $sidebar['id']) {
                    print' selected="selected"';
                }
                print' >' . ucwords($sidebar['name']) . '</option>';
            }
            print '
            </select>
        </div>';
    }
endif; // end   estate_sidebar_box





////////////////////////////////////////////////////////////////////////////////////////////////
// Saving of custom data
////////////////////////////////////////////////////////////////////////////////////////////////
if( !function_exists('estate_save_postdata') ):
function estate_save_postdata($post_id) {
    global $post;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    ///////////////////////////////////// Check permissions
    if(isset($_POST['post_type'])){
        if ('page' == $_POST['post_type'] or 'post' == $_POST['post_type'] or 'estate_property' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return;
        }
        else {
            if (!current_user_can('edit_post', $post_id))
                return;
        }
    }


    $allowed_keys=array(
      'embed_virtual_tour',
        'local_booking_type',
        'instant_booking',
        'wp_estate_replace_booking_form_local',
        'smoking_allowed',
        'party_allowed',
        'pets_allowed',
        'other_rules',
        'children_allowed',
        'cancellation_policy',
        'property_custom_details_admin_value',
        'property_custom_details_admin_label',
        'page_header_image_back_type',
        'page_header_image_full_screen',
        'page_header_overlay_val_video',
        'page_header_overlay_color_video',
        'page_header_video_height',
        'page_header_subtitle_over_video',
        'page_header_title_over_video',
        'page_header_video_full_screen',
        'page_custom_video_cover_image',
        'page_custom_video_ogv',
        'page_custom_video_webbm',
        'page_custom_video',
        'page_header_overlay_val',
        'page_header_overlay_color',
        'page_header_image_height',
        'page_header_subtitle_over_image',
        'page_header_title_over_image',
        'biling_period',
        'sidebar_option',
        'sidebar_select',
        'post_show_title',
        'group_pictures',
        'sidebar_option',
        'sidebar_select',
        'adv_filter_search_action',
        'adv_filter_search_category',
        'current_adv_filter_city',
        'current_adv_filter_area',
        'listing_filter',
        'show_featured_only',
        'show_filter_area',
        'header_type',
        'transparent_status',
        'listing_action',
        'listing_categ',
        'listing_city',
        'listing_area',
        'rev_slider',
        'page_custom_lat',
        'page_custom_long',
        'page_custom_zoom',
        'min_height',
        'max_height',
        'keep_min',
        'page_custom_image',
        'page_show_title',
        'property_address',
        'property_county',
        'property_state',
        'property_zip',
        'property_country',
        'property_status',
        'prop_featured',
        'property_price',
        'cleaning_fee',
        'cleaning_fee_per_day',
        'city_fee',
        'city_fee_per_day',
        'price_per_weekeend',
        'min_days_booking',
        'property_price_per_week',
        'property_price_per_month',
        'extra_price_per_guest',
        'max_extra_guest_no',
        'overload_guest',
        'checkin_change_over',
        'checkin_checkout_change_over',
        'price_per_guest_from_one',
        'property_size',
        'property_rooms',
        'property_bedrooms',
        'property_bathrooms',
        'guest_no',
        'embed_video_type',
        'property_affiliate',
        'virtual_tour',
        'private_notes',
        'checkin_message',
        'embed_video_id',
        'property_latitude',
        'property_longitude',
        'google_camera_angle',
        'page_custom_zoom',
        'property_agent',
        'agent_email',
        'agent_phone',
        'agent_mobile',
        'agent_skype',
        'agent_facebook',
        'agent_twitter',
        'agent_linkedin',
        'agent_pinterest',
        'live_in',
        'i_speak',
        'payment_info',
        'user_agent_id',
        'booking_from_date',
        'booking_to_date',
        'booking_id',
        'booking_guests',
        'booking_listing_name',
        'booking_status',
        'billing_freq',
        'pack_listings',
        'mem_list_unl',
        'pack_featured_listings',
        'pack_price',
        'pack_visible',
        'pack_stripe_id',
        'message_from_user',
        'message_to_user',
        'message_status',
        'delete_source',
        'delete_destination',
        'bypass_fit_bounds',
        'property_price_before_label',
        'property_price_after_label',
        'property_taxes',
        'security_deposit',
        'early_bird_percent',
        'early_bird_days',
        'cleaning_fee_per_day',
        'city_fee_per_day',
        'city_fee_percent',
        'min_days_booking',
        'image_to_attach'
        );



    $custom_fields = wprentals_get_option('wpestate_custom_fields_list','');
    if( !empty($custom_fields)){
        $i=0;
        while($i< count($custom_fields) ){
            $name =   $custom_fields[$i][0];
            if (function_exists('wpestate_limit45')) {
                $slug = wpestate_limit45(sanitize_title($name));
            } else {
                $slug = sanitize_title($name);
            }
            $slug           =     sanitize_key($slug);
            $allowed_keys[] =     $slug;
            $i++;
       }
    }



    foreach ($_POST as $key => $value) {
        if( !is_array ($value) ){
            if (in_array ($key, $allowed_keys)) {
                $postmeta = sanitize_text_field( $value );

                if( $key == 'property_price' ||$key == 'property_price_per_week' ||$key == 'property_price_per_month'  ){
                    
                    update_post_meta($post_id, sanitize_key($key),floatval ($postmeta) );
                    
                }else if( $key == 'property_affiliate'){
                    
                    update_post_meta($post_id,'property_affiliate',esc_url($_POST['property_affiliate']) );
              
                }else if( $key == 'cancellation_policy' || $key == 'other_rules' ){
                    
                   update_post_meta($post_id, sanitize_key($key),  sanitize_textarea_field( $value ) );
                   
                    
                }else if( $key == 'virtual_tour'){
                        global $allowedtags;
                    $iframe = array( 
                            'iframe'            =>  array(
                                'src'               =>  array(),
                                'width'             =>  array(),
                                'height'            =>  array(),
                                'frameborder'       =>  array(),
                                'style'             =>  array(),
                                'allow'             =>  array(),
                                'allowFullScreen'   =>  array(),
                                'scrolling'         => array(),// add any other attributes you wish to allow
                                'allowfullscreen'   => array()// add any other attributes you wish to allow
                            ) 
                    );
                    $allowed_html = array_merge( $allowedtags, $iframe );
                    $search         =   "'";
                    $replace        =   '"';
                    $virtual_tour   =   str_replace($search,$replace,$_POST['virtual_tour']);
                    
                    $virtual_tour   =   wp_kses (trim($virtual_tour),$allowed_html);
                    
            
                    
                    update_post_meta($post_id, 'virtual_tour',$virtual_tour );
                }else{
                    update_post_meta($post_id, sanitize_key($key), $postmeta );
                }
            }
        }
    }






    //////////////////////////////////////////////////////////////////
    //// change listing owner
    //////////////////////////////////////////////////////////////////

    if ( isset($_POST['property_agent'])){
        remove_action('save_post', 'estate_save_postdata',1);
        $new_user_as_agent  =   intval($_POST['property_agent']);
        //$new_user_id        =   intval ( get_post_meta($new_user_as_agent, 'user_agent_id', true) );
        $new_user_id = get_user_meta( $new_user_as_agent, 'user_agent_id',true);
        if($new_user_id==0){
            $new_user_id=1;
        }

        // change author
        $curpost = array(
            'ID'            => $post->ID,
            'post_author'   => $new_user_as_agent
        );

        wp_update_post($curpost );
        update_user_meta( $new_user_as_agent, 'user_agent_id', $new_user_id );
        add_action('save_post', 'estate_save_postdata', 1, 2);
    }

    ///////////////////////////// end change author



    if(isset($_POST['adv_filter_search_action'])){
        update_post_meta($post->ID, 'adv_filter_search_action',wpestate_sanitize_array ( $_POST['adv_filter_search_action'] ) );
     }else{
        if(isset($post->ID)){
           update_post_meta($post->ID, 'adv_filter_search_action','' );
        }
     }

     if(isset($_POST['adv_filter_search_category'])){
        update_post_meta($post->ID, 'adv_filter_search_category', wpestate_sanitize_array ($_POST['adv_filter_search_category']) );
     }else{
        if(isset($post->ID)){
            update_post_meta($post->ID, 'adv_filter_search_category','' );
        }
     }

     if(isset($_POST['current_adv_filter_city'])){
        update_post_meta($post->ID, 'current_adv_filter_city',wpestate_sanitize_array($_POST['current_adv_filter_city']) );
     }else{
        if(isset($post->ID)){
            update_post_meta($post->ID, 'current_adv_filter_city','' );
        }
     }


    if(isset($_POST['current_adv_filter_area'])){
        update_post_meta($post->ID, 'current_adv_filter_area',wpestate_sanitize_array ($_POST['current_adv_filter_area']) );
    }else{
        if(isset($post->ID)){
            update_post_meta($post->ID, 'current_adv_filter_area','' );
        }
    }


    if(isset($_POST['property_custom_details_admin_value'])){

        if(is_array($_POST['property_custom_details_admin_value'])){
            $extra_details_array = array();
            foreach( $_POST['property_custom_details_admin_value'] as $key=>$value){
                $extra_details_array[sanitize_text_field( $_POST['property_custom_details_admin_label'][$key] )]=sanitize_text_field($value);
            }
             update_post_meta($post_id, 'property_custom_details', $extra_details_array);
        }


    }

    $order=0;
   
   if ( isset( $post->ID ) && isset( $_POST['image_to_attach'] ) ) {
        $gallery = array_unique(
                array_filter(
                    array_map( 'trim', explode( ',', $_POST['image_to_attach'] ) ),
                    'is_numeric'
                )
            );




        if ( ! empty( $gallery ) ) {
            update_post_meta( $post->ID, 'wpestate_property_gallery', $gallery );
        } else {

            update_post_meta( $post->ID, 'wpestate_property_gallery', '' );
        }
    $gallery_meta = get_post_meta($post->ID, 'wpestate_property_gallery', true);

    }
    wp_reset_postdata();


}
endif; // end   estate_save_postdata









if (!function_exists('wpestate_sanitize_array')):
function wpestate_sanitize_array($original){
    $new_Array=array();
    foreach($original as $key=>$value){
        if(is_array($value)){
            $new_Array[sanitize_key($key)]=   wpestate_sanitize_array($value);
        }else{
            $new_Array[sanitize_key($key)]=  sanitize_text_field($value);
        }
    }
    return $new_Array;
}
endif;













/// edit reviews


if ( ! function_exists( 'extend_comment_edit_metafields' ) ):
	/**
	 * Save comment/review rating meta field data
	 * and trigger recalculation of the properties
	 * total star rating
	 *
	 */
	function extend_comment_edit_metafields( $comment_id ) {

		if ( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) {
			return;
		}

		$property_ID = intval( $_POST['comment_post_ID'] );
		if ( ( isset( $_POST['review_stars'] ) ) && ( $_POST['review_stars'] != '' ) ) {
			update_comment_meta( $comment_id, 'review_stars', intval( $_POST['review_stars'] ) );
			wpestate_calculate_property_rating( $property_ID );
		} else if ( isset( $_POST['star-rating-field'] ) && is_array( $_POST['star-rating-field'] ) ) {
			$star_rating = array();

			//gather all field data
			foreach ( $_POST['star-rating-field'] as $field_key => $field_value ) {
				$star_rating[ $field_key ] = sanitize_text_field( $field_value );
				$star_rating_partial[] = sprintf( '"%s": %s', $field_key, $field_value );
			}
			// calculate all total rating
			$star_rating_total     = wpestate_round_to_nearest_05( array_sum( $star_rating ) / count( $star_rating ) );
			$star_rating_partial[] = sprintf( '"%s": %s', 'rating', $star_rating_total );
			// create data for db
			$star_rating_str = '{' . implode( ',', $star_rating_partial ) . '}';
			update_comment_meta( $comment_id, 'review_stars', $star_rating_str );
			// recalculate review stars totals for the property
			wpestate_calculate_property_rating( $property_ID );
		}

	}
endif;


if ( ! function_exists( 'estate_comment_starts' ) ):
	/**
	 * Render star rating meta fields
	 * depending on old or new rating system
	 */
	function estate_comment_starts( $comment ) {

		$stars         = get_comment_meta( $comment->comment_ID, 'review_stars', TRUE );
		$rating_fields = wpestate_get_review_fields();
		$max_stars     = wpestate_get_max_stars();

		if ( is_string( $stars )) {
			$tmp_rating = json_decode( $stars, TRUE );
			$fields     = '';
			$fields     .= '<table>' . PHP_EOL;
			foreach ( $rating_fields['fields'] as $field_key => $field_value ) {
				$fields .= '<tr>' . PHP_EOL;
				$fields .= '<th align="left">' . esc_html( $field_value ) . '</th>' . PHP_EOL;
				$fields .= '<td><input name="star-rating-field[' . esc_attr( $field_key ) . ']" type="number" value="' . $tmp_rating[ $field_key ] . '" max="' . intval( $max_stars ) . '" min="1" step=".5"></td>' . PHP_EOL;
				$fields .= '</tr>' . PHP_EOL;
			}
			$fields .= '</table>' . PHP_EOL;
		} else if ( is_numeric( $stars ) ) {
			$i             = 1;
			$starts_select = '';
			while ( $i <= $max_stars ) {
				$starts_select .= '<option value="' . $i . '"';
				if ( $stars == $i ) {
					$starts_select .= ' selected="selected" ';
				}
				$starts_select .= '>' . $i . '</option>';
				$i ++;
			}
			$fields .= sprintf( '<select name="review_stars">%s</select>', $starts_select );
		}
		wp_nonce_field( 'extend_comment_update', 'extend_comment_update', FALSE );
		print '
        <table>
        <tr>
            <td width="33%" valign="top" align="left">
                ' . esc_html__( 'Stars', 'wprentals-core' ) . '
            </td>
            <td width="33%" valign="top" align="left">

              ' . $fields . '

            </td>
        </tr>

         </table>';
	}

endif;
