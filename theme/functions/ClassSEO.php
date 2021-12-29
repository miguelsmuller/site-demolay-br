<?php
/*
Name: SEO
Description: Controle único das opções do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

class ClassSEO
{
    protected $codAnalytics;
    protected $fbAppId;

    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct()
    {
        $defaults = array(
            'codAnalytics' => '',
            'fbAppId'      => ''
        );

        $optionsClass       = get_option( 'configSeo' );
        $this->codAnalytics = isset( $optionsClass['codAnalytics'] ) ? $optionsClass['codAnalytics'] : $defaults['codAnalytics'];
        $this->fbAppId      = isset( $optionsClass['fbAppId'] ) ? $optionsClass['fbAppId'] : $defaults['fbAppId'];

        add_action('admin_init', array( &$this, 'formSeo' ));
        add_action('admin_menu', array( &$this, 'addSubMenu' ));

        add_filter( 'wp_title', array( &$this, 'wpTitle' ), 0);

        add_action('wp_head', array( &$this, 'wp_head' ), 0);
        add_action('wp_footer', array( &$this, 'wp_footer' ));
        add_action('after_body', array( &$this, 'after_body' ), 0 );
    }

    /* #############################################################
    # GET PROTECTED
    ############################################################# */
    public function getCodAnalytics()
    {
        return ($this->codAnalytics);
    }
    public function getFbAppId()
    {
        return ($this->fbAppId);
    }

    /* #############################################################
    # CRIA UM FORMULÁRIO QUE SERÁ USADO PARA CONFIGURAÇÃO
    # DOS ITENS DESSA CLASSE
    ############################################################# */
    function formSeo()
    {
        add_settings_section(
            'sectionAnalytics',
            'Configure as opções do Google Analytics no site',
            '',
            'configSeo'
        );
        add_settings_section(
            'sectionFb',
            'Configure as opções de Facebook no site',
            '',
            'configSeo'
        );
        add_settings_field(
            'codAnalytics',
            'Código gerado pelo google analytics:',
            array( &$this, 'callbackCodAnalytics' ),
            'configSeo',
            'sectionAnalytics'
        );
        add_settings_field(
            'fbAppId',
            'ID do app:',
            array( &$this, 'callbackFbAppId' ),
            'configSeo',
            'sectionFb'
        );
        register_setting(
            'configSeo',
            'configSeo'
        );
    }
    function callbackCodAnalytics()
    {
        $html = '<textarea id="codAnalytics" name="configSeo[codAnalytics]" cols="80" rows="5" class="large-text">'.$this->getCodAnalytics().'</textarea>';
        $html .= '<br/><span class="description">Código UA gerado pelo sistema do Google Analytics. <strong>Não inserir as Tags de abertura e fechamento \<script\> e \<\/script\>.</strong></span>';
        echo $html;
    }
    function callbackFbAppId()
    {
        $html = '<input type="text" id="fbAppId" name="configSeo[fbAppId]" class="large-text" value="' . $this->getFbAppId() . '"/>';
        echo $html;
    }

    /* #############################################################
    # CRIA UM ITEM DO MENU QUE ESTÁ LINKADO AO FORMULÁRIO CRIADO
    # ANTES
    ############################################################# */
    function addSubMenu()
    {
        add_submenu_page('configSite', 'Config. SEO', 'Config. SEO', 'level_10', 'configSeo', array(&$this,'telaConfigSeo'));
    }
    function telaConfigSeo()
    {?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Configurações Gerais</h2>
            <form method="post" action="options.php">
                <?php
                if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
                settings_fields( 'configSeo' );
                do_settings_sections( 'configSeo' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    /* #############################################################
    # GERAÇÃO DO TITULO DAS PÁGINAS
    ############################################################# */
    function wpTitle( $title )
    {
        global $page, $paged;

        if ( is_feed() )
            return $title;

        $siteDescription = get_bloginfo( 'description' );

        $retorno = $title . get_bloginfo( 'name' );
        $retorno .= ( ! empty( $siteDescription ) && ( is_home() || is_front_page() ) ) ? ' | ' . $siteDescription: '';
        $retorno .= ( 2 <= $paged || 2 <= $page ) ? ' | ' . sprintf( __( 'Page %s' ), max( $paged, $page ) ) : '';

        return $retorno;
    }

    /* #############################################################
    # GERAÇÃO DOS METATAG'S ROBOTS
    ############################################################# */
    public function wp_head()
    {
        global $ClassMaintenance;
        global $post;

        /* # ROBOTS
        ============================================================= */
        if(is_single() || is_page() || is_home() ){
        ?>
        <meta name="robots" content="all,index,follow">
        <meta name="googlebot" content="index,noarchive,follow,noodp">
        <?php
        } else {
        ?>
        <meta name="robots" content="noindex,follow">
        <meta name="googlebot" content="noindex,noarchive,follow,noodp">
        <?php
        }
        echo PHP_EOL;

        /* # DESCRIPTION
        ============================================================= */
        $pageTitle       = trim (wp_title('', false));
        $manutencao      = $ClassMaintenance->getStatus();
        $siteName        = get_bloginfo( 'name' );
        $siteDescription = get_bloginfo( 'description' );

        $description = '';
        if ( is_home() || is_front_page() ){
            $description = 'A Ordem DeMolay é a maior organização juvenil do mundo. Dedicada a ensinar os jovens a serem líderes e pessoas melhores.';

        } elseif ( $manutencao == TRUE ){
            $description = 'Em Manutenção - ' . $siteName;

        } elseif ( is_archive() ) {
            $description = 'Arquivo de ' . $pageTitle . ' - ' . $siteName;

        } elseif ( is_404() ) {
            $description = 'Erro 404 (Página não localizada) - ' . $siteName;

        } elseif (is_single() || is_page() ) {
            $descrip = strip_tags($post->post_content);
            $descrip_more = '';
            if (strlen($descrip) > 155) {
                $descrip = substr($descrip,0,155);
                $descrip_more = ' ...';
            }
            $descrip = str_replace('"', '', $descrip);
            $descrip = str_replace("'", '', $descrip);
            $descripwords = preg_split('/[\n\r\t ]+/', $descrip, -1, PREG_SPLIT_NO_EMPTY);
            array_pop($descripwords);
            $description = implode(' ', $descripwords) . $descrip_more;

        } else {
            $description = $siteDescription;
        }
        ?>
            <meta name="description" content="<?php echo $description; ?>">
        <?php
        echo PHP_EOL;

        /* # KEYWORDS
        ============================================================= */
        ?>
        <meta name="keywords" content="">
        <?php
        echo PHP_EOL;

        /* # FACEBOOK
        ============================================================= */
        $facebook = '';

        if ($this->fbAppId) {
            $path_img = get_bloginfo('template_directory') . '/assets/images/img-facebook.png';

            $facebook .= ''. PHP_EOL;
            $facebook .= ''. PHP_EOL;
            if(is_home()) {
                $faceUrl = get_bloginfo( 'url' );
                $faceTitle = get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
                $faceDescription = 'A Ordem DeMolay é a maior organização juvenil do mundo, de fins filosóficos, filantrópicos, e sem fins lucrativos, já tendo iniciado desde de sua origem, mais de 2,5 milhões de jovens.';

            }else{
                $faceUrl = get_permalink();
                $faceTitle = get_the_title();
                $faceDescription = 'A Ordem DeMolay é a maior organização juvenil do mundo, de fins filosóficos, filantrópicos, e sem fins lucrativos, já tendo iniciado desde de sua origem, mais de 2,5 milhões de jovens.';

            }
            if (is_single()) {
                $faceImg = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

            }else{
                $faceImg = $path_img;
            }

            ?>
            <meta property="og:locale" content="pt_BR" />
            <meta property="og:type" content="website" />
            <meta property="og:url" content="<?php echo $faceUrl ?>" />
            <meta property="og:title" content="<?php echo $faceTitle ?>" />
            <meta property="og:description" content="<?php echo $faceDescription ?>" />
            <meta property="og:image" content="<?php echo $faceImg; ?>" />
            <meta property="fb:app_id" content="<?php echo $this->fbAppId; ?>" />
            <?php
            echo PHP_EOL;

            echo PHP_EOL . '<script>'. PHP_EOL;
            echo $this->codAnalytics;
            echo '</script>'. PHP_EOL;
        }
    }

    /* #############################################################
    # PUBLIC FUNCTION: GERAR_META_ANALYTICS
    ############################################################# */
    function wp_footer()
    {
        //echo PHP_EOL . '<script>'. PHP_EOL;
        //echo $this->codAnalytics;
        //echo '</script>'. PHP_EOL;
    }

    /* #############################################################
    # PUBLIC FUNCTION: GERAR_META_ANALYTICS
    ############################################################# */
    function after_body()
    {
        if ($this->fbAppId) {
        ?>
        <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=<?php echo $this->fbAppId; ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
        <?php
        }
    }
}
$ClassSEO = new ClassSEO();
?>