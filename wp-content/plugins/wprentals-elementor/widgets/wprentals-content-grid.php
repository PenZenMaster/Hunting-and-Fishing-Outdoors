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

class Wprentals_Content_grid extends Widget_Base {

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
        return 'Wprentals_Content_grid';
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
        return __('WpRentals Content Grid', 'rentals-elementor');
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

        $this->start_controls_section(
                'content_section',
                [
                    'label' => esc_html__('Content', 'rentals-elementor'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

      




        $this->add_control(
                'item_ids',
                [
                    'label' => __('Items IDs', 'rentals-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'Label Block'
                ]
        );

        $this->end_controls_section();

        /*
         * -------------------------------------------------------------------------------------------------
         * Start Sizes
         */

        $this->start_controls_section(
                'size_section',
                [
                    'label' => esc_html__('Item Settings', 'rentals-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
                'item_col_gap',
                [
                    'label' => esc_html__('Space Between Columns', 'rentals-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
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
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_content_grid_wrapper' => 'gap: {{SIZE}}{{UNIT}};'
                    ],
                ]
        );

        
          $this->add_responsive_control(
                'item_col_row',
                [
                    'label' => esc_html__('Space Between Rows', 'rentals-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
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
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_content_grid_wrapper_second_col' => 'gap: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        
        
        
        
        $this->add_responsive_control(
                'item_border_radius',
                [
                    'label' => esc_html__('Image Border Radius', 'rentals-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .property_listing'             => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                      ],
                ]
        );

       

        $this->end_controls_section();
        
            $this->start_controls_section(
              'left_section',
              [
                  'label' => esc_html__('Item Settings', 'rentals-elementor'),
                  'tab' => Controls_Manager::TAB_STYLE,
              ]
            );
         
            
        $this->add_responsive_control(
            'left-elements-padding', [
            'label' => esc_html__('Internal Padding ', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
           
            'selectors' => [
                '{{WRAPPER}}    .wpestate_content_grid_wrapper_second_col_item_wrapper ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            ]
         );
           $this->add_control(
             'left-elements_backcolor', [
            'label' => esc_html__('Item Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wpestate_content_grid_wrapper_second_col_item_wrapper' => 'background-color: {{VALUE}};',
            ],
           
                ]
        );

              
                
        $this->add_responsive_control(
                'leftitem_border_radius',
                [
                    'label' => esc_html__('Item Border Radius', 'rentals-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .wpestate_content_grid_wrapper_second_col_item_wrapper'             => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                      ],
                ]
        );
           
           
            $this->end_controls_section();   

        /*
         * -------------------------------------------------------------------------------------------------
         * Start Typografy
         */

        $this->start_controls_section(
                'typography_section',
                [
                    'label' => esc_html__('Font Styles', 'rentals-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'main_item_title',
                    'label' => esc_html__('Main Item Title Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .wpestate_content_grid_wrapper_first_col  h4 a,{{WRAPPER}} .wpestate_content_grid_wrapper_first_col  h4',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 18]],
                    ],
                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'second_item_title',
                    'label' => esc_html__('Secondary Items Title Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .property_unit_content_grid_small_details  h4 a,{{WRAPPER}} .property_unit_content_grid_small_details  h4',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 18]],
                    ],
                ]
        );
         
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'main_item_price',
                    'label' => esc_html__('Main Item Price Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .property_unit_content_grid_big_details .listing_unit_price_wrapper,{{WRAPPER}} .property_unit_content_grid_big_details .price_label',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 18]],
                    ],
                ]
        );
          
          
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'second_item_price',
                    'label' => esc_html__('Secondary Items Price Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .property_unit_content_grid_small_details .listing_unit_price_wrapper,{{WRAPPER}} .property_unit_content_grid_small_details .price_label',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 18]],
                    ],
                ]
        );
        
        
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'main_item_address',
                    'label' => esc_html__('Main Item Address Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .property_unit_content_grid_big_details_location,{{WRAPPER}} .property_unit_content_grid_big_details_location a',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 18]],
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'secondary_item_address',
                    'label' => esc_html__('Secondary Item Address/Details Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .property_unit_content_grid_small_details_location,{{WRAPPER}} .property_unit_content_grid_small_details_location a',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 18]],
                    ],
                ]
        );
        
         $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'main_item_meta',
                    'label' => esc_html__('Main Item Date Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .property_unit_content_grid_big_details .blog_unit_meta',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 13]],
                    ],
                ]
        );
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'secondary_item_meta',
                    'label' => esc_html__('Secondary Item Date Typography', 'rentals-elementor'),
                     'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
                     ],
                    'selector' => '{{WRAPPER}} .property_unit_content_grid_small_details .blog_unit_meta',
                    'fields_options' => [
                        // Inner control name
                        'font_weight' => [
                            // Inner control settings
                            'default' => '500',
                        ],
                        'font_family' => [
                            'default' => 'Roboto',
                        ],
                        'font_size' => ['default' => ['unit' => 'px', 'size' => 13]],
                    ],
                ]
        );
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        $this->add_responsive_control(
                'property_title_margin_bottom',
                [
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
                        '{{WRAPPER}} .wpestate_content_grid_wrapper_first_col h4' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_responsive_control(
                'property_title_seco_margin_bottom',
                [
                    'label' => esc_html__('Secondary Item Title Margin Bottom', 'rentals-elementor'),
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
                        '{{WRAPPER}} .wpestate_content_grid_wrapper_second_col_item_wrapper h4' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                    'selector' => '{{WRAPPER}} .property_listing'
                ]
        );
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
    public function wpestate_drop_posts($post_type) {
        $args = array(
            'numberposts' => -1,
            'post_type' => $post_type
        );

        $posts = get_posts($args);
        $list = array();
        foreach ($posts as $cpost) {

            $list[$cpost->ID] = $cpost->post_title;
        }
        return $list;
    }

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
        $args['item_ids'] = $settings['item_ids'];

        echo wprentals_content_grids($args);
    }
}
