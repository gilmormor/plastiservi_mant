$(document).ready(function() {
	$('#rin_cedalum').numeric();
	$('#rin_cedalum').focus();
	$('#eliminar').attr("disabled", true);
	$('#guardar').attr("disabled", true);

 
 	$("#rin_cedalum").focusout(function(e)
	{
		if ($('#rin_cedalum').val().trim() === '')
		{

		}else
		{
			var cedula=$("#rin_cedalum").val();
/*
			$.ajax({type:"GET",url: "../controladores/controlador_rechazados_inscripcion.php",
			data:
			{
				accion:"buscar_json",
				cedula:cedula
			},success:asignarValor,dataType:"json",async:false}); 
*/

			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_rechazados_inscripcion.php',
					data 	: 
					{
						accion:"buscar",
						cedula:cedula
					},
					dataType: 'json',
					encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				$('#eliminar').attr("disabled", true);
				$('#guardar').attr("disabled", true);
				if(datos.exito)
				{
					//alertify.success('Registro encontrado.');
					$('#rin_descrip').val(datos.tablaest.rin_descrip);
					$('#rin_fecha').val(datos.tablaest.rin_fecha);
					$('#nomape').val(datos.tablaest.est_nombres+" "+datos.tablaest.est_apellidos);
					$('#eliminar').attr("disabled", false);
					$('#guardar').attr("disabled", false);
				}else
				{
					alertify.error(datos.mensaje);
					//alertify.alert('Mensaje',"<h3>"+sessvars.mensajefininsc+"</h3>");
					//alertify.success('Cédula NO existe.');
					//alertify.alert("Mensaje","<h3>Cédula NO existe.</h3>");
					$('#guardar').attr("disabled", false);
					$aux_ced = $('#rin_cedalum').val();
					blanquearCajas();
					//$('#rin_cedalum').val($aux_ced);
					$('#guardar').attr("disabled", false);
				}
				$('#rin_descrip').focus();
			});
		}
	});




 	$("#eliminar").click(function(e)
	{
		/*
		var confirm= alertify.confirm('Mensaje','Confirmar solicitud?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
	    	alertify.success('Registro Eliminado');
			var cedula=$("#rin_cedalum").val();
			$.ajax({type:"GET",url: "../controladores/controlador_rechazados_inscripcion.php",
			data:
			{
				accion:"eliminar",
				cedula:cedula
			},success:mensaje,dataType:"json",async:false}); 
			blanquearCajas();

		});

		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});
		*/
		var confirm= alertify.confirm('Mensaje','Confirmar solicitud?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_rechazados_inscripcion.php',
					data 	: 
					{
						accion: "eliminar",
						cedula: $('#rin_cedalum').val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if (datos.exito)
				{
					alertify.success(datos.mensaje);
					blanquearCajas();
				}
				else
				{
					if (datos.errores.cedula)
					{
						$('#rin_cedalum').focus();
						alertify.success(datos.errores.cedula);
					}
					if (datos.errores.descrip)
					{
						$('#rin_descrip').focus();
						alertify.success(datos.errores.descrip);
					}
					if (datos.errores.errorbd)
						alertify.success(datos.errores.errorbd);
				}

			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});

	});

 	$("#guardar").click(function(e)
	{
		if (!($('#rin_descrip').val().trim() === ''))
		{
			var confirm= alertify.confirm('Mensaje','Confirmar solicitud?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
			confirm.set({transition:'slide'});   	

			confirm.set('onok', function(){ //callbak al pulsar botón positivo
		    	
				$datosEnviados =
				{
					'usuario'	: $('#rin_cedalum').val(),
					'descrip'	: $('#rin_descrip').val()
				};
				$.ajax({
						type 	: 'POST',
						url		: '../controladores/controlador_rechazados_inscripcion.php',
						data 	: 
						{
							accion: "guardar",
							cedula: $datosEnviados.usuario,
							descrip: $datosEnviados.descrip
						},
						dataType: 'json',
						encode	: true
					})
				.done(function(datos){
					//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
					if (datos.exito)
					{
						alertify.success(datos.mensaje);
						blanquearCajas();
					}
					else
					{
						if (datos.errores.cedula)
						{
							$('#rin_cedalum').focus();
							alertify.success(datos.errores.cedula);
						}
						if (datos.errores.descrip)
						{
							$('#rin_descrip').focus();
							alertify.success(datos.errores.descrip);
						}
						if (datos.errores.errorbd)
							alertify.success(datos.errores.errorbd);
					}

				});
			});
			confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
			    alertify.error('Has Cancelado la Solicitud');
			});
		}else
		{
			alertify.error('Descripción no debe quedar en blanco.');
			$('#rin_descrip').focus();
		}

	});

 });

function asignarValor(json)
{
	$('#eliminar').attr("disabled", true);
	$('#guardar').attr("disabled", true);
	if (json)
	{
		alertify.success('Registro encontrado.');
		$('#rin_descrip').val(json["rin_descrip"]);
		$('#rin_fecha').val(json["rin_fecha"]);
		$('#nomape').val(json["est_nombres"]+" "+json["est_apellidos"]);
		$('#eliminar').attr("disabled", false);
		$('#guardar').attr("disabled", false);
	}else
	{
		alertify.success('Cédula NO existe.');
		//alertify.alert("Mensaje","<h3>Cédula NO existe.</h3>");
		$('#guardar').attr("disabled", false);
		$aux_ced = $('#rin_cedalum').val();
		blanquearCajas();
		$('#rin_cedalum').val($aux_ced);
		$('#guardar').attr("disabled", false);
	}
	$('#rin_descrip').focus();
}


function mensajeguardar(json)
{

	alertify.alert("Mensaje","<h3>Registro eliminado</h3>");
}

function blanquearCajas()
{
	$('#rin_cedalum').val("");
	$('#rin_descrip').val("");
	$('#rin_fecha').val("");
	$('#nomape').val("");
	$('#eliminar').attr("disabled", true);
	$('#guardar').attr("disabled", true);
	$('#rin_cedalum').focus();
}