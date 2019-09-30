<?php
require_once("../clases/conexion.class.php");
require_once("../clases/seccion.class.php");
require_once("../clases/utilidades.class.php");

$objConexiones=new conexion;
$objSeccion=new seccion;
$objUtilidades=new utilidades;

$conexion=$objConexiones->conectar();

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'consultar':
		$objSeccion->consultar($conexion,$_GET["codsec"]);
		break;
	case 'update':
		$objSeccion->update_matersecc($conexion,$_POST["codsec"],$_POST["codmat"],$_POST["capaci"],$_POST["activa"],$_POST["virtua"],$_POST["pendie"],$_POST["actcom"],$_POST["nuevos"]);
		break;
	default:
		# code...
		break;
}
?>