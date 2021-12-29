jQuery(document).ready(function($) {
    $("#form_verificar_regularidade").submit( function () {
        verificar_regularidade();
        $('#txtCID').select();
        return false;
    } );
    $('#txtCID').keypress(function() {
        limpar_alerta();
    });

    function limpar_alerta(){
        if ($('#form_verificar_regularidade .control-group').hasClass("warning")){
            $('#form_verificar_regularidade .alert').fadeOut('slow', function() {
                $('#form_verificar_regularidade .alert').remove();
            });
            $('#form_verificar_regularidade .control-group').removeClass("warning");
        }
    }

    function verificar_regularidade(){
        var CID = $('#txtCID').val();
        var URL = 'http://webservice.demolay.org.br/api/associado/cid/'+ CID +'/format/json';

        if (CID == '' || !$.isNumeric(CID)){
            $('#form_verificar_regularidade .control-group').addClass("warning");
            $('#form_verificar_regularidade .alert').remove();
            $('#form_verificar_regularidade').hide().prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Número da CID inválida!</strong></div>').fadeIn("slow");
        }else{
            limpar_alerta();
            $('#form_verificar_regularidade .alert').remove();
            $.getJSON(URL,function(data){

                var RELATORIO = '';
                RELATORIO += '<div id="rel'+ data[0].CID +'" class="relatorio">';
                RELATORIO += '<div class="row-fluid">';
                RELATORIO += '<div class="span9">';
                RELATORIO += '<p>'+ data[0].NOME +'</br>';
                RELATORIO += 'Regular para o ano de '+ data[0].REG_ANO +'</p>';
                RELATORIO += '</div>';
                RELATORIO += '<div class="span3">';
                RELATORIO += '<a href="modal'+ data[0].CID +'" role="button" class="btn btn-red pull-right" data-toggle="modal" data-target="#modal'+ data[0].CID +'">';
                RELATORIO += '<i class="icon-list-alt icon-large"></i>';
                RELATORIO += '</a>';
                RELATORIO += '</div>';
                RELATORIO += '</div>';
                RELATORIO += '</div>';

                var MODAL = '';
                MODAL += '<div id="modal'+ data[0].CID +'" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
                MODAL += '<div class="modal-header">';
                MODAL += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
                MODAL += '<h3 id="myModalLabel">'+ data[0].NOME +'</h3>';
                MODAL += '</div>';
                MODAL += '<div class="modal-body">';
                MODAL += '<p><strong>Nome:</strong> '+ data[0].NOME +'<br/>';
                MODAL += '<strong>CID:</strong> '+ data[0].CID + ', regular para o ano de '+ data[0].REG_ANO + '</p>';

                MODAL += '<p><strong>Capítulo:</strong> '+ data[0].nm_cap +' Nº '+ data[0].nr_cap +'<br/>';
                MODAL += '<strong>D. Iniciação:</strong> '+ data[0].dm_dt_iniciacao +'<br/>';
                if (data[0].dm_dt_elevacao != '') MODAL += '<strong>D. Elevação:</strong> '+ data[0].dm_dt_elevacao +'</p>';

                if  (data[0].cav_dt_investidura != '') {                        
                    MODAL += '<p><strong>Convento:</strong> '+ data[0].nm_convento +' Nº '+ data[0].nr_convento +'<br/>';
                    if (data[0].cav_dt_investidura != '') MODAL += '<strong>D. Investidura:</strong> '+ data[0].cav_dt_investidura +'<br/>';
                    if (data[0].cav_dt_grau_capela != '') MODAL += '<strong>D. Capela:</strong> '+ data[0].cav_dt_grau_capela +'<br/>';
                    if (data[0].cav_dt_grau_salem != '') MODAL += '<strong>D. Salém:</strong> '+ data[0].cav_dt_grau_salem +'<br/>';
                    if (data[0].cav_dt_grau_ex_templario != '') MODAL += '<strong>D. Ex-Templário:</strong> '+ data[0].cav_dt_grau_ex_templario +'<br/>';
                    if (data[0].cav_dt_grau_triade != '') MODAL += '<strong>D. Tríade:</strong> '+ data[0].cav_dt_grau_triade +'<br/>';
                    if (data[0].cav_dt_grau_ebano != '') MODAL += '<strong>D. Ébano:</strong> '+ data[0].cav_dt_grau_ebano +'<br/>';
                    if (data[0].cav_dt_grau_anon != '') MODAL += '<strong>D. Anon:</strong> '+ data[0].cav_dt_grau_anon +'<br/>';
                    if (data[0].cav_dt_grau_cadencia != '') MODAL += '<strong>D. Cadência:</strong> '+ data[0].cav_dt_grau_cadencia +'<br/>';
                    if (data[0].cav_dt_grau_comendador != '') MODAL += '<strong>D. Comendador da Cavalaria:</strong> '+ data[0].cav_dt_grau_comendador +'<br/>';
                    if (data[0].cav_dt_grau_grande_cruz != '') MODAL += '<strong>D. Grande Cruz:</strong> '+ data[0].cav_dt_grau_grande_cruz +'<br/>';
                    if (data[0].cav_dt_grau_manto != '') MODAL += '<strong>D. Manto Prateado:</strong> '+ data[0].cav_dt_grau_manto +'</p>';
                }

                if  (data[0].chev_dt_sagracao != '') {
                    MODAL += '<p><strong>Corte:</strong> '+ data[0].nm_corte +' Nº '+ data[0].nr_corte +'<br/>';
                    MODAL += '<strong>D. Sagração:</strong> '+ data[0].chev_dt_sagracao +'</p>';
                }

                MODAL += '</div>';
                MODAL += '<div class="modal-footer">';
                MODAL += '<button class="btn btn-green" data-dismiss="modal" aria-hidden="true">Fechar</button>';
                MODAL += '</div>';
                MODAL += '</div>';                                                                                                                                                                                                                                                                                                               
                $('#form_verificar_regularidade').hide().append( RELATORIO + MODAL ).fadeIn("slow");
            })                      
}
}

});