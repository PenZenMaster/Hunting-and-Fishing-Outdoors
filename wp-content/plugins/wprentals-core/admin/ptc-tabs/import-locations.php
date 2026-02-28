<?php
/**
 * Import Locations tab renderer.
 *
 * Provides a simple interface to import state, city and
 * area terms from a CSV file.
 *
 * @package WpRentals Core
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the Import Locations tab.
 *
 * Includes a simple upload field for the CSV file and
 * a button to trigger the import via AJAX.
 */
function wprentals_ptc_render_import_locations_tab() {
    ?>
    <form id="wprentals-import-locations-form" method="post" action="">
        <?php wp_nonce_field( 'wprentals-import-locations', 'wprentals-import-locations-nonce' ); ?>
        
        <div class="wprentals-explanations">
            <?php echo esc_html__( 'To Import use a csv file with his header: State, City, Area. Or use the sample file from wprentals-core/samples/import-locations-sample.csv', 'wprentals-core' ); ?>
        </div>
            
        <div class="wprentals-row">
            <input type="text" id="wprentals-import-file" class="wprentals-2025-input" />            
            <button id="wprentals-upload-csv" class="button wprentals_button secondary"><?php echo esc_html__( 'Choose CSV File', 'wprentals-core' ); ?></button>
        </div>

        
        <div class="wprentals-row">
            <button id="wprentals-run-import" class="button wprentals_button button-primary"><?php echo esc_html__( 'Import', 'wprentals-core' ); ?></button>
        </div>
        <p id="wprentals-import-status"></p>
    </form>
    <?php
}
