<?php
$loop = new WP_Query(array(
    'post_type' => 'destaque', 
    'posts_per_page' => 6, 
    'orderby'=> 'date', 
    'order'=> 'ASC'
)); 
?>
<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

<?php
$postOptions = get_post_custom( $post->ID );       
$url         = isset( $postOptions['url'] ) ? $postOptions['url'][0] : '';
$new_window  = isset( $postOptions['new_window']) ? esc_attr( $postOptions['new_window'][0] ) : FALSE;
$new_window  = $new_window == 'on' ? ' target="_blank"' : '';
$inativar    = isset( $postOptions['inativar'] ) ? esc_attr( $postOptions['inativar'][0] ) : FALSE;

if ($inativar != 'on'){   
    $popovers = '';
    if (get_the_excerpt() != ''){
        $popovers = 'data-toggle="popover" title="'. get_the_title() .'" data-content="'. get_the_excerpt() .'" data-original-title="A Title"';
    }
?>
    <div class="span2">
        <div <?php echo($popovers); ?>>
            <a href="<?php echo($url); ?>" <?php echo($new_window);  ?>>
            <?php
                $attr = array(
                    'class' => "change img-rounded img-polaroid item",  
                );
                echo the_post_thumbnail('medium',$attr); 
            ?>
            </a>
        </div>
    </div>
<?php } ?>
<?php endwhile; ?>
<?php wp_reset_query(); ?>