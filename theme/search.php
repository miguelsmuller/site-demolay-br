<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>
<main class="container" role="main">
    <div class="row">

        <section id="content" class="col-sm-12 col-md-9">

            <form action="<?php bloginfo('url'); ?>" method="get" accept-charset="utf-8" role="search" class="panel panel-default">
                <div class="panel-heading">Opções de Busca avançada</div>
                <div class="panel-body">

                    <div class="col-md-12">
                        <input type="text" name="s" id="search" class="form-control" value="<?php the_search_query(); ?>" placeholder="Critério de pesquisa" />
                    </div>
                    <div class="col-md-12">
                        <?php $query_types = get_query_var('post_type'); ?>

                        <input type="checkbox" name="post_type[]" value="arquivo" <?php if (in_array('arquivo', $query_types)) { echo 'checked="checked"'; } ?> /><label>Arquivo</label>
                        <input type="checkbox" name="post_type[]" value="post" <?php if (in_array('post', $query_types)) { echo 'checked="checked"'; } ?> /><label>Post</label>
                        <input type="checkbox" name="post_type[]" value="page" <?php if (in_array('page', $query_types)) { echo 'checked="checked"'; } ?> /><label>Página</label>
                        <input type="checkbox" name="post_type[]" value="evento" <?php if (in_array('evento', $query_types)) { echo 'checked="checked"'; } ?> /><label>Evento</label>
                    </div>

                </div>
                <div class="panel-footer">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Procurar">
                </div>
            </form>

            <?php
                global $wp_query;
                $total_results = $wp_query->found_posts;
            ?>

            <header>
                <h2>Resultado da busca para "<?php the_search_query(); ?>"</h2>
                <h5><?php echo $total_results; ?> itens encontrados</h5>
            </header>

            <ol class="list-unstyled">
                <?php if (have_posts()) : while (have_posts()) : the_post();  ?>
                    <li>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <header>
                                <h4>[<?php echo ucfirst (get_post_type()); ?>] <?php get_the_title() ?><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
                            </header>

                            <div class="entry-content">
                                <?php the_excerpt(); ?>
                                <p><a class="btn btn-primary" href="<?php the_permalink() ?>">Leia mais</a></p>
                            </div>

                            <footer>
                                <ul class="list-inline list-unstyled">
                                    <li>
                                        <i class="icon-user"></i> <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta( 'nickname' ); ?></a>
                                    </li>
                                    <li>
                                        <i class="icon-calendar"></i> <?php the_time('d/m/Y'); ?>
                                    </li>
                                    <li>
                                        <i class="icon-comment"></i> <a href="<?php comments_link(); ?>"><?php comments_number(); ?></a>
                                    </li>
                                    <li>
                                        <i class="icon-tags"></i> Tags :
                                        <?php
                                        $posttags = get_the_tags();
                                        if ($posttags) :
                                            foreach($posttags as $tag) {
                                                echo '<a href="'.get_tag_link($tag->term_id).'"><span class="label label-primary">'.$tag->name.'</span></a> ';
                                            }
                                        endif;
                                        ?>
                                    </li>
                                </ul>
                            </footer>
                            <hr>

                        </article>
                    </li>
                <?php endwhile; else: ?>
                <?php endif; ?>
            </ol>

            <?php if ( function_exists( 'paginacao' ) ) paginacao(); ?>

        </section>

        <?php get_sidebar(); ?>

    </div>
</main>
<?php get_footer(); ?>
