<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Property_Page_Contact_Owner extends Widget_Base {

    public function get_name() {
        return 'property_page_contact_owner_section';
    }

    public function get_title() {
        return esc_html__('Property Page Contact Owner Section', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note eicon-contact';
    }

    public function get_categories() {
        return ['wprentals_single_property'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'contact_text',
            [
                'label' => esc_html__('Contact Text', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Contact owner', 'wprentals-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Contact Box', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} #contact_host',
            ]
        );

          $this->add_responsive_control(
            'wrapper_padding',
            [
                'label'      => esc_html__( 'Padding', 'wprentals-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}}  #contact_host' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->start_controls_tabs('tabs_contact_style');

        $this->start_controls_tab(
            'tab_contact_normal',
            [
                'label' => esc_html__('Normal', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'contact_background_color',
            [
                'label' => esc_html__('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #contact_host' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'contact_text_color',
            [
                'label' => esc_html__('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #contact_host' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_contact_hover',
            [
                'label' => esc_html__('Hover', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'contact_hover_background_color',
            [
                'label' => esc_html__('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #contact_host:hover' => 'background-color: {{VALUE}}!important;',
                ],
            ]
        );

        $this->add_control(
            'contact_hover_text_color',
            [
                'label' => esc_html__('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #contact_host:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'contact_border',
                'selector' => '{{WRAPPER}} #contact_host',
            ]
        );

        $this->add_responsive_control(
            'contact_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} #contact_host' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $postID = get_the_ID();

        $settings = $this->get_settings_for_display();
        $contact_text = isset($settings['contact_text']) && '' !== $settings['contact_text']
            ? $settings['contact_text']
            : esc_html__('Contact owner', 'wprentals-core');

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()) {

            $postID = wpestate_last_property_id();
        }

        if ($postID) {
            ?>
            <div id="contact_host" class="col-md-6" data-postid="<?php echo intval($postID);?>">
                <?php echo esc_html($contact_text); ?>
            </div>
            <?php
        }
    }
}
