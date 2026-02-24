<?php
// v1.00 commented out $test lines 88, 132, 167, 202, 233
// v1.01 modify lines 25 - 35 
// v1.03 added if (is_array($saved_terms))
// v1.04 line 400 correcting spelling $saved_term_id not $saved_term_ids
// v1.05 line 384 to 409 This code now checks if $saved_terms is an empty array before converting it to an array. If it's empty, it sets $saved_term_id to an empty string instead of an empty array. This ensures that the in_array() function will work correctly.
// v1.06 This code now checks if $saved_term_id is a string and converts it to an array before passing it to the in_array() function, line 394 to 410
//
//
//
global $feature_list_array;
global $edit_id;
global $moving_array;
global $edit_link_calendar;
global $submission_page_fields;

$list_to_show = '';
$terms = get_terms(array(
    'taxonomy' => 'property_features',
    'hide_empty' => false,
));

$property_category = get_the_terms($edit_id, 'property_action_category');
$category_name = '';

if (!empty($property_category)) {
    foreach ($property_category as $category) {
        $category_name = $category->name;
    }
}

$parsed_features = wpestate_build_terms_array();
$single_return_string = '';
$multi_return_string = '';

if (is_array($parsed_features)) {
    foreach ($parsed_features as $key => $item) {     
        $feature_name = $item['name'];
        $tag_id = get_term_by('name', $feature_name, 'property_features');
        $tag_id1 = get_term_by('taxonomy','property_features','category');
        $action_fishing_cat = get_term_meta($tag_id->term_id, 'is_fishing', true);
        $action_huntcamp_cat = get_term_meta($term->term_id, 'is_hunt_camp', true);
        $action_hunting_cat = get_term_meta($term->term_id, 'is_hunting', true);
        $action_hunt_fish_cat = get_term_meta($term->term_id, 'is_hunt_fishing', true);

        // Begin code comment for amenity filter not working

        // if ($category_name == "Fishing") {
           
        //     if ($feature_name == "Type of game") {
        //         continue;
        //     }
        //     if (count($item['childs']) > 0) {
        //         $multi_return_string_part = '<div data-attr="' . $category->name . '"  class="listing_detail  col-md-12 feature_block_' . $item['name'] . ' ">';
        //         $multi_return_string_part .= '<div class="feature_chapter_name  col-md-12">' . $item['name'] . '</div>';
        //         $multi_return_string_part_check = '';
        //         if (is_array($item['childs'])) {
        //             foreach ($item['childs'] as $key_ch => $child) {                    
        //                 $term = get_term_by('name', $child, 'property_features');

        //                  //$action_fishing_cat  =  get_term_meta($term->term_id, 'is_fishing', true);
        //                 // $action_huntcamp_cat = get_term_meta($term->term_id, 'is_hunt_camp', true);
        //                 // $action_hunting_cat  =  get_term_meta($term->term_id, 'is_hunting', true);
        //                 // $action_hunt_fish_cat = get_term_meta($term->term_id, 'is_hunt_fishing', true);  
        //                 // Begin code change 
        //                 // Newly added Amenities are not displayed because in listing file check the term meta "is_fishing" and it is not found in "Barrel Fish" amenity.                                              
        //                 //if ($action_fishing_cat != '') {
        //                 // Begin code change for amenity not displaying 
        //                 $taxonomy_terms  =  get_term_meta($term->term_id, 'taxonomy_terms', true);
        //                 if (!empty($taxonomy_terms) && in_array(50,$taxonomy_terms)) {
        //                 // End code change for amenity not displaying                        
        //                     $temp = wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);                          
        //                     $multi_return_string_part .= $temp;
        //                     $multi_return_string_part_check .= $temp;
        //                 }
        //                 // End code change Newly added Amenities are not displayed because in listing file check the term meta "is_fishing" and it is not found in "Barrel Fish" amenity.
        //             }
        //         }
        //         $multi_return_string_part .= '</div>';
        //         if ($multi_return_string_part_check != '') {
        //             $multi_return_string .= $multi_return_string_part;
        //         }
        //     } else {
        //         $term = get_term_by('name', $item['name'], 'property_features');
        //         $single_return_string .= wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
        //     }
        // } else if ($category_name == "Hunt Camp") {
        //     if ($feature_name == "Type of fish") {
              
        //         continue;
        //     }
        //     if (count($item['childs']) > 0) {
        //         $multi_return_string_part = '<div data-attr="' . $category->name . '"  class="listing_detail  col-md-12 feature_block_' . $item['name'] . ' ">';
        //         $multi_return_string_part .= '<div class="feature_chapter_name  col-md-12">' . $item['name'] . '</div>';

        //         $multi_return_string_part_check = '';
        //         if (is_array($item['childs'])) {
        //             foreach ($item['childs'] as $key_ch => $child) {
        //                 $term = get_term_by('name', $child, 'property_features');
        //                 // $action_huntcamp_cat = get_term_meta($term->term_id, 'is_hunt_camp', true);
        //                 // //echo $action_huntcamp_cat;
        //                 // $action_hunting_cat = get_term_meta($term->term_id, 'is_hunting', true);
        //                 //if ($action_huntcamp_cat != '') {

        //                 // Begin code change for amenity not displaying 
        //                 $taxonomy_terms  =  get_term_meta($term->term_id, 'taxonomy_terms', true);
        //                 if (!empty($taxonomy_terms) && in_array(3,$taxonomy_terms)) {
        //                 // End code change for amenity not displaying
                        
        //                     $temp = wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
        //                     $multi_return_string_part .= $temp;
        //                     $multi_return_string_part_check .= $temp;
        //                 }
        //             }
        //         }
        //         $multi_return_string_part .= '</div>';

        //         if ($multi_return_string_part_check != '') {
        //             $multi_return_string .= $multi_return_string_part;
        //         }

        //     } else {
        //         $term = get_term_by('name', $item['name'], 'property_features');
        //         $single_return_string .= wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
        //     }
        // } else if ($category_name == "Hunting") {
        //     if ($feature_name == "Type of fish") {
        //         continue;
        //     }
        //     if (count($item['childs']) > 0) {
        //         $multi_return_string_part = '<div data-attr="' . $category->name . '"  class="listing_detail  col-md-12 feature_block_' . $item['name'] . ' ">';
        //         $multi_return_string_part .= '<div class="feature_chapter_name  col-md-12">' . $item['name'] . '</div>';

        //         $multi_return_string_part_check = '';
        //         if (is_array($item['childs'])) {
        //             foreach ($item['childs'] as $key_ch => $child) {
        //                 $term = get_term_by('name', $child, 'property_features');

        //                 // $action_hunting_cat = get_term_meta($term->term_id, 'is_hunting', true);
        //                 // if ($action_hunting_cat != '') {

        //                 // Begin code change for amenity not displaying
        //                 $taxonomy_terms  =  get_term_meta($term->term_id, 'taxonomy_terms', true);
        //                 if (!empty($taxonomy_terms) && in_array(51,$taxonomy_terms)) {
        //                 // End code change for amenity not displaying

        //                     $temp = wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
        //                     $multi_return_string_part .= $temp;
        //                     $multi_return_string_part_check .= $temp;
        //                 }
        //             }
        //         }
        //         $multi_return_string_part .= '</div>';
        //         if ($multi_return_string_part_check != '') {
        //             $multi_return_string .= $multi_return_string_part;
        //         }
        //     } else {
        //         $term = get_term_by('name', $item['name'], 'property_features');
        //         $single_return_string .= wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
        //     }
        // } else if ($category_name == "Stay and Fish") {
          
        //     if ($feature_name == "Type of fish") {
        //         continue;
        //     }
        //     if (count($item['childs']) > 0) {
        //         $multi_return_string_part = '<div data-attr="' . $category->name . '"  class="listing_detail  col-md-12 feature_block_' . $item['name'] . ' ">';
        //         $multi_return_string_part .= '<div class="feature_chapter_name  col-md-12">' . $item['name'] . '</div>';

        //         $multi_return_string_part_check = '';
        //         if (is_array($item['childs'])) {
        //             foreach ($item['childs'] as $key_ch => $child) {
        //                 $term = get_term_by('name', $child, 'property_features');
        //                 //$action_stay_and_fish = get_term_meta($term->term_id, 'is_stay_and_fish', true);
        //                 //if ($action_stay_and_fish != '') {

        //                 // Begin code change for amenity not displaying
        //                 $taxonomy_terms  =  get_term_meta($term->term_id, 'taxonomy_terms', true);
        //                 if (!empty($taxonomy_terms) && in_array(364,$taxonomy_terms)) {                            
        //                 // End code change for amenity not displaying

        //                     $temp = wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
        //                     $multi_return_string_part .= $temp;
        //                     $multi_return_string_part_check .= $temp;
        //                 }
        //             }
        //         }
        //         $multi_return_string_part .= '</div>';

        //         if ($multi_return_string_part_check != '') {
        //             $multi_return_string .= $multi_return_string_part;
        //         }

        //     } else {
        //         $term = get_term_by('name', $item['name'], 'property_features');
        //         $single_return_string .= wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
        //     }

        // } else {              
            // End code comment for amenity filter not working            

            if (count($item['childs']) > 0) {
                $multi_return_string_part = '<div data-attr="' . esc_attr($category_name) . '"  class="listing_detail  col-md-12 feature_block_' . $item['name'] . ' ">';
                $multi_return_string_part .= '<div class="feature_chapter_name  col-md-12">' . $item['name'] . '</div>';

                $multi_return_string_part_check = '';
                if (is_array($item['childs'])) {
                    foreach ($item['childs'] as $key_ch => $child) {
                        $term = get_term_by('name', $child, 'property_features');
                        //$action_hunt_fish_cat = get_term_meta($term->term_id, 'is_hunt_fishing', true);
                        $taxonomy_terms  =  get_term_meta($term->term_id, 'taxonomy_terms', true);
                        if($category_name != '' ){
                            //if (!empty($taxonomy_terms) && in_array(2,$taxonomy_terms)) {
                            // if ($action_hunt_fish_cat != '') {
                                $temp = wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
                                $multi_return_string_part .= $temp;
                                $multi_return_string_part_check .= $temp;
                            //}
                        }
                    }
                }
                $multi_return_string_part .= '</div>';

                if ($multi_return_string_part_check != '') {
                    $multi_return_string .= $multi_return_string_part;
                }

            } else {
                $term = get_term_by('name', $item['name'], 'property_features');
                $single_return_string .= wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields);
            }
        //}
    }
}

$list_to_show = $multi_return_string;

if ($single_return_string != '') {
    $list_to_show = $list_to_show . '<div class="listing_detail col-md-12 feature_block_others "><div class="feature_chapter_name  col-md-12">' . esc_html__('Other Features', 'wprentals') . '</div>' . $single_return_string . '</div>';
}

function wpestate_display_feature_submit($edit_id, $moving_array, $term, $submission_page_fields)
{

    $post_var_name = $term->slug;
    $list_to_show = '';
    $original_term = $term;
    if (defined('ICL_SITEPRESS_VERSION') && !is_admin()) {
        $current_language = apply_filters('wpml_current_language', null);
        $default_language = apply_filters('wpml_default_language', null);

        if ($current_language != $default_language) {
            $trid = apply_filters('wpml_element_trid', null, $term->term_id, 'tax_property_features');
            $term_translations = apply_filters('wpml_get_element_translations', null, $trid, 'tax_property_features');
            $original_term_id = $term_translations[$default_language]->element_id;
            do_action('wpml_switch_language', $default_language);
            $original_term = get_term($original_term_id);
            do_action('wpml_switch_language', $current_language);
        }
    }
    $value_label = $term->name;
    $cat_ft_image = get_term_meta($term->term_id, 'category_featured_image', true);

    // Begin code change for amenity image displaying
    if (is_object($term)) {
        $t_id = $term->term_id;
        $term_meta = get_option("taxonomy_$t_id");
        $category_featured_image =
            isset($term_meta["category_featured_image"]) &&
            $term_meta["category_featured_image"]
                ? $term_meta["category_featured_image"]
                : "";
        // Only overwrite if option has a value (preserves term meta if option is empty)
        if (!empty($category_featured_image)) {
            $cat_ft_image = $category_featured_image;
        }
    }
    // End code change for amenity image displaying
    

    $saved_terms = get_term_meta($term->term_id, 'taxonomy_terms', true);

    $new_class = '';
	if (is_array($saved_terms)) {
    foreach ($saved_terms as $value) {
        $new_class .= ' wqst_' . $value;
		}
	}
    $imgtype = substr($cat_ft_image, strripos($cat_ft_image, ".") + 1, 4);
    $additionalClass = '';
    if ($imgtype == 'jpg') {
        $additionalClass = "jpgClass";
    }
    if ($imgtype == 'svg') {
        $additionalClass = "svgClass";
    }
    if ($imgtype == 'png') {
        $additionalClass = "jpgClass";
    }
    if ($imgtype == 'jpeg') {
        $additionalClass = "jpgClass";
    }
    if ($imgtype == 'webp') {
        $additionalClass = "jpgClass";
    }

    $list_to_show .= ' <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 waitem ' . $new_class . '" ><p class="' . $additionalClass . '" style="display: flex;">
                   <input type="hidden" name="' . esc_attr($post_var_name) . '" value="" style="display:block;">
                   <input type="checkbox" style="margin-top: 12px;" class="feature_list_save"  id="' . esc_attr($post_var_name) . '" name="' . esc_attr($post_var_name) . '" value="1"   data-feature="' . intval($term->term_id) . '"';

    if (has_term($post_var_name, 'property_features', $edit_id)) {
        $list_to_show .= ' checked="checked" ';
    } else {
        if (is_array($moving_array)) {
            if (in_array($post_var_name, $moving_array)) {
                $list_to_show .= ' checked="checked" ';
            }
        }
    }

    if ($cat_ft_image != "") {
        $list_to_show .= ' />

            <img src="' . $cat_ft_image . '" class="feature_list_saveimage '.$term->term_id.'">
            <label style="padding-top: 6px;" for="' . esc_attr($post_var_name) . '">' . esc_html(stripslashes($value_label)) . '</label></p></div>';

    } else {
        $list_to_show .= ' />

            <label data-id="'.$category_featured_image.'" style="padding-top: 6px;" for="' . esc_attr($post_var_name) . '">' . esc_html(stripslashes($value_label)) . '</label></p></div>';
    }
    return $list_to_show;
}
?>
<div class="col-md-12">
    <h4 class="user_dashboard_panel_title"><?php esc_html_e('Amenities and Features', 'wprentals');?></h4>
    <?php wpestate_show_mandatory_fields();?>
    <div class="col-md-12" id="profile_message"></div>
    <?php 
    if ($list_to_show != '')?>
        <div class="row">
            <div class="col-md-12 dashboard_amenities">
                <div class="col-md-12 dashboard_chapter_label">
                    <?php esc_html_e('Select the amenities and features that apply for your listing', 'wprentals');?></div>
                <div class="col-sm-12" style="display: none;">
                    <h5 class="vd-add-amenity-note"><strong>Note:</strong>If any amenity is not present in the list <button
                            class="popmake-3578 vd-add-amenity">Click Here</button> to add a new Amenity</h5>
                </div>
                <div class="col-sm-12" style="display: none">
                    <h5 class="vd-add-amenity-note"><strong>Note:</strong>If any amenity is not present in the list <button
                            class="vd-add-amenity"><?php echo do_shortcode('[elementor-template id="3750"]'); ?></button> to
                        add a new Amenity</h5>
                </div>
                <div class="col-sm-12">
                    <h5 class="vd-add-amenity-note"><strong>Note:</strong>If any amenity is not present in the list <button
                            class="new-amenity-pop-up vd-add-new_amenity">Click Here</button> to add a new Amenity</h5>
                </div>
                <div class="col-12">
                    <?php
                    $taxonomy = 'property_action_category'; // Specify the taxonomy you want to target
                    $terms = get_terms($taxonomy);
                    $saved_term_id = '';
                    
                    $prop_action_category_array     =   get_the_terms($edit_id, 'property_action_category');

                    if (isset($term->term_id)) {
                        $saved_terms = get_term_meta($term->term_id, 'taxonomy_terms', true);                        
                        $saved_terms = array();
                        if (!empty($saved_terms)) {
                            // Begin code comment for amenity filter not working  

                           // $saved_term_id = is_string($saved_terms) ? (array) $saved_terms : $saved_terms; // Convert $saved_terms to an array if it's a string
                           // End code comment for amenity filter not working  
                        } else {                            
                            $saved_term_id = array(); // Set to an empty array if $saved_terms is empty                            
                            // Begin code change for amenity filter not working
                            if (is_array($prop_action_category_array)) {
                                foreach ($prop_action_category_array as $key => $value) {
                                    $saved_term_id[] = $value->term_id;
                                }
                            }
                            // End code change for amenity filter not working
                        }

                        foreach ($terms as $term) {

                            $checked = in_array($term->term_id, $saved_term_id) ? 'checked' : '';
                            echo '<label>';
                            echo '<input type="checkbox" class="filter_amenities" name="taxonomy_terms[]" value="' . $term->term_id . '" ' . $checked . '>';
                            echo $term->name;
                            echo '</label><br>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-12 dashboard_amenities">
                <div class="col-md-3 dashboard_chapter_label" style="display: none;">
                    <?php esc_html_e('Select the amenities and features that apply for your listing', 'wprentals');?></div>
                <div class="col-md-3">

                </div>
                <div class="col-md-9">
                    <?php print trim($list_to_show); //escaped above?>
                </div>
            </div>
        </div>
    <div class="col-md-12" style="display: inline-block;">
        <input type="hidden" name="" id="listing_edit" value="<?php print intval($edit_id);?>">
        <input type="submit" class="wpb_btn-info wpb_btn-small wpestate_vc_button  vc_button" id="edit_prop_ammenities"
            value="<?php esc_html_e('Save', 'wprentals')?>" />
        <a href="<?php echo esc_url($edit_link_calendar); ?>"
            class="next_submit_page"><?php esc_html_e('Go to Calendar settings.', 'wprentals');?></a>
        <?php
        $ajax_nonce = wp_create_nonce("wprentals_amm_features_nonce");
        print '<input type="hidden" id="wprentals_amm_features_nonce" value="' . esc_html($ajax_nonce) . '" />    ';
        ?>
    </div>
</div>