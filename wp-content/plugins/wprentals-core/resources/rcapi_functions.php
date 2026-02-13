<?php
  


//samele array request
$test=array(
    'order'=>1,
    'taxonomy'=>array(
            'property_category'=>array(5,6,237,11)
    ),
    'meta'=>array(
        'prop_featured'=>array('value'=>1,'type'=>'numeric','compare'=>'='),
    ),
    'ids'=>array(1,2,3),
);




/*
*
* Build arguments 
* 
* 
*/
if( !function_exists('westate_api2_get_listings_build_arguments') ):
function westate_api2_get_listings_build_arguments($input_parameters){

    
        $wp_query_argument= array(
            'post_type'         => 'estate_property',
            'post_status'       => 'publish',
        );
        
        
        
        // add taxonomies
        if(isset($input_parameters['taxonomy'])):
            $taxonomies =  wpestate_api_2_get_listings_build_arguments_add_taxonomies($input_parameters['taxonomy']);
            if(!empty($taxonomies)):
                $wp_query_argument['tax_query']=$taxonomies;
            endif;
        endif;  
           
       
        //add meta querues
        if(isset($input_parameters['meta'])):
            $meta_data =  wpestate_api_2_get_listings_build_arguments_add_meta($input_parameters['meta']);
            if(!empty($meta_data)):
                $wp_query_argument['meta_query']=$meta_data;
            endif;
        endif;  
        
        
        // if ids
        if(isset($input_parameters['ids'])){
            $wp_query_argument['post__in']=$input_parameters['ids'];
            unset( $wp_query_argument['tax_query']);            
        }
    
        
        //add order
        $wp_query_argument=wpestate_api_2_get_listings_build_arguments_add_order($input_parameters['order'],$wp_query_argument);
        

        
        //add pagination
        $wp_query_argument=wpestate_api_2_get_listings_build_arguments_add_pagination($input_parameters['page_no'],$input_parameters['items_per_page'],$wp_query_argument);
                
        return $wp_query_argument;
}
endif;
    
    
/*
*
* Display Query Result
* 
* 
*/
if( !function_exists('westate_api2_display_query_results') ):
function westate_api2_display_query_results($aguments,$display_parameters){
    global $is_shortcode;
    global $wpestate_listing_type;
    global $wpestate_property_unit_slider;
    global $wpestate_row_number_col;
    global $wpestate_currency;
    global $wpestate_where_currency;
    $wpestate_currency      =   esc_html( wprentals_get_option('wp_estate_currency_label_main') );
    $wpestate_where_currency=   esc_html( wprentals_get_option('wp_estate_where_currency_symbol') );
    
    
    $wpestate_property_unit_slider  =   esc_html ( wprentals_get_option('wp_estate_prop_list_slider','') );
    $wpestate_listing_type          =   wprentals_get_option('wp_estate_listing_unit_type','');
    $is_shortcode                   =   1;
    $wpestate_row_number_col        =   $display_parameters['number_of_col'];
    
    $recent_posts = new WP_Query($aguments);
    
   
    
    
    ob_start();   

    print '<div class="items_shortcode_wrapper ' .esc_attr($display_parameters['display_grid_class']).'" >';
        while ($recent_posts->have_posts()): $recent_posts->the_post();
              include(locate_template('templates/property_unit.php',false,false) );
        endwhile;
    print '</div>';
    
    $return_string= ob_get_contents();
    ob_end_clean();
    wp_reset_query();   
    wp_reset_postdata();

    return $return_string;
}
endif;

/*
*
* Build taxonomies 
* 
* 
*/
if( !function_exists('wpestate_api_2_get_listings_build_arguments_add_meta') ):
function wpestate_api_2_get_listings_build_arguments_add_meta($input_meta){
   
    $return_array=array();
    if(is_array($input_meta) &&!empty($input_meta)):
        foreach ($input_meta as $key_name=>$values){
            if( !empty($values) ){
                $temp_array=array(  
                            'key'       => $key_name,
                            'value'     => $values['value'],
                            'type'      => $values['type'],
                            'compare'   => $values['compare']
                        );
                $return_array[]=$temp_array;
            }

        }
    endif;
    return $return_array; 

    
}
endif;

/*
*
* Build taxonomies 
* 
* 
*/
if( !function_exists('wpestate_api_2_get_listings_build_arguments_add_taxonomies') ):
function wpestate_api_2_get_listings_build_arguments_add_taxonomies($input_taxonmies){
    $return_array =array();
    if(is_array($input_taxonmies) &&!empty($input_taxonmies)):
        foreach ($input_taxonmies as $key_name=>$values){
         
            
            if( !empty($values) ){
                $temp_array=array(  
                            'taxonomy'  => $key_name,
                            'field'     => 'term_id',
                            'terms'     => $values
                        );
                $return_array[]=$temp_array;
            }
        }
    endif;
    
     if(is_array($return_array) &&!empty($return_array)):
        //  $return_array['relation'] = 'OR';
     endif;
     return $return_array;
        
}
endif;

/*
*
* Build order 
* 
* 
*/
if( !function_exists('wpestate_api_2_get_listings_build_arguments_add_order') ):
function wpestate_api_2_get_listings_build_arguments_add_order($order,$input_arguments){
     
    $meta_directions='DESC';
    $meta_order     ='post_date';
    $order_by       ='meta_value_num';
     
    switch ($order) {
             case 0:
               $meta_order='prop_featured';
               $meta_directions='DESC';
               break;
           case 1:
               $meta_order='property_price';
               $meta_directions='DESC';
               break;
           case 2:
               $meta_order='property_price';
               $meta_directions='ASC';
               break;
           case 3:
               $meta_order='property_size';
               $meta_directions='DESC';
               break;
           case 4:
               $meta_order='property_size';
               $meta_directions='ASC';
               break;
           case 5:
               $meta_order='property_bedrooms';
               $meta_directions='DESC';
               break;
           case 6:
               $meta_order='property_bedrooms';
               $meta_directions='ASC';
               break;
            case 10:
               $order_by='post__in';
               $meta_order='';
               break;
        }
        
        $input_arguments['orderby']=$order_by;
        if($meta_order!=''){
            $input_arguments['meta_key']=$meta_order;
        }
        
        $input_arguments['order']=$meta_directions;
        
        return $input_arguments;
}
endif;



/*
*
* Build order 
* 
* 
*/

if( !function_exists('wpestate_api_2_get_listings_build_arguments_add_pagination') ):
function wpestate_api_2_get_listings_build_arguments_add_pagination($page_no,$items_per_page,$wp_query_argument){
    $wp_query_argument['paged']=intval($page_no);
    $wp_query_argument['posts_per_page']=intval($items_per_page);
        
    return $wp_query_argument;
}
endif;


?>