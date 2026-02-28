<?php
// Namespace and use statements
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

// Prevent direct access to the file
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Class representing the custom Elementor widget.
 * This widget creates a button that allows users to add new listings.
 */
class Wprentals_Site_Create_Listing extends Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'Site_Create_Listing';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__('Add New Listing Button', 'wprentals-core');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'wprentals-note eicon-site-logo';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return ['wprentals_header'];
    }

    /**
     * Register widget controls.
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
            'button_label',
            [
                'label' => __('Button label', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Add Listing', 'wprentals-core')
            ]
        );

        $this->add_control(
            'show_custom',
            [
                'label' => __('Custom Link', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'wprentals-core'),
                'label_off' => __('No', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_control(
            'custom_link',
            [
                'label' => __('Link', 'wprentals-core'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://link.com', 'wprentals-core'),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],
                'condition' => [
                    'show_custom' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Spacing Controls
        $this->add_responsive_control(
            'button_padding',
            [
                'label' => __('Padding', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_margin',
            [
                'label' => __('Margin', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
                ],
                'selector' => '{{WRAPPER}} .wprentals-studio-submit-wrapper #submit_action',
            ]
        );

        // Colors - Normal State
        $this->add_control(
            'button_color',
            [
                'label' => __('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#ffffff'
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing' => 'background: {{VALUE}} !important;',
                ],
                'default' => '#a672e7'
            ]
        );

        // Border Controls
        $this->add_control(
            'button_border_color',
            [
                'label' => __('Border Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_width',
            [
                'label' => __('Border Width', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important; border-style: solid !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            ]
        );

        // Hover State Heading
        $this->add_control(
            'hover_heading',
            [
                'label' => __('Hover State', 'wprentals-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Colors - Hover State
        $this->add_control(
            'button_color_hover',
            [
                'label' => __('Text Color (Hover)', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing:hover' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#ffffff'
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label' => __('Background Color (Hover)', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing:hover' => 'background: {{VALUE}} !important;',
                ],
                'default' => '#8a60bd'
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => __('Border Color (Hover)', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .submit_listing.wprentals_studio_submit_listing:hover' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $label = !empty($settings['button_label']) ? $settings['button_label'] : esc_html__('Submit Property', 'wprentals-core');
        $show_custom = !empty($settings['show_custom']) ? $settings['show_custom'] : 'no';

        $link = '';
        $target = '';
        $rel = '';

        if ($show_custom === 'yes' && !empty($settings['custom_link']['url'])) {
            $link = $settings['custom_link']['url'];
            $target = !empty($settings['custom_link']['is_external']) ? '_blank' : '';
            $rel = !empty($settings['custom_link']['nofollow']) ? 'nofollow' : '';
        } elseif (esc_html(wprentals_get_option('wp_estate_show_submit', '')) === 'yes') {
            $dashboard_links = wpestate_get_all_dashboard_template_links();
            $link = isset($dashboard_links['user_dashboard_add_step1.php']) ? $dashboard_links['user_dashboard_add_step1.php'] : '';
        }

        if ($link) {
            ?>
            <div class="wprentals-studio-submit-wrapper">   
                <a href="<?php echo esc_url($link); ?>"<?php if ($target) { ?> target="<?php echo esc_attr($target); ?>"<?php } ?><?php if ($rel) { ?> rel="<?php echo esc_attr($rel); ?>"<?php } ?> id="submit_action" class="submit_listing wprentals_studio_submit_listing"><?php echo esc_html($label); ?></a>
            </div>
            <?php
        }
    }
}