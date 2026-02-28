<?php
/**
 * WpRentals License tab.
 * Displays registration and deregistration forms for the theme license.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Output the License tab content.
 */
function wprentals_ptc_render_license_tab() {
    $license = WpestateFunk::get_instance();
    echo '<div class="wprentals-settings">';
    $license->show_deregister_license_form();
    echo '</div>';
}
