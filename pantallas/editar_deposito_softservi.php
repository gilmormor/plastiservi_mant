<?php
require_once("../clases/utilidades.class.php");
//require("../clases/conexion_softservi.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
$objConexion=new conexion;
//$objConexionsoftservi=new conexion_softservi;
$objSeguridad=new seguridad;

$conexion=$objConexion->conectar();

$operacion=28;

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
    <link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<form action="../controladores/controlador_deposito_softservi.php" method="POST">
		<input type="hidden" name="accion" value="modificar">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase">Modificar Deposito SOFTSERVI</div>
			</div>	

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="referencia">N&uacute;mero de Deposito:</label>
				</div>

				<div class="col-md-9 col-sm-9">
					<!--
					<input type="text" class="form-control" name="referencia" id="referencia" placeholder="Referencia" maxlength="10" required="" onChange="mostrar_datos_deposito_softservi()">
					-->
					<input type="text" class="form-control" name="referencia" id="referencia" placeholder="Referencia" maxlength="10" required>
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="cedula">Cedula:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="cedula" id="cedula" placeholder="Cedula" maxlength="8" readonly>
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="estatus">Estatus:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="estatus" id="estatus" placeholder="Estatus" maxlength="1" readonly>
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="fecha">Fecha:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="date" class="form-control" name="fecha" id="fecha" placeholder="DD/MM/AAAA" required readonly>
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="monto">Monto en Efectivo:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="monto" id="monto" placeholder="Monto" maxlength="13" required>
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="monto">Monto en Cheque:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="montocheque" id="montocheque" placeholder="Monto en cheque" maxlength="13" required readonly>
				</div>
			</div>
			
			<div class="form-group text-center separador-md">
				<div class="bg-default">
					<input type="submit" id="guardar" value="Guardar" class="bt btn-primary">
				</div>
			</div>

			
		</div>
	</form>

	<script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../bootstrap/js/jquery.js"></script>
    <script src="../bootstrap/js/bootstrap-datepicker.js"></script>
    <script src="../bootstrap/js/jquery.numeric.min.js"></script>
    <script src="../bootstrap/js/alertify.js"></script>
    <script src="../javascript/validaciones.js" language="javascript" type="text/javascript"> </script>
    <script src="../javascript/editar_deposito_softservi.js" language="javascript" type="text/javascript"> </script>
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