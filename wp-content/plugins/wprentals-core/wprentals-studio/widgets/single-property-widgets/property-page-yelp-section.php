<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Yelp_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_yelp_section';
    }

    public function get_title() {
        return esc_html__('Property Page Yelp Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-info-box';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $default_title_text = (string) esc_html__("What's Nearby", 'wprentals-core');

        if (\function_exists('icl_translate')) {
            $default_title_text = \icl_translate(
                'wprentals',
                'wp_estate_property_yelp_title',
                (string) esc_html__("What's Nearby", 'wprentals-core')
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
            'yelp_title_text',
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
            'title_style_section',
            [
                'label' => esc_html__( 'Title', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .yelp_wrapper .panel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .yelp_wrapper .panel-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .yelp_wrapper .panel-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__( 'Content', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .yelp_wrapper .panel-body.panel-body-border' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .yelp_wrapper .yelp_category' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .yelp_wrapper .yelp_unit_name' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .yelp_wrapper .yelp_unit_distance' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .yelp_wrapper .panel-body.panel-body-border, {{WRAPPER}} .yelp_wrapper .yelp_category, {{WRAPPER}} .yelp_wrapper .yelp_unit_name, {{WRAPPER}} .yelp_wrapper .yelp_unit_distance',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .yelp_wrapper .yelp_icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .yelp_wrapper .yelp_icon svg' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
                    '{{WRAPPER}} .yelp_wrapper .yelp_icon svg *' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
                ],
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
                    '{{WRAPPER}} .yelp_wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .yelp_wrapper',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .yelp_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .yelp_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .yelp_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .yelp_wrapper',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $custom_title = isset($settings['yelp_title_text']) ? $settings['yelp_title_text'] : '';
        $postID = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()) {

            $postID = wpestate_last_property_id();
        }

        if ($postID) {
            Plugin::instance()->db->switch_to_post($postID);

            $yelp_markup = wpestate_property_yelp_wrapper($postID);
            $show_panel_title_arrow = isset($settings['show_panel_title_arrow']) ? $settings['show_panel_title_arrow'] : 'yes';

            if ('' !== trim($custom_title)) {
                $sanitized_title = esc_html(sanitize_text_field($custom_title));

                $replaced_markup = preg_replace(
                    '/(<a\s+[^>]*id="yelp_details"[^>]*>\s*(?:<span\s+class="panel-title-arrow"><\/span>\s*)?)(.*?)(<\/a>)/is',
                    '$1' . $sanitized_title . '$3',
                    $yelp_markup,
                    1
                );

                if (null !== $replaced_markup) {
                    $yelp_markup = $replaced_markup;
                }
            }

            if ('yes' !== $show_panel_title_arrow) {
                $stripped_markup = preg_replace(
                    '/>\s*<span\s+class="panel-title-arrow"><\/span>\s*/',
                    '>',
                    $yelp_markup
                );

                if (null !== $stripped_markup) {
                    $yelp_markup = $stripped_markup;
                }
            }

            echo $yelp_markup;

            Plugin::instance()->db->restore_current_post();
        }
    }
}
