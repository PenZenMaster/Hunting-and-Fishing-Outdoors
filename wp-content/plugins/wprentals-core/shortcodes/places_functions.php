<?php

/*
 * Display PLaces/Categories function as Tabs
 *
 *
 */

if ( ! function_exists( 'wprentals_get_taxonomy_term_meta' ) ) {
    function wprentals_get_taxonomy_term_meta( $term_id ) {
        $term_id = intval( $term_id );

        if ( ! $term_id ) {
            return array();
        }

        $meta = get_option( 'taxonomy_' . $term_id );
        $meta = is_array( $meta ) ? $meta : array();

        $term_meta_keys = array(
            'category_featured_image',
            'category_attach_id',
            'category_tax',
            'category_tagline',
        );

        foreach ( $term_meta_keys as $key ) {
            $value = get_term_meta( $term_id, $key, true );

            if ( '' !== $value && null !== $value ) {
                $meta[ $key ] = $value;
            }
        }

        if ( isset( $meta['category_attach_id'] ) ) {
            $meta['category_attach_id'] = intval( $meta['category_attach_id'] );

            if ( ! $meta['category_attach_id'] ) {
                unset( $meta['category_attach_id'] );
            }
        }

        return $meta;
    }
}

if (!function_exists('wpestate_places_list_functionas_tabs')):

    function wpestate_places_list_functionas_tabs($attributes, $content = null) {



        $attributes = shortcode_atts(
                array(
                    'form_fields' => '',
                    'place_per_row' => 4,
                    'show_zero_terms' => true
                ), $attributes);

        if (isset($attributes['place_per_row'])) {
            $row_number = $attributes['place_per_row'];
        }




        // max 4 per row
        if ($row_number > 6) {
            $row_number = 6;
        }

        if ($row_number == 6) {
            $row_number_col = 2; // col value is 3
        } else if ($row_number == 4) {
            $row_number_col = 3; // col value is 3
        } else if ($row_number == 3) {
            $row_number_col = 4; // col value is 4
        } else if ($row_number == 2) {
            $row_number_col = 6; // col value is 6
        } else if ($row_number == 1) {
            $row_number_col = 12; // col value is 12
            if (isset($attributes['align']) && $attributes['align'] == 'vertical') {
                $row_number_col = 0;
            }
        }




        $all_places_array = $attributes['form_fields'];
        $tab_items = '<ul class="nav nav-tabs wpestate_categories_as_tabs_ul" role="tablist">';
        $tab_content = '<div class="tab-content">';
        $return_string = '<div role="tabpanel" class="wpestate_categories_as_tabs_wrapper" >';
        $class_active = 'active';

        if (is_array($all_places_array)):
            foreach ($all_places_array as $key => $place_tax) {
                $tab_items .= '<li role="presentation" class="wpestate_categories_as_tabs_item ' . esc_attr($class_active) . '" >';
                $item_icon = '';
                if (isset($place_tax['icon']) && $place_tax['icon'] != '') {
                    ob_start();
                    \Elementor\Icons_Manager::render_icon($place_tax['icon'], ['aria-hidden' => 'true']);
                    $item_icon = ob_get_contents();
                    ob_end_clean();
                }

                $tab_items .= '<a href="#wpestate_cat_as_tabs_' . sanitize_title(trim($place_tax['field_type'])) . '" role="tab" data-toggle="tab">';

                $tab_items .= $item_icon;
                $tab_items .= esc_html($place_tax['field_label']) . '</a>';

                $tab_items .= '</li>';

                $tab_content .= '<div role="tabpanel" class=" wpestate_categories_as_tabs_panel tab-pane ' . esc_attr($class_active) . '" id="wpestate_cat_as_tabs_' . sanitize_title($place_tax['field_type']) . '">
            ' . wpestate_show_tax_items($place_tax['field_type'], $row_number_col, $attributes['show_zero_terms']) . '
            </div>';
                $class_active = '';
            }
        endif;

        $tab_items .= '</ul>';
        $tab_content .= '</div>';

        $return_string .= $tab_items . $tab_content . '</div>';

        return $return_string;
    }

endif;

/*
 * Display PLaces/Categories function as Tabs
 *
 *
 */


if (!function_exists('wpestate_show_tax_items')):

    function wpestate_show_tax_items($taxonomy, $row_number_col = "4", $show_zero = true) {
        $return_string = '';

        $terms = get_terms(array(
            'taxonomy' => trim($taxonomy),
            'hide_empty' => $show_zero,
                ));

        if (!is_wp_error($terms)) {

            foreach ($terms as $term) {
                $return_string .= '<div class="col-md-' . esc_attr($row_number_col) . '">'
                        . '<a class="wpestate_categories_as_tabs_term" href="' . esc_url(get_term_link($term)) . '">' . esc_attr($term->name) . '</a> '
                        . '<span class="places_list_tab_term-count">' . $term->count . ' ' . esc_html__('properties', 'wprentals-core') . '</span></div>';
            }
        }
        return $return_string;
    }

endif;

/*
 *
 *
 * Featured places
 *
 *
 */

if (!function_exists('wpestate_featured_place')):

    function wpestate_featured_place($attributes, $content = null) {
        $place_id = '';
        $return_string = '';
        $extra_class_name = '';
        $type_class = '';
        $places_label = '';
        $category_count = '';

        $attributes = shortcode_atts(
                array(
                    'id' => 0,
                    'places_label' => '',
                    'type' => "type1",
                    'places_height' => '',
                ), $attributes);

        if (isset($attributes['id'])) {
            $place_id = $attributes['id'];
        }


        if (isset($attributes['type']) && $attributes['type'] == 'type1') {
            $type_class = ' type_1_class ';
        }

        if (isset($attributes['type']) && $attributes['type'] == 'type3') {
            $type_class = ' type_3_class ';
        }

        if (isset($attributes['places_label'])) {
            $places_label = $attributes['places_label'];
        }

        $places_style = "";
        if (isset($attributes['places_height'])) {
            $places_height = $attributes['places_height'];
            if (is_array($places_height)) {
                $places_height = $attributes['places_height']['size'];
            }
            if ($places_height != '') {
                $places_style = "height:" . $places_height . "px;";
            }
        }





        if (isset($attributes['extra_class_name'])) {
            $extra_class_name = $attributes['extra_class_name'];
        }

        $place_id = intval($place_id);
        $category_attach_id = '';
        $category_tax = '';
        $category_featured_image = '';
        $meta_featured_image = '';
        $category_name = '';
        $category_featured_image_url = '';
        $term_meta = wprentals_get_taxonomy_term_meta( $place_id );
        $category_tagline = '';

        if (isset($term_meta['category_featured_image'])) {
            $category_featured_image = $term_meta['category_featured_image'];

            if (!is_array($category_featured_image)) {
                $meta_featured_image = $category_featured_image;
            }
        }

        if (isset($term_meta['category_attach_id'])) {
            $category_attach_id = $term_meta['category_attach_id'];
            $category_featured_image = wp_get_attachment_image_src($category_attach_id, 'wpestate_property_featured');
            $category_featured_image_url ='';
            if( isset($category_featured_image[0]) ){
                $category_featured_image_url = $category_featured_image[0];
            }
        }

        if ($category_featured_image_url == '' && $meta_featured_image != '') {
            $category_featured_image_url = $meta_featured_image;
        }
        $term = get_term($place_id);
        if (isset($term->name)) {
            $category_name = $term->name;
            $category_count = $term->count;
        }


        if (isset($term_meta['category_tax'])) {
            $category_tax = $term_meta['category_tax'];
           
        }

        if (isset($term_meta['category_tagline'])) {
            $category_tagline = $term_meta['category_tagline'];
        }

        $term_link = get_term_link($place_id, $category_tax);
        if (is_wp_error($term_link)) {
            $term_link = '';
        }


        if ($category_featured_image_url == '') {
            $category_featured_image_url = get_template_directory_uri() . '/img/defaultimage.jpg';
        }

        $return_string .= '<div class="places_wrapper ' . $extra_class_name . ' ' . $type_class . ' "   data-link="' . $term_link . '"><div class="listing-hover-gradient"></div><div class="listing-hover"></div>';
        $return_string .= '<div class="places1 featuredplace" style="background-image:url(' . $category_featured_image_url . ');' . $places_style . '"></div>';

        $return_string .= '<div class="category_name">';
        if (isset($attributes['type']) && $attributes['type'] == 'type1') {
            $return_string .= '<div class="featured_place_count">' . $category_count . ' ' . __('Listings', 'wprentals-core') . '</div>';
        }
        $return_string .= '<a class="featured_listing_title" href="' . $term_link . '">' . stripslashes($category_name) . '</a>';

        $return_string .= '<div class="category_tagline">' . stripslashes($category_tagline) . '</div>';

        if (isset($attributes['type']) && $attributes['type'] == 'type3') {
            $return_string .= '<div class="featured_place_count">' . $category_count . ' ' . __('Listings', 'wprentals-core') . '</div>';
            $return_string .= '<div class="featured_more"><a href="' . $term_link . '">' . __('Discover', 'wprentals-core') . '</a> <i class="fas fa-chevron-right"></i></div>';
        }

        $return_string .= '</div>';

        if (isset($attributes['type']) && $attributes['type'] == 'type3') {
            $return_string .= '<div class="places_label">' . $places_label . ' </div>';
        }

        $return_string .= '';

        $return_string .= '</div>';

        return $return_string;
    }

endif; // end   wpestate_featured_agent


/*
 *
 *
 * Featured places list
 *
 *
 */

if (!function_exists('wpestate_places_list_function')):

    function wpestate_places_list_function($attributes, $content = null) {
        $place_list = '';
        $return_string = '';
        $extra_class_name = '';
        $spaces_unit = '';

        $attributes = shortcode_atts(
                array(
                    'place_list' => '',
                    'place_per_row' => 4,
                    'spaces_unit' => 8,
                    'extra_class_name' => '',
                    'design_type' => 'type1',
                    'display_grid'=>'no'
                ), $attributes);

        if (isset($attributes['place_list'])) {
            $place_list = $attributes['place_list'];
        }

        if (isset($attributes['place_per_row'])) {
            $place_per_row = $attributes['place_per_row'];
        }

        if (isset($attributes['spaces_unit'])) {
            $spaces_unit = intval($attributes['spaces_unit']);
        }

        if (isset($attributes['display_grid'])) {
            $display_grid = ($attributes['display_grid']);
        }


        if ($place_per_row > 5) {
            $place_per_row = 5;
        }

        if (isset($attributes['extra_class_name'])) {
            $extra_class_name = $attributes['extra_class_name'];
        }

        $all_places_array = explode(',', $place_list);

     

        if ($display_grid == 'yes') {
            print '<div class="items_shortcode_wrapper_category_grid"> ';
            $place_per_row = '_grid';
        }else{
            print '<div class="items_shortcode_wrapper_category"> ';
        }


        foreach ($all_places_array as $place_id) {

            $place_id = intval($place_id);
            if ($place_id != 0 || $place_id != '') {
                $category_attach_id = '';
                $category_tax = '';
                $category_featured_image = '';
                $meta_featured_image = '';
                $category_name = '';
                $category_featured_image_url = '';
                $term_meta = wprentals_get_taxonomy_term_meta( $place_id );
                $category_tagline = '';

                if (isset($term_meta['category_featured_image'])) {
                    $category_featured_image = $term_meta['category_featured_image'];

                    if (!is_array($category_featured_image)) {
                        $meta_featured_image = $category_featured_image;
                    }
                }

                if (isset($term_meta['category_attach_id'])) {
                    $category_attach_id = $term_meta['category_attach_id'];
                    if ($attributes['design_type'] == 'type1') {
                        $category_featured_image = wp_get_attachment_image_src($category_attach_id, 'wpestate_property_places');
                    } else {
                        $category_featured_image = wp_get_attachment_image_src($category_attach_id, 'wpestate_property_listings');
                    }
                    $category_featured_image_url='';
                    if(isset( $category_featured_image[0])){
                        $category_featured_image_url = $category_featured_image[0];
                    }
                }

                if ($category_featured_image_url == '' && $meta_featured_image != '') {
                    $category_featured_image_url = $meta_featured_image;
                }
                $category_count = 0;
                if (isset($term_meta['category_tax'])) {
                    $category_tax = $term_meta['category_tax'];
                    $term = get_term($place_id, $category_tax);
                    $category_name = '';
                    if (isset($term->name)) {
                        $category_name = $term->name;
                    }
                    $category_count = '';
                    if (isset($term->count)) {
                        $category_count = $term->count;
                    }
                }

                if (isset($term_meta['category_tagline'])) {
                    $category_tagline = stripslashes($term_meta['category_tagline']);
                }

                $term_link = get_term_link($place_id, $category_tax);
                if (is_wp_error($term_link)) {
                    $term_link = '';
                }


                if ($category_featured_image_url == '') {
                    $category_featured_image_url = get_template_directory_uri() . '/img/defaultimage.jpg';
                }


                if (intval($spaces_unit) != 0) {
                    $extra_class_name .= ' places_wrapper_no_shadow ';
                }


                include(locate_template('templates/property_categories_templates/property-category-unit-' . $attributes['design_type'] . '.php'));
            }
        }

       
        print '</div>';
        return $return_string;
    }


endif;
