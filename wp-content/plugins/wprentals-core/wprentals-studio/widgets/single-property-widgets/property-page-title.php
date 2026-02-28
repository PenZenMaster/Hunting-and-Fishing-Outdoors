<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Title extends Widget_Base {

    public function get_name() {
        return 'property_page_title';
    }

    public function get_title() {
        return esc_html__('WpRentals Property Title', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-product-title';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Property Title', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label'   => esc_html__('HTML Tag', 'wprentals-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => [
                        'title' => esc_html__('H1', 'wprentals-core'),
                        'icon'  => 'eicon-editor-h1',
                    ],
                    'h2' => [
                        'title' => esc_html__('H2', 'wprentals-core'),
                        'icon'  => 'eicon-editor-h2',
                    ],
                    'h3' => [
                        'title' => esc_html__('H3', 'wprentals-core'),
                        'icon'  => 'eicon-editor-h3',
                    ],
                    'h4' => [
                        'title' => esc_html__('H4', 'wprentals-core'),
                        'icon'  => 'eicon-editor-h4',
                    ],
                    'h5' => [
                        'title' => esc_html__('H5', 'wprentals-core'),
                        'icon'  => 'eicon-editor-h5',
                    ],
                    'h6' => [
                        'title' => esc_html__('H6', 'wprentals-core'),
                        'icon'  => 'eicon-editor-h6',
                    ],
                    'span' => [
                        'title' => esc_html__('span', 'wprentals-core'),
                        'icon'  => 'eicon-editor-span',
                    ],
                    'div' => [
                        'title' => esc_html__('div', 'wprentals-core'),
                        'icon'  => 'eicon-editor-div',
                    ],
                    'p' => [
                        'title' => esc_html__('p', 'wprentals-core'),
                        'icon'  => 'eicon-editor-p',
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label'     => esc_html__('Alignment', 'wprentals-core'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left' => [
                        'title' => esc_html__('Left', 'wprentals-core'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wprentals-core'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wprentals-core'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'wprentals-core'),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wprentals-single-property-title' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .wprentals-single-property-title a' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wprentals-single-property-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wprentals-single-property-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'wprentals_tab_item_typography',
                'selector' => '{{WRAPPER}} .wprentals-single-property-title',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'permalink',
            [
                'label'        => esc_html__('Permalink', 'wprentals-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'wprentals-core'),
                'label_off'    => esc_html__('No', 'wprentals-core'),
                'return_value' => 'yes',
                'default'      => esc_html__('No', 'wprentals-core'),
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
            $settings  = $this->get_settings_for_display();
            $tag       = $settings['html_tag'];
            $permalink = $settings['permalink'];

            $content = esc_html(get_the_title($property_id));

            if ($permalink === 'yes') {
                $content = '<a href="' . esc_url(get_permalink($property_id)) . '">' . $content . '</a>';
            }

            echo '<' . $tag . ' class="wprentals-single-property-title">' . $content . '</' . $tag . '>';
        }
    }
}
