<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Files\Assets\Svg\Svg_Handler;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class Wprentals_Recent_Items_Top_Bar_v1 extends Widget_Base {

    public function __construct($data = [], $args = null) {
            parent::__construct($data, $args);

            wp_register_script( 'wprentals_top_bar_widget_control_elementor', trailingslashit( get_template_directory_uri() ).'js/wprentals_top_bar_widget_control_elementor.js', [ 'elementor-frontend' ], '1.0.0', true );
    }
        
        
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
        return 'Wprentals_Recent_Items_Top_Bar_v1';
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
        return __('WpRentals Recent Listings with Filters', 'rentals-elementor');
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
        return ['wprentals_top_bar_widget_control_elementor'];
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
    
    protected function wpestate_get_terms(){
        $parms =array(
                    'hide_empty'=>false,
                    'taxonomy'  =>array(
                                    'property_category',
                                    'property_action_category',
                                    'property_city',
                                    'property_area',
                                    'property_features',
                                    'property_status'),
                            
        );
        $terms = get_terms(
                 $parms
        );
        
        $return_array=array();
        foreach($terms as $term){
            $return_array[$term->term_id]=$term->name;
        }
        

        return $return_array;

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





        
        $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Filters', 'rentals-elementor'),
                ]
        ); 
         
        $repeater = new Repeater();

        $repeater->add_control(
                'field_type', [
            'label' => esc_html__('Category Terms', 'rentals-elementor'),
            'type' => Controls_Manager::SELECT,
            'options' => $this->wpestate_get_terms(),
            'default' => '',
                ]
        );
        
    
        
        $repeater->add_control(
                'field_label', [
            'label' => esc_html__('Form Fields Label', 'rentals-elementor'),
            'type' => Controls_Manager::TEXT,
            'default' => '',
                ]
        );
           $repeater->add_control(
             'include_terms', [
            'label' => __('Load listings from this category in the inital display (all listings load if none is selected )', 'rentals-elementor'),
            'label_block'=>false,
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'rentals-elementor'),
            'label_off' => __('no', 'rentals-elementor'),
            'return_value' => 'yes',
            'default' => 'no',
                ]
        );

            
           
        $repeater->add_control(
                'icon',
                [
                        'label' => __( 'Icon', 'text-domain' ),
                        'type' => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                                'value' => 'fas fa-star',
                                'library' => 'solid',
                        ],
                ]
        );
            
        
     
          $this->add_control(
                'form_fields', [
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    '_id' => 'name',
                    'field_type' => 'property_category',
                    'field_label' => esc_html__('Categories', 'rentals-elementor'),
                   
                ],
             
            ],
            'title_field' => '{{{ field_label }}}',
                ]
        );
 
        $this->end_controls_section();
        
        
        
        $this->start_controls_section(
                'section_content1',
                [
                    'label' => __('Content', 'rentals-elementor'),
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

    

  
 
      

        /*
         * -------------------------------------------------------------------------------------------------
         * Start Hide sections
         */
        $this->start_controls_section(
                'hide_show_section',
                [
                    'label' => esc_html__('Show/Hide Data', 'rentals-elementor'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );

        $this->add_control(
                'hide_compare',
                [
                    'label' => esc_html__('Hide Owner Thumb', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .owner_thumb' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hide_favorites',
                [
                    'label' => esc_html__('Hide Add to Favorites', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .property_unit_action' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hide_featured',
                [
                    'label' => esc_html__('Hide Featured Label', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .featured_div' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hide_title',
                [
                    'label' => esc_html__('Hide Title', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .category_name .listing_title_unit' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hide_location',
                [
                    'label' => esc_html__('Hide Location', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .category_tagline.map_icon' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hide_categories',
                [
                    'label' => esc_html__('Hide Categories', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .category_tagline.actions_icon' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hide_price',
                [
                    'label' => esc_html__('Hide Price', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .price_unit' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'hide_review',
                [
                    'label' => esc_html__('Hide Review', 'rentals-elementor'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'rentals-elementor'),
                    'label_off' => esc_html__('No', 'rentals-elementor'),
                    'return_value' => 'none',
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}}  .property-rating' => 'display: {{VALUE}};',
                    ],
                ]
        );

        $this->end_controls_section();

        /*
         * -------------------------------------------------------------------------------------------------
         * End hide section
         */

        
        
        
        $this->start_controls_section(
                'tab_items_section', [
            'label' => esc_html__('Filters Settings', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
         
        $this->add_responsive_control(
                'align', [
            'label' => __('Alignment', 'rentals-elementor'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'left' => [
                    'title' => __('Left', 'rentals-elementor'),
                    'icon' => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => __('Center', 'rentals-elementor'),
                    'icon' => 'eicon-text-align-center',
                ],
                'right' => [
                    'title' => __('Right', 'rentals-elementor'),
                    'icon' => 'eicon-text-align-right',
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .wpestate_top_bar_control_widget' => '    justify-content: {{VALUE}};',
            ],
                ]
        );
           


         $this->add_responsive_control(
            'form_wrapper-content_padding', [
            'label' => esc_html__('Top  item Content Padding ', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
           
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item>a ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
         
           $this->add_responsive_control(
            'tab_item_margin', [
            'label' => esc_html__('Tab item Margin ', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
           
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );

         $this->add_responsive_control(
                'tab_item_border_radius', [
            'label' => esc_html__('Tab Item Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item>a ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
  
            ],
                ]
        );

         
        $this->add_control(
                'tab_item_back_color', [
            'label' => esc_html__('Tab Item Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item>a' => 'background-color: {{VALUE}};',
            ],
           
                ]
        );


  
        $this->add_control(
                'tab_item_font_color', [
            'label' => esc_html__('Tab Item Font Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item>a' => 'color: {{VALUE}};',
            ],
            
                ]
        );

    $this->add_control(
                'tab_item_back_selected_color', [
            'label' => esc_html__('Tab Item Active Background Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item.active>a' => 'background-color: {{VALUE}};',
            ],
            
                ]
        );


  
        $this->add_control(
                'tab_item_active_font_color', [
            'label' => esc_html__('Active Tab Item Font Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item.active>a' => 'color: {{VALUE}};',
            ],
            
                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
            'name' => 'tab_item_typo',
            'label' => esc_html__('Tab Item Typography', 'rentals-elementor'),
             'global' => [
                        'default' => Global_Typography::TYPOGRAPHY_TEXT
             ],
            'selector' => '{{WRAPPER}}  .nav-tabs>li.wpestate_categories_as_tabs_item>a' ,
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
            'tab_item_icon_margin', [
            'label' => esc_html__('Tab item Icon Margin ', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
        
            'selectors' => [
                '{{WRAPPER}}    .nav-tabs>li.wpestate_categories_as_tabs_item i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}}    .nav-tabs>li.wpestate_categories_as_tabs_item svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
               
               
        $this->add_control(
                'tab_item_icon_font_color', [
            'label' => esc_html__('Tab Item Icon Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item i '   => 'color: {{VALUE}};',
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item svg ' => 'fill: {{VALUE}};',
            ],
            
                ]
        );
        
        
        
   

              
              
        $this->add_control(
                'tab_item_icon_active_font_color', [
            'label' => esc_html__('Tab Item Active Icon Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item.active i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item.active svg' => 'fill: {{VALUE}};',
            ],
            
                ]
        );
        
        
        $this->add_responsive_control(
            'item_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'rentals-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
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
              
                    '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .nav-tabs>li.wpestate_categories_as_tabs_item svg' => 'height: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
            
        
        $this->add_control(
             'icon_position', [
            'label' => __('Put Icon above label', 'rentals-elementor'),
            'label_block'=>false,
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'rentals-elementor'),
            'label_off' => __('no', 'rentals-elementor'),
           'return_value' => 'none',
                    'default' => '',
                  'selectors' => [
                        '{{WRAPPER}}  .wpestate_categories_as_tabs_item>a' => 'flex-direction: column;',
                    ],
                ]
        );
        
       
            

        $this->end_controls_section();


              
        
        $this->start_controls_section(
                'unit_items_section', [
            'label' => esc_html__('Unit Settings', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
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

    protected function render() {
        $settings = $this->get_settings_for_display();

        $attributes['full_row'] = 'no';
        $attributes['type'] = 'properties';   
        $attributes['number'] = $settings['number'];
        $attributes['rownumber'] = $settings['rownumber'];
        $attributes['unit_type'] = 1;
        $attributes['form_fields'] = $settings['form_fields'];
               
        $attributes['display_grid']=$settings['display_grid'];
        
        echo wpestate_recent_items_top_bar_1($attributes);
    }

}
