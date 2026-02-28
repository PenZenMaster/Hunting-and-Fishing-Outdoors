<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Property_Page_Booking_Form extends Widget_Base {

    public function get_name() {
        return 'property_page_booking_form';
    }

    public function get_title() {
        return esc_html__('Property Page Booking Form', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-calendar';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    private static $inline_styles_printed = false;

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_third_form_wrapper',
            [
                'label'        => esc_html__( 'Show Action Buttons', 'wprentals-core' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Show', 'wprentals-core' ),
                'label_off'    => esc_html__( 'Hide', 'wprentals-core' ),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_social_share',
            [
                'label'        => esc_html__( 'Show Social Share', 'wprentals-core' ),
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
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request h3',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'text_style_section',
            [
                'label' => esc_html__( 'Text', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request :not(h3):not(input):not(textarea):not(select):not(option):not(button)' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request .prop_social a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'text_typography',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request :not(h3):not(input):not(textarea):not(select):not(option):not(button)',
            ]
        );

        $this->add_responsive_control(
            'wrapper_padding',
            [
                'label'      => esc_html__( 'Wrapper Padding', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'inputs_style_section',
            [
                'label' => esc_html__( 'Inputs', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $input_selector_parts = [
            '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request input',
            '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request select',
            '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request textarea',
            '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request .form-control',
            '{{WRAPPER}} .widget-container .wp-block-search__input',
            '{{WRAPPER}} .invoices-wrapper .form-control',
            '{{WRAPPER}} #advanced_search_shortcode .form-control',
            '{{WRAPPER}} .agent_contanct_form .form-control',
            '{{WRAPPER}} #commentform .form-control',
            '{{WRAPPER}} #advanced_search_map_list .form-control',
            '{{WRAPPER}} #booking_form_request .form-control',
        ];

        $input_fields_selector      = implode(', ', $input_selector_parts);
        $input_placeholder_selector = implode(', ', array_map(static function ($selector) {
            return $selector . '::placeholder';
        }, $input_selector_parts));

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'inputs_typography',
                'selector' => $input_fields_selector,
            ]
        );

        $this->add_control(
            'inputs_text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $input_fields_selector      => 'color: {{VALUE}} !important;',
                    $input_placeholder_selector => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'inputs_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $input_fields_selector => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'inputs_border_width',
            [
                'label'      => esc_html__( 'Border Width', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    $input_fields_selector => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_control(
            'inputs_border_style',
            [
                'label'   => esc_html__( 'Border Style', 'wprentals-core' ),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    ''       => esc_html__( 'Default', 'wprentals-core' ),
                    'solid'  => esc_html__( 'Solid', 'wprentals-core' ),
                    'dashed' => esc_html__( 'Dashed', 'wprentals-core' ),
                    'dotted' => esc_html__( 'Dotted', 'wprentals-core' ),
                    'double' => esc_html__( 'Double', 'wprentals-core' ),
                    'none'   => esc_html__( 'None', 'wprentals-core' ),
                ],
                'selectors' => [
                    $input_fields_selector => 'border-style: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'inputs_border_color',
            [
                'label'     => esc_html__( 'Border Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    $input_fields_selector => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'favorite_button_style_section',
            [
                'label' => esc_html__( 'Favorite Button', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'favorite_button_typography',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites',
            ]
        );

        $this->start_controls_tabs( 'favorite_button_style_tabs' );

        $this->start_controls_tab(
            'favorite_button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'favorite_button_text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'favorite_button_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'favorite_button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'favorite_button_hover_text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'favorite_button_hover_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'favorite_button_border',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites',
            ]
        );

        $this->add_responsive_control(
            'favorite_button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'favorite_button_padding',
            [
                'label'      => esc_html__( 'Padding', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #add_favorites' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'contact_button_style_section',
            [
                'label' => esc_html__( 'Contact Button', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'contact_button_typography',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host',
            ]
        );

        $this->start_controls_tabs( 'contact_button_style_tabs' );

        $this->start_controls_tab(
            'contact_button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'contact_button_text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'contact_button_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'contact_button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'contact_button_hover_text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'contact_button_hover_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host:hover' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'contact_button_border',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host',
            ]
        );

        $this->add_responsive_control(
            'contact_button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'contact_button_padding',
            [
                'label'      => esc_html__( 'Padding', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #contact_host' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'submit_button_style_section',
            [
                'label' => esc_html__( 'Submit Button', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'submit_button_typography',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front',
            ]
        );

        $this->start_controls_tabs( 'submit_button_style_tabs' );

        $this->start_controls_tab(
            'submit_button_style_normal',
            [
                'label' => esc_html__( 'Normal', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'submit_button_text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_button_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front' => 'background-color: {{VALUE}};',
                                        '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'submit_button_style_hover',
            [
                'label' => esc_html__( 'Hover', 'wprentals-core' ),
            ]
        );

        $this->add_control(
            'submit_button_hover_text_color',
            [
                'label'     => esc_html__( 'Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'submit_button_hover_background_color',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'submit_button_border',
                'selector' => '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front',
            ]
        );

        $this->add_responsive_control(
            'submit_button_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'submit_button_padding',
            [
                'label'      => esc_html__( 'Padding', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals_elementor_booking_form_wrapper .booking_form_request #submit_booking_front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $postID = get_the_ID();

        $preview_property_id = 0;
        $property_id          = get_the_ID();

        if (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $property_id = wpestate_last_property_id();
        }

        $wrapper_classes = ['wprentals_elementor_booking_form_wrapper'];
        $show_third_form = isset($settings['show_third_form_wrapper']) ? $settings['show_third_form_wrapper'] : 'yes';
        $show_social     = isset($settings['show_social_share']) ? $settings['show_social_share'] : 'yes';

        if ('yes' !== $show_third_form) {
            $wrapper_classes[] = 'wprentals-hide-third-form-wrapper';
        }

        if ('yes' !== $show_social) {
            $wrapper_classes[] = 'wprentals-hide-prop-social';
        }

        $attributes           = [];
        $attributes['id']     = $postID;
        $wrapper_class_string = implode(' ', $wrapper_classes);

        if ($property_id) {
            self::print_inline_styles();

            print '<div class="' . esc_attr($wrapper_class_string) . '">';

            if (function_exists('wpestate_booking_form')) {
                echo wpestate_booking_form($attributes);
            }

            if (function_exists('wpestate_ajax_show_contact_owner_form')) {
             //   wpestate_ajax_show_contact_owner_form();
            }

            print '</div>';
        }

    }





       
    
    private static function print_inline_styles() {
        if (self::$inline_styles_printed) {
            return;
        }

        echo '<style class="wprentals-property-page-booking-form-inline">';
        echo '.wprentals_elementor_booking_form_wrapper.wprentals-hide-third-form-wrapper .booking_form_request .third-form-wrapper{display:none !important;}';
        echo '.wprentals_elementor_booking_form_wrapper.wprentals-hide-prop-social .booking_form_request .prop_social{display:none !important;}';
        echo '</style>';

        self::$inline_styles_printed = true;
    }
}
