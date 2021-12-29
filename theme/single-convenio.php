<?php
wp_reset_query();
if (have_posts()) : while (have_posts()) : the_post();

    $desconto = get_post_meta($post->ID, "desconto", true);
    $servico = get_post_meta($post->ID, "servico", true);
    $publico = get_post_meta($post->ID, "publico", true);
    $documentos = get_post_meta($post->ID, "documentos", true);

    $endereco_convenio = get_post_meta($post->ID, "endereco_convenio", true);
    $telefone_convenio = get_post_meta($post->ID, "telefone_convenio", true);
    $email_convenio = get_post_meta($post->ID, "email_convenio", true);
    $site_convenio = get_post_meta($post->ID, "site_convenio", true);

    $restrito_convenio = get_post_meta($post->ID, "restrito_convenio", true);
    //$restrito_convenio = get_post_meta($post->ID, "restrito_convenio", true) != '' ? get_post_meta($post->ID, "restrito_convenio", true) : '';

?>
<?php
if ( !is_user_logged_in() && $restrito_convenio == 'on' ) {
    $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
    wp_redirect( wp_login_url( $current_url ), 302 );
    exit;
}
?>
<?php get_header(); ?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">
                <?php
                if( has_post_thumbnail() ){
                ?>
                <div class="span5">
                    <?php
                    $attr = array(
                        'class' => "img-rounded img-polaroid top-thumbnails"
                    );

                    echo the_post_thumbnail('medium',$attr);
                    ?>
                </div>

                <div class="span7">
                <?php
                } else {
                ?>
                <div class="span12">
                <?php
                }
                ?>

                    <h2 class="main-post"><?php the_title(); ?></h2>
                    <p><em><?php the_terms($post->ID, 'categoria_convenio'); ?></em><br/>
                     <p><em><?php echo $endereco_convenio?></em><br/><br/>
                    Telefone: <em><?php echo $telefone_convenio?></em><br/>
                    Site: <em><a href="<?php echo $site_convenio; ?>" target="_blank"><?php echo $site_convenio?></a></em><br/>
                    E-Mail: <em><a target="_blank" href="mailto:<?php echo $email_convenio; ?>?Subject=Convênio - DeMolay Brasil"><?php echo $email_convenio?></a></em></p>
                </div>

            </div>
            </div>
            </div>
            </div>

            <div class="row-fluid main-content single-event">
                <div class="span12 widget">
                    <div class="widget-titulo">
                        <h4>Informações</h4>
                    </div>
                    <div class="well widget-conteudo">
                        <?php the_content(); ?>
                        <dl class="dl-horizontal">

                            <?php if ($desconto){?>
                            <dt>Desconto</dt>
                            <dd><?php echo $desconto?></dd>
                            <?php }?>

                            <?php if ($servico){?>
                            <dt>Serviços</dt>
                            <dd><?php echo $servico?></dd>
                            <?php }?>

                            <?php if ($publico){?>
                            <dt>Público</dt>
                            <dd><?php echo $publico?></dd>
                            <?php }?>

                            <?php if ($documentos){?>
                            <dt>Documentos</dt>
                            <dd><?php echo str_replace("\r\n", "<br/>", $documentos);?></dd>
                            <?php }?>
                        </dl>
                    </div>
                </div>

            </div>

        </div>

        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php get_template_part( 'template-part/sidebar', 'convenio' ); ?>
        </div>
    </div>
</div>
<?php endwhile; else: ?>
<?php endif; ?>
<?php get_footer(); ?>