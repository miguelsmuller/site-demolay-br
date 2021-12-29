<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<!-- HEADER PAGE -->
<section id="image-page">
    <?php
    $image = get_post_thumbnail_id();
    $image = wp_get_attachment_url( $image,'full');
    $image = aq_resize( $image, 645, 600, true, true, true );
    ?>
    <img src="<?php echo $image ?>" alt="<?php bloginfo('name') ?>" class="article-thumbnails">
</section>

<!-- MAIN CONTENT AREA -->
<main id="container-page" role="main">
    <div class="row">

        <!-- LEFT CONTENT AREA -->
        <div class="col-md-8">

            <!-- ARTICLE CONTENT -->
            <article class="panel panel-purple">
                <div class="panel-body">
                    <header class="article-info">
                        <h1><?php the_title(); ?></h1>
                        <ul>
                            <li>
                                <span class="icon-calendar"></span> Publicado a <?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' atrás'; ?>
                            </li>
                            <li>
                                <span class="icon-books"></span> <?php the_category(', '); ?>
                            </li>
                            <li>
                                <span class="icon-comment"></span> <a href="<?php comments_link(); ?>" class="easy"><?php comments_number(); ?></a>
                            </li>
                        </ul>
                    </header>
                    <div class="article-social">
                        <ul class="list-inline">
                            <li><a id="like_facebook" href="<?php the_permalink(); ?>"><span class="icon-favorite"></span></a></li>
                            <li><a id="share_facebook" href="<?php the_permalink(); ?>"><span class="icon-facebook"></span></a></li>
                            <li><a id="share_twitter" href="http://twitter.com/share?text=<?php the_title(); ?>"><span class="icon-twitter"></span></a></li>
                            <li><a id="share_plus" href="https://plus.google.com/share?url=<?php the_permalink(); ?>"><span class="icon-google-plus"></span></a></li>
                            <li><a id="share_whatsapp" href="whatsapp://send?text=<?php the_title(); ?> - <?php the_permalink(); ?>" data-action="share/whatsapp/share"><span class="icon-whatsapp"></span></a></li>
                        </ul>
                    </div>
                    <div class="article-content">
                        <?php the_content(); ?>
                    </div>
                    <footer class="article-footer">

                    </footer>
                </div>
                <?php
                if ( has_tag() ){
                ?>
                <div class="panel-footer"><?php the_tags('Artigo referenciado como: ', ', '); ?></div>
                <?php
                }
                ?>
            </article>

            <!-- RELATED CONTENT ARTICLES -->
            <section id="post-related">
                <div class="row">
                    <ol class="list-post list-post-column">
                        <li>
                            <article id="post-<?php the_ID(); ?>" class="article article-y">
                                <a href="<?php the_permalink() ?>">
                                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/slide-1.jpg" alt="" class="article-thumbnails">
                                </a>
                                <header class="article-entry">
                                    <a href="<?php the_permalink() ?>">
                                        <h2 class="article-title">
                                            Aliquam hendrerit accumsan eros ut dictum nulla eget pulvinar tortor.
                                        </h2>
                                    </a>
                                </header>
                            </article>
                        </li>

                        <li>
                            <article id="post-<?php the_ID(); ?>" class="article article-y">
                                <a href="<?php the_permalink() ?>">
                                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/slide-2.jpg" alt="" class="article-thumbnails">
                                </a>
                                <header class="article-entry">
                                    <a href="<?php the_permalink() ?>">
                                        <h2 class="article-title">
                                            Nam posuere fermentum gravida hendrerit accumsan.
                                        </h2>
                                    </a>
                                </header>
                            </article>
                        </li>

                        <li>
                            <article id="post-<?php the_ID(); ?>" class="article article-y">
                                <a href="<?php the_permalink() ?>">
                                    <img src="<?php bloginfo('template_directory'); ?>/assets/images/slide-3.jpg" alt="" class="article-thumbnails">
                                </a>
                                <header class="article-entry">
                                    <a href="<?php the_permalink() ?>">
                                        <h2 class="article-title">
                                            Ut accumsan sem vitae metus fermentum consequat.
                                        </h2>
                                    </a>
                                </header>
                            </article>
                        </li>
                    </ol>
                </div>
            </section>

            <!-- COMMENT ARTICLE -->
            <?php
                if ( comments_open() || '0' != get_comments_number() )
                    comments_template();
            ?>
        </div>

        <!-- RIGHT CONTENT AREA -->
        <aside class="col-md-4">
            <div id="affix-sidebar">
                <div class="panel panel-purple panel-tabs">
                    <div class="panel-heading">Mais Publicações em:</div>
                    <div class="panel-body">
                        <ul id="myTab" class="nav nav-pills nav-justified nav-widget">
                            <li class="active"><a href="#tags" data-toggle="tab">Referências</a></li>
                            <li class=""><a href="#categorias" data-toggle="tab">Categorias</a></li>
                            <li class=""><a href="#periodo" data-toggle="tab">Periodo</a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade widget-tagcloud  active in" id="tags">
                                <a href="#" class="tag-link-3" style="font-size: 11pt;">Dia das Mães</a>
                                <a href="#" class="tag-link-4" style="font-size: 11pt;">Eleições 2014</a>
                                <a href="#" class="tag-link-5" style="font-size: 11pt;">Gabinete Juvenil</a>
                                <a href="#" class="tag-link-6" style="font-size: 11pt;">Liderança Adult</a>
                                <a href="#" class="tag-link-8" style="font-size: 11pt;">Filantropia</a>
                                <a href="#" class="tag-link-6" style="font-size: 11pt;">Doações</a>
                                <a href="#" class="tag-link-8" style="font-size: 11pt;">Solidariedade</a>
                                <a href="#" class="tag-link-7" style="font-size: 11pt;">Quisque</a>
                                <a href="#" class="tag-link-3" style="font-size: 11pt;">Phasellus</a>
                                <a href="#" class="tag-link-4" style="font-size: 11pt;">Proin</a>
                            </div>
                            <div class="tab-pane fade widget-list" id="categorias">
                                <ul class="menu">
                                    <li class="item">
                                        <a href="#"><span class="item-desc">Supremo Conselho</span></a>
                                        <a href="#"><span class="item-desc">Grandes Capítulos</span></a>
                                        <a href="#"><span class="item-desc">Gabinete da Liderança Juvenil</span></a>
                                        <a href="#"><span class="item-desc">Alumni Brasil</span></a>
                                        <a href="#"><span class="item-desc">Comissões Nacionais</span></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-pane fade widget-list" id="periodo">
                                <ul class="menu">
                                    <li><a href="#">julho 2014</a></li>
                                    <li><a href="#">junho 2014</a></li>
                                    <li><a href="#">maio 2014</a></li>
                                    <li><a href="#">abril 2014</a></li>
                                    <li><a href="#">março 2014</a></li>
                                    <li><a href="#">fevereiro 2014</a></li>
                                    <li><a href="#">janeiro 2014</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-purple panel-widget">
                    <div class="panel-heading">Menu de widget</div>
                    <div class="panel-body">
                        <div class="">
                            <ul id="menu-menu-widget" class="menu">
                                <li id="menu-item-157" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-157">
                                    <a href="#">O que é a Ordem DeMolay ?</a>
                                </li>
                                <li id="menu-item-158" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-158">
                                    <a href="#">O Nome “Ordem DeMolay”</a>
                                </li>
                                <li id="menu-item-159" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-159">
                                    <a href="#">Convênios DeMolay Service</a>
                                </li>
                                <li id="menu-item-160" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-160">
                                    <a href="#">Diretoria Executiva</a>
                                </li>
                                <li id="menu-item-161" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-161">
                                    <a href="#">Gabinente Juvenil Estadual</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </aside>
    </div>
</main>
<?php endwhile; ?>
<?php get_footer(); ?>