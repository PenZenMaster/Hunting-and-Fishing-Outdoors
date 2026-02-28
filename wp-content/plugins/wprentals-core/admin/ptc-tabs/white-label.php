<?php
/**
 * White Label tab renderer.
 *
 * Provides an interface to configure branding options
 * directly from the admin area.
 *
 * @package WpRentals Core
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the White Label settings tab.
 *
 * Allows users to change theme name, author and logo
 * directly from the admin dashboard.
 */
function wprentals_ptc_render_white_label_tab() {
    if ( defined( 'HIDE_WHITE_LABEL_ACCESS' ) && HIDE_WHITE_LABEL_ACCESS === true ) {
        echo '<div class="notice notice-warning"><p>' . esc_html__( 'White Label settings are currently hidden via wp-config.php', 'wprentals-core' ) . '</p></div>';
        return;
    }

    $branding = wprentals_white_label_get_settings();
    ?>
    <form method="post" action="options.php" class="wprentals-half">
        <?php settings_fields( 'wprentals_white_label' ); ?>

        <div class="wprentals-explanations">
            <?php echo esc_html__( "Use define('HIDE_WHITE_LABEL_ACCESS', true); in wp-config.php if you want to hide this section", 'wprentals-core' ); ?>
        </div>

        <div class="wprentals-form">
            <div class="wprentals-row wprentals-column">
                <label for="wl-branding" class="wprentals-label-full" ><?php echo esc_html__( 'Theme Branding', 'wprentals-core' ); ?></label>
                <input type="text" class="wprentals-2025-input" id="wl-branding" name="wprentals_white_label[branding]" value="<?php echo esc_attr( $branding['branding'] ); ?>">
                <p class="description"><?php echo esc_html__( 'Replaces "WpRentals" text in the admin area.', 'wprentals-core' ); ?></p>
            </div>
            <div class="wprentals-row wprentals-column">
                <label for="wl-name" class="wprentals-label-full"><?php echo esc_html__( 'Theme Name', 'wprentals-core' ); ?></label>
                <input type="text" class="wprentals-2025-input" id="wl-name" name="wprentals_white_label[name]" value="<?php echo esc_attr( $branding['name'] ); ?>">
                <p class="description"><?php echo esc_html__( 'Change the text.', 'wprentals-core' ); ?></p>
            </div>
            <div class="wprentals-row wprentals-column">
                <label for="wl-author" class="wprentals-label-full"><?php echo esc_html__( 'Theme Author', 'wprentals-core' ); ?></label>
                <input type="text" class="wprentals-2025-input" id="wl-author" name="wprentals_white_label[author]" value="<?php echo esc_attr( $branding['author'] ); ?>">
                <p class="description"><?php echo esc_html__( 'Change the text.', 'wprentals-core' ); ?></p>
            </div>
            <div class="wprentals-row wprentals-column">
                <label for="wl-author-url" class="wprentals-label-full"><?php echo esc_html__( 'Author URL', 'wprentals-core' ); ?></label>
                <input type="text" class="wprentals-2025-input" id="wl-author-url" name="wprentals_white_label[author_url]" value="<?php echo esc_url( $branding['author_url'] ); ?>">
                <p class="description"><?php echo esc_html__( 'Change the text.', 'wprentals-core' ); ?></p>
            </div>
            <div class="wprentals-row wprentals-column">
                <label for="wl-description" class="wprentals-label-full" ><?php echo esc_html__( 'Theme Description', 'wprentals-core' ); ?></label>
                <textarea class="wprentals-2025-input " rows="3" id="wl-description" name="wprentals_white_label[description]"><?php echo esc_textarea( $branding['description'] ); ?></textarea>
                <p class="description"><?php echo esc_html__( 'Change the text.', 'wprentals-core' ); ?></p>
            </div>
            <div class="wprentals-row wprentals-column">
                <label for="wl-screenshot" class="wprentals-label-full"><?php echo esc_html__( 'Screenshot URL', 'wprentals-core' ); ?></label>
                <input type="text" class="wprentals-2025-input wl-media-field" id="wl-screenshot" name="wprentals_white_label[screenshot]" value="<?php echo esc_url( $branding['screenshot'] ); ?>">
                <button type="button" class="button wl-screenshot-upload wprentals_button small_button "><?php echo esc_html__( 'Upload', 'wprentals-core' ); ?></button>
                <p class="description"><?php echo esc_html__( 'Change the text.', 'wprentals-core' ); ?></p>
            </div>
            <div class="wprentals-row wprentals-column">
                <label for="wl-logo" class="wprentals-label-full"><?php echo esc_html__( 'Branding Logo URL', 'wprentals-core' ); ?></label>
                <input type="text" class="wprentals-2025-input wl-media-field" id="wl-logo" name="wprentals_white_label[branding_logo]" value="<?php echo esc_url( $branding['branding_logo'] ); ?>">
                <button type="button" class="button wl-logo-upload wprentals_button small_button"><?php echo esc_html__( 'Upload', 'wprentals-core' ); ?></button>
                <p class="description"><?php echo esc_html__( 'Change the text.', 'wprentals-core' ); ?></p>
            </div>
            <div class="wprentals-row wprentals_check_row">
                 <input type="checkbox" class="wprentals_checkbox" id="wprentals_hide_themes_customizer" name="wprentals_white_label[hide_themes_customizer]" value="1" <?php checked( $branding['hide_themes_customizer'], true ); ?>>
                 <label class="wprentals-label-full" for="wprentals_hide_themes_customizer" ><?php echo esc_html__( 'Hide "Themes" section from Customizer', 'wprentals-core' ); ?></label>
            
            </div>
        </div>
        <?php submit_button( 'Save', 'primary wprentals_button' ); ?>
    </form>




    <?php
}

