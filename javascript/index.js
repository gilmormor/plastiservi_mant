$(document).ready(function() {
	// Validar campos numericos de pantalla agregar_deposito.php
    //$('#dep_cedula').numeric();
    $('#divrecuperarclave').hide();
    $('#cedula').numeric();

	$("#formulario").validate(
	{
		rules:
		{
			usuario: {required: true, email: true, minlength: 8, maxlength: 80},
			clave :{required: true, minlength: 1, maxlength: 20}
		},
		submitHandler: function(form){
			$.ajax({
					type 	: 'POST',
					url		: 'controladores/controlador_usuario.php',
					data 	: 
					{
						accion: "iniciosesion",
						correo     : $("#usuario").val(),
						clave      : $("#clave").val()
					},
					dataType: 'json',
					encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					sessvars.tipousuario = datos.datosest.fk_tip_usu; //Variable de sesion javascript tipo de usuario
					sessvars.cedula = datos.datosest.ced_usu;    //Variable de sesion javascript Cedula
					//SI ES TIPO DE USUARIO ESTUDIANTE=4 ENTRA DE UNA AL SISTEMA NO ENVIA CLAVE DE VALIDACION AL CORREO
					//LA CLAVE DE VALIDACION ES PARA LOS PROFESORES
					sessvars.fil_numcuentasoft   = datos.datosfil.fil_numcuentasoft;   //NUMERO DE CUANTA SOFTSERVI
					sessvars.fil_numcuentacol    = datos.datosfil.fil_numcuentacol;    //NUMERO DE CUANTA COLEGIO
					sessvars.fil_numcuentapad    = datos.datosfil.fil_numcuentapad;    //NUMERO DE CUANTE CONSEJO DE PADRES
					sessvars.fil_mtoinsc         = datos.datosfil.fil_mtoinsc;         //MONTO MINIMO DE INSCRIPCION
					sessvars.fil_mtoinscmax      = datos.datosfil.fil_mtoinscmax;      //MONTO MAXIMO DE INSCRIPCION
					sessvars.fil_mtoconpadres    = datos.datosfil.fil_mtoconpadres;    //MONTO MINIMO DE CONSEJO DE PADRES
					sessvars.fil_mtoconpadresmax = datos.datosfil.fil_mtoconpadresmax; //MONTO MAXIMO DE CONSEJO DE PADRES
					sessvars.fil_mntoweb         = datos.datosfil.fil_mntoweb;         //MONTO MINIMO DE INSCRIPCION WEF
					sessvars.fil_mntowebmax      = datos.datosfil.fil_mntowebmax;      //MONTO MAXIMO DE INSCRIPCION WEF
					sessvars.fil_carnet          = datos.datosfil.fil_carnet;          //MONTO MINIMO DE CARNET
					sessvars.fil_carnetmax       = datos.datosfil.fil_carnetmax;       //MONTO MAXIMO DE CARNET
					sessvars.fil_webcarnet       = parseFloat(sessvars.fil_mntoweb)+parseFloat(sessvars.fil_carnet);       //MONTO MINIMO WEB + CARNET
					sessvars.fil_webcarnetmax    = parseFloat(sessvars.fil_mntowebmax)+parseFloat(sessvars.fil_carnetmax); //MONTO MAXIMO WEB + CARNET
					sessvars.fil_codlapso        = datos.datosfil.fil_codlapso;   //Lapso de inscripcion
					sessvars.fil_fecha_dep       = datos.datosfil.fil_fecha_dep;  //Fecha filtro de depositos: Depositos mayores a esta fecha
					//sessvars.fil_mtoseguroescolar= datos.datosfil.fil_mtoseguroescolar;
					sessvars.fil_inscviva        = datos.datosfil.sta_inscviva; //Inscripcion viva
					sessvars.mensajefininsc      = "Proceso de Inscripción Finalizó.";
					sessvars.usuNombre           = datos.usunomape;
					sessvars.usuarioID           = datos.usuarioID;
					

					if(datos.fk_tip_usu == 4)
					{
						//Ayax para buscar en filtros si se piden los depositos de Softservi, Colegio o Consejo de Padres
						/*
						$.ajax({
								type 	: 'POST',
								url		: 'controladores/controlador_filtros.php',
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
								//VARIABLES DE SESION
								sessvars.fil_numcuentasoft   = datosfiltro.datos.fil_numcuentasoft;   //NUMERO DE CUANTA SOFTSERVI
								sessvars.fil_numcuentacol    = datosfiltro.datos.fil_numcuentacol;    //NUMERO DE CUANTA COLEGIO
								sessvars.fil_numcuentapad    = datosfiltro.datos.fil_numcuentapad;    //NUMERO DE CUANTE CONSEJO DE PADRES
								sessvars.fil_mtoinsc         = datosfiltro.datos.fil_mtoinsc;         //MONTO MINIMO DE INSCRIPCION
								sessvars.fil_mtoinscmax      = datosfiltro.datos.fil_mtoinscmax;      //MONTO MAXIMO DE INSCRIPCION
								sessvars.fil_mtoconpadres    = datosfiltro.datos.fil_mtoconpadres;    //MONTO MINIMO DE CONSEJO DE PADRES
								sessvars.fil_mtoconpadresmax = datosfiltro.datos.fil_mtoconpadresmax; //MONTO MAXIMO DE CONSEJO DE PADRES
								sessvars.fil_mntoweb         = datosfiltro.datos.fil_mntoweb;         //MONTO MINIMO DE INSCRIPCION WEF
								sessvars.fil_mntowebmax      = datosfiltro.datos.fil_mntowebmax;      //MONTO MAXIMO DE INSCRIPCION WEF
								sessvars.fil_carnet          = datosfiltro.datos.fil_carnet;          //MONTO MINIMO DE CARNET
								sessvars.fil_carnetmax       = datosfiltro.datos.fil_carnetmax;       //MONTO MAXIMO DE CARNET
								sessvars.fil_webcarnet       = parseFloat(sessvars.fil_mntoweb)+parseFloat(sessvars.fil_carnet);       //MONTO MINIMO WEB + CARNET
								sessvars.fil_webcarnetmax    = parseFloat(sessvars.fil_mntowebmax)+parseFloat(sessvars.fil_carnetmax); //MONTO MAXIMO WEB + CARNET
								sessvars.fil_codlapso        = datosfiltro.datos.fil_codlapso;   //Lapso de inscripcion
								sessvars.fil_fecha_dep       = datosfiltro.datos.fil_fecha_dep;  //Fecha filtro de depositos: Depositos mayores a esta fecha
								//alert(sessvars.fil_codlapso);

								//Si cualquiera de los 3 status de depositos esta en 1 se abre la pantalla de ingresar depositos
								if((datosfiltro.datos.fil_depsoftservi==1) || (datosfiltro.datos.fil_depcolegio==1) || (datosfiltro.datos.fil_depconpadres==1))
								{
									alertify.success('Registro de Depositos.');
									location.href = "pantallas/depositos_registrar.php";
								}else
								{
									alertify.success(datos.mensaje);
									location.href = "menu_principal.php";
								}
							}else
							{
								alertify.error(datos.mensaje);
								location.href = "index.php";
							}
						});
						*/
						if((datos.datosfil.fil_depsoftservi==1) || (datos.datosfil.fil_depcolegio==1) || (datos.datosfil.fil_depconpadres==1))
						{
							alertify.success('Registro de Depositos.');
							location.href = "pantallas/depositos_registrar.php";
						}else
						{
							//alertify.success(datos.mensaje);
							//location.href = "menu_principal.php";
							//Validar cambiando la variable de sesion $_SESSION["statusdepositosok"] a true ya que 
							$.ajax({
								type 	: 'POST',
								url		: 'controladores/controlador_usuario.php',
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
									location.href = "menu_principal.php";
								}else
								{
									alertify.error('Ocurrio un error.');
								}
							});
						}

					}else
					{
						//SI ES DOCENTE VA A PEDIR LA CLAVE DE VALIDACION QUE SE ENVIO AL CORREO
						/* EN COMENTARIO PORQUE NO ESTA ENVIANDO CLAVE DE VALIDACION AL CORREO
						$("#formulario").hide();
						$('#formvcodval').show();
						alertify.success('Clave de validación fue enviada a su correo.');				
						*/
						//ESTO ES PORQUE NO ESTA ENVIANDO CLAVE DE VALIDACION AL CORREO
						alertify.success(datos.mensaje);
						location.href = "menu_principal.php";

					}
				}else
				{
					alertify.error(datos.mensaje);
				}
			});


		}

	});

	$("#formvcodval").validate
	({
		rules:
		{
			codvalidacion :{required: true, minlength: 4, maxlength: 20}
		},
		messages:
		{
			codvalidacion :{required: "Este campo es obligatorio.", minlength: "Por favor, no escribas menos de {0} caracteres.", maxlength: "Por favor, no escribas más de {0} caracteres."}
		},
		submitHandler: function(form){
			$.ajax({
					type 	: 'POST',
					url		: 'controladores/controlador_usuario.php',
					data 	: 
					{
						accion         : "validacion",
						codvalidacion  : $("#codvalidacion").val()
					},
					dataType: 'json',
					encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					$("#formulario").hide();
					$('#formvcodval').show();
					alertify.success(datos.mensaje);				
					location.href = "menu_principal.php";
				}else
				{
					alertify.error(datos.mensaje);
				}
			});
		}

	});


	$("#iniciar").click(function(e)
	{
		/*
		var confirm= alertify.confirm('Mensaje','Confirmar Guardar?',null,null).set('labels', {ok:'Confirmar', cancel:'Cancelar'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_deposito.php',
					data 	: 
					{
						accion: "insertar",
						dep_cedula     : $("#dep_cedula").val(),
						mfor_cod       : $("#mfor_cod").val(),
						fid_banco      : $("#fid_banco").val(),
						dep_referencia : $("#dep_referencia").val(),
						dep_lote       : $("#dep_lote").val(),
						dep_clavconf   : $("#dep_clavconf").val(),
						dep_fecha      : $("#dep_fecha").val(),
						dep_monto      : $("#dep_monto").val(),
						dep_nofacturar : $("#dep_nofacturar").val()
					},
					dataType: 'json',
					encode	: true
			})

			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				respuesta(datos);
			});
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});
		*/
	});

	$('#recuperarclave').click(function(e)
	{
		$('#sha').hide();
		$('#divrecuperarclave').show();
		$('#email').focus();
	});

	$('#inises').click(function(e)
	{
		$('#divrecuperarclave').hide();
		$('#sha').show();
		$('#usuario').focus();
	});

	$('#rcontra').click(function(e)
	{

		$.ajax({
				type 	: 'POST',
				url		: 'controladores/controlador_usuario.php',
				data 	: 
				{
					accion : "recuperarclave",
					email  : $("#email").val(),
					cedula : $("#cedula").val()
				},
				dataType: 'json',
				encode	: true
		})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				$('#divrecuperarclave').hide();
				$('#sha').show();
				$('#usuario').focus();

				alertify.success(datos.mensaje);				
				//location.href = "menu_principal.php";
			}else
			{
				alertify.error(datos.mensaje);
			}
		});
	});

});