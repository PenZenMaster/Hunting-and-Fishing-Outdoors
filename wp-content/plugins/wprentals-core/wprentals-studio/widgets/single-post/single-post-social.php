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
use Elementor\Group_Control_Text_Stroke;
use Elementor\Plugin;

if (!defined('ABSPATH'))
    exit;

class Wprentals_Single_Post_Social extends Widget_Base {

    public function get_name() {
        return 'Single_post_social';
    }

    public function get_title() {
        return esc_html__('WpRentals Single Post Social', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-social-icons';
    }

    public function get_categories() {
        return ['wprentals_single_post'];
    }

    protected function register_controls() {
        
        // Content Section - Show/Hide
        $this->start_controls_section(
            'section_content', [
                'label' => esc_html__('Content', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_share_text',
            [
                'label' => esc_html__('Show Share Text', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_whatsapp',
            [
                'label' => esc_html__('Show WhatsApp', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_facebook',
            [
                'label' => esc_html__('Show Facebook', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_twitter',
            [
                'label' => esc_html__('Show Twitter', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_email',
            [
                'label' => esc_html__('Show Email', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_pinterest',
            [
                'label' => esc_html__('Show Pinterest', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'section_style', [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => esc_html__('Alignment', 'wprentals-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'wprentals-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wprentals-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'wprentals-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Justified', 'wprentals-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .prop_social' => 'display: flex; justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'space_between',
            [
                'label' => esc_html__('Space Between Icons', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .prop_social' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Share Text Style
        $this->start_controls_section(
            'section_text_style', [
                'label' => esc_html__('Share Text', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_share_text' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .prop_social_share' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Icon Style
        $this->start_controls_section(
            'section_icon_style', [
                'label' => esc_html__('Icons', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 72,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .prop_social i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .prop_social i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color',
            [
                'label' => esc_html__('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .prop_social a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_width',
            [
                'label' => esc_html__('Border Width', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .prop_social a' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color',
            [
                'label' => esc_html__('Border Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .prop_social a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .prop_social a' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Hover Icon Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .prop_social a:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color_hover',
            [
                'label' => esc_html__('Hover Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .prop_social a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $post_id = get_the_ID();
        if (Plugin::instance()->editor->is_edit_mode() || 
            Plugin::instance()->preview->is_preview_mode() || 
            is_singular('wpestate-studio') ||
            is_preview()) {
            
            $post_id = wpestate_last_post_id();
        }
        
        if ($post_id) {
            if (function_exists('wpestate_share_unit_desing')) {
                $output = wpestate_share_unit_desing($post_id, $is_single = 1);
                
                // Hide elements based on settings
                if ($settings['show_share_text'] !== 'yes') {
                    $output = preg_replace('/<span class="prop_social_share">.*?<\/span>/', '', $output);
                }
                if ($settings['show_whatsapp'] !== 'yes') {
                    $output = preg_replace('/<a[^>]*share_whatsup[^>]*>.*?<\/a>/', '', $output);
                }
                if ($settings['show_facebook'] !== 'yes') {
                    $output = preg_replace('/<a[^>]*share_facebook[^>]*>.*?<\/a>/', '', $output);
                }
                if ($settings['show_twitter'] !== 'yes') {
                    $output = preg_replace('/<a[^>]*share_tweet[^>]*>.*?<\/a>/', '', $output);
                }
                if ($settings['show_email'] !== 'yes') {
                    $output = preg_replace('/<a[^>]*share_email[^>]*>.*?<\/a>/', '', $output);
                }
                if ($settings['show_pinterest'] !== 'yes') {
                    $output = preg_replace('/<a[^>]*share_pinterest[^>]*>.*?<\/a>/', '', $output);
                }
                
                echo $output;
            }
        }
    }
}