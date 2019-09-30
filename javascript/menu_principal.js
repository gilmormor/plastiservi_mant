$(document).ready(function() 
{	// dialog que muestra el gif de espera, en las consultas
	$('#mensaje1').hide();
	//$('#mensaje2').html('<font color="red">'+sessvars.mensajefininsc+"</font>");
	startTime();
	$.ajax({
			type 	: 'POST',
			url		: 'controladores/controlador_datoscolegio.php',
			data 	: 
			{
				accion  : "consultar"
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			$('#titulo').html(datos.datos[0]['nomcorto']);
			$('#titulosist').html(datos.datos[0]['nomcolegio']);
			
		}else
		{
			alertify.error(datos.mensaje);
		}
	});

	tamanoVentana();
	$(window).resize(function() {
		tamanoVentana();
	});


	if(sessvars.fil_inscviva == 1)
	{
		$('#mensaje1').hide();
	}else
	{
		$('#mensaje1').show();
	}
/*
	$("#op39").click(function() { 
		location.assign('http://aprenderaprogramar.com') = this.href; // ir al link 
	});

	$("#op39").click();
*/
	consultarSolicitudesMant();
	//$('#central').load('pantallas/validarOrdenTrabajo.php');


});

function tamanoVentana()
{
	//aux_alto = $( window ).height() - 157
	//$('#central').height(aux_alto);
	var ventana_ancho = $(window).width();
	var ventana_alto = $(window).height();
	var ventana_menualto = $('#menu').height();
	aux_alto=ventana_alto-ventana_menualto-57;
	$('#central').height(aux_alto);

}

nomUsuario="";
$.ajax({
		type 	: 'POST',
		url		: 'controladores/controlador_usuario.php',
		data 	: 
		{
			accion  : "nomUsuario"
		},
		dataType: 'json',
		encode	: true
})
.done(function(datos){
	//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
	nomUsuario = datos.nomUsuario;
	$("#IDusuario").html('<span class="glyphicon glyphicon-user"></span> ' + nomUsuario)
	startTime();
});

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

	$('#mespiepag').html(d+'/'+mes+'/'+today.getFullYear()+' '+h+':'+m+':'+s);
	//aux_fechaHora=d+'/'+mes+'/'+today.getFullYear()+' '+h+':'+m+':'+s;
	t=setTimeout('startTime()',500);
}
function checkTime(i){
	if (i<10) {
		i="0" + i;
	}
	return i;
}

function consultarSolicitudesMant()
{
	//alert('entro');
	$("#tablaMaquinas").hide();
	$.ajax({
			type 	: 'POST',
			url		: 'controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion        : "consultarValidarOT01",
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
			if(datos.numreg>0){
				$("#porValidar").html(datos.tabla);
				configurarTabla();
				$("#ModalCenter").modal("show");
			}
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