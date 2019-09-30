$(document).ready(function() {
	/*
	// Validar campos numericos de pantalla agregar_deposito.php IUFRONT
	$( "#dialog_carga" ).dialog(
	{
		autoOpen: false,
		width: 150,
		height: 130,
		modal: true,
		open:function()
		{
			$('.ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-icon-only.ui-dialog-titlebar-close').remove();
		},
		buttons: {}
	});
	$(document).ajaxStart(function(){$("#dialog_carga").dialog( "open" );});
	$(document).ajaxComplete(function(){$("#dialog_carga").dialog( "close" );});
	*/
    $('#cedula').numeric();
    $('#referencia').numeric();
    //*******************************************************************
	// Validar campos numericos de pantalla agregar_conveniosofitasa.php
    //*******************************************************************

	$('.datepicker').datepicker({
		language: "es",
		autoclose: true,
		todayHighlight: true
	}).datepicker("setDate", new Date());


	$('#btnguardardep').attr("disabled", true); //deshabilitar boton de agregar deposito iufront

	$('#cedula').focus();

	$("#consultar").click(function(e)
	{

	});
	reporte();
	$("#mostrarparametos").click(function(e)
	{
		$("#tablaListaNegra").html('');
		$('.parametros').slideDown();
		$("#mostrarparametos").css("display", "none");
	});

});

function reporte(){
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_usuario.php',
				data 	: 
				{
					accion: "consultarClave"
				},
				dataType: 'json',
				encode	: true
		})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{


			}else
			{
				alertify.error(datos.mensaje);
			}
			$("#tablaListaNegra").html(datos.tabla);
			configurarTabla();
			//$("#mostrarparametos").css("display", "block");

		});
}

function configurarTabla(){
	$('.AllDataTables').DataTable({
		language: {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
			    "sFirst":    "Primero",
			    "sLast":     "Último",
			    "sNext":     "Siguiente",
			    "sPrevious": "Anterior"
			},
			"oAria": {
			    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
			},
			decimal: "."
		}
	});

}