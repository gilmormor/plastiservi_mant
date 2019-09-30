<?php
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
//$objConexion=new conexion;
$conexion=Db::getInstance();/*instancia la clase*/
$objSeguridad=new seguridad;

//$conexion=$objConexion->conectar();

$operacion=0;

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$operacion);

$retorno=1;
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
	<link rel="stylesheet" href="../bootstrap/css/alertify.min.css">
	<link type="text/css" rel="Stylesheet" href="../bootstrap/css/jquery.qtip.min.css" />
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css"/>
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<div class="form-group">
		<div id="titulo" name="titulo" class="bg-primary text-center titulo text-uppercase">REGISTRAR DEPOSITOS</div>
	</div>	
	<input type="hidden" id="faltantesoft" name="faltantesoft" value="0.00">
	<input type="hidden" id="faltantecol" name="faltantecol" value="0.00">
	<input type="hidden" id="faltantepad" name="faltantepad" value="0.00">
	<div class="container-fluid" >
		<div class="col-xs-12 col-md-12">
			<div class="col-xs-12" id="fechadeposito" class="text-base">
			
			</div>
			<div class="col-xs-12" id="">
				<p>
				Debe ingresar el número de depósito correspondiente. El monto total en efectivo debe ser mayor o igual a <b>Total a depositar</b>. Si la suma de depósitos es menor al <b>Total a depositar</b> no podra entrar al Sistema. Debe Tomar en cuenta que si ud hace los depositos en cheque estos deben ser validados por el colegio. Verifique los montos.
				</p><br>
			</div>
		</div>
		<div class="col-xs-12 col-md-4" id="divsoft">
			<div class="col-xs-12">
				<div class="col-xs-12">
					<label class="text-base" for="depsoftservi" id="lblsoft">Softservi, C.A.:</label>
					<input type="text" id="depsoftservi" class="form-control" name="depsoftservi" placeholder="Ingrese Núm Depósito">
				</div>	
			</div>

			<div class="col-xs-12" id="vidtablasoft" id="vidtablasoft">
				<div class="col-xs-12">
					<table class="table table-striped table-bordered table-hover table-condensed" width="100%" id="tabladepsoft" name="tabladepsoft">
					</table>
				</div>
			</div>
			<div class="col-xs-12">
			<!--
				<div class="col-xs-12">
					<label class="text-base" id="lbltotaldepsoft"></label>
				</div>	
			-->
			 	<div class="col-xs-12">
			 		<button type="button" class="btn btn-primary col-xs-8 col-md-8 col-xs-offset-2 col-md-offset-2" id="btnsoft" name="btnsoft">Guardar Depósito</button>
			 	</div>
			</div>
	 
		</div>

		<div class="col-xs-12 col-md-4" id="divcol">
			<div class="col-xs-12">
				<div class="col-xs-12">
					<label class="text-base" for="depcol" id="lblcol">Colegio:</label>
					<input type="text" id="depcol" class="form-control" name="depcol" placeholder="Ingrese Núm Depósito">
				</div>	
			</div>

			<div class="col-xs-12" id="vidtablacol" name="vidtablacol">
				<div class="col-xs-12">
					<table class="table table-striped table-bordered table-hover table-condensed" width="100%" id="tabladepcol" name="tabladepcol">
					</table>
				</div>
			</div>
			<div class="col-xs-12">
			<!--
				<div class="col-xs-12">
					<label class="text-base" id="lbltotaldepcol"></label>
				</div>
			-->
			 	<div class="form-group col-xs-12">
			 		<button type="button" class="btn btn-primary col-xs-8 col-md-8 col-xs-offset-2 col-md-offset-2" id="btncol" name="btncol">Guardar Depósito</button>
			 	</div>
			</div>

		</div>
		<div class="form-group col-xs-12 col-md-4" id="divpad">
			<div class="col-xs-12">
				<div class="col-xs-12">
					<label class="text-base" for="deppad" id="lblpad">Consejo de Padres:</label>
					<input type="text" id="deppad" class="form-control" name="deppad" placeholder="Ingrese Núm Depósito">
				</div>	
			</div>

			<div class="col-xs-12" id="vidtablapad" name="vidtablapad">
				<div class="col-xs-12">
					<table class="table table-striped table-bordered table-hover table-condensed" width="100%" id="tabladeppad" name="tabladeppad">
					</table>
				</div>
			</div>
			<div class="col-xs-12">
			<!--
				<div class="col-xs-12">
					<label class="text-base" id="lbltotaldeppad"></label>
				</div>
			-->
			 	<div class="form-group col-xs-12">
			 		<button type="button" class="btn btn-primary col-xs-8 col-md-8 col-xs-offset-2 col-md-offset-2" id="btnpad" name="btnpad">Guardar Depósito</button>
			 	</div>
			</div>
		</div>
		<div class="container">
			<legend></legend>
		</div>
	 	<div class="form-group col-xs-12">
	 		<button type="button" class="btn btn-primary col-xs-12 col-md-12" id="btnsiguiente" name="btnpad">Siguiente</button>
	 	</div>

	</div>


	<div id="dialog_carga" title="Cargando..." style="display:none;" align="center">
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
	<script src="../bootstrap/js/sessvars.js"></script>
	<script src="../javascript/depositos_registrar.js" language="javascript" type="text/javascript"> </script>
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
