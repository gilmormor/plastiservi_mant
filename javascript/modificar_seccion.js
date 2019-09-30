$(document).ready(function() 
{

	$('#masec_codsec').focus();
	//$('#guardar').attr("disabled", true);
	$("#guardar").hide();
	//$("#zona_cargando").hide();
 	$("#masec_codsec").change(function(e)
	{
		$("#zona_secciones").html('');
		//$("#zona_cargando").show();
		//$('#zona_cargando').html('<p><img src="../imagenes/cargando.gif" /></p>');
		$("#guardar").hide();
		if (!($('#masec_codsec').val().trim() === ''))
		{

			//alertify.error('Entro en el change y no blanco');

			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_seccion.php?codsec='+$('#masec_codsec').val(),
					data 	: 
					{
						accion: "consultar",
						codsec: $('#masec_codsec').val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				$("#nroreg").val(datos.nroreg);
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
					$("#zona_secciones").html(datos.tabla);
					$("#guardar").show();
					//$('#zona_cargando').html('');
				}else
				{
					$("#zona_secciones").html('');
					alertify.error(datos.mensaje);
					$("#guardar").hide();
					//$('#zona_cargando').html('');
				}
			});
			//$('#zona_cargando').html('');
			/*
			$("#secciones").jqGrid({
				url:'../controladores/controlador_seccion.php?accion=consultar&codsec='+$('#masec_codsec').val(),
				datatype: 'json',
				height: 190,
				width: 830,
				mtype: 'GET',
				colNames:['Cod. Mat','Materia', 'U.C','Capacidad'],
				colModel:[
				    {name:'masec_codmat', index:'masec_codmat', width:80, resizable:false, align:"center"},
				    {name:'mat_descripcion', index:'mat_descripcion', width:300,resizable:false, sortable:true},
				    {name:'unicre', index:'unicre', width:30,align:"center"},
				    {name:'masec_capacidad', index:'masec_capacidad', width:70,align:"right",editable: true,editrules:{number:true},editoptions:{size:"3",maxlength:"2",onkeypress:"return SoloNumeros2(event)"}}
				],
				pager: '#paginacion',
				rowNum:10,
				rowList:[15,30],
				sortname: 'masec_codmat',
				viewrecords: true,
				sortorder: 'asc',
				forceFit : true, 
				cellEdit: true, 
				cellsubmit: 'clientArray',
				loadonce:true,
				caption: 'MATERIAS'
				afterSaveCell : function(rowid,name,val,iRow,iCol) // antes de guardar la el dato
					{
						var codmat = $("#secciones").jqGrid('getCell',rowid,0);
						var codsec = $('#masec_codsec').val();
						var capaci = $("#secciones").jqGrid('getCell',rowid,iCol);
						update_secciones (codmat, codsec, capaci, name)
					}
			});
			*/
		}
	});



	$("#guardar").click(function()
	{
		var confirm= alertify.confirm('Mensaje','Confirmar Guardar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			var j = parseInt($("#nroreg").val());
			for(i = 1; i <= j; i++)
			{
				if($("#staguardar"+i).prop("checked")) //Si esta marcado el estatus para guardar de cada fila
				{
					$("#staguardar"+i).prop("checked", false); //Cambio el estatus para que no vuelva a guardar si le dan de nuevo el boton 
					$codmat = $("#codmat"+i).html();
					$capaci = $("#capaci"+i).val();
					$activa = $("#activa"+i).val();
					$virtua = $("#virtua"+i).val();
					$pendie = $("#pendie"+i).val();
					$actcom = $("#actcom"+i).val();
					$nuevos = $("#nuevos"+i).val();

					//alertify.error($codmat+" "+$capaci+" "+$activa+" "+$virtua+" "+$pendie+" "+$actcom+" "+$nuevos);

					$.ajax({
							type 	: 'POST',
							url		: '../controladores/controlador_seccion.php',
							data 	: 
							{
								accion: "update",
								codsec: $('#masec_codsec').val(),
								codmat: $codmat,
								capaci: $capaci,
								activa: $activa,
								virtua: $virtua,
								pendie: $pendie,
								actcom: $actcom,
								nuevos: $nuevos
							},
							dataType: 'json',
							encode	: true
						})
					.done(function(datos){
						//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
						if(datos.exito)
						{
							alertify.success(datos.mensaje);
						}else
						{
							alertify.error(datos.mensaje);
						}
					});
				}
			}
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});

	});
 });

function blanquearCajas()
{
	$("#zona_materias").html("");
	$('#nomape').val("");
	$("#carr_nombre").val("");
}

function SoloNumeros2(evt)
{
	var nav4 = window.Event ? true : false;
	var key = nav4 ? evt.which : evt.keyCode;
	return (key <= 13 || (key >= 48 && key <= 57)||(key==78)||(key==80)||(key==73));
} 

function update_secciones(codmat, codsec, capaci, name)
{
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_seccion.php',
			data 	: 
			{
				accion: "update",
				codmat: codmat,
				codsec: codsec,
				capaci: capaci,
				namecampo  : name
			},
			dataType: 'json',
			encode	: true
		})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
			alertify.success(datos.mensaje);
		else
			alertify.error(datos.mensaje);
	});

}
function actstaguar(i)
{
	//MArcar checked para saber si fueron modificados o no los valor de una seccion
	//Marcado: Si fueron modificados
	$("#staguardar"+i).prop("checked", true);
}