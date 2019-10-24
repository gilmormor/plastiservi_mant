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

	//$("#departamentoAreaID").selectpicker('selectAll');
	//$("#departamentoAreaID").selectpicker('selectAll');

	//$('#departamentoAreaID option').prop('selected', true); //Seleccionar todos los items de un select muntiple


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
	var colorNames = Object.keys(window.chartColors);

	aux_valarea = $('#departamentoAreaID').val();	
	if(aux_valarea=="" || aux_valarea=="0"){
		alertify.error('Debes seleccionar al menos un area.');
	}else{
		var filas = $("#maquinariaequiposDetalleID").val();
		var valTablaMaqDetID = [];
		if((filas != null)){
			for(j=0; j<filas.length; j++){ //Recorre las filas 1 a 1
				maquinariaequiposDetalleID = filas[j];
				var fila = {
					maquinariaequiposDetalleID
				};
				valTablaMaqDetID.push(fila);
			}
		}
		var valTablacolores = [];
		$("#maquinariaequiposDetalleID option").each(function(){
			maquinariaequiposDetalleID = $(this).attr('colorbackgr');
			//maquinariaequiposDetalleID = $(this).attr('colorborder');
			//alert(maquinariaequiposDetalleID + ' - ' + $(this).attr('colorborder'));
			var fila = {
				colorbackgr: $(this).attr('colorbackgr'),
				colorborder: $(this).attr('colorborder')
			};
			valTablacolores.push(fila);
		});

		$("#graficos").hide();
		$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion                     : "graficoTAMaq",
				fechad                     : $("#fechad").val(),
				fechah                     : $("#fechah").val(),
				departamentoAreaID         : aux_valarea,
				maquinariaequiposDetalleID : JSON.stringify(valTablaMaqDetID),
				valTablacolores			   : JSON.stringify(valTablacolores)
			},
			dataType: 'json',
			encode	: true
		})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(true)
			{
				$("#graficos").show();
				$('.resultados').html('<canvas id="graficoBarra"></canvas>');
				$('.resultadosPie1').html('<canvas id="graficoPie1"></canvas>');
				$('.resultadosPie2').html('<canvas id="graficoPie2"></canvas>');
				$('.resultadosPie3').html('<canvas id="graficoPie3"></canvas>');
				//$("#grafico").html('');
				nombreDpto      = [];
				contSOT         = [];
				SOTSinIniciar   = [];
				enEjecucion     = [];
				SOTConFinSinVal = [];
				SOTConFinConVal = [];
				SOTRechazadas   = [];
				contSOTV		= 0;
				SOTSinIniciarV	= 0;
				enEjecucionV	= 0;
				SOTConFinSinValV= 0;
				SOTConFinConValV= 0;
				SOTRechazadasV	= 0;
				mantCorrectivo	= 0;
				mantPreventivo	= 0;
				mantSinAsignar	= 0;
/*
				for(i=0;i<datos.length;i++){
					nombreDpto[i]       = datos[i]['nombreDpto'];
					contSOT[i]          = datos[i]['contSOT'];
					SOTSinIniciar[i]    = datos[i]['SOTSinIniciar'];
					enEjecucion[i]      = datos[i]['enEjecucion'];
					SOTConFinSinVal[i]  = datos[i]['SOTConFinSinVal'];
					SOTConFinConVal[i]  = datos[i]['SOTConFinConVal'];
					SOTRechazadas[i]    = datos[i]['SOTRechazadas'];
					contSOTV 		 = contSOTV + Number(datos[i]['contSOT']);
					SOTSinIniciarV 	 = SOTSinIniciarV + Number(datos[i]['SOTSinIniciar']);
					enEjecucionV 	 = enEjecucionV + Number(datos[i]['enEjecucion']);
					SOTConFinSinValV = SOTConFinSinValV + Number(datos[i]['SOTConFinSinVal']);
					SOTConFinConValV = SOTConFinConValV + Number(datos[i]['SOTConFinConVal']);
					SOTRechazadasV 	 = SOTRechazadasV + Number(datos[i]['SOTRechazadas']);
					mantCorrectivo	 = mantCorrectivo + Number(datos[i]['mantCorrectivo']);
					mantPreventivo	 = mantPreventivo + Number(datos[i]['mantPreventivo']);
					mantSinAsignar	 = mantSinAsignar + Number(datos[i]['mantSinAsignar']);
				}
*/
/*
				for(i=0;i<datos['dpto'].length;i++){
					nombreDpto[i]  = datos['dpto'][i]['nombreDpto'];
				}
*/
				nombreDpto[0]  = datos['dpto'][0]['nombreDpto'];

				var color = Chart.helpers.color;
				var Datos = {
						labels : nombreDpto,
						datasets : [{
								label: 'Solicitudes OT',
								backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
								borderColor: window.chartColors.red,
								borderWidth: 1,
								data : contSOT
							},{
								label: 'No Ini',
								backgroundColor: color(window.chartColors.orange).alpha(0.5).rgbString(),
								borderColor: window.chartColors.orange,
								borderWidth: 1,
								data : SOTSinIniciar
							},{
								label: 'En Ejecución',
								backgroundColor: color(window.chartColors.yellow).alpha(0.8).rgbString(),
								borderColor: window.chartColors.yellow,
								borderWidth: 1,
								data : enEjecucion
							},{
								label: 'Sin Validar',
								backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
								borderColor: window.chartColors.blue,
								borderWidth: 1,
								data : SOTConFinSinVal
							},{
								label: 'Finalizadas',
								backgroundColor: color(window.chartColors.purple).alpha(0.5).rgbString(),
								borderColor: window.chartColors.purple,
								borderWidth: 1,
								data : SOTConFinConVal
							},{
								label: 'Rechazadas',
								backgroundColor: color(window.chartColors.red).alpha(0.9).rgbString(),
								borderColor: window.chartColors.red,
								borderWidth: 1,
								data : SOTRechazadas
							}
						]
					}

				//alert(color(window.chartColors.purple).alpha(0.5).rgbString()+ '  ' + window.chartColors.purple);

				//datos.maquinas.push
				//alert(datos.maquinas.length);
				for(j=0; j<datos.maquinas.length; j++){
					var colorName = colorNames[(j+1) % colorNames.length];
					var dsColor = window.chartColors[colorName];
					aux_VecValor = {
						borderColor: dsColor
					};
					//datos.maquinas[j].push(aux_VecValor);
					//alert(datos.maquinas[j].length);
					//alert(dsColor);
				}
				//return 0;
				var Datos = {
						labels : nombreDpto,
						datasets : datos['maquinas']
					}

				//alert(datos['mecanicos']);
				//console.log(Datos);
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
				var ctx = document.getElementById('graficoBarra').getContext('2d');
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
							text: 'Tiempo de atención por Máquina - Expresado en Horas'
						}
					}
				});
				myBar.clear();

				var config1 = {
					type: 'pie',
					data: {
						datasets: [{
							data: [
								SOTSinIniciarV,
								enEjecucionV,
								SOTConFinSinValV,
								SOTConFinConValV,
								SOTRechazadasV,
							],
							backgroundColor: [
								window.chartColors.orange,
								window.chartColors.yellow,
								window.chartColors.blue,
								window.chartColors.purple,
								window.chartColors.red,
							],
							label: 'Dataset 1'
						}],
						labels: [
							'No Ini',
							'En Ejecución',
							'Sin Validar',
							'Finalizadas',
							'Rechazadas'
						]
					},
					options: {
						responsive: true
					}
				};

				SOTSinIniciarV   = (SOTSinIniciarV * 100) / contSOTV;
				enEjecucionV     = (enEjecucionV * 100) / contSOTV;
				SOTConFinSinValV = (SOTConFinSinValV * 100) / contSOTV;
				SOTConFinConValV = (SOTConFinConValV * 100) / contSOTV;
				SOTRechazadasV   = (SOTRechazadasV * 100) / contSOTV;

				//Redondear a 2 decimales
				SOTSinIniciarV   = SOTSinIniciarV.toFixed(2);
				enEjecucionV     = enEjecucionV.toFixed(2);
				SOTConFinSinValV = SOTConFinSinValV.toFixed(2);
				SOTConFinConValV = SOTConFinConValV.toFixed(2);
				SOTRechazadasV   = SOTRechazadasV.toFixed(2);

				var config2 = {
					type: 'pie',
					data: {
						datasets: [{
							data: [
								SOTSinIniciarV,
								enEjecucionV,
								SOTConFinSinValV,
								SOTConFinConValV,
								SOTRechazadasV,
							],
							backgroundColor: [
								window.chartColors.orange,
								window.chartColors.yellow,
								window.chartColors.blue,
								window.chartColors.purple,
								window.chartColors.red,
							],
							label: 'Dataset 1'
						}],
						labels: [
							'No Ini',
							'En Ejecución',
							'Sin Validar',
							'Finalizadas',
							'Rechazadas'
						]
					},
					options: {
						responsive: true
					}
				};

				var config3 = {
					type: 'pie',
					data: {
						datasets: [{
							data: [
								mantCorrectivo,
								mantPreventivo,
								mantSinAsignar,
							],
							backgroundColor: [
								window.chartColors.purple,
								window.chartColors.blue,
								window.chartColors.red
							],
							label: 'Dataset 1'
						}],
						labels: [
							'Correctivo',
							'Preventivo',
							'Sin Asignar'
						]
					},
					options: {
						responsive: true
					}
				};

				mantCorrectivo = (mantCorrectivo * 100) / contSOTV;
				mantPreventivo = (mantPreventivo * 100) / contSOTV;
				mantSinAsignar = (mantSinAsignar * 100) / contSOTV;

				//Redondear a 2 decimales
				mantCorrectivo = mantCorrectivo.toFixed(2);
				mantPreventivo = mantPreventivo.toFixed(2);
				mantSinAsignar = mantSinAsignar.toFixed(2);

				var config4 = {
					type: 'pie',
					data: {
						datasets: [{
							data: [
								mantCorrectivo,
								mantPreventivo,
								mantSinAsignar,
							],
							backgroundColor: [
								window.chartColors.purple,
								window.chartColors.blue,
								window.chartColors.red
							],
							label: 'Dataset 1'
						}],
						labels: [
							'Correctivo',
							'Preventivo',
							'Sin Asignar'
						]
					},
					options: {
						responsive: true
					}
				};


				var ctxPie1 = document.getElementById('graficoPie1').getContext('2d');
				window.myPie1 = new Chart(ctxPie1, config1);
				myPie1.clear();

				var ctxPie2 = document.getElementById('graficoPie2').getContext('2d');
				window.myPie2 = new Chart(ctxPie2, config2);
				myPie2.clear();

				var ctxPie3 = document.getElementById('graficoPie3').getContext('2d');
				window.myPie3 = new Chart(ctxPie3, config3);
				myPie3.clear();

				var ctxPie4 = document.getElementById('graficoPie4').getContext('2d');
				window.myPie4 = new Chart(ctxPie4, config4);
				myPie3.clear();


				$("#tituloPie1").html("Grafico Total Solicitudes ST: "+contSOTV);
				$("#graficos").show();
			}else
			{
				alertify.error(datos.mensaje);
			}
			$(".selectpicker").selectpicker('refresh');
		});
	}
}

$('#departamentoAreaID').on('change', function () {
	//llenarProvincia(this,0);
	var colorNames = Object.keys(window.chartColors);
	var color = Chart.helpers.color;
	$.ajax({
		type 	: 'POST',
		url		: '../controladores/controlador_maquina.php',
		data 	: 
		{
			accion             : "listadoMaqxArea",
			departamentoAreaID : $(this).val()
		},
		dataType: 'json',
		encode	: true
	})
	.done(function(datos){
		$("#maquinariaequiposDetalleID").empty();
		for(i=0;i<datos['maquinas'].length;i++){
			var colorName = colorNames[(i+1) % colorNames.length];
			var dsColor = window.chartColors[colorName];

			aux_maquinariaequiposDetalleID = datos['maquinas'][i]['maquinariaequiposDetalleID'];
			aux_nombre = datos['maquinas'][i]['codigoInterno'] +' - '+ datos['maquinas'][i]['nombre'];
			$("#maquinariaequiposDetalleID").append("<option value='" + aux_maquinariaequiposDetalleID + "' colorbackgr='"+color(dsColor).alpha(0.5).rgbString()+"' colorborder='"+dsColor+"'>" + aux_nombre + "</option>");
		}
		$(".selectpicker").selectpicker('refresh');
	});
});