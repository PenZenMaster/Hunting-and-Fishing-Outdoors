<?php

if ( ! function_exists( 'wprentals_render_postslider' ) ) {
    /**
     * Render the post slider for a given post.
     *
     * @param int $post_id Post identifier.
     *
     * @return string
     */
    function wprentals_render_postslider( $post_id ) {
        $post_id = intval( $post_id );

        if ( $post_id <= 0 ) {
            return '';
        }

        $group_pictures = esc_html( get_post_meta( $post_id, 'group_pictures', true ) );

        if ( 'yes' !== $group_pictures ) {
            return '';
        }

        $arguments = array(
            'numberposts'    => -1,
            'post_type'      => 'attachment',
            'post_parent'    => $post_id,
            'post_status'    => null,
            'orderby'        => 'menu_order',
            'post_mime_type' => 'image',
            'order'          => 'ASC',
        );

        $post_attachments = get_posts( $arguments );

        if ( empty( $post_attachments ) && ! has_post_thumbnail( $post_id ) ) {
            return '';
        }

        ob_start();
        ?>
        <div id="carousel-example-generic" class="carousel slide post-carusel" data-ride="carousel" data-interval="false">
            <ol class="carousel-indicators">
                <?php
                $counter = 0;
                foreach ( $post_attachments as $attachment ) {
                    $counter++;
                    $active = ( 1 === $counter ) ? ' active ' : ' ';
                    ?>
                    <li data-target="#carousel-example-generic" data-slide-to="<?php print intval( $counter - 1 ); ?>" class="<?php echo esc_attr( $active ); ?>"></li>
                    <?php
                }
                ?>
            </ol>
            <div class="carousel-inner">
                <?php
                $counter = 0;
                foreach ( $post_attachments as $attachment ) {
                    $counter++;
                    $active           = ( 1 === $counter ) ? ' active ' : ' ';
                    $full_img         = wp_get_attachment_image_src( $attachment->ID, 'wpestate_property_full_map' );
                    $full_prty        = wp_get_attachment_image_src( $attachment->ID, 'full' );
                    $attachment_meta  = wpestate_get_attachment( $attachment->ID );
                    $attachment_alt   = isset( $attachment_meta['caption'] ) ? $attachment_meta['caption'] : '';
                    ?>
                    <div class="item <?php echo esc_attr( $active ); ?>">
                        <a href="<?php echo esc_url( $full_prty[0] ); ?>" rel="prettyPhoto[pp_gal]" class="prettygalery">
                            <img src="<?php echo esc_url( $full_img[0] ); ?>" alt="<?php echo esc_attr( $attachment_alt ); ?>" class="img-responsive" />
                        </a>
                        <?php if ( ! empty( $attachment_meta['caption'] ) ) { ?>
                            <div class="carousel-caption">
                                <div class="carousel-caption-text"><?php echo esc_html( $attachment_meta['caption'] ); ?></div>
                                <div class="carousel-caption-back"></div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <a class="left carousel-control" href="#carousel-example-generic" id="post_carusel_left" data-slide="prev">
                <i class="fas fa-angle-left"></i>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" id="post_carusel_right" data-slide="next">
                <i class="fas fa-angle-right"></i>
            </a>
        </div>
        <?php

        return ob_get_clean();
    }
}

if ( ! function_exists( 'wprentals_render_breadcrumbs' ) ) {
    /**
     * Render breadcrumbs for a given post.
     *
     * @param int $post_id Post identifier.
     *
     * @return string
     */
    function wprentals_render_breadcrumbs( $post_id ) {
        $post_id = intval( $post_id );

        if ( $post_id <= 0 ) {
            $current_post = get_post();
            $post_id      = ( $current_post ) ? $current_post->ID : 0;
        }

        $category = get_the_term_list( $post_id, 'property_category', '', ', ', '' );

        if ( empty( $category ) || is_wp_error( $category ) ) {
            $category = get_the_category_list( ', ', '', $post_id );
        }

        ob_start();
        ?>
        <div class="col-md-12 breadcrumb_container">
            <?php if ( ! is_404() && ! is_front_page() ) { ?>
                <ol class="breadcrumb">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'wprentals' ); ?></a></li>
                    <?php
                    if ( is_archive() ) {
                        if ( is_category() || is_tax() ) {
                            ?>
                            <li class="active"><?php echo esc_html( single_cat_title( '', false ) ); ?></li>
                            <?php
                        } else {
                            ?>
                            <li class="active"><?php esc_html_e( 'Archives', 'wprentals' ); ?></li>
                            <?php
                        }
                    } elseif ( is_search() ) {
                        ?>
                        <li class="active"><?php esc_html_e( 'Search Results', 'wprentals' ); ?></li>
                        <?php
                    } else {
                        if ( ! empty( $category ) ) {
                            echo '<li>' . wp_kses_post( $category ) . '</li>';
                        }

                        if ( ! is_front_page() && 0 !== $post_id ) {
                            $parents = get_post_ancestors( $post_id );

                            if ( ! empty( $parents ) ) {
                                $id     = $parents ? $parents[ count( $parents ) - 1 ] : $post_id;
                                $parent = get_page( $id );

                                if ( $parent instanceof WP_Post ) {
                                    echo '<li><a href="' . esc_url( get_permalink( $parent ) ) . '">' . get_sanitized_truncated_title( $parent->ID, 0 ) . '</a></li>';
                                }
                            }

                            echo '<li class="active">' . get_sanitized_truncated_title( $post_id, 0 ) . '</li>';
                        }
                    }
                    ?>
                </ol>
            <?php } ?>
        </div>
        <?php

        return ob_get_clean();
    }
}

if ( ! function_exists( 'wprentals_render_related_posts' ) ) {
    /**
     * Render the related posts section for a given post.
     *
     * @param int $post_id Post identifier.
     *
     * @return string
     */
    function wprentals_render_related_posts( $post_id ,$title='') {
        $post_id = intval( $post_id );

        if ( $post_id <= 0 ) {
            return '';
        }

        $post_object = get_post( $post_id );

        if ( ! $post_object instanceof WP_Post ) {
            return '';
        }

        $tags = wp_get_post_tags( $post_id );

        if ( empty( $tags ) ) {
            return '';
        }

        $first_tag = $tags[0]->term_id;

        if ( empty( $first_tag ) ) {
            return '';
        }

        global $wpestate_options;
        global $unit_class;
        global $wpestate_row_number_col;

        $unit_class_exists             = array_key_exists( 'unit_class', $GLOBALS );
        $wpestate_row_number_col_exists = array_key_exists( 'wpestate_row_number_col', $GLOBALS );
        $unit_class_previous           = $unit_class_exists ? $unit_class : null;
        $row_number_previous           = $wpestate_row_number_col_exists ? $wpestate_row_number_col : null;

        $unit_class             = 'col-md-6';
        $wpestate_row_number_col = 6;

        if ( isset( $wpestate_options['content_class'] ) && 'col-md-12' === $wpestate_options['content_class'] ) {
            $unit_class             = 'col-md-4';
            $wpestate_row_number_col = 4;
        }

        $query_arguments = array(
            'tag__in'       => array( $first_tag ),
            'post__not_in'  => array( $post_id ),
            'posts_per_page'=> 2,
            'meta_query'    => array(
                array(
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS',
                ),
            ),
        );

        $related_query = new WP_Query( $query_arguments );

        $output = '';

        if ( $related_query->have_posts() ) {
            ob_start();
            ?>
            <div class="related_posts blog_list_wrapper row">
                <h2><?php 
                if($title==''){
                    esc_html_e( 'Related Posts', 'wprentals' ); 
                }else{
                    print esc_html($title);
                }
                ?></h2>
                
                
                <?php
                while ( $related_query->have_posts() ) {
                    $related_query->the_post();

                    if ( has_post_thumbnail() ) {
                        $template_path = locate_template( 'templates/blog-unit/blog_unit.php' );

                        if ( ! empty( $template_path ) ) {
                            include $template_path;
                        }
                    }
                }
                ?>
            </div>
            <?php

            $output = ob_get_clean();
        }

        wp_reset_postdata();

        if ( $unit_class_exists ) {
            $unit_class = $unit_class_previous;
        } else {
            unset( $GLOBALS['unit_class'] );
        }

        if ( $wpestate_row_number_col_exists ) {
            $wpestate_row_number_col = $row_number_previous;
        } else {
            unset( $GLOBALS['wpestate_row_number_col'] );
        }

        return $output;
    }
}
