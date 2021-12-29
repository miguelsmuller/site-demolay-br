<?php get_header(); ?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">
        <?php
        wp_reset_query();
        if (have_posts()) : while (have_posts()) : the_post();
        ?>

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">

                <h2 class="main-post"><?php the_title(); ?></h2>
                <?php
                $dataPublicacao = get_the_time('d/m/Y');
                $dataAlteração = get_the_modified_time('d/m/Y');
                if ($dataPublicacao != $dataAlteração){
                    $data = 'Publicado em '. $dataPublicacao . ' e alterado em '.$dataAlteração;
                }else{
                    $data = 'Publicado em '. $dataPublicacao;
                }
                the_content();
                ?>
                
            </div>
            </div>
            </div>
            </div>

            <?php if ('open' == $post->comment_status) : ?>
            <div class="row-fluid"><div class="span12">
                <div class="widget">
                    <div class="widget-titulo">
                        <h4>Comentários</h4>
                    </div>
                    <div class="face-box-large">
                        <div class="fb-comments" data-href="<?php the_permalink(); ?> " data-num-posts="3" data-width="770"></div>
                    </div>
                    <div class="face-box-normal">
                        <div class="fb-comments" data-href="<?php the_permalink(); ?> " data-num-posts="3" data-width="620"></div>
                    </div>
                </div>
            </div></div>
            <?php endif;?>


        <?php endwhile; else: ?>
        <?php endif; ?>
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