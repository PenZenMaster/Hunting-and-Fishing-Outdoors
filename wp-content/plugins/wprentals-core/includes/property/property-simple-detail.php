<?php
/**
 * Helper functions used by the Elementor simple detail widget.
 *
 * @package WPRentals Core
 */

if ( ! function_exists( 'wprentals_estate_property_simple_detail' ) ) :
    /**
     * Render a single property detail value.
     *
     * @param int         $propid     Property ID.
     * @param array       $attributes Attribute list received from the widget.
     * @param string|null $content    Optional content (unused).
     *
     * @return string
     */
    function wprentals_estate_property_simple_detail( $propid, $attributes, $content = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
        $defaults = array(
            'detail'       => 'none',
            'label'        => esc_html__( 'Label:', 'wprentals-core' ),
            'is_elementor' => '',
            'label_inline' => '',
        );

        if ( ! is_array( $attributes ) ) {
            $attributes = array();
        }

        $attributes = wp_parse_args( $attributes, $defaults );

        $detail       = $attributes['detail'];
        $label        = $attributes['label'];
        $label_inline = 'yes' === $attributes['label_inline'];

        if ( empty( $propid ) ) {
            return '';
        }

        $features_details = array();
        $feature_terms    = wpestate_get_cached_terms( 'property_features' );

        if ( is_array( $feature_terms ) ) {
            foreach ( $feature_terms as $term ) {
                $features_details[ $term->slug ] = $term->name;
            }
        }

        if ( isset( $features_details[ $detail ] ) ) {
            $detail_value = has_term( $detail, 'property_features', $propid )
                ? esc_html__( 'Yes', 'wprentals-core' )
                : esc_html__( 'No', 'wprentals-core' );
        } else {
            $detail_value = wprentals_estate_property_simple_detail_switch( $propid, $detail );
        }

        if ( '' === trim( (string) $detail_value ) ) {
            return '';
        }

        $wrapper_classes = array( 'property_custom_detail_wrapper' );

        if ( $label_inline ) {
            $wrapper_classes[] = 'property_custom_detail_wrapper--inline';
            $detail_value     = wprentals_estate_property_simple_detail_inline_value( $detail_value );
        }

        $wrapper_class_attr = esc_attr( implode( ' ', array_unique( array_filter( $wrapper_classes ) ) ) );
        $value_tag          = $label_inline ? 'span' : 'div';
        $label_output       = esc_html( $label );

        if ( '' !== $label_output ) {
            $label_output .= ' ';
        }

        return sprintf(
            '<div class="%1$s"><span class="property_custom_detail_label">%2$s</span><%3$s class="property_custom_detail_value">%4$s</%3$s></div>',
            $wrapper_class_attr,
            $label_output,
            $value_tag,
            $detail_value
        );
    }
endif;

if ( ! function_exists( 'wprentals_estate_property_simple_detail_switch' ) ) :
    /**
     * Resolve the requested detail into a formatted string.
     *
     * @param int    $propid Property ID.
     * @param string $detail Detail key.
     *
     * @return string
     */
    function wprentals_estate_property_simple_detail_switch( $propid, $detail ) {
        $wpestate_currency       = esc_html( wprentals_get_option( 'wp_estate_currency_label_main', '' ) );
        $wpestate_where_currency = esc_html( wprentals_get_option( 'wp_estate_where_currency_symbol', '' ) );

        switch ( $detail ) {
            case 'none':
                return '';
            case 'title':
                return get_the_title( $propid );
            case 'property_agent':
                $agent_id = intval( get_post_meta( $propid, 'property_agent', true ) );

                if ( $agent_id ) {
                    $owner_id = get_post_meta($agent_id, 'user_agent_id', true);
                    if ( ! empty( $owner_id ) ) {
                        $owner_title = get_the_title( $owner_id );
                        return esc_html( $owner_title );
                    }

                    $user_info = get_userdata( $agent_id );
                    if ( $user_info ) {
                        return esc_html( $user_info->display_name );
                    }
                }
           


                               
                
                
                
                
            case 'property_price_per_week':
                $meta_value = get_post_meta( $propid, 'property_price_per_week', true );
                        return wpestate_show_price_booking($meta_value, $wpestate_currency, $wpestate_where_currency, 1);
            case 'property_price_per_month':
                $meta_value = get_post_meta( $propid, 'property_price_per_month', true );
            return wpestate_show_price_booking($meta_value, $wpestate_currency, $wpestate_where_currency, 1);
            case 'price_per_weekeend':
                $meta_value = get_post_meta( $propid, 'price_per_weekeend', true );
                return wpestate_show_price_booking($meta_value, $wpestate_currency, $wpestate_where_currency, 1);
            case 'security_deposit':
                $meta_value = get_post_meta( $propid, 'security_deposit', true );
            return wpestate_show_price_booking($meta_value, $wpestate_currency, $wpestate_where_currency, 1);
            case 'extra_price_per_guest':
                $meta_value = get_post_meta( $propid, 'extra_price_per_guest', true );
            return wpestate_show_price_booking($meta_value, $wpestate_currency, $wpestate_where_currency, 1);
            case 'cleaning_fee':
                $meta_value = get_post_meta( $propid, 'cleaning_fee', true );

             return wpestate_show_price_booking($meta_value, $wpestate_currency, $wpestate_where_currency, 1);
            case 'city_fee':
                $meta_value = get_post_meta( $propid, 'city_fee', true );
                return wpestate_show_price_booking($meta_value, $wpestate_currency, $wpestate_where_currency, 1);



            case 'property_price':
                return wpestate_show_price( $propid, $wpestate_currency, $wpestate_where_currency, 1 );
            case 'description':
                return apply_filters( 'the_content', get_post_field( 'post_content', $propid ) );
            case 'property_size':
            case 'property_lot_size':
                $value = floatval( get_post_meta( $propid, $detail, true ) );
                if ( $value ) {
                    $measure_sys = esc_html( wprentals_get_option( 'wp_estate_measure_sys', '' ) );
                    $formatted   = wprentals_custom_number_format( $value, 2 );
                    $suffix      = $measure_sys ? sprintf( '%s<sup>2</sup>', esc_html( $measure_sys ) ) : '';

                    return trim( sprintf( '%s %s', $formatted, $suffix ) );
                }
                return '';
            case 'property_category':
            case 'property_action_category':
            case 'property_city':
            case 'property_area':
                return get_the_term_list( $propid, $detail, '', ', ', '' );
            case 'property_status':
                $term_list = get_the_term_list( $propid, 'property_status', '', ', ', '' );

                    return $term_list;
               

           
            case 'property_video':
                return wprentals_estate_property_simple_detail_video( $propid );
            case 'virtual_tour':
                return wp_kses_post( get_post_meta( $propid, 'virtual_tour', true ) );
            case 'prop_featured':
            case 'instant_booking':
            case 'wp_estate_replace_booking_form_local':
            case 'overload_guest':
            case 'price_per_guest_from_one':
                return wprentals_estate_property_simple_detail_yes_no( get_post_meta( $propid, $detail, true ) );
            case 'smoking_allowed':
            case 'party_allowed':
            case 'pets_allowed':
            case 'children_allowed':
                return wprentals_estate_property_simple_detail_yes_no( get_post_meta( $propid, $detail, true ), true );
            case 'local_booking_type':
                $booking_type_value = intval( get_post_meta( $propid, 'local_booking_type', true ) );
                $booking_options    = array(
                    1 => esc_html__( 'Per Day/Night', 'wprentals-core' ),
                    2 => esc_html__( 'Per Hour', 'wprentals-core' ),
                );

                return $booking_options[ $booking_type_value ] ?? '';
            case 'cleaning_fee_per_day':
            case 'city_fee_per_day':
                return wprentals_estate_property_simple_detail_fee_label( $propid, $detail );
            case 'checkin_change_over':
            case 'checkin_checkout_change_over':
                $week_days  = wprentals_estate_property_simple_detail_week_days();
                $meta_value = (string) get_post_meta( $propid, $detail, true );

                return $week_days[ $meta_value ] ?? '';
            case 'property_country':
                $country_value = get_post_meta( $propid, 'property_country', true );
                $countries     = wpestate_country_list_only_array();

                if ( isset( $countries[ $country_value ] ) ) {
                    return $countries[ $country_value ];
                }

                return ! empty( $country_value ) ? esc_html( $country_value ) : '';
            default:
                if ( '' === $detail ) {
                    return '';
                }

                $meta_value = get_post_meta( $propid, $detail, true );

                if ( is_array( $meta_value ) ) {
                    $meta_value = implode( ', ', array_filter( array_map( 'trim', $meta_value ) ) );
                }

                if ( '' === $meta_value ) {
                    return '';
                }

                $meta_value = apply_filters( 'wpml_translate_single_string', $meta_value, 'wprentals-core', 'wp_estate_property_custom_' . $meta_value );

                if ( $meta_value === esc_html__( 'Not Available', 'wprentals-core' ) ) {
                    return '';
                }

                return wp_kses_post( $meta_value );
        }
    }
endif;

if ( ! function_exists( 'wprentals_estate_property_simple_detail_video' ) ) :
    /**
     * Build video output for property detail.
     *
     * @param int $propid Property ID.
     *
     * @return string
     */
    function wprentals_estate_property_simple_detail_video( $propid ) {
        $video_id      = esc_html( get_post_meta( $propid, 'embed_video_id', true ) );
        $video_type    = esc_html( get_post_meta( $propid, 'embed_video_type', true ) );
        $custom_video  = get_post_meta( $propid, 'property_custom_video', true );
        $virtual_tour  = get_post_meta( $propid, 'virtual_tour', true );
        $return_string = '';

        if ( ! empty( $custom_video ) ) {
            $return_string = wp_kses_post( $custom_video );
        } elseif ( ! empty( $video_id ) ) {
            if ( 'vimeo' === $video_type && function_exists( 'wpestate_custom_vimdeo_video' ) ) {
                $return_string = wpestate_custom_vimdeo_video( $video_id );
            } elseif ( function_exists( 'wpestate_custom_youtube_video' ) ) {
                $return_string = wpestate_custom_youtube_video( $video_id );
            }
        } elseif ( ! empty( $virtual_tour ) ) {
            $return_string = wp_kses_post( $virtual_tour );
        }

        return $return_string;
    }
endif;

if ( ! function_exists( 'wprentals_estate_property_simple_detail_fee_label' ) ) :
    /**
     * Map fee selection values to readable labels.
     *
     * @param int    $propid   Property ID.
     * @param string $meta_key Meta key.
     *
     * @return string
     */
    function wprentals_estate_property_simple_detail_fee_label( $propid, $meta_key ) {
        $rental_type  = wprentals_get_option( 'wp_estate_item_rental_type' );
        $booking_type = wprentals_return_booking_type( $propid );

        $options = array(
            0 => esc_html__( 'Single Fee', 'wprentals-core' ),
            1 => ucfirst( wpestate_show_labels( 'per_night', $rental_type, $booking_type ) ),
            2 => esc_html__( 'Per Guest', 'wprentals-core' ),
            3 => ucfirst( wpestate_show_labels( 'per_night', $rental_type, $booking_type ) ) . ' ' . esc_html__( 'per Guest', 'wprentals-core' ),
        );

        $meta_value = get_post_meta( $propid, $meta_key, true );
        $meta_value = ( '' === $meta_value ) ? '' : intval( $meta_value );

        return isset( $options[ $meta_value ] ) ? $options[ $meta_value ] : '';
    }
endif;

if ( ! function_exists( 'wprentals_estate_property_simple_detail_week_days' ) ) :
    /**
     * Return translated week day labels.
     *
     * @return array
     */
    function wprentals_estate_property_simple_detail_week_days() {
        return array(
            '0' => esc_html__( 'All', 'wprentals-core' ),
            '1' => esc_html__( 'Monday', 'wprentals-core' ),
            '2' => esc_html__( 'Tuesday', 'wprentals-core' ),
            '3' => esc_html__( 'Wednesday', 'wprentals-core' ),
            '4' => esc_html__( 'Thursday', 'wprentals-core' ),
            '5' => esc_html__( 'Friday', 'wprentals-core' ),
            '6' => esc_html__( 'Saturday', 'wprentals-core' ),
            '7' => esc_html__( 'Sunday', 'wprentals-core' ),
        );
    }
endif;

if ( ! function_exists( 'wprentals_estate_property_simple_detail_yes_no' ) ) :
    /**
     * Convert a yes/no style meta value into human readable text.
     *
     * @param mixed $value             Raw meta value.
     * @param bool  $allow_passthrough Allow non yes/no values to pass through.
     *
     * @return string
     */
    function wprentals_estate_property_simple_detail_yes_no( $value, $allow_passthrough = false ) {
        if ( is_array( $value ) ) {
            $value = reset( $value );
        }

        $value = strtolower( (string) $value );

        if ( in_array( $value, array( '1', 'yes', 'on', 'true' ), true ) ) {
            return esc_html__( 'Yes', 'wprentals-core' );
        }

        if ( in_array( $value, array( '0', 'no', 'off', 'false' ), true ) ) {
            return esc_html__( 'No', 'wprentals-core' );
        }

        return $allow_passthrough ? esc_html( $value ) : '';
    }
endif;

if ( ! function_exists( 'wprentals_estate_property_simple_detail_inline_value' ) ) :
    /**
     * Normalize detail values for inline label display.
     *
     * @param string $value Raw detail value.
     *
     * @return string
     */
    function wprentals_estate_property_simple_detail_inline_value( $value ) {
        if ( '' === $value || ! is_string( $value ) ) {
            return $value;
        }

        $block_tag_pattern = '#<\s*(p|div|br|ul|ol|li|table|thead|tbody|tfoot|tr|td|th|blockquote|section|article|h[1-6])[^>]*>#i';

        if ( ! preg_match( $block_tag_pattern, $value ) ) {
            return $value;
        }

        $value = preg_replace( '#<\s*br\s*/?>#i', ' ', $value );
        $value = preg_replace( '#</?p[^>]*>#i', ' ', $value );
        $value = preg_replace( '#</?(div|ul|ol|li|table|thead|tbody|tfoot|tr|td|th|blockquote|section|article|h[1-6])[^>]*>#i', ' ', $value );
        $value = wp_strip_all_tags( $value );
        $value = preg_replace( '/\s+/u', ' ', $value );

        return esc_html( trim( $value ) );
    }
endif;

if ( ! function_exists( 'wprentals_estate_property_simple_detail_fallback_id' ) ) :
    /**
     * Retrieve a fallback property ID for builder previews.
     *
     * @return int
     */
    function wprentals_estate_property_simple_detail_fallback_id() {
        $latest_property = get_posts(
            array(
                'post_type'   => 'estate_property',
                'post_status' => 'publish',
                'numberposts' => 1,
                'orderby'     => 'ID',
                'order'       => 'DESC',
                'fields'      => 'ids',
            )
        );

        return ! empty( $latest_property ) ? intval( $latest_property[0] ) : 0;
    }
endif;

