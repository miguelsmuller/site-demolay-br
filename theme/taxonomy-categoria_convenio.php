<?php get_header(); ?>
<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
<div class="container wrap">    
    <div class="row">
        
        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">
                <h2>DeMolay Service - <?php echo $term->name ?></h2>

                <?php 
                $termchildren = get_term_children( $term->term_id, get_query_var( 'taxonomy' ) );
                if ( count($termchildren) >= 1) {
                    echo str_replace("\r\n", "<br/>", $term->description);
                    $args = array(
                        'taxonomy'          => 'categoria_convenio',
                        'hide_empty'        => false,
                        'title_li'          => '',
                        'child_of'          => $term->term_id
                    );
                    echo '<div class="page-list well span10"><ul>';
                    wp_list_categories( $args );
                    echo '</ul></div>';
                }else{
                    $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                    echo $term->description ;
                
                    $str_com_tags = get_post_type_archive_link('convenio');       
                    $str_sem_tags = preg_replace("/%.+?%\//i", "", $str_com_tags);
                
                    $loop = new WP_Query(array(
                        'post_type' => 'convenio', 
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'categoria_convenio',
                                'field' => 'slug',
                                'terms' => $term->slug,
                                'include_children' => false
                            )
                        ),
                        'posts_per_page' => -1, 
                        'orderby'=> 'title', 
                        'order'=> 'ASC'
                    ));

                ?>
                <div class="row-fluid">
                    <div class="page-list well span10">
                        <ul>
                        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?> 
                                            
                            <li><a href="<?php the_permalink() ?>">
                            <?php  the_title(); ?>
                            </a></li>
                        
                        <?php endwhile; wp_reset_query(); ?>
                        </ul>
                    </div>
                </div>
            
                <p><a href="<?php echo $str_sem_tags; ?>">Clique aqui </a> caso você queira ver convênios de todas as categorias.</p>
                <?php
                }
                ?>

            </div>
            </div>
            </div>
            </div>
        </div>
        
        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php get_template_part( 'template-part/sidebar', 'convenio' ); ?>
        </div>
    </div>  
</div>
<?php get_footer(); ?>