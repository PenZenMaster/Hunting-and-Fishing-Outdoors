<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class Wprentals_Term_Description extends Widget_Base {
   
    public function get_name() {
        return 'term_description';
    }
   
    public function get_title() {
        return __('Term Description', 'wprentals-core');
    }
   
    public function get_icon() {
        return 'wprentals-note  eicon-editor-paragraph';
    }
   
    public function get_categories() {
        return ['category_widgets'];
    }
   
    protected function register_controls() {
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'wprentals-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
       
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .wprentals-term-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Color', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_paragraph_spacing',
            [
                'label' => __('Paragraph Spacing', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 5,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-term-description p' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
       
        $this->end_controls_section();
    }
   
    protected function render() {
        $description = '';
        $term        = get_queried_object();
       
        if ( $term instanceof \WP_Term ) {
            $description = term_description( $term->term_id, $term->taxonomy );
        }
       
        if ( (! $description && \Elementor\Plugin::$instance->editor->is_edit_mode()) || is_singular( 'wpestate-studio' ) ) {
            $latest_terms = get_terms([
                'taxonomy'   => 'property_city',
                'hide_empty' => false,
                'number'     => 1,
                'orderby'    => 'term_id',
                'order'      => 'DESC',
            ]);
           
            if ( ! empty( $latest_terms ) && ! is_wp_error( $latest_terms ) ) {
                $term        = $latest_terms[0];
                $description = term_description( $term->term_id, $term->taxonomy );
            }
        }
       
        if ($description) {
            echo '<div class="wprentals-term-description">' . wp_kses_post($description) . '</div>';
        } else {
            echo '<div class="wprentals-term-description">' . esc_html__( 'This term does not have a description.', 'wprentals-core' ) . '</div>';
        }
    }
}