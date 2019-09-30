<?php
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
$objConexion=new conexion;
$objSeguridad=new seguridad;

$conexion=$objConexion->conectar();

$operacion=26;

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$operacion);

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
    <link type="text/css" rel="Stylesheet" href="../bootstrap/css/jquery.qtip.min.css" />
    <link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
    <link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
    <link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>

	<link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<form method="POST">
		<input type="hidden" name="accion" value="insertar">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase">Convenio SOFITASA</div>
			</div>	

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="referencia">Número de Cedula:</label>
				</div>

				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="est_ced" id="est_ced" placeholder="Cedula" maxlength="8" required autofocus>
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="cedula">Nombre Estudiante:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="nombre" id="nombre" maxlength="100" readonly>
				</div>
			</div>
			<div class="form-group text-center separador-md">
				<div class="bg-default text-center">
					<button type="button" id="guardar" name="guardar" class="btn btn-primary">Guardar</button>
				</div>
<!--
				<div class="bg-default">
					<input type="submit" value="Guardar" class="bt btn-primary">
				</div>
-->
			</div>

			
		</div>
	</form>
	<div id="dialog_carga" title="Cargando..." style="display:none;" align="center">
	<img src="../imagenes/cargando.gif" alt="q" width="50" height="50"></div>

	<script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../bootstrap/js/jquery.js"></script>
    <script src="../bootstrap/js/bootstrap-datepicker.js"></script>
    <script src="../bootstrap/js/jquery.numeric.min.js"></script>
    <script src="../bootstrap/js/alertify.js"></script>
    <script src="../bootstrap/js/livevalidation.js"></script>
    <script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/grid.locale-es.js"></script>
    <script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/jquery.jqGrid.min.js"></script>
    <script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
    <script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>

	<script src="../javascript/agregar_conveniosofitasa.js" language="javascript" type="text/javascript"> </script>
	<script src="../javascript/principal.js" language="javascript" type="text/javascript"> </script>
	
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