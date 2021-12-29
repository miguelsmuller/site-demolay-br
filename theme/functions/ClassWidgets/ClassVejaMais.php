<?php
/*
Name: CUOP
Description: Controle único das opções do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

class ClassVejaMais extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array( 'description' => 'Mostra uma imagem na barra lateral com padrões definidos' );
        $control_ops = array();
        
        $this->WP_Widget( 'widgetVejaMais', '- Widget Veja Mais', $widget_ops, $control_ops );
    }
    
    function widget( $args, $instance )
    {
        extract( $args );

        echo $before_widget;

        $title = apply_filters('widget_title', $instance['title'] );
        if ( $title ) { echo $before_title . $title . $after_title; }
       
        $tags = isset( $instance['tags'] ) ? $instance['tags'] : false;
        $tags_quant = $instance['tags_quant'];
        $categorias = isset( $instance['categorias'] ) ? $instance['categorias'] : false;
        $periodo = isset( $instance['periodo'] ) ? $instance['periodo'] : false;
        
        if ($tags || $categorias || $periodo){
            echo '<div class="well widget-conteudo widget-tabs">';
                echo '<ul id="myTab" class="nav nav-tabs">';
                    if ($tags){ echo '<li class="active"><a href="#tags" data-toggle="tab">TAGs</a></li>'; }
                    if ($categorias){ echo '<li><a href="#categorias" data-toggle="tab">Categorias</a></li>'; }
                    if ($periodo){ echo '<li><a href="#periodo" data-toggle="tab">Periodo</a></li>'; }
                echo '</ul>';
            
                echo '<div id="myTabContent" class="tab-content">';
                    if ($tags){ 
                        echo '<div class="tab-pane fade active in widget-tagcloud" id="tags">';
                        $args = array(
                            'number'    => $tags_quant,
                            'orderby'   => 'count', 
                            'order'     => 'DESC'
                        );
                        wp_tag_cloud($args);
                        echo '</div>';
                    }
                    
                    if ($categorias){ 
                        echo '<div class="tab-pane fade widget-list" id="categorias">';
                        $args = array(
                            'type'          => 'post',
                            'parent'      => 0,
                            'hierarchical'  => 1,
                            'orderby'       => 'slug',
                            'order'         => 'ASC',
                        );
                        $categories = get_categories($args);
                        //echo ('<pre>'); print_r($categories); echo ('</pre>');
                        echo '<ul>';
                            foreach($categories as $item){
                                echo '<li class="item"><a href="'. get_category_link( $item->term_id ) .'">'. $item->name .'<span class="badge badge-info pull-right">'. $item->count .'</span></a></li>';  
                            }
                        echo '</ul>';
                        echo '</div>';
                    }
                    
                    if ($periodo){ 
                        echo '<div class="tab-pane fade widget-list" id="periodo">';
                        wp_get_archives( array( 'type' => 'monthly', 'limit' => 12 ) );
                        echo '</div>';
                    }
                echo '</div>';
                
            echo '</div>';
        }
            
        
        /* IMPRIMI O RODAPÉ DO WIDGET
        =================================================================== */
        echo $after_widget;
        
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['tags_quant'] = $new_instance['tags_quant'] ;
        $instance['tags'] = $new_instance['tags'] ;
        $instance['categorias'] = $new_instance['categorias'] ;
        $instance['periodo'] = $new_instance['periodo'] ;

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => __('Mais Publicações em:'), 'tags_quant' => __('15') );
        $instance = wp_parse_args( (array) $instance, $defaults );
        
        wp_enqueue_media();
        
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Título:'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        
        <p>  
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['tags']), true ); ?> id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" />  
            <label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e('Mostrar Tags', 'example'); ?></label>  
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id( 'tags_quant' ); ?>"><?php _e('Quantidade de Tags:'); ?></label>
            <input id="<?php echo $this->get_field_id( 'tags_quant' ); ?>" name="<?php echo $this->get_field_name( 'tags_quant' ); ?>" value="<?php echo $instance['tags_quant']; ?>" class="widefat" type="number" />
        </p>
        
        <p>  
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['categorias']), true ); ?> id="<?php echo $this->get_field_id( 'categorias' ); ?>" name="<?php echo $this->get_field_name( 'categorias' ); ?>" />  
            <label for="<?php echo $this->get_field_id( 'categorias' ); ?>"><?php _e('Mostrar Categorias', 'example'); ?></label>  
        </p>
        
        <p>  
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['periodo']), true ); ?> id="<?php echo $this->get_field_id( 'periodo' ); ?>" name="<?php echo $this->get_field_name( 'periodo' ); ?>" />  
            <label for="<?php echo $this->get_field_id( 'periodo' ); ?>"><?php _e('Mostrar Período', 'example'); ?></label>  
        </p>

  
    <?php
    }
}

?>