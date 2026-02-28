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

class Wprentals_Single_Post_Author_Box extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Single_post_author_box';
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
        return esc_html__('WpRentals Single Post Author Box', 'wprentals-core');
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
        return 'wprentals-note eicon-site-identity';
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

	public function get_style_depends() {
        return [ 'wprentals-author-box-style' ];
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
        $this->add_control(
			'show_avatar',
			[
				'label' => esc_html__( 'Profile Picture', 'wprentals-core' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-author-box--avatar-',
				'label_on' => esc_html__( 'Show', 'wprentals-core' ),
				'label_off' => esc_html__( 'Hide', 'wprentals-core' ),
				'default' => 'yes',
				'separator' => 'before',
				'condition' => [
					'source' => 'current',
				],
				'render_type' => 'template',
			]
		);

		// Used by the WordPress `get_avatar_url()` function to set the image size.
		$this->add_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Picture Size', 'wprentals-core' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 300,
				'condition' => [
					'source' => 'current',
					'show_avatar' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_avatar',
			[
				'label' => esc_html__( 'Profile Picture', 'wprentals-core' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'source' => 'custom',
				],
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'show_name',
			[
				'label' => esc_html__( 'Display Name', 'wprentals-core' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-author-box--name-',
				'label_on' => esc_html__( 'Show', 'wprentals-core' ),
				'label_off' => esc_html__( 'Hide', 'wprentals-core' ),
				'default' => 'yes',
				'condition' => [
					'source' => 'current',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_name',
			[
				'label' => esc_html__( 'Name', 'wprentals-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Doe', 'wprentals-core' ),
				'condition' => [
					'source' => 'custom',
				],
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'author_name_tag',
			[
				'label' => esc_html__( 'HTML Tag', 'wprentals-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
				],
				'default' => 'h4',
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => esc_html__( 'Link', 'wprentals-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'wprentals-core' ),
					'website' => esc_html__( 'Website', 'wprentals-core' ),
					'posts_archive' => esc_html__( 'Posts Archive', 'wprentals-core' ),
				],
				'condition' => [
					'source' => 'current',
				],
				'description' => esc_html__( 'Link for the Author Name and Image', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'show_biography',
			[
				'label' => esc_html__( 'Biography', 'wprentals-core' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-author-box--biography-',
				'label_on' => esc_html__( 'Show', 'wprentals-core' ),
				'label_off' => esc_html__( 'Hide', 'wprentals-core' ),
				'default' => 'yes',
				'condition' => [
					'source' => 'current',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_link',
			[
				'label' => esc_html__( 'Archive Button', 'wprentals-core' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'elementor-author-box--link-',
				'label_on' => esc_html__( 'Show', 'wprentals-core' ),
				'label_off' => esc_html__( 'Hide', 'wprentals-core' ),
				'default' => 'no',
				'condition' => [
					'source' => 'current',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'author_website',
			[
				'label' => esc_html__( 'Link', 'wprentals-core' ),
				'type' => Controls_Manager::URL,
				'condition' => [
					'source' => 'custom',
				],
				'description' => esc_html__( 'Link for the Author Name and Image', 'wprentals-core' ),
			]
		);

		$this->add_control(
			'author_bio',
			[
				'label' => esc_html__( 'Biography', 'wprentals-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'wprentals-core' ),
				'rows' => 3,
				'condition' => [
					'source' => 'custom',
				],
				'separator' => 'before',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'posts_url',
			[
				'label' => esc_html__( 'Archive Button', 'wprentals-core' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'source' => 'custom',
				],
			]
		);

		$this->add_control(
			'link_text',
			[
				'label' => esc_html__( 'Archive Text', 'wprentals-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'All Posts', 'wprentals-core' ),
				'dynamic' => [
					'active' => true,
				],
				'ai' => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'wprentals-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wprentals-core' ),
						'icon' => 'eicon-h-align-left',
					],
					'above' => [
						'title' => esc_html__( 'Above', 'wprentals-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wprentals-core' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'separator' => 'before',
				'prefix_class' => 'wprentals-author-box--layout-image-',
			]
		);

		$this->add_control(
			'alignment',
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
				'prefix_class' => 'wprentals-author-box--align-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'wprentals-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				
			]
		);

		$this->add_control(
			'image_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'wprentals-core' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wprentals-core' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wprentals-core' ),
						'icon' => 'eicon-v-align-middle',
					],
				],
				'prefix_class' => 'wprentals-author-box--image-valign-',
			
			]
		);

		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 200,
					],
					'em' => [
						'max' => 20,
					],
					'rem' => [
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__avatar img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
			
			]
		);

		$this->add_responsive_control(
			'image_gap',
			[
				'label' => esc_html__( 'Gap', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}}.wprentals-author-box--layout-image-left .wprentals-author-box__avatar,
					 body:not(.rtl) {{WRAPPER}}:not(.wprentals-author-box--layout-image-above) .wprentals-author-box__avatar' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left: 0;',

					'body:not(.rtl) {{WRAPPER}}.wprentals-author-box--layout-image-right .wprentals-author-box__avatar,
					 body.rtl {{WRAPPER}}:not(.wprentals-author-box--layout-image-above) .wprentals-author-box__avatar' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right:0;',

					'{{WRAPPER}}.wprentals-author-box--layout-image-above .wprentals-author-box__avatar' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			
			]
		);

		$this->add_control(
			'image_border',
			[
				'label' => esc_html__( 'Border', 'wprentals-core' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__avatar img' => 'border-style: solid',
				],
			
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__avatar img' => 'border-color: {{VALUE}}',
				],
			
			]
		);

		$this->add_responsive_control(
			'image_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 20,
					],
					'em' => [
						'max' => 2,
					],
					'rem' => [
						'max' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__avatar img' => 'border-width: {{SIZE}}{{UNIT}}',
				],
			
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__avatar img' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .wprentals-author-box__avatar img',
				'fields_options' => [
					'box_shadow_type' => [
						'separator' => 'default',
					],
				],
			
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Author', 'wprentals-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_name_style',
			[
				'label' => esc_html__( 'Name', 'wprentals-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__name' => 'color: {{VALUE}}',
				],
				
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .wprentals-author-box__name',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				
			]
		);

		$this->add_responsive_control(
			'name_gap',
			[
				'label' => esc_html__( 'Gap', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__name' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				
			]
		);

		$this->add_control(
			'heading_bio_style',
			[
				'label' => esc_html__( 'Biography', 'wprentals-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label' => esc_html__( 'Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__bio' => 'color: {{VALUE}}',
				],
			
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'selector' => '{{WRAPPER}} .wprentals-author-box__bio',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			
			]
		);

		$this->add_responsive_control(
			'bio_gap',
			[
				'label' => esc_html__( 'Gap', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__bio' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => 'Button',
				'tab' => Controls_Manager::TAB_STYLE,
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wprentals-core' ),
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button' => 'color: {{VALUE}}; border-color: {{VALUE}}',
				],
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button' => 'background-color: {{VALUE}}',
				],
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .wprentals-author-box__button',
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wprentals-core' ),
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button:hover' => 'border-color: {{VALUE}}; color: {{VALUE}};',
				],
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'wprentals-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button:hover' => 'background-color: {{VALUE}};',
				],
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_hover_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'default' => [
					'unit' => 'ms',
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button' => 'transition-duration: {{SIZE}}{{UNIT}}',
				],
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'wprentals-core' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'max' => 20,
					],
					'em' => [
						'max' => 2,
					],
					'rem' => [
						'max' => 2,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wprentals-core' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'max' => 10,
					],
					'rem' => [
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'wprentals-core' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .wprentals-author-box__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				// 'condition' => [
				// 	'link_text!' => '',
				// ],
			]
		);

		$this->end_controls_section();
       
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
            $post = get_post($post_id);
            $authorID = $post->post_author;
            $settings = $this->get_settings_for_display();
            $author = [];
            $link_tag = 'div';
            $link_url = '';
            $link_target = '';
            $author_name_tag = Utils::validate_html_tag( $settings['author_name_tag'] );

            $custom_src = true;

            $avatar_args['size'] = $settings['avatar_size'];

                // $user_id = get_the_author_meta( 'ID' );
            $author['avatar'] = get_avatar_url( $authorID, $avatar_args );
            $author['display_name'] = get_the_author_meta( 'display_name', $authorID );
            $author['website'] = get_the_author_meta( 'user_url', $authorID );
            $author['bio'] = get_the_author_meta( 'description', $authorID );
            $author['posts_url'] = get_author_posts_url( $authorID );

            // if ( 'current' === $settings['source'] ) {

            //     $avatar_args['size'] = $settings['avatar_size'];

            //     $user_id = get_the_author_meta( 'ID' );
            //     $author['avatar'] = get_avatar_url( $authorID, $avatar_args );
            //     $author['display_name'] = get_the_author_meta( 'display_name', $authorID );
            //     $author['website'] = get_the_author_meta( 'user_url', $authorID );
            //     $author['bio'] = get_the_author_meta( 'description', $authorID );
            //     $author['posts_url'] = get_author_posts_url( $authorID );

            // } elseif ( $custom_src ) {

            //     if ( ! empty( $settings['author_avatar']['url'] ) ) {
            //         $avatar_src = $settings['author_avatar']['url'];

            //         if ( $settings['author_avatar']['id'] ) {
            //             $attachment_image_src = wp_get_attachment_image_src( $settings['author_avatar']['id'], 'medium' );

            //             if ( ! empty( $attachment_image_src[0] ) ) {
            //                 $avatar_src = $attachment_image_src[0];
            //             }
            //         }

            //         $author['avatar'] = $avatar_src;
            //     }

            //     $author['display_name'] = $settings['author_name'];
            //     $author['website'] = $settings['author_website']['url'];
            //     $author['bio'] = wpautop( $settings['author_bio'] );
            //     $author['posts_url'] = $settings['posts_url']['url'];
            // }

            $print_avatar = ( ( ! $custom_src && 'yes' === $settings['show_avatar'] ) || ( $custom_src && ! empty( $author['avatar'] ) ) );
            $print_name = ( ( ! $custom_src && 'yes' === $settings['show_name'] ) || ( $custom_src && ! empty( $author['display_name'] ) ) );
            $print_bio = ( ( ! $custom_src && 'yes' === $settings['show_biography'] ) || ( $custom_src && ! empty( $author['bio'] ) ) );
            $print_link = ( ( ! $custom_src && 'yes' === $settings['show_link'] ) && ! empty( $settings['link_text'] ) || ( $custom_src && ! empty( $author['posts_url'] ) && ! empty( $settings['link_text'] ) ) );

            if ( ! empty( $settings['link_to'] ) || $custom_src ) {
                if ( ( $custom_src || 'website' === $settings['link_to'] ) && ! empty( $author['website'] ) ) {
                    $link_tag = 'a';
                    $link_url = $author['website'];

                    $link_target = '_blank';
                } elseif ( 'posts_archive' === $settings['link_to'] && ! empty( $author['posts_url'] ) ) {
                    $link_tag = 'a';
                    $link_url = $author['posts_url'];
                }

                if ( ! empty( $link_url ) ) {
                    $this->add_render_attribute( 'author_link', 'href', esc_url( $link_url ) );

                    if ( ! empty( $link_target ) ) {
                        $this->add_render_attribute( 'author_link', 'target', $link_target );
                    }
                }
            }

            $this->add_render_attribute(
                'button',
                'class', [
                    'wprentals-author-box__button',
                    'wprentals-button',
                    'wprentals-size-xs',
                ]
            );

            if ( $print_link ) {
                $this->add_render_attribute( 'button', 'href', esc_url( $author['posts_url'] ) );
            }

            if ( $print_link && ! empty( $settings['button_hover_animation'] ) ) {
                $this->add_render_attribute(
                    'button',
                    'class',
                    'wprentals-animation-' . $settings['button_hover_animation']
                );
            }

            if ( $print_avatar ) {
                $this->add_render_attribute(
                    'avatar',
                    [
                        'src' => esc_url( $author['avatar'] ),
                        'alt' => ( ! empty( $author['display_name'] ) )
                            ? sprintf(
                                /* translators: %s: Author display name. */
                                esc_attr__( 'Picture of %s', 'wprentals-core' ),
                                $author['display_name']
                            )
                            : esc_html__( 'Author picture', 'wprentals-core' ),
                        'loading' => 'lazy',
                    ]
                );
            }

            ?>
            <div class="wprentals-author-box">
                <?php if ( $print_avatar ) { ?>
                    <<?php Utils::print_validated_html_tag( $link_tag ); ?> <?php $this->print_render_attribute_string( 'author_link' ); ?> class="elementor-author-box__avatar">
                        <img <?php $this->print_render_attribute_string( 'avatar' ); ?>>
                    </<?php Utils::print_validated_html_tag( $link_tag ); ?>>
                <?php } ?>

                <div class="wprentals-author-box__text">
                    <?php if ( $print_name ) : ?>
                        <<?php Utils::print_validated_html_tag( $link_tag ); ?> <?php $this->print_render_attribute_string( 'author_link' ); ?>>
                            <<?php Utils::print_validated_html_tag( $author_name_tag ); ?> class="elementor-author-box__name">
                                <?php Utils::print_unescaped_internal_string( $author['display_name'] ); ?>
                            </<?php Utils::print_validated_html_tag( $author_name_tag ); ?>>
                        </<?php Utils::print_validated_html_tag( $link_tag ); ?>>
                    <?php endif; ?>

                    <?php if ( $print_bio ) : ?>
                        <div class="wprentals-author-box__bio">
                            <?php Utils::print_unescaped_internal_string( $author['bio'] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( $print_link ) : ?>
                        <a <?php $this->print_render_attribute_string( 'button' ); ?>>
                            <?php $this->print_unescaped_setting( 'link_text' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }

        // use the above post_it to get all post details you need

    }

}