$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();

	$("#btnAgregar").click(function()
	{
		limpiarInputST();
		quitarverificar();
		$("#ModalCenter").modal("show");
		$('#descripM').focus();
	});
	$("#ModalCenter").draggable({opacity: 0.35, handle: ".modal-header"});

	llenarTabla();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
});


function llenarTabla(){
	$.ajax({
		type 	: 'POST',
		url		: '../controladores/controlador_maquinaequipofueraservicio.php',
		data 	: 
		{
			accion  : "llenarTabla"
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

function eliminar(i){
	var confirm= alertify.confirm('Mensaje','Desea Eliminar?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
	confirm.set({transition:'slide'});

	confirm.set('onok', function(){ //callbak al pulsar botón positivo

		//console.log(valTabla);
		$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_maquinaequipofueraservicio.php',
			data 	: 
			{
				accion          : "eliminar",
				fueraServicioID : $("#fueraServicioID" + i).html()
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

function modificar(i){
	limpiarInputST();
	quitarverificar();
	$("#exampleModalLongTitle").html('Modificar #' + $("#fueraServicioID" + i).html());
	$("#fueraServicioIDM").val($("#fueraServicioID"+i).html());
	$("#filaST").val(i);
	$("#descripcionM").val($("#descripcion"+i).html());
	$("#maquinariaequiposDetalleIDM").val($("#maquinariaequiposDetalleID"+i).html());
	$("#ModalCenter").modal("show");
	$('#maquinariaequiposDetalleIDM').prop('disabled', true);
	$(".selectpicker").selectpicker('refresh');
}

function limpiarInputST(){
	$("#exampleModalLongTitle").html("Agregar");
	$("#filaST").val('');
	$("#usuarioIDM").val('');
	$("#fueraServicioID").val('');
	$("#maquinariaequiposDetalleIDM").val('');
	$("#descripcionM").val('');
	$('#maquinariaequiposDetalleIDM').prop('disabled', false);
	$(".selectpicker").selectpicker('refresh');
}

function quitarverificar(){
	quitarValidacion('maquinariaequiposDetalleIDM','combobox','col-xs-12 col-sm-12');
	quitarValidacion('descripcionM','texto','col-xs-12 col-sm-12');
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
	var v1=0,v2=0;

	v2=validacion('maquinariaequiposDetalleIDM','combobox','col-xs-12 col-sm-12');
	v1=validacion('descripcionM','texto','col-xs-12 col-sm-12');

	if (v1===false || v2===false)
	{
		return false;
	}else{
		return true;
	}
}

function update(){
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_maquinaequipofueraservicio.php',
			data 	: 
			{
				accion                     : "insertUpdate",
				fueraServicioID            : $("#fueraServicioIDM").val(),
				descripcion                : $("#descripcionM").val(),
				maquinariaequiposDetalleID : $("#maquinariaequiposDetalleIDM").val()
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			i=$("#filaST").val();
			if($("#fueraServicioIDM").val()==""){
				llenarTabla();
			}else{
				$("#descripcion" + i).html($("#descripcionM").val());
				$("#maquinariaequiposDetalleID" + i).html($("#maquinariaequiposDetalleIDM").val());
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