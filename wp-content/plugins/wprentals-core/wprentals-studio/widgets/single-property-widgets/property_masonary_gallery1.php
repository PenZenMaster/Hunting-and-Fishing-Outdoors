<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Masonary_Gallery1 extends Widget_Base {

    public function get_name() {
        return 'property_masonary_gallery1';
    }

    public function get_title() {
        return esc_html__('Property Masonary Gallery', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-gallery-masonry';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'image_gallery_style_section',
            [
                'label' => esc_html__('Image Gallery', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_gallery_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .image_gallery'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .img_listings_overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_gallery_border_width',
            [
                'label'      => esc_html__('Border Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .image_gallery' => 'border-style: solid; border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_gallery_border_color',
            [
                'label'     => esc_html__('Border Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .image_gallery' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_gallery_overlay_heading',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Overlay', 'wprentals-core'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_gallery_overlay_color',
            [
                'label'     => esc_html__('Overlay Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .img_listings_overlay:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_gallery_overlay_opacity',
            [
                'label'      => esc_html__('Overlay Opacity', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [''],
                'range'      => [
                    '' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.01,
                    ],
                ],
                'default'    => [
                    'size' => 0.5,
                    'unit' => '',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .img_listings_overlay:hover' => 'opacity: {{SIZE}};',
                ],
            ]
        );

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
                    include(locate_template('templates/property_page_templates/property_page_templates_section/masonary_gallery_property_page.php'));
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
