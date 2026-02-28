<?php
/**
 * Listing block helper functions.
 *
 * @package WPRentals\API\Listings
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('wprentals_property_show_overview_section_function')) {
    /**
     * Render the overview section for a property in Elementor widgets.
     *
     * @param array $attributes Block/widget attributes.
     * @param array $settings   Elementor widget settings.
     * @param int   $property_id Optional property ID.
     *
     * @return string
     */
    function wprentals_property_show_overview_section_function($attributes, $settings, $property_id = 0)
    {
        if (!$property_id && isset($attributes['property_id'])) {
            $property_id = intval($attributes['property_id']);
        }

        if (!$property_id && function_exists('wpestate_return_property_id_elementor_builder')) {
            $property_id = wpestate_return_property_id_elementor_builder($attributes);
        }

        if (!$property_id) {
            $property_id = get_the_ID();
        }

        if (!$property_id) {
            return '';
        }

        $default_svg = [
            'guest_no'              => 'users.svg',
            'property_rooms'        => 'living.svg',
            'property_bedrooms'     => 'bed.svg',
            'property_bathrooms'    => 'house.svg',
            'property_size'         => 'building.svg',
            'property_lot_size'     => 'location_area.svg',
            'property_address'      => 'location.svg',
            'property_city'         => 'location_city.svg',
            'property_area'         => 'location_area.svg',
            'property_state'        => 'location_state.svg',
            'property_county_state' => 'location_state.svg',
            'property_country'      => 'location_country.svg',
        ];

        $wpestate_currency = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $where_currency    = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $measure_sys       = esc_html(wprentals_get_option('wp_estate_measure_sys', ''));

        $section_title = !empty($settings['section_title']) ? $settings['section_title'] : esc_html__('Overview', 'wprentals-core');

        ob_start();
        ?>
        <div class="single-overview-section panel-wrapper panel-group property-panel">
            <h4 class="panel-title-description"><?php echo esc_html($section_title); ?></h4>

            <ul class="overview_element overview_updatd_on">
                <li class="first_overview"><?php esc_html_e('Updated On:', 'wprentals-core'); ?></li>
                <li class="first_overview_date"><?php echo esc_html(get_the_modified_date('F j, Y', $property_id)); ?></li>
            </ul>

            <?php
            if (!empty($settings['overview_fields']) && is_array($settings['overview_fields'])) {
                foreach ($settings['overview_fields'] as $item) {
                    $field_key = isset($item['field_type']) ? sanitize_key($item['field_type']) : '';

                    if ('' === $field_key) {
                        continue;
                    }

                    $display_value       = '';
                    $countable_value     = null;
                    $allow_pluralization = false;

                    switch ($field_key) {
                        case 'guest_no':
                        case 'property_rooms':
                        case 'property_bedrooms':
                        case 'property_bathrooms':
                        case 'min_days_booking':
                            $meta_value = get_post_meta($property_id, $field_key, true);

                            if ($meta_value !== '' && is_numeric($meta_value)) {
                                $countable_value     = floatval($meta_value);
                                $allow_pluralization = true;

                                if ($countable_value > 0) {
                                    $display_value = number_format_i18n($countable_value);
                                } else {
                                    $countable_value = null;
                                }
                            }
                            break;

                        case 'property_size':
                        case 'property_lot_size':
                            $meta_value = get_post_meta($property_id, $field_key, true);

                            if ($meta_value !== '' && is_numeric($meta_value)) {
                                $size_value = floatval($meta_value);

                                if ($size_value > 0) {
                                    $formatted_size = wprentals_custom_number_format($size_value, 2);
                                    $display_value  = $formatted_size . ' ' . esc_html($measure_sys) . '<sup>2</sup>';
                                }
                            }
                            break;

                        case 'property_price':
                            if (function_exists('wpestate_show_price')) {
                                $display_value = wpestate_show_price($property_id, $wpestate_currency, $where_currency, 1);
                            }
                            break;

                        case 'property_id':
                            $countable_value     = floatval($property_id);
                            $allow_pluralization = false;
                            $display_value       = number_format_i18n($countable_value);
                            break;

                        case 'property_internal_id':
                        case 'property_address':
                        case 'property_zip':
                        case 'property_state':
                        case 'property_country':
                            $meta_value = get_post_meta($property_id, $field_key, true);

                            if ($meta_value !== '') {
                                $display_value = trim($meta_value);
                            }
                            break;

                        case 'property_city':
                        case 'property_area':
                        case 'property_county_state':
                        case 'property_category':
                        case 'property_action_category':
                            $terms = get_the_term_list($property_id, $field_key, '', ', ', '');

                            if (!is_wp_error($terms) && !empty($terms)) {
                                $display_value = wp_strip_all_tags($terms);
                            }
                            break;

                        case 'property_status':
                            if (function_exists('wpestate_return_property_status')) {
                                $status = wpestate_return_property_status($property_id, 'pin');

                                if (!empty($status) && !is_wp_error($status)) {
                                    $display_value = $status;
                                }
                            }
                            break;

                        default:
                            $meta_value = get_post_meta($property_id, $field_key, true);

                            if ($meta_value !== '') {
                                if (is_array($meta_value)) {
                                    $meta_value = array_filter($meta_value);
                                    $display_value = implode(', ', array_map('trim', $meta_value));
                                } else {
                                    $display_value = trim($meta_value);
                                }
                            } elseif (taxonomy_exists($field_key)) {
                                $terms = get_the_term_list($property_id, $field_key, '', ', ', '');

                                if (!is_wp_error($terms) && !empty($terms)) {
                                    $display_value = wp_strip_all_tags($terms);
                                }
                            }
                            break;
                    }

                    if ($display_value instanceof \WP_Error) {
                        continue;
                    }

                    if ('' === $display_value) {
                        continue;
                    }

                    $label_single = isset($item['label_singular']) ? $item['label_singular'] : '';
                    $label_plural = isset($item['label_plural']) ? $item['label_plural'] : '';

                    $label = $label_single;

                    if (null !== $countable_value && $allow_pluralization) {
                        $label = ($countable_value == 1 || '' === $label_plural) ? $label_single : $label_plural;
                    } elseif ('' !== $label_plural && '' === $label) {
                        $label = $label_plural;
                    }

              
                    ?>
                    <ul class="overview_element">
                        <?php if (isset($item['icon_type']) && 'none' !== $item['icon_type']) : ?>
                            <li class="first_overview">
                                <?php
                                if ('theme_options' === $item['icon_type'] && isset($default_svg[$field_key]) && function_exists('wpestate_return_svg_icon')) {
                                    echo wpestate_return_svg_icon($default_svg[$field_key]);
                                } elseif ('custom' === $item['icon_type'] && !empty($item['meta_icon'])) {
                                    if (isset($item['meta_icon']['library']) && 'svg' === $item['meta_icon']['library'] && isset($item['meta_icon']['value']['url'])) {
                                      
                                       if($item['meta_icon']['library']=='svg'){
                      
                                            $svg_url  = $item['meta_icon']['value']['url'] ?? '';
                                            $svg_code = '';

                                            if ($svg_url && str_ends_with($svg_url, '.svg')) {
                                                // Convert URL to local filesystem path
                                                $upload_dir = wp_get_upload_dir();
                                                if (strpos($svg_url, $upload_dir['baseurl']) === 0) {
                                                    $local_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $svg_url);
                                                    if (file_exists($local_path)) {
                                                        $svg_code = file_get_contents($local_path);
                                                    }
                                                }
                                            }

                                            if ($svg_code) {
                                                echo $svg_code; // inline SVG markup
                                            }

                                       }else{
                                            echo '<img src="' . esc_url($item['meta_icon']['value']['url']) . '" alt="' . esc_attr($field_key) . '">';
                                       }
                                      
                        
                                    } elseif (isset($item['meta_icon']['value'])) {
                                        echo '<i class="' . esc_attr($item['meta_icon']['value']) . '"></i>';
                                    }
                                }
                                ?>
                            </li>
                        <?php endif; ?>
                        <li>
                            <?php
                            echo wp_kses_post($display_value);

                            if (!empty($label)) {
                                echo ' ' . esc_html($label);
                            }
                            ?>
                        </li>
                    </ul>
                    <?php
                }
            }
            ?>
        </div>
        <?php

        return ob_get_clean();
    }
}
