<?php get_header(); ?>
<?php
global $ClassPostTypeEventos;
global $ClassConfigApi;
$quantEventos = $ClassPostTypeEventos->getQuantEventosIndex()
?>
<?php
wp_reset_query();
if (have_posts()) : while (have_posts()) : the_post();

$d_inicio = date("d/m/Y", get_post_meta($post->ID, "dInicio", true));
$d_fim = get_post_meta($post->ID, "dFim", true);

$site_evento = get_post_meta($post->ID, "siteEvento", true);
$email_evento = get_post_meta($post->ID, "emailEvento", true);

$local_evento    = get_post_meta($post->ID, "localEvento", true);
$local_evento    = empty($local_evento)? '-' : $local_evento;

$endereco_evento = get_post_meta($post->ID, "enderecoEvento", true);
$endereco_evento    = empty($endereco_evento)? '-' : $endereco_evento;

$cidade_evento   = get_post_meta($post->ID, "cidadeEvento", true);
$cidade_evento    = empty($cidade_evento)? '-' : $cidade_evento;

$estado_evento   = get_post_meta($post->ID, "estadoEvento", true);
$estado_evento    = empty($estado_evento)? '-' : $estado_evento;

$lat_log_evento = get_post_meta($post->ID, "latLogEvento", true);

if ($lat_log_evento != ''){
    ?>
    <div class="map_event hidden-phone">
        <div id="map_canvas" style="width:100%; height:250px"></div>
    </div>

    <?php
    $key = $ClassConfigApi->getApiKeyGoogle();
    echo "<script src=\"http://maps.googleapis.com/maps/api/js?key=" . $key . "&sensor=true\" type=\"text/javascript\"></script>";
    ?>
    <script type="text/javascript">
    var latlng = new google.maps.LatLng(<?php echo $lat_log_evento; ?>);
    var myOptions = {
        zoom: 16,
        scrollwheel: false,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
    map.setTilt(45);
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        title:""
    });
    </script>

    <?php
}
?>

<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
        <div class="span8">

            <div class="row-fluid main-content single-event">
                <div class="span12 box">
                    <div class="row-fluid">
                        <?php
                        if( has_post_thumbnail() ){
                            ?>
                            <div class="span4">
                                <?php
                                $attr = array(
                                    'class' => "img-rounded img-polaroid top-thumbnails"
                                    );

                                echo the_post_thumbnail('medium',$attr);
                                ?>
                            </div>

                            <div class="span8">
                                <?php
                            } else {
                                ?>
                                <div class="span12">
                                    <?php
                                }
                                ?>
                                <h2 class="main-post"><?php the_title(); ?></h2>
                                <ul class="inline detalhes_event">
                                    <li><a href="<?php echo $site_evento; ?>" target="_blank"><?php echo $site_evento; ?></a></li>
                                    <li> <a target="_blank" href="mailto:<?php echo $email_evento; ?>?Subject=SCODB - DeMolay Brasil"><?php echo $email_evento; ?></a></li>
                                </ul>
                                <dl class="dl-horizontal">
                                    <dt>Quando Será</dt>
                                    <dd><?php echo $d_inicio .' - '. $d_fim; ?></dd>
                                    <dt>Local</dt>
                                    <dd><?php echo $local_evento?></dd>
                                    <dt>Endereço</dt>
                                    <dd><?php echo $endereco_evento  ?></dd>
                                    <dt>Cidade</dt>
                                    <dd><?php echo $cidade_evento .' - '. $estado_evento; ?></dd>
                                    <dt>Mapa</dt>
                                    <dd><a target="_blank" href="https://maps.google.com.br/maps?q=<?php echo $lat_log_evento; ?>&num=1&t=h&z=19" style="text-align:left">Exibir mapa ampliado</a></dd>
                                </dl>
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
                        </div>
                    </div>

                </div>

            </div>

            <!-- AREA DIREITA   ================================================== -->
            <div class="span4">
                <?php $args = 'before_widget=<div class="widget">&before_title=<div class="widget-titulo"><h4>&after_title=</h4></div>&after_widget=</div>'; ?>
                <?php $instance = "title=Proximos Eventos&quant=$quantEventos"; ?>
                <?php the_widget('Widget_eventos', $instance, $args); ?>
                <?php dynamic_sidebar('Sidebar Single'); ?>
            </div>
        </div>
    </div>
<?php endwhile; else: ?>
<?php endif; ?>
<?php get_footer(); ?>
