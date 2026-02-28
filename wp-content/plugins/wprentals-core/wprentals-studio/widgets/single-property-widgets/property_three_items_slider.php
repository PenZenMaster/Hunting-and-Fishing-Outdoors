<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Three_Items_Slider extends Widget_Base {

    public function get_name() {
        return 'property_three_items_slider';
    }

    public function get_title() {
        return esc_html__('Property Multi Image Slider', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-slider-album';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'slider_settings_section',
            [
                'label' => esc_html__('Slider Settings', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_items_to_show',
            [
                'label'       => esc_html__('Number of Items', 'wprentals-core'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 1,
                'max'         => 10,
                'step'        => 1,
                'default'     => 3,
                'description' => esc_html__('Controls how many items are visible in the slider at once.', 'wprentals-core'),
            ]
        );

        $this->end_controls_section();

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
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_arrow_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0.5,
                        'max' => 6,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 6,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:before' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_arrow_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_arrow_border_width',
            [
                'label'      => esc_html__('Border Width', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
                ],
            ]
        );

        $this->add_control(
            'navigation_arrow_border_color',
            [
                'label'     => esc_html__('Border Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:before' => 'border-color: {{VALUE}};',
                ],
                'separator' => 'before',
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
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_arrow_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:before' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:hover:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:focus:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:hover:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:focus:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'navigation_arrow_hover_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:hover:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-prev.slick-arrow:focus:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:hover:before, {{WRAPPER}} #listing_main_image_photo_slider .slick-next.slick-arrow:focus:before' => 'background-color: {{VALUE}};',
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

            $slider_items = isset($settings['slider_items_to_show']) ? max(1, absint($settings['slider_items_to_show'])) : 3;

            global $post;

            $original_post = $post ?? null;
            $property_post = get_post($property_id);

            if ($property_post instanceof \WP_Post) {
                $post = $property_post;
                setup_postdata($post);
            }

            ?>

            <?php
            include locate_template('templates/listingslider_for_type3.php');
            ?>

            <script type="text/javascript">
                (function($) {
                    var initSlider = function($scope) {
                        var $context = $scope && $scope.length ? $scope : $(document);
                        var $slider = $context.find('#listing_main_image_photo_slider');

                        if (!$slider.length) {
                            $slider = $('#listing_main_image_photo_slider');
                        }

                        if ($slider.length && typeof wprentals_three_items_slick_slider === 'function') {
                            wprentals_three_items_slick_slider(<?php print (int) $slider_items; ?>);
                        }
                    };

                    $(document).ready(function() {
                        initSlider($(document));
                    });

                    if (window.elementorFrontend && window.elementorFrontend.hooks && window.elementorFrontend.hooks.addAction) {
                        window.elementorFrontend.hooks.addAction('frontend/element_ready/property_three_items_slider.default', function($scope) {
                            initSlider($scope);
                        });
                    }
                })(jQuery);
            </script>
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
