<?php
/**
 * Taxonomies tab renderer.
 *
 * Displays toggle controls for each taxonomy grouped by
 * post type.
 *
 * @package WpRentals Core
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render the Taxonomies tab content.
 *
 * @param array $taxonomies Taxonomies organized by post type.
 * @param array $post_types Post type labels.
 * @param array $options    Saved options.
 */
function wprentals_ptc_render_taxonomies_tab( $taxonomies, $post_types, $options ) {
    ?>
    <div class="wprentals-settings">
    <?php
    $taxonomies_filtered = array_filter(
        $taxonomies,
        function ( $taxes, $ptype ) use ( $options ) {
            return ! ( isset( $options['post_types'][ $ptype ] ) && ! $options['post_types'][ $ptype ] );
        },
        ARRAY_FILTER_USE_BOTH
    );

    if ( empty( $taxonomies_filtered ) ) {
        echo '<p class="wprentals-row">' . esc_html__( 'There are no taxonomies to display because no post types with taxonomies are enabled.', 'wprentals-core' ) . '</p>';
        echo '</div>';
        return;
    }

    $last_ptype = array_key_last( $taxonomies_filtered );

    foreach ( $taxonomies_filtered as $ptype => $taxes ) :
        ?>
        <div class="wprentals-heading"><strong><?php echo esc_html( $post_types[ $ptype ] ); ?></strong></div>
        <?php foreach ( $taxes as $tax_slug => $tax_label ) :
            $enabled = isset( $options['taxonomies'][ $tax_slug ] ) ? (bool) $options['taxonomies'][ $tax_slug ] : true;
        ?>
        <div class="wprentals-row">
            <div class="wprentals-label">
                <?php echo esc_html( $tax_label ); ?>
            </div>
            <div class="wprentals-field">
                <div class="wprentals-toggle-wrapper">
                    <input type="checkbox" id="taxonomy_<?php echo esc_attr( $tax_slug ); ?>" name="taxonomies[<?php echo esc_attr( $tax_slug ); ?>]" value="1" class="wprentals-toggle-input" <?php checked( $enabled, true ); ?>>
                    <label for="taxonomy_<?php echo esc_attr( $tax_slug ); ?>" class="wprentals-toggle-label">
                        <span class="wprentals-toggle-slider"></span>
                    </label>
                    <span class="wprentals-toggle-text">
                        <?php echo $enabled ? esc_html__( 'Enabled', 'wprentals-core' ) : esc_html__( 'Disabled', 'wprentals-core' ); ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    <?php if ( $ptype !== $last_ptype ) : ?>
        <hr>
    <?php endif; ?>

<?php endforeach; ?>

    </div>
    <?php
}
