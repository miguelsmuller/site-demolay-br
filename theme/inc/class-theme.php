<?php
if ( ! defined( 'ABSPATH' ) ) exit;

include 'class-post-type-artigo.php';
include 'class-post-type-projeto.php';
include 'class-post-type-area.php';
include 'class-post-type-destaque.php';

/** INCLUDE EXTRA COMPONENTES */
include dirname(__FILE__).'/../assets/components/Aqua-Resizer/aq_resizer.php';

/**
 * Criação dos menus, Configuração dos Thumbnails e dos ativação dos formatos de posts
 */
add_action( 'after_setup_theme', 'after_setup_theme' );
function after_setup_theme() {
    register_nav_menus(array(
        'general-menu' => 'Menu Geral',
        'footer-menu'  => 'Menu Rodapé'
    ));

    add_theme_support('post-thumbnails', array( 'post' ));
    set_post_thumbnail_size( 645, 600, array( 'top', 'center') );
}


/**
 * Criação dos menus, Configuração dos Thumbnails e dos ativação dos formatos de posts
 */
add_action( 'init', 'init_wp' );
function init_wp() {
    update_option('thumbnail_size_w', 300);
    update_option('thumbnail_size_h', 300);
    update_option('thumbnail_crop', 1 );

    update_option('medium_size_w', 600);
    update_option('medium_size_h', 600);
    update_option('medium_crop', 1 );

    update_option('large_size_w', 1024);
    update_option('large_size_h', 1024);
    update_option('large_crop', 1 );
}


/**
 * Registra uma área de widgets e desabilita alguns widgets padrões
 */
add_action( 'widgets_init', 'widgets_init' );
function widgets_init() {
    register_sidebar( array(
        'name'          => 'Sidebar Principal',
        'id'            => 'sidebar-principal',
        'description'   => 'Sidebar principal',
        'before_widget' => '<div class="panel panel-default">',
        'before_title'  => '<div class="panel-heading">',
        'after_title'   => '</div><div class="panel-body">',
        'after_widget'  => '</div></div>'
    ));

    unregister_widget( 'WP_Widget_Pages' );
    unregister_widget( 'WP_Widget_Calendar' );
    unregister_widget( 'WP_Widget_Archives' );
    unregister_widget( 'WP_Widget_Links' );
    unregister_widget( 'WP_Widget_Meta' );
    unregister_widget( 'WP_Widget_Categories' );
    unregister_widget( 'WP_Widget_Recent_Posts' );
    unregister_widget( 'WP_Widget_Recent_Comments' );
    unregister_widget( 'WP_Widget_RSS' );
    unregister_widget( 'WP_Widget_Tag_Cloud' );
    //unregister_widget( 'WP_Widget_Nav_Menu' );
    //unregister_widget( 'WP_Widget_Text' );
}


/**
 * Carrega os arquivos JS's e CSS's do tema
 */
add_action('wp_enqueue_scripts', 'enqueue_scripts' );
function enqueue_scripts(){
	$template_dir = get_bloginfo('template_directory');

	// COMMON STYLE AND SCRIPT
	wp_register_script( 'common-js', $template_dir .'/assets/scripts/javascript.min.js', array('jquery'), null, true );
	wp_localize_script(
		'common-js',
		'common_params',
		array(
			'site_url'  => esc_url( site_url() )
		)
	);
	wp_enqueue_script( 'common-js' );
	wp_enqueue_style( 'common-css', $template_dir .'/assets/styles/style.css' );

    wp_enqueue_script('jqueryui', $template_dir.'/assets/components/jqueryui/ui/jquery-ui.js', false, null, true);
    wp_enqueue_script('icheck', $template_dir.'/assets/components/iCheck/icheck.min.js', false, null, true);

    wp_enqueue_script('social', $template_dir.'/assets/scripts/social.min.js', false, null, true);

    if (is_home()) {
        wp_enqueue_script('carousel', $template_dir.'/assets/components/owl-carousel/owl-carousel/owl.carousel.js', false, null, true);
        wp_enqueue_script('instafeed', $template_dir.'/assets/components/instafeed.js/instafeed.min.js', false, null, true);
        wp_enqueue_script('jquery-masonry');
    }

    if ( is_page( 'onde-estamos' ) ) {
		$key = get_field('api_key_google', 'option');
        $urlTemplate = get_bloginfo('template_directory');
        $urlMapsApi = 'http://maps.googleapis.com/maps/api/js?key='. $key .'&sensor=TRUE';
        wp_register_script( 'maps-api', $urlMapsApi, array('site'), '', true);
        wp_register_script( 'maps-plugin', $urlTemplate.'/assets/components/gmaps-markerclusterer-plus/src/markerclusterer.js', array('site'), '', true);
        wp_register_script( 'maps-functions', $urlTemplate.'/assets/scripts/maps.min.js', array('site'), '', true);

        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_script('maps-api');
        wp_enqueue_script('maps-plugin');
        wp_enqueue_script('maps-functions');
    }
}


/**
 * Função quer permite a página infinita
 */
add_action('wp_ajax_infinite_scroll', 'wp_infinite_scroll');
add_action('wp_ajax_nopriv_infinite_scroll', 'wp_infinite_scroll');
function wp_infinite_scroll(){
    $template        = $_POST['template'];
    $post_type       = $_POST['post_type'];
    $posts_per_page  = $_POST['posts_per_page'];
    $paged           = $_POST['paged'];

    query_posts(array('post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'paged' => $paged,));
    get_template_part( $template );

    exit;
}


/**
 * Evira o envio de imagem com tamanho pequeno
 */
//add_filter('wp_handle_upload_prefilter','minimin_image_size');
function minimin_image_size($file)
{
    $img  =getimagesize($file['tmp_name']);
    $min_size = array('width' => '710', 'height' => '660');
    $max_size = array('width' => '2048', 'height' => '2048');
    $width = $img[0];
    $height = $img[1];

    if ($width < $min_size['width'] )
        return array("error"=>"Imagem muito pequena. Largura miníma é {$min_size['width']}px. A imagem que você enviou possui $width px de largura");

    elseif ($height <  $min_size['height'])
        return array("error"=>"Imagem muito pequena. Altura miníma é {$min_size['height']}px. A imagem que você enviou possui $height px de altura");

    elseif ($width >  $max_size['width'])
        return array("error"=>"Imagem muito grande. Altura máxima é {$max_size['width']}px. A imagem que você enviou possui $width px de altura");

    elseif ($height >  $max_size['height'])
        return array("error"=>"Imagem muito grande. Altura máxima é {$max_size['height']}px. A imagem que você enviou possui $height px de altura");

    else
        return $file;
}


/**
 * Remove o CSS do CF7 onde não tem necessidade
 */
add_action( 'wp_print_styles', 'cf_deregister_styles', 100 );
function cf_deregister_styles() {
    if ( ! is_page( 'contact-us' ) ) {
        wp_deregister_style( 'contact-form-7' );
    }
}


add_action( 'init', 'disable_wp_emojicons' );
function disable_wp_emojicons() {

  if ( ! is_single() ) {
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  }
}


/**
 * Remove o CSS e o JS do CF7 onde não tem necessidade
 */
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_action( 'wp_head', 'cf_register_assets' );
function cf_register_assets() {
	if ( is_page( 'fale-conosco' ) ) {
		wpcf7_enqueue_scripts();
		wpcf7_enqueue_styles();
	}
}


/**
 * Mensagem de atualização de navegador inseguro
 */
add_filter( 'navigator_insecure', 'navigator_insecure' );
function navigator_insecure( $msg ){
    return 'Parece que está a usar uma versão não segura do <a href="%update_url%" class="alert-link">%name%</a>. Para melhor navegar no nosso site, por favor atualize o seu browser.<br/><a href="%update_url%" class="alert-link">Clique aqui para ser direcionado para atualização do %name% agora.</a>';
}


/**
 * Mensagem de atualização de navegador desatualizado
 */
add_filter( 'navigator_upgrade', 'navigator_upgrade' );
function navigator_upgrade( $msg ){
    return 'Parece que está a usar uma versão antiga do <a href="%s" class="alert-link"%name%</a>. Para melhor navegar no nosso site, por favor atualize o seu browser.<br/><a href="%update_url%" class="alert-link">Clique aqui para ser direcionado para atualização do %name% agora.</a>';
}


/**
 * Remove o widget do mandrill da dashboard
 */
add_action( 'wp_dashboard_setup', 'dashboard_setup' );
function dashboard_setup() {
    if ( in_array('wpmandrill/wpmandrill.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ))) ){
        remove_meta_box('mandrill_widget', 'dashboard', 'normal');
    }
}


/**
 * Adiciona alguns CSS na pagina de login
 */
add_action( 'login_enqueue_scripts', 'login_scripts' );
function login_scripts() {
    $login_bg = get_template_directory_uri() . '/assets/images/image-login-background.png';
    $login_logo = get_template_directory_uri() . '/assets/images/image-login.png'
    ?>

    <style type="text/css" media="screen">
    .login form {
        border: 1px solid rgba(0, 0, 0, 0.2);
    }
    body.login {
        background-image: url("<?php echo $login_bg; ?>");
    }
    body.login div#login h1 a {
        background-image: url("<?php echo $login_logo; ?>");
    }
    body.login {
        background-color: #F6F6F6 !important;
    }
    body.login div#login{
        padding: 30px 0 0;
    }
    body.login div#login h1{
        width:320px;
        height:250px;
        margin-bottom:30px;
    }
    body.login div#login h1 a {
        background-size: 320px 250px;
        padding-bottom: 30px;
        width:320px;
        height:250px;
    }
    .text-center {
        text-align: center;
    }
    .login form {
        border: 2px solid gainsboro;
    }
    </style>
    <?php
}


add_filter( 'template_include', 'site_template_include', 99 );
function site_template_include( $template ) {
    if ( is_page( 'onde-estamos' )  ) {
        $new_template = locate_template( array( 'page-mapa.php' ) );
        if ( '' != $new_template ) {
            return $new_template ;
        }
    }

    return $template;
}



/**
 * Cria uma coluna na lista de slides do painel administrativo
 */
add_filter( 'manage_edit-post_columns', 'manage_custom_column' );
add_filter( 'manage_edit-page_columns', 'manage_custom_column' );
add_filter( 'manage_edit-arquivo_columns', 'manage_custom_column' );
add_filter( 'manage_edit-evento_columns', 'manage_custom_column' );
function manage_custom_column( $columns ) {
    if (is_plugin_active('wordpress-seo/wp-seo.php')) {
        unset($columns['wpseo-title']);
        unset($columns['wpseo-metadesc']);
        unset($columns['wpseo-focuskw']);
        unset($columns['wpseo-score']);
        $columns['wpseo-score']      = 'SEO';
    }

    return $columns;
}


/**
 * PRECISA DE UM COMENTÁRIO
 */
function list_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
?>
    <li id="comment-<?php comment_ID() ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?>>

    <div class="comment-avatar">
        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
    </div>

    <div class="comment-body">
        <?php if ( $comment->comment_approved == '0' ) : ?>
            <em class="label label-warning label-awaiting-moderation">Esse comentário está aguardando moderação.</em>
        <?php endif; ?>
        <?php
            $name = '<cite class="fn">'. get_comment_author_link() .'</cite>';
            $time = human_time_diff( get_comment_date('U'), current_time('timestamp') ) . ' atrás';
            $time = '<a href="'. htmlspecialchars( get_comment_link( $comment->comment_ID ) ) . '">'. $time . '</a>';
            echo $name .' • '. $time ;
        ?>

        <?php edit_comment_link( '(Edit)', '  ', '' );?>

        <?php comment_text(); ?>

        <div class="reply">
        <?php
            $argsMerge = array_merge( $args, array(
                                        'add_below' => 'div-comment',
                                        'depth' => $depth,
                                        'max_depth' => $args['max_depth'] )
            );
            $replyLink = get_comment_reply_link( $argsMerge );
            echo $replyLink;
        ?>
        </div>
    </div>
<?php
}


class GeneralMenu extends Walker_Nav_Menu {
    private $currentItem;

    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\" nav nav-pills nav-stacked nav-sub collapse\" id='menu-". $this->currentItem->ID ."'>\n";
    }

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $this->currentItem = $item;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $class_names = $value = '';

        /*$classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

        //if($args->has_children) {   $class_names .= ' dropdown'; }
        if($args->has_children && $depth === 0) { $class_names .= ' dropdown'; } elseif($args->has_children && $depth > 0) { $class_names .= ' dropdown-submenu'; }
        if(in_array('current-menu-item', $classes)) { $class_names .= ' active'; }

        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names .'>';*/
        $output .= $indent . '<li>';

        if($item->menu_item_parent == 0){
            $post_parent = $args->menu_id;
        }else{
            $post_parent = '#menu-'.$item->menu_item_parent;
        }

        $atts = array();
        $atts['title']  = ! empty( $item->title )      ? $item->title      : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['data-toggle']    = 'collapse';
        $atts['data-parent']    = $post_parent;
        $atts['href']           = '#menu-'.$item->ID;
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;

        //ICON
        if(! empty( $item->attr_title )){
            $item_output .= '<a'. $attributes .'><span class="' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
        } else {
            $item_output .= '<a'. $attributes .'>';
        }

        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= ($args->has_children) ? ' <span class="caret"></span></a>' : '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

    }

    function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( !$element ) {
            return;
        }

        $id_field = $this->db_fields['id'];

        //display this element
        if ( is_object( $args[0] ) ) {
           $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
        }

        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}
