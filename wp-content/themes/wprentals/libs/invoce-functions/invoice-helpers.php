<?php
/**
 * Invoice helper functions.
 *
 * The helpers declared in this file expose the labels and lookup utilities
 * required to work with numeric invoice type identifiers while remaining
 * compatible with legacy text values that may still be stored.
 */

if (!function_exists('wpestate_get_invoice_type_raw_labels')) {
    /**
     * Return the base English labels for every invoice type.
     *
     * @return array<int, string> Map of invoice identifiers to untranslated labels.
     */
    function wpestate_get_invoice_type_raw_labels()
    {
        // Provide the raw labels that match the historical text values.
        return array(
            WP_ESTATE_INVOICE_TYPE_UPGRADE_TO_FEATURED   => 'Upgrade to Featured',
            WP_ESTATE_INVOICE_TYPE_PUBLISH_WITH_FEATURED => 'Publish Listing with Featured',
            WP_ESTATE_INVOICE_TYPE_PACKAGE               => 'Package',
            WP_ESTATE_INVOICE_TYPE_LISTING               => 'Listing',
            WP_ESTATE_INVOICE_TYPE_RESERVATION_FEE       => 'Reservation fee',
        );
    }
}

if (!function_exists('wpestate_get_invoice_type_labels')) {
    /**
     * Return the translated invoice labels keyed by numeric identifier.
     *
     * @return array<int, string> Translation-ready invoice labels.
     */
    function wpestate_get_invoice_type_labels()
    {
        // Cache the translated labels to avoid repeated translation work.
        static $invoice_type_labels = null;

        if ($invoice_type_labels === null) {
            // Initialize the cache and translate each base label once.
            $invoice_type_labels = array();
            foreach (wpestate_get_invoice_type_raw_labels() as $key => $label) {
                // Translate the label within the theme text domain.
                $invoice_type_labels[$key] = esc_html__($label, 'wprentals');
            }
        }

        return $invoice_type_labels;
    }
}

if (!function_exists('wpestate_get_invoice_type_key')) {
    /**
     * Normalize an incoming invoice type value to its numeric identifier.
     *
     * @param string|int|null $value Incoming invoice type value.
     *
     * @return int|null The normalized numeric key or null when the value cannot be mapped.
     */
    function wpestate_get_invoice_type_key($value)
    {
        // Guard against empty values that cannot be normalized.
        if ($value === null || $value === '') {
            return null;
        }

        // Fetch the translated labels to validate numeric identifiers and strings.
        $labels = wpestate_get_invoice_type_labels();

        if (is_numeric($value)) {
            // Cast the numeric string or integer to an integer value.
            $int_value = intval($value);
            if (isset($labels[$int_value])) {
                // Return the identifier when it matches a known invoice type.
                return $int_value;
            }
        }

        // Compare against the translated labels using trimmed string values.
        $value = trim((string) $value);

        foreach ($labels as $key => $label) {
            // Match on the translated label first to support localized strings.
            if ($value === $label) {
                return $key;
            }
        }

        // Fall back to matching against the raw untranslated labels.
        $raw_labels = wpestate_get_invoice_type_raw_labels();
        foreach ($raw_labels as $key => $raw_label) {
            if ($value === $raw_label) {
                return $key;
            }
        }

        // Indicate failure to normalize when no match is found.
        return null;
    }
}

if (!function_exists('wpestate_get_invoice_type_label')) {
    /**
     * Retrieve the translated label for an invoice type value.
     *
     * @param string|int|null $value Invoice type value that may be numeric or textual.
     *
     * @return string The translated label or the raw value when no match is available.
     */
    function wpestate_get_invoice_type_label($value)
    {
        // Fetch the translated labels and normalize the provided value.
        $labels = wpestate_get_invoice_type_labels();
        $key    = wpestate_get_invoice_type_key($value);

        if ($key !== null && isset($labels[$key])) {
            // Return the translated label when the identifier is recognized.
            return $labels[$key];
        }

        // Fall back to returning the trimmed string representation.
        return trim((string) $value);
    }
}

if (!function_exists('wpestate_get_invoice_type_legacy_values')) {
    /**
     * Gather every legacy text value that could represent the provided invoice type.
     *
     * @param int $type_key Numeric invoice type identifier.
     *
     * @return array<int, string> Set of text labels historically stored in post meta.
     */
    function wpestate_get_invoice_type_legacy_values($type_key)
    {
        // Start with an empty collection of possible legacy labels.
        $legacy_values = array();
        $raw_labels    = wpestate_get_invoice_type_raw_labels();

        if (isset($raw_labels[$type_key])) {
            // Seed the list with the original untranslated base label.
            $base_label = $raw_labels[$type_key];
            $legacy_values[] = $base_label;

            // Add the default translation to ensure the current locale is covered.
            $translated_label = esc_html__($base_label, 'wprentals');
            if (!in_array($translated_label, $legacy_values, true)) {
                $legacy_values[] = $translated_label;
            }

            if (class_exists('Sitepress')) {
                // Capture the initial language so we can restore it afterwards.
                $wpml_current_language = apply_filters('wpml_current_language', null);
                $wpml_languages        = apply_filters('wpml_active_languages', null, 'orderby=id&order=desc');

                if (!empty($wpml_languages)) {
                    foreach ($wpml_languages as $language) {
                        // Switch the active language to pull the translated string.
                        do_action('wpml_switch_language', $language['language_code']);
                        $translated_string = esc_html__($base_label, 'wprentals');
                        if (!in_array($translated_string, $legacy_values, true)) {
                            // Record each unique translation for the migration to match.
                            $legacy_values[] = $translated_string;
                        }
                    }

                    if ($wpml_current_language !== null) {
                        // Restore the original language context after iteration.
                        do_action('wpml_switch_language', $wpml_current_language);
                    }
                }
            }
        }

        return $legacy_values;
    }
}

