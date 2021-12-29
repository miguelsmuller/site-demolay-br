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

class ClassPostTypeDestaque
{
    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct()
    {
        add_action('init',
            array( &$this, 'initPostType' )
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

        add_action( 'admin_head',
            array( &$this, 'adminHead' )
        );

        add_filter( 'admin_bar_menu', array( &$this, 'adminBarMenu' ), 100 );

        add_action( 'wp_before_admin_bar_render',
            array( &$this, 'wpBeforeAdminBarEender' )
        );

        add_filter( 'manage_edit-destaque_columns',
            array( &$this, 'manageEditColumns' )
        );
        add_action( 'manage_destaque_posts_custom_column',
            array( &$this, 'managePostsCustomColumn' )
        );
        add_filter( 'manage_edit-destaque_sortable_columns',
            array( &$this, 'manageEditSortableColumns' )
        );
    }

    /* #############################################################
    # CRIA O POST TYPE DO EVENTO
    ############################################################# */
    function initPostType()
    {
        register_post_type( 'destaque',
            array(
                'labels' => array(
                    'name'               => 'Destaques',
                    'singular_name'      => 'Destaque',
                    'add_new'            => 'Adicionar novo destaque',
                    'add_new_item'       => 'Adicionar novo destaque',
                    'edit'               => 'Editar',
                    'edit_item'          => 'Editar destaque',
                    'new_item'           => 'Novo destaque',
                    'view'               => 'Ver',
                    'view_item'          => 'Ver destaque',
                    'search_items'       => 'Buscar destaque',
                    'not_found'          => 'Nenhuma destaque encontrado',
                    'not_found_in_trash' => 'Nenhuma destaque encontrado na lixeira',
                    'parent'             => 'Destaques',
                    'menu_name'          => 'Destaques'
                ),

                'public' => false,
                'publicly_queryable' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'query_var' => true,
                'rewrite' => array('slug'=>'','with_front'=>false),
                'capability_type' => 'post',
                'has_archive' => true,
                'hierarchical' => false,
                'menu_position' => null,
                'supports' => array('title','thumbnail','excerpt')
            )
        );
    }

    /* #############################################################
    # ADICIONA METABOX ESPECIFICA DE EVENTOS
    ############################################################# */
    function addMetaBoxes()
    {
        add_meta_box( 'metaBox', 'Informações', array( &$this, 'functionMetabox' ), 'destaque', 'normal', 'high' );
    }
    function functionMetabox()
    {
        global $post;

        wp_nonce_field('nonce_action', 'nonce_name');

        $postOptions = get_post_custom( $post->ID );
        $url         = isset( $postOptions['url'] ) ? $postOptions['url'][0] : '';
        $new_window  = isset( $postOptions['new_window']) ? esc_attr( $postOptions['new_window'][0] ) : FALSE;
        $inativar    = isset( $postOptions['inativar'] ) ? esc_attr( $postOptions['inativar'][0] ) : FALSE;
    ?>
        <p>
            <label for="url">Link de destino: </label>
            <input type="text" name="url" id="url" value="<?php echo $url; ?>" class="widefat" />
        </p>

        <p>
            <input type="checkbox" id="new_window" name="new_window" <?php checked( $new_window, 'on' ); ?> />
            <label for="new_window">Abrir link em nova janela</label>
        </p>

        <p>
            <input  type="checkbox" id="inativar" name="inativar" <?php checked( $inativar, 'on' ); ?> />
            <label for="inativar">Inativar esse destaque</label>
        </p>
    <?php
    }

    /* #############################################################
    # FUNÇÃO PARA SALVAMENTO EXTRA DOS NOVOS CAMPOS
    ############################################################# */
    function savePost( $post_id )
    {
        if (get_post_type($post_id) !== 'destaque')
        return $post_id;

        // Antes de dar inicio ao salvamento precisamos verificar 3 coisas:
        // Verificar se a publicação é salva automaticamente
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        //Verificar o valor nonce criado anteriormente, e finalmente
        if( !isset( $_POST['nonce_name'] ) || !wp_verify_nonce($_POST['nonce_name'], 'nonce_action') ) return;
        //Verificar se o usuário atual tem acesso para salvar a pulicação
        if( !current_user_can( 'edit_post' ) ) return;

        // agora podemos realmente salvar os dados
        $allowed = array(
            'a' => array( // em permitir que a tag
                'href' => array() // e os âncoras só pode ter atributo href
            )
        );

        if( isset( $_POST['url'] ) )
            update_post_meta( $post_id, 'url', wp_kses( $_POST['url'], $allowed ) );

        $chk = isset( $_POST['new_window'] ) && $_POST['new_window'] ? 'on' : 'off';
            update_post_meta( $post_id, 'new_window', $chk );

        $chk = isset( $_POST['inativar'] ) && $_POST['inativar'] ? 'on' : 'off';
            update_post_meta( $post_id, 'inativar', $chk );
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE DESTAQUE
    ############################################################# */
    function postUpdatedMessages( $messages )
    {
        global $post, $post_ID;

        $messages['destaque'] = array(
            0  => '',
            1  => sprintf( 'DESTAQUE atualizado com sucesso', esc_url( get_permalink($post_ID) )),
            2  => 'Campo personalizado ATUALIZADO.',
            3  => 'Campo personalizado DELETADO.',
            4  => 'DESTAQUE Atualizado',
            5  => isset($_GET['revision']) ? sprintf( 'DESTAQUE restaurado de %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( 'DESTAQUE publicado com sucesso', esc_url( get_permalink($post_ID) ) ),
            7  => 'DESTAQUE salvo.',
            8  => sprintf( 'DESTAQUE enviado', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9  => sprintf( __('DESTAQUE agendando para: <strong>%1$s</strong>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Rascunho do evento atualizado'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
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
            'id'     => 'lista_destaque',
            'title'  => 'Destaques',
            'href'   => admin_url() .'edit.php?post_type=destaque'
        ));
    }

    /* #############################################################
    # COLOCA UNS CSS NO CABEÇALHO DA PAGINA
    ############################################################# */
    function adminHead()
    {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'destaque' ){
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
            .column-featured_image {
                width: 40px;
            }
            #menu-posts-destaque .wp-menu-image {
                background: url(<?php echo get_template_directory_uri() . '/functions/ClassPostTypeDestaque/ClassPostTypeDestaque.png' ?>) no-repeat 6px -17px !important;
            }
            #menu-posts-destaque:hover .wp-menu-image, #menu-posts-destaque.wp-has-current-submenu .wp-menu-image {
                background-position:6px 7px!important;
            }
        </style>
    <?php
    }

    /* #############################################################
    # COLOCA NA WP-ADMIN-BAR UM LINK PARA CRIAR UM NOVO EVENTO
    ############################################################# */
    function wpBeforeAdminBarEender() {
        global $wp_admin_bar;
        $wp_admin_bar->add_menu( array(
            'parent' => 'new-content',
            'id' => 'new-destaque',
            'title' => 'Destaque',
            'href' => admin_url( 'post-new.php?post_type=destaque')
        ) );
    }

    /* #############################################################
    # ORGANIZAÇÃO DA GRID NO PAINEL DE ADMINITRAÇÃO
    ############################################################# */
    function manageEditColumns($columns)
    {
        global $post;

        $new = array();
        foreach($columns as $key => $title) {
            if ( $post->post_type == 'destaque' ){
                if ($key=='title') // Put the Thumbnail column before the Author column
                    $new['featured_image'] = 'Slide';
                if ($key=='date') // Put the Thumbnail column before the Author column
                    $new['inativo'] = 'Inativo';
            }
            $new[$key] = $title;
        }
    return $new;
    }
    function get_thumbnail($post_ID) {
        $post_thumbnail_id = get_post_thumbnail_id($post_ID);
        if ($post_thumbnail_id) {
            $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
            return $post_thumbnail_img[0];
        }
    }
    function managePostsCustomColumn ($column)
    {
        global $post;

        $values              = get_post_custom( $post->ID );
        $inativo             = isset( $values['inativar'] ) ? esc_attr( $values['inativar'][0] ) : FALSE;
        $inativo             = ($inativo == 'on') ? 'SIM' : 'NÃO';

        switch( $column ) {
            case 'featured_image' :
                $post_featured_image = $this->get_thumbnail($post->ID);
                if ($post_featured_image) {
                    echo '<a href="'. get_bloginfo('wpurl') .'/wp-admin/post.php?post='.$post->ID.'&action=edit"><img width="42px" height="42px" src="' . $post_featured_image . '" /></a>';
                }
                break;

            case 'inativo' :
                echo $inativo;
                break;

            default :
                break;
        }

    }
    function manageEditSortableColumns( $columns ) {
        $columns['inativo']           = 'inativo';

        return $columns;
    }
}
$ClassPostTypeDestaque = new ClassPostTypeDestaque();
?>
