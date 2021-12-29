<?php
/*
Name: ClassMaintenance
Description:
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

class ClassMaintenance
{
    protected $status, $motivoMaintenance;
    protected $dtAtivado, $duracaoSias, $duracaoHoras, $duracaoMinutos;
    protected $paginasLiberadas;
    protected $liberadosFront, $liberadosBack;

    public function __construct()
    {
        $default = $this->setDefaults();

        $optionsClass            = get_option( 'configMaintenance' );
        $this->status            = isset( $optionsClass['status'] ) ? TRUE : FALSE;
        $this->dtAtivado         = isset( $optionsClass['dtAtivado'] ) ? $optionsClass['dtAtivado'] : '';
        $this->duracaoDias       = isset( $optionsClass['duracaoDias'] ) ? $optionsClass['duracaoDias'] : $default['duracaoDias'];
        $this->duracaoHoras      = isset( $optionsClass['duracaoHoras'] ) ? $optionsClass['duracaoHoras'] : $default['duracaoHoras'];
        $this->duracaoMinutos    = isset( $optionsClass['duracaoMinutos'] ) ? $optionsClass['duracaoMinutos'] : $default['duracaoMinutos'];
        $this->paginasLiberadas  = isset( $optionsClass['paginasLiberadas'] ) ? $optionsClass['paginasLiberadas'] : $default['paginasLiberadas'];
        $this->motivoMaintenance = isset( $optionsClass['motivoMaintenance'] ) ? $optionsClass['motivoMaintenance'] : $default['motivoMaintenance'];
        $this->liberadosFront    = isset( $optionsClass['liberadosFront'] ) ? $optionsClass['liberadosFront'] : $default['liberadosFront'];
        $this->liberadosBack     = isset( $optionsClass['liberadosBack'] ) ? $optionsClass['liberadosBack'] : $default['liberadosBack']
        ;

        add_action('admin_init', array( &$this, 'formMaintenance' ) );
        add_action('admin_menu', array( &$this, 'addSubMenu' ) );

        if ($this->getStatus() == TRUE) {
            add_filter('loginMessage', array( &$this, 'loginMessage' ) );
            add_action('admin_notices', array( &$this, 'adminNotices' ) );
            add_action('wp_loaded', array( &$this, 'applyMaintenanceMode' ) );
            add_action('after_body', array( &$this, 'NoticeMaintenancePage' ) );
        }
    }

    public function getStatus($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->status;
        } else {
            return ($this->status);
        }
    }
    public function getAcessoLiberado()
    {
        $optval = $this->liberadosFront;

        if ( $optval == 'manage_options' && current_user_can('manage_options') ) { return true; }
        elseif ( $optval == 'manage_categories' && current_user_can('manage_categories') ) { return true; }
        elseif ( $optval == 'publish_posts' && current_user_can('publish_posts') ) { return true;   }
        elseif ( $optval == 'edit_posts' && current_user_can('edit_posts') ) { return true; }
        elseif ( $optval == 'read' && current_user_can('read') ) { return true; }
        else { return false; }
    }
    public function getMotivoMaintenance($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->motivoMaintenance;
        } else {
            return ($this->motivoMaintenance);
        }
    }
    public function getDtAtivadoMaintenance($retorno = 'var')
    {
        if ($retorno == 'echo'){
            echo $this->dtAtivado;
        } else {
            return ($this->dtAtivado);
        }
    }
    public function getRetorno()
    {
        //PEGA OS VALORES INFORMATOS NA CONFIG E CONVERTE PRA MINUTOS
        $delay = 0;
        $delay = $delay + ( intval($this->duracaoDias) * 24 * 60 );
        $delay = $delay + ( intval($this->duracaoHoras) * 60 );
        $delay = $delay + ( intval($this->duracaoMinutos) );

        //PEGA O TEMPO EM QUE FOI ATIVADO E CALCULA O TEMPO PRA ACABAR SOMANDO
        //A QUANTIDADE DE MINUTOS EM MANUTENÇÃO E TRANSFORMANDO
        $intTimeActivated = intval($this->dtAtivado);
        $intTimeFinished  = $intTimeActivated + ($delay*60);

        //PEGA A O TEMPO ATUAL NO FORMATO TIMESTAMP
        $intCurrentTime = current_time('timestamp');

        //PEGA A DATA E TEMPO ATUAL NO FORMAT
        $strTryBackDate = date_i18n( get_option('date_format'), $intTimeFinished );
        $strTryBackTime = date_i18n( get_option('time_format'), $intTimeFinished );

        //NUMERO EM SEGUNDOS ATÉ ONDE SUPOSTAMENTE A MANUTENÇÃO ACABARÁ
        //APÓS CONVERTE PRA MINUTOS E HORAS
        $intTimeDelta_Seconds = $intTimeFinished - $intCurrentTime;
        $intTimeDelta_Minutes = round(($intTimeDelta_Seconds/(60)), 0);
        $intTimeDelta_Hours   = round(($intTimeDelta_Seconds/(60*60)), 1);

        if ( $intTimeDelta_Minutes < 0 ) {
            $intTimeDelta_Minutes = 0;
            $intTimeDelta_Hours   = 0;
        }

        $arrDuration = $this->duracao($intTimeDelta_Minutes);

        return array(
            'date'          => $strTryBackDate,
            'time'          => $strTryBackTime,
            'minutesTotal' => $intTimeDelta_Minutes,
            'hoursTotal'   => $intTimeDelta_Hours,
            'calcDays'     => $arrDuration['days'],
            'calcHours'    => $arrDuration['hours'],
            'calcMins'     => $arrDuration['mins'],
        );
    }
    private function duracao($minutes)
    {
        $minutes = intval($minutes);
        $vals_arr = array(  'days' => (int) ($minutes / (24*60) ),
                            'hours' => $minutes / 60 % 24,
                            'mins' => $minutes % 60);
        $return_arr = array();
        $is_added = false;
        foreach ($vals_arr as $unit => $amount) {
            $return_arr[$unit] = 0;

            if ( ($amount > 0) || $is_added ) {
                $is_added          = true;
                $return_arr[$unit] = $amount;
            }
        }
        return $return_arr;
    }

    /* #############################################################
    # SETA OS VALORES PADRÕES PARA A CLASSE
    ############################################################# */
    function setDefaults()
    {
        $defaults = array(
            'duracaoDias'       => '1',
            'duracaoHoras'      => '0',
            'duracaoMinutos'    => '0',
            'paginasLiberadas'  => '',
            'motivoMaintenance' => 'Nesse momento nosso site está sendo atualizado e por esse motivo você está vendo essa mensagem. <br/>Tente acessar novamente o site dentro de alguns instantes.<br/>Obrigado pela compreensão.',
            'liberadosFront'    => 'manage_options',
            'liberadosBack'     => 'manage_options'
        );
        return $defaults;
    }

    /* #############################################################
    # CRIA UM FORMULÁRIO QUE SERÁ USADO PARA CONFIGURAÇÃO
    # DOS ITENS DESSA CLASSE
    ############################################################# */
    function formMaintenance()
    {
        add_settings_section(
            'section',
            'Configure o modo de manutenção do site',
            '',
            'configMaintenance'
        );
        add_settings_field(
            'statusMaintenance',
            'Ativar o <strong>Modo Manutenção:</strong>:',
            array( &$this, 'callbackStatusManutencao' ),
            'configMaintenance',
            'section'
        );
        add_settings_field(
            'motivoManutencao',
            'Motivo manutenção:',
            array( &$this, 'callbackMotivoManutencao' ),
            'configMaintenance',
            'section'
        );
        add_settings_field(
            'paginasLiberadas',
            'As seguintes páginas tem acesso livre:',
            array( &$this, 'callbackPaginasLiberadas' ),
            'configMaintenance',
            'section'
        );
        add_settings_field(
            'usuariosLiberados',
            'Quem pode acessar o site:',
            array( &$this, 'callbackUsuariosLiberados' ),
            'configMaintenance',
            'section'
        );
        register_setting(
            'configMaintenance',
            'configMaintenance'
        );
    }
    function callbackStatusManutencao()
    {
        $previsao = $this->getRetorno();
        $previsao = $previsao['date'] . ' ás ' . $previsao['time'];
        $html = '<label>';
        $html .= '<input type="checkbox" id="status" name="configMaintenance[status]" value="TRUE" ' . checked( TRUE, $this->status, false ) . '/> Quero ativar';
        $html .= '</label><br/>';
        $html .= '<input type="hidden" name="configMaintenance[dtAtivado]" VALUE="'.current_time('timestamp').'">';
        if ($this->getStatus() == true) {
            $html .= "<br/><span class='description'>A manutenção está programada para ser encerrada em dia $previsao </span>";
        }
        $html .= '<table>
                    <tbody>
                        <tr>
                            <td><strong>Voltar em:</strong></td>
                            <td><input type="text" id="duracaoDias" name="configMaintenance[duracaoDias]" value="'.$this->duracaoDias.'" size="4" maxlength="5"> <label for="duracaoDias">Dias</label></td>
                            <td><input type="text" id="duracaoHoras" name="configMaintenance[duracaoHoras]" value="'.$this->duracaoHoras.'" size="4" maxlength="5"> <label for="duracaoHoras">Horas</label></td>
                            <td><input type="text" id="duracaoMinutos" name="configMaintenance[duracaoMinutos]" value="'.$this->duracaoMinutos.'" size="4" maxlength="5"> <label for="duracaoMinutos">Minutos</label></td>
                        </tr>
                    </tbody>
                </table>';
        echo $html;
    }
    function callbackMotivoManutencao()
    {
        $html = '<textarea id="motivoMaintenance" name="configMaintenance[motivoMaintenance]" cols="80" rows="5" class="large-text">'.$this->getMotivoMaintenance().'</textarea>';
        echo $html;
    }
    function callbackPaginasLiberadas()
    {
        $html = '<textarea id="paginasLiberadas" name="configMaintenance[paginasLiberadas]" cols="80" rows="5" class="large-text">'.$this->paginasLiberadas.'</textarea>';
        $html .= '<br/><span class="description">Digite os caminhos que devem estar acessíveis mesmo em modo de manutenção. Separe os vários caminhos com quebras de linha.<br/>Exemplo: Se você quer liberar acesso á pagina <strong>http://site.com/sobre/</strong>, você deve digitar <strong>/sobre/</strong>.<br/>Dica: Se você quiser liberar acesso a página inicial digite <strong>[HOME]</strong>.</span>';
        echo $html;
    }
    function callbackUsuariosLiberados()
    {
        $html = '<label>Acessar o site:';
        $html .= '<select id="liberadosFront" name="configMaintenance[liberadosFront]">
                    <option value="manage_options" ' . selected( $this->liberadosFront, 'manage_options', false) . '>Ninguém</option>
                    <option value="manage_categories" ' . selected( $this->liberadosFront, 'manage_categories', false) . '>Editor</option>
                    <option value="publish_posts" ' . selected( $this->liberadosFront, 'publish_posts', false) . '>Autor</option>
                    <option value="edit_posts" ' . selected( $this->liberadosFront, 'edit_posts', false) . '>Colaborador</option>
                    <option value="read" ' . selected( $this->liberadosFront, 'read', false) . '>Visitante</option>
                </select>';
        $html .= '</label><br />';
        $html .= '<label>Acessar o painel adminitrativo:';
        $html .= '<select id="liberadosBack" name="configMaintenance[liberadosBack]">
                    <option value="manage_options" ' . selected( $this->liberadosBack, 'manage_options', false) . '>Ninguém</option>
                    <option value="manage_categories" ' . selected( $this->liberadosBack, 'manage_categories', false) . '>Editor</option>
                    <option value="publish_posts" ' . selected( $this->liberadosBack, 'publish_posts', false) . '>Autor</option>
                    <option value="edit_posts" ' . selected( $this->liberadosBack, 'edit_posts', false) . '>Colaborador</option>
                    <option value="read" ' . selected( $this->liberadosBack, 'read', false) . '>Visitante</option>
                </select>';
        $html .= '</label><br />';
        echo $html;
    }

    /* #############################################################
    # CRIA UM ITEM DO MENU QUE ESTÁ LINKADO AO FORMULÁRIO CRIADO
    # ANTES
    ############################################################# */
    function addSubMenu()
    {
        add_submenu_page('configSite', 'Config. Manutenção', 'Config. Manutenção', 'level_10', 'maintenance', array(&$this,'telaMaintenance'));
    }
    function telaMaintenance()
    {
    ?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Configurações Gerais</h2>
            <form method="post" action="options.php">
                <?php
                if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
                settings_fields( 'configMaintenance' );
                do_settings_sections( 'configMaintenance' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    /* #############################################################
    # ALTERA A MENSAGEM QUE É APRESENTADA NA TELA DE LOGIN
    ############################################################# */
    function loginMessage()
    {
        return '<div id="login_error"><p class="text-center">Atualmente este site se encontra em <strong>MODO DE MANUTENÇÃO</strong>.</p></div>';
    }

    /* #############################################################
    # INSERE ALERTAS NO PAINEL ADMINITRATIVO
    ############################################################# */
    function adminNotices()
    { ?>
    <div class="error">
        <p>Atualmente o site se encontra em <strong>MODO DE MANUTENÇÃO</strong>. Para retornar ao modo normal mude as configurações <strong><a href="<?php echo get_bloginfo( 'url' ); ?>/wp-admin/admin.php?page=maintenance">CLICANDO AQUI.</a></strong></p>
    </div>
    <?php }

    /* #############################################################
    # APLICA O MODO DE MANUTENÇÃO QUANDO NECESSÁRIO
    ############################################################# */
    function applyMaintenanceMode()
    {
        /* # NUNCA MOSTRAR A PAGINA DE MANUTENÇÃO NO LOGIN E OUTRAS
        ============================================================= */
        if( strstr($_SERVER['PHP_SELF'],'wp-login.php') //WP-ADMIN É VERIFICADO EM OUTRO MOMENTO
            || strstr($_SERVER['PHP_SELF'], 'async-upload.php')
            || strstr(htmlspecialchars($_SERVER['REQUEST_URI']), '/plugins/')
            || strstr($_SERVER['PHP_SELF'], 'upgrade.php')
            || $this->liberarUrl()
        ){
            return;
        }

        /* # NUNCA MOSTRAR A PAGINA DE MANUTENÇÃO EM WP-ADMIN
        ============================================================= */
        if ( is_admin() || strstr(htmlspecialchars($_SERVER['REQUEST_URI']), '/wp-admin/') ) {
            if ( !is_user_logged_in() ) {
                auth_redirect();
            }
            if ( $this->usuarioAtualLiberado('backend') ) {
                return;
            } else {
                $this->displayMaintenancePage();
            }
        } else {
            if( $this->usuarioAtualLiberado('frontend') ) {
                return;
            } else {
                $this->displayMaintenancePage();
            }
        }
    }

    function displayMaintenancePage()
    {
        /* # VERIFICA QUAL PÁGINA SERÁ USADA NO REDIRECT DO VISITANTE
        ============================================================= */
        $file503 = get_template_directory() . '/503.php';
        if (file_exists($file503) == false) {
            $file503 = dirname(__FILE__) . '/ClassMaintenance/503.php';
        }

        /* # DEFINI O HEADER COMO INDISPONIVEL
        ============================================================= */
        //header('HTTP/1.0 503 Service Unavailable');
        //$minutos_retorno = $this->get_retorno();
        //$minutos_retorno = $minutos_retorno['minutes_total'];
        //if ( $minutos_retorno > 1 ) {
            //header('Retry-After: ' . $minutos_retorno * 60 );
        //}

        /* # MOSTRA A PÁGINA AO VISITANTE
        ============================================================= */
        include($file503);

        /* # NÃO CONTINUA MAIS NENHUM SCRIPT
        ============================================================= */
        exit();
    }

    /* #############################################################
    # FUNÇÃO SECUNDÁRIA QUE LIBERA ALGUMAS URL CADASTRADAS
    ############################################################# */
    function liberarUrl()
    {
        $urlarray = $this->paginasLiberadas;
        $urlarray = preg_replace("/\r|\n/s", ' ', $urlarray); //TRANSFORMA QUEBRA DE LINHAS EM ESPAÇO
        $urlarray = explode(' ', $urlarray); //TRANSFORMA A STRING EM UM ARRAY
        $oururl = 'http://' . $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI']);
        foreach ($urlarray as $expath) {
            if (!empty($expath)) {
                $expath = str_replace(' ', '', $expath);
                if (strpos($oururl, $expath) !== false) return true;
                if ( (strtoupper($expath) == '[HOME]') && ( trailingslashit(get_bloginfo('url')) == trailingslashit($oururl) ) )    return true;
            }
        }
        return false;
    }

    /* #############################################################
    # FUNÇÃO SECUNDÁRIA QUE LIBERA ALGUNS USUÁRIOS
    ############################################################# */
    function usuarioAtualLiberado($where)
    {
        if ($where == 'frontend') {
            $optval = $this->liberadosFront;
        } elseif ($where == 'backend') {
            $optval = $this->liberadosBack;
        } else {
            return false;
        }

        if ( $optval == 'manage_options' && current_user_can('manage_options') ) { return true; }
        elseif ( $optval == 'manage_categories' && current_user_can('manage_categories') ) { return true; }
        elseif ( $optval == 'publish_posts' && current_user_can('publish_posts') ) { return true;   }
        elseif ( $optval == 'edit_posts' && current_user_can('edit_posts') ) { return true; }
        elseif ( $optval == 'read' && current_user_can('read') ) { return true; }
        else { return false; }
    }

    function NoticeMaintenancePage()
    {
        if ($this->status == TRUE) {
            if ( $this->usuarioAtualLiberado('frontend') ) {
                $retorno = $this->getRetorno();
                $retorno = $retorno['date'].' ás '.$retorno['time'];
            ?>
            <div class="alert alert-info alert-block top-alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span class="content">
                    <div class="container">
                        <div class="row">
                            <div class="span12">
                                <strong>MODO MANUTENÇÃO ESTÁ ATIVO</strong>. Visitantes estão sendo encaminhados a uma página temporária. A previsão de retorno é para <strong><?php echo $retorno; ?></strong>
                            </div>
                        </div>
                    </div>
                </span>
            </div>
            <?php
            }
        }
    }
}
$ClassMaintenance = new ClassMaintenance();
?>
