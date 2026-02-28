<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Add_To_Favorites extends Widget_Base {

    public function get_name() {
        return 'property_page_add_to_favorites';
    }

    public function get_title() {
        return esc_html__('Property Page Add to Favorites', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-favorite';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'favorite_display_type',
            [
                'label' => esc_html__('Display Type', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default_icon' => esc_html__('Default Icon', 'wprentals-core'),
                    'elementor_icon' => esc_html__('Elementor Icon', 'wprentals-core'),
                    'text'         => esc_html__('Text', 'wprentals-core'),
                ],
                'default' => 'default_icon',
            ]
        );

        $this->add_control(
            'elementor_icon',
            [
                'label' => esc_html__('Choose Icon', 'wprentals-core'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-heart',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'favorite_display_type' => 'elementor_icon',
                ],
            ]
        );

        $this->add_control(
            'favorite_text',
            [
                'label' => esc_html__('Custom Text', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Add to favorites', 'wprentals-core'),
                'placeholder' => esc_html__('Add to favorites', 'wprentals-core'),
                'condition' => [
                    'favorite_display_type' => 'text',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon / Text', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

    $this->add_group_control(
Group_Control_Typography::get_type(),
[
'name' => 'favorite_typography',
'selector' => '{{WRAPPER}} .property_unit_action_elementor .icon-fav',
'fields_options' => [
'font_size' => [
'selectors' => [
'{{WRAPPER}} .property_unit_action_elementor .icon-fav' => 'font-size: {{SIZE}}{{UNIT}} !important;',
],
],
],
]
);

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 10, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor .icon-fav svg' => 'width: {{SIZE}}{{UNIT}} !important; height: {{SIZE}}{{UNIT}} !important;',
                    '{{WRAPPER}} .property_unit_action_elementor .icon-fav i' => 'font-size: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_icon_colors');

        $this->start_controls_tab(
            'tab_icon_normal',
            ['label' => esc_html__('Normal', 'wprentals-core')]
        );

        $this->add_control(
            'icon_color_off',
            [
                'label' => esc_html__('Color (Off)', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor .icon-fav-off svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .property_unit_action_elementor .icon-fav-off i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_on',
            [
                'label' => esc_html__('Color (On)', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor .icon-fav-on svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .property_unit_action_elementor .icon-fav-on i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            ['label' => esc_html__('Hover', 'wprentals-core')]
        );

        $this->add_control(
            'icon_hover_color_off',
            [
                'label' => esc_html__('Hover Color (Off)', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor:hover .icon-fav-off svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .property_unit_action_elementor:hover .icon-fav-off i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color_on',
            [
                'label' => esc_html__('Hover Color (On)', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor:hover .icon-fav-on svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .property_unit_action_elementor:hover .icon-fav-on i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__('Container', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .property_unit_action_elementor',
            ]
        );

        $this->add_control(
            'container_hover_background',
            [
                'label' => esc_html__('Hover Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .property_unit_action_elementor',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .property_unit_action_elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $attributes = [ 'is_elementor' => 1 ];
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

        $property_id = function_exists('wpestate_return_property_id_elementor_builder')
            ? wpestate_return_property_id_elementor_builder($attributes)
            : get_the_ID();

        if (empty($property_id)) {
            return;
        }

        $current_user = wp_get_current_user();
        $favorite_class = 'icon-fav-off';
        $fav_mes = esc_html__('add to favorites', 'wprentals-core');

        if ($current_user instanceof \WP_User && $current_user->ID) {
            $user_option = 'favorites' . $current_user->ID;
            $favorites = get_option($user_option);

            if (is_array($favorites) && in_array((int) $property_id, array_map('intval', $favorites), true)) {
                $favorite_class = 'icon-fav-on';
                $fav_mes = esc_html__('remove from favorites', 'wprentals-core');
            }
        }

        $display_type = $settings['favorite_display_type'] ?? 'default_icon';
        $content = '';

        if ('text' === $display_type) {
            $text_value = $settings['favorite_text'] ?? '';
            $text_value = trim($text_value) !== '' ? $text_value : $fav_mes;
            $content = esc_html($text_value);
        } elseif ('elementor_icon' === $display_type) {
            $content = $this->get_elementor_icon_markup($settings) ?: $this->get_default_icon_markup();
        } else {
            $content = $this->get_default_icon_markup();
        }

        $this->add_render_attribute('favorites_container', 'class', 'property_unit_action_elementor');
        $this->add_render_attribute('favorite_trigger', [
            'class' => 'icon-fav ' . $favorite_class,
            'data-original-title' => $fav_mes,
            'data-postid' => (int) $property_id,
        ]);

        if ('text' !== $display_type) {
            $this->add_render_attribute('favorite_trigger', 'style', 'max-width:100px;display:inline-block;');
        }

        echo '<div ' . $this->get_render_attribute_string('favorites_container') . '>';
        echo '<span ' . $this->get_render_attribute_string('favorite_trigger') . '>';
        echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</span>';
        echo '</div>';
    }

    private function get_default_icon_markup() {
        if (function_exists('wpestate_return_svg_icon')) {
            $icon = wpestate_return_svg_icon('heart.svg');
            if (!empty($icon)) {
                return $icon;
            }
        }
        return '<i class="fas fa-heart" aria-hidden="true"></i>';
    }

    private function get_elementor_icon_markup($settings) {
        if (empty($settings['elementor_icon']) || empty($settings['elementor_icon']['value'])) {
            return '';
        }
        ob_start();
        Icons_Manager::render_icon($settings['elementor_icon'], ['aria-hidden' => 'true']);
        return ob_get_clean() ?: '';
    }
}