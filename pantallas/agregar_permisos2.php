<?php
require_once("../clases/conexion.class.php");
require_once("../clases/operaciones.class.php");
require_once("../clases/utilidades.class.php");
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

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Agregar Permisos</title>
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<script src="../javascript/validaciones.js" language="javascript" type="text/javascript"> </script>
</head>

<body>
<form method="POST" action="../controladores/controlador_operaciones.php">
<input type="hidden" value="asignar_permisos" name="accion">
<table class="table table-striped table-bordered table-hover table-condensed" width="60%" align="center">

<tr>
<td colspan="2" align="center" class="bg-primary">Agregar Permisos</td>
</tr>

<tr>
<td width="40%">Usuario:</td>
<td>
	<?php $objUtilidades->hacer_lista_desplegable($conexion,$tabla="usuario",$value="ema_usu",$mostrar="nom_usu",$nombre="fk_usuario",$sql="select * from usuario where usuario.fk_tip_usu!=4",$funcion="pintar_operaciones()"); ?>
	</td>
</tr>

</table>

<div class="cargando"> </div>

<div id="zona_operaciones"> </div>

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
