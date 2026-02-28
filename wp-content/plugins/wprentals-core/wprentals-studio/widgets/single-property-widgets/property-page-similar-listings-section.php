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

class Wprentals_Property_Page_Similar_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_similar_listings_section';
    }

    public function get_title() {
        return esc_html__('Property Page Similar Listings Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-gallery-grid';
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
            'listings_per_row',
            [
                'label'   => esc_html__( 'Listings Per Row', 'wprentals-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => esc_html__( '2 Listings', 'wprentals-core' ),
                    '3' => esc_html__( '3 Listings', 'wprentals-core' ),
                    '4' => esc_html__( '4 Listings', 'wprentals-core' ),
                ],
            ]
        );

         $this->add_control(
            'listings_number',
            [
                'label'   => esc_html__( 'Number of Listings', 'wprentals-core' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '2' => esc_html__( '2 Listings', 'wprentals-core' ),
                    '3' => esc_html__( '3 Listings', 'wprentals-core' ),
                    '4' => esc_html__( '4 Listings', 'wprentals-core' ),
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
                    '{{WRAPPER}} .similar_listings_wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .similar_listings_wrapper',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .similar_listings_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .similar_listings_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .similar_listings_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .similar_listings_wrapper',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $listings_per_row = isset($settings['listings_per_row']) ? (string) $settings['listings_per_row'] : '3';

        $listings_number = isset($settings['listings_number']) ? (string) $settings['listings_number'] : '3';

        
        $columns_map = [
            '2' => 6,
            '3' => 4,
            '4' => 3,
        ];
        $bootstrap_columns = isset($columns_map[$listings_per_row]) ? $columns_map[$listings_per_row] : 4;
        $postID = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular( 'wpestate-studio' ) ||
            is_preview()) {

            $postID = wpestate_last_property_id();
        }

        if ($postID) {
            Plugin::instance()->db->switch_to_post( $postID );

            $similar_markup = '';

            if (function_exists('wprentals_get_similar_listing')) {
                $similar_markup = wprentals_get_similar_listing($postID,$listings_number, 1, $bootstrap_columns);
            }

            echo $similar_markup;

            Plugin::instance()->db->restore_current_post();
        }
    }
}
