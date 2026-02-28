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
use Elementor\Icons_Manager;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Footer_Properties_By_Category extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Properties_By_Category';
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
        return esc_html__('Properties By Category', 'wprentals-core');
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

        // Available property taxonomies
        $taxonomies = array(
            'property_category'         => esc_html__('Property Category','wprentals-core'),
            'property_action_category'  => esc_html__('Property Action','wprentals-core'),
            'property_city'             => esc_html__('Property City','wprentals-core'),
            'property_area'             => esc_html__('Property Area','wprentals-core')
        );
        
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content',  'wprentals-core' ),
            ]
        );

        $this->add_control( 'Title', [
            'label' => __( 'Element Title', 'wprentals-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block'=>true,
            'default' => 'Our Listings',
            ]
        );

        $this->add_control( 'taxonomy', [
            'label' => __( 'Select Taxonomy', 'wprentals-core' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'Title'  ,
            'options' => $taxonomies
            ]
        );

        $this->add_control( 'show_count', [
            'label' => __( 'Show Count', 'wprentals-core' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'yes',
            'options' => [
                'yes' => __( 'Yes', 'wprentals-core' ),
                'no' => __( 'No', 'wprentals-core' ),
            ],
        ]
        );

        $this->add_control( 'show_child', [
            'label' => __( 'Show Child', 'wprentals-core' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'yes',
            'options' => [
                'yes' => __( 'Yes', 'wprentals-core' ),
                'no' => __( 'No', 'wprentals-core' ),
            ],
        ]
        );
        $this->add_control( 'show_icon', [
            'label' => __( 'Show Icon', 'wprentals-core' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'no',
            'options' => [
                'yes' => __( 'Yes', 'wprentals-core' ),
                'no' => __( 'No', 'wprentals-core' ),
            ],
        ]
        );

        $this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'wprentals-core' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
                'condition' => [
                    'show_icon' => 'yes',
                ],
				'recommended' => [
					'fa-solid' => [
						'circle',
						'dot-circle',
						'square-full',
					],
					'fa-regular' => [
						'circle',
						'dot-circle',
						'square-full',
					],
				],
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'title_style_section',
            [
                'label' => esc_html__('Title Style', 'wprentals-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .widget-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .widget-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Margin', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .widget-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - List Items
        $this->start_controls_section(
            'list_style_section',
            [
                'label' => esc_html__('List Style', 'wprentals-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'list_item_color',
            [
                'label' => esc_html__('Link/Icon Color', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category_list_widget ul li a' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .category_list_widget ul li i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .category_list_widget ul li .wprentals-category-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .category_list_widget ul li .wprentals-category-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.2,
                        'max' => 6,
                    ],
                    'rem' => [
                        'min' => 0.2,
                        'max' => 6,
                    ],
                    'default' => [
                        'size' => 12,
                        'unit' => 'px',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .category_list_widget ul li .wprentals-category-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .category_list_widget ul li .wprentals-category-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
                'condition' => [
                    'show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'list_item_hover_color',
            [
                'label' => esc_html__('Link Hover Color', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category_list_widget ul li a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .category_list_widget ul li:hover .wprentals-category-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .category_list_widget ul li:hover .wprentals-category-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'list_typography',
                'selector' => '{{WRAPPER}} .category_list_widget ul li a',
            ]
        );

        $this->add_responsive_control(
            'list_item_padding',
            [
                'label' => esc_html__('Item Padding', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .category_list_widget ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'count_gap',
            [
                'label' => esc_html__('Icon / Count Spacing', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .category_list_widget ul li' => 'display: flex; align-items: center; column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Count Numbers
        $this->start_controls_section(
            'count_style_section',
            [
                'label' => esc_html__('Count Style', 'wprentals-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label' => esc_html__('Count Color', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .category_no' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'count_typography',
                'selector' => '{{WRAPPER}} .category_no',
            ]
        );

        $this->add_responsive_control(
            'count_margin',
            [
                'label' => esc_html__('Count Margin', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .category_no' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();

        // Style Section - Container
        $this->start_controls_section(
            'container_style_section',
            [
                'label' => esc_html__('Container Style', 'wprentals-core'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => esc_html__('Background', 'wprentals-core'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .category_list_widget',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .category_list_widget',
            ]
        );

        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .category_list_widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'wprentals-core'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .category_list_widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'selector' => '{{WRAPPER}} .category_list_widget',
            ]
        );

        $this->end_controls_section();
          

    }


  

/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 
	 * @since 1.0.0
	 *
	 * @access protected
	 */

    protected function render() {
        if (!function_exists('wpestate_recursive_category_list')) {
            return;
        }

        $settings = $this->get_settings_for_display();

        $title = isset($settings['Title']) ? $settings['Title'] : '';
        $taxonomy = isset($settings['taxonomy']) ? $settings['taxonomy'] : 'property_category';
        $show_count = isset($settings['show_count']) ? $settings['show_count'] : 'yes';
        $show_child = isset($settings['show_child']) ? $settings['show_child'] : 'yes';
        $show_icon = isset($settings['show_icon']) ? $settings['show_icon'] : 'no';

        $terms = [];
        if (!empty($taxonomy)) {
            $terms = get_terms(
                [
                    'taxonomy' => $taxonomy,
                    'parent'   => 0,
                ]
            );

            if (is_wp_error($terms)) {
                $terms = [];
            }
        }

        if (!empty($title)) {
            echo '<h3 class="widget-title">' . esc_html($title) . '</h3>';
        }

        $container_classes = 'category_list_widget';
        if ('yes' === $show_icon) {
            $container_classes .= ' category_list_widget--with-icons';
        }

        echo '<div class="' . esc_attr($container_classes) . '">';

        if (!empty($terms)) {
            $categories_markup = wpestate_recursive_category_list($terms, $taxonomy, $show_child, $show_count);

            if ('yes' === $show_icon && !empty($settings['icon'])) {
                $icon_markup = $this->get_icon_markup($settings['icon']);
                if (!empty($icon_markup)) {
                    $categories_markup = str_replace('<li><a', '<li>' . $icon_markup . ' <a', $categories_markup);
                }

                $categories_markup = $this->ensure_icon_list_styles($categories_markup);
            }

            echo $categories_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        echo '</div>';
    }

    /**
     * Build icon markup for category list entries.
     *
     * @param array $icon_setting Icon settings from Elementor.
     *
     * @return string
     */
    private function get_icon_markup($icon_setting) {
        if (empty($icon_setting['value'])) {
            return '';
        }

        ob_start();
        Icons_Manager::render_icon($icon_setting, ['aria-hidden' => 'true']);
        $icon_html = ob_get_clean();

        if (empty($icon_html)) {
            return '';
        }

        return '<span class="wprentals-category-icon">' . trim($icon_html) . '</span>';
    }

    /**
     * Ensure unordered lists suppress bullet styling when icons are shown.
     *
     * @param string $markup Rendered category markup.
     *
     * @return string
     */
    private function ensure_icon_list_styles($markup) {
        return preg_replace_callback(
            '/<ul([^>]*)>/i',
            function ($matches) {
                $attributes = $matches[1];

                if (false !== stripos($attributes, 'list-style-type')) {
                    return '<ul' . $attributes . '>';
                }

                if (preg_match('/style=("|\')(.*?)("|\')/i', $attributes, $style_match)) {
                    $quote = $style_match[1];
                    $styles = trim($style_match[2]);

                    if ('' !== $styles && ';' !== substr($styles, -1)) {
                        $styles .= ';';
                    }

                    $styles .= ' list-style-type: none;';
                    $new_style = 'style=' . $quote . trim($styles) . $quote;
                    $attributes = str_replace($style_match[0], $new_style, $attributes);
                } else {
                    $attributes .= ' style="list-style-type: none;"';
                }

                return '<ul' . $attributes . '>';
            },
            $markup
        );
    }


}
