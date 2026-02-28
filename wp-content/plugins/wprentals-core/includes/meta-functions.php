<?php
/**
 * WPRentals Meta Functions
 *
 * Handles the addition of meta tags to the header for SEO and social sharing.
 *
 * @package    WPRentals
 * @subpackage Core
 * @since      4.0
 * 
 * @uses       is_tax() For checking taxonomy pages
 * @uses       is_singular() For checking single post types
 * @uses       get_post_thumbnail_id() For getting featured image
 * @uses       wp_get_attachment_image_src() For getting image details
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds custom meta tags to header for SEO and social sharing
 * Handles taxonomy descriptions, noindex rules, and OpenGraph data
 *
 * @since 4.0
 * @return void
 */
if (!function_exists('wpestate_add_custom_meta_to_header')):
function wpestate_add_custom_meta_to_header() {
    global $post;
    
    // Add meta description for taxonomy pages
    if (is_tax()) {
        $description = strip_tags(term_description('', get_query_var('taxonomy')));
        if (!empty($description)) {
            $meta_description = sprintf(
                '<meta name="description" content="%s">',
                esc_attr($description)
            );
            echo wp_kses(
                $meta_description,
                array(
                    'meta' => array(
                        'name' => array(),
                        'content' => array()
                    )
                )
            );
        }
    }

    // Add noindex for specific post types
    $noindex_types = array('wpestate_invoice', 'wpestate_message', 'wpestate_booking');
    if (is_singular($noindex_types)) {
        $meta_robots = '<meta name="robots" content="noindex">';
        echo wp_kses(
            $meta_robots,
            array(
                'meta' => array(
                    'name' => array(),
                    'content' => array()
                )
            )
        );
    }

    // Add OpenGraph meta for property listings
    if (is_singular('estate_property')) {
        $image_id = get_post_thumbnail_id();
        $share_img = wp_get_attachment_image_src($image_id, 'full');
        $share_img_path = '';
        
        if (isset($share_img[0])) {
            $share_img_path = $share_img[0];
        }

        // Only output meta tags if we have content to share
        if (!empty($share_img_path) || !empty($post->post_content)) {
            // Prepare meta tags array
            $meta_tags = array();
            
            if (!empty($share_img_path)) {
                $meta_tags[] = sprintf(
                    '<meta property="og:image" content="%s"/>',
                    esc_url($share_img_path)
                );
                $meta_tags[] = sprintf(
                    '<meta property="og:image:secure_url" content="%s"/>',
                    esc_url($share_img_path)
                );
            }
            
            if (!empty($post->post_content)) {
                $meta_tags[] = sprintf(
                    '<meta property="og:description" content="%s"/>',
                    esc_attr(wp_strip_all_tags($post->post_content))
                );
            }

            // Output meta tags
            foreach ($meta_tags as $tag) {
                echo wp_kses(
                    $tag,
                    array(
                        'meta' => array(
                            'property' => array(),
                            'content' => array()
                        )
                    )
                );
            }
        }
    }
}
endif;

// Add meta tags to header
add_action('wp_head', 'wpestate_add_custom_meta_to_header');