<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<!-- LE META TAGS -->
<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- LE TITLE -->
<title><?php wp_title( '|', true, 'right' ); ?></title>

<!-- LE ICONS -->
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/assets/icons/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php bloginfo('template_directory'); ?>/assets/icons/favicon-144.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/assets/icons/favicon-114.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/assets/icons/favicon-72.png">
<link rel="apple-touch-icon-precomposed" href="<?php bloginfo('template_directory'); ?>/assets/icons/favicon-57.png">

<!-- LE FONTS -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

<!-- LE PINGBACK -->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- LE AUTOGENERATE -->
<?php wp_head();?>

</head>
<body <?php body_class(); ?>>

<!-- LE ANALYTICS -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49239000-1', 'auto');
  ga('send', 'pageview');
</script>

<!-- LE PREPAGE -->
<?php do_action( 'after_body' ); ?>

<!-- GENERAL MENU -->
<nav id="generalMenu" class="aside-panel">
    <header>
        <h1 class="aside-title">Menu Principal</h1>
    </header>
    <?php
    $args = array(
        'theme_location' => 'general-menu',
        'container'      => false,
        'menu_id'        => 'general-menu',
        'menu_class'     => 'nav nav-pills nav-first nav-stacked',
        'fallback_cb'    => 'fallbackNoMenu',
        'walker'          => new GeneralMenu()
    );
    wp_nav_menu($args);
    ?>
</nav>

<!-- USER ARES -->
<section id="userArea" class="aside-panel">
    <?php
    if ( !is_user_logged_in() ) {
    ?>
        <header>
            <h1 class="aside-title">Acesso Ao Conteúdo Restrito</h1>
        </header>
        <form id="loginform" name="loginform" method="post" action="<?php echo wp_login_url(); ?>" role="form">
            <input type="hidden" name="redirect_to" value="<?php echo get_actual_url(); ?>">
            <div class="form-group">
                <label for="user_login">CID:</label>
                <input type="text" class="form-control" name="log" id="user_login" placeholder="USUÁRIO DO SISDM">
            </div>
            <div class="form-group">
                <label for="user_pass">Senha:</label>
                <input type="password" class="form-control" name="pwd" id="user_pass" placeholder="SENHA DO SISDM">
            </div>
            <button type="submit" name="wp-submit" id="wp-submit" class="btn btn-purple btn-lg btn-block btn-sidebar">Acessar conteúdo restrito</button>
            <a href="http://sisdm.br.demolay.org.br:8080/demolay/publico/FormCadastro.action" class="form-help">
                Não tem usuário cadastrado? Cadastre-se aqui!
            </a>
            <a href="http://sisdm.br.demolay.org.br:8080/demolay/publico/FormEsqueciSenha.action" class="form-help">
                Esqueceu a senha? Faça recuperação dela aqui!
            </a>
        </form>
    <?php
    }else{
    ?>
        <header>
            <h1 class="aside-title">Perfil de usuário</h1>
        </header>
        <a href="<?php echo admin_url(); ?>" title="Logout" class="btn btn-purple btn-lg btn-block">
            Acessar painel administrativo
        </a>
        <a href="<?php echo wp_logout_url(home_url()); ?>" title="Logout" class="btn btn-purple btn-lg btn-block">
            Sair da navegação identificada
        </a>
    <?php
    }
    ?>
</section>

<!-- WRAPPER -->
<div class="wrapper">
    <!-- NAVIGATION -->
    <section id="navigation" role="navigation">
        <!-- NAVIGATION MAIN PRIMARY -->
        <div class="navigation-primary">
            <div class="navigation-image">
                <a href="<?php bloginfo('url') ?>">
                    <img class="scodb-logo" src="<?php bloginfo('template_directory'); ?>/assets/images/scodb-logo.png" alt="<?php bloginfo('name') ?>">
                    <img class="scodb-name" src="<?php bloginfo('template_directory'); ?>/assets/images/scodb-texto.png" alt="<?php bloginfo('name') ?>">
                </a>
            </div>
            <nav class="navigation-menu">
                <ul>
                    <li><a id="cmdArtigosMenu" href="">ARTIGOS</a></li>
                    <li><a id="cmdInstitucionalMenu" href="">INSTITUCIONAL</a></li>
                    <li><a id="" href="">ATENDIMENTO</a></li>
                    <li><a id="" href="">BIBLIOTECA</a></li>
                    <li><a id="" href="<?php echo get_permalink('1181'); ?>">NOTÍCIAS</a></li>
                    <li><a id="" href="http://sisdm.demolay.org.br/Index.action" target="_blank">SISDM</a></li>
                </ul>
            </nav>
            <nav class="navigation-functions">
                <ul class="animated-list">
                    <li><button id="cmdSearh" type="button"><span class="icon-search"></span></button></li>
                    <li><button id="cmdUserArea" type="button"><span class="icon-user"></span></button></li>
                    <li><button id="cmdGeneralMenu" type="button"><span class="icon-menu"></span></button></li>
                </ul>
            </nav>
            <form id="search" action="#" method="post">
                <div class="input-group">
                    <input type="text" class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-purple" type="button">Pesquisar</button>
                    </span>
                </div>
            </form>
        </div>

        <!-- ARTIGOS NAVIGATION MAIN SECONDARY -->
        <div id="navigationArtigos" class="navigation-secondary">
            <div class="navigation-secondary-inner">
                <div class="navigation-secondary-row">
                    <div class="col-sm-4">
                        <ul>
                            <li><a href="<?php echo get_permalink('10'); ?>">O que é a Ordem DeMolay?</a></li>
                            <li><a href="<?php echo get_permalink('16'); ?>">O Inicio da Ordem DeMolay</a></li>
                            <li><a href="<?php echo get_permalink('29'); ?>">A expansão da Ordem DeMolay</a></li>
                            <li><a href="<?php echo get_permalink('34'); ?>">Ordem DeMolay no Brasil</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <ul>
                            <li><a href="<?php echo get_permalink('27'); ?>">O Ritual DeMolay</a></li>
                            <li><a href="<?php echo get_permalink('20'); ?>">Quem foi Jacques DeMolay</a></li>
                            <li><a href="<?php echo get_permalink('31'); ?>">Símbolos da Ordem DeMolay</a></li>
                            <li><a href="<?php echo get_permalink('65'); ?>">Datas importantes</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <ul>
                            <li><a href="<?php echo get_permalink('6018'); ?>">O que eu ganho sendo um DeMolay?</a></li>
                            <li><a href="<?php echo get_permalink('6019'); ?>">Como se tornar um DeMolay</a></li>
                            <li><a href="<?php echo get_permalink('63'); ?>">Como fundar um Capítulo DeMolay</a></li>
                            <li><a href="<?php echo get_permalink('67'); ?>">Hall da Fama Brasileiro</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- INSTITUCIONAL NAVIGATION MAIN SECONDARY -->
        <div id="navigationInstitucional" class="navigation-secondary">
            <div class="navigation-secondary-inner">
                <div class="navigation-secondary-row">
                    <div class="col-sm-4 col-md-offset-2">
                        <h1>Liderança Adulta</h1>
                        <ul>
                            <li><a href="<?php echo get_permalink('6060'); ?>">Diretoria Executiva</a></li>
                            <li><a href="<?php echo get_permalink('6061'); ?>">Administrações anteriores</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <h1>Liderança Juvenil</h1>
                        <ul>
                            <li><a href="<?php echo get_permalink('6062'); ?>">Gabinete Nacional</a></li>
                            <li><a href="<?php echo get_permalink('6063'); ?>">Administração Anteriores</a></li>
                        </ul>
                    </div>
                </div>
                <div class="navigation-secondary-row">
                    <h2>Comissões Nacionais</h2>
                    <div class="col-sm-3">
                        <ul>
                            <li><a href="<?php echo get_permalink('6066'); ?>">Informática</a></li>
                            <li><a href="<?php echo get_permalink('6067'); ?>">Filantropia</a></li>
                            <li><a href="<?php echo get_permalink('6068'); ?>">Comunicação</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <ul>
                            <li><a href="<?php echo get_permalink('6069'); ?>">Orçamentos e Finanças</a></li>
                            <li><a href="<?php echo get_permalink('6070'); ?>">Org. Filiadas e Paralelas</a></li>
                            <li><a href="<?php echo get_permalink('6071'); ?>">Relações Institucionais</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <ul>
                            <li><a href="<?php echo get_permalink('6072'); ?>">Legislação</a></li>
                            <li><a href="<?php echo get_permalink('6073'); ?>">Disciplinar</a></li>
                            <li><a href="<?php echo get_permalink('6074'); ?>">Treinamentos</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <ul>
                            <li><a href="<?php echo get_permalink('6075'); ?>">Honrarias e Prêmios</a></li>
                            <li><a href="<?php echo get_permalink('6076'); ?>">Relações Internacionais</a></li>
                            <li><a href="<?php echo get_permalink('6077'); ?>">Ritual, Liturgia e Joias</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>