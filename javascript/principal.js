$(document).ready(function() 
{	// dialog que muestra el gif de espera, en las consultas
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

	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_seguridad.php',
			data 	: 
			{
				accion : "devolverPermisosPantalla"
			},
			dataType: 'json',
			encode	: true
		})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		sessvars.staDelete = datos.staDelete;
		sessvars.staInsert = datos.staInsert;
		sessvars.staUpdate = datos.staUpdate;
		//alert("Delete: "+datos.staDelete+"  Insert: "+sessvars.staInsert+"  Update: "+sessvars.staUpdate)
	});
});

function amdTodma(aux_fecha)
{
	var fecha_dma=aux_fecha.substr(8,2)+"/"+aux_fecha.substr(5,2)+"/"+aux_fecha.substr(0,4);
	return fecha_dma;
}

/*
$(window).resize(function() {
	ancho_largo();
});

function ancho_largo(){
	var ventana_ancho = $(window).width();
	var ventana_alto = $(window).height();
	var ventana_menualto = $('#menu').height();
	aux_alto=ventana_alto-ventana_menualto-50.8;
	$('#central').height(aux_alto);
}
*/