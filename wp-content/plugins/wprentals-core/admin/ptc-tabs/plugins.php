<?php
/**
 * Plugins tab renderer helpers.
 *
 * Provides functions to list required and recommended
 * plugins and render an install table in the admin.
 *
 * @package WpRentals Core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Retrieve the list of plugins used by the theme.
 *
 * Falls back to a hard coded list if the helper function
 * from the theme is not available.
 *
 * @return array Plugin definitions.
 */


function wprentals_ptc_get_plugins() {
    if ( function_exists( 'wpestate_return_requred_plugins' ) ) {
        $plugins = wpestate_return_requred_plugins();
        if ( is_array( $plugins ) ) {
            return $plugins;
        }
    }
    return array(
        array(
            'name'    => 'WpRentals Core functionality',
            'slug'    => 'wprentals-core',
            'source'  => get_theme_file_uri( '/libs/plugins/wprentals-core.zip' ),
            'version' => '5.2.0',
            'required'=> true,
            'description' => 'Functionality for the theme',
            'image'   => WPESTATE_PLUGIN_DIR_URL . 'img/wprentals-plugins-icon.png',
        ),
        array(
            'name'    => 'WpRentals Gutenberg Blocks functionality',
            'slug'    => 'rentals-gutenberg',
            'source'  => get_theme_file_uri( '/libs/plugins/wprentals-gutenberg.zip' ),
            'version' => '1.50.1',
            'required'=> false,
            'image'   => WPESTATE_PLUGIN_DIR_URL . 'img/wprentals-plugins-icon.png',
        ),
        array(
            'name'    => 'WpRentals Elementor Addon',
            'slug'    => 'wprentals-elementor',
            'source'  => get_theme_file_uri( '/libs/plugins/wprentals-elementor.zip' ),
            'version' => '5.2.0',
            'required'=> true,
            'image'   => WPESTATE_PLUGIN_DIR_URL . 'img/wprentals-plugins-icon.png',
        ),
        // array(
        //     'name'    => 'WpRentals Elementor Design Studio',
        //     'slug'    => 'wprentals-studio',
        //     'source'  => get_theme_file_uri( '/libs/plugins/wprentals-studio.zip' ),
        //     'version' => '5.2.0',
        //     'required'=> true,
        // ),
        // array(
        //     'name'    => 'WpEstate CRM',
        //     'slug'    => 'wpestate-crm',
        //     'source'  => get_theme_file_uri( '/libs/plugins/wpestate-crm.zip' ),
        //     'version' => '5.2.0',
        //     'required'=> true,
        // ),
        array(
            'name'    => 'Revolution Slider',
            'slug'    => 'revslider',
            'source'  => 'https://plugins.wpestate.org/revslider.zip',
            'version' => '6.7.35',
            'required'=> false,
            'image'   =>  esc_url(get_theme_file_uri('/img/revolution-slider.png'))
        ),
        array(
            'name'    => 'WPBakery Visual Composer',
            'slug'    => 'js_composer',
            'source'  => 'https://plugins.wpestate.org/js_composer.zip',
            'version' => '8.5',
            'required'=> false,
            'image'   => esc_url(get_theme_file_uri('/img/bakery.png'))
        ),
        array(
            'name'    => 'Elementor',
            'slug'    => 'elementor',
            'source'  => 'https://downloads.wordpress.org/plugin/elementor.3.30.2.zip',
            'version' => '3.30.2',
            'required'=> false,
            'image'   => esc_url(get_theme_file_uri('/img/elementor-logo.png')) 
        ),
        array(
            'name'    => 'One Click Demo Import',
            'slug'    => 'one-click-demo-import',
            'source'  => 'https://downloads.wordpress.org/plugin/one-click-demo-import.3.3.0.zip',
            'version' => '3.3.0',
            'required'=> true,
        ),
        array(
            'name'    => 'Envato Market',
            'slug'    => 'envato-market',
            'source'  => 'https://goo.gl/pkJS33',
            'version' => '2.0.11',
            'required'=> true,
            'image' => esc_url(get_theme_file_uri('/img/envato.png')) 
        ),
        // array(
        //     'name'    => 'MLS Import - Import MLS listings in your website',
        //     'slug'    => 'mlsimport',
        //     'source'  => 'https://wordpress.org/plugins/mlsimport/',
        //     'version' => '6.0.7',
        //     'required'=> false,
        // ),
        array(
            'name'    => 'SVG Support for Demo Import',
            'slug'    => 'svg-support',
            'source'  => 'https://downloads.wordpress.org/plugin/svg-support.2.5.14.zip',
            'version' => '2.5.14',
            'required'=> false,
        ),
    );
}

/**
 * Locate the plugin file for a given slug.
 *
 * @param string $slug Plugin directory slug.
 * @return string Plugin file path or empty string if not found.
 */
function wprentals_ptc_get_plugin_file( $slug ) {
    $plugins = get_plugins();
    foreach ( $plugins as $file => $data ) {
        if ( dirname( $file ) === $slug ) {
            return $file;
        }
    }
    return '';
}

/**
 * Generate a URL to a plugin icon based on its slug.
 *
 * @param string $slug Plugin directory slug.
 * @return string Icon URL.
 */
function wprentals_ptc_get_plugin_icon( $slug ) {
    $icon = 'https://ps.w.org/' . $slug . '/assets/icon-128x128.png';
    return esc_url( $icon );
}

/**
 * Output the Plugins tab with installation actions.
 *
 * Lists each plugin along with its status and provides
 * download or activation links.
 */
function wprentals_ptc_render_plugins_tab() {
    if ( ! current_user_can( 'install_plugins' ) ) {
        return;
    }

    $plugins = wprentals_ptc_get_plugins();
    ?>
    <div class="wprentals-plugin-table">
        <div class="wprentals-plugin-header">
            <div class="col-icon"><?php echo esc_html__( 'Plugin', 'wprentals-core' ); ?></div>
            <div class="col-name"></div>
            <div class="col-required"><?php echo esc_html__( 'Required', 'wprentals-core' ); ?></div>
            <div class="col-version"><?php echo esc_html__( 'Version', 'wprentals-core' ); ?></div>
            <div class="col-status"><?php echo esc_html__( 'Status', 'wprentals-core' ); ?></div>
            <div class="col-action"><?php echo esc_html__( 'Action', 'wprentals-core' ); ?></div>
        </div>

        <?php foreach ( $plugins as $plugin ) :
            $file         = wprentals_ptc_get_plugin_file( $plugin['slug'] );
            $is_installed = ! empty( $file );
            $is_active    = $is_installed && is_plugin_active( $file );
            $icon_url     = isset( $plugin['image'] ) ? $plugin['image'] : wprentals_ptc_get_plugin_icon( $plugin['slug'] );
            $activate_url = '';
            if ( $is_installed && ! $is_active ) {
                $activate_url = wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $file ), 'activate-plugin_' . $file );
            }
        ?>
            <div class="wprentals-plugin-row">
                <div class="col-icon"><img src="<?php echo esc_url( $icon_url ); ?>" width="80" height="80" alt="" /></div>
                <div class="col-name">
                    <strong><?php echo esc_html( $plugin['name'] ); ?></strong>
                    <?php if ( ! empty( $plugin['description'] ) ) : ?>
                        <div class="description"><?php echo esc_html( $plugin['description'] ); ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-required"><?php echo ! empty( $plugin['required'] ) ? esc_html__( 'Yes', 'wprentals-core' ) : esc_html__( 'No', 'wprentals-core' ); ?></div>
                <div class="col-version"><?php echo esc_html( $plugin['version'] ); ?></div>
                <?php if ( $is_active ) : ?>
                    <div class="col-status"><?php echo esc_html__( 'Active', 'wprentals-core' ); ?></div>
                   <div class="col-action"><?php echo esc_html__( 'No action required', 'wprentals-core' ); ?></div>
                <?php elseif ( $is_installed ) : ?>
                    <div class="col-status"><?php echo esc_html__( 'Installed', 'wprentals-core' ); ?></div>
                    <div class="col-action"><a class="button wprentals_button" href="<?php echo esc_url( $activate_url ); ?>"><?php echo esc_html__( 'Activate', 'wprentals-core' ); ?></a></div>
                <?php else : ?>
                    <div class="col-status"><?php echo esc_html__( 'Not Installed', 'wprentals-core' ); ?></div>
                    <div class="col-action"><a class="button wprentals_button secondary wprentals-install-plugin" href="#" data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>" data-source="<?php echo esc_url( $plugin['source'] ); ?>"><?php echo esc_html__( 'Install', 'wprentals-core' ); ?></a></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Enqueue scripts for the Plugins tab.
 *
 * @param string $hook Current admin page hook.
 */
function wprentals_ptc_enqueue_plugins_scripts( $hook ) {
    $page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
    $tab  = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';

    $page_match = 'wprentals-post-type-control-plugins' === $page;
    $tab_match  = 'wprentals-post-type-control' === $page && 'plugins' === $tab;

    if ( 'wprentals-settings_page_wprentals-post-type-control-plugins' === $hook || $page_match || $tab_match ) {
        wp_enqueue_script(
            'wprentals-plugin-install',
            WPESTATE_PLUGIN_DIR_URL . 'admin/js/plugin-install.js',
            array( 'jquery' ),
            '1.0',
            true
        );

        wp_localize_script(
            'wprentals-plugin-install',
            'wprentalsInstallPlugin',
            array(
                'nonce'      => wp_create_nonce( 'wprentals-install-plugin' ),
                'installing' => __( 'Installing...', 'wprentals-core' ),
                'error'      => __( 'Installation failed.', 'wprentals-core' ),
            )
        );
    }
}
add_action( 'admin_enqueue_scripts', 'wprentals_ptc_enqueue_plugins_scripts' );

/**
 * Handle AJAX requests to install a plugin from the plugins tab.
 */
function wprentals_ptc_handle_install_plugin() {
    check_ajax_referer( 'wprentals-install-plugin', 'nonce' );

    if ( ! current_user_can( 'install_plugins' ) ) {
        wp_send_json_error( __( 'Insufficient permissions.', 'wprentals-core' ) );
    }

    $user_id = get_current_user_id();
    $limit_key = 'wprentals_install_' . $user_id;
    if ( get_transient( $limit_key ) ) {
        wp_send_json_error( __( 'Please wait before installing another plugin.', 'wprentals-core' ) );
    }
    set_transient( $limit_key, time(), MINUTE_IN_SECONDS );

    $slug   = isset( $_POST['slug'] ) ? sanitize_key( wp_unslash( $_POST['slug'] ) ) : '';
    $source = isset( $_POST['source'] ) ? esc_url_raw( wp_unslash( $_POST['source'] ) ) : '';

    if ( ! $slug || ! $source ) {
        wp_send_json_error( __( 'Missing plugin information.', 'wprentals-core' ) );
    }

    $plugin_def  = null;
    $allowed_src = '';
    foreach ( wprentals_ptc_get_plugins() as $p ) {
        if ( $p['slug'] === $slug ) {
            $plugin_def  = $p;
            $allowed_src = esc_url_raw( $p['source'] );
            break;
        }
    }

    if ( ! $plugin_def || $allowed_src !== $source ) {
        wp_send_json_error( __( 'Invalid plugin source.', 'wprentals-core' ) );
    }

    $path = parse_url( $source, PHP_URL_PATH );
    $extension = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
    if ( $extension && 'zip' !== $extension ) {
        wp_send_json_error( __( 'Invalid plugin package.', 'wprentals-core' ) );
    }

    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/plugin.php';

    $skin     = new WP_Ajax_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader( $skin );
    $result   = $upgrader->install( $source );

    $errors = method_exists( $skin, 'get_errors' ) ? $skin->get_errors() : null;
    if ( ! $result || is_wp_error( $result ) || ( $errors instanceof WP_Error && $errors->has_errors() ) ) {
        if ( is_wp_error( $result ) ) {
            $message = $result->get_error_message();
        } elseif ( $errors instanceof WP_Error ) {
            $message = implode( '\n', $errors->get_error_messages() );
        } else {
            $message = __( 'Installation failed.', 'wprentals-core' );
        }

        wp_send_json_error( $message );
    }

    delete_transient( $limit_key );
    wp_send_json_success( __( 'Plugin installed.', 'wprentals-core' ) );
}
add_action( 'wp_ajax_wprentals_install_plugin', 'wprentals_ptc_handle_install_plugin' );

