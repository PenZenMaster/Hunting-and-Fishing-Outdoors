<?php

// Template Name: Add New Amenities

$amenity_entry_id = $_GET['am_id'];
global $wpdb;
$new_amenities_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM new_amenities WHERE new_amenity_entry_id = $amenity_entry_id"));
$add_amenity_name = $new_amenities_data[0]->nw_amenity_name;
$add_amenity_category = $new_amenities_data[0]->nw_amenity_category;
$add_amenity_description = $new_amenities_data[0]->nw_amenity_description;
$add_amenity_img_url = $new_amenities_data[0]->nw_amenity_image;
$add_amenity_slug = str_replace(' ', '-', $add_amenity_name);

if ($amenity_entry_id > 0 and $_GET['action'] == 'Approve') {
    $_POST['amenity_entry_id'] = $_GET['am_id'];
    approve_add_new_amenity();
}
if ($amenity_entry_id > 0 and $_GET['action'] == 'Deny') {
    $_POST['amenity_entry_id'] = $_GET['am_id'];
    deny_add_new_amenity();
}
?>
<style type="text/css">
.vdf_add_amenity_content .vdf_col {
    text-align: center;
}

.vdf_amnty_btns {
    display: inline-flex;
    padding-top: 30px;
}

.vdf_amnty_btns .vdf_amnty_btns_1,
.vdf_amnty_btns .vdf_amnty_btns_2 {
    margin: 0 20px;
}

.vdf_amnty_btns .vdf_amnty_btns_1 .vdf_approve {
    padding: 8px 15px;
    border: none;
    background: darkslateblue;
    color: #fff;
    border-radius: 7px;
    cursor: pointer;
}

.vdf_amnty_btns .vdf_amnty_btns_2 .vdf_deny {
    padding: 8px 15px;
    border: none;
    color: #000;
    border-radius: 7px;
    cursor: pointer;
}

#vdf_cnfrm_main01 p {
    display: none;
}

.vdf_amnty_details_main {
    width: 24%;
    margin: auto;
    text-align: left;
}

.vdf_amnty_dtls_content {
    display: flex;
}

.vdf_amnty_names {
    width: 55%;
    font-weight: 600;
}

.vdf_amnty_values {
    width: 50%;
}

.vdf_amnty_values a {
    text-decoration: none;
}

.vdf_open_error p {
    text-align: center;
    font-size: 20px;
    font-weight: 600;
}
</style>
<?php
function add_amenity($add_amenity_name, $add_amenity_category, $add_amenity_description)
{

    echo '<div class="vdf_add_amenity_main">
        <div class="vdf_add_amenity_content">
            <div class="vdf_container">
                <div class="vdf_row">
                    <div class="vdf_col">
                        <div class="vdf_amnty_text">
                            <h3>Click Approve to approve new amenity request</h3>
                        </div>
                        <div class="vdf_amnty_details_main">
                          <div class="vdf_amnty_dtls_content">
                            <div class="vdf_amnty_names">
                              <p>Amenity name</p>
                              <p>Amenity category</p>
                              <p>Amenity description</p>
                            </div>
                            <div class="vdf_amnty_values">
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_name . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_category . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_description . '</p>
                            </div>
                          </div>
                        </div>

                        <div class="vdf_cnfrm_main" id="vdf_cnfrm_main01">
                        ';
    if ($_GET['action'] == 'Approve') {
        echo ' <div class="vdf_cnfrm_msg" style="display:block">
                            <p style="display:block" id="vdf_appr_confrm01">New amenity request was added successfully</p>
                          </div>';
    }
    if ($_GET['action'] == 'Deny') {
        echo '<div class="vdf_cnfrm_msg" >
                            <p style="display:block" id="vdf_deny_confrm01">New amenity request was denied successfully</p>
                          </div>';
    }
    echo '</div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

}
function add_amenity_other($add_amenity_name, $add_amenity_category, $add_amenity_description, $add_amenity_img_url)
{

    echo '<div class="vdf_add_amenity_main">
        <div class="vdf_add_amenity_content">
            <div class="vdf_container">
                <div class="vdf_row">
                    <div class="vdf_col">
                        <div class="vdf_amnty_text">
                            <h3>Click Approve to approve new amenity request</h3>
                        </div>
                        <div class="vdf_amnty_details_main">
                          <div class="vdf_amnty_dtls_content">
                            <div class="vdf_amnty_names">
                              <p>Amenity name</p>
                              <p>Amenity category</p>
                              <p>Amenity description</p>
                              <p>Amenity Image</p>
                            </div>
                            <div class="vdf_amnty_values">
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_name . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_category . '</p>
                              <p><span>:</span>&nbsp;&nbsp' . $add_amenity_description . '</p>
                              <p><span>:</span>&nbsp;&nbsp;<a href="' . $add_amenity_img_url . '" target="_blank">View</a></p>
                            </div>
                          </div>
                        </div>

                        <div class="vdf_cnfrm_main" id="vdf_cnfrm_main01">
                        ';
    if ($_GET['action'] == 'Approve') {
        echo ' <div class="vdf_cnfrm_msg" style="display:block">
                                                <p style="display:block" id="vdf_appr_confrm01">New amenity request was added successfully</p>
                                              </div>';
    }
    if ($_GET['action'] == 'Deny') {
        echo '<div class="vdf_cnfrm_msg" style="display:block">
                                                <p style="display:block" id="vdf_deny_confrm01">New amenity request was denied successfully</p>
                                              </div>';
    }
    echo '</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

}
function add_amenity_error()
{

    echo '<div class="vdf_opn_again_err">
        <div class="vdf_open_error">
            <p>This request is no longer available because it has been already processed.</p>
        </div>
    </div>';

}
// var_dump($add_amenity_category);
// var_dump($add_amenity_name);
// var_dump($add_amenity_description);
// var_dump($add_amenity_slug);
// var_dump($add_amenity_img_url);
if ($add_amenity_category == 'Basic' || $add_amenity_category == 'Includes' || $add_amenity_category == 'Features') {

    if ($add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_slug != '') {
        add_amenity($add_amenity_name, $add_amenity_category, $add_amenity_description);
    } else {
        add_amenity_error();
    }
} else {
    if ($add_amenity_name != '' && $add_amenity_category != '' && $add_amenity_description != '' && $add_amenity_slug != '' && $add_amenity_img_url != '') {
        add_amenity_other($add_amenity_name, $add_amenity_category, $add_amenity_description, $add_amenity_img_url);
    } else {
        add_amenity_error();
    }

}

?>
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#vdf_approve01').click(function() {
        jQuery(this).parent().parent().css('display', 'none');
        jQuery("#vdf_cnfrm_main01 #vdf_appr_confrm01").css('display', 'block');
        var amenity_url = window.location.href
        //console.log(amenity_url);
        var amnty_id_positon = amenity_url.indexOf('=');
        var amenity_entry_id = amenity_url.substring(amnty_id_positon + 1, 70);
        console.log(amenity_entry_id);
        //console.log(amenity_entry_id);
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            data: {
                action: 'approve_add_new_amenity',
                amenity_entry_id: amenity_entry_id
            },
            success: function(response) {
                var data = response;
                console.log(data);
                // if (data == 'Fail') {
                //   jQuery(".vdf_opn_again_err").css('display','block');
                //   jQuery(".vdf_amnty_btns").css('display','none');
                //   jQuery(".vdf_cnfrm_main").css('display','none');
                // }
                // else{
                //   jQuery(".vdf_opn_again_err").css('display','none');
                //   jQuery(this).parent().parent().css('display','none');
                //   jQuery("#vdf_cnfrm_main01 #vdf_appr_confrm01").css('display','block');
                // }
            },
            error: function(xhr, status, error, response) {
                console.log(error);
                var err = eval("(" + xhr.responseText + ")");
                console.log(xhr);
            }
        });

    });
});
</script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#vdf_deny01').click(function() {
        jQuery(this).parent().parent().css('display', 'none');
        jQuery("#vdf_cnfrm_main01 #vdf_deny_confrm01").css('display', 'block');
        var amenity_url = window.location.href
        //console.log(amenity_url);
        var amnty_id_positon = amenity_url.indexOf('=');
        var amenity_entry_id = amenity_url.substring(amnty_id_positon + 1, 70);
        //console.log(amenity_entry_id);
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            data: {
                action: 'deny_add_new_amenity',
                amenity_entry_id: amenity_entry_id
            },
            success: function(response) {
                var data = response;
                console.log(data);
            },
            error: function(xhr, status, error, response) {
                console.log(error);
                var err = eval("(" + xhr.responseText + ")");
                console.log(xhr);
            }
        });

    });
});
</script>