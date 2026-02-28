<?php 
require_once WPESTATE_PLUGIN_PATH . 'api/rest/utilities-functions.php';



require_once WPESTATE_PLUGIN_PATH . 'api/rest/properties/properties_routes.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/properties/properties_functions.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/properties/property_create.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/properties/property_update.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/properties/property_delete.php';



require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_routes.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_functions.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_retrive.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_create.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_update.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_delete.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_availability.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/bookings/bookings_estimate.php';

// Core components for reviews CRUD operations via the REST API
require_once WPESTATE_PLUGIN_PATH . 'api/rest/reviews/reviews_routes.php';  // API route definitions
require_once WPESTATE_PLUGIN_PATH . 'api/rest/reviews/reviews_functions.php'; // Helper functions 
require_once WPESTATE_PLUGIN_PATH . 'api/rest/reviews/review_create.php';    // Reviews creation
require_once WPESTATE_PLUGIN_PATH . 'api/rest/reviews/review_update.php';    // Reviews updating
require_once WPESTATE_PLUGIN_PATH . 'api/rest/reviews/review_delete.php';    // Reviews deletion


require_once WPESTATE_PLUGIN_PATH . 'api/rest/invoices/invoices_routes.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/invoices/invoices_functions.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/invoices/invoices_retrive.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/invoices/invoices_create.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/invoices/invoices_update.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/invoices/invoices_delete.php';
require_once WPESTATE_PLUGIN_PATH . 'api/rest/invoices/invoices_customer.php';

