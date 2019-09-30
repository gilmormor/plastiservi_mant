<?php
require_once("../clases/conexion.class.php");
require_once("../clases/repest.class.php");

/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$objRepest=new repest;

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'buscar':
	$objRepest->buscar($conexion,$_POST['rep_cedalum']);
		break;
	default:
		# code...
		break;
}
?>