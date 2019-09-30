<?php
require_once("../clases/estudiante.class.php");
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");

$objestudiante=new estudiante();	
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
//$objConexion=new conexion;
$conexion=Db::getInstance();/*instancia la clase*/
$objSeguridad=new seguridad;

//$conexion=$objConexion->conectar();

$operacion=18;

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$operacion);
//$retorno = 1;
if($retorno==1)
{


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Datos del Estudiante</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../bootstrap/css/alertify.min.css">
	<link type="text/css" rel="Stylesheet" href="../bootstrap/css/jquery.qtip.min.css" />
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css"/>
	<!--<link rel="stylesheet" href="../bootstrap/datepicker/css/bootstrap-datepicker.css"/>-->
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>

	<div class="container-fluid" >
		<div class="form-group separador-md col-xs-12 col-md-12">
			<div class="bg-primary text-center titulo text-uppercase">Reporte de Inscritos</div>
		</div>	
	</div>
	<div class="container">
		<legend></legend>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form">
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="insc_codlapso" type="text" class="form-group">Año Escolar:</label>
								<?php $objUtilidades->hacer_lista_desplegable($conexion,$tabla="insc_codlapso",$value="insc_codlapso",$mostrar="insc_codlapso",$nombre="insc_codlapso",$sql="select insc_codlapso from eva_inscripciones where insc_codlapso!='' group by insc_codlapso",$funcion="validacion('insc_codlapso','texto','col-xs-12 col-sm-3')"); ?>
								<span class="help-block"></span>
							</div>
						</div>
					</div>	
				</form>		
			</div>		
		</div>
	</div>

	<div class="container">
		<legend></legend>
	</div>

 	<div class="col-xs-12">
 		<button type="button" class="btn btn-primary col-xs-4 col-md-4 col-xs-offset-4 col-md-offset-4" id="btngenerar" name="btngenerar" title="Generar Reporte - Imprimir">Generar</button>
 	</div>

 	<div class="col-xs-12">
 		<button type="button" class="btn btn-primary col-xs-4 col-md-4 col-xs-offset-4 col-md-offset-4" id="btnusuarios" name="btnusuarios" title="Listado de Usuarios">Usuarios</button>
 	</div>

	<div id="inscp" class="col-xs-12">
	</div>


	<div id="dialog_cargar" title="Cargando..." style="display:none;" align="center">
	<img src="../imagenes/cargando.gif" alt="q" width="50" height="50"></div>


	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../bootstrap/js/jquery.js"></script>
	<script src="../bootstrap/js/jquery.numeric.min.js"></script>
	<script src="../bootstrap/js/alertify.js"></script>
	<script src="../bootstrap/js/livevalidation.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/grid.locale-es.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/jquery.jqGrid.min.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
	<script src="../bootstrap/js/jquery.validate.js"></script> <!--Plugin para Validar Formularios -->
	<script src="../bootstrap/js/messages_es.js"></script> <!--JS para cambiar mensaje de validación a español -->
	<script src="../bootstrap/js/bootstrap-datepicker.js"></script>
<!--	<script src="../bootstrap/datepicker/js/bootstrap-datepicker.js"></script> -->
	<script src="../bootstrap/js/sessvars.js"></script>
	<script src="../javascript/reporte_inscritos.js" language="javascript" type="text/javascript"> </script>
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
