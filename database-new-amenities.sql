-- ========================================
-- HNFO Custom Amenities Table
-- Corrected version - fixes Upwork dev errors
-- Created: 2026-02-13
-- ========================================

-- Drop existing tables if they exist (cleanup)
DROP TABLE IF EXISTS `add_new_amenities`;
DROP TABLE IF EXISTS `new_amenities`;

-- Create the correct table structure
CREATE TABLE `new_amenities` (
  `new_amenity_entry_id` INT(11) NOT NULL AUTO_INCREMENT,
  `nw_amenity_name` VARCHAR(255) NOT NULL,
  `nw_amenity_category` VARCHAR(255) NOT NULL,
  `nw_amenity_description` TEXT DEFAULT NULL,
  `nw_amenity_image` VARCHAR(500) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`new_amenity_entry_id`),
  KEY `idx_category` (`nw_amenity_category`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- What's Different From Upwork Version?
-- ========================================

-- ✓ FIXED: Single table (removed useless add_new_amenities)
-- ✓ FIXED: Proper PRIMARY KEY with AUTO_INCREMENT
-- ✓ FIXED: new_amenity_entry_id is the primary key (not s_no)
-- ✓ FIXED: VARCHAR(255) instead of VARCHAR(250)
-- ✓ FIXED: TEXT for description (was incomplete VARCHAR)
-- ✓ ADDED: created_at timestamp for tracking
-- ✓ ADDED: updated_at timestamp for auditing
-- ✓ ADDED: Proper indexes for performance

-- ========================================
-- Table Purpose
-- ========================================

-- This table stores PENDING amenity requests from users.
-- Workflow:
-- 1. User submits amenity via Elementor form
-- 2. Data inserted here with auto-generated ID
-- 3. Email sent to admin with approval link
-- 4. Admin approves → creates taxonomy term → deletes row
-- 5. Admin denies → deletes row
--
-- This is a TEMPORARY holding table, not permanent storage.

-- ========================================
-- Verification Query
-- ========================================

-- After running this script, verify with:
-- SHOW CREATE TABLE new_amenities;
-- DESCRIBE new_amenities;
-- SELECT COUNT(*) FROM new_amenities;

-- ========================================
-- Sample Data (for testing only)
-- ========================================

-- Uncomment to insert test data:
/*
INSERT INTO `new_amenities`
  (`nw_amenity_name`, `nw_amenity_category`, `nw_amenity_description`, `nw_amenity_image`)
VALUES
  ('Test Amenity', 'Basic', 'This is a test amenity for verification', 'https://example.com/image.jpg');
*/

-- ========================================
-- Migration Note
-- ========================================

-- If you have existing data in old structure, run this migration:
/*
INSERT INTO new_amenities
  (new_amenity_entry_id, nw_amenity_name, nw_amenity_category, nw_amenity_description, nw_amenity_image)
SELECT
  s_no as new_amenity_entry_id,
  nw_amenity_name,
  nw_amenity_category,
  nw_amenity_description,
  nw_amenity_image
FROM old_table_backup;
*/
