<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Post_Type_Artigo
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
        add_filter( 'manage_edit-artigo_columns', array( &$this, 'create_custom_column' ));
    }


    /**
     * Cria o tipo de post slide
     */
    function init_post_type(){

        //REGISTER ARQUIVO POST TYPE
        register_post_type( 'artigo',
            array(
                'labels' => array(
                    'name'               => 'Artigos',
                    'singular_name'      => 'Artigo',
                    'add_new'            => 'Adicionar artigo',
                    'add_new_item'       => 'Adicionar artigo',
                    'edit_item'          => 'Editar artigo',
                    'new_item'           => 'Novo artigo',
                    'view_item'          => 'Ver artigo',
                    'search_items'       => 'Buscar artigo',
                    'not_found'          => 'Nenhuma artigo encontrado',
                    'not_found_in_trash' => 'Nenhuma artigo encontrado na lixeira',
                    'parent'             => 'Artigos',
                    'menu_name'          => 'Artigos'
                ),

                'hierarchical'    => true,
                'public'          => true,
                'query_var'       => true,
                'supports'        => array( 'title', 'editor', 'page-attributes' ),
                'has_archive'     => false,
                'capability_type' => 'page',
                'rewrite'         => array('slug' => 'artigos', 'with_front' => false),
                'menu_icon'       => 'dashicons-welcome-learn-more',
                'show_ui'         => true,
                'show_in_menu'    => true,
            )
        );
    }


    /**
     * Inclui código CSS no painel administrativo
     */
    function admin_head() {
        $screen = get_current_screen();
        if( $screen->base == 'dashboard' ) {
        ?>
            <style type="text/css" media="screen">
                #dashboard_right_now a.artigo-count:before,
                #dashboard_right_now span.artigo-count:before {
                    content: '\f118';
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

        $messages['artigo'] = array(
            1  => sprintf('<strong>Artigo</strong> atualizada com sucesso - <a href="%s">Ver Artigo</a>', $link),
            6  => sprintf('<strong>Artigo</strong> publicada com sucesso - <a href="%s">Ver Artigo</a>', $link),
            9  => sprintf('<strong>Artigo</strong> agendanda para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver Artigo</a>',$date ,$link),
            10 => sprintf('Rascunho do <strong>Artigo</strong> atualizada. <a target="_blank" href="%s">Ver Artigo</a>', $link_preview),
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
$Class_Post_Type_Artigo = new Class_Post_Type_Artigo();
