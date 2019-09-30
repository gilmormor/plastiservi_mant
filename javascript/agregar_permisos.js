$(document).ready(function() {


});

function mostrarOpciones(){
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_opciones.php',
			data 	: 
			{
				acciones : "consultarMenuOpciones",
				ema_usu  : $("#ema_usu").val()
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			$("#numReg").val(datos.numReg);
			$("#tabla").html(datos.tabla);
			//$("#tablaMaquinas").fadeIn(2000);
			$("#tablaMaquinas").show();
			configurarTabla();
			$("#btnGuardarM").show();

		}else
		{
			alertify.error(datos.mensaje);
		}


	});

}

$("#btnGuardarM").click(function()
{
	if(sessvars.staInsert==0){
		alertify.alert('Mensaje',"<h3>Usuario no tiene permiso para Guardar.</h3>");
	}else{
		$("#btnGuardarM").addClass( "disabled" );
		$("#btnGuardarM").prop('disabled', true);
		var confirm= alertify.confirm('Mensaje','Desea Guardar?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
		confirm.set({transition:'slide'});

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			
			var filas = $("#tabla").find("tbody tr"); //devulve las filas del body de tu tabla segun el ejemplo que brindaste
			var resultado = "";
			//alert(filas.length);
			var valTabla = [];
			aux_todolleno=true;
			numReg=$('#numReg').val();
			contDiez = 0;
			for(i=1; i<=numReg; i++){ //Recorre las filas 1 a 1
				contDiez++;
				if(contDiez>10){
					contDiez = 1;
					$("#tablaOrdTrab_next").click();
				}
				fk_ope    = $("#id_ope"+i).html();
				open      = $('#open'+i).prop('checked');
				staDelete = $("#staDelete"+i).prop('checked');
				staInsert = $("#staInsert"+i).prop('checked');
				staUpdate = $("#staUpdate"+i).prop('checked');

				var fila = {
					fk_ope,
					open,
					staDelete,
					staInsert,
					staUpdate
				};
				valTabla.push(fila);
			}
			//console.log(valTabla);
			$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_opciones.php',
				data 	: 
				{
					acciones : "guardarPermisos",
					fk_usu   : $("#ema_usu").val(),
					valores  : JSON.stringify(valTabla)
				},
				dataType: 'json',
				encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
					mostrarOpciones();
				}else{
					alertify.error(datos.mensaje);
				}
				
			});

		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});
		/*
		numMaq=$('#numReg').val();
		for(j=1; j<=numMaq; j++){
			$('#estado'+j).prop('disabled', false);
			$('#observaciones'+j).prop('disabled', false);
		}*/
		$("#btnGuardarM").removeClass( "disabled" );
		$("#btnGuardarM").prop('disabled', false);
	}

});

function ocultarMostrarFiltro(){
	if($('#divFiltros').css('display') == 'none'){
		$('#botonD').attr("class", "glyphicon glyphicon-triangle-bottom");
		$('#botonD').attr("title", "Ocultar Filtros");
	}else{
		$('#botonD').attr("class", "glyphicon glyphicon-triangle-top");
		$('#botonD').attr("title", "Mostrar Filtros");
	}

	$('#divFiltros').slideToggle(500);
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
