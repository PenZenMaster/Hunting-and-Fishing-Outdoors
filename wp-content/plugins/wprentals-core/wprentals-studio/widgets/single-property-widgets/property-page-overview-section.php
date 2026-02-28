<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Overview_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_overview_section';
    }

    public function get_title() {
        return esc_html__('Property Page Overview Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-posts-group';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $metadata_fields = [
            'guest_no'              => esc_html__('Guests', 'wprentals-core'),
            'property_rooms'        => esc_html__('Rooms', 'wprentals-core'),
            'property_bedrooms'     => esc_html__('Bedrooms', 'wprentals-core'),
            'property_bathrooms'    => esc_html__('Bathrooms', 'wprentals-core'),
            'property_size'         => esc_html__('Property Size', 'wprentals-core'),   
            'min_days_booking'      => esc_html__('Minimum Stay', 'wprentals-core'),
            'property_price'        => esc_html__('Price', 'wprentals-core'),
            'property_id'           => esc_html__('Property ID', 'wprentals-core'),
            'property_address'      => esc_html__('Address', 'wprentals-core'),
            'property_zip'          => esc_html__('ZIP', 'wprentals-core'),
            'property_city'         => esc_html__('City', 'wprentals-core'),
            'property_area'         => esc_html__('Neighborhood', 'wprentals-core'),
            'property_state'        => esc_html__('State', 'wprentals-core'),
            'property_county_state' => esc_html__('County / State', 'wprentals-core'),
            'property_country'      => esc_html__('Country', 'wprentals-core'),
            'property_status'       => esc_html__('Status', 'wprentals-core'),
        ];

        $custom_fields = \wprentals_get_option('wpestate_custom_fields_list', '');

        if (is_array($custom_fields)) {
            foreach ($custom_fields as $custom_field) {
                if (empty($custom_field[0])) {
                    continue;
                }

                $name = $custom_field[0];
                $slug = \wpestate_limit45(sanitize_title($name));
                $slug = sanitize_key($slug);
                $label = stripslashes($custom_field[1]);

                if (!empty($slug)) {
                    $metadata_fields[$slug] = $label;
                }
            }
        }

        $repeater = new Repeater();

        $repeater->add_control(
            'field_type',
            [
                'label'   => esc_html__('Field', 'wprentals-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => $metadata_fields,
            ]
        );

        $repeater->add_control(
            'label_singular',
            [
                'label'       => esc_html__('Label', 'wprentals-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'label_plural',
            [
                'label'       => esc_html__('Label Plural', 'wprentals-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'icon_type',
            [
                'label'   => esc_html__('Icons From', 'wprentals-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'theme_options' => esc_html__('Theme Icons', 'wprentals-core'),
                    'custom'        => esc_html__('Custom Icon', 'wprentals-core'),
                    'none'          => esc_html__('No Icon', 'wprentals-core'),
                ],
                'default' => 'theme_options',
            ]
        );

        $repeater->add_control(
            'meta_icon',
            [
                'label'     => esc_html__('Upload Icon', 'wprentals-core'),
                'type'      => Controls_Manager::ICONS,
                'condition' => [
                    'icon_type' => 'custom',
                ],
            ]
        );

        $this->start_controls_section(
            'overview_content',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hide_section_title',
            [
                'label'        => esc_html__('Hide Section Title', 'wprentals-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'wprentals-core'),
                'label_off'    => esc_html__('No', 'wprentals-core'),
                'return_value' => 'none',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .panel-title-description' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'section_title',
            [
                'label'       => esc_html__('Section Title', 'wprentals-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'hide_updated_on',
            [
                'label'        => esc_html__('Hide Updated On', 'wprentals-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'wprentals-core'),
                'label_off'    => esc_html__('No', 'wprentals-core'),
                'return_value' => 'none',
                'default'      => '',
                'selectors'    => [
                    '{{WRAPPER}} .overview_updatd_on' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overview_fields',
            [
                'type'         => Controls_Manager::REPEATER,
                'fields'       => $repeater->get_controls(),
                'title_field'  => '{{{ label_singular }}}',
                'default'      => [
                    [
                        'id'             => 'guest_no',
                        'field_type'     => 'guest_no',
                        'label_singular' => esc_html__('Guest', 'wprentals-core'),
                        'label_plural'   => esc_html__('Guests', 'wprentals-core'),
                        'icon_type'      => 'theme_options',
                    ],
                    [
                        'id'             => 'property_bedrooms',
                        'field_type'     => 'property_bedrooms',
                        'label_singular' => esc_html__('Bedroom', 'wprentals-core'),
                        'label_plural'   => esc_html__('Bedrooms', 'wprentals-core'),
                        'icon_type'      => 'theme_options',
                    ],
                    [
                        'id'             => 'property_bathrooms',
                        'field_type'     => 'property_bathrooms',
                        'label_singular' => esc_html__('Bathroom', 'wprentals-core'),
                        'label_plural'   => esc_html__('Bathrooms', 'wprentals-core'),
                        'icon_type'      => 'theme_options',
                    ],
                    [
                        'id'             => 'property_size',
                        'field_type'     => 'property_size',
                        'label_singular' => esc_html__('Size', 'wprentals-core'),
                        'label_plural'   => '',
                        'icon_type'      => 'theme_options',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'item_size',
            [
                'label'      => esc_html__('Detail Section Width', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 75,
                        'max' => 200,
                    ],
                    '%'  => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'devices'   => ['desktop', 'tablet', 'mobile'],
                'selectors' => [
                    '{{WRAPPER}} .overview_element' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_spacing_margin_section',
            [
                'label' => esc_html__('Spaces & Sizes', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'property_title_margin_bottom',
            [
                'label'      => esc_html__('Title Margin Bottom (px)', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .panel-title-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'property_content_margin_bottom',
            [
                'label'      => esc_html__('Item Margin Bottom (px)', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .overview_element' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'overview_flex_direction',
            [
                'label'           => esc_html__('Items Direction', 'wprentals-core'),
                'type'            => Controls_Manager::CHOOSE,
                'options'         => [
                    'row' => [
                        'title' => esc_html__('Row', 'wprentals-core'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'wprentals-core'),
                        'icon'  => 'eicon-editor-list-ul',
                    ],
                ],
                'selectors'       => [
                    '{{WRAPPER}} .overview_element' => 'flex-direction: {{VALUE}};',
                ],
                'desktop_default' => 'row',
                'tablet_default'  => 'row',
                'mobile_default'  => 'row',
            ]
        );

        $this->add_responsive_control(
            'overview_items_alignment',
            [
                'label'           => esc_html__('Items Alignment', 'wprentals-core'),
                'type'            => Controls_Manager::CHOOSE,
                'options'         => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'wprentals-core'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'center'     => [
                        'title' => esc_html__('Center', 'wprentals-core'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__('End', 'wprentals-core'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors'       => [
                    '{{WRAPPER}} .overview_element' => 'align-items: {{VALUE}};',
                ],
                'desktop_default' => 'center',
                'tablet_default'  => 'center',
                'mobile_default'  => 'center',
            ]
        );

        $this->add_responsive_control(
            'overview_items_gap',
            [
                'label'      => esc_html__('Items Gap', 'wprentals-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em'  => [
                        'min' => 0,
                        'max' => 6,
                        'step'=> 0.1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 6,
                        'step'=> 0.1,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .overview_element' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'desktop_default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'tablet_default'  => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'mobile_default'  => [
                    'unit' => 'px',
                    'size' => 10,
                ],
            ]
        );

        $this->add_responsive_control(
            'listing_padding',
            [
                'label'      => esc_html__('Wrapper Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .property-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'listing_margin',
            [
                'label'      => esc_html__('Wrapper Margin', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .property-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'listing_border_radius',
            [
                'label'      => esc_html__('Wrapper Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .property-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'typography_section',
            [
                'label' => esc_html__('Typography', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__('Section Title Typography', 'wprentals-core'),
                'name'     => 'property_title_typography',
                'selector' => '{{WRAPPER}} .panel-title-description',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__('Overview Items Typography', 'wprentals-core'),
                'name'     => 'property_content_typography',
                'selector' => '{{WRAPPER}} .overview_element a, {{WRAPPER}} .overview_element li',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_colors',
            [
                'label' => esc_html__('Colors', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'unit_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property-panel' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Section Title Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .panel-title-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'unit_font_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .overview_element'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .overview_element li'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .overview_element i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .overview_element a'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .overview_element img' => 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} .overview_element path'=> 'color: {{VALUE}}; fill: {{VALUE}};',
                    '{{WRAPPER}} h4'                    => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_grid_box_shadow',
            [
                'label' => esc_html__('Box Shadow', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .property-panel',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id  = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode()
            || Plugin::instance()->preview->is_preview_mode()
            || is_singular('wpestate-studio')
            || is_preview()
        ) {
            $post_id = \wpestate_last_property_id();
        }

        if (!$post_id) {
            return;
        }

        Plugin::instance()->db->switch_to_post($post_id);

        $attributes = [
            'is_elementor' => 1,
        ];

        if (Plugin::instance()->editor->is_edit_mode()) {
            $attributes['is_elementor_edit'] = 1;
        }

        if (function_exists('wprentals_property_show_overview_section_function')) {
            echo wprentals_property_show_overview_section_function($attributes, $settings, $post_id);
        }

        Plugin::instance()->db->restore_current_post();
    }
}
