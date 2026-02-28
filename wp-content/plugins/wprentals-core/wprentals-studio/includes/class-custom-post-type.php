<?php
/**
 * Registers and manages the `wpestate-studio` Elementor template post type.
 *
 * This port copies the Residence Studio implementation but swaps the wording
 * and taxonomy bindings so it fits the WpRentals environment. The class sets
 * up admin tabs, exposes filters for Elementor template categories, and
 * enables WPBakery editing for backwards compatibility.
 */
class WpRentals_Custom_Post_Type {
    private $tabs = array(
        'all'       => 'All',
        'header'    => 'Header',
        'footer'    => 'Footer',
        'property'  => 'Property',
        'agent'     => 'Owner',
        'taxonomies'=> 'Taxonomies',
        'post'      => 'Single Post',
        'block'     => 'Block',
    );

    public function __construct() {

        add_action('init', [$this, 'register_custom_post_type']);
        add_filter('views_edit-wpestate-studio', [$this, 'template_tabs']);
        add_action('pre_get_posts', [$this, 'apply_tab_filter']);
        add_action('init', [$this, 'enable_wpbakery']);

    }





public function enable_wpbakery() {
      if ( function_exists( 'vc_set_default_editor_post_types' ) ) {
          vc_set_default_editor_post_types( array( 'page', 'post', 'wpestate-studio' ) );
      }
}





public function register_custom_post_type() {
    // Check if the branding function exists, otherwise use default
    $branding = function_exists('wprentals_theme_branding') ? wprentals_theme_branding() : 'WpRentals';
    // Check if branding logo function exists and get icon
    $icon = 'dashicons-welcome-widgets-menus'; // Default fallback
    if (function_exists('wprentals_get_theme_branding_logo_url')) {
        $branding_logo = wprentals_get_theme_branding_logo_url();
        if (!empty($branding_logo)) {
            $icon = $branding_logo;
        }
    }

    
    register_post_type('wpestate-studio', array(
        'labels' => array(
            'name' => sprintf(esc_html__('%s Studio Templates', 'wprentals-core'), $branding),
            'singular_name' => sprintf(esc_html__('%s Studio Templates', 'wprentals-core'), $branding),
            'add_new' => sprintf(esc_html__('Add New %s Studio Template', 'wprentals-core'), $branding),
            'add_new_item' => sprintf(esc_html__('Add %s Studio Template', 'wprentals-core'), $branding),
            'edit' => sprintf(esc_html__('Edit %s Studio Templates', 'wprentals-core'), $branding),
            'edit_item' => sprintf(esc_html__('Edit %s Studio Template', 'wprentals-core'), $branding),
            'new_item' => sprintf(esc_html__('New %s Studio Template', 'wprentals-core'), $branding),
            'view' => sprintf(esc_html__('View %s Studio Templates', 'wprentals-core'), $branding),
            'view_item' => sprintf(esc_html__('View %s Studio Template', 'wprentals-core'), $branding),
            'search_items' => sprintf(esc_html__('Search %s Studio Templates', 'wprentals-core'), $branding),
            'not_found' => sprintf(esc_html__('No %s Studio Templates found', 'wprentals-core'), $branding),
            'not_found_in_trash' => sprintf(esc_html__('No %s Studio Templates found in trash', 'wprentals-core'), $branding),
            'parent' => sprintf(esc_html__('Parent %s Studio Templates', 'wprentals-core'), $branding)
        ),
        'public' => true,
        'has_archive' => false,
        'hierarchical' => false,
        'can_export' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        // Preserve the historic slug to match the Residence Studio endpoints
        // Elementor expects when generating preview URLs.
        'rewrite' => array('slug' => 'wpestate-studio-templates'),
        'supports' => array('title', 'thumbnail', 'page-attributes', 'editor'),
        'can_export' => true,
        'show_in_rest' => true,
        // Keep the REST API base identical to the original Residence Studio
        // integration so Elementor continues to resolve document endpoints
        // using the hard coded `wpestate-studio-templates` namespace. Changing
        // the namespace prevents the editor iframe from loading which surfaces
        // the "Preview could not be loaded" error reported by users.
        'rest_base' => 'wpestate-studio-templates',
    
        'exclude_from_search' => true,
        'menu_icon' => $icon,
        'menu_position' => 4,
    ));
    add_post_type_support('wpestate-studio', 'elementor');
}









    public function template_tabs($views) {
        $current = isset($_GET['template_tab']) ? sanitize_key($_GET['template_tab']) : 'all';
        $base = admin_url('edit.php?post_type=wpestate-studio');
        foreach ($this->tabs as $slug => $label) {
            $url = $slug === 'all' ? $base : add_query_arg('template_tab', $slug, $base);
            $class = $current === $slug ? 'class="current"' : '';
            $count = $this->count_tab($slug);
            $views[$slug] = '<a href="' . esc_url($url) . '" ' . $class . '>' . esc_html($label) . ' <span class="count">(' . intval($count) . ')</span></a>';
        }
        return $views;
    }

    public function apply_tab_filter($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        if ($query->get('post_type') !== 'wpestate-studio') {
            return;
        }
        $tab = isset($_GET['template_tab']) ? sanitize_key($_GET['template_tab']) : 'all';
        if ($tab === 'all') {
            return;
        }
        $args = $this->get_tab_query_args($tab);
        if (!empty($args['meta_query'])) {
            $query->set('meta_query', $args['meta_query']);
        }
    }

    private function count_tab($tab) {
        $args = $this->get_tab_query_args($tab);
        $query = new WP_Query($args);
        return $query->found_posts;
    }

    private function get_tab_query_args($tab) {
        $args = array(
            'post_type'      => 'wpestate-studio',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'post_status'    => 'any',
        );

        switch ($tab) {
            case 'header':
                $args['meta_query'][] = array(
                    'key'     => 'wpestate_head_foot_template',
                    'value'   => array('wpestate_template_header', 'wpestate_template_before_header', 'wpestate_template_after_header'),
                    'compare' => 'IN',
                );
                break;
            case 'footer':
                $args['meta_query'][] = array(
                    'key'     => 'wpestate_head_foot_template',
                    'value'   => array('wpestate_template_footer', 'wpestate_template_before_footer', 'wpestate_template_after_footer'),
                    'compare' => 'IN',
                );
                break;
            case 'property':
                $args['meta_query'][] = array(
                    'key'   => 'wpestate_head_foot_template',
                    'value' => 'wpestate_single_property_page',
                );
                break;
            case 'agent':
                $args['meta_query'][] = array(
                    'key'   => 'wpestate_head_foot_template',
                    'value' => 'wpestate_single_agent',
                );
                break;
            case 'block':
                $args['meta_query'][] = array(
                    'key'   => 'wpestate_head_foot_template',
                    'value' => 'wpestate_template_custom_block',
                );
                break;
            case 'taxonomies':
                $loc = wpestate_templates_selection_options();
                $tax = array_keys($loc['taxonomies']['value']);
                $args['meta_query'][] = array(
                     'key'   => 'wpestate_head_foot_template',
                    'value' => 'wpestate_category_page',
                );
                break;
            case 'special':
                $special = array_keys(wpestate_templates_special_pages());
                $args['meta_query'][] = array(
                    'key'     => 'wpestate_head_foot_positions',
                    'value'   => $special,
                    'compare' => 'IN',
                );
                break;
            case 'post':
                $args['meta_query'][] = array(
                    'key'   => 'wpestate_head_foot_template',
                    'value' => 'wpestate_single_post',
                );
                break;
        }
        return $args;
    }
}

new WpRentals_Custom_Post_Type();
