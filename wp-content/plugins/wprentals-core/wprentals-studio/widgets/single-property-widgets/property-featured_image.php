<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Featured_Image extends Widget_Base {

    public function get_name() {
        return 'property_featured_image';
    }

    public function get_title() {
        return esc_html__('WpRentals Property Featured Image', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-featured-image';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'wprentals-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'full',
            ]
        );

        $this->add_control(
            'use_background_image',
            [
                'label' => esc_html__('Use Background Image', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wprentals-core'),
                'label_off' => esc_html__('No', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => esc_html__('No', 'wprentals-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );



        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__('Height', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 400,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 1500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};width:auto;',
                    '{{WRAPPER}} .wprentals-property-featured-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'object-fit',
            [
                'label' => esc_html__('Object Fit', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'use_background_image' => 'yes',
                    'height[size]!' => '',
                ],
                'options' => [
                    '' => esc_html__('Default', 'wprentals-core'),
                    'cover' => esc_html__('Cover', 'wprentals-core'),
                    'contain' => esc_html__('Contain', 'wprentals-core'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .wprentals-property-featured-image' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'object-position',
            [
                'label' => esc_html__('Object Position', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => [
                    'use_background_image' => 'yes',
                ],
                'options' => [
                    'center center' => esc_html__('Center Center', 'wprentals-core'),
                    'center left' => esc_html__('Center Left', 'wprentals-core'),
                    'center right' => esc_html__('Center Right', 'wprentals-core'),
                    'top center' => esc_html__('Top Center', 'wprentals-core'),
                    'top left' => esc_html__('Top Left', 'wprentals-core'),
                    'top right' => esc_html__('Top Right', 'wprentals-core'),
                    'bottom center' => esc_html__('Bottom Center', 'wprentals-core'),
                    'bottom left' => esc_html__('Bottom Left', 'wprentals-core'),
                    'bottom right' => esc_html__('Bottom Right', 'wprentals-core'),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .wprentals-property-featured-image' => 'background-position: {{VALUE}};',
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

            $featured_image = get_the_post_thumbnail($property_id, $settings['thumbnail_size'], [
                'class' => 'wprentals-property-featured-image',
            ]);

            if ($settings['use_background_image'] === 'yes') {
                $background_styles = [];

                $thumbnail_url = get_the_post_thumbnail_url($property_id, $settings['thumbnail_size']);

                if (!empty($thumbnail_url)) {
                    $background_styles[] = 'background-image: url(' . esc_url($thumbnail_url) . ')';
                }

                $style_attribute = '';

                if (!empty($background_styles)) {
                    $style_attribute = ' style="' . esc_attr(implode('; ', $background_styles) . ';') . '"';
                }

                echo '<div class="wprentals-property-featured-image"' . $style_attribute . '></div>';
            } else {
                echo '<div class="wprentals-property-featured-image">' . $featured_image . '</div>';
            }
        }
    }
}
