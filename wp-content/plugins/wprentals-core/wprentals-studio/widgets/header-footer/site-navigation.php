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
use Elementor\Core\Files\Assets\Svg\Svg_Handler;


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


class Wprentals_Navigation_Menu extends Widget_Base {


    public function get_name() {
        return 'wprentals_navigation_menu';
    }

    public function get_title() {
        return esc_html__( 'Navigation Menu', 'wprentals-core' );
    }

    public function get_icon() {
        return 'wprentals-note eicon-nav-menu';
    }

 
   
    public function get_categories() {
        return ['wprentals_header'];
    }

 
    private function get_available_menus() {
            $menus = wp_get_nav_menus();

            $options = array();

            foreach ( $menus as $menu ) {
                    $options[ $menu->slug ] = $menu->name;
            }

            return $options;
    }


	protected function register_controls() {
	
		
		 $this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'wprentals-core' ),
			]
		);

		$menu_options = $this->get_available_menus();

		if( $menu_options  ) {
			$this->add_control(
				'menu_slug',
				[
					'label' => __( 'Menu', 'wprentals-core' ),
					'type' => 'select',
					'default' => isset( array_keys( $menu_options )[0] ) ? array_keys( $menu_options )[0] : '',
					'options' => $menu_options,
					'description' => sprintf( __( 'Go to the <a href="%s" target="_blank">Menus Screen</a> to manage your menus.', 'wprentals-core' ), admin_url( 'nav-menus.php' ) ),

				]
			);
		} else {
			$this->add_control(
				'menu_slug',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf( __( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus Screen</a> to create one.', 'wprentals-core' ), self_admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                )
			);
		}

	

   

		$this->add_control(
			'hover_class',
			[
				'label' => esc_html__( 'Hover', 'wprentals-core' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
                                        '1' => 'type 1',
                                        '2' => 'type 2',
                                        '3' => 'type 3',
                                        '4' => 'type 4',
                                        '5' => 'type 5',
                                        '6' => 'type 6',
				],
				'style_transfer' => true,
				
			]
		);

              

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
                
        $this->add_control(
            'mobile_heading',
            [
                'label' => esc_html__( 'Mobile Dropdown', 'wprentals-core' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
             
            ]
        );

        $this->add_control(
			'mobile-menu-breakpoint',
			[
				'label'   => esc_html__( 'Breakpoint', 'wprentals-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'tablet',
				'options' => [
					'tablet' => esc_html__( 'Tablet Portrait & Less', 'wprentals-core' ),
					'mobile' => esc_html__( 'Mobile Portrait & Less', 'wprentals-core' ),
					'none'   => esc_html__( 'None', 'wprentals-core' ),
				],
			
			]
		);

		
	
		
                $this->start_controls_tabs( 'nav_icon_options' );

		$this->start_controls_tab( 'mobile_icon_options_closed', [
			'label' => esc_html__( 'Menu Closed Icon', 'wprentals-core' ),
		] );

		$this->add_control(
			'icon_closed',
			[
				'label' => esc_html__( 'Icon', 'wprentals-core' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => esc_html__( 'Default', 'wprentals-core' ),
							'icon' => 'eicon-menu-bar',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-solid' => [
                                                'bars',
                                                'plus',
						'plus-square',
						'plus-circle',
						
					],
					'fa-regular' => [
						'plus-square',
					],
				],
			]
		);

		$this->end_controls_tab();


		$this->start_controls_tab( 'mobile_icon_options_opened', [
			'label' => esc_html__( 'Menu Open Icon', 'wprentals-core' ),
		] );

		$this->add_control(
			'icon_opened',
			[
				'label' => esc_html__( 'Icon', 'wprentals-core' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => esc_html__( 'Default', 'wprentals-core' ),
							'icon' => 'eicon-close',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-solid' => [
						'times',
						'times-circle',
						'window-close',
						'minus',
						'minus-square',
						'minus-circle',
						
					],
					'fa-regular' => [
						'window-close',
						'times-circle',
						'minus-square',
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	
		
		$this->end_controls_section();
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                
       
                
                
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$this->start_controls_section(
			'section_style_main-menu',
			[
				'label' => esc_html__( 'Menu', 'wprentals-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				

			]
		);

	
                $this->add_control(
                    'hover_class_color_menu_item_hover',
                    [
                        'label' => esc_html__( 'Hover Border / Background Color', 'wprentals-core' ),
                        'type' => Controls_Manager::COLOR,
                        'global' => [
                            'default' => Global_Colors::COLOR_ACCENT,
                        ],
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .wprentals-studio-menu.hover_type_2 #access.wprentals-navigation-menu .menu > li:hover > a.menu-item-link:before' => 'border-top: 3px solid {{VALUE}};',
                            '{{WRAPPER}} .wprentals-studio-menu.hover_type_3 #access.wprentals-navigation-menu .menu > li:hover > a.menu-item-link::before' => 'background: {{VALUE}} !important;',
                            '{{WRAPPER}} .wprentals-studio-menu.hover_type_4 #access.wprentals-navigation-menu .menu > li:hover > a.menu-item-link' => 'background-color: {{VALUE}};',
                            '{{WRAPPER}} .wprentals-studio-menu.hover_type_5 #access.wprentals-navigation-menu .menu > li:hover > a.menu-item-link:before' => 'background: {{VALUE}}',
                            '{{WRAPPER}} .wprentals-studio-menu.hover_type_6 #access.wprentals-navigation-menu .menu > li:hover > a.menu-item-link:before' => 'border-color: {{VALUE}} !important;',
                        
                        ],
                    ]
                );
                                
                                
                                

		$this->add_group_control(
			Group_Control_Typography::get_type(),
                        [
                                'name' => 'menu_typography',
                                'global' => [
                                        'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                                ],
                            'selector' => '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > li > a.menu-item-link',
                        ]
		);

		$this->start_controls_tabs( 'tabs_menu_item_style' );

		$this->start_controls_tab(
			'tab_menu_item_normal',
			[
				'label' => esc_html__( 'Normal', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_menu_item',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default' => '',
                                'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > li > a.menu-item-link' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
///////hoverx //////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->start_controls_tab(
			'tab_menu_item_hover',
			[
				'label' => esc_html__( 'Hover', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_menu_item_hover',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
                                'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > li:hover > a.menu-item-link,
                                     {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > li:focus > a.menu-item-link' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
				
			]
		);

		

		
		$this->end_controls_tab();

        
                
                
                
		$this->start_controls_tab(
			'tab_menu_item_active',
			[
				'label' => esc_html__( 'Active', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_menu_item_active',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
                                'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > li.current-menu-item > a.menu-item-link,
                                         {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > li.current-menu-parent > a.menu-item-link,
                                         {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > li.current-menu-ancestor > a.menu-item-link' => 'color: {{VALUE}}',
				],
			]
		);

           
                
            
		

		$this->end_controls_tab();

		$this->end_controls_tabs();


// dividers//////////////////////////////////////////////////////////////////////
                $this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);


                $this->add_control(
                        'nav_menu_divider_style',
                        [
                                'label' => esc_html__( 'Divider Style', 'wprentals-core' ),
                                'type' => Controls_Manager::SELECT,
                                'options' => [
                                        'solid' => esc_html__( 'Solid', 'wprentals-core' ),
                                        'double' => esc_html__( 'Double', 'wprentals-core' ),
                                        'dotted' => esc_html__( 'Dotted', 'wprentals-core' ),
                                        'dashed' => esc_html__( 'Dashed', 'wprentals-core' ),
                                ],
                                'default' => 'solid',
                                'selectors' => [
                                        '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu>li:not(:last-child)' => 'position:relative;',
                                        '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu>li:not(:last-child)::after' => 'content:""; position:absolute; top:50%; right:0; transform:translateY(-50%); display:block; border-left-style: {{VALUE}};',
                                ],

                        ]
                );

		$this->add_control(
			'nav_menu_divider_weight',
			[
				'label' => esc_html__( 'Divider Width', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
			
                                'selectors' => [
                                        '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu> li:not(:last-child)::after' => 'border-left-width: {{SIZE}}{{UNIT}}',
                                ],
			]
		);

		$this->add_control(
			'nav_menu_divider_height',
			[
				'label' => esc_html__( 'Divider Height', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
		
                                'selectors' => [
                                        '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu> li:not(:last-child)::after' => 'height: {{SIZE}}{{UNIT}}',
                                ],
			]
		);

		$this->add_control(
			'nav_menu_divider_color',
			[
				'label' => esc_html__( 'Divider Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			
                                'selectors' => [
                                        '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu> li:not(:last-child)::after' => 'border-color: {{VALUE}}',
                                ],
			]
		);

                
        
		$this->add_control(
			'hr1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
                

		$this->add_responsive_control(
			'menu_item_padding',
			[
				'label'      => esc_html__( 'Menu Item Padding', 'wprentals-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
                                'selectors'  => [
                                        '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu>li>a.menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                        '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu' => 'padding: 0px!important;',
				],
			]
		);

		$this->end_controls_section();

                
                
		$this->start_controls_section(
			'section_style_dropdown',
			[
				'label' => esc_html__( 'Menu Dropdown', 'wprentals-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

	

		$this->start_controls_tabs( 'tabs_dropdown_item_style' );

		$this->start_controls_tab(
			'tab_dropdown_item_normal',
			[
				'label' => esc_html__( 'Normal', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_dropdown_item',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu .sub-menu a.menu-item-link, {{WRAPPER}} .wpestate_mega_menu2_wrapper a' => 'color: {{VALUE}}',
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .megamenu-title a:hover' => 'color: {{VALUE}}!important;cursor:default;',
				],
			]
		);

		$this->add_control(
			'background_color_dropdown_item',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu .sub-menu, {{WRAPPER}} .wpestate_mega_menu2_wrapper' => 'background-color: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_item_hover',
			[
				'label' => esc_html__( 'Hover', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_dropdown_item_hover',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu .sub-menu a.menu-item-link:hover, {{WRAPPER}} .wpestate_mega_menu2_wrapper a:hover'  => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'background_color_dropdown_item_hover',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu .sub-menu li:hover'  => 'background-color: {{VALUE}}',
                                    '{{WRAPPER}} .wpestate_mega_menu2_wrapper>.wpestate_megamenu_class>li:hover' =>'background-color:transparent!important ',
				],
				'separator' => 'none',
			]
		);

		

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'dropdown_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'exclude' => [ 'line_height' ],
                            'selector' => '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu .sub-menu a.menu-item-link, {{WRAPPER}} .wpestate_mega_menu2_wrapper a' ,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_border',
                            'selector' => '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > .menu-item > .sub-menu, {{WRAPPER}} .wpestate_mega_menu2_wrapper',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'dropdown_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wprentals-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > .menu-item > .sub-menu, {{WRAPPER}} .wpestate_mega_menu2_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
    	],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'dropdown_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
                            'selector' => '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .menu > .menu-item > .sub-menu, {{WRAPPER}} .wpestate_mega_menu2_wrapper',
			]
		);
//v
		   $this->add_responsive_control(
                         'dropitem_content_padding',
                         [
                             'label'      => esc_html__( 'Dropdown Item Padding', 'wprentals-core' ),
                             'type'       => Controls_Manager::DIMENSIONS,
                             'size_units' => [ 'px', '%', 'em' ],
                             'selectors'  => [
                                 ' {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li, {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                             ],
                         ]
                     );
                   
                   
                   
		$this->add_control(
			'heading_dropdown_divider',
			[
				'label' => esc_html__( 'Divider', 'wprentals-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_divider',
				'selector' => 
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li:not(:last-child), {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li:not(:last-child)',
				'exclude' => [ 'width','color' ],
			]
		);

                
                
                	$this->add_control(
			'dropdown_divider_color',
			[
				'label' => esc_html__( 'Border Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default' => '',
				'selectors' => [
                                    '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li:not(:last-child), {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li:not(:last-child)' => 'border-bottom-color:{{VALUE}}',
				],
			]
		);
                
		$this->add_control(
			'dropdown_divider_width',
			[
				'label' => esc_html__( 'Border Width', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 2,
					],
				],
				'selectors' => [
                                '{{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li:not(:last-child), {{WRAPPER}} .wprentals-studio-menu #access.wprentals-navigation-menu .sub-menu > li:not(:last-child)' => 'border-width:0px;border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			
			]
		);


		$this->end_controls_section();
    
            $this->start_controls_section(
			'section_style_dropdown_mobile',
			[
				'label' => esc_html__( 'Mobile Menu Dropdown', 'wprentals-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

	

		$this->start_controls_tabs( 'tabs_dropdown_item_style_mobile' );

		$this->start_controls_tab(
			'tab_dropdown_item_normal_mobile',
			[
				'label' => esc_html__( 'Normal', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_dropdown_item_mobile',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mobilex-menu a' => 'color: {{VALUE}};    transition: none;',
       
				],
			]
		);


		$this->add_control(
			'background_color_dropdown_item_mobile',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpestate-elementor-menu-mobile-container .mobilex-menu'=> 'background-color: {{VALUE}};    transition: none;',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_item_hover_mobile',
			[
				'label' => esc_html__( 'Hover', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_dropdown_item_hover_mobile',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}  .mobilex-menu a:hover' => 'color: {{VALUE}};    transition: none;',
					'{{WRAPPER}}  .mobilex-menu li:hover a' => 'color: {{VALUE}};    transition: none;',		
				],
			]
		);

		$this->add_control(
			'background_color_dropdown_item_hover_mobile',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .mobilex-menu li:hover'  => 'background-color: {{VALUE}};    transition: none;',
					'{{WRAPPER}} .mobilex-menu li a:hover'  => 'background-color: {{VALUE}};    transition: none;',
					'{{WRAPPER}} .mobilex-menu .sub-menu li:hover'  => 'background-color: {{VALUE}};    transition: none;',
					'{{WRAPPER}} .mobilex-menu li:hover a'  => 'background-color: {{VALUE}};    transition: none;',
				],
				'separator' => 'none',
			]
		);

		

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'dropdown_typography_mobile',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'exclude' => [ 'line_height' ],
				'selector' => '{{WRAPPER}}  .mobilex-menu  a' ,
				'separator' => 'before',
			]
		);

	
	


		   $this->add_responsive_control(
                         'dropitem_content_padding_mobile_2',
                         [
                             'label'      => esc_html__( 'Dropdown Item Padding', 'wprentals-core' ),
                             'type'       => Controls_Manager::DIMENSIONS,
                             'size_units' => [ 'px', '%', 'em' ],
                             'selectors'  => [
                                 ' {{WRAPPER}}  .mobilex-menu li ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                             ],
                         ]
                     );
                   
                   
                   
		$this->add_control(
			'heading_dropdown_divider_mobile',
			[
				'label' => esc_html__( 'Divider', 'wprentals-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_divider_mobile',
				'selector' => 
					'{{WRAPPER}} .mobilex-menu li:not(:last-child)',
				'exclude' => [ 'width','color' ],
			]
		);

                
                
		$this->add_control(
			'dropdown_divider_color_mobile',
			[
				'label' => esc_html__( 'Border Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default' => '',
				'selectors' => [
						'{{WRAPPER}} .mobilex-menu li' => 'border-bottom: 1px solid {{VALUE}};',
						'{{WRAPPER}} .mobilex-menu .sub-menu li:hover:not(:last-child)' => 'border-bottom: 1px solid {{VALUE}};',
				],
			]
		);
                
		$this->add_control(
			'dropdown_divider_width_mobile',
			[
				'label' => esc_html__( 'Border Width', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 2,
					],
				],
				'selectors' => [
				    '{{WRAPPER}} .mobilex-menu li:not(:last-child)' => 'border-width:0px;border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			
			]
		);

		
		$this->add_responsive_control(
			'mobile_menu_width',
			[
				'label' => esc_html__('Mobile Menu Width (%)', 'wprentals-core'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => ['min' => 20, 'max' => 100],
				],
				'default' => [
					'size' => 100,
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .mobilex-menu' => 'width: {{SIZE}}% !important;',
				],
			]
		);

		$this->end_controls_section();


		   
		$this->start_controls_section(
			'section_style_toggle_mobile',
			[
				'label' => esc_html__( 'Toggle Button', 'wprentals-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
                
		$this->add_responsive_control(
			'toogle_menu_item_padding',
			[
				'label'      => esc_html__( 'Menu Item Padding', 'wprentals-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
				'{{WRAPPER}} .wpestate_mobile_menu_trigger_close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .wpestate_mobile_menu_trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

   		$this->start_controls_tabs( 'tabs_dropdown_item_style_mobile_toggle' );
                
		$this->start_controls_tab(
			'tab_dropdown_item_normal_toggle',
			[
				'label' => esc_html__( 'Normal', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_dropdown_item_mobile_toggle',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpestate_mobile_menu_trigger' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger_close' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger svg path' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger_close svg path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color_dropdown_item_mobile_toggle',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpestate_mobile_menu_trigger' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger_close' => 'background-color: {{VALUE}};',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dropdown_item_hover_mobile_toggle',
			[
				'label' => esc_html__( 'Hover', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'color_dropdown_item_hover_mobile_toggle',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpestate_mobile_menu_trigger:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger_close:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger:hover svg path' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger_close:hover svg path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color_dropdown_item_hover_mobile_toggle',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpestate_mobile_menu_trigger:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpestate_mobile_menu_trigger_close:hover' => 'background-color:{{VALUE}};',
               
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_tab();


		$this->end_controls_tabs();
                
                
		$this->end_controls_section();
	}

	/**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
    
        $menus = $this->get_available_menus();

        if ( empty( $menus ) ) {
                return;
        }

        $token = wp_generate_password(5, false, false);

        $settings = $this->get_active_settings();

        $show_desktop = $show_menu_desktop = $show_menu_tablet = $mb_fullwidth = '';
        $mobile_menu_breakpoint = $settings['mobile-menu-breakpoint'];

        $extra_class = 'menu ';
		
		if( $mobile_menu_breakpoint == 'dektop' ) {
			$show_menu_desktop = '';
        }else if( $mobile_menu_breakpoint == 'mobile' ) {
			$show_menu_desktop = 'wpestate-show-menu-desktop';
			$show_menu_tablet = 'wpestate-show-menu-tablet';
        } else if( $mobile_menu_breakpoint == 'tablet' ) {
			$show_menu_desktop = 'wpestate-show-menu-desktop';
        }

                
                
        $walker_class = 'wpestate_custom_walker';

        if ( ! class_exists( $walker_class ) ) {
                $megamenu_template = \locate_template( 'libs/megamenu.php', false, false );

                if ( ! empty( $megamenu_template ) ) {
                        require_once $megamenu_template;
                }
        }

        if ( ! class_exists( $walker_class ) ) {
                return;
        }

        $walker_fqn = '\\' . $walker_class;

        $args = [
                'menu' => $settings['menu_slug'],
                'menu_class' => $extra_class,
                'container' => false,
                'fallback_cb' => '__return_empty_string',
                'echo' => false,
                'depth' => 4,
                'walker' => new $walker_fqn(),
        ];

        $wrapper_class = 'hover_type_' . \sanitize_html_class( $settings['hover_class'] );
        $nav_classes = array_filter( [
                'wprentals-navigation-menu',
                'px-5',
                'py-0',
                'navbar',
                'navbar-expand-lg',
                $show_menu_desktop,
                $show_menu_tablet,
                'wpestate-hide-menu-' . \sanitize_html_class( $mobile_menu_breakpoint ),
        ] );

        $nav_classes = array_map( 'sanitize_html_class', $nav_classes );
        $nav_class_attribute = implode( ' ', $nav_classes );

        $menu_html  = '<div class="wprentals-studio-menu ' . esc_attr( $wrapper_class ) . '">';
        $menu_html .= '<nav id="access" class="' . esc_attr( $nav_class_attribute ) . '">';
        $menu_html .= wp_nav_menu( $args );
        $menu_html .= '</nav>';
        $menu_html .= '</div>';

                      

                $mobile_args= array( 
                  'theme_location'    =>  'mobile',               
                  'container'         =>  false,
                  'menu_class'        =>  'mobilex-menu',
           
                'echo' => false,
                ) ;
                
                
                $mobile_menu_html   ='<div class="wpestate-elementor-menu-mobile-container   wpestate-show-menu-'.esc_attr($mobile_menu_breakpoint).'  " >';
                ob_start();
                ?>
                
                <div class="wpestate_mobile_menu_trigger">
                    
                    <?php 
                    
                    if( ! empty( $settings['icon_closed']['value'] )  ) {
                       \Elementor\Icons_Manager::render_icon($settings['icon_closed'], ['aria-hidden' => 'true']);
                    }else{
                        $burger_icon_template = locate_template( 'css/css-images/svg-icons/burger-menu.svg' );

                        if ( ! empty( $burger_icon_template ) && file_exists( $burger_icon_template ) ) {
                            include $burger_icon_template;
                        } else {
                            \Elementor\Icons_Manager::render_icon(
                                [
                                    'value'   => 'eicon-menu-bar',
                                    'library' => 'eicons',
                                ],
                                [ 'aria-hidden' => 'true' ]
                            );
                        }
                    }
                  ?>
                </div>
                
                <div class="wpestate_mobile_menu_trigger_close" >
                    <?php 
                    if( ! empty( $settings['icon_opened']['value'] )  ) {
                        \Elementor\Icons_Manager::render_icon($settings['icon_opened'], ['aria-hidden' => 'true']);
                    }else{
                        $close_icon_template = locate_template( 'css/css-images/svg-icons/close-button.svg' );

                        if ( ! empty( $close_icon_template ) && file_exists( $close_icon_template ) ) {
                            include $close_icon_template;
                        } else {
                            \Elementor\Icons_Manager::render_icon(
                                [
                                    'value'   => 'eicon-close',
                                    'library' => 'eicons',
                                ],
                                [ 'aria-hidden' => 'true' ]
                            );
                        }
                    }
                    
                    
                  ?>
                </div>
                
                <?php 
                
                $buttons = ob_get_contents();
                ob_end_clean();
                $mobile_menu_html .=$buttons;
                $mobile_menu_html   .=  wp_nav_menu( $mobile_args );
                $mobile_menu_html   .='</div>';
       

                
                
                
		if ( empty( $menu_html ) ) {
                    return;
		}
                
        
                print $menu_html;
                print $mobile_menu_html;
              
     

    }

   

}
