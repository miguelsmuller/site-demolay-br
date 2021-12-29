<?php
$loop = new WP_Query(array(
    'post_type' => 'slide',
    'posts_per_page' => -1,
    'orderby'=> 'date',
    'order'=> 'DESC'
));
?>

<?php if ( $loop->have_posts() ){ ?>
<div id="myCarousel" class="carousel">

    <div class="carousel-inner">
    <?php
        $attr_item = 'active';
        while ( $loop->have_posts() ) : $loop->the_post();
            $url = get_post_meta($post->ID, "url", true) != '' ? get_post_meta($post->ID, "url", true) : '#';
            $new_window = get_post_meta($post->ID, "new_window", true) == 'on' ? ' target="_blank"' : '';

            if (get_post_meta($post->ID, "inativar", true) == 'off'){
    ?>

        <div class="<?php echo($attr_item); ?> item">
        <?php
            echo '<a href="'. $url .'" '. $new_window .'>';
            $attr = array(
                'class' => "change",
            );
            echo the_post_thumbnail('carousel-thumbnails', $attr);
            echo '</a>';
            $attr_item = '';
            if (get_the_content() != ''){
            ?>
                <div class="carousel-caption visible-desktop">
                    <h4><?php the_title();?></h4>
                    <?php the_content();?>
                </div>
            <?php } ?>
        </div>

    <?php } endwhile; ?>
    </div>

    <a class="carousel-control left" href="#myCarousel" data-slide="prev"></a>
    <a class="carousel-control right" href="#myCarousel" data-slide="next"></a>

</div>
<?php } ?>