<?php
/**
 * Example Test Case
 *
 * This is a sample test case to verify PHPUnit is working correctly.
 *
 * @package    HNFO
 * @author     Rank Rocket Co
 * @copyright  2026 Rank Rocket Co - All Rights Reserved
 */

use PHPUnit\Framework\TestCase;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillsTestCase;

/**
 * Example test case class.
 */
class ExampleTest extends PolyfillsTestCase {

	/**
	 * Test that assertions work correctly.
	 *
	 * @return void
	 */
	public function test_example_assertion() {
		$this->assertTrue( true );
		$this->assertEquals( 2, 1 + 1 );
	}

	/**
	 * Test string operations.
	 *
	 * @return void
	 */
	public function test_string_operations() {
		$string = 'Hello WordPress';
		$this->assertStringContainsString( 'WordPress', $string );
		$this->assertEquals( 15, strlen( $string ) );
	}

	/**
	 * Test array operations.
	 *
	 * @return void
	 */
	public function test_array_operations() {
		$array = [ 'one', 'two', 'three' ];
		$this->assertCount( 3, $array );
		$this->assertContains( 'two', $array );
	}
}
