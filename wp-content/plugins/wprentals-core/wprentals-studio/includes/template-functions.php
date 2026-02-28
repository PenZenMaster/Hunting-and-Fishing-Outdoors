<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Template tag compatibility layer for the WpRentals Studio module.
 *
 * The functions mirror those in Residence Studio so the theme can request
 * template IDs and rendered markup without caring whether Elementor or the
 * Studio bundle is active. Each helper proxies to `WpRentals_Render_Template`.
 */

function wpestate_get_header_id() {
    $header_id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_template_header');
    return $header_id !== '' ? $header_id : false;
}

function wpestate_header_enabled() {
    return apply_filters('wpestate_header_enabled', wpestate_get_header_id() !== false);
}

function wpestate_header_template_id() {
    return apply_filters('wpestate_header_template_id', wpestate_get_header_id());
}

function wpestate_get_header_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_header_template_id());
}

function wpestate_render_header() {
    if (!wpestate_header_enabled()) {
        return;
    }
    echo '<header itemscope="itemscope" itemtype="http://schema.org/WPHeader">';
    wpestate_get_header_template();
    echo '</header>';
}

function wpestate_get_before_header_id() {
    $id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_template_before_header');
   return $id !== '' ? $id : false;
}

function wpestate_before_header_enabled() {
    return apply_filters('wpestate_before_header_enabled', wpestate_get_before_header_id() !== false);
}

function wpestate_before_header_template_id() {
    return apply_filters('wpestate_before_header_template_id', wpestate_get_before_header_id());
}

function wpestate_get_before_header_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_before_header_template_id());
}

function wpestate_render_before_header() {
    if (!wpestate_before_header_enabled()) {
        return;
    }
    wpestate_get_before_header_template();
}

function wpestate_get_after_header_id() {
    $id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_template_after_header');
    return $id !== '' ? $id : false;
}

function wpestate_after_header_enabled() {
    return apply_filters('wpestate_after_header_enabled', wpestate_get_after_header_id() !== false);
}

function wpestate_after_header_template_id() {
    return apply_filters('wpestate_after_header_template_id', wpestate_get_after_header_id());
}

function wpestate_get_after_header_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_after_header_template_id());
}

function wpestate_render_after_header() {
    if (!wpestate_after_header_enabled()) {
        return;
    }
    wpestate_get_after_header_template();
}

function wpestate_get_footer_id() {
    $footer_id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_template_footer');
    return $footer_id !== '' ? $footer_id : false;
}

function wpestate_footer_enabled() {
    return apply_filters('wpestate_footer_enabled', wpestate_get_footer_id() !== false);
}

function wpestate_footer_template_id() {
    return apply_filters('wpestate_footer_template_id', wpestate_get_footer_id());
}

function wpestate_get_footer_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_footer_template_id());
}

function wpestate_render_footer() {
    if (!wpestate_footer_enabled()) {
        return;
    }
    echo '<footer itemscope="itemscope" itemtype="http://schema.org/WPFooter">';
    wpestate_get_footer_template();
    echo '</footer>';
}

function wpestate_get_before_footer_id() {
    $id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_template_before_footer');
   return $id !== '' ? $id : false;
}

function wpestate_before_footer_enabled() {
    return apply_filters('wpestate_before_footer_enabled', wpestate_get_before_footer_id() !== false);
}

function wpestate_before_footer_template_id() {
    return apply_filters('wpestate_before_footer_template_id', wpestate_get_before_footer_id());
}

function wpestate_get_before_footer_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_before_footer_template_id());
}

function wpestate_render_before_footer() {
    if (!wpestate_before_footer_enabled()) {
        return;
    }
    wpestate_get_before_footer_template();
}

function wpestate_get_after_footer_id() {
    $id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_template_after_footer');
    return $id !== '' ? $id : false;
}

function wpestate_after_footer_enabled() {
    return apply_filters('wpestate_after_footer_enabled', wpestate_get_after_footer_id() !== false);
}

function wpestate_after_footer_template_id() {
    return apply_filters('wpestate_after_footer_template_id', wpestate_get_after_footer_id());
}

function wpestate_get_after_footer_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_after_footer_template_id());
}

function wpestate_render_after_footer() {
    if (!wpestate_after_footer_enabled()) {
        return;
    }
    wpestate_get_after_footer_template();
}

function wpestate_get_single_property_id() {
    global $post;

    $local_id = false;
    if ( isset( $post->ID ) ) {
        $local_id = get_post_meta( $post->ID, 'property_page_desing_local', true );
    }

    if ( ! empty( $local_id ) ) {
        return $local_id;
    }

    $global_id = WpRentals_Render_Template::instance()->fetch_plugin_settings( 'wpestate_single_property_page' );

    if ( $global_id !== '' ) {
        return $global_id;
    }

    if ( ! is_singular( 'estate_property' ) ) {
        return false;
    }

    $fallback_id = wpestate_locate_single_property_template();

    return $fallback_id ? $fallback_id : false;
}

function wpestate_single_property_enabled() {
    return apply_filters('wpestate_single_property_enabled', wpestate_get_single_property_id() !== false);
}

function wpestate_single_property_template_id() {
    return apply_filters('wpestate_single_property_template_id', wpestate_get_single_property_id());
}

function wpestate_get_single_property_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_single_property_template_id());
}

function wpestate_render_single_property() {
    if (!wpestate_single_property_enabled()) {
        return;
    }

    $wpestate_wide_elememtor_page_class = '';
    $template_id = wpestate_single_property_template_id();
    if ($template_id) {
        $full_width = get_post_meta($template_id, 'wpestate_custom_full_width', true);
        if ($full_width === 'yes') {
            $wpestate_wide_elememtor_page_class = 'wpestate_wide_elememtor_page';
        }
    }

    ?>

        <div class=" <?php echo esc_attr($wpestate_wide_elememtor_page_class); ?>">
        
                <?php  wpestate_get_single_property_template(); ?>
  
             
        </div>
           <?php get_footer(); ?>
    <?php
}

/**
 * Locate the matching Studio template for the current single property view.
 *
 * The resolver mirrors the include/exclude logic from the Studio header/footer
 * controller so property templates respect display locations and taxonomy
 * assignments defined in the template editor.
 *
 * @return int Template post ID or 0 when no layout matches.
 */
function wpestate_locate_single_property_template() {
    $post_id = get_queried_object_id();
    if (!$post_id) {
        return 0;
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'estate_property') {
        return 0;
    }

    $templates = get_posts([
        'post_type'      => 'wpestate-studio',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => 'wpestate_head_foot_template',
        'meta_value'     => 'wpestate_single_property_page',
    ]);

    if (empty($templates)) {
        return 0;
    }

    $tax_matches = [];
    $taxonomies  = get_object_taxonomies($post->post_type);

    foreach ($taxonomies as $tax_slug) {
        $term_ids = wp_get_object_terms($post_id, $tax_slug, ['fields' => 'ids']);
        if (is_wp_error($term_ids)) {
            continue;
        }
        foreach ($term_ids as $term_id) {
            $tax_matches[] = $tax_slug . ':' . $term_id;
        }
    }

    foreach ($templates as $template_post) {
        $positions         = get_post_meta($template_post->ID, 'wpestate_head_foot_positions', true);
        $exclude_positions = get_post_meta($template_post->ID, 'wpestate_head_foot_exclude_positions', true);
        $tax_terms         = get_post_meta($template_post->ID, 'wpestate_head_foot_tax_terms', true);
        $exclude_terms     = get_post_meta($template_post->ID, 'wpestate_head_foot_exclude_tax_terms', true);

        $positions         = is_array($positions) ? $positions : ($positions ? [$positions] : []);
        $exclude_positions = is_array($exclude_positions) ? $exclude_positions : ($exclude_positions ? [$exclude_positions] : []);
        $tax_terms         = is_array($tax_terms) ? $tax_terms : [];
        $exclude_terms     = is_array($exclude_terms) ? $exclude_terms : [];

        $skip_template = false;
        foreach ($exclude_positions as $idx => $exclude_position) {
            $term = isset($exclude_terms[$idx]) ? $exclude_terms[$idx] : '';
            if (wpestate_single_property_position_matches($exclude_position, $term, $post, $tax_matches)) {
                $skip_template = true;
                break;
            }
        }

        if ($skip_template) {
            continue;
        }

        foreach ($positions as $idx => $position) {
            $term = isset($tax_terms[$idx]) ? $tax_terms[$idx] : '';
            if (wpestate_single_property_position_matches($position, $term, $post, $tax_matches)) {
                return (int) $template_post->ID;
            }
        }
    }

    return 0;
}

/**
 * Wrapper for property template rule matching.
 *
 * @param string  $position   Selected display location.
 * @param string  $term_value Optional taxonomy/term value.
 * @param WP_Post $post       Current estate_property post.
 * @param array   $tax_matches List of taxonomy matches for the property.
 *
 * @return bool True when the rule matches the property context.
 */
function wpestate_single_property_position_matches($position, $term_value, $post, $tax_matches) {
    return wpestate_single_post_position_matches($position, $term_value, $post, $tax_matches);
}

function wpestate_get_single_agent_id() {
    $id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_single_agent');

    if ($id !== '') {
        return $id;
    }

    if (!is_singular('estate_agent')) {
        return false;
    }

    $fallback_id = wpestate_locate_single_agent_template();

    return $fallback_id ? $fallback_id : false;
}

function wpestate_single_agent_enabled() {
    return apply_filters('wpestate_single_agent_enabled', wpestate_get_single_agent_id() !== false);
}

function wpestate_single_agent_template_id() {
    return apply_filters('wpestate_single_agent_template_id', wpestate_get_single_agent_id());
}

function wpestate_get_single_agent_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_single_agent_template_id());
}

function wpestate_render_single_agent() {
    if (!wpestate_single_agent_enabled()) {
        return;
    }
    
    $wpestate_wide_elememtor_page_class='';
    $template_id = wpestate_single_agent_template_id();
    if ($template_id) {
        $full_width = get_post_meta($template_id, 'wpestate_custom_full_width', true);
        if ($full_width === 'yes') {
            $wpestate_wide_elememtor_page_class = 'wpestate_wide_elememtor_page';
        }
    }


    echo '<div class="wpestate-single-agent  '.esc_attr($wpestate_wide_elememtor_page_class).' ">';
    wpestate_get_single_agent_template();
    echo '</div>';
}

/**
 * Resolve the matching single agent template based on include/exclude rules.
 *
 * @return int Template post ID or 0 when no layout matches the current agent.
 */
function wpestate_locate_single_agent_template() {
    $post_id = get_queried_object_id();
    if (!$post_id) {
        return 0;
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'estate_agent') {
        return 0;
    }

    $templates = get_posts([
        'post_type'      => 'wpestate-studio',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => 'wpestate_head_foot_template',
        'meta_value'     => 'wpestate_single_agent',
    ]);

    if (empty($templates)) {
        return 0;
    }

    $tax_matches = [];
    $taxonomies  = get_object_taxonomies($post->post_type);

    foreach ($taxonomies as $tax_slug) {
        $term_ids = wp_get_object_terms($post_id, $tax_slug, ['fields' => 'ids']);
        if (is_wp_error($term_ids)) {
            continue;
        }
        foreach ($term_ids as $term_id) {
            $tax_matches[] = $tax_slug . ':' . $term_id;
        }
    }

    foreach ($templates as $template_post) {
        $positions         = get_post_meta($template_post->ID, 'wpestate_head_foot_positions', true);
        $exclude_positions = get_post_meta($template_post->ID, 'wpestate_head_foot_exclude_positions', true);
        $tax_terms         = get_post_meta($template_post->ID, 'wpestate_head_foot_tax_terms', true);
        $exclude_terms     = get_post_meta($template_post->ID, 'wpestate_head_foot_exclude_tax_terms', true);

        $positions         = is_array($positions) ? $positions : ($positions ? [$positions] : []);
        $exclude_positions = is_array($exclude_positions) ? $exclude_positions : ($exclude_positions ? [$exclude_positions] : []);
        $tax_terms         = is_array($tax_terms) ? $tax_terms : [];
        $exclude_terms     = is_array($exclude_terms) ? $exclude_terms : [];

        $skip_template = false;
        foreach ($exclude_positions as $idx => $exclude_position) {
            $term = isset($exclude_terms[$idx]) ? $exclude_terms[$idx] : '';
            if (wpestate_single_agent_position_matches($exclude_position, $term, $post, $tax_matches)) {
                $skip_template = true;
                break;
            }
        }

        if ($skip_template) {
            continue;
        }

        foreach ($positions as $idx => $position) {
            $term = isset($tax_terms[$idx]) ? $tax_terms[$idx] : '';
            if (wpestate_single_agent_position_matches($position, $term, $post, $tax_matches)) {
                return (int) $template_post->ID;
            }
        }
    }

    return 0;
}

function wpestate_get_single_post_id() {
    $id = WpRentals_Render_Template::instance()->fetch_plugin_settings('wpestate_single_post');

    if ($id !== '') {
        return $id;
    }

    if (!is_singular('post')) {
        return false;
    }

    $fallback_id = wpestate_locate_single_post_template();

    return $fallback_id ? $fallback_id : false;
}

function wpestate_single_post_enabled() {
    return apply_filters('wpestate_single_post_enabled', wpestate_get_single_post_id() !== false);
}

function wpestate_single_post_template_id() {
    return apply_filters('wpestate_single_post_template_id', wpestate_get_single_post_id());
}

function wpestate_get_single_post_template() {
    echo WpRentals_Render_Template::get_elementor_template(wpestate_single_post_template_id());
}

function wpestate_render_single_post() {
    if (!wpestate_single_post_enabled()) {
        return;
    }
      
    $wpestate_wide_elememtor_page_class='';
    $template_id = wpestate_single_post_template_id();
    if ($template_id) {
        $full_width = get_post_meta($template_id, 'wpestate_custom_full_width', true);
        if ($full_width === 'yes') {
            $wpestate_wide_elememtor_page_class = 'wpestate_wide_elememtor_page';
        }
    }




    echo '<div class="wpestate-single-post-wprentals-studio '.esc_attr( $wpestate_wide_elememtor_page_class ).' ">';
    wpestate_get_single_post_template();
    echo '</div>';
}

/**
 * Find the matching single post template for the current singular post.
 *
 * This falls back to a direct meta scan in situations where the cached
 * header/footer template map has not been hydrated yet (for example when
 * running early during the enqueue phase). The lookup mirrors the include
 * and exclude logic applied inside the header/footer controller so display
 * location and taxonomy based conditions are respected on posts.
 *
 * @return int Template post ID or 0 when no match is found.
 */
function wpestate_locate_single_post_template() {
    $post_id = get_queried_object_id();
    if (!$post_id) {
        return 0;
    }

    $post = get_post($post_id);
    if (!$post || $post->post_type !== 'post') {
        return 0;
    }

    $templates = get_posts([
        'post_type'      => 'wpestate-studio',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => 'wpestate_head_foot_template',
        'meta_value'     => 'wpestate_single_post',
    ]);

    if (empty($templates)) {
        return 0;
    }

    $tax_matches = [];
    $taxonomies  = get_object_taxonomies($post->post_type);

    foreach ($taxonomies as $tax_slug) {
        $term_ids = wp_get_object_terms($post_id, $tax_slug, ['fields' => 'ids']);
        if (is_wp_error($term_ids)) {
            continue;
        }
        foreach ($term_ids as $term_id) {
            $tax_matches[] = $tax_slug . ':' . $term_id;
        }
    }

    foreach ($templates as $template_post) {
        $positions         = get_post_meta($template_post->ID, 'wpestate_head_foot_positions', true);
        $exclude_positions = get_post_meta($template_post->ID, 'wpestate_head_foot_exclude_positions', true);
        $tax_terms         = get_post_meta($template_post->ID, 'wpestate_head_foot_tax_terms', true);
        $exclude_terms     = get_post_meta($template_post->ID, 'wpestate_head_foot_exclude_tax_terms', true);

        $positions         = is_array($positions) ? $positions : ($positions ? [$positions] : []);
        $exclude_positions = is_array($exclude_positions) ? $exclude_positions : ($exclude_positions ? [$exclude_positions] : []);
        $tax_terms         = is_array($tax_terms) ? $tax_terms : [];
        $exclude_terms     = is_array($exclude_terms) ? $exclude_terms : [];

        $skip_template = false;
        foreach ($exclude_positions as $idx => $exclude_position) {
            $term = isset($exclude_terms[$idx]) ? $exclude_terms[$idx] : '';
            if (wpestate_single_post_position_matches($exclude_position, $term, $post, $tax_matches)) {
                $skip_template = true;
                break;
            }
        }

        if ($skip_template) {
            continue;
        }

        foreach ($positions as $idx => $position) {
            $term = isset($tax_terms[$idx]) ? $tax_terms[$idx] : '';
            if (wpestate_single_post_position_matches($position, $term, $post, $tax_matches)) {
                return (int) $template_post->ID;
            }
        }
    }

    return 0;
}

/**
 * Determine whether a display rule matches the provided post context.
 *
 * @param string  $position   Selected location (post type, standard rule or taxonomy).
 * @param string  $term_value Optional taxonomy term in "taxonomy:term" format.
 * @param WP_Post $post       Current post object.
 * @param array   $tax_matches List of taxonomy/term pairs for the post.
 *
 * @return bool True when the rule matches the current post.
 */
function wpestate_single_post_position_matches($position, $term_value, $post, $tax_matches) {
    if (!$position) {
        return false;
    }

    if ($position === 'standard-global' || $position === 'standard-singulars') {
        return true;
    }

    if ($position === 'standard-archives') {
        return false;
    }

    if ($position === $post->post_type) {
        return true;
    }

    if ($term_value) {
        return in_array($term_value, $tax_matches, true);
    }

    if (empty($tax_matches)) {
        return false;
    }

    foreach ($tax_matches as $match) {
        list($tax_slug) = explode(':', $match);
        if ($tax_slug === $position) {
            return true;
        }
    }

    return false;
}

/**
 * Wrapper around the single post matcher so agent templates reuse the logic.
 *
 * @param string  $position   Selected location.
 * @param string  $term_value Optional taxonomy term.
 * @param WP_Post $post       Current agent post object.
 * @param array   $tax_matches List of taxonomy matches for the agent.
 *
 * @return bool True when the rule matches the agent context.
 */
function wpestate_single_agent_position_matches($position, $term_value, $post, $tax_matches) {
    return wpestate_single_post_position_matches($position, $term_value, $post, $tax_matches);
}

/**
 * Check if a taxonomy/term pair matches a template rule.
 *
 * @param string $position Taxonomy slug stored in the location field.
 * @param string $term_val Optional term value in format "taxonomy:term".
 * @param string $tax      Current taxonomy slug.
 * @param int    $term_id  Current term ID.
 * @return bool True on match.
 */
function wpestate_category_match( $position, $term_val, $tax, $term_id ) {
    if ( empty( $position ) ) {
        return false;
    }

    if ( in_array( $position, array( 'standard-global', 'standard-archives' ), true ) ) {
        return true;
    }

    if ( $term_val ) {
        if ( $term_val === $tax . ':' . $term_id ) {
            return true;
        }

        $term = get_term( $term_id, $tax );
        if ( $term && ! is_wp_error( $term ) ) {
            $term_id_string  = (string) $term->term_id;
            $term_slug_value = $tax . ':' . $term->slug;

            if ( $term_val === $term_slug_value || $term_val === $term->slug || $term_val === $term_id_string ) {
                return true;
            }
        }

        return false;
    }

    if ( 'estate_property_all_taxonomies' === $position ) {
        $tax_obj = get_taxonomy( $tax );
        if ( $tax_obj && in_array( 'estate_property', (array) $tax_obj->object_type, true ) ) {
            return true;
        }
    }

    if ( $position === $tax ) {
        return true;
    }

    $tax_obj = get_taxonomy( $tax );
    if ( $tax_obj && ! empty( $tax_obj->object_type ) ) {
        if ( in_array( $position, (array) $tax_obj->object_type, true ) ) {
            return true;
        }
    }

    return false;
}

/**
 * Locate a design studio template for a taxonomy archive.
 *
 * @param string $tax     Taxonomy slug.
 * @param int    $term_id Term ID.
 * @return int Template post ID or 0 if none found.
 */
function wpestate_get_category_template_id( $tax, $term_id = 0 ) {
    $args = array(
        'post_type'      => 'wpestate-studio',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_key'       => 'wpestate_head_foot_template',
        'meta_value'     => 'wpestate_category_page',
    );

    $posts = get_posts( $args );
    if ( empty( $posts ) ) {
        return 0;
    }

    foreach ( $posts as $post ) {
        $positions         = get_post_meta( $post->ID, 'wpestate_head_foot_positions', true );
        $exclude_positions = get_post_meta( $post->ID, 'wpestate_head_foot_exclude_positions', true );
        $tax_terms         = get_post_meta( $post->ID, 'wpestate_head_foot_tax_terms', true );
        $exclude_terms     = get_post_meta( $post->ID, 'wpestate_head_foot_exclude_tax_terms', true );

        $positions         = is_array( $positions ) ? $positions : ( $positions ? array( $positions ) : array() );
        $exclude_positions = is_array( $exclude_positions ) ? $exclude_positions : ( $exclude_positions ? array( $exclude_positions ) : array() );
        $tax_terms         = is_array( $tax_terms ) ? $tax_terms : array();
        $exclude_terms     = is_array( $exclude_terms ) ? $exclude_terms : array();

        // Skip if any exclude rule matches.
        foreach ( $exclude_positions as $idx => $ex_pos ) {
            $ex_term = isset( $exclude_terms[ $idx ] ) ? $exclude_terms[ $idx ] : '';
            if ( wpestate_category_match( $ex_pos, $ex_term, $tax, $term_id ) ) {
                continue 2;
            }
        }

        foreach ( $positions as $idx => $pos ) {
            $term_val = isset( $tax_terms[ $idx ] ) ? $tax_terms[ $idx ] : '';
            if ( wpestate_category_match( $pos, $term_val, $tax, $term_id ) ) {
                return (int) $post->ID;
            }
        }
    }

    return 0;
}

/**
 * Get the category template ID for the current archive.
 *
 * @return int Template ID or 0.
 */
function wpestate_current_category_template_id() {
    if ( ! ( is_tax() || is_category() || is_tag() ) ) {
        return 0;
    }

    $obj = get_queried_object();
    if ( empty( $obj ) || is_wp_error( $obj ) ) {
        return 0;
    }

    return wpestate_get_category_template_id( $obj->taxonomy, $obj->term_id );
}

/**
 * Output the category template for the current archive if available.
 */
function wpestate_render_current_category_template() {
    $id = wpestate_current_category_template_id();
    if ( $id ) {
        echo WpRentals_Render_Template::get_elementor_template( $id );
    }
}



add_action('save_post_wpestate-studio', 'wpestate_clear_property_template_cache');
add_action('deleted_post', 'wpestate_clear_property_template_cache_on_delete');

/**
 * Clear cache when a wpestate-studio post is added or updated
 */
function wpestate_clear_property_template_cache( $post_id ) {
    // Prevent autosave or revision triggers
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
        return;
    }

    if ( get_post_type( $post_id ) === 'wpestate-studio' ) {
        delete_transient( 'wpestate_property_page_templates' );
     }
}

/**
 * Clear cache when a wpestate-studio post is deleted
 */
function wpestate_clear_property_template_cache_on_delete( $post_id ) {
    if ( get_post_type( $post_id ) === 'wpestate-studio' ) {
        delete_transient( 'wpestate_property_page_templates' );
    }
}
/**
 * Get the latest wpestate-studio post ID.
 *
 * @return int Post ID or 0 if none found.
 */
function wpestate_last_property_id() {
    $query = new WP_Query([
        'post_type'      => 'estate_property',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'orderby'        => 'ID',
        'order'          => 'DESC',
        'fields'         => 'ids',
    ]);
    return !empty($query->posts) ? (int) $query->posts[0] : 0;

}

/**
 * Get the latest wpestate-studio agnet ID.
 *
 * @return int Post ID or 0 if none found.
 */
function wpestate_last_agent_id() {
    $query = new WP_Query([
        'post_type'      => 'estate_agent',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'orderby'        => 'ID',
        'order'          => 'DESC',
        'fields'         => 'ids',
    ]);
    return !empty($query->posts) ? (int) $query->posts[0] : 0;

}

/**
 * Get the latest wpestate-studio post ID.
 *
 * @return int Post ID or 0 if none found.
 */
function wpestate_last_post_id() {
    $query = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'orderby'        => 'ID',
        'order'          => 'DESC',
        'fields'         => 'ids',
    ]);
    return !empty($query->posts) ? (int) $query->posts[0] : 0;

}


function wpestate_enqueue_template_css() {
    $template_functions = array(
        'wpestate_header_template_id',
        'wpestate_before_header_template_id',
        'wpestate_after_header_template_id',
        'wpestate_footer_template_id',
        'wpestate_before_footer_template_id',
        'wpestate_after_footer_template_id',
        'wpestate_single_property_template_id',
        'wpestate_single_agent_template_id',
        'wpestate_single_post_template_id',
        'wpestate_current_category_template_id',
    );

    foreach ( $template_functions as $func ) {
        if ( function_exists( $func ) ) {
            $id = call_user_func( $func );
            if ( $id && did_action( 'elementor/loaded' ) ) {
                $css_file = new \Elementor\Core\Files\CSS\Post( $id );
                $css_file->enqueue();
            }
        }
    }
}

add_action( 'wp_enqueue_scripts', 'wpestate_enqueue_template_css', 5 );
function wpestate_category_template_enabled() {
    return wpestate_current_category_template_id() !== 0;
}