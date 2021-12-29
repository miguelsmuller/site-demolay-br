<?php
/*
Name: ClassNavegador.php
Description: Função principalmente para chegagem da versão do navegador
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

class ClassNavegador
{
	/* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
	public function __construct()
    {
		add_action('after_body',
            array( &$this, 'verificarNavegador' )
        );
	}

	/* #############################################################
    # VERIFICA A SITUAÇÃO ATUAL DO NAVEGADOR
    ############################################################# */
    public function verificarNavegador()
    {
        if ( empty( $_SERVER['HTTP_USER_AGENT'] ) )
            return false;

        $key = md5( $_SERVER['HTTP_USER_AGENT'] );
        if ( false === ($response = get_site_transient('browser_' . $key) ) ) {
            global $wp_version;
            $options = array(
                'body'          => array( 'useragent' => $_SERVER['HTTP_USER_AGENT'] ),
                'user-agent'    => 'WordPress/' . $wp_version . '; ' . home_url()
            );

            $response = wp_remote_post( 'http://api.wordpress.org/core/browse-happy/1.0/', $options );

            if ( is_wp_error( $response ) || 200 != wp_remote_retrieve_response_code( $response ) )
                return false;

            /**
            * Response should be an array with:
            *  'name' - string - A user friendly browser name
            *  'version' - string - The most recent version of the browser
            *  'current_version' - string - The version of the browser the user is using
            *  'upgrade' - boolean - Whether the browser needs an upgrade
            *  'insecure' - boolean - Whether the browser is deemed insecure
            *  'upgrade_url' - string - The url to visit to upgrade
            *  'img_src' - string - An image representing the browser
            *  'img_src_ssl' - string - An image (over SSL) representing the browser
            */
            $response = maybe_unserialize( wp_remote_retrieve_body( $response ) );
            if ( ! is_array( $response ) )
                return false;

            set_site_transient( 'browser_' . $key, $response, 604800 ); // cache for 1 week
        }

        if ( $response && $response['upgrade'] ) {
            if ( $response['insecure'] ){
                $msg = sprintf( __( "Parece que está a usar uma versão não segura do <a href='%s'>%s</a>. Para melhor navegar no nosso site, por favor atualize o seu browser." ), esc_attr( $response['update_url'] ), esc_html( $response['name'] ) );
            }
            else
            {
                $msg = sprintf( __( "Parece que está a usar uma versão antiga do <a href='%s'>%s</a>. Para melhor navegar no nosso site, por favor atualize o seu browser." ), esc_attr( $response['update_url'] ), esc_html( $response['name'] ) );
            }
            $notice .= sprintf( __( '<br/><a href="%1$s" class="update-browser-link">Nós aconselhamos que você atualize o %2$s agora.</a>' ), esc_attr( $response['update_url'] ), esc_html( $response['name'] ), esc_url( $browsehappy ) ) ;
            ?>
            <div class="alert alert-info alert-block top-alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span class="content">
                    <div class="container">
                        <div class="row">
                            <div class="span12">
                                <?php echo $msg . $notice?>
                            </div>
                        </div>
                    </div>
                </span>
            </div>
            <?php
        }
    }
}
$ClassNavegador = new ClassNavegador();
?>
