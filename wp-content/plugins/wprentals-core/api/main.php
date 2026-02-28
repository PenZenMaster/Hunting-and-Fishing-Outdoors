<?php
/**
* WPRentals API Bootstrap File
* 
* This file serves as the main entry point for the WPRentals API system.
* It loads all API-related components and initializes the REST API if enabled.
*
* @package WPRentals
* @subpackage API
* @since 4.0
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit;
}

/**
* Load API Components
* Each component handles a specific aspect of the API functionality
*/

// User Management Components
require_once WPESTATE_PLUGIN_PATH . 'api/user/user.php';             // Core user functionality
require_once WPESTATE_PLUGIN_PATH . 'api/user/capabilities.php';      // User capability definitions
require_once WPESTATE_PLUGIN_PATH . 'api/user/user-role-assignment.php';  // Role assignment handling
require_once WPESTATE_PLUGIN_PATH . 'api/user/capability-management.php';  // Capability management functions

// System Components
require_once WPESTATE_PLUGIN_PATH . 'api/updates/updates.php';        // Update system functionality

require_once WPESTATE_PLUGIN_PATH . 'api/cache/cache.php';           // Caching system implementation
require_once WPESTATE_PLUGIN_PATH . 'api/data_query/index.php';      // Data query handling
require_once WPESTATE_PLUGIN_PATH . 'api/listings/block_functions.php'; // Listing block helpers
require_once WPESTATE_PLUGIN_PATH . 'api/inc/taxonomies_functions/term-custom-fields.php';
require_once WPESTATE_PLUGIN_PATH . 'api/inc/taxonomies_functions/term-description-editor.php';


// only for developers
//require_once WPESTATE_PLUGIN_PATH . 'api/developer/main.php';         // Developer tools and utilities


/**
* Initialize REST API if enabled
* 
* Hooks into WordPress init action to ensure proper timing for Redux Framework
* initialization before checking API settings.
*
* @since 4.0
*/
add_action('init', function() {
   // Access Redux options
   global $wprentals_admin;
   
   // Check if API is enabled in Redux settings
   // Default to 'no' if setting not found
   $api_enabled = wprentals_get_option('wp_estate_enable_api', '', 'no');
   
   // Load REST API components if enabled
   if($api_enabled === 'yes') {
       require_once WPESTATE_PLUGIN_PATH . 'api/rest/index.php';
   }
}, 15); // Priority 15 ensures Redux (priority 1) has loaded first