<?php get_header(); ?>
<!--AREA DO CARROUSEL E DO TEXTO DE INFORMAÇÃO-->
<div class="wrap-carrousel hidden-phone">
    <div class="container row-wrap-carrousel">
        <div class="row-fluid">
            <!--CARROUSEL-->
            <div id="carrousel" class="span8" data-intro='As atividades e projetos que são destaques estão aqui, á um clique do mouse.' data-step='3'>
                <?php get_template_part( 'template-part/loop', 'slide' ); ?>
            </div>

            <div id="widget-inicial" class="span4 visible-desktop">
                <?php
                global $ClassPostTypeEventos;
                $quantEventos = $ClassPostTypeEventos->getQuantEventosIndex()
                ?>
                <?php $args = 'before_widget=<div class="widget">&before_title=<div class="widget-titulo"><h4>&after_title=</h4></div>&after_widget=</div>'; ?>
                <?php $instance = "title=Proximos Eventos&quant=$quantEventos"; ?>
                <?php the_widget('Widget_eventos', $instance, $args); ?>
                <?php  ?>
            </div>
        </div>
    </div>
</div>

<!--DESTAQUE-->
<div class="container area-detaque hidden-phone">
    <div class="row-fluid" data-intro='Sites paralelos, atividades e muitas informações que sabemos ser úteis estão aqui.' data-step='4'>
        <?php get_template_part( 'template-part/loop', 'destaque' ); ?>
    </div>
</div>

<div class="container">
    <div class="row-fluid">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">
            <?php get_template_part( 'template-part/loop', 'noticias' ); ?>
        </div>

        <!-- AREA DIREITA   ================================================== -->
        <div class="span4" data-intro='Aqui nessa barra lateral você vai sempre encontrar ferramentas úteis para o dia a dia.' data-step='6'>
            <?php dynamic_sidebar('sidebar-principal'); ?>
        </div>
    </div>
</div>
<div id="instafeed" class="visible-desktop" data-intro='Vamos socializar, #SCODB no instagram. A maior fraternidade juvenil mudial.' data-step='7'></div>
<?php get_footer(); ?>