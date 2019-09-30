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

$operacion=7;

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$operacion);

if ($retorno==1)
{

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
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
	<form action="../controladores/controlador_deposito.php" method="POST">
		<input type="hidden" id="accion" name="accion" value="insertar">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase">Agregar Depositos o Punto de Venta</div>
			</div>	

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="dep_cedula">Número de Cedula:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="dep_cedula" id="dep_cedula" placeholder="Número de Cédula" maxlength="11" required pattern="[0-9]{1,11}">
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="nombre">Nombre Estudiante:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre Estudiante" readonly>
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="mfor_cod">Forma de Pago:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<?php $objUtilidades->hacer_lista_desplegable($conexion,$tabla="vista_formapagoact",$value="mfor_cod",$mostrar="mfor_desc",$nombre="mfor_cod",$sql="",$funcion=""); ?>
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="fid_banco">Banco:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<?php $objUtilidades->hacer_lista_desplegable($conexion,$tabla="eva_bancos",$value="fid",$mostrar="ban_descrip",$nombre="fid_banco",$sql="",$funcion=""); ?>
				</div>
			</div>

			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="dep_referencia">Número de Depósito:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="dep_referencia" id="dep_referencia" placeholder="Numero de Deposito" maxlength="10" required pattern="[0-9]{0,10}">
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="dep_lote">Lote:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="dep_lote" id="dep_lote" placeholder="Lote" maxlength="4" pattern="[0-9]{0,4}">
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="dep_clavconf">Clave de Aprobacion:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="dep_clavconf" id="dep_clavconf" placeholder="Clave de Aprobacion" maxlength="6" pattern="[0-9]{0,6}">
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="dep_fecha">Fecha del Deposito:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="dep_fecha" id="dep_fecha" placeholder="DD/MM/AAAA" required readonly>
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="dep_monto">Monto:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<input type="text" class="form-control" name="dep_monto" id="dep_monto" placeholder="9999999999.99" required pattern="[0-9]{0,10}.[0-9]{0,2}" maxlength="13">
				</div>
			</div>
			<div class="form-group separador-md">
				<div class="col-md-3 col-sm-3">
					<label for="dep_nofacturar">Generar factura por lotes:</label>
				</div>
				<div class="col-md-9 col-sm-9">
					<select id='dep_nofacturar' name='dep_nofacturar' class='form-control' required>
						<option value="">Seleccione...</option>
						<option value="0">Si</option>
						<option value="1">No</option>
					</select>
				</div>
		     </div>
<!--
			<div class="form-group separador-md">
				<div class="form-group text-center separador-md">
					<div class="bg-default">
						<input type="submit" id="btnguardardep" name="btnguardardep" value="Guardar" class="bt btn-primary">
					</div>
				</div>
			</div>
-->
			<div class="form-group separador-md">
				<div class="form-group bg-default text-center separador-md">
					<button type="button" id="guardar" name="guardar" class="btn btn-primary" style="display:none;">Guardar</button>
				</div>
			</div>
		</div>
	</form>
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
	<script src="../bootstrap/js/bootstrap-datepicker.js"></script>
	<script src="../javascript/validaciones.js" language="javascript" type="text/javascript"> </script>
	<script src="../javascript/agregar_deposito.js" language="javascript" type="text/javascript"> </script>
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