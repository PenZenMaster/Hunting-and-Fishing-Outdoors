# Custom Amenity Request System

## Overview

This custom system allows property owners to request new amenities that admins can approve or deny. When approved, the amenity becomes available site-wide for all properties.

## System Components

### 1. Database Table: `new_amenities`

**Purpose:** Stores pending amenity requests before approval/denial

**Installation:** Run `/wp-content/themes/wprentals/wqs/install-amenities-table.php`

**Structure:**
- `new_amenity_entry_id` (INT, AUTO_INCREMENT) - Unique ID
- `nw_amenity_name` (VARCHAR 255) - Name of amenity
- `nw_amenity_category` (VARCHAR 255) - Category (Basic, Features, Includes, Type of Fish, Type of Game)
- `nw_amenity_description` (TEXT) - Description
- `nw_amenity_image` (VARCHAR 500) - Image URL
- `created_at` (TIMESTAMP) - Submission date

### 2. Elementor Form Integration

**File:** `/wqs/functions.php`
**Function:** `wqs_new_record()`

**Workflow:**
1. User submits Elementor form on property page
2. Form data captured via `elementor_pro/forms/new_record` hook
3. Data inserted into `new_amenities` table
4. Email sent to admin with Approve/Deny links

**Email Configuration:**
```php
// Default: Uses WordPress admin email
$admin_email = get_option('hnfo_amenity_admin_email', get_option('admin_email'));

// To set custom email (run once):
update_option('hnfo_amenity_admin_email', 'your-custom@email.com');
```

### 3. Approval Page Template

**File:** `/add-new-amenities.php`
**Template Name:** Add New Amenities

**URL Format:**
```
https://yoursite.com/add-new-amenities/?am_id=123&action=Approve
https://yoursite.com/add-new-amenities/?am_id=123&action=Deny
```

**Security Features:**
- Requires `manage_options` capability (admin only)
- Input sanitization on all parameters
- SQL injection protection
- XSS protection

### 4. AJAX Handlers

**File:** `/wqs/functions.php`

**Functions:**
- `approve_add_new_amenity()` - Approves request, creates taxonomy term
- `deny_add_new_amenity()` - Denies request, deletes from table

**Security:**
- Capability checks (`manage_options`)
- Input validation
- Nonce verification (TODO: add nonce to AJAX calls)

## User Workflow

### Submission Process

1. **Property Owner:**
   - Fills out amenity request form (Elementor)
   - Enters: Name, Category, Description, Image URL
   - Submits form

2. **System:**
   - Validates form data
   - Inserts into `new_amenities` table
   - Generates unique ID
   - Sends email to admin

3. **Admin Email:**
   - Subject: "New Amenity Request"
   - Contains: Preview, Details, Approve/Deny links

### Approval Process

1. **Admin clicks "Approve" link**
2. System loads `/add-new-amenities/` page
3. Displays amenity details
4. On confirmation:
   - Creates WordPress taxonomy term (`property_features`)
   - Sets parent category (Basic, Features, etc.)
   - Adds metadata (image, property types)
   - Deletes from `new_amenities` table
   - Shows success message

### Denial Process

1. **Admin clicks "Deny" link**
2. System loads `/add-new-amenities/` page
3. Displays amenity details
4. On confirmation:
   - Deletes from `new_amenities` table
   - Shows denial message
   - No taxonomy term created

## Category Mapping

The system maps categories to parent taxonomy IDs:

| Category | Taxonomy Parent ID | Used For |
|----------|-------------------|----------|
| Basic | 21 | Basic amenities (WiFi, Kitchen, etc.) |
| Features | 29 | Property features (Pool, Gym, etc.) |
| Includes | 24 | What's included (Linens, Toiletries) |
| Type of Fish | 94 | Fishing-specific properties |
| Type of Game | 178 | Hunting-specific properties |

**Note:** These IDs are specific to your taxonomy structure. Verify in WordPress admin.

## Property Type Metadata

When an amenity is approved, it's tagged with these property types:

```php
update_term_meta($term_id, 'is_fishing', 'Fishing');
update_term_meta($term_id, 'is_hunt_camp', 'Hunt Camp');
update_term_meta($term_id, 'is_hunting', 'Hunting');
update_term_meta($term_id, 'is_hunt_fishing', 'Hunting and Fishing');
update_term_meta($term_id, 'is_stay_and_fish', 'Stay and Fish');
```

This makes the amenity available for multiple property types.

## Security Notes

### Recent Security Fixes (2026-02-13)

✅ **FIXED:**
- SQL injection vulnerabilities
- XSS vulnerabilities
- Missing capability checks
- Hardcoded credentials
- Unsanitized inputs

### Remaining Security Improvements

⚠️ **TODO:**
- Add nonce verification to AJAX calls
- Add rate limiting for form submissions
- Add CAPTCHA to prevent spam
- Implement approval expiration (auto-delete old requests)

## Configuration Options

### Set Custom Admin Email

```php
// Add to functions.php or run once in WordPress
update_option('hnfo_amenity_admin_email', 'amenities@yoursite.com');
```

### Customize Email Template

Edit `/wqs/functions.php` around line 40:

```php
$message = '<h2>Hello Admin,</h2>';
$message .= '<p>Customize this message...</p>';
```

## Troubleshooting

### Table Doesn't Exist

**Solution:** Run the installation script
```bash
php -f wp-content/themes/wprentals/wqs/install-amenities-table.php
```

Or visit: `https://yoursite.com/wp-content/themes/wprentals/wqs/install-amenities-table.php`

### Emails Not Sending

**Check:**
1. WordPress email configuration (use SMTP plugin if needed)
2. Admin email setting: `get_option('hnfo_amenity_admin_email')`
3. Server email logs

### Approval Links Don't Work

**Check:**
1. Page with "Add New Amenities" template exists
2. Permalink is `/add-new-amenities/`
3. User has `manage_options` capability

### Amenity Not Appearing After Approval

**Check:**
1. Taxonomy term was created (check `property_features` taxonomy)
2. Parent category ID is correct for your site
3. Term metadata was saved

## Database Queries

### View Pending Requests

```sql
SELECT * FROM new_amenities ORDER BY created_at DESC;
```

### Count Pending by Category

```sql
SELECT nw_amenity_category, COUNT(*) as count
FROM new_amenities
GROUP BY nw_amenity_category;
```

### Delete Old Requests (Manual Cleanup)

```sql
DELETE FROM new_amenities
WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);
```

## Migration Notes

### Moving to Child Theme

When creating a child theme, move these files:

1. `/add-new-amenities.php` → Child theme root
2. `/wqs/` directory → Child theme
3. Update paths in `enqueue_wqs_script()` to use `get_stylesheet_directory_uri()`

### Moving to Custom Plugin

**Recommended approach:**

1. Create plugin: `hnfo-custom-features`
2. Move `/wqs/functions.php` → Plugin
3. Move `/add-new-amenities.php` → Plugin (as template)
4. Add activation hook to create table
5. Add admin settings page for configuration

## Support

For issues or questions about this system, contact:
- System created: ~2023 (Upwork developers)
- Security hardened: 2026-02-13
- Documentation created: 2026-02-13

---

**Last Updated:** 2026-02-13
**Version:** 1.0 (Post-Security Audit)
