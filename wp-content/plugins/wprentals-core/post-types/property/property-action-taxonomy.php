<?php
/**
 * Property Action taxonomy admin integration.
 */

add_action( 'property_action_category_edit_form_fields', 'wpestate_property_category_callback_function', 10, 2 );
add_action( 'property_action_category_add_form_fields', 'wpestate_property_category_callback_add_function', 10, 2 );
add_action( 'created_property_action_category', 'wpestate_property_city_save_extra_fields_callback', 10, 2 );
add_action( 'edited_property_action_category', 'wpestate_property_city_save_extra_fields_callback', 10, 2 );
