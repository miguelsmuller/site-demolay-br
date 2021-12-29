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

class ClassConsultas extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array( 'description' => 'Mostra uma imagem na barra lateral com padrões definidos' );
        $control_ops = array();
        
        $this->WP_Widget( 'widgetConsultas', '- Widget Cosultas', $widget_ops, $control_ops );

        add_action( 'wp_print_scripts',
            array( &$this, 'wp_print_scripts' )
        );

    }

    function wp_print_scripts(){
        wp_enqueue_script('ClassConsultas', get_template_directory_uri() .'/functions/ClassWidgets/ClassConsultas.js', false, '', true);
    }
    
    function widget( $args, $instance )
    {
        extract( $args );

        echo $before_widget;

        $title = apply_filters('widget_title', $instance['title'] );
        if ( $title ) { echo $before_title . $title . $after_title; }

        $consulta_cid = isset( $instance['consulta_cid'] ) ? $instance['consulta_cid'] : false;
        $consulta_certificados = isset( $instance['consulta_certificados'] ) ? $instance['consulta_certificados'] : false;
    
        echo '<div class="well widget-conteudo widget-accordion">';
        ?>
        
        <div class="accordion" id="accordion2">
        
            <?php if ($consulta_cid == true){?>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">Consultar DeMolay</a>
                </div>
                <div id="collapseOne" class="accordion-body collapse in">
                    <div class="accordion-inner">
                        <form id="form_verificar_regularidade" class="form-inline">
                            <div class="control-group">
                                <div class="controls">
                                    <input type="text" id="txtCID" class="input-large" placeholder="Número da CID">
                                    <button id="verificar_regularidade"  type="submit" class="btn btn-green pull-right">Verificar CID</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
            
            <?php }?>
                        
            <?php if ($consulta_certificados == true){?>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">Consultar Certificado do PACC</a>
                </div>
                <div id="collapseTwo" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <form id="form_verificar_certificado" class="form-inline" method="post" action="http://www.demolaybrasil.com.br/moodle/blocks/verify_certificate/index.php" target="_blank">
                            <input type="text" id="certnumber" name="certnumber" class="input-medium" placeholder="Número do Certificado">
                            <button type="submit" id="verificar_certificado" class="btn btn-default pull-right">Verificar Certificado</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php }?>
          
        </div>
        
        <?php               
        echo '</div>';

        echo $after_widget;
        
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['consulta_cid'] = $new_instance['consulta_cid'] ;
        $instance['consulta_certificados'] = $new_instance['consulta_certificados'] ;

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => 'Consultas' );
        $instance = wp_parse_args( (array) $instance, $defaults );
        
        wp_enqueue_media();
        
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>
        
        <p>  
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['consulta_cid']), true ); ?> id="<?php echo $this->get_field_id( 'consulta_cid' ); ?>" name="<?php echo $this->get_field_name( 'consulta_cid' ); ?>" />  
            <label for="<?php echo $this->get_field_id( 'consulta_cid' ); ?>">Consulta CID</label>  
        </p>
        
        <p>  
            <input class="checkbox" type="checkbox" <?php checked( isset( $instance['consulta_certificados']), true ); ?> id="<?php echo $this->get_field_id( 'consulta_certificados' ); ?>" name="<?php echo $this->get_field_name( 'consulta_certificados' ); ?>" />  
            <label for="<?php echo $this->get_field_id( 'consulta_certificados' ); ?>">Consulta Certificados</label>  
        </p>
  
    <?php
    }
}

?>