<?php
/**
 * Template Name: Add New Amenities
 *
 * Security hardened: 2026-02-13
 * - Added input sanitization
 * - Added capability checks
 * - Fixed SQL injection vulnerability
 * - Added XSS protection
 */

// Security: Check if user has permission to manage amenities.
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( __( 'You do not have permission to access this page.', 'wprentals' ) );
}

// Security: Generate nonce for AJAX approve/deny actions.
$hnfo_amenity_nonce = wp_create_nonce( 'hnfo_amenity_action' );

// Security: Sanitize and validate input
$amenity_entry_id = isset( $_GET['am_id'] ) ? absint( $_GET['am_id'] ) : 0;
$action           = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';

// Initialize variables
$add_amenity_name        = '';
$add_amenity_category    = '';
$add_amenity_description = '';
$add_amenity_img_url     = '';
$add_amenity_slug        = '';

// Security: Only proceed if we have a valid ID
if ( $amenity_entry_id > 0 ) {
	global $wpdb;
	// Security: Properly prepared statement
	$new_amenities_data = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT * FROM new_amenities WHERE new_amenity_entry_id = %d',
			$amenity_entry_id
		)
	);

	if ( ! empty( $new_amenities_data ) ) {
		// Security: Sanitize output data
		$add_amenity_name        = sanitize_text_field( $new_amenities_data[0]->nw_amenity_name );
		$add_amenity_category    = sanitize_text_field( $new_amenities_data[0]->nw_amenity_category );
		$add_amenity_description = sanitize_textarea_field( $new_amenities_data[0]->nw_amenity_description );
		$add_amenity_img_url     = esc_url( $new_amenities_data[0]->nw_amenity_image );
		$add_amenity_slug        = sanitize_title( $add_amenity_name );

		// Security: Process actions only for valid entries.
		if ( in_array( $action, array( 'Approve', 'Deny' ), true ) ) {
			$_POST['amenity_entry_id'] = $amenity_entry_id;
			if ( 'Approve' === $action && function_exists( 'hnfo_approve_add_new_amenity' ) ) {
				hnfo_approve_add_new_amenity();
			} elseif ( 'Deny' === $action && function_exists( 'hnfo_deny_add_new_amenity' ) ) {
				hnfo_deny_add_new_amenity();
			}
		}
	}
}
?>
<style type="text/css">
.vdf_add_amenity_content .vdf_col {
	text-align: center;
}

.vdf_amnty_btns {
	display: inline-flex;
	padding-top: 30px;
}

.vdf_amnty_btns .vdf_amnty_btns_1,
.vdf_amnty_btns .vdf_amnty_btns_2 {
	margin: 0 20px;
}

.vdf_amnty_btns .vdf_amnty_btns_1 .vdf_approve {
	padding: 8px 15px;
	border: none;
	background: darkslateblue;
	color: #fff;
	border-radius: 7px;
	cursor: pointer;
}

.vdf_amnty_btns .vdf_amnty_btns_2 .vdf_deny {
	padding: 8px 15px;
	border: none;
	color: #000;
	border-radius: 7px;
	cursor: pointer;
}

#vdf_cnfrm_main01 p {
	display: none;
}

.vdf_amnty_details_main {
	width: 24%;
	margin: auto;
	text-align: left;
}

.vdf_amnty_dtls_content {
	display: flex;
}

.vdf_amnty_names {
	width: 55%;
	font-weight: 600;
}

.vdf_amnty_values {
	width: 50%;
}

.vdf_amnty_values a {
	text-decoration: none;
}

.vdf_open_error p {
	text-align: center;
	font-size: 20px;
	font-weight: 600;
}
</style>
<?php
function add_amenity( $add_amenity_name, $add_amenity_category, $add_amenity_description ) {

	echo '<div class="vdf_add_amenity_main">
        <div class="vdf_add_amenity_content">
            <div class="vdf_container">
                <div class="vdf_row">
                    <div class="vdf_col">
                        <div class="vdf_amnty_text">
                            <h3>Click Approve to approve new amenity request</h3>
                        </div>
                        <div class="vdf_amnty_details_main">
                          <div class="vdf_amnty_dtls_content">
                            <div class="vdf_amnty_names">
                              <p>Amenity name</p>
                              <p>Amenity category</p>
                              <p>Amenity description</p>
                            </div>
                            <div class="vdf_amnty_values">
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_name . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_category . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_description . '</p>
                            </div>
                          </div>
                        </div>

                        <div class="vdf_cnfrm_main" id="vdf_cnfrm_main01">
                        ';
	// Security: Use sanitized variable instead of raw $_GET
	if ( 'Approve' === $action ) {
		echo ' <div class="vdf_cnfrm_msg" style="display:block">
                            <p style="display:block" id="vdf_appr_confrm01">New amenity request was added successfully</p>
                          </div>';
	}
	if ( 'Deny' === $action ) {
		echo '<div class="vdf_cnfrm_msg" >
                            <p style="display:block" id="vdf_deny_confrm01">New amenity request was denied successfully</p>
                          </div>';
	}
	echo '</div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}
function add_amenity_other( $add_amenity_name, $add_amenity_category, $add_amenity_description, $add_amenity_img_url ) {

	echo '<div class="vdf_add_amenity_main">
        <div class="vdf_add_amenity_content">
            <div class="vdf_container">
                <div class="vdf_row">
                    <div class="vdf_col">
                        <div class="vdf_amnty_text">
                            <h3>Click Approve to approve new amenity request</h3>
                        </div>
                        <div class="vdf_amnty_details_main">
                          <div class="vdf_amnty_dtls_content">
                            <div class="vdf_amnty_names">
                              <p>Amenity name</p>
                              <p>Amenity category</p>
                              <p>Amenity description</p>
                              <p>Amenity Image</p>
                            </div>
                            <div class="vdf_amnty_values">
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_name . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_category . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_description . '</p>
                              <p><span>:</span>&nbsp;&nbsp;<a href="' . $add_amenity_img_url . '" target="_blank">View</a></p>
                            </div>
                          </div>
                        </div>

                        <div class="vdf_cnfrm_main" id="vdf_cnfrm_main01">
                        ';
	// Security: Use sanitized variable instead of raw $_GET
	if ( 'Approve' === $action ) {
		echo ' <div class="vdf_cnfrm_msg" style="display:block">
                                                <p style="display:block" id="vdf_appr_confrm01">New amenity request was added successfully</p>
                                              </div>';
	}
	if ( 'Deny' === $action ) {
		echo '<div class="vdf_cnfrm_msg" style="display:block">
                                                <p style="display:block" id="vdf_deny_confrm01">New amenity request was denied successfully</p>
                                              </div>';
	}
	echo '</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}
function add_amenity_error() {

	echo '<div class="vdf_opn_again_err">
        <div class="vdf_open_error">
            <p>This request is no longer available because it has been already processed.</p>
        </div>
    </div>';
}
// var_dump($add_amenity_category);
// var_dump($add_amenity_name);
// var_dump($add_amenity_description);
// var_dump($add_amenity_slug);
// var_dump($add_amenity_img_url);
if ( $add_amenity_category == 'Basic' || $add_amenity_category == 'Includes' || $add_amenity_category == 'Features' ) {

	if ( $add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_slug != '' ) {
		add_amenity( $add_amenity_name, $add_amenity_category, $add_amenity_description );
	} else {
		add_amenity_error();
	}
} elseif ( $add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_slug != '' && $add_amenity_img_url != '' ) {
		add_amenity_other( $add_amenity_name, $add_amenity_category, $add_amenity_description, $add_amenity_img_url );
} else {
	add_amenity_error();
}

?>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
	integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script type="text/javascript">
(function($) {
	var ajaxUrl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
	var nonce   = '<?php echo esc_js( $hnfo_amenity_nonce ); ?>';
	// Use URLSearchParams for reliable ID extraction.
	var amenityId = parseInt( new URLSearchParams( window.location.search ).get( 'am_id' ) || '0', 10 );

	$('#vdf_approve01').on('click', function() {
		$(this).parent().parent().css('display', 'none');
		$('#vdf_cnfrm_main01 #vdf_appr_confrm01').css('display', 'block');
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxUrl,
			data: {
				action: 'approve_add_new_amenity',
				amenity_entry_id: amenityId,
				nonce: nonce
			},
			success: function(response) {
				console.log(response);
			},
			error: function(xhr, status, error) {
				console.log(error);
				try { console.log(JSON.parse(xhr.responseText)); } catch(e) {}
			}
		});
	});

	$('#vdf_deny01').on('click', function() {
		$(this).parent().parent().css('display', 'none');
		$('#vdf_cnfrm_main01 #vdf_deny_confrm01').css('display', 'block');
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxUrl,
			data: {
				action: 'deny_add_new_amenity',
				amenity_entry_id: amenityId,
				nonce: nonce
			},
			success: function(response) {
				console.log(response);
			},
			error: function(xhr, status, error) {
				console.log(error);
				try { console.log(JSON.parse(xhr.responseText)); } catch(e) {}
			}
		});
	});
}(jQuery));
</script>