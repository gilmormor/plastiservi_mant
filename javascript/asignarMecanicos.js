$(document).ready(function() {
	// Validar campos numericos de pantalla

    $('#cedula').numeric();
    $('#referencia').numeric();
    //*******************************************************************
	// Validar campos numericos de pantalla
    //*******************************************************************

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
    $("#btnEliminar").click(function(e)
	{
		//console.log($("#tablaOrdTrab_length option:selected").text());
		$("#tablaOrdTrab_length option[value='100']").attr("selected", true);
		$("#tablaOrdTrab_length").change();
	});

	$('.datepicker').datepicker({
		language: "es",
		autoclose: true,
		todayHighlight: true
	}).datepicker("setDate", new Date());


	$('#btnguardardep').attr("disabled", true); //deshabilitar boton de agregar deposito iufront

	$('#cedula').focus();

	$("#consultar").click(function(e)
	{

		//$('.parametros').slideUp();

	});
	$("#mostrarparametos").click(function(e)
	{
		$("#tablaDepositos").html('');
		$("#tablaDepositosSoftservi").html('');
		$('.parametros').slideDown();
		$("#mostrarparametos").css("display", "none");
	});

	$("#btnGuardar").click(function()
	{
		//Validar los campos obligatorios
		//alert(sessvars.fil_codlapso);
		if(verificar())
		{
			update();
		}else{
			alertify.error("Falta incluir informacion");
		}
	});

});
//$('#example-getting-started').multiselect();
$("#boton2").click(function()
{
	alert("prueba");
	createOptions(3);
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

function consultarObservaciones()
{
	if($('#departamentoAreaID').val()!=''){
		var confirm= alertify.confirm('Mensaje','Confirmar Consulta?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo

			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_maquina.php',
					data 	: 
					{
						accion              : "consultar",
						maquinaID           : '',
						departamentoAreaID  : $("#departamentoAreaID").val()
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
				configurarTabla();
				/*
				$("#tablaOrdTrab_length").val("100");
				$("#tablaOrdTrab_length").change();
				*/
				aux_numreg = datos.numreg;
				aux_entero = parseInt(aux_numreg,10);

				if(((aux_numreg)%10)==0){
					aux_entero = aux_entero-1;
				}

				$(".selectpicker").selectpicker('refresh');
				$('.btn-primary').tooltip({placement: "top"});
				$('.btn-danger').tooltip({placement: "right"});
				$('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
				//alert(aux_entero);
				aux_diez = 10;
				for(i=1; i<=aux_entero; i++){
					$("#tablaOrdTrab_next").click();
					$(".selectpicker").selectpicker('refresh');
					$('.btn-primary').tooltip({placement: "top"});
					$('.btn-danger').tooltip({placement: "right"});
					$('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
					
					var nFilas = $("#tablaOrdTrab tbody tr").length;
					for(e=1; e<=nFilas; e++){
						filaOt = $("#solicitudTrabID"+(e+aux_diez)).html();

						aux_prioridad = $("#prioridadC"+(e+aux_diez)).html();
						$("#prioridad"+(e+aux_diez)).val(aux_prioridad);
						$("#prioridad"+(e+aux_diez)).selectpicker('refresh');

						if(datos.ordenTrab[filaOt]){
							for(j=0; j<datos.ordenTrab[filaOt].length; j++){
								$('#mecanico'+(e+aux_diez)+' option[value='+datos.ordenTrab[filaOt][j]+']').prop('selected', true);
								$("#mecanico"+(e+aux_diez)).selectpicker('refresh');	
							}
						}
					}
					aux_diez = aux_diez + 10;
				}
				for(i=1; i<=aux_entero; i++){
					$("#tablaOrdTrab_previous").click();
				}


				//$("#oper1").html("<?php $objUtilidades->hacer_lista_desplegableMultiSelecB3($conexion,$tabla='dptoarea',$value='dptoareaID',$mostrar='Descripcion',$nombre='dptoareaID',$sql=select dptoareaID,Descripcion from dptoarea;',$funcion='');  ?>");
/*
				$(".selectpicker").selectpicker('refresh');
				$("#tablaOrdTrab_next").click();
				$(".selectpicker").selectpicker('refresh');
				$("#tablaOrdTrab_next").click();
				$(".selectpicker").selectpicker('refresh');
				$("#tablaOrdTrab_previous").click();
				$("#tablaOrdTrab_previous").click();
*/
				var nFilas = $("#tablaOrdTrab tbody tr").length;
				for(i=1; i<=nFilas; i++){
					filaOt=$("#solicitudTrabID"+i).html();
					//alert(datos.ordenTrab[filaOt].length);
					aux_prioridad = $("#prioridadC"+i).html();
					$("#prioridad"+i).val(aux_prioridad);
					$("#prioridad"+i).selectpicker('refresh');
					if(datos.ordenTrab[filaOt]){
						for(j=0; j<datos.ordenTrab[filaOt].length; j++){
							$('#mecanico'+i+' option[value='+datos.ordenTrab[filaOt][j]+']').prop('selected', true);
							$("#mecanico"+i).selectpicker('refresh');					
						}
					}
				}

/*
				var nFilas = $("#tablaOrdTrab tbody tr").length;
				for(i=1; i<=nFilas; i++){ //Recorre las filas 1 a 1
					var ordenTrabID = $("#ordenTrabID"+i).html();
					//alert('ordenTrabID:' + ordenTrabID);
					$.ajax({
							type 	: 'POST',
							url		: '../controladores/controlador_ordentrabmantpersona.php',
							data 	: 
							{
								accion        : "consultar",
								ordenTrabID   : ordenTrabID
							},
							dataType: 'json',
							encode	: true
					})
					.done(function(datos1){
						//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
						if(datos1.exito)
						{
							//$('#mecanico1 option[value=1]').prop('selected', true);
							//alert(datos.personas.length);
							for(j=0; j<datos1.personas.length; j++){
								//alert('ordenTrabID: ' + ordenTrabID+' Persona: ' + datos1.personas[j]);
								//alert(j);
								$('#mecanico'+(i-1)+' option[value='+datos1.personas[j]+']').prop('selected', true);
								$(".selectpicker").selectpicker('refresh');
							}
							
							//console.log(datos.personas);

						}else
						{
							alertify.error(datos.mensaje);
						}
						//$("#tablaMaquinas").html(datos.tabla);
						//configurarTabla();
						//$("#oper1").html("<?php $objUtilidades->hacer_lista_desplegableMultiSelecB3($conexion,$tabla='dptoarea',$value='dptoareaID',$mostrar='Descripcion',$nombre='dptoareaID',$sql=select dptoareaID,Descripcion from dptoarea;',$funcion='');  ?>");
						//$(".selectpicker").selectpicker('refresh');
					});

				}	*/			

				$(".selectpicker").selectpicker('refresh');
				//createOptions(3);
				/*
				aux_combomultple=$("#combomultiple").html();
				alert(aux_combomultple);
				for (var i = 1; i <= datos.nroreg; i++) {

					$("#ope"+i).html(aux_combomultple);
				};*/
				//$("#mostrarparametos").css("display", "block");

			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		    $('.parametros').slideDown();
		});
	}else{
		alertify.error('Seleccione una opción.');
		$("#tablaMaquinas").html("");
	}
}

function createOptions(number) {
  var options = [], _options;
  //alert(number);

  for (var i = 0; i < number; i++) {
    var option = '<option value="' + i + '">Option ' + i + '</option>';
    alert(option);
    options.push(option);
  }

  _options = options.join('');
  //alert(_options);
  

  $('#number2')[0].innerHTML = _options;
  $('#number2-multiple')[0].innerHTML = _options;

  $('#ope1')[0].innerHTML = _options;
}

function guardarMecanico(i){
	var nFilas = $("#tablaOrdTrab tbody tr").length;
	var solicitudTrabID = $("#solicitudTrabID"+i).html();

	var filas = $("#mecanico"+i).val();
	var aux_prioridad = $("#prioridad"+i).val();
	//console.log(valTabla);
	if((filas == null) || aux_prioridad==""){
		alertify.error('Debes seleccionar Mecánico y Prioridad.');
	}else{
		var aux_prioridad = $("#prioridad"+i).val();
		var valTabla = [];
		for(i=0; i<filas.length; i++){ //Recorre las filas 1 a 1
			personaID = filas[i];

			var fila = {
				personaID
			};
			valTabla.push(fila);
		}

		var confirm= alertify.confirm('Mensaje','Confirmar Guardar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo

			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_solicitudtrabmantpersona.php',
					data 	: 
					{
						accion          : "guardar",
						solicitudTrabID : solicitudTrabID,
						prioridad       : aux_prioridad,
						filas           : JSON.stringify(valTabla)
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
				//$("#tablaMaquinas").html(datos.tabla);
				//configurarTabla();
				//$("#oper1").html("<?php $objUtilidades->hacer_lista_desplegableMultiSelecB3($conexion,$tabla='dptoarea',$value='dptoareaID',$mostrar='Descripcion',$nombre='dptoareaID',$sql=select dptoareaID,Descripcion from dptoarea;',$funcion='');  ?>");
				//$(".selectpicker").selectpicker('refresh');
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		    $('.parametros').slideDown();
		});		
	}


}

function eliminarMecanicos(i){

	var filas = $("#mecanico"+i).val();
	//console.log(valTabla);

	if(filas == null){
		alertify.error('Debes seleccionar al menos una opción.');
	}else{
		var solicitudTrabID = $("#solicitudTrabID"+i).html();

		var confirm= alertify.confirm('Mensaje','Confirmar eliminar todos los mécanicos?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo

			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_solicitudtrabmantpersona.php',
					data 	: 
					{
						accion            : "eliminar",
						solicitudTrabID   : solicitudTrabID
					},
					dataType: 'json',
					encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					$("#mecanico"+i).val('');
					$("#mecanico"+i).selectpicker('refresh');
					alertify.success(datos.mensaje);
				}else
				{
					alertify.error(datos.mensaje);
				}
				//$("#tablaMaquinas").html(datos.tabla);
				//configurarTabla();
				//$("#oper1").html("<?php $objUtilidades->hacer_lista_desplegableMultiSelecB3($conexion,$tabla='dptoarea',$value='dptoareaID',$mostrar='Descripcion',$nombre='dptoareaID',$sql=select dptoareaID,Descripcion from dptoarea;',$funcion='');  ?>");
				//$(".selectpicker").selectpicker('refresh');
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		    $('.parametros').slideDown();
		});
	}
}

function ordenTrabajo(i){
	limpiarInputOT();
	quitarverificar();
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion             : "consultar",
				solicitudTrabID    : $("#solicitudTrabID"+i).html()
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			//alertify.success(datos.mensaje);
			//alert('#btnInicioServ'+i);
			aux_tipofalla = datos.tipofalla.split(',');
			var coddpto = $("#departamentoAreaID"+i).html();
			//var valor = $("#departamentoAreaID option[value="+coddpto+"]").html();
			$("#exampleModalLongTitle").html('Orden de Trabajo Nro.'+datos.ordentrabmantID)
			$("#solicitudTrabID").val($("#solicitudTrabID"+i).html());
			$("#departamentoAreaIDM").val($("#nombreDpto"+i).html());
			$("#ema_usuM").val($("#ema_usu"+i).html());
			$("#fechaHoraini").val(datos.fechaini);
			$("#responsable").val($("#mecanico"+i).val())
			$(".selectpicker").selectpicker('refresh');
			$("#responsable").attr("disabled", true);
			$("#descripTrabajo").val(datos.descripTrabajo);
			$("#mant").val(datos.mant);
			$("#tipofalla").val(aux_tipofalla);
			$("#tipomant").val(datos.tipomant);
			$("#descripTrabajo").val(datos.descripTrabajo);
			$("#indSeguridad").val(datos.indSeguridad);
			$("#repuestosmat").val(datos.repuestosmat);
			$("#observaciones").val(datos.observaciones);
			$("#prioridad").val(datos.prioridad);
			$(".selectpicker").selectpicker('refresh');
		}else
		{
			alertify.error(datos.mensaje);
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
	$("#indSeguridad").val('');
	$("#repuestosmat").val('');
	$("#observaciones").val('');
	$(".selectpicker").selectpicker('refresh');
}

function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0,v6=0,v7=0;

	v7=validacion('mant','combobox','col-xs-12 col-sm-3');
	v6=validacion('descripTrabajo','texto','col-xs-12 col-sm-6');
	v5=validacion('indSeguridad','texto','col-xs-12 col-sm-6'); //en comentario por el rollo de ser tan grande la tabla de plantel de procedencia
	v4=validacion('repuestosmat','texto','col-xs-12 col-sm-6');
	v3=validacion('observaciones','texto','col-xs-12 col-sm-6');
	v2=validacion('tipofalla','combobox','col-xs-12 col-sm-3');
	v1=validacion('tipomant','combobox','col-xs-12 col-sm-3');

	if (v1===false || v2===false || v3===false || v4===false || v5===false || v6===false || v7===false)
	{
		//$("#exito").hide();
		//$("#error").show();
		return false;
	}else{
		//$("#error").hide();
		//$("#exito").show();
		return true;
	}
}

function quitarverificar(){
	quitarValidacion('mant','combobox','col-xs-12 col-sm-3');
	quitarValidacion('descripTrabajo','texto','col-xs-12 col-sm-6');
	quitarValidacion('indSeguridad','texto','col-xs-12 col-sm-6'); //en comentario por el rollo de ser tan grande la tabla de plantel de procedencia
	quitarValidacion('repuestosmat','texto','col-xs-12 col-sm-6');
	quitarValidacion('observaciones','texto','col-xs-12 col-sm-6');
	quitarValidacion('tipofalla','combobox','col-xs-12 col-sm-3');
	quitarValidacion('tipomant','combobox','col-xs-12 col-sm-3');
}

function validacion(campo,tipo,columnas)
{
	var a=0;
	//columnas = $('#'+campo).parent().parent().attr("class");
	switch (tipo) 
	{ 
		case "texto": 
			codigo = document.getElementById(campo).value;
			if( codigo == null || codigo.length == 0 || /^\s+$/.test(codigo) ) {
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
				$('#'+campo).parent().children('span').text("Campo obligatorio").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback check'></span>");
				$('#'+campo).focus();
				return false;
			}
			else
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().attr("class", columnas+" has-success has-feedback");
				$('#'+campo).parent().children('span').hide();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
				return true;
			}

		break 
		case "numerico": 
			codigo = document.getElementById(campo).value;
			if( codigo == null || codigo.length == 0 || /^\s+$/.test(codigo) ) {
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", columnas+" has-error has-feedback");
				$('#'+campo).parent().children('span').text("Campo obligatorio").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
				$('#'+campo).focus();
				return false;
			}
			else
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", columnas+" has-success has-feedback");
				$('#'+campo).parent().children('span').hide();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
				return true;
			}

		break 
		case "combobox": 
			codigo = document.getElementById(campo).value;
			if( codigo == null || codigo.length == 0 || /^\s+$/.test(codigo) ) {
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", columnas+" has-error has-feedback");
				$('#'+campo).parent().parent().children('span').text("Campo obligatorio").show();
				$('#'+campo).parent().parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback check'></span>");
				$('#'+campo).focus();
				return false;
			}
			else
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", columnas+" has-success has-feedback");
				$('#'+campo).parent().parent().children('span').hide();
				$('#'+campo).parent().parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
				return true;
			}

		break 
		case "email": 
			cajatexto = document.getElementById(campo).value;
			var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
			if( cajatexto == null || cajatexto.length == 0 || /^\s+$/.test(cajatexto) )
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", columnas+" has-error has-feedback");
				$('#'+campo).parent().children('span').text("Campo obligatorio").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
				$('#'+campo).focus();
				return false;
			}
			else
			{
				if(caract.test(cajatexto) == false)
				{
					$("#glypcn"+campo).remove();
					$('#'+campo).parent().parent().attr("class", columnas+" has-error has-feedback");
					$('#'+campo).parent().children('span').text("Correo no valido").show();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
					$('#'+campo).focus();
					return false;
				}else
				{
					$("#glypcn"+campo).remove();
					$('#'+campo).parent().parent().attr("class", columnas+" has-success has-feedback");
					$('#'+campo).parent().children('span').hide();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
					return true;				
				}
			} 
		break 
		default: 
		
	}
}

function quitarValidacion(campo,tipo,columnas)
{
	var a=0;
	//columnas = $('#'+campo).parent().parent().attr("class");
	switch (tipo) 
	{ 
		case "texto": 
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().attr("class", columnas);
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
			return true;
		break 
		case "numerico": 
			codigo = document.getElementById(campo).value;
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", columnas);
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
			return true;

		break 
		case "combobox": 
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", columnas);
			$('#'+campo).parent().parent().children('span').hide();
			$('#'+campo).parent().parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
			return true;

		break 
		case "email": 
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", columnas);
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
		break 
		default: 
		
	}
}

function finServicioMant(i){
		var confirm= alertify.confirm('Mensaje','Finalizar Orden de Mantención?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			solicitudTrabID=$("#solicitudTrabID"+i).html();
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_ordentrabmant.php',
					data 	: 
					{
						accion            : "finalizarOT",
						solicitudTrabID   : solicitudTrabID
					},
					dataType: 'json',
					encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
					//$("#fila" + i).remove();
					//$("#fila" + i).animate({'line-height':0},1000).hide(50);
					$("#fila" + i).fadeOut(2000);
				}else
				{
					alertify.error(datos.mensaje);
				}
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		    $('.parametros').slideDown();
		});

}


function update(){
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion           : "update",
				solicitudTrabID  : $("#solicitudTrabID").val(),
				mant             : $("#mant").val(),
				prioridad        : $("#prioridad").val(),
				indSeguridad     : $("#indSeguridad").val(),
				descripTrabajo   : $("#descripTrabajo").val(),
				repuestosmat     : $("#repuestosmat").val(),
				observaciones    : $("#observaciones").val(),
				tipofalla        : JSON.stringify($("#tipofalla").val()),
				tipomant         : $("#tipomant").val()
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			alertify.success(datos.mensaje);
			//alert('#btnInicioServ'+i);
		}else
		{
			alertify.error(datos.mensaje);
		}
	});
}

function iniciarServicioMant(i){
	var solicitudTrabID = $("#solicitudTrabID"+i).html();

	var filas = $("#mecanico"+i).val();
	var aux_prioridad = $("#prioridad"+i).val();
	//console.log(valTabla);
	if((filas == null) || aux_prioridad == null){
		alertify.error('Debes seleccionar Mecánico y Prioridad.');
	}else{
		var valTabla = [];
		for(j=0; j<filas.length; j++){ //Recorre las filas 1 a 1
			personaID = filas[j];

			var fila = {
				personaID
			};
			valTabla.push(fila);
		}

/*
	var filasOpe = $("#mecanico"+i).val();
	if(filasOpe == null){
		alertify.error('Debes seleccionar Mecánico y Prioridad.');
	}else{
*/
		var confirm= alertify.confirm('Mensaje','Iniciar Orden de Mantención?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_ordentrabmant.php',
					data 	: 
					{
						accion                     : "crear",
						prioridad                  : aux_prioridad,
						filasMecanicos             : JSON.stringify(valTabla),
						solicitudTrabID            : $("#solicitudTrabID"+i).html(),
						departamentoAreaID         : $("#departamentoAreaID"+i).html(),
						usuarioID                  : $("#usuarioID"+i).html(),
						maquinariaequiposDetalleID : $("#maquinariaequiposDetalleID"+i).html()
					},
					dataType: 'json',
					encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
					//alert('#btnInicioServ'+i);
					$("#fila"+i).fadeOut(500);
					$('#btnInicioServ'+i).attr("class", "btn btn-warning btn-sm");
					$('#btnInicioServ'+i).attr("onclick", "finServicioMant("+i+")");
					$('.btn-warning').tooltip({title: "Finalizar Orden de trabajo"});
					$('#glypcnbtnInicioServ'+i).attr("class", "glyphicon glyphicon-stop");
					$('#btnOrdenTrabajo'+i).attr("class", "btn btn-primary btn-sm");
					$('#mecanico'+i).prop('disabled', true);
					$('#prioridad'+i).prop('disabled', true);
					$("#fila"+i).fadeIn(500);
					$(".selectpicker").selectpicker('refresh');

				}else
				{
					alertify.error(datos.mensaje);
				}
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		    $('.parametros').slideDown();
		});
	}
}

function finServicioMant(i){
		var confirm= alertify.confirm('Mensaje','Finalizar Orden de Mantención?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			solicitudTrabID=$("#solicitudTrabID"+i).html();
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_ordentrabmant.php',
					data 	: 
					{
						accion            : "finalizarOT",
						solicitudTrabID   : solicitudTrabID
					},
					dataType: 'json',
					encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
					//$("#fila" + i).remove();
					//$("#fila" + i).animate({'line-height':0},1000).hide(50);
					$("#fila" + i).fadeOut(2000);
				}else
				{
					alertify.error(datos.mensaje);
				}
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		    $('.parametros').slideDown();
		});

}