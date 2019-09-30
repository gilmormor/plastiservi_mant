<?php
require_once("../clases/conexion.class.php");
require_once("../clases/inscrip_estudiante.class.php");

$objConexiones=new conexion;
$objInscestudi=new inscrip_estudiante;

$conexion=$objConexiones->conectar();

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'buscar_json':
		$objInscestudi->consultar_inscripcion($conexion,$_POST["cedula"]);
		break;
	default:
		# code...
		break;
}
?>