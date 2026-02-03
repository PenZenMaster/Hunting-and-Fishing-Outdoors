# WordPress Development Environment Setup Complete

**Project**: HNFO WordPress Development
**Setup Date**: 2026-02-02
**PHP Version**: 8.4.2
**Composer Version**: 2.8.8

---

## Setup Summary

All tasks have been completed successfully:

### 1. Git Configuration

**Created**: `.gitignore`

- WordPress core files excluded from version control
- Only custom themes and plugins will be tracked
- Vendor directory, uploads, and cache excluded
- IDE files and OS files excluded

**Git Status**: Clean - only development configuration files are untracked

### 2. WordPress Coding Standards Documentation

**Created Files**:
- `WORDPRESS-STANDARDS.md` - Comprehensive WordPress coding standards reference
- `.claude-wordpress.md` - Project-specific WordPress development rules for Claude Code

**Standards Covered**:
- PHP Coding Standards (tabs, naming, Yoda conditions)
- JavaScript Standards (camelCase, strict equality)
- CSS Standards (lowercase with hyphens)
- HTML Standards (semantic markup)
- Accessibility Standards (WCAG 2.0 Level AA)
- Internationalization (i18n with text domain)
- Security Best Practices (sanitization, escaping, nonces)
- Inline Documentation Standards

**Project Prefixes**:
- Text Domain: `hnfo`
- Function Prefix: `hnfo_`
- Class Prefix: `HNFO_`
- Constant Prefix: `HNFO_`

### 3. Development Tools Installation

**Installed via Composer**:

1. **PHP_CodeSniffer** (v3.13.5)
   - WordPress Coding Standards (v3.3.0)
   - PHPCompatibility for WordPress (v2.1.8)
   - Checks code against WordPress standards

2. **PHPUnit** (v9.6.34)
   - Unit testing framework
   - Yoast PHPUnit Polyfills (v2.0.5)
   - Example test included and passing

**Configuration Files**:
- `composer.json` - PHP dependencies and scripts
- `phpcs.xml.dist` - PHP_CodeSniffer configuration
- `phpunit.xml.dist` - PHPUnit configuration
- `tests/bootstrap.php` - PHPUnit bootstrap file

**Available Coding Standards**:
- WordPress
- WordPress-Core
- WordPress-Extra
- WordPress-Docs
- PHPCompatibility
- PHPCompatibilityWP
- PSR12, PSR2, PSR1
- Squiz, PEAR, Zend

---

## Quick Start Commands

### Check Code Quality

```cmd
composer phpcs
```

Or check specific files:
```cmd
vendor\bin\phpcs wp-content\plugins\your-plugin
```

### Auto-Fix Code Style Issues

```cmd
composer phpcbf
```

### Run Unit Tests

```cmd
composer test
```

Or run specific test:
```cmd
vendor\bin\phpunit tests\ExampleTest.php
```

### View Available Standards

```cmd
vendor\bin\phpcs -i
```

---

## Verification Results

### PHP_CodeSniffer
- Status: Working
- Standards Installed: 18 total (including all WordPress standards)
- Configuration: Valid

### PHPUnit
- Status: Working
- Example Test: 3 tests, 6 assertions - all passing
- Bootstrap: Loaded successfully
- Coverage Driver: Not available (optional - can install Xdebug if needed)

### Git
- Status: Configured
- Ignored Files: WordPress core, vendor, uploads, cache
- Tracked Files: Only development config files and custom code

---

## Project Structure

```
D:\local\HNFO-DEV\app\public\
+-- .gitignore                 [Git ignore rules]
+-- README.md                  [Project documentation]
+-- PROJECT-SETUP.md           [This file - setup summary]
+-- WORDPRESS-STANDARDS.md     [WordPress coding standards reference]
+-- .claude-wordpress.md       [Claude Code WordPress rules]
+-- composer.json              [PHP dependencies]
+-- composer.lock              [Dependency lock file]
+-- phpcs.xml.dist             [PHPCS configuration]
+-- phpunit.xml.dist           [PHPUnit configuration]
+-- vendor/                    [Composer dependencies - not tracked]
+-- tests/                     [Unit tests directory]
|   +-- bootstrap.php          [PHPUnit bootstrap]
|   +-- ExampleTest.php        [Example test case]
|   +-- plugins/               [Plugin tests directory]
|   +-- themes/                [Theme tests directory]
|   +-- coverage/              [Code coverage reports]
+-- wp-admin/                  [WordPress core - not tracked]
+-- wp-includes/               [WordPress core - not tracked]
+-- wp-content/                [WordPress content]
    +-- plugins/               [Custom plugins - will be tracked]
    +-- themes/                [Custom themes - will be tracked]
    +-- uploads/               [Media files - not tracked]
```

---

## Next Steps

### 1. Initialize Git Repository

```cmd
git add .gitignore README.md composer.json composer.lock phpcs.xml.dist phpunit.xml.dist WORDPRESS-STANDARDS.md .claude-wordpress.md PROJECT-SETUP.md
git commit -m "chore: initial WordPress development environment setup

- Configure .gitignore for WordPress project
- Add WordPress coding standards documentation
- Install PHP_CodeSniffer with WordPress standards
- Install PHPUnit testing framework
- Configure development tools and quality gates

Co-Authored-By: Claude Sonnet 4.5 <noreply@anthropic.com>"
```

### 2. Create Your First Custom Plugin

```cmd
mkdir wp-content\plugins\hnfo-plugin
```

Create `wp-content\plugins\hnfo-plugin\hnfo-plugin.php`:

```php
<?php
/**
 * Plugin Name: HNFO Plugin
 * Description: Custom plugin for HNFO
 * Version: 1.0.0
 * Author: Rank Rocket Co
 * Text Domain: hnfo
 *
 * @package HNFO
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
```

### 3. Create Your First Custom Theme

```cmd
mkdir wp-content\themes\hnfo-theme
```

Create theme files following WordPress theme structure.

### 4. Run Quality Checks

Before committing any code:

```cmd
composer phpcs
composer test
```

---

## Development Workflow

### Adding New Features

1. Create feature branch: `git checkout -b feature/feature-name`
2. Write code following WordPress standards (see `.claude-wordpress.md`)
3. Add unit tests for new functionality
4. Run quality checks: `composer phpcs && composer test`
5. Auto-fix style issues if needed: `composer phpcbf`
6. Commit with conventional commit message: `git commit -m "feat: description"`
7. Push and create pull request

### Quality Gate (Must Pass Before Commit)

```cmd
vendor\bin\phpcs --standard=phpcs.xml.dist && vendor\bin\phpunit
```

---

## Important Reminders

### Security (Non-Negotiable)

- Always sanitize user input
- Always escape output
- Use nonces for form submissions
- Check user capabilities
- Use `$wpdb->prepare()` for database queries

### Code Style

- Use tabs for indentation (not spaces)
- Follow WordPress naming conventions
- Add proper docblocks to all functions/classes
- Use Yoda conditions in comparisons

### Internationalization

- Wrap all user-facing strings with translation functions
- Always use text domain: `hnfo`
- Example: `__( 'Text', 'hnfo' )` or `esc_html__( 'Text', 'hnfo' )`

### Prefixing

- All functions: `hnfo_function_name()`
- All classes: `HNFO_Class_Name`
- All constants: `HNFO_CONSTANT_NAME`
- All hooks: `hnfo_hook_name`

---

## Resources

### Documentation
- WordPress Coding Standards: https://developer.wordpress.org/coding-standards/
- WordPress Plugin Handbook: https://developer.wordpress.org/plugins/
- WordPress Theme Handbook: https://developer.wordpress.org/themes/
- PHP_CodeSniffer: https://github.com/squizlabs/PHP_CodeSniffer/wiki
- PHPUnit: https://phpunit.de/documentation.html

### Project Files
- Full Standards Reference: `WORDPRESS-STANDARDS.md`
- Claude Code Rules: `.claude-wordpress.md`
- Project Documentation: `README.md`

---

## Support & Troubleshooting

### PHPCS Not Finding Files

Make sure you're checking the correct directories:
```cmd
vendor\bin\phpcs wp-content\plugins
vendor\bin\phpcs wp-content\themes
```

### PHPUnit Tests Not Running

Verify test location and naming:
- Tests must be in `tests/` directory or subdirectories
- Test files must end with `Test.php`
- Test classes must extend `TestCase`
- Test methods must start with `test_`

### Code Coverage Not Available

Install Xdebug to enable code coverage:
- Download from: https://xdebug.org/download
- Configure in `php.ini`
- Restart PHP

---

## Environment Details

**System Information**:
- OS: Windows (CYGWIN_NT-10.0)
- PHP: 8.4.2 (CLI)
- Composer: 2.8.8
- PHP_CodeSniffer: 3.13.5
- PHPUnit: 9.6.34

**WordPress Configuration**:
- Minimum WP Version: 6.0
- Minimum PHP Version: 8.0
- Text Domain: hnfo
- Prefix: hnfo_ / HNFO_

---

**Setup completed successfully. Ready for WordPress development!**
