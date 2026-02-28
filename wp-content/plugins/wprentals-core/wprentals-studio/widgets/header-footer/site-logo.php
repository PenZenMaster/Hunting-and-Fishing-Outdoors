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

class Wprentals_Site_Logo extends Widget_Base {

    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'Site_Logo';
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
        return esc_html__('Website Logo', 'wprentals-core');
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
        $this->wprentals_site_logo_controls();
        $this->wprentals_site_logo_styling_controls();
        $this->wprentals_site_logo_caption_styling_controls();
    }

    /**
     * Register Site Logo Styling Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function wprentals_site_logo_styling_controls() {
        $this->start_controls_section(
            'section_style_wprentals_site_logo',
            [
                'label' => esc_html__('Website Logo', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'padding_vertical_logo',
            [
                'label' => esc_html__('Vertical Padding', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-site-logo' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_top',
            [
                'label' => __('Top', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-site-logo' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'wprentals-core'),
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
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'space',
            [
                'label' => __('Max Width', 'wprentals-core') . ' (%)',
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
                'size_units' => ['%'],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wprentals-site-logo' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'separator_panel_style',
            array(
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            )
        );

        $this->start_controls_tabs('image_effects');

        $this->start_controls_tab('normal',
            array(
                'label' => __('Normal', 'wprentals-core'),
            )
        );

        $this->add_control(
            'opacity',
            array(
                'label' => __('Opacity', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wprentals-site-logo img, {{WRAPPER}} .wprentals-site-logo .text-logo' => 'opacity: {{SIZE}};',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab('hover',
            array(
                'label' => __('Hover', 'wprentals-core'),
            )
        );

        $this->add_control(
            'opacity_hover',
            array(
                'label' => __('Opacity', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}  .wprentals-site-logo:hover img, {{WRAPPER}} .wprentals-site-logo:hover .text-logo' => 'opacity: {{SIZE}};',
                ),
            )
        );



        $this->add_control(
            'background_hover_transition',
            array(
                'label' => __('Transition Duration', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 3,
                        'step' => 0.1,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wprentals-site-logo img, {{WRAPPER}} .wprentals-site-logo .text-logo' => 'transition-duration: {{SIZE}}s',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name' => 'image_border',
                'selector' => '{{WRAPPER}} .wprentals-site-logo img, {{WRAPPER}} .wprentals-site-logo .text-logo',
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'image_border_radius',
            array(
                'label' => __('Border Radius', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .wprentals-site-logo img, {{WRAPPER}} .wprentals-site-logo .text-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'image_box_shadow',
                'exclude' => array(
                    'box_shadow_position',
                ),
                'selector' => '{{WRAPPER}} .wprentals-site-logo img, {{WRAPPER}} .wprentals-site-logo .text-logo',
            )
        );

        $this->end_controls_section();
    }

    /**
     * Register Site Logo Content Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function wprentals_site_logo_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Logo', 'wprentals-core'),
            ]
        );

        $this->add_control(
            'logo_source',
            [
                'label' => esc_html__('Logo Source', 'wprentals-core'),
                'type' => 'select',
                'options' => [
                    'wp_estate_logo_image' => esc_html__('Main Logo (Theme Options)', 'wprentals-core'),
                    'wp_estate_transparent_logo_image' => esc_html__('Transparent Logo (Theme Options)', 'wprentals-core'),
                    'wp_estate_mobile_logo_image' => esc_html__('Mobile Logo (Theme Options)', 'wprentals-core'),
                    'wp_estate_logo_image_retina' => esc_html__('Main Logo Retina (Theme Options)', 'wprentals-core'),
                    'wp_estate_transparent_logo_image_retina' => esc_html__('Transparent Logo Retina (Theme Options)', 'wprentals-core'),
                    'wp_estate_mobile_logo_image_retina' => esc_html__('Mobile Logo Retina (Theme Options)', 'wprentals-core'),
                    'custom_logo' => esc_html__('Custom Logo', 'wprentals-core'),
                ],
                'default' => 'wp_estate_logo_image',
            ]
        );

        $this->add_control(
            'important_note',
            [
                'type' => 'raw_html',
                'raw' => esc_html__('Please select or upload your Logo in Theme Options.', 'wprentals-core'),
                 'content_classes' => 'elementor-control-field-description',
                'condition' => [
                    'logo_source!' => 'custom_logo',
                ],
            ]
        );

        $this->add_control(
            'custom_image',
            [
                'label' => esc_html__('Choose Image', 'wprentals-core'),
                'type' => 'media',
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'logo_source' => 'custom_logo',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'logo_size',
                'label' => __('Image Size', 'wprentals-core'),
                'default' => 'medium',
                'condition' => [
                    'logo_source' => 'custom_logo',
                ],
            ]
        );

       

        $this->add_control(
            'caption_source',
            [
                'label' => __('Caption', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'no' => __('No', 'wprentals-core'),
                    'yes' => __('Yes', 'wprentals-core'),
                ],
                'default' => 'no',
                'condition' => [
                    'logo_source' => 'custom_logo',
                ],
            ]
        );

        $this->add_control(
            'caption',
            [
                'label' => __('Custom Caption', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter caption', 'wprentals-core'),
                'condition' => [
                    'caption_source' => 'yes',
                    'logo_source' => 'custom_logo',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link_to',
            [
                'label' => __('Link', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'wprentals-core'),
                    'none' => __('None', 'wprentals-core'),
                    'custom' => __('Custom URL', 'wprentals-core'),
                ],
                'condition' => [
                    'logo_source' => 'custom_logo'
                ]
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __('Link', 'wprentals-core'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => __('https://your-link.com', 'wprentals-core'),
                'condition' => [
                    'link_to' => 'custom',
                ],
                'show_label' => false,
            ]
        );
        $this->end_controls_section();
    }

    /**
     * Register Site Logo Caption Styling Controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function wprentals_site_logo_caption_styling_controls() {
        $this->start_controls_section(
            'section_style_caption',
            [
                'label' => __('Caption', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'caption_source!' => 'no',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .site-tagline' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'caption_background_color',
            [
                'label' => __('Background Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .site-tagline' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .site-tagline',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'caption_text_shadow',
                'selector' => '{{WRAPPER}} .site-tagline',
            ]
        );

        $this->add_responsive_control(
            'caption_padding',
            [
                'label' => __('Padding', 'wprentals-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .site-tagline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'caption_space',
            [
                'label' => __('Spacing', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'default' => [
                        'size' => 0,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .site-tagline' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0px;',
                    ],
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Check if the logo has a caption.
     *
     * @param array $settings The widget settings.
     * @return bool True if the logo has a caption, false otherwise.
     */
    private function has_caption($settings) {
        return (!empty($settings['caption_source']) && 'no' !== $settings['caption_source'] );
    }

    /**
     * Get the caption text.
     *
     * @param array $settings The widget settings.
     * @return string The caption text.
     */
    private function get_caption($settings) {
        $caption = '';
        if ('yes' === $settings['caption_source']) {
            $caption = !empty($settings['caption']) ? $settings['caption'] : '';
        }
        return $caption;
    }

    /**
     * Get the site logo image URL.
     *
     * @param string $size The image size.
     * @return string The site logo image URL.
     */
    public function site_image_url($size) {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['custom_image']['url'])) {
            $logo = wp_get_attachment_image_src($settings['custom_image']['id'], $size, true);
        } else {
            $logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'), $size, true);
        }
        return $logo[0];
    }

    /**
     * Render the widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $has_caption = $this->has_caption($settings);
        $logo_source = $settings['logo_source'];

        if (empty($logo_source) || 'theme_option' === $logo_source) {
            $logo_source = 'wp_estate_logo_image';
        }

        if ('default' === $settings['link_to']) {
            $link = site_url();
            $this->add_render_attribute('link', 'href', $link);
        } else {
            $link = $this->get_link_url($settings);

            if ($link) {
                $this->add_link_attributes('link', $link);
            }
        }
        ?>
        <div class="wprentals-site-logo">
        <?php if ($link) : ?>
                <a <?php echo $this->get_render_attribute_string('link'); ?>>
        <?php endif; ?>
        <?php
        if ('custom_logo' === $logo_source) {
            $this->custom_logo_render($settings);

            if ($has_caption) {
                $caption_text = $this->get_caption($settings);
                if (!empty($caption_text)) {

                    echo '<p class="site-tagline">' . wp_kses_post($caption_text) . '</p>';
                }
            }
        }

        if ('custom_logo' !== $logo_source) {
            $this->themeoption_logo_render($logo_source);
        }
        ?>
                <?php if ($link) : ?>
                </a>
                <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render the custom logo.
     *
     * @param array $settings The widget settings.
     */
    protected function custom_logo_render($settings) {
        $has_caption = $this->has_caption($settings);
        $size = $settings['logo_size_size'];
        $site_image = $this->site_image_url($size);
        $img_animation = '';

        if (!empty($site_image)) {

            if ('custom' !== $size) {
                $image_size = $size;
            } else {
                require_once ELEMENTOR_PATH . 'includes/libraries/bfi-thumb/bfi-thumb.php';

                $image_dimension = $settings['logo_size_custom_dimension'];

                $image_size = [
                    0 => null, // Width.
                    1 => null, // Height.
                    'bfi_thumb' => true,
                    'crop' => true,
                ];

                $has_custom_size = false;
                if (!empty($image_dimension['width'])) {
                    $has_custom_size = true;
                    $image_size[0] = $image_dimension['width'];
                }

                if (!empty($image_dimension['height'])) {
                    $has_custom_size = true;
                    $image_size[1] = $image_dimension['height'];
                }

                if (!$has_custom_size) {
                    $image_size = 'full';
                }
            }

            $image_url = $site_image;

            if (!empty($settings['custom_image']['url'])) {
                $image_data = wp_get_attachment_image_src($settings['custom_image']['id'], $image_size, true);

                $site_image_class = 'elementor-animation-';

                if (!empty($settings['hover_animation'])) {
                    $img_animation = $settings['hover_animation'];
                }
                if (!empty($image_data)) {
                    $image_url = $image_data[0];
                }

                $class_animation = $site_image_class . $img_animation;

                echo '<img class="image-logo ' . esc_attr($class_animation) . '"  src="' . esc_url($image_url) . '" alt="' . esc_attr(Control_Media::get_image_alt($settings['custom_image'])) . '"/>';
            }
        }
    }

    /**
     * Render the theme option logo.
     */
    protected function themeoption_logo_render($logo_source) {
        $logo_data = wprentals_get_option($logo_source);

        $logo_url = '';
        $logo_id = 0;
        $logo_width = 0;
        $logo_height = 0;

        if (is_array($logo_data)) {
            $logo_url = isset($logo_data['url']) ? $logo_data['url'] : '';
            $logo_id = isset($logo_data['id']) ? intval($logo_data['id']) : 0;
            $logo_width = isset($logo_data['width']) ? intval($logo_data['width']) : 0;
            $logo_height = isset($logo_data['height']) ? intval($logo_data['height']) : 0;
        } elseif (!empty($logo_data)) {
            $logo_url = $logo_data;
        }

        if (empty($logo_url)) {
            $logo_url = wprentals_get_option($logo_source, 'url');
        }

        if (empty($logo_url)) {
            $custom_logo_id = get_theme_mod('custom_logo');
            if ($custom_logo_id) {
                $image = wp_get_attachment_image_src($custom_logo_id, 'full');
                if (!empty($image)) {
                    $logo_url = $image[0];
                    $logo_width = isset($image[1]) ? intval($image[1]) : 0;
                    $logo_height = isset($image[2]) ? intval($image[2]) : 0;
                    $logo_id = $custom_logo_id;
                }
            }
        }

        if (empty($logo_url)) {
            $logo_url = get_template_directory_uri() . '/img/logo.png';

            $default_logo_path = get_template_directory() . '/img/logo.png';
            if (file_exists($default_logo_path)) {
                $default_dimensions = getimagesize($default_logo_path);
                if (false !== $default_dimensions) {
                    $logo_width = isset($default_dimensions[0]) ? intval($default_dimensions[0]) : $logo_width;
                    $logo_height = isset($default_dimensions[1]) ? intval($default_dimensions[1]) : $logo_height;
                }
            }
        }

        if (empty($logo_url)) {
            return;
        }

        $alt_text = '';
        if ($logo_id) {
            $alt_text = get_post_meta($logo_id, '_wp_attachment_image_alt', true);
        }

        if (empty($alt_text)) {
            $alt_text = get_bloginfo('name', 'display');
        }

        $attributes = [
            'class' => 'image-logo theme-option-logo ' . sanitize_html_class($logo_source),
            'src' => $logo_url,
            'alt' => $alt_text,
        ];

        if ($logo_width > 0) {
            $attributes['width'] = $logo_width;
        }

        if ($logo_height > 0) {
            $attributes['height'] = $logo_height;
        }

        $attributes_output = '';
        foreach ($attributes as $attribute => $value) {
            if ('src' === $attribute) {
                $value = esc_url($value);
            } else {
                $value = esc_attr($value);
            }

            $attributes_output .= sprintf(' %s="%s"', esc_attr($attribute), $value);
        }

        echo '<img' . $attributes_output . ' />';
    }

    /**
     * Get the link URL.
     *
     * @param array $settings The widget settings.
     * @return mixed The link URL or false if not set.
     */
    private function get_link_url($settings) {
        if ('none' === $settings['link_to']) {
            return false;
        }

        if ('custom' === $settings['link_to']) {
            if (empty($settings['link']['url'])) {
                return false;
            }

            if (!empty($settings['is_external'])) {
                $this->add_render_attribute('link', 'target', '_blank');
            }

            if (!empty($settings['nofollow'])) {
                $this->add_render_attribute('link', 'rel', 'nofollow');
            }

            return $settings['link'];
        }

        if ('default' === $settings['link_to']) {
            if (empty($settings['link']['url'])) {
                return false;
            }
            return site_url();
        }
    }
}
