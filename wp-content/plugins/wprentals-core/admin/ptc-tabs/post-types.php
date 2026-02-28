<?php
/**
 * Post Types tab renderer.
 *
 * Displays toggle controls for enabling or disabling
 * custom post types in the admin area.
 *
 * @package WpRentals Core
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the Post Types tab content.
 *
 * @param array $post_types List of post types.
 * @param array $options    Saved options.
 */
function wprentals_ptc_render_post_types_tab( $post_types, $options ) {
    ?>
    <div class="wprentals-settings">
        <?php foreach ( $post_types as $slug => $label ) :
            $enabled = isset( $options['post_types'][ $slug ] ) ? (bool) $options['post_types'][ $slug ] : true;
        ?>
            <div class="wprentals-row">
                <div class="wprentals-label">
                    <?php echo esc_html( $label ); ?>
                </div>
                <div class="wprentals-field">
                    <div class="wprentals-toggle-wrapper">
                        <input type="checkbox" id="post_type_<?php echo esc_attr( $slug ); ?>" name="post_types[<?php echo esc_attr( $slug ); ?>]" value="1" class="wprentals-toggle-input" <?php checked( $enabled, true ); ?>>
                        <label for="post_type_<?php echo esc_attr( $slug ); ?>" class="wprentals-toggle-label">
                            <span class="wprentals-toggle-slider"></span>
                        </label>
                        <span class="wprentals-toggle-text">
                            <?php echo $enabled ? esc_html__( 'Enabled', 'wprentals-core' ) : esc_html__( 'Disabled', 'wprentals-core' ); ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}
