<?php
/**
 * Invoice Post Type Registration
 * src: post-types/invoice/invoice-post-type.php
 * This file handles the registration of the Invoice custom post type
 *
 * @package WPRentals Core
 * @subpackage Invoice
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Invoice custom post type
 */
add_action('after_setup_theme', 'wpestate_create_invoice_type', 20);
if (!function_exists('wpestate_create_invoice_type')):
    function wpestate_create_invoice_type() {
        if ( function_exists( 'wprentals_ptc_is_post_type_enabled' ) && ! wprentals_ptc_is_post_type_enabled( 'wpestate_invoice' ) ) {
                return;
        }
        // Setup post type labels
        $labels = array(
            'name'                  => esc_html__('Invoices', 'wprentals-core'),
            'singular_name'         => esc_html__('Invoices', 'wprentals-core'),
            'add_new'              => esc_html__('Add New Invoice', 'wprentals-core'),
            'add_new_item'         => esc_html__('Add Invoice', 'wprentals-core'),
            'edit'                 => esc_html__('Edit Invoice', 'wprentals-core'),
            'edit_item'            => esc_html__('Edit Invoice', 'wprentals-core'),
            'new_item'             => esc_html__('New Invoice', 'wprentals-core'),
            'view'                 => esc_html__('View Invoices', 'wprentals-core'),
            'view_item'            => esc_html__('View Invoices', 'wprentals-core'),
            'search_items'         => esc_html__('Search Invoices', 'wprentals-core'),
            'not_found'            => esc_html__('No Invoices found', 'wprentals-core'),
            'not_found_in_trash'   => esc_html__('No Invoices found in Trash', 'wprentals-core'),
            'parent'               => esc_html__('Parent Invoice', 'wprentals-core')
        );

        // Define capabilities for the post type
        $capabilities = array(
            'edit_post'              => 'edit_wpestate_invoice',
            'read_post'              => 'read_wpestate_invoice',
            'delete_post'            => 'delete_wpestate_invoice',
            'edit_posts'             => 'edit_wpestate_invoices',
            'edit_others_posts'      => 'edit_others_wpestate_invoices',
            'publish_posts'          => 'publish_wpestate_invoices',
            'read_private_posts'     => 'read_private_wpestate_invoices',
            'create_posts'           => 'create_wpestate_invoices',
            'delete_posts'           => 'delete_wpestate_invoices',
            'delete_private_posts'   => 'delete_private_wpestate_invoices',
            'delete_published_posts' => 'delete_published_wpestate_invoices',
            'delete_others_posts'    => 'delete_others_wpestate_invoices',
            'edit_private_posts'     => 'edit_private_wpestate_invoices',
            'edit_published_posts'   => 'edit_published_wpestate_invoices'
        );
        
        // Register post type
        register_post_type('wpestate_invoice', array(
            'labels'              => $labels,
            'public'              => false,
            'show_ui'            => true,
            'show_in_nav_menus'  => true,
            'show_in_menu'       => true,
            'show_in_admin_bar'  => true,
            'has_archive'         => true,
            'rewrite'            => array('slug' => 'invoice'),
            'supports'           => array('title'),
            'can_export'         => true,
            'register_meta_box_cb'=> 'wpestate_add_pack_invoices',
            'menu_icon'          => WPESTATE_PLUGIN_DIR_URL . '/img/invoices.png',
            'exclude_from_search'=> true,
            'capability_type'    => 'wpestate_invoice',
            'map_meta_cap'       => true,
            'capabilities'       => $capabilities
        ));

        // Ensure administrator has these capabilities
        $admin = get_role('administrator');
        if ($admin) {
            foreach ($capabilities as $cap) {
                $admin->add_cap($cap);
            }
        }
    }
endif;

/**
 * Add custom columns to invoice list
 */
add_filter('manage_edit-wpestate_invoice_columns', 'wpestate_invoice_my_columns');
if (!function_exists('wpestate_invoice_my_columns')):
    function wpestate_invoice_my_columns($columns) {
        $slice = array_slice($columns, 2, 2);
        unset($columns['comments']);
        unset($slice['comments']);
        $splice = array_splice($columns, 2);
        
        $custom_columns = array(
            'invoice_price'  => esc_html__('Price', 'wprentals-core'),
            'invoice_for'    => esc_html__('Billing For', 'wprentals-core'),
            'invoice_type'   => esc_html__('Invoice Type', 'wprentals-core'),
            'invoice_status' => esc_html__('Status', 'wprentals-core'),
            'invoice_user'   => esc_html__('Purchased by User', 'wprentals-core')
        );

        return array_merge($columns, $custom_columns, array_reverse($slice));
    }
endif;

/**
 * Populate custom columns in invoice list
 */
add_action('manage_posts_custom_column', 'wpestate_invoice_populate_columns');
if (!function_exists('wpestate_invoice_populate_columns')):
    function wpestate_invoice_populate_columns($column) {
        $post_id = get_the_ID();
        
        switch($column) {
            case 'invoice_price':
                echo esc_html(get_post_meta($post_id, 'item_price', true));
                break;

            case 'invoice_for':
                $invoice_type_value = get_post_meta($post_id, 'invoice_type', true);
                echo esc_html(wpestate_get_invoice_type_label($invoice_type_value));
                break;
                
            case 'invoice_type':
                echo esc_html(get_post_meta($post_id, 'biling_type', true));
                break;
                
            case 'invoice_status':
                $status = esc_html(get_post_meta($post_id, 'invoice_status', true));
                if ($status === 'confirmed') {
                    echo esc_html($status . ' / ' . esc_html__('paid', 'wprentals-core'));
                } else {
                    echo esc_html($status);
                }
                break;
                
            case 'invoice_user':
                $user_id = get_post_meta($post_id, 'buyer_id', true);
                $user_info = get_userdata($user_id);
                if (isset($user_info->user_login)) {
                    echo esc_html($user_info->user_login);
                }
                break;
        }
    }
endif;

/**
 * Make custom columns sortable
 */
add_filter('manage_edit-wpestate_invoice_sortable_columns', 'wpestate_invoice_sort_me');
if (!function_exists('wpestate_invoice_sort_me')):
    function wpestate_invoice_sort_me($columns) {
        $columns['invoice_price'] = 'invoice_price';
        $columns['invoice_user']  = 'invoice_user';
        $columns['invoice_for']   = 'invoice_for';
        $columns['invoice_type']  = 'invoice_type';
        return $columns;
    }
endif;

/**
 * Insert new invoice
 *
 * @param int|string $billing_for Billing type identifier or legacy label
 * @param int    $type Type (1 for one time, 2 for recurring)
 * @param int    $pack_id Package or item ID
 * @param string $date Purchase date
 * @param int    $user_id User ID
 * @param bool   $is_featured Is featured listing
 * @param bool   $is_upgrade Is upgrade
 * @param string $paypal_tax_id PayPal transaction ID
 * @return int Invoice post ID
 */
if (!function_exists('wpestate_insert_invoice')):
    function wpestate_insert_invoice($billing_for, $type, $pack_id, $date, $user_id, $is_featured, $is_upgrade, $paypal_tax_id) {
        // Validate inputs
        $user_id = absint($user_id);
        $pack_id = absint($pack_id);
        $date = sanitize_text_field($date);
        $paypal_tax_id = sanitize_text_field($paypal_tax_id);
        
        // Create post
        $post = array(
            'post_title'  => esc_html__('Invoice', 'wprentals-core'),
            'post_status' => 'publish',
            'post_type'   => 'wpestate_invoice'
        );
        
        if (intval($user_id) !== 0) {
            $post['post_author'] = $user_id;
        }
        
        $post_id = wp_insert_post($post);
        
        if (is_wp_error($post_id)) {
            return false;
        }

        // Set billing type text
        $type = ($type == 2) ? esc_html__('Recurring', 'wprentals-core') : esc_html__('One Time', 'wprentals-core');

        $invoice_type_key = wpestate_get_invoice_type_key($billing_for);

        // Calculate price
        $price_submission          = floatval(wprentals_get_option('wp_estate_price_submission', ''));
        $price_featured_submission = floatval(wprentals_get_option('wp_estate_price_featured_submission', ''));

        if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_PACKAGE) {
            $price = get_post_meta($pack_id, 'pack_price', true);
        } else if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED) {
            $price = $price_featured_submission;
        } else if ($invoice_type_key === WP_ESTATE_INVOICE_TYPE_PUBLISH_WITH_FEATURED) {
            $price = $price_featured_submission + $price_submission;
        } else {
            $price = $price_submission;

            if ($is_upgrade == 1) {
                $price = $price_featured_submission;
            } else if ($is_featured == 1 && $invoice_type_key === WP_ESTATE_INVOICE_TYPE_LISTING) {
                $price = $price_featured_submission + $price_submission;
            }
        }

        // Update post meta
        $meta_data = array(
            'invoice_type'      => $invoice_type_key !== null ? $invoice_type_key : $billing_for,
            'biling_type'       => $type,
            'item_id'           => $pack_id,
            'item_price'        => $price,
            'purchase_date'     => $date,
            'buyer_id'          => $user_id,
            'txn_id'            => $paypal_tax_id,
            'invoice_currency'  => wpestate_curency_submission_pick()
        );

        foreach ($meta_data as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }

        // Update post title
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => 'Invoice ' . $post_id
        ));

        return $post_id;
    }
endif;