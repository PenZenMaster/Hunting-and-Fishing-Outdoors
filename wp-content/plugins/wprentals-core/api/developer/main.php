<?php
/**MILLDONE
 * src: api\developer\main.php
 * WPRentals Developer Menu
 * Creates a new admin menu for developers with various debugging tools
 *
 * @package WPRentals Core
 * @subpackage Admin
 * @since 4.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Creates developer menu in WordPress admin
 * @since 4.0.0
 * @hooks into admin_menu
 */

add_action('admin_menu', 'wprentals_add_developer_menu');
function wprentals_add_developer_menu() {
    // Add main menu
    $wprentals_branding = wprentals_theme_branding(); // Uses the filter system
    add_menu_page(
        $wprentals_branding .' Developer',  // Page title
        $wprentals_branding .' Developer', // Menu title
        'manage_options',       // Capability required
        'wprentals-developer', // Menu slug
        'wprentals_developer_main_page', // Callback function
        'dashicons-code-standards', // Icon
        58 // Position after wprentals
    );

    // Add submenu for Users & Capabilities
    add_submenu_page(
        'wprentals-developer', // Parent slug
        'Users & Capabilities', // Page title
        'Users & Capabilities', // Menu title
        'manage_options',       // Capability required
        'wprentals-developer-capabilities', // Menu slug
        'wprentals_display_users_capabilities_page' // Callback function
    );

    // Add submenu for Performance Testing
    add_submenu_page(
        'wprentals-developer', // Parent slug
        'Performance Testing', // Page title
        'Performance Testing', // Menu title
        'manage_options',      // Capability required
        'wprentals-developer-performance', // Menu slug
        'wpestate_api_test_performance_page' // Callback function
    );
}


/**
 * Renders main developer tools page
 * @since 4.0.0
 * @security Requires manage_options capability
 */

function wprentals_developer_main_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('WPRentals Developer Tools', 'wprentals-core'); ?></h1>
        <div class="wprentals-dev-section">
            <p><?php echo esc_html__('Welcome to the WPRentals Developer Tools. Use the submenu items to access different developer features:', 'wprentals-core'); ?></p>
            <ul>
                <li><?php echo esc_html__('Users & Capabilities - View detailed information about user roles and capabilities', 'wprentals-core'); ?></li>
                <li><?php echo esc_html__('Performance Testing - Compare and analyze API performance metrics', 'wprentals-core'); ?></li>
            </ul>
        </div>



        <?php 
        
        $post_type='estate_property';
        $paged=1;
        $posts_per_page=10;
        $meta_input = array(
            array(
                'key' => 'property_price',
                'value' => 1,
                'compare' => '>=',
                'type' => 'numeric'
            )
        );


        $postID=657;
        $current_adv_filter_search_action       = get_post_meta ( $postID, 'adv_filter_search_action', true);
        $current_adv_filter_search_category     = get_post_meta ( $postID, 'adv_filter_search_category', true);
        $current_adv_filter_area                = get_post_meta ( $postID, 'current_adv_filter_area', true);
        $current_adv_filter_city                = get_post_meta ( $postID, 'current_adv_filter_city', true);



        $taxonomy_input=array(
            'property_category'=> array('apartment', 'house', 'villa'),
            'property_city'=> array('new-york', 'los-angeles','boston')
        );

        $taxonomy_input=array(
            'property_category'=> $current_adv_filter_search_category,
            'property_action_category'=> $current_adv_filter_search_action,
            'property_city'=> $current_adv_filter_city,
            'property_area'=> $current_adv_filter_area,
            'property_features'=> array(21,26)
        );

        

        $order=8;

        wpestate_api_custom_query(
            $post_type,
            $paged,
            $posts_per_page,
            $meta_input , 
            $taxonomy_input,
            $order);
        
        ?>




    </div>
    <?php
}

/**
 * Displays user capabilities analysis page
 * @since 4.0.0
 * @security Requires manage_options capability
 */

function wprentals_display_users_capabilities_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Users and Capabilities', 'wprentals-core'); ?></h1>
        <div class="wprentals-dev-section">
            <?php wprentals_display_users_capabilities(); ?>
        </div>
    </div>
    <?php
}

/**
 * Renders detailed user capabilities table
 * @since 4.0.0
 * @displays Role and capability information
 */
function wprentals_display_users_capabilities() {
    // Get all users
    $users = get_users(array(
        'orderby' => 'registered',
        'order' => 'DESC'
    ));

    // Add CSS for the table
    ?>
    <style type="text/css">
        .wprentals-caps-table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
            background: white;
        }
        .wprentals-caps-table th,
        .wprentals-caps-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .wprentals-caps-table th {
            background-color: #f5f5f5;
        }
        .wprentals-caps-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .capability-true {
            color: green;
            font-weight: bold;
        }
        .capability-false {
            color: red;
        }
        .user-roles {
            color: #2271b1;
            font-weight: bold;
        }
    </style>

    <table class="wprentals-caps-table">
        <thead>
            <tr>
                <th><?php esc_html_e('User', 'wprentals-core'); ?></th>
                <th><?php esc_html_e('Roles', 'wprentals-core'); ?></th>
                <th><?php esc_html_e('Capabilities', 'wprentals-core'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td>
                    <?php 
                    echo esc_html($user->user_login);
                    echo ' (ID: ' . esc_html($user->ID) . ')';
                    ?>
                </td>
                <td class="user-roles">
                    <?php 
                    $roles = array_map('translate_user_role', $user->roles);
                    echo esc_html(implode(', ', $roles));
                    ?>
                </td>
                <td>
                    <?php
                    $capabilities = array_keys($user->allcaps);
                    sort($capabilities);
                    foreach ($capabilities as $cap) {
                        echo '<div>';
                        echo '<span class="capability-' . ($user->allcaps[$cap] ? 'true' : 'false') . '">';
                        echo esc_html($cap);
                        echo '</span>';
                        echo '</div>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php
}

/**
 * Displays performance testing interface
 * @since 4.0.0
 * @security Requires manage_options capability
 */
function wpestate_api_test_performance_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Performance Testing', 'wprentals-core'); ?></h1>
        <div class="wprentals-dev-section">
            <?php 
            // Function to be implemented
            if (function_exists('wpestate_api_run_performance_test')) {
                wpestate_api_run_performance_test(10);
            } else {
                echo '<p>' . esc_html__('Performance testing function not implemented yet.', 'wprentals-core') . '</p>';
            }
            ?>
        </div>
    </div>
    <?php
}


// export thme options in json

function wprentals_export_redux_options_to_json() {
    $redux = ReduxFrameworkInstances::get_instance('wprentals_admin');
    $opt_data = array();
    
    if(isset($redux->sections)) {
        foreach($redux->sections as $section) {
            if(isset($section['fields']) && !empty($section['fields'])) {
                foreach($section['fields'] as $field) {
                    if(isset($field['id'])) {
                        $opt_data[] = array(
                            'id'        => $field['id'],
                            'type'      => isset($field['type']) ? $field['type'] : '',
                            'title'     => isset($field['title']) ? $field['title'] : '',
                            'subtitle'  => isset($field['subtitle']) ? $field['subtitle'] : '',
                            'options'   => isset($field['options']) ? $field['options'] : array(),
                            'default'   => isset($field['default']) ? $field['default'] : ''
                        );
                    }
                }
            }
        }
    }
    
    // Save to JSON
    $upload_dir = wp_upload_dir();
    $json_file_path = $upload_dir['basedir'] . '/redux_options_meta.json';
    file_put_contents($json_file_path, json_encode($opt_data, JSON_PRETTY_PRINT));
}

//add_action('redux/loaded', 'wprentals_export_redux_options_to_json');

