<?php
/**
 * WPRentals Elementor Plugin Test
 *
 * Unit tests for the WPRentals Elementor plugin functionality.
 *
 * @package    HNFO
 * @subpackage Tests
 * @author     Rank Rocket Co
 * @copyright  2026 Rank Rocket Co - All Rights Reserved
 */

use PHPUnit\Framework\TestCase;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillsTestCase;

/**
 * Test case for WPRentals Elementor plugin.
 */
class WPRentalsElementorTest extends PolyfillsTestCase {

	/**
	 * Test that the plugin directory exists.
	 *
	 * @return void
	 */
	public function test_plugin_directory_exists() {
		$plugin_dir = dirname( __DIR__, 2 ) . '/wp-content/plugins/wprentals-elementor';
		$this->assertDirectoryExists( $plugin_dir, 'WPRentals Elementor plugin directory should exist' );
	}

	/**
	 * Example test for Elementor widget registration.
	 * TODO: Replace with actual Elementor widget tests.
	 *
	 * @return void
	 */
	public function test_example_elementor_widget() {
		// Example test structure
		$widget_name = 'wprentals-property-widget';

		$this->assertIsString( $widget_name );
		$this->assertNotEmpty( $widget_name );

		// TODO: Add actual Elementor widget tests here
		// Example:
		// $widget = new WPRentals_Property_Widget();
		// $this->assertInstanceOf( 'Elementor\Widget_Base', $widget );
	}
}
