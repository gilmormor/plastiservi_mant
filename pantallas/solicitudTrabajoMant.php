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

$retorno = $objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$num_opc);
$nom_ope = $objOpcion->devolver_desc_opc($conexion,$num_opc); //Nombre en el Menú
$usuarioID = $_SESSION["usuarioID"];
//echo $num_opc;
//$retorno = 1;
if($retorno==1)
{


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Chequeo Diario de Equipos</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

<!--
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
	<link rel="stylesheet" href="../bootstrap4.3.1/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css"/>
	<link rel="stylesheet" href="../bootstrap/css/alertify.min.css">
	<link type="text/css" rel="Stylesheet" href="../bootstrap/css/jquery.qtip.min.css" />
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
-->
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap.min.css">
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-select.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/alertify.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-datepicker.min.css"/>
	<link rel="stylesheet" href="../css/estilos_nivel2.css">

</head>
<body>
	<input type="hidden" name="numReg" id="numReg">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div class="form-group col-xs-12 col-sm-12">
					<div class="bg-primary text-center titulo text-uppercase"><?php echo $nom_ope; ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div class="form-group col-xs-12 col-sm-4">
					<button type="button" id="btnAgregar" name="btnAgregar" class="btn btn-primary" title='Agregar Solicitud de Mantención' data-placement="right" data-toggle="tooltip">Agregar</button>
				</div>
			</div>
		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div class="form-group col-xs-12 col-sm-12" id="tablaST">
				</div>
			</div>
		</div>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h3 class="modal-title" id="exampleModalLongTitle" name="exampleModalLongTitle">Agregar Solicitud</h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
				<form>
					<input type="hidden" name="solicitudTrabIDM" id="solicitudTrabIDM">
					<input type="hidden" name="filaST" id="filaST">
					<input type="hidden" name="usuarioIDM" id="usuarioIDM">
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<div class="form-group col-xs-12 col-sm-12">
								<label for="MaqIDDptoAreaID" class="col-form-label">Equipo:</label>
								<?php 
								$objUtilidades->hacer_lista_desplegableBusB41S($conexion,$tabla="vistamaquinasporusuario",$value="MaqIDDptoAreaID",$mostrar="codinternoNombreMaq",$nombre="MaqIDDptoAreaID",$sql="select * FROM vistamaquinasporusuario WHERE usuarioID='$usuarioID';",$funcion="");
								?>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group col-xs-12 col-sm-12">
								<label for="prioridadM" class="col-form-label">Prioridad:</label>
								<select  class='selectpicker show-tick form-control' id='prioridadM' name='prioridadM' title='Seleccione...'>
									<option value='1'>Emergencia</option>
									<option value='2'>Urgente</option>
									<option value='3'>Normal</option>
								</select>
								<span class="help-block"></span>
							</div>
						</div>

					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="form-group col-xs-12 col-sm-12">
								<label for="descripcionM" class="col-form-label">Descripción del trabajo:</label>
								<textarea class="form-control" id="descripcionM"></textarea>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
				</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar" data-toggle='tooltip'>Cerrar</button>
	        <button type="button" class="btn btn-primary" id="btnGuardar" name="btnGuardar" title="Guardar" data-toggle="tooltip">Guardar</button>
	      </div>
	    </div>
	  </div>
	</div>


	<div id="dialog_carga" title="Cargando..."align="center">
		<div class="loader"></div>
	</div>
<!--
	<script src="../bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="../bootstrap/js/jquery.js"></script>
	<script src="../bootstrap4.3.1/js/bootstrap.min.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
	<script src="../bootstrap/js/alertify.js"></script>
	<script src="../bootstrap/js/sessvars.js"></script>
-->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
	<script src="../bootstrap_iugc/js/bootstrap.min.js"></script>
	<script src="../bootstrap_iugc/js/bootstrap-select.js"></script>
	<script src="../bootstrap_iugc/js/jquery.dataTables.min.js"></script>
	<script src="../bootstrap_iugc/js/dataTables.bootstrap.min.js"></script>
	<script src="../bootstrap_iugc/js/alertify.js"></script>
	<script src="../bootstrap_iugc/js/jquery.numeric.min.js"></script>
	<script src="../bootstrap_iugc/js/bootstrap-datepicker.min.js"></script>
	<script src="../bootstrap_iugc/js/bootstrap-datepicker.es.min.js"></script>
	<script src="../bootstrap/js/sessvars.js"></script>


	<script src="../javascript/solicitudTrabajoMant.js" language="javascript" type="text/javascript"> </script>
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
