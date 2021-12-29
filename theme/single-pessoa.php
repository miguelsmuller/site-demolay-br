<?php get_header(); ?>
<?php
wp_reset_query();
if (have_posts()) : while (have_posts()) : the_post();
?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <?php
            $cid = get_post_meta($post->ID, "cid", true) != '' ? get_post_meta($post->ID, "cid", true) : '';

            $dom_gces = new DOMDocument();
            if (@$dom_gces->load(urlWebService.'/associado/detalhado/sim/cid/'.$cid) === TRUE)
            {
                $pessoas =  $dom_gces->getElementsByTagName("item");
            }

            foreach($pessoas as $pessoa)
            {
                $NO_NOME = $pessoa->getElementsByTagName("NOME");
                $nome  = ucwords(convertem($NO_NOME->item(0)->nodeValue, 0));

                $NO_dm_cd_cap = $pessoa->getElementsByTagName("dm_cd_cap");
                $dm_cd_cap  = $NO_dm_cd_cap->item(0)->nodeValue;
                $NO_nm_cap = $pessoa->getElementsByTagName("nm_cap");
                $nm_cap  = ucwords(convertem($NO_nm_cap->item(0)->nodeValue, 0));
                $capitulo = $nm_cap . ' Nº '. $dm_cd_cap;

                $NO_uf_gce = $pessoa->getElementsByTagName("uf_gce");
                $gce  = $NO_uf_gce->item(0)->nodeValue;

                $NO_nr_convento = $pessoa->getElementsByTagName("nr_convento");
                $nr_convento  = $NO_nr_convento->item(0)->nodeValue;
                $NO_nm_convento = $pessoa->getElementsByTagName("nm_convento");
                $nm_convento  = ucwords(convertem($NO_nm_convento->item(0)->nodeValue, 0));
                $convento = $nm_convento . ' Nº '. $nr_convento;

                $NO_nr_corte = $pessoa->getElementsByTagName("nr_corte");
                $nr_corte  = $NO_nr_corte->item(0)->nodeValue;
                $NO_nm_corte = $pessoa->getElementsByTagName("nm_corte");
                $nm_corte  = ucwords(convertem($NO_nm_corte->item(0)->nodeValue, 0));
                $corte = $nm_corte . ' Nº'. $nr_corte;

                $NO_CON_EMAIL = $pessoa->getElementsByTagName("CON_EMAIL");
                $mail_SISDM  = $NO_CON_EMAIL->item(0)->nodeValue;

                $NO_TWITTER = $pessoa->getElementsByTagName("TWITTER");
                $twitter  = $NO_TWITTER->item(0)->nodeValue;

                $NO_FACEBOOK = $pessoa->getElementsByTagName("FACEBOOK");
                $facebook  = $NO_FACEBOOK->item(0)->nodeValue;

                $usar_mail_sisdm = get_post_meta($post->ID, "usar_mail_sisdm", true) == 'on' ? 'ON' : 'OFF';
                $liberar_contato = get_post_meta($post->ID, "liberar_contato", true) == 'on' ? 'ON' : 'OFF';
                $mail_CPT = get_post_meta($post->ID, "mail", true) != '' ? get_post_meta($post->ID, "mail", true) : '';

            }

            ?>

            <div class="row-fluid main-content"><div class="span12 box">

                <h2><?php the_title();?> </h2>
                <h4><?php the_terms($post->ID, 'lotacao'); ?></h4>

                <div class="row-fluid list-estrutura">
                    <div class="span2">
                        <?php
                        if( has_post_thumbnail() ){
                            $attr = array(
                                'class' => "img-circle img-polaroid"
                                );

                            echo the_post_thumbnail('perfil-pessoa',$attr);
                        }else{
                            echo '<img class="img-circle img-polaroid" height="150px" src="'. get_bloginfo('template_directory').'/images/default-user-avatar.png"/>';
                        }
                        ?>
                    </div>
                    <div class="span10 resume">
                        <h3><?php echo $nome;?></h3>
                        <p><span class="item-titulo">Grande Capítulo:</span> <?php echo $gce;?></p>
                        <p><span class="item-titulo">Capítulo:</span> <?php echo $capitulo;?></p>
                        <p><span class="item-titulo">Convento:</span> <?php echo $convento;?></p>
                        <p><span class="item-titulo">Côrte:</span> <?php echo $corte;?></p>
                        <div class="employee_social">
                            <?php
                            if ($liberar_contato == 'ON'){
                                if ($usar_mail_sisdm == 'ON'){
                                    ?>
                                    <a href="mailto:<?php echo antispambot($mail_SISDM);?>" target="_blank" class="social_icon mail" data-toggle="tooltip" data-original-title="Enviar um E-Mail"> </a>
                                    <?php
                                }else{
                                    ?>
                                    <a href="mailto:<?php echo antispambot($mail_CPT);?>" target="_blank" class="social_icon mail" data-toggle="tooltip" data-original-title="Enviar um E-Mail"> </a>
                                    <?php
                                }
                            }
                            ?>

                            <?php
                            if ($facebook != ''){
                                if (filter_var($facebook, FILTER_VALIDATE_URL) === FALSE) {
                                    ?>
                                    <a href="https://www.facebook.com/<?php echo $facebook;?>" target="_blank" class="social_icon facebook" data-toggle="tooltip" data-original-title="Curta no Facebook"> </a>
                                    <?php
                                }else{
                                    ?>
                                    <a href="<?php echo $facebook;?>" target="_blank" class="social_icon facebook" data-toggle="tooltip" data-original-title="Curta no Facebook"> </a>
                                    <?php
                                }
                            }
                            ?>

                            <?php
                            if ($twitter != ''){
                                if (filter_var($twitter, FILTER_VALIDATE_URL) === FALSE) {
                                    ?>
                                    <a href="https://twitter.com/<?php echo $twitter;?>" target="_blank" class="social_icon twitter" data-toggle="tooltip" data-original-title="Siga no Twitter"> </a>
                                    <?php
                                }else{
                                    ?>
                                    <a href="<?php echo $twitter;?>" target="_blank" class="social_icon twitter" data-toggle="tooltip" data-original-title="Siga no Twitter"> </a>
                                    <?php
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>

                <?php if (get_the_content() != ''){ ?>
                <div class="row-fluid main-content">
                    <div class="span12 widget">
                        <div class="widget-titulo">
                            <h4>Histórico</h4>
                        </div>
                        <div class="well widget-conteudo">
                            <?php the_content();?>
                        </div>
                    </div>

                </div>
                <?php }?>

            </div>

        </div></div>

        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php get_template_part( 'template-part/sidebar', 'pessoa' ); ?>
        </div>
    </div>
</div>
<?php endwhile; else: ?>
<?php endif; ?>
<?php get_footer(); ?>