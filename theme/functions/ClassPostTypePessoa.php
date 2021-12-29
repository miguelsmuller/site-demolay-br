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

class ClassPostTypePessoa
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

        add_action( 'lotacao_add_form_fields',
            array( &$this, 'lotacao_add_form_fields' ), 10, 2
        );
        add_action( 'lotacao_edit_form_fields',
            array( &$this, 'lotacao_edit_form_fields' ), 10, 2
        );
        add_action( 'edited_lotacao',
            array( &$this, 'save_lotacao' ), 10, 2
        );
        add_action( 'create_lotacao',
            array( &$this, 'save_lotacao' ), 10, 2
        );

        add_action( 'wp_before_admin_bar_render',
            array( &$this, 'wpBeforeAdminBarEender' )
        );

        add_filter( 'manage_edit-pessoa_columns',
            array( &$this, 'manageEditColumns' )
        );
        add_action( 'manage_pessoa_posts_custom_column',
            array( &$this, 'managePostsCustomColumn' )
        );
        add_filter( 'manage_edit-pessoa_sortable_columns',
            array( &$this, 'manageEditSortableColumns' )
        );

        add_filter( 'restrict_manage_posts',
            array( &$this, 'restrictManagePosts' )
        );
        add_filter( 'parse_query',
            array( &$this, 'parseQuery' )
        );
    }

    /* #############################################################
    # CRIA O POST TYPE DO EVENTO
    ############################################################# */
    function initPostType()
    {
        register_post_type( 'pessoa',
            array(
                'labels' => array(
                    'name'               => 'Pessoas',
                    'singular_name'      => 'Pessoa',
                    'add_new'            => 'Adicionar Pessoa',
                    'add_new_item'       => 'Adicionar Pessoa',
                    'edit'               => 'Editar',
                    'edit_item'          => 'Editar Pessoa',
                    'new_item'           => 'Nova Pessoa',
                    'view'               => 'Ver',
                    'view_item'          => 'Ver Pessoa',
                    'search_items'       => 'Buscar Pessoa',
                    'not_found'          => 'Nenhuma pessoa encontrado',
                    'not_found_in_trash' => 'Nenhuma pessoa encontrado na lixeira',
                    'parent'             => 'Pessoas'
                ),

                'hierarchical'    => false,
                'public'          => true,
                'query_var'       => true,
                'rewrite'         => array('slug' => 'estrutura/%lotacao%', 'with_front' => false),
                'menu_position'   => null,
                'supports'        => array( 'editor', 'title','thumbnail' ),
                'has_archive'     => true,
                'capability_type' => 'post',
            )
        );

        register_taxonomy('lotacao',array('pessoa'),
            array(
                'labels'  => array(
                    'name'              => _x( 'Departamento', 'taxonomy general name' ),
                    'singular_name'     => _x( 'Derpatamentos', 'taxonomy singular name' ),
                    'search_items'      =>  __( 'Buscar departamento' ),
                    'all_items'         => __( 'Todas os departamentos' ),
                    'parent_item'       => __( 'Departamento Pai' ),
                    'parent_item_colon' => __( 'Departamento Pai:' ),
                    'edit_item'         => __( 'Editar departamento' ),
                    'update_item'       => __( 'Atualizar Departamento' ),
                    'add_new_item'      => __( 'Adicionar novo Departamento' ),
                    'new_item_name'     => __( 'New Tag Name' )
                ),
                'public'        => true,
                'hierarchical'  => true,
                'show_ui'       => true,
                'query_var'     => true,
                'show_tagcloud' => false,
                'rewrite'       => array( 'slug' => 'estrutura', 'with_front' => false),
        ));
    }
    function post_type_link($post_link, $id = 0)
    {
        $post = get_post($id);

        if ( is_wp_error($post) || 'pessoa' != $post->post_type || empty($post->post_name) )
            return $post_link;

        $terms = get_the_terms($post->ID, 'lotacao');

        if( is_wp_error($terms) || !$terms ) {
            $lotacao = 'desalocado';
        }
        else {
            $lotacao_obj = array_pop($terms);
            $lotacao = $lotacao_obj->slug;
        }

        return home_url(user_trailingslashit( "estrutura/$lotacao/$post->post_name" ));
    }

    function init() {
        add_rewrite_rule( '^estrutura$', 'index.php?post_type=pessoa', 'top' );
    }

    /* #############################################################
    # ADICIONA METABOX ESPECIFICA DE EVENTOS
    ############################################################# */
    function addMetaBoxes()
    {
        add_meta_box( 'metaBox', 'Informações', array( &$this, 'functionMetabox' ), 'pessoa', 'normal', 'high' );
        add_meta_box( 'metaBoxPeso', 'Informações', array( &$this, 'functionMetaboxPeso' ), 'pessoa', 'side', 'default' );
    }
    function functionMetaboxPeso()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        $pesoPessoa = isset( $values['pesoPessoa'] ) ? $values['pesoPessoa'][0] : '0';

        wp_nonce_field('nonce_action', 'nonce_name');
    ?>
    <div id="extrafields">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="pesoPessoa">Peso: </label></th>
                    <td>
                        <select id="pesoPessoa" name="pesoPessoa">
                            <option value="0"<?php selected( $pesoPessoa, '0')?>>Primeiro</option>
                            <option value="1"<?php selected( $pesoPessoa, '1')?>>Segundo</option>
                            <option value="2"<?php selected( $pesoPessoa, '2')?>>Terceiro</option>
                            <option value="3"<?php selected( $pesoPessoa, '3')?>>Membro</option>
                            <option value="4"<?php selected( $pesoPessoa, '4')?>>Colaborador</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
    }
    function functionMetabox()
    {
        global $post;
        $values = get_post_custom( $post->ID );

        $cid = isset( $values['cid'] ) ? $values['cid'][0] : '';
        $mail = isset( $values['mail'] ) ? $values['mail'][0] : '';
        $liberar_contato = isset( $values['liberar_contato'] ) ? esc_attr( $values['liberar_contato'][0] ) : '';
        $usar_mail_sisdm = isset( $values['usar_mail_sisdm'] ) ? esc_attr( $values['usar_mail_sisdm'][0] ) : '';
        $mostrarTitulo = isset( $values['mostrarTitulo'] ) ? esc_attr( $values['mostrarTitulo'][0] ) : '';

        wp_nonce_field('nonce_action', 'nonce_name');
    ?>
        <div id="extrafields">
        <h4>INFORMAÇÕES COMPLEMENTARES</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="cid">CID: </label></th>
                    <td>
                        <input type="text" name="cid" id="cid" value="<?php echo $cid; ?>" class="widefat" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="mostrarTitulo">Mostrar Título: </label></th>
                    <td>
                        <input type="checkbox" id="mostrarTitulo" name="mostrarTitulo" <?php checked( $mostrarTitulo, 'on' ); ?> />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="mail">E-Mail: </label></th>
                    <td>
                        <input type="text" name="mail" id="mail" value="<?php echo $mail; ?>" class="widefat" />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="liberar_contato">Liberar Contato: </label></th>
                    <td>
                        <input type="checkbox" id="liberar_contato" name="liberar_contato" <?php checked( $liberar_contato, 'on' ); ?> />
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="usar_mail_sisdm">Usar mail do SISDM: </label></th>
                    <td>
                        <input type="checkbox" id="usar_mail_sisdm" name="usar_mail_sisdm" <?php checked( $usar_mail_sisdm, 'on' ); ?> />
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
        if (get_post_type($post_id) !== 'pessoa')
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
        if( isset( $_POST['cid'] ) )
            update_post_meta( $post_id, 'cid', wp_kses( $_POST['cid'], $allowed ) );

        if( isset( $_POST['mail'] ) )
            update_post_meta( $post_id, 'mail', wp_kses( $_POST['mail'], $allowed ) );

        if( isset( $_POST['pesoPessoa'] ) )
            update_post_meta( $post_id, 'pesoPessoa', wp_kses( $_POST['pesoPessoa'], $allowed ) );

        $chk = isset( $_POST['mostrarTitulo'] ) && $_POST['mostrarTitulo'] ? 'on' : 'off';
                update_post_meta( $post_id, 'mostrarTitulo', $chk );

        $chk = isset( $_POST['liberar_contato'] ) && $_POST['liberar_contato'] ? 'on' : 'off';
                update_post_meta( $post_id, 'liberar_contato', $chk );

        $chk = isset( $_POST['usar_mail_sisdm'] ) && $_POST['usar_mail_sisdm'] ? 'on' : 'off';
                update_post_meta( $post_id, 'usar_mail_sisdm', $chk );
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE PESSOA
    ############################################################# */
    function postUpdatedMessages( $messages )
    {
        global $post, $post_ID;

        $messages['pessoa'] = array(
            0  => '',
            1  => sprintf( 'PESSOA atualizado com sucesso - <a href="%s">Ver pessoa</a>', esc_url( get_permalink($post_ID) )),
            2  => 'Campo personalizado ATUALIZADO.',
            3  => 'Campo personalizado DELETADO.',
            4  => 'PESSOA Atualizado',
            5  => isset($_GET['revision']) ? sprintf( 'PESSOA restaurado de %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( 'PESSOA publicado com sucesso - <a href="%s">Ver PESSOA</a>', esc_url( get_permalink($post_ID) ) ),
            7  => 'PESSOA salvo.',
            8  => sprintf( 'PESSOA enviado. <a target="_blank" href="%s">Ver PESSOA</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9  => sprintf( __('PESSOA agendando para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver PESSOA</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Rascunho do PESSOA atualizado. <a target="_blank" href="%s">Ver PESSOA</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
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
            'id'     => 'lista_pessoa',
            'title'  => 'Pessoas',
            'href'   => admin_url() .'edit.php?post_type=pessoa'
        ));
    }

    /* #############################################################
    # COLOCA UNS CSS NO CABEÇALHO DA PAGINA
    ############################################################# */
    function adminHead()
    {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'pessoa' ){
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
            .column-cid {
                width: 60px;
            }
            #menu-posts-pessoa .wp-menu-image {
                background: url(<?php echo get_template_directory_uri() . '/functions/ClassPostTypePessoa/ClassPostTypePessoa.png' ?>) no-repeat 6px -17px !important;
            }
            #menu-posts-pessoa:hover .wp-menu-image, #menu-posts-pessoa.wp-has-current-submenu .wp-menu-image {
                background-position:6px 7px!important;
            }
        </style>
    <?php
    }

    function lotacao_add_form_fields() {
    ?>
        <div class="form-field">
            <label for="term_meta[liberar_contato]">Liberar Contato:</label>
            <input type="checkbox" id="term_meta[liberar_contato]" name="term_meta[liberar_contato]" />
        </div>
        <div class="form-field">
            <label for="term_meta[email_contato]">E-Mail Contato:</label>
            <input type="text" name="term_meta[email_contato]" id="term_meta[email_contato]" value="">
        </div>
        <div class="form-field">
            <label for="term_meta[site]">Site:</label>
            <input type="text" name="term_meta[site]" id="term_meta[site]" value="">
        </div>
    <?php
    }

    function lotacao_edit_form_fields($term) {
        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option( "taxonomy_$t_id" );
        echo $term_meta['liberar_contato'];
        $term_meta['liberar_contato'] = isset($term_meta['liberar_contato']) ? $term_meta['liberar_contato'] : false ;?>

        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[liberar_contato]">Liberar Contato:</label></th>
            <td style="text-align:left;">
                <input type="checkbox" id="term_meta[liberar_contato]" name="term_meta[liberar_contato]" <?php checked( $term_meta['liberar_contato'],'on' ); ?> />
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[email_contato]">Email de contato:</label></th>
            <td>
                <input type="text" name="term_meta[email_contato]" value="<?php echo esc_attr( $term_meta['email_contato'] ) ? esc_attr( $term_meta['email_contato'] ) : ''; ?>">
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[site]">Site:</label></th>
            <td>
                <input type="text" name="term_meta[site]" value="<?php echo esc_attr( $term_meta['site'] ) ? esc_attr( $term_meta['site'] ) : ''; ?>">
            </td>
        </tr>
    <?php
    }
    function save_lotacao( $term_id ) {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            $chk = isset( $_POST['term_meta']['liberar_contato'] ) && $_POST['term_meta']['liberar_contato'] ? 'on' : 'off';
            $term_meta['liberar_contato'] =  $chk;

            // Save the option array.
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }

    /* #############################################################
    # COLOCA NA WP-ADMIN-BAR UM LINK PARA CRIAR UM NOVO EVENTO
    ############################################################# */
    function wpBeforeAdminBarEender() {
        global $wp_admin_bar;
        $wp_admin_bar->add_menu( array(
            'parent' => 'new-content',
            'id'     => 'new-pessoa',
            'title'  => 'Pessoa',
            'href'   => admin_url( 'post-new.php?post_type=pessoa')
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
                if ($key=='title') // Put the Thumbnail column before the Author column
                    $new['cid'] = 'CID';

                if ($key=='date'){ // Put the Thumbnail column before the Author column
                    //$new['nome'] = 'Nome';
                    $new['lotacao'] = 'Departamento';
                }
            $new[$key] = $title;
        }
        //unset( $new['title'] );
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

        $values = get_post_custom( $post->ID );
        $cid = isset( $values['cid'] ) ? $values['cid'][0] : '';

        switch( $column ) {
            case 'cid' :
                if ( empty( $cid ) )
                    echo  '-';

                else
                    echo  '<a href="'. get_bloginfo('wpurl') .'/wp-admin/post.php?post='.$post->ID.'&action=edit"><strong>'.$cid.'</strong></a>';

                break;

            case 'lotacao' :

                $terms = get_the_terms( $post->ID, 'lotacao' );

                if ( !empty( $terms ) ) {
                    $out = array();
                    foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'lotacao' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'lotacao', 'display' ) )
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
        $columns['lotacao'] = 'lotacao';
        $columns['cid']     = 'cid';

        return $columns;
    }

    function restrictManagePosts()
    {
        $screen = get_current_screen();
        global $wp_query;
        if ( $screen->post_type == 'pessoa' ) {
            wp_dropdown_categories( array(
                'show_option_all' => 'Todos os Departamentos',
                'taxonomy'        => 'lotacao',
                'name'            => 'lotacao',
                'orderby'         => 'name',
                'selected'        => ( isset( $wp_query->query['lotacao'] ) ? $wp_query->query['lotacao'] : '' ),
                'hierarchical'    => false,
                'depth'           => 3,
                'show_count'      => false,
                'hide_empty'      => true,
            ) );
        }
    }
    function parseQuery( $query )
    {
        $qv = &$query->query_vars;
        if ( ( isset( $qv['lotacao'] ) ) && is_numeric( $qv['lotacao'] ) ) {
            $term = get_term_by( 'id', $qv['lotacao'], 'lotacao' );
            $qv['lotacao'] = $term->slug;
        }
    }
}
$ClassPostTypePessoa = new ClassPostTypePessoa();
?>
