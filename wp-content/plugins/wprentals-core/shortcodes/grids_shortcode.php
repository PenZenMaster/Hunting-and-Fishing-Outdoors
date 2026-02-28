<?php

/*
* Widget wprentals grids function
*
*
*/
if( !function_exists('wprentals_content_grids') ):
function wprentals_content_grids($settings){
 

    $items_id   =   $settings['item_ids'];
    $item_array =   explode(',',$items_id);
    
    $permited_posts_types = array('estate_property','post');
    
    print '<div class="wpestate_content_grid_wrapper">';
    
        print '<div class="wpestate_content_grid_wrapper_first_col">';
            $itemID=$item_array[0];  
            $post_type=get_post_type($itemID);
            if(in_array($post_type, $permited_posts_types)){
                include( locate_template('templates/shortcode_templates/content_grid_big_'.$post_type.'_type_1.php') );
            }
        print '</div>';
        unset($item_array[0]);
    
    
        print '<div class="wpestate_content_grid_wrapper_second_col">';
            foreach($item_array as $key=>$value){
                $itemID=$value;
                $post_type=get_post_type($itemID);
                if(in_array($post_type, $permited_posts_types)){
                    include( locate_template('templates/shortcode_templates/content_grid_small_'.$post_type.'_type_1.php') );
                }
            }
        print '</div>';
    
    print '</div>';
    
}
endif;


/*
* Widget wprentals grids function
*
*
*/

if( !function_exists('wprentals_display_grids') ):
function wprentals_display_grids($args){
  $display_grids= wprentals_display_grids_setup();
  $taxonomies   = wprentals_query_taxonomies($args);

  $type         = intval( $args['type']);
  $place_type   = intval($args['wprentals_design_type']);
  $use_grid     = $display_grids[$type];

  $item_height_style='';
  $item_height=300;

  $category_tax=$args['grid_taxonomy'];
  $grid_pattern_size= count($use_grid['position']);

  $container='<div class="row elementor_wprentals_grid">';
  $container.='</div>';

  
  
  
  
  $container='<div class="row elementor_wprentals_grid">';
      foreach(  $taxonomies as $key=>$taxonomy ){
       
        
        if($key>($grid_pattern_size-1) ){
            $key_position = ($key % $grid_pattern_size )+1;
        }else{
            $key_position  = intval($key)+1;
        }
        
        $item_length=$use_grid['position'][$key_position];

        
        
        if( isset($taxonomies[$key]) ):
            $container.='<div class="'.esc_attr($item_length).' col-sm-12 elementor_rentals_grid">';
                ob_start();
                    $place_id       = $taxonomy->term_id;
                    $category_name  = $taxonomy->name;
                    $category_count = $taxonomy->count;
                    $type_class     = ' type_'.$place_type.'_class ';
                    include( locate_template('templates/places_unit_1.php' ) );
                $container.=ob_get_contents();
                ob_end_clean();
            $container.='</div>';
        endif;


    }

  $container.='</div>';
  return $container;
}
endif;



/*
* Default values for ELementor wprentals grids
*
*
*/

if( !function_exists('wprentals_display_grids_setup') ):
function wprentals_display_grids_setup(){
  $setup=array(
    1 =>  array(
              'position' => array(
                              1=> 'col-md-8',
                              2=> 'col-md-4',
                              3=> 'col-md-4',
                              4=> 'col-md-4',
                              5=> 'col-md-4',

                            )
          ),
      2 =>  array(
                'position' => array(
                                1=> 'col-md-6',
                                2=> 'col-md-3',
                                3=> 'col-md-3',
                                4=> 'col-md-3',
                                5=> 'col-md-3',
                                6=> 'col-md-6',
                              )
            ),
      3 =>  array(
                'position' => array(
                                  1=> 'col-md-4',
                                  2=> 'col-md-4',
                                  3=> 'col-md-4',
                                  4=> 'col-md-4',
                                  5=> 'col-md-4',
                                  6=> 'col-md-4',
                              )
            ),
        4 =>  array(
                  'position' => array(
                                  1=> 'col-md-4',
                                  2=> 'col-md-4',
                                  3=> 'col-md-4',
                                  4=> 'col-md-6',
                                  5=> 'col-md-6',
                                )
              ),
        5 =>  array(
                  'position' => array(
                                  1=> 'col-md-4',
                                  2=> 'col-md-8',
                                  3=> 'col-md-8',
                                  4=> 'col-md-4',
                                )
              ),
        6 =>  array(
                  'position' => array(
                                  1=> 'col-md-3',
                                  2=> 'col-md-3',
                                  3=> 'col-md-3',
                                  4=> 'col-md-3',
                                  5=> 'col-md-3',
                                  6=> 'col-md-3',
                                  7=> 'col-md-3',
                                  8=> 'col-md-3',
                                )
              ),
  );
  return $setup;
}
endif;

/*
*
*
*
*
*
*/

function wprentals_query_taxonomies($args){
  $defaults = array(
    'grid_taxonomy'       => '',
    'hide_empty_taxonomy' => false,
    'items_no'            => '',
    'orderby'             => 'name',
    'order'               => 'ASC',
  );

  $args          = wp_parse_args( $args, $defaults );
  $requested_tax = sanitize_key( $args['grid_taxonomy'] );

  if ( '' === $requested_tax ) {
    return array();
  }

  $items_no = '' !== $args['items_no'] ? intval( $args['items_no'] ) : '';
  $orderby  = ! empty( $args['orderby'] ) ? sanitize_key( $args['orderby'] ) : $defaults['orderby'];
  $order    = ! empty( $args['order'] ) ? strtoupper( $args['order'] ) : $defaults['order'];
  $order    = in_array( $order, array( 'ASC', 'DESC' ), true ) ? $order : $defaults['order'];

  $arguments = array(
    'taxonomy'   => $requested_tax,
    'hide_empty' => ! empty( $args['hide_empty_taxonomy'] ),
    'orderby'    => $orderby,
    'order'      => $order,
  );

  if ( '' !== $items_no ) {
    $arguments['number'] = $items_no;
  }

  if ( isset( $args[ $requested_tax ] ) && ! empty( $args[ $requested_tax ] ) ) {
    $slug = $args[ $requested_tax ];

    if ( is_array( $slug ) ) {
      $sanitized_slug = array_filter( array_map( 'sanitize_title', (array) $slug ) );

      if ( ! empty( $sanitized_slug ) ) {
        $arguments['slug'] = $sanitized_slug;
      }
    } else {
      $arguments['slug'] = sanitize_title( $slug );
    }
  }

  $temrs = get_terms($arguments);

  if ( !is_wp_error( $temrs ) ) {
    return $temrs;
  }else{
    return array();
  }


}
