<?php
/*
Name: ClassCore
Description: Controla as demais classes do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/
class ClassPageLogin
{
    private $usuarioDeveloper = 'admin_miguel';

    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct()
    {
        add_action( 'login_enqueue_scripts',
            array( &$this, 'loginEnqueueScripts' )
        );
        add_filter( 'login_headerurl',
            array( &$this, 'loginHeaderUrl' )
        );
        add_filter( 'login_headertitle',
            array( &$this, 'loginHeaderTitle' )
        );
        add_action('admin_enqueue_scripts',
            array( &$this, 'adminEnqueueScripts' )
        );
    }

    /* #############################################################
    # SEM DESCRIÇÃO NO MOMENTO
    ############################################################# */
    function loginEnqueueScripts()
    {
        if (in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
            $caminhoBase = get_template_directory_uri() . '/functions/ClassPageLogin/';
            wp_enqueue_style( 'ClassPageLogin', $caminhoBase .'ClassPageLogin.css'  );

            $bgLogin    = get_template_directory_uri() . '/images/image-login-background.png';
            if (file_exists($bgLogin) == false) {
                $bgLogin = get_template_directory_uri() .'/functions/ClassPageLogin/image-login-background.png';
            }else{
                $bgLogin = get_template_directory_uri() . '/images/image-login.png';
            }

            $imgLogin   = get_template_directory() . '/images/image-login.png';
            if (file_exists($imgLogin) == false) {
                $imgLogin = get_template_directory_uri() .'/functions/ClassPageLogin/image-login.png';
            }else{
                $imgLogin = get_template_directory_uri() . '/images/image-login.png';
            }

            echo '<style type="text/css" media="screen">';
                echo 'body.login {';
                    echo 'background-image: url("'. $bgLogin .'");';
                echo '}';
                echo 'body.login div#login h1 a {';
                    echo 'background-image: url("'. $imgLogin .'");';
                echo '}';
            echo '</style>';
        }
    }

    /* #############################################################
    # SEM DESCRIÇÃO NO MOMENTO
    ############################################################# */
    function loginHeaderUrl(){
        return get_bloginfo( 'url' );
    }

    /* #############################################################
    # SEM DESCRIÇÃO NO MOMENTO
    ############################################################# */
    function loginHeaderTitle() {
        return get_bloginfo( 'name' );
    }

    /* #############################################################
    # SEM DESCRIÇÃO NO MOMENTO
    ############################################################# */
    function adminEnqueueScripts(){
        //$caminhoBase = get_template_directory_uri() . '/functions/ClassPageLogin/';
        //wp_enqueue_style( 'PageLogin', $caminhoBase .'ClassPageLogin.css'  );
    }
}
$ClassPageLogin = new ClassPageLogin();
?>