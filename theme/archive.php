<?php get_header(); ?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">

                <?php
                    $args = array( 'paged'=> $paged );
                ?>

                <?php
                if (is_date()){
                    echo ('<h2>Notícias Publicadas no período de '. get_the_time('F \d\e\ Y') .'</h2>');
                }elseif (is_category()){
                    $categoria = single_cat_title("", false);
                    echo ('<h2>Notícias publicadas na categoria '. $categoria .'</h2>');
                }elseif (is_tag()){
                    $categoria = single_cat_title("", false);
                    echo ('<h2>Notícias publicadas com a tag '. $categoria .'</h2>');
                }else{
                    echo ('<h2>Notícias Publicadas</h2>');

                    $args['name'] = '';
                    $args['pagename'] = '';
                }
                ?>

                <?php
                global $wp_query;
                $args = array_merge( $wp_query->query_vars, $args );
                //$loop_posts = new WP_Query( $args );
                query_posts( $args );

                global $wp_query;
                $total_results = $wp_query->found_posts;

                ?>

                <div class="list_posts_samll" style="padding-top: 0px !important;">

                    <?php
                    if ( function_exists( 'apiki_paginate_links' ) ){
                        echo '<div class="pagination pull-right">';
                        apiki_paginate_links();
                        echo '</div>';
                    }
                    ?>
                    <?php if (have_posts()) : while (have_posts()) : the_post();  ?>
                    <div class="row-fluid item">
                        <?php
                        if( has_post_thumbnail() ){
                        ?>
                        <div class="span3">
                        <a href="<?php the_permalink() ?>">
                            <?php
                            $attr = array(
                                'class' => "img-rounded img-polaroid item",

                            );

                            echo the_post_thumbnail('thumbnail',$attr);
                            ?>
                        </a>
                        </div>

                        <div class="span9">
                        <?php
                        }else{
                        ?>
                        <div class="span12">
                        <?php
                        }
                        ?>
                            <a href="<?php the_permalink() ?>"><h4><?php echo wp_trim_words( get_the_title(), 12, '' ) ?></h4></a>
                            <?php
                                remove_all_shortcodes('the_excerpt');
                                echo (get_the_excerpt().'<a href="'. get_permalink() .'">[...]</a>');
                            ?>
                        </div>
                    </div>

                    <?php endwhile; else: ?>
                    <?php endif; ?>

                    <?php
                    if ( function_exists( 'apiki_paginate_links' ) ){
                        echo '<div class="pagination last pull-right">';
                        apiki_paginate_links();
                        echo '</div>';
                    }
                    ?>
                </div>

            </div>
            </div>
            </div>
            </div>
        </div>

        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php $args = 'before_widget=<div class="widget">&before_title=<div class="widget-titulo"><h4>&after_title=</h4></div>&after_widget=</div>'; ?>
            <?php $instance = 'title=Mais Publicações em:&tags=true&tags_quant=45&categorias=true&periodo=true'; ?>
            <?php the_widget('ClassVejaMais', $instance, $args); ?>
            <?php dynamic_sidebar('Sidebar Single'); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>