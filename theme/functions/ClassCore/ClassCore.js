jQuery(document).ready(function ($) {
	jQuery.datepicker.setDefaults({
		dateFormat: "dd/mm/yy",
		monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
		dayNames: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
		dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
		currentText: "Hoje",
		numberOfMonths: 1,
		showButtonPanel: false
	});

    jQuery( "#dInicio" ).datepicker({
        defaultDate: "+1w",
        onClose: function( selectedDate ) {
            jQuery( "#dFim" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    jQuery( "#dFim" ).datepicker({
        defaultDate: "+1w",
        onClose: function( selectedDate ) {
            jQuery( "#dInicio" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
});