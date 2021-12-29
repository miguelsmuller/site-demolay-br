<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php get_header(); ?>

<!-- LAST NEWS -->
<section id="news-feed" class="owl-carousel">
    <?php get_template_part( 'loop', 'post-index' ); ?>
</section>

<!-- TRENDING TOPICS -->
<section id="trending">
    <ul id="footer-tags">
        <?php $tags = tags_popular('8', '120'); ?>
        <?php if (!empty($tags)) { ?>
        <?php foreach ((array)$tags as $tag): ?>
            <li>
                <a href="<?php echo get_tag_link ($tag->term_id); ?>" rel="tag" alt="<?php echo $tag->name; ?>" class="label label-gray label-trending">
                    <?php echo $tag->name; ?>
                </a>
            </li>
        <?php endforeach ?>
    <?php } ?>
    </ul>
    <a href="http://www.lojademolay.org.br/">
        <img src="<?php bloginfo('template_directory'); ?>/assets/images/loja-demolay.png" alt="Loja DeMolay - SCODB">
    </a>
</section>

<!-- FEATURES -->
<main id="container-page" role="main">
    <section id="msnry">
        <?php
        $featured = new WP_Query( array(
            'post_type'      => 'featured-picture',
            'posts_per_page' => -1,
        ));
        ?>
        <?php if ( $featured->have_posts() ) : while ( $featured->have_posts() ) : $featured->the_post(); ?>
            <?php
            // THUMBNAIL
            $thumbnail = get_field('thumbnail');
            $thumbnail = wp_get_attachment_url( $thumbnail['id'],'full' );

            // FORMATO
            $formato = get_field('formato');
            $formato = ($formato == '') ? '3' : $formato ;

            // DESTINO
            if (get_field('tipo_destino') == 'interno'){
                $destino = get_field('destino_interno');
                $destino = get_permalink( $destino->ID );
            }else{
                $destino = get_field('destino_externo');
            }
            if ($destino == '') $destino = '#';

            // TARGET
            $target = get_field('target');
            $target = isset($target[0]) ? 'sim' : 'nao';
            $target = $target == 'sim' ? ' target="_blank"' : '';
            ?>

            <div class="msnry-panel msnry-panel-<?php echo $formato; ?>">
                <div class="panel panel-purple">
                    <div class="panel-image">
                        <a href="<?php echo $destino; ?>" <?php echo $target; ?>>
                            <img src="<?php echo $thumbnail; ?>" class="img-responsive" alt="<?php the_title(); ?>">
                        </a>
                    </div>
                </div>
            </div>

        <?php endwhile; else : ?>
        <?php endif; ?>
    </section>
</main>

<!-- INSTAGRAM -->
<section id="instagram">
    <div id="instafeed"></div>
</section>

<?php get_footer(); ?>
