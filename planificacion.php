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

$operacion=15;

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
	<link rel="stylesheet" href="../bootstrap/css/alertify.min.css">
	<link type="text/css" rel="Stylesheet" href="../bootstrap/css/jquery.qtip.min.css" />
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css"/>
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<input type="hidden" name="con_filas" id="con_filas" value="0" class="form-control">
	<input type="hidden" name="con_fil_bor" id="con_fil_bor" value="0" class="form-control">
	<div class="form-group">
		<div id="titulo" name="titulo" class="bg-primary text-center titulo text-uppercase">PLANIFICACIÓN - <?php echo $_SESSION['nom_usu'] . $_SESSION['ape_usu']; ?></div>
	</div>	
	<div class="container-fluit">
		<form id="porcentajes" role="form">
			<input type="hidden" name="accion" value="insertar">
			<table class="table table-striped table-bordered table-hover table-condensed" width="90%" id="tablamaestra">
			<tr>
			<td>
				<div class="form-group">
					<label for="lapso" class="control-label col-md-3 col-sm-3 col-xs-3">Lapso:</label>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<select id='lapso' name='lapso' class='form-control' onChange="validacion('lapso');">
							<option value="">Seleccione...</option>
							<option value="1"> 1er. Lapso </option>
							<option value="2"> 2do. Lapso </option>
							<option value="3"> 3er. Lapso </option>
						</select>
					</div>
				</div>				
			</td>
			<td>
				<div class="form-group">
					<label for="codseccodmat" class="control-label col-md-3 col-sm-3 col-xs-3">Sección:</label>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<?php $objUtilidades->hacer_lista_desplegable($conexion,$tabla="eva_materseccion",$value="codseccodmat",$mostrar="secmatmostrar",$nombre="codseccodmat",$sql="select concat(masec_codsec,'&',masec_codmat) as codseccodmat,concat(masec_codsec,' - ',mat_descripcion) as secmatmostrar from eva_materseccion inner join eva_materias on masec_codmat=mat_cod where masec_cedprof='".$_SESSION['cedula']."' group by masec_codsec","validacion('codseccodmat');"); ?>						
					</div>
				</div>
			</td>
			</tr>
			<tr>
			<td>
				<div class="form-group">
					<label for="pla_porc" class="control-label col-md-3 col-sm-3 col-xs-3">Porcentaje:</label>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<input type="text" class="form-control" name="pla_porc" id="pla_porc" placeholder="%" maxlength="3" onkeyup="validacion('pla_porc');">
						<span class="help-block"></span>
					</div>
				</div>				
			</td>
			<td>
				<div class="form-group">
					<label for="pla_desc" class="control-label col-md-3 col-sm-3 col-xs-3">Instrumento:</label>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<input type="text" class="form-control" name="pla_desc" id="pla_desc" placeholder="Instrumento de Evaluación" maxlength="20" onkeyup="validacion('pla_desc');">
						<span class="help-block"></span>
					</div>
				</div>
			</td>
			</tr>
			<tr>
			<td>
				<div class="form-group">
					<label for="pla_fecha" class="control-label col-md-3 col-sm-3 col-xs-3">Fecha:</label>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<input type="text" class="form-control" name="pla_fecha" id="pla_fecha" placeholder="DD/MM/AAAA" readonly onchange="validacion('pla_fecha');">
						<span class="help-block"></span>
					</div>
				</div>
			</td>
			<td>
				<div class="form-group">
					<label for="pla_objeti" class="control-label col-md-3 col-sm-3 col-xs-3">Objetivo:</label>
					<div class="col-md-9 col-sm-9 col-xs-9">
						<input type="text" class="form-control" name="pla_objeti" id="pla_objeti" placeholder="Objetivo" onkeyup="validacion('pla_objeti');">
						<span class="help-block"></span>
					</div>
				</div>
			</td>
			</tr>
			<tr>
			<td colspan="2" align="center">
				<div class="form-group">
					<button type="button" class="btn btn-primary" name="agregar" id="agregar">Agregar</button>
					<button type="button" class="btn btn-primary" name="guardar" id="guardar" disabled>Guardar</button>
				</div>				
			</td>
			</tr>
			</table>
		</form>
	</div>
	<!--<table id="secciones"></table> Esto es tara usar con el jqgrid que necesita una tabla-->
	<!--<div id="zona_cargando" align="center" height:"50px" width:"50px" background-size:"contain"></div> -->
	<!--<div id="zona_cargando" align="center"><p><img src="../imagenes/cargando.gif" /></p></div> -->

	<table class="table table-striped table-bordered table-hover table-condensed" width="90%" id="tabla_detalle" name="tabla_detalle">
	</table>
<!--
	<table class="table table-striped table-bordered table-hover table-condensed" width="90%">
		<tr>
			<td width="80%" align="right">Total %:</td>
			<td width="20%" ><input type="text" style="text-align:right;" name="total" id="total" value="0" readonly class="form-control input-sm"></td>
		</tr>
	</table>
-->
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
	<script src="../javascript/planificacion.js" language="javascript" type="text/javascript"> </script>
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
<?php
      							$sql="SELECT * FROM ss_tipo_parto";
      							$rec=mysql_query($sql);
      							while($filas=mysql_fetch_array($rec))
      							{

      								echo "<option>";
      								echo $filas['parto_id'];
      								echo "</option>";
      							}
      						?>