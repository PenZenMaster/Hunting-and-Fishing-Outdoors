<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Owner_Property_Listings extends Widget_Base {

    public function get_name() {
        return 'wprentals_owner_property_listings';
    }

    public function get_title() {
        return esc_html__('WpRentals Owner Property Listings', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-post-list';
    }

    public function get_categories() {
        return ['wprentals_owners'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'show_my_listings_title',
            [
                'label'        => esc_html__('Show "My Listings" Title', 'wprentals-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'wprentals-core'),
                'label_off'    => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings   = $this->get_settings_for_display();
        $show_title = isset($settings['show_my_listings_title']) ? $settings['show_my_listings_title'] === 'yes' : true;

        $current_agent_id = get_the_ID();

        if (
            \Elementor\Plugin::instance()->editor->is_edit_mode() ||
            \Elementor\Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $current_agent_id = wpestate_last_agent_id();
        }

        if ($current_agent_id) {
            global $agent_id;
            global $leftcompare;
            global $prop_selection;
            global $wp_query;
            global $wpestate_curent_fav;
            global $wpestate_full_page;
            global $comments_data;
            global $wpestate_listing_type;
            global $wpestate_property_unit_slider;

            $agent_id      = $current_agent_id;
            $owner_id      = get_post_meta($agent_id, 'user_agent_id', true);
            $user_agent_id = wpestate_user_for_agent($agent_id);

            $comments_data = wpestate_review_composer($agent_id);

            $wrapper_id = 'wprentals-owner-property-listings-' . $this->get_id();

            if (!$show_title) {
                echo '<style>#' . esc_attr($wrapper_id) . ' #other_listings{display:none!important;}</style>';
            }

            echo '<div id="' . esc_attr($wrapper_id) . '">';
            include locate_template('templates/agent_listings.php');
            echo '</div>';

            wp_reset_postdata();
        }
    }
}
