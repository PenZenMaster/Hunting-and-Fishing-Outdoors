<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

class Wprentals_Owner_Contact_Button extends Widget_Base {

    public function get_name() {
        return 'wprentals_owner_contact_button';
    }

    public function get_title() {
        return esc_html__('WpRentals Owner Contact Button', 'wprentals-core');
    }

    public function get_icon() {
        return 'wprentals-note  eicon-mail';
    }

    public function get_categories() {
        return ['wprentals_owners'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'wprentals-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__('Padding', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'button_border',
                'selector' => '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner',
            ]
        );

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'wprentals-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],
                'selectors'  => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_button_states');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_normal_border_color',
            [
                'label'     => esc_html__('Border Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label'     => esc_html__('Text Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background_color',
            [
                'label'     => esc_html__('Background Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'button_border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-owner-widget-contact-button #contact_me_long_owner:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $owner_id = get_the_ID();

        if (
            Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()
        ) {
            $owner_id = wpestate_last_agent_id();
        }

        if ($owner_id) {
            ?>
            <div class="wprentals-owner-widget wprentals-owner-widget-contact-button">
                <div id="contact_me_long_owner" class=" owner_read_more" data-postid="<?php print intval($owner_id);?>">
                    <?php esc_html_e('Contact Owner','wprentals-core');?>
                </div>
            </div>
            <?php
        }
    }
}
