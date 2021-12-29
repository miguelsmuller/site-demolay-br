<?php global $ClassMaintenance; global $ClassTheme; ?>
<?php get_header(); ?>
<body class="modoManutencao">

<div id="wrap">

	<!-- FUNDO PAGINA ================================================== -->
	<div class="container fundo_manutencao">
		<div class="row">
			<div class="span12">
			<img class="emblema_site visible-desktop" src="<?php bloginfo('template_directory'); ?>/assets/images/logo-scodb-large.png" alt="<?php bloginfo('name'); ?>"/>
			<img class="nome_site" src="<?php bloginfo('template_directory'); ?>/assets/images/nome-scodb-compressed.png" alt="<?php bloginfo('name'); ?>"/>
			</div>
		</div>
	</div>
</div>

<!-- FAIXA DE MANUTENÇÃO ================================================== -->
<!-- FAIXA DE MANUTENÇÃO ================================================== -->
<!-- FAIXA DE MANUTENÇÃO ================================================== -->
<!-- FAIXA DE MANUTENÇÃO ================================================== -->
<div class="faixa_manutencao">
	<div class="container">
		<div class="row">
			<div class="offset2 span4">
				<div class="row">
					<div class="span6">
						<p class="mamo_pagetitle">Em manutenção</p>
					</div>
					<div class="span6">
						<p class="mamo_template_tag_message"><?php $ClassMaintenance->getMotivoMaintenance('echo') ?></p>
					</div>
					<div class="span6 visible-desktop">
						<div class="mamo_redesSociais">
							<?php
							if ($ClassTheme->getFacebook() != ''){
							?>
								<a href="<?php $ClassTheme->getFacebook('echo');?>" class="rolloverFacebook" data-toggle="tooltip" data-original-title="Curta no Facebook"> </a>
							<?php
							}
							?>
							<?php
							if ($ClassTheme->getTwitter() != ''){
							?>
								<a href="<?php $ClassTheme->getTwitter('echo');?>" class="rolloverTwitter" data-toggle="tooltip" data-original-title="Siga no Twitter"> </a>
							<?php
							}
							?>
							<?php
							if ($ClassTheme->getPlus() != ''){
							?>
								<a href="<?php $ClassTheme->getPlus('echo');?>" class="rolloverPlus" data-toggle="tooltip" data-original-title="Página no Plus"> </a>
							<?php
							}
							?>
							<?php
							if ($ClassTheme->getLinkedin() != ''){
							?>
								<a href="<?php $ClassTheme->getLinkedin('echo');?>>" class="rolloverlinkedin" data-toggle="tooltip" data-original-title="Algo no Linkedin"> </a>
							<?php
							}
							?>
							<?php
							if ($ClassTheme->getYoutube() != ''){
							?>
								<a href="<?php $ClassTheme->getYoutube('echo');?>" class="rolloverYoutube" data-toggle="tooltip" data-original-title="Veja no youtube"> </a>
							<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="span5 offset1 visible-desktop">
				<div id="defaultCountdown"></div>
			</div>
		</div>
	</div>
</div>

<a href="https://www.facebook.com/miguel.sneto" target="_blank"><div class="design_by"><img src="<?php bloginfo('template_directory'); ?>/assets/images/design-by-dark-compressed.png" alt="Miguel Müller - Desenvolvedor" /></div></a>

<?php wp_footer(); ?>

<?php $segundos = $ClassMaintenance->getRetorno(); ?>
<?php $segundos = $segundos['minutesTotal']*60; ?>

<script type="text/javascript">
jQuery(document).ready(function ($) {
	$('[data-toggle=tooltip]').tooltip();
});
</script>

<script src="http://keith-wood.name/js/jquery.countdown.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/features/maintenance_countdown/jquery.countdown-pt-BR.js"></script>
<script type="text/javascript">
jQuery(document).ready(function ($) {
	var austDay = new Date();
	austDay.setSeconds(austDay.getSeconds()+<?php echo $segundos; ?>);


	$('#defaultCountdown').countdown({until: austDay});
	$('#year').text(austDay.getFullYear());
});
</script>

</body>
</html>