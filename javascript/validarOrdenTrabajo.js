$(document).ready(function() {
	// Validar campos numericos de pantalla
/*
    $('#cedula').numeric();
    $('#referencia').numeric();
*/
    //*******************************************************************
	// Validar campos numericos de pantalla
    //*******************************************************************

    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});


//	$(".selectpicker").selectpicker('refresh');
	//$("#tablaMaquinas").fadeOut();
	$( "#ModalCenter" ).draggable({opacity: 0.35, handle: ".modal-header"});
	consultarSolicitudesMant();

});
//$('#example-getting-started').multiselect();
/*
$("#boton2").click(function()
{
	alert("prueba");
	createOptions(3);
});
*/

$("#btnGuardarM").click(function()
{
	//Validar los campos obligatorios
	//alert(sessvars.fil_codlapso);
	quitarverificar();
	if(sessvars.staInsert==0){
		alertify.alert('Mensaje',"<h3>Usuario no tiene permiso para Guardar.</h3>");
	}else{
		if(verificar())
		{
			update();
		}else{
			alertify.error("Falta incluir informacion");
		}
	}
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

function consultarSolicitudesMant()
{
	//alert('entro');
	$("#tablaMaquinas").hide();
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion        : "consultarValidarOT",
				usuarioID     : sessvars.usuarioID,
				maquinaID     : ''
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
		//$("#tablaMaquinas").fadeOut();
		$("#tablaMaquinas").html(datos.tabla);
		//$("#tablaMaquinas").fadeIn(2000);
		$("#tablaMaquinas").show();
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
		aux_diez = 10;
		for(i=1; i<=aux_entero; i++){
			$("#tablaOrdTrab_next").click();
			$(".selectpicker").selectpicker('refresh');
			$('.btn-primary').tooltip({placement: "top"});
			$('.btn-danger').tooltip({placement: "right"});
			$('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
			
			var nFilas = $("#tablaOrdTrab tbody tr").length;
			for(e=1; e<=nFilas; e++){
				filaOt=$("#solicitudTrabID"+(e+aux_diez)).html();
				//alert(datos.ordenTrab[filaOt].length);
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


		//console.log(datos.personas);
		$(".selectpicker").selectpicker('refresh');
		var nFilas = $("#tablaOrdTrab tbody tr").length;
		//alert(nFilas);
		for(i=1; i<=nFilas; i++){
			filaOt=$("#solicitudTrabID"+i).html();
			//alert(datos.ordenTrab[filaOt].length);
			if(datos.ordenTrab[filaOt]){
				for(j=0; j<datos.ordenTrab[filaOt].length; j++){
					$('#mecanico'+i+' option[value='+datos.ordenTrab[filaOt][j]+']').prop('selected', true);
					$("#mecanico"+i).selectpicker('refresh');					
				}
			}
		}

		$(".selectpicker").selectpicker('refresh');
		//createOptions(3);

	});

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

function validarOrdenTrabajo(i){
/*
	var confirm= alertify.confirm('Mensaje','Validar Orden de Mantención?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
	confirm.set({transition:'slide'});   	

	confirm.set('onok', function(){ //callbak al pulsar botón positivo
*/
	if(sessvars.staUpdate==0){
		alertify.alert('Mensaje',"<h3>Usuario no tiene permiso para Modificar.</h3>");
	}else{
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
				$("#exampleModalLongTitle").html('Validar Orden de Trabajo Nro.'+datos.ordentrabmantID)
				$("#solicitudTrabID").val($("#solicitudTrabID"+i).html());
				$("#ordentrabmantID").val($("#ordentrabmantID"+i).html());
				$("#departamentoAreaIDM").val($("#nombreDpto"+i).html());
				$("#ema_usuM").val($("#ema_usu"+i).html());
				$("#fechaHoraini").val(datos.fechaini);
				$("#responsable").val($("#mecanico"+i).val())
				$(".selectpicker").selectpicker('refresh');
				$("#responsable").attr("disabled", true);
				aux_ttmID = datos.ttmID.split(',');
				$("#ttmID").val(aux_ttmID);
				$("#ttmID").attr("disabled", true);

				$("#descripTrabajo").val(datos.descripTrabajo);
				$("#mant").val(datos.mant);
				$("#tipofalla").val(aux_tipofalla);
				$("#tipomant").val(datos.tipomant);
				$("#descripTrabajo").val(datos.descripTrabajo);
//				$("#indSeguridad").val(datos.indSeguridad);
				$("#repuestosmat").val(datos.repuestosmat);
				$("#observaciones").val(datos.observaciones);
				$("#prioridad").val(datos.prioridad);
				$("#iFila").val(i);
				$("#ModalCenter").modal("show");
				$(".selectpicker").selectpicker('refresh');
			}else
			{
				alertify.error(datos.mensaje);
			}
		});
	}
/*
	});
	confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
	    alertify.error('Has Cancelado la Solicitud');
	    $('.parametros').slideDown();
	});
*/
}

function update(){
	var confirm= alertify.confirm('Mensaje','Guardar y cerrar Orden de Trabajo?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
	confirm.set({transition:'slide'});   	

	confirm.set('onok', function(){ //callbak al pulsar botón positivo

		$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion           : "updateEvaluacion",
				solicitudTrabID  : $("#solicitudTrabID").val(),
				ordentrabmantID  : $("#ordentrabmantID").val(),
				evaluacion       : $("#evaluacion").val(),
				statusaceprech   : $("#statusaceprech").val(),
				obserEvaluacion  : $("#obserEvaluacion").val(),
				obseraceprech    : $("#obseraceprech").val()
			},
			dataType: 'json',
			encode	: true
		})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				$("#ModalCenter .close").click();
				i = $("#iFila").val();
				$("#fila" + i).fadeOut();
				//alert('#btnInicioServ'+i);
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



function limpiarInputOT(){
	$("#exampleModalLongTitle").html('Validar Orden de Trabajo');
	$("#solicitudTrabID").val('');
	$("#ordentrabmantID").val('');
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
	$("#evaluacion").val('');
	$("#statusaceprech").val('');
	$("#obserEvaluacion").val('');
	$("#obseraceprech").val('');
	$(".selectpicker").selectpicker('refresh');

}

function startTime(){
	today=new Date();
	h=today.getHours();
	m=today.getMinutes();
	s=today.getSeconds();
	d=today.getDate();
	mes=today.getMonth() + 1;
	h=checkTime(h);
	m=checkTime(m);
	s=checkTime(s);
	d=checkTime(d);	
	mes=checkTime(mes);

	fechaHora=d+'/'+mes+'/'+today.getFullYear()+' '+h+':'+m+':'+s;
	//aux_fechaHora=d+'/'+mes+'/'+today.getFullYear()+' '+h+':'+m+':'+s;

	return fechaHora;
}
function checkTime(i){
	if (i<10) {
		i="0" + i;
	}
	return i;
}

function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0,v6=0,v7=0,v8=0,v9=0,v10=0,v11=0;

	if($("#statusaceprech").val()==0 || $("#statusaceprech").val()==2){
		v11=validacion('obseraceprech','texto','col-xs-12 col-sm-6');
	}
	if($("#evaluacion").val()<=2){
		v10=validacion('obserEvaluacion','texto','col-xs-12 col-sm-6');
	}
	v9=validacion('statusaceprech','combobox','col-xs-12 col-sm-6');
	v8=validacion('evaluacion','combobox','col-xs-12 col-sm-6');
	v7=validacion('mant','combobox','col-xs-12 col-sm-3');
	v6=validacion('descripTrabajo','texto','col-xs-12 col-sm-6');
	v5=validacion('ttmID','combobox','col-xs-12 col-sm-6');
	v4=validacion('repuestosmat','texto','col-xs-12 col-sm-6');
	v3=validacion('observaciones','texto','col-xs-12 col-sm-6');
	v2=validacion('tipofalla','combobox','col-xs-12 col-sm-3');
	v1=validacion('tipomant','combobox','col-xs-12 col-sm-3');

	if (v1===false || v2===false || v3===false || v4===false || v5===false || v6===false || v7===false || v8===false || v9===false || v10===false || v11===false)
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
	quitarValidacion('ttmID','combobox','col-xs-12 col-sm-6'); //en comentario por el rollo de ser tan grande la tabla de plantel de procedencia
	quitarValidacion('repuestosmat','texto','col-xs-12 col-sm-6');
	quitarValidacion('observaciones','texto','col-xs-12 col-sm-6');
	quitarValidacion('tipofalla','combobox','col-xs-12 col-sm-3');
	quitarValidacion('tipomant','combobox','col-xs-12 col-sm-3');
	quitarValidacion('obseraceprech','texto','col-xs-12 col-sm-6');
	quitarValidacion('obserEvaluacion','texto','col-xs-12 col-sm-6');
	quitarValidacion('statusaceprech','combobox','col-xs-12 col-sm-6');
	quitarValidacion('evaluacion','combobox','col-xs-12 col-sm-6');
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
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon form-control-feedback'></span>");
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
				$('#'+campo).parent().parent().append("<span id='glypcn"+campo+"' class='glyphicon form-control-feedback'></span>");
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
		var confirm= alertify.confirm('Mensaje','Finalizar Orden de Mantencion?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
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