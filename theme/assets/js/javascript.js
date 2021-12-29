/* #############################################################
# CONTEUGO GERAL do arquivo
############################################################# */
jQuery(document).ready(function ($) {
    // TOOLTIP AND POPOVES
    $('[data-toggle=tooltip]').tooltip();
    $('[data-toggle=popover]').popover({
        trigger: 'hover'
    });
    $("a[rel^='lightbox']").prettyPhoto({
        social_tools: false
    });
    $(".scroll").mCustomScrollbar({
        theme:"dark-thick"
    });
    $(window).scroll(function(event) {
        var x = 0 - $(this).scrollLeft();
        var y = $(this).scrollTop();

        // whether that's below the form
        if (y >= 230) {
            // if so, ad the fixed class
            $('.scroll_fixed').addClass('fixed');
        } else {
            // otherwise remove it
            $('.scroll_fixed').removeClass('fixed');
        }
    });
    $('#menu-secundario.dropdown-toggle').dropdown();
    $('.dropdown-menu-popup').find('form').click(function (e) {
     e.stopPropagation();
 });
});


/*
}*/
/* #############################################################
# GOOGLE MAPS
############################################################# */
jQuery(document).ready(function ($) {
    function listar(){
        var UF = $("#input_gce").val();

        if (UF.length !== 0){
            desabilitar_form();

            $.ajax({
                type: "GET",
                url: "http://webservice.demolay.org.br/api/cidades/estado/" + UF +"/format/json",
                dataType: "json",
                success: function(json){
                    var options = "";
                    for($i=0; $i < json.length; $i++){
                        options += '<option value="' + json[$i].CD_CIDADE + '">' + json[$i].NM_CIDADE + '</option>';
                    }
                    $("#input_cidade").html(options);
                },
                complete : function(){
                    habilitar_form();
                }
            });
        }else{
            $("#input_cidade").html('<option value=" ">Selecione...</option>');
        }
    }

    function habilitar_form(){
        $('#input_cidade').prop('disabled', false);
        $('#send_form').removeClass("disabled").html('Buscar Informações');
    }

    function desabilitar_form(){
        $('#input_cidade').prop('disabled', true);
        $('#send_form').addClass("disabled").html('<i class="icon-refresh icon-spin"></i>');
    }

    function lista_capitulos(){
        if ($('#input_cidade').length !== 0){
            $('#relatorio').hide().html('');

            desabilitar_form();

            var input_tipo = $('#input_tipo').val();
            var input_gce = $('#input_gce').val();
            var input_cidade = $('#input_cidade').val();

            var URL = 'http://webservice.demolay.org.br/api/capitulos/situacao/a/cidade/'+ input_cidade +'/format/json';

            $.getJSON(URL,function(json){
                //console.log(json);
                var RELATORIO = '';

                if (json.length !== 0 ){
                    RELATORIO += '<div class="accordion" id="accordion">';

                    for($i=0; $i < json.length; $i++){
                        RELATORIO += '<div class="accordion-group">';
                        RELATORIO += '<div class="accordion-heading">';
                        RELATORIO += '<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#'+ json[$i].nr_cap +'">';
                        RELATORIO += 'Capítulo ' + json[$i].nm_cap + ' Nº' + json[$i].nr_cap;
                        RELATORIO += '</a>';
                        RELATORIO += '</div>';
                        RELATORIO += '<div id="'+ json[$i].nr_cap +'" class="accordion-body collapse">';
                        RELATORIO += '<div class="accordion-inner"><div class="row-fluid"><div class="span12">';
                        RELATORIO += '<p>Endereço: '+ json[$i].end_ds +'<br/>';
                        RELATORIO += 'Complemento: '+ json[$i].end_complemento +'<br/>';
                        RELATORIO += 'CEP: '+ json[$i].end_cep +'</p>';
                        RELATORIO += '<p>Site: '+ json[$i].con_site +'<br/>';
                        RELATORIO += 'E-Mail: '+ json[$i].con_email +'</p>';
                        RELATORIO += '</div></div></div>';
                        RELATORIO += '</div>';
                        RELATORIO += '</div>';
                    }
                    RELATORIO += '</div>';
                }
                $('#relatorio').hide().html('').append( RELATORIO ).fadeIn("slow");
            })
            .fail(function() {
                $('#relatorio').hide().append( '<h3>Nenhum capítulo nessa localidade</h3>' ).fadeIn("slow");
            })
            .always(function() {
                habilitar_form();
            });
        }
    }

    if ($("#map-canvas").length){

        $("#input_gce").change(function(){
            listar();
        });

        $("#lista-capitulos").submit( function () {
            lista_capitulos();
            return false;
        });
    }
});























/**
* Returns an XMLHttp instance to use fdor asynchronous
* downloading. This method will never throw an exception, but will
* return NULL if the browser does not support XmlHttp for any reason.
* @return {XMLHttpRequest|Null}
*/
function createXmlHttpRequest() {
    try {
        if (typeof ActiveXObject != 'undefined') {
             return new ActiveXObject('Microsoft.XMLHTTP');
        } else if (window.XMLHttpRequest) {
            return new XMLHttpRequest();
        }
    } catch (e) {
        changeStatus(e);
    }
    return null;
}

/**
* This functions wraps XMLHttpRequest open/send function.
* It lets you specify a URL and will call the callback if
* it gets a status code of 200.
* @param {String} url The URL to retrieve
* @param {Function} callback The function to call once retrieved.
*/
function downloadUrl(url, callback) {
    var status = -1;
    var request = createXmlHttpRequest();
    if (!request) {
        return false;
    }

    request.onreadystatechange = function() {
        if (request.readyState == 4) {
            try {
                status = request.status;
            } catch (e) {
                // Usually indicates request timed out in FF.
            }
            if (status == 200) {
                callback(request.responseXML, request.status);
                request.onreadystatechange = function() {};
            }
        }
    };

    request.open('GET', url, true);

    try {
        request.send(null);
    } catch (e) {
        changeStatus(e);
    }
}

/**
 * Parses the given XML string and returns the parsed document in a
 * DOM data structure. This function will return an empty DOM node if
 * XML parsing is not supported in this browser.
 * @param {string} str XML string.
 * @return {Element|Document} DOM.
 */
 function xmlParse(str) {
  if (typeof ActiveXObject != 'undefined' && typeof GetObject != 'undefined') {
    var doc = new ActiveXObject('Microsoft.XMLDOM');
    doc.loadXML(str);
    return doc;
}

if (typeof DOMParser != 'undefined') {
    return (new DOMParser()).parseFromString(str, 'text/xml');
}

return createElement('div', null);
}

/**
 * Appends a JavaScript file to the page.
 * @param {string} url
 */
 function downloadScript(url) {
  var script = document.createElement('script');
  script.src = url;
  document.body.appendChild(script);
}
