// JavaScript Doc


function mostrar_datos()
{
	var referencia=document.getElementById("referencia").value
	/*Creamos un objeto AJAX*/
	var objAjax=new XMLHttpRequest;
	objAjax.open("GET","../controladores/controlador_deposito.php?accion=buscar_deposito&dep_referencia="+referencia);

	objAjax.onreadystatechange=function()
	{
		//alert("Status vale: "+objAjax.status+"  -  readystate vale: "+objAjax.readyState)
		//alert("readystate vale: "+objAjax.readyState)
		if(objAjax.status==200 && objAjax.readyState==4)
		{
			var respuesta=objAjax.responseText
			//alert("La respuesta es: "+respuesta)

			var a = respuesta.split('#');
			//alert(a[3]);
			document.getElementById('cedula').value=a[0];
			document.getElementById('estatus').value=a[1];
			var aux_fecha=a[2].substr(8,2)+"/"+a[2].substr(5,2)+"/"+a[2].substr(0,4);
			document.getElementById('fecha').value=aux_fecha;
			document.getElementById('monto').value=a[3];
			document.getElementById('montocheque').value=a[4];
			document.getElementById('guardardepiufrom').disabled;
			if (a[4]>0)
			{
				document.getElementById('guardardepiufrom').enabled;
			}
		}
	}
	objAjax.send(null)
}


function mostrar_usuestudiante()
{
	var estudiante=document.getElementById("vcedula").value
	/*Creamos un objeto AJAX*/
	var objAjax=new XMLHttpRequest;
	objAjax.open("GET","../controladores/controlador_usuestudiante.php?accion=buscar_usuestudiante&usu_cod="+estudiante);

	objAjax.onreadystatechange=function()
	{
	//	alert("Status vale: "+objAjax.status+"  -  readystate vale: "+objAjax.readyState)
	//	alert("readystate vale: "+objAjax.readyState)
		if(objAjax.status==200 && objAjax.readyState==4)
		{
			var respuesta=objAjax.responseText
			//alert("La respuesta es: "+respuesta)

			var a = respuesta.split('#');
			// alert(a[3]);
			document.getElementById('nombre').value=a[0];
			document.getElementById('clave').value=a[1];
			document.getElementById('email').value=a[2];
		}
	}
	objAjax.send(null)
}

function validar_estudiante(){
	var usu_cod=document.getElementById("usu_cod");
	if(usu_cod.value=="")
		alert("Debes llenar los campos obligatorios");
		else
		document.getElementById("").submit();

}


function validar_deposito(){
	var dep_referencia=document.getElementById("dep_referencia");
	if(dep_referencia.value=="")
		alert("Debes llenar los campos obligatorios");
		else
		document.getElementById("").submit();

}

function pintar_operaciones()
{
 var delay=1000; //1 second
 var gif=document.getElementsByClassName("cargando")
 gif[0].style.display="block"
 document.getElementById("zona_operaciones").innerHTML=""

 setTimeout(function() {
 	
 var usuario=document.getElementById("fk_usuario").value
 var objAjax=new XMLHttpRequest()
 
 objAjax.open("GET","../controladores/controlador_operaciones.php?accion=seleccionar_operaciones&correo="+usuario);	
 
 objAjax.onreadystatechange=function()
 {
		 
    if(objAjax.status==200 && objAjax.readyState==4)
	{
	    	document.getElementById("zona_operaciones").innerHTML=objAjax.responseText 
			gif[0].style.display="none"
			
	}		
 }
objAjax.send(null);	

}, delay);

  //your code to be executed after 1 second
}

function mostrar_datos_opciones()
{
	var opcion=document.getElementById("fk_ope").value
	/*Creamos un objeto AJAX*/
	var objAjax=new XMLHttpRequest;
	objAjax.open("GET","../controladores/controlador_operaciones.php?accion=buscar_operacion&ide_ope="+opcion);

	objAjax.onreadystatechange=function()
	{
		//alert("Status vale: "+objAjax.status+"  -  readystate vale: "+objAjax.readyState)
		//alert("readystate vale: "+objAjax.readyState)
		if(objAjax.status==200 && objAjax.readyState==4)
		{
			var respuesta=objAjax.responseText
			//alert("La respuesta es: "+respuesta)

			var a = respuesta.split('#');
			//alert(a[3]);
			document.getElementById('nom_ope').value=a[0];
			document.getElementById('url').value=a[1];
			document.getElementById('fk_mod').value=a[2];
			//document.getElementById('est_ope').value=a[3];

			if(a[3]=='A')
			{
				document.getElementById('est_ope1').checked=true;
				document.getElementById('est_ope2').checked=false;
			}
			else
			{
				document.getElementById('est_ope1').checked=false;
				document.getElementById('est_ope2').checked=true;
			}
		}
	}
	objAjax.send(null)
}

function mostrar_datos_deposito_softservi()
{
	var referencia=document.getElementById("referencia").value
	/*Creamos un objeto AJAX*/
	var objAjax=new XMLHttpRequest;
	objAjax.open("GET","../controladores/controlador_deposito_softservi.php?accion=buscar_deposito&dep_referencia="+referencia);

	objAjax.onreadystatechange=function()
	{
		//alert("Status vale: "+objAjax.status+"  -  readystate vale: "+objAjax.readyState)
		//alert("readystate vale: "+objAjax.readyState)
		if(objAjax.status==200 && objAjax.readyState==4)
		{
			var respuesta=objAjax.responseText
			//alert("La respuesta es: "+respuesta)

			var a = respuesta.split('#');
			//alert(a[3]);
			document.getElementById('cedula').value=a[0];
			document.getElementById('estatus').value=a[1];
			var aux_fecha=a[2].substr(8,2)+"/"+a[2].substr(5,2)+"/"+a[2].substr(0,4);
			document.getElementById('fecha').value=aux_fecha;
			document.getElementById('monto').value=a[3];
			document.getElementById('montocheque').value=a[4];
		}
	}
	objAjax.send(null)
}
function mostrar_datos_estudiante()
{
	var est_ced=document.getElementById("est_ced").value
	/*Creamos un objeto AJAX*/
	var objAjax=new XMLHttpRequest;
	objAjax.open("GET","../controladores/controlador_estudiante.php?accion=buscar_estudiante&cedula="+est_ced);

	objAjax.onreadystatechange=function()
	{
	//	alert("Status vale: "+objAjax.status+"  -  readystate vale: "+objAjax.readyState)
	//	alert("readystate vale: "+objAjax.readyState)
		if(objAjax.status==200 && objAjax.readyState==4)
		{
			var respuesta=objAjax.responseText
			//alert("La respuesta es: "+respuesta)

			var a = respuesta.split('#');
			// alert(a[3]);
			if(a[0]=='No Encontrado')
			{
				alert("Estudiante NO existe");
				document.getElementById('nombre').value="Estudiante NO existe";
				//document.getElementById('est_ced').value="";
			}
			else
			{
				document.getElementById('nombre').value=a[0]+' '+a[1];
			}
		}
	}
	objAjax.send(null)
}

function validarSiNumero(numero){
	if (!/^([0-9])*$/.test(numero))
	alert("El valor " + numero + " no es un número");
}