<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

    
if ( !function_exists( 'wpestate_chld_thm_cfg_parent_css' ) ):
   function wpestate_chld_thm_cfg_parent_css() {

    $parent_style = 'wpestate_style'; 
    wp_enqueue_style('bootstrap',get_template_directory_uri().'/css/bootstrap.css', array(), '1.0', 'all');
    wp_enqueue_style('bootstrap-theme',get_template_directory_uri().'/css/bootstrap-theme.css', array(), '1.0', 'all');
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css',array('bootstrap','bootstrap-theme'),'all' );
    wp_enqueue_style( 'wpestate-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
    
   }    
    
endif;
add_action( 'wp_enqueue_scripts', 'wpestate_chld_thm_cfg_parent_css' );
load_child_theme_textdomain('wprentals', get_stylesheet_directory().'/languages');
// END ENQUEUE PARENT ACTION

// Custom HNFO functionality (amenity request system)
require_once get_stylesheet_directory() . '/wqs/functions.php';

// Half-day / same-day booking overrides (migrated from parent 3.11.4 for upgrade safety).
// Both functions are guarded with if(!function_exists()), so loading here ensures the
// child-theme version wins over whatever the upgraded parent ships.
require_once get_stylesheet_directory() . '/libs/ajax-half-day-booking.php';

// Fix: parent 3.17.0 get_pages() uses 'number' => count($templates) = 21, cutting off
// pages whose titles sort alphabetically past position 21 (e.g. "My Listings" at 25+).
// This override passes 'number' => 0 (unlimited) so all dashboard pages are found.
require_once get_stylesheet_directory() . '/libs/dashboard-link-fix.php';

/**
 * Enqueue the half-day price-save JS patch.
 *
 * Uses $.ajaxPrefilter to inject missing half-day fields into the
 * wpestate_ajax_update_listing_price AJAX call. Safe alongside both
 * WPRentals 3.11.4 (already sends the fields -- prefilter skips them)
 * and 3.17.0+ (removed the fields -- prefilter adds them back).
 */
function hnfo_enqueue_half_day_price_save_js() {
    wp_enqueue_script(
        'hnfo-half-day-price-save',
        get_stylesheet_directory_uri() . '/js/half-day-price-save.js',
        array( 'jquery' ),
        '1.00',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hnfo_enqueue_half_day_price_save_js' );


// add_action('init','admin_vd_check');
// function admin_vd_check() {
//    $action_terms = get_terms( array(
//        'taxonomy' => 'property_features',
//        'hide_empty' => false,
//    ) );

//    foreach ($action_terms as $action_key) {
//        //print_r($action_terms);
//        $amnts_term_id = $action_key->term_id;
//         update_term_meta($amnts_term_id,'is_fishing','Fishing');
//         update_term_meta($amnts_term_id,'is_hunt_camp','Hunt Camp');
//          update_term_meta($amnts_term_id,'is_hunting','Hunting');
//          update_term_meta($amnts_term_id,'is_hunt_fishing','Hunting and Fishing');
//        //echo $amnts_term_name;
       
//        //echo $action_category;

//    }
// }
add_action('admin_head', 'admin_custom_func');

function admin_custom_func() {

  echo '<style>
      .fishing-actions .vd_actions_list {
         display: flex;
      }
  </style>';
}

add_action('wp_footer','vw_half_day_time');

function vw_half_day_time(){
   ?>
   <script type="text/javascript"> 
      jQuery(document).ready(function(){


         jQuery('.vw_half_day_session').css('display','none');
         jQuery('.vw-half-day').css('display','none');
         jQuery('.vdf_htly_tm').css('display','none');
         jQuery('input[name=vdf_price_per_day]').change(function(){
            var select_period_status = jQuery(this).parent().find('label').text();
            //console.log(select_period_status);
            if (select_period_status == 'Per day') {
              jQuery('.vdf_day_choose').css('display','none');
              jQuery('.vdf_def_price').css('display','none');
              jQuery('#vdf_day01').css('display','block');
              jQuery('#vdf_bk_day01').css('display','block');
              jQuery('.vw_half_day_session').css('display','none');
              jQuery('.vw-half-day').css('display','none');
              jQuery('.vdf_end_date').css('display','block');
              jQuery('.vdf_htly_tm').css('display','none');
            }
            else if(select_period_status == 'Per half day'){
              jQuery('.vdf_day_choose').css('display','none');
              jQuery('.vdf_def_price').css('display','none');
              jQuery('#vdf_hfday01').css('display','block');
              jQuery('#vdf_bk_hfday01').css('display','block');
              jQuery('.vw_half_day_session').css('display','block');
              jQuery('.vdf_end_date').css('display','none');
              jQuery('.vdf_htly_tm').css('display','none');
            }
            else{
              jQuery('.vdf_day_choose').css('display','none');
              jQuery('.vdf_def_price').css('display','none');
              jQuery('#vdf_hourly01').css('display','block');
              jQuery('#vdf_bk_hourly01').css('display','block');
              jQuery('.vw_half_day_session').css('display','none');
              jQuery('.vw-half-day').css('display','none');
              jQuery('.vdf_end_date').css('display','none');
              jQuery('.vdf_htly_tm').css('display','block');
            }
          });

         jQuery('#vdf_price_per_day').trigger('click');

         jQuery('.vw-half-day #end_hour_no_wrapper').css('pointer-events','none');
         jQuery('.vw-half-day #start_hour_no_wrapper').css('pointer-events','none');
          jQuery('.vw-half-day .end_hour .dropdown').css('cursor','default');
          jQuery('.vw-half-day .start_hour .dropdown').css('cursor','default');
          jQuery('#start_hour_wrapper_list').css('display','none');
         jQuery(".vw-half-day #start_hour_wrapper_list li").click(function(){
             var half_start_time = jQuery(this).text();
             //console.log(half_start_time);
             var half_day_start_hrs = half_start_time.replace(':00','');
             var half_day_end_hrs = parseInt(half_day_start_hrs) + 6;
             //alert(half_day_end_hrs);
             if (half_day_end_hrs > 23) {
               half_day_end_hrs = half_day_end_hrs - 23;
             }
             else{
               half_day_end_hrs = half_day_end_hrs;
             }
         });

        //  jQuery('#vw_mrng_time01').change(function(){
        //  	jQuery('.vw-half-day').css('display','block');
        //  var half_start_hr_mrng = jQuery('#fah_start_hour01').val();
        //  var half_end_hr_mrng = jQuery('#fah_end_hour01').val();
        //        jQuery(".vw-half-day #start_hour_wrapper_list li").each(function(){
        //         var start_time_text = jQuery(this).text();
        //         if(half_start_hr_mrng == start_time_text){
        //           //console.log(start_time_text);
        //           jQuery(this).trigger('click');
        //         }
        //       });
        //          jQuery(".vw-half-day #end_hour_wrapper_list li").each(function(){
        //         var end_time_text = jQuery(this).text();
        //         if(half_end_hr_mrng == end_time_text){
        //           //console.log(end_time_text);
        //           jQuery(this).trigger('click');
        //         }
        //       });
        //  });
        //  jQuery('#vw_evng_time01').change(function(){
        //  	jQuery('.vw-half-day').css('display','block'); 
        //  var half_start_hr_evng = jQuery('#fah_start_hour01_noon').val();
        //  var half_end_hr_evng = jQuery('#fah_end_hour01_noon').val();
        //  //console.log(half_start_hr_evng);
        // // console.log(half_end_hr_evng);
        //   jQuery(".vw-half-day #start_hour_wrapper_list li").each(function(){
        //         var start_time_text_evng = jQuery(this).text();
        //         //console.log(start_time_text_evng);
        //         if(half_start_hr_evng == start_time_text_evng){
        //           //console.log(start_time_text);
        //           jQuery(this).trigger('click');
        //         }
        //       });
        //          jQuery(".vw-half-day #end_hour_wrapper_list li").each(function(){
        //         var end_time_text_evng = jQuery(this).text();
        //         if(half_end_hr_evng == end_time_text_evng){
        //          // console.log(end_time_text);
        //           jQuery(this).trigger('click');
        //         }
        //       });
        //  });

          jQuery('input[name=vw_mrng_time01]').change(function(){

            var select_status = jQuery(this).parent().find('label').text();

            if (select_status == 'Morning') {
                jQuery('.vw-half-day').css('display','block');
                 jQuery(".vw-half-day #start_hour_wrapper_list li").prop('selectedIndex',0);
                 var half_start_hr_mrng = jQuery('#fah_start_hour01').val();
                 var half_end_hr_mrng = jQuery('#fah_end_hour01').val();
                       jQuery(this).parent().parent().parent().next().next().next().find("#start_hour_wrapper_list li").each(function(){
                        var start_time_text = jQuery(this).text();
                        if(half_start_hr_mrng == start_time_text){
                          //console.log(start_time_text);
                          jQuery(this).trigger('click');
                        }
                      });
                          jQuery(this).parent().parent().parent().next().next().next().find("#end_hour_wrapper_list li").each(function(){
                        var end_time_text = jQuery(this).text();
                        if(half_end_hr_mrng == end_time_text){
                          //console.log(end_time_text);
                          jQuery(this).trigger('click');
                        }
                      });
            }
            else if(select_status == 'Afternoon') {
                jQuery('.vw-half-day').css('display','block'); 
                jQuery(".vw-half-day #start_hour_wrapper_list li").prop('selectedIndex',0);
               var half_start_hr_evng = jQuery('#fah_start_hour01_noon').val();
               var half_end_hr_evng = jQuery('#fah_end_hour01_noon').val();
               //console.log(half_start_hr_evng);
              // console.log(half_end_hr_evng);
                 jQuery(this).parent().parent().parent().next().next().next().find("#start_hour_wrapper_list li").each(function(){
                      var start_time_text_evng = jQuery(this).text();
                      //console.log(start_time_text_evng);
                      if(half_start_hr_evng == start_time_text_evng){
                        //console.log(start_time_text);
                        jQuery(this).trigger('click');
                      }
                    });
                       jQuery(this).parent().parent().parent().next().next().next().find("#end_hour_wrapper_list li").each(function(){
                      var end_time_text_evng = jQuery(this).text();
                      if(half_end_hr_evng == end_time_text_evng){
                       // console.log(end_time_text);
                        jQuery(this).trigger('click');
                      }
                    });
            }
          });
      });
   </script>
   <?php
}

function wpse27856_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );


add_action( 'wpforms_process_complete_3568', 'vdw_send_add_new_amenities', 10, 4 );
function change_default_sender_name( $original_email_from ) {
    return 'verdantwaye';
}
add_filter( 'wp_mail_from_name', 'change_default_sender_name' );

function vdw_send_add_new_amenities($fields, $entry, $entry_id, $form_data ){
 
  $amenity_name = $fields[1]['value'];
  $amenity_category = $fields[4]['value'];
  $amenity_desc = $fields[2]['value'];
  $amenity_image = $fields[3]['value'];
  $amenity_entry_id = $form_data;
  
  //die();

 global $wpdb;
 $amenities_data = $wpdb->get_results($wpdb->prepare("INSERT INTO new_amenities (new_amenity_entry_id, nw_amenity_name, nw_amenity_category, nw_amenity_description, nw_amenity_image) VALUES ($amenity_entry_id, '".$amenity_name."', '".$amenity_category."', '".$amenity_desc."', '".$amenity_image."')"));

 	$to = 'hilarion@g3.agency';
  //$to = 'asusannaneha@gmail.com';
	$subject = 'New Amenity Request';
  $message = '<h2>Hello Admin,</h2>';
  $message .= '<p><b>Approve or Deny new amenity request<b></p><a href="https://hnfo.verdantwaye.com/add-new-amenities/?am_id='.$amenity_entry_id.'"><b>Click here</b></a>';
  $message .= '<h3>Below are new amenity details:</h3>';
  $message .= '<p><b>Amenity name: </b>'.$amenity_name.'</p>';
  $message .= '<p><b>Amenity category: </b>'.$amenity_category.'</p>';
  $message .= '<p><b>Amenity description: </b>'.$amenity_desc.'</p>';
  $message .= '<p><b>Amenity Image: </b><a href="'.$amenity_image.'">View</a></p>';
	//$message .= $current_user;
	$headers = 'From: hello@hnfo.verdantwaye.com' . "\r\n" .
    'CC: prabha161810@gmail.com,saibhaskarmail@gmail.com' . "\r\n" .
    'Reply-To: hello@hnfo.verdantwaye.com'. "\r\n";
	wp_mail( $to, $subject, $message, $headers );
} 

// add_action( 'wpforms_process_entry_save', 'wpf_dev_process_entry_save', 10, 4 );

// function wpf_dev_process_entry_save(){
//   echo "success";
//   die();
// }

// ========================================
// REMOVED: Duplicate insecure amenity functions
// These functions are now in parent theme: wp-content/themes/wprentals/wqs/functions.php
// Removed on 2026-02-13: Child theme had old insecure versions with SQL injection
// Parent theme has secure versions with proper sanitization and capability checks
// ========================================

// ========================================
// Override: wpestate_display_feature
// Fixes PHP 8 "Undefined array key category_featured_image" warnings from parent theme.
// Parent theme accesses $term_meta['category_featured_image'] without isset() guards.
// This override adds isset() checks before each access.
// ========================================
if ( ! function_exists( 'wpestate_display_feature' ) ) :
    function wpestate_display_feature( $show_no_features, $term_name, $post_id, $property_features ) {
        $return_string = '';
        $term_object   = get_term_by( 'name', $term_name, 'property_features' );
        $term_meta     = get_option( "taxonomy_$term_object->term_id" );
        $term_icon     = '';
        $term_icon_wp  = '';

        if ( $term_meta != '' ) {
            $cat_image = isset( $term_meta['category_featured_image'] ) ? $term_meta['category_featured_image'] : '';
            $term_icon = '<img class="property_features_svg_icon" src="' . $cat_image . '" >';

            if ( ! empty( $cat_image ) ) {
                $term_icon_wp = wp_remote_get( $cat_image );
            }

            if ( is_wp_error( $term_icon_wp ) ) {
                $term_icon = '';
            } else {
                $term_icon = wp_remote_retrieve_body( $term_icon_wp );
            }
        }

        if ( $show_no_features != 'no' ) {
            if ( is_array( $property_features ) && array_search( $term_name, array_column( $property_features, 'name' ) ) !== false ) {
                if ( $term_icon == '' ) {
                    $term_icon = '<i class="fas fa-check checkon"></i>';
                }
                $return_string .= '<div class="listing_detail col-md-6">' . $term_icon . trim( $term_name ) . '</div>';
            } else {
                if ( $term_icon == '' ) {
                    $term_icon = '<i class="fas fa-times"></i>';
                }
                $return_string .= '<div class="listing_detail not_present col-md-6">' . $term_icon . trim( $term_name ) . '</div>';
            }
        } else {
            if ( is_array( $property_features ) && array_search( $term_name, array_column( $property_features, 'name' ) ) !== false ) {
                if ( $term_icon == '' ) {
                    $term_icon = '<i class="fas fa-check checkon"></i>';
                }
                $return_string .= '<div class="listing_detail col-md-6">' . $term_icon . trim( $term_name ) . '</div>';
            }
        }

        return $return_string;
    }
endif;
