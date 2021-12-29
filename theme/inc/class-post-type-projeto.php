<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Class_Post_Type_Projeto
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
        add_filter( 'manage_edit-projeto_columns', array( &$this, 'create_custom_column' ));
    }


    /**
     * Cria o tipo de post slide
     */
    function init_post_type(){

        //REGISTER ARQUIVO POST TYPE
        register_post_type( 'projeto',
            array(
                'labels' => array(
                    'name'               => 'Projetos',
                    'singular_name'      => 'Projeto',
                    'add_new'            => 'Adicionar projeto',
                    'add_new_item'       => 'Adicionar projeto',
                    'edit_item'          => 'Editar projeto',
                    'new_item'           => 'Novo projeto',
                    'view_item'          => 'Ver projeto',
                    'search_items'       => 'Buscar projeto',
                    'not_found'          => 'Nenhuma Projeto encontrado',
                    'not_found_in_trash' => 'Nenhuma Projeto encontrado na lixeira',
                    'parent'             => 'Projetos',
                    'menu_name'          => 'Projetos'
                ),

                'hierarchical'    => true,
                'public'          => true,
                'query_var'       => true,
                'supports'        => array( 'title', 'editor', 'page-attributes' ),
                'has_archive'     => false,
                'capability_type' => 'page',
                'rewrite'         => array('slug' => 'projetos', 'with_front' => false),
                'menu_icon'       => 'dashicons-lightbulb',
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
                #dashboard_right_now a.projeto-count:before,
                #dashboard_right_now span.projeto-count:before {
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

        $messages['projeto'] = array(
            1  => sprintf('<strong>Projeto</strong> atualizada com sucesso - <a href="%s">Ver Projeto</a>', $link),
            6  => sprintf('<strong>Projeto</strong> publicada com sucesso - <a href="%s">Ver Projeto</a>', $link),
            9  => sprintf('<strong>Projeto</strong> agendanda para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Ver Projeto</a>',$date ,$link),
            10 => sprintf('Rascunho do <strong>Projeto</strong> atualizada. <a target="_blank" href="%s">Ver Projeto</a>', $link_preview),
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
$Class_Post_Type_Projeto = new Class_Post_Type_Projeto();
