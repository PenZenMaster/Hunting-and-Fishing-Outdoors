<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Classic_Slider extends Widget_Base {

    public function get_name() {
        return 'property_classic_slider';
    }

    public function get_title() {
        return esc_html__('Property Classic Slider', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-post-slider';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'navigation_style_section',
            [
                'label' => esc_html__('Navigation Arrows', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'navigation_arrow_width',
            [
                'label'      => esc_html__('Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_arrow_height',
            [
                'label'      => esc_html__('Height', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_arrow_padding',
            [
                'label'      => esc_html__('Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('navigation_arrow_colors_tabs');

        $this->start_controls_tab(
            'navigation_arrow_colors_normal_tab',
            [
                'label' => esc_html__('Normal', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'navigation_arrow_color',
            [
                'label'     => esc_html__('Icon Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_arrow_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'navigation_arrow_colors_hover_tab',
            [
                'label' => esc_html__('Hover', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'navigation_arrow_hover_color',
            [
                'label'     => esc_html__('Icon Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control:hover i, {{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control:focus i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_arrow_hover_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control:hover, {{WRAPPER}} .panel-wrapper.imagebody_wrapper .carousel-control:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $property_id = get_the_ID();

        if (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $property_id = wpestate_last_property_id();
        }

        if ($property_id) {
            $settings = $this->get_settings_for_display();

            global $post;

            $original_post = $post ?? null;
            $property_post = get_post($property_id);

            if ($property_post instanceof \WP_Post) {
                $post = $property_post;
                setup_postdata($post);
            }

            ?>
            <div class="panel-wrapper imagebody_wrapper">
                <div class="panel-body imagebody imagebody_new property_pictures_wrapper">
                    <?php
                    include(locate_template('templates/property_page_templates/property_page_templates_section/property_slider_type1.php'));
                    ?>
                </div>
            </div>
            <?php

            if ($property_post instanceof \WP_Post) {
                if ($original_post instanceof \WP_Post) {
                    $post = $original_post;
                    setup_postdata($post);
                } else {
                    wp_reset_postdata();
                }
            }
        }
    }
}
