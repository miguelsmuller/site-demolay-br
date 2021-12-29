<?php global $ClassUser;
$grausUsuario = $ClassUser->getLevelUsuario();

$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

$t_id = $term->term_id;
$term_meta = get_option( "taxonomy_$t_id" );

$privado = (isset($term_meta['categoria_privada'])) && ($term_meta['categoria_privada'] == 'on') ? 'on' : "off";

if ( (!is_user_logged_in())  && ($privado == 'on') ) {
    wp_redirect( wp_login_url(), 302 );
    //exit;
}
?>

<?php get_header(); ?>
<?php $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); ?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">
                <h2>Documentos - <?php echo $term->name ?></h2>

                <?php
                $termchildren = get_term_children( $term->term_id, get_query_var( 'taxonomy' ) );
                if ( count($termchildren) >= 1) {
                    echo str_replace("\r\n", "<br/>", $term->description);
                    $args = array(
                        'taxonomy'          => 'arquivo_categoria',
                        'hide_empty'        => true,
                        'title_li'          => '',
                        'child_of'          => $term->term_id
                    );
                    echo '<div class="page-list well span10"><ul>';
                    wp_list_categories( $args );
                    echo '</ul></div>';
                }else{
                    $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
                    echo $term->description ;

                    $str_com_tags = get_post_type_archive_link('arquivo');
                    $str_sem_tags = preg_replace("/%.+?%\//i", "", $str_com_tags);

                    $loop = new WP_Query(array(
                        'post_type' => 'arquivo',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'arquivo_categoria',
                                'field' => 'slug',
                                'terms' => $term->slug,
                                'include_children' => false
                            )
                        ),
                        'posts_per_page' => -1,
                        'orderby'=> 'title',
                        'order'=> 'ASC'
                    ));
                ?>

<div class="row-fluid">
    <ul class="lista-download">
        <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
            <?php
            $id                   = $post->ID;
            $title                = get_the_title();
            $permalink            = get_permalink();
            $quantDownloads       = get_post_meta($id, "quantDownloads", true) != '' ? get_post_meta($id, "quantDownloads", true) :0;
            $url_arquivo          = get_post_meta($id, "url_arquivo", true) != '' ? get_post_meta($id, "url_arquivo", true) : '';
            $dir_arquivo          = get_post_meta($id, "dir_arquivo", true) != '' ? get_post_meta($id, "dir_arquivo", true) : '';
            $nome_arquivo         = get_post_meta($id, "nome_arquivo", true) != '' ? get_post_meta($id, "nome_arquivo", true) : '';
            $ext_arquivo          = get_post_meta($id, "ext_arquivo", true) != '' ? get_post_meta($id, "ext_arquivo", true) : '';
            $indisponivel         = get_post_meta($post->ID, "indisponivel", true) != '' ? get_post_meta($post->ID, "indisponivel", true) : FALSE;
            $privado              = get_post_meta($post->ID, "privado", true) != '' ? get_post_meta($post->ID, "privado", true) : '';
            $permalink_visualizar = get_permalink().'action_arquivo/visualizar';
            $permalink_download   = get_permalink().'action_arquivo/download';
            $fullname             = WP_CONTENT_DIR . '/uploads/arquivo-post-type/'.$nome_arquivo;

            $action_download = get_query_var( 'action_download' );

            if ($indisponivel == TRUE ) {
                $links = "";
                $conteudo = "<p>Esse download se encontra indisponível no momento.</p>";
            } else {
                if ( array_key_exists($privado, $grausUsuario) && is_user_logged_in() ){
                    $links = "<div class='pull-right'>
                        <a class='btn btn-green' href='$permalink_visualizar' target='_blank'>Visualizar</a>
                        <a class='btn btn-green' href='$permalink_download'>Download</a>
                    </div>";
                    $conteudo = get_the_content();;
                }else{
                    $links = "";
                    $conteudo = "<p>Esse download se encontra indisponível no momento.</p>";
                }
            }
        ?>
        <li class="well widget-conteudo widget-redonded widget-download">
            <div class="row-fluid">
                <div class="span12 detalhes-download">
                    <?php echo $links; ?>
                    <h5>
                        <?php the_title(); ?>
                        <span class="small">Classificação: <?php echo $privado; ?></span>
                    </h5>
                    <div style="margin-bottom: 10px;">
                        <?php echo $conteudo; ?>
                    </div>
                </div>
            </div>
        </li>

        <?php endwhile; wp_reset_query(); ?>
    </ul>
</div>

                <?php
                }
                ?>

            </div>
            </div>
            </div>
            </div>
        </div>

        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php get_template_part( 'template-part/sidebar', 'arquivo' ); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>