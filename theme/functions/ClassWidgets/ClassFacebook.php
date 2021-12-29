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

class ClassFacebook extends WP_Widget
{
	function __construct()
	{
		$widget_ops		= array( 'description' => 'Mostra uma imagem na barra lateral com padrões definidos' );
		$control_ops	= array();
		
		$this->WP_Widget( 'widgetFacebook', '- Widget Facebook', $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance )
	{
		extract( $args );
		
		echo $before_widget;

		$title = apply_filters('widget_title', $instance['title'] );
		if ( $title ) { echo $before_title . $title . $after_title; }
		
		?>
		
		<div class="fb-like-box" data-href="https://www.facebook.com/scodb" data-width="370" data-height="400" data-show-faces="true" data-stream="false" data-border-color="transparent" data-header="false"></div>
		<?php
		
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
		$defaults = array( 'title' => 'Facebook Oficial' );
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