<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Wprentals_Term_City_Location extends Widget_Base {

    /**
     * Retrieve a taxonomy meta value prioritising native term meta over the legacy option array.
     *
     * @param int    $term_id Term identifier.
     * @param string $key     Meta key.
     *
     * @return string
     */
    private function get_term_meta_value( $term_id, $key ) {
        $value = get_term_meta( $term_id, $key, true );

        if ( '' === $value ) {
            $legacy = get_option( 'taxonomy_' . $term_id );
            if ( isset( $legacy[ $key ] ) && '' !== $legacy[ $key ] ) {
                $value = $legacy[ $key ];
            }
        }

        return $value;
    }

    public function get_name() {
        return 'term_city_location';
    }

    public function get_title() {
        return __('Term City or County/State', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note  eicon-map-pin';
    }

    public function get_categories() {
        return ['category_widgets'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'wprentals-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'city_location_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .wprentals-term-city-location',
            ]
        );

        $this->add_control(
            'city_location_color',
            [
                'label' => __('Color', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-city-location' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

    protected function render() {
        $location = '';
        $term     = get_queried_object();
     
     

        if ( $term instanceof \WP_Term ) {
            if ( 'property_city' === $term->taxonomy ) {
                $location = $this->get_term_meta_value( $term->term_id, 'stateparent' );
            } elseif ( 'property_area' === $term->taxonomy ) {
                $location = $this->get_term_meta_value( $term->term_id, 'cityparent' );
            }
        }

        if (( ! $location && \Elementor\Plugin::$instance->editor->is_edit_mode()) || is_singular( 'wpestate-studio' ) ) {
            $latest_terms = get_terms([
                'taxonomy'   => 'property_city',
                'hide_empty' => false,
                'number'     => 1,
                'orderby'    => 'term_id',
                'order'      => 'DESC',
            ]);

            if ( ! empty( $latest_terms ) && ! is_wp_error( $latest_terms ) ) {
                $term = $latest_terms[0];

                if ( $term instanceof \WP_Term ) {
                    if ( 'property_city' === $term->taxonomy ) {
                        $location = $this->get_term_meta_value( $term->term_id, 'stateparent' );
                    } elseif ( 'property_area' === $term->taxonomy ) {
                        $location = $this->get_term_meta_value( $term->term_id, 'cityparent' );
                    }
                }
            }
        }

        if ($location) {
            echo '<div class="wprentals-term-city-location">' . esc_html($location) . '</div>';
        } else {
            echo '<div class="wprentals-term-city-location">' . esc_html__( 'This term does not have a city location.', 'wprentals-core' ) . '</div>';
        }
    }
}