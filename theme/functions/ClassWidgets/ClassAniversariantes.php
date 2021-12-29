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

class ClassAniversariantes extends WP_Widget
{
    function __construct()
    {
        $widget_ops  = array( 'description' => 'Mostra uma imagem na barra lateral com padrões definidos' );
        $control_ops = array();

        $this->WP_Widget( 'widget-aniversariante', '- Widget Aniversariantes', $widget_ops, $control_ops );
    }

    function widget( $args, $instance )
    {
        extract( $args );

        echo $before_widget;

        $title = apply_filters('widget_title', $instance['title'] );
        if ( $title ) { echo $before_title . $title . $after_title; }

        echo '<div class="well widget-conteudo ">';

            $dom_object = new DOMDocument();
            if (@$dom_object->load(urlWebService."/associados_aniversariando") === TRUE)
            {
                echo '<div class="widget-aniversariante scroll a215">';
                    echo '<ul>';

                    $item = $dom_object->getElementsByTagName("item");

                    foreach( $item as $value )
                    {
                        $no_nome= $value->getElementsByTagName("NOME");
                        $nome  = ucwords(convertem($no_nome->item(0)->nodeValue, 0));

                        $no_nr_capitulo = $value->getElementsByTagName("nr_cap");
                        $nr_capitulo  =$no_nr_capitulo->item(0)->nodeValue;

                        $no_nome_capitulo = $value->getElementsByTagName("nm_cap");
                        $nome_capitulo  = ucwords(convertem($no_nome_capitulo->item(0)->nodeValue,0));

                        $no_uf_gce = $value->getElementsByTagName("uf_gce");
                        $uf_gce  = convertem($no_uf_gce->item(0)->nodeValue,1);

                        echo '<li><a href="#">';
                            echo $nome;
                            echo '<br/><small>Capítulo '. $nome_capitulo . ' Nº '. $nr_capitulo . ' - '. $uf_gce .'</small>';
                        echo '</a></li>';
                    }

                    echo '</ul>';
                echo '</div>';
            }
            else
            {
                echo '<h5>Não existe aniversariante nos próximos dias.</h5>';
            }

        echo '</div>';

        echo $after_widget;
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    /* FORMULÁRIO DE ADMINITRAÇÃO DO BACKEND
    =================================================================== */
    function form( $instance ) {

        /* VALORES PADRÕES */
        $defaults = array( 'title' => 'Aniversariantes do Dia' );
        $instance = wp_parse_args( (array) $instance, $defaults );

        wp_enqueue_media();

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
        </p>

    <?php
    }
}

?>