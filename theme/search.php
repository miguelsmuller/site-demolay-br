<?php
/*
Template Name: Search Page
*/
?>
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
                global $query_string;

                $query_args = explode("&", $query_string);
                $search_query = array();

                foreach($query_args as $key => $string) {
                    $query_split = explode("=", $string);
                    $search_query[$query_split[0]] = urldecode($query_split[1]);
                }

                $search_query = array_merge( $search_query, array('posts_per_page' => -1) );
                $search = new WP_Query($search_query);

                global $wp_query;
                $total_results = $wp_query->found_posts;
                ?>

                <h2 style="margin-bottom: 0;">Resultado da busca para "<?php the_search_query(); ?>"</h2>
                <h5 style="padding-bottom: 0;"><?php echo $total_results; ?> itens encontrados</h5>

<div class="list_posts_samll">

<?php if ($search->have_posts()) : while ($search->have_posts()) : $search->the_post();  ?>
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
        <a href="<?php the_permalink() ?>"><h4>[<?php echo ucfirst (get_post_type()); ?>] <?php echo wp_trim_words( get_the_title(), 12, '' ) ?></h4></a>
        <?php
            remove_all_shortcodes('the_excerpt');
            echo ('<a href="'. get_permalink() .'">[...]</a>');
        ?>
    </div>
</div>

<?php endwhile; else: ?>
<?php endif; ?>

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