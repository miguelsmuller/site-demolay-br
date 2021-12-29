<?php get_header(); ?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">
        <?php
        wp_reset_query();
        if (have_posts()) : while (have_posts()) : the_post();

        $postOptions = get_post_custom( $post->ID );
        $mostrarThumbSingle = isset( $postOptions['mostrarThumbSingle'] ) ? esc_attr( $postOptions['mostrarThumbSingle'][0] ) : '';
        ?>

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">

                <?php
                if( has_post_thumbnail() && $mostrarThumbSingle != TRUE ){
                    $attr = array(
                        'class' => "img-rounded img-polaroid top-thumbnails"
                    );

                    echo the_post_thumbnail('noticia-topo',$attr);
                }
                ?>

                <h2 class="main-post"><?php the_title(); ?></h2>
                <?php
                $dataPublicacao = get_the_time('d/m/Y');
                $dataAlteração = get_the_modified_time('d/m/Y');
                if ($dataPublicacao != $dataAlteração){
                    $data = 'Publicado em '. $dataPublicacao . ' e alterado em '.$dataAlteração;
                }else{
                    $data = 'Publicado em '. $dataPublicacao;
                }
                ?>
                <span class="resume-post">
                <?php echo($data); ?> | Categorias: <?php the_category(', '); ?> | Publicado por: <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta( 'nickname' ); ?></a>
                </span>
                <!-- PLUGIN FACEBOOK -->
                <div class="fb-like" data-href="<?php the_permalink(); ?>" data-send="true" data-width="620" data-show-faces="false" data-font="tahoma"></div>

                <?php
                the_content();
                the_tags('<div class="tags">Tags: ', ' ', '</div>');
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

            <?php
$tags = wp_get_post_tags($post->ID);
if ($tags) {

    $tag_ids = array();
    foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;

    $args=array(
        'tag__in' => $tag_ids,
        'post__not_in' => array($post->ID),
        'showposts'=>4 // Number of related posts that will be shown.
    );

    $my_query = new wp_query($args);
    if( $my_query->have_posts() ) {
    ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget">
                <div class="widget-titulo">
                    <h4>Publicações Recomendadas</h4>
                </div>
                <div class="well widget-conteudo widget-relacionadas">
                    <div class="row-fluid">
                    <?php
                    echo '';
                    while ($my_query->have_posts()) {
                        $my_query->the_post();
                    ?>
                        <div class="span3 item">
                            <a href="<?php the_permalink() ?>" rel="bookmark">
                            <?php
                            $attr = array(
                                'class' => "img-rounded img-polaroid item",
                            );
                            echo the_post_thumbnail('thumbnail',$attr);
                            ?>
                            </a>
                            <p class="title"><?php the_title(); ?></p>
                        </div>
                    <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
<?php } ?>


        <?php endwhile; else: ?>
        <?php endif; ?>
        </div>

        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php $args = 'before_widget=<div class="widget">&before_title=<div class="widget-titulo"><h4>&after_title=</h4></div>&after_widget=</div>'; ?>
            <?php $instance = 'title=Mais Publicações em:&tags=true&tags_quant=25&categorias=true&periodo=true'; ?>
            <?php the_widget('ClassVejaMais', $instance, $args); ?>
            <?php dynamic_sidebar('Sidebar Single'); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>