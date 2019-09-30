$(document).ready(function(){
	/*
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
	$(document).ajaxComplete(function(){$("#dialog_cargar").dialog( "close" );});*/
	//startTime();
	$('#departamentoAreaID').val('');
	$(".selectpicker").selectpicker('refresh');
	$('[data-toggle="tooltip"]').tooltip();
	$( "#ModalCenter" ).draggable({opacity: 0.35, handle: ".modal-header"});

	/*
	$('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
	$('[data-toggle="tooltip"]').tooltip({placement: "right"});
	*/

});


function llenarMaquinas()
{
	if($('#departamentoAreaID').val()!=''){
		$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_maquina.php',
				data 	: 
				{
					accion             : "llenarMaquinasPorArea",
					departamentoAreaID : $('#departamentoAreaID').val()
				},
				dataType: 'json',
				encode	: true
			})
		.done(function(datos){
			//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
			$('#tablaMaquinas').html(datos.tabla);
			var nFilas = $("#tablaMaquinas tbody tr").length;
			for(i=1; i<=nFilas; i++){
				$('#divTabOT'+i).hide();
			}

			$('#tablaMaquinas').show();
			$('#divGuardar').html(datos.botonGuardar);
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="tooltip"]').tooltip({delay: {show: 500, hide: 100}});
			$('#numReg').val(datos.nroreg);

			/*
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
			*/
		});
	}else{
		$('#tablaMaquinas').html('');
		$('#divGuardar').html('');
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
				$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
				$('#'+campo).parent().children('span').text("Campo obligatorio").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback check'></span>");
				$('#'+campo).focus();
				return false;
			}
			else
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().attr("class", columnas+" has-success has-feedback");
				$('#'+campo).parent().children('span').hide();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
				return true;
			}

		break 
		case "numerico": 
			codigo = document.getElementById(campo).value;
			if( codigo == null || codigo.length == 0 || /^\s+$/.test(codigo) ) {
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
				$('#'+campo).parent().children('span').text("Campo obligatorio").show();
				$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
				$('#'+campo).focus();
				return false;
			}
			else
			{
				$("#glypcn"+campo).remove();
				$('#'+campo).parent().attr("class", columnas+" has-success has-feedback");
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
				$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
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
					$('#'+campo).parent().attr("class", columnas+" has-error has-feedback");
					$('#'+campo).parent().children('span').text("Correo no valido").show();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-remove form-control-feedback'></span>");
					$('#'+campo).focus();
					return false;
				}else
				{
					$("#glypcn"+campo).remove();
					$('#'+campo).parent().attr("class", columnas+" has-success has-feedback");
					$('#'+campo).parent().children('span').hide();
					$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon glyphicon-ok form-control-feedback'></span>");
					return true;				
				}
			} 
		break 
		default: 
		
	}
}

function activarObserv(i)
{
	if($('#estatus'+i).val()=="2" && $('#observaciones'+i).val()!="" && $('#observaciones'+i).val().length>0) {
		var nomEquipo=$('#codigoInterno'+i).val();
		var confirm= alertify.confirm('Mensaje','Lista de chequeo de Equipo '+nomEquipo+' previamente guardado. Desea eliminarlo?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
		confirm.set({transition:'slide'});   	

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			checkestado(i,1);
		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
			alertify.error('Has Cancelado la Solicitud');
			if($('#estado'+i).prop('checked')){
				$('#estado'+i).prop('checked', false);
			}else{
				$('#estado'+i).prop('checked', true);
			}
		});
	}else{
		checkestado(i,1);
	}
}

function checkestado(i,aux_val){
	if($('#estado'+i).prop('checked'))
	{
		$('#observaciones'+i).prop('disabled', false);
		$('#observaciones'+i).focus();
	}else{
		$('#observaciones'+i).prop('disabled', true);
		if(aux_val==1){
			$('#observaciones'+i).val('');
		}
	}
}

function guardarMant(){
	if(sessvars.staInsert==0){
		alertify.alert('Mensaje',"<h3>Usuario no tiene permiso para Guardar.</h3>");
	}else{
		$("#btnguardar").addClass( "disabled" );
		$("#btnguardar").prop('disabled', true);
		var confirm= alertify.confirm('Mensaje','Desea Guardar?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
		confirm.set({transition:'slide'});

		confirm.set('onok', function(){ //callbak al pulsar botón positivo
			
			var filas = $("#tablaMaquinas").find("tbody tr"); //devulve las filas del body de tu tabla segun el ejemplo que brindaste
			var resultado = "";
			//alert(filas.length);
			var valTabla = [];
			aux_todolleno=true;
			numMaq=$('#numReg').val();
			for(j=1; j<=numMaq; j++){
				aux_chulo = $('#estado'+j).prop('checked');
				/*$('#estado'+j).prop('disabled', true);
				$('#observaciones'+j).prop('disabled', true);*/
				if(aux_chulo && !validacion('observaciones'+j,'texto','Col')){
					alertify.error("Falta incluir Informacion.");
					aux_todolleno=false;
				}
			}

			if(aux_todolleno){

				for(i=1; i<=numMaq; i++){ //Recorre las filas 1 a 1
					var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
					maquinaID       = $("#maquinariaequiposDetalleID"+i).val();
					chulo           = $('#estado'+i).prop('checked');
					estatus         = $("#estatus"+i).val();
					observacion     = $("#observaciones"+i).val();
					solicitudTrabID = $("#solicitudTrabID"+i).val();

					if(observacion!="" && estatus == "0"){
						estatus = "1";
					}
					var fila = {
						maquinaID,
						estatus,
						observacion,
						solicitudTrabID
					};
					valTabla.push(fila);
				}
	/*
				for(i=0; i<filas.length; i++){ //Recorre las filas 1 a 1
					var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
					maquinaID       = $($(celdas[0]).children("input")[0]).val();
					chulo           = $('#estado'+(i+1)).prop('checked');
					estatus         = $($(celdas[3]).children("input")[0]).val();
					observacion     = $($(celdas[4]).children("input")[0]).val();
					solicitudTrabID = $($(celdas[5]).children("input")[0]).val();

					if(observacion!="" && estatus == "0"){
						estatus = "1";
					}
					var fila = {
						maquinaID,
						estatus,
						observacion,
						solicitudTrabID
					};
					valTabla.push(fila);
				}
	*/

				//console.log(valTabla);
				$.ajax({
					type 	: 'POST',
					url		: '../controladores/controlador_maquina.php',
					data 	: 
					{
						accion             : "guardarChDiaMaq",
						usuarioID          : sessvars.usuarioID,
						departamentoAreaID : $("#departamentoAreaID").val(),
						valores            : JSON.stringify(valTabla)
					},
					dataType: 'json',
					encode	: true
				})
				.done(function(datos){
					//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
					if(datos.exito)
					{
						alertify.success(datos.mensaje);
						llenarMaquinas();
					}else{
						alertify.error(datos.mensaje);
					}
					
				});
			}

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
		$("#btnguardar").removeClass( "disabled" );
		$("#btnguardar").prop('disabled', false);
	}
}

function mostrarH(i){
	if($('#divTabOT'+i).css('display') == 'none'){
		$('#botonD'+i).attr("class", "glyphicon glyphicon-triangle-top");
	}else{
		$('#botonD'+i).attr("class", "glyphicon glyphicon-triangle-bottom");
	}
	$('#divTabOT'+i).slideToggle(500);
}

function eliminarST(i){
	if(sessvars.staDelete==0){
		alertify.alert('Mensaje',"<h3>Usuario no tiene permiso para eliminar.</h3>");
	}else{
		var confirm= alertify.confirm('Mensaje','Desea Eliminar?',null,null).set('labels', {ok:'Si', cancel:'No'}); 	
		confirm.set({transition:'slide'});

		confirm.set('onok', function(){ //callbak al pulsar botón positivo

			//console.log(valTabla);
			st = $("#solicitudTrabID" + i).html();
			$.ajax({
				type 	: 'POST',
				url		: '../controladores/controlador_maquina.php',
				data 	: 
				{
					accion           : "eliminarST",
					solicitudTrabID  : st,
					usuarioID        : $("#usuarioID" + i).html()
				},
				dataType: 'json',
				encode	: true
			})
			.done(function(datos){
				//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
				if(datos.exito)
				{
					alertify.success(datos.mensaje);
					$("#fila" + i).fadeOut(2000);
				}else{
					alertify.error(datos.mensaje);
				}
			});

		});
		confirm.set('oncancel', function(){ //callbak al pulsar botón negativo
		    alertify.error('Has Cancelado la Solicitud');
		});
	}
}

function modificarObs(i){
	if(sessvars.tipousuario!=4 && sessvars.staUpdate==0){
		alertify.alert('Mensaje',"<h3>Usuario no tiene permiso para modificar.</h3>");
	}else{
		limpiarInputST();
		quitarverificar();

		$("#usuarioIDM").val($("#usuarioID"+i).html());
		$("#nomape_usuM").val($("#nomape_usu"+i).html());
		$("#ema_usuM").val($("#ema_usu"+i).html());
		$("#solicitudTrabID").val($("#solicitudTrabID"+i).html());
		$("#filaST").val(i);
		$("#exampleModalLongTitle").html('Solicitud Trabajo Nro.'+$("#solicitudTrabID"+i).html());
		$("#descripTrabajo").val($("#observaciones"+i).html());
		$("#ModalCenter").modal("show");
		//$(".selectpicker").selectpicker('refresh');
	}
}

function limpiarInputST(){
	$("#usuarioIDM").val('');
	$("#nomape_usuM").val('');
	$("#ema_usuM").val('');
	$("#solicitudTrabID").val('');
	$("#filaST").val('');
	$("#descripTrabajo").val('');
	//$(".selectpicker").selectpicker('refresh');
}

function quitarverificar(){
	quitarValidacion('descripTrabajo','texto','col-xs-12 col-sm-12');
}

function quitarValidacion(campo,tipo,columnas)
{
	var a=0;
	//columnas = $('#'+campo).parent().parent().attr("class");
	switch (tipo) 
	{ 
		case "texto": 
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().attr("class", columnas);
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
			return true;
		break 
		case "numerico": 
			codigo = document.getElementById(campo).value;
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", columnas);
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
			return true;

		break 
		case "combobox": 
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", columnas);
			$('#'+campo).parent().parent().children('span').hide();
			$('#'+campo).parent().parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
			return true;

		break 
		case "email": 
			$("#glypcn"+campo).remove();
			$('#'+campo).parent().parent().attr("class", columnas);
			$('#'+campo).parent().children('span').hide();
			$('#'+campo).parent().append("<span id='glypcn"+campo+"' class='glyphicon'></span>");
		break 
		default: 
		
	}
}

$("#btnGuardarM").click(function()
{
	//Validar los campos obligatorios
	//alert(sessvars.fil_codlapso);
	if(sessvars.staUpdate==0){
		alertify.alert('Mensaje',"<h3>Usuario no tiene permiso para Guardar.</h3>");
	}else{
		if(verificar())
		{
			update();
		}else{
			alertify.error("Falta incluir informacion");
		}
	}
});

function verificar()
{
	var v1=0;

	v1=validacion('descripTrabajo','texto','col-xs-12 col-sm-12');

	if (v1===false)
	{
		return false;
	}else{
		return true;
	}
}

function update(){
	$.ajax({
			type 	: 'POST',
			url		: '../controladores/controlador_maquina.php',
			data 	: 
			{
				accion           : "update",
				solicitudTrabID  : $("#solicitudTrabID").val(),
				descripcion      : $("#descripTrabajo").val(),
				usuarioID        : $("#usuarioIDM").val()
			},
			dataType: 'json',
			encode	: true
	})
	.done(function(datos){
		//ESPESIFICAR COMO ACTUAR CON LOS DATOS RECIBIDOS
		if(datos.exito)
		{
			$("#observaciones"+$("#filaST").val()).html($("#descripTrabajo").val());
			alertify.success(datos.mensaje);
			$("#ModalCenter").modal("hide");
			//alert('#btnInicioServ'+i);
		}else
		{
			alertify.error(datos.mensaje);
		}
	});
}