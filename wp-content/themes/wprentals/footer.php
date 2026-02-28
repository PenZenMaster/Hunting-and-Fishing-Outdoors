</div><!-- end content_wrapper started in header or full_width_row from prop list -->

<?php
global $post;   
$page_template='';
if(isset($post->ID)){
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
}

$show_half_map_footer = wprentals_get_option('wp_estate_property_list_type_footer', 'no');

if( !is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && wpestate_check_if_admin_page($post->ID) ){
    // do nothing for now

} else if(!is_search() && !is_category() && !is_tax() &&  !is_tag() &&  !is_archive() && $page_template== 'property_list_half.php'){
    // do nothing for now

    if ( $show_half_map_footer == 'yes' ) {

        get_template_part( 'templates/footer_template','', array() );
        
    }

} else if( ( is_category() || is_tax() ) &&    get_post_type() !== 'post' && wprentals_get_option('wp_estate_property_list_type')==2){
    // show footer on taxonomy half map pages if option enabled
    if ( $show_half_map_footer == 'yes' ) {
        get_template_part( 'templates/footer_template','', array() );
    }

} else if(  $page_template=='advanced_search_results.php' &&  wprentals_get_option('wp_estate_property_list_type_adv')==2){
    // show footer on search results half map pages if option enabled
    if ( $show_half_map_footer == 'yes' ) {
        get_template_part( 'templates/footer_template','', array() );
    }

}else{


?>
<?php 
if(is_singular('estate_property')){
    print wpestate_property_disclaimer_section($post->ID);
}

isset($post->ID) ? $post_id =$post->ID : $post_id='';
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
    if ( function_exists( 'wpestate_footer_enabled' ) && wpestate_footer_enabled() ) {
        if ( ! function_exists( 'wpestate_before_footer_enabled' ) || wpestate_before_footer_enabled() ) {
            do_action( 'wpestate_before_footer' );
        }

        do_action( 'wpestate_footer_studio' );

        if ( ! function_exists( 'wpestate_after_footer_enabled' ) || wpestate_after_footer_enabled() ) {
            do_action( 'wpestate_after_footer' );
        }
    } else {
        get_template_part( 'templates/footer_template','', array() );
    }
}

?>


<?php } // end property_list_half?>



</div> <!-- end class container -->



</div> <!-- end website wrapper -->


<?php wp_footer(); 
?>
</body>
</html>
