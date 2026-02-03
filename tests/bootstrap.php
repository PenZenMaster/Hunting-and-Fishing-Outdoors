<?php
/**
 * PHPUnit Bootstrap File
 *
 * This file is used to bootstrap the PHPUnit testing environment.
 * It loads WordPress test suite and sets up the testing environment.
 *
 * @package    HNFO
 * @author     Rank Rocket Co
 * @copyright  2026 Rank Rocket Co - All Rights Reserved
 */

// Composer autoloader
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Define constants for testing
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__ ) . '/' );
}

// Load Yoast PHPUnit Polyfills
require_once dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

/*
 * WordPress Test Suite Setup
 *
 * To run tests against WordPress core, you need to:
 * 1. Install WordPress test suite:
 *    bash bin/install-wp-tests.sh wordpress_test root '' localhost latest
 *
 * 2. Uncomment the following line and adjust the path:
 *    require_once '/tmp/wordpress-tests-lib/includes/bootstrap.php';
 *
 * For plugin/theme unit tests without WordPress core, use mocks instead.
 */

// Example: Load plugin for testing
// tests_add_filter( 'muplugins_loaded', function() {
//     require dirname( __DIR__ ) . '/wp-content/plugins/your-plugin/your-plugin.php';
// } );

echo "PHPUnit Bootstrap Loaded\n";
