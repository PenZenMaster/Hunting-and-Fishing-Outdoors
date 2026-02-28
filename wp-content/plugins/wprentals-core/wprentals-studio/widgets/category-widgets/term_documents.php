<?php

namespace ElementorStudioWidgetsWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class Wprentals_Term_Documents extends Widget_Base {

    /**
     * Retrieve a taxonomy meta value prioritising native term meta over the legacy option array.
     *
     * @param int    $term_id Term identifier.
     * @param string $key     Meta key.
     *
     * @return string
     */
    private function get_term_meta_value( $term_id, $key ) {
        $value = get_term_meta( $term_id, $key, true );

        if ( '' === $value ) {
            $legacy = get_option( 'taxonomy_' . $term_id );
            if ( isset( $legacy[ $key ] ) && '' !== $legacy[ $key ] ) {
                $value = $legacy[ $key ];
            }
        }

        return $value;
    }

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
        return 'Term Documents';
    }

    public function get_categories() {
        return ['category_widgets'];
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
        return __('Term Documents', 'wprentals-core');
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
        return 'wprentals-note eicon-document-file';
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
        return [''];
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
    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__('Show Title', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'documents_title',
            [
                'label' => esc_html__('Documents Title', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Documents', 'wprentals-core'),
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_icons',
            [
                'label' => esc_html__('Show PDF Icons', 'wprentals-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wprentals-core'),
                'label_off' => esc_html__('Hide', 'wprentals-core'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'icon_type',
            [
                'label' => esc_html__('Icon Type', 'wprentals-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'svg',
                'options' => [
                    'svg' => esc_html__('SVG Icon (Default)', 'wprentals-core'),
                    'custom' => esc_html__('Custom Icon', 'wprentals-core'),
                    'emoji' => esc_html__('Emoji', 'wprentals-core'),
                ],
                'condition' => [
                    'show_icons' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'custom_icon',
            [
                'label' => esc_html__('Choose Custom Icon', 'wprentals-core'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-file-pdf',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_icons' => 'yes',
                    'icon_type' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'emoji_icon',
            [
                'label' => esc_html__('Emoji Icon', 'wprentals-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '📄',
                'condition' => [
                    'show_icons' => 'yes',
                    'icon_type' => 'emoji',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'wprentals-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Title Typography', 'wprentals-core'),
                'selector' => '{{WRAPPER}} .documents-title',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .documents-title' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__('Title Margin Bottom', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .documents-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'document_typography',
                'label' => esc_html__('Document Link Typography', 'wprentals-core'),
                'selector' => '{{WRAPPER}} .document-link',
            ]
        );

        $this->add_control(
            'document_color',
            [
                'label' => esc_html__('Document Link Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document-link' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'document_hover_color',
            [
                'label' => esc_html__('Document Link Hover Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document-link:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'documents_gap',
            [
                'label' => esc_html__('Gap Between Documents', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .documents-list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'document_spacing',
            [
                'label' => esc_html__('Fallback Spacing (for older browsers)', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .document-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => esc_html__('Icon Spacing', 'wprentals-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .document-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_icons' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'wprentals-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .document-icon' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'show_icons' => 'yes',
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
        $settings = $this->get_settings_for_display();
        
        $current_term = null;
        $category_documents = '';
        
        if (is_tax()) {
            $current_term = get_queried_object();
        } elseif (\Elementor\Plugin::$instance->editor->is_edit_mode() || is_singular( 'wpestate-studio' ) ) {
            $latest_terms = get_terms([
                'taxonomy'   => 'property_city',
                'hide_empty' => false,
                'number'     => 1,
                'orderby'    => 'term_id',
                'order'      => 'DESC',
            ]);
           
            if (!empty($latest_terms) && !is_wp_error($latest_terms)) {
                $current_term = $latest_terms[0];
            }
        }
        
        if ( $current_term instanceof \WP_Term ) {
            $category_documents = $this->get_term_meta_value( $current_term->term_id, 'category_documents' );
        }
        
        // Parse documents - assuming they're stored as comma-separated attachment IDs or URLs
        $documents = [];
        if (!empty($category_documents)) {
            $documents = array_filter(array_map('trim', explode(',', $category_documents)));
        }
        
        if (empty($documents)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode() || is_singular( 'wpestate-studio' ) ) {
                ?>
                <div class="term-documents-container">
                    <?php if ($settings['show_title'] === 'yes' && !empty($settings['documents_title'])): ?>
                        <h3 class="documents-title"><?php echo esc_html($settings['documents_title']); ?></h3>
                    <?php endif; ?>
                    <div class="documents-list">
                        <div class="document-item">
                            <?php echo esc_html__('This term does not have any documents.', 'wprentals-core'); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            return;
        }
        
        ?>
        <div class="term-documents-container">
            <?php if ($settings['show_title'] === 'yes' && !empty($settings['documents_title'])): ?>
                <h3 class="documents-title"><?php echo esc_html($settings['documents_title']); ?></h3>
            <?php endif; ?>
            
            <div class="documents-list">
                <?php foreach ($documents as $document): ?>
                    <?php
                    $document_url = '';
                    $document_name = '';
                    
                    // Check if it's an attachment ID
                    if (is_numeric($document)) {
                        $attachment_id = intval($document);
                        $document_url = wp_get_attachment_url($attachment_id);
                        $document_name = get_the_title($attachment_id);
                        
                        if (!$document_name) {
                            $document_name = basename($document_url);
                        }
                    } else {
                        // Assume it's a URL
                        $document_url = esc_url($document);
                        $document_name = basename($document_url);
                    }
                    
                    if (!$document_url) {
                        continue;
                    }
                    
                    // Remove file extension from display name
                    $document_name = preg_replace('/\.[^.]+$/', '', $document_name);
                    ?>
                    
                    <div class="document-item">
                        <a href="<?php echo esc_url($document_url); ?>" class="document-link" target="_blank" rel="noopener">
                            <?php if ($settings['show_icons'] === 'yes'): ?>
                                <span class="document-icon">
                                    <?php 
                                    if ($settings['icon_type'] === 'svg') {
                                        $icon_markup = '';
                                        $icon_template = locate_template('templates/svg_icons/pdf_icon.svg');

                                        if (empty($icon_template)) {
                                            $plugin_icon_template = trailingslashit(WPESTATE_PLUGIN_PATH) . 'templates/svg_icons/pdf_icon.svg';

                                            if (file_exists($plugin_icon_template)) {
                                                $icon_template = $plugin_icon_template;
                                            }
                                        }

                                        if (!empty($icon_template) && file_exists($icon_template)) {
                                            ob_start();
                                            include $icon_template;
                                            $icon_markup = ob_get_clean();
                                        }

                                        if (!empty($icon_markup)) {
                                            echo $icon_markup;
                                        } else {
                                            echo esc_html('📄');
                                        }
                                    } elseif ($settings['icon_type'] === 'custom' && !empty($settings['custom_icon']['value'])) {
                                        \Elementor\Icons_Manager::render_icon($settings['custom_icon'], ['aria-hidden' => 'true']);
                                    } elseif ($settings['icon_type'] === 'emoji' && !empty($settings['emoji_icon'])) {
                                        echo esc_html($settings['emoji_icon']);
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>
                            <?php echo esc_html($document_name); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

}