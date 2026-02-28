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
    exit; // Exit if accessed directly

class Wprentals_Single_Post_Meta_Info extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Single_post_meta_info';
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
        return esc_html__('WpRentals Single Post Meta Info', 'wprentals-core');
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
        return 'wprentals-note eicon-meta-data';
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
        return ['wprentals_single_post'];
    }

    /**
     * Register widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {
        
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Post Meta Info', 'wprentals-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color', [
                'label'     => esc_html__('Color', 'wprentals-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meta-element' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .meta-element a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name' => 'wprentals_tab_item_typography',
                'selector' => '{{WRAPPER}} .meta-element',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY
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
        $post_id = get_the_ID();

        if (Plugin::instance()->editor->is_edit_mode() ||
            Plugin::instance()->preview->is_preview_mode() ||
            is_singular('wpestate-studio') ||
            is_preview()) {

            $post_id = wpestate_last_post_id();

        }
       
        if ($post_id) {
            ?>
            <div class="wprentals-studio-meta-info meta-info">
                <div class="meta-element">
                    <i class="far fa-calendar-alt meta_icon firsof"></i>
                    <?php
                    printf(
                        '%s %s %s %s',
                        esc_html__('Posted by', 'wprentals-core'),
                        get_the_author(),
                        esc_html__('on', 'wprentals-core'),
                        get_the_date()
                    );
                    ?>
                </div>

                <div class="meta-element">
                    <i class="far fa-file meta_icon"></i>
                    <?php the_category(', '); ?>
                </div>

                <div class="meta-element">
                    <i class="far fa-comment meta_icon"></i>
                    <?php
                    comments_number(
                        esc_html__('0 Comments', 'wprentals-core'),
                        esc_html__('1 Comment', 'wprentals-core'),
                        esc_html__('% Comments', 'wprentals-core')
                    );
                    ?>
                </div>
            </div>
            <?php
        }
    }
}
