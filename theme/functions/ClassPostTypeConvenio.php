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

class ClassPostTypeConvenio
{
    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct()
    {
        add_action('init',
            array( &$this, 'initPostType' )
        );
        add_filter('post_type_link',
            array( &$this, 'post_type_link' ), 10, 2
        );
        add_action( 'init',
            array( &$this, 'init' )
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

        add_filter( 'manage_edit-convenio_columns',
            array( &$this, 'manageEditColumns' )
        );
        add_action( 'manage_convenio_posts_custom_column',
            array( &$this, 'managePostsCustomColumn' )
        );
        add_filter( 'manage_edit-convenio_sortable_columns',
            array( &$this, 'manageEditSortableColumns' )
        );
    }

    /* #############################################################
    # CRIA O POST TYPE DO EVENTO
    ############################################################# */
    function initPostType()
    {
        register_post_type( 'convenio',
            array(
            'labels' => array(
                'name'               => 'Convenios',
                'singular_name'      => 'Convenio',
                'add_new'            => 'Adicionar novo convênio',
                'add_new_item'       => 'Adicionar novo convênio',
                'edit'               => 'Editar',
                'edit_item'          => 'Editar convênio',
                'new_item'           => 'Novo convênio',
                'view'               => 'Ver',
                'view_item'          => 'Ver convênio',
                'search_items'       => 'Buscar convênio',
                'not_found'          => 'Nenhuma convênio encontrado',
                'not_found_in_trash' => 'Nenhuma convênio encontrado na lixeira',
                'parent'             => 'Convênios'
                ),

            'hierarchical'    => false,
            'public'          => true,
            'query_var'       => true,
            'rewrite'         => array('slug' => 'parceiros/%categoria_convenio%', 'with_front' => false),
            'menu_position'   => null,
            'supports'        => array( 'title','editor','thumbnail' ),
            'has_archive'     => true,
            'capability_type' => 'post'
            )
        );

        register_taxonomy('categoria_convenio',array('convenio'),
            array(
                'labels'  => array(
                    'name'              => _x( 'Categorias dos Convenios', 'taxonomy general name' ),
                    'singular_name'     => _x( 'Categoria dos Convenios', 'taxonomy singular name' ),
                    'search_items'      =>  __( 'Buscar categoria' ),
                    'all_items'         => __( 'Todas as categorias' ),
                    'parent_item'       => __( 'Categoria Pai' ),
                    'parent_item_colon' => __( 'Categori Pai:' ),
                    'edit_item'         => __( 'Editar Categoria' ),
                    'update_item'       => __( 'Atualizar Categoria' ),
                    'add_new_item'      => __( 'Adicionar nova Categoria' ),
                    'new_item_name'     => __( 'New Tag Name' )
                    ),
                'public'        => true,
                'hierarchical'  => true,
                'show_ui'       => true,
                'query_var'     => true,
                'show_tagcloud' => false,
                'rewrite'       => array( 'slug' => 'parceiros', 'with_front' => false),
                ));

    }
    function post_type_link($post_link, $id = 0)
    {
        $post = get_post($id);

        if ( is_wp_error($post) || 'convenio' != $post->post_type || empty($post->post_name) )
            return $post_link;

        $terms = get_the_terms($post->ID, 'categoria_convenio');

        if( is_wp_error($terms) || !$terms ) {
            $grupo = 'desalocado';
        }
        else {
            $categoria_convenio_obj = array_pop($terms);
            $categoria_convenio = $categoria_convenio_obj->slug;
        }

        return home_url(user_trailingslashit( "parceiros/$categoria_convenio/$post->post_name" ));
    }

    function init() {
        add_rewrite_rule( '^parceiros$', 'index.php?post_type=convenio', 'top' );
    }

    /* #############################################################
    # ADICIONA METABOX ESPECIFICA DE EVENTOS
    ############################################################# */
    function addMetaBoxes()
    {
        add_meta_box( 'metaBox', 'Informações', array( &$this, 'functionMetabox' ), 'convenio', 'normal', 'high' );
    }
    function functionMetabox()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        $desconto          = isset( $values['desconto'] ) ? $values['desconto'][0] : '';
        $servico           = isset( $values['servico'] ) ? $values['servico'][0] : '';
        $publico           = isset( $values['publico'] ) ? $values['publico'][0] : '';
        $documentos        = isset( $values['documentos'] ) ? $values['documentos'][0] : '';
        $endereco_convenio = isset( $values['endereco_convenio'] ) ? $values['endereco_convenio'][0] : '';
        $telefone_convenio = isset( $values['telefone_convenio'] ) ? $values['telefone_convenio'][0] : '';
        $email_convenio    = isset( $values['email_convenio'] ) ? $values['email_convenio'][0] : '';
        $site_convenio     = isset( $values['site_convenio'] ) ? $values['site_convenio'][0] : '';
        $restrito_convenio = isset( $values['restrito_convenio'] ) ? esc_attr( $values['restrito_convenio'][0] ) : '';

        wp_nonce_field('nonce_action', 'nonce_name');
    ?>
        <div id="extrafields">
        <h4>INFORMAÇÕES COMPLEMENTARES</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="restrito_convenio">Visível apenas para usuário logados: </label></th>
                    <td>
                        <input type="checkbox" id="restrito_convenio" name="restrito_convenio" <?php checked( $restrito_convenio, 'on' ); ?> />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="url">Desconto: </label></th>
                    <td>
                        <input type="text" name="desconto" id="desconto" value="<?php echo $desconto; ?>" class="widefat" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="url">Serviços: </label></th>
                    <td>
                        <input type="text" name="servico" id="servico" value="<?php echo $servico; ?>" class="widefat" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="url">Público Alvo: </label></th>
                    <td>
                        <input type="text" name="publico" id="publico" value="<?php echo $publico; ?>" class="widefat" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="url">Documentos: </label></th>
                    <td>
                        <textarea class="widefat" rows="10" cols="20" id="documentos" name="documentos"><?php echo $documentos; ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>

        <h4>LOCALIZAÇÃO</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="endereco_convenio">Endereço:</label></th>
                    <td>
                        <textarea class="widefat" rows="4" cols="20" id="endereco_convenio" name="endereco_convenio"><?php echo $endereco_convenio; ?></textarea>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="telefone_convenio">Telefone:</label></th>
                    <td>
                        <input type="text" id="telefone_convenio" name="telefone_convenio" value="<?php echo $telefone_convenio; ?>" class="regular-text">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="email_convenio">E-Mail:</label></th>
                    <td>
                        <input type="text" id="email_convenio" name="email_convenio" value="<?php echo $email_convenio; ?>" class="regular-text">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="site_convenio">Site: </label></th>
                    <td>
                        <input type="text" id="site_convenio" name="site_convenio" value="<?php echo $site_convenio; ?>" class="regular-text">
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <?php
    }

    /* #############################################################
    # FUNÇÃO PARA SALVAMENTO EXTRA DOS NOVOS CAMPOS
    ############################################################# */
    function savePost( $post_id )
    {
        if (get_post_type($post_id) !== 'convenio')
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

        // Provavelmente é uma boa idéia para se certificar de seus dados é definido
        if( isset( $_POST['desconto'] ) )
            update_post_meta( $post_id, 'desconto', wp_kses( $_POST['desconto'], $allowed ) );

        if( isset( $_POST['servico'] ) )
            update_post_meta( $post_id, 'servico', wp_kses( $_POST['servico'], $allowed ) );

        if( isset( $_POST['publico'] ) )
            update_post_meta( $post_id, 'publico', wp_kses( $_POST['publico'], $allowed ) );

        if( isset( $_POST['documentos'] ) )
            update_post_meta( $post_id, 'documentos', wp_kses( $_POST['documentos'], $allowed ) );

        if( isset( $_POST['endereco_convenio'] ) )
            update_post_meta( $post_id, 'endereco_convenio', wp_kses( $_POST['endereco_convenio'], $allowed ) );

        if( isset( $_POST['telefone_convenio'] ) )
            update_post_meta( $post_id, 'telefone_convenio', wp_kses( $_POST['telefone_convenio'], $allowed ) );

        if( isset( $_POST['email_convenio'] ) )
            update_post_meta( $post_id, 'email_convenio', wp_kses( $_POST['email_convenio'], $allowed ) );

        if( isset( $_POST['site_convenio'] ) )
            update_post_meta( $post_id, 'site_convenio', wp_kses( $_POST['site_convenio'], $allowed ) );

        $chk = isset( $_POST['restrito_convenio'] ) && $_POST['restrito_convenio'] ? 'on' : 'off';
                update_post_meta( $post_id, 'restrito_convenio', $chk );
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE CONVENIO
    ############################################################# */
    function postUpdatedMessages( $messages )
    {
        global $post, $post_ID;

        $messages['convenio'] = array(
            0  => '',
            1  => sprintf( 'CONVENIO atualizado com sucesso - <a href="%s">Ver convenio</a>', esc_url( get_permalink($post_ID) )),
            2  => 'Campo personalizado ATUALIZADO.',
            3  => 'Campo personalizado DELETADO.',
            4  => 'CONVENIO Atualizado',
            5  => isset($_GET['revision']) ? sprintf( 'CONVENIO restaurado de %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( 'CONVENIO publicado com sucesso - <a href="%s">Ver CONVENIO</a>', esc_url( get_permalink($post_ID) ) ),
            7  => 'CONVENIO salvo.',
            8  => sprintf( 'CONVENIO enviado. <a target="_blank" href="%s">Ver CONVENIO</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9  => sprintf( __('CONVENIO agendando para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver CONVENIO</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Rascunho do CONVENIO atualizado. <a target="_blank" href="%s">Ver CONVENIO</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
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
            'id'     => 'lista_convenio',
            'title'  => 'Convênios',
            'href'   => admin_url() .'edit.php?post_type=convenio'
        ));
    }

    /* #############################################################
    # COLOCA UNS CSS NO CABEÇALHO DA PAGINA
    ############################################################# */
    function adminHead()
    {
        ?>
        <style type="text/css" media="screen">
            .column-cid {
                width: 60px;
            }
            #menu-posts-convenio .wp-menu-image {
                background: url(<?php echo get_template_directory_uri() . '/functions/ClassPostTypeConvenio/ClassPostTypeConvenio.png' ?>) no-repeat 6px -17px !important;
            }
            #menu-posts-convenio:hover .wp-menu-image, #menu-posts-convenio.wp-has-current-submenu .wp-menu-image {
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
            'id'     => 'new-convenio',
            'title'  => 'Convênio',
            'href'   => admin_url( 'post-new.php?post_type=convenio')
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
                if ($key=='date') { // Put the Thumbnail column before the Author column
                    $new['restrito_convenio'] = 'Restrito';
                    $new['categoria_convenio'] = 'Categoria';
                    $new['site_convenio'] = 'Site';
                }
            $new[$key] = $title;
        }
        return $new;

        // $columns['cid'] = 'cid';
        // $columns['lotacao'] = 'Departamento';
        // unset( $columns['comments'] );
        // unset( $columns['date'] );

        // return $columns;
    }
    function managePostsCustomColumn ($column)
    {
        global $post;

        $values  = get_post_custom( $post->ID );
        $site    = isset( $values['site_convenio'] ) ? $values['site_convenio'][0] : '-';
        $privado = isset( $values['restrito_convenio'] ) ? esc_attr( $values['restrito_convenio'][0] ) : FALSE;
        $privado = ($privado == TRUE) ? 'SIM' : 'NÃO';

        switch( $column ) {

            case 'restrito_convenio' :
                echo $privado;
                break;

            case 'site_convenio' :
                if ( empty( $site ) )
                    echo  '-';
                else
                    echo '<a href="'.$site.'" target="_blank">'.$site.'</a>';

                break;

            case 'categoria_convenio' :

                $terms = get_the_terms( $post_id, 'categoria_convenio' );

                if ( !empty( $terms ) ) {
                    $out = array();
                    foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'categoria_convenio' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'categoria_convenio', 'display' ) )
                        );
                    }
                    echo join( ', ', $out );
                }
                else {
                    _e( 'Não Categorizado' );
                }

                break;

            default :
                break;
        }
    }
    function manageEditSortableColumns( $columns ) {
        $columns['restrito_convenio']  = 'restrito_convenio';
        $columns['categoria_convenio'] = 'categoria_convenio';

        return $columns;
    }
}
$ClassPostTypeConvenio = new ClassPostTypeConvenio();
?>
