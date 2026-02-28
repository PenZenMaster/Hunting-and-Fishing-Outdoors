<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Handles admin facing notices for the WpRentals Studio integration.
 *
 * The helper mirrors the behaviour of the Residence Studio plugin by warning
 * the site owner when Elementor or the minimum version requirements are not
 * satisfied. Keeping these notices in core prevents silent failures when the
 * Studio bundle is bundled as part of the WpRentals theme.
 */
class WpRentals_Elementor_Admin_Notices {

    /**
     * Admin notice for missing main plugin
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'wprentals-core'),
            '<strong>' . esc_html__('WpRentals Elementor', 'wprentals-core') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'wprentals-core') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for minimum Elementor version
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'wprentals-core'),
            '<strong>' . esc_html__('WpRentals Elementor', 'wprentals-core') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'wprentals-core') . '</strong>',
            WpRentals_Elementor_Design_Studio::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for minimum PHP version
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'wprentals-core'),
            '<strong>' . esc_html__('WpRentals Elementor', 'wprentals-core') . '</strong>',
            '<strong>' . esc_html__('PHP', 'wprentals-core') . '</strong>',
            WpRentals_Elementor_Design_Studio::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
}
