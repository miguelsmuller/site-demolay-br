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
class ClassConfigProprietario
{
    protected $nomeProprietario;
    protected $emailFormContato, $emailRespContato;
    protected $localizacaoLatLon;

    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct()
    {
        $default = $this->setDefaults();

        $optionsClass               = get_option( 'ConfigProprietario' );
        $this->nomeProprietario     = isset( $optionsClass['nomeProprietario'] ) ? $optionsClass['nomeProprietario'] : $default['nomeProprietario'];
        $this->emailFormContato     = isset( $optionsClass['emailFormContato'] ) ? $optionsClass['emailFormContato'] : $default['emailFormContato'];
        $this->emailRespContato     = isset( $optionsClass['emailRespContato'] ) ? $optionsClass['emailRespContato'] : $default['emailRespContato'];
        $this->localizacaoLatLon    = isset( $optionsClass['localizacaoLatLon'] ) ? $optionsClass['localizacaoLatLon'] : $default['localizacaoLatLon'];

        add_action('admin_init',
            array( &$this, 'adminInit' )
        );
        add_action('admin_menu',
            array( &$this, 'adminMenu' )
        );
    }

    /* #############################################################
    # GET PROTECTED
    ############################################################# */
    public function getNomeProprietario($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->nomeProprietario;
        } else {
            return ($this->nomeProprietario);
        }
    }
    public function getEmailFormContato($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->emailFormContato;
        } else {
            return ($this->emailFormContato);
        }
    }
    public function getEmailRespContato($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->emailRespContato;
        } else {
            return ($this->emailRespContato);
        }
    }
    public function getlocalizacaoLatLon($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->localizacaoLatLon;
        } else {
            return ($this->localizacaoLatLon);
        }
    }

    /* #############################################################
    # SETA OS VALORES DEFAULTS
    ############################################################# */
    function setDefaults()
    {
        $defaults = array(
            'nomeProprietario'  => 'Nome proprietário não cadastrado',
            'emailFormContato'  => 'contato@empresa.com.br',
            'emailRespContato'  => 'naoresponsa@empresa.com.br',
            'localizacaoLatLon' => '-22.518741,-44.105324'
        );
        return $defaults;
    }

    /* #############################################################
    # CRIA O FORMULÁRIO DA PÁGINA
    ############################################################# */
    function adminInit()
    {
        add_settings_section(
            'section',
            'Defina os dados do proprietário do site',
            '',
            'ConfigProprietario'
        );
        add_settings_field(
            'nomeProprietario',
            'Proprietário do site:',
            array( &$this, 'callbackNomeProprietario' ),
            'ConfigProprietario',
            'section'
        );

        add_settings_field(
            'emailFormContato',
            'E-mail para contato:',
            array( &$this, 'callbackEmailFormContato' ),
            'ConfigProprietario',
            'section'
        );
        add_settings_field(
            'emailRespContato',
            'E-mail de resposta:',
            array( &$this, 'callbackEmailRespContato' ),
            'ConfigProprietario',
            'section'
        );
        add_settings_field(
            'localizacaoLatLon',
            'Latitude e longitude da sede:',
            array( &$this, 'callbackLocalizacaoLatLon' ),
            'ConfigProprietario',
            'section'
        );
        register_setting(
            'ConfigProprietario',
            'ConfigProprietario'
        );
    }
    function callbackNomeProprietario()
    {
        $html = '<input type="text" id="nomeProprietario" name="ConfigProprietario[nomeProprietario]" class="large-text" value="' . $this->getNomeProprietario() . '"/>';
        echo $html;
    }
    function callbackEmailFormContato()
    {
        $html = '<input type="text" id="emailFormContato" name="ConfigProprietario[emailFormContato]" class="large-text" value="' . $this->getEmailFormContato() . '"/>';
        echo $html;
    }
    function callbackEmailRespContato()
    {
        $html = '<input type="text" id="emailRespContato" name="ConfigProprietario[emailRespContato]" class="large-text" value="' . $this->getEmailRespContato() . '"/>';
        echo $html;
    }
    function callbackLocalizacaoLatLon()
    {
        $html = '<input type="text" id="localizacaoLatLon" name="ConfigProprietario[localizacaoLatLon]" class="large-text" value="' . $this->getlocalizacaoLatLon() . '"/>';
        echo $html;
    }

    /* #############################################################
    # CRIAR MENU PRINCIPAL NO PAINEL ADMINITRAÇÃO
    ############################################################# */
    function adminMenu()
    {
        $page = add_submenu_page('configSite', 'Config. Proprietário', 'Config. Proprietário', 'level_6', 'ConfigProprietario', array(&$this,'mostrarTela'));
    }
    function mostrarTela()
    {?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Configurações Gerais</h2>
            <form method="post" action="options.php">
                <?php
                if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
                settings_fields( 'ConfigProprietario' );
                do_settings_sections( 'ConfigProprietario' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }
}
$ClassConfigProprietario = new ClassConfigProprietario();
?>