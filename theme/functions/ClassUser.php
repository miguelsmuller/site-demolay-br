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
class ClassUser
{
	/* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
	public function __construct()
	{
		add_action( 'init', array( &$this, 'init' ));
		add_filter( 'query_vars', array( &$this, 'queryVars' ));
		add_filter( 'template_redirect', array( &$this, 'templateRedirect' ));

		add_filter( 'user_contactmethods', array( &$this, 'userContactMethods' ) ,10,1 );
	}

	/* #############################################################
    # CRIA PÁGINAS DO TIPO URL REWRITE
    ############################################################# */
	function init()
	{
	    add_rewrite_rule( 'usuario_novo', 'index.php?controle_usuario=usuario_novo', 'top' );
	    add_rewrite_rule( 'usuario_salvar', 'index.php?controle_usuario=usuario_salvar', 'top' );
	}
	function queryVars( $vars )
	{
	    $vars[] = 'controle_usuario';
	    return $vars;
	}
	function templateRedirect()
	{
		if ( get_query_var( 'controle_usuario' ) == 'usuario_salvar' ) {
	        add_filter( 'template_include', array( &$this, 'funcPHPantigo1' ) );
	    }

	    if ( get_query_var( 'controle_usuario' ) == 'usuario_novo' ) {
	        add_filter( 'template_include', array( &$this, 'funcPHPantigo2' ));
	    }
	}
	function funcPHPantigo1()
	{
		return dirname(__FILE__) . '/ClassUser/usuario_salvar.php';
	}
	function funcPHPantigo2()
	{
		$arquivo = get_template_directory() . '/usuario_novo.php';
		if (file_exists($arquivo) == false) {
			$arquivo = dirname(__FILE__) . '/ClassUser/usuario_novo.php';
		}
		return $arquivo;
	}

	/* #############################################################
    # ADICIONA E TIRA CAMPOS DO REGISTRO DE USUÁRIOS
    ############################################################# */
	function userContactMethods( $atts, $content="" )
	{
		$contactmethods['facebook']	= 'Facebook';
		$contactmethods['twitter']	= 'Twitter';
		//unset($contactmethods['last_name']);
	    return $contactmethods;
	}

	function getLevelUsuario()
	{
		$grauUser = array();
		if( !is_user_logged_in() ) {
			return $grauUser;
		} else {
			$username = get_the_author_meta( 'user_login', get_current_user_id() );
			$grauUser = array();

            if ( !is_numeric($username)) {
                if (is_super_admin()) {
                    $grauUser['liberado'] = '';
                    $grauUser['iniciatico'] = '';
                    $grauUser['demolay'] = '';
                    $grauUser['cavaleiro'] = '';
                    $grauUser['capela'] = '';
                    $grauUser['salem'] = '';
                    $grauUser['extemplario'] = '';
                    $grauUser['triade'] = '';
                    $grauUser['ebano'] = '';
                    $grauUser['anon'] = '';
                    $grauUser['cadencia'] = '';
                    $grauUser['comendador'] = '';
                    $grauUser['grandecruz'] = '';
                    $grauUser['mantoprateado'] = '';
                    $grauUser['chevalier'] = '';
                    $grauUser['Macom'] = '';
                }
            }else{

                $grauUser['liberado'] = '';

    			$dom_object = new DOMDocument();
                $dom_object->load(urlWebService.'/associado/cid/'.$username.'/detalhado/sim');

    			$itemdm_dt_iniciacao = $dom_object->getElementsByTagName("dm_dt_iniciacao");
                $dtIniciatico = $itemdm_dt_iniciacao->item(0)->nodeValue;
    			if ( $dtIniciatico   != '' ) { $grauUser['iniciatico'] = $dtIniciatico; }

                $itemdm_dt_elevacao = $dom_object->getElementsByTagName("dm_dt_elevacao");
                $dtDeMolay = $itemdm_dt_elevacao->item(0)->nodeValue;
    			if ( $dtDeMolay   != '' ) { $grauUser['demolay'] = $dtDeMolay; }

                $itemcav_dt_investidura = $dom_object->getElementsByTagName("cav_dt_investidura");
                $dtCavaleiro = $itemcav_dt_investidura->item(0)->nodeValue;
                if ( $dtCavaleiro   != '' ) { $grauUser['cavaleiro'] = $dtCavaleiro; }

                $itemcav_dt_grau_capela = $dom_object->getElementsByTagName("cav_dt_grau_capela");
                $dtCapela = $itemcav_dt_grau_capela->item(0)->nodeValue;
                if ( $dtCapela   != '' ) { $grauUser['capela'] = $dtCapela; }

                $itemcav_dt_grau_salem = $dom_object->getElementsByTagName("cav_dt_grau_salem");
                $dtSalem = $itemcav_dt_grau_salem->item(0)->nodeValue;
                if ( $dtSalem   != '' ) { $grauUser['salem'] = $dtSalem; }

                $itemcav_dt_grau_ex_templario = $dom_object->getElementsByTagName("cav_dt_grau_ex_templario");
                $dtExTemplario = $itemcav_dt_grau_ex_templario->item(0)->nodeValue;
                if ( $dtExTemplario   != '' ) { $grauUser['extemplario'] = $dtExTemplario; }

                $itemcav_dt_grau_triade = $dom_object->getElementsByTagName("cav_dt_grau_triade");
                $dtTriade = $itemcav_dt_grau_triade->item(0)->nodeValue;
                if ( $dtTriade   != '' ) { $grauUser['triade'] = $dtTriade; }

                $itemcav_dt_grau_ebano = $dom_object->getElementsByTagName("cav_dt_grau_ebano");
                $dtEbano = $itemcav_dt_grau_ebano->item(0)->nodeValue;
                if ( $dtEbano   != '' ) { $grauUser['ebano'] = $dtEbano; }

                $itemcav_dt_grau_anon = $dom_object->getElementsByTagName("cav_dt_grau_anon");
                $dtAnon = $itemcav_dt_grau_anon->item(0)->nodeValue;
                if ( $dtAnon   != '' ) { $grauUser['anon'] = $dtAnon; }

                $itemcav_dt_grau_cadencia = $dom_object->getElementsByTagName("cav_dt_grau_cadencia");
                $dtCadencia = $itemcav_dt_grau_cadencia->item(0)->nodeValue;
                if ( $dtCadencia   != '' ) { $grauUser['cadencia'] = $dtCadencia; }

                $itemcav_dt_grau_comendador = $dom_object->getElementsByTagName("cav_dt_grau_comendador");
                $dtComendador = $itemcav_dt_grau_comendador->item(0)->nodeValue;
                if ( $dtComendador   != '' ) { $grauUser['comendador'] = $dtComendador; }

                $itemcav_dt_grau_grande_cruz = $dom_object->getElementsByTagName("cav_dt_grau_grande_cruz");
                $dtGrandeCruz = $itemcav_dt_grau_grande_cruz->item(0)->nodeValue;
                if ( $dtGrandeCruz   != '' ) { $grauUser['grandecruz'] = $dtGrandeCruz; }

                $itemcav_dt_grau_manto = $dom_object->getElementsByTagName("cav_dt_grau_manto");
                $dtManto = $itemcav_dt_grau_manto->item(0)->nodeValue;
                if ( $dtManto   != '' ) { $grauUser['mantoprateado'] = $dtManto; }

                $itemchev_dt_sagracao = $dom_object->getElementsByTagName("chev_dt_sagracao");
                $dtChevalier = $itemchev_dt_sagracao->item(0)->nodeValue;
                if ( $dtChevalier   != '' ) { $grauUser['chevalier'] = $dtChevalier; }

                $itemmc_dt_iniciacao = $dom_object->getElementsByTagName("mac_dt_iniciacao");
                $dtMacom = $itemmc_dt_iniciacao->item(0)->nodeValue;
                if ( $dtMacom   != '' ) {
                    $grauUser['iniciatico'] = '';
                    $grauUser['demolay'] = '';
                    $grauUser['cavaleiro'] = '';
                    $grauUser['capela'] = '';
                    $grauUser['salem'] = '';
                    $grauUser['extemplario'] = '';
                    $grauUser['triade'] = '';
                    $grauUser['ebano'] = '';
                    $grauUser['anon'] = '';
                    $grauUser['cadencia'] = '';
                    $grauUser['comendador'] = '';
                    $grauUser['grandecruz'] = '';
                    $grauUser['mantoprateado'] = '';
                    $grauUser['chevalier'] = '';
                    $grauUser['Macom'] = $dtMacom;
                }
            }

			return $grauUser;
		}
	}
}
$ClassUser = new ClassUser();
?>
