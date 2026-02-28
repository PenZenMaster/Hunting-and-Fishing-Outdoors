<?php

if (!function_exists('wpestate_enable_registration_for_theme')) {
    /**
     * Ensure WordPress front-end registration remains enabled when the theme is activated.
     */
    function wpestate_enable_registration_for_theme(): void
    {
        update_option('users_can_register', 1);
    }
}

add_action('after_switch_theme', 'wpestate_enable_registration_for_theme');

if (!function_exists('wpestate_enable_registration_after_theme_update')) {
    /**
     * Re-enable registration when the theme is updated through the WordPress upgrader.
     *
     * @param WP_Upgrader $upgrader   The upgrader instance.
     * @param array       $hook_extra Context about the upgrade operation.
     */
    function wpestate_enable_registration_after_theme_update($upgrader, $hook_extra): void
    {
        if (!isset($hook_extra['type']) || 'theme' !== $hook_extra['type']) {
            return;
        }

        if (empty($hook_extra['themes'])) {
            return;
        }

        $current_theme = wp_get_theme();
        $current_slugs = array_filter([
            $current_theme->get_stylesheet(),
            $current_theme->get_template(),
        ]);

        foreach ((array) $hook_extra['themes'] as $updated_theme) {
            if (in_array($updated_theme, $current_slugs, true)) {
                update_option('users_can_register', 1);
                break;
            }
        }
    }
}

add_action('upgrader_process_complete', 'wpestate_enable_registration_after_theme_update', 10, 2);



/**
 * Utility helpers around user registration security for the front-end forms.
 *
 * @package WPRentals
 */

if (!function_exists('wpestate_can_register_users')) {
    /**
     * Determine if WordPress allows the creation of new users via registration.
     *
     * This wrapper centralises the `users_can_register` option usage and exposes
     * a dedicated filter that can be leveraged by child themes or plugins.
     *
     * @return bool
     */
    function wpestate_can_register_users(): bool
    {
        $can_register = get_option('users_can_register');

        /**
         * Filter whether the front-end registration flow is permitted.
         *
         * @param bool $can_register The option value before casting.
         */
        $can_register = apply_filters('wpestate_users_can_register', $can_register);

        return (bool) $can_register;
    }
}
