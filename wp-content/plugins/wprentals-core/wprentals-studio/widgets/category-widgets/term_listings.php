<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

if (! defined('ABSPATH')) {
    exit;
}

class Wprentals_Term_Listings extends Widget_Base
{
    public function get_name()
    {
        return 'Wprentals_Term_List_Properties';
    }

    public function get_categories()
    {
        return ['category_widgets'];
    }

    public function get_title()
    {
        return __('Listings per Term', 'wprentals-core');
    }

    public function get_icon()
    {
        return 'wprentals-note eicon-posts-grid';
    }

    public function get_script_depends()
    {
        return [];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label'       => esc_html__('Title', 'wprentals-core'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'number',
            [
                'label'   => esc_html__('Number of items', 'wprentals-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 9,
                'min'     => 1,
            ]
        );

        $this->add_control(
            'rownumber',
            [
                'label'   => esc_html__('Items per row', 'wprentals-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 6,
            ]
        );

        $this->add_control(
            'display_grid',
            [
                'label'        => esc_html__('Display as grid?', 'wprentals-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'wprentals-core'),
                'label_off'    => esc_html__('No', 'wprentals-core'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

           $this->add_responsive_control(
                'display_grid_unit_width',
                [
                    'label' => esc_html__('Unit Minimum Width', 'rentals-elementor'),
                    'condition' => [
                        'display_grid' => 'yes'
                    ],
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 220,
                            'max' => 400,
                        ],
                    ],
                    'devices' => ['desktop', 'tablet', 'mobile'],
                    'desktop_default' => [
                        'size' => '250',
                        'unit' => 'px',
                    ],
                    'tablet_default' => [
                        'size' => '250',
                        'unit' => 'px',
                    ],
                    'mobile_default' => [
                        'size' => '250',
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .items_shortcode_wrapper_grid' => '  grid-template-columns: repeat(auto-fit, minmax({{SIZE}}{{UNIT}}, auto));',
                    ],
                    
                ]
        );

        $this->add_responsive_control(
                'display_grid_unit_gap',
                [
                    'label' => esc_html__('Gap between units in px', 'rentals-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'condition' => [
                        'display_grid' => 'yes'
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'devices' => ['desktop', 'tablet', 'mobile'],
                    'desktop_default' => [
                        'size' => '10',
                        'unit' => 'px',
                    ],
                    'tablet_default' => [
                        'size' => '10',
                        'unit' => 'px',
                    ],
                    'mobile_default' => [
                        'size' => '10',
                        'unit' => 'px',
                    ],
                    'default' => [
					'unit' => 'px',
					'size' => 10,
				],
                    'selectors' => [
                        '{{WRAPPER}} .items_shortcode_wrapper_grid' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_grid_box_shadow',
            [
                'label' => esc_html__('Box Shadow', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .property_listing',
            ]
        );

        $this->end_controls_section();
    }

    private function get_preview_term($taxonomy)
    {
        if (empty($taxonomy) || ! taxonomy_exists($taxonomy)) {
            $taxonomy = 'property_category';
        }

        $latest_terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
            'number'     => 1,
            'orderby'    => 'term_id',
            'order'      => 'DESC',
        ]);

        if (! empty($latest_terms) && ! is_wp_error($latest_terms)) {
            return $latest_terms[0];
        }

        return null;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $term = get_queried_object();
        $selected_taxonomy =  'property_category';

        if ($term instanceof \WP_Term) {
            $taxonomy = $term->taxonomy;
        } else {
            $taxonomy = $selected_taxonomy;
        }

        if (! ($term instanceof \WP_Term)) {
            $term = $this->get_preview_term($taxonomy);
        } elseif (\Elementor\Plugin::$instance->editor->is_edit_mode() || is_singular('wpestate-studio')) {
            $preview_term = $this->get_preview_term($taxonomy);

            if ($preview_term instanceof \WP_Term) {
                $term = $preview_term;
            }
        }

        $taxonomy_filters = $this->resolve_taxonomy_filters($taxonomy, $term);

        $attributes = array_merge(
            $taxonomy_filters['attributes'],
            [
            'title'               => $settings['title'] ?? '',
            'type'                => 'properties',
            'number'              => isset($settings['number']) ? (int) $settings['number'] : 0,
            'rownumber'           => isset($settings['rownumber']) ? (int) $settings['rownumber'] : 0,
            'display_grid'        => (! empty($settings['display_grid']) && 'yes' === $settings['display_grid']) ? 'yes' : 'no',
            'additional_tax_query'=> $taxonomy_filters['additional_tax_query'],
            'category_filters' => 'yes'
        ]
        );

        $attributes['full_row'] = 'no';
        if (function_exists('wpestate_recent_posts_pictures')) {
            

            echo wpestate_recent_posts_pictures($attributes);
        } else {
            echo '<div class="wprentals-term-listings-placeholder">' . esc_html__('Listings will render on the live category page.', 'wprentals-core') . '</div>';
        }
    }

    private function get_taxonomy_options()
    {
        $taxonomies = get_object_taxonomies('estate_property', 'objects');
        $options = [];

        if (is_array($taxonomies)) {
            foreach ($taxonomies as $taxonomy) {
                $options[$taxonomy->name] = $taxonomy->labels->singular_name ?? $taxonomy->label;
            }
        }

        if (empty($options)) {
            $options['property_category'] = esc_html__('Property Category', 'wprentals-core');
        }

        return $options;
    }

    private function resolve_taxonomy_filters($taxonomy, $term)
    {
        $attributes = [
            'category_ids' => '',
            'action_ids'   => '',
            'city_ids'     => '',
            'area_ids'     => '',
            'features_ids'=>'',
            'status_ids'=>'',
        ];

        $additional_tax_query = [];

        if (! ($term instanceof \WP_Term)) {
            return [
                'attributes'           => $attributes,
                'additional_tax_query' => $additional_tax_query,
            ];
        }

        switch ($taxonomy) {
            case 'property_category':
                $attributes['category_ids'] = (string) $term->term_id;
                break;
            case 'property_action_category':
                $attributes['action_ids'] = (string) $term->term_id;
                break;
            case 'property_city':
                $attributes['city_ids'] = (string) $term->term_id;
                break;
            case 'property_area':
                $attributes['area_ids'] = (string) $term->term_id;
                break;
            case 'property_features':
                $attributes['features_ids'] = (string) $term->term_id;
                break;
            case 'property_status':
                $attributes['status_ids'] = (string) $term->term_id;
                break;
            default:
                if (! empty($taxonomy)) {
                    $additional_tax_query[] = [
                        'taxonomy' => $taxonomy,
                        'field'    => 'term_id',
                        'terms'    => [(int) $term->term_id],
                    ];
                }
                break;
        }

        return [
            'attributes'           => $attributes,
            'additional_tax_query' => $additional_tax_query,
        ];
    }
}
