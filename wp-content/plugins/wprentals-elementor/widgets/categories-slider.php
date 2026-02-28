<?php

namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;


if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Categories_Slider extends Widget_Base {

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
        return 'Wprentals_Categories_Slider';
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
        return __('WpRentals Categories Slider', 'rentals-elementor');
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

        $all_tax_elementor = $this->elementor_transform($all_tax);

        $this->start_controls_section(
                'section_content',
                [
                    'label' => __('Content', 'rentals-elementor'),
                ]
        );

        $this->add_control(
                'place_list',
                [
                    'label' => __('Type Categories, Actions, Cities or Areas (Neighborhoods) you want to show', 'rentals-elementor'),
                    'label_block' => true,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $all_tax_elementor,
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
                //  'type2' => __('Type 2', 'rentals-elementor'),
                'type3' => __('Type 3', 'rentals-elementor'),
              //  'type4' => __('Type 4', 'rentals-elementor'),
            ],
                ]
        );

        $this->add_control(
                'place_per_row',
                [
                    'label' => __('Items per row', 'rentals-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => 4
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
                'design_type' => array('type3')
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

        $this->end_controls_section();

        $this->start_controls_section(
                'size_section', [
            'label' => esc_html__('Item Settings', 'rentals-elementor'),
            'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'rentals-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .places_wrapper, {{WRAPPER}} .estate_places_slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .estate_places_slider .places_slider_wrapper_type_1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .places_slider_wrapper_type_1 .places_cover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'selector' => '{{WRAPPER}} .places_slider_wrapper_type_1 h4 a,{{WRAPPER}} .featured_listing_title',
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
                '{{WRAPPER}} .places_slider_wrapper_type_1 h4' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .category_name'            => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                '{{WRAPPER}} .places_slider_type_1_tagline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
            'selector' => '{{WRAPPER}} .places_slider_type_1_listings_no,{{WRAPPER}} .place_counter',
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
                '{{WRAPPER}} .featured_listing_title,{{WRAPPER}} .places_slider_wrapper_type_1 h4 a' => 'color: {{VALUE}}!important',
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
                '{{WRAPPER}}  .places_slider_type_1_tagline' => 'color: {{VALUE}}',
            ],
                ]
        );

        $this->add_control(
                'tax_listings_color', [
            'label' => esc_html__('Listings Text Color', 'rentals-elementor'),
            'type' => Controls_Manager::COLOR,
            'default' => '',
            'selectors' => [
                '{{WRAPPER}}  .places_slider_type_1_listings_no' => 'color: {{VALUE}}',
                '{{WRAPPER}}  .place_counter' => 'color: {{VALUE}}',
            ],
                ]
        );

       $this->add_responsive_control(
            'slider_arrow_position', [
            'label' => esc_html__('Arrow position - up or down)', 'rentals-elementor'),
            'type' => Controls_Manager::SLIDER,
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 350,
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
                '{{WRAPPER}} .slick-arrow' => 'top: {{SIZE}}{{UNIT}};',
            ],
                ]
        );

        $this->end_controls_section();

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
            'selector' => '{{WRAPPER}} .estate_places_slider',
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

        $attributes['place_list'] = $this->wpresidence_send_to_shortcode($settings['place_list']);
        $attributes['place_per_row'] = $settings['place_per_row'];
        $attributes['design_type'] = $settings['design_type'];

        echo wpestate_places_slider($attributes);

        if (\Elementor\Plugin::instance()->editor->is_edit_mode()) :
            ?>
            <script>

                jQuery('.estate_places_slider').each(function () {
                    var items = jQuery(this).attr('data-items-per-row');
                    var auto = parseInt(jQuery(this).attr('data-auto'));
                    var slick = jQuery(this).slick({
                        infinite: true,
                        slidesToShow: items,
                        slidesToScroll: 1,
                        dots: false,

                        responsive: [
                            {
                                breakpoint: 1025,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 1
                                }
                            },
                            {
                                breakpoint: 480,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    });
                    if (control_vars.is_rtl === '1') {
                        jQuery(this).slick('slickSetOption', 'rtl', true, true);
                        jQuery(this).slick('slidesToScroll', '-1');
                    }
                });
            </script>
            <?php

        endif;
    }

}
