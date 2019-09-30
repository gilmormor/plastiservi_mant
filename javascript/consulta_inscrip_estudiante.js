$(document).ready(function() {
	/*
	$( "#dialog_carga" ).dialog(
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
	$(document).ajaxStart(function(){$("#dialog_carga").dialog( "open" );});
	$(document).ajaxComplete(function(){$("#dialog_carga").dialog( "close" );});
	*/
	$('#est_ced').focus();
	$('#nomape').attr("disabled", true);
	$('#carr_nombre').attr("disabled", true);
	$('#est_ced').numeric();

 
 	$("#est_ced").focusout(function(e)
	{
		if (!($('#est_ced').val().trim() === ''))
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_inscrip_estudiante.php',
					data 	: 
					{
						accion: "buscar_json",
						cedula: $('#est_ced').val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exitoest)
				{
					$('#nomape').val(datos.nomape);
					if(datos.exitoinsc)
					{
						$("#carr_nombre").val(datos.nomcarrera);
						$("#zona_materias").html(datos.tabla);
						alertify.success('Estudiante Inscrito.');
					}
					else
					{
						$("#zona_materias").html("");
						alertify.success('Estudiante no esta Inscrito.');
					}
				}else
				{
					blanquearCajas();
					alertify.error('Estudiante no Existe.');
				}
			});

		}else
		{
			blanquearCajas();
			alertify.error('Incluya un numero de Cedula.');
			$('#est_ced').focus();		
		}
	});
 });

function blanquearCajas()
{
	$("#zona_materias").html("");
	$('#nomape').val("");
	$("#carr_nombre").val("");
}