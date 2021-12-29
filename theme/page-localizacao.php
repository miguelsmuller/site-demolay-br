<?php
/*
Template Name: Page Onde Estamos
*/
?>
<?php get_header(); ?>
<div class="container wrap">
    <div class="row">

        <!-- AREA ESQUERDA  ================================================== -->
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

                <div class="row-fluid main-content">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $input_gce = $_POST['input_gce'];
                    $input_cidade = $_POST['input_cidade'];
                    $input_tipo = 'capitulos';
                }
                ?>
                    <div class="span12 well">
                        <form id="lista-capitulos" class="validate" method="post" action="">

                            <div class="row-fluid">

                                <div class="span4">
                                    <div class="control-group">
                                        <?php
                                            $dom_gces = new DOMDocument();
                                            if (@$dom_gces->load(urlWebService.'/gces') === TRUE)
                                            {
                                                $gces =  $dom_gces->getElementsByTagName("item");
                                            }
                                        ?>
                                        <label class="control-label" for="input_gce">GCE Suborninado: </label>
                                        <select id="input_gce" name="input_gce" class="span12" >
                                            <option value="">Selecione...</option>
                                            <?php
                                            if ( count( $gces ) >=1 )
                                            {
                                                foreach($gces as $gce)
                                                {
                                                    $NO_UF_GCE = $gce->getElementsByTagName("UF_GCE");
                                                    $UF_GCE  = $NO_UF_GCE->item(0)->nodeValue;

                                                    $NO_NM_GCE = $gce->getElementsByTagName("NM_GCE");
                                                    $NM_GCE  = ucwords(convertem($NO_NM_GCE->item(0)->nodeValue, 0));

                                                    echo '<option value="'.$UF_GCE.'">'. $NM_GCE .'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="span5">
                                    <div class="control-group">
                                        <label class="control-label" for="input_cidade">Cidade: </label>
                                        <select id="input_cidade" name="input_cidade" class="span12" >
                                            <option value="">Selecione...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="span3">
                                    <div class="control-group">
                                        <label class="control-label" for="input_tipo">Tipo de Organização: </label>
                                        <select id="input_tipo" name="input_tipo" class="span12" required >
                                            <option value="capitulos">Capítulo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="offset3 span6">
                                    <button id="send_form" class="btn btn-block btn-green" type="submit">Buscar Informações</button>
                                </div>
                            </div>

                        </form>
                    </div>



                </div>

                <div id="relatorio" class="well widget-conteudo widget-accordion rounded">
                    <h3>Faça uma pesquisa usando os critérios acima</h3>
                </div>

            </div>
            </div>
            </div>
            </div>

            <div class="group-map">
                <div id="map-canvas" style="width:100%; height:600px;"></div>

                <script>
                var map;
                var infowindow;

                var image = {
                    url: 'http://demolay.org.br/wp-content/themes/SCODB/assets/images/icons/location.png',
                    // This marker is 20 pixels wide by 32 pixels tall.
                    size: new google.maps.Size(20, 32),
                    // The origin for this image is 0,0.
                    origin: new google.maps.Point(0,0),
                    // The anchor for this image is the base of the flagpole at 0,32.
                    anchor: new google.maps.Point(0, 32)
                };

                function initialize() {
                    var mapOptions = {
                        zoom: 4,
                        center: new google.maps.LatLng(-15.792254,-47.988281),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

                    //google.maps.event.trigger(map, "resize");

                    downloadUrl(urlWebService"http://webservice.demolay.org.br/api/capitulos_mapa", function(data) {
                        var markers = data.documentElement.getElementsByTagName("item");

                        for (var i = 0; i < markers.length; i++) {
                            var nm_cap =  (markers[i].getElementsByTagName("nm_cap")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("nm_cap")[0].childNodes[0].nodeValue : '';
                            var varGeolocal =  (markers[i].getElementsByTagName("geolocal")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("geolocal")[0].childNodes[0].nodeValue : '';

                            geolocal=varGeolocal.split(",");
                            var latlng = new google.maps.LatLng(parseFloat(geolocal[0]),
                                        parseFloat(geolocal[1]));

                            var args = {
                                endereco: (markers[i].getElementsByTagName("end_ds")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("end_ds")[0].childNodes[0].nodeValue : '',
                                complemento: (markers[i].getElementsByTagName("end_complemento")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("end_complemento")[0].childNodes[0].nodeValue : '',
                                cidade: (markers[i].getElementsByTagName("NM_CIDADE")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("NM_CIDADE")[0].childNodes[0].nodeValue : '',
                                estado: (markers[i].getElementsByTagName("uf_gce")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("uf_gce")[0].childNodes[0].nodeValue : '',
                                cep: (markers[i].getElementsByTagName("end_cep")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("end_cep")[0].childNodes[0].nodeValue : '',
                                email: (markers[i].getElementsByTagName("con_email")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("con_email")[0].childNodes[0].nodeValue : '',
                                site: (markers[i].getElementsByTagName("con_site")[0].childNodes.length != 0) ? markers[i].getElementsByTagName("con_site")[0].childNodes[0].nodeValue : ''
                            };

                            var marker = createMarker(nm_cap, latlng, args);
                        }
                    });
                }

                function createMarker(name, latlng, args) {
                    var contentString = '<div id="content">'+
                      '<div id="siteNotice">'+
                      '</div>'+
                      '<h3 id="firstHeading" class="firstHeading">Capítulo '+ name +'</h3>'+
                      '<div id="bodyContent">'+
                      '<p><b>Endereço: </b>'+ args['endereco'] + ' - ' + args['complemento'] + '<br/>' +
                      '<b>Cidade/Estado: </b>'+ args['cidade'] + ' - ' + args['estado'] + '<br/>' +
                      '<b>CEP: </b> '+ args['cep'] + ' <br/>' +
                      '<p><b>Site: </b><a href="http://'+ args['site'] +'" target="_blank">'+ args['site'] + '</a><br/>' +
                      '<b>E-mail: </b><a href="mailto:'+ args['email'] +'?Subject=Contato pelo site do SCODB" target="_blank">'+ args['email'] + '</a></p>' +
                      '</div>'+
                      '</div>';

                    var marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        icon: image
                    });
                    google.maps.event.addListener(marker, "click", function() {
                    if (infowindow) infowindow.close();
                        infowindow = new google.maps.InfoWindow({content: contentString, maxWidth: 400});
                        infowindow.open(map, marker);
                    });
                    return marker;
                }

                google.maps.event.addDomListener(window, 'load', initialize);

                </script>

            </div>

        <?php endwhile; else: ?>
        <?php endif; ?>
        </div>

        <!-- AREA DIREITA   ================================================== -->
        <div class="span4">
            <?php dynamic_sidebar('Sidebar Single'); ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>