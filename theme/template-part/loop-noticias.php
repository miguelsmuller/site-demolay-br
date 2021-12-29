<?php
global $ClassPostOption;
$quant = $ClassPostOption->getQuantPostsIndex()
?>
<div class="widget" data-intro='Tudo que você precisar você pode achar dentro dessa cadeia de menu que foi organizada para criar uma navegação fácil e rápida' data-step='5'>
    <div class="widget-titulo">
        <h4>Últimas Notícias</h4>
    </div>
    <div class="well widget-conteudo widget-tabs">

        <!-- ABA DE NOTICIAS    ================================================== -->

        <?php
        /*GET ALL CATEGORIES*/
        $args = array(
            'type'          => 'post',
            'hierarchical'  => 0,
            'orderby'       => 'slug',
            'order'         => 'ASC',
            'hide_empty'    => 0,
                    'pad_counts'    => false
        );
        $categories = get_categories($args);

        /*ORGANIZE CATEGORIES*/
        $childs = array();
        foreach($categories as $item)
            $childs[$item->parent][] = $item;

        foreach($categories as $item) if (isset($childs[$item->term_id]))
            $item->childs = $childs[$item->term_id];

        $tree = $childs[0];

        //echo ('<pre>'); print_r ($tree); echo ('</pre>');
        ?>

        <!-- ABA    ================================================== -->
        <?php
        echo '<ul id="myTab" class="nav nav-tabs">';
        $first_tab = 'active';

        foreach($tree as $item){
            $args_li = '';
            $args_a = ' data-toggle="tab"';
            $href_a = '';

            $args_li = 'class="'. $first_tab .'"';
            if (isset($item->childs) && is_array($item->childs)){
                $args_li = 'class="dropdown '. $first_tab .'"';
                $args_a = 'class="dropdown-toggle" data-toggle="dropdown"';
                $href_a = '#';
            }else{
                $href_a = '#'.$item->slug;
            }
            echo '<li '. $args_li .'><a href="'.  $href_a .'"'. $args_a .'>'. $item->name;
            if (isset($item->childs)){
                echo ' <b class="caret"></b></a>';
                make_dropdown($item->childs);
            }else{
                echo '</a>';
            }
            echo '</li>';
            $first_tab = '';
        }

        echo '</ul>';

        function make_dropdown($itens = array()){
            echo '<ul class="dropdown-menu">';
                foreach($itens as $item){
                    echo '<li><a data-toggle="tab" href="#'.  $item->slug .'">'. $item->name .'</a></li>';
                }
            echo '</ul>';
        }
        ?>

        <!-- CONTENT TAB    ================================================== -->
        <div id="myTabContent" class="tab-content list_posts_samll">
            <?php
            $first_pane = 'active in';
            foreach($categories as $item){
            ?>
            <div class="tab-pane fade <?php echo($first_pane); ?>" id="<?php echo($item->slug); ?>">

                <!-- CONTENT    ================================================== -->
                <?php
                    $loop_posts = new WP_Query(array('posts_per_page' => $quant , 'orderby'=> 'date', 'order'=> 'DESC', 'cat'=> $item->term_id));
                ?>
                <?php while ( $loop_posts->have_posts() ) : $loop_posts->the_post(); ?>

                <div class="row-fluid item">
                    <?php
                    if( has_post_thumbnail() ){
                    ?>
                    <div class="span3 hidden-phone">
                    <a href="<?php the_permalink() ?>">
                        <?php
                        $attr = array(
                            'class' => "change img-rounded img-polaroid item",

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

                <?php endwhile; ?>
                <?php wp_reset_query(); ?>

            </div>
            <?php
            $first_pane = '';
            }
            ?>
        </div>

    </div>
    </div>