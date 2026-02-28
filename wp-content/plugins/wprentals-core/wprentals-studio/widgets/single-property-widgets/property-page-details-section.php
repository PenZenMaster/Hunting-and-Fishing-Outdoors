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

class Wprentals_Property_Page_Details_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_details_section';
    }

    public function get_title() {
        return esc_html__('Property Page Details Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-kit-details';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $default_title_text = (string) \wprentals_get_option('wp_estate_property_details_text');

        if (\function_exists('icl_translate')) {
            $default_title_text = \icl_translate(
                'wprentals',
                'wp_estate_property_details_text',
                (string) \wprentals_get_option('wp_estate_property_details_text')
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
            'details_title_text',
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
                    '{{WRAPPER}} .panel-wrapper #listing_details' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .panel-wrapper #listing_details',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper #listing_details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .panel-wrapper .panel-body.panel-body-border' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .panel-wrapper .panel-body.panel-body-border .item_head' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .panel-wrapper .panel-body.panel-body-border, {{WRAPPER}} .panel-wrapper .panel-body.panel-body-border .item_head',
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
                    '{{WRAPPER}} .panel-wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .panel-wrapper',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .panel-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .panel-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .panel-wrapper',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $custom_title = isset($settings['details_title_text']) ? $settings['details_title_text'] : '';
        $postID = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular( 'wpestate-studio' ) ||
            is_preview()) {

            $postID = wpestate_last_property_id();
        }

        if ($postID) {
            Plugin::instance()->db->switch_to_post( $postID );

            $title_value = '';

            if ('' !== trim($custom_title)) {
                $title_value = sanitize_text_field($custom_title);
            }

            $details_markup = wpestate_property_details_wrapper($postID, $title_value);
            $show_panel_title_arrow = isset($settings['show_panel_title_arrow']) ? $settings['show_panel_title_arrow'] : 'yes';

            if ('yes' !== $show_panel_title_arrow) {
                $stripped_markup = preg_replace(
                    '/>\s*<span\s+class="panel-title-arrow"><\/span>\s*/',
                    '>',
                    $details_markup
                );

                if (null !== $stripped_markup) {
                    $details_markup = $stripped_markup;
                }
            }

            echo $details_markup;

            Plugin::instance()->db->restore_current_post();
        }
    }
}
