<?php
/*
Name: ClassTheme.php
Description: Funções com alguns itens específicos para implementação do tema atual
    dificilmente esse arquivo será semelhante a outro site usando essa base.
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/
function at_remove_wp_ver_css_js( $src ) {
    if ( strpos( $src, 'ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
add_filter( 'style_loader_src', 'at_remove_wp_ver_css_js', 9999 );
add_filter( 'script_loader_src', 'at_remove_wp_ver_css_js', 9999 );


add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
    remove_menu_page('edit-comments.php');
}

add_filter('sanitize_file_name', 'make_filename_hash', 10);
function make_filename_hash($filename) {
    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    $name = basename($filename, $ext);
    return 'SCODB-'.md5($name) . $ext;
}

function apiki_paginate_links( $args = array() )
{
    global $wp_query;

    $defaults = array(
        'big_number' => 999999999,
        'base'       => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
        'format'     => '?paged=%#%',
        'current'    => max( 1, get_query_var( 'paged' ) ),
        'total'      => $wp_query->max_num_pages,
        'prev_next'  => true,
        'end_size'   => 1,
        'mid_size'   => 2,
        'type'       => 'list'
    );

    $args = wp_parse_args( $args, $defaults );

    extract( $args, EXTR_SKIP );

    if ( $total == 1 ) return;

    $paginate_links = apply_filters( 'apiki_paginate_links', paginate_links( array(
        'base'      => $base,
        'format'    => $format,
        'current'   => $current,
        'total'     => $total,
        'prev_next' => $prev_next,
        'end_size'  => $end_size,
        'mid_size'  => $mid_size,
        'type'      => $type
    ) ) );

    echo $paginate_links;
}

class ClassTheme
{
    protected $GMN;
    protected $MCN;
    protected $MCNA;

    protected $endereco;

    protected $facebook;
    protected $twitter;
    protected $plus;
    protected $linkedin;
    protected $youtube;

    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct()
    {
        $optionsClass = get_option( 'configTema' );
        $this->GMN    = isset( $optionsClass['GMN'] ) ? $optionsClass['GMN'] : '';
        $this->MCN    = isset( $optionsClass['MCN'] ) ? $optionsClass['MCN'] : '';
        $this->MCNA   = isset( $optionsClass['MCNA'] ) ? $optionsClass['MCNA'] : '';

        $this->endereco = isset( $optionsClass['endereco'] ) ? $optionsClass['endereco'] : '';

        $this->facebook = isset( $optionsClass['facebook'] ) ? $optionsClass['facebook'] : '';
        $this->twitter  = isset( $optionsClass['twitter'] ) ? $optionsClass['twitter'] : '';
        $this->plus     = isset( $optionsClass['plus'] ) ? $optionsClass['plus'] : '';
        $this->linkedin = isset( $optionsClass['linkedin'] ) ? $optionsClass['linkedin'] : '';
        $this->youtube  = isset( $optionsClass['youtube'] ) ? $optionsClass['youtube'] : '';

        add_action('admin_init',
            array( &$this, 'formConfigTema' )
        );
        add_action('admin_menu',
            array( &$this, 'addSubmenu' )
        );

        add_filter( 'the_content' ,
            array( &$this, 'theContent' )
        );
        add_filter( 'wp_get_attachment_link' ,
            array( &$this, 'wpGetAttachmentLink' )
        );

        add_filter( 'the_content',
            array( &$this, 'lazeLoad' ), 99
        );
        add_filter( 'post_thumbnail_html',
            array( &$this, 'lazeLoad' ), 11
        );
    }

    /* #############################################################
    # GET PROTECTED
    ############################################################# */
    public function getGMN($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->GMN;
        } else {
            return ($this->GMN);
        }
    }
    public function getMCN($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->MCN;
        } else {
            return ($this->MCN);
        }
    }
    public function getMCNA($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->MCNA;
        } else {
            return ($this->MCNA);
        }
    }
    public function getEndereco($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->endereco;
        } else {
            return ($this->endereco);
        }
    }
    public function getFacebook($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->facebook;
        } else {
            return ($this->facebook);
        }
    }
    public function getTwitter($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->twitter;
        } else {
            return ($this->twitter);
        }
    }
    public function getPlus($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->plus;
        } else {
            return ($this->plus);
        }
    }
    public function getLinkedin($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->linkedin;
        } else {
            return ($this->linkedin);
        }
    }
    public function getYoutube($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->youtube;
        } else {
            return ($this->youtube);
        }
    }

    /* #############################################################
    # CRIA UM FORMULÁRIO QUE SERÁ USADO PARA CONFIGURAÇÃO
    # DOS ITENS DESSA CLASSE
    ############################################################# */
    function formConfigTema()
    {
        add_settings_section(
            'sectionLideranca',
            'Lideranças atuais',
            '',
            'configTema'
        );
        add_settings_field(
            'GMN',
            'Grande Mestre Nacional:',
            array( &$this, 'callbackGMN' ),
            'configTema',
            'sectionLideranca'
        );
        add_settings_field(
            'MCN',
            'Mestre Conselheiro Nacional:',
            array( &$this, 'callbackMCN' ),
            'configTema',
            'sectionLideranca'
        );
        add_settings_field(
            'MCNA',
            'Mestre Conselheiro Nacional Adjunto:',
            array( &$this, 'callbackMCNA' ),
            'configTema',
            'sectionLideranca'
        );

        add_settings_section(
            'sectionLocalizacao',
            'Localização:',
            '',
            'configTema'
        );
        add_settings_field(
            'endereco',
            'Endereço:',
            array( &$this, 'callbackEndereco' ),
            'configTema',
            'sectionLocalizacao'
        );

        add_settings_section(
            'sectionRedesSociais',
            'Redes Sociais:',
            '',
            'configTema'
        );
        add_settings_field(
            'facebook',
            'Facebook:',
            array( &$this, 'callbackFacebook' ),
            'configTema',
            'sectionRedesSociais'
        );
        add_settings_field(
            'twitter',
            'Twitter:',
            array( &$this, 'callbackTwitter' ),
            'configTema',
            'sectionRedesSociais'
        );
        add_settings_field(
            'plus',
            'Google Plus:',
            array( &$this, 'callbackPlus' ),
            'configTema',
            'sectionRedesSociais'
        );
        add_settings_field(
            'linkedin',
            'Linkedin:',
            array( &$this, 'callbackLinkedin' ),
            'configTema',
            'sectionRedesSociais'
        );
        add_settings_field(
            'youtube',
            'Youtube:',
            array( &$this, 'callbackYoutube' ),
            'configTema',
            'sectionRedesSociais'
        );

        register_setting(
            'configTema',
            'configTema'
        );
    }
    function callbackGMN()
    {
        $html = '<input type="text" id="GMN" name="configTema[GMN]" class="regular-text" value="' . $this->getGMN() . '"/>';
        echo $html;
    }
    function callbackMCN()
    {
        $html = '<input type="text" id="MCN" name="configTema[MCN]" class="regular-text" value="' . $this->getMCN() . '"/>';
        echo $html;
    }
    function callbackMCNA()
    {
        $html = '<input type="text" id="MCNA" name="configTema[MCNA]" class="regular-text" value="' . $this->getMCNA() . '"/>';
        echo $html;
    }

    function callbackEndereco()
    {
        $html = '<textarea id="endereco" name="configTema[endereco]" cols="80" rows="5" class="large-text">'.$this->endereco.'</textarea><br/>';
        echo $html;
    }

    function callbackFacebook()
    {
        $html = '<input type="text" id="facebook" name="configTema[facebook]" class="regular-text" value="' . $this->getFacebook() . '"/>';
        echo $html;
    }
    function callbackTwitter()
    {
        $html = '<input type="text" id="twitter" name="configTema[twitter]" class="regular-text" value="' . $this->getTwitter() . '"/>';
        echo $html;
    }
    function callbackPlus()
    {
        $html = '<input type="text" id="plus" name="configTema[plus]" class="regular-text" value="' . $this->getPlus() . '"/>';
        echo $html;
    }
    function callbackLinkedin()
    {
        $html = '<input type="text" id="linkedin" name="configTema[linkedin]" class="regular-text" value="' . $this->getLinkedin() . '"/>';
        echo $html;
    }
    function callbackYoutube()
    {
        $html = '<input type="text" id="youtube" name="configTema[youtube]" class="regular-text" value="' . $this->getYoutube() . '"/>';
        echo $html;
    }

    /* #############################################################
    # CRIA UM ITEM DO MENU QUE ESTÁ LINKADO AO FORMULÁRIO CRIADO
    # ANTE
    ############################################################# */
    function addSubmenu()
    {
        add_submenu_page('configSite', 'Config. Tema', 'Config. Tema', 'level_6', 'configTema', array(&$this,'telaConfigTema'));
    }
    function telaConfigTema()
    {?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Configurações Gerais</h2>
            <form method="post" action="options.php">
                <?php
                if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
                settings_fields( 'configTema' );
                do_settings_sections( 'configTema' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    /* #############################################################
    # USADO PARA INSERIR CLASSE NAS IMAGENS E LINKS DA PUBLICAÇÃO
    ############################################################# */
    function theContent( $content )
    {
        global $post;
        /*$pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
        $replacement = '<a$1rel="lightbox[galeria]" href=$2$3.$4$5$6>';

        $content = preg_replace($pattern, $replacement, $content);

        $pattern = "/<img(.*?)class=('|\")(.*?)('|\") alt=('|\")(.*?)('|\") src=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
        $replacement = '<a href=$8$9.$10$11 rel="lightbox[galeria]"><img$1 class=$2img-rounded img-polaroid $3$4 alt=$5$6$7 src=$8$9.$10$11$12></a>';

        $content = preg_replace($pattern, $replacement, $content);*/

        //$pattern = "/<img(.*?)class=('|\")(.*?)('|\") alt=('|\")(.*?)('|\") src=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
        //$replacement = '<a href=$8$9.$10$11><img$1 class=$2img-rounded img-polaroid $3$4 alt=$5$6$7 src=$8$9.$10$11$12></a>';

        //$content = preg_replace($pattern, $replacement, $content);

        return $content;
    }
    function wpGetAttachmentLink( $attachment_link )
    {
        $pattern = "/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
        $replacement = '<a$1rel="lightbox[galeria]" href=$2$3.$4$5$6>';

        $attachment_link = preg_replace($pattern, $replacement, $attachment_link);

        $attachment_link = str_replace("attachment-thumbnail", "img-rounded img-polaroid attachment-thumbnail", $attachment_link);
        return $attachment_link;
    }

    function lazeLoad( $content ) {
        /*// Don't lazyload for feeds, previews, mobile
        if( is_feed() || is_preview() || ( function_exists( 'is_mobile' ) && is_mobile() ) )
            return $content;

        // Don't lazy-load if the content has already been run through previously
        if ( false !== strpos( $content, 'data-src' ) )
            return $content;

        // In case you want to change the placeholder image
        $placeholder_image = apply_filters( 'lazyload_images_placeholder_image', get_template_directory_uri() . '/images/change-lilas-compressed.png' );

        // This is a pretty simple regex, but it works
        $content = preg_replace( '#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf( '<img${1}src="%s" data-original="${2}"${3}><noscript><img${1}src="${2}"${3}></noscript>', $placeholder_image ), $content );*/

        return $content;
    }
}
$ClassTheme = new ClassTheme();
?>
