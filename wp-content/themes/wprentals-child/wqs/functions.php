<?php

/**
 * Return a slug-to-term-id map for property_action_category terms.
 *
 * Cached in a static variable so repeated calls within a request
 * cost only one database round-trip.
 *
 * @return array<string, int> Associative array of slug => term_id.
 */
function hnfo_get_action_category_ids() {
	static $ids = null;

	if ( null !== $ids ) {
		return $ids;
	}

	$slugs = array( 'fishing', 'hunting', 'hunt-camp', 'hunting-and-fishing', 'stay-and-fish' );
	$ids   = array();

	foreach ( $slugs as $slug ) {
		$term = get_term_by( 'slug', $slug, 'property_action_category' );
		if ( $term instanceof WP_Term ) {
			$ids[ $slug ] = $term->term_id;
		}
	}

	return $ids;
}

function enqueue_wqs_script() {

	wp_enqueue_script( 'wqs-script', get_stylesheet_directory_uri() . '/wqs/js/custom-script.js', array( 'jquery' ), '1.3', true );

	if ( is_singular( 'estate_property' ) ) {
	}
	wp_enqueue_style( 'wqs-style', get_stylesheet_directory_uri() . '/wqs/css/custom-style.css', array(), '4.2', 'all' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_wqs_script' );

add_action( 'elementor_pro/forms/new_record', 'wqs_new_record', 10, 2 );
function wqs_new_record( $record, $ajax_handler ) {

	$raw_fields = $record->get( 'fields' );
	$fields = [];
	foreach ( $raw_fields as $id => $field ) {
		$fields[ $id ] = $field['value'];
	}
	global $wpdb;
	$last_entry_record = $wpdb->get_results( 'SELECT * FROM new_amenities ORDER BY new_amenity_entry_id DESC' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	$last_entry = $last_entry_record[0]->new_amenity_entry_id;
	$last_entry_id = $last_entry + 1;
	// if ($fields['field_2167f68'] == '(binary)') {
	// $fields['field_d034d3a'] = 0;
	// }

	// Security: Sanitize inputs before database insert
	$output['success'] = $wpdb->insert(
		'new_amenities',
		array(
			'new_amenity_entry_id'   => absint( $last_entry_id ),
			'nw_amenity_name'        => sanitize_text_field( $fields['field_2e513a9'] ),
			'nw_amenity_category'    => sanitize_text_field( $fields['field_2167f68'] ),
			'nw_amenity_description' => sanitize_textarea_field( $fields['field_2084096'] ),
			'nw_amenity_image'       => esc_url_raw( $fields['field_d034d3a'] ),
		),
		array( '%d', '%s', '%s', '%s', '%s' )
	);

	// Security: Use configurable email instead of hardcoded
	$admin_email = get_option( 'hnfo_amenity_admin_email', get_option( 'admin_email' ) );
	$site_url    = home_url( '/add-new-amenities/' );

	$to      = $admin_email;
	$subject = 'New Amenity Request';
	$message = '<h2>Hello Admin,</h2>';
	$message .= '<p><a href="' . esc_url( $site_url . '?action=Approve&am_id=' . absint( $last_entry_id ) ) . '"><b>Approve</b></a> or <a href="' . esc_url( $site_url . '?action=Deny&am_id=' . absint( $last_entry_id ) ) . '"><b>Deny</b></a> new amenity request<b></p>';
	$message .= '<p><img src="' . esc_url( $fields['field_d034d3a'] ) . '" style="max-width:300px"></p>';
	$message .= '<h3>Below are new amenity details:</h3>';
	$message .= '<p><b>Amenity name: </b>' . esc_html( $fields['field_2e513a9'] ) . '</p>';
	$message .= '<p><b>Amenity category: </b>' . esc_html( $fields['field_2167f68'] ) . '</p>';
	$message .= '<p><b>Amenity description: </b>' . esc_html( $fields['field_2084096'] ) . '</p>';

	// Security: Proper email headers
	$headers   = array();
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>';
	$headers[] = 'Reply-To: ' . $admin_email;
	wp_mail( $to, $subject, $message, $headers );
	$output['success2'] = $message;
	$ajax_handler->add_response_data( true, $output );
}

add_action( 'wp_ajax_approve_add_new_amenity', 'approve_add_new_amenity' );
// Intentionally no wp_ajax_nopriv_ - approve is an admin-only action.

function approve_add_new_amenity() {
	// Security: Check user capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		return;
	}

	// Security: Verify nonce.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hnfo_amenity_action' ) ) {
		wp_send_json_error( array( 'message' => 'Invalid security token' ) );
		return;
	}

	// Security: Sanitize input.
	$amenity_entry_id = isset( $_POST['amenity_entry_id'] ) ? absint( $_POST['amenity_entry_id'] ) : 0;

	if ( $amenity_entry_id <= 0 ) {
		wp_send_json_error( array( 'message' => 'Invalid amenity ID' ) );
		return;
	}

	global $wpdb;
	// Security: Properly prepared statement
	$new_amenities_data = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT * FROM new_amenities WHERE new_amenity_entry_id = %d',
			$amenity_entry_id
		)
	);
	// Security: Check if data exists
	if ( empty( $new_amenities_data ) ) {
		wp_send_json_error( array( 'message' => 'Amenity not found' ) );
		return;
	}

	// Security: Sanitize data from database
	$add_amenity_name        = sanitize_text_field( $new_amenities_data[0]->nw_amenity_name );
	$add_amenity_category    = sanitize_text_field( $new_amenities_data[0]->nw_amenity_category );
	$add_amenity_description = sanitize_textarea_field( $new_amenities_data[0]->nw_amenity_description );
	$add_amenity_img_url     = esc_url_raw( $new_amenities_data[0]->nw_amenity_image );
	$add_amenity_slug        = sanitize_title( $add_amenity_name );
	if ( $add_amenity_category == 'Basic' || $add_amenity_category == 'Features' || $add_amenity_category == 'Includes' ) {
		if ( $add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_slug != '' ) {
			if ( $add_amenity_category == 'Basic' ) {
				$add_amenity_parent = 21;
			} elseif ( $add_amenity_category == 'Features' ) {
				$add_amenity_parent = 29;
			} elseif ( $add_amenity_category == 'Includes' ) {
				$add_amenity_parent = 24;
			} elseif ( $add_amenity_category == 'Type of Fish' ) {
				$add_amenity_parent = 94;
			} elseif ( $add_amenity_category == 'Type of Game' ) {
				$add_amenity_parent = 178;
			}

			$create_amenities = wp_insert_term(
				"$add_amenity_name",
				'property_features', // the taxonomy
				array(
					'description' => $add_amenity_description,
					'slug' => strtolower( $add_amenity_slug ),
					'parent' => $add_amenity_parent,
				)
			);
			$term_id = intval( $create_amenities['term_id'] );
			update_term_meta( $term_id, 'category_featured_image', $add_amenity_img_url );
			// Basic, Features, Includes apply to all property types.
			$cats = hnfo_get_action_category_ids();
			update_term_meta(
				$term_id,
				'taxonomy_terms',
				array(
					$cats['fishing'],
					$cats['hunt-camp'],
					$cats['hunting'],
					$cats['hunting-and-fishing'],
					$cats['stay-and-fish'],
				)
			);
			update_term_meta( $term_id, 'is_fishing', 'Fishing' );
			update_term_meta( $term_id, 'is_hunt_camp', 'Hunt Camp' );
			update_term_meta( $term_id, 'is_hunting', 'Hunting' );
			update_term_meta( $term_id, 'is_hunt_fishing', 'Hunting and Fishing' );
			update_term_meta( $term_id, 'is_stay_and_fish', 'Stay and Fish' );

			// Clear amenities cache so new term appears immediately
			delete_transient( 'wpestate_get_features_array' );

			// Security: Properly prepared DELETE statement
			$wpdb->query(
				$wpdb->prepare(
					'DELETE FROM new_amenities WHERE new_amenity_entry_id = %d',
					$amenity_entry_id
				)
			);

			// Return success response
			wp_send_json_success( array( 'term_id' => $term_id ) );
		} else {
			echo json_encode( 'Fail' );
		}
	} elseif ( $add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_img_url != '' && $add_amenity_slug != '' ) {
		if ( $add_amenity_category == 'Basic' ) {
			$add_amenity_parent = 21;
		} elseif ( $add_amenity_category == 'Features' ) {
			$add_amenity_parent = 29;
		} elseif ( $add_amenity_category == 'Includes' ) {
			$add_amenity_parent = 24;
		} elseif ( $add_amenity_category == 'Type of Fish' ) {
			$add_amenity_parent = 94;
		} elseif ( $add_amenity_category == 'Type of Game' ) {
			$add_amenity_parent = 178;
		}

			$create_amenities = wp_insert_term(
				"$add_amenity_name",
				'property_features', // the taxonomy
				array(
					'description' => $add_amenity_description,
					'slug' => strtolower( $add_amenity_slug ),
					'parent' => $add_amenity_parent,
				)
			);
			$term_id = intval( $create_amenities['term_id'] );
			update_term_meta( $term_id, 'category_featured_image', $add_amenity_img_url );
			// Type of Fish: Fishing, Hunting And Fishing, Stay and Fish.
			// Type of Game: Hunting, Hunt Camp, Hunting And Fishing.
			$cats = hnfo_get_action_category_ids();
		if ( 'Type of Fish' === $add_amenity_category ) {
			$taxonomy_terms = array( $cats['fishing'], $cats['hunting-and-fishing'], $cats['stay-and-fish'] );
		} else {
			$taxonomy_terms = array( $cats['hunting'], $cats['hunt-camp'], $cats['hunting-and-fishing'] );
		}
			update_term_meta( $term_id, 'taxonomy_terms', $taxonomy_terms );
			update_term_meta( $term_id, 'is_fishing', 'Fishing' );
			update_term_meta( $term_id, 'is_hunt_camp', 'Hunt Camp' );
			update_term_meta( $term_id, 'is_hunting', 'Hunting' );
			update_term_meta( $term_id, 'is_hunt_fishing', 'Hunting and Fishing' );
			update_term_meta( $term_id, 'is_stay_and_fish', 'Stay and Fish' );

			// Clear amenities cache so new term appears immediately
			delete_transient( 'wpestate_get_features_array' );

			// Security: Properly prepared DELETE statement
			$wpdb->query(
				$wpdb->prepare(
					'DELETE FROM new_amenities WHERE new_amenity_entry_id = %d',
					$amenity_entry_id
				)
			);

			// Return success response
			wp_send_json_success( array( 'term_id' => $term_id ) );

	} else {
		echo json_encode( 'Fail' );
	}
	if ( $_GET['action'] != 'Approve' ) {
		die();
	}
}
/*
Ajax actions(hooks) when the admin click the new amenity request link received in email and clicks "Approve" then this function will
 *execute and adds that paricular amenity to the "amenities & features list" and displays in frontend
 */
// Code ends here

/*
Ajax actions(hooks) when the admin click the new amenity request link received in email and clicks "Deny" then this function will
 *execute and it will not added in the amenity list and doesn't display in frontend
 *Deleting the request from the table which we are saving when user submits the new amenity form
 */
// Code starts here
add_action( 'wp_ajax_deny_add_new_amenity', 'deny_add_new_amenity' );
// Intentionally no wp_ajax_nopriv_ - deny is an admin-only action.

function deny_add_new_amenity() {
	// Security: Check user capability.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'Unauthorized' ) );
		return;
	}

	// Security: Verify nonce.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hnfo_amenity_action' ) ) {
		wp_send_json_error( array( 'message' => 'Invalid security token' ) );
		return;
	}

	// Security: Sanitize input.
	$amenity_entry_id = isset( $_POST['amenity_entry_id'] ) ? absint( $_POST['amenity_entry_id'] ) : 0;
	if ( $amenity_entry_id <= 0 ) {
		wp_send_json_error( array( 'message' => 'Invalid amenity ID' ) );
		return;
	}

	global $wpdb;
	$wpdb->query( $wpdb->prepare( 'DELETE FROM new_amenities WHERE new_amenity_entry_id = %d', $amenity_entry_id ) );

	wp_send_json_success( array( 'deleted' => $amenity_entry_id ) );
}

// Add taxonomy meta box
function add_taxonomy_meta_box() {
	$taxonomy = 'property_features'; // Specify the taxonomy you want to target
	$post_type = 'estate_property'; // Specify the post type you want to target

	add_action( "{$taxonomy}_add_form_fields", 'taxonomy_meta_box_callback' );
	add_action( "{$taxonomy}_edit_form_fields", 'taxonomy_meta_box_callback' );
	add_action( "create_{$taxonomy}", 'save_taxonomy_meta_box_data' );
	add_action( "edited_{$taxonomy}", 'save_taxonomy_meta_box_data' );
}
add_action( 'admin_init', 'add_taxonomy_meta_box' );

// Taxonomy meta box callback.
function taxonomy_meta_box_callback( $term ) {
	$taxonomy = 'property_action_category';
	$terms    = get_terms(
		array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		)
	);
	$saved_term_ids = array();

	if ( isset( $term->term_id ) ) {
		$saved_terms    = get_term_meta( $term->term_id, 'taxonomy_terms', true );
		$saved_term_ids = ! empty( $saved_terms ) ? $saved_terms : array();
	}

	// Security: Output nonce for the save handler to verify.
	wp_nonce_field( 'hnfo_taxonomy_meta', '_hnfo_tax_nonce' );

	foreach ( $terms as $term ) {
		// Security: Escape all output.
		echo '<label>';
		echo '<input type="checkbox" name="taxonomy_terms[]" value="' . esc_attr( $term->term_id ) . '" ' . checked( in_array( $term->term_id, $saved_term_ids, true ), true, false ) . '>';
		echo esc_html( $term->name );
		echo '</label><br>';
	}
}

// Save taxonomy meta box data.
function save_taxonomy_meta_box_data( $term_id ) {
	// Security: Verify nonce before processing POST data.
	if ( ! isset( $_POST['_hnfo_tax_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_hnfo_tax_nonce'] ) ), 'hnfo_taxonomy_meta' ) ) {
		return;
	}

	if ( isset( $_POST['taxonomy_terms'] ) && is_array( $_POST['taxonomy_terms'] ) ) {
		// Security: Sanitize each term ID as an integer.
		$taxonomy_terms = array_map( 'absint', wp_unslash( $_POST['taxonomy_terms'] ) );
		update_term_meta( $term_id, 'taxonomy_terms', $taxonomy_terms );
	} else {
		delete_term_meta( $term_id, 'taxonomy_terms' );
	}
}

// Add the code to the wp_footer action
add_action( 'wp_footer', 'set_last_entry_id' );

function set_last_entry_id() {
	global $wpdb;

	$last_entry_record = $wpdb->get_results( 'SELECT * FROM new_amenities ORDER BY new_amenity_entry_id DESC' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- static query, no user input
	// Guard: table may be empty on a fresh install.
	$last_entry    = ! empty( $last_entry_record ) ? absint( $last_entry_record[0]->new_amenity_entry_id ) : 0;
	$last_entry_id = $last_entry + 1;

	// Output the jQuery script to set the value of the input field
	echo '<script>
        jQuery(document).ready(function($) {
            $("#form-field-field_7ecc6f3").val(' . $last_entry_id . ');
        });
    </script>';
}

// Add amenity request modal to footer
add_action( 'wp_footer', 'add_amenity_modal_html' );

// Handle HTML form submission (non-Elementor)
add_action( 'wp_ajax_submit_new_amenity', 'handle_new_amenity_submission' );
add_action( 'wp_ajax_nopriv_submit_new_amenity', 'handle_new_amenity_submission' );

function handle_new_amenity_submission() {
	// Security: Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'submit_new_amenity' ) ) {
		wp_send_json_error( 'Invalid security token' );
		return;
	}

	// Security: Sanitize inputs
	$entry_id    = isset( $_POST['entry_id'] ) ? absint( $_POST['entry_id'] ) : 0;
	$name        = isset( $_POST['amenity_name'] ) ? sanitize_text_field( $_POST['amenity_name'] ) : '';
	$category    = isset( $_POST['amenity_category'] ) ? sanitize_text_field( $_POST['amenity_category'] ) : '';
	$description = isset( $_POST['amenity_description'] ) ? sanitize_textarea_field( $_POST['amenity_description'] ) : '';
	$image       = isset( $_POST['amenity_image'] ) ? esc_url_raw( $_POST['amenity_image'] ) : '';

	// Validate required fields
	if ( empty( $name ) || empty( $category ) || empty( $description ) ) {
		wp_send_json_error( 'Please fill in all required fields' );
		return;
	}

	// Insert into database
	global $wpdb;
	$result = $wpdb->insert(
		'new_amenities',
		array(
			'new_amenity_entry_id'   => $entry_id,
			'nw_amenity_name'        => $name,
			'nw_amenity_category'    => $category,
			'nw_amenity_description' => $description,
			'nw_amenity_image'       => $image,
		),
		array( '%d', '%s', '%s', '%s', '%s' )
	);

	if ( ! $result ) {
		wp_send_json_error( 'Database error: Could not save amenity request' );
		return;
	}

	// Send email notification to admin
	$admin_email = get_option( 'hnfo_amenity_admin_email', get_option( 'admin_email' ) );
	$site_url    = home_url( '/add-new-amenities/' );

	$to      = $admin_email;
	$subject = 'New Amenity Request';
	$message = '<h2>Hello Admin,</h2>';
	$message .= '<p><a href="' . esc_url( $site_url . '?action=Approve&am_id=' . absint( $entry_id ) ) . '"><b>Approve</b></a> or <a href="' . esc_url( $site_url . '?action=Deny&am_id=' . absint( $entry_id ) ) . '"><b>Deny</b></a> new amenity request</p>';
	if ( ! empty( $image ) ) {
		$message .= '<p><img src="' . esc_url( $image ) . '" style="max-width:300px"></p>';
	}
	$message .= '<h3>Amenity Details:</h3>';
	$message .= '<p><b>Amenity name: </b>' . esc_html( $name ) . '</p>';
	$message .= '<p><b>Category: </b>' . esc_html( $category ) . '</p>';
	$message .= '<p><b>Description: </b>' . esc_html( $description ) . '</p>';

	$headers   = array();
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>';
	$headers[] = 'Reply-To: ' . $admin_email;

	wp_mail( $to, $subject, $message, $headers );

	wp_send_json_success( array( 'message' => 'Amenity request submitted successfully' ) );
}

function add_amenity_modal_html() {
	global $wpdb;

	// Get next entry ID
	$last_entry_record = $wpdb->get_results( 'SELECT * FROM new_amenities ORDER BY new_amenity_entry_id DESC LIMIT 1' ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- static query, no user input
	$last_entry_id = ! empty( $last_entry_record ) ? intval( $last_entry_record[0]->new_amenity_entry_id ) + 1 : 1;
	?>
	<div id="new-amenity-modal" class="new-amenity-modal-overlay" style="display:none;">
		<div class="new-amenity-modal-container">
			<div class="new-amenity-modal-header">
				<h3>Request New Amenity</h3>
				<button class="new-amenity-modal-close">&times;</button>
			</div>
			<div class="new-amenity-modal-body">
				<form id="new-amenity-form" class="new-amenity-form">
					<?php wp_nonce_field( 'submit_new_amenity', 'amenity_nonce' ); ?>

					<div class="form-group">
						<label for="amenity_name">Amenity Name <span class="required">*</span></label>
						<input type="text" id="amenity_name" name="amenity_name" required>
					</div>

					<div class="form-group">
						<label for="amenity_category">Category <span class="required">*</span></label>
						<select id="amenity_category" name="amenity_category" required>
							<option value="">Select Category</option>
							<option value="Basic">Basic</option>
							<option value="Features">Features</option>
							<option value="Includes">Includes</option>
							<option value="Type of Fish">Type of Fish</option>
							<option value="Type of Game">Type of Game</option>
						</select>
					</div>

					<div class="form-group">
						<label for="amenity_description">Description <span class="required">*</span></label>
						<textarea id="amenity_description" name="amenity_description" rows="4" required></textarea>
					</div>

					<div class="form-group">
						<label for="amenity_image">Image URL</label>
						<input type="url" id="amenity_image" name="amenity_image" placeholder="https://example.com/image.jpg">
						<small>Optional: Enter the URL of an image for this amenity</small>
					</div>

					<input type="hidden" name="entry_id" value="<?php echo esc_attr( $last_entry_id ); ?>">

					<div class="form-actions">
						<button type="submit" class="submit-button">Submit Request</button>
						<button type="button" class="cancel-button new-amenity-modal-close">Cancel</button>
					</div>

					<div class="form-message" style="display:none;"></div>
				</form>
			</div>
		</div>
	</div>

	<style>
		.new-amenity-modal-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(0, 0, 0, 0.7);
			z-index: 99999;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.new-amenity-modal-container {
			background: white;
			border-radius: 8px;
			max-width: 600px;
			width: 90%;
			max-height: 90vh;
			overflow-y: auto;
			box-shadow: 0 4px 20px rgba(0,0,0,0.3);
		}
		.new-amenity-modal-header {
			padding: 20px;
			border-bottom: 1px solid #ddd;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}
		.new-amenity-modal-header h3 {
			margin: 0;
			font-size: 24px;
		}
		.new-amenity-modal-close {
			background: none;
			border: none;
			font-size: 32px;
			cursor: pointer;
			color: #999;
			line-height: 1;
			padding: 0;
			width: 32px;
			height: 32px;
		}
		.new-amenity-modal-close:hover {
			color: #333;
		}
		.new-amenity-modal-body {
			padding: 20px;
		}
		.new-amenity-form .form-group {
			margin-bottom: 20px;
		}
		.new-amenity-form label {
			display: block;
			margin-bottom: 5px;
			font-weight: bold;
		}
		.new-amenity-form .required {
			color: red;
		}
		.new-amenity-form input[type="text"],
		.new-amenity-form input[type="url"],
		.new-amenity-form select,
		.new-amenity-form textarea {
			width: 100%;
			padding: 10px;
			border: 1px solid #ddd;
			border-radius: 4px;
			font-size: 14px;
			box-sizing: border-box;
		}
		.new-amenity-form textarea {
			resize: vertical;
		}
		.new-amenity-form small {
			display: block;
			margin-top: 5px;
			color: #666;
			font-size: 12px;
		}
		.new-amenity-form .form-actions {
			display: flex;
			gap: 10px;
			margin-top: 20px;
		}
		.new-amenity-form .submit-button {
			flex: 1;
			padding: 12px 24px;
			background: #0073aa;
			color: white;
			border: none;
			border-radius: 4px;
			font-size: 16px;
			cursor: pointer;
		}
		.new-amenity-form .submit-button:hover {
			background: #005a87;
		}
		.new-amenity-form .submit-button:disabled {
			background: #ccc;
			cursor: not-allowed;
		}
		.new-amenity-form .cancel-button {
			padding: 12px 24px;
			background: #f0f0f0;
			color: #333;
			border: none;
			border-radius: 4px;
			font-size: 16px;
			cursor: pointer;
		}
		.new-amenity-form .cancel-button:hover {
			background: #e0e0e0;
		}
		.new-amenity-form .form-message {
			margin-top: 15px;
			padding: 10px;
			border-radius: 4px;
		}
		.new-amenity-form .form-message.success {
			background: #d4edda;
			color: #155724;
			border: 1px solid #c3e6cb;
		}
		.new-amenity-form .form-message.error {
			background: #f8d7da;
			color: #721c24;
			border: 1px solid #f5c6cb;
		}
	</style>

	<script>
	jQuery(document).ready(function($) {
		$('#new-amenity-form').on('submit', function(e) {
			e.preventDefault();

			var $form = $(this);
			var $submitBtn = $form.find('.submit-button');
			var $message = $form.find('.form-message');

			// Disable submit button
			$submitBtn.prop('disabled', true).text('Submitting...');
			$message.hide().removeClass('success error');

			// Prepare form data
			var formData = {
				action: 'submit_new_amenity',
				nonce: $form.find('#amenity_nonce').val(),
				amenity_name: $form.find('#amenity_name').val(),
				amenity_category: $form.find('#amenity_category').val(),
				amenity_description: $form.find('#amenity_description').val(),
				amenity_image: $form.find('#amenity_image').val(),
				entry_id: $form.find('input[name="entry_id"]').val()
			};

			// Submit via AJAX
			$.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', formData, function(response) {
				if (response.success) {
					$message.addClass('success').html('<strong>Success!</strong> Your amenity request has been submitted and an admin will review it shortly.').show();
					$form[0].reset();

					// Close modal after 3 seconds
					setTimeout(function() {
						$('#new-amenity-modal').fadeOut(300);
						$message.hide();
					}, 3000);
				} else {
					$message.addClass('error').html('<strong>Error:</strong> ' + (response.data || 'Something went wrong. Please try again.')).show();
				}
			}).fail(function() {
				$message.addClass('error').html('<strong>Error:</strong> Could not submit request. Please check your connection and try again.').show();
			}).always(function() {
				$submitBtn.prop('disabled', false).text('Submit Request');
			});
		});
	});
	</script>
	<?php
}
