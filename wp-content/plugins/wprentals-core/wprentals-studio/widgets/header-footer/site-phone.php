<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Site_Phone extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Site_Phone';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Contact Phone', 'wprentals-core');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'wprentals-note eicon-site-logo';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return ['wprentals_header'];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'wprentals-core'),
            ]
        );
        
        $this->add_control(
            's_icon_button',
            [
                'label' => __('Icon', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::ICONS,
            ]
        );
        
        $this->add_control(
            'phone_text',
            [
                'label' => __('Phone Number', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
        
        $this->add_control(
            'phone_text_label',
            [
                'label' => __('Phone Number Label', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        
        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => esc_html__('Typography', 'wprentals-core'),
                'selector' => '{{WRAPPER}} .header_phone a',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
            ]
        );
        
        // Text Color
        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .header_phone a' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        // Text Hover Color
        $this->add_control(
            'text_hover_color',
            [
                'label' => esc_html__('Text Hover Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .header_phone a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        // Icon Size
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16,
                ],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .header_phone i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .header_phone svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        // Icon Color
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .header_phone i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .header_phone svg' => 'fill: {{VALUE}}',
                ],
            ]
        );
        
        // Icon Hover Color
        $this->add_control(
            'icon_hover_color',
            [
                'label' => esc_html__('Icon Hover Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .header_phone:hover i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .header_phone:hover svg' => 'fill: {{VALUE}}',
                ],
            ]
        );
        
        // Gap Between Elements
        $this->add_responsive_control(
            'elements_gap',
            [
                'label' => esc_html__('Gap Between Elements', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 8,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .header_phone' => 'display: flex; align-items: center; gap: {{SIZE}}{{UNIT}};',
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
        $settings           = $this->get_settings_for_display();
        $phone_no           = $settings['phone_text'];
        $phone_text_label   = $settings['phone_text_label'];
        $icon               = $settings['s_icon_button'];
        
        print '<div class="header_phone wprentals-studio-header_phone">';
        if (!empty($icon['value'])) {
            \Elementor\Icons_Manager::render_icon($icon, ['aria-hidden' => 'true']);
        } else {
            // Display default SVG if no icon is set
            print '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision;text-rendering:geometricPrecision;image-rendering:optimizeQuality;" viewBox="0 0 295.64 369.5375" x="0px" y="0px" fill-rule="evenodd" clip-rule="evenodd"><defs></defs><g><path class="fil0" d="M231.99 189.12c18.12,10.07 36.25,20.14 54.37,30.21 7.8,4.33 11.22,13.52 8.15,21.9 -15.59,42.59 -61.25,65.07 -104.21,49.39 -87.97,-32.11 -153.18,-97.32 -185.29,-185.29 -15.68,-42.96 6.8,-88.62 49.39,-104.21 8.38,-3.07 17.57,0.35 21.91,8.15 10.06,18.12 20.13,36.25 30.2,54.37 4.72,8.5 3.61,18.59 -2.85,25.85 -8.46,9.52 -16.92,19.04 -25.38,28.55 18.06,43.98 55.33,81.25 99.31,99.31 9.51,-8.46 19.03,-16.92 28.55,-25.38 7.27,-6.46 17.35,-7.57 25.85,-2.85z"/></g></svg>';
        }
        
        print '<a href="tel:' . esc_attr($phone_no) . '">' . esc_html($phone_text_label) . '</a>';
        print '</div>';
    }
}