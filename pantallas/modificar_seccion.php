<?php
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
$objConexion=new conexion;
$objSeguridad=new seguridad;

$conexion=$objConexion->conectar();

$operacion=33;

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
	<form id="rechazos" method="POST">
		<input type="hidden" name="accion" value="insertar">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase">MODIFICAR SECCION</div>
			</div>	
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="masec_codsec">Sección:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<?php $objUtilidades->hacer_lista_desplegable($conexion,$tabla="vista_seccionesagrupadas",$value="masec_codsec",$mostrar="masec_codsec",$nombre="masec_codsec",""); ?>
				</div>
			</div>
		</div>
	</form>
	<!--<table id="secciones"></table> Esto es tara usar con el jqgrid que necesita una tabla-->
	<!--<div id="zona_cargando" align="center" height:"50px" width:"50px" background-size:"contain"></div> -->
	<!--<div id="zona_cargando" align="center"><p><img src="../imagenes/cargando.gif" /></p></div> -->

	<div id="dialog_carga" title="Cargando..." style="display:none;" align="center">
	<img src="../imagenes/cargando.gif" alt="q" width="50" height="50"></div>

	<div id="zona_secciones"> </div>
	<input type="hidden" id="nroreg" name="nroreg" value="222">
	<div class="bg-default text-center">
		<button type="button" id="guardar" name="guardar" class="btn btn-primary" style="display:none;">Guardar</button>
	</div>

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
	<script src="../javascript/modificar_seccion.js" language="javascript" type="text/javascript"> </script>
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