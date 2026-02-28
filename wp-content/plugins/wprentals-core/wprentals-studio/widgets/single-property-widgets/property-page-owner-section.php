<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

class Wprentals_Property_Page_Owner_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_owner_section';
    }

    public function get_title() {
        return esc_html__('Property Page Owner Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-person';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }
    protected function register_controls() {
        $this->start_controls_section(
            'name_style_section',
            [
                'label' => esc_html__( 'Name', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper h3[itemprop="agent"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .owner-page-wrapper h3[itemprop="agent"]',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper h3[itemprop="agent"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .owner-page-wrapper .owner_area_description' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .owner-page-wrapper .owner_read_more' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .owner-page-wrapper .owner_read_more:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .owner-page-wrapper .owner_area_description, {{WRAPPER}} .owner-page-wrapper .owner_read_more',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .owner-page-wrapper svg' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
                    '{{WRAPPER}} .owner-page-wrapper svg *' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style_section',
            [
                'label' => esc_html__( 'Image', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => esc_html__( 'Width', 'wprentals-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 400 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 120,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .owner_listing_image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => esc_html__( 'Height', 'wprentals-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 400 ],
                    '%'  => [ 'min' => 0, 'max' => 100 ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 120,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .owner_listing_image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 50,
                    'right'  => 50,
                    'bottom' => 50,
                    'left'   => 50,
                    'unit'   => '%',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .owner_listing_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'default'    => [
                    'bottom' => 20,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .owner_listing_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'image_border',
                'selector' => '{{WRAPPER}} .owner-page-wrapper .owner_listing_image',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top'    => 3,
                            'right'  => 3,
                            'bottom' => 3,
                            'left'   => 3,
                        ],
                    ],
                    'color' => [
                        'default' => '#cda7fd',
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_background_size',
            [
                'label'   => esc_html__( 'Background Size', 'wprentals-core' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'auto'    => esc_html__( 'Auto', 'wprentals-core' ),
                    'cover'   => esc_html__( 'Cover', 'wprentals-core' ),
                    'contain' => esc_html__( 'Contain', 'wprentals-core' ),
                ],
                'default'   => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper .owner_listing_image' => 'background-size: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_background_position',
            [
                'label'   => esc_html__( 'Background Position', 'wprentals-core' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'left top'      => esc_html__( 'Top Left', 'wprentals-core' ),
                    'top center'    => esc_html__( 'Top Center', 'wprentals-core' ),
                    'right top'     => esc_html__( 'Top Right', 'wprentals-core' ),
                    'left center'   => esc_html__( 'Center Left', 'wprentals-core' ),
                    'center center' => esc_html__( 'Center', 'wprentals-core' ),
                    'right center'  => esc_html__( 'Center Right', 'wprentals-core' ),
                    'left bottom'   => esc_html__( 'Bottom Left', 'wprentals-core' ),
                    'bottom center' => esc_html__( 'Bottom Center', 'wprentals-core' ),
                    'right bottom'  => esc_html__( 'Bottom Right', 'wprentals-core' ),
                ],
                'default'   => 'center center',
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper .owner_listing_image' => 'background-position: {{VALUE}};',
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
                    '{{WRAPPER}} .owner-page-wrapper' => 'background-color: {{VALUE}} !important;background-image:none;',
                    '{{WRAPPER}} .owner-page-wrapper .owner-wrapper' => 'background-color: {{VALUE}} !important;background-image:none;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .owner-page-wrapper',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .owner-page-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .owner-page-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .owner-page-wrapper',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'button_style_section',
            [
                'label' => esc_html__( 'Contact Button', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .owner-page-wrapper #contact_me_long',
            ]
        );

        $this->start_controls_tabs( 'button_style_tabs' );

        $this->start_controls_tab(
            'button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .owner-page-wrapper #contact_me_long',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => esc_html__( 'Text Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color_hover',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border_hover',
                'selector' => '{{WRAPPER}} .owner-page-wrapper #contact_me_long:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $postID = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()) {

            $postID = wpestate_last_property_id();
        }

        if ($postID) {
            Plugin::instance()->db->switch_to_post($postID);
            $owner_markup = wprentals_get_owner_section($postID);

            echo $owner_markup;

            Plugin::instance()->db->restore_current_post();
        }
    }
}
