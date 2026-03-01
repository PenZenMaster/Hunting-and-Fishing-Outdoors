<?php
/**
 * Module/Script Name: homepage-widgets-migration.php
 * Path: wp-content/themes/wprentals-child/libs/homepage-widgets-migration.php
 *
 * Description:
 * One-time data migration that injects WPRentals Elementor widgets into the
 * empty column placeholders on the home page (post ID 1745). The page was
 * imported from the WPRentals "Rent a Yacht" demo with section headings in
 * place but no dynamic content widgets. This script fills the six empty
 * sections identified by their Elementor element IDs:
 *
 *   075c8e2 - Book a Trip Today (1 col: 82a8c0b)
 *   279c542 - Packages          (1 col: 2ba384f)
 *   7f6fc94 - Featured Guides   (3 cols: 94b7465, d103b32, bb0045b)
 *   8fbba8e - Properties        (3 cols: 3eabba2, 207cd82, 6f33c15)
 *   661ef7b - Reviews           (1 col: 2062ab7) - top-level section
 *   235e3b8 - Blog/Camp Fire    (3 cols: 83f2802, 92ce9c0, f417271)
 *
 * Triggering:
 *   Visit https://hnfo-development.local/?run_homepage_migration=1
 *   (requires administrator login)
 *
 * Idempotency:
 *   The script skips columns that already have widgets, and a wp_options guard
 *   prevents double-runs.
 *
 * Author(s):
 * Rank Rocket Co (C) Copyright 2026 - All Rights Reserved
 *
 * Created Date: 2026-02-28
 * Last Modified Date: 2026-03-01
 *
 * Comments:
 * v1.00 - Initial implementation. Resolves GitHub issue: empty home page
 *         sections (Book a Trip Today, Packages, Featured Guides, Properties,
 *         Reviews, Blog/Camp Fire Chat).
 * v1.01 - Rewrote section identification to use exact Elementor element IDs
 *         rather than heading text, after discovering sections are nested.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the one-time migration trigger on init.
 *
 * @return void
 */
function hnfo_homepage_migration_trigger() {
	if ( ! isset( $_GET['run_homepage_migration'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Forbidden.', 'wprentals' ), 403 );
	}

	$result = hnfo_run_homepage_widgets_migration();
	echo '<pre>';
	echo esc_html( $result['message'] ) . "\n\n";
	foreach ( $result['log'] as $line ) {
		echo esc_html( $line ) . "\n";
	}
	echo '</pre>';
	exit;
}

/**
 * Target section IDs and the widget type/settings to inject into each column.
 * Column assignments are listed in order (col-index 0, 1, 2).
 *
 * @return array
 */
function hnfo_migration_targets() {
	return array(

		'075c8e2' => array(
			'label'   => 'Book a Trip Today',
			'columns' => array(
				0 => array(
					'widgetType' => 'Wprentals_Recent_Items_v1',
					'widget_id'  => 'trip001',
					'settings'   => array(
						'number'      => '6',
						'rownumber'   => '3',
						'random_pick' => 'yes',
					),
				),
			),
		),

		'279c542' => array(
			'label'   => 'Packages',
			'columns' => array(
				0 => array(
					'widgetType' => 'Wprentals_Recent_Items_v1',
					'widget_id'  => 'pkgs001',
					'settings'   => array(
						'number'      => '6',
						'rownumber'   => '3',
						'random_pick' => 'no',
					),
				),
			),
		),

		'7f6fc94' => array(
			'label'   => 'Featured Guides',
			'columns' => array(
				0 => array(
					'widgetType' => 'Wprentals_Featured_Owner',
					'widget_id'  => 'gd00001',
					'settings'   => array(
						'owner_id'    => '1',
						'design_type' => '1',
					),
				),
				1 => array(
					'widgetType' => 'Wprentals_Featured_Owner',
					'widget_id'  => 'gd00002',
					'settings'   => array(
						'owner_id'    => '69',
						'design_type' => '1',
					),
				),
				2 => array(
					'widgetType' => 'Wprentals_Featured_Owner',
					'widget_id'  => 'gd00003',
					'settings'   => array(
						'owner_id'    => '1',
						'design_type' => '1',
					),
				),
			),
		),

		'8fbba8e' => array(
			'label'   => 'Properties',
			'columns' => array(
				0 => array(
					'widgetType' => 'Wprentals_Featured_Listing',
					'widget_id'  => 'pr00001',
					'settings'   => array(
						'listing_id' => '1926',
						'type'       => 'type1',
					),
				),
				1 => array(
					'widgetType' => 'Wprentals_Featured_Listing',
					'widget_id'  => 'pr00002',
					'settings'   => array(
						'listing_id' => '1994',
						'type'       => 'type1',
					),
				),
				2 => array(
					'widgetType' => 'Wprentals_Featured_Listing',
					'widget_id'  => 'pr00003',
					'settings'   => array(
						'listing_id' => '2892',
						'type'       => 'type1',
					),
				),
			),
		),

		'661ef7b' => array(
			'label'   => 'Reviews',
			'columns' => array(
				0 => array(
					'widgetType' => 'WpRentals_Testimonial_Slider',
					'widget_id'  => 'rev0001',
					'settings'   => array(
						'list' => array(
							array(
								'testimonial_title' => 'Amazing Experience',
								'testimonial_name'  => 'John D.',
								'testimonial_job'   => 'Fly Fishing Guest',
								'testimonial_stars' => '5',
								'testimonial_text'  => '<p>Had an incredible time on our fishing trip. The guide was knowledgeable and the scenery was breathtaking. Will definitely book again!</p>',
								'testimonial_image' => array(
									'url' => '',
									'id'  => '',
								),
							),
							array(
								'testimonial_title' => 'Best Hunting Trip Ever',
								'testimonial_name'  => 'Sarah M.',
								'testimonial_job'   => 'Hunting Guest',
								'testimonial_stars' => '5',
								'testimonial_text'  => '<p>Booked a whitetail hunt through the platform. Everything was well-organized and the accommodations were top notch. Highly recommend!</p>',
								'testimonial_image' => array(
									'url' => '',
									'id'  => '',
								),
							),
						),
					),
				),
			),
		),

		'235e3b8' => array(
			'label'   => 'Blog / Camp Fire Chat',
			'columns' => array(
				0 => array(
					'widgetType' => 'Wprentals_Featured_Article',
					'widget_id'  => 'bl00001',
					'settings'   => array(
						'article_id' => '190',
						'type'       => 'type1',
					),
				),
				1 => array(
					'widgetType' => 'Wprentals_Featured_Article',
					'widget_id'  => 'bl00002',
					'settings'   => array(
						'article_id' => '192',
						'type'       => 'type1',
					),
				),
				2 => array(
					'widgetType' => 'Wprentals_Featured_Article',
					'widget_id'  => 'bl00003',
					'settings'   => array(
						'article_id' => '200',
						'type'       => 'type1',
					),
				),
			),
		),
	);
}

/**
 * Perform the migration.
 *
 * @return array{message: string, log: string[]}
 */
function hnfo_run_homepage_widgets_migration() {
	$page_id = 1745;
	$log     = array();

	$already_run = get_option( 'hnfo_homepage_migration_v1' );
	if ( $already_run ) {
		return array(
			'message' => 'Migration already applied (hnfo_homepage_migration_v1 = true). Delete the option to re-run.',
			'log'     => array(),
		);
	}

	$raw_json = get_post_meta( $page_id, '_elementor_data', true );
	if ( empty( $raw_json ) ) {
		return array(
			'message' => "ERROR: _elementor_data empty for post $page_id.",
			'log'     => array(),
		);
	}

	$data = json_decode( $raw_json, true );
	if ( ! is_array( $data ) ) {
		return array(
			'message' => 'ERROR: json_decode failed - ' . json_last_error_msg(),
			'log'     => array(),
		);
	}

	$log[] = 'Loaded ' . count( $data ) . ' top-level sections.';

	$targets  = hnfo_migration_targets();
	$modified = false;

	hnfo_inject_widgets( $data, $targets, $log, $modified );

	// Report any targets not found.
	foreach ( $targets as $sid => $cfg ) {
		$log[] = "WARNING: Section '$sid' ({$cfg['label']}) not found in the Elementor data.";
	}

	if ( ! $modified ) {
		return array(
			'message' => 'No changes made. All target columns may already have widgets, or sections were not found.',
			'log'     => $log,
		);
	}

	$new_json = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( false === $new_json ) {
		return array(
			'message' => 'ERROR: json_encode failed.',
			'log'     => $log,
		);
	}

	update_post_meta( $page_id, '_elementor_data', wp_slash( $new_json ) );
	$log[] = 'Saved updated _elementor_data.';

	delete_post_meta( $page_id, '_elementor_css' );
	delete_post_meta( $page_id, '_elementor_element_cache_data' );
	if ( class_exists( '\Elementor\Plugin' ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
		$log[] = 'Elementor file cache cleared.';
	}
	delete_transient( 'elementor_cache' );

	update_option( 'hnfo_homepage_migration_v1', true );

	return array(
		'message' => 'Migration complete. Visit the home page to verify the sections.',
		'log'     => $log,
	);
}

/**
 * Recursively walk the Elementor elements tree. When a section whose ID is
 * in $targets is found, inject widgets into its empty columns.
 *
 * @param array  $elements Elements array (modified in place).
 * @param array  $targets  Remaining target sections to find (modified: matched entries removed).
 * @param array  $log      Log lines (modified in place).
 * @param bool   $modified Set to true when any change is made (modified in place).
 * @return void
 */
function hnfo_inject_widgets( array &$elements, array &$targets, array &$log, bool &$modified ) {
	foreach ( $elements as &$element ) {
		$eid  = $element['id'] ?? '';
		$type = $element['elType'] ?? '';

		// Check if this element is a target section.
		if ( 'section' === $type && isset( $targets[ $eid ] ) ) {
			$cfg   = $targets[ $eid ];
			$label = $cfg['label'];
			$log[] = "Found target section '$label' (id=$eid).";

			$col_index = 0;
			foreach ( $element['elements'] as &$column ) {
				if ( ! empty( $column['elements'] ) ) {
					$log[] = "  col[$col_index] id={$column['id']}: already has widgets, skipped.";
					$col_index++;
					continue;
				}

				if ( ! isset( $cfg['columns'][ $col_index ] ) ) {
					$log[] = "  col[$col_index] id={$column['id']}: no widget defined for this slot, skipped.";
					$col_index++;
					continue;
				}

				$slot    = $cfg['columns'][ $col_index ];
				$widget  = array(
					'id'         => $slot['widget_id'],
					'elType'     => 'widget',
					'settings'   => $slot['settings'],
					'elements'   => array(),
					'widgetType' => $slot['widgetType'],
				);
				$column['elements'][] = $widget;
				$modified              = true;
				$log[] = "  + col[$col_index] id={$column['id']}: inserted '{$slot['widgetType']}'.";
				$col_index++;
			}
			unset( $column );

			// Remove from targets so we can report remaining ones after.
			unset( $targets[ $eid ] );
			continue; // No need to recurse into this section's children.
		}

		// Recurse into nested elements.
		if ( ! empty( $element['elements'] ) ) {
			hnfo_inject_widgets( $element['elements'], $targets, $log, $modified );
		}
	}
	unset( $element );
}
