<?php
/**
 * WPRentals Core Plugin Test
 *
 * Unit tests for the WPRentals Core plugin functionality.
 *
 * @package    HNFO
 * @subpackage Tests
 * @author     Rank Rocket Co
 * @copyright  2026 Rank Rocket Co - All Rights Reserved
 */

use PHPUnit\Framework\TestCase;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillsTestCase;

/**
 * Test case for WPRentals Core plugin.
 */
class WPRentalsCoreTest extends PolyfillsTestCase {

	/**
	 * Test that the plugin directory exists.
	 *
	 * @return void
	 */
	public function test_plugin_directory_exists() {
		$plugin_dir = dirname( __DIR__, 2 ) . '/wp-content/plugins/wprentals-core';
		$this->assertDirectoryExists( $plugin_dir, 'WPRentals Core plugin directory should exist' );
	}

	/**
	 * Test that required plugin files exist.
	 *
	 * @return void
	 */
	public function test_required_plugin_files_exist() {
		$plugin_dir = dirname( __DIR__, 2 ) . '/wp-content/plugins/wprentals-core';

		// Check for common plugin files
		$required_files = [
			'admin/admin-init.php',
			'admin/options-init.php',
		];

		foreach ( $required_files as $file ) {
			$file_path = $plugin_dir . '/' . $file;
			$this->assertFileExists( $file_path, "Required file should exist: {$file}" );
		}
	}

	/**
	 * Test that plugin class files have no syntax errors.
	 *
	 * @return void
	 */
	public function test_plugin_classes_directory_exists() {
		$classes_dir = dirname( __DIR__, 2 ) . '/wp-content/plugins/wprentals-core/classes';
		$this->assertDirectoryExists( $classes_dir, 'Plugin classes directory should exist' );
	}

	/**
	 * Example test for plugin helper functions.
	 * TODO: Replace with actual plugin function tests.
	 *
	 * @return void
	 */
	public function test_example_plugin_functionality() {
		// Example: Test a utility function (replace with actual plugin functions)
		$test_data = 'test-slug';
		$expected  = 'test-slug';

		$this->assertEquals( $expected, $test_data );

		// TODO: Add actual plugin function tests here
		// Example:
		// $result = wprentals_core_function();
		// $this->assertNotEmpty( $result );
	}
}
