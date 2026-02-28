<?php
/**MILLDONE
 * Property List Filters Search Template
 * src:templates\properties_list_templates\filters_templates\property_list_filters_search.php
 * This template is responsible for displaying the search filters and sorting options
 * on property listing pages in the wprentals theme. It includes:
 * - Hidden inputs for AJAX functionality
 * - Sorting dropdown

 *
 * @package Wprentals
 * @subpackage PropertyListings
 * @since wprentals 1.0
 *
 * @global object $post The current post object
 * @global string $wpestate_prop_unit The current property unit display type ('grid' or 'list')
 *
 * @uses wpestate_listings_sort_options_array() to get sorting options
 * @uses wprentals_display_orderby_dropdown() to display the sorting dropdown
 */
?>
<div class="wprentals_adv_listing_filters_head row advanced_filters col-md-3">
  
    <?php
    // Hidden input for storing search arguments
    // These arguments are used to persist search criteria across AJAX requests
    ?>
    <input type="hidden" id="searcharg" value='<?php echo json_encode($args); ?>'>

    <?php
    // Hidden input for the current page ID used in AJAX requests
    ?>
    <input type="hidden" id="page_idx" value="<?php echo intval($post->ID); ?>">
    
    <?php
    // Generate a nonce for security in AJAX requests
    // This helps prevent CSRF attacks
    $ajax_nonce = wp_create_nonce("wpestate_search_nonce");
    ?>
    <input type="hidden" id="wpestate_search_nonce" value="<?php echo esc_attr($ajax_nonce); ?>"> 


        <?php 
        // Display the dropdown for ordering properties
        // This function is defined elsewhere in the theme
        wprentals_display_orderby_dropdown($post->ID);
        ?>

</div>

