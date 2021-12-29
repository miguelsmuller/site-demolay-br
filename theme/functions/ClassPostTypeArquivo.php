<?php
/*
Name: CUOP
Description: Controle único das opções do tema
Version: 1.0
Author: Miguel Müller
AuthorURI: https://github.com/miguelsneto
license: Creative Commons - Atribuição-NãoComercial-SemDerivados 3.0 Não Adaptada License.
LicenseURI: http://creativecommons.org/licenses/by-nc-nd/3.0/
*/

add_action( 'before_delete_post', 'my_func' );
function my_func( $postid ){

    // We check if the global post type isn't ours and just return
    global $post_type;
    if ( $post_type != 'arquivo' ) return;

    $urlArquivo          = get_post_meta($postid, "urlArquivo", true) != '' ? get_post_meta($postid, "urlArquivo", true) : '';
    $dirArquivo          = get_post_meta($postid, "dirArquivo", true) != '' ? get_post_meta($postid, "dirArquivo", true) : '';
    $nomeArquivo         = get_post_meta($postid, "nomeArquivo", true) != '' ? get_post_meta($postid, "nomeArquivo", true) : '';
    $fullname            =  WP_CONTENT_DIR . '/uploads/arquivo-post-type/'.$nomeArquivo;

    if (file_exists($fullname) == TRUE) {
        unlink($fullname);
    }
}

class ClassPostTypeArquivo
{
    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct(){
        add_action('init',
            array( &$this, 'initPostType' )
        );
        add_action('post_edit_form_tag',
            array( &$this, 'postEditFormTag' )
        );
        add_action( 'add_meta_boxes',
            array( &$this, 'addMetaBoxes' )
        );
        add_action( 'save_post',
            array( &$this, 'savePost' )
        );
        add_filter( 'post_updated_messages',
            array( &$this, 'postUpdatedMessages' )
        );


        add_action( 'arquivo_categoria_add_form_fields',
            array( &$this, 'arquivo_categoria_add_form_fields' ) , 10, 2
         );
        add_action( 'arquivo_categoria_edit_form_fields',
            array( &$this, 'arquivo_categoria_edit_form_fields' ) , 10, 2
        );
        add_action( 'edited_arquivo_categoria',
            array( &$this, 'save_taxonomy_download' ), 10, 2
        );
        add_action( 'create_arquivo_categoria',
            array( &$this, 'save_taxonomy_download' ), 10, 2
        );



        add_action( 'admin_head',
            array( &$this, 'adminHead' )
        );



        add_filter( 'admin_bar_menu', array( &$this, 'adminBarMenu' ), 100 );




        add_filter( 'manage_edit-arquivo_columns',
            array( &$this, 'manageEditColumns' )
        );
        add_action( 'manage_arquivo_posts_custom_column',
            array( &$this, 'managePostsCustomColumn' )
        );
        add_filter( 'manage_edit-arquivo_sortable_columns',
            array( &$this, 'manageEditSortableColumns' )
        );




        add_filter( 'restrict_manage_posts',
            array( &$this, 'restrictManagePosts' )
        );
        add_filter( 'parse_query',
            array( &$this, 'parseQuery' )
        );




        add_action( 'init',
            array( &$this, 'init' )
        );
        add_filter( 'query_vars',
            array( &$this, 'queryVars' )
        );
        add_filter( 'template_redirect',
            array( &$this, 'templateRedirect' )
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

    /* #############################################################
    # GET PROTECTED
    ############################################################# */

    /* #############################################################
    # SETA OS VALORES PADRÕES PARA A CLASSE
    ############################################################# */

    /* #############################################################
    # CRIA O POST TYPE
    ############################################################# */
    function initPostType(){
        register_post_type( 'arquivo',
            array(
                'labels' => array(
                    'name'               => 'Arquivos',
                    'singular_name'      => 'Arquivo',
                    'add_new'            => 'Adicionar novo arquivo',
                    'add_new_item'       => 'Adicionar novo arquivo',
                    'edit'               => 'Editar',
                    'edit_item'          => 'Editar arquivo',
                    'new_item'           => 'Novo arquivo',
                    'view'               => 'Ver',
                    'view_item'          => 'Ver arquivo',
                    'search_items'       => 'Buscar arquivo',
                    'not_found'          => 'Nenhuma arquivo encontrado',
                    'not_found_in_trash' => 'Nenhuma arquivo encontrado na lixeira',
                    'parent'             => 'Arquivos',
                    'menu_name'          => 'Arquivos'
                ),

                'hierarchical'    => false,
                'public'          => true,
                'query_var'       => true,
                'rewrite'         => array('slug' => 'arquivos', 'with_front' => false),
                'menu_position'   => null,
                'supports'        => array( 'title','editor' ),
                'has_archive'     => true,
                'capability_type' => 'post'
            )
        );

        register_taxonomy('arquivo_categoria',array('arquivo'),
            array(
                'labels'  => array(
                    'name'              => 'Categorias dos arquivos',
                    'singular_name'     => 'Categoria do arquivo',
                    'search_items'      =>  'Buscar categorias de arquivos',
                    'all_items'         => 'Categorias de arquivos',
                    'parent_item'       => 'Categoria de arquivo pai',
                    'parent_item_colon' => 'Categoria de arquivo pai',
                    'edit_item'         => 'Editar categoria de arquivo',
                    'update_item'       => 'Atualizar categoria de arquivo',
                    'add_new_item'      => 'Adicionar nova categoria de arquivo'
                ),
                'public'        => true,
                'hierarchical'  => true,
                'show_ui'       => true,
                'query_var'     => true,
                'show_tagcloud' => false,
                'rewrite'       => array( 'slug' => 'arquivo_categoria', 'with_front' => false ),
        ));

        register_taxonomy('arquivo_tag',array('arquivo'),
            array(
                'labels'  => array(
                    'name'              => 'Referências',
                    'singular_name'     => 'Referência',
                    'search_items'      => 'Buscar referências de arquivos',
                    'all_items'         => 'Referências de arquivos',
                    'parent_item'       => '',
                    'parent_item_colon' => '',
                    'edit_item'         => 'Editar referências de arquivo',
                    'update_item'       => 'Atualizar referências de arquivo',
                    'add_new_item'      => 'Adicionar nova referências de arquivo'
                ),
                'public'        => false,
                'hierarchical'  => false,
                'show_ui'       => true,
                'query_var'     => true,
                'show_tagcloud' => true,
                'rewrite'       => array( 'slug' => 'arquivo_tag', 'with_front' => false ),
        ));
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE EVENTO
    ############################################################# */
    function postEditFormTag() {
        echo ' enctype="multipart/form-data"';
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE EVENTO
    ############################################################# */
    function is_edit_page($new_edit = null){
        global $pagenow;
        //make sure we are on the backend
        if (!is_admin()) return false;


        if($new_edit == "edit")
            return in_array( $pagenow, array( 'post.php',  ) );
        elseif($new_edit == "new") //check for new post page
            return in_array( $pagenow, array( 'post-new.php' ) );
        else //check for either new or edit
            return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
    }
    function addMetaBoxes(){
        add_meta_box( 'metaBoxEventos', 'Informações do Arquivo', array( &$this, 'functionMetabox' ), 'arquivo', 'normal', 'high' );
    }
    function functionMetabox(){
        global $post;

        wp_nonce_field('nonceAction', 'nonceName');

        $postOptions       = get_post_custom( $post->ID );

        $file = isset( $postOptions['urlArquivo'] ) ? $postOptions['urlArquivo'][0] : '';

        $indisponivel        = isset( $postOptions['indisponivel'] ) ? esc_attr( $postOptions['indisponivel'][0] ) : 0;
        $privado             = isset( $postOptions['privado'] ) ? esc_attr( $postOptions['privado'][0] ) : 'iniciatico';
        $quantidadeDownloads = isset( $postOptions['quantidadeDownloads'] ) ? $postOptions['quantidadeDownloads'][0] : '0';
    ?>

    <div id="extrafields">
        <h4>Arquivo físico</h4>
        <table class="form-table">
            <tbody>

                <?php
                if ($this->is_edit_page('new')){
                ?>
                <input type="hidden" name="metodo" value="novo" />
                <tr valign="top">
                    <th scope="row"><label for="file">Origem do arquivo no computador:</label></th>
                    <td>
                        <input type="file" id="file" name="file" value="" style="width: 90%;">
                    </td>
                </tr>
                <?php
                }else{
                ?>
                <input type="hidden" name="metodo" value="edicao" />
                <tr valign="top">
                    <th scope="row"><label for="fileExternal">Origem do arquivo na internet:</label></th>
                    <td>
                        <input type="text" id="fileExternal" disabled="disabled" name="fileExternal" value="<?php echo $file; ?>" style="width: 90%;" />
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <h4>INFORMAÇÕES COMPLEMENTARES</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">Indisponível:</th>
                    <td>
                        <input type="checkbox" id="indisponivel" readonly="readonly" name="indisponivel" <?php checked( $indisponivel, '1' ); ?> /><label for="indisponivel"> Arquivo indisponível no momento</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Restrição do Arquivo:</th>
                    <td>
                        <label>
                            <input type="radio" id="privado" name="privado" value="liberado" <?php checked( $privado, 'liberado' ); ?> />
                            <strong>Liberado</strong> sem necessidade de login<br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="iniciatico" <?php checked( $privado, 'iniciatico' ); ?> />
                        Com login a partir do <strong>Iniciático</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="demolay" <?php checked( $privado, 'demolay' ); ?> />
                        Com login a partir do <strong>DeMolay</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="cavaleiro" <?php checked( $privado, 'cavaleiro' ); ?> />
                        Com login a partir do <strong>Cavaleiro</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="capela" <?php checked( $privado, 'capela' ); ?> />
                        Com login a partir do <strong>Capela</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="salem" <?php checked( $privado, 'salem' ); ?> />
                        Com login a partir do <strong>Salém</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="extemplario" <?php checked( $privado, 'extemplario' ); ?> />
                        Com login a partir do <strong>Templário</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="triade" <?php checked( $privado, 'triade' ); ?> />
                        Com login a partir do <strong>Tríade</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="ebano" <?php checked( $privado, 'ebano' ); ?> />
                        Com login a partir do <strong>Ébano</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="anon" <?php checked( $privado, 'anon' ); ?> />
                        Com login a partir do <strong>Anon</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="cadencia" <?php checked( $privado, 'cadencia' ); ?> />
                        Com login a partir do <strong>Cadência</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="comendador" <?php checked( $privado, 'comendador' ); ?> />
                        Com login a partir do <strong>Comendador</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="grandecruz" <?php checked( $privado, 'grandecruz' ); ?> />
                        Com login a partir do <strong>Grande Cruz</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="mantoprateado" <?php checked( $privado, 'mantoprateado' ); ?> />
                        Com login a partir do <strong>Manto Prateado</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="chevalier" <?php checked( $privado, 'chevalier' ); ?> />
                        Com login a partir do <strong>Chevalier</strong><br/>
                        </label>
                        <label>
                            <input type="radio" id="privado" name="privado" value="Macom" <?php checked( $privado, 'Macom' ); ?> />
                        Com login de <strong>Maçom</strong><br/>
                        </label>

                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="quantidadeDownloads">Quant. Downloads:</label></th>
                    <td>
                        <input type="text" id="quantidadeDownloads" readonly="readonly" name="quantidadeDownloads" value="<?php echo $quantidadeDownloads; ?>" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE EVENTO
    ############################################################# */
    function savePost( $postId )
    {
        if (get_post_type( $postId ) !== 'arquivo')
        return $postId;

        // Antes de dar inicio ao salvamento precisamos verificar 3 coisas:
        // Verificar se a publicação é salva automaticamente
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        //Verificar o valor nonce criado anteriormente, e finalmente
        if( !isset( $_POST['nonceName'] ) || !wp_verify_nonce($_POST['nonceName'], 'nonceAction') ) return;
        //Verificar se o usuário atual tem acesso para salvar a pulicação
        if( !current_user_can( 'edit_post' ) ) return;

        if( isset( $_POST['indisponivel'] ) ) {
            update_post_meta( $postId, 'indisponivel', '1' );
        }else{
            update_post_meta( $postId, 'indisponivel', '0' );
        }

        if( isset( $_POST['privado'] ) )
            update_post_meta( $postId, 'privado', wp_kses( $_POST['privado'], $allowed ) );


        if ( $_POST['metodo'] == 'novo'  ){
            if(!empty($_FILES['file']['name'])) {

                //CONFIGURA UM CONJUNTO DE TIPOS DE ARQUIVOS SUPORTADOS PARA UPLOAD
                $supported_types = array('audio/mp4', 'audio/mpeg');

                if (!file_exists(WP_CONTENT_DIR . '/uploads/arquivo-post-type')) {
                    mkdir(WP_CONTENT_DIR . '/uploads/arquivo-post-type', 0755, false);
                }

                $destinoFisico =  WP_CONTENT_DIR . '/uploads/arquivo-post-type';
                $nomeArquivo = $_FILES["file"]["name"];

                $extensaoArquivo = pathinfo($nomeArquivo, PATHINFO_EXTENSION);
                //$nomeArquivo = strtolower($nomeArquivo);
                //$extensaoArquivo = split("[/\\.]", $nomeArquivo) ;
                //$n = count($extensaoArquivo)-1;
                //$extensaoArquivo = $extensaoArquivo[$n];

                $newname = time() . rand(); // Create a new name
                $filepath = $destinoFisico . '/' . $newname.'.'.$extensaoArquivo; // Get the complete file path
                $nomeArquivo = $newname.'.'.$extensaoArquivo; // Get the new name with the extension

                if (move_uploaded_file($_FILES["file"]["tmp_name"], $filepath)) {
                    //SALVA DADOS D
                    $array_dir = wp_upload_dir();
                    $destinho_url = $array_dir['baseurl'].'/arquivo-post-type/';
                    update_post_meta($postId, "urlArquivo", $destinho_url.$nomeArquivo);
                    update_post_meta($postId, "dirArquivo", $destinoFisico.'/'.$nomeArquivo);
                    update_post_meta($postId, "nomeArquivo", $nomeArquivo);
                    update_post_meta($postId, "extArquivo", $extensaoArquivo);
                }
            }else{
                //$errors->add('oops', 'Arquivo vazio');
            }

            if ( !wp_is_post_revision( $postId ) && $_POST['privado'] = 'indisponivel' ){

                global $wpdb;
                $SQL = "SELECT U.ID, U.user_email, M.user_id, M.meta_key, M.meta_value
                        FROM $wpdb->users U
                        INNER JOIN $wpdb->usermeta M ON U.ID = M.user_id
                        WHERE ( (M.meta_key =  'notificarArquivo') AND (M.meta_value =  'SIM') );";

                $usersarray = $wpdb->get_results($SQL);
                $usersarray = get_object_vars($usersarray[0]) != NULL ? get_object_vars($usersarray[0]) : FALSE;
                IF ($usersarray != FALSE ){
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

                //$status = $ClassEmail->enviarEmail();
                }


            }

        }

        return $postId;
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE ARQUIVO
    ############################################################# */
    function postUpdatedMessages( $messages )
    {
        global $post, $post_ID;

        $messages['arquivo'] = array(
            0  => '',
            1  => sprintf( 'ARQUIVO atualizado com sucesso - <a href="%s">Ver arquivo</a>', esc_url( get_permalink($post_ID) )),
            2  => 'Campo personalizado ATUALIZADO.',
            3  => 'Campo personalizado DELETADO.',
            4  => 'ARQUIVO Atualizado',
            5  => isset($_GET['revision']) ? sprintf( 'ARQUIVO restaurado de %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( 'ARQUIVO publicado com sucesso - <a href="%s">Ver ARQUIVO</a>', esc_url( get_permalink($post_ID) ) ),
            7  => 'ARQUIVO salvo.',
            8  => sprintf( 'ARQUIVO enviado. <a target="_blank" href="%s">Ver ARQUIVO</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9  => sprintf( __('ARQUIVO agendando para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver ARQUIVO</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Rascunho do arquivo atualizado. <a target="_blank" href="%s">Ver ARQUIVO</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            );
        return $messages;
    }

    /* #############################################################
    # COLOCA NA WP-ADMIN-BAR UM LINK PARA CRIAR UM NOVO EVENTO
    ############################################################# */
    function adminBarMenu( $wp_admin_bar )
    {
        if ( !is_user_logged_in() ) { return; }
        if ( !is_super_admin() || !is_admin_bar_showing() ) { return; }

        $wp_admin_bar->add_menu( array(
            'parent' => 'menu_listas',
            'id'     => 'lista_arquivos',
            'title'  => 'Arquivos',
            'href'   => admin_url() .'edit.php?post_type=arquivo'
        ));
    }

    function arquivo_categoria_add_form_fields() {
    ?>
        <div class="form-field">
            <label for="term_meta[categoria_privada]">Categoria Privada</label>
            <input type="checkbox" id="term_meta[categoria_privada]" name="term_meta[categoria_privada]" />
        </div>
    <?php
    }

    function arquivo_categoria_edit_form_fields($term) {
        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option( "taxonomy_$t_id" );
        echo $term->term_id . ' ' . $term_meta['categoria_privada'];
        $term_meta['categoria_privada'] = isset($term_meta['categoria_privada']) ? $term_meta['categoria_privada'] : false; ?>

        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[categoria_privada]">Categoria Privada:</label></th>
            <td style="text-align:left;">
                <input type="checkbox" id="term_meta[categoria_privada]" name="term_meta[categoria_privada]" <?php checked( $term_meta['categoria_privada'],'on' ); ?> />
            </td>
        </tr>

        <input type="hidden" name="term_meta[email_contato]" value="a">

    <?php
    }

    function save_taxonomy_download( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            $chk = isset( $_POST['term_meta']['categoria_privada'] ) && $_POST['term_meta']['categoria_privada'] ? 'on' : 'off';
            $term_meta['categoria_privada'] =  $chk;

            // Save the option array.
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE ARQUIVO
    ############################################################# */
    function adminHead() {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'arquivo' ){
        ?>
            <style type="text/css" media="screen">
                #content_ifr{
                    height: 120px !important;
                }
            </style>
        <?php
        }
        ?>
        <style type="text/css" media="screen">
            #menu-posts-arquivo .wp-menu-image {
                background: url(<?php echo get_template_directory_uri() . '/functions/ClassPostTypeArquivo/images/post-type-arquivo.png' ?>) no-repeat 6px -17px !important;
            }
            #menu-posts-arquivo:hover .wp-menu-image, #menu-posts-arquivo.wp-has-current-submenu .wp-menu-image {
                background-position:6px 7px!important;
            }
        </style>
    <?php
    }

    /* #############################################################
    # ORGANIZAÇÃO DA GRID NO PAINEL DE ADMINITRAÇÃO
    ############################################################# */
    function manageEditColumns($columns){
        $columns['arquivo_tag']         = 'Referências';
        $columns['categoria']           = 'Categoria';
        $columns['nomeArquivo']         = 'Nome Arquivo';
        $columns['privado']             = 'Restrição';
        $columns['quantidadeDownloads'] = 'Quant. Downloads';

        unset( $columns['date'] );

        return $columns;
    }
    function managePostsCustomColumn ($column){
        global $post;

        $values              = get_post_custom( $post->ID );
        $quantidadeDownloads = isset( $values['quantidadeDownloads'] ) ? $values['quantidadeDownloads'][0] : '0';
        $nomeArquivo         = isset( $values['nomeArquivo'] ) ? $values['nomeArquivo'][0] : '0';
        $privado             = isset( $values['privado'] ) ? esc_attr( $values['privado'][0] ) : '-';
        $privado             = strtoupper($privado);

        switch( $column ) {
            case 'arquivo_tag':
                the_terms( $post->ID, 'arquivo_tag', ' ');
                break;

            case 'quantidadeDownloads' :
                    echo $quantidadeDownloads;
                break;

            case 'nomeArquivo' :
                    echo $nomeArquivo;
                break;

            case 'privado' :
                    echo $privado;
                break;

            case 'categoria' :
                $terms = get_the_terms( $post->ID, 'arquivo_categoria' );
                if ( !empty( $terms ) ) {
                    $out = array();
                    foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'arquivo_categoria' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'arquivo_categoria', 'display' ) )
                        );
                    }
                    echo join( ', ', $out );
                }
                else {
                    echo 'Não categorizado';
                }
                break;

            default :
                break;
        }
    }
    function manageEditSortableColumns( $columns ) {
        $columns['arquivo_tag']         = 'categoria';
        $columns['categoria']           = 'categoria';
        $columns['quantidadeDownloads'] = 'quantidadeDownloads';
        $columns['privado']             = 'privado';
        $columns['indisponivel']        = 'indisponivel';

        return $columns;
    }

    /* #############################################################
    # ACTION: RESTRICT_MANAGE_POSTS
    # ACTION:PARSE_QUERY
    # COLOCA UM FRILTO NA TELA DE LISTAGEM
    ############################################################# */
    function restrictManagePosts() {
        $screen = get_current_screen();
        global $wp_query;
        if ( $screen->post_type == 'arquivo' ) {
            wp_dropdown_categories( array(
                'show_option_all' => 'Todas as categorias',
                'taxonomy' => 'arquivo_categoria',
                'name' => 'arquivo_categoria',
                'orderby' => 'name',
                'selected' => ( isset( $wp_query->query['arquivo_categoria'] ) ? $wp_query->query['arquivo_categoria'] : '' ),
                'hierarchical' => false,
                'depth' => 3,
                'show_count' => false,
                'hide_empty' => true,
            ) );
        }
    }
    function parseQuery( $query ){
        $qv = &$query->query_vars;
        if ( ( isset( $qv['arquivo_categoria'] ) ) && is_numeric( $qv['arquivo_categoria'] ) ) {
            $term = get_term_by( 'id', $qv['arquivo_categoria'], 'arquivo_categoria' );
            $qv['arquivo_categoria'] = $term->slug;
        }
    }
    /* #############################################################
    # ACTION: RESTRICT_MANAGE_POSTS
    # ACTION:PARSE_QUERY
    # COLOCA UM FRILTO NA TELA DE LISTAGEM
    ############################################################# */
    function queryVars( $vars ) {
        $vars[] = 'action_arquivo';
        return $vars;
    }
    function init() {
        add_rewrite_rule(
            'arquivos/([^/]*)/action_arquivo/([^/]*)',
            'index.php?post_type=arquivo&arquivo=$matches[1]&action_arquivo=$matches[2]',
            'top'
        );
    }
    function templateRedirect() {
        if ( (get_query_var( 'post_type' ) == 'arquivo') && (get_query_var( 'action_arquivo' )) ) {
            add_filter( 'template_include', array( &$this, 'funcPHPantigo1' ) );
        }
    }
    function funcPHPantigo1(){
        return get_template_directory() . '/single-arquivo.php';
    }







    function formUserOptions( $user ) {
    ?>
        <table class="form-table">

            <tr>
                <th>
                    <label for="notificarArquivoNovo">Quer ser notificado por email sobre novos DOCUMENTOS publicados no site ?
                </label></th>
                <td>
                    <input type="checkbox" name="notificarArquivo" <?php if (get_the_author_meta( 'notificarArquivo', $user->ID) == 'SIM' ) { ?>checked="checked"<?php }?> value="SIM" /> SIM, QUERO SER NOTIFICADO<br />
                </td>
            </tr>
        </table>

    <?php }

    function formUserSaveOptions( $user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return FALSE;
        update_usermeta( $user_id, 'notificarArquivo', $_POST['notificarArquivo'] );
    }
}
$ClassPostTypeArquivo = new ClassPostTypeArquivo();
?>
