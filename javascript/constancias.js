$(document).ready(function(){
	$( "#dialog_cargar" ).dialog(
	{
		autoOpen: false,
		width: 150,
		height: 130,
		modal: true,
		open:function()
		{
			$('.ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-icon-only.ui-dialog-titlebar-close').remove();
		},
		buttons: {}
	});
	$(document).ajaxStart(function(){$("#dialog_cargar").dialog( "open" );});
	$(document).ajaxComplete(function(){$("#dialog_cargar").dialog( "close" );});

	$("span.help-block").hide();
	$('#est_ced').numeric();
	$('#est_nacionalidad').attr("disabled", true);
	$('#est_nombres').attr("disabled", true);
	$('#est_apellidos').attr("disabled", true);

	blanquearCajas();
	if(sessvars.tipousuario == 4)
	{
		buscarEstudiante();
		$("#est_cedcombo").show();
		$("#est_ced").hide();
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_estudiante.php',
				data 	: 
				{
					accion     : "estudiantesXrepresentante",
					est_ced    : sessvars.cedula
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				$("#est_cedcombo").html(datos.combo);
				$("#est_cedcombo").val(sessvars.cedula);
			}else
			{
				alertify.error(datos.mensaje);
			}
		});
	}
	if(sessvars.tipousuario == 2)
	{
		$('#ofertanuevo').show();
		desactivarCajas();
		$("#est_ced").focus();
	}

	$('#est_ced').focusin(function()
	{
		desactivarCajas();
	});

	$('#est_ced').focusout(function()
	{
		sessvars.cedula = $('#est_ced').val();
		codigo = document.getElementById('est_ced').value;
		if(!(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo))) //si no es blanco
		{
			buscarEstudiante();
		}
	});

	$("#btnaceptar").click(function()
	{
		if(verificar())
		{
			var confirm= alertify.confirm('Mensaje','Como desea ver la Constancia?',null,null).set('labels', {ok:'Descargar Archivo', cancel:'Ver en pantalla'}); 	
			confirm.set({transition:'slide'});   	

			confirm.set('onok', function(){ //callbak al pulsar botón positivo
				imprimirPlanilla("D");
			});
			confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
				imprimirPlanilla("I");
			});
		}else
		{
			alertify.error('Falta incluir informacion.');
		}
	});



/*
	$('#est_ced').focusout(function()
	{
		alert('entro');
		sessvars.cedula = $('#est_ced').val();
		codigo = document.getElementById('est_ced').value;
		if(!(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo))) //si no es blanco
		{
			buscarEstudiante();
		}
	});
*/

})

function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0;

	v5=validacion('tco_url','texto','col-xs-12 col-sm-4');
	v4=validacion('est_apellidos','texto','col-xs-12 col-sm-4');
	v3=validacion('est_nombres','texto','col-xs-12 col-sm-4');
	v2=validacion('est_nacionalidad','texto','col-xs-12 col-sm-1');
	v1=validacion('est_ced','numerico','col-xs-12 col-sm-3');


	if (v1===false || v2===false || v3===false || v4===false || v5===false)
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


function buscarEstudiante()
{
	$.ajax({
		type 	: 'POST',
		url		: '../controladores/controlador_estudiante.php',
		data 	: 
		{
			accion       : "consultarTodo",
			est_ced      : sessvars.cedula,
			fil_codlapso : sessvars.fil_codlapso
		},
		dataType: 'json',
		encode	: true
	})
	
	.done(function(datosest){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datosest.exito)
		{
			$('#est_exp').val(datosest.datosest.est_exp);
			$('#est_ced').val(sessvars.cedula);
			$('#est_nacionalidad').val(datosest.datosest.est_nacionalidad);
			$('#est_nombres').val(datosest.datosest.est_nombres);
			$('#est_apellidos').val(datosest.datosest.est_apellidos);
			if(datosest.exitoinscrip)
			{
				//activarCajas();
				alertify.success('Estudiante ya esta Inscrito.');
			}else
			{
				//desactivarCajas();
				alertify.error('Estudiante no esta Inscrito.');
			}
		}else
		{
			blanquearCajas();
			alertify.error(datosest.mensaje);
		}
	});
}


function aMays(e, elemento) 
{
	tecla=(document.all) ? e.keyCode : e.which; 
	elemento.value = elemento.value.toUpperCase();
}



function onchangeComboCed()
{
	sessvars.cedula = $("#est_cedcombo").val();
	$("#est_ced").val(sessvars.cedula);
	buscarEstudiante()
}



function imprimirPlanilla(aux_resplan)
{
	aux_url = $('#tco_url').val();
	window.open("../"+aux_url+"?tetwtwtqtwtetthsssaaqqwrraaayqyyqywyyeyeyeyeyewsdfsdfsdfdsfsdtwtwtwtqtqtqtqqttdfgdsfgsdgsdsdfgsdgsdgsdgasfteytrhtjsdfhgsdgtqtqtwtwtwerwtertwgdfgdfgdfgertertadadadasdhtwert=6277625362623562&ced_estudiante="+sessvars.cedula+"&aux_lapso="+sessvars.fil_codlapso+"&aux_resplan="+aux_resplan+" ","nuevo"); 
//	window.open("../clases/constancia_inscripcion.class.php?tetwtwtqtwtetthsssaaqqwrraaayqyyqywyyeyeyeyeyewsdfsdfsdfdsfsdtwtwtwtqtqtqtqqttdfgdsfgsdgsdsdfgsdgsdgsdgasfteytrhtjsdfhgsdgtqtqtwtwtwerwtertwgdfgdfgdfgertertadadadasdhtwert=6277625362623562&ced_estudiante="+sessvars.cedula+"&aux_lapso="+sessvars.fil_codlapso+"&aux_resplan="+aux_resplan+" ","nuevo"); 
}

function activarCajas()
{
	$('#est_ced').attr("disabled", false);
	$('#tco_url').attr("disabled", false);
}

function desactivarCajas()
{
	//$('#est_ced').attr("disabled", false);
	$('#est_nacionalidad').attr("disabled", true);
	$('#est_nombres').attr("disabled", true);
	$('#est_apellidos').attr("disabled", true);
	blanquearCajas();
	//$('#tco_url').attr("disabled", true);
	//$('#btnaceptar').attr("disabled", true);
	//alert('desactivar');
}

function blanquearCajas()
{
	//$('#est_ced').val('');
	$('#est_nacionalidad').val('');
	$('#est_nombres').val('');
	$('#est_apellidos').val('');
	$('#tco_url').val('');
}
