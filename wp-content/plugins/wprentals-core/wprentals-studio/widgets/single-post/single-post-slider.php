<?php
namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Single_Post_Slider extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Single_post_Slider';
	}

        public function get_categories() {
		return [ 'wprentals_single_post' ];
	}


	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WpRentals Single Post Slider', 'wprentals-core' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wprentals-note  eicon-slider-album ';
	}



	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
	return [ '' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
         public function elementor_transform($input){
            $output=array();
            if( is_array($input) ){
                foreach ($input as $key=>$tax){
                    $output[$tax['value']]=$tax['label'];
                }
            }
            return $output;
        }




    protected function register_controls() {
        $text_align=array('left'=>'left','right'=>'right','center'=>'center');
        $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'wprentals-core' ),
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

        public function wprentals_send_to_shortcode($input){
            $output='';
            if($input!==''){
                $numItems = count($input);
                $i = 0;

                foreach ($input as $key=>$value){
                    $output.=$value;
                    if(++$i !== $numItems) {
                      $output.=', ';
                    }
                }
            }
            return $output;
        }
	    protected function render() {
        
			$post_id = get_the_ID();
			if (Plugin::instance()->editor->is_edit_mode() || 
				Plugin::instance()->preview->is_preview_mode() || 
				is_singular( 'wpestate-studio' ) ||
				is_preview()) {
				
				$post_id = wpestate_last_post_id();
			
			}
			
			if ($post_id) {
			
				if ( function_exists( 'wprentals_render_postslider' ) ) {
					echo wprentals_render_postslider( $post_id );
				}


    		}

}
	
}
