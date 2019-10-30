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
if ($retorno==1)
{

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Asignar Trabajos a Mecánicos</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap.min.css">
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-select.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/alertify.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-datepicker.min.css"/>
	<link rel="stylesheet" href="../css/estilos_nivel2.css">

</head>
<body>
<!--	<form action="../controladores/controlador_deposito.php" method="POST"> -->
<!--	<form method="POST">-->
		<input type="hidden" name="accion" value="modificar">
		<div class="container">
			<div class="form-group separador-md" style="margin-bottom: 1px;margin-top: 0px;margin-left: -15px;margin-right: -15px;">
				<div class="bg-primary text-center titulo text-uppercase">Orden de Trabajo</div><button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ocultarMostrarFiltro()">
	          <span aria-hidden="true" class='glyphicon glyphicon-chevron-up rotateMe' id="botonD" name="botonD" style="top: -4px;" title="Ocultar Filtros" data-toggle='tooltip'></span>
	        </button>
			</div>
		</div>

		<div class="container" id="divFiltros" name="divFiltros">
			<div class="row">
				<div class="col-xs-12 col-md-4 col-sm-4">
					<div class="col-xs-12 col-md-3 col-sm-3 text-right" style="padding-left: 0px;padding-right: 0px;">
						<label for="fecha">Fecha Desde:</label>
					</div>
					<div class="col-xs-12 col-md-9 col-sm-9">
						<input type="text" bsDaterangepicker class="form-control datepicker" name="fechad" id="fechad" placeholder="DD/MM/AAAA" required readonly="">
					</div>
				</div>

				<div class="col-xs-12 col-md-4 col-sm-4">
					<div class="col-xs-12 col-md-3 col-sm-3 text-right" style="padding-left: 0px;padding-right: 0px;">
						<label for="dep_fecha">Fecha Hasta:</label>
					</div>
					<div class="col-xs-12 col-md-9 col-sm-9">
						<input type="text" class="form-control datepicker" name="fechah" id="fechah" placeholder="DD/MM/AAAA" required readonly="">
					</div>
				</div>
				<div class="col-xs-12 col-md-4 col-sm-4">
					<div class="col-xs-12 col-md-3 col-sm-3 text-right" style="padding-left: 0px;padding-right: 0px;">
						<label for="personaID">Mecánico:</label>
					</div>
					<div class="col-xs-12 col-md-9 col-sm-9">
						<?php $usuarioID = $_SESSION["usuarioID"];
						$objUtilidades->hacer_lista_desplegableB41($conexion,$tabla="vistapersonamant",$value="personaID",$mostrar="nombre",$nombre="personaID",$sql="select * from vistapersonamant;",$funcion=""); ?>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col-xs-12 col-md-4 col-sm-4">
					<div class="col-xs-12 col-md-3 col-sm-3 text-right" style="padding-left: 0px;padding-right: 0px;">
						<label for="departamentoAreaID" type="text" data-toggle="tooltip" data-placement="top" title="Seleccione una Opción">Area:</label>
					</div>
					<div class="col-xs-12 col-md-9 col-sm-9">
						<?php $usuarioID = $_SESSION["usuarioID"];
						$objUtilidades->hacer_lista_desplegableMultiple($conexion,$tabla="vistadepartamentos",$value="departamentoAreaID",$mostrar="nombreDpto",$nombre="departamentoAreaID",$sql="select *,CONCAT(nombreDpto,' - ',nombreArea) AS areadpto from vistadepartamentos where statusMant;",$funcion="",$subtext="nombreArea"); ?>
					</div>
				</div>
				<div class="col-xs-12 col-md-4 col-sm-4">
					<div class="col-xs-12 col-md-3 col-sm-3 text-right" style="padding-left: 0px;padding-right: 0px;">
						<label for="staTrabajo" class="col-form-label">Trabajo:</label>
					</div>
					<div class="col-xs-12 col-md-9 col-sm-9">
						<select  class="selectpicker show-tick form-control" id="staTrabajo">
							<option value="1">Todos</option>
							<option value="2">Por asignar</option>
							<option value="3">En ejecución</option>
							<option value="4">Terminados sin validar</option>
							<option value="5">Terminados y Validados</option>
							<option value="6">Rechazados</option>
						</select>
					</div>
					<!--
						<button id="trabTodos" class="btn btn-primary btn-sm" title='Ver todos los registros' data-toggle='tooltip'>Todos</button>
						<button id="trabEjecu" class="btn btn-primary btn-sm" title='Filtar Trabajos en ejecución' data-toggle='tooltip'>Ejecución</button>		 		
						<button id="trabAsign" class="btn btn-primary btn-sm" title='Filtrar Trabajos por asignar' data-toggle='tooltip'>Asignar</button>		 		
					-->
				</div>
				<div class="col-xs-12 col-md-4 col-sm-4">
					<button type="button" class="btn btn-primary text-center" id="btnConsultar" name="btnConsultar"  data-toggle='tooltip' title="Consultar">Consultar</button>
				</div>			
			</div>
			<div class="row">
				<div>
					<legend></legend>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<div class="row" id="tablaMaquinas">
					</div>			
				</div>
			</div>
		</div>


	<div id="dialog_carga" name="dialog_carga" title="Cargando..."align="center">
		<div class="loader"></div>
	</div>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="../bootstrap_iugc/js/bootstrap.min.js"></script>
	<script src="../bootstrap_iugc/js/bootstrap-select.js"></script>
	<script src="../bootstrap_iugc/js/jquery.dataTables.min.js"></script>
	<script src="../bootstrap_iugc/js/dataTables.bootstrap.min.js"></script>
	<script src="../bootstrap_iugc/js/alertify.js"></script>
	<script src="../bootstrap_iugc/js/jquery.numeric.min.js"></script>
	<script src="../bootstrap_iugc/js/bootstrap-datepicker.min.js"></script>
	<script src="../bootstrap_iugc/js/bootstrap-datepicker.es.min.js"></script>
	<script src="../bootstrap/js/sessvars.js"></script>

	<script src="../javascript/validaciones.js" language="javascript" type="text/javascript"> </script>
	<script src="../javascript/consultaOT.js" language="javascript" type="text/javascript"> </script>
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