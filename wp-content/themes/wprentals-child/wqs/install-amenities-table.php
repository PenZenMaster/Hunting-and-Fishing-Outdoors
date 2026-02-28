<?php
/**
 * Installation Script for new_amenities Table
 *
 * This table is required for the custom amenity request system.
 * Run this script once to create the table structure.
 *
 * WARNING: Only run this if the table doesn't exist!
 *
 * @package    HNFO
 * @created    2026-02-13
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	// Load WordPress if running standalone
	require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/wp-load.php';
}

// Security check
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'Unauthorized access' );
}

global $wpdb;

// Table name (no prefix - as used in original code)
$table_name = 'new_amenities';

// Check if table already exists
$table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

if ( $table_exists ) {
	echo "<h2>Table Already Exists</h2>\n";
	echo "<p>The table <code>$table_name</code> already exists in the database.</p>\n";

	// Show current structure
	$columns = $wpdb->get_results( "DESCRIBE $table_name" );
	echo "<h3>Current Table Structure:</h3>\n";
	echo "<table border='1' cellpadding='5'>\n";
	echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
	foreach ( $columns as $column ) {
		echo '<tr>';
		echo "<td>{$column->Field}</td>";
		echo "<td>{$column->Type}</td>";
		echo "<td>{$column->Null}</td>";
		echo "<td>{$column->Key}</td>";
		echo "<td>{$column->Default}</td>";
		echo "<td>{$column->Extra}</td>";
		echo "</tr>\n";
	}
	echo "</table>\n";

	// Count rows
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
	echo "<p><strong>Total rows:</strong> $count</p>\n";
} else {
	// Create the table
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE `$table_name` (
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
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	// Verify creation
	$table_exists_now = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

	if ( $table_exists_now ) {
		echo "<h2>✓ Table Created Successfully</h2>\n";
		echo "<p>The table <code>$table_name</code> has been created.</p>\n";

		// Show structure
		$columns = $wpdb->get_results( "DESCRIBE $table_name" );
		echo "<h3>Table Structure:</h3>\n";
		echo "<table border='1' cellpadding='5'>\n";
		echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>\n";
		foreach ( $columns as $column ) {
			echo '<tr>';
			echo "<td>{$column->Field}</td>";
			echo "<td>{$column->Type}</td>";
			echo "<td>{$column->Null}</td>";
			echo "<td>{$column->Key}</td>";
			echo "<td>{$column->Default}</td>";
			echo "<td>{$column->Extra}</td>";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "<h3>Next Steps:</h3>\n";
		echo "<ol>\n";
		echo "<li>Test the amenity submission form</li>\n";
		echo "<li>Test the approval workflow at /add-new-amenities/</li>\n";
		echo "<li>Verify email notifications are working</li>\n";
		echo "</ol>\n";
	} else {
		echo "<h2>✗ Error Creating Table</h2>\n";
		echo "<p>There was an error creating the table. Check your database permissions.</p>\n";
		if ( $wpdb->last_error ) {
			echo '<p><strong>Error:</strong> ' . esc_html( $wpdb->last_error ) . "</p>\n";
		}
	}
}

// Usage instructions
echo "\n\n";
echo "<hr>\n";
echo "<h3>Table Purpose:</h3>\n";
echo "<p>This table stores pending amenity requests submitted through the Elementor form.</p>\n";
echo "<ul>\n";
echo "<li><strong>Submit:</strong> Users submit new amenities via form → stored here</li>\n";
echo "<li><strong>Approve:</strong> Admin approves → creates taxonomy term → deletes from table</li>\n";
echo "<li><strong>Deny:</strong> Admin denies → deletes from table</li>\n";
echo "</ul>\n";

echo "<h3>How to Run This Script:</h3>\n";
echo '<p>Option 1: Visit this URL directly (admin only): <code>' . esc_url( get_template_directory_uri() . '/wqs/install-amenities-table.php' ) . "</code></p>\n";
echo "<p>Option 2: Run via SSH: <code>php -f wp-content/themes/wprentals/wqs/install-amenities-table.php</code></p>\n";
echo "<p>Option 3: Copy the SQL and run it in phpMyAdmin/database tool</p>\n";

echo "<h3>SQL for Manual Installation:</h3>\n";
echo "<p>Use the complete SQL file at: <code>/database-new-amenities.sql</code></p>\n";
echo "<pre style='background: #f5f5f5; padding: 10px; border: 1px solid #ddd;'>";
echo "CREATE TABLE `new_amenities` (\n";
echo "  `new_amenity_entry_id` INT(11) NOT NULL AUTO_INCREMENT,\n";
echo "  `nw_amenity_name` VARCHAR(255) NOT NULL,\n";
echo "  `nw_amenity_category` VARCHAR(255) NOT NULL,\n";
echo "  `nw_amenity_description` TEXT DEFAULT NULL,\n";
echo "  `nw_amenity_image` VARCHAR(500) DEFAULT NULL,\n";
echo "  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,\n";
echo "  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,\n";
echo "  PRIMARY KEY (`new_amenity_entry_id`),\n";
echo "  KEY `idx_category` (`nw_amenity_category`),\n";
echo "  KEY `idx_created` (`created_at`)\n";
echo ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n";
echo "</pre>\n";
echo "<p><strong>Note:</strong> This corrects errors in the original Upwork SQL dump.</p>\n";
