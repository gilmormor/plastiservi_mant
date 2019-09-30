$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();

	$("#btnAgregar").click(function()
	{
		limpiarInputST();
		quitarverificar();
		$(".selectpicker").selectpicker('refresh');
		$("#ModalCenter").modal("show");
		$(".selectpicker").selectpicker('refresh');
	});
	$("#ModalCenter").draggable({opacity: 0.35, handle: ".modal-header"});

	llenarTablaSolicitudTrabajoMant();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
});


function llenarTablaSolicitudTrabajoMant(){
	$.ajax({
		type 	: 'POST',
		url		: '../controladores/controlador_solicitudtrabmant.php',
		data 	: 
		{
			accion  : "consultaTrabMant"
		},
		dataType: 'json',
		encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			$("#tablaST").html(datos.tabla);
			configurarTabla();
			$(".selectpicker").selectpicker('refresh');
			alertify.success(datos.mensaje);
		}else{
			alertify.error(datos.mensaje);
		}
	});	
}


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
				$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
				$('#'+campo).parent().children('span').text("Campo obligatorio").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
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
				$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
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
					$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
					$('#'+campo).parent().children('span').text("Correo no valido").show();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
					$('#'+campo).focus();
					return false;
				}else
				{
					$("#glypcn"+campo).remove();
					$('#'+campo).parent().attr("class", columnas+" has-success has-feedback");
					$('#'+campo).parent().children('span').hide();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
					return true;				
				}
			} 
		break 
		default: 
		
	}
}



function mostrarH(i){
	if($('#divTabOT'+i).css('display') == 'none'){
		$('#botonD'+i).attr("class", "glyphicon glyphicon-triangle-top");
	}else{
		$('#botonD'+i).attr("class", "glyphicon glyphicon-triangle-bottom");
	}
	$('#divTabOT'+i).slideToggle(500);
}

function eliminarST(i){
	var confirm= alertify.confirm('Mensaje','Desea Eliminar?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
	confirm.set({transition:'slide'});

	confirm.set('onok', function(){ //callbak al pulsar botón positivo

		//console.log(valTabla);
		st = $("#solicitudTrabID" + i).html();
		$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_maquina.php',
			data 	: 
			{
				accion           : "eliminarST",
				solicitudTrabID  : st,
				usuarioID        : $("#usuarioID" + i).html()
			},
			dataType: 'json',
			encode	: true
		})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				$("#fila" + i).fadeOut(2000);
			}else{
				alertify.error(datos.mensaje);
			}
		});

	});
	confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
	    alertify.error('Has Cancelado la Solicitud');
	});
}

function modificarObs(i){
	limpiarInputST();
	quitarverificar();
	$("#exampleModalLongTitle").html('Modificar Solicitud #' + $("#solicitudTrabID" + i).html());
	$("#solicitudTrabIDM").val($("#solicitudTrabID"+i).html());
	$("#filaST").val(i);
	$("#usuarioIDM").val($("#usuarioID"+i).html());
	MaqIDDptoAreaID = $("#maquinariaequiposDetalleID"+i).html() + "," + $("#departamentoAreaID"+i).html() + "," + $("#codigoInterno" + i).html();
	$("#MaqIDDptoAreaID").val(MaqIDDptoAreaID);
	$("#prioridadM").val($("#prioridad"+i).html());
	$("#descripcionM").val($("#observaciones"+i).html());
	$("#ModalCenter").modal("show");
	$(".selectpicker").selectpicker('refresh');
}

function limpiarInputST(){
	$("#exampleModalLongTitle").html("Agregar Solicitud");
	$("#usuarioIDM").val('');
	$("#MaqIDDptoAreaID").val('');
	$("#prioridadM").val('');

	$("#ema_usuM").val('');
	$("#solicitudTrabIDM").val('');
	$("#filaST").val('');
	$("#descripcionM").val('');
	$(".selectpicker").selectpicker('refresh');
}

function quitarverificar(){
	quitarValidacion('MaqIDDptoAreaID','combobox','col-xs-12 col-sm-12');
	quitarValidacion('prioridadM','combobox','col-xs-12 col-sm-12');
	quitarValidacion('descripcionM','texto','col-xs-12 col-sm-12');


	quitarValidacion('descripTrabajo','texto','col-xs-12 col-sm-12');
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

function verificar()
{
	var v1=0,v2=0,v3=0;

	v1=validacion('descripcionM','texto','col-xs-12 col-sm-12');
	v2=validacion('prioridadM','combobox','col-xs-12 col-sm-12');
	v3=validacion('MaqIDDptoAreaID','combobox','col-xs-12 col-sm-12');

	if (v1===false || v2===false || v3===false)
	{
		return false;
	}else{
		return true;
	}
}

function update(){
	MaqIDDptoAreaID = $("#MaqIDDptoAreaID").val();
	vecMaqDpto = MaqIDDptoAreaID.split(',');
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_solicitudtrabmant.php',
			data 	: 
			{
				accion                     : "insertUpdate",
				maquinariaequiposDetalleID : vecMaqDpto[0],
				departamentoAreaID         : vecMaqDpto[1],
				descripcion                : $("#descripcionM").val(),
				prioridad                  : $("#prioridadM").val(),
				solicitudTrabID            : $("#solicitudTrabIDM").val()
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			i=$("#filaST").val();
			if($("#solicitudTrabIDM").val()==""){
				llenarTablaSolicitudTrabajoMant();
			}else{
				$("#observaciones" + i).html($("#descripcionM").val());
				$("#codigoInterno" + i).html(vecMaqDpto[2]);
				$("#prioridad" + i).html($("#prioridadM").val());
			}
			alertify.success(datos.mensaje);
			$("#ModalCenter").modal("hide");
			//alert('#btnInicioServ'+i);
		}else
		{
			alertify.error(datos.mensaje);
		}
	});
}