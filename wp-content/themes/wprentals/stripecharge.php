<?php
// Template Name:Stripe Charge Page
// Wp Estate Pack


$endpoint_secret    =   esc_html ( wprentals_get_option('wp_estate_stripe_webhook','') );
$payload            =   file_get_contents('php://input');
error_log('Stripe webhook: payload received');
$sig_header         =   esc_html($_SERVER['HTTP_STRIPE_SIGNATURE']);
$event = null;
$pay_type = 0;
$userId   = 0;


 

//if($sig_header=='')return;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
    error_log('Stripe webhook: event constructed of type ' . $event->type);
} catch(\UnexpectedValueException $e) {
    error_log('Stripe webhook: invalid payload ' . $e->getMessage());
    http_response_code(400); // PHP 5.4 or greater
    exit('');
} catch(\Stripe\Error\SignatureVerification $e) {
    error_log('Stripe webhook: invalid signature ' . $e->getMessage());
    http_response_code(400); // PHP 5.4 or greater
    exit();
}



//error_log($event);

if ($event->type == "payment_intent.succeeded") {
        error_log('Stripe webhook: handling payment_intent.succeeded');
        $intent   = $event->data->object;
        $pay_type = 0;
        $userId   = 0;

        if ( isset( $event->data->object->charges->data[0]->metadata->pay_type ) ) {
            $pay_type = intval( $event->data->object->charges->data[0]->metadata->pay_type );
            $userId   = intval( $event->data->object->charges->data[0]->metadata->user_id );
        }
        if ( isset( $event->data->object->metadata->pay_type ) ) {
            $pay_type = intval( $event->data->object->metadata->pay_type );
            $userId   = intval( $event->data->object->metadata->user_id );
        }
        error_log('Stripe webhook: intent metadata pay_type=' . $pay_type . ' userId=' . $userId);

        $depozit   = intval( $intent->amount );
        $user_data = get_userdata( $userId );

        if($pay_type==1){
            $user_email     =   $user_data->user_email;

            if( isset($event->data->object->charges->data[0]->metadata->invoice_id) ){
                $invoice_id     =   intval($event->data->object->charges->data[0]->metadata->invoice_id);
                $booking_id     =   intval($event->data->object->charges->data[0]->metadata->booking_id );
            }
            if( isset($event->data->object->metadata->invoice_id) ) {
                $invoice_id     =   intval($event->data->object->metadata->invoice_id);
                $booking_id     =   intval($event->data->object->metadata->booking_id);
            }
            error_log('Stripe webhook: booking payment invoice_id=' . ( $invoice_id ?? 0 ) . ' booking_id=' . ( $booking_id ?? 0 ) );

            $is_stripe=1;
            wpestate_booking_mark_confirmed($booking_id,$invoice_id,$userId,$depozit,$user_email,$is_stripe);
            error_log('Stripe webhook: booking marked confirmed for user ' . $userId);
            $redirect=wpestate_get_template_link('user_dashboard_my_reservations.php');
            http_response_code(200);
            exit();

        }else if($pay_type==2){

            if( isset($event->data->object->charges->data[0]->metadata->listing_id) ){
                $listing_id     =   intval($event->data->object->charges->data[0]->metadata->listing_id);
                $is_featured    =   intval($event->data->object->charges->data[0]->metadata->featured_pay);
                $is_upgrade     =   intval($event->data->object->charges->data[0]->metadata->is_upgrade);
            }else{
                $listing_id     =   intval($event->data->object->metadata->listing_id);
                $is_featured    =   intval($event->data->object->metadata->featured_pay);
                $is_upgrade     =   intval($event->data->object->metadata->is_upgrade);
            }

            error_log('Stripe webhook: listing payment listing_id=' . $listing_id . ' featured=' . $is_featured . ' upgrade=' . $is_upgrade);

            $time = time();
            $date = date('Y-m-d H:i:s',$time);

            if($is_upgrade==1){
                update_post_meta($listing_id, 'prop_featured', 1);
                $invoice_id = wpestate_insert_invoice(WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED,'One Time',$listing_id,$date,$userId,0,1,'' );
                update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                error_log('Stripe webhook: upgraded listing to featured invoice_id=' . $invoice_id);
                wpestate_email_to_admin(1);
            }else{
                update_post_meta($listing_id, 'pay_status', 'paid');
                $admin_submission_status = esc_html ( wprentals_get_option('wp_estate_admin_submission','') );
                $paid_submission_status  = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );

                if($admin_submission_status=='no'  && $paid_submission_status=='per listing' ){
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'publish'
                        );
                    $post_id =  wp_update_post($post );
                    error_log('Stripe webhook: auto-published listing ' . $listing_id);
                }
                // end make post publish

                if($is_featured==1){
                    update_post_meta($listing_id, 'prop_featured', 1);
                    $invoice_id = wpestate_insert_invoice(WP_ESTATE_INVOICE_TYPE_PUBLISH_WITH_FEATURED,'One Time',$listing_id,$date,$userId,1,0,'' );
                    update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                    error_log('Stripe webhook: listing published with featured invoice_id=' . $invoice_id);
                }else{
                    $invoice_id = wpestate_insert_invoice(WP_ESTATE_INVOICE_TYPE_LISTING,'One Time',$listing_id,$date,$userId,0,0,'' );
                    update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                    error_log('Stripe webhook: listing published invoice_id=' . $invoice_id);
                }
                wpestate_email_to_admin(0);
            }

            $redirect = wpestate_get_template_link('user_dashboard.php');
            http_response_code(200);
            exit();
        }

        http_response_code(200);
        exit();

}elseif ($event->type == "invoice.payment_succeeded") {

            error_log('Stripe webhook: handling invoice.payment_succeeded');
            $invoice_object    = $event->data->object;
            $customer_stripe_id = isset( $invoice_object->customer ) ? $invoice_object->customer : '';

            // Convert invoice object to array to safely read nested metadata across API versions
            $invoice_data = method_exists( $invoice_object, 'toArray' ) ? $invoice_object->toArray() : array();
            $subscription_metadata = null;
            $update_user_id = 0;
            $pack_id       = 0;
            $one_time_meta = 0;

            if ( isset( $invoice_data['lines']['data'][0]['metadata'] ) ) {
                $line_meta = $invoice_data['lines']['data'][0]['metadata'];
                if ( isset( $line_meta['wpestate_user'] ) ) {
                    $update_user_id = intval( $line_meta['wpestate_user'] );
                }
                if ( isset( $line_meta['wpestate_packID'] ) ) {
                    $pack_id = intval( $line_meta['wpestate_packID'] );
                }
                if ( isset( $line_meta['wpestate_onetime'] ) ) {
                    $one_time_meta = intval( $line_meta['wpestate_onetime'] );
                }
            }
            error_log('Stripe webhook: line metadata user=' . $update_user_id . ' pack=' . $pack_id . ' one_time=' . $one_time_meta);

            if ( ( $update_user_id === 0 || $pack_id === 0 || $one_time_meta === 0 ) &&
                 isset( $invoice_data['parent']['subscription_details']['metadata'] ) ) {
                $subscription_metadata = $invoice_data['parent']['subscription_details']['metadata'];
                if ( $update_user_id === 0 && isset( $subscription_metadata['wpestate_user'] ) ) {
                    $update_user_id = intval( $subscription_metadata['wpestate_user'] );
                }
                if ( $pack_id === 0 && isset( $subscription_metadata['wpestate_packID'] ) ) {
                    $pack_id = intval( $subscription_metadata['wpestate_packID'] );
                }
                if ( $one_time_meta === 0 && isset( $subscription_metadata['wpestate_onetime'] ) ) {
                    $one_time_meta = intval( $subscription_metadata['wpestate_onetime'] );
                }
            }
            error_log('Stripe webhook: parent metadata user=' . $update_user_id . ' pack=' . $pack_id . ' one_time=' . $one_time_meta);

            $subscription_id = null;
            if ( isset( $invoice_data['parent']['subscription_details']['subscription'] ) ) {
                $subscription_id = $invoice_data['parent']['subscription_details']['subscription'];
            } elseif ( isset( $invoice_data['lines']['data'][0]['parent']['subscription_item_details']['subscription'] ) ) {
                $subscription_id = $invoice_data['lines']['data'][0]['parent']['subscription_item_details']['subscription'];
            }
            error_log('Stripe webhook: subscription_id=' . ( $subscription_id ? $subscription_id : 'none' ) );

            if ( ( $update_user_id === 0 || $pack_id === 0 || $one_time_meta === 0 ) && $subscription_id ) {
                try {
                    error_log('Stripe webhook: retrieving subscription ' . $subscription_id);
                    $subscription = \Stripe\Subscription::retrieve( $subscription_id );
                    $subscription_metadata = $subscription->metadata ? $subscription->metadata->toArray() : array();
                    if ( $update_user_id === 0 && isset( $subscription_metadata['wpestate_user'] ) ) {
                        $update_user_id = intval( $subscription_metadata['wpestate_user'] );
                    }
                    if ( $pack_id === 0 && isset( $subscription_metadata['wpestate_packID'] ) ) {
                        $pack_id = intval( $subscription_metadata['wpestate_packID'] );
                    }
                    if ( $one_time_meta === 0 && isset( $subscription_metadata['wpestate_onetime'] ) ) {
                        $one_time_meta = intval( $subscription_metadata['wpestate_onetime'] );
                    }
                    error_log('Stripe webhook: subscription metadata user=' . $update_user_id . ' pack=' . $pack_id . ' one_time=' . $one_time_meta);
                } catch ( \Exception $e ) {
                    error_log('Stripe webhook: subscription retrieve error ' . $e->getMessage());
                }
            }

            if ( $update_user_id === 0 ) {
                $user_query = get_users( array( 'meta_key' => 'stripe', 'meta_value' => $customer_stripe_id ) );
                if ( ! empty( $user_query ) ) {
                    $update_user_id = intval( $user_query[0]->ID );
                }
            }
            error_log('Stripe webhook: user resolved to ' . $update_user_id);

            if ( $pack_id === 0 && isset( $invoice_data['lines']['data'][0] ) ) {
                $first_line = $invoice_data['lines']['data'][0];
                $stripe_product_id = null;
                if ( isset( $first_line['plan']['product'] ) ) {
                    $stripe_product_id = $first_line['plan']['product'];
                } elseif ( isset( $first_line['price']['product'] ) ) {
                    $stripe_product_id = $first_line['price']['product'];
                } elseif ( isset( $first_line['pricing']['price_details']['product'] ) ) {
                    $stripe_product_id = $first_line['pricing']['price_details']['product'];
                }
                if ( $stripe_product_id ) {
                    error_log('Stripe webhook: resolving pack by product ' . $stripe_product_id);
                    $pack_query = new WP_Query( array(
                        'post_type'      => 'membership_package',
                        'meta_key'       => 'pack_stripe_id',
                        'meta_value'     => $stripe_product_id,
                        'fields'         => 'ids',
                        'posts_per_page' => 1,
                    ) );
                    if ( $pack_query->have_posts() ) {
                        $pack_id = intval( $pack_query->posts[0] );
                    }
                    wp_reset_postdata();
                }
            }
            error_log('Stripe webhook: pack resolved to ' . $pack_id);

            $payment_type = ( intval( $one_time_meta ) === 1 ) ? 1 : 2;
            error_log('Stripe webhook: final metadata user=' . $update_user_id . ' pack=' . $pack_id . ' payment_type=' . $payment_type);

            if ( $update_user_id && $pack_id ) {
                if ( wpestate_check_downgrade_situation( $update_user_id, $pack_id ) ) {
                    wpestate_downgrade_to_pack( $update_user_id, $pack_id );
                }
                wpestate_upgrade_user_membership( $update_user_id, $pack_id, $payment_type, '' );
                error_log('Stripe webhook: membership upgraded for user ' . $update_user_id);
            } else {
                error_log('Stripe webhook: insufficient data to upgrade membership');
            }

            http_response_code(200);
            exit();
}elseif ($event->type == "invoice.payment_failed" || $event->type=="customer.subscription.deleted") {

        $customer_stripe_id =$event->data->object->customer;
        error_log('Stripe webhook: ' . $event->type . ' for customer ' . $customer_stripe_id);
        $args   =   array(  'meta_key'      => 'stripe',
                            'meta_value'    => $customer_stripe_id
                            );

        $customers  =   get_users( $args );
        foreach ( $customers as $user ) {
            update_user_meta( $user->ID, 'stripe', '' );
            wpestate_downgrade_to_free($user->ID);
            error_log('Stripe webhook: downgraded user ' . $user->ID);
        }
        http_response_code(200);
        exit();


}elseif ($event->type == "payment_intent.payment_failed") {
        $intent = $event->data->object;
        $error_message = $intent->last_payment_error ? $intent->last_payment_error->message : "";
        error_log('Stripe webhook: payment_intent.payment_failed id=' . $intent->id . ' error=' . $error_message);
        printf("Failed: %s, %s", $intent->id, $error_message);
        http_response_code(200);
        exit();
}elseif($event->type=="invoice.payment_action_required"){
    $user_email     = '';
    $payment_intent = '';

    if ( isset( $event->data->object->charges->data[0]->metadata->pay_type ) ) {
        $pay_type = intval( $event->data->object->charges->data[0]->metadata->pay_type );
        $userId   = intval( $event->data->object->charges->data[0]->metadata->user_id );
    }
    if ( isset( $event->data->object->metadata->pay_type ) ) {
        $pay_type = intval( $event->data->object->metadata->pay_type );
        $userId   = intval( $event->data->object->metadata->user_id );
    }

    if ( isset( $event->data->object->customer_email ) ) {
        $user_email = $event->data->object->customer_email;
    }

    if ( isset( $event->data->object->payment_intent ) ) {
        $payment_intent = $event->data->object->payment_intent;
    }

    error_log('Stripe webhook: payment_action_required email=' . $user_email . ' payment_intent=' . $payment_intent);

    if ( $user_email !== '' ) {
        $user   = get_user_by( 'email', $user_email );
        $userId = $user->ID;
        update_user_meta( $userId, 'wpestate_payment_intent_recurring', $payment_intent );
        $arguments = array( 'payment_intent' => $payment_intent );
        wpestate_select_email_type( $user_email, 'payment_action_required', $arguments );
        error_log('Stripe webhook: stored payment_intent for user ' . $userId);
    }

}else{
    error_log('Stripe webhook: unhandled event ' . $event->type);
    http_response_code(400);
    exit();
}
//11