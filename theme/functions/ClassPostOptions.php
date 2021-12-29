<?php
/*
Name: CUOP
Description: Controla opções relativas as publicações de posts
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

class ClassPostOption
{
    protected $quantPostsIndex;

    public function __construct()
    {
        $optionsClass          = get_option( 'configPost' );
        $this->quantPostsIndex = isset( $optionsClass['quantPostsIndex'] ) ? $optionsClass['quantPostsIndex'] : '6';

        add_action('save_post',
            array( &$this, 'savePost' )
        );

        add_action('admin_init', array( &$this, 'formOpcoesIndex' ) );
        add_action('admin_menu', array( &$this, 'addSubMenu' ) );

        add_action('add_meta_boxes',
            array( &$this, 'addMetaBoxes' )
        );


        add_action('the_excerpt',
            array( &$this, 'tratarExcerpt' )
        );
        add_action('get_the_excerpt',
            array( &$this, 'tratarExcerpt' )
        );

        add_action( 'show_user_profile',
            array( &$this, 'formUserOptions' )
        );
        add_action( 'edit_user_profile',
            array( &$this, 'formUserOptions' )
        );
        add_action( 'personal_options_update',
            array( &$this, 'formUserSaveOptions' )
        );
        add_action( 'edit_user_profile_update',
            array( &$this, 'formUserSaveOptions' )
        );
    }

    function savePost( $post_id )
    {
        //VERIFICAÇÕES DE SEGURANÇA
        if ( get_post_type($post_id) !== 'post' ) return $post_id;
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( !isset( $_POST['nonce_name'] ) || !wp_verify_nonce($_POST['nonce_name'], 'nonce_action') ) return;
        if ( !current_user_can( 'edit_post' ) ) return;

        //SALVA OPÇÕES DA PUBLICAÇÃO
        $valueChk = isset( $_POST['mostrarThumbSingle'] ) && $_POST['mostrarThumbSingle'] ? TRUE : FALSE;
            update_post_meta( $post_id, 'mostrarThumbSingle', $valueChk );

        //ENVIA EMAIL DE NOTIFICAÇÃO AOS USUARIOS REGISTRADOS
        if ( $_POST['metodo'] == 'novo'  ){
            if ( !wp_is_post_revision( $postId ) && $_POST['privado'] = 'indisponivel' ){
                global $wpdb;
                $SQL = "SELECT U.ID, U.user_email, M.user_id, M.meta_key, M.meta_value
                        FROM $wpdb->users U
                        INNER JOIN $wpdb->usermeta M ON U.ID = M.user_id
                        WHERE ( (M.meta_key =  'notificarArquivo') AND (M.meta_value =  'SIM') );";

                $usersarray = $wpdb->get_results($SQL);
                $usersarray = get_object_vars($usersarray[0]);
                unset($usersarray['ID']);
                unset($usersarray['user_id']);
                unset($usersarray['meta_key']);
                unset($usersarray['meta_value']);
                $users = implode(",", $usersarray);

                $arquivo = $_POST['post_title'];
                $linkVisualizar = '<a href="$destinho_url.$nomeArquivo/action_arquivo/visualizar">Visualizar</a>';
                $linkDownload = '<a href="$destinho_url.$nomeArquivo/action_arquivo/download">Download</a>';
                $mensagem = "O Arquivo <strong>$arquivo</strong> foi inserido/atualizado no nosso site.<br/> Você pode tentar visualizar o arquivo pelo link: $linkVisualizar <br/> Ou fazer o download através do link: $linkDownload";

                $ClassEmail = new ClassEmail();
                $ClassEmail->setRemetenteNome('SCODB-Automático');
                $ClassEmail->setRemetenteEmail('nao-responda@demolay.org.br');
                $ClassEmail->setResponderPara('nao-responda@demolay.org.br');
                $ClassEmail->setDestinatario($users);
                $ClassEmail->setAssunto('Novo arquivo publicado');
                $ClassEmail->setMensagem($mensagem);

                $ClassEmail->setTemplate('modelo-email-simples');

                $status = $ClassEmail->enviarEmail();
            }
        }
    }

    public function getQuantPostsIndex()
    {
        return ($this->quantPostsIndex);
    }

    function addSubMenu()
    {
        $page = add_posts_page('Config. Posts', 'Config. Posts', 'level_10', 'configPost', array(&$this,'telaConfigPost'));
    }
    function telaConfigPost()
    {?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Configurações Gerais</h2>
            <form method="post" action="options.php">
                <?php
                if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
                settings_fields( 'configPost' );
                do_settings_sections( 'configPost' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }
    function formOpcoesIndex()
    {
        add_settings_section(
            'section',
            'Defina as opções gerais customizadas do seu site',
            '',
            'configPost'
        );
        add_settings_field(
            'quantPostIndex',
            'Quantidade de Post na página inicial:',
            array( &$this, 'callbackQuantPostsIndex' ),
            'configPost',
            'section'
        );
        register_setting(
            'configPost',
            'configPost'
        );
    }
    function callbackQuantPostsIndex()
    {
        $html = '<input type="number" id="quantPostsIndex" name="configPost[quantPostsIndex]" class=".regular-text" value="' . $this->getQuantPostsIndex() . '"/>';
        $html .= '<br/><span class="description">Essa configuração só tem utilidade caso você esteja mostrando os posts recentes na pagina no formato widget</span>';
        echo $html;
    }



    /* #############################################################
    # CRIAÇÃO DE METABOX PARA QUE SEJA POSSÍVEL O POST TER CAMPOS
    # PERSONALIZADOS
    ############################################################# */
    function addMetaBoxes()
    {
        add_meta_box( 'postOption', 'Opções da Publicação:', array( &$this, 'htmlMetaboxPostOptions' ), 'post', 'side', 'high' );
    }
    function htmlMetaboxPostOptions()
    {
    global $post;
    global $pagenow;

    wp_nonce_field('nonce_action', 'nonce_name');

    $postOptions = get_post_custom( $post->ID );

    $mostrarThumbSingle = isset( $postOptions['mostrarThumbSingle'] ) ? esc_attr( $postOptions['mostrarThumbSingle'][0] ) : '';
    ?>
    <div id="extrafields">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="mostrarThumbSingle">Não mostra imagem de destaque: </label></th>
                    <td>
                        <input type="checkbox" id="mostrarThumbSingle" name="mostrarThumbSingle" <?php checked( $mostrarThumbSingle, TRUE ); ?> />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
    if ( in_array($pagenow, array( 'post-new.php',  )) ){
    ?>
        <input type="hidden" name="metodo" value="novo" />
    <?php
    }else{
    ?>
        <input type="hidden" name="metodo" value="edicao" />
    <?php
    }
    ?>
    <?php
    }


    /* #############################################################
    # ALTERA A SAUDAÇÃO DE BOAS VINDAS DO WP-ADMIN-BAR
    ############################################################# */
    function tratarExcerpt( $excerpt )
    {
        $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
        return preg_replace ("/\[(\S+)\]/e", "", $excerpt);
    }

    function formUserOptions( $user ) {
    ?>
        <table class="form-table">

            <tr>
                <th>
                    <label for="notificarArquivoNovo">Quer ser notificado por email sobre novas <strong>NOTICÍAS</strong> publicadas no site ?
                </label></th>
                <td>
                    <input type="checkbox" name="notificarNoticia" <?php if (get_the_author_meta( 'notificarNoticia', $user->ID) == 'SIM' ) { ?>checked="checked"<?php }?> value="SIM" /> SIM, QUERO SER NOTIFICADO<br />
                </td>
            </tr>
        </table>

    <?php }

    function formUserSaveOptions( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return FALSE;
        update_usermeta( $user_id, 'notificarNoticia', $_POST['notificarNoticia'] );
    }
}
$ClassPostOption = new ClassPostOption();



class ClassPageOption
{
    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct(){
        add_action('add_meta_boxes',
            array( &$this, 'addMetaBoxes' )
        );
        add_action('save_post',
            array( &$this, 'savePost' )
        );



    }

    /* #############################################################
    # ADICIONA META BOX A PAGINA
    ############################################################# */
    function addMetaBoxes(){
        add_meta_box( 'page_option', 'Opções da Publicação:', array( &$this, 'htmlMetaboxPageOptions' ), 'page', 'side', 'high' );
    }
    function htmlMetaboxPageOptions()
    {
    global $post;

    wp_nonce_field('nonce_action', 'nonce_name');

    $postOptions = get_post_custom( $post->ID );
    $mostrarPaginaInicial = isset( $postOptions['mostrarPaginaInicial'] ) ? esc_attr( $postOptions['mostrarPaginaInicial'][0] ) : '';
    ?>
    <div id="extrafields">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="mostrarPaginaInicial">Mostra essa publicação na página inicial: </label></th>
                    <td>
                        <input type="checkbox" id="mostrarPaginaInicial" name="mostrarPaginaInicial" <?php checked( $mostrarPaginaInicial, TRUE ); ?> />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
    }

    /* #############################################################
    # SALVA OS DADOS EXTRAS DA PAGINA
    ############################################################# */
    function savePost( $postId )
    {
        if (get_post_type($postId) !== 'page')
        return $postId;

        // Antes de dar inicio ao salvamento precisamos verificar 3 coisas:
        // Verificar se a publicação é salva automaticamente
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        //Verificar o valor nonce criado anteriormente, e finalmente
        if( !isset( $_POST['nonce_name'] ) || !wp_verify_nonce($_POST['nonce_name'], 'nonce_action') ) return;
        //Verificar se o usuário atual tem acesso para salvar a pulicação
        if( !current_user_can( 'edit_post' ) ) return;

        // MOSTRAR_PAGINA_INICIAL
        $value_chk = isset( $_POST['mostrarPaginaInicial'] ) && $_POST['mostrarPaginaInicial'] ? TRUE : FALSE;
            update_post_meta( $postId, 'mostrarPaginaInicial', $value_chk );
    }

}
$ClassPageOption = new ClassPageOption();
?>
