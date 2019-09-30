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
	<title>Document</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<form action="../controladores/controlador_modulo.php" method="POST">
		<input type="hidden" name="acciones" value="agregar_modulos">
		<div class="container">
			<div class="form-group">
				<div class="bg-primary text-center titulo text-uppercase">Agregar Modulo</div>
			</div>	
			
			<div class="form-group">
				<div class="col-md-3">
					<label for="nom_mod">Nombre:</label>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control" name="nom_mod" id="nom_mod" placeholder="Nombre del Modulo" maxlength="30">
				</div>

			</div>
			<div class="form-group">
				<div class="bg-default text-center">
					<input type="submit" value="Guardar Modulo" class="btn btn-primary">
				</div>
			</div>
		</div>
	</form>	


	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../bootstrap/js/jquery-3.1.1.min.js"></script>
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