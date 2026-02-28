<?php 
/** MILLDONE
 * Property Tabbed Interface Metabox
 * src : post-types/property/property-metaboxes.php
 * Handles the management and display of custom metabox tabs for the 'estate_property' post type. The tabs include details like general information, price, media, specific details, map, owner, and submission status.
 *
 * @package WPRentals Core
 * @subpackage Property Metaboxes
 * @since 4.0.0
 *
 * @dependencies
 * - WordPress metabox functions
 * - WPRentals theme options and settings
 *
 * Usage:
 * - This file should be included as part of the WPRentals theme to add and manage property details tabs for 'estate_property'.
 */


// Add metaboxes to 'estate_property'.
if( !function_exists('wpestate_add_property_metaboxes') ):
    /**
     * Adds the property metabox to the 'estate_property' post type.
     */
    function wpestate_add_property_metaboxes() {
        add_meta_box('new_tabbed_interface', esc_html__('Property Details', 'wprentals-core'), 'estate_tabbed_interface', 'estate_property', 'normal', 'default');
    }
endif;

if( !function_exists('estate_tabbed_interface') ):
    /**
     * Renders the tabbed interface metabox for 'estate_property'.
     */
    function estate_tabbed_interface() {
        global $post;
        ?>
        <div class="property_options_wrapper meta-options">
            <div class="property_options_wrapper_list">
                <div class="property_tab_item active_tab" data-content="property_details">
                    <?php esc_html_e('Property General Details', 'wprentals-core'); ?>
                </div>
                <div class="property_tab_item" data-content="property_price">
                    <?php esc_html_e('Property Price', 'wprentals-core'); ?>
                </div>
                <div class="property_tab_item" data-content="property_media">
                    <?php esc_html_e('Property Media', 'wprentals-core'); ?>
                </div>
                <div class="property_tab_item" data-content="property_specific_details">
                    <?php esc_html_e('Property Specific Details', 'wprentals-core'); ?>
                </div>
                <div class="property_tab_item" data-content="property_map" id="property_map_trigger">
                    <?php esc_html_e('Map', 'wprentals-core'); ?>
                </div>
                <div class="property_tab_item" data-content="property_agent">
                    <?php esc_html_e('Owner', 'wprentals-core'); ?>
                </div>
                <div class="property_tab_item" data-content="property_paid">
                    <?php esc_html_e('Paid Submission', 'wprentals-core'); ?>
                </div>
            </div>
            <div class="property_options_content_wrapper">
                <div class="property_tab_item_content active_tab" id="property_details">
                    <h3><?php esc_html_e('General Details', 'wprentals-core'); ?></h3>
                    <?php wpestate_listing_details_box($post); ?>
                </div>
                <div class="property_tab_item_content" id="property_price">
                    <h3><?php esc_html_e('Property Price', 'wprentals-core'); ?></h3>
                    <?php wpestate_property_price_admin($post); ?>
                </div>
                <div class="property_tab_item_content" id="property_media">
                    <h3><?php esc_html_e('Property Media', 'wprentals-core'); ?></h3>
                    <?php wpestate_property_add_media($post); ?>
                </div>
                <div class="property_tab_item_content" id="property_specific_details">
                    <h3><?php esc_html_e('Specific Details', 'wprentals-core'); ?></h3>
                    <?php wpestate_listing_details_specific_box($post); ?>
                </div>
                <div class="property_tab_item_content" id="property_map">
                    <h3><?php esc_html_e('Place It On The Map', 'wprentals-core'); ?></h3>
                    <?php wpestate_map_estate_box($post); ?>
                </div>
                <div class="property_tab_item_content" id="property_agent">
                    <h3><?php esc_html_e('Owner', 'wprentals-core'); ?></h3>
                    <?php wpestate_agentestate_box($post); ?>
                </div>
                <div class="property_tab_item_content" id="property_paid">
                    <h3><?php esc_html_e('Paid Submission', 'wprentals-core'); ?></h3>
                    <?php wpestate_paid_submission($post); ?>
                </div>
            </div>
        </div>
        <?php
    }
endif;



if( !function_exists('wpestate_listing_details_box') ):
    /**
     * Adds property general details metabox to the 'estate_property' post type.
     *
     * @param WP_Post $post The post object.
     */
    function wpestate_listing_details_box($post) {
        global $post;

        // Add a nonce field for security.
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');

        // Define the fields for the property details.
        $items = array(
            array(
                'name'  => 'guest_no',
                'label' => esc_html__('Guest Number', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_address',
                'label' => esc_html__('Property Address', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_county',
                'label' => esc_html__('Property County', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_state',
                'label' => esc_html__('Property State', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_zip',
                'label' => esc_html__('Property Zip', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_country',
                'label' => esc_html__('Property Country', 'wprentals-core'),
                'type'  => 'select',
                'defaults' => wpestate_country_list_only_array(),
            ),
            array(
                'name'  => 'prop_featured',
                'label' => esc_html__('Make it Featured', 'wprentals-core'),
                'type'  => 'checkbox',
                'defaults' => 1,
            ),
            array(
                'name'  => 'property_affiliate',
                'label' => esc_html__('Affiliate Link', 'wprentals-core'),
                'type'  => 'input',
                'defaults' => 1,
                'iscssfull' => 'yes',
            ),
            array(
                'name'  => 'private_notes',
                'label' => esc_html__('Private Notes', 'wprentals-core'),
                'type'  => 'textarea',
                'defaults' => 1,
                'iscssfull' => 'yes',
            ),
            array(
                'name'  => 'checkin_message',
                'label' => esc_html__('Check-in Message', 'wprentals-core'),
                'type'  => 'textarea',
                'defaults' => 1,
                'iscssfull' => 'yes',
            ),
            array(
                'name'  => 'instant_booking',
                'label' => esc_html__('Allow instant booking?', 'wprentals-core'),
                'type'  => 'checkbox',
                'defaults' => 1,
            ),
            array(
                'name'  => 'wp_estate_replace_booking_form_local',
                'label' => esc_html__('Show Contact form instead of Booking Form?', 'wprentals-core'),
                'type'  => 'checkbox',
                'defaults' => 1,
            ),
        );

        // Render each field using the wpestate_display_admin_item function.
        foreach ($items as $item) {
            echo wpestate_display_admin_item($item, $post->ID);
        }
    }
endif;



if( !function_exists('wpestate_property_price_admin') ):
    /**
     * Adds property price details metabox to the 'estate_property' post type.
     *
     * @param WP_Post $post The post object.
     */
    function wpestate_property_price_admin($post) {
        global $post;

        // Add a nonce field for security.
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');

        // Retrieve settings and define price-related options.
        $measure_sys = esc_html(wprentals_get_option('wp_estate_measure_sys', ''));
        $booking = array(
            1 => esc_html__('Per Day/Night', 'wprentals-core'),
            2 => esc_html__('Per Hour', 'wprentals-core'),
        );
        $week_days = array(
            '0' => esc_html__('All', 'wprentals-core'),
            '1' => esc_html__('Monday', 'wprentals-core'),
            '2' => esc_html__('Tuesday', 'wprentals-core'),
            '3' => esc_html__('Wednesday', 'wprentals-core'),
            '4' => esc_html__('Thursday', 'wprentals-core'),
            '5' => esc_html__('Friday', 'wprentals-core'),
            '6' => esc_html__('Saturday', 'wprentals-core'),
            '7' => esc_html__('Sunday', 'wprentals-core'),
        );
        $wp_estate_currency_symbol = esc_html(wprentals_get_option('wp_estate_currency_label_main', ''));
        $setup_weekend_status = esc_html(wprentals_get_option('wp_estate_setup_weekend', ''));
        $weekend = array(
            0 => esc_html__('Sunday and Saturday', 'wprentals-core'),
            1 => esc_html__('Friday and Saturday', 'wprentals-core'),
            2 => esc_html__('Friday, Saturday and Sunday', 'wprentals-core'),
        );

        $rental_type = wprentals_get_option('wp_estate_item_rental_type');
        $booking_type = wprentals_return_booking_type($post->ID);
        $options_array = array(
            0 => esc_html__('Single Fee', 'wprentals-core'),
            1 => ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)),
            2 => esc_html__('Per Guest', 'wprentals-core'),
            3 => ucfirst(wpestate_show_labels('per_night', $rental_type, $booking_type)) . ' ' . esc_html__('per Guest', 'wprentals-core'),
        );

        // Define the fields for the property price details.
        $items = array(
            array(
                'name' => 'local_booking_type',
                'label' => esc_html__('Booking Type', 'wprentals-core'),
                'type' => 'select',
                'defaults' => $booking,
            ),
            array(
                'name' => 'property_price',
                'label' => esc_html__('Property Price', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'property_price_before_label',
                'label' => esc_html__('Before Label', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'property_price_after_label',
                'label' => esc_html__('After Label', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'property_taxes',
                'label' => esc_html__('Property Taxes in %', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'property_price_per_week',
                'label' => esc_html__('Price per night (7d+)', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'property_price_per_month',
                'label' => esc_html__('Price per night (30d+)', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'price_per_weekeend',
                'label' => esc_html__('Price per weekend', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'cleaning_fee',
                'label' => esc_html__('Cleaning Fee', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'cleaning_fee_per_day',
                'label' => esc_html__('Cleaning Fee calculation', 'wprentals-core'),
                'type' => 'select',
                'defaults' => $options_array,
            ),
            array(
                'name' => 'city_fee',
                'label' => esc_html__('City Fee', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'city_fee_per_day',
                'label' => esc_html__('City Fee calculation', 'wprentals-core'),
                'type' => 'select',
                'defaults' => $options_array,
            ),
            array(
                'name' => 'min_days_booking',
                'label' => esc_html__('Minimum Days of booking', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'security_deposit',
                'label' => esc_html__('Security Deposit', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'early_bird_percent',
                'label' => esc_html__('Early bird discount', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'early_bird_days',
                'label' => esc_html__('Early bird days before', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'extra_price_per_guest',
                'label' => esc_html__('Extra Price per Guest', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'overload_guest',
                'label' => esc_html__('Allow guests above capacity?', 'wprentals-core'),
                'type' => 'checkbox',
                'defaults' => 1,
            ),
            array(
                'name' => 'max_extra_guest_no',
                'label' => esc_html__('Maximum extra guests above capacity(if extra guest are allowed)', 'wprentals-core'),
                'type' => 'input',
            ),
            array(
                'name' => 'price_per_guest_from_one',
                'label' => esc_html__('Pay by the no of guests (room prices will NOT be used anymore and billing will be done by guest no only)', 'wprentals-core'),
                'type' => 'checkbox',
                'defaults' => 1,
            ),
            array(
                'name' => 'checkin_change_over',
                'label' => esc_html__('Allow only bookings starting with the check-in on', 'wprentals-core'),
                'type' => 'select',
                'defaults' => $week_days,
            ),
            array(
                'name' => 'checkin_checkout_change_over',
                'label' => esc_html__('Allow only bookings with the check-in/check-out on', 'wprentals-core'),
                'type' => 'select',
                'defaults' => $week_days,
            ),
        );

        // Render each field using the wpestate_display_admin_item function.
        foreach ($items as $item) {
            echo wpestate_display_admin_item($item, $post->ID);
        }
    }
endif;
        
             
        

if( !function_exists('wpestate_listing_details_specific_box') ):
    /**
     * Adds property specific details metabox to the 'estate_property' post type.
     *
     * @param WP_Post $post The post object.
     */
    function wpestate_listing_details_specific_box($post) {
        global $post;

        // Add a nonce field for security.
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');

        // Retrieve measurement system setting.
        $measure_sys = esc_html(wprentals_get_option('wp_estate_measure_sys', ''));

        // Define the fields for the specific property details.
        $items = array(
            array(
                'name'  => 'property_size',
                'label' => esc_html__('Property Size in', 'wprentals-core') . ' ' . esc_html($measure_sys),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_rooms',
                'label' => esc_html__('Property Rooms', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_bedrooms',
                'label' => esc_html__('Property Bedrooms', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'property_bathrooms',
                'label' => esc_html__('Property Bathrooms', 'wprentals-core'),
                'type'  => 'input',
            ),
            array(
                'name'  => 'cancellation_policy',
                'label' => esc_html__('Cancellation Policy', 'wprentals-core'),
                'type'  => 'textarea',
                'iscssfull' => 'yes',
            ),
            array(
                'name'  => 'other_rules',
                'label' => esc_html__('Other Rules', 'wprentals-core'),
                'type'  => 'textarea',
                'iscssfull' => 'yes',
            ),
            array(
                'name'  => 'smoking_allowed',
                'label' => esc_html__('Smoking Allowed', 'wprentals-core'),
                'type'  => 'select',
                'defaults' => array('yes' => 'Yes', 'no' => 'No'),
                'selected' => 'no',
            ),
            array(
                'name'  => 'party_allowed',
                'label' => esc_html__('Party Allowed', 'wprentals-core'),
                'type'  => 'select',
                'defaults' => array('yes' => 'Yes', 'no' => 'No'),
                'selected' => 'no',
            ),
            array(
                'name'  => 'pets_allowed',
                'label' => esc_html__('Pets Allowed', 'wprentals-core'),
                'type'  => 'select',
                'defaults' => array('yes' => 'Yes', 'no' => 'No'),
                'selected' => 'no',
            ),
            array(
                'name'  => 'children_allowed',
                'label' => esc_html__('Children Allowed', 'wprentals-core'),
                'type'  => 'select',
                'defaults' => array('yes' => 'Yes', 'no' => 'No'),
                'selected' => 'no',
            ),
        );

        // Render each field using the wpestate_display_admin_item function.
        foreach ($items as $item) {
            echo wpestate_display_admin_item($item, $post->ID);
        }

        // Add custom details box.
        wpestate_custom_details_box($post);
    }
endif;
        


/**
 * Generates a dropdown list of countries for property listings.
 *
 * This function creates a dropdown list of countries that can be used to set the 'property_country' field for properties.
 *
 * @package WPRentals Core
 * @since 4.0.0
 *
 * @param string $selected The selected country.
 * @param string $class Additional CSS classes for styling the dropdown.
 *
 * @return string HTML markup for the dropdown select.
 */
if( !function_exists('wpestate_country_list') ):
    function wpestate_country_list($selected, $class = '') {
        // Get the list of countries from a helper function.
        $countries = wpestate_country_list_only_array();

        // Set the default selected country if none provided.
        if (empty($selected)) {
            $selected = wprentals_get_option('wp_estate_general_country');
        }

        // Start the select element with appropriate attributes.
        $country_select = '<select id="property_country" name="property_country" class="' . esc_attr($class) . '">';

        // Generate each option for the select dropdown.
        foreach ($countries as $key => $country) {
            $country_select .= '<option value="' . esc_attr($key) . '"' . selected(strtolower($selected), strtolower($key), false) . '>' . esc_html($country) . '</option>';
        }

        // Close the select element.
        $country_select .= '</select>';

        return $country_select;
    }
endif; // end wpestate_country_list





/**
 * Retrieves a list of agents for a specific property post.
 *
 * This function returns the list of agents for a given property. This can be used to associate agents with specific properties.
 *
 * @package WPRentals Core
 * @since 4.0.0
 *
 * @param int $mypost The post ID of the property.
 *
 * @return array The list of agents.
 */
if( !function_exists('wpestate_agent_list') ):
    function wpestate_agent_list($mypost) {
        // Placeholder function, assuming agent list generation.
        return $agent_list;
    }
endif; // end wpestate_agent_list


/**
 * Displays an admin item in the property details metabox.
 *
 * This function handles the rendering of input, select, checkbox, textarea, and radio fields for the property details.
 *
 * @package WPRentals Core
 * @since 4.0.0
 *
 * @param array $item The item configuration array (name, label, type, etc.).
 * @param int $edit_id The post ID for retrieving metadata values.
 *
 * @return string HTML markup for the admin item.
 */
function wpestate_display_admin_item($item,$edit_id){
    $return='';
    $css_class='property_prop_half';
    if(isset($item['iscssfull']) && $item['iscssfull']=='yes'){
        $css_class .=' prop_full';
    }
    
    if( $item['type']=='input'){

        $return = '<div class="'.$css_class.'">
            <label for="'.$item['name'].'">'.$item['label'].'</label><br>
            <input type="text" id="'.$item['name'].'" size="40" name="'.$item['name'].'" value="'.get_post_meta($edit_id,$item['name'],true).'">
        </div>';

    }else if(  $item['type']=='select' ){
        $selected=get_post_meta($edit_id,$item['name'],true);
        if($item['name']=='property_country' && $selected==''){
            $selected=   wprentals_get_option('wp_estate_general_country');
        }


        $return = '<div class="'.$css_class.'">
            <label for="'.$item['name'].'">'.$item['label'].'</label><br>
            <select id="'.$item['name'].'"  name="'.$item['name'].'" >';

          
            foreach($item['defaults'] as $key=>$value){
                $return.='<option value="' . $key . '"';
                if (strtolower($selected) == strtolower ($key) || ( empty( $selected ) && isset($item['selected']) && strtolower($item['selected']) == strtolower($key))) {
                    $return.='selected="selected"';
                }
                $return.='>' . $value . '</option>';
            }

        $return.='</select>
        </div>';


    }else if(  $item['type']=='checkbox' ){

        $return.='<div class="'.$css_class.'">
            <input type="hidden" name="'.$item['name'].'" value="0">
            <input type="checkbox"  id="'.$item['name'].'" name="'.$item['name'].'" value="'.$item['defaults'].'" ';
            if (intval(get_post_meta($edit_id, $item['name'], true)) == 1) {
                $return.='checked="checked"';
            }
            $return.=' />
            <label class="checklabel" for="'.$item['name'].'">'.$item['label'].'</label>
        </div>';
    }else if($item['type']=='textarea'){
            $return = '<div class="'.$css_class.'">
            <label for="'.$item['name'].'">'.$item['label'].'</label><br>
            <textarea type="text" id="'.$item['name'].'"  name="'.$item['name'].'" > '.get_post_meta($edit_id,$item['name'],true).'</textarea>
        </div>';

    }  else if($item['type']=='radio'){

        $value = esc_html(get_post_meta($edit_id,$item['name'],true));
        if($value=='yes' ){
            $check_yes = ' checked ';
            $check_no  = ' ';
        }else{
            $check_yes = '  ';
            $check_no  = ' checked ';
        }


         $return = '<div class="'.$css_class.'">
            <label for="smoking_allowed">'.$item['label'].'</label>
            <input type="radio"   name="'.$item['name'].'" value="yes"    '.$check_yes.' >'.$item['radio_label'][0].'
            <input type="radio"  class="second_radio"  name="'.$item['name'].'" '.$check_no.' value="no">'.$item['radio_label'][1].'
        </div>';
    }
    return $return;

}





if ( ! function_exists( 'wpestate_property_add_media' ) ) :

    /**
     * Displays the media section for a property.
     *
     * This function displays the existing media attachments of a property and provides options to add, edit, or delete
     * attachments such as images and videos. The function also allows adding embedded videos and virtual tours.
     *
     * @global WP_Post $post The current post object.
     *
     * @since 1.0.0
     */
    function wpestate_property_add_media() {
        global $post;

      

        $already_in = '';
        $post_attachments_new = wpestate_generate_property_slider_image_ids($post->ID);
         print'<input type="hidden" id="propertyID" value="' . esc_html($post->ID) . '" />    ';
        echo '<div class="property_uploaded_thumb_wrapepr" id="property_uploaded_thumb_wrapepr">';
        $ajax_nonce = wp_create_nonce( 'wpestate_attach_delete' );
        echo '<input type="hidden" id="wpestate_attach_delete" value="' . esc_html( $ajax_nonce ) . '" />';

 

        foreach ( $post_attachments_new as $attachment_id ) {
        

            $attachment = get_post($attachment_id);

            if ($attachment && ($attachment->post_mime_type == 'image/jpeg' ||
                                $attachment->post_mime_type == 'application/pdf' ||
                                $attachment->post_mime_type == 'image/webp' ||
                                $attachment->post_mime_type == 'image/png')) {
                            
            }

        

            echo '<div class="uploaded_thumb" data-imageid="' . esc_attr( $attachment_id ) . '">';
            
                if ($attachment->post_mime_type == 'application/pdf') {
                    print ' <img src="' . get_theme_file_uri('/img/pdf.png') . '" alt="' . esc_html__('user document', 'wprentals-core') . '" />';
                } else {
                    $preview = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    print '<img src="' . $preview[0] . '" alt="slider" />';
                }
                
                $already_in .= $attachment_id . ',';
                print '<a target="_blank" href="' . esc_url(admin_url()) . 'post.php?post=' . $attachment_id . '&action=edit" class="attach_edit"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                <span class="attach_delete"><i class="far fa-trash-alt" aria-hidden="true"></i></span>';
                print '</div>';
        }

        echo '<input type="hidden" id="image_to_attach" name="image_to_attach" value="' . esc_attr( $already_in ) . '"/>';
        $ajax_nonce = wp_create_nonce( 'wpestate_image_upload' );
        echo '<input type="hidden" id="wpestate_image_upload" value="' . esc_html( $ajax_nonce ) . '" />';
        echo '</div>';

        echo '<button class="upload_button button" id="button_new_image" data-postid="' . esc_attr( $post->ID ) . '">' . esc_html__( 'Upload new Image', 'wprentals-core' ) . '</button>';

        $mypost = $post->ID;
        $option_video = '';
        $video_values = array( 'vimeo', 'youtube' );
        $video_type = get_post_meta( $mypost, 'embed_video_type', true );
        $property_custom_video = get_post_meta( $mypost, 'property_custom_video', true );

        foreach ( $video_values as $value ) {
            $option_video .= '<option value="' . esc_attr( $value ) . '"';
            if ( $value == $video_type ) {
                $option_video .= ' selected="selected"';
            }
            $option_video .= '>' . esc_html( $value ) . '</option>';
        }

        echo '<div class="property_prop_half" style="clear: both;">
            <label for="embed_video_id">' . esc_html__( 'Video From: ', 'wprentals-core' ) . '</label><br />
            <select id="embed_video_type" name="embed_video_type">
                ' . $option_video . '
            </select>
        </div>

        <div class="property_prop_half">
            <label for="embed_video_id">' . esc_html__( 'Embed Video id: ', 'wprentals-core' ) . '</label><br />
            <input type="text" id="embed_video_id" name="embed_video_id" size="40" value="' . esc_attr( get_post_meta( $mypost, 'embed_video_id', true ) ) . '">
        </div>';

        echo '<div class="property_prop_half">
            <label for="embed_video_type">' . esc_html__( 'Virtual Tour ', 'wprentals-core' ) . '</label><br />
            <textarea id="virtual_tour" name="virtual_tour">' . esc_textarea( get_post_meta( $mypost, 'virtual_tour', true ) ) . '</textarea>
        </div>';
    }
endif;



if( !function_exists('wpestate_custom_details_box') ):
    function wpestate_custom_details_box(){
        global $post;
        $i=0;
        $custom_fields = wprentals_get_option('wpestate_custom_fields_list','');
    
        if( !empty($custom_fields)){
            print '<h3>'.esc_html__('Custom Details','wprentals-core').'</h3>';
    
            while($i< count($custom_fields) ){
                $name               =   $custom_fields[$i][0];
                $label              =   $custom_fields[$i][1];
                $type               =   $custom_fields[$i][2];
                if(isset( $custom_fields[$i][4])){
                    $dropdown_values    =   $custom_fields[$i][4];
                }
                if (function_exists('wpestate_limit45')) {
					$slug = wpestate_limit45(sanitize_title($name));
				} else {
					$slug = sanitize_title($name);
				}
                $slug               =   sanitize_key($slug);
    
                print '<div class="property_prop_half">';
                if ( $type =='long text' ){
                    print '<label for="'.$slug.'">'.stripslashes($label).' (*text) </label>';
                    print '<textarea type="text" id="'.$slug.'"  size="0" name="'.$slug.'" rows="3" cols="42">' . esc_html(get_post_meta($post->ID, $slug, true)) . '</textarea>';
                }else if( $type =='short text' ){
                    print '<label for="'.$slug.'">'.stripslashes($label).' (*text) </label>';
                    print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
                }else if( $type =='numeric'  ){
                    print '<label for="'.$slug.'">'.stripslashes($label).' (*numeric) </label>';
                    $numeric_value=get_post_meta($post->ID,$slug, true);
                    if($numeric_value!=''){
                        $numeric_value=  floatval($numeric_value);
                    }
                    print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . $numeric_value . '">';
                }else if( $type =='date' ){
                    print '<label for="'.$slug.'">'.stripslashes($label).' (*date) </label>';
                    print '<input type="text" id="'.$slug.'" size="40" name="'.$slug.'" value="' . esc_html(get_post_meta($post->ID,$slug, true)) . '">';
                    print wpestate_date_picker_translation($slug);
                          
    
                }else if( $type =='dropdown' ){
                    $dropdown_values_array=explode(',',$dropdown_values);
    
                    print '<label for="'.$slug.'">'.stripslashes($label).' </label>';
                    print '<select id="'.$slug.'"  name="'.$slug.'" >';
                    print '<option value="">'.esc_html__('Not Available','wprentals-core').'</option>';
                    $value = esc_html(get_post_meta($post->ID,$slug, true));
                    foreach($dropdown_values_array as $key=>$value_drop){
                        print '<option value="'.trim($value_drop).'"';
                        if( trim( htmlspecialchars_decode($value) ) === trim( htmlspecialchars_decode ($value_drop) ) ){
                            print ' selected ';
                        }
                        if (function_exists('icl_translate') ){
                            $value_drop = apply_filters('wpml_translate_single_string', $value_drop,'custom field value','custom_field_value'.$value_drop );
                        }
    
                        print '>'.stripslashes( trim( $value_drop ) ).'</option>';
                    }
                    print '</select>';
                }
                print '</div>';
                $i++;
            }
        }

        print '<div style="clear:both"></div>';
    
    
        $details =   get_post_meta($post->ID, 'property_custom_details', true);
    
        if(is_array($details) && !empty($details)){
            print '   <div class="extra_detail_option_wrapper_admin"> <h3>'.esc_html__('Extra Details','wprentals-core').'</h3>';
            foreach ($details as $label=>$value){
                print '
    
                    <div class="property_prop_half ">
                        <label class="extra_detail_option_label">'.esc_html__('Label','wprentals-core').'</label>
                        <input type="text" name="property_custom_details_admin_label[]" class=" extra_option_name form-control" value="'.$label.'">
                    </div>

                    <div class="property_prop_half ">
                        <label class="extra_detail_option_label">'.esc_html__('Value','wprentals-core').'</label>
                        <input type="text" name="property_custom_details_admin_value[]" class=" extra_option_value form-control" value="'.$value.'">
                    </div>';
    
            }
            print' </div>';
    
        }
    
    
        print '<div style="clear:both"></div>';
    
    }
endif; // end

if( !function_exists('wpestate_map_estate_box') ):

    function wpestate_map_estate_box($post) {
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
        global $post;
    
        $mypost                 =   $post->ID;
        $gmap_lat               =   floatval(get_post_meta($mypost, 'property_latitude', true));
        $gmap_long              =   floatval(get_post_meta($mypost, 'property_longitude', true));
        $google_camera_angle    =   intval( esc_html(get_post_meta($mypost, 'google_camera_angle', true)) );
        $cache_array            =   array('yes','no');
        $keep_min_symbol        =   '';
        $keep_min_status        =   esc_html ( get_post_meta($post->ID, 'keep_min', true) );
    
        foreach($cache_array as $value){
                $keep_min_symbol.='<option value="'.$value.'"';
                if ($keep_min_status==$value){
                        $keep_min_symbol.=' selected="selected" ';
                }
                $keep_min_symbol.='>'.$value.'</option>';
        }
    
        print '<script type="text/javascript">
        //<![CDATA[
        jQuery(document).ready(function(){
            '.wpestate_date_picker_translation("property_date").'
        });
        //]]>
        </script>
        <p class="meta-options">
        <div id="googleMap" style="width:100%;height:380px;margin-bottom:30px;"></div> ';
    
        if(  wprentals_get_option('wp_estate_kind_of_places')!=2){
            print '<p class="meta-options">
                <a class="button" href="#" id="admin_place_pin">'.esc_html__( 'Place Pin with Listing Address','wprentals-core').'</a>
            </p>';
        }

        print '
        <div class="property_prop_half ">
        <label for="property_latitude">'.esc_html__( 'Latitude:','wprentals-core').'</label>  <input type="text" id="property_latitude" style="margin-right:20px;" size="40" name="property_latitude" value="' . $gmap_lat . '">
        </div>
        <div class="property_prop_half ">
        <label for="property_longitude">'.esc_html__( 'Longitude:','wprentals-core').'</label> <input type="text" id="property_longitude" style="margin-right:20px;" size="40" name="property_longitude" value="' . $gmap_long . '">
        </div>
        <p>
        <div class="property_prop_half ">
        <label for="google_camera_angle" >'.esc_html__( 'Google View Camera Angle','wprentals-core').'</label>
        <input type="text" id="google_camera_angle" style="margin-right:0px;" size="5" name="google_camera_angle" value="'.$google_camera_angle.'">

        </div>';

        $page_custom_zoom  = get_post_meta($mypost, 'page_custom_zoom', true);
        if ($page_custom_zoom==''){
            $page_custom_zoom=16;
        }
    
        print '
         <div class="property_prop_half ">
           <label for="page_custom_zoom">'.esc_html__( 'Zoom Level for map (1-20)','wprentals-core').'</label><br />
           <select name="page_custom_zoom" id="page_custom_zoom">';
    
          for ($i=1;$i<21;$i++){
               print '<option value="'.$i.'"';
               if($page_custom_zoom==$i){
                   print ' selected="selected" ';
               }
               print '>'.$i.'</option>';
           }
    
         print'
           </select>
        </div>
        ';
    
    
    
    }
    endif; // end   map_estate_box
    


if( !function_exists('wpestate_agentestate_box') ):
    function wpestate_agentestate_box($post) {
        global $post;
        wp_nonce_field(plugin_basename(__FILE__), 'estate_property_noncename');
    
        $mypost         =   $post->ID;
        $originalpost   =   $post;
        $agent_list     =   '';
        $picked_agent   =   wpsestate_get_author($mypost);
        $blogusers = get_users( 'orderby=nicename' );
    
        foreach ( $blogusers as $user ) {
            $the_id       =  $user->ID;
            $agent_list  .=  '<option value="' . $the_id . '"  ';
            if ($the_id == $picked_agent) {
                $agent_list.=' selected="selected" ';
            }
            $user_info = get_userdata($the_id);
            $username = $user_info->user_login;
            $first_name = $user_info->first_name;
            $last_name = $user_info->last_name;
            $agent_list.= '>' .  $user->user_login .' - '.$first_name.' '.$last_name.'</option>';
        }
    
    
    
        wp_reset_postdata();
        $post = $originalpost;
        $originalAuthor = get_post_meta($mypost, 'original_author',true );
        //print ($originalAuthor);
        print '
        <div class="property_prop_half ">
        <label for="property_zip">'.esc_html__( 'Listing Owner: ','wprentals-core').'</label><br />
        <select id="property_agent" name="property_agent">
            <option value="">none</option>
            <option value=""></option>
            '. $agent_list .'
        </select>
        </div>';
    }
endif; // end   agentestate_box
    




if( !function_exists('wpestate_paid_submission') ):

    function wpestate_paid_submission($post){
        global $post;
    
        $paid_submission_status= esc_html ( wprentals_get_option('wp_estate_paid_submission','') );
    
        if($paid_submission_status=='no'){
    
            esc_html_e('Paid Submission is disabled','wprentals-core');
    
        }else if($paid_submission_status=='membership'){
    
            esc_html_e('Part of membership package','wprentals-core');
    
        }else if($paid_submission_status=='per listing'){
    
            esc_html_e('Pay Status: ','wprentals-core');
            $pay_status           = get_post_meta($post->ID, 'pay_status', true);
            if($pay_status=='paid'){
                esc_html_e('PAID','wprentals-core');
            }
            else{
                esc_html_e('Not Paid','wprentals-core');
            }
        }
    
    }
    endif; // end   estate_paid_submission
    
          