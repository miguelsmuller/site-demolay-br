<body <?php body_class(); ?>>

    <?php do_action( 'after_body' ); ?>
    <!--MENU TOPO-->
    <nav class="nav-menu-topo visible-desktop" >
        <div class="container" data-intro='Uma menu de acesso rápido para você ir direto aos sistemas paralelos.' data-step='1'>
            <div class="row">
            <?php
                if ( has_nav_menu( 'menu-topo' ) ) {
                    $menuOptions = array(
                        'theme_location'    => 'menu-topo',
                        'menu'              => '',
                        'container'         => '',
                        'container_id'      => '',
                        'container_class'   => '',
                        'menu_class'        => 'inline pull-right',
                        'menu_id'           => 'menu-topo'
                    );
                    wp_nav_menu($menuOptions);
                }
            ?>
            </div>
        </div>
    </nav>

    <!--MENU MOBILE-->
    <nav class="nav-menu-principal navbar navbar-static-top hidden-desktop visible-phone">
        <div class="navbar-inner">
            <div class="container">
                <?php
                if ( has_nav_menu( 'menu-mobile' ) ) {
                ?>
                <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>

                <?php
                    $menuOptions = array(
                        'container'       => 'div',
                        'container_id'    => 'menubar-mobile',
                        'container_class' => 'nav-collapse collapse',
                        'menu_class'      => 'nav nav-tabs',
                        'theme_location'  => 'menu-mobile',
                        'walker'          => new MenuBootstrap()
                    );
                    wp_nav_menu($menuOptions);
                ?>
                <?php
                }
                ?>
            </div>
        </div>
    </nav>

    <!--HEADER SITE-->
    <header class="heder-topo-2014">

    </header>

    <!--MENU PRINCIPAL-->
    <nav class="nav-menu-principal navbar navbar-static-top visible-desktop scroll_fixed">
        <div class="navbar-inner">
            <div class="container" data-intro='Tudo que você precisar você pode achar dentro dessa cadeia de menu que foi organizada para criar uma navegação fácil e rápida' data-step='2'>
                <?php
                    $menuOptions = array(
                        'theme_location'    => 'menu-principal',
                        'walker'            => new MenuBootstrap(),
                        'container'         => '',
                        'container_id'      => '',
                        'container_class'   => '',
                        'menu_class'        => 'menu-principal nav nav-tabs',
                        'menu_id'           => '',
                        'fallback_cb' => 'wp_page_menu'
                    );
                    wp_nav_menu($menuOptions);
                ?>
                <?php if( !is_user_logged_in() ) {?>
                <?php
                global $wp;
                $current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
                ?>
                <ul id="menu-secundario" class="menu-principal menu-secundario nav nav-tabs pull-right">

                    <li id="menu-item-89" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-89">
                        <a id="login_box" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lock icon-white"></i></a>
                        <div class="dropdown-menu dropdown-menu-popup">

                            <form name="loginform" id="loginform" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">
                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="control-group">
                                            <div class="control">
                                                <label class="control-label" for="log">Usuário: </label>
                                                <input type="text" id="log" name="log" class="span12" placeholder="USUÁRIO">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <div class="control">
                                                <label class="control-label" for="pwd">Senha: </label>
                                                <input type="password" id="pwd" name="pwd" class="span12" placeholder="SENHA">
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <label class="login-checkbox-remember"><input name="rememberme" id="rememberme" type="checkbox" checked="checked" value="forever" /> Lembrar</label>
                                            </div>
                                            <div class="span6">
                                                <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-green pull-right" value="Acessar área restrita">
                                            </div>
                                        </div>
                                        <div class="row-fluid">
                                            <div class="span6">
                                                <a href="http://sisdm.demolay.org.br/demolay/publico/FormCadastro.action"><small>Cadastre-se aqui!</small></a>
                                            </div>
                                            <div class="span6">
                                                <a class="pull-right" href="http://sisdm.demolay.org.br/demolay/publico/FormEsqueciSenha.action"><small>Esqueceu sua senha?</small></a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="testcookie" value="1">
                                        <input type="hidden" name="redirect_to" value="<?php echo $current_url; ?>/">
                                    </div>
                                </div>
                            </form>

                        </div>

                    </li>
                </ul>
                <?php } else { ?>
                <ul id="menu-secundario" class="menu-principal menu-secundario nav nav-tabs pull-right">

                    <li id="menu-item-89" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-home menu-item-89">
                        <a id="login_box" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i></a>
                        <div class="dropdown-menu dropdown-menu-popup" style="padding: 5px 10px 10px 10px !important;">
                            <div class="row-fluid">
                                <div class="span6">
                                    <a href="<?php echo admin_url(); ?>profile.php" title="Logout" class="btn btn-green btn-block pull-right">Meu perfil</a>
                                </div>
                                <div class="span6">
                                    <a href="<?php echo wp_logout_url(); ?>" title="Logout" class="btn btn-green btn-block pull-right">Sair do sistema</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <?php } ?>
            </div>
        </div>
    </nav>
    <?php
    //echo $GLOBALS['wp_query']->request;
    //$rewrite = get_option( 'rewrite_rules' );
    //echo '<pre>'; print_r ($rewrite); echo '</pre>';
    ?>