<?php
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
require_once("../clases/opciones.class.php");
$objUtilidades=new utilidades;
$conexion=Db::getInstance();/*instancia la clase*/
$objSeguridad=new seguridad;
$objOpcion = new opciones;

$nombre_script=$objUtilidades->ruta_script(); //Averigua el nombre del script que se está ejecutando
//echo $nombre_script;
$num_opc = $objOpcion->devolver_cod_opc($conexion,$nombre_script); //Devuelve el código de la opción asociada al script

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$num_opc);

if ($retorno==1)
{

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Deposito</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap/css/jquery-ui.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.min.css"/>
    <link rel="stylesheet" href="../bootstrap/css/alertify.min.css">
    <link rel="stylesheet" href="../bootstrap/css/dataTables.bootstrap.min.css">


	<link rel="stylesheet" href="../css/estilos_nivel2.css">


</head>
<body>
<!--	<form action="../controladores/controlador_deposito.php" method="POST"> -->
	<form method="POST">
		<input type="hidden" name="accion" value="modificar">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase">Lista Negra</div>
			</div>	


			<div class="row" id="tablaListaNegra">
			</div>
		</div>
	</form>

<!--
	<div id="dialog_carga" title="Cargando..."align="center">
	<div class="loader"></div></div>
-->

	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../bootstrap/js/jquery-3.3.1.min.js"></script>
	<script src="../bootstrap/js/jquery.numeric.min.js"></script>
	<script src="../bootstrap/js/alertify.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jquery-ui.js"></script>
	<script src="../bootstrap/js/bootstrap-datepicker.min.js"></script>
	<script src="../bootstrap/js/bootstrap-datepicker.es.min.js"></script>
	<script src="../bootstrap/js/jquery.dataTables.min.js"></script>
	<script src="../bootstrap/js/dataTables.bootstrap.min.js"></script>

	<script src="../javascript/validaciones.js" language="javascript" type="text/javascript"> </script>
	<script src="../javascript/consulta_listanegra.js" language="javascript" type="text/javascript"> </script>
	<!--
	<script src="../javascript/principal.js" language="javascript" type="text/javascript"> </script>
	-->

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