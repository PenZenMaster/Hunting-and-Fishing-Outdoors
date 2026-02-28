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

class Wprentals_Site_Social extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Site_Social';
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
        return esc_html__('Social Networks Icons', 'wprentals-core');
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
        return 'wprentals-note eicon-site-logo';
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
        return ['wprentals_header'];
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
            $defaults = array(  
                                    'facebook'      => esc_html__('Facebook Link:','wprentals-core'),
                                    'whatsup'       => esc_html__('WhatsApp Link:','wprentals-core'),
                                    'telegram'      => esc_html__('Telegram Link:','wprentals-core'),
                                    'tiktok'        => esc_html__('TikTok Link:','wprentals-core'),
                                    'rss'           => esc_html__('Rss Link:','wprentals-core'),
                                    'twitter'       => esc_html__('x - Twitter Link:','wprentals-core'),
                                    'dribbble'      => esc_html__('Dribble Link:','wprentals-core'),
                                    'google'        => esc_html__('Google+ Link:','wprentals-core'),
                                    'linkedIn'      => esc_html__('LinkedIn Link:','wprentals-core'),                            
                                    'tumblr'        => esc_html__('Tumblr Link:','wprentals-core'),
                                    'pinterest'     => esc_html__('Pinterest Link:','wprentals-core'),                               
                                    'youtube'       => esc_html__('Youtube Link:','wprentals-core'),
                                    'vimeo'         => esc_html__('Vimeo Link:','wprentals-core'),
                                    'instagram'     => esc_html__('Instagram Link:','wprentals-core'),
                                    'foursquare'    => esc_html__('Foursquare Link:','wprentals-core'),                  
                                    'line'          => esc_html__('Line Link:','wprentals-core'),
                                    'wechat'        => esc_html__('WeChat Link:','wprentals-core'),
                                    );
		
                
             foreach ($defaults as $key => $label) {
                $this->add_control(
                    $key . '_link',
                    [
                        'label' => $label,
                        'type' => \Elementor\Controls_Manager::TEXT,
                    ]
                );
            }

            $this->end_controls_section();
            
           $this->start_controls_section(
                'section_style', [
                'label' => esc_html__('Style',  'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                ]
            );
           
            $this->add_responsive_control(
                'wpersidence_form_column_gap', [
            'label' => esc_html__('Icon size',  'wprentals-core'),
            'type' => Controls_Manager::SLIDER,
            'default' => [
                'size' => 14,
            ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a' => 'font-size:  {{SIZE}}{{UNIT}};',
           ],
                ]
        );
                  
                  
            $this->add_control(
                  'unit_color',
                  [
                      'label'     => esc_html__( 'Color',  'wprentals-core' ),
                      'type'      => Controls_Manager::COLOR,
                      'default'   => '',
                      'selectors' => [
                          '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a' => 'color: {{VALUE}}',

                      ],
                  ]
              );
              
            
            $this->add_control(
            'unit_bck_color',
            [
                'label'     => esc_html__( 'Background Color',  'wprentals-core' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'transparent',
                'selectors' => [
                    '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a' => 'background-color: {{VALUE}}',
                ],
            ]
            );
            

           // Add Hover Icon Color control
            $this->add_control(
                'hover_icon_color',
                [
                    'label' => __( 'Hover Icon Color', 'wprentals-core' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a:hover' => 'color: {{VALUE}}',
                        '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a:hover i' => 'color: {{VALUE}}',
                    ],
                ]
            );

            // Add Hover Background Color control
            $this->add_control(
                'hover_background_color',
                [
                    'label' => __( 'Hover Background Color', 'wprentals-core' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a:hover' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
              
$this->add_control(
    'gap_icons', [
        'label' => esc_html__('Gap between icons', 'wprentals-core'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'em'],
        'range' => [
            'px' => [
                'min' => 0,
                'max' => 100,
            ],
        ],
        'selectors' => [
            '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal' => 'gap: {{SIZE}}{{UNIT}};',
        ],
    ]
);
            
             // Border right control
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border_right',
                'label' => __( 'Border Right',  'wprentals-core' ),
                'selector' => '{{WRAPPER}} .social_sidebar_internal a',
            ]
        );

    // Add Hover Border Color control
    $this->add_control(
        'hover_border_color',
        [
            'label' => __( 'Hover Border Color', 'wprentals-core' ),
            'type' => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a:hover' => 'border-color: {{VALUE}}',
            ],
        ]
    );   

    // Add Border Radius control
    $this->add_control(
        'border_radius',
        [
            'label' => __( 'Border Radius', 'wprentals-core' ),
            'type' => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em' ],
            'selectors' => [
                '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

       // Add Width control
       $this->add_responsive_control(
        'width',
        [
            'label' => __( 'Width', 'wprentals-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]
    );

    // Add Height control
    $this->add_responsive_control(
        'height',
        [
            'label' => __( 'Height', 'wprentals-core' ),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range' => [
                'px' => [
                    'min' => 0,
                    'max' => 200,
                ],
                '%' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .wprentals_elementor_social_sidebar_internal a' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]
    );



            $this->end_controls_section();
          

    }


  

/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 
	 * @since 1.0.0
	 *
	 * @access protected
	 */

    protected function render() {
        $settings = $this->get_settings_for_display();

        $defaults = array(
            'facebook'   => '<i class="fab fa-facebook-f"></i>',
            'whatsup'    => '<i class="fab fa-whatsapp"></i>',
            'telegram'   => '<i class="fab fa-telegram-plane"></i>',
            'tiktok'     => '<i class="fa-brands fa-tiktok"></i>',
            'rss'        => '<i class="fas fa-rss fa-fw"></i>',
            'twitter'    => '<i class="fa-brands fa-x-twitter"></i>',
            'dribbble'   => '<i class="fab fa-dribbble  fa-fw"></i>',
            'google'     => '<i class="fab fa-google-plus-g  fa-fw"></i>',
            'linkedIn'   => '<i class="fab fa-linkedin-in"></i>',
            'tumblr'     => '<i class="fab fa-tumblr  fa-fw"></i>',
            'pinterest'  => '<i class="fab fa-pinterest-p  fa-fw"></i>',
            'youtube'    => '<i class="fab fa-youtube  fa-fw"></i>',
            'vimeo'      => '<i class="fab fa-vimeo-v  fa-fw"></i>',
            'instagram'  => '<i class="fa-brands fa-instagram"></i>',
            'foursquare' => '<i class="fab  fa-foursquare  fa-fw"></i>',
            'line'       => '<i class="fab fa-line"></i>',
            'wechat'     => '<i class="fab fa-weixin"></i>',
        );

        $output = '<div class="social_sidebar_internal wprentals_elementor_social_sidebar_internal  wprentals_studio_elementor_social">';

        foreach ($defaults as $key => $icon) {
            $setting_key = $key . '_link';
            if (!empty($settings[$setting_key])) {
                $output .= '<a href="' . esc_url($settings[$setting_key]) . '" target="_blank">' . wp_kses_post($icon) . '</a>';
            }
        }

        $output .= '</div>';

        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }


}
