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
$nom_ope = $objOpcion->devolver_desc_opc($conexion,$num_opc); //Nombre en el Menú

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
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/estilos_nivel2.css">

</head>
<body>
<!--	<form action="../controladores/controlador_deposito.php" method="POST"> -->
<!--	<form method="POST">-->
		<input type="hidden" name="accion" value="modificar">
		<input type="hidden" id="numReg" name="numReg">
		<div class="container">
			<div class="form-group separador-md">
				<div class="bg-primary text-center titulo text-uppercase"><?php echo $nom_ope; ?></div><button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ocultarMostrarFiltro()">
	          <span aria-hidden="true" class='glyphicon glyphicon-triangle-bottom' id="botonD" name="botonD" style="top: -4px;" title="Ocultar Filtros" data-toggle='tooltip'></span>
	        </button>
			</div>
		</div>
		<div class="container" id="divFiltros" name="divFiltros">
			<div class="row">
				<div class="col-xs-12 col-md-5 col-sm-5" style="top: -13px;">
					<div class="col-xs-12 col-md-3 col-sm-3 text-right" style="padding-left: 0px;padding-right: 0px;">
						<label for="fecha">Usuario:</label>
					</div>
					<div class="col-xs-12 col-md-9 col-sm-9">
						<?php $usuarioID = $_SESSION["usuarioID"];
						$objUtilidades->hacer_lista_desplegableBusB41($conexion,$tabla="vistausuariopersona",$value="ema_usu",$mostrar="nomape",$nombre="ema_usu",$sql="select * from vistausuariopersona where fk_tip_usu!=4;",$funcion="mostrarOpciones()");?>
					</div>
				</div>
				<div class="col-xs-12 col-md-5 col-sm-5" style="top: -13px;">
					<div class="col-xs-12 col-md-2 col-sm-2">
						<button type="button" class="btn btn-primary" id="btnGuardarM" name="btnGuardarM" title="Guardar" style='display:none;'>Guardar</button>
					</div>

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
					<div class="row" id="tabla">
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
	<script src="../javascript/agregar_permisos.js" language="javascript" type="text/javascript"> </script>
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