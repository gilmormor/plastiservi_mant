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

	$("#btngenerar").click(function()
	{
		if(verificar())
		{
			var confirm= alertify.confirm('Mensaje','Como desea ver el Reporte?',null,null).set('labels', {ok:'Descargar Archivo', cancel:'Ver en pantalla'}); 	
			confirm.set({transition:'slide'});   	

			confirm.set('onok', function(){ //callbak al pulsar botón positivo
				reporteInscrip("D");
			});
			confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
				reporteInscrip("I");
			    //alertify.error('Has Cancelado la Solicitud');
			});
		}
/*
	$("#btnusuarios").click(function()
	{
		if(verificar())
		{
			var confirm= alertify.confirm('Mensaje','Como desea ver el Reporte?',null,null).set('labels', {ok:'Descargar Archivo', cancel:'Ver en pantalla'}); 	
			confirm.set({transition:'slide'});   	

			confirm.set('onok', function(){ //callbak al pulsar botón positivo
				reporteUsuarios("D");
			});
			confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
				reporteUsuarios("I");
			    //alertify.error('Has Cancelado la Solicitud');
			});
		}
	});
*/

})

function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0,v6=0,v7=0,v8=0,v9=0,v10=0,v11=0,v12=0,v13=0,v14=0,v15=0,v16=0,v17=0,v18=0,v19=0,v20=0;
	var v21=0,v22=0,v23=0,v24=0,v25=0,v26=0,v27=0,v28=0,v29=0,v30=0,v31=0,v32=0,v33=0,v34=0,v35=0,v36=0,v37=0,v38=0,v39=0,v40=0;
	var v41=0,v42=0,v43=0,v44=0,v45=0,v46=0,v47=0,v48=0,v49=0,v50=0,v51=0,v52=0,v53=0,v54=0,v55=0,v56=0;


	v1=validacion('insc_codlapso','texto','col-xs-12 col-sm-3');

	if (v1===false)
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


function desactivarCajas()
{
	//$('#est_ced').attr("disabled", false);

}

function activarCajas()
{
	//$('#est_ced').attr("disabled", false);

}




function blanquearCajas()
{
	//$('#est_ced').val('');

}



function aMays(e, elemento) 
{
	tecla=(document.all) ? e.keyCode : e.which; 
	elemento.value = elemento.value.toUpperCase();
}

function validarFechaEnBlanco()
{
	/*
	codigo = document.getElementById('est_fecnac').value;
	if(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) //si es blanco
	{
		$('#est_fecnac').val(sessvars.est_fecnac);
	}
	*/
}

function reporteInscrip(aux_resplan)
{
	window.open("../clases/reporte_inscritos.class.php?aux_lapso="+$('#insc_codlapso').val()+"&aux_resplan="+aux_resplan+" ","nuevo"); 
}
/*
function reporteUsuarios(aux_resplan)
{
	window.open("../clases/reporte_usuarios.class.php?aux_lapso="+$('#insc_codlapso').val()+"&aux_resplan="+aux_resplan+" ","nuevo"); 
}
*/