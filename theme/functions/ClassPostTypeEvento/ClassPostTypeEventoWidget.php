<?php
add_action( 'widgets_init', 'widgets_init' );
function widgets_init()
{
    register_widget( 'Widget_eventos' );
}

class Widget_eventos extends WP_Widget
{
    function Widget_eventos()
    {
        $widget_ops = array( 'description' => 'Mostra uma imagem na barra lateral com padrões definidos' );
        $control_ops = array();
        
        $this->WP_Widget( 'widget_eventos', '- Widget Eventos', $widget_ops, $control_ops );
    }
    
    function widget( $args, $instance )
    {
        extract( $args );

        /* PREPARA AS VARIAVEIS
        =================================================================== */
        $title = apply_filters('widget_title', $instance['title'] );
        $quant = $instance['quant'];
        
        /* IMPRIMI O TITULO DO WIDGET
        =================================================================== */
        echo $before_widget;
        if ( $title )
        {
            echo $before_title . $title . $after_title;
        }
        
        /* PARTE DE IMPRESSÃO DO WIDGET
        =================================================================== */
        
        /*echo '<div class="well widget-conteudo widget-eventos scroll a215">';*/
        echo '<div class="well widget-conteudo widget-eventos scroll">';
        ?>
        <div class="widget-list" >
        <ul>
        
            <?php
            $args = array(
                'post_type' => 'evento', 
                'posts_per_page' => $quant, 
                'meta_query' => array(
                    array(
                        'key' => 'dInicio',
                        'value' => time(),
                        'compare' => '>=',
                        ),
                ),
                'orderby' => 'meta_value', 
                'meta_key'=> 'dInicio',
                'order'=> 'ASC'
            );
            $loop = new WP_Query($args);  
            global $post;      
            
            if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
            ?>
            
                <?php           
                $local = array();
                $postOptions = get_post_custom( $post->ID );

                if ( $postOptions['localEvento'][0] != '') {
                    $local[] = $postOptions['localEvento'][0]; }

                if ( $postOptions['cidadeEvento'][0] != '') {
                    $local[] = $postOptions['cidadeEvento'][0]; }

                if ( $postOptions['estadoEvento'][0] != '') {
                    $local[] = $postOptions['estadoEvento'][0]; }


                $d_inicio    = date("d/m/Y",$postOptions['dInicio'][0]);
                $d_inicio    = explode("/", $d_inicio);
                
                $data_inicio = $d_inicio[0];
                $mes_inicio = $d_inicio[1];
                $ano_inicio = $d_inicio[2];

                $mes_inicio = mesExtenso($mes_inicio,'reduzida');          
                ?>
                <li>
                    <a href="<?php the_permalink() ?>">
                    <div class="row-fluid">
                        
                        <div class="span4">
                            <table>
                              <tr>
                                <td class="dia" rowspan="2"><?php echo $data_inicio;?></td>
                                <td class="mes"><?php echo $mes_inicio;?></td>
                              </tr>
                              <tr>
                                <td class="ano"><?php echo $ano_inicio;?></td>
                              </tr>
                            </table>
                        </div> 
                        <div class="span8" style="margin-left: -10px;">
                            <span class="titulo"><?php the_title();?></span><br />
                            <span class="descricao"><?php echo implode(" - ", $local); ?></span>
                        </div>
                    </div>
                    </a>
                </li>
            <?php endwhile; else: ?>
                <li>
                    <span class="nao-localizado">
                    Nenhum evento localizado dentro dos parâmetros.
                    </span>
                </li>
            <?php endif; ?>
         
        </ul>
        </div>
        
        <?php wp_reset_query(); ?>
        <?php               
        echo '</div>';
        
        
            
        
        /* IMPRIMI O RODAPÉ DO WIDGET
        =================================================================== */
        echo $after_widget;
        
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['quant'] = strip_tags( $new_instance['quant'] );

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => '','quant' => '1' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        
        wp_enqueue_media();
        
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Título:'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'quant' ); ?>"><?php _e('Quantidade:'); ?></label>
            <input id="<?php echo $this->get_field_id( 'quant' ); ?>" name="<?php echo $this->get_field_name( 'quant' ); ?>" value="<?php echo $instance['quant']; ?>" class="widefat" type="number" />
        </p>
  
    <?php
    }
}
?>