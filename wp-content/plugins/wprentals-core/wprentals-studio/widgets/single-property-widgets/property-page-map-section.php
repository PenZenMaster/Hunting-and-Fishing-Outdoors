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

class Wprentals_Property_Page_Map_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_map_section';
    }

    public function get_title() {
        return esc_html__('Property Page Map Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-google-maps';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
      
      

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'map_title_text',
            [
                'label'       => esc_html__( 'Title Text', 'wprentals-core' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Listing Map', 'wprentals-core' ),
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
                    '{{WRAPPER}} .property_page_container.google_map_type1 #on_the_map' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .property_page_container.google_map_type1 #on_the_map',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .property_page_container.google_map_type1 #on_the_map' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .property_page_container.google_map_type1 .google_map_on_list_wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .property_page_container.google_map_type1 .google_map_on_list_wrapper',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_page_container.google_map_type1 .google_map_on_list_wrapper i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .property_page_container.google_map_type1 .google_map_on_list_wrapper svg' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
                    '{{WRAPPER}} .property_page_container.google_map_type1 .google_map_on_list_wrapper svg *' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
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
                    '{{WRAPPER}} .property_page_container.google_map_type1' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .property_page_container.google_map_type1',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .property_page_container.google_map_type1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .property_page_container.google_map_type1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .property_page_container.google_map_type1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .property_page_container.google_map_type1',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $custom_title = isset($settings['map_title_text']) ? $settings['map_title_text'] : '';
        $postID = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular( 'wpestate-studio' ) ||
            is_preview()) {

            $postID = wpestate_last_property_id();
        }

        if ($postID) {
            Plugin::instance()->db->switch_to_post( $postID );

            $map_markup = wpestate_property_show_map($postID);
            $title_value = '';

            if ('' !== trim($custom_title)) {
                $title_value = sanitize_text_field($custom_title);
            }

            if ('' !== $title_value) {
                $map_markup = preg_replace(
                    '/(<h3\s+class="panel-title"\s+id="on_the_map">)(.*?)(<\/h3>)/i',
                    '$1' . esc_html($title_value) . '$3',
                    $map_markup,
                    1
                );
            }

            $show_panel_title_arrow = isset($settings['show_panel_title_arrow']) ? $settings['show_panel_title_arrow'] : 'yes';

            if ('yes' !== $show_panel_title_arrow) {
                $stripped_markup = preg_replace(
                    '/>\s*<span\s+class="panel-title-arrow"><\/span>\s*/',
                    '>',
                    $map_markup
                );

                if (null !== $stripped_markup) {
                    $map_markup = $stripped_markup;
                }
            }

            echo $map_markup;

            Plugin::instance()->db->restore_current_post();
        }
    }
}
