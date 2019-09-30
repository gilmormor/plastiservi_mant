<?php
require_once("../clases/conexion.class.php");
require_once("../clases/representante.class.php");

/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$objRepresentante=new representante;

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'buscar':
	$objRepresentante->buscar($conexion,$_POST['rep_ced']);
		break;
	case 'consultarXrepest':
	$objRepresentante->consultarXrepest($conexion,$_POST['rep_ced']);
		break;
	default:
		# code...
		break;
}
?>