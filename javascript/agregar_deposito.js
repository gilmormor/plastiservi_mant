$(document).ready(function() {
	// Validar campos numericos de pantalla agregar_deposito.php IUFRONT
    $('#dep_cedula').numeric();
    $('#dep_referencia').numeric();
    $('#dep_lote').numeric();
    $('#dep_clavconf').numeric();
    $('#dep_monto').numeric('.');
    //*******************************************************************
	// Validar campos numericos de pantalla agregar_conveniosofitasa.php
    $('#est_ced').numeric();
    //*******************************************************************


    $('#dep_fecha').datepicker();

	$('#btnguardardep').attr("disabled", true); //deshabilitar boton de agregar deposito iufront

/*
	$('#dep_fecha').datepicker({
	  showOn: 'button',
	  buttonText: 'Escoja una fecha',
	  buttonImage: 'imagenes/calendar.png',
	  buttonImageOnly: true,
	  dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
	  numberOfMonths: 1,
	  yearRange:'1960:2000',
	  changeMonth: true,
	  changeYear:true,
	  dateFormat: 'dd/mm/yy',
	  maxDate: '0y',
	  monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
	  showButtonPanel: true,
	  showAnim: 'fadeIn'
	});
*/
 	$("#dep_cedula").focusout(function(e)
	{
		if($('#dep_cedula').val()!='')
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_estudiante.php',
					data 	: 
					{
						accion  : "consultar",
						est_ced : $("#dep_cedula").val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				$respuesta = datos.exito;
				if(datos.exito)
				{
					//alertify.success(datos.mensaje);
					$('#nombre').val(datos.tablaest.est_nombres+' '+datos.tablaest.est_apellidos);
					$("#guardar").show();
				}else
				{
					alertify.error(datos.mensaje);
					$("#guardar").hide();
					$('#nombre').val('');
					$('#dep_cedula').val('');
				}
			});
		}
	});

 	$("#dep_referencia").focusout(function(e)
	{
		if($('#dep_referencia').val()!='')
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_deposito.php',
					data 	: 
					{
						accion: "buscar_depositonew",
						dep_referencia: $("#dep_referencia").val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				$respuesta = datos.exito;
				if(datos.exito)
				{
					alertify.error(datos.mensaje);
					$('#dep_referencia').val('');
					//alertify.alert("<h3>Numero de Deposito ya existe.</h3>");
					$("#guardar").hide();
				}else
				{
					//alertify.success(datos.mensaje);
					$("#guardar").show();
				}
			});
		}
	});

	$("#guardar").click(function(e)
	{
		var confirm= alertify.confirm('Mensaje','Confirmar Guardar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			$bandera = false;
			if($('#dep_cedula').val()=='')
			{
				alertify.error('Falta Núm. de Cédula');
				$('#dep_cedula').focus();
			}else
			if($('#nombre').val()=='')
			{
				alertify.error('Falta Nombre');
				$('#nombre').focus();
			}else
			if($('#mfor_cod').val()=='')
			{
				alertify.error('Falta Forma de pago');
				$('#mfor_cod').focus();
			}else
			if($('#fid_banco').val()=='')
			{
				alertify.error('Falta Banco');
				$('#fid_banco').focus();
			}else
			if($('#dep_referencia').val()=='')
			{
				alertify.error('Falta Núm de Deposito (Referencia)');
				$('#dep_referencia').focus();
			}else
			if($('#dep_fecha').val()=='')
			{
				alertify.error('Falta Fecha');
				$('#dep_fecha').focus();
			}else
			if($('#dep_monto').val()=='')
			{
				alertify.error('Falta Monto');
				$('#dep_monto').focus();
			}else
			if($('#dep_nofacturar').val()=='')
			{
				alertify.error('Falta Generar Factura por lote');
				$('#dep_nofacturar').focus();
			}else
			{
				$bandera = true;
			}

			if($bandera)
			{
				$.ajax({
						type 	: 'POST',
						url		: '../controladores/controlador_deposito.php',
						data 	: 
						{
							accion: "insertar",
							dep_cedula     : $("#dep_cedula").val(),
							mfor_cod       : $("#mfor_cod").val(),
							fid_banco      : $("#fid_banco").val(),
							dep_referencia : $("#dep_referencia").val(),
							dep_lote       : $("#dep_lote").val(),
							dep_clavconf   : $("#dep_clavconf").val(),
							dep_fecha      : $("#dep_fecha").val(),
							dep_monto      : $("#dep_monto").val(),
							dep_nofacturar : $("#dep_nofacturar").val()
						},
						dataType: 'json',
						encode	: true
				})
				.done(function(datos){
					//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
					respuesta(datos);
				});
			}
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});
	});

});


function buscarDeposito(json)
{
	if (json)
	{
		//alert("Deposito ya existe.");
		alertify.alert("<h3>Numero de Deposito ya existe.</h3>");
		$('#btnguardardep').attr("disabled", true);
		$("#dep_referencia").focus();
	}else
	{
		$('#btnguardardep').attr("disabled", false);
	}
}
function respuesta(datos)
{
	if(datos.exito)
	{
		alertify.success(datos.mensaje);
		$("#guardar").hide();
		blanquearcajas();
	}else
	{
		alertify.error(datos.mensaje);
	}

}

function blanquearcajas()
{
	$("#dep_cedula").val('');
	$("#nombre").val('');
	$("#mfor_cod").val('');
	$("#fid_banco").val('');
	$("#dep_referencia").val('');
	$("#dep_lote").val('');
	$("#dep_clavconf").val('');
	$("#dep_fecha").val('');
	$("#dep_monto").val('');
	$("#dep_nofacturar").val('');
	$("#dep_cedula").focus();
}
