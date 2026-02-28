<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Files\Assets\Svg\Svg_Handler;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Categories_List extends Widget_Base {

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
        return 'Wprentals_Categories_List';
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
        return __('WpRentals Categories List', 'rentals-elementor');
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
        return 'eicon-product-categories';
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
        $all_tax_elemetor = $this->elementor_transform($all_tax);

        $this->start_controls_section(
                'section_content', [
            'label' => __('Content', 'rentals-elementor'),
                ]
        );

        $this->add_control(
                'place_list', [
            'label' => __('Type the category name you want to show', 'rentals-elementor'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $all_tax_elemetor,
                ]
        );

        $this->add_control(
                'place_per_row', [
            'label' => __('Categories per row', 'rentals-elementor'),
            'label_block' => true,
            'default' => 3,
            'type' => Controls_Manager::TEXT,
                ]
        );

        $this->add_control(
                'design_type', [
            'label' => __('Design Style', 'plugin-domain'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'type1',
            'options' => [
                'type1' => __('Type 1', 'rentals-elementor'),
                'type2' => __('Type 2', 'rentals-elementor'),
                'type3' => __('Type 3', 'rentals-elementor'),
            ],
                ]
        );

        $this->add_responsive_control(
                'spaces_unit', [
            'label' => esc_html__('Space between units', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'condition' => [
                'design_type' => array('type1', 'type3')
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => 8,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .places_wrapper_elementor' => ' margin-bottom: {{SIZE}}{{UNIT}};padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
            ],
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
                            'min' => 200,
                            'max' => 500,
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
                        '{{WRAPPER}} .items_shortcode_wrapper_category_grid' => '  grid-template-columns: repeat(auto-fit, minmax({{SIZE}}{{UNIT}}, auto));',
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
                        '{{WRAPPER}} .items_shortcode_wrapper_category_grid' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        
        
        
        
        

        $this->end_controls_section();

        $this->start_controls_section(
                'size_section', [
            'label' => esc_html__('Item Settings', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'item_height', [
            'label' => esc_html__('Item Height', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 50,
                    'max' => 500,
                ],
            ],
            'condition' => [
                'design_type' => array('type1', 'type3')
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => 302,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .places_wrapper' => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .places_height' => 'height: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'item_height_square', [
            'label' => esc_html__('Item Size', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 50,
                    'max' => 500,
                ],
            ],
            'condition' => [
                'design_type' => 'type2',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => 75,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .places_wrapper_design_2_wrapper .places_wrapper_design_2' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'item_margin_bottom', [
            'label' => esc_html__('Item Margin Bottom', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 5,
                    'max' => 100,
                ],
            ],
            'condition' => [
                'design_type' => 'type2',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => 15,
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'default' => [
                'size' => 15,
                'unit' => 'px',
            ],     
            'selectors' => [
                '{{WRAPPER}} .places_wrapper_design_2_wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'item_border_radius', [
            'label' => esc_html__('Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .places_wrapper_design_2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .places_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

        $this->end_controls_section();

        /*
         * -------------------------------------------------------------------------------------------------
         * Start Typografy
         */

        $this->start_controls_section(
                'typography_section', [
            'label' => esc_html__('Style', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'tax_title',
            'label' => esc_html__('Title Typography', 'rentals-elementor'),
             'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
             ],
            'selector' => '{{WRAPPER}} .featured_listing_title',
            'fields_options' => [
                // Inner control name
                'font_weight' => [
                    // Inner control settings
                    'default' => '500',
                ],
                'font_family' => [
                    'default' => 'Roboto',
                ],
                'font_size' => ['default' => ['unit' => 'px', 'size' => 24]],
            ],
                ]
        );
        $this->add_responsive_control(
                'property_title_margin_bottom', [
            'label' => esc_html__('Title Margin Bottom(px)', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .featured_listing_title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->add_responsive_control(
                'property_tagline_margin_bottom', [
            'label' => esc_html__('Tagline Margin Bottom(px)', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'condition' => [
                'design_type' => 'type1',
            ],
            'devices' => ['desktop', 'tablet', 'mobile'],
            'desktop_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'tablet_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'mobile_default' => [
                'size' => '',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .category_tagline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'tax_listings',
            'label' => esc_html__('Listings Text Typography', 'rentals-elementor'),
             'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
             ],
            'selector' => '{{WRAPPER}} .place_counter,{{WRAPPER}} .category_tagline',
            'fields_options' => [
                // Inner control name
                'font_weight' => [
                    // Inner control settings
                    'default' => '300',
                ],
                'font_family' => [
                    'default' => 'Roboto',
                ],
                'font_size' => ['default' => ['unit' => 'px', 'size' => 14]],
            ],
                ]
        );

        $this->add_control(
                'tax_title_color', [
            'label' => esc_html__('Title Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .featured_listing_title' => 'color: {{VALUE}}!important',
            ],
                ]
        );

        $this->add_control(
                'tax_tagline_color', [
            'label' => esc_html__('Subtitle Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'condition' => [
                'design_type' => 'type1',
            ],
            'selectors' => [
                '{{WRAPPER}}  .place_counter' => 'color: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'tax_listings_color', [
            'label' => esc_html__('Listings Text Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}}  .category_tagline' => 'color: {{VALUE}}',
                '{{WRAPPER}}  .place_counter' => 'color: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'tax_listings_color_back', [
            'label' => esc_html__('Listings Backgorund Color - Design Type2', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'condition' => [
                'place_type' => '2',
            ],
            'selectors' => [
                '{{WRAPPER}}  .places_type_2_listings_no' => 'background: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'ovarlay_color_back', [
            'label' => esc_html__('Image Overlay Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .listing-hover' => 'background: {{VALUE}};opacity: 1;',
            ],
                ]
        );

        $this->add_control(
                'ovarlay_color_back_hover', [
            'label' => esc_html__('Image Overlay Background Color Hover', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}} .listing-hover:hover' => 'background: {{VALUE}};opacity: 1;',
            ],
                ]
        );

        $this->end_controls_section();

        /* -------------------------------------------------------------------------------------------------
         * Start shadow section
         */
        $this->start_controls_section(
                'section_grid_box_shadow', [
            'label' => esc_html__('Box Shadow', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(), [
            'name' => 'box_shadow',
            'label' => esc_html__('Box Shadow', 'rentals-elementor'),
            'selector' => '{{WRAPPER}} .places_wrapper_design_2,{{WRAPPER}} .places_wrapper',
                ]
        );

        $this->end_controls_section();
        /*
         * -------------------------------------------------------------------------------------------------
         * End shadow section
         */
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

        $attributes['place_list'] = $this->wpresidence_send_to_shortcode($settings['place_list']);
        $attributes['place_per_row'] = $settings['place_per_row'];
        $attributes['spaces_unit'] = isset($settings['spaces_unit']) ? $settings['spaces_unit'] : '';
        $attributes['design_type'] = $settings['design_type'];
        $attributes['display_grid']=$settings['display_grid'];
        echo wpestate_places_list_function($attributes);
    }

}
