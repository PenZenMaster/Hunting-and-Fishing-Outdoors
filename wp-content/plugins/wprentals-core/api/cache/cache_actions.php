<?php
/**
 * src: api\cache\cache_actions.php
 * Purpose: Core cache manipulation functions
 * Handles reading, writing, and clearing of cache data
 * Supports both object cache and transients
 */




/**
 * Cache the post meta and taxonomy data for a given post.
 *
 * This function checks if the object cache is available (using wp_using_ext_object_cache()).
 * If not, it falls back to using transients.
 *
 * @param int    $post_id   The ID of the post to cache.
 * @param string $post_type The post type of the post.
 */
if(!function_exists('wpestate_api_set_cache_post_data')){

	function wpestate_api_set_cache_post_data($postID, $post_type) {
		$post_type_data = wpestate_api_get_cached_post_types_and_data();
		$post_type_data_structure = $post_type_data[$post_type];
		$post = get_post($postID);
		// Get all post meta in a single query
		$all_post_meta = get_post_meta($postID);
	
		$cache_data = [
			'ID' => $postID,
			'title' => get_the_title($postID),
			'description' => $post->post_content,
			'excerpt' => get_the_excerpt($postID),
			'media' => wpestate_generate_array_image_urls(wpestate_generate_property_slider_image_ids($postID, true)),
			'terms' => array(),
			'meta' => array(),
			'custom_meta' => array(),
		];
	
		// Populate standard meta
		foreach ($post_type_data_structure['meta'] as $meta_key) {
			$cache_data['meta'][$meta_key] = isset($all_post_meta[$meta_key]) ? maybe_unserialize(   $all_post_meta[$meta_key][0] ): '';
			
		}

	
		// Populate custom meta
		foreach ($post_type_data_structure['custom_meta'] as $meta_key) {
			$cache_data['custom_meta'][$meta_key] = isset($all_post_meta[$meta_key]) ? $all_post_meta[$meta_key][0] : '';
		}
	
		foreach ($post_type_data_structure['taxonomies'] as $taxonomy) {
			$cache_data['terms'] = wpestate_api_get_optimized_terms_for_taxonomy($postID, $post_type_data_structure['taxonomies']);
		}
	
		$cache_key = wpestate_api_get_cache_key($post_type, $postID);
		
		if (wp_using_ext_object_cache()) {
			wp_cache_set($cache_key, $cache_data, '', 24 * HOUR_IN_SECONDS);
		} else {
			set_transient($cache_key, $cache_data, 24 * HOUR_IN_SECONDS);
		}
	}
}






/**
 * Retrieve cached data for a given post. If no cache is found, create it, store it, and return the data.
 *
 * @param int    $post_id   The ID of the post.
 * @param string $post_type The type of the post.
 *
 * @return array The cached data, including 'meta' and 'terms'.
 */
if(!function_exists('wpestate_api_get_cached_post_data')){
	function wpestate_api_get_cached_post_data($post_id, $post_type){
		// Generate cache key for the post
		$cache_key = wpestate_api_get_cache_key($post_type, $post_id);

		// Try to retrieve the cached data
		if(wp_using_ext_object_cache()){
			// Fetch data from object cache
			$cached_data = wp_cache_get($cache_key);
		}else{
			// Fetch data from transient		
			$cached_data = get_transient($cache_key);
		}

 

		// If cache is empty, generate and store it
		if($cached_data === false){
            
			// Cache does not exist; generate the cache data
			wpestate_api_set_cache_post_data($post_id, $post_type);

			// Fetch the newly cached data
			if(wp_using_ext_object_cache()){
				$cached_data = wp_cache_get($cache_key);
			}else{
				$cached_data = get_transient($cache_key);
			}
		}

		return $cached_data;
	}
}



/**
 *
 * Clear the cache for a given post ID.
 * This function checks if the object cache is available. If not, it
 * falls back to clearing transients.
 *
 * @param int    $post_id   The ID of the post to clear.
 * @param string $post_type The post type of the post.
 *
 */
if(!function_exists('wpestate_api_clear_post_cache')){
	function wpestate_api_clear_post_cache($post_id, $post_type){
		// Generate cache key
		$cache_key = wpestate_api_get_cache_key($post_type, $post_id);
		// Check if the object cache is available
		if(wp_using_ext_object_cache()){
			// Object cache is available, use wp_cache_delete
			wp_cache_delete($cache_key);
		}else{
			// Object cache is not available, use delete_transient
			delete_transient($cache_key);
		}
	}
}


/**
 * Clear all cached data related to posts and terms.
 *
 * This function clears all cached data, both from the object cache
 * (using wp_cache_flush) and from transients associated with cached post data.
 */
if(!function_exists('wpestate_api_clear_all_cache')){
	function wpestate_api_clear_all_cache(){
		// 1. Clear all object cache
		if(wp_using_ext_object_cache()){
			wp_cache_flush(); // Flush the entire object cache
		}

		// 2. Clear all transients associated with cached post data
		global $wpdb;

		// Define the prefix used in the transient keys in the codebase
		$transient_prefix = 'wpestate_api_';

		// Delete all transients that match the prefix used for caching
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s", "_transient_{$transient_prefix}%", "_transient_timeout_{$transient_prefix}%"));
	}
}


/**
 * Handles manual cache reset requests
 * @since 4.0.0
 * @hooks into admin_post_wpestate_core_reset_post_cache
 * @security Includes nonce verification
 */
add_action('admin_post_wpestate_core_reset_post_cache', 'wpestate_core_reset_post_cache');
if(!function_exists('wpestate_core_reset_post_cache')){
	function wpestate_core_reset_post_cache(){
		if(isset($_GET['_wpnonce'], $_GET['post_id'])){

			if(!wp_verify_nonce($_GET['_wpnonce'], 'wpestate_purge_cache')){
				wp_nonce_ays('');
			}

			$post_id   = intval($_GET['post_id']);
			$post_type = get_post_type($post_id);

			wpestate_api_clear_post_cache($post_id, $post_type);

			$redirect_url = add_query_arg('cache_reset_success', '1', wp_get_referer());
			wp_redirect($redirect_url);
			die();
		}
	}
}
