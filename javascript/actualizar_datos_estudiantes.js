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
	$('#rep_telcelmad').numeric();
	$('#rep_telhabrepmad').numeric();
	$('#rep_teltrarepmad').numeric();
	$('#rep_telcelpad').numeric();
	$('#rep_telhabreppad').numeric();
	$('#rep_teltrareppad').numeric();
	$('#rep_telcel').numeric();
	$('#rep_telhabrep').numeric();
	$('#rep_teltrarep').numeric();
	$('#cli_telf').numeric();
	$("#est_cedcombo").hide();

	if(sessvars.tipousuario == 4)
	{
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_filtros.php',
				data 	: 
				{
					accion  : "filtros"
				},
				dataType: 'json',
				encode	: true
		})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				if(datos.datos.sta_inscviva == 0)
				{
					$('#btnguardar').hide();
					$('#btnimprimir').show();
					alertify.alert('Mensaje',"<h3>"+sessvars.mensajefininsc+"</h3>");
				}else
				{
					$('#btnguardar').show();
					$('#btnimprimir').hide();
				}
			}else
			{
				alertify.error(datos.mensaje);
			}
		});
	}

	$('#est_fecnac').datepicker({
		format: "dd/mm/yyyy",
		todayBtn: true,
		language: "es",
		multidateSeparator: "/",
		daysOfWeekHighlighted: "0",
		todayHighlight: true
	});

    $('#sandbox-container input').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        multidateSeparator: "/"
    });

    $('#est_fecnac').focusout(function()
    {
		validarFechaEnBlanco();
   });
	var ofer_academica = "";

	$('#ofertanuevo').hide();
	$('#vidtablaofer').hide();
	blanquearCajas();
	if(sessvars.tipousuario == 4)
	{
		$('#vidtablaofer').show();
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
		if((codigo == null || codigo.length == 0 || /^\s+$/.test(codigo))){
			desactivarCajas();
		}
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

	$('#rep_cedmad').focusout(function()
	{
		codigo = document.getElementById('rep_cedmad').value;
		if(!(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo))) //si no es blanco
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_representante.php',
					data 	: 
					{
						accion     : "buscar",
						rep_ced    : $('#rep_cedmad').val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				aux_ced=$('#rep_cedmad').val();
				blanquearDatosMadre();
				if(datos.exito)
				{
					llenarCajasMadre(datos);
					alertify.success(datos.mensaje);
				}else
				{
					//$("#cli_cedrif").val('');
					alertify.error(datos.mensaje);
				}
				$('#rep_cedmad').val(aux_ced);
			});
		}else
		{
			blanquearDatosMadre();
		}
	});
	$('#rep_cedpad').focusout(function()
	{
		codigo = document.getElementById('rep_cedpad').value;
		if(!(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo))) //si no es blanco
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_representante.php',
					data 	: 
					{
						accion     : "buscar",
						rep_ced    : $('#rep_cedpad').val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				aux_ced=$('#rep_cedpad').val();
				blanquearDatosPadre();
				if(datos.exito)
				{
					llenarCajasPadre(datos);
					alertify.success(datos.mensaje);
				}else
				{
					//$("#cli_cedrif").val('');
					alertify.error(datos.mensaje);
				}
				$('#rep_cedpad').val(aux_ced);
			});
		}else
		{
			blanquearDatosPadre();
		}
	});
	$('#rep_ced').focusout(function()
	{
		codigo = document.getElementById('rep_ced').value;
		if(!(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo))) //si no es blanco
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_representante.php',
					data 	: 
					{
						accion     : "buscar",
						rep_ced    : $('#rep_ced').val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				aux_ced=$('#rep_ced').val();
				blanquearDatosRepresentante();
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
				}else
				{
					alertify.error(datos.mensaje);
				}
				llenarCajasRep(datos,aux_ced);
				$('#rep_ced').val(aux_ced);
			});
		}else
		{
			blanquearDatosRepresentante();
		}
	});
	$("#btnguardar").click(function()
	{
		//Validar los campos obligatorios
		//alert(sessvars.fil_codlapso);
		validarFechaEnBlanco();
		if(verificar())
		{
			var confirm= alertify.confirm('Mensaje','Desea Guardar?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
			confirm.set({transition:'slide'});   	

			confirm.set('onok', function(){ //callbak al pulsar botón positivo
				if(sessvars.tipousuario == 4)
				{
					insertUpdateTodo(0);
				}else
				{
					$.ajax({
							type 	: 'POST',
							url		: '../controladores/controlador_inscripciones.php',
							data 	: 
							{
								accion     : "consultar",
								est_ced    : $("#est_ced").val(),
								periescol  : sessvars.fil_codlapso
							},
							dataType: 'json',
							encode	: true
						})
					.done(function(datos){
						//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
						if(datos.exito)
						{
							//alertify.success(datos.mensaje);
							if(datos.datos.insc_status == 1)
							{
								alertify.success('Inscripcion ya fue bajada. No se puede modificar');
							}else
							{
								//Si la seccion seleccionada es igual a la inscrita pues no hay cambio de año o grado
								if($("#masec_codsec").val() == datos.datos.cod_sec)
								{
									insertUpdateTodo(0);
								}else{
									var confirm= alertify.confirm('Mensaje','Va a cambiar Año o Sección de '+datos.datos.cod_sec+' a '+$("#masec_codsec").val()+', Desea modificar la inscripcion?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
									confirm.set({transition:'slide'});   	

									confirm.set('onok', function(){ //callbak al pulsar botón positivo
										insertUpdateTodo(1);
									});
									confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
										insertUpdateTodo(0);
									    //alertify.error('Has Cancelado la Solicitud');
									});
								}
							}
						}else
						{
							//$("#cli_cedrif").val('');
							insertUpdateTodo(0)
							//alertify.error(datos.mensaje);
						}
					});			
				}
			});
			confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
			    alertify.error('Has Cancelado la Solicitud');
			});
		}else
		{
			alertify.error('Falta incluir informacion.');
		}
	});

	$("#btnimprimir").click(function()
	{
		//Validar los campos obligatorios
		//alert(sessvars.fil_codlapso);
		//alertify.alert("<h3>El proceso de Inscripcion Finalizó.</h3>");
		alertify.error(sessvars.mensajefininsc+"<br> Solo Puede Imprimir la planilla.");
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_inscripciones.php',
				data 	: 
				{
					accion     : "consultar",
					est_ced    : $("#est_ced").val(),
					periescol  : sessvars.fil_codlapso
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				var confirm= alertify.confirm('Mensaje','Como desea ver Planilla de Inscripcion?',null,null).set('labels', {ok:'Descargar Archivo', cancel:'Ver en pantalla'}); 	
				confirm.set({transition:'slide'});   	

				confirm.set('onok', function(){ //callbak al pulsar botón positivo
					imprimirPlanilla("D");
				});
				confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
					imprimirPlanilla("I");
				});
			}else
			{
				alertify.error(datos.mensaje);
			}
		});
	});

	$('#cli_cedrif').focusout(function()
	{
		codigo = document.getElementById('cli_cedrif').value;
		if(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) //si es blanco
		{
				$("#cli_apenom").val('');
				$('#cli_direc').val('');
				$('#cli_telf').val('');
		}else
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_cliente.php',
					data 	: 
					{
						accion     : "consultar",
						cli_cedrif : $("#cli_cedrif").val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				$("#cli_apenom").val('');
				$('#cli_direc').val('');
				$('#cli_telf').val('');
				if(datos.exito)
				{
					//alertify.success(datos.mensaje);
					$("#cli_apenom").val(datos.datoscli.cli_apenom);
					$("#cli_direc").val(datos.datoscli.cli_direc);
					$("#cli_telf").val(datos.datoscli.cli_telf);
					alertify.success(datos.mensaje);
				}else
				{
					//$("#cli_cedrif").val('');
					alertify.error(datos.mensaje);
				}
			});
		}
	});
	$("#est_placoddea").focusin(function()
	{
		$("#est_nomplapro").attr("disabled", true);
	});
	$('#est_placoddea').focusout(function()
	{
		codigo = document.getElementById('est_placoddea').value;
		if(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) //si es blanco
		{
			$("#est_nomplapro").val('');
		}else
		{
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_planteles.php',
					data 	: 
					{
						accion          : "buscarplantel",
						plan_est_codigo : $("#est_placoddea").val()
					},
					dataType: 'json',
					encode	: true
				})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				$("#est_placod").val('');
				$("#est_nomplapro").val('');
				if(datos.exito)
				{
					//alertify.success(datos.mensaje);
					$("#est_placod").val(datos.datosplantel.fid);
					$("#est_nomplapro").val(datos.datosplantel.plan_nombre);
					alertify.success(datos.mensaje);
				}else
				{
					//$("#cli_cedrif").val('');
					$("#est_nomplapro").attr("disabled", false);
					alertify.error(datos.mensaje);
				}
			});
		}
	});

})

function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0,v6=0,v7=0,v8=0,v9=0,v10=0,v11=0,v12=0,v13=0,v14=0,v15=0,v16=0,v17=0,v18=0,v19=0,v20=0;
	var v21=0,v22=0,v23=0,v24=0,v25=0,v26=0,v27=0,v28=0,v29=0,v30=0,v31=0,v32=0,v33=0,v34=0,v35=0,v36=0,v37=0,v38=0,v39=0,v40=0;
	var v41=0,v42=0,v43=0,v44=0,v45=0,v46=0,v47=0,v48=0,v49=0,v50=0,v51=0,v52=0,v53=0,v54=0,v55=0,v56=0;

	v55=1;
	v54=1;
	v53=1;
	v52=1;
	v51=1;
	if(sessvars.tipousuario==2)
	{
		if($("#masec_codsec").is(':visible'))
		{
			v55=validacion('masec_codsec','texto','col-xs-12 col-sm-12');
		}
	}

	//Si el RIF es diferente a blanco 
	codigo = document.getElementById('cli_cedrif').value;
	if(!(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)))
	{
		v54=validacion('cli_telf','texto','col-xs-12 col-sm-2');
		v53=validacion('cli_direc','texto','col-xs-12 col-sm-4');
		v52=validacion('cli_apenom','texto','col-xs-12 col-sm-4');
	}

	v50=validacion('rep_vivecondes','texto','col-xs-12 col-sm-3');
	v49=validacion('est_medtras','texto','col-xs-12 col-sm-2');
	v48=validacion('est_vivecon','texto','col-xs-12 col-sm-2');
	v47=validacion('est_grafam','texto','col-xs-12 col-sm-2');
	v46=validacion('est_familia','texto','col-xs-12 col-sm-2');
	v45=validacion('est_telfemer','texto','col-xs-12 col-sm-4');
	v44=validacion('est_callemer','texto','col-xs-12 col-sm-4');
	v43=validacion('est_paren','texto','col-xs-12 col-sm-2');

	//Datos del Representante son obligatorios
	v42=validacion('rep_email','email','col-xs-12 col-sm-5');
	v41=1; //validacion('rep_profrep','texto','col-xs-12 col-sm-5');
	v40=1; //validacion('rep_teltrarep','numerico','col-xs-12 col-sm-3');
	v39=1; //validacion('rep_dirtrarep','texto','col-xs-12 col-sm-5');
	v38=1; //validacion('rep_lugtrarep','texto','col-xs-12 col-sm-4');
	v37=validacion('rep_telhabrep','numerico','col-xs-12 col-sm-3');
	v36=validacion('rep_telcel','numerico','col-xs-12 col-sm-3');
	v53=validacion('rep_dirhabrep','texto','col-xs-12 col-sm-6');
	v35=validacion('rep_nomrep','texto','col-xs-12 col-sm-8');
	v34=validacion('rep_nac','numerico','col-xs-12 col-sm-2');
	v33=validacion('rep_ced','numerico','col-xs-12 col-sm-2');

	//Si no tiene padre. Quito la validacion de campos obligatorios etc
	codigo = document.getElementById('rep_cedpad').value;
	if((codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) ) //si no tiene Padre y se logueo un estudiante
	{
		v23=1;
		v24=1;
		v25=1;
		v26=1;
		v27=1;
		v28=1;
		v29=1;
		v30=1;
		v31=1;
		v32=1;
	}else
	{
		v32=validacion('rep_emailpad','email','col-xs-12 col-sm-7');
		v31=1; //validacion('rep_profreppad','texto','col-xs-12 col-sm-5');
		v30=1; //validacion('rep_teltrareppad','numerico','col-xs-12 col-sm-3');
		v29=1; //validacion('rep_dirtrareppad','texto','col-xs-12 col-sm-5');
		v52=validacion('rep_dirhabpad','texto','col-xs-12 col-sm-6');
		v27=validacion('rep_telhabreppad','numerico','col-xs-12 col-sm-3');
		v26=validacion('rep_telcelpad','numerico','col-xs-12 col-sm-3');
		v28=1; //validacion('rep_lugtrareppad','numerico','col-xs-12 col-sm-4');
		v25=validacion('rep_nomreppad','texto','col-xs-12 col-sm-8');
		v24=validacion('rep_nacpad','texto','col-xs-12 col-sm-2');
		v23=validacion('rep_cedpad','numerico','col-xs-12 col-sm-2');
	}

	//Si no tiene madre. Quito la validacion de campos obligatorios etc
	codigo = document.getElementById('rep_cedmad').value;
	if(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) //si no tiene Madre y se logueo un estudiante
	{
		v14=1;
		v15=1;
		v16=1;
		v17=1;
		v18=1;
		v19=1;
		v20=1;
		v21=1;
		v22=1;
	}else
	{
		v22=validacion('rep_emailmad','email','col-xs-12 col-sm-7');
		v21=1; //validacion('rep_profrepmad','texto','col-xs-12 col-sm-5');
		v20=1; //validacion('rep_dirtrarepmad','texto','col-xs-12 col-sm-5');
		v51=validacion('rep_dirhabmad','texto','col-xs-12 col-sm-6');
		v19=1; //validacion('rep_lugtrarepmad','texto','col-xs-12 col-sm-4');
		v18=validacion('rep_telhabrepmad','numerico','col-xs-12 col-sm-3');
		v17=validacion('rep_telcelmad','numerico','col-xs-12 col-sm-3');
		v16=validacion('rep_nomrepmad','texto','col-xs-12 col-sm-8');
		v15=validacion('rep_nacmad','texto','col-xs-12 col-sm-2');
		v14=validacion('rep_cedmad','numerico','col-xs-12 col-sm-2');
	}

	v13=validacion('est_email','email','col-xs-12 col-sm-5');
	v12=validacion('est_tipoparto','texto','col-xs-12 col-sm-3');
	v11=validacion('est_sexo','texto','col-xs-12 col-sm-2');
	v10=validacion('est_edocivil','texto','col-xs-12 col-sm-2');
	v9=validacion('est_estnac','texto','col-xs-12 col-sm-3');
	v8=validacion('est_codpais','texto','col-xs-12 col-sm-3');
	v7=validacion('est_lugnac','texto','col-xs-12 col-sm-6');
	//Si est_placoddea es diferente a blanco 
	codigo = document.getElementById('est_placoddea').value;
	if(!(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)))
	{
		v56=validacion('est_nomplapro','texto','col-xs-12 col-sm-6');
	}
	v6=validacion('est_placoddea','texto','col-xs-12 col-sm-4'); //en comentario por el rollo de ser tan grande la tabla de plantel de procedencia
	v5=validacion('est_fecnac','numerico','col-xs-12 col-sm-2');
	v4=validacion('est_apellidos','texto','col-xs-12 col-sm-4');
	v3=validacion('est_nombres','texto','col-xs-12 col-sm-4');
	v2=validacion('est_nacionalidad','texto','col-xs-12 col-sm-1');
	v1=validacion('est_ced','numerico','col-xs-12 col-sm-3');




	if (v1===false || v2===false || v3===false || v4===false || v5===false || v6===false || v7===false || v8===false || v9===false || v10===false || v11===false || v12===false || v13===false || v14===false || v15===false || v16===false || v17===false || v18===false || v19===false || v20===false || v21===false || v22===false || v23===false || v24===false || v25===false || v26===false || v27===false || v28===false || v29===false || v30===false || v31===false || v32===false || v33===false || v34===false || v35===false || v36===false || v37===false || v38===false || v39===false || v40===false || v41===false || v42===false || v43===false || v44===false || v45===false || v46===false || v47===false || v48===false || v49===false || v50===false || v51===false || v52===false || v53===false || v54===false || v55===false || v56===false)
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
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback check'></span>");
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
	$('#est_nacionalidad').attr("disabled", true);
	$('#est_nombres').attr("disabled", true);
	$('#est_apellidos').attr("disabled", true);
	$('#est_fecnac').attr("disabled", true);
	$('#est_placoddea').attr("disabled", true);
	$('#est_lugnac').attr("disabled", true);
	$('#est_codpais').attr("disabled", true);
	$('#est_estnac').attr("disabled", true);
	$('#est_edocivil').attr("disabled", true);
	$('#est_sexo').attr("disabled", true);
	$('#est_tipoparto').attr("disabled", true);
	$('#rep_cedmad').attr("disabled", true);
	$('#rep_nacmad').attr("disabled", true);
	$('#rep_nomrepmad').attr("disabled", true);
	$('#rep_cedpad').attr("disabled", true);
	$('#rep_nacpad').attr("disabled", true);
	$('#rep_nomreppad').attr("disabled", true);
	$('#rep_ced').attr("disabled", true);
	$('#rep_nac').attr("disabled", true);
	$('#rep_nomrep').attr("disabled", true);
	$('#est_fecnac').attr("disabled", true);
	$('#rep_dirhabmad').attr("disabled", true);
	$('#rep_telcelmad').attr("disabled", true);
	$('#rep_telhabrepmad').attr("disabled", true);
	$('#rep_teltrarepmad').attr("disabled", true);
	$('#rep_lugtrarepmad').attr("disabled", true);
	$('#rep_dirtrarepmad').attr("disabled", true);
	$('#rep_profrepmad').attr("disabled", true);
	$('#rep_emailmad').attr("disabled", true);
	$('#rep_dirhabpad').attr("disabled", true);
	$('#rep_telcelpad').attr("disabled", true);
	$('#rep_telhabreppad').attr("disabled", true);
	$('#rep_teltrareppad').attr("disabled", true);
	$('#rep_lugtrareppad').attr("disabled", true);
	$('#rep_dirtrareppad').attr("disabled", true);
	$('#rep_profreppad').attr("disabled", true);
	$('#rep_emailpad').attr("disabled", true);
	$('#rep_dirhabrep').attr("disabled", true);
	$('#rep_telcel').attr("disabled", true);
	$('#rep_telhabrep').attr("disabled", true);
	$('#rep_teltrarep').attr("disabled", true);
	$('#rep_lugtrarep').attr("disabled", true);
	$('#rep_dirtrarep').attr("disabled", true);
	$('#rep_profrep').attr("disabled", true);
	$('#rep_email').attr("disabled", true);
	$('#est_paren').attr("disabled", true);
	$('#est_email').attr("disabled", true);
	$('#est_callemer').attr("disabled", true);
	$('#est_telfemer').attr("disabled", true);
	$('#est_familia').attr("disabled", true);
	$('#est_grafam').attr("disabled", true);
	$('#est_vivecon').attr("disabled", true);
	$('#est_medtras').attr("disabled", true);
	$('#rep_vivecondes').attr("disabled", true);
	$('#cli_cedrif').attr("disabled", true);
	$('#cli_apenom').attr("disabled", true);
	$('#cli_direc').attr("disabled", true);
	$('#cli_telf').attr("disabled", true);
	$('#masec_codsec').attr("disabled", true);

/*
	$('#est_ced').attr("disabled", true);
	$('#est_nacionalidad').attr("disabled", true);
	$('#est_nombres').attr("disabled", true);
	$('#est_apellidos').attr("disabled", true);
	$('#est_fecnac').attr("disabled", true);
	$('#est_placoddea').attr("disabled", true);
	$('#est_lugnac').attr("disabled", true);
	$('#est_codpais').attr("disabled", true);
	$('#est_estnac').attr("disabled", true);
	$('#est_edocivil').attr("disabled", true);
	$('#est_sexo').attr("disabled", true);
	$('#est_tipoparto').attr("disabled", true);
	$('#rep_cedmad').attr("disabled", true);
	$('#rep_nacmad').attr("disabled", true);
	$('#rep_nomrepmad').attr("disabled", true);
	$('#rep_cedpad').attr("disabled", true);
	$('#rep_nacpad').attr("disabled", true);
	$('#rep_nomreppad').attr("disabled", true);
	$('#rep_ced').attr("disabled", true);
	$('#rep_nac').attr("disabled", true);
	$('#rep_nomrep').attr("disabled", true);
	$('#masec_codsec').attr("disabled", true);
	//Si tipo de usuario es 2 Administrativo
	if(sessvars.tipousuario == 2)
	{
		$('#est_ced').attr("disabled", false);
		$('#est_fecnac').attr("disabled", false);
		$('#rep_dirhabmad').attr("disabled", false);
		$('#rep_telcelmad').attr("disabled", false);
		$('#rep_telhabrepmad').attr("disabled", false);
		$('#rep_teltrarepmad').attr("disabled", false);
		$('#rep_lugtrarepmad').attr("disabled", false);
		$('#rep_dirtrarepmad').attr("disabled", false);
		$('#rep_profrepmad').attr("disabled", false);
		$('#rep_emailmad').attr("disabled", false);
		$('#rep_dirhabpad').attr("disabled", false);
		$('#rep_telcelpad').attr("disabled", false);
		$('#rep_telhabreppad').attr("disabled", false);
		$('#rep_teltrareppad').attr("disabled", false);
		$('#rep_lugtrareppad').attr("disabled", false);
		$('#rep_dirtrareppad').attr("disabled", false);
		$('#rep_profreppad').attr("disabled", false);
		$('#rep_emailpad').attr("disabled", false);
		$('#rep_dirhabrep').attr("disabled", false);
		$('#rep_telcel').attr("disabled", false);
		$('#rep_telhabrep').attr("disabled", false);
		$('#rep_teltrarep').attr("disabled", false);
		$('#rep_lugtrarep').attr("disabled", false);
		$('#rep_dirtrarep').attr("disabled", false);
		$('#rep_profrep').attr("disabled", false);
		$('#rep_email').attr("disabled", false);
		$('#est_paren').attr("disabled", false);
		$('#est_email').attr("disabled", false);
		$('#est_callemer').attr("disabled", false);
		$('#est_telfemer').attr("disabled", false);
		$('#est_familia').attr("disabled", false);
		$('#est_grafam').attr("disabled", false);
		$('#est_vivecon').attr("disabled", false);
		$('#est_medtras').attr("disabled", false);
		$('#rep_vivecondes').attr("disabled", false);
		$('#cli_cedrif').attr("disabled", false);
		$('#cli_apenom').attr("disabled", false);
		$('#cli_direc').attr("disabled", false);
		$('#cli_telf').attr("disabled", false);
	}

	//Si tipo de usuario es 4 Estudiante
	if(sessvars.tipousuario == 4)
	{
		//Si no tiene madre. Se desactivan todos los campos de la madre
		codigo = document.getElementById('rep_cedmad').value;
		if(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) //si no tiene representante y se logueo un estudiante
		{
			$('#rep_telcelmad').attr("disabled", true);
			$('#rep_telhabrepmad').attr("disabled", true);
			$('#rep_teltrarepmad').attr("disabled", true);
			$('#rep_lugtrarepmad').attr("disabled", true);
			$('#rep_dirtrarepmad').attr("disabled", true);
			$('#rep_profrepmad').attr("disabled", true);
			$('#rep_emailmad').attr("disabled", true);			
		}
		//Si no tiene Padre. Se desactivan todos los campos de la Padre
		codigo = document.getElementById('rep_cedpad').value;
		if((sessvars.tipousuario == 4) && (codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) ) //si no tiene representante y se logueo un estudiante
		{
			$('#rep_telcelpad').attr("disabled", true);
			$('#rep_telhabreppad').attr("disabled", true);
			$('#rep_teltrareppad').attr("disabled", true);
			$('#rep_lugtrareppad').attr("disabled", true);
			$('#rep_dirtrareppad').attr("disabled", true);
			$('#rep_profreppad').attr("disabled", true);
			$('#rep_emailpad').attr("disabled", true);
		}
		//Si no tiene Representante. Se desactivan todos los campos de la Representante
		codigo = document.getElementById('rep_ced').value;
		if((sessvars.tipousuario == 4) && (codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) ) //si no tiene representante y se logueo un estudiante
		{
			$('#rep_telcel').attr("disabled", true);
			$('#rep_telhabrep').attr("disabled", true);
			$('#rep_teltrarep').attr("disabled", true);
			$('#rep_lugtrarep').attr("disabled", true);
			$('#rep_dirtrarep').attr("disabled", true);
			$('#rep_profrep').attr("disabled", true);
			$('#rep_email').attr("disabled", true);
			$('#est_paren').attr("disabled", true);
		}
	}
	*/
}

function activarCajas()
{
	$('#est_ced').attr("disabled", false);
	$('#est_nacionalidad').attr("disabled", false);
	$('#est_nombres').attr("disabled", false);
	$('#est_apellidos').attr("disabled", false);
	$('#est_fecnac').attr("disabled", false);
	$('#est_placoddea').attr("disabled", false);
	$('#est_lugnac').attr("disabled", false);
	$('#est_codpais').attr("disabled", false);
	$('#est_estnac').attr("disabled", false);
	$('#est_edocivil').attr("disabled", false);
	$('#est_sexo').attr("disabled", false);
	$('#est_tipoparto').attr("disabled", false);
	$('#est_email').attr("disabled", false);
	$('#rep_cedmad').attr("disabled", false);
	$('#rep_nacmad').attr("disabled", false);
	$('#rep_nomrepmad').attr("disabled", false);
	$('#rep_dirhabmad').attr("disabled", false);
	$('#rep_telcelmad').attr("disabled", false);
	$('#rep_telhabrepmad').attr("disabled", false);
	$('#rep_teltrarepmad').attr("disabled", false);
	$('#rep_lugtrarepmad').attr("disabled", false);
	$('#rep_dirtrarepmad').attr("disabled", false);
	$('#rep_profrepmad').attr("disabled", false);
	$('#rep_emailmad').attr("disabled", false);
	$('#rep_cedpad').attr("disabled", false);
	$('#rep_nacpad').attr("disabled", false);
	$('#rep_nomreppad').attr("disabled", false);
	$('#rep_dirhabpad').attr("disabled", false);
	$('#rep_telcelpad').attr("disabled", false);
	$('#rep_telhabreppad').attr("disabled", false);
	$('#rep_teltrareppad').attr("disabled", false);
	$('#rep_lugtrareppad').attr("disabled", false);
	$('#rep_dirtrareppad').attr("disabled", false);
	$('#rep_profreppad').attr("disabled", false);
	$('#rep_emailpad').attr("disabled", false);
	$('#rep_ced').attr("disabled", false);
	$('#rep_nac').attr("disabled", false);
	$('#rep_nomrep').attr("disabled", false);
	$('#rep_dirhabrep').attr("disabled", false);
	$('#rep_telcel').attr("disabled", false);
	$('#rep_telhabrep').attr("disabled", false);
	$('#rep_teltrarep').attr("disabled", false);
	$('#rep_lugtrarep').attr("disabled", false);
	$('#rep_dirtrarep').attr("disabled", false);
	$('#rep_profrep').attr("disabled", false);
	$('#rep_email').attr("disabled", false);
	$('#est_paren').attr("disabled", false);
	$('#est_callemer').attr("disabled", false);
	$('#est_telfemer').attr("disabled", false);
	$('#est_familia').attr("disabled", false);
	$('#est_grafam').attr("disabled", false);
	$('#est_vivecon').attr("disabled", false);
	$('#est_medtras').attr("disabled", false);
	$('#rep_vivecondes').attr("disabled", false);
	$('#cli_cedrif').attr("disabled", false);
	$('#cli_apenom').attr("disabled", false);
	$('#cli_direc').attr("disabled", false);
	$('#cli_telf').attr("disabled", false);
	$('#masec_codsec').attr("disabled", false);

	//Si tipo de usuario es 4 Estudiante
	if(sessvars.tipousuario == 4)
	{
		$('#est_ced').attr("disabled", true);
		$('#est_nacionalidad').attr("disabled", true);
		$('#est_nombres').attr("disabled", true);
		$('#est_apellidos').attr("disabled", true);
		//$('#est_fecnac').attr("disabled", true);
		//$('#est_fecnac').attr("disabled", true);
		$('#est_placoddea').attr("disabled", true);
		$('#est_lugnac').attr("disabled", true);
		//$('#est_codpais').attr("disabled", true);
		//$('#est_estnac').attr("disabled", true);
		$('#est_edocivil').attr("disabled", true);
		$('#est_sexo').attr("disabled", true);
		$('#est_tipoparto').attr("disabled", true);
		$('#rep_cedmad').attr("disabled", true);
		$('#rep_nacmad').attr("disabled", true);
		$('#rep_cedpad').attr("disabled", true);
		$('#rep_nacpad').attr("disabled", true);
		$('#rep_ced').attr("disabled", true);
		$('#rep_nac').attr("disabled", true);

		//Si no tiene madre. Se desactivan todos los campos de la madre
		codigo = document.getElementById('rep_cedmad').value;
		if(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) //si no tiene representante y se logueo un estudiante
		{
			$('#rep_cedmad').attr("disabled", true);
			$('#rep_nacmad').attr("disabled", true);
			$('#rep_nomrepmad').attr("disabled", true);
			$('#rep_dirhabmad').attr("disabled", true);
			$('#rep_telcelmad').attr("disabled", true);
			$('#rep_telhabrepmad').attr("disabled", true);
			$('#rep_teltrarepmad').attr("disabled", true);
			$('#rep_lugtrarepmad').attr("disabled", true);
			$('#rep_dirtrarepmad').attr("disabled", true);
			$('#rep_profrepmad').attr("disabled", true);
			$('#rep_emailmad').attr("disabled", true);
	
		}
		//Si no tiene Padre. Se desactivan todos los campos de la Padre
		codigo = document.getElementById('rep_cedpad').value;
		if((sessvars.tipousuario == 4) && (codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) ) //si no tiene representante y se logueo un estudiante
		{
			$('#rep_cedpad').attr("disabled", true);
			$('#rep_nacpad').attr("disabled", true);
			$('#rep_nomreppad').attr("disabled", true);
			$('#rep_dirhabpad').attr("disabled", true);
			$('#rep_telcelpad').attr("disabled", true);
			$('#rep_telhabreppad').attr("disabled", true);
			$('#rep_teltrareppad').attr("disabled", true);
			$('#rep_lugtrareppad').attr("disabled", true);
			$('#rep_dirtrareppad').attr("disabled", true);
			$('#rep_profreppad').attr("disabled", true);
			$('#rep_emailpad').attr("disabled", true);
		}
		//Si no tiene Representante. Se desactivan todos los campos de la Representante
		codigo = document.getElementById('rep_ced').value;
		if((sessvars.tipousuario == 4) && (codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) ) //si no tiene representante y se logueo un estudiante
		{
			$('#rep_ced').attr("disabled", true);
			$('#rep_nac').attr("disabled", true);
			$('#rep_nomrep').attr("disabled", true);
			$('#rep_dirhabrep').attr("disabled", true);
			$('#rep_telcel').attr("disabled", true);
			$('#rep_telhabrep').attr("disabled", true);
			$('#rep_teltrarep').attr("disabled", true);
			$('#rep_lugtrarep').attr("disabled", true);
			$('#rep_dirtrarep').attr("disabled", true);
			$('#rep_profrep').attr("disabled", true);
			$('#rep_email').attr("disabled", true);
			$('#est_paren').attr("disabled", true);
		}
	}
}


function blanquearDatosMadre()
{
	$('#rep_cedmad').val('');
	$('#rep_nacmad').val('');
	$('#rep_nomrepmad').val('');
	$('#rep_dirhabmad').val('');
	$('#rep_telcelmad').val('');
	$('#rep_telhabrepmad').val('');
	$('#rep_teltrarepmad').val('');
	$('#rep_lugtrarepmad').val('');
	$('#rep_dirtrarepmad').val('');
	$('#rep_profrepmad').val('');
	$('#rep_emailmad').val('');
}

function blanquearDatosPadre()
{
	$('#rep_cedpad').val('');
	$('#rep_nacpad').val('');
	$('#rep_nomreppad').val('');
	$('#rep_dirhabpad').val('');
	$('#rep_telcelpad').val('');
	$('#rep_telhabreppad').val('');
	$('#rep_teltrareppad').val('');
	$('#rep_lugtrareppad').val('');
	$('#rep_dirtrareppad').val('');
	$('#rep_profreppad').val('');
	$('#rep_emailpad').val('');
}

function blanquearDatosRepresentante()
{
	$('#rep_ced').val('');
	$('#rep_nac').val('');
	$('#rep_nomrep').val('');
	$('#rep_dirhabrep').val('');
	$('#rep_telcel').val('');
	$('#rep_telhabrep').val('');
	$('#rep_teltrarep').val('');
	$('#rep_lugtrarep').val('');
	$('#rep_dirtrarep').val('');
	$('#rep_profrep').val('');
	$('#rep_email').val('');
}

function blanquearCajas()
{
	//$('#est_ced').val('');
	$('#est_nacionalidad').val('');
	$('#est_nombres').val('');
	$('#est_apellidos').val('');
	$('#est_fecnac').val('');
	$('#est_placod').val('');
	$('#est_placoddea').val('');
	$('#est_nomplapro').val('');
	$('#est_lugnac').val('');
	$('#est_codpais').val('');
	$('#est_estnac').val('');
	$('#est_edocivil').val('');
	$('#est_sexo').val('');
	$('#est_tipoparto').val('');
	$('#est_email').val('');
	$('#est_paren').val('');
	$('#est_callemer').val('');
	$('#est_telfemer').val('');
	$('#est_grafam').val('');
	$('#est_familia').val('');
	$('#est_vivecon').val('');
	$('#est_medtras').val('');
	$('#rep_vivecondes').val('');
	$('#cli_cedrif').val('');
	$('#cli_apenom').val('');
	$('#cli_direc').val('');
	$('#cli_telf').val('');

	$('#rep_cedmad').val('');
	$('#rep_nacmad').val('');
	$('#rep_nomrepmad').val('');
	$('#rep_dirhabmad').val('');
	$('#rep_telcelmad').val('');
	$('#rep_telhabrepmad').val('');
	$('#rep_teltrarepmad').val('');
	$('#rep_lugtrarepmad').val('');
	$('#rep_dirtrarepmad').val('');
	$('#rep_profrepmad').val('');
	$('#rep_emailmad').val('');

	$('#rep_cedpad').val('');
	$('#rep_nacpad').val('');
	$('#rep_nomreppad').val('');
	$('#rep_dirhabpad').val('');
	$('#rep_telcelpad').val('');
	$('#rep_telhabreppad').val('');
	$('#rep_teltrareppad').val('');
	$('#rep_lugtrareppad').val('');
	$('#rep_dirtrareppad').val('');
	$('#rep_profreppad').val('');
	$('#rep_emailpad').val('');

	$('#rep_ced').val('');
	$('#rep_nac').val('');
	$('#rep_nomrep').val('');
	$('#rep_dirhabrep').val('');
	$('#rep_telcel').val('');
	$('#rep_telhabrep').val('');
	$('#rep_teltrarep').val('');
	$('#rep_lugtrarep').val('');
	$('#rep_dirtrarep').val('');
	$('#rep_profrep').val('');
	$('#rep_email').val('');
	$('#tablaoferta').html('');
	$("#divmaterias").html('MATERIAS');

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
			alertify.success(datosest.mensaje);
			ofer_academica = datosest.oferta_academica;
			$('#est_exp').val(datosest.datosest.est_exp);
			$('#est_ced').val(sessvars.cedula);
			$('#est_nacionalidad').val(datosest.datosest.est_nacionalidad);
			$('#est_nombres').val(datosest.datosest.est_nombres);
			$('#est_apellidos').val(datosest.datosest.est_apellidos);
			$('#est_fecnac').val(amdTodma(datosest.datosest.est_fecnac)); //amdTodma funcion creadfa por mi para voltear la fecha a DD/MM/AAAA. La funcion esta en principal.js
			sessvars.est_fecnac = $('#est_fecnac').val();
			$('#est_lugnac').val(datosest.datosest.est_lugnac);
			$('#est_codpais').val(datosest.datosest.est_codpais);
			$('#est_estnac').val(datosest.datosest.est_estnac);
			$('#est_edocivil').val(datosest.datosest.est_edocivil);
			$('#est_sexo').val(datosest.datosest.est_sexo);
			$('#est_tipoparto').val(datosest.datosest.est_tipoparto);
			codigo = document.getElementById('est_tipoparto').value;
			if((codigo == null || codigo.length == 0 || /^\s+$/.test(codigo))) //si no es blanco
			{
				$('#est_tipoparto').val(1);
			}
			$('#est_email').val(datosest.datosest.est_email);
			$('#est_paren').val(datosest.datosest.est_paren);
			$('#est_callemer').val(datosest.datosest.est_callemer);
			$('#est_telfemer').val(datosest.datosest.est_telfemer);
			$('#est_grafam').val(datosest.datosest.est_grafam);
			$('#est_familia').val(datosest.datosest.est_familia);
			$('#est_vivecon').val(datosest.datosest.est_vivecon);
			$('#est_medtras').val(datosest.datosest.est_medtras);
			$('#rep_vivecondes').val(datosest.datosest.rep_vivecondes);
			if(datosest.exitocliente)
			{
				$('#cli_cedrif').val(datosest.datoscliente.cli_cedrif);
				$('#cli_apenom').val(datosest.datoscliente.cli_apenom);
				$('#cli_direc').val(datosest.datoscliente.cli_direc);
				$('#cli_telf').val(datosest.datoscliente.cli_telf);
			}
			if(datosest.exitoplantel)
			{
				$('#est_placod').val(datosest.datosest.est_placod);
				$('#est_placoddea').val(datosest.datosest.est_placoddea);
				$('#est_nomplapro').val(datosest.datosplantel.plan_nombre);
			}else
			{
				$('#est_placod').val('');
				$('#est_placoddea').val('');
			}
			if(datosest.exitorepest)
			{
				blanquearDatosMadre();
				blanquearDatosPadre();
				blanquearDatosRepresentante();
				if(datosest.exitomadre)
				{
					$('#rep_cedmad').val(datosest.datosmadre.rep_ced);
					$('#rep_nacmad').val(datosest.datosmadre.rep_nac);
					$('#rep_nomrepmad').val(datosest.datosmadre.rep_nomrep);
					$('#rep_dirhabmad').val(datosest.datosmadre.rep_dirhabrep);
					$('#rep_telcelmad').val(datosest.datosmadre.rep_telcel);
					$('#rep_telhabrepmad').val(datosest.datosmadre.rep_telhabrep);
					$('#rep_teltrarepmad').val(datosest.datosmadre.rep_teltrarep);
					$('#rep_lugtrarepmad').val(datosest.datosmadre.rep_lugtrarep);
					$('#rep_dirtrarepmad').val(datosest.datosmadre.rep_dirtrarep);
					$('#rep_profrepmad').val(datosest.datosmadre.rep_profrep);
					$('#rep_emailmad').val(datosest.datosmadre.rep_email);
				}
				if(datosest.exitopadre)
				{
					$('#rep_cedpad').val(datosest.datospadre.rep_ced);
					$('#rep_nacpad').val(datosest.datospadre.rep_nac);
					$('#rep_nomreppad').val(datosest.datospadre.rep_nomrep);
					$('#rep_dirhabpad').val(datosest.datospadre.rep_dirhabrep);
					$('#rep_telcelpad').val(datosest.datospadre.rep_telcel);
					$('#rep_telhabreppad').val(datosest.datospadre.rep_telhabrep);
					$('#rep_teltrareppad').val(datosest.datospadre.rep_teltrarep);
					$('#rep_lugtrareppad').val(datosest.datospadre.rep_lugtrarep);
					$('#rep_dirtrareppad').val(datosest.datospadre.rep_dirtrarep);
					$('#rep_profreppad').val(datosest.datospadre.rep_profrep);
					$('#rep_emailpad').val(datosest.datospadre.rep_email);
				}
				if(datosest.exitorep)
				{
					$('#rep_ced').val(datosest.datosrep.rep_ced);
					$('#rep_nac').val(datosest.datosrep.rep_nac);
					$('#rep_nomrep').val(datosest.datosrep.rep_nomrep);
					$('#rep_dirhabrep').val(datosest.datosrep.rep_dirhabrep);
					$('#rep_telcel').val(datosest.datosrep.rep_telcel);
					$('#rep_telhabrep').val(datosest.datosrep.rep_telhabrep);
					$('#rep_teltrarep').val(datosest.datosrep.rep_teltrarep);
					$('#rep_lugtrarep').val(datosest.datosrep.rep_lugtrarep);
					$('#rep_dirtrarep').val(datosest.datosrep.rep_dirtrarep);
					$('#rep_profrep').val(datosest.datosrep.rep_profrep);
					$('#rep_email').val(datosest.datosrep.rep_email);
				}
			}
			if(datosest.exitocli)
			{
				$("#cli_cedrif").val(datosest.datoscli.cli_cedrif);
				$("#cli_apenom").val(datosest.datoscli.cli_apenom);
				$("#cli_direc").val(datosest.datoscli.cli_direc);
				$("#cli_telf").val(datosest.datoscli.cli_telf);
			}
			if(datosest.exitoinscrip)
			{
				//$('#masec_codsec').val("017A");
				//alert($('#masec_codsec').val());
				$('#masec_codsec').val(datosest.datosinsc.ofac_seccion)
				if(sessvars.tipousuario == 2)
				{
					//alert(datosest.datosinsc.ofac_seccion);
					$('#ofertanuevo').show();
					$('#masec_codsec').val(datosest.datosinsc.ofac_seccion)
					$('#vidtablaofer').show();
					//$('#vidtablaofer').hide();					
				}
				if(sessvars.tipousuario == 4)
				{
					$('#ofertanuevo').hide();
					$('#vidtablaofer').show();					
				}

			}else
			{
				if(datosest.exitooferta)
				{
					$('#ofertanuevo').hide();
					$('#vidtablaofer').show();
				}else
				{
					$('#ofertanuevo').show();
					$('#vidtablaofer').hide();
				}
				//Si aun no esta Boro las cajas de direccion,Telefono, Correo etc...
				//borrarCajasDirTelf();
			}
			//si no se ha inscrito por lo menos un hermano o el mismo borro las cajas
			if(datosest.inscritoherm == false)
			{
				borrarCajasDirTelf();
			}
			//alert(datosest.tablaoferta);
			
			$('#tablaoferta').html(datosest.tablaoferta);
			$("#divmaterias").html(datosest.titulomaterias);
			$("#btnguardar").attr("disabled", false);
			//if(datosest.statusbajado)
			if(datosest.insc_status == '1')
			{
				//$("#btnguardar").attr("disabled", true);
				$('#tablaoferta').show();
				desactivarCajas();
				$('#est_ced').attr("disabled",true);
				alertify.success('Datos no pueden ser Modificados. Estudiante ya esta Inscrito.');
			}else
			{
				activarCajas();
			}
		}else
		{
			blanquearCajas();
			alertify.error(datosest.mensaje);
			activarCajas();
			if(datosest.inhabilitadoinsc){
				desactivarCajas();
				$("#btnguardar").attr("disabled", true);
			}
			$('#ofertanuevo').show();
			$('#vidtablaofer').hide();
			$('#est_nacionalidad').focus();
		}
	});
}
//$('#ofertanuevo').show();
function llenarCajasMadre(vector)
{
	blanquearDatosMadre();
	$('#rep_cedmad').val(vector.datosrepre.rep_ced);
	$('#rep_nacmad').val(vector.datosrepre.rep_nac);
	$('#rep_nomrepmad').val(vector.datosrepre.rep_nomrep);
	$('#rep_dirhabmad').val(vector.datosrepre.rep_dirhabrep);
	$('#rep_telcelmad').val(vector.datosrepre.rep_telcel);
	$('#rep_telhabrepmad').val(vector.datosrepre.rep_telhabrep);
	$('#rep_teltrarepmad').val(vector.datosrepre.rep_teltrarep);
	$('#rep_lugtrarepmad').val(vector.datosrepre.rep_lugtrarep);
	$('#rep_dirtrarepmad').val(vector.datosrepre.rep_dirtrarep);
	$('#rep_profrepmad').val(vector.datosrepre.rep_profrep);
	$('#rep_emailmad').val(vector.datosrepre.rep_email);
}

function llenarCajasPadre(vector)
{
	blanquearDatosPadre();
	$('#rep_cedpad').val(vector.datosrepre.rep_ced);
	$('#rep_nacpad').val(vector.datosrepre.rep_nac);
	$('#rep_nomreppad').val(vector.datosrepre.rep_nomrep);
	$('#rep_dirhabpad').val(vector.datosrepre.rep_dirhabrep);
	$('#rep_telcelpad').val(vector.datosrepre.rep_telcel);
	$('#rep_telhabreppad').val(vector.datosrepre.rep_telhabrep);
	$('#rep_teltrareppad').val(vector.datosrepre.rep_teltrarep);
	$('#rep_lugtrareppad').val(vector.datosrepre.rep_lugtrarep);
	$('#rep_dirtrareppad').val(vector.datosrepre.rep_dirtrarep);
	$('#rep_profreppad').val(vector.datosrepre.rep_profrep);
	$('#rep_emailpad').val(vector.datosrepre.rep_email);
}

function llenarCajasRep(vector,aux_ced)
{
	blanquearDatosRepresentante();
	if(vector.exito)
	{
		$('#rep_ced').val(vector.datosrepre.rep_ced);
		$('#rep_nac').val(vector.datosrepre.rep_nac);
		$('#rep_nomrep').val(vector.datosrepre.rep_nomrep);
		$('#rep_dirhabrep').val(vector.datosrepre.rep_dirhabrep);
		$('#rep_telcel').val(vector.datosrepre.rep_telcel);
		$('#rep_telhabrep').val(vector.datosrepre.rep_telhabrep);
		$('#rep_teltrarep').val(vector.datosrepre.rep_teltrarep);
		$('#rep_lugtrarep').val(vector.datosrepre.rep_lugtrarep);
		$('#rep_dirtrarep').val(vector.datosrepre.rep_dirtrarep);
		$('#rep_profrep').val(vector.datosrepre.rep_profrep);
		$('#rep_email').val(vector.datosrepre.rep_email);
	}else
	{
		if(aux_ced == $('#rep_cedmad').val())
		{
			$('#rep_ced').val($('#rep_cedmad').val());
			$('#rep_nac').val($('#rep_nacmad').val());
			$('#rep_nomrep').val($('#rep_nomrepmad').val());
			$('#rep_dirhabrep').val($('#rep_dirhabmad').val());
			$('#rep_telcel').val($('#rep_telcelmad').val());
			$('#rep_telhabrep').val($('#rep_telhabrepmad').val());
			$('#rep_teltrarep').val($('#rep_teltrarepmad').val());
			$('#rep_lugtrarep').val($('#rep_lugtrarepmad').val());
			$('#rep_dirtrarep').val($('#rep_dirtrarepmad').val());
			$('#rep_profrep').val($('#rep_profrepmad').val());
			$('#rep_email').val($('#rep_emailmad').val());
			alertify.success('Se copiaron los datos de la Madre.');
		}
		if(aux_ced == $('#rep_cedpad').val())
		{
			$('#rep_ced').val($('#rep_cedpad').val());
			$('#rep_nac').val($('#rep_nacpad').val());
			$('#rep_nomrep').val($('#rep_nomreppad').val());
			$('#rep_dirhabrep').val($('#rep_dirhabpad').val());
			$('#rep_telcel').val($('#rep_telcelpad').val());
			$('#rep_telhabrep').val($('#rep_telhabreppad').val());
			$('#rep_teltrarep').val($('#rep_teltrareppad').val());
			$('#rep_lugtrarep').val($('#rep_lugtrareppad').val());
			$('#rep_dirtrarep').val($('#rep_dirtrareppad').val());
			$('#rep_profrep').val($('#rep_profreppad').val());
			$('#rep_email').val($('#rep_emailpad').val());
			alertify.success('Se copiaron los datos del Padre.');
		}
	}
}

function aMays(e, elemento) 
{
	tecla=(document.all) ? e.keyCode : e.which; 
	elemento.value = elemento.value.toUpperCase();
}

function validarFechaEnBlanco()
{
	codigo = document.getElementById('est_fecnac').value;
	if(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) //si es blanco
	{
		$('#est_fecnac').val(sessvars.est_fecnac);
	}
}

function onchangeComboCed()
{
	sessvars.cedula = $("#est_cedcombo").val();
	$("#est_ced").val(sessvars.cedula);
	buscarEstudiante()
}

function borrarCajasDirTelf()
{
	//Correo Estudiante
	$('#est_email').val('');

	//Blanquear Datos a llenar Madre
	$('#rep_dirhabmad').val('');
	$('#rep_telcelmad').val('');
	$('#rep_telhabrepmad').val('');
	$('#rep_teltrarepmad').val('');
	$('#rep_lugtrarepmad').val('');
	$('#rep_dirtrarepmad').val('');
	$('#rep_profrepmad').val('');
	$('#rep_emailmad').val('');

	//Blanquear Datos a llenar padre
	$('#rep_dirhabpad').val('');
	$('#rep_telcelpad').val('');
	$('#rep_telhabreppad').val('');
	$('#rep_teltrareppad').val('');
	$('#rep_lugtrareppad').val('');
	$('#rep_dirtrareppad').val('');
	$('#rep_profreppad').val('');
	$('#rep_emailpad').val('');

	//Blanquear Datos a llenar Representante
	$('#rep_dirhabrep').val('');
	$('#rep_telcel').val('');
	$('#rep_telhabrep').val('');
	$('#rep_teltrarep').val('');
	$('#rep_lugtrarep').val('');
	$('#rep_dirtrarep').val('');
	$('#rep_profrep').val('');
	$('#rep_email').val('');

}

function imprimirPlanilla(aux_resplan)
{
	window.open("../clases/planilla_inscripcion.class.php?tetwtwtqtwtetthsssaaqqwrraaayqyyqywyyeyeyeyeyewsdfsdfsdfdsfsdtwtwtwtqtqtqtqqttdfgdsfgsdgsdsdfgsdgsdgsdgasfteytrhtjsdfhgsdgtqtqtwtwtwerwtertwgdfgdfgdfgertertadadadasdhtwert=6277625362623562&ced_estudiante="+sessvars.cedula+"&aux_lapso="+sessvars.fil_codlapso+"&aux_resplan="+aux_resplan+" ","nuevo"); 
}

function insertUpdateTodo(aux_elimatinsc)
{
	//alert(aux_elimatinsc);
	//*******************************************************************
	//Creo un vector asosiativo con todos los input de la pantalla.
	//Este vector lo envio al controlador, para enviar un solo parámetro
	var datosest1 = new Array(); //este arreglo lo cree pero no lo estoy usando
	var datosestc = '{';
	$("input").each(function() {
		datosest1[$(this).attr("name")] = $(this).val();
		datosestc = datosestc + '"' + $(this).attr("name") + '":"' + $(this).val() + '",';
	});
	$("select").each(function() {
		datosest1[$(this).attr("name")] = $(this).val();
		datosestc = datosestc + '"' + $(this).attr("name") + '":"' + $(this).val() + '",';
	});
	datosestc = datosestc + '"fin":"fin"}';
	//alert(datosestc);
	//*******************************************************************
	//por defecto pongo asigno cero a la variable, pero si el usuario es tipo 4 
	//es decir estudiante se supone que ya paso por la pantalla de depositos. 
	//es decir entro al sistema y ya pago.
	aux_bajarinsc = 0;
	if(sessvars.tipousuario == 4)
	{
		aux_bajarinsc = 1;
	}
	$.ajax({
		type 	: 'POST',
		url		: '../controladores/controlador_estudiante.php',
		data 	: 
		{
			accion         : "insertUpdateTodo",
			datosest       : datosestc,
			lapsoinscp     : sessvars.fil_codlapso,
			tipousuario    : sessvars.tipousuario,
			cod_secc       : $("#masec_codsec").val(),
			bajarinsc      : aux_bajarinsc,
			aux_elimatinsc : aux_elimatinsc
		},
		dataType: 'json',
		encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exitooferta)
		{
			alertify.success('Datos se guardaron con exito.');
			//verPlanillaInscripcion();
//					window.open("../clases/planilla_inscripcion.class.php?ced_estudiante="+sessvars.cedula+"&aux_lapso="+sessvars.fil_codlapso+" ","nuevo","width=800, height=800"); 
			var confirm= alertify.confirm('Mensaje','Como desea ver Planilla de Inscripcion?',null,null).set('labels', {ok:'Descargar Archivo', cancel:'Ver en pantalla'}); 	
			confirm.set({transition:'slide'});   	

			confirm.set('onok', function(){ //callbak al pulsar botón positivo
				imprimirPlanilla("D");
				buscarEstudiante();
			});
			confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
				imprimirPlanilla("I");
				buscarEstudiante();
			    //alertify.error('Has Cancelado la Solicitud');
			});
			
		}else
		{
			alertify.success('Falló la insercion de los datos.');
			alertify.success(datos.mensaje);
		}
	});
}