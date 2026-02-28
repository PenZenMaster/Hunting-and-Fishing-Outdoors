<?php
/**
 * Property Features taxonomy admin integration.
 */

add_action( 'property_features_edit_form_fields', 'wpestate_property_category_callback_function', 10, 2 );
add_action( 'property_features_add_form_fields', 'wpestate_property_category_callback_add_function', 10, 2 );
add_action( 'created_property_features', 'wpestate_property_features_save_extra_fields_callback', 10, 2 );
add_action( 'edited_property_features', 'wpestate_property_features_save_extra_fields_callback', 10, 2 );

if ( ! function_exists( 'wpestate_property_features_save_extra_fields_callback' ) ) {
    /**
     * Saves property feature term metadata.
     *
     * @param int $term_id Term identifier.
     */
    function wpestate_property_features_save_extra_fields_callback( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id      = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $term_meta = is_array( $term_meta ) ? $term_meta : array();
            $cat_keys  = array_keys( $_POST['term_meta'] );
            $allowed   = array();

            foreach ( $cat_keys as $key ) {
                $key = sanitize_key( $key );
                if ( isset( $_POST['term_meta'][ $key ] ) ) {
                    $term_meta[ $key ] = wp_kses( wp_unslash( $_POST['term_meta'][ $key ] ), $allowed );
                }
            }

            update_option( "taxonomy_$t_id", $term_meta );
        }
    }
}
