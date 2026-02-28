<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Owner_Header extends Widget_Base {

    public function get_name() {
        return 'wprentals_owner_header';
    }

    public function get_title() {
        return esc_html__('WpRentals Owner Header', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-header';
    }

    public function get_categories() {
        return ['wprentals_owners'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_wrapper_style',
            [
                'label' => esc_html__('Wrapper', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

   

         $this->add_control(
            'wrapper_background',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper' => 'background-color: {{VALUE}};background-image:none',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_owner_image_style',
            [
                'label' => esc_html__('Owner Image', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'owner_image_width',
            [
                'label'      => esc_html__('Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 600,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .owner-image-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'owner_image_height',
            [
                'label'      => esc_html__('Height', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 600,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .owner-image-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'owner_image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .owner-image-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'owner_image_border',
                'label'    => esc_html__('Border', 'wprentals-core'),
                'selector' => '{{WRAPPER}} .owner-page-wrapper .owner-image-container',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_owner_column_style',
            [
                'label' => esc_html__('Owner Column', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'owner_column_padding',
            [
                'label'      => esc_html__('Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .user_picture_owner_page' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_details_column_style',
            [
                'label' => esc_html__('Details Column', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'details_column_padding',
            [
                'label'      => esc_html__('Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper .col-md-10' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'details_text_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper .col-md-10, {{WRAPPER}} .owner-page-wrapper .col-md-10 *:not(i)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'details_typography',
                'label'    => esc_html__('Typography', 'wprentals-core'),
                'selector' => '{{WRAPPER}} .owner-page-wrapper .col-md-10, {{WRAPPER}} .owner-page-wrapper .col-md-10 *:not(i)',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_social_icons_style',
            [
                'label' => esc_html__('Social Icons', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'social_icons_color',
            [
                'label'     => esc_html__('Icon Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper .social_icons_owner i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_contact_button_style',
            [
                'label' => esc_html__('Contact Button', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'contact_button_margin',
            [
                'label'      => esc_html__('Margin', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_padding',
            [
                'label'      => esc_html__('Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'contact_button_typography',
                'label'    => esc_html__('Typography', 'wprentals-core'),
                'selector' => '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'contact_button_border',
                'label'    => esc_html__('Border', 'wprentals-core'),
                'selector' => '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner',
                'exclude'  => ['color'],
            ]
        );

        $this->add_responsive_control(
            'contact_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('contact_button_style_tabs');

        $this->start_controls_tab(
            'contact_button_normal_tab',
            [
                'label' => esc_html__('Normal', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'contact_button_text_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'contact_button_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'contact_button_border_color',
            [
                'label'     => esc_html__('Border Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'contact_button_hover_tab',
            [
                'label' => esc_html__('Hover', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'contact_button_hover_text_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'contact_button_hover_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'contact_button_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .owner-page-wrapper #contact_me_long_owner:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $current_agent_id = get_the_ID();

        if (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $current_agent_id = wpestate_last_agent_id();
        }

        if ($current_agent_id) {
            global $agent_id, $owner_id, $user_agent_id, $wp_query, $post;

            $is_elementor=1;
            $agent_id      = $current_agent_id;
            $owner_id      = get_post_meta($agent_id, 'user_agent_id', true);
            $user_agent_id = wpestate_user_for_agent($agent_id);

            $previous_wp_query = $wp_query;
            $previous_post     = $post;

            $agent_query = new \WP_Query([
                'post_type'      => 'estate_agent',
                'p'              => $agent_id,
                'posts_per_page' => 1,
                'post_status'    => 'publish',
            ]);

            if ($agent_query->have_posts()) {
                $wp_query = $agent_query;

                include(locate_template('templates/owner_details_header.php'));
            }

            $wp_query = $previous_wp_query;
            $post     = $previous_post;

            wp_reset_postdata();
        }
    }
}
