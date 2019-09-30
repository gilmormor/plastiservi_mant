$(document).ready(function() {

    //*******************************************************************
	// Validar campos numericos de pantalla editar_deposito.php
    $('#referencia').numeric();
    $('#cedula').numeric();
    $('#estatus').numeric();
    $('#monto').numeric('.');
    $('#montocheque').numeric('.');    
    //*******************************************************************


    $('#fecha').datepicker();
 	/*
 	$("#referencia").focusout(function(e)
	{
		//alert("Entro");
		$('#guardardepiufrom').attr("disabled", true);
		var montocheque=parseInt($("#montocheque").val());
		if (montocheque>0)
		{
			$('#guardardepiufrom').attr("disabled", false);
		}
	});
	*/
	$('#guardar').attr("disabled", true); //deshabilitar boton de agregar deposito iufront
 	$('#fecha').attr("disabled", true);
 	$("#referencia").focusout(function(e)
	{
		//alert("Entro");
		$('#accion').val("buscar_depositojson");
		var referencia=$("#referencia").val();
		$.ajax({type:"GET",url: "../controladores/controlador_deposito.php",
		data:
		{
			accion:"buscar_depositojson",
			dep_referencia:referencia
		},success:asignarValor,dataType:"json",async:false}); 
		$('#accion').val("modificar");
	});

	$("#guardar").click(function()
	{
		var confirm= alertify.confirm('Mensaje','Confirmar Guardar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_deposito.php',
					data 	: 
					{
						accion     : "modificar",
						referencia : $('#referencia').val(),
						cedula     : $('#cedula').val(),
						estatus    : $('#estatus').val(),
						fecha      : $('#fecha').val(),
						monto      : $('#monto').val(),
						montocheque: $('#montocheque').val()
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
				$('#guardardepiufrom').attr("disabled", true);
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});
	});
});

function asignarValor(json)
{
	$('#guardar').attr("disabled", true);
	if (json)
	{
		$('#cedula').val(json["dep_cedula"]);
		$('#estatus').val(json["dep_status"]);
		var fecha=json["dep_fecha"]
		var fecha_dma=fecha.substr(8,2)+"/"+fecha.substr(5,2)+"/"+fecha.substr(0,4);
		$('#fecha').val(fecha_dma);
		$('#monto').val(json["dep_monto"]);
		$('#montocheque').val(json["dep_montocheque"]);
		var montocheq=parseInt(json["dep_montocheque"]);
		if (montocheq>0 || json["dep_neumonico"]=="PDV")
		{
			$('#guardar').attr("disabled", false);
		}
	}else
	{
		alertify.alert("<h3>Numero de Deposito NO existe.</h3>");
	}
}