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
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion             : "graficoEvalOT",
				fechad             : $("#fechad").val(),
				fechah             : $("#fechah").val(),
				departamentoAreaID : $("#departamentoAreaID").val()
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(true)
		{
			$('.resultados').html('<canvas id="grafico"></canvas>');
			//$("#grafico").html('');
			fechaHoraini = [];
			contSOT      = [];
			sinvalidar   = [];
			malo         = [];
			regular      = [];
			bien         = [];
			muyBien      = [];
			excelente    = [];
			for(i=0;i<datos.length;i++){
				fechaHoraini[i]  = datos[i]['fechaHoraini'];
				contSOT[i]       = datos[i]['contSOT'];
				sinvalidar[i]    = datos[i]['sinvalidar'];
				malo[i]          = datos[i]['malo'];
				regular[i]       = datos[i]['regular'];
				bien[i]          = datos[i]['bien'];
				muyBien[i]       = datos[i]['muyBien'];
				excelente[i]     = datos[i]['excelente'];
			}
			var color = Chart.helpers.color;
			var Datos = {
					labels : fechaHoraini,
					datasets : [{
							label: 'Solicitudes OT',
							backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
							borderColor: window.chartColors.red,
							borderWidth: 1,
							data : contSOT
						},{
							label: 'SinIniciar',
							backgroundColor: color(window.chartColors.orange).alpha(0.5).rgbString(),
							borderColor: window.chartColors.orange,
							borderWidth: 1,
							data : sinvalidar
						},{
							label: 'Malo',
							backgroundColor: color(window.chartColors.grey).alpha(0.8).rgbString(),
							borderColor: window.chartColors.grey,
							borderWidth: 1,
							data : malo
						},{
							label: 'Regular',
							backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
							borderColor: window.chartColors.blue,
							borderWidth: 1,
							data : regular
						},{
							label: 'Bien',
							backgroundColor: color(window.chartColors.purple).alpha(0.5).rgbString(),
							borderColor: window.chartColors.purple,
							borderWidth: 1,
							data : bien
						},{
							label: 'Muy Bien',
							backgroundColor: color(window.chartColors.otro).alpha(0.5).rgbString(),
							borderColor: window.chartColors.otro,
							borderWidth: 1,
							data : muyBien
						},{
							label: 'Excelente',
							backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
							borderColor: window.chartColors.green,
							borderWidth: 1,
							data : excelente
						}
					]
				}
/*
			var Datos = {
					labels : ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
					datasets : [{
							label: 'Santa Ester: ',
							backgroundColor: 'rgba(0,116,217,0.6)',
							borderColor: 'rgba(0,0,255,0.7)',
							borderWidth: 1,
							data : [e,f,m,a,m,j,jl,ag,s,o,n,d]
						}
					]
				}

*/

			//var contexto = $("#grafico").getContext('2d');
			var ctx = document.getElementById('grafico').getContext('2d');
			//window.Barra = new Chart(ctx).Bar(Datos, {responsive : true});

			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: Datos,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: 'Evaluacion Ordenes de Trabajo'
					}
				}
			});
			myBar.clear();

		}else
		{
			alertify.error(datos.mensaje);
		}
		$(".selectpicker").selectpicker('refresh');
	});

}