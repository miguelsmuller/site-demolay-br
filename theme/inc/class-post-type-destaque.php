<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Post_Type_Destaque
{
    /**
     * Construtor da Classe
     */
    public function __construct(){
        // Actions
        add_action( 'init', array( &$this, 'init_post_type'));
        add_action( 'admin_head', array( &$this, 'admin_head'));


        // Filters
        add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages'));

        // Mudança das colunas do WP-ADMIN
        add_filter( 'manage_edit-featured-picture_columns',array( &$this, 'create_custom_column' ));
        add_action( 'manage_featured-picture_posts_custom_column',array( &$this, 'manage_custom_column' ));
        add_filter( 'manage_edit-featured-picture_sortable_columns',array( &$this, 'manage_sortable_columns' ));
    }


    /**
     * Cria o tipo de post slide
     */
    function init_post_type()
    {
        register_post_type( 'featured-picture',
            array(
                'labels' => array(
                    'name'               => 'Destaque',
                    'singular_name'      => 'Destaque',
                    'add_new'            => 'Adicionar nova Destaque',
                    'add_new_item'       => 'Adicionar nova Destaque',
                    'edit'               => 'Editar',
                    'edit_item'          => 'Editar Destaque',
                    'new_item'           => 'Novo Destaque',
                    'view'               => 'Ver',
                    'view_item'          => 'Ver Destaque',
                    'search_items'       => 'Buscar destaque',
                    'not_found'          => 'Nenhum destaque encontrado',
                    'not_found_in_trash' => 'Nenhum destaque encontrado na lixeira',
                    'parent'             => 'Destaque',
                    'menu_name'          => 'Destaque'
                ),

                'hierarchical'       => false,
                'public'             => false,
                'query_var'          => true,
                'supports'           => array('title'),
                'has_archive'        => true,
                'capability_type'    => 'post',
                'menu_icon'          => 'dashicons-star-filled',
                'show_ui'            => true,
                'show_in_menu'       => true,

            )
        );


        if(function_exists("register_field_group")) {
            register_field_group(array (
                'id' => 'acf_featured-picture',
                'title' => 'Imagem de Destaque',
                'fields' => array (
                    array (
                        'key' => 'field_54b5cb6a1d6ga',
                        'label' => 'Imagem',
                        'name' => 'thumbnail',
                        'type' => 'image',
                        'required' => 1,
                        'save_format' => 'object',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ),
                    array (
                        'key' => 'field_54f618c68f683',
                        'label' => 'formato',
                        'name' => 'formato',
                        'prefix' => '',
                        'type' => 'select',
                        'instructions' => '',
                        'required' => 1,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'choices' => array (
                            2 => 'Formato Pequeno 1/6',
                            3 => 'Formato Médio 1/4',
                            6 => 'Formato Grande 1/2',
                            12 => 'Linha Inteira 1',
                        ),
                        'default_value' => array (
                            0 => 3,
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                        'ui' => 0,
                        'ajax' => 0,
                        'placeholder' => '',
                        'disabled' => 0,
                        'readonly' => 0,
                    ),
                    array (
                        'key' => 'field_54b5cb761d6gb',
                        'label' => 'Tipo de Destino',
                        'name' => 'tipo_destino',
                        'type' => 'radio',
                        'required' => 1,
                        'choices' => array (
                            'interno' => 'Destino Interno',
                            'externo' => 'Destino Externo',
                        ),
                        'other_choice' => 0,
                        'save_other_choice' => 0,
                        'default_value' => '',
                        'layout' => 'horizontal',
                    ),
                    array (
                        'key' => 'field_54b5cb781d6gc',
                        'label' => 'Destino Externo',
                        'name' => 'destino_externo',
                        'prefix' => '',
                        'type' => 'url',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array (
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'default_value' => '',
                        'placeholder' => 'http://www.google.com.br',
                        'conditional_logic' => array (
                            'status' => 1,
                            'rules' => array (
                                array (
                                    'field' => 'field_54b5cb761d6gb',
                                    'operator' => '==',
                                    'value' => 'externo',
                                ),
                            ),
                            'allorany' => 'all',
                        ),
                    ),
                    array (
                        'key' => 'field_54b5cb791d6gd',
                        'label' => 'Destino Interno',
                        'name' => 'destino_interno',
                        'type' => 'post_object',
                        'required' => 1,
                        'conditional_logic' => array (
                            'status' => 1,
                            'rules' => array (
                                array (
                                    'field' => 'field_54b5cb761d6gb',
                                    'operator' => '==',
                                    'value' => 'interno',
                                ),
                            ),
                            'allorany' => 'all',
                        ),
                        'post_type' => array (
                            0 => 'post',
                            1 => 'page',
                            2 => 'evento',
                            3 => 'arquivo',
                            4 => 'artigo',
                            5 => 'area',
                            6 => 'projeto',
                        ),
                        'taxonomy' => array (
                            0 => 'all',
                        ),
                        'allow_null' => 0,
                        'multiple' => 0,
                    ),
                    array (
                        'key' => 'field_54b5cb7b1d6ge',
                        'label' => 'Abrir em nova Janela',
                        'name' => 'target',
                        'type' => 'checkbox',
                        'choices' => array (
                            'sim' => 'Sim, eu gostaria que essa página fosse aberta em uma nova janela',
                        ),
                        'default_value' => '',
                        'layout' => 'vertical',
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'featured-picture',
                            'order_no' => 0,
                            'group_no' => 0,
                        ),
                    ),
                ),
                'options' => array (
                    'position' => 'normal',
                    'layout' => 'default',
                    'hide_on_screen' => array (
                        0 => 'permalink',
                        1 => 'the_content',
                        2 => 'excerpt',
                        3 => 'custom_fields',
                        4 => 'discussion',
                        5 => 'comments',
                        6 => 'revisions',
                        7 => 'slug',
                        8 => 'author',
                        9 => 'format',
                        10 => 'featured_image',
                        11 => 'categories',
                        12 => 'tags',
                        13 => 'send-trackbacks',
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'left',
                'instruction_placement' => 'field',
                'hide_on_screen' => '',
            ));
        }
    }


    /**
     * Inclui código CSS no painel administrativo
     */
    function admin_head()
    {
        global $post;

        //Apenas no modo de edição do Post Type
        if ( isset($post->post_type) && $post->post_type == 'featured-picture' ){
        ?>
            <style type="text/css" media="screen">
                .column-featured_image{
                    width: 150px;
                }
                .misc-pub-visibility,
                .misc-pub-curtime{
                    display: none;
                }
                .misc-pub-section {
                    padding: 6px 10px 18px;
                }
                .label-red,
                .label-green,
                .label-gray{
                    padding: .3em 0.6em .3em;
                    font-weight: bold;
                    border-radius: .25em;
                    line-height: 1;
                    color: #FFF;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;
                    display: inline;
                }
                .label-red{
                    background-color: #D9534F;
                }
                .label-green{
                    background-color: #5CB85C;
                }
                .label-gray{
                    background-color: #777;
                }
                .details-list{
                    margin: 0;
                }
            </style>
        <?php
        }
        //Em qualquer página do painel administrativo
        ?>
    <?php
    }


    /**
     * Personaliza as mensagens do processo de salvamento
     */
    function post_updated_messages( $messages ) {
        global $post;
        $postDate = date_i18n(get_option('date_format'), strtotime( $post->post_date ));

        $messages['featured-picture'] = array(
            1  => '<strong>Destaque</strong> atualizado com sucesso',
            6  => '<strong>Destaque</strong> publicado com sucesso',
            9  => sprintf('<strong>Destaque</strong> agendando para <strong>%s</strong>', $postDate),
            10 => 'Rascunho do <strong>Destaque</strong> atualizado'
        );
        return $messages;
    }


    /**
     * Cria uma coluna na lista de slides do painel administrativo
     */
    function create_custom_column($columns)
    {
        global $post;

        $new = array();
        foreach($columns as $key => $title) {
            if ( $key=='title' )
                $new['featured_image'] = 'Destaque';
            if ( $key=='date' ){
                $new['status']  = 'Situação';
                $new['details'] = 'Detalhes';
            }
            $new[$key] = $title;
        }
    return $new;
    }


    /**
     * Inseri valor na coluna especifica da listagem do painel administrativo
     */
    function manage_custom_column ($column)
    {
        global $post;

        switch( $column ) {
            case 'featured_image' :
                $thumbnail = get_field('thumbnail');

                if( $thumbnail ) {
                    if ( in_array( 'slide', get_intermediate_image_sizes() )){
                        $new_url = wp_get_attachment_image_src($thumbnail['id'], 'slide');
                        $thumbnail['url'] = $new_url[0];
                    }else{
                        $new_url = wp_get_attachment_image_src($thumbnail['id'], 'thumbnail');
                        $thumbnail['url'] = $new_url[0];
                    }

                    $url_edit_post = get_bloginfo('wpurl') .'/wp-admin/post.php?post='.$post->ID.'&action=edit';
                    echo '<a href="'. $url_edit_post .'"><img width="100%" src="'. $thumbnail['url'] .'" /></a>';
                }
                break;

            case 'status' :
                if (get_post_status( $post->ID ) == 'draft') {
                    echo '<span class="label-red">Rascunho</span>';
                }else{
                    echo '<span class="label-green">Publicado</span>';
                }
                break;

            case 'details' :
                if (get_field('tipo_destino') == 'interno'){
                    $destino_interno = get_field('destino_interno');
                    $post_type = get_post_type_object( $destino_interno->post_type );
                    $attr['type'] = 'Interno';
                    $attr['target'] = get_permalink( $destino_interno->ID );
                    $attr['title'] = '['. $post_type->labels->singular_name .'] - '. $destino_interno->post_title;

                }else{
                    $attr['type'] = 'Link Externo';
                    $attr['target'] = get_field('destino_externo');
                    $attr['title'] = get_field('destino_externo');
                }
                $attr['nova-janela'] = get_field('target');
                $attr['nova-janela'] = isset($attr['nova-janela'][0]) ? 'Sim' : 'Não';
                $attr['format'] = get_field('formato');

                $format = '<ul class="details-list">
                <li><span class="label-gray">%s</span></li>
                <li>Destino: <a href="%s" target="_blank">%s</a></li>
                <li>Formato: %s</li>
                <li>Nova Janela: %s</li>
                </ul>';

                printf($format, $attr['type'], $attr['target'], $attr['title'], $attr['format'], $attr['nova-janela']);

                break;
        }
    }


    /**
     * Permite que a coluna seja ordenada de acordo com o valor
     */
    function manage_sortable_columns( $columns ){
        $columns['status'] = 'status';
        return $columns;
    }
}
$Class_Post_Type_Destaque = new Class_Post_Type_Destaque();
