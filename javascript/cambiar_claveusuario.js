$(document).ready(function() {
	$('#cla_usu').focus();
	var cla_usu1  = $( "#cla_usu1" ),
	cla_usu2 = $( "#cla_usu2" ),
	allFields = $( [] ).add( cla_usu1 ).add( cla_usu2 ),
	tips = $( ".validateTips" );

	//-----------
	pass1val =new LiveValidation('cla_usu1');
	pass1val.add( Validate.Presence );
	pass1val.add(Validate.Length,{minimum: 3, maximum: 10,failureMessage: "Las contraseñas deben ser entre 3 y 10 caracteres!"});
	//-----------
	pass2val =new LiveValidation('cla_usu2');
	pass2val.add( Validate.Presence );
	pass2val.add(Validate.Confirmation,{ match: 'cla_usu1', failureMessage: "Las contraseñas no concuerdan!" } );
	pass2val.add(Validate.Length,{minimum: 3, maximum: 10,failureMessage: "Las contraseñas deben ser entre 3 y 10 caracteres!"});
	//-----------


 	$("#cla_usu2").focusout(function(e)
	{
		if(!($("#cla_usu1").val()==$("#cla_usu2").val()))
		{
			alertify.error('Claves no coinciden.');
		}
	});


	$("#cambiar").click(function(e)
	{
		$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_usuario.php',
			data 	: 
			{
				accion: "cambiarclave",
				cla_usu  : $('#cla_usu').val(),
				cla_usu1 : $('#cla_usu1').val(),
				cla_usu2 : $('#cla_usu2').val()
			},
			dataType: 'json',
			encode	: true
		})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			if(datos.exito)
			{
				alertify.success(datos.mensaje);
				blanquearCajas();
			}
			else
				alertify.error(datos.mensaje);
		});
	});

 });

function blanquearCajas()
{
	$('#cla_usu').val("");
	$("#cla_usu1").val("");
	$("#cla_usu2").val("");
}