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
<!--	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap.min.css">
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-select.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/alertify.min.css">
	<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-datepicker.min.css"/>
	<link rel="stylesheet" href="../css/estilos_nivel2.css">

<!--
<link rel="stylesheet" href="../bootstrap4.3.1/css/alertify.css">
<link rel="stylesheet" href="../bootstrap4.3.1/css/themes/bootstrap.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
-->
	<!--<link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap_iugc/css/jquery-ui.css">
    <link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-datepicker.min.css"/>
    <link rel="stylesheet" href="../bootstrap_iugc/css/alertify.min.css">
    <link rel="stylesheet" href="../bootstrap_iugc/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap_iugc/css/bootstrap-select.css">-->

<style type="text/css">
    /* Estilo que muestra la capa flotante */
    #flotante
    {
        position: absolute;
        z-index: 100;
        display:none;
        font-family:Arial;
        font-size:0.8em;
        width:280px;
        border:1px solid #808080;
        background-color:#f1f1f1;
        padding:5px;
    }
 
    #indseg {font-weight:bold;}












/* START TOOLTIP STYLES */
[tooltip] {
  position: relative; /* opinion 1 */
}

/* Applies to all tooltips */
[tooltip]::before,
[tooltip]::after {
  text-transform: none; /* opinion 2 */
  font-size: .9em; /* opinion 3 */
  line-height: 1;
  user-select: none;
  pointer-events: none;
  position: absolute;
  display: none;
  opacity: 0;
}
[tooltip]::before {
  content: '';
  border: 5px solid transparent; /* opinion 4 */
  z-index: 1001; /* absurdity 1 */
}
[tooltip]::after {
  content: attr(tooltip); /* magic! */
  
  /* most of the rest of this is opinion */
  font-family: Helvetica, sans-serif;
  text-align: left;
  
  /* 
    Let the content set the size of the tooltips 
    but this will also keep them from being obnoxious
    */
  min-width: 3em;
  max-width: 200em;
  /*white-space: nowrap;*/
  overflow: hidden;
  text-overflow: ellipsis;
  padding: 1ch 1.5ch;
  border-radius: .3ch;
  box-shadow: 0 1em 2em -.5em rgba(0, 0, 0, 0.35);
  background: #333;
  color: #fff;
  z-index: 1000; /* absurdity 2 */
}

/* Make the tooltips respond to hover */
[tooltip]:hover::before,
[tooltip]:hover::after {
  display: block;
}

/* don't show empty tooltips */
[tooltip='']::before,
[tooltip='']::after {
  display: none !important;
}

/* FLOW: UP */
[tooltip]:not([flow])::before,
[tooltip][flow^="up"]::before {
  bottom: 100%;
  border-bottom-width: 0;
  border-top-color: #333;
}
[tooltip]:not([flow])::after,
[tooltip][flow^="up"]::after {
  bottom: calc(100% + 5px);
}
[tooltip]:not([flow])::before,
[tooltip]:not([flow])::after,
[tooltip][flow^="up"]::before,
[tooltip][flow^="up"]::after {
  left: 50%;
  transform: translate(-50%, -.5em);
}

/* FLOW: DOWN */
[tooltip][flow^="down"]::before {
  top: 100%;
  border-top-width: 0;
  border-bottom-color: #333;
}
[tooltip][flow^="down"]::after {
  top: calc(100% + 5px);
}
[tooltip][flow^="down"]::before,
[tooltip][flow^="down"]::after {
  left: 50%;
  transform: translate(-50%, .5em);
}

/* FLOW: LEFT */
[tooltip][flow^="left"]::before {
  top: 50%;
  border-right-width: 0;
  border-left-color: #333;
  left: calc(0em - 5px);
  transform: translate(-.5em, -50%);
}
[tooltip][flow^="left"]::after {
  top: 50%;
  right: calc(100% + 5px);
  transform: translate(-.5em, -50%);
}

/* FLOW: RIGHT */
[tooltip][flow^="right"]::before {
  top: 50%;
  border-left-width: 0;
  border-right-color: #333;
  right: calc(0em - 5px);
  transform: translate(.5em, -50%);
}
[tooltip][flow^="right"]::after {
  top: 50%;
  left: calc(100% + 5px);
  transform: translate(.5em, -50%);
}

/* KEYFRAMES */
@keyframes tooltips-vert {
  to {
    opacity: .9;
    transform: translate(-50%, 0);
  }
}

@keyframes tooltips-horz {
  to {
    opacity: .9;
    transform: translate(0, -50%);
  }
}

/* FX All The Things */ 
[tooltip]:not([flow]):hover::before,
[tooltip]:not([flow]):hover::after,
[tooltip][flow^="up"]:hover::before,
[tooltip][flow^="up"]:hover::after,
[tooltip][flow^="down"]:hover::before,
[tooltip][flow^="down"]:hover::after {
  animation: tooltips-vert 300ms ease-out forwards;
}

[tooltip][flow^="left"]:hover::before,
[tooltip][flow^="left"]:hover::after,
[tooltip][flow^="right"]:hover::before,
[tooltip][flow^="right"]:hover::after {
  animation: tooltips-horz 300ms ease-out forwards;
}










</style>

</head>
<body>
<!--	<form action="../controladores/controlador_deposito.php" method="POST"> -->
<!--	<form method="POST">-->
		<input type="hidden" name="accion" value="modificar">
		<div class="container">
			<div class="form-group separador-md" style="margin-bottom: 1px;margin-top: 0px;margin-left: -15px;margin-right: -15px;">
				<div class="bg-primary text-center titulo text-uppercase">Orden de Trabajo</div>
			</div>
			<!--
			<div class="col-xs-12 text-center" id="selecConsult" name="selecConsult"  style='display:none;'>
				<button id="trabTodos" class="btn btn-primary btn-sm" title='Ver todos los registros' data-toggle='tooltip'>Todos</button>
				<button id="trabEjecu" class="btn btn-primary btn-sm" title='Filtar Trabajos en ejecución' data-toggle='tooltip'>Ejecución</button>		 		
				<button id="trabAsign" class="btn btn-primary btn-sm" title='Filtrar Trabajos por asignar' data-toggle='tooltip'>Asignar</button>		 		
			</div>
			<div class="row" id="tablaMaquinas">
			</div>-->
		</div>
		<div class="container" id="container1" name="container1" style="display: none">
			<div class="row">
				<div class="col-xs-12 col-sm-12 text-center">
					<div class="col-xs-1 col-sm-1">
						<label for="departamentoAreaID" type="text" data-toggle="tooltip" data-placement="top" title="Seleccione una Opción">Area:</label>
					</div>
					<div class="col-xs-5 col-sm-5">
						<?php $objUtilidades->hacer_lista_desplegableB41($conexion,$tabla="vistadepartamentos",$value="departamentoAreaID",$mostrar="nombreDpto",$nombre="departamentoAreaID",$sql="select * from vistadepartamentos where statusMant;",$funcion="consultarObservaciones(1)"); ?>
					</div>
					<div class="col-xs-6 col-sm-6">
						<button id="trabTodos" class="btn btn-primary btn-sm" title='Ver todos los registros' data-toggle='tooltip'>Todos</button>
						<button id="trabEjecu" class="btn btn-primary btn-sm" title='Filtar Trabajos en ejecución' data-toggle='tooltip'>Ejecución</button>		 		
						<button id="trabAsign" class="btn btn-primary btn-sm" title='Filtrar Trabajos por asignar' data-toggle='tooltip'>Asignar</button>		 		
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12">
					<div class="row" id="tablaMaquinas">
					</div>			
				</div>
			</div>
		</div>

<!--	</form>-->


	<!-- Modal -->
	<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h3 class="modal-title" id="exampleModalLongTitle" name="exampleModalLongTitle">Orden de Trabajo</h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
				<form>
					<input type="hidden" name="solicitudTrabID" id="solicitudTrabID">
					<input type="hidden" name="prioridad" id="prioridad">
					<div id="flotante"></div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="form-group col-xs-12 col-sm-4">
								<label for="departamentoAreaIDM" class="col-form-label">Departamento:</label>
								<input type="text" class="form-control" id="departamentoAreaIDM" disabled>
								<span class="help-block"></span>
							</div>
							<div class="form-group col-xs-12 col-sm-4">
								<label for="ema_usuM" class="col-form-label">Solicitante:</label>
								<input type="text" class="form-control" id="ema_usuM" disabled>
								<span class="help-block"></span>
							</div>
							<div class="form-group col-xs-12 col-sm-4">
								<label for="fechaHoraini" class="col-form-label">Fecha Ini:</label>
								<input type="text" class="form-control" id="fechaHoraini" disabled>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="form-group col-xs-12 col-sm-3">
								<label for="responsable" class="col-form-label">Responsable(s):</label>
								<?php $objUtilidades->hacer_lista_desplegableMultiple($conexion,$tabla="vistapersonamant",$value="personaID",$mostrar="nombre",$nombre="responsable",$sql="select personaID,nombre,apellido from vistapersonamant;",$funcion="",$subtext="nombreArea"); ?>

								
								<span class="help-block"></span>
							</div>
							<div class="form-group col-xs-12 col-sm-3">
								<label for="mant" class="col-form-label">Mantenimniento:</label>
								<select  class="selectpicker form-control" id="mant" data-toggle='tooltip' title='Seleccione...'>
									<option value="I">Interno</option>
									<option value="E">Externo</option>
								</select>
								<span class="help-block"></span>
							</div>
							<div class="form-group col-xs-12 col-sm-3">
								<label for="tipofalla" class="col-form-label">Tipo de falla:</label>
								<select  class="selectpicker form-control" multiple id="tipofalla" data-toggle='tooltip' title='Seleccione...'>
									<option value="E" textayu="Prueba1">Eléctrica</option>
									<option value="M" textayu="Prueba2">Mecánica</option>
									<option value="H" textayu="Prueba3">Hidráulica</option>
									<option value="N" textayu="Prueba4">Neumática</option>
								</select>
								<span class="help-block"></span>
							</div>
							<div class="form-group col-xs-12 col-sm-3">
								<label for="tipomant" class="col-form-label">Tipo de Mantenimiento:</label>
								<select  class="selectpicker form-control" id="tipomant" data-toggle='tooltip' title='Seleccione...'>
									<option value="C">Corrrectivo</option>
									<option value="P">Preventivo</option>
								</select>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="form-group col-xs-12 col-sm-6">
								<label for="descripTrabajo" class="col-form-label">Descripción del trabajo:</label>
								<textarea class="form-control" id="descripTrabajo"></textarea>
								<span class="help-block"></span>
							</div>

							<div class="form-group col-xs-12 col-sm-6" id="lbltipotrab" name="lbltipotrab" tooltip="Get Down." flow="up">
								<label for="ttmID" class="col-form-label">Indicaciones de Seguridad: (NCH 1466 OF.1978):</label>
								<?php $objUtilidades->hacer_lista_desplegableMultiple($conexion,$tabla="tipotrabmant",$value="ttmID",$mostrar="descrip",$nombre="ttmID",$sql="select ttmID,descrip,indseg from tipotrabmant where usuarioIDdelete=0;",$funcion="",$subtext="nombreArea"); ?>
								<span class="help-block"></span>
							</div>

							<div class="form-group col-xs-12 col-sm-3" style='display:none;'>
								<?php $objUtilidades->hacer_lista_desplegableMultiple($conexion,$tabla="tipotrabmant",$value="ttmID",$mostrar="indseg",$nombre="indseg",$sql="select ttmID,descrip,indseg from tipotrabmant where usuarioIDdelete=0;",$funcion="",$subtext="nombreArea"); ?>
							</div>

<!--
							<div class="form-group col-xs-12 col-sm-6">
								<label for="indSeguridad" class="col-form-label">Indicaciones de Seguridad: (NCH 1466 OF.1978)</label>
								<textarea class="form-control" id="indSeguridad"></textarea>
								<span class="help-block"></span>
							</div>
-->
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<div class="form-group col-xs-12 col-sm-6">
								<label for="repuestosmat" class="col-form-label">Repuestos y Materiales:</label>
								<textarea class="form-control" id="repuestosmat"></textarea>
								<span class="help-block"></span>
							</div>
							<div class="form-group col-xs-12 col-sm-6">
								<label for="observaciones" class="col-form-label">Observaciones:</label>
								<textarea class="form-control" id="observaciones"></textarea>
								<span class="help-block"></span>
							</div>
						</div>
					</div>
				</form>

	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Cerrar" data-toggle='tooltip'>Cerrar</button>
	        <button type="button" class="btn btn-primary" id="btnGuardarM" name="btnGuardarM" title="Guardar">Guardar</button>
	      </div>
	    </div>
	  </div>
	</div>


	<div id="dialog_carga" name="dialog_carga" title="Cargando..."align="center">
		<div class="loader"></div>
	</div>


<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
-->

<!--
<script src="../bootstrap4.3.1/js/jquery-1.11.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="../bootstrap4.3.1/js/alertify.js"></script>
-->


<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
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

	<script src="../javascript/validaciones.js" language="javascript" type="text/javascript"> </script>
	<script src="../javascript/ordenTrabajoMant.js" language="javascript" type="text/javascript"> </script>
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