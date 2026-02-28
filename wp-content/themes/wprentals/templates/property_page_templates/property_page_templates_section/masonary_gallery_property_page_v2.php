<?php
global $post_attachments;
global $post;
$post_thumbnail_id       =   get_post_thumbnail_id( $post->ID );
$preview                 =   wp_get_attachment_image_src($post_thumbnail_id, 'full');
$wpestate_currency       =   esc_html( wprentals_get_option('wp_estate_currency_label_main', '') );
$wpestate_where_currency =   esc_html( wprentals_get_option('wp_estate_where_currency_symbol', '') );
$price                   =   intval   ( get_post_meta($post->ID, 'property_price', true) );
$price_label             =   esc_html ( get_post_meta($post->ID, 'property_label', true) );

?>

<div class="listing_main_image header_masonry panel-body imagebody imagebody_new" id="">
    <div class="gallery_wrapper gallery_wrapper_v2 row">
        <?php echo wpestate_return_property_status($post->ID); ?>
        <?php
        $hidden = '';
        $post_attachments = wpestate_generate_property_slider_image_ids($post->ID, false);
        $total_pictures   = count($post_attachments);

        // Add featured image
        $post_thumbnail_id = get_post_thumbnail_id();
        $post_attachments  = array_diff($post_attachments, [$post_thumbnail_id]);

        $full_prty         = wp_get_attachment_image_src($post_thumbnail_id, 'wpestate_property_featured');
        $full_prty_hidden  = wp_get_attachment_image_src($post_thumbnail_id, 'full');
        $full_prty_0       = isset($full_prty[0]) ? $full_prty[0] : '';
        $full_prty_hidden_0 = isset($full_prty_hidden[0]) ? $full_prty_hidden[0] : '';

        $image_excerpt = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);
        if (!$image_excerpt) {
            $image_excerpt = get_sanitized_truncated_title($post->ID, 0);
        }

        $post_attachments = array_values(array_filter($post_attachments, function ($value) use ($post_thumbnail_id) {
            return (int) $value !== (int) $post_thumbnail_id && $value !== 'undefined';
        }));

        $display_attachments   = array_slice($post_attachments, 0, 5);
        $remaining_attachments = array_slice($post_attachments, 5);

        $slider_index = 1;
        print '<div class="col-md-8 image_gallery lightbox_trigger special_border" data-slider-no="' . esc_attr($slider_index) . '" style="background-image:url(' . esc_attr($full_prty_0) . ')">';
        print '    <div class="img_listings_overlay"></div>';
        print '</div>';

 
        $hidden .= '<a href="' . esc_url($full_prty_hidden_0) . '" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" title="' . esc_attr__('featured image', 'wprentals') . '" data-caption="' . esc_attr($image_excerpt) . '" class="fancybox-thumb prettygalery listing_main_image" data-elementor-open-lightbox="no">';

        $hidden .= '    <img src="' . esc_url($full_prty_hidden_0) . '" data-original="' . esc_attr($full_prty_hidden_0) . '" alt="' . esc_attr($image_excerpt) . '" class="img-responsive"/>';
        $hidden .= '</a>';

        $slider_index++;

        if (!empty($display_attachments)) {
            print '<div class="col-md-4 wpresidence_gallery_first_col">';

            for ($i = 0; $i < 2 && $i < count($display_attachments); $i++) {
                $attachment_id = $display_attachments[$i];
                if (!wp_attachment_is_image($attachment_id)) {
                    continue;
                }

                $image_excerpt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                if (!$image_excerpt) {
                    $image_excerpt = get_sanitized_truncated_title($post->ID, 0);
                }

                $full_prty        = wp_get_attachment_image_src($attachment_id, 'listing_full_slider');
                $full_prty_hidden = wp_get_attachment_image_src($attachment_id, 'full');

                $special_class = $i === 0 ? ' special_border_top ' : '';

                print '<div class="image_gallery lightbox_trigger ' . esc_attr($special_class) . '" data-slider-no="' . esc_attr($slider_index) . '" style="background-image:url(' . esc_attr($full_prty[0]) . ')">';
                print '    <div class="img_listings_overlay"></div>';
                print '</div>';

                $hidden .= ' <a href="' . esc_url($full_prty_hidden[0]) . '" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" data-caption="' . esc_attr($image_excerpt) . '" class="fancybox-thumb prettygalery listing_main_image">';
                $hidden .= '        <img src="' . esc_url($full_prty_hidden[0]) . '" data-original="' . esc_attr($full_prty_hidden[0]) . '" alt="' . esc_attr($image_excerpt) . '" class="img-responsive" />';
                $hidden .= '    </a>';

                $slider_index++;
            }

            print '</div>';
        }

        for ($i = 2; $i < count($display_attachments); $i++) {
            $attachment_id = $display_attachments[$i];
            if (!wp_attachment_is_image($attachment_id)) {
                continue;
            }

            $image_excerpt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            if (!$image_excerpt) {
                $image_excerpt = get_sanitized_truncated_title($post->ID, 0);
            }

            $full_prty        = wp_get_attachment_image_src($attachment_id, 'listing_full_slider');
            $full_prty_hidden = wp_get_attachment_image_src($attachment_id, 'full');

            $classes = 'image_gallery lightbox_trigger col-md-4';
            if ($i === 2) {
                $classes .= ' special_border_left';
            } elseif ($i === 4) {
                $classes = 'col-md-4 image_gallery last_gallery_item lightbox_trigger';
            }

            print '<div class="' . esc_attr($classes) . '" data-slider-no="' . esc_attr($slider_index) . '" style="background-image:url(' . esc_attr($full_prty[0]) . ')">';
            if ($i === count($display_attachments) - 1) {
                print '    <div class="img_listings_overlay img_listings_overlay_last"></div>';
                print '    <span class="img_listings_mes">' . esc_html__('See all', 'wprentals') . ' ' . esc_html($total_pictures) . ' ' . esc_html__('photos', 'wprentals') . '</span>';
            } else {
                print '    <div class="img_listings_overlay"></div>';
            }
            print '</div>';

            $hidden .= ' <a href="' . esc_url($full_prty_hidden[0]) . '" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" data-caption="' . esc_attr($image_excerpt) . '" class="fancybox-thumb prettygalery listing_main_image">';
            $hidden .= '        <img src="' . esc_url($full_prty_hidden[0]) . '" data-original="' . esc_attr($full_prty_hidden[0]) . '" alt="' . esc_attr($image_excerpt) . '" class="img-responsive" />';
            $hidden .= '    </a>';

            $slider_index++;
        }

        foreach ($remaining_attachments as $attachment_id) {
            if (!wp_attachment_is_image($attachment_id)) {
                continue;
            }

            $image_excerpt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            if (!$image_excerpt) {
                $image_excerpt = get_sanitized_truncated_title($post->ID, 0);
            }

            $full_prty_hidden = wp_get_attachment_image_src($attachment_id, 'full');

            $hidden .= ' <a href="' . esc_url($full_prty_hidden[0]) . '" rel="data-fancybox-thumb" data-fancybox="website_rental_gallery" data-caption="' . esc_attr($image_excerpt) . '" class="fancybox-thumb prettygalery listing_main_image">';
            $hidden .= '        <img src="' . esc_url($full_prty_hidden[0]) . '" data-original="' . esc_attr($full_prty_hidden[0]) . '" alt="' . esc_attr($image_excerpt) . '" class="img-responsive" />';
            $hidden .= '    </a>';
        }
        ?>
    </div>
</div>
<div class="hidden_photos hidden_type3"><?php echo trim($hidden); ?></div>