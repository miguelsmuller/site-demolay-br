<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php
/*
Template Name: Página Localização
*/
?>

<?php get_header(); ?>

<!-- HEADER PAGE -->
<header id="header-page">
    <div class="container">
        <div class="col-md-8">
            <h1>Localização de instituições filiadas</h1>
        </div>
    </div>
</header>

<!-- TRENDING TOPICS -->
<section id="trending">
    <ul id="footer-tags">
        <li><a href="#" rel="tag" class="label label-gray label-trending">Dia das Mães</a></li>
        <li><a href="#" rel="tag" class="label label-gray label-trending">Eleições 2014</a></li>
        <li><a href="#" rel="tag" class="label label-gray label-trending">Gabinete Juvenil</a></li>
        <li><a href="#" rel="tag" class="label label-gray label-trending">Liderança Adulta</a></li>
        <li><a href="#" rel="tag" class="label label-gray label-trending">Filantropia</a></li>
        <li><a href="#" rel="tag" class="label label-gray label-trending">Doações</a></li>
        <li><a href="#" rel="tag" class="label label-gray label-trending">Solidariedade</a></li>
    </ul>
    <a href="#"><img src="<?php bloginfo('template_directory'); ?>/assets/images/loja-demolay.png" alt="Loja DeMolay - SCODB"></a>
</section>

<!-- MAIN CONTENT AREA -->
<main id="container-map" role="main">

    <input type="hidden" id="txtLatitude" name="txtLatitude" />
    <input type="hidden" id="txtLongitude" name="txtLongitude" />

    <div id="map_canvas" style="width:100%; height:480px"></div>

    <div id="map_filter" class="panel panel-purple">
        <div class="panel-heading">Opções de filtros</div>
        <div class="panel-body">
            <fieldset>
                <div class="radio">
                    <label>
                        <input type="radio" name="optionsRadios" id="mapTypeRoadmap" value="mapTypeRoadmap" onclick="alert('hello');" checked> Mapa
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="optionsRadios" id="mapTypeSatellite" value="mapTypeSatellite" onclick="alert('hello');"> Satélite
                    </label>
                </div>
            </fieldset>

            <fieldset>
                <input type="text" id="txtEndereco" name="txtEndereco" class="form-control" placeholder="Rua, Cidade ou Estado">
            </fieldset>

            <fieldset>
                <div class="checkbox">
                    <label>
                        <input id="showCapitulos" type="checkbox" value="" checked> Capítulos DeMolays
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input id="showConventos" type="checkbox" value=""> Conventos da Cavalaria
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input id="showTavolas" type="checkbox" value=""> Távolas de Escudeiros
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input id="showCortes" type="checkbox" value=""> Côrtes Chevaliers
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input id="showGces" type="checkbox" value=""> Grandes Capítulos Estaduais
                    </label>
                </div>
            </fieldset>
            <ul class="help">
                <li>
                    <button data-toggle="modal" data-target="#modalNaoAparece" class="btn btn-link">
                        Porque minha instituição não aparece no mapa ?
                    </button>
                </li>
                <li>
                    <button data-toggle="modal" data-target="#modalAlterarLocalizacao" class="btn btn-link">
                        Como alterar a localização da instituição ?
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <div id="map_overlay">
        <div class="loader">Loading...</div>
    </div>
</main>

<div class="modal fade" id="modalNaoAparece" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Porque minha instituição não aparece no mapa ?</h4>
            </div>
            <div class="modal-body">
                <p>O sistema de localização de instituições utilizado aqui foi implementado usando a integração entre o <strong>Google Maps</strong> e o <strong>SISDM</strong>.</p>
                <p>As localizações são baseadas em <strong>coordenadas geográficas</strong> (Latitude e longitude).</p>
                <p>Os motivos mais comuns para não exibição do marcador são:</p>
                <ul>
                    <li>A coordenada não está cadastrada no SISDM.</li>
                    <li>O formato de coordenada é inválido.</li>
                    <li>Foi cadastrada uma coordenada diferente da realidade.</li>
                    <li>A instituição não está ativa junto ao SISDM.</li>
                </ul>
                <p>Qualquer dúvida entre em contato com <strong>Comissão de Informatica</strong>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-purple" data-dismiss="modal">OK, intendi...</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAlterarLocalizacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Como alterar a localização da instituição ?</h4>
            </div>
            <div class="modal-body">
                Somente os Grandes Capitulos estad
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-purple" data-dismiss="modal">OK, intendi...</button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
