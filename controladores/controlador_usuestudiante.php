<?php
require_once("../clases/conexion.class.php");
require_once("../clases/usuestudiante.class.php");
require_once("../clases/utilidades.class.php");

$objConexiones=new conexion;
$objEstudiante=new estudiante;
$objUtilidades=new utilidades;

$conexion=$objConexiones->conectar();

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'buscar_usuestudiante':
	$objEstudiante->buscar_usuestudiante($conexion,$_GET["usu_cod"]);
		break;

	default:
		# code...
		break;
}
?>