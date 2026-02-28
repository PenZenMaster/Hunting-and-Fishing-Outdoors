<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Property_Page_Availability_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_availability_section';
    }

    public function get_title() {
        return esc_html__('Property Page Availability Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-date';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $default_title_text = esc_html__( 'Availability', 'wprentals-core' );

        if (\function_exists('icl_translate')) {
            $default_title_text = \icl_translate(
                'wprentals',
                'wp_estate_property_availability_text',
                esc_html__( 'Availability', 'wprentals-core' )
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
            'availability_title_text',
            [
                'label'       => esc_html__( 'Title Text', 'wprentals-core' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => $default_title_text,
                'placeholder' => esc_html__( 'Enter title', 'wprentals-core' ),
                'label_block' => true,
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
                    '{{WRAPPER}} #listing_calendar.panel-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} #listing_calendar.panel-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} #listing_calendar.panel-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability .calendar-legend span' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability .booking-calendar-wrapper .month-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability table.booking-calendar th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .property_page_container.wprentals_front_avalability, {{WRAPPER}} .property_page_container.wprentals_front_avalability .calendar-legend span, {{WRAPPER}} .property_page_container.wprentals_front_avalability .booking-calendar-wrapper .month-title, {{WRAPPER}} .property_page_container.wprentals_front_avalability table.booking-calendar th',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'calendar_style_section',
            [
                'label' => esc_html__( 'Calendar', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'calendar_date_color',
            [
                'label'     => esc_html__( 'Date Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability table.booking-calendar td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'calendar_date_typography',
                'selector' => '{{WRAPPER}} .property_page_container.wprentals_front_avalability table.booking-calendar td',
            ]
        );

        $this->add_control(
            'calendar_price_color',
            [
                'label'     => esc_html__( 'Price Font Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability .wprentals_front_calendar_price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'calendar_price_typography',
                'selector' => '{{WRAPPER}} .property_page_container.wprentals_front_avalability .wprentals_front_calendar_price',
            ]
        );

        $this->add_control(
            'calendar_future_background_color',
            [
                'label'     => esc_html__( 'Future Day Background', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability .all-front-calendars .has_future' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'calendar_past_background_color',
            [
                'label'     => esc_html__( 'Past Day Background', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability .all-front-calendars .has_past' => 'background-color: {{VALUE}}!important;',
                      '{{WRAPPER}} .property_page_container.wprentals_front_avalability .all-front-calendars .has_past' => 'background: {{VALUE}}!important;',
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
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .property_page_container.wprentals_front_avalability',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .property_page_container.wprentals_front_avalability' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .property_page_container.wprentals_front_avalability',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $custom_title = isset($settings['availability_title_text']) ? $settings['availability_title_text'] : '';
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

            $availability_markup = wpestate_property_show_avalability($postID);
            if ('' !== $title_value) {
                $replaced_markup = preg_replace(
                    '/(<h3\s+[^>]*id="listing_calendar"[^>]*>\s*(?:<span\s+class="panel-title-arrow"><\/span>\s*)?)(.*?)(<\/h3>)/is',
                    '$1' . esc_html($title_value) . '$3',
                    $availability_markup,
                    1
                );

                if (null !== $replaced_markup) {
                    $availability_markup = $replaced_markup;
                }
            }

            echo $availability_markup;

            Plugin::instance()->db->restore_current_post();
        }
    }
}
