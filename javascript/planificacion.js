$(document).ready(function() {
	// Validar campos numericos de pantalla
	mostrarOcultarCajas(100);
    $('#pla_porc').numeric();
    $('#total').val('0');
    $("span.help-block").hide();
    //*******************************************************************
	var planificacion = new Array();
	$('#pla_fecha').datepicker();


	$("#agregar").click(function()
	{
		if (verificar())
		{
			var totalporc = parseInt($('#total').val()) + parseInt($('#pla_porc').val())
			if(totalporc>100)
			{
				alertify.error('Sobrepasa el 100%.')
				$("#pla_porc").focus()
			}else
			{
				agregar_fila()
				validarfilas()
			}	
		}	
	})

	$("#guardar").click(function()
	{
		var confirm= alertify.confirm('Mensaje','Confirmar Guardar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo

			var j = parseInt($('#con_filas').val())
			//alert(salida_ciclo)
			var con_filas=document.getElementById("con_filas").value
			con_filas=parseInt(con_filas)+2

			var tabla=document.getElementById("tabla_detalle")
			var total_filas=tabla.rows.length
			var fila_real=total_filas-2

			var valor = $('#codseccodmat').val();
			var secmat = valor.split('&');
			var aux_guardo = true;
			for(i=1;i<=j;i++)
			{
				if ( (($('#porcentaje'+i).length) > 0) && (parseInt($('#fid'+i).val()) == 0) ) //POrcentaje y el fid de la fila deben ser mayor a cero
				{
					$.ajax({
						type 	: 'POST',
						url		: '../controladores/controlador_planificacion.php',
						data 	: 
						{
							accion : "insertar",
							pla_lapso   : $('#lapso').val(),
							pla_codsec  : secmat[0],
							pla_codmat  : secmat[1],
							pla_porc	: $('#porcentaje'+i).val(),
							pla_desc    : $('#instrumento'+i).val(),
							pla_fecha   : $('#fecha'+i).val(),
							pla_objeti	: $('#objetivo'+i).val()
						},
						dataType: 'json',
						encode	: true
					})
					.done(function(datos){
						//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
						if(datos.exito)
						{
							//alertify.success(datos.mensaje);
							$('#guardar').attr("disabled", true);
							$('#agregar').attr("disabled", false);
							$('#fid'+i).val(i);
						}else
						{
							alertify.error(datos.mensaje);
							aux_guardo = false;
						}
					});
				}
			}
			if(aux_guardo)
			{
				alertify.success('Planificación se Guardo con Exito.');
			}
			else
			{
				alertify.error('Ocurrio un error al Guardar. Verifique los datos.');
			}
			blanquearCampos();
			buscarMostrarPlanificacion();
			mostrarOcultarCajas(100);
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});

	})

	$('#lapso').change(function(e)
	{
		buscarMostrarPlanificacion();
	});
	$('#codseccodmat').change(function(e)
	{
		buscarMostrarPlanificacion();
	});

});

function blanquearCampos()
{
	$("#tabla_detalle").html('');
	$('#codseccodmat').attr("disabled", false);
	$('#lapso').attr("disabled", false);
	$('#codseccodmat').val('');
	$('#lapso').val('');
	$('#porcentaje').val('');
	$('#instrumento').val('');
	$('#fecha').val('');
	$('#objetivo').val('');
	encabezadoTablaDetalle();
}


function agregar_fila()
{
	$("#tabla_detalle").show();
	var con_filas=document.getElementById("con_filas").value
	con_filas=parseInt(con_filas)+1
	document.getElementById("con_filas").value=con_filas
	 
	
	var tabla=document.getElementById("tabla_detalle")
	var total_filas=tabla.rows.length
	var fila_real=total_filas-2
	
	
	var fila=tabla.insertRow(total_filas)

	var pla_porc = document.getElementById("pla_porc").value
	var pla_desc = document.getElementById("pla_desc").value
	var pla_fecha = document.getElementById("pla_fecha").value
	var pla_objeti = document.getElementById("pla_objeti").value
	
	var columna1=fila.insertCell(0)
//	columna1.innerHTML='<input value="-" onClick="eliminar_fila(this)" type="button" class="form-control" title="Eliminar">'
	columna1.innerHTML='<input src="../imagenes/borrar.png" value="0" name="fid'+con_filas+'" id="fid'+con_filas+'" onClick="eliminar_fila(this,0)" type="image" class="form-control input-sm" title="Eliminar">'
	columna1.align="center"
	
	var columna2=fila.insertCell(1)
	columna2.innerHTML= '<input type="text" value="'+pla_desc+'" name="instrumento'+con_filas+'" id="instrumento'+con_filas+'" class="form-control input-sm" readonly disabled>'	
	
	var columna3=fila.insertCell(2)
	columna3.innerHTML='<input type="text" value="'+pla_fecha+'" name="fecha'+con_filas+'" id="fecha'+con_filas+'" class="form-control input-sm" readonly disabled>'

	var columna4=fila.insertCell(3)
	columna4.innerHTML='<input type="text" value="'+pla_objeti+'" name="objetivo'+con_filas+'" id="objetivo'+con_filas+'" class="form-control input-sm" readonly disabled>'

	var columna5=fila.insertCell(4)
	columna5.innerHTML='<input type="text" style="text-align:right;" value="'+pla_porc+'" name="porcentaje'+con_filas+'" id="porcentaje'+con_filas+'" class="form-control input-sm" readonly disabled>'

	
	$("#pla_desc").val('')
	$("#pla_porc").val('')
	$("#pla_fecha").val('')
	$("#pla_objeti").val('')
	$("#pla_porc").focus()

}
	
function eliminar_fila(fila_real,i)
{
	var confirm= alertify.confirm('Mensaje','Confirmar Eliminar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
	confirm.set({transition:'slide'});   	

	confirm.set('onok', function(){ //callbak al pulsar botón positivo
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_planificacion.php',
				data 	: 
				{
					accion : "eliminar",
					fid   : $('#fid'+i).val()
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				$('#fid'+i).val(i);

			}else
			{
				alertify.error(datos.mensaje);
			}
		});
		var fila_tabla=fila_real.parentNode.parentNode.rowIndex;
		var tabla=document.getElementById("tabla_detalle")
		tabla.deleteRow(fila_tabla)	
		
		var filas_borradas=document.getElementById("con_fil_bor").value 
		filas_borradas=parseInt(filas_borradas)+1
		document.getElementById("con_fil_bor").value=filas_borradas
		validarfilas()
		$('#guardar').attr("disabled", true);
		$('#agregar').attr("disabled", false);

	});
	confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
	    alertify.error('Has Cancelado la Solicitud');
	});

	//calcular_total()
	
}

function validarfilas()
{
	if(($('#tabla_detalle tr').length)>2)
	{
		$('#lapso').attr("disabled", true);
		$('#codseccodmat').attr("disabled", true);
	}else
	{
		$('#lapso').attr("disabled", false);
		$('#codseccodmat').attr("disabled", false);
	}
	calcularTotal()
	if(parseInt($('#total').val())>=100)
	{
		$('#agregar').attr("disabled", true);
		$('#guardar').attr("disabled", false);
	}else
	{
		$('#agregar').attr("disabled", false);
		$('#guardar').attr("disabled", true);
	}
	mostrarOcultarCajas(parseInt($('#total').val()));
}

function calcularTotal()
{
	var aux_total = 0
	var j = parseInt($('#con_filas').val())

	for(i=1;i<=j;i++)
	{
		if ( ($('#porcentaje'+i).length) > 0 )
		{
			//alert($('#porcentaje'+i).val()+' - '+i)
			aux_total = aux_total + parseInt($('#porcentaje'+i).val())			
		}
	}
	$('#total').val(aux_total)
	mostrarOcultarCajas(aux_total)

}

function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0,v6=0;
	v1=validacion('lapso');
	v2=validacion('codseccodmat');
	v3=validacion('pla_porc');
	v4=validacion('pla_desc');
	v5=validacion('pla_fecha');
	v6=validacion('pla_objeti');
	if (v1===false || v2===false || v3===false || v4===false || v5===false || v6===false ) {
		//$("#exito").hide();
		//$("#error").show();
		return false;
	}else{
		//$("#error").hide();
		//$("#exito").show();
		return true;
	}
}

function validacion(campo)
{
	var a=0;
	if (campo==='lapso')
	{
		indice = document.getElementById(campo).selectedIndex;
		if( indice == null || indice == 0 ) 
		{
			$('#'+campo).parent().parent().attr("class", "form-group has-error");
			return false;
		}
		else
		{
			$('#'+campo).parent().parent().attr("class", "form-group has-success");
			return true;
		}
	}

	if (campo==='codseccodmat')
	{
		indice = document.getElementById(campo).selectedIndex;
		if( indice == null || indice == 0 ) 
		{
			$('#'+campo).parent().parent().attr("class", "form-group has-error");
			return false;
		}
		else
		{
			$('#'+campo).parent().parent().attr("class", "form-group has-success");
			return true;
		}
	}

	if (campo==='pla_porc')
	{
		codigo = document.getElementById(campo).value;
		if( codigo == null || codigo.length == 0 || /^\s+$/.test(codigo) ) {
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
			$('#'+campo).parent().children('span').text("Campo obligatorio").show();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
			return false;

		}
		else
		{
			if(isNaN(codigo)) 
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
				$('#'+campo).parent().children('span').text("No Acepta letras").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
				return false;
			}
			else
			{
				if(codigo>100)
				{
					$("#glypcn"+campo).remove();
					$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
					$('#'+campo).parent().children('span').text("Valor < 100").show();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
					return false;
				}
				else
				{
					$("#glypcn"+campo).remove();
					$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
					$('#'+campo).parent().children('span').hide();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
					return true;
				}
			}
	    }
	}

	if (campo==='pla_desc')
	{
		cajatexto = document.getElementById(campo).value;
		if( cajatexto == null || cajatexto.length == 0 || /^\s+$/.test(cajatexto) )
		{
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
			$('#'+campo).parent().children('span').text("Campo obligatorio").show();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
			return false;
		}
		else
		{
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
			return true;
		} 
	}

	if (campo==='pla_fecha')
	{
		cajatexto = document.getElementById(campo).value;
		if( cajatexto == null || cajatexto.length == 0 || /^\s+$/.test(cajatexto) )
		{
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
			$('#'+campo).parent().children('span').text("Campo obligatorio").show();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
			return false;
		}
		else
		{
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
			return true;
		}
	}
	if (campo==='pla_objeti')
	{
		cajatexto = document.getElementById(campo).value;
		if( cajatexto == null || cajatexto.length == 0 || /^\s+$/.test(cajatexto) )
		{
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
			$('#'+campo).parent().children('span').text("Campo obligatorio").show();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
			return false;
		}
		else
		{
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
			return true;
		} 
	}
}

function buscarMostrarPlanificacion()
{
	var valor = $('#codseccodmat').val();
	var secmat = valor.split('&');
	if (!($('#codseccodmat').val().trim() === '') && !($('#lapso').val().trim() === ''))
	{
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_planificacion.php',
				data 	: 
				{
					accion : "buscarmostrar",
					pla_lapso   : $('#lapso').val(),
					pla_codsec  : secmat[0],
					pla_codmat  : secmat[1]
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				$("#tabla_detalle").html(datos.tabla);
				$('#guardar').attr("disabled", true);
				$('#agregar').attr("disabled", true);
				$('#con_filas').val(datos.nroreg);
				$("#tabla_detalle").show();
				validarBotonAgregar();
				mostrarOcultarCajas(datos.totalporcen);
			}else
			{
				alertify.error(datos.mensaje);
				encabezadoTablaDetalle();
				mostrarOcultarCajas(0);
			}
		});
	}
	else
	{
		$("#tabla_detalle").html('');
	}
}

function encabezadoTablaDetalle()
{
	$("#tabla_detalle").hide();
    $conenidotabla = '<tr><td colspan="5" align="center" class="bg-primary">Detalle de Planificación</td></tr><tr><td colspan="4" align="right">Total %:</td><td><input type="text" style="text-align:right;" name="total" id="total" value="0" readonly class="form-control input-sm"></td></tr>'
    $("#tabla_detalle").html($conenidotabla);
	$('#guardar').attr("disabled", true);
	$('#agregar').attr("disabled", false);
   
}

function validarBotonAgregar()
{
	var totalporc = parseInt($('#total').val())
	if(totalporc<100)
	{
		$('#agregar').attr("disabled", false);
		$("#pla_porc").focus();
	}	
}

function activarCajas()
{
	$('#porcentaje').attr("disabled", false);
	$('#instrumento').attr("disabled", false);
	$('#fecha').attr("disabled", false);
	$('#objetivo').attr("disabled", false);
}
function desactivarCajas()
{
	$('#porcentaje').attr("disabled", true);
	$('#instrumento').attr("disabled", true);
	$('#fecha').attr("disabled", true);
	$('#objetivo').attr("disabled", true);
}

function mostrarOcultarCajas(total)
{
	var j = parseInt($('#con_filas').val())
	var contNoGuardados = 0

	$("#tablamaestra tbody tr").each(function (index)
	{
		if(total>=100)
		{
			if(index>0)
			{
				$(this).hide();
				for(i=1;i<=j;i++)
				{
					var valfid = parseInt($('#fid'+i).val());
					if(valfid==0 && (($('#tabla_detalle tr').length)>0))
						contNoGuardados++;
				}
				if(contNoGuardados>0 && index==3)
					$(this).show();
			}
		}
		else
		{
			$(this).show();
		}
	});

}