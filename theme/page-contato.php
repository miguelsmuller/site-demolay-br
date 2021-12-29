<?php
/*
Template Name: Page Atendimento
*/
?>
<?php global $ClassTheme; ?>
<?php global $ClassConfigApi; ?>

<?php
include 'features/recaptcha/recaptchalib.php';
?>
<?php

$destinatario       = isset( $_POST['destinatario'] ) ? $_POST['destinatario'] : '';
$capituloRequerente = isset( $_POST['capituloRequerente'] ) ? $_POST['capituloRequerente'] : '';
$nomeRequerente     = isset( $_POST['nomeRequerente'] ) ? $_POST['nomeRequerente'] : '';
$cidRequerente      = isset( $_POST['cidRequerente'] ) ? $_POST['cidRequerente'] : '';
$emailRequerente    = isset( $_POST['emailRequerente'] ) ? $_POST['emailRequerente'] : '';
$assuntoContato     = isset( $_POST['assuntoContato'] ) ? $_POST['assuntoContato'] : '';
$conteudoContato    = isset( $_POST['conteudoContato'] ) ? $_POST['conteudoContato'] : '';
$data_hora          = date( 'd/m/Y H:i:s' );

$mensagem = '';

if (isset($_POST['cmdEnviar'])) {
  $privatekey = $ClassConfigApi->getApiRecaptchaPrivate();

	$resp       = recaptcha_check_answer ($privatekey,
						$_SERVER["REMOTE_ADDR"],
						$_POST["recaptcha_challenge_field"],
						$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		$mensagem = '<div class="alert alert-error"><strong>Capcha Error</strong> Os caracteres de validação não conferem.</div>';
	} else {
		/* E-MAIL NOTIFICAÇÃO VISITANTE
		================================================== */
		$headersVisitante = array('From: SCODB <nao-responda@demolay.org.br>','Content-Type: text/html');

		$textoVisitante   = "";

		$opcoes_gerais    = get_option(scopo.'_opcoes_gerais');
		$modeloResposta   = $opcoes_gerais['modelo_mail'];

		if ($modeloResposta == ''){
			$textoVisitante = $conteudoContato;
		}else{
			$textoVisitante = str_replace("+=TEXTO=+", $conteudoContato, $modeloResposta);
			$textoVisitante = str_replace("+=NOME=+", $nomeRequerente, $textoVisitante);
		}
		$textoVisitante = str_replace("\r\n", "<br/>", $textoVisitante);

		wp_mail($emailRequerente, '[CONTATO SCODB] '.$assuntoContato, $textoVisitante, $headersVisitante );

		/* E-MAIL NOTIFICAÇÃO GCERJ
		================================================== */
		$headersContato[] = 'From: SCODB <nao-responda@demolay.org.br>';
		$headersContato[] = 'Reply-To: '. $nomeRequerente .' <'. $emailRequerente .'>';
		$headersContato[] = 'Content-Type: text/html'; // note you can just use a simple email address

		$msgVisitante = str_replace("\r\n", "<br/>", $conteudoContato);

		$textoSCODB     = "";
		$textoSCODB     = "<b><i>Enviado em: </i></b>". $data_hora . "<br />";
		$textoSCODB     .= "<b><i>Nome: </i></b>". $nomeRequerente ;
		$textoSCODB     .= " <b><i>CID: </i></b>". $cidRequerente . "<br />";
		$textoSCODB     .= "<b><i>E-Mail: </i></b>". $emailRequerente . "<br />";
		$textoSCODB     .= "<b><i>Capítulo: </i></b>". $capituloRequerente . "<br />";
		$textoSCODB     .= "<br /><b><i>Assunto: </i></b>". $assuntoContato . "<br />";
		$textoSCODB     .= "<br /><b><i>Mensagem: </i></b><br />". $msgVisitante . "<br />";

		$statusEnvio = wp_mail($destinatario, '[Chamado site SCODB] '.$assuntoContato, $textoSCODB, $headersContato );
		if ( $statusEnvio == "0" ) {
			$mensagem = '<div class="alert alert-error"><strong>Erro!!</strong> Não foi possível realizar a operação</div>';
		}else {
			$mensagem = '<div class="alert alert-success"><strong>Obrigado pelo contato!!</strong> Chamado registrado com sucesso.</div>';
		}
	}
}
?>

<?php get_header(); ?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA	================================================== -->
        <div class="span8">
		<?php
		wp_reset_query();
        if (have_posts()) : while (have_posts()) : the_post();
        ?>

            <div class="row-fluid main-content">
            <div class="span12 box">
            <div class="row-fluid">
            <div class="span12">

                <h2 class="main-post"><?php the_title(); ?></h2>
                <?php
                the_content();
                ?>

                <?php
					echo $mensagem;
				?>

                <?php
				$option_select = array();
				$args = array(
					'orderby'       => 'slug',
					'order'         => 'ASC',
					'hide_empty'    => true
				);
				$lotacao = get_terms( 'lotacao', 'hide_empty=0' );

				foreach ( $lotacao as $departamento ) {
					$option_select[$departamento->slug]['name'] = $departamento->name;
					$option_select[$departamento->slug]['email'] = array();

					$t_id = $departamento->term_id;
					$term_meta = get_option( "taxonomy_$t_id" );

					if ((isset($term_meta['liberar_contato'])) && ($term_meta['liberar_contato'] == 'on')){
						$mail = array('titulo'=>'Enviar para todo(a) '. $departamento->name, 'mail'=>$term_meta['email_contato']);

						array_push($option_select[$departamento->slug]['email'], $mail);
					}

					$loop = new WP_Query(array(
								'post_type' => 'pessoa',
								'tax_query' => array(
									array(
										'taxonomy' => 'lotacao',
										'field' => 'slug',
										'terms' => $departamento->slug,
										'include_children' => false
									)
								),
								'meta_query' => array(
									array(
										'key' => 'liberar_contato',
										'value' => 'on',
										'compare' => '=',
										),
								),
								'posts_per_page' => -1,
								'orderby'=> 'slug',
								'order'=> 'ASC'));

					global $post;

					while ( $loop->have_posts() ) : $loop->the_post();
						$mail = array('titulo'=>get_the_title(). '' .get_post_meta($post->ID, "nome", true), 'mail'=>get_post_meta($post->ID, "mail", true));
						array_push($option_select[$departamento->slug]['email'], $mail);
					endwhile;

				}
				?>
                <div class="row-fluid main-content">
                    <div class="span12 widget">
                        <div class="widget-titulo">
                            <h4>Formulário de Contato</h4>
                        </div>

                        <div class="well widget-conteudo">

                            <?php if ( count($option_select) >= 1 ) { ?>

                            <!-- FORMULARIO DE CONTATO
                            ================================================== -->
                            <form action="" method="post" class="formContato validate">
                                <div class="control-group">
                                    <label class="control-label" for="destinatario">Setor para contato: </label>
                                    <div class="controls">
                                        <select name="destinatario" class="span8" TABINDEX=0>
                                        	<?php
											foreach ( $option_select as $s_option ) {
												if (count($s_option['email']) >= 1 ){
													echo '<optgroup label="'. $s_option['name'] .'">';
														foreach ( $s_option['email'] as $mail ) {
															?>
															<option value="<?php echo $mail['mail']; ?>" <?php selected( $mail['mail'], $destinatario ); ?>><?php echo $mail['titulo']; ?></option>
                                                            <?php
														}
													echo '</optgroup>';
												}
											}
											?>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="cidRequerente">Sua CID: </label>
                                    <div class="controls">
                                        <input type="text" name="cidRequerente" class="span2" value="<?php echo $cidRequerente; ?>" placeholder="" maxlength="7">
                                        <span class="help-inline">Apenas se você for um membro filiado ao SCODBs</span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="nomeRequerente">*Seu Nome: </label>
                                    <div class="controls">
                                        <input type="text" name="nomeRequerente" class="span9" value="<?php echo $nomeRequerente; ?>" placeholder="" required="required">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="capituloRequerente">Seu Capítulo: </label>
                                    <div class="controls">
                                        <input type="text" name="capituloRequerente" class="span9" value="<?php echo $capituloRequerente; ?>" placeholder="">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="emailRequerente">*Seu E-mail: </label>
                                    <div class="controls">
                                        <input type="email" name="emailRequerente" class="span7" value="<?php echo $emailRequerente; ?>" placeholder="" required="required">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="assuntoContato">*Assunto: </label>
                                    <div class="controls">
                                        <input type="text" name="assuntoContato" class="span8" value="<?php echo $assuntoContato	; ?>" placeholder="" required="required">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="conteudoContato">*Sua mensagem: </label>
                                    <div class="controls">
                                        <textarea name="conteudoContato" class="btn-block" rows="8" required="required"><?php echo $conteudoContato; ?></textarea>
                                    </div>
                                </div>

                                <div class="row-fluid">
                                    <p>Campos marcados com * são obrigatórios.</p>
                                </div>

                                <div class="row-fluid">
                                    <div class="span6">
                                        <?php
                                            $publickey = $ClassConfigApi->getApiRecaptchaPublic();
                                            echo recaptcha_get_html($publickey);
                                        ?>
                                    </div>
                                    <div class="span6">
                                        <div class="control-group">
                                            <input type="submit" name="cmdEnviar" value="Enviar Formulario" class="btn btn-blue btn-large" style="margin-top: 40px;" />
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <?php }else{ ?>
                        		<h3>Não existem contatos cadastros no momento.</h3>
                        	<?php } ?>

                        </div>

                    </div>

                </div>

            </div>
            </div>
            </div>
            </div>


        <?php endwhile; else: ?>
        <?php endif; ?>
        </div>

        <!-- AREA DIREITA	================================================== -->
		<div class="span4">
            <div class="logo-contato"></div>
            <div class="row-fluid widget">
                <div class="span12">
                    <div class="row-fluid widget-titulo">
                        <div class="span12">
                            <h4>Endereço de Correspondência</h4>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="well widget-conteudo widget-endereco">
                                <?php
                                $ClassTheme->getEndereco('echo');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php dynamic_sidebar('Sidebar Contato'); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
