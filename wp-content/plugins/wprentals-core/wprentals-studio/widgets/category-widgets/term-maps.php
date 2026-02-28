<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Wprentals_Term_Map extends Widget_Base
{
    /**
     * Retrieve the widget name.
     */
    public function get_name()
    {
        return 'term_maps';
    }

    public function get_title()
    {
        return __('Term Map', 'wprentals-core');
    }

    public function get_icon()
    {
        return 'wprentals-note eicon-google-maps';
    }

    public function get_categories()
    {
        return ['category_widgets'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'map_height',
            [
                'label' => __('Map Height', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'map_snazy',
            [
                'label' => __('Map Style from Snazy Maps', 'wprentals-core'),
                'type' => Controls_Manager::CODE,
                'language' => 'html',
                'rows' => 20,
            ]
        );

        $this->end_controls_section();
    }

    public function wpresidence_send_to_shortcode($input)
    {
        $output = '';
        if (is_array($input) && $input !== []) {
            $values = array_values($input);
            $output = implode(', ', $values);
        }

        return $output;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $term        = get_queried_object();
        $description = '';

        if ($term instanceof \WP_Term) {
            $description = term_description($term->term_id, $term->taxonomy);
        }

        if ((! $description && \Elementor\Plugin::$instance->editor->is_edit_mode()) || is_singular('wpestate-studio')) {
            $latest_terms = get_terms([
                'taxonomy' => 'property_city',
                'hide_empty' => false,
                'number' => 1,
                'orderby' => 'term_id',
                'order' => 'DESC',
            ]);

            if (! empty($latest_terms) && ! is_wp_error($latest_terms)) {
                $term        = $latest_terms[0];
                $description = term_description($term->term_id, $term->taxonomy);
            }
        }

        if ($term instanceof \WP_Term) {
            $term_id = (string) $term->term_id;

            switch ($term->taxonomy) {
                case 'property_category':
                    $settings['category_ids'] = [$term_id];
                    break;
                case 'property_action_category':
                    $settings['action_ids'] = [$term_id];
                    break;
                case 'property_city':
                    $settings['city_ids'] = [$term_id];
                    break;
                case 'property_area':
                    $settings['area_ids'] = [$term_id];
                    break;
            }
        }

        $attributes = [];
        $attributes['map_height'] = $settings['map_height'] ?? '';
        $attributes['category_ids'] = $this->wpresidence_send_to_shortcode($settings['category_ids'] ?? '');
        $attributes['action_ids'] = $this->wpresidence_send_to_shortcode($settings['action_ids'] ?? '');
        $attributes['city_ids'] = $this->wpresidence_send_to_shortcode($settings['city_ids'] ?? '');
        $attributes['area_ids'] = $this->wpresidence_send_to_shortcode($settings['area_ids'] ?? '');
        $attributes['map_snazy'] = $settings['map_snazy'] ?? '';
        $attributes['is_elementor'] = 1;

      

        if (  \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            print '<div class="wpresidence_map_placholder googleMap_term_shortcode_class">'.esc_html__('The map will be loaded on live page','wpresidence-elementor').'</div>';
        }else{
       //      echo do_shortcode('[term_page_map]');
          echo wpestate_full_map_shortcode($attributes);
        }

    }
}
