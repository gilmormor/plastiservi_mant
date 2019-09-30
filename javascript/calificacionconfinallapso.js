$(document).ready(function() {

	$("#dialog").dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			"Cerrar": function () {
			$(this).dialog("close");
			}
		}
	});

	$("#dialogo").click(function()
	{
		$("#dialog").dialog("option", "width", 600);
		$("#dialog").dialog("option", "height", 300);
		$('#dialog').dialog('open');
	});

	$('#lapso').change(function(e)
	{
		buscarMostrarPlanificacion();
	});
	$('#codseccodmat').change(function(e)
	{
		buscarMostrarPlanificacion();
	});


})

function buscarMostrarPlanificacion()
{
	var valor = $('#codseccodmat').val();
	var secmat = valor.split('&');
	if (!($('#codseccodmat').val().trim() === '') && !($('#lapso').val().trim() === ''))
	{
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_planificacion.php',
				data 	: 
				{
					accion : "buscarmostrarplanotas",
					pla_lapso   : $('#lapso').val(),
					pla_codsec  : secmat[0],
					pla_codmat  : secmat[1]
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				$("#tabla_detalle").html(datos.tabla);
				$('#con_filas').val(datos.nroreg);
				$("#tabla_detalle").show();
			}else
			{
				alertify.error(datos.mensaje);
				encabezadoTablaDetalle();
			}
		});
	}
	else
	{
		$("#tabla_detalle").html('');
	}
}

function nominanotas(fila_real,i)
{
	var confirm= alertify.confirm('Mensaje','Confirmar Eliminar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
	confirm.set({transition:'slide'});   	

	confirm.set('onok', function(){ //callbak al pulsar botón positivo
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_planificacion.php',
				data 	: 
				{
					accion : "nominanotas",
					fid   : $('#fid'+i).val()
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				$('#fid'+i).val(i);

			}else
			{
				alertify.error(datos.mensaje);
			}
		});
		var fila_tabla=fila_real.parentNode.parentNode.rowIndex;
		var tabla=document.getElementById("tabla_detalle")
		tabla.deleteRow(fila_tabla)	
		
		var filas_borradas=document.getElementById("con_fil_bor").value 
		filas_borradas=parseInt(filas_borradas)+1
		document.getElementById("con_fil_bor").value=filas_borradas
		validarfilas()
		$('#guardar').attr("disabled", true);
		$('#agregar').attr("disabled", false);

	});
	confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
	    alertify.error('Has Cancelado la Solicitud');
	});

	//calcular_total()
	
}