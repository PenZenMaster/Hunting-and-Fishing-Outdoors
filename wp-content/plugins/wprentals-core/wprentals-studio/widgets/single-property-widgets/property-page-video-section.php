<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Video_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_video_section';
    }

    public function get_title() {
        return esc_html__('Property Page Video Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-slider-video';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'wrapper_style_section',
            [
                'label' => esc_html__( 'Wrapper', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wrapper_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-body.video-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .panel-body.video-body',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-body.video-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label'      => esc_html__( 'Padding', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-body.video-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wrapper_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-body.video-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .panel-body.video-body',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $post_id = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()) {
            $post_id = wpestate_last_property_id();
        }

        if (!$post_id) {
            return;
        }

        Plugin::instance()->db->switch_to_post($post_id);

        $video_id   = esc_html(get_post_meta($post_id, 'embed_video_id', true));
        $video_type = esc_html(get_post_meta($post_id, 'embed_video_type', true));

        if ($video_id !== '') {
            echo '<div class="panel-body video-body">';

            if ($video_type === 'vimeo' && function_exists('wpestate_custom_vimdeo_video')) {
                echo wpestate_custom_vimdeo_video($video_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } elseif (function_exists('wpestate_custom_youtube_video')) {
                echo wpestate_custom_youtube_video($video_id); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            echo '</div>';
        } elseif (Plugin::instance()->editor->is_edit_mode() || Plugin::instance()->preview->is_preview_mode()) {
            echo '<div class="panel-body video-body">' . esc_html__('No property video is available.', 'wprentals-core') . '</div>';
        }

        Plugin::instance()->db->restore_current_post();
    }
}
