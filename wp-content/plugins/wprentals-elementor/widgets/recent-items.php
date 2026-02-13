<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Recent_Items extends Widget_Base {

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Wprentals_Recent_Items';
    }

    public function get_categories() {
        return ['wprentals'];
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('WpRentals Recent Items', 'rentals-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-masonry';
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        return [''];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    public function elementor_transform($input) {
        $output = array();
        if (is_array($input)) {
            foreach ($input as $key => $tax) {
                $output[$tax['value']] = $tax['label'];
            }
        }
        return $output;
    }

    protected function register_controls() {
        global $all_tax;
        global $wprentals_property_category_values;
        global $wprentals_property_action_category_values;
        global $wprentals_property_city_values;
        global $wprentals_property_area_values;

        $wprentals_property_category_values_elementor = $this->elementor_transform($wprentals_property_category_values);
        $wprentals_property_action_category_values_elementor = $this->elementor_transform($wprentals_property_action_category_values);
        $wprentals_property_city_values_elementor = $this->elementor_transform($wprentals_property_city_values);
        $wprentals_property_area_values_elementor = $this->elementor_transform($wprentals_property_area_values);
        $featured_listings = array('no' => 'no', 'yes' => 'yes');
        $items_type = array('properties' => 'properties', 'articles' => 'articles');
        $blog_items_type = array(
            1 => 'type 1 - full row',
            2 => 'type 2',
            3 => 'type 3');
        $recent_items_space = array('yes' => 'yes', 'no' => 'no');

        $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Content', 'rentals-elementor'),
                ]
        );

        $this->add_control(
                'full_row',
                [
                    'label' => __('Use without spaces between listings? (If yes, title or link to global listing will not show)', 'rentals-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'no',
                    'options' => $recent_items_space,
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'title',
                [
                    'label' => __('Title', 'rentals-elementor'),
                    'type' => Controls_Manager::TEXT,
                ]
        );

        $this->add_control(
                'type',
                [
                    'label' => __('What type of items', 'rentals-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'properties',
                    'options' => $items_type
                ]
        );

        $this->add_control(
                'blogtype',
                [
                    'label' => __('Select blog unit card', 'rentals-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'condition' => [
                        'type' => 'articles',
                    ],
                    'default' => 2,
                    'options' => $blog_items_type
                ]
        );

        $this->add_control(
                'number',
                [
                    'label' => __('No of items', 'rentals-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => 3,
                ]
        );

        $this->add_control(
                'rownumber',
                [
                    'label' => __('No of items per row', 'rentals-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => 3,
                ]
        );

        $this->add_control(
                'link',
                [
                    'label' => __('Link to global listing', 'rentals-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'Label Block'
                ]
        );

        $this->add_control(
                'random_pick',
                [
                    'label' => __('Random Pick ?', 'rentals-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'no',
                    'options' => $featured_listings
                ]
        );

        $this->add_control(
                'display_grid',
                [
                    'label' => esc_html__('Display as grid ?', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'yes',
                    'default' => esc_html__('No', 'rentals-elementor'),
                    'description'=> esc_html__('There is no fixed number of units. The grid will auto adjust to display units with a minimum width set by the control below.?', 'rentals-elementor'),
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
                'filters_section',
                [
                    'label' => esc_html__('Filters', 'rentals-elementor'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_control(
                'category_ids',
                [
                    'label' => __('List of category names (*only for properties)', 'rentals-elementor'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $wprentals_property_category_values_elementor,
                ]
        );
        $this->add_control(
                'action_ids',
                [
                    'label' => __('List of action names (*only for properties)', 'rentals-elementor'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $wprentals_property_action_category_values_elementor,
                ]
        );
        $this->add_control(
                'city_ids',
                [
                    'label' => __('List of city names (*only for properties)', 'rentals-elementor'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $wprentals_property_city_values_elementor,
                ]
        );

        $this->add_control(
                'area_ids',
                [
                    'label' => __('List of area names (*only for properties)', 'rentals-elementor'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $wprentals_property_area_values_elementor,
                ]
        );

        $this->add_control(
                'show_featured_only',
                [
                    'label' => __('Show featured listings only?', 'rentals-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'no',
                    'options' => $featured_listings
                ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
                'settings_section',
                [
                    'label' => esc_html__('Settings', 'rentals-elementor'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_responsive_control(
                'unit_border_width', [
            'label' => esc_html__('Border Width', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'placeholder' => '1',
            'size_units' => ['px'],
            'selectors' => [
                '{{WRAPPER}} .places_wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};box-shadow:none;border-style: solid;',
            ],
                ]
        );

        $this->add_responsive_control(
                'field_border_radius', [
            'label' => esc_html__('Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .places_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .blog-unit-3 .listing-unit-img-wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .property_unit_v4 .property_listing img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
           
                

            ],
                ]
        );

        $this->add_control(
                'unit_border_color',
                [
                    'label' => esc_html__('Border Color', 'rentals-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .places_wrapper' => 'border-color: {{VALUE}}',
                    ],
                ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    public function wpresidence_send_to_shortcode($input) {
        $output = '';
        if ($input !== '') {
            $numItems = count($input);
            $i = 0;

            foreach ($input as $key => $value) {
                $output .= $value;
                if (++$i !== $numItems) {
                    $output .= ', ';
                }
            }
        }
        return $output;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $attributes['title'] = $settings['title'];
        $attributes['full_row'] = $settings['full_row'];
        $attributes['type'] = $settings['type'];
        $attributes['category_ids'] = $this->wpresidence_send_to_shortcode($settings['category_ids']);
        $attributes['action_ids'] = $this->wpresidence_send_to_shortcode($settings['action_ids']);
        $attributes['city_ids'] = $this->wpresidence_send_to_shortcode($settings['city_ids']);
        $attributes['area_ids'] = $this->wpresidence_send_to_shortcode($settings['area_ids']);
        $attributes['number'] = $settings['number'];
        $attributes['rownumber'] = $settings['rownumber'];
        $attributes['link'] = $settings['link'];
        $attributes['show_featured_only'] = $settings['show_featured_only'];
        $attributes['random_pick'] = $settings['random_pick'];
        $attributes['blogtype'] = $settings['blogtype'];
        $attributes['display_grid']=$settings['display_grid'];
        
        
        echo wpestate_recent_posts_pictures($attributes);
    }

}
