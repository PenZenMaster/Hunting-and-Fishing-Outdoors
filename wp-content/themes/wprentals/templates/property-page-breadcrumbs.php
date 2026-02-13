<?php
$enable_show_breadcrumbs           =    wprentals_get_option('wp_estate_show_breadcrumbs');
$wpestate_property_breadcrumbs     =    wprentals_get_option('wpestate_property_breadcrumbs');

if($enable_show_breadcrumbs=='yes'){
    $item_custom='';

    if(is_array($wpestate_property_breadcrumbs) && !empty($wpestate_property_breadcrumbs)):

        foreach($wpestate_property_breadcrumbs as $key=>$value){
            $terms= get_the_term_list($post->ID, $value, '', ', ', '');
            if($terms!=''){
                $item_custom.=  '<li>'. $terms.'</li>';
            }
          
        }

    endif;



?>
<div class="col-xs-12 col-md-12 breadcrumb_container">
    <ol class="breadcrumb">
        <li>
            <a href=" <?php echo esc_url(home_url('/'));?> "><?php print esc_html__('Home','wprentals');?></a>
        </li>
        <?php
            if($item_custom!=''){
                print trim($item_custom);
            }
        ?>
        <li class="active">
            <?php echo get_the_title($post->ID);?>
        </li>
    </ol>
</div>


<?php 
} 
?>