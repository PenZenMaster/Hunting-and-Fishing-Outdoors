<?php
/**
 * Property City taxonomy admin integration.
 *
 * Uses the shared property category interface for add/edit screens while
 * preserving the existing save behaviour.
 */

add_action( 'property_city_edit_form_fields', 'wpestate_property_category_callback_function', 10, 2 );
add_action( 'property_city_add_form_fields', 'wpestate_property_category_callback_add_function', 10, 2 );
add_action( 'created_property_city', 'wpestate_property_city_save_extra_fields_callback', 10, 2 );
add_action( 'edited_property_city', 'wpestate_property_city_save_extra_fields_callback', 10, 2 );

if ( ! function_exists( 'wpestate_property_city_save_extra_fields_callback' ) ) {
    /**
     * Persists term meta for property cities.
     *
     * @param int $term_id Term identifier.
     */
    function wpestate_property_city_save_extra_fields_callback( $term_id ) {
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
