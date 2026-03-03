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

// Places API (New) server-side proxy (GitHub issue #6).
// WPRentals 3.17.0 calls places.googleapis.com/v1/ directly from the browser,
// exposing the API key and preventing HTTP referrer restrictions on the GCP key.
// This proxy intercepts those fetch calls and forwards them server-side.
require_once get_stylesheet_directory() . '/libs/places-proxy.php';

// One-time home page widget migration (GitHub issue: empty sections).
// Triggered via ?run_homepage_migration=1 by an administrator.
// Remove this require after migration is confirmed complete.
require_once get_stylesheet_directory() . '/libs/homepage-widgets-migration.php';
add_action( 'init', 'hnfo_homepage_migration_trigger' );

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

/**
 * Enqueue the Places API proxy JS patch.
 *
 * Runs at priority 20 (after parent theme's priority 10) so we can check
 * whether wpestate_ajaxcalls_add is already enqueued before adding our
 * monkey-patch. Only loads on pages where the listing submission JS is present.
 */
function hnfo_enqueue_places_proxy_js() {
    if ( ! wp_script_is( 'wpestate_ajaxcalls_add', 'enqueued' ) ) {
        return;
    }

    wp_enqueue_script(
        'hnfo-places-proxy',
        get_stylesheet_directory_uri() . '/js/places-proxy.js',
        array( 'wpestate_ajaxcalls_add' ),
        '1.00',
        true
    );

    wp_localize_script(
        'hnfo-places-proxy',
        'hnfo_places_proxy_vars',
        array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'hnfo_places_nonce' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'hnfo_enqueue_places_proxy_js', 20 );


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

add_action( 'wpforms_process_complete_3568', 'vdw_send_add_new_amenities', 10, 4 );
function vdw_send_add_new_amenities( $fields, $entry, $entry_id, $form_data ) {

    $amenity_name     = sanitize_text_field( $fields[1]['value'] );
    $amenity_category = sanitize_text_field( $fields[4]['value'] );
    $amenity_desc     = sanitize_textarea_field( $fields[2]['value'] );
    $amenity_image    = esc_url_raw( $fields[3]['value'] );

    global $wpdb;
    $wpdb->insert(
        'new_amenities',
        array(
            'new_amenity_entry_id'   => absint( $entry_id ),
            'nw_amenity_name'        => $amenity_name,
            'nw_amenity_category'    => $amenity_category,
            'nw_amenity_description' => $amenity_desc,
            'nw_amenity_image'       => $amenity_image,
        ),
        array( '%d', '%s', '%s', '%s', '%s' )
    );

    $admin_email = get_option( 'hnfo_amenity_admin_email', get_option( 'admin_email' ) );
    $approve_url = home_url( '/add-new-amenities/?am_id=' . absint( $entry_id ) );

    $to      = $admin_email;
    $subject = 'New Amenity Request';
    $message  = '<h2>Hello Admin,</h2>';
    $message .= '<p><b>Approve or Deny new amenity request:</b> <a href="' . esc_url( $approve_url ) . '">Click here</a></p>';
    $message .= '<h3>Below are new amenity details:</h3>';
    $message .= '<p><b>Amenity name: </b>' . esc_html( $amenity_name ) . '</p>';
    $message .= '<p><b>Amenity category: </b>' . esc_html( $amenity_category ) . '</p>';
    $message .= '<p><b>Amenity description: </b>' . esc_html( $amenity_desc ) . '</p>';
    $message .= '<p><b>Amenity Image: </b><a href="' . esc_url( $amenity_image ) . '">View</a></p>';

    $headers   = array();
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>';
    $headers[] = 'Reply-To: ' . $admin_email;

    wp_mail( $to, $subject, $message, $headers );
}


