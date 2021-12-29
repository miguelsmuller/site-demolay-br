<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <a href="<?php the_permalink() ?>" alt="<?php the_title(); ?>" >
        <?php
        $image = get_post_thumbnail_id();
        $image = wp_get_attachment_url( $image,'full');
        $image = aq_resize( $image, 645, 600, true, true, true );
        ?>
        <!-- 396 x 368 -->
        <article class="item" style="background-image: url(<?php echo $image ?>);">
            <h1><?php the_title(); ?></h1>
        </article>

        <!-- <article class="item">
            <img src="<?php echo $image ?>">
            <h1><?php the_title(); ?></h1>
        </article> -->
    </a>
<?php endwhile; else : ?>

<?php endif; ?>
