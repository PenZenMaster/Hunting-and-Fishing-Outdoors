<?php
/**
 * WPRentals Theme Test
 *
 * Unit tests for the WPRentals theme functionality.
 *
 * @package    HNFO
 * @subpackage Tests
 * @author     Rank Rocket Co
 * @copyright  2026 Rank Rocket Co - All Rights Reserved
 */

use PHPUnit\Framework\TestCase;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillsTestCase;

/**
 * Test case for WPRentals theme.
 */
class WPRentalsThemeTest extends PolyfillsTestCase {

	/**
	 * Test that the theme directory exists.
	 *
	 * @return void
	 */
	public function test_theme_directory_exists() {
		$theme_dir = dirname( __DIR__, 2 ) . '/wp-content/themes/wprentals';
		$this->assertDirectoryExists( $theme_dir, 'WPRentals theme directory should exist' );
	}

	/**
	 * Test that child theme directory exists.
	 *
	 * @return void
	 */
	public function test_child_theme_directory_exists() {
		$child_theme_dir = dirname( __DIR__, 2 ) . '/wp-content/themes/wprentals-child';
		$this->assertDirectoryExists( $child_theme_dir, 'WPRentals child theme directory should exist' );
	}

	/**
	 * Test that required theme files exist.
	 *
	 * @return void
	 */
	public function test_required_theme_files_exist() {
		$theme_dir = dirname( __DIR__, 2 ) . '/wp-content/themes/wprentals';

		// Check for required WordPress theme files
		$required_files = [
			'style.css',
			'functions.php',
			'index.php',
		];

		foreach ( $required_files as $file ) {
			$file_path = $theme_dir . '/' . $file;
			$this->assertFileExists( $file_path, "Required theme file should exist: {$file}" );
		}
	}

	/**
	 * Test child theme style.css exists.
	 *
	 * @return void
	 */
	public function test_child_theme_stylesheet_exists() {
		$child_theme_dir = dirname( __DIR__, 2 ) . '/wp-content/themes/wprentals-child';
		$stylesheet      = $child_theme_dir . '/style.css';

		$this->assertFileExists( $stylesheet, 'Child theme stylesheet should exist' );
	}

	/**
	 * Example test for theme helper functions.
	 * TODO: Replace with actual theme function tests.
	 *
	 * @return void
	 */
	public function test_example_theme_functionality() {
		// Example: Test a theme utility function
		$test_value = 'test-value';
		$this->assertIsString( $test_value );

		// TODO: Add actual theme function tests here
		// Example:
		// $result = wprentals_get_property_meta( $property_id );
		// $this->assertIsArray( $result );
	}
}
