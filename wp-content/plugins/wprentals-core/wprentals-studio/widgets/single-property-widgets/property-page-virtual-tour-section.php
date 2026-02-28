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

class Wprentals_Property_Page_Virtual_Tour_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_virtual_tour_section';
    }

    public function get_title() {
        return esc_html__('Property Page Virtual Tour Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-slides';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $default_title_text = esc_html__( 'Virtual Tour', 'wprentals-core' );

        if (\function_exists('icl_translate')) {
            $default_title_text = \icl_translate(
                'wprentals',
                'wp_estate_virtual_tour_text',
                esc_html__( 'Virtual Tour', 'wprentals-core' )
            );
        }

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'virtual_tour_title_text',
            [
                'label'       => esc_html__( 'Title Text', 'wprentals-core' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => $default_title_text,
                'placeholder' => esc_html__( 'Enter title', 'wprentals-core' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'show_panel_title_arrow',
            [
                'label'        => esc_html__( 'Show Panel Title Arrow', 'wprentals-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'wprentals-core' ),
                'label_off'    => esc_html__( 'Hide', 'wprentals-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

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
                    '{{WRAPPER}} .virtual_tour_wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .virtual_tour_wrapper',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .virtual_tour_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .virtual_tour_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .virtual_tour_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .virtual_tour_wrapper',
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

        $virtual_tour_markup = '';

        if (function_exists('wpestate_show_virtual_tour')) {
            ob_start();
            wpestate_show_virtual_tour($post_id);
            $virtual_tour_markup = (string) ob_get_clean();
        }

        $settings = $this->get_settings_for_display();

        if ($virtual_tour_markup !== '') {
            $custom_title = isset($settings['virtual_tour_title_text']) ? $settings['virtual_tour_title_text'] : '';

            if ('' !== trim((string) $custom_title)) {
                $title_value = sanitize_text_field($custom_title);

                $updated_markup = preg_replace_callback(
                    '/(<a[^>]*class="panel-title"[^>]*>\s*(?:<span\s+class="panel-title-arrow"><\/span>\s*)?)(.*?)(<\/a>)/is',
                    function ($matches) use ($title_value) {
                        return $matches[1] . esc_html($title_value) . $matches[3];
                    },
                    $virtual_tour_markup,
                    1
                );

                if (null !== $updated_markup) {
                    $virtual_tour_markup = $updated_markup;
                }
            }

            $show_panel_title_arrow = isset($settings['show_panel_title_arrow']) ? $settings['show_panel_title_arrow'] : 'yes';

            if ('yes' !== $show_panel_title_arrow) {
                $stripped_markup = preg_replace(
                    '/>\s*<span\s+class="panel-title-arrow"><\/span>\s*/',
                    '>',
                    $virtual_tour_markup
                );

                if (null !== $stripped_markup) {
                    $virtual_tour_markup = $stripped_markup;
                }
            }

            echo $virtual_tour_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        Plugin::instance()->db->restore_current_post();
    }
}
