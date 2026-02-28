<?php 

/**
 * Membership Metaboxes
 *
 * Custom metabox fields for membership packages.
 *
 * @package WPRentals
 * @subpackage Membership
 */




/**
 * Add package metaboxes
 *
 * @function wpestate_add_pack_metaboxes
 */

if( !function_exists('wpestate_add_pack_metaboxes') ):
    function wpestate_add_pack_metaboxes() {
        add_meta_box(  'estate_membership-sectionid',  esc_html__(  'Package Details', 'wprentals-core' ),'membership_package','membership_package' ,'normal','default'
    );
}
endif; // end   wpestate_add_pack_metaboxes

/**
 * Package metabox display
 *
 * @function membership_package
 * @param object $post Post object
 */

if( !function_exists('membership_package') ):
    function membership_package( $post ) {
	    wp_nonce_field( plugin_basename( __FILE__ ), 'estate_pack_noncename' );
	    global $post;

        $unlimited_days     =   esc_html(get_post_meta($post->ID, 'mem_days_unl', true));
        $unlimited_lists    =   esc_html(get_post_meta($post->ID, 'mem_list_unl', true));
        $billing_periods    =   array('Day','Week','Month','Year');

        $billng_saved       =   esc_html(get_post_meta($post->ID, 'biling_period', true));
        $billing_select     =   '<select name="biling_period" width="200px" id="billing_period">';
        foreach($billing_periods as $period){
            $billing_select.='<option value="'.$period.'" ';
            if($billng_saved==$period){
                    $billing_select.=' selected="selected" ';
            }
            $billing_select.='>'.$period.'</option>';
        }
        $billing_select.='</select>';

        $check_unlimited_lists='';
        if($unlimited_lists==1){
            $check_unlimited_lists=' checked="checked"  ';
        }


        $visible_array=array('yes','no');
        $visible_saved=get_post_meta($post->ID, 'pack_visible', true);
        $visible_select='<select id="pack_visible" name="pack_visible">';

        foreach($visible_array as $option){
            $visible_select.='<option value="'.$option.'" ';
            if($visible_saved==$option){
                $visible_select.=' selected="selected" ';
            }
            $visible_select.='>'.$option.'</option>';
        }
        $visible_select.='</select>';

        $available_tabs = array( 'pack_price_period', 'pack_listings', 'pack_display' );
        $active_tab     = 'pack_price_period';
        if ( isset( $_GET['membership_tab'] ) && in_array( $_GET['membership_tab'], $available_tabs, true ) ) {
            $active_tab = sanitize_key( $_GET['membership_tab'] );
        }

        print '<div class="property_options_wrapper meta-options">'
            .'<div class="property_options_wrapper_list">';
                print '<div class="property_tab_item'.( $active_tab === 'pack_price_period' ? ' active_tab' : '' ).'" data-content="pack_price_period">'.esc_html__('Billing Price and Period','wprentals-core').'</div>';
                print '<div class="property_tab_item'.( $active_tab === 'pack_listings' ? ' active_tab' : '' ).'" data-content="pack_listings">'.esc_html__('Listings Included','wprentals-core').'</div>';
                print '<div class="property_tab_item'.( $active_tab === 'pack_display' ? ' active_tab' : '' ).'" data-content="pack_display">'.esc_html__('Display','wprentals-core').'</div>';
        print '</div><div class="property_options_content_wrapper">';

        print '<div class="property_tab_item_content'.( $active_tab === 'pack_price_period' ? ' active_tab' : '' ).'" id="pack_price_period">';
        print '    <div class="property_prop_half">
                    <label for="pack_price">'.esc_html__('Package Price in ','wprentals-core').' '.wpestate_curency_submission_pick().'</label><br />
                    <input type="text" id="pack_price" name="pack_price" value="'.esc_html( get_post_meta($post->ID,'pack_price',true) ).'">
                </div>';
        print '    <div class="property_prop_half">
                    <label for="biling_period">'.esc_html__('Billing Period:','wprentals-core').'</label><br />
                    '.$billing_select.'
                </div>';
        print '    <div class="property_prop_half">
                    <label for="billing_freq">'.esc_html__('Billing Frequency','wprentals-core').'</label><br />
                    <input type="text" id="billing_freq" name="billing_freq" value="'.intval(get_post_meta($post->ID,'billing_freq',true)).'">
                </div>';
        print '    <div class="property_prop_half">
                    <label for="pack_stripe_id">Package Stripe ID (enter the ID from Stripe Account)</label><br />
                    <input type="text" id="pack_stripe_id" name="pack_stripe_id" value="'.esc_html( get_post_meta($post->ID,'pack_stripe_id',true) ).'">
                </div>';
        print '</div>';

        print '<div class="property_tab_item_content'.( $active_tab === 'pack_listings' ? ' active_tab' : '' ).'" id="pack_listings">';
        print '    <div class="property_prop_half">
                    <label for="pack_listings">'.esc_html__('How many listings are included?','wprentals-core').'</label><br />
                    <input type="text" id="pack_listings" name="pack_listings" value="'.esc_html( get_post_meta($post->ID,'pack_listings',true) ).'">
                    <br/>
                    <div class="wprentals_check_list_wrapper">
                        <input type="hidden" name="mem_list_unl" value=""/>
                        <input type="checkbox" class="wprentals-admin-checkbox" id="mem_list_unl" name="mem_list_unl" value="1" '.$check_unlimited_lists.' />
                        <label for="mem_list_unl" class="regular-label">'.esc_html__('Unlimited listings','wprentals-core').'</label>
                    </div>
                </div>';
        print '    <div class="property_prop_half">
                    <label for="pack_featured_listings">'.esc_html__('How many Featured listings are included?','wprentals-core').'</label><br />
                    <input type="text" id="pack_featured_listings" name="pack_featured_listings" value="'.esc_html( get_post_meta($post->ID,'pack_featured_listings',true) ).'">
                </div>';
        // print '    <div class="property_prop_half">
        //             <label for="pack_image_included">'.esc_html__('How many images are included per listing?','wprentals-core').'</label><br />
        //             <input type="text" id="pack_image_included" name="pack_image_included" value="'.intval( get_post_meta($post->ID,'pack_image_included',true) ).'">
        //         </div>';
        print '</div>';

        print '<div class="property_tab_item_content'.( $active_tab === 'pack_display' ? ' active_tab' : '' ).'" id="pack_display">';
        // print '    <div class="property_prop_half">
        //             <label for="pack_visible_user_role">'.esc_html__('Display package for? *Hold CTRL for multiple selection.','wprentals-core').'</label><br />
        //             '.$visible_pack_select.'
        //         </div>';
        print '    <div class="property_prop_half">
                    <label for="pack_visible">'.esc_html__('Is it visible?','wprentals-core').'</label><br />
                    '.$visible_select.'
                </div>';
        print '</div>';
    
        print '</div></div>';
        
    }
endif; // end   membership_package