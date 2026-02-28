<?php
/**
 * Property Area taxonomy admin integration.
 */

add_action( 'property_area_edit_form_fields', 'wpestate_property_category_callback_function', 10, 2 );
add_action( 'property_area_add_form_fields', 'wpestate_property_category_callback_add_function', 10, 2 );
add_action( 'created_property_area', 'wpestate_property_area_save_extra_fields_callback', 10, 2 );
add_action( 'edited_property_area', 'wpestate_property_area_save_extra_fields_callback', 10, 2 );

add_filter( 'manage_edit-property_area_columns', 'wprentals_custom_columns_property_area' );
add_filter( 'manage_property_area_custom_column', 'wprentals_custom_columns_content_taxonomy', 10, 3 );

if ( ! function_exists( 'wprentals_custom_columns_property_area' ) ) {
    /**
     * Adds custom columns to the property area list table.
     *
     * @param array $new_columns Existing columns.
     *
     * @return array
     */
    function wprentals_custom_columns_property_area( $new_columns ) {
        $new_columns = array(
            'cb'    => '<input type="checkbox" />',
            'name'  => esc_html__( 'Name', 'wprentals-core' ),
            'city'  => esc_html__( 'City', 'wprentals-core' ),
            'slug'  => esc_html__( 'Slug', 'wprentals-core' ),
            'posts' => esc_html__( 'Posts', 'wprentals-core' ),
            'id'    => esc_html__( 'ID', 'wprentals-core' ),
        );

        return $new_columns;
    }
}

if ( ! function_exists( 'wprentals_custom_columns_content_taxonomy' ) ) {
    /**
     * Renders custom column data for property areas.
     *
     * @param string $out        Column output.
     * @param string $column     Column key.
     * @param int    $term_id    Term identifier.
     */
    function wprentals_custom_columns_content_taxonomy( $out, $column, $term_id ) {
        if ( 'city' === $column ) {
            $term_meta = get_option( "taxonomy_$term_id" );
            if ( isset( $term_meta['cityparent'] ) ) {
                echo esc_html( $term_meta['cityparent'] );
            }
        }

        if ( 'id' === $column ) {
            echo esc_html( $term_id );
        }
    }
}

if ( ! function_exists( 'wpestate_property_area_save_extra_fields_callback' ) ) {
    /**
     * Persists term meta for property areas.
     *
     * @param int $term_id Term identifier.
     */
    function wpestate_property_area_save_extra_fields_callback( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id      = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $term_meta = is_array( $term_meta ) ? $term_meta : array();
            $cat_keys  = array_keys( $_POST['term_meta'] );

            foreach ( $cat_keys as $key ) {
                $key = sanitize_key( $key );
                if ( isset( $_POST['term_meta'][ $key ] ) ) {
                    $term_meta[ $key ] = wp_kses_post( wp_unslash( $_POST['term_meta'][ $key ] ) );
                }
            }

            update_option( "taxonomy_$t_id", $term_meta );
        }
    }
}
