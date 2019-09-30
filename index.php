<?php
session_start();
if(@$_GET["salir"]==1)
{
	session_destroy();
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Inicio de Sesión</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link type="text/css" rel="stylesheet"  href="css/estilos.css">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="bootstrap/css/alertify.min.css">
	<link rel="shortcut icon" href="imagenes/logoShor.png">

<style>
 
body {
  padding-top: 40px;
  padding-bottom: 40px;
  
}

span 
{
	color:   #ff8c00;
}

.formulario {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
 
}

/* Clase que tendra el tooltip */  
.cssToolTip {
 position: relative; /* Esta clase tiene que tener posicion relativa */
 color:   #ff8c00; /* Color del texto */
}
 
/* El tooltip */
.cssToolTip span {
 /*background: rgba(20,20,20,0.9) url('imagenes/cargando.gif') center left 5px no-repeat; */
 background: rgba(20,20,20,0.9) center left 5px no-repeat; 
 border: 2px solid #87cefa;
 border-radius: 5px;
 box-shadow: 5px 5px 5px #333;
 color: #87cefa;
 display: none; /* El tooltip por defecto estara oculto */
 font-size: 0.8em;
 padding: 10px 10px 10px 35px;
 max-width: 6000px;
 position: absolute; /* El tooltip se posiciona de forma absoluta para no modificar el aspezto del resto de la pagina */
 top: 15px; /* Posicion apartir de la parte superior del primer elemento padre con posicion relativa */
 left: 100px; /* Posicion apartir de la parte izquierda del primer elemento padre con posicion relativa */
 z-index: 100; /* Poner un z-index alto para que aparezca por encima del resto de elementos */
}
 
/* El tooltip cuando se muestra */
.cssToolTip:hover span {
 display: inline; /* Para mostrarlo simplemente usamos display block por ejemplo */
}

.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('imagenes/cargando.gif') 50% 50% no-repeat rgb(249,249,249);
    opacity: .8;
}

#sha{
	max-width: 340px;
    -webkit-box-shadow: 0px 0px 18px 0px rgba(48, 50, 50, 0.48);
    -moz-box-shadow:    0px 0px 18px 0px rgba(48, 50, 50, 0.48);
    box-shadow:         0px 0px 18px 0px rgba(48, 50, 50, 0.48);
    border-radius: 6%;
  }
 #avatar{
width: 96px;
height: 96px;
margin: 0px auto 10px;
display: block;
border-radius: 50%;
 } 

#divrecuperarclave{
	max-width: 340px;
    -webkit-box-shadow: 0px 0px 18px 0px rgba(48, 50, 50, 0.48);
    -moz-box-shadow:    0px 0px 18px 0px rgba(48, 50, 50, 0.48);
    box-shadow:         0px 0px 18px 0px rgba(48, 50, 50, 0.48);
    border-radius: 6%;
  }
 #avatar{
 
</style>

</head>

<body>

	<div class="container well" id="sha">
		<div class="row">
			<div class="col-xs-12">
				<img src="imagenes/avatar.png" class="ing-responsive" id="avatar">
			</div>
		</div>
		<form class="login" name="formulario" id="formulario" method="POST">
			<div class="form-group">
				<input type="email" name="usuario" id="usuario" class="form-control" placeholder="Correo Electronico" required autofocus>
			</div>
			<div class="form-group">
				<input type="password" name="clave" id="clave" class="form-control" placeholder="Contraseña" required>
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="iniciar" id="iniciar">iniciar sesión</button>
			<!--
			<div class="form-group" align="center">
				¿No tiene una cuenta?
				<a id="crearcuenta" href="pantallas/crear_usuario.php">Cree una</a>
			</div>
			-->
			<div class="form-group" align="center">
				<a id="recuperarclave" href="#">Recuperar Contraseña</a>
			</div>

		</form>
		<form class="login" name="formvcodval" id="formvcodval" method="POST" style="display:none;">
			<div class="form-group cssToolTip">
				<input type="password" name="codvalidacion" id="codvalidacion" class="form-control" placeholder="Código de Validación" required title="Este codigo va ser enviado a su correo Electrónico. Debe incluir el codigo para poder ingresar al Sistema">
				<!--<span> Este codigo va ser enviado a su correo Electrónico. Debe incluir el codigo para poder ingresar al Sistema </span> -->
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="btncodval" id="btncodval">Validar</button>
		</form>
	</div>

	<div class="container well" id="divrecuperarclave" style="display: none">
 		<form class="login" name="formrcontra" id="formrcontra" method="POST">
	    	<div class="head text-subheader" id="SignupPageTitle" role="heading"><h2>Recuperar Contraseña</h2></div>
			<div>
				Ingrese email ó Número de Cédula para recuperar la contraseña. Si los datos son validos se enviara a su correo la contraseña. <a id="inises" href="index.php">iniciar sesión</a>
			</div>
			<br>
			<div>
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control" placeholder="Correo Electronico" autofocus>
				</div>
				<!--
				<div class="form-group">
					<input type="text" name="cedula" id="cedula" class="form-control" placeholder="Num. de Cédula">
				</div>
				-->
				<button class="btn btn-lg btn-primary btn-block" type="button" name="c" id="rcontra">Recuperar Contraseña</button>

			</div>
		</form>
	</div>

<!--
	<div id="dialog_carga" title="Cargando..." style="display:none;" align="center">
	<img src="imagenes/cargando.gif" alt="q" width="50" height="50"></div>
-->

	<div id="dialog_carga" title="Cargando..."align="center">
	<div class="loader"></div></div>


	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/jquery.numeric.min.js"></script>
	<script src="bootstrap/js/alertify.js"></script>
	<script src="bootstrap/js/livevalidation.js"></script>
	<script type="text/javascript" src="bootstrap/js/jqGrid-4.5.4/js/grid.locale-es.js"></script>
	<script type="text/javascript" src="bootstrap/js/jqGrid-4.5.4/js/jquery.jqGrid.min.js"></script>
	<script src="bootstrap/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
	<script src="bootstrap/js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
	<script src="bootstrap/js/jquery.validate.js"></script> <!--Plugin para Validar Formularios -->
	<script src="bootstrap/js/messages_es.js"></script> <!--JS para cambiar mensaje de validación a español -->
	<script src="bootstrap/js/sessvars.js"></script>
	<script src="javascript/index.js" language="javascript" type="text/javascript"> </script>
	<script src="javascript/principal.js" language="javascript" type="text/javascript"> </script>

<!--
	<script src="bootstrap/js/jquery-3.1.1.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="bootstrap/js/alertify.js"></script>
	<script src="bootstrap/js/jquery.validate.js"></script>
	<script src="bootstrap/js/messages_es.js"></script>
	<script src="bootstrap/js/jquery.numeric.min.js"></script>
	<script src="javascript/index.js" language="javascript" type="text/javascript"> </script>
	<script src="javascript/principal.js" language="javascript" type="text/javascript"> </script>
-->
</body>
</html>