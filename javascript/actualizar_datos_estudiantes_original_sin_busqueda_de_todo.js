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

	$('#est_fecnac').datepicker();

	if(sessvars.tipousuario == 4)
	{

		$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_estudiante.php',
			data 	: 
			{
				accion : "consultar",
				est_ced   : sessvars.cedula
			},
			dataType: 'json',
			encode	: true
		})
		
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS

			if(datos.exito)
			{
				desactivarCajas();
				$('#est_ced').val(sessvars.cedula);
				$('#est_nacionalidad').val(datos.tablaest.est_nacionalidad);
				$('#est_nombres').val(datos.tablaest.est_nombres);
				$('#est_apellidos').val(datos.tablaest.est_apellidos);
				$('#est_fecnac').val(amdTodma(datos.tablaest.est_fecnac)); //amdTodma funcion creadfa por mi para voltear la fecha a DD/MM/AAAA. La funcion esta en principal.js
				$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_planteles.php',
					data 	: 
					{
						accion          : "buscarplantel",
						plan_est_codigo : datos.tablaest.est_placod
					},
					dataType: 'json',
					encode	: true
				})
				.done(function(datosplantel){
					//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
					if(datosplantel.exito)
					{
						$('#est_placod').val(datosplantel.datos.plan_nombre);
					}else
					{
						$('#est_placod').val('');
						alertify.error(datosplantel.mensaje);
					}
					$('#lugar_nace').val(datos.tablaest.est_lugnac);
					$('#est_codpais').val(datos.tablaest.est_codpais);est_estnac
					$('#est_estnac').val(datos.tablaest.est_estnac);
					$('#est_edocivil').val(datos.tablaest.est_edocivil);
					$('#est_sexo').val(datos.tablaest.est_sexo);
					$('#est_tipoparto').val(datos.tablaest.est_tipoparto);
					$('#est_email').val(datos.tablaest.est_email);
					$('#est_paren').val(datos.tablaest.est_paren);

					$('#est_callemer').val(datos.tablaest.est_callemer);
					$('#est_telfemer').val(datos.tablaest.est_telfemer);
					$('#est_grafam').val(datos.tablaest.est_grafam);
					$('#est_familia').val(datos.tablaest.est_familia);
					$('#est_vivecon').val(datos.tablaest.est_vivecon);
					$('#est_medtras').val(datos.tablaest.est_medtras);
					$('#rep_vivecondes').val(datos.tablaest.rep_vivecondes);

					$('#cli_cedrif').val(datos.tablaest.cli_cedrif);
					$('#cli_apenom').val(datos.tablaest.cli_apenom);
					$('#cli_direc').val(datos.tablaest.cli_direc);
					$('#cli_telf').val(datos.tablaest.cli_telf);

				

					//BUscar en tabla repest para traerse las cedulas de los padres y representantes
					$.ajax({
						type 	: 'POST',
						url		: '../controladores/controlador_repest.php',
						data 	: 
						{
							accion      : "buscar",
							rep_cedalum : sessvars.cedula
						},
						dataType: 'json',
						encode	: true
					})
					.done(function(datos){
						//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
						if(datos.exito)
						{
							aux_cedmad = datos.datosrep.rep_cedmad;
							aux_cedpad = datos.datosrep.rep_cedpad;
							aux_cedrep = datos.datosrep.rep_cedrep;

							blanquearDatosMadre();
							blanquearDatosPadre();
							blanquearDatosRepresentante();
							if(aux_cedmad>0)
							{
								$.ajax({
									type 	: 'POST',
									url		: '../controladores/controlador_representante.php',
									data 	: 
									{
										accion  : "buscar",
										rep_ced : aux_cedmad
									},
									dataType: 'json',
									encode	: true
								})
								.done(function(datos){
									//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
									if(datos.exito)
									{
										$('#rep_cedmad').val(datos.datosrepre.rep_ced);
										$('#rep_nacmad').val(datos.datosrepre.rep_nac);
										$('#rep_nomrepmad').val(datos.datosrepre.rep_nomrep);
										$('#rep_telcelmad').val(datos.datosrepre.rep_telcel);
										$('#rep_telhabrepmad').val(datos.datosrepre.rep_telhabrep);
										$('#rep_teltrarepmad').val(datos.datosrepre.rep_teltrarep);
										$('#rep_lugtrarepmad').val(datos.datosrepre.rep_lugtrarep);
										$('#rep_dirtrarepmad').val(datos.datosrepre.rep_dirtrarep);
										$('#rep_profrepmad').val(datos.datosrepre.rep_profrep);
										$('#rep_emailmad').val(datos.datosrepre.rep_email);
									}
								});
							}
							if(aux_cedpad>0)
							{
								$.ajax({
									type 	: 'POST',
									url		: '../controladores/controlador_representante.php',
									data 	: 
									{
										accion  : "buscar",
										rep_ced : aux_cedpad
									},
									dataType: 'json',
									encode	: true
								})
								.done(function(datos){
									//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
									if(datos.exito)
									{
										$('#rep_cedpad').val(datos.datosrepre.rep_ced);
										$('#rep_nacpad').val(datos.datosrepre.rep_nac);
										$('#rep_nomreppad').val(datos.datosrepre.rep_nomrep);
										$('#rep_telcelpad').val(datos.datosrepre.rep_telcel);
										$('#rep_telhabreppad').val(datos.datosrepre.rep_telhabrep);
										$('#rep_teltrareppad').val(datos.datosrepre.rep_teltrarep);
										$('#rep_lugtrareppad').val(datos.datosrepre.rep_lugtrarep);
										$('#rep_dirtrareppad').val(datos.datosrepre.rep_dirtrarep);
										$('#rep_profreppad').val(datos.datosrepre.rep_profrep);
										$('#rep_emailpad').val(datos.datosrepre.rep_email);
									}
								});
							}


							if(aux_cedrep>0)
							{
								$.ajax({
									type 	: 'POST',
									url		: '../controladores/controlador_representante.php',
									data 	: 
									{
										accion  : "buscar",
										rep_ced : aux_cedrep
									},
									dataType: 'json',
									encode	: true
								})
								.done(function(datos){
									//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
									if(datos.exito)
									{
										$('#rep_ced').val(datos.datosrepre.rep_ced);
										$('#rep_nac').val(datos.datosrepre.rep_nac);
										$('#rep_nomrep').val(datos.datosrepre.rep_nomrep);
										$('#rep_telcel').val(datos.datosrepre.rep_telcel);
										$('#rep_telhabrep').val(datos.datosrepre.rep_telhabrep);
										$('#rep_teltrarep').val(datos.datosrepre.rep_teltrarep);
										$('#rep_lugtrarep').val(datos.datosrepre.rep_lugtrarep);
										$('#rep_dirtrarep').val(datos.datosrepre.rep_dirtrarep);
										$('#rep_profrep').val(datos.datosrepre.rep_profrep);
										$('#rep_email').val(datos.datosrepre.rep_email);
									}
								});

							}
						}else
						{
							aux_cedmad = 0;
							aux_cedpad = 0;
							aux_cedrep = 0;

							$('#est_placod').val('');
							alertify.error(datos.mensaje);
						}
					});
				});
			}else
			{
				$('#est_nombres').val('');
				$('#est_apellidos').val('');
				$('#lugar_nace').val('');
			}
		});
	}


	$("#btnguardar").click(function()
	{
		if(verificar())
		{

		}else
		{
			alertify.error('Falta incluir informacion.');
		}
	});
})

function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0,v6=0,v7=0,v8=0,v9=0,v10=0,v11=0,v12=0,v13=0,v14=0,v15=0,v16=0,v17=0,v18=0,v19=0,v20=0;
	var v21=0,v22=0,v23=0,v24=0,v25=0,v26=0,v27=0,v28=0,v29=0,v30=0,v31=0,v32=0,v33=0,v34=0,v35=0,v36=0,v37=0,v38=0,v39=0,v40=0;
	var v41=0,v42=0,v43=0,v44=0,v45=0,v46=0,v47=0,v48=0,v49=0,v50=0;

	v1=validacion('est_ced','numerico','col-xs-12 col-md-3');
	v2=validacion('est_nacionalidad','texto','col-xs-12 col-md-1');
	v3=validacion('est_nombres','texto','col-xs-12 col-sm-3');
	v4=validacion('est_apellidos','texto','col-xs-12 col-sm-3');
	v5=validacion('est_fecnac','numerico','col-xs-12 col-sm-2');
	v6=validacion('est_placod','texto','col-xs-12 col-sm-3');
	v7=validacion('lugar_nace','texto','col-xs-12 col-sm-3');
	v8=validacion('est_codpais','texto','col-xs-12 col-sm-3');
	v9=validacion('est_estnac','texto','col-xs-12 col-sm-3');
	v10=validacion('est_edocivil','texto','col-xs-12 col-sm-2');
	v11=validacion('est_sexo','texto','col-xs-12 col-sm-2');
	v12=validacion('est_tipoparto','texto','col-xs-12 col-sm-3');
	v13=validacion('est_email','email','col-xs-12 col-sm-5');
	v14=validacion('rep_cedmad','numerico','col-xs-12 col-sm-2');
	v15=validacion('rep_nacmad','texto','col-xs-12 col-sm-1');
	v16=validacion('rep_nomrepmad','texto','col-xs-12 col-sm-3');
	v17=validacion('rep_telcelmad','numerico','col-xs-12 col-sm-3');
	v18=validacion('rep_telhabrepmad','numerico','col-xs-12 col-sm-3');
	v19=validacion('rep_lugtrarepmad','texto','col-xs-12 col-sm-4');
	v20=validacion('rep_dirtrarepmad','texto','col-xs-12 col-sm-5');
	v21=validacion('rep_profrepmad','texto','col-xs-12 col-sm-5');
	v22=validacion('rep_emailmad','email','col-xs-12 col-sm-7');
	v23=validacion('rep_cedpad','numerico','col-xs-12 col-sm-2');
	v24=validacion('rep_nacpad','texto','col-xs-12 col-sm-1');
	v25=validacion('rep_nomreppad','texto','col-sm-3 col-xs-12');
	v26=validacion('rep_telcelpad','numerico','col-xs-12 col-sm-3');
	v27=validacion('rep_telhabreppad','numerico','col-xs-12 col-sm-3');
	v28=validacion('rep_lugtrareppad','numerico','col-xs-12 col-sm-4');
	v29=validacion('rep_dirtrareppad','texto','col-xs-12 col-sm-5');
	v30=validacion('rep_teltrareppad','numerico','col-xs-12 col-sm-3');
	v31=validacion('rep_profreppad','texto','col-xs-12 col-sm-5');
	v32=validacion('rep_emailpad','email','col-xs-12 col-sm-7');
	v33=validacion('rep_ced','numerico','col-xs-12 col-sm-2');
	v34=validacion('rep_nac','numerico','col-xs-12 col-sm-1');
	v35=validacion('rep_nomrep','texto','col-xs-12 col-sm-3');
	v36=validacion('rep_telcel','numerico','col-xs-12 col-sm-3');
	v37=validacion('rep_telhabrep','numerico','col-xs-12 col-sm-3');
	v38=validacion('rep_lugtrarep','texto','col-xs-12 col-sm-4');
	v39=validacion('rep_dirtrarep','texto','col-xs-12 col-sm-5');
	v40=validacion('rep_teltrarep','numerico','col-xs-12 col-sm-3');
	v41=validacion('rep_profrep','texto','col-xs-12 col-sm-5');
	v42=validacion('rep_email','email','col-xs-12 col-sm-5');
	v43=validacion('par_desc','texto','col-xs-12 col-sm-2');
	v44=validacion('est_callemer','texto','col-xs-12 col-sm-4');
	v45=validacion('est_telfemer','texto','col-xs-12 col-sm-4');
	v46=validacion('est_familia','texto','col-xs-12 col-sm-2');
	v47=validacion('est_grafam','texto','col-xs-12 col-sm-2');
	v48=validacion('est_vivecon','texto','col-xs-12 col-sm-2');
	v49=validacion('est_medtras','texto','col-xs-12 col-sm-2');
	v50=validacion('rep_vivecondes','texto','col-xs-12 col-sm-3');


	if (v1===false || v2===false || v3===false || v4===false || v5===false || v6===false || v7===false || v8===false || v9===false || v10===false || v11===false || v12===false || v13===false || v14===false || v15===false || v16===false || v17===false || v18===false || v19===false || v20===false || v21===false || v22===false || v23===false || v24===false || v25===false || v26===false || v27===false || v28===false || v29===false || v30===false || v31===false || v32===false || v33===false || v34===false || v35===false || v36===false || v37===false || v38===false || v39===false || v40===false || v41===false || v42===false || v43===false || v44===false || v45===false || v46===false || v47===false || v48===false || v49===false || v50===false)
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
	$('#est_ced').attr("disabled", true);
	$('#est_nacionalidad').attr("disabled", true);
	$('#est_nombres').attr("disabled", true);
	$('#est_apellidos').attr("disabled", true);
	$('#est_placod').attr("disabled", true);
	$('#lugar_nace').attr("disabled", true);
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
}

function blanquearDatosMadre()
{
	$('#rep_cedmad').val('');
	$('#rep_nacmad').val('');
	$('#rep_nomrepmad').val('');
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
	$('#rep_telcel').val('');
	$('#rep_telhabrep').val('');
	$('#rep_teltrarep').val('');
	$('#rep_lugtrarep').val('');
	$('#rep_dirtrarep').val('');
	$('#rep_profrep').val('');
	$('#rep_email').val('');
}

