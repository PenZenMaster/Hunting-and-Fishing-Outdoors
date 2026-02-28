<?php
/**
 * Invoice constants.
 *
 * This file exposes the numeric identifiers that are persisted in the
 * `invoice_type` post meta field. Each constant replaces the previously
 * translated text values that were stored prior to the refactor.
 */

if (!defined('WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED')) {
    // Define the invoice type identifiers only once to avoid redefinition notices.
    define('WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED', 1);
    define('WP_ESTATE_INVOICE_TYPE_PUBLISH_WITH_FEATURED', 2);
    define('WP_ESTATE_INVOICE_TYPE_PACKAGE', 3);
    define('WP_ESTATE_INVOICE_TYPE_LISTING', 4);
    define('WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE', 5);
}

