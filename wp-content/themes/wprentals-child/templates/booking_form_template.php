<?php
global $post;
global $current_user;
global $feature_list_array;
global $wpestate_propid ;
global $post_attachments;
global $wpestate_options;
global $wpestate_where_currency;
global $wpestate_property_description_text;
global $wpestate_property_details_text;
global $wpestate_property_details_text;
global $wpestate_property_adr_text;
global $wpestate_property_price_text;
global $wpestate_property_pictures_text;
global $wpestate_propid;
global $wpestate_gmap_lat;
global $wpestate_gmap_long;
global $wpestate_unit;
global $wpestate_currency;
global $wpestate_use_floor_plans;
global $favorite_text;
global $favorite_class;
global $property_action_terms_icon;
global $property_action;
global $property_category_terms_icon;
global $property_category;
global $guests;
global $bedrooms;
global $bathrooms;
global $show_sim_two;
global $guest_list;
global $post_id;
$rental_type        =   wprentals_get_option('wp_estate_item_rental_type','');
$booking_type       =   wprentals_return_booking_type($post->ID);
?>

<?php if($listing_page_type!=5){ ?>

    <div  itemprop="price"  class="listing_main_image_price">
        <?php
        $price_per_guest_from_one       =   floatval( get_post_meta($post->ID, 'price_per_guest_from_one', true) );
        $price                          =   floatval( get_post_meta($post->ID, 'property_price', true) );
$price_hfday = floatval(get_post_meta($post->ID, 'property_price_hfday', true));
$price_hr = floatval(get_post_meta($post->ID, 'property_price_hr', true));
$book_type = wprentals_return_booking_type($post->ID);
$after_noon_price = 0;
if($book_type == 2) {
    $price = floatval(get_post_meta($post->ID, 'morning_price', true));
    $after_noon_price = floatval(get_post_meta($post->ID, 'afternoon_price', true));
            }
//echo $price_hfday;
//echo $price_hr;
//echo $price;
        ?>
    <div class="vdf_selected_day">
        <div class="vdf_selected_day_main">
            <div class="vdf_day vdf_day_choose" id="vdf_bk_day01">
                <p class="morning_price">$ <?php echo number_format_i18n($price); ?> Per guest</p>
                <p class="afternoon_price hide">$ <?php echo number_format_i18n($after_noon_price); ?> Per guest</p>
            </div>

        </div>
    </div>
    </div>
<?php } ?>


<?php echo wpestate_show_booking_form($post_id,$wpestate_options,$favorite_class,$favorite_text); ?>
