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
	<style type="text/css">
		.custom-radio, .custom-checkbox {
		    clip: rect(1px 1px 1px 1px);
		    clip: rect(1px, 1px, 1px, 1px);
		    position: absolute;
		}

		/*
		 * Dejar espacio a la 'label' para posicionar el checkbox hecho con pseudoelementos
		 */
		.custom-radio + label, .custom-checkbox + label {
		  position: relative;
		  padding-left: 16px;
		}
		/*
		 * El pseudoelemento que emulará el input
		 */
		.custom-radio + label:before, .custom-checkbox + label:before {
		    content: "";
		    display: inline-block;
		    -moz-box-sizing: border-box;
		    -webkit-box-sizing: border-box;
		    box-sizing: border-box;
		    font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
		    font-weight: bold;
		    font-size: 18px;
		    width: 20px;
		    height: 20px;
		    line-height: 11px;
		    text-align: center;
		    position: absolute;
		    left: 0;
		    top: 50%;
		    margin-top: -4.5px;
		    background: white;
		    background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #ffffff), color-stop(100%, #dddddd));
		    background-image: -webkit-linear-gradient(#ffffff, #dddddd);
		    background-image: -moz-linear-gradient(#ffffff, #dddddd);
		    background-image: -o-linear-gradient(#ffffff, #dddddd);
		    background-image: linear-gradient(#ffffff, #dddddd);
		    zoom: 1;
		    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#dddddd);
		    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#dddddd')";
		    -webkit-border-radius: 3px;
		    -moz-border-radius: 3px;
		    -ms-border-radius: 3px;
		    -o-border-radius: 3px;
		    border-radius: 3px;
		    border: 1px solid #aaa;
		}
		/*
		 * Fondo para cuando se pasa el ratón por encima
		 */
		.custom-radio + label:hover:before, .custom-checkbox + label:hover:before {
		    background: #fafafa;
		}

		/*
		 * Fondo para cuando se está haciendo click
		 * Con filtros para ie9
		 */
		.custom-radio + label:active:before, .custom-checkbox + label:active:before {
		    background: #f2f2f2;
		    background-image: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #dddddd), color-stop(100%, #ffffff));
		    background-image: -webkit-linear-gradient(#dddddd, #ffffff);
		    background-image: -moz-linear-gradient(#dddddd, #ffffff);
		    background-image: -o-linear-gradient(#dddddd, #ffffff);
		    background-image: linear-gradient(#dddddd, #ffffff);
		    zoom: 1;
		    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#dddddd, endColorstr=#ffffff);
		    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#dddddd', endColorstr='#ffffff')";
		}

		/*
		 * Redondear el botón "radio"
		 * Sobreescribimos el border-radius: 3px general
		 */
		.custom-radio + label:before {
		    -webkit-border-radius: 50%;
		    -moz-border-radius: 50%;
		    -ms-border-radius: 50%;
		    -o-border-radius: 50%;
		    border-radius: 50%;
		}
		/*
		 * Mostrar un punto cuando está seleccionado el "radio"
		 * Usamos box-shadow para simular un fondo gris, mientras que dejamos un pequeño 
		 * espacio para el punto negro (#444), que es el fondo
		 */
		.custom-radio:checked + label:before {
		    background: #444;
		    -webkit-box-shadow: 0 0 0 3px #eeeeee inset;
		    -moz-box-shadow: 0 0 0 3px #eeeeee inset;
		    box-shadow: 0 0 0 3px #eeeeee inset;
		}

		/*
		 * Estilos focus para la gente que navega con el teclado, etc
		 */
		.custom-radio:focus + label:before,
		.custom-checkbox:focus + label:before {
		    outline: 1px dotted;
		}

		/* Mostrar la "X" cuando está chequeada (sólo el checkbox).
		 * Podríamos usar una fuente de iconos para mostrar un tic
		 */
		.custom-checkbox:checked + label:before {
		    content: "X";
		    padding-top: 4.5px;
		}

		/*
		 * Sólo para IE 6, 7 y 8 (no soportado)
		 */
		@media \0screen\,screen\9 {
		    .custom-radio,
		    .custom-checkbox {
		        clip: auto;
		        position: static;
		    }

		    .custom-radio + label,
		    .custom-checkbox + label {
		        padding-left: 0;
		    }

		    .custom-radio + label:before,
		    .custom-checkbox + label:before {
		        display: none;
		    }
		}
	</style>
</head>
<body>
	<input type="hidden" name="numReg" id="numReg">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div class="col-xs-12 col-sm-12">
					<div class="bg-primary text-center titulo text-uppercase">Chequeo Diario de Equipos</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12" style="padding-bottom: 2px;padding-top: 2px;">
				<div class="col-xs-1 col-sm-1">
					<label for="departamentoAreaID" type="text" data-toggle="tooltip" data-placement="top" title="Seleccione una Opción">Area:</label>
				</div>
				<div class="col-xs-12 col-sm-6">
					<?php $objUtilidades->hacer_lista_desplegableB41($conexion,$tabla="vistadepartamentos",$value="departamentoAreaID",$mostrar="nombreDpto",$nombre="departamentoAreaID",$sql="select * from vistadepartamentos where statusMant;",$funcion="llenarMaquinas()"); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div class="col-xs-12 col-sm-12">
					<table class="table table-striped" width="100%" id="tablaMaquinas" name="tablaMaquinas">
					</table>
				</div>
				<div class="col-xs-12 text-center" id="divGuardar" name="divGuardar">
			 		
				</div>
			</div>		
		</div>
	</div>


	<!-- Modal -->
	<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h3 class="modal-title" id="exampleModalLongTitle" name="exampleModalLongTitle">Solicitud Trabajo</h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
				<form>
					<input type="hidden" name="solicitudTrabID" id="solicitudTrabID">
					<input type="hidden" name="filaST" id="filaST">
					<input type="hidden" name="usuarioIDM" id="usuarioIDM">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="form-group col-xs-12 col-sm-6">
								<label for="nomape_usuM" class="col-form-label">Nombre:</label>
								<input type="text" class="form-control" id="nomape_usuM" disabled>
								<span class="help-block"></span>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label for="ema_usuM" class="col-form-label">Email:</label>
								<input type="text" class="form-control" id="ema_usuM" disabled>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="form-group col-xs-12 col-sm-12">
								<label for="descripTrabajo" class="col-form-label">Descripción del trabajo:</label>
								<textarea class="form-control" id="descripTrabajo"></textarea>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
				</form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar" data-toggle='tooltip'>Cerrar</button>
	        <button type="button" class="btn btn-primary" id="btnGuardarM" name="btnGuardarM" title="Guardar" data-toggle="tooltip">Guardar</button>
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


	<script src="../javascript/chequeoDiarioEquipos.js" language="javascript" type="text/javascript"> </script>
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
