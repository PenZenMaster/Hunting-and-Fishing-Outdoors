<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Owner_Status extends Widget_Base {

    public function get_name() {
        return 'wprentals_owner_status';
    }

    public function get_title() {
        return esc_html__('WpRentals Owner Status', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note  eicon-check-circle';
    }

    public function get_categories() {
        return ['wprentals_owners'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'status_text_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-status .verified_userid-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'status_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-status' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'status_typography',
                'selector' => '{{WRAPPER}} .wprentals-owner-widget-status .verified_userid-text',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'status_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-status .verified_userid i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'status_icon_size',
            [
                'label'     => esc_html__('Icon Size', 'wprentals-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 8,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-status .verified_userid i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'status_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals-owner-widget-status' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'status_padding',
            [
                'label'      => esc_html__('Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals-owner-widget-status' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'status_border',
                'selector' => '{{WRAPPER}} .wprentals-owner-widget-status',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $owner_id = get_the_ID();

        if (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $owner_id = wpestate_last_agent_id();
        }

        if ($owner_id) {
            $agent_id = get_post_meta($owner_id, 'user_agent_id', true);
            $badge    = $agent_id ? wpestate_display_verification_badge($agent_id) : '';

            if ($badge) {
                $badge = $this->prepare_badge_markup($badge);
                echo '<div class="wprentals-owner-widget wprentals-owner-widget-status">';
                echo wp_kses_post($badge);
                echo '</div>';
            }
        }
    }

    private function prepare_badge_markup($badge) {
        if (strpos($badge, 'verified_userid-text') !== false) {
            return $badge;
        }

        $pattern = '/(<span[^>]*class="[^"]*verified_userid[^"]*"[^>]*>\s*(?:<i[^>]*>.*?<\/i>\s*)?)(.*?)(<\/span>)/is';
        $replacement = '$1<span class="verified_userid-text">$2</span>$3';

        $processed_badge = preg_replace($pattern, $replacement, $badge, 1);

        return $processed_badge ?: $badge;
    }
}
