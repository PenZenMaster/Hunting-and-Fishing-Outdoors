# Testing & Quality Assurance Guide

## Overview

This project uses Test-Driven Development (TDD) practices with automated quality gates to maintain code quality and prevent regressions.

## Testing Infrastructure

### Components

- **PHPUnit 9.6**: Unit testing framework
- **PHP_CodeSniffer**: WordPress Coding Standards enforcement
- **Git Hooks**: Pre-commit and pre-push quality gates
- **Yoast PHPUnit Polyfills**: Cross-version PHPUnit compatibility

### Directory Structure

```
tests/
├── bootstrap.php              # Test environment setup
├── ExampleTest.php            # Example test case
├── plugins/                   # Plugin tests
│   ├── WPRentalsCoreTest.php
│   └── WPRentalsElementorTest.php
└── themes/                    # Theme tests
    └── WPRentalsThemeTest.php
```

## Running Tests

### Run All Tests

```bash
composer test
```

### Run Specific Test Suite

```bash
# Plugin tests only
vendor/bin/phpunit --testsuite="Plugin Tests"

# Theme tests only
vendor/bin/phpunit --testsuite="Theme Tests"
```

### Run Single Test File

```bash
vendor/bin/phpunit tests/plugins/WPRentalsCoreTest.php
```

### Run with Code Coverage

```bash
vendor/bin/phpunit --coverage-html tests/coverage
```

Then open `tests/coverage/index.html` in your browser.

## Coding Standards

### Check Coding Standards

```bash
composer phpcs
```

### Auto-Fix Coding Standards

```bash
composer phpcbf
```

### Check Specific Files

```bash
vendor/bin/phpcs wp-content/plugins/wprentals-core/
```

## Git Hooks (Automated Quality Gates)

### Pre-Commit Hook

Runs **before every commit**:

1. ✓ WordPress Coding Standards (PHPCS) on staged files
2. ✓ PHPUnit tests

**Bypass** (emergency only):
```bash
git commit --no-verify -m "Emergency commit"
```

### Pre-Push Hook

Runs **before pushing to remote**:

1. ✓ Full test suite with coverage
2. ✓ Coding standards on all files
3. ✓ PHP syntax validation

**Bypass** (emergency only):
```bash
git push --no-verify
```

## Test-Driven Development (TDD) Workflow

### 1. Write a Failing Test

Create a test that describes the desired functionality:

```php
<?php
class MyFeatureTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase {

    public function test_my_new_feature() {
        $result = my_new_function( 'input' );
        $this->assertEquals( 'expected-output', $result );
    }
}
```

### 2. Run Tests (Should Fail)

```bash
composer test
```

### 3. Write Minimal Code to Pass

Implement just enough code to make the test pass.

### 4. Refactor

Improve the code while keeping tests green.

### 5. Repeat

Add more test cases and functionality incrementally.

## Writing Tests

### Test Class Template

```php
<?php
/**
 * Test Description
 *
 * @package    HNFO
 * @subpackage Tests
 * @author     Rank Rocket Co
 * @copyright  2026 Rank Rocket Co - All Rights Reserved
 */

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class MyTest extends TestCase {

    /**
     * Setup before each test.
     *
     * @return void
     */
    public function set_up() {
        parent::set_up();
        // Setup code here
    }

    /**
     * Cleanup after each test.
     *
     * @return void
     */
    public function tear_down() {
        // Cleanup code here
        parent::tear_down();
    }

    /**
     * Test description.
     *
     * @return void
     */
    public function test_something() {
        $this->assertTrue( true );
    }
}
```

### Common Assertions

```php
// Equality
$this->assertEquals( $expected, $actual );
$this->assertSame( $expected, $actual ); // Strict comparison

// Boolean
$this->assertTrue( $condition );
$this->assertFalse( $condition );

// Null
$this->assertNull( $value );
$this->assertNotNull( $value );

// Empty
$this->assertEmpty( $value );
$this->assertNotEmpty( $value );

// Arrays
$this->assertCount( 3, $array );
$this->assertContains( 'item', $array );
$this->assertArrayHasKey( 'key', $array );

// Strings
$this->assertStringContainsString( 'needle', 'haystack' );
$this->assertMatchesRegularExpression( '/pattern/', $string );

// Files
$this->assertFileExists( '/path/to/file' );
$this->assertDirectoryExists( '/path/to/dir' );

// Exceptions
$this->expectException( \Exception::class );
$this->expectExceptionMessage( 'Error message' );
```

## Best Practices

### 1. Test Naming

- Use descriptive test names: `test_user_can_create_property()`
- Follow pattern: `test_[what]_[condition]_[expected_result]()`

### 2. Test Organization

- One assertion concept per test
- Use `setUp()` and `tearDown()` for common code
- Group related tests in test classes

### 3. Test Coverage

- Aim for 80%+ code coverage on custom code
- Focus on business logic and critical paths
- Don't test framework code (WordPress core)

### 4. Mock External Dependencies

```php
// Example: Mock WordPress functions
function wp_get_current_user() {
    return (object) [ 'ID' => 1, 'user_login' => 'testuser' ];
}
```

### 5. Keep Tests Fast

- Avoid database calls when possible
- Use mocks for external services
- Group slow tests separately

## Continuous Integration (CI)

GitHub Actions automatically runs tests on:

- Every push to any branch
- Every pull request

See `.github/workflows/tests.yml` for configuration.

## Troubleshooting

### Tests Are Slow

- Enable code coverage only when needed
- Use `--no-coverage` flag for faster runs
- Check for database calls or external API requests

### Hook Blocking Commit

```bash
# Fix issues first (recommended)
composer phpcbf  # Auto-fix coding standards
composer test    # Run tests

# Or bypass (emergency only)
git commit --no-verify
```

### PHPUnit Not Found

```bash
composer install
```

### Xdebug Not Available

Code coverage requires Xdebug. Install it:

```bash
# Windows (via Chocolatey)
choco install php-xdebug

# Linux/Mac
pecl install xdebug
```

## Resources

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Yoast PHPUnit Polyfills](https://github.com/Yoast/PHPUnit-Polyfills)

## Support

For issues or questions about testing:

1. Check this documentation
2. Review existing tests for examples
3. Consult the team lead

---

**Remember**: Quality code is tested code. Write tests first, then implement features.
