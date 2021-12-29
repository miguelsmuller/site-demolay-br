/*global google:false, MarkerClusterer:false, common_params:false*/
/* #############################################################
# VARIAVEIS
############################################################# */
var urlApi = 'http://www.wsold.demolay.org.br/api';
var urlTemplate = common_params.files_url;

var map;
var infowindow;
var geocoder;

var markerCluster;
var markerListCapitulos = [];
var markerListConventos = [];

var ajaxCount;

/* #############################################################
# INICIALIZAÇÃO DO MAPA
############################################################# */
function initialize() {
    //INICIALIZA O MAPA
    var mapOptions = {
        zoom: 4,
        mapTypeControl: false,
        streetViewControl: false,
        center: new google.maps.LatLng(-12.709053, -51.110798),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

    //DEFINE MARKER CLUSTER
    var markerClusterStyle = [{
        textColor: 'white',
        url: urlTemplate + '/assets/images/maps/m1.png',
        height: 52,
        width: 52
    }];
    markerCluster = new MarkerClusterer(map);
    markerCluster.setStyles(markerClusterStyle);

    //ADICIONA EVENTO AO MAPA
    google.maps.event.addListener(map, 'click', function(event) {
        var latitude = event.latLng.lat();
        var longitude = event.latLng.lng();
        infowindow.close();
        map.panTo( new google.maps.LatLng(latitude,longitude) );
    });

    //????
    geocoder = new google.maps.Geocoder();
}
initialize();

/* #############################################################
# EXTRA FUNCTIONS
############################################################# */
// FORMATA STRING COM A PRIMEIRA LETRA DAS PALAVRAS EM MAISCULO
function eachWord(str){
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

/* #############################################################
# GET CAPITULO
############################################################# */
function getCapitulos(){
    $.ajax({
        type: 'GET',
        url: urlApi + '/capitulos_mapa/format/json',
        success: function(json){
            $.each(json, function(index, capitulo) {

                if (!capitulo.geolocal) { return true; }
                var geolocal = capitulo.geolocal.split(',');
                var nome = 'Capítulo ' + eachWord(capitulo.nm_cap) + ' Nº' + capitulo.nr_cap;

                var attributes = {
                    latitude    : geolocal[0],
                    longitude   : geolocal[1],
                    tipo        : 'capitulo',
                    nome        : nome,
                    endereco    : eachWord(capitulo.end_ds),
                    complemento : eachWord(capitulo.end_complemento),
                    cep         : capitulo.end_cep,
                    email       : (capitulo.con_email) ? capitulo.con_email.toLowerCase() : '',
                    site        : (capitulo.con_site) ? capitulo.con_site.toLowerCase() : '',
                    cidade      : eachWord(capitulo.NM_CIDADE),
                    estado      : capitulo.uf_gce,
                    situacao    : capitulo.st_atual
                };
                makeMarker(attributes);
            });

            if((markerCluster instanceof MarkerClusterer)) {
                markerCluster.addMarkers(markerListCapitulos);
            }
        },
        complete : function(){
            ajaxCount++;
        }
    });
}

/* #############################################################
# GET CONVENTOS
############################################################# */
function getConventos(){
    $.ajax({
        type: 'GET',
        url: urlApi + '/conventos_mapa/format/json',
        dataType: 'json',
        success: function(json){
            $.each(json, function(index, capitulo) {

                if (!capitulo.geolocal) { return true; }
                var geolocal = capitulo.geolocal.split(',');
                var nome = 'Convento ' + eachWord(capitulo.nm_convento) + ' Nº' + capitulo.nr_convento;

                var attributes = {
                    latitude    : geolocal[0],
                    longitude   : geolocal[1],
                    tipo        : 'convento',
                    nome        : nome,
                    endereco    : eachWord(capitulo.end_ds),
                    complemento : eachWord(capitulo.end_complemento),
                    cep         : capitulo.end_cep,
                    email       : (capitulo.con_email) ? capitulo.con_email.toLowerCase() : '',
                    site        : (capitulo.con_site) ? capitulo.con_site.toLowerCase() : '',
                    cidade      : eachWord(capitulo.NM_CIDADE),
                    estado      : capitulo.uf_gce,
                    situacao    : capitulo.st_atual
                };
                makeMarker(attributes);
            });

            if((markerCluster instanceof MarkerClusterer)) {
                markerCluster.addMarkers(markerListConventos);
            }
        },
        complete : function(){
            ajaxCount++;
        }
    });
}

/* #############################################################
# CRIAR MARCADOR
############################################################# */
function makeMarker(attributes){
    var iconMarker;
    switch(attributes.tipo) {
        case 'capitulo':
            iconMarker = urlTemplate + '/assets/images/maps/marker-capitulo.png';
            break;
        case 'convento':
            iconMarker = urlTemplate + '/assets/images/maps/marker-convento.png';
            break;
    }

    //CRIA UM MARCADOR
    var marker = new google.maps.Marker({
        map: map,
        position: new google.maps.LatLng(attributes.latitude, attributes.longitude),
        title: attributes.nome,
        icon: iconMarker
    });

    //CRIA O CONTEUDO DO JANELA DE INFORMAÇÃO
    var contentString = '<div id="info-content">'+
      '<table>'+
        '<tr id="iw-url-row" class="iw_table_row">'+
          '<td id="iw-url" class="iw_title" colspan="2">'+ attributes.nome +'</td>'+
        '</tr>'+
        '<tr id="iw-address-row" class="iw_table_row">'+
          '<td class="iw_attribute_name">Endereço:</td>'+
          '<td id="iw-address">'+ attributes.endereco +'</td>'+
        '</tr>'+
        '<tr id="iw-cidade-row" class="iw_table_row">'+
          '<td class="iw_attribute_name">Cidade:</td>'+
          '<td id="iw-cidade">'+ attributes.cidade +' - '+ attributes.estado +'</td>'+
        '</tr>'+
        '<tr id="iw-cep-row" class="iw_table_row">'+
          '<td class="iw_attribute_name">CEP:</td>'+
          '<td id="iw-cep">'+ attributes.cep +'</td>'+
        '</tr>';

    if (attributes.email) {
        contentString = contentString + '<tr id="iw-email-row" class="iw_table_row">'+
          '<td class="iw_attribute_name">Email:</td>'+
          '<td id="iw-email"><a href="mailto:'+ attributes.email +'" target="_blank">'+ attributes.email +'</a></td>'+
        '</tr>';
    }

    if (attributes.site) {
        contentString = contentString + '<tr id="iw-website-row" class="iw_table_row">'+
          '<td class="iw_attribute_name">Website:</td>'+
          '<td id="iw-website"><a href="http://'+ attributes.site +'" target="_blank">'+ attributes.site +'</a></td>'+
        '</tr>';
    }

    contentString = contentString + '</table>'+
    '</div>';

    //CRIA A JANELA DE INFORMAÇÃO
    infowindow = new google.maps.InfoWindow({
        content: contentString,
        maxWidth: 200
    });

    //ADICIONA UM EVENTO AO CLICK DO MARCADOR
    google.maps.event.addListener(marker, 'click', function() {
        if (infowindow) { infowindow.close(); }
        infowindow = new google.maps.InfoWindow({content: contentString, maxWidth: 700});
        infowindow.open(map, marker);
        map.panTo(marker.getPosition());
    });

    //ADICIONA AO ARRAY ESPECIFICO
    switch(attributes.tipo) {
        case 'capitulo':
            markerListCapitulos.push(marker);
            break;
        case 'convento':
            markerListConventos.push(marker);
            break;
    }
}

/* #############################################################
# MUDAR TIPO DE MAPA
############################################################# */
function changeMapType(mapType) {
    map.setMapTypeId(google.maps.MapTypeId[mapType]);
}

/* #############################################################
# REMOÇÃO DE MARCADORES
############################################################# */
function removeCapitulos(){
    if((markerCluster instanceof MarkerClusterer)) {
        for (var i = 0; i < markerListCapitulos.length; i++) {
            markerListCapitulos[i].setMap(null);
        }
        markerListCapitulos = [];
        markerCluster.clearMarkers();
    }
}
function removeConventos(){
    if((markerCluster instanceof MarkerClusterer)) {
        for (var i = 0; i < markerListConventos.length; i++) {
            markerListConventos[i].setMap(null);
        }
        markerListConventos = [];
        markerCluster.clearMarkers();
    }
}

/* #############################################################
# AÇÕES DO MAPA
############################################################# */
$(document).ready(function () {

    //CRIAÇÃO DOS PONTOS
    function updateMap() {
        ajaxCount = 0;
        $('#map_overlay').addClass('open');

        if ($('#showCapitulos').prop('checked')) {
            removeCapitulos();
            getCapitulos();
        } else {
            removeCapitulos();
            ajaxCount++;
        }

        if ($('#showConventos').prop('checked')) {
            removeConventos();
            getConventos();
        }else {
            removeConventos();
            ajaxCount++;
        }
    }
    updateMap();

    //AO FINALIZAR AS REQUIZIÇÕES AJAX
    $( document ).ajaxComplete(function() {
        if (ajaxCount === 2) {
            $('#map_overlay').removeClass('open');
            ajaxCount = 0;
        }
    });

    //DIRECIONAR MAPA PARA ENDEREÇO
    function goMapTo(endereco) {
        geocoder.geocode({ 'address': endereco + ', Brasil', 'region': 'BR' }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    $('#txtEndereco').val(results[0].formatted_address);
                    $('#txtLatitude').val(latitude);
                    $('#txtLongitude').val(longitude);

                    var location = new google.maps.LatLng(latitude, longitude);
                    //marker.setPosition(location);
                    map.panTo(location);
                    map.setZoom(13);
                }
            }
        });
    }

    // FILTRO - TIPO DE MAPA
    $('#mapTypeRoadmap').on('ifChecked', function(){
        changeMapType('ROADMAP');
    });
    $('#mapTypeSatellite').on('ifChecked', function(){
        changeMapType('HYBRID');
    });

    // FILTRO - ENDEREÇO
    $('#txtEndereco').autocomplete({
        delay: 80,
        source: function (request, response) {
            geocoder.geocode({ 'address': request.term + ', Brasil', 'region': 'BR' }, function (results) {
                response($.map(results, function (item) {
                    return {
                        label: item.formatted_address,
                        value: item.formatted_address,
                        latitude: item.geometry.location.lat(),
                        longitude: item.geometry.location.lng()
                    };
                }));
            });
        },
        select: function (event, ui) {
            //console.log(geocoder.geocode);
            $('#txtLatitude').val(ui.item.latitude);
            $('#txtLongitude').val(ui.item.longitude);
            var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
            map.panTo(location);
            map.setZoom(13);
        }
    });
    $('#txtEndereco').blur(function() {
        if($(this).val() !== ''){
            goMapTo($(this).val());
        }
    });

    // FILTRO - TIPOS
    $('#showCapitulos').on('ifToggled', function(){
        updateMap();
    });
    $('#showConventos').on('ifToggled', function(){
        updateMap();
    });
});
