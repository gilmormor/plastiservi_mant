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
	<title>Agregar Operaciones</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<form action="../controladores/controlador_opciones.php" method="POST">
		<input type="hidden" name="acciones" value="agregar_opcion">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase">Agregar Operaciones</div>
			</div>	

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="nom_ope">Nombre de Operacion:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="nom_ope" id="nom_ope" placeholder="Nombre de Operacion" maxlength="30">
				</div>
			</div>


			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="url">URL:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="url" id="url" placeholder="Ingrese el URL del Modulo" maxlength="50">
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="modulo">Modulo:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<?php
						$objUtilidades->hacer_lista_desplegable($conexion,"modulo","id_mod","nom_mod","fk_mod","","");
					?>
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="estado">Estado</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="radio" name="est_ope" id="est_ope1" value="A" checked>Activo
					<input type="radio" name="est_ope" id="est_ope2" value="I">Inactivo
				</div>
			</div>
			
			<div class="form-group text-center separador-md">
				<div class="bg-default">
					<input type="submit" value="Guardar" class="btn btn-primary">
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