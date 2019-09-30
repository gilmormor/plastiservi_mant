$(document).ready(function() {
    //*******************************************************************
	// Validar campos numericos de pantalla agregar_conveniosofitasa.php
    $('#est_ced').numeric();
    //*******************************************************************

	$("#est_ced").focusin(function(e)
	{
		$('#guardar').attr("disabled", true);
	});

	$("#est_ced").focusout(function(e)
	{
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_estudiante.php',
				data 	: 
				{
					accion : "buscar_estudiante",
					cedula : $('#est_ced').val()
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			$("#nombre").val("");
			$('#guardar').attr("disabled", true);
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				$("#nombre").val(datos.nomape);
				$('#guardar').attr("disabled", false);
			}else
			{
				alertify.error(datos.mensaje);
			}
		});
	});


	$("#guardar").click(function()
	{
		var confirm= alertify.confirm('Mensaje','Confirmar Guardar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_convenio.php',
					data 	: 
					{
						accion     : "insertar",
						cedula : $('#est_ced').val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
				}else
				{
					alertify.error(datos.mensaje);
				}
				$('#guardar').attr("disabled", true);
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});
	});
});