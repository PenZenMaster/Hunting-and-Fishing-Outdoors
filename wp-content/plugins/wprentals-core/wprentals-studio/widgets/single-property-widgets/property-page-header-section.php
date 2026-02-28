<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;
use function wpestate_last_property_id;
use function wpestate_show_labels;
use function wpestate_show_price;
use function wprentals_get_option;
use function wprentals_return_booking_type;
use function wprentals_return_property_ratiings_v1;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Wprentals_Property_Page_Header_Section extends Widget_Base {

    public function get_name() {
        return 'property_page_header_section';
    }

    public function get_title() {
        return esc_html__('Property Page Header Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-header';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_entry_title_style',
            [
                'label' => esc_html__('Entry Title', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'entry_title_typography',
                'selector' => '{{WRAPPER}} .content-fixed-listing .entry-title',
            ]
        );

        $this->add_control(
            'entry_title_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-fixed-listing .entry-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_price_style',
            [
                'label' => esc_html__('Price', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .content-fixed-listing .listing_main_image_price',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-fixed-listing .listing_main_image_price, {{WRAPPER}} .content-fixed-listing .listing_main_image_price span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_ratings_style',
            [
                'label' => esc_html__('Property Ratings', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'ratings_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-fixed-listing .property_ratings, {{WRAPPER}} .content-fixed-listing .property_ratings .rating_no' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ratings_icon_color',
            [
                'label'     => esc_html__('Stars Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-fixed-listing .property_ratings i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_location_style',
            [
                'label' => esc_html__('Location', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'location_typography',
                'selector' => '{{WRAPPER}} .content-fixed-listing .listing_main_image_location',
            ]
        );

        $this->add_control(
            'location_color',
            [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .content-fixed-listing .listing_main_image_location, {{WRAPPER}} .content-fixed-listing .listing_main_image_location a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $property_id = get_the_ID();

        if (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $property_id = wpestate_last_property_id();
        }

        if (!$property_id) {
            return;
        }

        $listing_page_type = (int) wprentals_get_option('wp_estate_listing_page_type', '');

        $property_city = get_the_term_list($property_id, 'property_city', '', ', ', '');
        $property_area = get_the_term_list($property_id, 'property_area', '', ', ', '');

        $property_area_markup = '';
        $property_area_plain  = '';

        if (!empty($property_area) && '' !== trim(wp_strip_all_tags($property_area))) {
            $property_area_markup = ', ' . $property_area;
            $property_area_plain  = ', ' . wp_strip_all_tags($property_area);
        }

        $location_markup = $property_city . $property_area_markup;
        $location_plain  = wp_strip_all_tags($property_city) . $property_area_plain;

        Plugin::instance()->db->switch_to_post($property_id);

        global $wpestate_currency, $wpestate_where_currency;

        if (!isset($wpestate_currency)) {
            $wpestate_currency = esc_html(wprentals_get_option('wp_estate_currency_symbol', ''));
        }

        if (!isset($wpestate_where_currency)) {
            $wpestate_where_currency = esc_html(wprentals_get_option('wp_estate_where_currency_symbol', ''));
        }

        echo '<div class="content-fixed-listing listing_type_5">';
        echo '<div class="listing_type_title_wrapper">';
        echo '<h1 itemprop="name" class="entry-title entry-prop">' . esc_html(get_the_title($property_id)) . '</h1>';

        echo '<div itemprop="price" class="listing_main_image_price">';

        $price_per_guest_from_one = (float) get_post_meta($property_id, 'price_per_guest_from_one', true);
        $price                   = (float) get_post_meta($property_id, 'property_price', true);

        wpestate_show_price($property_id, $wpestate_currency, $wpestate_where_currency, 0);

        $rental_type  = wprentals_get_option('wp_estate_item_rental_type');
        $booking_type = wprentals_return_booking_type($property_id);

        if (0.0 !== $price) {
            if (1 === $price_per_guest_from_one) {
                echo ' ' . esc_html__('per guest', 'wprentals-core');
            } else {
                echo ' ' . wpestate_show_labels('per_night', $rental_type, $booking_type);
            }
        }

        echo '</div>';

        if (function_exists('wprentals_return_property_ratiings_v1')) {
            echo wprentals_return_property_ratiings_v1($property_id);
        }

        echo '<div class="listing_main_image_location" itemprop="location" itemscope itemtype="http://schema.org/Place">';
        echo wp_kses_post($location_markup);
        echo '<div class="schema_div_noshow" itemprop="name">' . esc_html($location_plain) . '</div>';
        echo '</div>';
        echo '</div>';

        echo '</div>';

        Plugin::instance()->db->restore_current_post();
    }
}
