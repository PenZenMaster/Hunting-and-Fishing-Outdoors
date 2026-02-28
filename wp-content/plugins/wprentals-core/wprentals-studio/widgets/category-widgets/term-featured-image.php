<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Wprentals_Term_Featured_Image extends Widget_Base {

    /**
     * Retrieve a taxonomy meta value prioritising term meta over the legacy option storage.
     *
     * @param int    $term_id Term identifier.
     * @param string $key     Meta key to retrieve.
     *
     * @return string
     */
    private function get_term_meta_value( $term_id, $key ) {
        $value = get_term_meta( $term_id, $key, true );

        if ( '' === $value ) {
            $legacy_meta = get_option( 'taxonomy_' . $term_id );
            if ( isset( $legacy_meta[ $key ] ) && '' !== $legacy_meta[ $key ] ) {
                $value = $legacy_meta[ $key ];
            }
        }

        return $value;
    }

    public function get_name() {
        return 'term_featured_image';
    }

    public function get_title() {
        return __('Term Featured Image', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note  eicon-featured-image';
    }

    public function get_categories() {
        return ['category_widgets'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'use_as_background',
            [
                'label' => __( 'Use as Background', 'wprentals-core' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'wprentals-core' ),
                'label_off' => __( 'No', 'wprentals-core' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Image Style', 'wprentals-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'wprentals-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 50, 'max' => 1000 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-featured-image' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wprentals-term-featured-image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .wprentals-term-featured-image, {{WRAPPER}} .wprentals-term-featured-image img',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'wprentals-core' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-featured-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wprentals-term-featured-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bg_position',
            [
                'label' => __( 'Background Position', 'wprentals-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'center center',
                'options' => [
                    'left top' => __( 'Left Top', 'wprentals-core' ),
                    'left center' => __( 'Left Center', 'wprentals-core' ),
                    'left bottom' => __( 'Left Bottom', 'wprentals-core' ),
                    'center top' => __( 'Center Top', 'wprentals-core' ),
                    'center center' => __( 'Center Center', 'wprentals-core' ),
                    'center bottom' => __( 'Center Bottom', 'wprentals-core' ),
                    'right top' => __( 'Right Top', 'wprentals-core' ),
                    'right center' => __( 'Right Center', 'wprentals-core' ),
                    'right bottom' => __( 'Right Bottom', 'wprentals-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-featured-image' => 'background-position: {{VALUE}};',
                ],
                'condition' => [
                    'use_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bg_repeat',
            [
                'label' => __( 'Background Repeat', 'wprentals-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'no-repeat',
                'options' => [
                    'no-repeat' => __( 'No Repeat', 'wprentals-core' ),
                    'repeat' => __( 'Repeat', 'wprentals-core' ),
                    'repeat-x' => __( 'Repeat X', 'wprentals-core' ),
                    'repeat-y' => __( 'Repeat Y', 'wprentals-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-featured-image' => 'background-repeat: {{VALUE}};',
                ],
                'condition' => [
                    'use_as_background' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bg_size',
            [
                'label' => __( 'Background Size', 'wprentals-core' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'auto' => __( 'Auto', 'wprentals-core' ),
                    'cover' => __( 'Cover', 'wprentals-core' ),
                    'contain' => __( 'Contain', 'wprentals-core' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-featured-image' => 'background-size: {{VALUE}};',
                ],
                'condition' => [
                    'use_as_background' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_opacity',
            [
                'label' => __( 'Opacity', 'wprentals-core' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-featured-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings   = $this->get_settings_for_display();
        $use_bg     = isset( $settings['use_as_background'] ) && 'yes' === $settings['use_as_background'];
        $image_html = '';
        $image_src  = '';
        $term       = get_queried_object();

        if ( (! $term instanceof \WP_Term && \Elementor\Plugin::$instance->editor->is_edit_mode()) || is_singular( 'wpestate-studio' ) ) {
            $latest_terms = get_terms([
                'taxonomy'   => 'property_city',
                'hide_empty' => false,
                'number'     => 1,
                'orderby'    => 'term_id',
                'order'      => 'DESC',
            ]);

            if ( ! empty( $latest_terms ) && ! is_wp_error( $latest_terms ) ) {
                $term = $latest_terms[0];
            }
        }

        if ( $term instanceof \WP_Term ) {
            $attach_id = $this->get_term_meta_value( $term->term_id, 'category_attach_id' );
            $image_url = $this->get_term_meta_value( $term->term_id, 'category_featured_image' );

            if ( $attach_id ) {
                $image_html = wp_get_attachment_image( $attach_id, 'full' );
                $image_src  = wp_get_attachment_url( $attach_id );
            } elseif ( $image_url ) {
                $image_html = '<img src="' . esc_url( $image_url ) . '" alt="" />';
                $image_src  = $image_url;
            } else {
                $image_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                if ( ! $image_id ) {
                    $image_id = get_term_meta( $term->term_id, 'category_image', true );
                }
                if ( $image_id ) {
                    $image_html = wp_get_attachment_image( $image_id, 'full' );
                    $image_src  = wp_get_attachment_url( $image_id );
                }
            }
        }

        if ( $image_html ) {
            if ( $use_bg && $image_src ) {
                echo '<div class="wprentals-term-featured-image" style="background-image: url(' . esc_url( $image_src ) . ');"></div>';
            } else {
                echo '<div class="wprentals-term-featured-image">' . $image_html . '</div>';
            }
        } else {
            echo '<div class="wprentals-term-featured-image">' . esc_html__( 'This term does not have a featured image.', 'wprentals-core' ) . '</div>';
        }
    }
}