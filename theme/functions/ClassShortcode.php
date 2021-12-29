<?php
/*
Name: ClassShortcode.php
Description: Controle único das opções do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

class ClassShortcode
{

	/* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
	public function __construct()
	{
		add_shortcode( 'label',
			array( &$this, 'label' )
		);
		add_shortcode( 'label-success',
			array( &$this, 'label_success' )
		);
		add_shortcode( 'label-warning',
			array( &$this, 'label_warning' )
		);
		add_shortcode( 'label-important',
			array( &$this, 'label_important' )
		);
		add_shortcode( 'label-info',
			array( &$this, 'label_info' )
		);
		add_shortcode( 'label-inverse',
			array( &$this, 'label_inverse' )
		);
	}

	/* #############################################################
    # SEM DESCRIÇÃO NO MOMENTO
    ############################################################# */
	function label( $atts, $content="" ) {
	     return "<span class='label'>$content</span>";
	}
	function label_success( $atts, $content="" ) {
	     return "<span class='label label-success'>$content</span>";
	}
	function label_warning( $atts, $content="" ) {
	     return "<span class='label label-warning'>$content</span>";
	}
	function label_important( $atts, $content="" ) {
	     return "<span class='label label-important'>$content</span>";
	}
	function label_info( $atts, $content="" ) {
	     return "<span class='label label-info'>$content</span>";
	}
	function label_inverse( $atts, $content="" ) {
	     return "<span class='label label-inverse'>$content</span>";
	}
}
$ClassShortcode = new ClassShortcode();
?>
