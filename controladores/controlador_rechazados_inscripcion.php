<?php
require_once("../clases/conexion.class.php");
require_once("../clases/rechazados_inscripcion.class.php");
require_once("../clases/utilidades.class.php");

//$objConexiones=new conexion;
$conexion=Db::getInstance();/*instancia la clase*/
$objRechaInscp=new rechazadosIncripcion;
$objUtilidades=new utilidades;

//$conexion=$objConexiones->conectar();

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'buscar_json':
		$objRechaInscp->buscar_json($conexion,$_GET["cedula"]);
		break;
	case 'buscar':
		$respuesta=$objRechaInscp->buscar($conexion,$_POST['cedula']);
		//$objRechaInscp->buscar_json($conexion,$_GET["cedula"]);
		break;
	case 'eliminar':
		$objRechaInscp->eliminar($conexion,$_POST["cedula"]);
		break;
	case 'guardar':
		$objRechaInscp->guardar($conexion,$_POST["cedula"],$_POST["descrip"]);
		break;

	default:
		# code...
		break;
}
?>