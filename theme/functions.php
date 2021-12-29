<?php
/*
Name: Functions
Description: Controla as demais classes do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

define('urlWebService', 'http://www.wsold.demolay.org.br/api');
global $ClassConfigApi;

class ClassThemeFeatures
{
    //MENUS QUE ESTARÃO PRESENTES NO TEMA
    private $themesMenu = array(
        'menu-principal' => 'Menu Principal',
        'menu-topo'      => 'Menu Topo',
        'menu-rodape'    => 'Menu Rodape',
        'menu-sidebar'   => 'Menu Sidebar',
        'menu-mobile'    => 'Menu Mobile'
    );

    //SIDEBARS QUE ESTARÃO PRESENTES NO TEMA
    private $themesSideBars = array(
        array(
            'name'          => 'Sidebar Principal',
            'id'            => 'sidebar-principal',
            'description'   => 'Sidebar Principal',
            'before_widget' => '<div class="row-fluid widget"><div class="span12">',
            'before_title'  => '<div class="row-fluid widget-titulo"><div class="span12"><h4>',
            'after_title'   => '</h4></div></div><div class="row-fluid"><div class="span12">',
            'after_widget'  => '</div></div></div></div>'
        ),
        array(
            'name'          => 'Sidebar Single',
            'id'            => 'sidebar-single',
            'description'   => 'Sidebar com itens de notícias',
            'before_widget' => '<div class="row-fluid widget"><div class="span12">',
            'before_title'  => '<div class="row-fluid widget-titulo"><div class="span12"><h4>',
            'after_title'   => '</h4></div></div><div class="row-fluid"><div class="span12">',
            'after_widget'  => '</div></div></div></div>'
        ),
        array(
            'name'          => 'Sidebar Contato',
            'id'            => 'sidebar-contato',
            'description'   => 'Sidebar Contato',
            'before_widget' => '<div class="row-fluid widget"><div class="span12">',
            'before_title'  => '<div class="row-fluid widget-titulo"><div class="span12"><h4>',
            'after_title'   => '</h4></div></div><div class="row-fluid"><div class="span12">',
            'after_widget'  => '</div></div></div></div>'
        )
    );

    //TIPOS DE PUBLICAÇÕES QUE AS POSTAGEM PODEM SER
    private $themesPostsType = array(
        //'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'
    );

    //WIDGETS QUE SERÃO DESABILITADOS
    private $widgetsDisables = array(
        'WP_Widget_Nav_Menu','WP_Widget_Pages','WP_Widget_Calendar','WP_Widget_Archives', 'WP_Widget_Links', 'WP_Widget_Meta', 'WP_Widget_Text', 'WP_Widget_Categories', 'WP_Widget_Recent_Posts', 'WP_Widget_Recent_Comments', 'WP_Widget_RSS', 'WP_Widget_Tag_Cloud' //'WP_Widget_Search',
    );

    //DETERMINA SE O TIPO DE PUBLICAÇÃO PAGE TERÁ EXCERPT
    private $pageWithExcerpt = TRUE;

    //DETERMINA STATUS DA OPÇÃO THUMBNAILS E SUAS PROPRIEDADES
    private $imgThumbnails = array (
        'status'         => TRUE,
        'cropThumbnails' => FALSE,
        'width'          => 300,
        'height'         => 300
    );

    //DETERMINA NOVOS RECORTES DE IMAGENS
    private $imagesSizes = array(
        'carousel-thumbnails' => array( 773, 335, TRUE),
        'noticia-topo' => array( 770, 220, TRUE ),
        'perfil-pessoa' => array( 220, 240, TRUE ),
    );

    //QUAIS ARQUIVOS SERÃO CARREGOS NA INICIALIZAÇÃO
    private $filesFonts = array(
        'lato' => 'http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900',
    );
    private $filesCss = array(
        'jqueryui'              => '/features/jqueryui/css/theme/jquery-ui-1.10.3.custom.min.css',
        'bootstrap'             => '/features/bootstrap/css/bootstrap.min.css',
        'bootstrapResponsive'   => '/features/bootstrap/css/bootstrap-responsive.min.css',
        'bootstrapModal'        => '/features/bootstrapModal/bootstrap-modal.css',
        'fontAwesome'           => '/features/fontawesome/css/font-awesome.css',
        'prettyPhoto'           => '/features/prettyPhoto/css/prettyPhoto.css',
        'scrollstyle'           => '/features/scrollbar/jquery.mCustomScrollbar.css'
    );
    private $filesJs = array(
        'jqueryui'               => '/features/jqueryui/js/jquery-ui-1.10.3.custom.min.js',
        'bootstrap'              => '/features/bootstrap/js/bootstrap.min.js',
        'bootstrapModal'         => '/features/bootstrapModal/bootstrap-modal.js',
        'bootstrapModalMananger' => '/features/bootstrapModal/bootstrap-modalmanager.js',
        'prettyPhoto'            => '/features/prettyPhoto/js/jquery.prettyPhoto.js',
        'mousewheel'             => '/features/scrollbar/jquery.mousewheel.min.js',
        'scroll'                 => '/features/scrollbar/jquery.mCustomScrollbar.js',
        'instagram1'             => '/functions/ClassInstagram/instafeed.min.js',
        'instagram2'             => '/functions/ClassInstagram/feed_instagram.js'
    );

    public function __construct()
    {
        //INICIALIZAÇÃO DAS OPÇÕES DO TEMA
        add_action('after_setup_theme', array( &$this, 'setupFeatures' ) );

        //INICIALIZAÇÃO DOS WIDGETS SPACES DO TEMA
        add_action( 'widgets_init', array( &$this, 'widgetsInit' ) );

        //CARREGA CSS E JS
        add_action('wp_enqueue_scripts', array( &$this, 'wpEnqueue' ) );
    }

    function setupFeatures()
    {
        //MENUS QUE ESTARÃO PRESENTES NO TEMA
        if ( count($this->themesMenu) >= 1 )
            register_nav_menus($this->themesMenu);

        //TIPOS DE PUBLICAÇÕES QUE AS POSTAGEM PODEM SER
        if ( count($this->themesPostsType) >= 1 )
            add_theme_support( 'post-formats', $this->$themesPostsType );

        //DETERMINA SE O TIPO DE PUBLICAÇÃO PAGE TERÁ EXCERPT
        if ($this->pageWithExcerpt)
            add_post_type_support( 'page', 'excerpt' );

        //DETERMINA STATUS DA OPÇÃO THUMBNAILS E SUAS PROPRIEDADES
        if ($this->imgThumbnails['status'] == TRUE){
            add_theme_support('post-thumbnails');
            set_post_thumbnail_size(
                $this->imgThumbnails['width'],
                $this->imgThumbnails['height'],
                $this->imgThumbnails['cropThumbnails']
            );
        }

        //DETERMINA NOVOS RECORTES DE IMAGENS
        if ( count($this->imagesSizes) >= 1 ){
            foreach ($this->imagesSizes as $key => $values) {
                $width  = $values[0];
                $height = $values[1];
                $crop   = $values[2];

                add_image_size( $key, $width, $height, $crop );
            }
        }
    }

    function widgetsInit()
    {
        //SIDEBARS QUE ESTARÃO PRESENTES NO TEMA
        $itensSideBar = $this->themesSideBars;
        if ( count($itensSideBar) >= 1 ) {
            foreach ($itensSideBar as $sideBar) {
                register_sidebar( $sideBar );
            }
        }

        ////WIDGETS QUE SERÃO DESABILITADOS
        $widgetsDisables = $this->widgetsDisables;
        if ( count($widgetsDisables) >= 1 ) {
            foreach ($widgetsDisables as $widget) {
                unregister_widget( $widget );
            }
        }
    }

    function wpEnqueue()
    {
        /* #############################################################
        # CARREGA OS ARQUIVOS CSS'S DO TEMA
        ############################################################# */
        wp_enqueue_style( 'speed', get_bloginfo('stylesheet_url'));

        /* #############################################################
        # CARREGA OS ARQUIVOS JS'S DO TEMA
        ############################################################# */
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', '/wp-includes/js/jquery/jquery.js', false, '', true);
        wp_enqueue_script('speed', get_bloginfo('stylesheet_directory').'/javascript.js', false, '', true );

        //ARQUIVOS ESPECIFICOS
        if ( is_page_template('page-localizacao.php') ) {
          $key = $ClassConfigApi->getApiKeyGoogle();
            wp_enqueue_script(
                'maps',
                'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key='.$key
            );
        }
    }

}
$ClassThemeFeatures = new ClassThemeFeatures();


include 'functions/BasicFunctions.php';
include 'functions/ClassCore.php';
include 'functions/ClassPageLogin.php';
include 'functions/ClassConfigProprietario.php';
include 'functions/ClassConfigApi.php';
include 'functions/ClassMaintenance.php';
include 'functions/ClassUser.php';
include 'functions/ClassTwitterBootstrap.php';
include 'functions/ClassPostOptions.php';
include 'functions/ClassShortcode.php';
include 'functions/ClassTheme.php';
include 'functions/ClassSEO.php';
include 'functions/ClassNavegador.php';
include 'functions/ClassNotificacao.php';
include 'functions/ClassPostTypeEvento.php';
include 'functions/ClassPostTypeArquivo.php';
include 'functions/ClassPostTypeSlide.php';
include 'functions/ClassPostTypeDestaque.php';
include 'functions/ClassPostTypePessoa.php';
include 'functions/ClassWidgets.php';
include 'functions/ClassTaxonomy.php';
include 'functions/ClassPostTypeConvenio.php';
include 'functions/ClassPostTypePodcast.php';

include 'features/menu_with_cpt/post-type-archive-links.php';
?>
