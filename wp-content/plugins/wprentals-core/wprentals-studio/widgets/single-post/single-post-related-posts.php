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

class Wprentals_Single_Post_Related_Posts extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Single_post_related_posts';
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
        return esc_html__('WpRentals Single Post Related Posts', 'wprentals-core');
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
        return 'wprentals-note eicon-archive-posts';
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

        $this->start_controls_section( 'section_content', [
            'label' => __( 'Content', 'wprentals-core' ),
            ]
        );

        $this->add_control( 'title', [
            'label' => __( 'Title', 'wprentals-core' ),
            'type' => Controls_Manager::TEXT,
            'label_block'=>true,
            'default' => 'Description',
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
            $settings = $this->get_settings_for_display();
    
            if (Plugin::instance()->editor->is_edit_mode() || 
                Plugin::instance()->preview->is_preview_mode() || 
                is_singular( 'wpestate-studio' ) ||
                is_preview()) {
                
                $post_id = wpestate_last_post_id();
            
            }
            
            if ($post_id) {
               
                   print ' '.wprentals_render_related_posts($post_id,$settings['title']);
            }
        }

}