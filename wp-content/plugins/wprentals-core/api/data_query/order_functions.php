<?php



function wpestate_api_create_query_order_by_array($order){
    /*
       "1"=>esc_html__('Price High to Low','wprentals-core'),
        "2"=>esc_html__('Price Low to High','wprentals-core'),
        "3"=>esc_html__('Newest first','wprentals-core'),
        "4"=>esc_html__('Oldest first','wprentals-core'),
        "11"=>esc_html__('Newest Edited','wprentals-core'),
        "12"=>esc_html__('Oldest Edited ','wprentals-core'),
        "5"=>esc_html__('Bedrooms High to Low','wprentals-core'),
        "6"=>esc_html__('Bedrooms Low to high','wprentals-core'),
        "7"=>esc_html__('Bathrooms High to Low','wprentals-core'),
        "8"=>esc_html__('Bathrooms Low to high','wprentals-core'),
        "0"=>esc_html__('Default','wprentals-core')
    */
    $meta_directions    =   'DESC';
    $meta_order         =   'prop_featured';
    $order_by           =   'meta_value_num';
    $order = intval($order);
 
 
    switch ($order){
        case 1:
            $meta_order='property_price';
            $meta_directions='DESC';
            $order_by='meta_value_num';
            break;
        case 2:
            $meta_order='property_price';
            $meta_directions='ASC';
            $order_by='meta_value_num';
            break;
        case 3:
            $meta_order='';
            $meta_directions='DESC';
            $order_by='ID';
            break;
        case 4:
            $meta_order='';
            $meta_directions='ASC';
            $order_by='ID';
            break;
        case 5:
            $meta_order='property_bedrooms';
            $meta_directions='DESC';
            $order_by='meta_value_num';
            break;
        case 6:
            $meta_order='property_bedrooms';
            $meta_directions='ASC';
            $order_by='meta_value_num';
            break;
        case 7:
            $meta_order='property_bathrooms';
            $meta_directions='DESC';
            $order_by='meta_value_num';
            break;
        case 8:
            $meta_order='property_bathrooms';
            $meta_directions='ASC';
            $order_by='meta_value_num';
            break;
        case 11:
            $meta_order='';
            $meta_directions='DESC';
            $order_by='modified';
            break;
        case 12:
            $meta_order='';
            $meta_directions='ASC';
            $order_by='modified';
            break;

        case 99:
                $meta_order='';
                $meta_directions='ASC';
                $order_by='rand';
                break;
    }
    
    $order_array=array(
        'orderby'           => $order_by,
    );

    if($meta_order!=''){
        $order_array['meta_key']=$meta_order;
    }
    if($meta_directions!=''){
        $order_array['order']=$meta_directions;
    }
 
          

    return $order_array;
 }