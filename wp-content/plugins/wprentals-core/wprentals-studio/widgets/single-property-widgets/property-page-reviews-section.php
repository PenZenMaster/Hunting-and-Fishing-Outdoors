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

class Wprentals_Property_Page_Reviews_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_reviews_section';
    }

    public function get_title() {
        return esc_html__('Property Page Reviews Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-review';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $default_title_text = esc_html__('Reviews', 'wprentals-core');

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'reviews_title_text',
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
                    '{{WRAPPER}} .reviews_wrapper #listing_reviews' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .reviews_wrapper #listing_reviews',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper #listing_reviews' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .reviews_wrapper .review-content' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .review-content-owner-reply' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .rating_legend' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .ratings-star' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .review-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .reviews_wrapper .review-content, {{WRAPPER}} .reviews_wrapper .review-content-owner-reply, {{WRAPPER}} .reviews_wrapper .rating_legend, {{WRAPPER}} .reviews_wrapper .ratings-star, {{WRAPPER}} .reviews_wrapper .review-date',
            ]
        );

        $this->add_control(
            'reviewer_name_color',
            [
                'label'     => esc_html__( 'Reviewer Name Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .reviews_wrapper .reviwer-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'reviewer_name_typography',
                'selector' => '{{WRAPPER}} .reviews_wrapper .reviwer-name',
            ]
        );

        $this->add_control(
            'rating_legend_color',
            [
                'label'     => esc_html__( 'Rating Legend Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .reviews_wrapper .property-rating .rating_legend' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_total_color',
            [
                'label'     => esc_html__( 'Rating Text Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .reviews_wrapper .property_ratings .ratings-star' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ratings_style_section',
            [
                'label' => esc_html__( 'Ratings', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'summary_icon_color',
            [
                'label'     => esc_html__( 'Summary Stars Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .reviews_wrapper .listing_reviews_container > .property_ratings i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .listing_reviews_container > .property_ratings svg' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .listing_reviews_container > .property_ratings svg *' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'review_icon_color',
            [
                'label'     => esc_html__( 'Review Stars Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .reviews_wrapper .review-list-content .property_ratings i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .review-list-content .property_ratings svg' => 'color: {{VALUE}}; fill: {{VALUE}}; stroke: {{VALUE}};',
                    '{{WRAPPER}} .reviews_wrapper .review-list-content .property_ratings svg *' => 'fill: {{VALUE}}; stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'review_item_gap',
            [
                'label'      => esc_html__( 'Review Gap', 'wprentals-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper .listing-review' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'review_item_style_section',
            [
                'label' => esc_html__( 'Review Item', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'review_item_background',
            [
                'label'     => esc_html__( 'Background Color', 'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .reviews_wrapper .listing-review' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'review_item_border',
                'selector' => '{{WRAPPER}} .reviews_wrapper .listing-review',
            ]
        );

        $this->add_responsive_control(
            'review_item_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper .listing-review' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'review_item_padding',
            [
                'label'      => esc_html__( 'Padding', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper .listing-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'review_item_box_shadow',
                'selector' => '{{WRAPPER}} .reviews_wrapper .listing-review',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'reviewer_image_style_section',
            [
                'label' => esc_html__( 'Reviewer Image', 'wprentals-core' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'reviewer_image_size',
            [
                'label'      => esc_html__( 'Size', 'wprentals-core' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper .reviewer_image' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reviewer_image_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper .reviewer_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'reviewer_image_border',
                'selector' => '{{WRAPPER}} .reviews_wrapper .reviewer_image',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'reviewer_image_box_shadow',
                'selector' => '{{WRAPPER}} .reviews_wrapper .reviewer_image',
            ]
        );

        $this->add_responsive_control(
            'reviewer_image_margin',
            [
                'label'      => esc_html__( 'Margin', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper .reviewer_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .reviews_wrapper' => 'background-color: {{VALUE}};',
                     '{{WRAPPER}} .listing_reviews_wrapper' => 'background-color: {{VALUE}};',

                    
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'wrapper_border',
                'selector' => '{{WRAPPER}} .reviews_wrapper',
            ]
        );

        $this->add_responsive_control(
            'wrapper_border_radius',
            [
                'label'      => esc_html__( 'Border Radius', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .reviews_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .reviews_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .reviews_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .reviews_wrapper',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $custom_title_value = '';
        $has_custom_title = false;

        if (isset($settings['reviews_title_text'])) {
            $custom_title_value = sanitize_text_field($settings['reviews_title_text']);
            $has_custom_title = '' !== trim($custom_title_value);
        }

        $postID = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular( 'wpestate-studio' ) ||
            is_preview()) {

            $postID = wpestate_last_property_id();
        }

        if ($postID) {
            Plugin::instance()->db->switch_to_post( $postID );

            $reviews_markup = wpestate_property_show_reviews($postID);

            if (!empty($reviews_markup)) {
                $processed_markup = preg_replace_callback(
                    '/(<h3[^>]*id="listing_reviews"[^>]*>\s*)(<span\s+class="panel-title-arrow"><\/span>\s*)?(.*?)(<\/h3>)/si',
                    function ($matches) use ($has_custom_title, $custom_title_value) {
                        $prefix = $matches[1];
                        $arrow_markup = isset($matches[2]) ? $matches[2] : '';
                        $title_content = $matches[3];

                        if ($has_custom_title) {
                            $title_content = esc_html($custom_title_value);
                        }

                        return $prefix . $arrow_markup . $title_content . $matches[4];
                    },
                    $reviews_markup,
                    1
                );

                if (null !== $processed_markup) {
                    $reviews_markup = $processed_markup;
                }
            }

            if (!empty($reviews_markup)) {
                echo '<div class="reviews_wrapper panel-wrapper">';
                echo $reviews_markup;
                echo '</div>';
            }

            Plugin::instance()->db->restore_current_post();
        }
    }
}
