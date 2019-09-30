$(document).ready(function() {
	// Validar campos numericos de pantalla
    $('#depsoftservi').numeric();
    $('#depcol').numeric();
    $('#deppad').numeric();
    $("span.help-block").hide();
    //*******************************************************************
	$("#divsoft").hide();
	$("#divcol").hide();
	$("#divpad").hide();

	$("#vidtablasoft").hide();
	$("#vidtablacol").hide();
	$("#vidtablapad").hide();

	$('#faltantesoft').val(0);
	$('#faltantecol').val(0);
	$('#faltantepad').val(0);

	$('#btnsoft').attr("disabled", true);
	$('#btncol').attr("disabled", true);
	$('#btnpad').attr("disabled", true);

	$("#depsoftservi").focus()

	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_filtros.php',
			data 	: 
			{
				accion: "filtros"
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datosfiltro){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datosfiltro.exito)
		{
			aux_filtrofechadeposito = datosfiltro.datos.fil_fecha_dep;
			aux_filtrofechadeposito_dma = datosfiltro.datos.fil_fecha_dep_dma;

			//alert($('#numcuentasoft').val());
			$('#fechadeposito').html('<p style="color:#FF0000";><b>Sólo se aceptan depositos con fecha a partir del: '+aux_filtrofechadeposito_dma+'</b></p>')
			if(datosfiltro.datos.fil_depsoftservi==1)
			{
				$('#btnsoft').attr("disabled", false);
				$("#divsoft").show();
				sumamonto = number_format(parseFloat(datosfiltro.datos.fil_mntoweb)+parseFloat(datosfiltro.datos.fil_carnet),2) ;
				//$("#lbltotaldepsoft").html("Monto a depósitar: Bs."+sumamonto);
				//$("#lbltotaldepsoft").html("Total deposito Softservi debe ser Igual a: Bs."+sumamonto);

			}else
			{
				$('#btnsoft').attr("disabled", true);
			}
			if(datosfiltro.datos.fil_depcolegio==1)
			{
				$('#btncol').attr("disabled", false);
				$("#divcol").show();
				sumamonto = number_format(parseFloat(datosfiltro.datos.fil_mtoinsc),2) ;
				//$("#lbltotaldepcol").html("Monto a depósitar: Bs."+sumamonto);
				//$("#lbltotaldepcol").html("Total deposito Colegio debe ser Igual a: Bs."+sumamonto);

			}else
			{
				$('#btncol').attr("disabled", true);
			}
			if(datosfiltro.datos.fil_depconpadres==1)
			{
				$('#btnpad').attr("disabled", false);
				$("#divpad").show();
				sumamonto = number_format(parseFloat(datosfiltro.datos.fil_mtoconpadres),2) ;
				//$("#lbltotaldeppad").html("Monto a depósitar: Bs."+sumamonto);
				//$("#lbltotaldeppad").html("Total deposito Padres debe ser Igual a: Bs."+sumamonto);
			}else
			{
				$('#btnpad').attr("disabled", true);
			}
		}else
		{
			alertify.error(datos.mensaje);
			location.href = "index.php";
		}
	});

//	mostrartabladepositos('../controladores/controlador_deposito_softservi.php',sessvars.fil_numcuentasoft,"#tabladepsoft","#vidtablasoft",sessvars.fil_webcarnet,sessvars.fil_webcarnetmax,1);
	mostrartabladepositos('../controladores/controlador_deposito.php',sessvars.fil_numcuentasoft,"#tabladepsoft","#vidtablasoft",sessvars.fil_webcarnet,sessvars.fil_webcarnetmax,1);
	mostrartabladepositos('../controladores/controlador_deposito.php',sessvars.fil_numcuentacol,"#tabladepcol","#vidtablacol",sessvars.fil_mtoinsc,sessvars.fil_mtoinscmax,2);
	mostrartabladepositos('../controladores/controlador_deposito.php',sessvars.fil_numcuentapad,"#tabladeppad","#vidtablapad",sessvars.fil_mtoconpadres,sessvars.fil_mtoconpadresmax,3);

	$("#btnsoft").click(function()
	{
		//utilizarDeposito('../controladores/controlador_deposito_softservi.php',$('#depsoftservi').val(),sessvars.fil_numcuentasoft,"#tabladepsoft","#vidtablasoft",sessvars.fil_webcarnet,sessvars.fil_webcarnetmax,1)
		utilizarDeposito('../controladores/controlador_deposito.php',$('#depsoftservi').val(),sessvars.fil_numcuentasoft,"#tabladepsoft","#vidtablasoft",sessvars.fil_webcarnet,sessvars.fil_webcarnetmax,1)
	})

	$("#btncol").click(function()
	{
		utilizarDeposito('../controladores/controlador_deposito.php',$('#depcol').val(),sessvars.fil_numcuentacol,"#tabladepcol","#vidtablacol",sessvars.fil_mtoinsc,sessvars.fil_mtoinscmax,2)
	})
	$("#btnpad").click(function()
	{
		utilizarDeposito('../controladores/controlador_deposito.php',$('#deppad').val(),sessvars.fil_numcuentapad,"#tabladeppad","#vidtablapad",sessvars.fil_mtoconpadres,sessvars.fil_mtoconpadresmax,3)
	})

	$("#btnsiguiente").click(function()
	{
		aux_bandera = true;
		if(!($('#btnsoft').attr("disabled")))
		{
			aux_bandera = false;
			alertify.error('Faltan depositos en Softservi');
		}
		if(!($('#btncol').attr("disabled")))
		{
			aux_bandera = false;
			alertify.error('Faltan depositos en Colegio');
		}
		if(!($('#btnpad').attr("disabled")))
		{
			aux_bandera = false;
			alertify.error('Faltan depositos en Consejo de Padres');
		}
		if(aux_bandera)
		{
			//Validar cambiando la variable de sesion $_SESSION["statusdepositosok"] a true ya que 
			$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_usuario.php',
				data 	: 
				{
					accion : "validarElPasoVentanaPrincipal"
				},
				dataType: 'json',
				encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
					location.href = "../menu_principal.php";
				}else
				{
					alertify.error('Ocurrio un error.');
				}
			});

		}
	})


});


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

function number_format(amount, decimals) {

    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) 
        return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + '.' + '$2');

    return amount_parts.join(',');
}

function mostrartabladepositos(aux_url,aux_numcuenta,aux_tabla,aux_div,aux_montomin,aux_montomax,aux_sta)
{
	//Buscar y mostrar Hermanos
	$.ajax({
		type 	: 'POST',
		url		: '../controladores/controlador_estudiante.php',
		data 	: 
		{
			accion : "estudiantesXrepresentante",
			est_ced: sessvars.cedula
		},
		dataType: 'json',
		encode	: true
	})
	.done(function(datosher){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datosher.exito)
		{
			$('#tablaesther').html(datosher.tabla);
			//Si es deposito softservi o Colegio multiplico el $aux_montomin y $aux_montomax x la cantidad 
			//de hermanos ($aux_conther)
			if((aux_sta == 1) || (aux_sta == 2))
			{
				aux_montomin = aux_montomin * parseInt(datosher.contador);
				aux_montomax = aux_montomax * parseInt(datosher.contador);
			}
			$.ajax({
				type 	: 'POST',
				url		: aux_url,
				data 	: 
				{
					accion       : "buscar_depositoxcedula",
					dep_cuenta   : aux_numcuenta,
					aux_montomin : aux_montomin,
					aux_montomax : aux_montomax,
					aux_sta      : aux_sta,
					aux_conther : datosher.contador
				},
				dataType: 'json',
				encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				$(aux_tabla).html(datos.tabla);
				$(aux_div).show();
				if(datos.exito)
				{
					//alertify.success(datos.mensaje);
					//$("#tabladepsoft").html('');
				}else
				{
					//alertify.error(datos.mensaje);
				}
				if(aux_sta==1)
				{
					$('#faltantesoft').val(datos.faltante);
					if(datos.faltante==0)
						$('#btnsoft').attr("disabled", true);
				}
				if(aux_sta==2)
				{
					$('#faltantecol').val(datos.faltante);
					if(datos.faltante==0)
						$('#btncol').attr("disabled", true);
				}
				if(aux_sta==3)
				{
					$('#faltantepad').val(datos.faltante);
					if(datos.faltante==0)
						$('#btnpad').attr("disabled", true);			
				}

			});
		}else
		{
			alertify.error(datosher.mensaje);
		}
	});
}

function utilizarDeposito($aux_url,$aux_referencia,$aux_numcuenta,$aux_tabladep,$aux_vidtabla,aux_montomin,aux_montomax,aux_sta)
{
	$.ajax({
		type 	: 'POST',
		url		: $aux_url,
		data 	: 
		{
			accion         : "utilizarDeposito",
			dep_referencia : $aux_referencia,
			dep_cuenta     : $aux_numcuenta,
			dep_fecha      : sessvars.fil_fecha_dep,
			aux_sta        : aux_sta
		},
		dataType: 'json',
		encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			mostrartabladepositos($aux_url,$aux_numcuenta,$aux_tabladep,$aux_vidtabla,aux_montomin,aux_montomax,aux_sta);
			alertify.success(datos.mensaje);
		}else
		{
			alertify.error(datos.mensaje);
		}
	});
}

function buscarHermanos()
{
	//Buscar y mostrar Hermanos
	$.ajax({
		type 	: 'POST',
		url		: '../controladores/controlador_estudiante.php',
		data 	: 
		{
			accion : "estudiantesXrepresentante",
			est_ced: sessvars.cedula
		},
		dataType: 'json',
		encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			$('#tablaesther').html(datos.tabla);
			//alertify.success(datos.mensaje);
			//location.href = "../menu_principal.php";
		}else
		{
			alertify.error(datos.mensaje);
		}
	});
}
