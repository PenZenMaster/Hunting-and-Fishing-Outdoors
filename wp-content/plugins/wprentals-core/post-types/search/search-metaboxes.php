<?php
/**
 * Search Metaboxes
 * src: post-types/search/search-metaboxes.php
 */
if (!defined('ABSPATH')) {
    exit;
}


/**
* Add search metaboxes to admin edit screen
* Registers the search details metabox
*
* @return void 
*/

add_action('add_meta_boxes', 'wpestate_add_searches');
if (!function_exists('wpestate_add_searches')):
    function wpestate_add_searches() {
        add_meta_box(
            'estate_search-sectionid',
            esc_html__('Search Details', 'wprentals-core'),
            'wpestate_search_details',
            'wpestate_search',
            'normal',
            'default'
        );
    }
endif;




/**
* Display search metabox content
* Shows saved search parameters and details
*
* @param WP_Post $post Post object
* @return void
*/
if (!function_exists('wpestate_search_details')):
    function wpestate_search_details($post) {
        wp_nonce_field('wpestate_search_nonce', 'wpestate_search_nonce');
        // Add your metabox content here
    }
endif;