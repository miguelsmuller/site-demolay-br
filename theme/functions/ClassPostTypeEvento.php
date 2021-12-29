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
class ClassPostTypeEventos
{
    protected $quantEventosIndex;

    /* #############################################################
    # FUNÇÃO CONSTRUTURA DA CLASSE
    ############################################################# */
    public function __construct()
    {
        $default = $this->setDefaults();

        $options                 = get_option( 'configEvento' );
        $this->quantEventosIndex = isset( $options['quantEventosIndex'] ) ? $options['quantEventosIndex'] : $default['quantEventosIndex'];

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

        add_action( 'eventoCategoria_add_form_fields',
            array( &$this, 'eventoCategoriaAddFormFields' ) , 10, 2
        );
        add_action( 'eventoCategoria_edit_form_fields',
            array( &$this, 'eventoCategoriaEditFormFields' ) , 10, 2
        );
        add_action( 'edited_eventoCategoria',
            array( &$this, 'saveEventoCategoria' ) , 10, 2
        );
        add_action( 'create_eventoCategoria',
            array( &$this, 'saveEventoCategoria' ) , 10, 2
        );

        add_action('admin_init',
            array( &$this, 'formAdminEvento' )
        );
        add_action('admin_menu',
            array( &$this, 'addSubMenu' )
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

        add_action('wp_enqueue_scripts',
            array( &$this, 'wpEnqueueScripts')
        );
        add_action('admin_enqueue_scripts',
            array( &$this, 'admin_enqueue_scripts')
        );


        add_filter( 'manage_edit-evento_columns',
            array( &$this, 'manageEditColumns' )
        );
        add_action( 'manage_evento_posts_custom_column',
            array( &$this, 'managePostsCustomColumn' )
        );
        add_filter( 'manage_edit-evento_sortable_columns',
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
    # GET PROTECTED
    ############################################################# */
    public function getQuantEventosIndex( $retorno = 'var' )
    {
        if ($retorno == 'echo'){
            echo $this->quantEventosIndex;
        } else {
            return ($this->quantEventosIndex);
        }
    }

    /* #############################################################
    # SETA OS VALORES PADRÕES PARA A CLASSE
    ############################################################# */
    function setDefaults()
    {
        $defaults = array(
            'quantEventosIndex' => 6
        );
        return $defaults;
    }

    /* #############################################################
    # CRIA O POST TYPE DO EVENTO
    ############################################################# */
    function initPostType()
    {
        register_post_type( 'evento',
            array(
                'labels' => array(
                    'name'               => 'Eventos',
                    'singular_name'      => 'Evento',
                    'add_new'            => 'Adicionar novo evento',
                    'add_new_item'       => 'Adicionar novo evento',
                    'edit'               => 'Editar',
                    'edit_item'          => 'Editar evento',
                    'new_item'           => 'Novo evento',
                    'view'               => 'Ver',
                    'view_item'          => 'Ver evento',
                    'search_items'       => 'Buscar evento',
                    'not_found'          => 'Nenhuma evento encontrado',
                    'not_found_in_trash' => 'Nenhuma evento encontrado na lixeira',
                    'parent'             => 'Eventos',
                    'menu_name'          => 'Eventos'
                ),

                'hierarchical'    => false,
                'public'          => true,
                'query_var'       => true,
                'rewrite'         => array('slug' => 'eventos', 'with_front' => false),
                'menu_position'   => null,
                'supports'        => array( 'title','editor','thumbnail' ),
                'has_archive'     => true,
                'capability_type' => 'post'
            )
        );

        register_taxonomy('eventoCategoria',array('evento'),
            array(
                'labels'  => array(
                    'name'              => 'Cat. Eventos',
                    'singular_name'     => 'Cat. Evento',
                    'search_items'      => 'Buscar categorias de eventos',
                    'all_items'         => 'Categorias de eventos',
                    'parent_item'       => 'Categoria de evento pai',
                    'parent_item_colon' => 'Categoria de evento pai',
                    'edit_item'         => 'Editar categoria de evento',
                    'update_item'       => 'Atualizar categoria de evento',
                    'add_new_item'      => 'Adicionar nova categoria de evento'
                ),
                'public'        => false,
                'hierarchical'  => true,
                'show_ui'       => true,
                'query_var'     => true,
                'show_tagcloud' => false,
                'rewrite'       => array( 'slug' => 'evento-de', 'with_front' => false ),
        ));

        register_taxonomy('eventoTemporario',array('evento'),
            array(
                'labels'  => array(
                    'name'              => 'Cat. temporárias',
                    'singular_name'     => 'Cat. temporária',
                    'search_items'      => 'Buscar categorias temporárias',
                    'all_items'         => 'Categorias temporárias',
                    'parent_item'       => 'Categoria temporária pai',
                    'parent_item_colon' => 'Categoria temporária pai',
                    'edit_item'         => 'Editar categoria temporária',
                    'update_item'       => 'Atualizar categoria temporária',
                    'add_new_item'      => 'Adicionar nova categoria temporária'
                ),
                'public'        => false,
                'hierarchical'  => true,
                'show_ui'       => true,
                'query_var'     => true,
                'show_tagcloud' => false,
                'rewrite'       => array( 'slug' => 'evento-quando', 'with_front' => false ),
        ));
    }

    /* #############################################################
    # ADICIONA METABOX ESPECIFICA DE EVENTOS
    ############################################################# */
    function addMetaBoxes()
    {
        add_meta_box( 'metaBoxEventos', 'Informações do Evento', array( &$this, 'functionMetabox' ), 'evento', 'normal', 'high' );
    }
    function functionMetabox()
    {
        global $post;

        wp_nonce_field('nonce_action', 'nonce_name');

        $postOptions       = get_post_custom( $post->ID );
        $dInicio        = isset( $postOptions['dInicio'] ) ? date("d/m/Y",$postOptions['dInicio'][0]) : date('d/m/Y');
        $dFim           = isset( $postOptions['dFim'] ) ? date("d/m/Y",$postOptions['dFim'][0]) : date('d/m/Y');
        $siteEvento     = isset( $postOptions['siteEvento'] ) ? $postOptions['siteEvento'][0] : '';
        $emailEvento    = isset( $postOptions['emailEvento'] ) ? $postOptions['emailEvento'][0] : '';
        $localEvento    = isset( $postOptions['localEvento'] ) ? $postOptions['localEvento'][0] : '';
        $enderecoEvento = isset( $postOptions['enderecoEvento'] ) ? $postOptions['enderecoEvento'][0] : '';
        $cidadeEvento   = isset( $postOptions['cidadeEvento'] ) ? $postOptions['cidadeEvento'][0] : '';
        $estadoEvento   = isset( $postOptions['estadoEvento'] ) ? $postOptions['estadoEvento'][0] : '';
        $latLogEvento   = isset( $postOptions['latLogEvento'] ) ? $postOptions['latLogEvento'][0] : '';
    ?>
    <div id="extrafields">
        <h4>DATA DO EVENTO</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="dInicio">D. Inicio: </label></th>
                    <td>
                        <input type="text" name="dInicio" id="dInicio" value="<?php echo $dInicio ?>" class="regular-text datepicker" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="dFim">D. Término: </label></th>
                    <td>
                        <input type="text" name="dFim" id="dFim" value="<?php echo $dFim; ?>" class="regular-text datepicker" />
                    </td>
                </tr>
            </tbody>
        </table>

        <h4>INFORMAÇÕES COMPLEMENTARES</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="siteEvento">Site do Evento: </label></th>
                    <td>
                        <input name="siteEvento" type="text" id="siteEvento" value="<?php echo $siteEvento; ?>" class="regular-text">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="emailEvento">E-Mail: </label></th>
                    <td>
                        <input name="emailEvento" type="text" id="emailEvento" value="<?php echo $emailEvento; ?>" class="regular-text">
                    </td>
                </tr>
            </tbody>
        </table>

        <h4>LOCALIZAÇÃO DO EVENTO</h4>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label for="localEvento">Local do Evento</label></th>
                    <td>
                        <input name="localEvento" type="text" id="localEvento" value="<?php echo $localEvento; ?>" class="regular-text">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="enderecoEvento">Endereço:</label></th>
                    <td>
                        <input name="enderecoEvento" type="text" id="enderecoEvento" value="<?php echo $enderecoEvento; ?>" class="widefat">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="cidadeEvento">Cidade:</label></th>
                    <td>
                        <input name="cidadeEvento" type="text" id="cidadeEvento" value="<?php echo $cidadeEvento; ?>" class="regular-text">
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="estadoEvento">Estado:</label></th>
                    <td>
                        <select id="estadoEvento" name="estadoEvento">
                            <option value="AC"<?php selected( $estadoEvento, 'AC')?>>Acre</option>
                            <option value="AL"<?php selected( $estadoEvento, 'AL')?>>Alagoas</option>
                            <option value="AP"<?php selected( $estadoEvento, 'AP')?>>Amapá</option>
                            <option value="AM"<?php selected( $estadoEvento, 'AM')?>>Amazonas</option>
                            <option value="BA"<?php selected( $estadoEvento, 'BA')?>>Bahia</option>
                            <option value="CE"<?php selected( $estadoEvento, 'CE')?>>Ceará</option>
                            <option value="DF"<?php selected( $estadoEvento, 'DF')?>>Distrito Federal</option>
                            <option value="ES"<?php selected( $estadoEvento, 'ES')?>>Espírito Santo</option>
                            <option value="GO"<?php selected( $estadoEvento, 'GO')?>>Goiás</option>
                            <option value="MA"<?php selected( $estadoEvento, 'MA')?>>Maranhão</option>
                            <option value="MT"<?php selected( $estadoEvento, 'MT')?>>Mato Grosso</option>
                            <option value="MS"<?php selected( $estadoEvento, 'MS')?>>Mato Grosso do Sul</option>
                            <option value="MG"<?php selected( $estadoEvento, 'MG')?>>Minas Gerais</option>
                            <option value="PA"<?php selected( $estadoEvento, 'PA')?>>Pará</option>
                            <option value="PB"<?php selected( $estadoEvento, 'PB')?>>Paraíba</option>
                            <option value="PR"<?php selected( $estadoEvento, 'PR')?>>Paraná</option>
                            <option value="PE"<?php selected( $estadoEvento, 'PE')?>>Pernambuco</option>
                            <option value="PI"<?php selected( $estadoEvento, 'PI')?>>Piauí</option>
                            <option value="RJ"<?php selected( $estadoEvento, 'RJ')?>>Rio de Janeiro</option>
                            <option value="RN"<?php selected( $estadoEvento, 'RN')?>>Rio Grande do Norte</option>
                            <option value="RS"<?php selected( $estadoEvento, 'RS')?>>Rio Grande do Sul</option>
                            <option value="RO"<?php selected( $estadoEvento, 'RO')?>>Rondônia</option>
                            <option value="RR"<?php selected( $estadoEvento, 'RR')?>>Roraima</option>
                            <option value="SC"<?php selected( $estadoEvento, 'SC')?>>Santa Catarina</option>
                            <option value="SP"<?php selected( $estadoEvento, 'SP')?>>São Paulo</option>
                            <option value="SE"<?php selected( $estadoEvento, 'SE')?>>Sergipe</option>
                            <option value="TO"<?php selected( $estadoEvento, 'TO')?>>Tocantins</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="latLogEvento">Lat.Log: </label></th>
                    <td>
                        <input name="latLogEvento" type="text" id="latLogEvento" value="<?php echo $latLogEvento; ?>" class="regular-text">
                        <p class="description">Ex. -22.518741,-44.105324</p>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
    <?php
    }

    /* #############################################################
    # FUNÇÃO PARA SALVAMENTO EXTRA DOS NOVOS CAMPOS DO EVENTO
    ############################################################# */
    function savePost( $post_id )
    {
        if (get_post_type($post_id) !== 'evento')
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
        if( isset( $_POST['dInicio'] ) )
            $valD = explode("/", $_POST['dInicio']);
            $_POST['dInicio'] = $valD['1'] . '/' . $valD['0'] . '/' . $valD['2'];
            update_post_meta( $post_id, 'dInicio', wp_kses( strtotime($_POST['dInicio']), $allowed ) );

        if( isset( $_POST['dFim'] ) )
            $valD = explode("/", $_POST['dFim']);
            $_POST['dFim'] = $valD['1'] . '/' . $valD['0'] . '/' . $valD['2'];
            update_post_meta( $post_id, 'dFim', wp_kses( strtotime($_POST['dFim']), $allowed ) );

        if( isset( $_POST['siteEvento'] ) )
            update_post_meta( $post_id, 'siteEvento', wp_kses( $_POST['siteEvento'], $allowed ) );

        if( isset( $_POST['emailEvento'] ) )
            update_post_meta( $post_id, 'emailEvento', wp_kses( $_POST['emailEvento'], $allowed ) );

        if( isset( $_POST['localEvento'] ) )
            update_post_meta( $post_id, 'localEvento', wp_kses( $_POST['localEvento'], $allowed ) );

        if( isset( $_POST['enderecoEvento'] ) )
            update_post_meta( $post_id, 'enderecoEvento', wp_kses( $_POST['enderecoEvento'], $allowed ) );

        if( isset( $_POST['cidadeEvento'] ) )
            update_post_meta( $post_id, 'cidadeEvento', wp_kses( $_POST['cidadeEvento'], $allowed ) );

        if( isset( $_POST['estadoEvento'] ) )
            update_post_meta( $post_id, 'estadoEvento', wp_kses( $_POST['estadoEvento'], $allowed ) );

        if( isset( $_POST['latLogEvento'] ) )
            update_post_meta( $post_id, 'latLogEvento', wp_kses( $_POST['latLogEvento'], $allowed ) );
    }

    /* #############################################################
    # ATUALIZA AS MENSAGENS DE ALERTA DO POST TYPE EVENTO
    ############################################################# */
    function postUpdatedMessages( $messages )
    {
        global $post, $post_ID;

        $messages['evento'] = array(
            0  => '',
            1  => sprintf( 'EVENTO atualizado com sucesso - <a href="%s">Ver evento</a>', esc_url( get_permalink($post_ID) )),
            2  => 'Campo personalizado ATUALIZADO.',
            3  => 'Campo personalizado DELETADO.',
            4  => 'EVENTO Atualizado',
            5  => isset($_GET['revision']) ? sprintf( 'EVENTO restaurado de %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( 'EVENTO publicado com sucesso - <a href="%s">Ver EVENTO</a>', esc_url( get_permalink($post_ID) ) ),
            7  => 'EVENTO salvo.',
            8  => sprintf( 'EVENTO enviado. <a target="_blank" href="%s">Ver EVENTO</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9  => sprintf( __('EVENTO agendando para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver EVENTO</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Rascunho do evento atualizado. <a target="_blank" href="%s">Ver EVENTO</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            );
        return $messages;
    }

    /* #############################################################
    # COLOCA UNS CSS NO CABEÇALHO DA PAGINA
    ############################################################# */
    function adminHead()
    {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'evento' ){
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
            #menu-posts-evento .wp-menu-image {
                background: url(<?php echo get_template_directory_uri() . '/functions/ClassPostTypeEvento/images/post-type-evento.png' ?>) no-repeat 6px -17px !important;
            }
            #menu-posts-evento:hover .wp-menu-image, #menu-posts-evento.wp-has-current-submenu .wp-menu-image {
                background-position:6px 7px!important;
            }
        </style>
    <?php
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
            'id'     => 'lista_eventos',
            'title'  => 'Eventos',
            'href'   => admin_url() .'edit.php?post_type=evento'
        ));
    }

    /* #############################################################
    # CRIA CAMPO DE COR NA TAXONOMY
    ############################################################# */
    function eventoCategoriaAddFormFields()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        ?>
        <div class="">
            <label for="term_meta[corCategoriaEvento]">Cor da Categoria</label>
            <input type="text" name="term_meta[corCategoriaEvento]" id="term_meta[corCategoriaEvento]" value="#71498b" data-default-color="#71498b" class="color-field">
            <p class="description">Cor que representa a categoria no calendário</p>
        </div>
        <script type="text/javascript">
        jQuery(document).ready(function($){
            var myOptions = {
                mode: 'hsv',
                change: function(event, ui){},
                clear: function() {},
                hide: true,
                palettes: ['#595959', '#938953', '#17365D', '#4F81BD', '#953734', '#76923C', '#E36C09', '#71498b']
            };
            $('.color-field').wpColorPicker(myOptions);
        });
        </script>
    <?php
    }
    function eventoCategoriaEditFormFields($term)
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        $t_id = $term->term_id;

        // retrieve the existing value(s) for this meta field. This returns an array
        $term_meta = get_option( "eventoCorRepresentativaCategoria$t_id" ); ?>
        <tr class="">
        <th scope="row" valign="top"><label for="term_meta[corCategoriaEvento]">Cor da Categoria</label></th>
            <td>
                <input type="text" name="term_meta[corCategoriaEvento]" value="<?php echo esc_attr( $term_meta['corCategoriaEvento'] ) ? esc_attr( $term_meta['corCategoriaEvento'] ) : ''; ?>" data-default-color="#4F81BD" class="color-field">
                <p class="description">Cor que representa a categoria no calendário</p>
                <script type="text/javascript">
                jQuery(document).ready(function($){
                    var myOptions = {
                        mode: 'hsv',
                        change: function(event, ui){},
                        clear: function() {},
                        hide: true,
                        palettes: ['#595959', '#938953', '#17365D', '#4F81BD', '#953734', '#76923C', '#E36C09', '#71498b']
                    };
                    $('.color-field').wpColorPicker(myOptions);
                });
                </script>
            </td>
        </tr>
    <?php
    }
    function saveEventoCategoria( $term_id )
    {
        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "eventoCorRepresentativaCategoria$t_id" );
            $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }
            // Save the option array.
            update_option( "eventoCorRepresentativaCategoria$t_id", $term_meta );
        }
    }

    /* #############################################################
    # CRIA UM FORMULÁRIO QUE SERÁ USADO PARA CONFIGURAÇÃO
    # DOS ITENS DESSA CLASSE
    ############################################################# */
    function formAdminEvento()
    {
        add_settings_section(
            'section',
            'Defina os dados do proprietário do site',
            '',
            'configEvento'
        );
        add_settings_field(
            'quantEventosIndex',
            'Quantidade de Eventos na página inicial:',
            array( &$this, 'callbackQuantEventosIndex' ),
            'configEvento',
            'section'
        );
        register_setting(
            'configEvento',
            'configEvento'
        );
    }
    function callbackQuantEventosIndex()
    {
        $html = '<input type="number" id="quantEventosIndex" name="configEvento[quantEventosIndex]" class=".regular-text" value="' . $this->quantEventosIndex . '"/>';
        $html .= '<br/><span class="description">Essa configuração só tem utilidade caso você esteja mostrando os eventos recentes na pagina no formato widget</span>';
        echo $html;
    }

    /* #############################################################
    # CRIA UM ITEM DO MENU QUE ESTÁ LINKADO AO FORMULÁRIO CRIADO
    # ANTES
    ############################################################# */
    function addSubMenu()
    {
        $page = add_submenu_page('edit.php?post_type=evento', 'Config. Eventos', 'Config. para Eventos', 'level_10', 'configEvento', array(&$this,'telaConfigEvento'));
    }
    function telaConfigEvento()
    {?>
        <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2>Configurações Gerais</h2>
            <form method="post" action="options.php">
                <?php
                if (array_key_exists('settings-updated', $_GET)) echo '<div style="padding: 10px;" class="updated below-h2">Propriedades alteradas com sucesso</div>';
                settings_fields( 'configEvento' );
                do_settings_sections( 'configEvento' );
                submit_button();
                ?>
            </form>
        </div>
    <?php }

    /* #############################################################
    # CRIA UM PADRÕES DE REWRITE PAGE
    ############################################################# */
    function init()
    {
        add_rewrite_rule( 'eventos_listagem', 'index.php?controle_evento=feed', 'top' );
    }
    function queryVars( $vars )
    {
        $vars[] = 'controle_evento';
        return $vars;
    }
    function templateRedirect()
    {
        if ( get_query_var( 'controle_evento' ) == 'feed' ) {
            add_filter( 'template_include', array( &$this, 'funcPHPantigo1' ) );
        }
    }
    function funcPHPantigo1(){
        return dirname(__FILE__) . '/ClassPostTypeEvento/feed-evento.php';
    }

    /* #############################################################
    # CARREGA O PLUGIN FULLCALENDAR NA ARCHIVE PAGE
    ############################################################# */
    function wpEnqueueScripts()
    {
        if (is_post_type_archive('evento')){
            $caminhoBase = get_template_directory_uri() . '/functions/ClassPostTypeEvento/';

            wp_enqueue_script('fullcalendar', $caminhoBase . 'full_calendar/fullcalendar/fullcalendar.js', false, '1.0', true);
            wp_enqueue_script('fullcalendarjs', $caminhoBase . 'full_calendar.js', false, '1.0', true);
            wp_enqueue_style('css_fullcalendar', $caminhoBase . ' full_calendar/fullcalendar/fullcalendar.css');

            $jsonevents = get_bloginfo('url') . '/eventos_listagem';

            wp_localize_script( 'fullcalendar', 'themeforce', array(
                'events' => $jsonevents,
            ));
        }
    }

    function admin_enqueue_scripts()
    {
        global $post;
        if ( isset($post->post_type) && $post->post_type == 'evento' ){
            $caminhoBase = get_template_directory_uri() . '/functions/ClassCore/';

            wp_enqueue_style('jquery-ui', $caminhoBase . 'jqueryui/css/theme/jquery-ui-1.10.3.custom.min.css' );
            wp_enqueue_script('jquery-ui', $caminhoBase . 'jqueryui/js/jquery-ui-1.10.3.custom.min.js', 'jquery' );

            wp_enqueue_style( 'ClassCore', $caminhoBase .'ClassCore.css'  );
            wp_enqueue_script('ClassCore', $caminhoBase . 'ClassCore.js', array('jquery','jquery-ui') );
        }
    }


    /* #############################################################
    # ORGANIZAÇÃO DA GRID NO PAINEL DE ADMINITRAÇÃO
    ############################################################# */
    function manageEditColumns($columns)
    {
        $columns['eventoTemporario'] = 'Categoria temporária';
        $columns['eventoCategoria']  = 'Categoria do evento';
        $columns['dInicio']          = 'D. Inicio';
        $columns['dFim']             = 'D. Término';
        $columns['siteEvento']       = 'Site';

        unset( $columns['comments'] );
        unset( $columns['date'] );

        return $columns;
    }
    function managePostsCustomColumn ($column)
    {
        global $post;

        $values     = get_post_custom( $post->ID );
        $dInicio    = isset( $values['dInicio'] ) ? date("d/m/Y",$values['dInicio'][0]) : '';
        $dFim       = isset( $values['dFim'] ) ? date("d/m/Y",$values['dFim'][0]) : '';

        $siteEvento = isset( $values['siteEvento'] ) ? $values['siteEvento'][0] : '';

        switch( $column ) {
            case 'eventoTemporario' :
                $terms = get_the_terms( $post->ID, 'eventoTemporario' );
                if ( !empty( $terms ) ) {
                    $out = array();
                    foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'eventoTemporario' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'eventoTemporario', 'display' ) )
                        );
                    }
                    echo join( ', ', $out );
                }
                else {
                    echo 'Não categorizado';
                }
                break;

            case 'eventoCategoria' :
                $terms = get_the_terms( $post->ID, 'eventoCategoria' );
                if ( !empty( $terms ) ) {
                    $out = array();
                    foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'eventoCategoria' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'eventoCategoria', 'display' ) )
                        );
                    }
                    echo join( ', ', $out );
                }
                else {
                    echo 'Não categorizado';
                }
                break;

            case 'dInicio' :
                if ( empty( $dInicio ) )
                    echo  '-';
                else
                    echo $dInicio;
                break;

            case 'dFim' :
                if ( empty( $dFim ) )
                    echo  '-';
                else
                    echo $dFim;
                break;

            case 'siteEvento' :
                if ( empty( $siteEvento ) )
                    echo  '';
                else
                    echo '<a href="'. $siteEvento .'" target="_blank">'.$siteEvento.'</a>';
                break;

            default :
                break;
        }
    }
    function manageEditSortableColumns( $columns )
    {
        $columns['dInicio'] = 'dInicio';
        $columns['dFim']    = 'dFim';

        return $columns;
    }

    /* #############################################################
    # ACTION: RESTRICT_MANAGE_POSTS
    # ACTION:PARSE_QUERY
    # COLOCA UM FRILTO NA TELA DE LISTAGEM
    ############################################################# */
    function restrictManagePosts()
    {
        $screen = get_current_screen();
        global $wp_query;
        if ( $screen->post_type == 'evento' ) {
            wp_dropdown_categories( array(
                'show_option_all' => 'Todas as categorias',
                'taxonomy'        => 'eventoCategoria',
                'name'            => 'eventoCategoria',
                'orderby'         => 'name',
                'selected'        => ( isset( $wp_query->query['eventoCategoria'] ) ? $wp_query->query['eventoCategoria'] : '' ),
                'hierarchical'    => false,
                'depth'           => 3,
                'show_count'      => false,
                'hide_empty'      => true,
            ) );
            wp_dropdown_categories( array(
                'show_option_all' => 'Todas categorias temporárias',
                'taxonomy'        => 'eventoTemporario',
                'name'            => 'eventoTemporario',
                'orderby'         => 'name',
                'selected'        => ( isset( $wp_query->query['eventoTemporario'] ) ? $wp_query->query['eventoTemporario'] : '' ),
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
        if ( ( isset( $qv['eventoCategoria'] ) ) && is_numeric( $qv['eventoCategoria'] ) ) {
            $term = get_term_by( 'id', $qv['eventoCategoria'], 'eventoCategoria' );
            $qv['eventoCategoria'] = $term->slug;
        }
        if ( ( isset( $qv['eventoTemporario'] ) ) && is_numeric( $qv['eventoTemporario'] ) ) {
            $term = get_term_by( 'id', $qv['eventoTemporario'], 'eventoTemporario' );
            $qv['eventoTemporario'] = $term->slug;
        }
    }
}
$ClassPostTypeEventos = new ClassPostTypeEventos();

include 'ClassPostTypeEvento/ClassPostTypeEventoWidget.php';
?>
