<?php
/**
 * Bootstrap for the Elementor widget namespace used by the WpRentals Studio module.
 *
 * This file mirrors the plugin loader that ships with the temp Residence Studio
 * integration and is responsible only for wiring Elementor specific widgets
 * when the Design Studio bundle is loaded from the core plugin. The widgets
 * themselves are intentionally disabled for now – we only keep the bootstrap
 * in place so future widget ports can plug in without additional plumbing.
 *
 * @package WpRentals_Studio
 */

namespace ElementorStudioWidgetsWpRentals;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

    /**
     * Instance
     *
     * @since 1.2.0
     * @access private
     * @static
     *
     * @var Plugin The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.2.0
     * @access public
     *
     * @return Plugin An instance of the class.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * widget_scripts
     *
     * Load required plugin core files.
     *
     * @since 1.2.0
     * @access public
     */
    public function widget_scripts() {
        
    }

    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.2.0
     * @access private
     */
    private function include_widgets_files() {
        $this->load_widget_directory(__DIR__ . '/widgets/header-footer');
        $this->load_widget_directory(__DIR__ . '/widgets/category-widgets');
        $this->load_widget_directory(__DIR__ . '/widgets/single-post');
        $this->load_widget_directory(__DIR__ . '/widgets/single-property-widgets');
        $this->load_widget_directory(__DIR__ . '/widgets/owener-widgets');
    }

    /**
     * Require all widget files from a directory if it exists.
     *
     * @param string $directory Absolute path to the widgets directory.
     *
     * @return void
     */
    private function load_widget_directory($directory) {
        if (!is_dir($directory)) {
            return;
        }

        $directory = rtrim($directory, '/\\') . '/';
        $widget_files = glob($directory . '*.php');
        if (empty($widget_files)) {
            return;
        }

        foreach ($widget_files as $widget_file) {
            require_once $widget_file;
        }
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.2.0
     * @access public
     */
    public function register_widgets() {
        $this->include_widgets_files();

        $widget_classes = [
            'Wprentals_Site_Logo',
            'Wprentals_Navigation_Menu',
            'Wprentals_Site_Login',
            'Wprentals_Site_Create_Listing',
            'Wprentals_Site_Currency_Changer',
            'Wprentals_Site_Social',
            'Wprentals_Site_Phone',
            'Wprentals_Site_Language',
            'Wprentals_Footer_Properties_By_Category',
            'Wprentals_Term_Title',
            'Wprentals_Term_Description',
            'Wprentals_Term_Parent',
            'Wprentals_Term_City_Location',
            'Wprentals_Term_Featured_Image',
            'Wprentals_Term_Gallery',
            'Wprentals_Term_Tagline',
            'Wprentals_Term_Custom_Field_Widget',
            'Wprentals_Term_Map',
            'Wprentals_Term_Page_Breadcrumbs',
            'Wprentals_Term_Header',
            'Wprentals_Term_Property_Count',
            'Wprentals_Term_Documents',
            'Wprentals_Term_Listings',
            'Wprentals_Single_Post_Title2',
            'Wprentals_Single_Post_Content',
            'Wprentals_Single_Post_Featured_Image',
            'Wprentals_Single_Post_Excerpt',
            'Wprentals_Single_Post_Meta_Info',
            'Wprentals_Single_Post_Breadcrumbs',
            'Wprentals_Single_Post_Slider',
            'Wprentals_Single_Post_Social',
            'Wprentals_Single_Post_Comments',
            'Wprentals_Single_Post_Related_Posts',
            'Wprentals_Single_Post_Author_Box',

            'Wprentals_Property_Page_Breadcrumbs',
            'Wprentals_Property_Page_Title',    
            'Wprentals_Property_Page_Status',
            'Wprentals_Property_Page_Featured_Image',
            'Wprentals_Property_Page_Header_Section',
            'Wprentals_Property_Page_Overview_Section',
            'Wprentals_Property_Page_Description_Section',
            'Wprentals_Property_Page_Address_Section',
            'Wprentals_Property_Page_Details_Section',
            'Wprentals_Property_Page_Features_Section',
            'Wprentals_Property_Page_Sleeping_Arrangements_Section',
            'Wprentals_Property_Page_Terms_Conditions_Section',
            'Wprentals_Property_Page_Availability_Section',
            'Wprentals_Property_Page_Map_Section',
            'Wprentals_Property_Page_Video_Section',
            'Wprentals_Property_Page_Virtual_Tour_Section',
            'Wprentals_Property_Page_Yelp_Section',
            'Wprentals_Property_Page_Owner_Section',
            'Wprentals_Property_Page_Reviews_Section',
            'Wprentals_Property_Page_Similar_Section',
            'Wprentals_Property_Page_Booking_Form',

            'Wprentals_Property_Page_Featured_Image_Header',
            'Wprentals_Property_Page_Classic_Slider',
            'Wprentals_Property_Page_Masonary_Gallery1',
            'Wprentals_Property_Page_Masonary_Gallery2',
            'Wprentals_Property_Page_Three_Items_Slider',


            'Wprentals_Property_Page_Add_To_Favorites',
            'Wprentals_Property_Page_Contact_Owner',
            'Wprentals_Property_Page_Agent_Details_Intext',
            'Wprentals_Property_Page_Price',
            'Wprentals_Property_Page_Simple_Detail',

            'Wprentals_Owner_Name',
            'Wprentals_Owner_Featured_Image',
            'Wprentals_Owner_Status',
            'Wprentals_Owner_Single_Details',
            'Wprentals_Owner_Contact_Button',
            'Wprentals_Owner_Header',
            'Wprentals_Owner_Reviews',
            'Wprentals_Owner_Property_Listings',

        ];

        foreach ($widget_classes as $widget_class) {
            $class = __NAMESPACE__ . '\\Widgets\\' . $widget_class;
            if (class_exists($class)) {
                \Elementor\Plugin::instance()->widgets_manager->register(new $class());
            }
        }
    }

    /**
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.2.0
     * @access public
     */
    public function add_elementor_widget_categories($elements_manager) {
        $priority_category = $this->determine_priority_category();

        $elements_manager->add_category(
            'category_widgets', [
                'title' => __('WpRentals Category Widgets', 'wprentals-core'),
                'icon'  => 'fa fa-folder-open',
            ]
        );

        $elements_manager->add_category(
            'wprentals_header', [
                'title' => __('WpRentals Header & Footer Widgets', 'wprentals-core'),
                'icon'  => 'fa fa-home',
            ]
        );

        $elements_manager->add_category(
            'wprentals_single_post', [
                'title' => __('WpRentals Single Post Widgets', 'wprentals-core'),
                'icon'  => 'fa fa-home',
            ]
        );

        $elements_manager->add_category(
            'wprentals_single_property', [
                'title' => __('WpRentals Single Property Widgets', 'wprentals-core'),
                'icon'  => 'fa fa-home',
            ]
        );

        $elements_manager->add_category(
            'wprentals_owners', [
                'title' => __('WpRentals Owner Widgets', 'wprentals-core'),
                'icon'  => 'fa fa-user',
            ]
        );

        if ($priority_category) {
            $this->reorder_categories_to_top($elements_manager, $priority_category);
        }
    }

    /**
     * Determine which Elementor widget category should be prioritised.
     *
     * @since 1.2.0
     *
     * @return string Empty string when no category needs to be prioritised.
     */
    private function determine_priority_category() {
        $post_id = $this->resolve_current_post_id();

        if (!$post_id) {
            return '';
        }

        if ('wpestate-studio' !== get_post_type($post_id)) {
            return '';
        }

        $template = get_post_meta($post_id, 'wpestate_head_foot_template', true);

        $header_templates = [
            'wpestate_template_header',
            'wpestate_template_before_header',
            'wpestate_template_after_header',
            'wpestate_template_footer',
            'wpestate_template_before_footer',
            'wpestate_template_after_footer',
        ];

        if (in_array($template, $header_templates, true)) {
            return 'wprentals_header';
        }

        if ('wpestate_single_post' === $template) {
            return 'wprentals_single_post';
        }

        if ('wpestate_single_property_page' === $template) {
            return 'wprentals_single_property';
        }

        if ('wpestate_category_page' === $template) {
            return 'category_widgets';
        }

        if ('wpestate_single_agent' === $template) {
            return 'wprentals_owners';
        }

        $positions = get_post_meta($post_id, 'wpestate_head_foot_positions', true);

        if (is_array($positions) && in_array('estate_agent', $positions, true)) {
            return 'wprentals_owners';
        }

        if (is_string($positions) && 'estate_agent' === $positions) {
            return 'wprentals_owners';
        }

        return '';
    }

    /**
     * Attempt to resolve the post ID currently being edited in Elementor.
     *
     * @since 1.2.0
     *
     * @return int Post ID or 0 when it cannot be detected.
     */
    private function resolve_current_post_id() {
        $post_id = 0;

        if (isset($_GET['post'])) {
            $post_id = intval($_GET['post']);
        }

        if (!$post_id && isset($_GET['elementor-preview'])) {
            $post_id = intval($_GET['elementor-preview']);
        }

        if (!$post_id && isset($_POST['editor_post_id'])) {
            $post_id = intval($_POST['editor_post_id']);
        }

        if (!$post_id && isset($_POST['post_id'])) {
            $post_id = intval($_POST['post_id']);
        }

        if (!$post_id) {
            global $post;
            if ($post && isset($post->ID)) {
                $post_id = (int) $post->ID;
            }
        }

        return $post_id;
    }

    /**
     * Reorder Elementor categories so the priority category appears first.
     *
     * @since 1.2.0
     *
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
     * @param string                        $priority_category Category slug to prioritise.
     *
     * @return void
     */
    private function reorder_categories_to_top($elements_manager, $priority_category) {
        if (!$priority_category) {
            return;
        }

        $reorder_cats = function() use ($priority_category) {
            if (!isset($this->categories) || !is_array($this->categories)) {
                return;
            }

            uksort($this->categories, function($key_one, $key_two) use ($priority_category) {
                if ($key_one === $priority_category) {
                    return -1;
                }

                if ($key_two === $priority_category) {
                    return 1;
                }

                return 0;
            });
        };

        $reorder_cats->call($elements_manager);
    }

    public function __construct() {

        // Register widget scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

        // Register widgets
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        add_action('elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories']);
    }

}

// Instantiate Plugin Class
Plugin::instance();

