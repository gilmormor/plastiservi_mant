<?php
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
require_once("../clases/opciones.class.php");
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
//$objConexion=new conexion;
$conexion=Db::getInstance();/*instancia la clase*/

$objSeguridad=new seguridad;
$objOpcion = new opciones;

//$conexion=$objConexion->conectar();

$nombre_script=$objUtilidades->ruta_script(); //Averigua el nombre del script que se está ejecutando
//echo $nombre_script;
$num_opc = $objOpcion->devolver_cod_opc($conexion,$nombre_script); //Devuelve el código de la opción asociada al script

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$num_opc);
//$retorno = 1;
if($retorno==1)
{


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Deposito</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css"/>
    <link rel="stylesheet" href="../bootstrap/css/alertify.min.css">
    <link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<form id="rechazos" method="POST">
		<input type="hidden" name="accion" value="insertar">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase">CAMBIAR CLAVE</div>
			</div>	
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="cla_usu">Clave Actual:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="password" class="form-control" name="cla_usu" id="cla_usu" placeholder="Clave Anterior" maxlength="32" required>
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="cla_usu1">Nueva Clave:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="password" class="form-control" name="cla_usu1" id="cla_usu1" placeholder="Nueva Clave" maxlength="32" required>
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="cla_usu2">Confirmar Clave:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="password" class="form-control" name="cla_usu2" id="cla_usu2" placeholder="Repetir Clave" maxlength="32" required>
				</div>
			</div>
			<div class="form-group text-center separador-md">
				<div class="bg-default">
					<button type="button" id="cambiar" class="btn btn-primary">Cambiar</button>
				</div>
			</div>

		</div>
		<div id="zona_materias"> </div>
	</form>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../bootstrap/js/jquery.js"></script>
    <script src="../bootstrap/js/bootstrap-datepicker.js"></script>
    <script src="../bootstrap/js/jquery.numeric.min.js"></script>
    <script src="../bootstrap/js/alertify.js"></script>
    <script src="../bootstrap/js/livevalidation.js"></script>
    <script src="../javascript/cambiar_claveusuario.js" language="javascript" type="text/javascript"> </script>
</body>
</html>
<?php
}else
{
	echo "<script>
			alert('El usuario no tiene privilegios para acceder a esta pagina');
			location.replace('../index.php');
			</script>";
}
?>