# HNFO WordPress Development Project

WordPress development project for HNFO by Rank Rocket Co.

---

## Project Setup

### Requirements

- PHP 8.0 or higher
- Composer
- WordPress 6.0 or higher
- MySQL/MariaDB
- Node.js (for frontend build tools, if needed)

### Installation

1. Clone the repository
2. Install Composer dependencies:
   ```
   composer install
   ```

3. Configure WordPress:
   - Copy `wp-config-sample.php` to `wp-config.php`
   - Update database credentials
   - Set authentication keys and salts

4. Access the site and complete WordPress installation

---

## Development Tools

### PHP_CodeSniffer (Code Quality)

Check code against WordPress Coding Standards:

```
composer phpcs
```

Or using vendor binary:

```
vendor\bin\phpcs --standard=phpcs.xml.dist
```

Auto-fix coding standards issues:

```
composer phpcbf
```

Or:

```
vendor\bin\phpcbf --standard=phpcs.xml.dist
```

Check a specific file or directory:

```
vendor\bin\phpcs wp-content\plugins\your-plugin
```

### PHPUnit (Unit Testing)

Run all tests:

```
composer test
```

Or:

```
vendor\bin\phpunit
```

Run specific test file:

```
vendor\bin\phpunit tests\ExampleTest.php
```

Run with coverage report:

```
vendor\bin\phpunit --coverage-html tests\coverage
```

---

## WordPress Coding Standards

This project follows WordPress Coding Standards. Key principles:

- **Indentation**: Use tabs, not spaces
- **Naming**: `lowercase_with_underscores` for functions, `PascalCase` for classes
- **Security**: Sanitize inputs, escape outputs, use nonces
- **Internationalization**: All user-facing strings must be translatable
- **Documentation**: All functions and classes must have proper docblocks

See `WORDPRESS-STANDARDS.md` for complete reference.

---

## Project Structure

```
.
+-- wp-admin/              [WordPress Core - Not tracked]
+-- wp-includes/           [WordPress Core - Not tracked]
+-- wp-content/
    +-- plugins/           [Custom plugins - Tracked]
    +-- themes/            [Custom themes - Tracked]
    +-- uploads/           [Media files - Not tracked]
+-- tests/                 [Unit tests]
+-- vendor/                [Composer dependencies - Not tracked]
+-- composer.json          [PHP dependencies]
+-- phpcs.xml.dist         [Coding standards config]
+-- phpunit.xml.dist       [Testing config]
+-- .gitignore             [Git ignore rules]
```

---

## Git Workflow

### Commit Messages

Use conventional commits:

- `feat:` - New feature
- `fix:` - Bug fix
- `refactor:` - Code refactoring
- `docs:` - Documentation
- `test:` - Tests
- `chore:` - Maintenance

Example:
```
feat: add user profile widget
fix: correct sanitization in contact form
```

### Branching

- `main` - Production-ready code
- `develop` - Integration branch
- `feature/*` - New features
- `fix/*` - Bug fixes

---

## Quality Gates

Before committing code, ensure:

1. Code passes PHPCS checks: `composer phpcs`
2. All tests pass: `composer test`
3. No PHP errors or warnings
4. Security best practices followed

---

## Custom Development

### Creating a Custom Plugin

1. Create directory: `wp-content\plugins\your-plugin\`
2. Create main file: `your-plugin.php`
3. Add plugin header:

```php
<?php
/**
 * Plugin Name: Your Plugin Name
 * Plugin URI: https://example.com
 * Description: Plugin description
 * Version: 1.0.0
 * Author: Rank Rocket Co
 * Author URI: https://rankrocket.com
 * Text Domain: your-plugin
 * Domain Path: /languages
 *
 * @package YourPlugin
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
```

### Creating a Custom Theme

1. Create directory: `wp-content\themes\your-theme\`
2. Create `style.css` with theme header:

```css
/*
Theme Name: Your Theme Name
Theme URI: https://example.com
Author: Rank Rocket Co
Author URI: https://rankrocket.com
Description: Theme description
Version: 1.0.0
Text Domain: your-theme
*/
```

3. Create `index.php` (required)
4. Create other template files as needed

---

## Resources

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [WordPress Theme Handbook](https://developer.wordpress.org/themes/)
- [PHP_CodeSniffer Documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

---

## License

Proprietary - Rank Rocket Co (C) Copyright 2026 - All Rights Reserved

---

## Support

For project support, contact the development team.
