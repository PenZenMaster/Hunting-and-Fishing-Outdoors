<?php
/**
 * Property term helper utilities for WPRentals.
 *
 * Provides shared helpers for taxonomy term admin interfaces including
 * dropdown generators, metadata parsing and AJAX handlers for managing
 * gallery and document attachments.
 *
 * @package WPRentals Core
 */

if ( ! function_exists( 'wpestate_country_list' ) ) {
    /**
     * Builds a country select field.
     *
     * @param string $selected Selected country.
     * @param string $class    Extra CSS classes.
     * @param string $name     Input name attribute.
     *
     * @return string
     */
    function wpestate_country_list( $selected, $class = '', $name = 'property_country' ) {
        $countries      = wpestate_country_list_only_array();
        $select_classes = trim( $class );
        $select_name    = $name ? $name : 'property_country';
        $select_id      = 'property_country';

        if ( empty( $selected ) ) {
            $selected = wprentals_get_option( 'wp_estate_general_country' );
        }

        $output  = '<select id="' . esc_attr( $select_id ) . '"  name="' . esc_attr( $select_name ) . '" class="' . esc_attr( $select_classes ) . '">';
        foreach ( $countries as $country_key => $country_label ) {
            $output .= '<option value="' . esc_attr( $country_key ) . '" ' . selected( strtolower( $selected ), strtolower( $country_key ), false ) . '>' . esc_html( $country_label ) . '</option>';
        }
        $output .= '</select>';

        return $output;
    }
}

if ( ! function_exists( 'wpestate_country_list_search' ) ) {
    /**
     * Creates a list of countries for custom dropdowns.
     *
     * @param string $selected Selected country (unused but kept for parity).
     *
     * @return string
     */
    function wpestate_country_list_search( $selected ) {
        unset( $selected );
        $countries = wpestate_country_list_only_array();
        $output    = '';

        foreach ( $countries as $country_key => $country_label ) {
            $output .= '<li role="presentation" data-value="' . esc_attr( $country_key ) . '">' . esc_html( $country_label ) . '</li>';
        }

        return $output;
    }
}

if ( ! function_exists( 'wpestate_agent_list' ) ) {
    /**
     * Placeholder agent list helper.
     *
     * @param mixed $mypost Post context.
     *
     * @return mixed
     */
    function wpestate_agent_list( $mypost ) {
        unset( $mypost );
        return array();
    }
}

if ( ! function_exists( 'wpestate_get_all_cities' ) ) {
    /**
     * Returns HTML <option> tags for all property cities.
     *
     * @param string $selected Selected city name.
     *
     * @return string
     */
    function wpestate_get_all_cities( $selected = '' ) {
        $tax_terms = wpestate_get_cached_terms( 'property_city', array( 'hide_empty' => false ) );
        $tax_terms = is_array( $tax_terms ) ? $tax_terms : array();
        $output    = '';

        foreach ( $tax_terms as $tax_term ) {
            $output .= '<option value="' . esc_attr( $tax_term->name ) . '" ' . selected( $tax_term->name, $selected, false ) . '>' . esc_html( $tax_term->name ) . '</option>';
        }

        return $output;
    }
}

if ( ! function_exists( 'wpestate_get_all_states' ) ) {
    /**
     * Returns HTML <option> tags for all property states/counties.
     *
     * @param string $selected Selected state name.
     *
     * @return string
     */
    function wpestate_get_all_states( $selected = '' ) {
        $tax_terms = wpestate_get_cached_terms( 'property_county_state', array( 'hide_empty' => false ) );
        $tax_terms = is_array( $tax_terms ) ? $tax_terms : array();
        $output    = '';

        foreach ( $tax_terms as $tax_term ) {
            $output .= '<option value="' . esc_attr( $tax_term->name ) . '" ' . selected( $tax_term->name, $selected, false ) . '>' . esc_html( $tax_term->name ) . '</option>';
        }

        return $output;
    }
}

if ( ! function_exists( 'wpestate_parse_category_term_array' ) ) {
    /**
     * Normalises term metadata into a predictable structure.
     *
     * @param array $term_meta Raw term meta array from the database.
     *
     * @return array
     */
    function wpestate_parse_category_term_array( $term_meta ) {
        $term_meta       = is_array( $term_meta ) ? $term_meta : array();
        $meta            = array();
        $text_fields     = array(
            'pagetax',
            'category_featured_image',
            'category_featured_image_icon',
            'category_tagline',
            'category_attach_id',
            'category_gallery',
            'category_documents',
            'term_geojson',
        );

        foreach ( $text_fields as $field ) {
            if ( isset( $term_meta[ $field ] ) && '' !== $term_meta[ $field ] ) {
                $value = 'category_tagline' === $field ? stripslashes( $term_meta[ $field ] ) : $term_meta[ $field ];
                $meta[ $field ] = $value;
            } else {
                $meta[ $field ] = '';
            }
        }

        $map_fields = array(
            'term_address'      => array( 'term_address', 'property_address' ),
            'term_zip'          => array( 'term_zip', 'property_zip' ),
            'term_country'      => array( 'term_country', 'property_country' ),
            'term_latitude'     => array( 'term_latitude', 'property_latitude' ),
            'term_longitude'    => array( 'term_longitude', 'property_longitude' ),
            'google_camera_angle' => array( 'google_camera_angle' ),
            'term_google_view'  => array( 'term_google_view', 'property_google_view' ),
        );

        foreach ( $map_fields as $return_key => $possible_keys ) {
            $value = '';
            foreach ( $possible_keys as $key ) {
                if ( isset( $term_meta[ $key ] ) && '' !== $term_meta[ $key ] ) {
                    $value = $term_meta[ $key ];
                    break;
                }
            }
            $meta[ $return_key ] = $value;
        }

        $meta['page_custom_zoom'] = isset( $term_meta['page_custom_zoom'] ) && '' !== $term_meta['page_custom_zoom'] ? $term_meta['page_custom_zoom'] : 16;

        return $meta;
    }
}

add_action( 'wp_ajax_wpestate_delete_term_gallery_image', 'wpestate_delete_term_gallery_image' );
/**
 * AJAX callback to remove a gallery image from a term.
 */
function wpestate_delete_term_gallery_image() {
    check_ajax_referer( 'wpestate_term_gallery', 'nonce' );

    $term_id     = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0;
    $gallery_ids = isset( $_POST['gallery_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['gallery_ids'] ) ) : '';

    if ( ! $term_id ) {
        wp_send_json_error();
    }

    $option_key = 'taxonomy_' . $term_id;
    $term_meta  = get_option( $option_key, array() );

    if ( '' !== $gallery_ids ) {
        $term_meta['category_gallery'] = $gallery_ids;
        update_option( $option_key, $term_meta );
    }

    wp_send_json_success();
}

add_action( 'wp_ajax_wpestate_delete_term_document', 'wpestate_delete_term_document' );
/**
 * AJAX callback to remove a document from a term.
 */
function wpestate_delete_term_document() {
    check_ajax_referer( 'wpestate_term_documents', 'nonce' );

    $term_id      = isset( $_POST['term_id'] ) ? intval( $_POST['term_id'] ) : 0;
    $document_ids = isset( $_POST['document_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['document_ids'] ) ) : '';

    if ( ! $term_id ) {
        wp_send_json_error();
    }

    $option_key = 'taxonomy_' . $term_id;
    $term_meta  = get_option( $option_key, array() );

    if ( '' !== $document_ids ) {
        $term_meta['category_documents'] = $document_ids;
        update_option( $option_key, $term_meta );
    }

    wp_send_json_success();
}
