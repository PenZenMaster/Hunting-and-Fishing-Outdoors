<?php

function enqueue_wqs_script()
{

    wp_enqueue_script('wqs-script', get_template_directory_uri() . '/wqs/js/custom-script.js', array('jquery'), '1.3', true);

    if (is_singular('estate_property')) {}
    wp_enqueue_style('wqs-style', get_template_directory_uri() . '/wqs/css/custom-style.css', array(), '4.2', 'all');

}
add_action('wp_enqueue_scripts', 'enqueue_wqs_script');

add_action('elementor_pro/forms/new_record', 'wqs_new_record', 10, 2);
function wqs_new_record($record, $ajax_handler)
{

    $raw_fields = $record->get('fields');
    $fields = [];
    foreach ($raw_fields as $id => $field) {
        $fields[$id] = $field['value'];
    }
    global $wpdb;
    $last_entry_record = $wpdb->get_results($wpdb->prepare("SELECT  * FROM new_amenities ORDER BY new_amenity_entry_id DESC"));
    $last_entry = $last_entry_record[0]->new_amenity_entry_id;
    $last_entry_id = $last_entry + 1;
    // if ($fields['field_2167f68'] == '(binary)') {
    //  $fields['field_d034d3a'] = 0;
    // }
 
    $output['success'] = $wpdb->insert('new_amenities', array('new_amenity_entry_id' => $last_entry_id, 'nw_amenity_name' => $fields['field_2e513a9'], 'nw_amenity_category' => $fields['field_2167f68'], 'nw_amenity_description' => $fields['field_2084096'], 'nw_amenity_image' => $fields['field_d034d3a']));

    $to = 'support@hnfo.net';
    $subject = 'New Amenity Request';
    $message = '<h2>Hello Admin,</h2>';
    $message .= '<p><a href="https://hnfo.net/add-new-amenities/?action=Approve&am_id=' . $last_entry_id . '"><b>Approve</b></a> or <a href="https://hnfo.net/add-new-amenities/?action=Deny&am_id=' . $last_entry_id . '"><b>Deny</b></a> new amenity request<b></p>';
    $message .= '<p><img src="' . $fields['field_d034d3a'] . '" style="max-width:300px"></p>';
    $message .= '<h3>Below are new amenity details:</h3>';
    $message .= '<p><b>Amenity name: </b>' . $fields['field_2e513a9'] . '</p>';
    $message .= '<p><b>Amenity category: </b>' . $fields['field_2167f68'] . '</p>';
    $message .= '<p><b>Amenity description: </b>' . $fields['field_2084096'] . '</p>';

    //$message .= $current_user;
    $headers = '
    Content-Type: text/html; charset=UTF-8 \r\n
    From: support@hnfo.net' . "\r\n" .
        'CC:  . "\r\n" .
        Reply-To: support@hnfo.net' . "\r\n";
    wp_mail($to, $subject, $message, $headers);
    $output['success2'] = $message;
    $ajax_handler->add_response_data(true, $output);

}

add_action("wp_ajax_approve_add_new_amenity", "approve_add_new_amenity");
add_action("wp_ajax_nopriv_approve_add_new_amenity", "approve_add_new_amenity");

function approve_add_new_amenity()
{

    $amenity_entry_id = $_POST['amenity_entry_id'];

    global $wpdb;
    $new_amenities_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM new_amenities WHERE new_amenity_entry_id = $amenity_entry_id"));
    // if (!empty($new_amenities_data)) {
    $add_amenity_name = $new_amenities_data[0]->nw_amenity_name;
    $add_amenity_category = $new_amenities_data[0]->nw_amenity_category;
    $add_amenity_description = $new_amenities_data[0]->nw_amenity_description;
    $add_amenity_img_url = $new_amenities_data[0]->nw_amenity_image;
    $add_amenity_slug = str_replace(' ', '-', $add_amenity_name);
    if ($add_amenity_category == 'Basic' || $add_amenity_category == 'Features' || $add_amenity_category == 'Includes') {
        if ($add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_slug != '') {
            if ($add_amenity_category == 'Basic') {
                $add_amenity_parent = 21;
            } elseif ($add_amenity_category == 'Features') {
                $add_amenity_parent = 29;
            } elseif ($add_amenity_category == 'Includes') {
                $add_amenity_parent = 24;
            } elseif ($add_amenity_category == 'Type of Fish') {
                $add_amenity_parent = 94;
            } elseif ($add_amenity_category == 'Type of Game') {
                $add_amenity_parent = 178;
            }

            $create_amenities = wp_insert_term("$add_amenity_name", 'property_features', // the taxonomy
                array(
                    'description' => $add_amenity_description,
                    'slug' => strtolower($add_amenity_slug),
                    'parent' => $add_amenity_parent,
                )
            );
            $term_id = intval($create_amenities["term_id"]);
            update_term_meta($term_id, 'category_featured_image', $add_amenity_img_url);
            update_term_meta($term_id, 'is_fishing', 'Fishing');
            update_term_meta($term_id, 'is_hunt_camp', 'Hunt Camp');
            update_term_meta($term_id, 'is_hunting', 'Hunting');
            update_term_meta($term_id, 'is_hunt_fishing', 'Hunting and Fishing');
            update_term_meta($term_id, 'is_stay_and_fish', 'Stay and Fish');

            $del_amenities_data = $wpdb->get_results($wpdb->prepare("DELETE FROM new_amenities WHERE new_amenity_entry_id = $amenity_entry_id"));
            if ($_GET['action'] != 'Approve') {
                print_r(json_encode($term_id));
            }
        } else {
            echo json_encode('Fail');
        }
    } else {
        if ($add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_img_url != '' && $add_amenity_slug != '') {
            if ($add_amenity_category == 'Basic') {
                $add_amenity_parent = 21;
            } elseif ($add_amenity_category == 'Features') {
                $add_amenity_parent = 29;
            } elseif ($add_amenity_category == 'Includes') {
                $add_amenity_parent = 24;
            } elseif ($add_amenity_category == 'Type of Fish') {
                $add_amenity_parent = 94;
            } elseif ($add_amenity_category == 'Type of Game') {
                $add_amenity_parent = 178;
            }

            $create_amenities = wp_insert_term("$add_amenity_name", 'property_features', // the taxonomy
                array(
                    'description' => $add_amenity_description,
                    'slug' => strtolower($add_amenity_slug),
                    'parent' => $add_amenity_parent,
                )
            );
            $term_id = intval($create_amenities["term_id"]);
            update_term_meta($term_id, 'category_featured_image', $add_amenity_img_url);
            update_term_meta($term_id, 'is_fishing', 'Fishing');
            update_term_meta($term_id, 'is_hunt_camp', 'Hunt Camp');
            update_term_meta($term_id, 'is_hunting', 'Hunting');
            update_term_meta($term_id, 'is_hunt_fishing', 'Hunting and Fishing');
            update_term_meta($term_id, 'is_stay_and_fish', 'Stay and Fish');

            $del_amenities_data = $wpdb->get_results($wpdb->prepare("DELETE FROM new_amenities WHERE new_amenity_entry_id = $amenity_entry_id"));
            if ($_GET['action'] != 'Approve') {
                print_r(json_encode($term_id));
            }

        } else {
            echo json_encode('Fail');
        }
    }
    if ($_GET['action'] != 'Approve') {
        die();
    }

}
/*Ajax actions(hooks) when the admin click the new amenity request link received in email and clicks "Approve" then this function will
 *execute and adds that paricular amenity to the "amenities & features list" and displays in frontend
 */
//Code ends here

/*Ajax actions(hooks) when the admin click the new amenity request link received in email and clicks "Deny" then this function will
 *execute and it will not added in the amenity list and doesn't display in frontend
 *Deleting the request from the table which we are saving when user submits the new amenity form
 */
//Code starts here
add_action("wp_ajax_deny_add_new_amenity", "deny_add_new_amenity");
add_action("wp_ajax_nopriv_deny_add_new_amenity", "deny_add_new_amenity");

function deny_add_new_amenity()
{
    $amenity_entry_id = $_POST['amenity_entry_id'];

    global $wpdb;
    $del_amenities_data = $wpdb->get_results($wpdb->prepare("DELETE FROM new_amenities WHERE new_amenity_entry_id = $amenity_entry_id"));

    if ($_GET['action'] != 'Deny') {
        print_r(json_encode($del_amenities_data));
        die();
    }
}

// Add taxonomy meta box
function add_taxonomy_meta_box()
{
    $taxonomy = 'property_features'; // Specify the taxonomy you want to target
    $post_type = 'estate_property'; // Specify the post type you want to target

    add_action("{$taxonomy}_add_form_fields", 'taxonomy_meta_box_callback');
    add_action("{$taxonomy}_edit_form_fields", 'taxonomy_meta_box_callback');
    add_action("create_{$taxonomy}", 'save_taxonomy_meta_box_data');
    add_action("edited_{$taxonomy}", 'save_taxonomy_meta_box_data');
}
add_action('admin_init', 'add_taxonomy_meta_box');

// Taxonomy meta box callback
function taxonomy_meta_box_callback($term)
{
    $taxonomy = 'property_action_category'; // Specify the taxonomy you want to target
    $terms = get_terms($taxonomy, array(
        'hide_empty' => false,
    ));
    $saved_term_ids = array();

    if (isset($term->term_id)) {
        $saved_terms = get_term_meta($term->term_id, 'taxonomy_terms', true);
        $saved_term_ids = !empty($saved_terms) ? $saved_terms : array();
    }

    foreach ($terms as $term) {
        $checked = in_array($term->term_id, $saved_term_ids) ? 'checked' : '';
        echo '<label>';
        echo '<input type="checkbox" name="taxonomy_terms[]" value="' . $term->term_id . '" ' . $checked . '>';
        echo $term->name;
        echo '</label><br>';
    }
}

// Save taxonomy meta box data
function save_taxonomy_meta_box_data($term_id)
{
    $taxonomy = 'property_category'; // Specify the taxonomy you want to target

    if (isset($_POST['taxonomy_terms'])) {
        $taxonomy_terms = $_POST['taxonomy_terms'];
        update_term_meta($term_id, 'taxonomy_terms', $taxonomy_terms);
    } else {
        delete_term_meta($term_id, 'taxonomy_terms');
    }
}

// Add the code to the wp_footer action
add_action('wp_footer', 'set_last_entry_id');

function set_last_entry_id() {
    global $wpdb;

    $last_entry_record = $wpdb->get_results($wpdb->prepare("SELECT * FROM new_amenities ORDER BY new_amenity_entry_id DESC"));
    $last_entry = $last_entry_record[0]->new_amenity_entry_id;
    $last_entry_id = $last_entry + 1;

    // Output the jQuery script to set the value of the input field
    echo '<script>
        jQuery(document).ready(function($) {
            $("#form-field-field_7ecc6f3").val(' . $last_entry_id . ');
        });
    </script>';
}
