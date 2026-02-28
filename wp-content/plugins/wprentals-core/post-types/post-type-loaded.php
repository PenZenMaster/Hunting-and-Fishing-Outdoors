<?php
/**
 * Combined Post Types Loader
 * 
 * Centralizes all post type related functionality including registration,
 * taxonomies, metaboxes, and utility functions.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Agent
require_once WPESTATE_PLUGIN_PATH . 'post-types/agent/agent-post-type.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/agent/agent-metaboxes.php';

// Booking
require_once WPESTATE_PLUGIN_PATH . 'post-types/booking/booking-post-type.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/booking/booking-metaboxes.php';

// Invoice
require_once WPESTATE_PLUGIN_PATH . 'post-types/invoice/invoice-post-type.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/invoice/invoice-metaboxes.php';

// Membership
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-actions.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-ajax.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-functions.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-metaboxes.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-post-type.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-status-functions.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-user-verifications.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/membership/membership-utils.php';

// Messages
require_once dirname(__FILE__) . '/message/message-post-type.php';
require_once dirname(__FILE__) . '/message/message-metaboxes.php';

// Property
require_once dirname(__FILE__) . '/property/property-post-type.php';
require_once dirname(__FILE__) . '/property/property-admin-columns.php';
require_once dirname(__FILE__) . '/property/property-features-taxonomy.php';
require_once dirname(__FILE__) . '/property/property-help-functions.php';
require_once dirname(__FILE__) . '/property/property-city-taxonomy.php';
require_once dirname(__FILE__) . '/property/property-area-taxonomy.php';
require_once dirname(__FILE__) . '/property/property-category-taxonomy.php';
require_once dirname(__FILE__) . '/property/property-action-taxonomy.php';
require_once dirname(__FILE__) . '/property/property-metaboxes.php';
require_once dirname(__FILE__) . '/property/property-status.php';
require_once dirname(__FILE__) . '/property/property-utils.php';

// Searches
require_once WPESTATE_PLUGIN_PATH . 'post-types/search/search-post-type.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/search/search-metaboxes.php';

// Reviews
require_once WPESTATE_PLUGIN_PATH . 'post-types/reviews/reviews-post-type.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/reviews/reviews-admin-columns.php';
require_once WPESTATE_PLUGIN_PATH . 'post-types/reviews/reviews-metaboxes.php';


// Property permalink structure
add_action('init', function() {
    add_rewrite_rule(
        '^properties/([^/]*)/?',
        'index.php?post_type=estate_property&name=$matches[1]',
        'top'
    );
});