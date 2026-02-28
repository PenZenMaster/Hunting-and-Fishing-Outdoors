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

class Wprentals_Property_Page_Breadcrumbs extends Widget_Base {

    public function get_name() {
        return 'property_page_breadcrumbs';
    }

    public function get_title() {
        return esc_html__('WpRentals Property Breadcrumbs', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-navigation-horizontal';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Breadcrumbs', 'wprentals-core'),
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

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb li' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .breadcrumb a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .breadcrumb > li + li:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'wprentals_tab_item_typography',
                'selector' => '{{WRAPPER}} .breadcrumb a, {{WRAPPER}} .breadcrumb li',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
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
            echo wprentals_render_breadcrumbs($property_id);
        }
    }
}
