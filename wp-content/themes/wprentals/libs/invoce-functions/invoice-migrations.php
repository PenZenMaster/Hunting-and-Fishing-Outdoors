<?php
/**
 * Invoice migration helpers.
 *
 * These routines ensure that existing invoices normalize their `invoice_type`
 * meta to the new numeric identifiers and run only once within the admin area.
 */

if (!function_exists('wpestate_migrate_legacy_invoice_type_meta')) {
    /**
     * Convert legacy invoice type text values to numeric identifiers.
     *
     * @return bool True when at least one invoice was updated, otherwise false.
     */
    function wpestate_migrate_legacy_invoice_type_meta()
    {
        // Process invoices in batches to avoid exhausting memory on large sites.
        $paged       = 1;
        $batch_size  = 200;
        $updated_any = false;

        do {
            // Query a single page of invoices that store the invoice_type meta key.
            $query = new WP_Query(
                array(
                    'post_type'      => 'wpestate_invoice',
                    'post_status'    => 'any',
                    'posts_per_page' => $batch_size,
                    'paged'          => $paged,
                    'fields'         => 'ids',
                    'meta_key'       => 'invoice_type',
                )
            );

            if (!$query->have_posts()) {
                // Exit the loop early when no invoices remain to process.
                break;
            }

            foreach ($query->posts as $invoice_id) {
                // Retrieve the current invoice type meta value for inspection.
                $current_value = get_post_meta($invoice_id, 'invoice_type', true);
                // Normalize the value to its numeric identifier when possible.
                $normalized    = wpestate_get_invoice_type_key($current_value);

                if ($normalized !== null && (string) $current_value !== (string) $normalized) {
                    // Persist the normalized numeric identifier to the post meta table.
                    update_post_meta($invoice_id, 'invoice_type', $normalized);
                    $updated_any = true;
                }
            }

            // Reset global post data after each query iteration.
            wp_reset_postdata();

            // Advance to the next page of invoices.
            $paged++;
        } while ($paged <= $query->max_num_pages);

        // Indicate whether at least one invoice was updated.
        return $updated_any;
    }
}

if (!function_exists('wpestate_maybe_run_invoice_type_migration')) {
    /**
     * Trigger the invoice type migration once within the WordPress admin area.
     *
     * @return void
     */
    function wpestate_maybe_run_invoice_type_migration()
    {
        // Ensure the migration executes only in the admin area.
        if (!is_admin()) {
            return;
        }

        // Skip execution for Ajax requests to avoid disrupting async flows.
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        // Bail early if the migration flag indicates it already ran.
        if (get_option('wpestate_invoice_type_meta_migrated', false)) {
            return;
        }

        // Restrict execution to administrators who can manage options.
        if (!current_user_can('manage_options')) {
            return;
        }

        // Run the migration and persist the result so it does not run again.
        $updated = wpestate_migrate_legacy_invoice_type_meta();
        update_option('wpestate_invoice_type_meta_migrated', $updated ? '1' : 'no-changes');
    }
}

// Hook the migration trigger into the admin init lifecycle.
add_action('admin_init', 'wpestate_maybe_run_invoice_type_migration');

