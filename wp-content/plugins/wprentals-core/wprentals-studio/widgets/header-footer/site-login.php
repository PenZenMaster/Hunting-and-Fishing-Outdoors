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

class Wprentals_Site_Login extends Widget_Base {

    public function get_name() {
        return 'Site_Login';
    }

    public function get_title() {
        return esc_html__('Website Login & User Menu', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-site-logo';
    }

    public function get_categories() {
        return ['wprentals_header'];
    }

    protected function register_controls() {

        // Content Settings
        $this->start_controls_section(
            'section_general_settings',
            [
                'label' => __('Content', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_user_menu',
            [
                'label' => __('Show User Menu Open (edit mode only)', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'wprentals-core'),
                'label_off' => __('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_cart_menu',
            [
                'label' => __('Show Cart Menu Open (edit mode only)', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'wprentals-core'),
                'label_off' => __('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->end_controls_section();

        // Main Container Styles
        $this->start_controls_section(
            'section_container_style',
            [
                'label' => __('Main Container', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('container_tabs');

        $this->start_controls_tab(
            'container_normal',
            [
                'label' => __('Normal', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'container_background',
            [
                'label' => __('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'container_text_color',
            [
                'label' => __('Text & Icons Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .user_menu' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .menu_username' => 'color: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_trigger i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} #topbarlogin:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .signuplink' => 'color: {{VALUE}}',
                    '{{WRAPPER}} #shopping-cart_icon path' => 'fill: {{VALUE}}',
                    '{{WRAPPER}} #topbarregister:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .user_menu i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'container_hover',
            [
                'label' => __('Hover', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'container_hover_background',
            [
                'label' => __('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'container_hover_text_color',
            [
                'label' => __('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .signuplink:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap:hover .user_menu' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap:hover .menu_username' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap:hover .user_menu i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'container_typography',
                'selector' => '{{WRAPPER}} .wprentals_header_elementor_user_wrap, {{WRAPPER}} .menu_username',
                'separator' => 'before',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => __('Margin', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .wprentals_header_elementor_user_wrap',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .wprentals_header_elementor_user_wrap',
            ]
        );

        $this->end_controls_section();

        // User Picture Styles
        $this->start_controls_section(
            'section_user_picture_style',
            [
                'label' => __('User Picture', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'user_picture_size',
            [
                'label' => __('Size', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .menu_user_picture' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'user_picture_border',
                'selector' => '{{WRAPPER}} .menu_user_picture',
            ]
        );

        $this->add_responsive_control(
            'user_picture_border_radius',
            [
                'label' => __('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .menu_user_picture' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'user_picture_margin',
            [
                'label' => __('Margin', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .menu_user_picture' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'user_picture_box_shadow',
                'selector' => '{{WRAPPER}} .menu_user_picture',
            ]
        );

        $this->end_controls_section();

    
        // Menu Items Styles
        $this->start_controls_section(
            'section_menu_items_style',
            [
                'label' => __('Menu Items', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('menu_items_tabs');

        $this->start_controls_tab(
            'menu_items_normal',
            [
                'label' => __('Normal', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'menu_items_color',
            [
                'label' => __('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #user_menu_open li' => 'color: {{VALUE}}',
                     '{{WRAPPER}} #user_menu_open a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open > li > a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open a svg path' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open a svg circle' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_items_background',
            [
                'label' => __('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #user_menu_open > li > a' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open a' => 'background-color: {{VALUE}}',
                ],
            ]
        );


       $this->add_responsive_control(
            'user_menu_open_right',
            [
                'label' => esc_html__('Right Position', 'your-textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [ 'min' => -500, 'max' => 500 ],
                    '%'  => [ 'min' => -100, 'max' => 100 ],
                ],
            'default' => [
                'size' => 27,
                'unit' => 'px',
            ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap #user_menu_open' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Top position
        $this->add_responsive_control(
            'user_menu_open_top',
            [
                'label' => esc_html__('Top Position', 'your-textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'range' => [
                    'px' => [ 'min' => -500, 'max' => 500 ],
                    '%'  => [ 'min' => -100, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 70,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpestate_header_shoping_cart' => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wprentals_header_elementor_user_wrap #user_menu_open' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'menu_items_hover',
            [
                'label' => __('Hover', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'menu_items_hover_color',
            [
                'label' => __('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #user_menu_open > li > a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open > li > a:focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open > li > a:hover svg path' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open > li > a:hover svg circle' => 'stroke: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open > li > a:hover i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'menu_items_hover_background',
            [
                'label' => __('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #user_menu_open > li > a:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} #user_menu_open > li > a:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_items_typography',
                'selector' => '{{WRAPPER}} #user_menu_open a, {{WRAPPER}} #user_menu_open li a',
                'separator' => 'before',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
            ]
        );

        $this->end_controls_section();

        // Cart Menu Styles
        $this->start_controls_section(
            'section_cart_menu_style',
            [
                'label' => __('Cart Menu', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cart_background_color',
            [
                'label' => __('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpestate_header_shoping_cart' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'cart_font_color',
            [
                'label' => __('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpestate_header_shoping_cart' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .wpestate_header_shoping_cart a' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'cart_padding',
            [
                'label' => __('Padding', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .wpestate_header_shoping_cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

     

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cart_border',
                'selector' => '{{WRAPPER}} .wpestate_header_shoping_cart',
            ]
        );

        $this->add_responsive_control(
            'cart_border_radius',
            [
                'label' => __('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .wpestate_header_shoping_cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'cart_box_shadow',
                'selector' => '{{WRAPPER}} .wpestate_header_shoping_cart',
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        global $wpestate_global_payments;
        $settings = $this->get_settings_for_display();
        
        $user_menu_class = $settings['show_user_menu'] === 'yes' ? 'wprentals-studio-show-user-menu' : 'wprentals-studio-hide-user-menu';
        $cart_menu_class = $settings['show_cart_menu'] === 'yes' ? 'wprentals-studio-show-cart-menu' : 'wprentals-studio-hide-cart-menu';
        
        $wrapper_classes = array(
            'wprentals_header_elementor_user_wrap',
            $user_menu_class,
            $cart_menu_class
        );
        ?>
        <div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
            <?php
            $template_path = locate_template('templates/top_user_menu.php', false, false);
            if (!empty($template_path) && file_exists($template_path)) {
                include $template_path;
            }
            ?>
        </div>
        <?php 
        if (Plugin::$instance->editor->is_edit_mode()) {
            echo '<style>
                .wprentals-studio-show-user-menu #user_menu_open {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                .wprentals-studio-hide-user-menu #user_menu_open {
                    display: none !important;
                }
                .wprentals-studio-show-cart-menu .wpestate_header_shoping_cart {
                    display: block !important;
                    visibility: visible !important;
                    opacity: 1 !important;
                }
                .wprentals-studio-hide-cart-menu .wpestate_header_shoping_cart {
                    display: none !important;
                }
            </style>';
        }
      
    }
}