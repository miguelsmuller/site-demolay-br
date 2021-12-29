<?php
/*
Name: ClassEmail
Description: Classe para envio de email baseado em templates
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/



#############################
COMO USAR
#############################
$ClassEmail = new ClassEmail();

$ClassEmail->setRemetenteNome('Nome destinatario');
$ClassEmail->setRemetenteEmail('Email destinatario');
$ClassEmail->setResponderPara('Email a qual responder');
$ClassEmail->setDestinatario('Email do destinatario');
$ClassEmail->setAssunto('Assunto email');
$ClassEmail->setMensagem('Conteudo email');

$ClassEmail->setTemplate('modelo-email-simples');
$ClassEmail->setCampoEmail('#NOME#',$nome);

$status = $ClassEmail->enviarEmail();
*/

class ClassNotificacao
{
    protected $remetenteNomeDefault;
    protected $remetenteEmailDefault;
    protected $responderParaDefault;
    protected $destinatarioDefault;

    /* #############################################################
    # FUNÇÃO __CONSTRUCT DA CLASSE
    ############################################################# */
    public function __construct()
    {
        $optionsClass                = get_option( 'ConfigMail' );
        $this->remetenteNomeDefault  = isset( $optionsClass['remetenteNomeDefault'] ) ? $optionsClass['remetenteNomeDefault'] : '';
        $this->remetenteEmailDefault = isset( $optionsClass['remetenteEmailDefault'] ) ? $optionsClass['remetenteEmailDefault'] : '';
        $this->responderParaDefault  = isset( $optionsClass['responderParaDefault'] ) ? $optionsClass['responderParaDefault'] : '';
        $this->destinatarioDefault   = isset( $optionsClass['destinatarioDefault'] ) ? $optionsClass['destinatarioDefault'] : '';

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
    public function getRemetenteNomeDefault($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->remetenteNomeDefault;
        } else {
            return ($this->remetenteNomeDefault);
        }
    }
    public function getRemetenteEmailDefault($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->remetenteEmailDefault;
        } else {
            return ($this->remetenteEmailDefault);
        }
    }
    public function getResponderParaDefault($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->responderParaDefault;
        } else {
            return ($this->responderParaDefault);
        }
    }
    public function getDestinatarioDefault($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->destinatarioDefault;
        } else {
            return ($this->destinatarioDefault);
        }
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
            'ConfigMail'
        );
        add_settings_field(
            'remetenteNomeDefault',
            'Nome Remetente envio:',
            array( &$this, 'callRemetenteNomeDefault' ),
            'ConfigMail',
            'section'
        );
        add_settings_field(
            'remetenteEmailDefault',
            'E-Mail Remetente envio:',
            array( &$this, 'callRemetenteEmailDefault' ),
            'ConfigMail',
            'section'
        );
        add_settings_field(
            'responderParaDefault',
            'Responder para envio:',
            array( &$this, 'callResponderParaDefault' ),
            'ConfigMail',
            'section'
        );
        add_settings_field(
            'destinatarioDefault',
            'Destinatário envio:',
            array( &$this, 'callDestinatarioDefault' ),
            'ConfigMail',
            'section'
        );                
        register_setting(
            'ConfigMail',
            'ConfigMail'
        );
    }
    function callRemetenteNomeDefault()
    {
        $html = '<input type="text" id="remetenteNomeDefault" name="ConfigMail[remetenteNomeDefault]" class="regular-text" value="' . $this->getRemetenteNomeDefault() . '"/>';
        $html .= '<p class="description">Se você estiver usando plugin de envio junto com google apps a mudança desse valor não fará diferença.</p>';
        echo $html;
    }
    function callRemetenteEmailDefault()
    {
        $html = '<input type="text" id="remetenteEmailDefault" name="ConfigMail[remetenteEmailDefault]" class="regular-text" value="' . $this->getRemetenteEmailDefault() . '"/>';
        echo $html;
    }
    function callDestinatarioDefault()
    {
        $html = '<input type="text" id="destinatarioDefault" name="ConfigMail[destinatarioDefault]" class="regular-text" value="' . $this->getDestinatarioDefault() . '"/>';
        echo $html;
    }
    function callResponderParaDefault()
    {
        $html = '<input type="text" id="responderParaDefault" name="ConfigMail[responderParaDefault]" class="regular-text" value="' . $this->getResponderParaDefault() . '"/>';
        echo $html;
    }    

    /* #############################################################
    # CRIAR MENU PRINCIPAL NO PAINEL ADMINITRAÇÃO
    ############################################################# */
    function adminMenu()
    {
        $page = add_submenu_page('config_site', 'Config. Mail', 'Config. Mail', 'level_6', 'ConfigMail', array(&$this,'mostrarTela'));
    }
    function mostrarTela()
    {?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Configurações Gerais</h2>
            <form method="post" action="options.php">
                <?php
                if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
                settings_fields( 'ConfigMail' );
                do_settings_sections( 'ConfigMail' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }
}
$ClassNotificacao = new ClassNotificacao();

class ClassEmail
{
    protected $template;
    protected $remetenteNome;
    protected $remetenteEmail;
    protected $responderPara;
    protected $destinatario;
    protected $prefixoAssunto;
    protected $assunto;
    protected $mensagem;

    /* #############################################################
    # SET PROTECTED
    ############################################################# */
    public function setRemetenteNome($value)
    {
        $this->remetenteNome = $value;
    }
    public function setRemetenteEmail($value)
    {
        $this->remetenteEmail = $value;
    }
    public function setResponderPara($value)
    {
        $this->responderPara = $value;
    }
    public function setDestinatario($value)
    {
        $this->destinatario = $value;
    }
    public function setPrefixoAssunto($value)
    {
        $this->prefixoAssunto = $value;
    }
    public function setAssunto($value)
    {
        $this->assunto = $value;
    }
    public function setMensagem($value)
    {
        $this->mensagem = str_replace('\r\n', '<br/>', $value);
    }

    /* #############################################################
    # CRIA O FORMULÁRIO DA PÁGINA
    ############################################################# */
    function enviarEmail()
    {
        global $ClassSistemaEmail;
        
        if ( empty($this->remetenteNome) ){
            $this->remetenteNome = $ClassSistemaEmail->getRemetenteNomeDefault();
        }
        if ( empty($this->remetenteEmail) ){
            $this->remetenteEmail = $ClassSistemaEmail->getRemetenteEmailDefault();
        }
        if( empty($this->destinatario) ){
            $this->destinatario = $ClassSistemaEmail->getDestinatarioDefault();
        }
        if( empty($this->responderPara) ){
            $this->responderPara = $ClassSistemaEmail->getResponderParaDefault();
        }

        //SE VALORES CONTINUAREM VAZIOS SAI DA ROTINA
        if ( empty($this->remetenteEmail) || empty($this->destinatario) ) {
            RETURN FALSE;
        }

        $assuntoEmail = $this->prefixoAssunto . ' ' . $this->assunto;

        $header[] = 'Content-Type: text/html';
        $header[] = 'From: '.$this->remetenteNome .'<'.$this->remetenteEmail.'>';
        $header[] = 'Reply-To:'. $this->remetenteNome .'<'.$this->responderPara.'>';

        $status = wp_mail( $this->destinatario, $assuntoEmail, $this->mensagem, $header );

        RETURN $status;
    }
    function setTemplate($template)
    {
        $template = file_get_contents( dirname(__FILE__) . '/ClassNotificacao/templates/' . $template .   '.html');
        $this->mensagem = str_replace("#MENSAGEM#", $this->mensagem, $template);
    }
    function setCampoEmail($oque = '', $por = '')
    {
        $this->mensagem = str_replace($oque, $por, $this->mensagem);
    }
}
?>