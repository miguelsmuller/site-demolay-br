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
class ClassConfigApi
{
  protected $ApiKeyGoogle;
  protected $ApiKeyGitHub;
  protected $ApiKeyFacebook;
  protected $ApiRecaptchaPrivate;
  protected $ApiRecaptchaPublic;

  /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
  public function __construct()
  {
    $default = $this->setDefaults();

    $optionsClass = get_option('ConfigAPI');
    $this->ApiKeyGoogle   = isset($optionsClass['ApiKeyGoogle']) ? $optionsClass['ApiKeyGoogle'] : $default['ApiKeyGoogle'];
    $this->ApiKeyGitHub   = isset($optionsClass['ApiKeyGitHub']) ? $optionsClass['ApiKeyGitHub'] : $default['ApiKeyGitHub'];
    $this->ApiKeyFacebook = isset($optionsClass['ApiKeyFacebook']) ? $optionsClass['ApiKeyFacebook'] : $default['ApiKeyFacebook'];
    $this->ApiRecaptchaPrivate = isset($optionsClass['ApiRecaptchaPrivate']) ? $optionsClass['ApiRecaptchaPrivate'] : $default['ApiRecaptchaPrivate'];
    $this->ApiRecaptchaPublic = isset($optionsClass['ApiRecaptchaPublic']) ? $optionsClass['ApiRecaptchaPublic'] : $default['ApiRecaptchaPublic'];

    add_action(
      'admin_init',
      array(&$this, 'adminInit')
    );
    add_action(
      'admin_menu',
      array(&$this, 'adminMenu')
    );
  }

  /* #############################################################
    # GET PROTECTED
    ############################################################# */
  public function getApiKeyGoogle($retorno = 'var')
  {
    if ($retorno == 'echo') {
      echo $this->ApiKeyGoogle;
    } else {
      return ($this->ApiKeyGoogle);
    }
  }
  public function getApiKeyGitHub($retorno = 'var')
  {
    if ($retorno == 'echo') {
      echo $this->ApiKeyGitHub;
    } else {
      return ($this->ApiKeyGitHub);
    }
  }
  public function getApiKeyFacebook($retorno = 'var')
  {
    if ($retorno == 'echo') {
      echo $this->ApiKeyFacebook;
    } else {
      return ($this->ApiKeyFacebook);
    }
  }
  public function getApiRecaptchaPrivate($retorno = 'var')
  {
    if ($retorno == 'echo') {
      echo $this->ApiRecaptchaPrivate;
    } else {
      return ($this->ApiRecaptchaPrivate);
    }
  }
  public function getApiRecaptchaPublic($retorno = 'var')
  {
    if ($retorno == 'echo') {
      echo $this->ApiRecaptchaPublic;
    } else {
      return ($this->ApiRecaptchaPublic);
    }
  }

  /* #############################################################
    # SETA OS VALORES DEFAULTS
    ############################################################# */
  function setDefaults()
  {
    $defaults = array(
      'ApiKeyGoogle'   => '',
      'ApiKeyGitHub'   => '',
      'ApiKeyFacebook' => '',
      'ApiRecaptchaPrivate' => '',
      'ApiRecaptchaPublic' => ''
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
      'Defina os dados de configuração de integração',
      '',
      'ConfigAPI'
    );
    add_settings_field(
      'ApiKeyGoogle',
      'Chave de API do google:',
      array(&$this, 'callbackApiKeyGoogle'),
      'ConfigAPI',
      'section'
    );
    add_settings_field(
      'ApiKeyGitHub',
      'Chave de API do GitHub:',
      array(&$this, 'callbackApiKeyGitHub'),
      'ConfigAPI',
      'section'
    );
    add_settings_field(
      'ApiKeyFacebook',
      'Chave de API do Facebook:',
      array(&$this, 'callbackApiKeyFacebook'),
      'ConfigAPI',
      'section'
    );
    add_settings_field(
      'ApiRecaptchaPrivate',
      'Chave Privada do Google Recaptcha:',
      array(&$this, 'callbackApiRecaptchaPrivate'),
      'ConfigAPI',
      'section'
    );
    add_settings_field(
      'ApiRecaptchaPublic',
      'Chave Pública do Google Recaptcha:',
      array(&$this, 'callbackApiRecaptchaPublic'),
      'ConfigAPI',
      'section'
    );
    register_setting(
      'ConfigAPI',
      'ConfigAPI'
    );
  }
  function callbackApiKeyGoogle()
  {
    $html = '<input type="text" id="ApiKeyGoogle" name="ConfigAPI[ApiKeyGoogle]" class="large-text" value="' . $this->getApiKeyGoogle() . '"/>';
    echo $html;
  }
  function callbackApiKeyGitHub()
  {
    $html = '<input type="text" id="ApiKeyGitHub" name="ConfigAPI[ApiKeyGitHub]" class="large-text" value="' . $this->getApiKeyGitHub() . '"/>';
    echo $html;
  }
  function callbackApiKeyFacebook()
  {
    $html = '<input type="text" id="ApiKeyFacebook" name="ConfigAPI[ApiKeyFacebook]" class="large-text" value="' . $this->getApiKeyFacebook() . '"/>';
    echo $html;
  }
  function callbackApiRecaptchaPrivate()
  {
    $html = '<input type="text" id="ApiRecaptchaPrivate" name="ConfigAPI[ApiRecaptchaPrivate]" class="large-text" value="' . $this->getApiRecaptchaPrivate() . '"/>';
    echo $html;
  }
  function callbackApiRecaptchaPublic()
  {
    $html = '<input type="text" id="ApiRecaptchaPublic" name="ConfigAPI[ApiRecaptchaPublic]" class="large-text" value="' . $this->getApiRecaptchaPublic() . '"/>';
    echo $html;
  }

  /* #############################################################
    # CRIAR MENU PRINCIPAL NO PAINEL ADMINITRAÇÃO
    ############################################################# */
  function adminMenu()
  {
    $page = add_submenu_page('configSite', 'Config. API', 'Config. API', 'level_6', 'ConfigAPI', array(&$this, 'mostrarTela'));
  }
  function mostrarTela()
  { ?>
    <div class="wrap">
      <div id="icon-options-general" class="icon32"></div>
      <h2>Configurações de Integração</h2>
      <form method="post" action="options.php">
        <?php
        if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
        settings_fields('ConfigAPI');
        do_settings_sections('ConfigAPI');
        submit_button();
        ?>
      </form>
    </div>
<?php }
}
$ClassConfigApi = new ClassConfigApi();
?>
