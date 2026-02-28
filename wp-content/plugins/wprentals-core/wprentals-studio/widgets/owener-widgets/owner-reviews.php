<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Owner_Reviews extends Widget_Base {

    public function get_name() {
        return 'wprentals_owner_reviews';
    }

    public function get_title() {
        return esc_html__('WpRentals Owner Reviews', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-review';
    }

    public function get_categories() {
        return ['wprentals_owners'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_style_reviewer_image',
            [
                'label' => esc_html__('Reviewer Image', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'reviewer_image_width',
            [
                'label'      => esc_html__('Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .listing-reviews-wrapper .reviewer_image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reviewer_image_height',
            [
                'label'      => esc_html__('Height', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .listing-reviews-wrapper .reviewer_image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'reviewer_image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .listing-reviews-wrapper .reviewer_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_reviews_title',
            [
                'label' => esc_html__('Reviews Title', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'reviews_title_typography',
                'selector' => '{{WRAPPER}} .listing-reviews-wrapper #listing_reviews',
            ]
        );

        $this->add_control(
            'reviews_title_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .listing-reviews-wrapper #listing_reviews' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_reviewer_name',
            [
                'label' => esc_html__('Reviewer Name', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'reviewer_name_typography',
                'selector' => '{{WRAPPER}} .listing-reviews-wrapper .reviwer-name',
            ]
        );

        $this->add_control(
            'reviewer_name_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .listing-reviews-wrapper .reviwer-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_review_content',
            [
                'label' => esc_html__('Review Content', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'review_content_typography',
                'selector' => '{{WRAPPER}} .listing-reviews-wrapper .review-content',
            ]
        );

        $this->add_control(
            'review_content_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .listing-reviews-wrapper .review-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_review_ratings',
            [
                'label' => esc_html__('Review Ratings', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'review_ratings_typography',
                'selector' => '{{WRAPPER}} .listing-reviews-wrapper .property_ratings .ratings-star',
            ]
        );

        $this->add_control(
            'review_ratings_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .listing-reviews-wrapper .property_ratings .ratings-star' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'review_star_color',
            [
                'label'     => esc_html__('Star Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .listing-reviews-wrapper .property_ratings i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_review_date',
            [
                'label' => esc_html__('Review Date', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'review_date_typography',
                'selector' => '{{WRAPPER}} .listing-reviews-wrapper .review-date',
            ]
        );

        $this->add_control(
            'review_date_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .listing-reviews-wrapper .review-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_review_item',
            [
                'label' => esc_html__('Review Item', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'review_item_padding',
            [
                'label'      => esc_html__('Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .listing-reviews-wrapper .listing-review' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

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

            $agent_id      = $current_agent_id;
            $owner_id      = get_post_meta($agent_id, 'user_agent_id', true);
            $user_agent_id = wpestate_user_for_agent($agent_id);
            $is_elementor  = 1;
     
         

            global $agent_id, $prop_selection, $comments_data, $post;
            $comments_data = wpestate_review_composer($agent_id);
    
            include locate_template('templates/agent_reviews.php');

            wp_reset_postdata();
        }
    }
}
