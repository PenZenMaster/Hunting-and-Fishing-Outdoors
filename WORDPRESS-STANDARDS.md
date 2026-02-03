# WordPress Coding Standards Reference

**Project**: HNFO-DEV WordPress Project
**Created**: 2026-02-02
**Source**: https://developer.wordpress.org/coding-standards/

---

## Core Principles

> "It's important that best practices are followed so that the codebase is consistent and readable, and changes are easy to find and read, whether the code is five days old or five years old."

---

## 1. PHP Coding Standards

### Key Requirements

- **Indentation**: Use tabs for indentation, not spaces
- **Brace Style**: Opening braces on same line; closing braces on new line
- **Naming Conventions**:
  - Functions: `lowercase_with_underscores()`
  - Classes: `PascalCase`
  - Constants: `UPPERCASE_WITH_UNDERSCORES`
  - Variables: `$lowercase_with_underscores`
- **Yoda Conditions**: Place constant/literal on left side of comparison
  - Correct: `if ( true === $the_force )`
  - Incorrect: `if ( $the_force === true )`
- **Space Usage**: Space after control structures, around operators
- **Single vs Double Quotes**: Use single quotes unless interpolating variables

### Security Best Practices

- **Sanitization**: Always sanitize user input
  - `sanitize_text_field()`, `sanitize_email()`, `sanitize_url()`, etc.
- **Escaping**: Escape all output
  - `esc_html()`, `esc_attr()`, `esc_url()`, `esc_js()`
- **Nonces**: Verify intent with nonces for form submissions
  - `wp_nonce_field()`, `wp_verify_nonce()`
- **Capability Checks**: Verify user permissions
  - `current_user_can()`
- **Database Queries**: Use `$wpdb->prepare()` for all queries

### File Organization

```php
<?php
/**
 * Plugin/Theme Name
 *
 * @package     PackageName
 * @author      Author Name
 * @copyright   2026 Rank Rocket Co
 * @license     GPL-2.0+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
```

---

## 2. JavaScript Coding Standards

### Key Requirements

- **Indentation**: Tabs for indentation
- **Naming Conventions**:
  - Variables/Functions: `camelCase`
  - Constructors: `PascalCase`
  - Constants: `UPPERCASE_WITH_UNDERSCORES`
- **Strict Equality**: Use `===` and `!==` instead of `==` and `!=`
- **jQuery**: Prefix jQuery object variables with `$`
  - `const $element = jQuery( '#id' );`
- **Use `jQuery` not `$`**: For WordPress compatibility

---

## 3. CSS Coding Standards

### Key Requirements

- **Indentation**: Tabs for indentation
- **Selectors**: Lowercase with hyphens
  - `.my-class-name`
- **Properties**: One property per line
- **Property Order**: Alphabetical ordering preferred
- **Prefixes**: Use vendor prefixes where needed
- **Colors**: Use hex codes (lowercase) or rgba()

---

## 4. HTML Coding Standards

### Key Requirements

- **Indentation**: Tabs for indentation
- **Attributes**: Use double quotes for attribute values
- **Self-Closing Elements**: Include trailing slash
  - `<br />`, `<img src="..." />`
- **Semantic HTML**: Use appropriate semantic elements
- **Accessibility**: Include proper ARIA labels and roles

---

## 5. Accessibility Standards

### Requirements

- **WCAG 2.0 Level AA Compliance** minimum
- **Keyboard Navigation**: All interactive elements accessible via keyboard
- **Screen Reader Support**: Proper labels and ARIA attributes
- **Color Contrast**: Minimum 4.5:1 for normal text
- **Focus Indicators**: Visible focus states for all interactive elements
- **Alt Text**: Descriptive alternative text for images

---

## 6. Internationalization (i18n)

### Text Domain Usage

```php
// Correct - with text domain
__( 'Hello World', 'text-domain' );
_e( 'Hello World', 'text-domain' );
_n( 'One item', '%s items', $count, 'text-domain' );
esc_html__( 'Hello World', 'text-domain' );
esc_html_e( 'Hello World', 'text-domain' );
```

### Rules

- All user-facing strings must be translatable
- Use appropriate text domain (plugin slug or theme name)
- Never use variables in text domain argument
- Use context when needed: `_x()`, `esc_html_x()`

---

## 7. Inline Documentation Standards

### PHP DocBlocks

```php
/**
 * Short description (one line).
 *
 * Long description with more details about what this
 * function does and how to use it.
 *
 * @since 1.0.0
 *
 * @param string $param1 Description of parameter.
 * @param int    $param2 Description of parameter.
 * @return bool True on success, false on failure.
 */
function my_function( $param1, $param2 ) {
    // Function body
}
```

### Required Elements

- File headers with package and copyright
- Function/method documentation with `@param` and `@return`
- Class documentation
- Hook documentation with `@since`

---

## 8. Development Tools

### PHP_CodeSniffer (PHPCS)

- **Standard**: `WordPress`, `WordPress-Core`, `WordPress-Extra`
- **Command**: `phpcs --standard=WordPress path/to/files`
- **Auto-fix**: `phpcbf --standard=WordPress path/to/files`

### PHPUnit

- Unit tests for all custom functionality
- Follow WordPress core testing conventions
- Mock WordPress functions when needed

---

## 9. Git & Version Control

### Commit Messages

Use conventional commits:
- `feat:` - New feature
- `fix:` - Bug fix
- `refactor:` - Code refactoring
- `docs:` - Documentation changes
- `test:` - Test additions/changes
- `chore:` - Maintenance tasks

### Branching

- `main` - Production-ready code
- `develop` - Integration branch
- `feature/*` - New features
- `fix/*` - Bug fixes
- `hotfix/*` - Urgent production fixes

---

## 10. Project-Specific Rules

### File Headers (from CLAUDE.md)

```php
<?php
/**
 * Module/Script Name: [Name of the file or module]
 * Path: [Full path to file with name]
 *
 * Description:
 * [Brief summary of what the script does]
 *
 * @package    PackageName
 * @author     Rank Rocket Co
 * @copyright  2026 Rank Rocket Co - All Rights Reserved
 * @version    1.00
 *
 * Created Date: 2026-02-02
 * Last Modified Date: [Auto-filled with last modified date]
 *
 * Comments:
 * - Initial version v1.00
 */
```

### Quality Gate

```bash
phpcs --standard=phpcs.xml.dist
```

- Enforce WordPress Coding Standards
- Security: sanitize inputs, escape outputs, nonces + capability checks
- I18n: wrap user-visible strings with correct text domain
- Enqueue scripts/styles properly; avoid inline unless necessary

---

## Resources

- Main Documentation: https://developer.wordpress.org/coding-standards/
- PHP Standards: https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/
- JS Standards: https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/
- CSS Standards: https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/
- Accessibility: https://developer.wordpress.org/coding-standards/wordpress-coding-standards/accessibility/
- Inline Docs: https://developer.wordpress.org/coding-standards/inline-documentation-standards/

---

**Note**: This document serves as quick reference. Always consult official WordPress documentation for detailed guidance.
