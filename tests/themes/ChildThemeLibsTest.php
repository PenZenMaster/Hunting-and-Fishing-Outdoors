<?php
/**
 * Module/Script Name: ChildThemeLibsTest.php
 * Path: tests/themes/ChildThemeLibsTest.php
 *
 * Description:
 * Unit tests for the HNFO child theme lib files. Tests focus on verifiable
 * source-level properties (file existence, security guards, the specific bug
 * fix that motivated each file) rather than WP runtime behaviour, since the
 * WP test suite is not loaded in this environment.
 *
 * Covered files:
 *   libs/dashboard-link-fix.php   - 21-page limit regression fix
 *   libs/ajax-half-day-booking.php - half-day booking AJAX handlers
 *   libs/places-proxy.php          - server-side Places API proxy
 *
 * Author(s):
 * Rank Rocket Co (C) Copyright 2026 - All Rights Reserved
 *
 * Created Date: 2026-02-28
 * Last Modified Date: 2026-02-28
 *
 * Comments:
 * v1.00 - Initial test coverage for child theme lib files.
 */

use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillsTestCase;

/**
 * Tests for child theme lib files.
 */
class ChildThemeLibsTest extends PolyfillsTestCase {

	/** @var string Absolute path to the child theme root. */
	private static $child_dir;

	/**
	 * Set up paths shared across tests.
	 *
	 * @return void
	 */
	public static function set_up_before_class(): void {
		parent::set_up_before_class();
		self::$child_dir = dirname( __DIR__, 2 ) . '/wp-content/themes/wprentals-child';
	}

	// -------------------------------------------------------------------------
	// dashboard-link-fix.php
	// -------------------------------------------------------------------------

	/**
	 * The file must exist so the child theme can override the parent function.
	 *
	 * @return void
	 */
	public function test_dashboard_link_fix_file_exists(): void {
		$this->assertFileExists(
			self::$child_dir . '/libs/dashboard-link-fix.php',
			'dashboard-link-fix.php must exist in child theme libs/'
		);
	}

	/**
	 * The ABSPATH guard must be present to prevent direct file execution.
	 *
	 * @return void
	 */
	public function test_dashboard_link_fix_has_abspath_guard(): void {
		$source = file_get_contents( self::$child_dir . '/libs/dashboard-link-fix.php' );
		$this->assertStringContainsString(
			"defined( 'ABSPATH' )",
			$source,
			'dashboard-link-fix.php must have an ABSPATH direct-access guard'
		);
	}

	/**
	 * The core bug fix: get_pages() must be called with 'number' => 0 (unlimited).
	 * The parent theme used count($templates) = 21 which cut off pages past
	 * position 21. Any value other than 0 would reintroduce the regression.
	 *
	 * @return void
	 */
	public function test_dashboard_link_fix_uses_unlimited_page_count(): void {
		$source = file_get_contents( self::$child_dir . '/libs/dashboard-link-fix.php' );
		$this->assertStringContainsString(
			"'number' => 0",
			$source,
			"get_pages() must use 'number' => 0 (unlimited) to prevent the 21-page cutoff regression"
		);
	}

	/**
	 * The function_exists guard must be present so the child definition takes
	 * priority over the parent on the first load.
	 *
	 * @return void
	 */
	public function test_dashboard_link_fix_has_function_exists_guard(): void {
		$source = file_get_contents( self::$child_dir . '/libs/dashboard-link-fix.php' );
		$this->assertStringContainsString(
			"function_exists( 'wpestate_get_all_dashboard_template_links' )",
			$source,
			'dashboard-link-fix.php must guard the function definition with function_exists()'
		);
	}

	/**
	 * The template list must include the dashboard pages known to sort past
	 * position 21 alphabetically (the pages that triggered the original bug).
	 *
	 * @return void
	 */
	public function test_dashboard_link_fix_includes_critical_templates(): void {
		$source    = file_get_contents( self::$child_dir . '/libs/dashboard-link-fix.php' );
		$must_have = array(
			'user_dashboard_edit_listing.php',
			'user_dashboard_my_bookings.php',
			'user_dashboard_my_reservations.php',
		);
		foreach ( $must_have as $template ) {
			$this->assertStringContainsString(
				$template,
				$source,
				"Template '{$template}' must be present in dashboard-link-fix.php template list"
			);
		}
	}

	// -------------------------------------------------------------------------
	// ajax-half-day-booking.php
	// -------------------------------------------------------------------------

	/**
	 * The file must exist to preserve half-day booking after the 3.17.0 upgrade.
	 *
	 * @return void
	 */
	public function test_half_day_booking_file_exists(): void {
		$this->assertFileExists(
			self::$child_dir . '/libs/ajax-half-day-booking.php',
			'ajax-half-day-booking.php must exist in child theme libs/'
		);
	}

	/**
	 * Both AJAX handler functions must be defined in the file.
	 *
	 * @return void
	 */
	public function test_half_day_booking_defines_both_handler_functions(): void {
		$source = file_get_contents( self::$child_dir . '/libs/ajax-half-day-booking.php' );
		$this->assertStringContainsString(
			'function wpestate_ajax_update_listing_price',
			$source,
			'wpestate_ajax_update_listing_price() must be defined in ajax-half-day-booking.php'
		);
		$this->assertStringContainsString(
			'function wpestate_ajax_show_booking_costs',
			$source,
			'wpestate_ajax_show_booking_costs() must be defined in ajax-half-day-booking.php'
		);
	}

	/**
	 * Both handlers must verify the nonce before processing data.
	 *
	 * @return void
	 */
	public function test_half_day_booking_has_nonce_verification(): void {
		$source = file_get_contents( self::$child_dir . '/libs/ajax-half-day-booking.php' );
		$this->assertStringContainsString(
			'check_ajax_referer',
			$source,
			'ajax-half-day-booking.php must use check_ajax_referer() for CSRF protection'
		);
	}

	/**
	 * The price handler must verify the user is logged in before mutating data.
	 *
	 * @return void
	 */
	public function test_half_day_booking_has_authentication_check(): void {
		$source = file_get_contents( self::$child_dir . '/libs/ajax-half-day-booking.php' );
		$this->assertStringContainsString(
			'is_user_logged_in',
			$source,
			'ajax-half-day-booking.php must check is_user_logged_in() before processing'
		);
	}

	// -------------------------------------------------------------------------
	// libs/places-proxy.php
	// -------------------------------------------------------------------------

	/**
	 * The proxy file must exist so the server-side Places API route is available.
	 *
	 * @return void
	 */
	public function test_places_proxy_file_exists(): void {
		$this->assertFileExists(
			self::$child_dir . '/libs/places-proxy.php',
			'places-proxy.php must exist in child theme libs/'
		);
	}

	/**
	 * Both proxy AJAX actions must be registered (autocomplete and details).
	 *
	 * @return void
	 */
	public function test_places_proxy_registers_both_ajax_actions(): void {
		$source = file_get_contents( self::$child_dir . '/libs/places-proxy.php' );
		$this->assertStringContainsString(
			'hnfo_places_autocomplete',
			$source,
			'places-proxy.php must register the hnfo_places_autocomplete AJAX action'
		);
		$this->assertStringContainsString(
			'hnfo_places_details',
			$source,
			'places-proxy.php must register the hnfo_places_details AJAX action'
		);
	}

	/**
	 * The proxy must verify the nonce on every request.
	 *
	 * @return void
	 */
	public function test_places_proxy_has_nonce_verification(): void {
		$source = file_get_contents( self::$child_dir . '/libs/places-proxy.php' );
		$this->assertStringContainsString(
			'check_ajax_referer',
			$source,
			'places-proxy.php must use check_ajax_referer() on every proxy request'
		);
	}

	/**
	 * The proxy must call the Places API (New) endpoint, not the legacy endpoint.
	 * WPRentals 3.17.0 uses /v1/ and the proxy must match.
	 *
	 * @return void
	 */
	public function test_places_proxy_targets_new_api_endpoint(): void {
		$source = file_get_contents( self::$child_dir . '/libs/places-proxy.php' );
		$this->assertStringContainsString(
			'places.googleapis.com/v1/',
			$source,
			'places-proxy.php must call the Places API (New) /v1/ endpoint to match WPRentals 3.17.0'
		);
	}
}
