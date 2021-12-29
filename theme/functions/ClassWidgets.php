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
include 'ClassWidgets/ClassFacebook.php';
include 'ClassWidgets/ClassAniversariantes.php';
include 'ClassWidgets/ClassConsultas.php';
include 'ClassWidgets/ClassVejaMais.php';
include 'ClassWidgets/ClassPagesDestaques.php';

class ClassWidgets
{
	/* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
	public function __construct(){
		add_action('widgets_init',
            array( &$this, 'widgets_init' )
        );
	}

	function widgets_init()
	{
		register_widget( 'ClassFacebook' );
		register_widget( 'ClassAniversariantes' );
		register_widget( 'ClassConsultas' );
        register_widget( 'ClassVejaMais' );
        register_widget( 'ClassPagesDestaques' );
	}
}
$ClassWidgets = new ClassWidgets();

