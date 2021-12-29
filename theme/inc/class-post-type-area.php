<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Post_Type_Area
{
    /**
     * Construtor da Classe
     */
    public function __construct(){
        // Actions
        add_action( 'init', array( &$this, 'init_post_type'));
        add_action( 'admin_head', array( &$this, 'admin_head' ));

        // Filters
        add_filter( 'post_updated_messages', array( &$this, 'post_updated_messages'));
        add_filter( 'manage_edit-area_columns', array( &$this, 'create_custom_column' ));
    }


    /**
     * Cria o tipo de post slide
     */
    function init_post_type(){

        //REGISTER ARQUIVO POST TYPE
        register_post_type( 'area',
            array(
                'labels' => array(
                    'name'               => 'Areas',
                    'singular_name'      => 'Area',
                    'add_new'            => 'Adicionar area',
                    'add_new_item'       => 'Adicionar area',
                    'edit_item'          => 'Editar area',
                    'new_item'           => 'Novo area',
                    'view_item'          => 'Ver area',
                    'search_items'       => 'Buscar area',
                    'not_found'          => 'Nenhuma Area encontrada',
                    'not_found_in_trash' => 'Nenhuma Area encontrada na lixeira',
                    'parent'             => 'Areas',
                    'menu_name'          => 'Areas'
                ),

                'hierarchical'    => false,
                'public'          => true,
                'query_var'       => true,
                'supports'        => array( 'title', 'editor' ),
                'has_archive'     => false,
                'capability_type' => 'post',
                'rewrite'         => array('slug' => 'estrutura', 'with_front' => false),
                'menu_icon'       => 'dashicons-networking',
                'show_ui'         => true,
                'show_in_menu'    => true,
            )
        );

        if(function_exists("register_field_group"))
        {
            register_field_group(array (
                'id' => 'acf_area',
                'title' => 'Area',
                'fields' => array (
                    array (
                        'key' => 'field_536e5ce077ea9',
                        'label' => 'Membros',
                        'name' => 'membros',
                        'type' => 'repeater',
                        'required' => 1,
                        'sub_fields' => array (
                            array (
                                'key' => 'field_536e5cf777eaa',
                                'label' => 'CID',
                                'name' => 'cid',
                                'type' => 'number',
                                'column_width' => '',
                                'default_value' => '',
                                'placeholder' => '99999',
                                'prepend' => '',
                                'append' => '',
                                'formatting' => 'html',
                                'maxlength' => '',
                            ),
                            array (
                                'key' => 'field_536e5d5377ead',
                                'label' => 'Imagem de Perfil',
                                'name' => 'image_perfil',
                                'type' => 'image',
                                'column_width' => '',
                                'save_format' => 'object',
                                'preview_size' => 'thumbnail',
                                'library' => 'all',
                            ),
                            array (
                                'key' => 'field_536e5d7977eae',
                                'label' => 'Cargo',
                                'name' => 'cargo',
                                'type' => 'text',
                                'column_width' => '',
                                'default_value' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'formatting' => 'html',
                                'maxlength' => '',
                            ),
                            array (
                                'key' => 'field_53b303363193b',
                                'label' => 'E-Mail institucional',
                                'name' => 'mail',
                                'type' => 'email',
                                'column_width' => '',
                                'default_value' => '',
                                'placeholder' => 'joazinho@gmail.com',
                                'prepend' => '',
                                'append' => '',
                            ),
                        ),
                        'row_min' => 1,
                        'row_limit' => '',
                        'layout' => 'row',
                        'button_label' => 'Adicionar Membro',
                    )
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'area',
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
                        2 => 'excerpt',
                        3 => 'custom_fields',
                        6 => 'revisions',
                        7 => 'slug',
                        8 => 'author',
                        9 => 'format',
                        10 => 'featured_image',
                        11 => 'tags',
                        12 => 'send-trackbacks',
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'left',
                'instruction_placement' => 'field',
                'hide_on_screen' => '',
            ));

            register_field_group(array (
                'id' => 'acf_area_extra',
                'title' => 'Mostra essa área no seguinte formato',
                'fields' => array (
                    array (
                        'key' => 'field_54f0f3962b6df',
                        'label' => 'Mostra essa área no seguinte formato',
                        'name' => 'format',
                        'type' => 'select',
                        'choices' => array (
                            'default' => 'Padrão',
                            'time_line' => 'Linha do Tempo',
                        ),
                        'default_value' => 'default',
                        'allow_null' => 0,
                        'multiple' => 0,
                    ),
                ),
                'location' => array (
                    array (
                        array (
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'area',
                            'order_no' => 0,
                            'group_no' => 0,
                        ),
                    ),
                ),
                'options' => array (
                    'position' => 'side',
                    'layout' => 'default',
                    'hide_on_screen' => array (
                    ),
                ),
                'menu_order' => 0,
            ));
        }
    }


    /**
     * Inclui código CSS no painel administrativo
     */
    function admin_head() {
        $screen = get_current_screen();
        if( $screen->base == 'dashboard' ) {
        ?>
            <style type="text/css" media="screen">
                #dashboard_right_now a.area-count:before,
                #dashboard_right_now span.area-count:before {
                    content: '\f325';
                }
            </style>
        <?php
        }
    }


    /**
     * Personaliza as mensagens do processo de salvamento
     */
    function post_updated_messages( $messages ) {
        global $post;
        $link = esc_url( get_permalink($post->ID));
        $link_preview = esc_url( add_query_arg('preview', 'true', get_permalink($post->ID)));
        $date = date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ));

        $messages['area'] = array(
            1  => sprintf('<strong>Estrutura</strong> atualizada com sucesso - <a href="%s">Ver Estrutura</a>', $link),
            6  => sprintf('<strong>Estrutura</strong> publicada com sucesso - <a href="%s">Ver Estrutura</a>', $link),
            9  => sprintf('<strong>Estrutura</strong> agendanda para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver Estrutura</a>',$date ,$link),
            10 => sprintf('Rascunho do <strong>Estrutura</strong> atualizada. <a target="_blank" href="%s">Ver Estrutura</a>', $link_preview),
        );
        return $messages;
    }


    /**
     * Cria uma coluna na lista de slides do painel administrativo
     */
    function create_custom_column( $columns ) {
        if (is_plugin_active('wordpress-seo/wp-seo.php')) {
            unset($columns['wpseo-title']);
            unset($columns['wpseo-metadesc']);
            unset($columns['wpseo-focuskw']);
            unset($columns['wpseo-score']);
            $columns['wpseo-score']      = 'SEO';
        }

        return $columns;
    }
}
$Class_Post_Type_Area = new Class_Post_Type_Area();





/* 'bold', 'italic', 'underline', 'blockquote', 'strikethrough', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignright', 'undo', 'redo', 'link', 'unlink', 'fullscreen', 'hr', 'wp_more', 'undo', 'redo' */
add_filter("mce_buttons", "base_extended_editor_mce_buttons", 999);
function base_extended_editor_mce_buttons($buttons) {
    global $post;
    if ( isset($post->post_type) && $post->post_type == 'area' ){
        $buttons = array(
            'bold', 'italic', 'underline', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignright', 'link', 'unlink', 'removeformat', 'undo', 'redo', 'fullscreen'
        );
    }
    return $buttons;
}

add_filter("mce_buttons_2", "base_extended_editor_mce_buttons_2", 999);
function base_extended_editor_mce_buttons_2($buttons) {
    global $post;
    if ( isset($post->post_type) && $post->post_type == 'area' ){
        $buttons = array();
    }
    return $buttons;
}
