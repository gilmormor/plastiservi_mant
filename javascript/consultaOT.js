$(document).ready(function() {
	// Validar campos numericos de pantalla
/*
    $('#cedula').numeric();
    $('#referencia').numeric();
*/
    //*******************************************************************
	// Validar campos numericos de pantalla
    //*******************************************************************

    $("#selecConsult").hide();
	$('#trabTodos').tooltip({placement: "left"});
	$('#trabEjecu').tooltip({placement: "bottom"});
	$('#trabAsign').tooltip({placement: "right"});
	$( "#ModalCenter" ).draggable({opacity: 0.35, handle: ".modal-header"});

	$('.datepicker').datepicker({
		language: "es",
		autoclose: true,
		todayHighlight: true
	}).datepicker("setDate");


/* ordenar datatable	
	$("#asc").click(function()
	{
		var table = $("#tablaOrdTrab").DataTable();
		table.order( [[ 8, 'asc' ]] )
    .draw();
	});
	$("#desc").click(function()
	{
		var table = $("#tablaOrdTrab").DataTable();
		table.order( [[ 8, 'desc' ]] )
    .draw();
	});
*/


});

function ocultarMostrarFiltro(){
	if($('#divFiltros').css('display') == 'none'){
		$('#botonD').attr("class", "glyphicon glyphicon-chevron-up");
		$('#botonD').attr("title", "Ocultar Filtros");
	}else{
		$('#botonD').attr("class", "glyphicon glyphicon-chevron-down");
		$('#botonD').attr("title", "Mostrar Filtros");
	}

	$('#divFiltros').slideToggle(500);
}

$("#btnConsultar").click(function()
{
	consultar();
});


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


function limpiarInputOT(){
	$("#exampleModalLongTitle").html('Orden de Trabajo');
	$("#departamentoAreaIDM").val('');
	$("#ema_usuM").val('');
	$("#fechaHoraini").val('');
	$("#responsable").val('')
	$(".selectpicker").selectpicker('refresh');
	$("#responsable").attr("disabled", true);
	$("#descripTrabajo").val('');
	$("#mant").val('');
	$("#tipofalla").val('');
	$("#tipomant").val('');
	$("#descripTrabajo").val('');
	$("#ttmID").val('');
	$("#repuestosmat").val('');
	$("#observaciones").val('');
	$(".selectpicker").selectpicker('refresh');
}


function consultar()
{
	//alert('entro');
	//$("#tablaMaquinas").hide();
	var filas = $("#departamentoAreaID").val();
	//console.log(valTabla);
	//if((filas == null)){
	if((false)){
		alertify.error('Debes seleccionar al menos un area.');
	}else{
		var valTabla = [];
		if((filas != null)){
			for(j=0; j<filas.length; j++){ //Recorre las filas 1 a 1
				departamentoAreaID = filas[j];

				var fila = {
					departamentoAreaID
				};
				valTabla.push(fila);
			}			
		}
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_ordentrabmant.php',
				data 	: 
				{
					accion             : "consultaxFiltro",
					fechad             : $("#fechad").val(),
					fechah             : $("#fechah").val(),
					personaID          : $("#personaID").val(),
					departamentoAreaID : JSON.stringify(valTabla),
					staTrabajo         : $("#staTrabajo").val()
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
			$("#tablaMaquinas").html(datos.tabla);
			//$("#tablaMaquinas").fadeIn(2000);
			configurarTabla();
			$(".selectpicker").selectpicker('refresh');
		});
	}
}