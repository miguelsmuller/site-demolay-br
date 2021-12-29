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

class ClassPostTypePodcast
{
    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct(){
        add_action('init',
            array( &$this, 'initPostType' )
        );
        add_action( 'init',
            array( &$this, 'init' )
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

        add_filter( 'admin_bar_menu', array( &$this, 'adminBarMenu' ), 100 );


        add_action( 'admin_head',
            array( &$this, 'adminHead' )
        );

        add_action('wp_enqueue_scripts',
            array( &$this, 'wpEnqueueScripts')
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
        register_post_type( 'podcast',
            array(
                'labels' => array(
                    'name' => 'Podcasts',
                    'singular_name' => 'Podcast',
                    'add_new' => 'Adicionar podcast',
                    'add_new_item' => 'Adicionar podcast',
                    'edit' => 'Editar',
                    'edit_item' => 'Editar podcast',
                    'new_item' => 'Nova podcast',
                    'view' => 'Ver',
                    'view_item' => 'Ver podcast',
                    'search_items' => 'Buscar podcast',
                    'not_found' => 'Nenhuma podcast encontrado',
                    'not_found_in_trash' => 'Nenhuma download encontrado na lixeira',
                    'parent' => 'Podcast'
                ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array('slug'=>'arquivo_democast','with_front'=>false),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array('title','editor   ')
            )
        );
    }

    function init() {
        add_rewrite_rule( '^arquivo_democast$', 'index.php?post_type=podcast', 'top' );
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
    function addMetaBoxes(){
        add_meta_box( 'metaBoxEventos', 'Informações do Arquivo', array( &$this, 'functionMetabox' ), 'podcast', 'normal', 'high' );
    }
    function functionMetabox(){
        global $post;

        wp_nonce_field('nonceAction', 'nonceName');

        $postOptions       = get_post_custom( $post->ID );

        $file = isset( $postOptions['urlArquivo'] ) ? $postOptions['urlArquivo'][0] : '';
    ?>

    <div id="extrafields">
        <h4>Arquivo físico</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="file">Origem do arquivo no computador:</label></th>
                    <td>
                        <input type="file" id="file" name="file" value="" style="width: 90%;">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="fileExternal">Origem do arquivo na internet:</label></th>
                    <td>
                        <input type="text" id="fileExternal" name="fileExternal" value="<?php echo $file; ?>" style="width: 90%;" />
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
        if (get_post_type($postId) !== 'podcast')
        return $postId;

        // Antes de dar inicio ao salvamento precisamos verificar 3 coisas:
        // Verificar se a publicação é salva automaticamente
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        //Verificar o valor nonce criado anteriormente, e finalmente
        if( !isset( $_POST['nonceName'] ) || !wp_verify_nonce($_POST['nonceName'], 'nonceAction') ) return;
        //Verificar se o usuário atual tem acesso para salvar a pulicação
        if( !current_user_can( 'edit_post' ) ) return;

        $array_dir = wp_upload_dir();
        $destinho_url = $array_dir['baseurl'].'/podcast/';

        if ( $_POST['fileExternal'] != ''  ){
            update_post_meta( $postId, 'urlArquivo', wp_kses( $_POST['fileExternal'], $allowed ) );

            /*remove_action('save_post', array( &$this, 'savePost' ));

            $my_post = array();
            $my_post['ID'] = $postId;
            $my_post['post_content'] = '<a href="'.  wp_kses( $_POST['fileExternal'], $allowed ) .'">DeMocast</a>';
            wp_update_post( $my_post );

            // re-hook this function
            add_action('save_post', array( &$this, 'savePost' ));*/







        }else{
            if(!empty($_FILES['file']['name'])) {

                //CONFIGURA UM CONJUNTO DE TIPOS DE ARQUIVOS SUPORTADOS PARA UPLOAD
                $supported_types = array('audio/mp4', 'audio/mpeg');

                if (!file_exists(WP_CONTENT_DIR . '/uploads/podcast')) {
                    mkdir(WP_CONTENT_DIR . '/uploads/podcast', 0755, false);
                }

                $destinoFisico =  WP_CONTENT_DIR . '/uploads/podcast';
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
                    update_post_meta($postId, "urlArquivo", $destinho_url.$nomeArquivo);
                    update_post_meta($postId, "dirArquivo", $destinoFisico.'/'.$nomeArquivo);
                    update_post_meta($postId, "nomeArquivo", $nomeArquivo);
                    update_post_meta($postId, "extArquivo", $extensaoArquivo);

                    //wp_update_post(array('post_content' => '<a href="'.  $destinho_url.$nomeArquivo .'">DeMocast</a>'));
                }
            }else{
                $errors->add('oops', 'Arquivo vazio');
            }
        }

        return $errors;
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE PODCAST
    ############################################################# */
    function postUpdatedMessages( $messages )
    {
        global $post, $post_ID;

        $messages['podcast'] = array(
            0  => '',
            1  => sprintf( 'PODCAST atualizado com sucesso - <a href="%s">Ver podcast</a>', esc_url( get_permalink($post_ID) )),
            2  => 'Campo personalizado ATUALIZADO.',
            3  => 'Campo personalizado DELETADO.',
            4  => 'PODCAST Atualizado',
            5  => isset($_GET['revision']) ? sprintf( 'PODCAST restaurado de %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( 'PODCAST publicado com sucesso - <a href="%s">Ver PODCAST</a>', esc_url( get_permalink($post_ID) ) ),
            7  => 'PODCAST salvo.',
            8  => sprintf( 'PODCAST enviado. <a target="_blank" href="%s">Ver PODCAST</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9  => sprintf( __('PODCAST agendando para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver PODCAST</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Rascunho do podcast atualizado. <a target="_blank" href="%s">Ver PODCAST</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
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
            'id'     => 'lista_podcast',
            'title'  => 'Podcasts',
            'href'   => admin_url() .'edit.php?post_type=podcast'
        ));
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE ARQUIVO
    ############################################################# */
    function adminHead() {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'podcast' ){
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
            #menu-posts-podcast .wp-menu-image {
                background: url(<?php echo get_template_directory_uri() . '/functions/ClassPostTypePodcast/ClassPostTypePodcast.png' ?>) no-repeat 6px -17px !important;
            }
            #menu-posts-podcast:hover .wp-menu-image, #menu-posts-podcast.wp-has-current-submenu .wp-menu-image {
                background-position:6px 7px!important;
            }
        </style>
    <?php
    }

    /* #############################################################
    # CARREGA O PLUGIN FULLCALENDAR NA ARCHIVE PAGE
    ############################################################# */
    function wpEnqueueScripts()
    {
        if (is_post_type_archive('podcast')){
            $caminhoBase = get_template_directory_uri() . '/functions/ClassPostTypePodcast/jPlayer/';

            wp_enqueue_style( 'skinplayer', $caminhoBase.'skin/blue.monday/jplayer.blue.monday.css' );
            wp_enqueue_script('jquery2', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js');
            wp_enqueue_script( 'jsplayer', $caminhoBase.'js/jquery.jplayer.min.js' );
        }
    }
}
$ClassPostTypePodcast = new ClassPostTypePodcast();
?>
