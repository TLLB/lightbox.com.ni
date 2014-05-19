<?php
    global $current_gallery;

    if ( post_password_required() ) {
        $is_password_protected = true;
    }else{
        $is_password_protected = false;
    }
    wp_localize_script( 'functions', 'enb_Galleria', array(
        'gallery_enb'          => true,
        'password_protected'   => $is_password_protected
        )
    );

    
    if(isset($current_gallery)){ /*this is available when a gallery is used in a template, for exanple when you want to show on hte front page a gallery*/
        global $wp_query;

        $wp_query = new WP_Query( array('p' => $current_gallery, 'post_type' => 'gallery' ) );

        $post_id = $post -> ID;
    }
    while( have_posts() ){ 
        the_post();
?>
        
        <?php
            } /*EOF while( have_posts () ) */
        ?>
<?php 
/*if the gallery is mosaic*/
if ( get_posts_gallery_type($post -> ID) == 'mosaic' ) { ?>
    <div class="row" >
        <div class="twelve columns">

            <?php if(options::logic( 'likes' , 'enb_likes' )  ){ ?> 
                <div class="single-like-container">
                    <?php like::content($post->ID,$return = false, $show_icon = true, $show_label = false);  ?>
                </div>
            <?php } ?>
            
            <h1 class="post-title">
                <?php the_title(); ?>
            </h1>

            <?php  
                        if(get_post_type($post -> ID) == 'post'){
                            $cat_tax = 'category';    
                        } elseif(get_post_type( $post -> ID) == 'gallery') {
                            $cat_tax = 'gallery-category';   
                        } elseif(get_post_type( $post -> ID) == 'page') {
                            $cat_tax = '';
                        }
                        $the_categories = post::get_post_categories($post->ID, $only_first_cat = false, $taxonomy = $cat_tax, $margin_elem_start = ' <li>', $margin_elem_end = '</li>', $delimiter = ''); 
                    ?> 

            <?php 
                   
                echo '    <div class="meta-details">';
                include_once('entry-meta.php'); 
                echo '</div>';
            ?>


     
            <?php if (!$is_password_protected) { ?>
                <div class="content">    
                    <?php the_content(); ?>
                </div>
        </div>
    </div>

    <?php }


} ?>


    <div class="<?php if((options::logic( 'blog_post' , 'hide_gallery_info' ) && meta::logic( $post , 'gallerytype' , 'hide_gallery_info' )) || meta::logic( $post , 'gallerytype' , 'hide_gallery_info' )){ echo 'no-gallery-sidebar'; } ?>">

        <?php if(!options::logic( 'blog_post' , 'hide_gallery_info' ) && !meta::logic( $post , 'gallerytype' , 'hide_gallery_info' ) && !post_password_required() && get_posts_gallery_type($post -> ID) != 'mosaic'){ ?> 
            <div class="gallery-info">
                <h1 class="post-title">
                    <?php the_title();?>
                </h1>

                <?php if(options::logic( 'likes' , 'enb_likes' )){ ?> 
                <div class="single-like-container">
                    <?php like::content($post->ID,$return = false, $show_icon = true, $show_label = false);  ?>
                </div>
                <?php } ?>

                <?php  
                    if(get_post_type($post -> ID) == 'post'){
                        $cat_tax = 'category';    
                    } elseif(get_post_type( $post -> ID) == 'gallery') {
                        $cat_tax = 'gallery-category';   
                    } elseif(get_post_type( $post -> ID) == 'page') {
                        $cat_tax = '';
                    }
                    $the_categories = post::get_post_categories($post->ID, $only_first_cat = false, $taxonomy = $cat_tax, $margin_elem_start = ' <li>', $margin_elem_end = '</li>', $delimiter = ''); 
                ?> 
                <?php
                    echo '<div class="meta-details">';
                    include_once('entry-meta.php'); 
                    echo '</div>';
                ?>

                <div class="content">    
                    <?php the_content(); ?>
                </div> 

                <?php  if(options::logic( 'blog_post' , 'show_next_prev' )){ ?>
                <nav class="hotkeys-meta">
                    <?php $portfolio_first_categ = post::get_post_categories($post->ID, $only_first_cat = true, $taxonomy = 'gallery-category', $margin_elem_start = '', $margin_elem_end = '', $delimiter = '', $a_class = 'icon-root', $show_cat_name = false);
                    ?>
                    <?php
                        $ppost = get_previous_post();
                        $npost = get_next_post();
                        if( !empty( $ppost ) ){
                            echo '<span><a class="icon-prev" href="' . get_permalink( $ppost -> ID ) . '" title="'.$ppost -> post_title.'"></a></span>';
                        } 
                        if(strlen(trim($portfolio_first_categ) )){
                            echo '<span>'.$portfolio_first_categ.'</span>';
                        }
                            
                        if( !empty( $npost ) ){
                            echo '<span><a class="icon-next" href="' . get_permalink( $npost -> ID ) . '" title="'.$npost -> post_title.'"></a></span>';
                        }
                    ?>

                </nav>
                <?php } ?>            
            </div>
            <?php if(options::logic( 'blog_post' , 'show_collapse_btn' )){ ?> 
            <div class="collapse-btn">
                <i class="icon-prev "></i>
                <span><?php _e('Click to collapse', 'cosmotheme'); ?></span>
            </div>  
        <?php } 
            }
        ?> 
        
        <?php
            if ( !post_password_required() ) {
                if( get_posts_gallery_type($post -> ID) == 'sly'){
                    echo post::get_post_img_slideshow( $post -> ID, 'single_gallery' );
                }else if( get_posts_gallery_type($post -> ID) == 'image_flow' ){
                    echo post::get_post_img_flow_slide($post -> ID, $size="single_gallery");
                }else if( get_posts_gallery_type($post -> ID) == 'mosaic' ){
                    echo post::get_post_img_mosaic($post -> ID);
                }else{
                    echo post::get_post_gallery_slide($post -> ID, $size="single_gallery");
                }
                


            }else{
?>
                <div class="entry-header row">
                    <div class="password-center three columns">
                        <i class="icon-password"></i>
                    </div>
                    <div class="nine columns">
                        <h1 class="post-title">
                            <?php the_title(); ?>
                        </h1>

                        <?php if(options::logic( 'likes' , 'enb_likes' )){ ?> 
                        <div class="single-like-container">
                            <?php like::content($post->ID,$return = false, $show_icon = true, $show_label = false);  ?>
                        </div>
                        <?php } ?>

                        <?php  
                            if(get_post_type($post -> ID) == 'post'){
                                $cat_tax = 'category';    
                            } elseif(get_post_type( $post -> ID) == 'gallery') {
                                $cat_tax = 'gallery-category';   
                            } elseif(get_post_type( $post -> ID) == 'page') {
                                $cat_tax = '';
                            }
                            $the_categories = post::get_post_categories($post->ID, $only_first_cat = false, $taxonomy = $cat_tax, $margin_elem_start = ' <li>', $margin_elem_end = '</li>', $delimiter = ''); 
                        ?> 
                        <?php
                            echo '<div class="meta-details">';
                            include_once('entry-meta.php'); 
                            echo '</div>';
                        ?>
                        <div class="content">    
                            <?php the_content(); ?>
                        </div> 

                        <?php  if(options::logic( 'blog_post' , 'show_next_prev' )){ ?>
                        <nav class="hotkeys-meta">
                            <?php $portfolio_first_categ = post::get_post_categories($post->ID, $only_first_cat = true, $taxonomy = 'gallery-category', $margin_elem_start = '', $margin_elem_end = '', $delimiter = '', $a_class = 'icon-root', $show_cat_name = false);
                            ?>
                            <?php
                                $ppost = get_previous_post();
                                $npost = get_next_post();
                                if( !empty( $ppost ) ){
                                    echo '<span><a class="icon-prev" href="' . get_permalink( $ppost -> ID ) . '" title="'.$ppost -> post_title.'"></a></span>';
                                } 
                                if(strlen(trim($portfolio_first_categ) )){
                                    echo '<span>'.$portfolio_first_categ.'</span>';
                                }
                                    
                                if( !empty( $npost ) ){
                                    echo '<span><a class="icon-next" href="' . get_permalink( $npost -> ID ) . '" title="'.$npost -> post_title.'"></a></span>';
                                }
                            ?>

                        </nav>
                        <?php } ?>      
                    </div>
                </div>


                
<?php                
            }
        ?> 

    </div>
            
