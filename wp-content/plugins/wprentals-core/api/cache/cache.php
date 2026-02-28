<?php
/**
 * src: api\cache\cache.php
 * Purpose: Main cache configuration and initialization
 * Defines cached post types and their associated data
 * Provides core cache utility functions
 */

require_once WPESTATE_PLUGIN_PATH . 'api/cache/cache_actions.php';
require_once WPESTATE_PLUGIN_PATH . 'api/cache/cache_admin_interface.php';
require_once WPESTATE_PLUGIN_PATH . 'api/cache/cache_trigger.php';
require_once WPESTATE_PLUGIN_PATH . 'api/cache/cache_get_data_functions.php';
require_once WPESTATE_PLUGIN_PATH . 'api/cache/performance_tests.php';

/**
 * Defines post types and data structures for caching
 * @return array Post types with their meta and taxonomy configurations
 * @since 4.0.0
 */
if(!function_exists('wpestate_api_get_cached_post_types_and_data')){
	function wpestate_api_get_cached_post_types_and_data(){
		
		

		$data= array(

			'estate_property'  => array(
								'taxonomies' => array(
													'property_category',
													'property_action_category', 
													'property_city',
													'property_area',
													'property_features',	
													'property_status'
												),
				
								'meta'       => array(					
													'property_price',
													'local_booking_type',
													'property_label',
													'property_label_before',
													'property_size',
													'property_rooms',
													'property_bedrooms',
													'property_bathrooms',
													'property_bedrooms_details',
													'property_address',
													'property_county',
													'property_state',
													'property_zip',
													'property_country',
													'property_admin_area',
													'property_status',
													'property_price_per_week',
													'property_price_per_month',
													'cleaning_fee_per_day',
													'city_fee_per_day',
													'price_per_guest_from_one',
													'property_latitude',
													'property_longitude',
													'google_camera_angle',
													'property_agent',
													'guest_no',
													'property_affiliate',
													'instant_booking',
													'children_as_guests',
													'overload_guest',
													'max_extra_guest_no',
													'wp_estate_replace_booking_form_local',
													'property_category',
													'property_action_category',
													'property_city',
													'property_area',
													'property_area_front',
													'city_fee',
													'cleaning_fee',
													'price_per_weekeend',
													'checkin_change_over',
													'checkin_checkout_change_over',
													'min_days_booking',
													'extra_price_per_guest',
													'city_fee_percent',
													'security_deposit',
													'property_price_after_label',
													'property_price_before_label',
													'extra_pay_options',
													'early_bird_days',
													'early_bird_percent',
													'property_taxes',
													'security_deposit',
													'booking_start_hour',
													'booking_end_hour',
													'embed_video_type',
													'embed_video_id',
													'virtual_tour',
													'custom_price',
													'mega_details',
													'cancellation_policy',
													'other_rules',
													'smoking_allowed',
													'pets_allowed',
													'party_allowed',
													'children_allowed',
													'private_notes',
													'checkin_message',
													'extra_details_options',
													'property_custom_details',
													'property_icalendar_import_multi',
													'booking_dates'
								),
								'custom_meta'=> wpestate_api_return_custom_fields_array_for_cache(),

			
			)
		);
	

		return $data;
			
				




	}
}




/**
 * Retrieves custom fields for caching
 * @return array Custom field slugs
 * @since 4.0.0
 */
function wpestate_api_return_custom_fields_array_for_cache(){
	$custom_fields = wprentals_get_option('wpestate_custom_fields_list', '');
	$custom_fields_array=array();

	if (is_array($custom_fields)) {
		foreach ($custom_fields as $key => $custom_field ){
	
			if(isset($custom_field[0])  && $custom_field[0]!=''){
				$name 		  =   $custom_field[0];
				if (function_exists('wpestate_limit45')) {
					$slug = wpestate_limit45(sanitize_title($name));
				} else {
					$slug = sanitize_title($name);
				}
				$slug         =   sanitize_key($slug);
				$custom_fields_array[]=$slug;
			}
			
		}
	}

	return $custom_fields_array;

}




/**
 * Checks if post type supports caching
 * @param string $post_type Post type to check
 * @return boolean True if caching is supported
 * @since 4.0.0
 */
function wpestate_api_permit_cache_operations($post_type){
	if( in_array($post_type,array_keys( wpestate_api_get_cached_post_types_and_data() ) ) ){
		return true;
	}
	return false;
}



/**
 * Generate a cache key for a custom post type and post ID.
 *
 * @param string $post_type The custom post type.
 * @param int    $post_id   The ID of the post.
 *
 * @return string The generated cache key.
 */
if(!function_exists('wpestate_api_get_cache_key')){
	function wpestate_api_get_cache_key($post_type, $post_id){
		return "wpestate_api_{$post_type}_{$post_id}_cache";
	}
}





/**
 *
 * Manually trigger cache recreation for a given post ID.
 * This function allows manually refreshing the cache by clearing the
 * old cache and generating a new one.
 *
 * @param int    $post_id   The ID of the post.
 * @param string $post_type The post type of the post.
 */
if(!function_exists('wpestate_api_manually_refresh_cache')){
	function wpestate_api_manually_refresh_cache($post_id, $post_type){
		// Check if the post type is one that should be cached
		if(!wpestate_api_permit_cache_operations($post_type)){  
			return;
		}

		wpestate_api_set_cache_post_data($post_id, $post_type); // Re-cache the data
	}
}