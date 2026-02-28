<?php
/**
 * Module/Script Name: dashboard-link-fix.php
 * Path: wp-content/themes/wprentals-child/libs/dashboard-link-fix.php
 *
 * Description:
 * Overrides wpestate_get_all_dashboard_template_links() to fix a bug in
 * WPRentals 3.17.0 where 'number' => count($templates) limits get_pages()
 * to 21 results. Sites with more than 21 dashboard pages (e.g., HNFO has 35
 * across three installs) lose the alphabetically-later pages. "My Listings"
 * (title "My Listings" / "My Properties") sorts at position 25+ and is cut
 * off, causing the My Listings dropdown link to disappear entirely because
 * the new theme uses strict isset() rather than the old non-empty string check.
 *
 * Fix: pass 'number' => 0 (WordPress default = unlimited).
 *
 * Author(s):
 * Rank Rocket Co (C) Copyright 2026 - All Rights Reserved
 *
 * Created Date: 2026-02-27
 * Last Modified Date: 2026-02-27
 *
 * Comments:
 * v1.00 - Initial fix for 3.17.0 upgrade regression.
 *         Source function: wprentals/libs/dashboard-functions/dashboard-links-functions.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'wpestate_get_all_dashboard_template_links' ) ) :
    function wpestate_get_all_dashboard_template_links() {
        $transient_name = 'wpestate_dashboard_links';
        if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
            $transient_name .= '_' . ICL_LANGUAGE_CODE;
        }

        $dashboard_links = wpestate_request_transient_cache( $transient_name );
        if ( $dashboard_links !== false && ! empty( $dashboard_links ) ) {
            return $dashboard_links;
        }

        $templates = array(
            'user_dashboard_main.php',
            'user_dashboard_add_step1.php',
            'user_dashboard_profile.php',
            'user_dashboard_packs.php',
            'user_dashboard_favorite.php',
            'user_dashboard.php',
            'user_dashboard_searches.php',
            'user_dashboard_my_reservations.php',
            'user_dashboard_my_bookings.php',
            'user_dashboard_inbox.php',
            'user_dashboard_invoices.php',
            'user_dashboard_edit_listing.php',
            'user_dashboard_my_reviews.php',
            'compare_listings.php',
            'ical.php',
            'user_dashboard_allinone.php',
            'processor.php',
            'stripecharge.php',
            'advanced_search_results.php',
            'terms_conditions.php',
        );

        // Fix: 'number' => 0 means unlimited (WordPress default).
        // Parent theme used count($templates) = 21 which cuts off pages whose
        // titles sort alphabetically past position 21 (e.g., "My Listings").
        $pages = get_pages(
            array(
                'meta_key'   => '_wp_page_template',
                'meta_value' => $templates,
                'number'     => 0,
            )
        );

        $dashboard_links = array();

        if ( ! empty( $pages ) ) {
            $page_ids = wp_list_pluck( $pages, 'ID' );
            update_postmeta_cache( $page_ids );

            foreach ( $pages as $page ) {
                $template = get_post_meta( $page->ID, '_wp_page_template', true );
                if ( $template && in_array( $template, $templates, true ) ) {
                    // First match wins; lower page IDs (earlier installs) take priority.
                    if ( ! isset( $dashboard_links[ $template ] ) ) {
                        $dashboard_links[ $template ] = esc_url( get_permalink( $page->ID ) );
                    }
                }
            }

            wpestate_set_transient_cache( $transient_name, $dashboard_links, 60 * 60 * 24 );
        }

        return $dashboard_links;
    }
endif;
