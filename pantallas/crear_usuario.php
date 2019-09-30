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
$retorno=1;

if ($retorno==1)
{


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Crear Usuario</title>
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


	<div class="col-md-offset-2 col-lg-8">

		<div class="container-fluit">
		    <div>
		    	<img src="../imagenes/softservi.jpg" />
				<div class="head text-subheader" id="SignupPageTitle" role="heading"><h1>Crear Usuario</h1></div>
			    <div class="container">
			        <div class="row">
			            <div class="col-xs-9 form-group">
				            Si ya inicias sesión en un PC, una tableta o un teléfono inteligente, usa esa dirección de correo electrónico para <a href="../index.php">iniciar sesión</a>. De lo contrario, crea un Usuario. En caso de tener 2 o más representados debes crear un solo usuario por todos.
			            </div>
			        </div>
			    </div>
			</div>

			<form id="crearusuarios" role="form">
				<input type="hidden" name="accion" value="insertar">
				<div class="form-group">
					<label for="ced_usu" class="control-label col-md-12 col-sm-12 col-xs-12 paddin0">Cédula Representante:</label>
					<div class="col-md-12 col-sm-12 col-xs-12 paddin0">
						<input type="text" class="form-control" name="ced_usu" id="ced_usu" placeholder="Ejemplo: 12345678 (Sin la V, Sin Puntos)" maxlength="11" onkeyup="validacion('ced_usu');" autofocus="autofocus" onblur="validacion('ced_usu');">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="est_ced" class="control-label col-md-2 col-sm-2 col-xs-2 paddin0">Cédula:</label>
					<label for="nombre" class="control-label col-md-5 col-sm-5 col-xs-5 paddin0">Nombre:</label>
					<label for="apellido" class="control-label col-md-5 col-sm-5 col-xs-5 paddin0">Apellido:</label>
					<div class="col-md-2 col-sm-2 col-xs-2 paddin0">
						<input type="text" class="form-control" name="est_ced" id="est_ced" placeholder="Num. Cédula" disabled>
						<span class="help-block"></span>
					</div>
					<div class="col-md-5 col-sm-5 col-xs-5 paddin0">
						<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre Estudiante" disabled>
						<span class="help-block"></span>
					</div>
					<div class="col-md-5 col-sm-5 col-xs-5 paddin0">
						<input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido Estudiante" disabled>
						<span class="help-block"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="ema_usu" class="control-label col-md-12 col-sm-12 col-xs-12 paddin0">Usuario: (Correo Electrónico)</label>
					<div class="col-md-12 col-sm-12 col-xs-12 paddin0">
						<input type="text" class="form-control" name="ema_usu" id="ema_usu" placeholder="nombre@dominio.com" maxlength="100" onkeyup="validacion('ema_usu');" onblur="validacion('ema_usu');">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="cla_usu" class="control-label col-md-12 col-sm-12 col-xs-12 paddin0">Contraseña:</label>
					<div class="col-md-12 col-sm-12 col-xs-12 paddin0">
						<input type="password" class="form-control" name="cla_usu" id="cla_usu" placeholder="Contraseña" maxlength="20" onkeyup="validacion('cla_usu');" onblur="validacion('cla_usu');">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="cla_usu1" class="control-label col-md-12 col-sm-12 col-xs-12 paddin0">Repita la contraseña:</label>
					<div class="col-md-12 col-sm-12 col-xs-12 paddin0">
						<input type="password" class="form-control" name="cla_usu1" id="cla_usu1" placeholder="Repetir contraseña" maxlength="20" onkeyup="validacion('cla_usu1');" onblur="validacion('cla_usu1');">
						<span class="help-block"></span>
					</div>
				</div>
				<br>
				<div class="form-group">
					<div class="col-md-offset-5 col-md-12 col-sm-12 col-xs-12 paddin0">
						<button type="button" class="btn btn-primary" name="crear" id="crear">Crear Cuenta</button>
					</div>
				</div>				
			</form>
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
	<script src="../javascript/crear_usuario.js" language="javascript" type="text/javascript"> </script>
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
