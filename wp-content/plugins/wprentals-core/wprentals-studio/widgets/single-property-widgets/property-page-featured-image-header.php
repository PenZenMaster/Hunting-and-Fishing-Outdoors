<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Property_Page_Featured_Image_Header extends Widget_Base {

    public function get_name() {
        return 'property_page_featured_image_header';
    }

    public function get_title() {
        return esc_html__('Property Page Featured Image Header', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-image-rollover';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .entry-title, {{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .entry-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .entry-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'title_width',
            [
                'label'      => esc_html__('Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 1153,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .entry-prop' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-0.5 * {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_left',
            [
                'label'      => esc_html__('Left Position', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min' => -2000,
                        'max' => 2000,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .entry-prop' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_bottom',
            [
                'label'      => esc_html__('Bottom Position', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => -500,
                        'max' => 1000,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 25,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .entry-prop' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'location_style_section',
            [
                'label' => esc_html__('Location', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_location, {{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_location a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'location_typography',
                'selector' => '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_location',
            ]
        );

        $this->add_responsive_control(
            'location_margin',
            [
                'label'      => esc_html__('Margin', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_location' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'location_width',
            [
                'label'      => esc_html__('Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 1170,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_location' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-0.5 * {{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'location_left',
            [
                'label'      => esc_html__('Left Position', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min' => -2000,
                        'max' => 2000,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_location' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'location_bottom',
            [
                'label'      => esc_html__('Bottom Position', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => -500,
                        'max' => 1000,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_location' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'price_style_section',
            [
                'label' => esc_html__('Price', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_price',
            ]
        );

$this->add_responsive_control(
    'price_position_bottom',
    [
        'label' => esc_html__('Bottom Position', 'wprentals-core'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em'],
        'range' => [
            'px' => ['min' => 0, 'max' => 500],
        ],
        'selectors' => [
            '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_price' => 'bottom: {{SIZE}}{{UNIT}};',
        ],
    ]
);

$this->add_responsive_control(
    'price_position_right',
    [
        'label' => esc_html__('Left Position', 'wprentals-core'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em'],
        'range' => [
            'px' => ['min' => 0, 'max' => 1200],
        ],
        'selectors' => [
            '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .listing_main_image_price' => 'left: {{SIZE}}{{UNIT}};',
        ],
    ]
);
        $this->end_controls_section();

        $this->start_controls_section(
            'stars_style_section',
            [
                'label' => esc_html__('Stars', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stars_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image .property_ratings .property-rating i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'main_image_style_section',
            [
                'label' => esc_html__('Main Image Wrapper', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

   

        $this->add_responsive_control(
            'main_image_width',
            [
                'label'      => esc_html__('Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw'],
                'range'      => [
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'main_image_height',
            [
                'label'      => esc_html__('Height', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 515,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'main_image_background_size',
            [
                'label'   => esc_html__('Background Size', 'wprentals-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'cover'   => esc_html__('Cover', 'wprentals-core'),
                    'contain' => esc_html__('Contain', 'wprentals-core'),
                    'auto'    => esc_html__('Auto', 'wprentals-core'),
                ],
                'default'   => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'main_image_background_position',
            [
                'label'   => esc_html__('Background Position', 'wprentals-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    '50% 50%'        => esc_html__('Center Center', 'wprentals-core'),
                    '50% 0%'         => esc_html__('Center Top', 'wprentals-core'),
                    '50% 100%'       => esc_html__('Center Bottom', 'wprentals-core'),
                    '0% 50%'         => esc_html__('Left Center', 'wprentals-core'),
                    '100% 50%'       => esc_html__('Right Center', 'wprentals-core'),
                    '0% 0%'          => esc_html__('Left Top', 'wprentals-core'),
                    '100% 0%'        => esc_html__('Right Top', 'wprentals-core'),
                    '0% 100%'        => esc_html__('Left Bottom', 'wprentals-core'),
                    '100% 100%'      => esc_html__('Right Bottom', 'wprentals-core'),
                ],
                'default'   => '50% 50%',
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image' => 'background-position: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'main_image_background_repeat',
            [
                'label'   => esc_html__('Background Repeat', 'wprentals-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'no-repeat' => esc_html__('No Repeat', 'wprentals-core'),
                    'repeat'    => esc_html__('Repeat', 'wprentals-core'),
                    'repeat-x'  => esc_html__('Repeat X', 'wprentals-core'),
                    'repeat-y'  => esc_html__('Repeat Y', 'wprentals-core'),
                ],
                'default'   => 'no-repeat',
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image' => 'background-repeat: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'main_image_overflow',
            [
                'label'   => esc_html__('Overflow', 'wprentals-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'hidden'  => esc_html__('Hidden', 'wprentals-core'),
                    'visible' => esc_html__('Visible', 'wprentals-core'),
                    'auto'    => esc_html__('Auto', 'wprentals-core'),
                    'scroll'  => esc_html__('Scroll', 'wprentals-core'),
                ],
                'default'   => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image' => 'overflow: {{VALUE}};',
                ],
            ]
        );

  $this->add_control(
    'main_image_opacity',
    [
        'label'      => esc_html__('Opacity', 'wprentals-core'),
        'type'       => Controls_Manager::SLIDER,
        'range'      => [
            'px' => [
                'min'  => 0,
                'max'  => 1,
                'step' => 0.01,
            ],
        ],
        'default'    => [
            'size' => 1,
        ],
        'selectors'  => [
            '{{WRAPPER}} .panel-wrapper.imagebody_wrapper .listing_main_image' => 'opacity: {{SIZE}};',
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
                    include(locate_template('templates/listingslider.php'));
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
