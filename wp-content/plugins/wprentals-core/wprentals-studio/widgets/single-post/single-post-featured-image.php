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
use Elementor\Plugin;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Single_Post_Featured_Image extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Single_post_featured_image';
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
        return esc_html__('WpRentals Single Post Featured Image', 'wprentals-core');
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
        return 'wprentals-note eicon-featured-image';
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
                'label' => __( 'Content',  'wprentals-core' ),
            ]
        );


        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
			]
		);

        $this->add_control(
                'use_background_image',
                [
                    'label' => esc_html__('Use Background Image', 'wprentals-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'wprentals-core'),
                    'label_off' => esc_html__('No', 'wprentals-core'),
                    'return_value' => 'yes',
                    'default' => esc_html__('No', 'wprentals-core'),
                ]
        );
     
        
        $this->end_controls_section();
        
          
        $this->start_controls_section(
            'section_style', [
                'label' => esc_html__('Style',  'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
           
        $this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'wprentals-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wprentals-core' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wprentals-core' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wprentals-core' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => esc_html__( 'Width', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1500,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
				
					'{{WRAPPER}} .wprentals-post-featured-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

                $this->add_responsive_control(
                        'height',
			[
				'label' => esc_html__( 'Height', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wprentals-post-featured-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

                $this->add_responsive_control(
                        'object-fit',
                        [
                                'label' => esc_html__( 'Object Fit', 'wprentals-core' ),
                                'type' => Controls_Manager::SELECT,
                                'condition' => [
                                        'use_background_image' => 'yes',
                                        'height[size]!' => '',
                                ],
                                'options' => [
                                        '' => esc_html__( 'Default', 'wprentals-core' ),

                                        'cover' => esc_html__( 'Cover', 'wprentals-core' ),
                                        'contain' => esc_html__( 'Contain', 'wprentals-core' ),

                                ],
                                'default' => '',
                                'selectors' => [
                                        '{{WRAPPER}} .wprentals-post-featured-image' => 'background-size: {{VALUE}};',
                                ],
                        ]
                );

                $this->add_responsive_control(
                        'object-position',
                        [
                                'label' => esc_html__( 'Object Position', 'wprentals-core' ),
                                'type' => Controls_Manager::SELECT,
                                'condition' => [
                                        'use_background_image' => 'yes',
                                ],
                                'options' => [
                                        'center center' => esc_html__( 'Center Center', 'wprentals-core' ),
                                        'center left' => esc_html__( 'Center Left', 'wprentals-core' ),
                                        'center right' => esc_html__( 'Center Right', 'wprentals-core' ),
                                        'top center' => esc_html__( 'Top Center', 'wprentals-core' ),
                                        'top left' => esc_html__( 'Top Left', 'wprentals-core' ),
                                        'top right' => esc_html__( 'Top Right', 'wprentals-core' ),
                                        'bottom center' => esc_html__( 'Bottom Center', 'wprentals-core' ),
                                        'bottom left' => esc_html__( 'Bottom Left', 'wprentals-core' ),
                                        'bottom right' => esc_html__( 'Bottom Right', 'wprentals-core' ),
                                ],
                                'default' => 'center center',
                                'selectors' => [

                                        '{{WRAPPER}} .wprentals-post-featured-image' => 'background-position: {{VALUE}};',
                                ],

                        ]
                );

                $this->add_responsive_control(
                        'background-repeat',
                        [
                                'label' => esc_html__( 'Background Repeat', 'wprentals-core' ),
                                'type' => Controls_Manager::SELECT,
                                'condition' => [
                                        'use_background_image' => 'yes',
                                ],
                                'options' => [
                                        '' => esc_html__( 'Default', 'wprentals-core' ),
                                        'no-repeat' => esc_html__( 'No Repeat', 'wprentals-core' ),
                                        'repeat' => esc_html__( 'Repeat', 'wprentals-core' ),
                                        'repeat-x' => esc_html__( 'Repeat X', 'wprentals-core' ),
                                        'repeat-y' => esc_html__( 'Repeat Y', 'wprentals-core' ),
                                ],
                                'default' => 'no-repeat',
                                'selectors' => [
                                        '{{WRAPPER}} .wprentals-post-featured-image' => 'background-repeat: {{VALUE}};',
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
            is_singular( 'wpestate-studio' ) ||
			is_preview()) {
            
            $post_id = wpestate_last_post_id();
        
        }
       
        if ($post_id) {
            $post = get_post($post_id);

            $settings = $this->get_settings_for_display();

            $featured_image = get_the_post_thumbnail($post_id, $settings['thumbnail_size'], [
                'class' => 'wprentals-post-featured-image',
            ]);

            if ($settings['use_background_image'] === 'yes') {
                $background_styles = [];

                $thumbnail_url = get_the_post_thumbnail_url($post_id, $settings['thumbnail_size']);

                if (!empty($thumbnail_url)) {
                    $background_styles[] = 'background-image: url(' . esc_url($thumbnail_url) . ')';
                }

                $style_attribute = '';

                if (!empty($background_styles)) {
                    $style_attribute = ' style="' . esc_attr(implode('; ', $background_styles) . ';') . '"';
                }

                echo '<div class="wprentals-post-featured-image"' . $style_attribute . '></div>';
            }else{
				echo '<div class="wprentals-post-featured-image">' . $featured_image . '</div>';
		}
            
           
        }
    }


}
