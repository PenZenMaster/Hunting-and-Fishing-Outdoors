<?php
/** MILLDONE
 * Property Admin Columns
 * src: post-types\property\property-admin-columns.php
 * Handles custom columns for the 'estate_property' post type in the WordPress admin area. This includes adding, populating, and sorting custom columns.
 *
 * @package WPRentals Core
 * @subpackage Property
 * @since 4.0.0
 *
 * @dependencies
 * - WordPress post type functions
 * - WPRentals theme options and settings
 *
 * Usage:
 * - This file should be included as part of the WPRentals theme. It adds custom columns to the "estate_property" post type in the admin area, providing additional information about each property.
 */

// Add filter to modify columns for the 'estate_property' post type.
add_filter( 'manage_edit-estate_property_columns', 'wpestate_my_columns' );

if (!function_exists('wpestate_my_columns')):
    /**
     * Defines custom columns for property listings in admin
     * 
     * Customizes the columns shown in the properties list table including:
     * ID, thumbnail, location info, property details, pricing, and featured status
     *
     * @param array $columns The default WordPress columns
     * @return array Modified array of columns
     */
    function wpestate_my_columns($columns) {
    unset($columns['comments']);
    unset($columns['author']);
    unset($columns['date']);
    unset($columns['revealid_id']);
    
    $custom_columns = array(
        'estate_image' => esc_html__('Image', 'wprentals-core'),
        'title' => esc_html__('Title', 'wprentals-core'),
        'estate_info' => esc_html__('Info', 'wprentals-core'),
        'estate_price' => esc_html__('Price', 'wprentals-core'),
        'estate_status' => esc_html__('Status', 'wprentals-core'),
        'estate_featured' => esc_html__('Featured', 'wprentals-core'),
        'estate_user_date' => esc_html__('User / Date', 'wprentals-core'),
        'estate_ID' => esc_html__('ID', 'wprentals-core'),
        'estate_actions' => esc_html__('Actions', 'wprentals-core'),
    );
    
    $ordered = array();
    $ordered['estate_image'] = $custom_columns['estate_image'];
    $ordered['title'] = $custom_columns['title'];
    
    foreach ($custom_columns as $key => $label) {
        if ($key !== 'estate_image' && $key !== 'title') {
            $ordered[$key] = $label;
        }
    }
    
    $columns = array_merge($columns, $ordered);
   
 
    // Switch title and estate_image
    $reordered = array();
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $reordered['estate_image'] = $columns['estate_image'];
            $reordered['title'] = $value;
            unset($columns['estate_image']);
        } elseif ($key !== 'estate_image') {
            $reordered[$key] = $value;
        }
    }
    
    return $reordered;
    }

endif;











// Add action to populate custom columns.
add_action( 'manage_posts_custom_column', 'wpestate_populate_columns' );

if ( ! function_exists( 'wpestate_populate_columns' ) ):
    /**
     * Populates the custom columns for 'estate_property' in the admin listing.
     *
     * @param string $column The name of the column being populated.
     */
    function wpestate_populate_columns( $column ) {
        $the_id = get_the_ID();

         if ('estate_ID' == $column) {
            echo $the_id;
        } 

        else if ( 'estate_image' == $column ) {
            if ( has_post_thumbnail( $the_id ) ) {
                echo get_the_post_thumbnail( $the_id, 'wpestate_slider_thumb' );
            } else {
                $thumb_prop_default = wprentals_get_option( 'wp_estate_default_property_image', 'url' );
                if ( empty( $thumb_prop_default ) ) {
                    $thumb_prop_default = get_stylesheet_directory_uri() . '/img/defaultimage_prop.jpg';
                }
                echo '<img src="' . esc_url( $thumb_prop_default ) . '" class="attachment-wpestate_slider_thumb" alt="" />';
            }
        }

        else if ( 'estate_featured' == $column ) {
            $is_featured = intval( get_post_meta( $the_id, 'prop_featured', true ) ) === 1 ? 'Yes' : 'No';
            echo esc_html( $is_featured );
        }

        else if ( 'estate_status' == $column ) {
            $estate_status = get_post_status( $the_id );
            $status_class  = sanitize_html_class( 'publish' === $estate_status ? 'published' : $estate_status );

            $status_labels = array(
                'publish' => esc_html__( 'Published', 'wprentals-core' ),
                'pending' => esc_html__( 'Pending', 'wprentals-core' ),
                'draft'   => esc_html__( 'Draft', 'wprentals-core' ),
                'expired' => esc_html__( 'Expired', 'wprentals-core' ),
            );

            $status_text = $status_labels[ $estate_status ] ?? $estate_status;

            echo '<span class="status_label ' . esc_attr( $status_class ) . '">' . esc_html( $status_text ) . '</span>';

            $paid_submission = esc_html( wprentals_get_option( 'wp_estate_paid_submission', '' ) );
            if ( 'per listing' === $paid_submission ) {
                $pay_status = get_post_meta( $the_id, 'pay_status', true );
                if ( ! empty( $pay_status ) ) {
                    $pay_class = sanitize_html_class( strtolower( str_replace( array( ' ', '-' ), '', $pay_status ) ) );
                    echo ' <span class="status_label ' . esc_attr( $pay_class ) . '">' . esc_html( $pay_status ) . '</span>';
                }
            }
        }

        else if ( 'estate_user_date' == $column ) {
            $user_id      = wpsestate_get_author( $the_id );
            $estate_autor = get_the_author_meta( 'display_name', $user_id );
            $post_date    = get_the_date( '', $the_id );
            echo esc_html__( 'Published by', 'wprentals-core' ) . ' ';
            echo '<a href="' . esc_url( get_edit_user_link( $user_id ) ) . '">' . esc_html( $estate_autor ) . '</a><br>';
            echo esc_html( $post_date );
        }

        else if ( 'estate_info' == $column ) {
            $city     = get_the_term_list( $the_id, 'property_city', '', ', ', '' );
            $area     = get_the_term_list( $the_id, 'property_area', '', ', ', '' );
            $action   = get_the_term_list( $the_id, 'property_action_category', '', ', ', '' );
            $category = get_the_term_list( $the_id, 'property_category', '', ', ', '' );

            if ( ! empty( $city ) ) {
                echo 'City: ' . wp_kses_post( $city ) . '<br>';
            }
            if ( ! empty( $area ) ) {
                echo 'Area: ' . wp_kses_post( $area ) . '<br>';
            }
            if ( ! empty( $action ) ) {
                echo 'Action: ' . wp_kses_post( $action ) . '<br>';
            }
            if ( ! empty( $category ) ) {
                echo 'Category: ' . wp_kses_post( $category ) . '<br>';
            }
        }

        else if ( 'estate_price' == $column ) {
            $wpestate_currency = esc_html( wprentals_get_option( 'wp_estate_currency_label_main', '' ) );
            $wpestate_where_currency = esc_html( wprentals_get_option( 'wp_estate_where_currency_symbol', '' ) );
            wpestate_show_price( $the_id, $wpestate_currency, $wpestate_where_currency, 0 );
        } 
        else if ('estate_actions' == $column) {

            echo '<div class="wpestate_admin_actions_wrapper">';
            echo wp_estate_display_action_buttons($the_id);
            echo '</div>';

        }
    }
endif;





// Add filter to make certain columns sortable.
add_filter( 'manage_edit-estate_property_sortable_columns', 'wprentals_sort_columns' );

if ( ! function_exists( 'wprentals_sort_columns' ) ):
    /**
     * Makes certain columns sortable in the 'estate_property' admin listing.
     *
     * @param array $columns The existing columns.
     * @return array Modified columns with sortable options.
     */
    function wprentals_sort_columns( $columns ) {
        $columns['estate_price']    = 'estate_price';
        $columns['estate_status']   = 'estate_status';
        $columns['estate_featured'] = 'estate_featured';
        $columns['estate_id']       = 'estate_id';
        return $columns;
    }
endif;






// Add filter to modify the orderby request for sorting custom columns.
add_filter( 'request', 'wprentals_sort_custom_columns' );

if ( ! function_exists( 'wprentals_sort_custom_columns' ) ):
    /**
     * Modifies the query to sort by custom columns in the 'estate_property' admin listing.
     *
     * @param array $vars The query variables.
     * @return array Modified query variables for custom sorting.
     */
    function wprentals_sort_custom_columns( $vars ) {
        if ( isset( $vars['orderby'] ) ) {
            switch ( $vars['orderby'] ) {
                case 'estate_price':
                    $vars = array_merge(
                        $vars,
                        array(
                            'meta_key' => 'property_price',
                            'orderby'  => 'meta_value_num',
                        )
                    );
                    break;
                case 'estate_featured':
                    $vars = array_merge(
                        $vars,
                        array(
                            'meta_key' => 'prop_featured',
                            'orderby'  => 'meta_value_num',
                        )
                    );
                    break;
                case 'estate_status':
                    $vars['orderby'] = 'post_status';
                    break;
                case 'estate_id':
                    $vars['orderby'] = 'ID';
                    break;
            }
        }

        return $vars;
    }
endif;

// Add filters for ID and taxonomies in the admin list.
add_action( 'restrict_manage_posts', 'wpestate_property_filters', 10, 2 );
add_filter( 'parse_query', 'wpestate_property_filters_query' );

if ( ! function_exists( 'wpestate_property_filters' ) ):
    /**
     * Outputs filtering controls above the posts table for properties.
     *
     * @param string $post_type The current post type.
     * @param string $which     The placement of the tablenav.
     */
    function wpestate_property_filters( $post_type, $which ) {
        if ( 'estate_property' !== $post_type || 'top' !== $which ) {
            return;
        }

        $current_id = isset( $_GET['filter_by_id'] ) ? intval( $_GET['filter_by_id'] ) : '';
        echo '<input type="number" name="filter_by_id" placeholder="' . esc_attr__( 'ID', 'wprentals-core' ) . '" value="' . esc_attr( $current_id ) . '" style="max-width:80px;margin-right:6px;" />';

        $taxonomies = array(
            'property_city'            => esc_html__( 'All Cities', 'wprentals-core' ),
            'property_area'            => esc_html__( 'All Areas', 'wprentals-core' ),
            'property_action_category' => esc_html__( 'All Actions', 'wprentals-core' ),
            'property_category'        => esc_html__( 'All Categories', 'wprentals-core' ),
        );

        foreach ( $taxonomies as $tax => $label ) {
            $selected = isset( $_GET[ $tax ] ) ? sanitize_text_field( wp_unslash( $_GET[ $tax ] ) ) : '';
            wp_dropdown_categories(
                array(
                    'show_option_all' => $label,
                    'taxonomy'        => $tax,
                    'name'            => $tax,
                    'orderby'         => 'name',
                    'selected'        => $selected,
                    'hierarchical'    => true,
                    'depth'           => 1,
                    'show_count'      => 0,
                    'hide_empty'      => 0,
                    'value_field'     => 'slug',
                )
            );
        }
    }
endif;

if ( ! function_exists( 'wpestate_property_filters_query' ) ):
    /**
     * Applies filtering to the property admin query based on submitted filter values.
     *
     * @param WP_Query $query The current query instance.
     */
    function wpestate_property_filters_query( $query ) {
        global $pagenow;

        if ( 'edit.php' !== $pagenow || ! isset( $_GET['post_type'] ) || 'estate_property' !== $_GET['post_type'] ) {
            return;
        }

        if ( ! empty( $_GET['filter_by_id'] ) ) {
            $query->set( 'p', intval( $_GET['filter_by_id'] ) );
        }

        $taxonomies = array( 'property_city', 'property_area', 'property_action_category', 'property_category' );
        foreach ( $taxonomies as $tax ) {
            if ( ! empty( $_GET[ $tax ] ) ) {
                $query->set( $tax, sanitize_text_field( wp_unslash( $_GET[ $tax ] ) ) );
            }
        }
    }
endif;

// Ensure disabled listings appear when viewing all posts in admin.
add_action( 'pre_get_posts', 'wpestate_include_disabled_listings_admin' );
if ( ! function_exists( 'wpestate_include_disabled_listings_admin' ) ):
    /**
     * Include disabled listings in the "All" admin view when all_posts=1.
     *
     * @param WP_Query $query Current query instance.
     */
    function wpestate_include_disabled_listings_admin( $query ) {
        global $pagenow;

        if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() ) {
            return;
        }

        if ( ! isset( $_GET['post_type'] ) || 'estate_property' !== $_GET['post_type'] ) {
            return;
        }

        if ( empty( $_GET['all_posts'] ) || intval( $_GET['all_posts'] ) !== 1 ) {
            return;
        }

        $query->set( 'post_status', array( 'publish', 'pending', 'draft', 'expired', 'disabled' ) );
    }
endif;
?>
