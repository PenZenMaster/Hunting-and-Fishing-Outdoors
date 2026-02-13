<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Properties_Slider extends Widget_Base {

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
        return 'Wprentals_Properties_Slider';
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
        return __('WpRentals Property Slider', 'rentals-elementor');
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
        return 'eicon-slider-album';
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
        return ['owl_carousel'];
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
    public function elementor_transform($input){
        $output=array();
        if( is_array($input) ){
            foreach ($input as $key=>$tax){
                $output[$tax['value']]=$tax['label'];
            }
        }
        return $output;
    }

    /*
     * 
     * 
     * 
     **/
    
    protected function get_wprentals_property_taxonomy_terms($taxonomy){
        $wprentals_property_terms_values=array();
    
        $terms_category = get_terms( array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
        ) );

        foreach($terms_category as $term){         
            $wprentals_property_terms_values[$term->term_id]     =   $term->name;
        }
        
        return $wprentals_property_terms_values;

    }
    
    /*
     * 
     * 
     * 
     **/
    
    protected function register_controls() {

        global $all_tax;

        $all_tax_elemetor = $this->elementor_transform($all_tax);

        $featured_listings = array('no' => 'no', 'yes' => 'yes');
        

        global $wprentals_property_category_values;
        global $wprentals_property_action_category_values;
        global $wprentals_property_city_values;
        global $wprentals_property_area_values;

        
        
        $wprentals_property_features_values=$this->get_wprentals_property_taxonomy_terms('property_features');
        $wprentals_property_status_values=$this->get_wprentals_property_taxonomy_terms('property_status');
        
        $wprentals_property_category_values_elementor           =   $this->elementor_transform($wprentals_property_category_values);
        $wprentals_property_action_category_values_elementor    =   $this->elementor_transform($wprentals_property_action_category_values);
        $wprentals_property_city_values_elementor               =   $this->elementor_transform($wprentals_property_city_values);
        $wprentals_property_area_values_elementor               =   $this->elementor_transform($wprentals_property_area_values);
        
        
        
        /*
         * Start filters
         */
        $this->start_controls_section(
                'filters_section', [
            'label' => esc_html__('Filters', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_CONTENT,
                ]
        );







        $this->add_control(
                'category_ids', [
            'label' => __('List of categories ', 'rentals-elementor'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $wprentals_property_category_values_elementor,
            'default' => '',
                ]
        );

        $this->add_control(
                'action_ids', [
            'label' => __('List of action categories ', 'rentals-elementor'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $wprentals_property_action_category_values_elementor,
            'default' => '',
                ]
        );

        $this->add_control(
                'city_ids', [
            'label' => __('List of city  ', 'rentals-elementor'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $wprentals_property_city_values_elementor,
            'default' => '',
                ]
        );
        $this->add_control(
                'area_ids', [
            'label' => __('List of areas ', 'rentals-elementor'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $wprentals_property_area_values_elementor,
            'default' => '',
                ]
        );
       

        $this->add_control(
                'status_ids', [
            'label' => __('List of Property Status ', 'rentals-elementor'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $wprentals_property_status_values,
            'default' => '',
                ]
        );


        $this->add_control(
                'features_ids', [
            'label' => __('List of Property Features ', 'rentals-elementor'),
            'label_block' => true,
            'type' => \Elementor\Controls_Manager::SELECT2,
            'multiple' => true,
            'options' => $wprentals_property_features_values,
            'default' => '',
                ]
        );

        $this->add_control(
            'show_featured_only', [
            'label' => __('Show featured listings only?', 'rentals-elementor'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'no',
            'options' => $featured_listings
                ]
        );

        $this->add_control(
            'propertyid', [
            'label' => __('Items IDs - will override the above filters', 'rentals-elementor'),
            'label_block' => true,
            'type' => Controls_Manager::TEXT,
            'Label Block'
                ]
        );

        $this->add_control(
                'number',
                [
                    'label' => __( 'No of items', 'rentals-elementor' ),
                    'type' => Controls_Manager::TEXT,
                ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
                'size_section', [
            'label' => esc_html__('Style', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        


        $this->add_group_control(
                Group_Control_Typography::get_type(), [
                    'name' => 'wprentals_field_typography_title',
                    'label' => esc_html__('Title Typography', 'rentals-elementor'),
                    'selector' => '{{WRAPPER}} .wpestate_properties_slider_v1_title a',
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );

          
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
                    'name' => 'wprentals_field_typography_price',
                    'label' => esc_html__('Price Typography', 'rentals-elementor'),
                    'selector' => '{{WRAPPER}} .price_unit,{{WRAPPER}} .pernight ',
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );
         $this->add_group_control(
                Group_Control_Typography::get_type(), [
                    'name' => 'wprentals_field_typography_location',
                    'label' => esc_html__('Location Typography', 'rentals-elementor'),
                    'selector' => '{{WRAPPER}} .wpestate_properties_slider_v1_location a,{{WRAPPER}} .wpestate_properties_slider_v1_location',
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );
            

        $this->add_group_control(
                Group_Control_Typography::get_type(), [
                    'name' => 'wprentals_field_typography_content',
                    'label' => esc_html__('Content Typography', 'rentals-elementor'),
                    'selector' => '{{WRAPPER}} .wpestate_properties_slider_v1_text',
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
                    'name' => 'wpestate_properties_slider_v1_single_details',
                    'label' => esc_html__('Content Typography', 'rentals-elementor'),
                    'selector' => '{{WRAPPER}} .wpestate_properties_slider_v1_single_details',
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );
            
            
        $this->add_group_control(
                Group_Control_Typography::get_type(), [
                    'name' => 'wprentals_field_typography_agent',
                    'label' => esc_html__('Owner name Font Size', 'rentals-elementor'),
                    'selector' => '{{WRAPPER}} .owner_name',
                    'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
                ]
        );
            
          
        $this->add_responsive_control(
                'image_border_radius', [
            'label' => __('Border Radius', 'rentals-elementor'),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors' => [
                '{{WRAPPER}} .wpestate_properties_slider_v1 .item'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .wpestate_properties_slider_v1'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
                ]
        );
           
        
        $this->add_responsive_control(
            'icon size',
            [
                'label' => esc_html__( 'Icon Size', 'rentals-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 50,
                    ],
                ],
                'desktop_default' => [
                    'size' => '20',
                    'unit' => 'px',
                ],
               
                'selectors' => [
                    '{{WRAPPER}}  svg' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
          $this->add_responsive_control(
            'owner_size',
            [
                'label' => esc_html__( 'Owner Image Size', 'rentals-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 150,
                    ],
                ],
                'desktop_default' => [
                    'size' => '60',
                    'unit' => 'px',
                ],
               
                'selectors' => [
                    '{{WRAPPER}}  .owner_thumb' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    
        $this->end_controls_section();
      
        
        $this->start_controls_section(
                'color_section', [
                'label' => esc_html__('Color', 'rentals-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        
         $this->add_control(
            'background_color',
            [
                'label'     => esc_html__( 'Background Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpestate_properties_slider_v1_content' => 'background-color: {{VALUE}}',

                ],
            ]
        );

        
            
        
        $this->add_control(
            'title_font_color',
            [
                'label'     => esc_html__( 'Title Font Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpestate_properties_slider_v1_title a' => 'color: {{VALUE}}',

                ],
            ]
        );

                

        $this->add_control(
            'price_font_color',
            [
                'label'     => esc_html__( 'Price Font Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .price_unit, {{WRAPPER}}  .pernight' => 'color: {{VALUE}}',

                ],
            ]
        );

        
        $this->add_control(
            'contentx_font_color',
            [
                'label'     => esc_html__( 'Content Font Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpestate_properties_slider_v1_text' => 'color: {{VALUE}}',

                ],
            ]
        );

        
        $this->add_control(
            'wprentals_field_color_location',
            [
                'label'     => esc_html__( 'Location Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpestate_properties_slider_v1_location a,{{WRAPPER}} .wpestate_properties_slider_v1_location' => 'color: {{VALUE}}',

                ],
            ]
        );
          
         
        

        $this->add_control(
            'agent_font_color',
            [
                'label'     => esc_html__( 'Details Font Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpestate_properties_slider_v1_single_details,{{WRAPPER}} .wpestate_properties_slider_v1_single_details a' => 'color: {{VALUE}}',

                ],
            ]
        );

         $this->add_control(
            'icon_font_color',
            [
                'label'     => esc_html__( 'Icon  Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} svg ,{{WRAPPER}} path' => 'fill: {{VALUE}}!important',

                ],
            ]
        );

        $this->add_control(
            'wprentals_field_color_owner_name',
            [
                'label'     => esc_html__( 'Owner Name Color', 'rentals-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .owner_name a,{{WRAPPER}} .owner_name' => 'color: {{VALUE}}',

                ],
            ]
        );     
         
       

               
      

        $this->end_controls_section();
        
        
        
      


         $this->start_controls_section(
                'arrow_section', [
            'label' => esc_html__('Arrows Style', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        
           $this->add_responsive_control(
            'arrow_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'rentals-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-prev.slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-next.slick-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  ],
            ]
        );
        
            $this->add_control(
                   'arrow_color',
                   [
                       'label'     => esc_html__( 'Arrow Color', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                           '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-prev.slick-arrow:before' => 'color: {{VALUE}}',
                           '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-next.slick-arrow:before' => 'color: {{VALUE}}',
                       ],
                   ]
            );
            
              $this->add_control(
                   'arrow_bck_color',
                   [
                       'label'     => esc_html__( 'Arrow Background Color', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                           '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-prev.slick-arrow' => 'background-color: {{VALUE}}',
                           '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-next.slick-arrow' => 'background-color: {{VALUE}}',
                       ],
                   ]
            );
           
           
            
            $this->add_control(
                   'arrow_color_hover',
                   [
                       'label'     => esc_html__( 'Arrow Color Hover', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-prev.slick-arrow:hover:before' => 'color: {{VALUE}}',
                            '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-next.slick-arrow:hover:before' => 'color: {{VALUE}}',
                       ],
                   ]
               );
            
             $this->add_control(
                   'arrow_bck_color_hover',
                   [
                       'label'     => esc_html__( 'Arrow Background Color Hover', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-prev.slick-arrow:hover' => 'background-color: {{VALUE}}',
                            '{{WRAPPER}} .wpestate_properties_slider_v1 button.slick-next.slick-arrow:hover' => 'background-color: {{VALUE}}',
                       ],
                   ]
               );

             
               $this->add_control(
                   'bullet_color',
                   [
                       'label'     => esc_html__( 'Dot Color', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_properties_slider_v1 .slick-dots li ' => 'background-color: {{VALUE}}',
                        
                       ],
                   ]
               );

               
                 $this->add_control(
                   'bullet_color_active',
                   [
                       'label'     => esc_html__( 'Dot Color Active', 'rentals-elementor' ),
                       'type'      => Controls_Manager::COLOR,
                       'default'   => '',
                       'selectors' => [
                            '{{WRAPPER}} .wpestate_properties_slider_v1 .slick-dots li.slick-active ' => 'background-color: {{VALUE}}',

                       ],
                   ]
               );


           $this->end_controls_section();
        
        
          /*
              *-------------------------------------------------------------------------------------------------
              * Start shadow section
              */
              $this->start_controls_section(
                'section_grid_box_shadow',
                [
                    'label' => esc_html__( 'Box Shadow', 'rentals-elementor'),
                    'tab'   => Controls_Manager::TAB_STYLE,
                ]
                );
                $this->add_group_control(
                    Group_Control_Box_Shadow::get_type(),
                    [
                        'name'     => 'box_shadow',
                        'label'    => esc_html__( 'Box Shadow', 'rentals-elementor'),
                        'selector' => '{{WRAPPER}} .wpestate_properties_slider_v1',
                    ]
                );

                $this->end_controls_section();
              /*
              *-------------------------------------------------------------------------------------------------
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
     public function wprentals_send_to_shortcode($input) {
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
    
        $settings                           =   $this->get_settings_for_display();
        $attributes['propertyid']           =   $settings['propertyid'];
        $attributes['category_ids']         =   $this->wprentals_send_to_shortcode($settings['category_ids']);
        $attributes['action_ids']           =   $this->wprentals_send_to_shortcode($settings['action_ids']);
        $attributes['city_ids']             =   $this->wprentals_send_to_shortcode($settings['city_ids']);
        $attributes['area_ids']             =   $this->wprentals_send_to_shortcode($settings['area_ids']);
        $attributes['status_ids']           =   $this->wprentals_send_to_shortcode($settings['status_ids']);
        $attributes['features_ids']         =   $this->wprentals_send_to_shortcode($settings['features_ids']);

        $attributes['show_featured_only']   =   $settings['show_featured_only'];
        $attributes['number']               =   $settings['number'];
        $slider_id      =   'property_slider_carousel_elementor_v1_'.rand(1,99999);
        $slider_data    =   wpestate_slider_properties_v1($attributes,$slider_id);
        
        print trim($slider_data['return']);

        print '
            <script type="text/javascript">
                //<![CDATA[
                jQuery(document).ready(function(){
                   wpestate_testimonial_slider("'.$slider_id.'");
                });
                //]]>
            </script>';
        }
  

}
