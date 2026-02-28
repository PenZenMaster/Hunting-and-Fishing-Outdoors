<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use function get_post_type;
use function get_the_ID;
use function is_preview;
use function is_singular;
use function wprentals_get_option;
use function wpestate_limit45;
use function wpestate_last_property_id;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Simple_Detail extends Widget_Base {

    /**
     * Retrieve the widget name.
     */
    public function get_name() {
        return 'property_page_simple_detail';
    }

    /**
     * Retrieve the widget title.
     */
    public function get_title() {
        return esc_html__('Property Single Detail', 'wprentals-core');
    }

    /**
     * Retrieve the widget icon.
     */
    public function get_icon() {
        return 'wprentals-note eicon-product-price';
    }

    /**
     * Retrieve widget categories.
     */
    public function get_categories() {
        return ['wprentals_single_property'];
    }

    /**
     * Retrieve script dependencies.
     */
    public function get_script_depends() {
        return [];
    }

    /**
     * Transform Elementor options into associative array.
     *
     * @param array $input Input control values.
     * @return array
     */
    public function elementor_transform($input) {
        $output = [];
        if (is_array($input)) {
            foreach ($input as $tax) {
                $output[$tax['value']] = $tax['label'];
            }
        }

        return $output;
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'detail',
            [
                'label' => esc_html__('Select single detail', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'title',
                'options' => $this->get_single_detail_options(),
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => esc_html__('Element Label', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Description', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'label_inline',
            [
                'label' => esc_html__('Display Label Inline', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'label_on' => esc_html__('Yes', 'wprentals-core'),
                'label_off' => esc_html__('No', 'wprentals-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_label_style',
            [
                'label' => esc_html__('Label', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_custom_detail_label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'label_typography',
                'selector' => '{{WRAPPER}} .property_custom_detail_label',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_value_style',
            [
                'label' => esc_html__('Value', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .property_custom_detail_value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .property_custom_detail_value',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $attributes = [
            'is_elementor' => 1,
            'detail' => $settings['detail'] ?? '',
            'label' => $settings['label'] ?? '',
            'label_inline' => $settings['label_inline'] ?? '',
        ];

        $property_id      = get_the_ID();
        $last_property_id = wpestate_last_property_id();

        $should_force_last_property = (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        );

        if ($should_force_last_property && $last_property_id) {
            $property_id = $last_property_id;
        } elseif (
            $property_id &&
            'estate_property' !== get_post_type($property_id) &&
            $last_property_id
        ) {
            $property_id = $last_property_id;
        }

        if (!$property_id && $last_property_id) {
            $property_id = $last_property_id;
        }

        if ($property_id) {
            $switched = false;

            if (method_exists(Plugin::instance()->db, 'switch_to_post') && method_exists(Plugin::instance()->db, 'restore_current_post')) {
                Plugin::instance()->db->switch_to_post($property_id);
                $switched = true;
            }

            echo \wprentals_estate_property_simple_detail($property_id, $attributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

            if ($switched) {
                Plugin::instance()->db->restore_current_post();
            }
        }
    }

    /**
     * Build the list of selectable property details.
     *
     * @return array
     */
    protected function get_single_detail_options() {
        $options = [
            'none' => esc_html__('None', 'wprentals-core'),
            'title' => esc_html__('Title', 'wprentals-core'),
            'description' => esc_html__('Description', 'wprentals-core'),
            'property_category' => esc_html__('Categories', 'wprentals-core'),
            'property_action_category' => esc_html__('Action', 'wprentals-core'),
            'property_city' => esc_html__('City', 'wprentals-core'),
            'property_area' => esc_html__('Neighborhood', 'wprentals-core'),
            'property_address' => esc_html__('Address', 'wprentals-core'),
            'guest_no' => esc_html__('Guest Number', 'wprentals-core'),
            'property_county' => esc_html__('Property County', 'wprentals-core'),
            'property_state' => esc_html__('Property State', 'wprentals-core'),
            'property_zip' => esc_html__('Property Zip', 'wprentals-core'),
            'property_country' => esc_html__('Property Country', 'wprentals-core'),
            'property_status' => esc_html__('Status', 'wprentals-core'),
            'prop_featured' => esc_html__('Featured', 'wprentals-core'),
            'property_affiliate' => esc_html__('Affiliate Link', 'wprentals-core'),
            'private_notes' => esc_html__('Private Notes', 'wprentals-core'),
            'checkin_message' => esc_html__('Check-in Message', 'wprentals-core'),
            'instant_booking' => esc_html__('Instant Booking', 'wprentals-core'),
            'wp_estate_replace_booking_form_local' => esc_html__('Contact Form Instead of Booking', 'wprentals-core'),
            'local_booking_type' => esc_html__('Booking Type', 'wprentals-core'),
            'property_price' => esc_html__('Price', 'wprentals-core'),
            'property_price_before_label' => esc_html__('Price Label Before', 'wprentals-core'),
            'property_price_after_label' => esc_html__('Price Label After', 'wprentals-core'),
            'property_taxes' => esc_html__('Taxes', 'wprentals-core'),
            'property_price_per_week' => esc_html__('Price per night (7d+)', 'wprentals-core'),
            'property_price_per_month' => esc_html__('Price per night (30d+)', 'wprentals-core'),
            'price_per_weekeend' => esc_html__('Price per weekend', 'wprentals-core'),
            'cleaning_fee' => esc_html__('Cleaning Fee', 'wprentals-core'),
            'cleaning_fee_per_day' => esc_html__('Cleaning Fee Calculation', 'wprentals-core'),
            'city_fee' => esc_html__('City Fee', 'wprentals-core'),
            'city_fee_per_day' => esc_html__('City Fee Calculation', 'wprentals-core'),
            'min_days_booking' => esc_html__('Minimum Days of Booking', 'wprentals-core'),
            'security_deposit' => esc_html__('Security Deposit', 'wprentals-core'),
            'early_bird_percent' => esc_html__('Early Bird Discount', 'wprentals-core'),
            'early_bird_days' => esc_html__('Early Bird Days Before', 'wprentals-core'),
            'extra_price_per_guest' => esc_html__('Extra Price per Guest', 'wprentals-core'),
            'overload_guest' => esc_html__('Allow Extra Guests', 'wprentals-core'),
            'max_extra_guest_no' => esc_html__('Maximum Extra Guests', 'wprentals-core'),
            'price_per_guest_from_one' => esc_html__('Price per Guest from One', 'wprentals-core'),
            'checkin_change_over' => esc_html__('Check-in Change Over', 'wprentals-core'),
            'checkin_checkout_change_over' => esc_html__('Check-in/Checkout Change Over', 'wprentals-core'),
            'property_size' => esc_html__('Property Size', 'wprentals-core'),
            'property_lot_size' => esc_html__('Property Lot Size', 'wprentals-core'),
            'property_rooms' => esc_html__('Rooms', 'wprentals-core'),
            'property_bedrooms' => esc_html__('Bedrooms', 'wprentals-core'),
            'property_bathrooms' => esc_html__('Bathrooms', 'wprentals-core'),
            'cancellation_policy' => esc_html__('Cancellation Policy', 'wprentals-core'),
            'other_rules' => esc_html__('Other Rules', 'wprentals-core'),
            'smoking_allowed' => esc_html__('Smoking Allowed', 'wprentals-core'),
            'party_allowed' => esc_html__('Party Allowed', 'wprentals-core'),
            'pets_allowed' => esc_html__('Pets Allowed', 'wprentals-core'),
            'children_allowed' => esc_html__('Children Allowed', 'wprentals-core'),
            'property_agent' => esc_html__('Owner', 'wprentals-core'),
            'property_video' => esc_html__('Video', 'wprentals-core'),
            'virtual_tour' => esc_html__('Virtual Tour', 'wprentals-core'),
        ];

        $custom_fields = wprentals_get_option('wpestate_custom_fields_list', '');
        if (is_array($custom_fields)) {
            foreach ($custom_fields as $field) {
                if (empty($field) || empty($field[0])) {
                    continue;
                }

                $raw_label = isset($field[1]) && !empty($field[1]) ? $field[1] : $field[0];
                $slug = sanitize_key(wpestate_limit45(sanitize_title($field[0])));

                if (!empty($slug) && empty($options[$slug])) {
                    $options[$slug] = esc_html($raw_label);
                }
            }
        }

        $feature_list = trim(stripslashes((string) get_option('wp_estate_feature_list')));
        if (!empty($feature_list)) {
            $feature_list_array = array_filter(array_map('trim', explode(',', $feature_list)));

            foreach ($feature_list_array as $value) {
                if ($value === '') {
                    continue;
                }

                $input_name = sanitize_key(wpestate_limit45(sanitize_title(str_replace(' ', '_', $value))));

                if (!empty($input_name) && empty($options[$input_name])) {
                    $options[$input_name] = esc_html($value);
                }
            }
        }

        return $options;
    }
}
