<div class="listing_type_title_wrapper">
    <h1 itemprop="name" class="entry-title entry-prop"><?php the_title(); ?></h1>     
                    
    <div class="property_ratings">
        <?php 
        if(wpestate_has_some_review($post->ID)!==0){
            $args = array(
                'post_id' => $post->ID, // use post_id, not post_ID
            );
            $comments   =   get_comments($args);
            $coments_no =   0;
            $stars_total=   0;


            foreach($comments as $comment) :
                $coments_no++;

            endforeach;
            
            if($coments_no>0){
                print wpestate_display_property_rating( $post->ID ); 
                print '<div class="rating_no">('.esc_html($coments_no).')</div>';
            }
        } 
        ?>         
    </div> 
                
                    
    <?php
    if(trim($property_area)!=''){
        $property_area=', '.$property_area;
    }
    ?>

    <div class="listing_main_image_location" itemprop="location" itemscope itemtype="http://schema.org/Place">
        <?php print  wp_kses_post($property_city.$property_area); ?>     
        <div  class="schema_div_noshow" itemprop="name"><?php echo strip_tags (  $property_city.', '.$property_area); ?></div>
    </div>   

</div>   