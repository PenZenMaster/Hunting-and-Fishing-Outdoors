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

class Wprentals_Site_Currency_Changer extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Site_Currency_Changer';
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
        return esc_html__('Currency Dropdown', 'wprentals-core');
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
        
        // Typography Section
        $this->start_controls_section(
            'section_typography',
            [
                'label' => esc_html__('Typography', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography for dropdown toggle
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'toggle_typography',
                'label'    => esc_html__('Toggle Typography', 'wprentals-core'),
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
                'selector' => '{{WRAPPER}} .sidebar_filter_menu',
            ]
        );

        // Typography for dropdown menu items
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'items_typography',
                'label'    => esc_html__('Menu Items Typography', 'wprentals-core'),
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
                'selector' => '{{WRAPPER}} .filter_menu li',
            ]
        );

        $this->end_controls_section();

        // Colors Section
        $this->start_controls_section(
            'section_colors',
            [
                'label' => esc_html__('Colors', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Dropdown toggle colors
        $this->add_control(
            'toggle_text_color',
            [
                'label' => esc_html__('Toggle Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sidebar_filter_menu' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .sidebar_filter_menu .caret' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_background_color',
            [
                'label' => esc_html__('Toggle Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-studio-currency' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Menu background
        $this->add_control(
            'menu_background_color',
            [
                'label' => esc_html__('Menu Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dropdown-menu' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} #list_sidebar_curr' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} #list_sidebar_curr::-webkit-scrollbar-track' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} #list_sidebar_curr::-webkit-scrollbar' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Menu item colors
        $this->add_control(
            'item_text_color',
            [
                'label' => esc_html__('Menu Item Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter_menu li' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Hover states
        $this->add_control(
            'item_hover_text_color',
            [
                'label' => esc_html__('Menu Item Hover Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter_menu li:hover' => 'color: {{VALUE}}; cursor: pointer;',
                ],
            ]
        );

        $this->add_control(
            'item_hover_background_color',
            [
                'label' => esc_html__('Menu Item Hover Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter_menu li:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Spacing & Radius Section
        $this->start_controls_section(
            'section_spacing',
            [
                'label' => esc_html__('Spacing & Radius', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_radius_dropdown', 
            [
                'label' => esc_html__('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => '4',
                    'right' => '4',
                    'bottom' => '4',
                    'left' => '4',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sidebar_filter_menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .dropdown-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',     
                ],
            ]
        );

        // Control for the padding of dropdown toggle
        $this->add_responsive_control(
            'toggle_padding',
            [
                'label' => esc_html__('Toggle Padding', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .sidebar_filter_menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]    
        );

         // Padding control for the dropdown menu items
         $this->add_responsive_control(
            'dropdown_item_padding',
            [
                'label' => esc_html__('Menu Items Padding', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .filter_menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin control for the dropdown
        $this->add_responsive_control(
            'dropdown_margin',
            [
                'label' => esc_html__('Dropdown Margin', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .dropdown' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}' => 'width: auto;',
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
        $multiple_cur = wprentals_get_option('wpestate_currency', '');
        $wpestate_where_currency = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        $normal_cur = esc_html(wprentals_get_option('wp_estate_currency_symbol'));
        $normal_label = wprentals_get_option('wp_estate_currency_label_main', '');

        $cur_list = sprintf(
            '<li role="presentation" data-curpos="%1$s" data-coef="1" data-value="%2$s" data-symbol="%3$s" data-symbol2="%3$s" data-pos="-1">%4$s </li>',
            esc_attr($wpestate_where_currency),
            esc_attr($normal_cur),
            esc_attr($normal_label),
            esc_html($normal_cur)
        );

        if (!empty($multiple_cur) && is_array($multiple_cur)) {
            foreach ($multiple_cur as $index => $currency) {
                if (!is_array($currency) || count($currency) < 4) {
                    continue;
                }

                $cur_list .= sprintf(
                    '<li role="presentation" data-curpos="%1$s" data-coef="%2$s" data-value="%3$s" data-symbol="%3$s" data-symbol2="%4$s" data-pos="%5$d"> %6$s </li>',
                    esc_attr($currency[3]),
                    esc_attr($currency[2]),
                    esc_attr($currency[0]),
                    esc_attr($currency[1]),
                    (int) $index,
                    esc_html($currency[0])
                );
            }
        }

        $current_currency = isset($_COOKIE['my_custom_curr']) ? sanitize_text_field(wp_unslash($_COOKIE['my_custom_curr'])) : '';

        $display = '<div class="dropdown form-control wprentals-studio-currency">';
        $display .= '<div data-toggle="dropdown" id="sidebar_currency_list" class="sidebar_filter_menu">';
        $display .= $current_currency ? esc_html($current_currency) : esc_html($normal_cur);
        $display .= '<span class="caret "></span>';
        $display .= '</div>';
        $display .= '<input type="hidden" name="filter_curr[]" value="">';
        $display .= '<ul id="list_sidebar_curr" class="dropdown-menu filter_menu list_sidebar_currency" role="menu" aria-labelledby="sidebar_currency_list">';
        $display .= $cur_list;
        $display .= '</ul>';
        $display .= '</div>';

        $ajax_nonce = wp_create_nonce('wprentals_change_currency_nonce');
        $display .= '<input type="hidden" id="wprentals_change_currency" value="' . esc_attr($ajax_nonce) . '" />';

        echo $display; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}