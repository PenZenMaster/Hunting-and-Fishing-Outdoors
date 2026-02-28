<?php

namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Term_Header extends Widget_Base {

    /**
     * Retrieve a taxonomy meta value prioritising native term meta over the legacy option array.
     *
     * @param int    $term_id Term identifier.
     * @param string $key     Meta key.
     *
     * @return string
     */
    private function get_term_meta_value( $term_id, $key ) {
        $value = get_term_meta( $term_id, $key, true );

        if ( '' === $value ) {
            $legacy = get_option( 'taxonomy_' . $term_id );
            if ( isset( $legacy[ $key ] ) && '' !== $legacy[ $key ] ) {
                $value = 'category_tagline' === $key ? stripslashes( $legacy[ $key ] ) : $legacy[ $key ];
            }
        }

        return $value;
    }

    public function get_name() {
        return 'term_header';
    }

    public function get_categories() {
        return ['category_widgets'];
    }

    public function get_title() {
        return __('Term Header', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note  eicon-header';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'container_height',
            [
                'label' => esc_html__('Container Height', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 800,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .term-featured-container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Title Styling
        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__('Title Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .term-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .term-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_text_shadow',
                'selector' => '{{WRAPPER}} .term-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .term-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Tagline Styling
        $this->start_controls_section(
            'tagline_style',
            [
                'label' => esc_html__('Tagline Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'tagline_typography',
                'selector' => '{{WRAPPER}} .term-tagline',
            ]
        );

        $this->add_control(
            'tagline_color',
            [
                'label' => esc_html__('Tagline Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .term-tagline' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'tagline_text_shadow',
                'selector' => '{{WRAPPER}} .term-tagline',
            ]
        );

        $this->add_responsive_control(
            'tagline_margin',
            [
                'label' => esc_html__('Tagline Margin', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .term-tagline' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Overlay Styling
        $this->start_controls_section(
            'overlay_style',
            [
                'label' => esc_html__('Overlay', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_overlay',
            [
                'label' => esc_html__('Show Overlay', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => esc_html__('Overlay Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .term-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'show_overlay' => 'yes',
                ],
            ]
        );

            $this->end_controls_section();
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Border', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        // Border
        $this->add_group_control(
        \Elementor\Group_Control_Border::get_type(),
        [
            'name' => 'container_border',
            'selector' => '{{WRAPPER}} .term-featured-container',
        ]
        );

        // Border Radius
        $this->add_responsive_control(
        'container_border_radius',
        [
            'label' => esc_html__('Border Radius', 'wprentals-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em', 'rem'],
            'selectors' => [
                '{{WRAPPER}} .term-featured-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  '{{WRAPPER}} .term-overlay'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
           
            ],
        ]
        );
            

        $this->end_controls_section();

        // Content Position
        $this->start_controls_section(
            'content_position',
            [
                'label' => esc_html__('Content Position', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        
       $this->add_responsive_control(
        'term_content_padding',
        [
            'label' => esc_html__('Content Padding', 'wprentals-core'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'allowed_dimensions' => ['top', 'right', 'bottom', 'left'],
            'selectors' => [
                '{{WRAPPER}} .term-content' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
            ],
        ]
    );



        $this->add_responsive_control(
        'content_align',
        [
            'label' => esc_html__('Content Alignment', 'wprentals-core'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => [
                    'title' => esc_html__('Start', 'wprentals-core'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'wprentals-core'),
                        'icon' => 'eicon-text-align-center',
                ],
                'flex-end' => [
                    'title' => esc_html__('End', 'wprentals-core'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'default' => 'center',
            'selectors' => [
                '{{WRAPPER}} .term-content' => 'align-items: {{VALUE}};',
            ],
        ]
        );

      

        $this->end_controls_section();
     
  
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $term = get_queried_object();
        if ( (! ( $term instanceof \WP_Term ) && \Elementor\Plugin::$instance->editor->is_edit_mode()) ||  is_singular( 'wpestate-studio' ) ) {
            $latest_terms = get_terms([
                'taxonomy'   => 'property_city',
                'hide_empty' => false,
                'number'     => 1,
                'orderby'    => 'term_id',
                'order'      => 'DESC',
            ]);
            if ( ! empty( $latest_terms ) ) {
                $term = $latest_terms[0];
            }
         
        }

        if ( ! ( $term instanceof \WP_Term ) ) {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                echo '<div class="term-featured-container fallback-preview">';
                echo '<p>' . esc_html__('No term found for preview', 'wprentals-core') . '</p>';
                echo '</div>';
            }
            return;
        }

        $tagline = $this->get_term_meta_value( $term->term_id, 'category_tagline' );

        // Get featured image
        $attach_id = $this->get_term_meta_value( $term->term_id, 'category_attach_id' );
        $image_url = $this->get_term_meta_value( $term->term_id, 'category_featured_image' );

        $background_image = '';
        if ( $image_url ) {
            $background_image = 'background-image: url(' . esc_url( $image_url ) . ');';
        } elseif ( $attach_id ) {
            $attachment_url = wp_get_attachment_image_src( $attach_id, 'full' );
            if ( $attachment_url ) {
                $background_image = 'background-image: url(' . esc_url( $attachment_url[0] ) . ');';
            }
        }

        ?>
        <div class="term-featured-container" style="<?php echo $background_image; ?>">
            <?php if ( 'yes' === $settings['show_overlay'] ) : ?>
                <div class="term-overlay"></div>
            <?php endif; ?>
            
            <div class="term-content">
                <h1 class="term-title"><?php echo esc_html( $term->name ); ?></h1>
                <?php if ( $tagline ) : ?>
                    <h2 class="term-tagline"><?php echo esc_html( $tagline ); ?></h2>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}