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
                <h2>Estrutura do SCODB - <?php echo $term->name ?></h2>

                <?php
                $termchildren = get_term_children( $term->term_id, get_query_var( 'taxonomy' ) );
                if ( count($termchildren) >= 1) {
                    echo str_replace("\r\n", "<br/>", $term->description);
                    $args = array(
                        'taxonomy'          => 'lotacao',
                        'hide_empty'        => true,
                        'title_li'          => '',
                        'child_of'          => $term->term_id
                    );
                    echo '<div class="page-list well span10"><ul>';
                    wp_list_categories( $args );
                    echo '</ul></div>';
                }else{
                    $t_id = $term->term_id;
                    $term_meta = get_option( "taxonomy_$t_id" );
                    if ($term_meta['site'] != ''){
                        echo '<a href="'.$term_meta['site'].'" target="_blank">'.$term_meta['site'].'</a><br/><br/>';
                    }
                    echo str_replace("\r\n", "<br/>", $term->description);

                    $queryPessoas = new WP_Query(array(
                        'post_type' => 'pessoa',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'lotacao',
                                'field' => 'slug',
                                'terms' => $term->slug
                                )
                            ),
                        'posts_per_page' => -1,
                        'orderby' => 'meta_value',
                        'meta_key'=> 'pesoPessoa',
                        'order'=> 'ASC'));
                    ?>
                    <div class="row-fluid">
                        <?php $titulo=''; ?>
                        <ul class="list-estrutura">
                            <?php while ( $queryPessoas->have_posts() ) : $queryPessoas->the_post(); ?>
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
                                <li>
                                    <div class="row-fluid">
                                        <div class="span12">

                                            <h3><?php if ( $titulo != get_the_title() ) {
                                                the_title();
                                                $titulo = get_the_title();
                                            } ?></h3>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span2">
                                            <a href="<?php the_permalink() ?>">
                                                <?php
                                                if( has_post_thumbnail() ){
                                                    $attr = array(
                                                        'class'  => "img-circle img-polaroid",
                                                    );

                                                    echo the_post_thumbnail('perfil-pessoa',$attr);
                                                }else{
                                                    echo '<img class="img-circle img-polaroid" height="150px" src="'. get_bloginfo('template_directory').'/assets/images/default-user-avatar.png"/>';
                                                }
                                                ?>
                                            </a>
                                        </div>
                                        <div class="span10">
                                    <h4><a href="<?php the_permalink() ?>"><?php echo $nome;?></a> <small>(<a target=_blank href=http://sisdm.br.demolay.org.br:8080/demolay/publico/ConsultaCid.action?cid=<?php echo $cid;?>><?php echo $cid;?></a>)</small></h4>
                                    <p>Capítulo: <?php echo $capitulo;?><br/>
                                    Grande Capítulo Estadual: <?php echo $gce;?></p>
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
                                </li>
                            <?php endwhile; ?>
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
            <?php get_template_part( 'template-part/sidebar', 'pessoa' ); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>