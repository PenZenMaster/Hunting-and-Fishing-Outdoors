<?php
/**
 * One-Time Database Table Installer
 *
 * Run this once by visiting: http://yoursite.local/install-amenities-db.php
 * Then DELETE this file after successful installation!
 *
 * @package HNFO
 * @security This file should be deleted after use
 */

// Load WordPress
require_once __DIR__ . '/wp-load.php';

// Security check - admin only
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'You must be logged in as an administrator to run this installer.' );
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Amenities Table Installer</title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
			max-width: 800px;
			margin: 50px auto;
			padding: 20px;
			background: #f5f5f5;
		}
		.container {
			background: white;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		}
		h1 {
			color: #23282d;
			border-bottom: 3px solid #0073aa;
			padding-bottom: 10px;
		}
		.success {
			background: #d4edda;
			border: 1px solid #c3e6cb;
			color: #155724;
			padding: 15px;
			border-radius: 4px;
			margin: 20px 0;
		}
		.error {
			background: #f8d7da;
			border: 1px solid #f5c6cb;
			color: #721c24;
			padding: 15px;
			border-radius: 4px;
			margin: 20px 0;
		}
		.warning {
			background: #fff3cd;
			border: 1px solid #ffeaa7;
			color: #856404;
			padding: 15px;
			border-radius: 4px;
			margin: 20px 0;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin: 20px 0;
		}
		table th, table td {
			border: 1px solid #ddd;
			padding: 10px;
			text-align: left;
		}
		table th {
			background: #f8f9fa;
			font-weight: 600;
		}
		.button {
			background: #dc3545;
			color: white;
			padding: 10px 20px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			text-decoration: none;
			display: inline-block;
			margin-top: 20px;
		}
		.button:hover {
			background: #c82333;
		}
		code {
			background: #f4f4f4;
			padding: 2px 6px;
			border-radius: 3px;
			font-family: monospace;
		}
	</style>
</head>
<body>
	<div class="container">
		<h1>🗄️ Amenities Table Installer</h1>

		<?php
		global $wpdb;

		$table_name = 'new_amenities';
		$table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

		if ( $table_exists ) {
			echo '<div class="warning">';
			echo '<h2>⚠️ Table Already Exists</h2>';
			echo '<p>The table <code>' . esc_html( $table_name ) . '</code> already exists in your database.</p>';
			echo '</div>';

			// Show structure
			$columns = $wpdb->get_results( "DESCRIBE $table_name" );
			echo '<h3>Current Table Structure:</h3>';
			echo '<table>';
			echo '<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>';
			foreach ( $columns as $column ) {
				echo '<tr>';
				echo '<td>' . esc_html( $column->Field ) . '</td>';
				echo '<td>' . esc_html( $column->Type ) . '</td>';
				echo '<td>' . esc_html( $column->Null ) . '</td>';
				echo '<td>' . esc_html( $column->Key ) . '</td>';
				echo '<td>' . esc_html( $column->Default ) . '</td>';
				echo '<td>' . esc_html( $column->Extra ) . '</td>';
				echo '</tr>';
			}
			echo '</table>';

			// Count rows
			$count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
			echo '<p><strong>Total rows:</strong> ' . esc_html( $count ) . '</p>';

		} else {
			// CREATE THE TABLE
			echo '<div class="warning">';
			echo '<h2>📋 Creating Table...</h2>';
			echo '</div>';

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
				echo '<div class="success">';
				echo '<h2>✅ Table Created Successfully!</h2>';
				echo '<p>The table <code>' . esc_html( $table_name ) . '</code> has been created in your database.</p>';
				echo '</div>';

				// Show structure
				$columns = $wpdb->get_results( "DESCRIBE $table_name" );
				echo '<h3>Table Structure:</h3>';
				echo '<table>';
				echo '<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>';
				foreach ( $columns as $column ) {
					echo '<tr>';
					echo '<td>' . esc_html( $column->Field ) . '</td>';
					echo '<td>' . esc_html( $column->Type ) . '</td>';
					echo '<td>' . esc_html( $column->Null ) . '</td>';
					echo '<td>' . esc_html( $column->Key ) . '</td>';
					echo '<td>' . esc_html( $column->Default ) . '</td>';
					echo '<td>' . esc_html( $column->Extra ) . '</td>';
					echo '</tr>';
				}
				echo '</table>';

				echo '<div class="success">';
				echo '<h3>🎉 Installation Complete!</h3>';
				echo '<p><strong>Next Steps:</strong></p>';
				echo '<ol>';
				echo '<li>✅ Table is ready for use</li>';
				echo '<li>🗑️ <strong>DELETE THIS FILE</strong> (install-amenities-db.php) for security</li>';
				echo '<li>🧪 Test the amenity submission form</li>';
				echo '<li>📧 Test the approval workflow</li>';
				echo '</ol>';
				echo '</div>';

			} else {
				echo '<div class="error">';
				echo '<h2>❌ Error Creating Table</h2>';
				echo '<p>There was an error creating the table. Check your database permissions.</p>';
				if ( $wpdb->last_error ) {
					echo '<p><strong>Error:</strong> ' . esc_html( $wpdb->last_error ) . '</p>';
				}
				echo '</div>';
			}
		}
		?>

		<hr style="margin: 30px 0;">

		<h3>⚠️ SECURITY WARNING</h3>
		<div class="error">
			<p><strong>DELETE THIS FILE AFTER INSTALLATION!</strong></p>
			<p>For security reasons, you should delete <code>install-amenities-db.php</code> from your server after the table is created.</p>
			<p>Location: <code><?php echo esc_html( __FILE__ ); ?></code></p>
		</div>

		<h3>📖 Documentation</h3>
		<p>For more information about the amenities system, see:</p>
		<ul>
			<li><code>wp-content/themes/wprentals/wqs/README-AMENITIES.md</code></li>
			<li><code>THEME_COMPARISON.md</code> (full theme analysis)</li>
		</ul>

		<a href="<?php echo admin_url(); ?>" class="button">← Back to WordPress Admin</a>
	</div>
</body>
</html>
