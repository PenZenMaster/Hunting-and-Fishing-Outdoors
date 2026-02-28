<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Wprentals_Featured_Article extends Widget_Base {

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
		return 'Wprentals_Featured_Article';
	}

        public function get_categories() {
		return [ 'wprentals' ];
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
		return __( 'WpRentals Featured Article', 'rentals-elementor' );
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
		return 'eicon-post';
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
            $featured_article_type=array(
                'type1'=>__("type1","rentals-elementor"),
                'type2'=>__("type2","rentals-elementor"),
                'type3'=>__("type3","rentals-elementor")
            );
            $blog_array              =   wprentals_return_article_arrays();
            $blog_array_elemetor      = $this->elementor_transform( $blog_array );

            $this->start_controls_section(
				'section_content',
				[
					'label' => __( 'Content', 'rentals-elementor' ),
				]
            );


            $this->add_control(
                'article_id',
                [
					'label' => __( 'Select the article', 'rentals-elementor' ),
					'label_block'=>true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $blog_array_elemetor,
				]
             );


            $this->add_control(
				'type',
				[
					'label' => __('Type', 'rentals-elementor' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $featured_article_type,
					'default'=>'type1'
				]
            );

            $this->end_controls_section();

	/*
		* -------------------------------------------------------------------------------------------------
		* Typography Controls
		*/
	$this->start_controls_section(
		'section_typography',
		[
			'label' => esc_html__('Typography', 'rentals-elementor'),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		[
			'name' => 'featured_typography',  // Name for the control
			'label' => esc_html__('Title Typography', 'resntals-elementor'),  // Label for the control
			'selector' => '{{WRAPPER}} a.blog-title-link, {{WRAPPER}} .featured_article_type2 h2', // Target both the title link and h2 inside .featured_article_type2
    
			'global'   => [
				'default' => Global_Typography::TYPOGRAPHY_ACCENT
			],
			'responsive' => true,  // Enable responsive typography
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(), [
			'name' => 'article_date',
			'label' => esc_html__('Date Typography', 'rentals-elementor'),
			'global' => [
				'default' => Global_Typography::TYPOGRAPHY_TEXT
			],
			'selector' => '{{WRAPPER}} .featured-article-date',
			]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(), [
			'name' => 'article_category',
			'label' => esc_html__('Category Typography', 'rentals-elementor'),
			'global' => [
				'default' => Global_Typography::TYPOGRAPHY_TEXT
			],
			'selector' => '{{WRAPPER}} .featued_article_categories_list a',
			]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(), [
			'name' => 'readmore',
			'label' => esc_html__('Read More Typography', 'rentals-elementor'),
			'global' => [
				'default' => Global_Typography::TYPOGRAPHY_TEXT
			],
			'condition' => [
				'type' => 'type3', // Show only when type3 is selected
			],
			'selector' => '{{WRAPPER}} .featured_article_type2 .featured_read_more',
		]
	);
	
	$this->add_group_control(
		Group_Control_Typography::get_type(), [
			'name' => 'Featured_category',
			'label' => esc_html__('Featured Article Typography', 'rentals-elementor'),
			'global' => [
				'default' => Global_Typography::TYPOGRAPHY_TEXT
			],
			'condition' => [
				'type' => 'type3', // Show only when type3 is selected
			],
			'selector' => '{{WRAPPER}} .featured_article_label',
		]
	);

    $this->end_controls_section();

	$this->start_controls_section(
		'size_section', [
		'label' => esc_html__('Item Settings', 'rentals-elementor'),
		'tab' => Controls_Manager::TAB_STYLE,
			]
	);

	$this->add_responsive_control(
			'item_height', [
		'label' => esc_html__('Item Height', 'rentals-elementor'),
		'type' => Controls_Manager::SLIDER,
		'range' => [
			'px' => [
				'min' => 50,
				'max' => 800,
			],
		],
		'devices' => ['desktop', 'tablet', 'mobile'],
		'desktop_default' => [
			'size' => 310,
			'unit' => 'px',
		],
		'tablet_default' => [
			'size' => '',
			'unit' => 'px',
		],
		'mobile_default' => [
			'size' => '',
			'unit' => 'px',
		],
		'selectors' => [
			'{{WRAPPER}} .places1' => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .featured_article_type2' => 'height: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .type_1_class .listing-unit-img-wrapper.shortcodefull' => 'height: {{SIZE}}{{UNIT}};',
		],
			]
	);

	$this->add_responsive_control(
		'item_border_radius', [
		'label' => esc_html__('Border Radius', 'rentals-elementor'),
		'type' => Controls_Manager::DIMENSIONS,
		'size_units' => ['px', '%'],
		'selectors' => [
			'{{WRAPPER}} .feature_agent_image_unit_wrapper_color' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',		
			'{{WRAPPER}} .type_1_class .listing-unit-img-wrapper.shortcodefull' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .blog_featured' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		]
);

	$this->end_controls_section();
			 


	/*
	* -------------------------------------------------------------------------------------------------
	* Start shadow section
	*/
	$this->start_controls_section(
		'section_grid_box_shadow', [
			'label' => esc_html__('Box Shadow', 'rentals-elementor'),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);
	$this->add_group_control(
		Group_Control_Box_Shadow::get_type(), [
			'name' => 'box_shadow',
			'label' => esc_html__('Box Shadow', 'rentals-elementor'),
			'selector' => '{{WRAPPER}} .blog_featured ,{{WRAPPER}} .featured_article_type2 ',
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
		$settings = $this->get_settings_for_display();
                $attributes['id']    =   $settings['article_id'];
                $attributes['type'] =   $settings['type'];
                echo  wpestate_featured_article($attributes);
	}


}
