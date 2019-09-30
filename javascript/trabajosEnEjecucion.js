$(document).ready(function() {
	// Validar campos numericos de pantalla

    $('#cedula').numeric();
    $('#referencia').numeric();

    //*******************************************************************
	// Validar campos numericos de pantalla
    //*******************************************************************
/*
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
*/

	$(".selectpicker").selectpicker('refresh');
	//$("#tablaMaquinas").fadeOut();
	//consultarTrabajosEnEjecucion();
	startTime();

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

function consultarTrabajosEnEjecucion()
{
	//alert('entro');
	//$("#tablaMaquinas").hide();
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_ordentrabmant.php',
			data 	: 
			{
				accion  : "consultarTrabajosEnEjecucion"
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
			//alert(datos.ordenTrab[filaOt]);
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

function startTime(){
	/*
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
*/
	consultarTrabajosEnEjecucion();
	t=setTimeout('startTime()',60000);

}
function checkTime(i){
	if (i<10) {
		i="0" + i;
	}
	return i;
}
