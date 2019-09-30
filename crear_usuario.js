$(document).ready(function() {
	// Validar campos numericos de pantalla
   $('#ced_usu').numeric();
   $('#nombre').attr("disabled", true);
    $("span.help-block").hide();
    //*******************************************************************

	$("#ced_usu").focusin(function()
	{
		$('#nombre').val('');
		$('#apellido').val('');
		$('#ema_usu').val('');
		$('#cla_usu').val('');
		$('#cla_usu1').val('');

		camposnormales('ced_usu');
		camposnormales('ema_usu');
		camposnormales('cla_usu');
		camposnormales('cla_usu1');
		activartextbox();
	})

	$("#ced_usu").focusout(function()
	{
		//validacion('ced_usu');
		codigo = document.getElementById('ced_usu').value;
		if (( codigo == null || codigo.length == 0 || /^\s+$/.test('ced_usu') ) == false)
		{

			$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_estudiante.php',
				data 	: 
				{
					accion    : "consultar",
					est_ced   : $('#ced_usu').val()
				},
				dataType: 'json',
				encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					$('#nombre').val(datos.nombre);
					$('#apellido').val(datos.apellido);
					$('#ema_usu').val('');
					$('#cla_usu').val('');
					$('#cla_usu1').val('');
					$.ajax({
						type 	: 'POST',
						url		: '../controladores/controlador_usuario.php',
						data 	: 
						{
							accion    : "consultar",
							ced_usu   : $('#ced_usu').val()
						},
						dataType: 'json',
						encode	: true
					})
					.done(function(datos){
						//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
						if(datos.exito)
						{
							//alertify.success(datos.mensaje);
							$('#nombre').val(datos.nom_usu);
							$('#apellido').val(datos.ape_usu);
							$('#ema_usu').val(datos.ema_usu);
							desactivartextbox();
							//$('#crear').attr("disabled", true);
							camposnormales('ced_usu');
							camposnormales('ema_usu');
							camposnormales('cla_usu');
							camposnormales('cla_usu1');
							alertify.success(datos.mensaje);
							alertify.success('Debe ingresar al sistema por medio del email y claves suministradas por Ud.');
						}else
						{
							alertify.error(datos.mensaje);
							alertify.success('Debe llenar los datos para crear el Usuario.');
							$( "#ema_usu" ).focus();
						}
					});

				}else
				{
					alertify.error('Para crear usuario debe ser Estudiante Regular de la Institución');
					alertify.error('Si es Estudiante Nuevo Ingreso debe Dirigirse al colegio para que validen sus Datos');
					blanquearCampos();
					//$('#crear').attr("disabled", true);
				}
			});
		}
	})

	$("#crear").click(function()
	{
		codigo = document.getElementById('nombre').value;
		if( !(codigo == null || codigo.length == 0 || /^\s+$/.test(codigo)) )
		{
			if (verificar())
			{
				$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_usuario.php',
					data 	: 
					{
						accion		: "consultarXemail",
						ema_usu		: $('#ema_usu').val()
					},
					dataType: 'json',
					encode	: true
				})
				.done(function(datos){
					//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
					campo = 'ema_usu';
					if(datos.exito)
					{
						$("#glypcn"+campo).remove();
						$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
						$('#'+campo).parent().children('span').text("Correo ya existe. Debe ingresar otro!").show();
						$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
						alertify.error(datos.mensaje);
					}else
					{
						$("#glypcn"+campo).remove();
						$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
						$('#'+campo).parent().children('span').hide();
						$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");

						$.ajax({
							type 	: 'POST',
							url		: '../controladores/controlador_usuario.php',
							data 	: 
							{
								accion		: "agregar_usuariojson",
								ema_usu		: $('#ema_usu').val(),
								cla_usu 	: $('#cla_usu').val(),
								ced_usu		: $('#ced_usu').val(),
								nom_usu		: $('#nombre').val(),
								ape_usu		: $('#apellido').val(),
								fk_tip_usu	: '4',
								tel_usu		: '',
								est_usu		: 'A'
							},
							dataType: 'json',
							encode	: true
						})
						.done(function(datos){
							//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
							if(datos.exito)
							{
								alertify.success(datos.mensaje);
								 blanquearCampos();
								 $.get("../index.php");
							}else
							{
								alertify.error(datos.mensaje);
							}
						});
					}
				});
			}
		}else
		{
			alertify.error('Nombre o Apellido no pueden quedar en blanco.');
		}
	})

});

function blanquearCampos()
{
	$('#ced_usu').val('');
	$('#nombre').val('');
	$('#apellido').val('');
	$('#ema_usu').val('');
	$('#cla_usu').val('');
	$('#cla_usu1').val('');
}

function activartextbox()
{
	$('#ema_usu').attr("disabled", false);
	$('#cla_usu').attr("disabled", false);
	$('#cla_usu1').attr("disabled", false);
}

function desactivartextbox()
{
	$('#nombre').attr("disabled", true);
	$('#apellido').attr("disabled", true);
	$('#ema_usu').attr("disabled", true);
	$('#cla_usu').attr("disabled", true);
	$('#cla_usu1').attr("disabled", true);
}


function verificar()
{
	var v1=0,v2=0,v3=0,v4=0,v5=0,v6=0;
	v1=validacion('ced_usu');
	v2=validacion('nombre');
	v3=validacion('ema_usu');
	v4=validacion('cla_usu');
	v5=validacion('cla_usu1');
	v6=validacion('apellido');
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
	if (campo==='ced_usu')
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
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
				$('#'+campo).parent().children('span').hide();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
				return true;
			}
	    }
	}

	if (campo==='ema_usu')
	{
		cajatexto = document.getElementById(campo).value;
		var caract = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
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
			if(caract.test(cajatexto) == false)
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", "form-group has-error has-feedback");
				$('#'+campo).parent().children('span').text("Correo no valido").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
				return false;
			}else
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
				$('#'+campo).parent().children('span').hide();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
				return true;				
			}
		} 
	}

	if (campo==='cla_usu')
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
			if ($('#cla_usu').val() != $('#cla_usu1').val())
			{
				$("#glypcn"+'cla_usu').remove();
				$('#'+'cla_usu').parent().parent().attr("class", "form-group has-error has-feedback");
				$('#'+'cla_usu').parent().children('span').text("Contraseñas no coinciden").show();
				$('#'+'cla_usu').parent().append("<span id='glypcn"+"cla_usu"+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");

				$("#glypcn"+'cla_usu1').remove();
				$('#'+'cla_usu1').parent().parent().attr("class", "form-group has-error has-feedback");
				$('#'+'cla_usu1').parent().children('span').text("Contraseñas no coinciden").show();
				$('#'+'cla_usu1').parent().append("<span id='glypcn"+"cla_usu1"+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");

				return false;

			}else
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
				$('#'+campo).parent().children('span').hide();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");

				$("#glypcn"+'cla_usu1').remove();
				$('#'+'cla_usu1').parent().parent().attr("class", "form-group has-success has-feedback");
				$('#'+'cla_usu1').parent().children('span').hide();
				$('#'+'cla_usu1').parent().append("<span id='glypcn"+"cla_usu1"+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");

				return true;

			}
		} 
	}
	if (campo==='cla_usu1')
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
			if ($('#cla_usu').val() != $('#cla_usu1').val())
			{
				$("#glypcn"+'cla_usu').remove();
				$('#'+'cla_usu').parent().parent().attr("class", "form-group has-error has-feedback");
				$('#'+'cla_usu').parent().children('span').text("Contraseñas no coinciden").show();
				$('#'+'cla_usu').parent().append("<span id='glypcn"+"cla_usu"+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");

				$("#glypcn"+'cla_usu1').remove();
				$('#'+'cla_usu1').parent().parent().attr("class", "form-group has-error has-feedback");
				$('#'+'cla_usu1').parent().children('span').text("Contraseñas no coinciden").show();
				$('#'+'cla_usu1').parent().append("<span id='glypcn"+"cla_usu1"+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
				return false;
			}else
			{
				$("#glypcn"+'cla_usu').remove();
				$('#'+'cla_usu').parent().parent().attr("class", "form-group has-success has-feedback");
				$('#'+'cla_usu').parent().children('span').hide();
				$('#'+'cla_usu').parent().append("<span id='glypcn"+"cla_usu"+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");

				$("#glypcn"+campo).remove();
				$('#'+campo).parent().parent().attr("class", "form-group has-success has-feedback");
				$('#'+campo).parent().children('span').hide();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");

				return true;
			}
		} 
	}
}

function camposnormales(campo)
{
	$("#glypcn"+campo).remove();
	$('#'+campo).parent().parent().attr("class", "form-group");
	$('#'+campo).parent().children('span').hide();
	$('#'+campo).parent().append("<span id='glypcn"+campo+"' class=''></span>");	
}
