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

 	//$('#guardardepiufrom').attr("disabled", true);
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

	$('#guardar').attr("disabled", true); //deshabilitar boton de agregar deposito iufront
 
 	$("#referencia").focusout(function(e)
	{
		//alert("Entro");
		$('#accion').val("buscar_depositojson");
		var referencia=$("#referencia").val();
		$.ajax({type:"GET",url: "../controladores/controlador_deposito_softservi.php",
		data:
		{
			accion:"buscar_depositojson",
			dep_referencia:referencia
		},success:asignarValor,dataType:"json",async:false}); 
		$('#accion').val("modificar");
	});


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
		$('#guardar').attr("disabled", false);
		/* //esto es para el iufron para que solo puedan modificar los 
		if (montocheq>0)
		{
			$('#guardar').attr("disabled", false);
		}
		*/
	}else
	{
		alertify.alert("<h3>Numero de Deposito NO existe.</h3>");
	}
}